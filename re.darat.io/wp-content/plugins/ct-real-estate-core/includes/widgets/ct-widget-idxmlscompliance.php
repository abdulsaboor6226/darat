<?php
/**
 * IDX MLS Compliance
 *
 * @package WP Pro Real Estate 7
 * @subpackage Widget
 */
 
if(!class_exists('ct_IDXMLSCompliance')) {
	class ct_IDXMLSCompliance extends WP_Widget {

	   function __construct() {
		   $widget_ops = array('description' => 'Use this widget to display MLS compliance information in the Listing Single Right sidebar. This is optional and only required if your MLS requests it. For use with the CT IDX Pro plugin.' );
		   parent::__construct(false, __('CT IDX MLS Compliance', 'contempo'),$widget_ops);
	   }

	   	function widget($args, $instance) {  
		extract( $args );
		
		$show_brokerage_number = isset( $instance['show_brokerage_number'] ) ? $instance['show_brokerage_number'] : '';
		
		?>
			<?php
			
	        	echo ct_sanitize_output( $before_widget );  

				global $post;

				$ct_source = get_post_meta($post->ID, 'source', true);
				$ct_idx_mls_idx_logo_small = get_post_meta($post->ID, '_ct_idx_mls_idx_logo_small', true);
				$ct_idx_mls_name = get_post_meta($post->ID, '_ct_idx_mls_name', true);
				$ct_idx_agent_name = get_post_meta($post->ID, '_ct_agent_name', true);
				$ct_idx_agent_id = get_post_meta($post->ID, '_ct_branding_agent_id', true);
				$ct_idx_selling_agent = get_post_meta($post->ID, '_ct_idx_selling_agent', true);
				$ct_idx_co_selling_agent = get_post_meta($post->ID, '_ct_idx_co_selling_agent', true);

				$ct_cpt_brokerage = get_post_meta($post->ID, '_ct_brokerage', true);
				$ct_cpt_brokerage_phone = get_post_meta($post->ID, '_ct_branding_agent_office_phone', true);

				if($ct_cpt_brokerage != 0) {

					$brokerage = new WP_Query(array(
			            'post_type' => 'brokerage',
			            'p' => $ct_cpt_brokerage,
			            'nopaging' => true
			        ));

					if ( $brokerage->have_posts() ) : while ( $brokerage->have_posts() ) : $brokerage->the_post();
			            
		            	$ct_brokerage_name = strtolower(get_the_title());

			        endwhile; endif; wp_reset_postdata();

			    }

				echo '<div class="widget-inner">';   
		        
					if(!empty($ct_source == 'idx-api')) {
						if(!empty($ct_idx_mls_idx_logo_small)) {
							echo '<img id="mls-compliance-logo" class="marB10" src="' . esc_url($ct_idx_mls_idx_logo_small) . '" />';
						}
						if(!empty($ct_idx_agent_name)) {
							echo '<p class="marB5">';
								echo '<small class="muted">Listing Agent:</small><br />';
								echo ucwords($ct_idx_agent_name);
								if(!empty($ct_idx_agent_id)) {
                                    echo '<span class="ct-mls-compliance-agent-id"><br />';
                                    echo '#' . esc_html($ct_idx_agent_id) . '</span>';
                                }
							echo '</p>';
						}
						if(has_term(array('sold'), 'ct_status', get_the_ID()) && !empty($ct_idx_selling_agent)) {
							echo '<p class="marB5">';
								echo '<small class="muted">Listing Sold by:</small><br />';
								echo ucwords($ct_idx_selling_agent) . '<br />';
								if(!empty($ct_brokerage_name)) {
									echo ucwords($ct_idx_co_selling_agent);
								}
							echo '</p>';
						}
						if(!empty($ct_brokerage_name)) {
							echo '<p class="marB0">';
								if(has_term(array('sold'), 'ct_status', get_the_ID())) {
									echo '<small class="muted">Selling Office:</small><br />';
								} else {
									echo '<small class="muted">Listing Office:</small><br />';
								}
								echo ucwords($ct_brokerage_name);
								if(!empty($ct_cpt_brokerage_phone) && $show_brokerage_number == 'yes') {
									echo '<br />';
									echo esc_html($ct_cpt_brokerage_phone);
								}
							echo '</p>';
						}
					}

			    echo '</div>';
			
			echo ct_sanitize_output( $after_widget ); ?>   
	    <?php
	   }

	   function update($new_instance, $old_instance) {                
		   return $new_instance;
	   }

	   function form($instance) { 

	   		$ct_yes_no = array(
	   			'no' => 'No',
	   			'yes' => 'Yes',
	   		);

	   		$show_brokerage_number = isset( $instance['show_brokerage_number'] ) ? esc_attr( $instance['show_brokerage_number'] ) : '';

			?>
			<p>
	            <label for="<?php echo esc_attr($this->get_field_id('show_brokerage_number')); ?>"><?php esc_html_e('Show Brokerage Phone?','contempo'); ?></label>
	            <select name="<?php echo esc_attr($this->get_field_name('show_brokerage_number')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('show_brokerage_number')); ?>">
	                <?php foreach ($ct_yes_no as $option => $value) { ?>
	                <option value="<?php echo esc_attr($option); ?>" <?php if($show_brokerage_number == $option){ echo "selected='selected'";} ?>><?php echo ucfirst($option); ?></option>
	                <?php } ?>
	            </select>
	        </p>
		<?php }
	} 

	function wpb_load_ct_IDXMLSCompliance() {
	    register_widget( 'ct_IDXMLSCompliance' );
	}
	add_action( 'widgets_init', 'wpb_load_ct_IDXMLSCompliance' );
}

?>