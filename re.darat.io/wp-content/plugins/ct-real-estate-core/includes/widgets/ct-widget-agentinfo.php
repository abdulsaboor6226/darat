<?php
/**
 * Agent Info
 *
 * @package WP Pro Real Estate 7
 * @subpackage Widget
 */
 
if(!class_exists('ct_AgentInfo')) {
	class ct_AgentInfo extends WP_Widget {

	   function __construct() {
		   $widget_ops = array('description' => 'Use this widget to display your listing agent information, can only be used in the Listing Single sidebar as it relies on listing information for content.' );
		   parent::__construct(false, __('CT Agent Info', 'contempo'),$widget_ops);    
	   }

	   function widget($args, $instance) {  
		extract( $args );
		
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		
		?>
			<?php
			
	        echo ct_sanitize_output( $before_widget );
			
			if ($title) {
				echo ct_sanitize_output( $before_title . $title . $after_title );
			}     

			global $post;

			$ct_source = get_post_meta($post->ID, 'source', true);

			$ct_idx_pro_assign_agents = get_option( 'ct_idx_pro_assign_agents' );
			$ct_idx_pro_assign_agents = isset( $ct_idx_pro_assign_agents ) ? $ct_idx_pro_assign_agents : '';
			$ct_idx_pro_assign_agents = json_decode($ct_idx_pro_assign_agents, true);

			if(!empty($ct_idx_pro_assign_agents) && $ct_source == 'idx-api') {
			    
			    foreach($ct_idx_pro_assign_agents as $agent) {
			        $ct_agent_first_name = get_user_meta($agent, 'first_name', true);
			        $ct_agent_last_name = get_user_meta($agent, 'last_name', true);
			        $ct_agent_display_name = $ct_agent_first_name . ' ' . $ct_agent_last_name;
			        $ct_agent_name_IDX = get_post_meta( $post->ID, '_ct_agent_name', true );

			        if($ct_agent_name_IDX == $ct_agent_display_name) {
			            $author_id = $agent;
			            $user_info = get_userdata($agent);
			        } else {
			            $author_id = $post->post_author;
			            $user_info = get_userdata($author_id);
			        }
			    }

			} else {
			    $author_id = $post->post_author;
			    $user_info = get_userdata($author_id);
			}

			$ct_profile_url = get_user_meta($author_id, 'ct_profile_url', true);
			$first_name = get_user_meta($author_id, 'first_name', true);
			$last_name = get_user_meta($author_id, 'last_name', true);
			$tagline = get_user_meta($author_id, 'tagline', true);
			$mobile = get_user_meta($author_id, 'mobile', true);
			$office = get_user_meta($author_id, 'office', true);
			$fax = get_user_meta($author_id, 'fax', true);
			$email = $user_info->user_email;
			$agent_license = get_user_meta($author_id, 'agentlicense', true);
			$ct_user_url = get_user_meta($author_id, 'user_url', true);
			$twitterhandle = get_user_meta($author_id, 'twitterhandle', true);
			$facebookurl = get_user_meta($author_id, 'facebookurl', true);
			$instagramurl = get_user_meta($author_id, 'instagramurl', true);
			$linkedinurl = get_user_meta($author_id, 'linkedinurl', true);
			$gplus = get_user_meta($author_id, 'gplus', true);
			$youtubeurl = get_user_meta($author_id, 'youtubeurl', true);

			echo '<div class="widget-inner">';   
	        
				if(!empty($ct_profile_url)) {
					echo '<figure class="col span_12 first row">';
						echo '<a href="' . get_author_posts_url($author_id) . '">';
							echo '<img class="authorimg" src="';
								echo esc_url($ct_profile_url);
							echo '" />';
						echo '</a>';
					echo '</figure>';
				} else {
					echo '<a href="' . get_author_posts_url($author_id) . '">';
							echo '<img class="author-img" src="' . get_template_directory_uri() . '/images/user-default.png' . '" />';
					echo '</a>';
				}
					
				?>
			    
			    <div class="details col span_12 first row">
			        <h5 class="author marT20 <?php if(empty($tagline)) { echo 'marB10'; } ?>"><a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo esc_html($first_name); ?> <?php echo esc_html($last_name); ?></a></h5>
			        <?php if($tagline) { ?>
			            <p class="muted tagline"><i><?php echo esc_html($tagline); ?></i></p>
			        <?php } ?>
			        <ul class="marB0">
			            <?php if($mobile) { ?>
				            <li class="marT3 marB0 row mobile"><span class="muted left"><?php ct_phone_svg(); ?></span><span class="right"><?php echo esc_html($mobile); ?></span></li>
			            <?php } ?>
			            <?php if($office) { ?>
				            <li class="marT3 marB0 row office"><span class="muted left"><?php ct_office_svg(); ?></span><span class="right"><?php echo esc_html($office); ?></span></li>
			            <?php } ?>
			            <?php if($fax) { ?>
				            <li class="marT3 marB0 row fax"><span class="muted left"><?php ct_printer_svg(); ?></span><span class="right"><?php echo esc_html($fax); ?></span></li>
				        <?php } ?>
			        	<?php if($email) { ?>
				        	<li class="marT3 marB0 row email"><span class="muted left"><?php ct_envelope_svg(); ?></span><span class="right"><a href="mailto:<?php echo antispambot($email,1) ?>"><?php esc_html_e('Email', 'contempo'); ?></a></span></li>
					    <?php } ?>
					</ul>

					<ul class="social marT15 marL0">
	                    <?php if ($twitterhandle) { ?><li class="twitter"><a href="https://twitter.com/#!/<?php echo esc_html($twitterhandle); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li><?php } ?>
	                    <?php if ($facebookurl) { ?><li class="facebook"><a href="<?php echo esc_url($facebookurl); ?>" target="_blank"><i class="fa fa-facebook"></i></a></li><?php } ?>
	                    <?php if ($instagramurl) { ?><li class="instagram"><a href="<?php echo esc_url($instagramurl); ?>" target="_blank"><i class="fa fa-instagram"></i></a></li><?php } ?>
	                    <?php if ($linkedinurl) { ?><li class="facebook"><a href="<?php echo esc_url($linkedinurl); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li><?php } ?>
	                    <?php if ($youtubeurl) { ?><li class="youtube"><a href="<?php echo esc_url($youtubeurl); ?>" target="_blank"><i class="fa fa-youtube"></i></a></li><?php } ?>
	                </ul>
			    </div>
				    <div class="clear"></div>

			   <?php do_action('after_agent_info_widget'); ?>

		    </div>
			<?php echo ct_sanitize_output( $after_widget ); ?>   
	    <?php
	   }

	   function update($new_instance, $old_instance) {                
		   return $new_instance;
	   }

	   function form($instance) { 
			
			$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
			
			?>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('title')); ?>"  value="<?php echo esc_attr($title); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" />
			</p>
		<?php }
	} 

	function wpb_load_ct_AgentInfo() {
	    register_widget( 'ct_AgentInfo' );
	}
	add_action( 'widgets_init', 'wpb_load_ct_AgentInfo' );
}

?>