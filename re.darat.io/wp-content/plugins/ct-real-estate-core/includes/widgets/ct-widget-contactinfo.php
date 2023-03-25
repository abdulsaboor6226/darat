<?php
/**
 * Contact Info
 *
 * @package WP Pro Real Estate 7
 * @subpackage Widget
 */

if(!class_exists('ct_ContactInfo')) {
	class ct_ContactInfo extends WP_Widget {

	   function __construct() {
		   $widget_ops = array('description' => 'Use this widget to display your contact information.' );
		   parent::__construct(false, __('CT Contact Info', 'contempo'),$widget_ops);      
	   }

	   function widget($args, $instance) {  
			extract( $args );

			global $ct_options;
			
			$title = isset( $instance['title'] ) ? esc_html( $instance['title'] ) : '';
			$logo = isset( $instance['logo'] ) ? esc_html( $instance['logo'] ) : '';
			$icon_color = isset( $instance['icon_color'] ) ? esc_html( $instance['icon_color'] ) : '';
			$ct_logo = isset( $ct_options['ct_logo']['url'] ) ? esc_html( $ct_options['ct_logo']['url'] ) : '';
			$ct_logo_highres = isset( $ct_options['ct_logo_highres']['url'] ) ? esc_html( $ct_options['ct_logo_highres']['url'] ) : '';
			$blurb = isset( $instance['blurb'] ) ? esc_html( $instance['blurb'] ) : '';
			$company = isset( $instance['company'] ) ? esc_html( $instance['company'] ) : '';
			$agent_lic = isset( $instance['agent_lic'] ) ? esc_html( $instance['agent_lic'] ) : '';
			$broker_lic = isset( $instance['broker_lic'] ) ? esc_html( $instance['broker_lic'] ) : '';
			$street = isset( $instance['street'] ) ? esc_html( $instance['street'] ) : '';
			$city = isset( $instance['city'] ) ? esc_html( $instance['city'] ) : '';
			$state = isset( $instance['state'] ) ? esc_html( $instance['state'] ) : '';
			$postal = isset( $instance['postal'] ) ? esc_html( $instance['postal'] ) : '';
			$country = isset( $instance['country'] ) ? esc_html( $instance['country'] ) : '';
			$phone = isset( $instance['phone'] ) ? esc_html( $instance['phone'] ) : '';
			$phone2 = isset( $instance['phone2'] ) ? esc_html( $instance['phone2'] ) : '';
			$email = isset( $instance['email'] ) ? esc_html( $instance['email'] ) : '';
			$email2 = isset( $instance['email2'] ) ? esc_html( $instance['email2'] ) : '';
			$facebook = isset( $instance['facebook'] ) ? esc_html( $instance['facebook'] ) : '';
			$twitter = isset( $instance['twitter'] ) ? esc_html( $instance['twitter'] ) : '';
			$linkedin = isset( $instance['linkedin'] ) ? esc_html( $instance['linkedin'] ) : '';
			$instagram = isset( $instance['instagram'] ) ? esc_html( $instance['instagram'] ) : '';
			$youtube = isset( $instance['youtube'] ) ? esc_html( $instance['youtube'] ) : '';
			$pinterest = isset( $instance['pinterest'] ) ? esc_html( $instance['pinterest'] ) : '';
		?>

			<?php echo ct_sanitize_output( $before_widget ); ?>
			<?php if ($title) { echo ct_sanitize_output( $before_title . $title . $after_title ); }
			echo '<div class="widget-inner">';
				// Remove after RE7 v323 release
				echo '<style>';
					echo '.contact-info svg { position: relative; float: left; top: 3px; margin-right: 12px;} .contact-info .company-address svg { top: 5px; margin-bottom: 42px;} .contact-info .company-phone svg { top: 7px; margin-bottom: 20px;}	.contact-info .company-email svg { top: 5px; margin-bottom: 20px;}';
				echo '</style>';
				if($logo == 'Yes') { ?>
					<?php if(!empty($ct_options['ct_logo']['url'])) { ?>
						<a href="<?php echo home_url(); ?>"><img class="widget-logo marB30" src="<?php echo esc_url($ct_logo); ?>" <?php if(!empty($ct_logo_highres)) { ?>srcset="<?php echo esc_url($ct_logo_highres); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" /></a>
					<?php } else { ?>
						<?php if($ct_options['ct_skin'] == 'minimal') { ?>
	                        <img class="widget-logo marB30" src="<?php echo get_stylesheet_directory_uri(); ?>/images/re7-logo-blue.png" srcset="<?php echo get_stylesheet_directory_uri(); ?>/images/re7-logo-blue@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
	                    <?php } else { ?>
	                    	<img class="widget-logo marB30" src="<?php echo get_stylesheet_directory_uri(); ?>/images/re7-logo.png" srcset="<?php echo get_stylesheet_directory_uri(); ?>/images/re7-logo@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
	                    <?php } ?>
					<?php } ?>
				<?php } ?>
				<?php
				$ct_allowed_html = array(
		                'a' => array(
		                    'href' => array(),
		                    'title' => array()
		                ),
		                'img' => array(
		                    'src' => array(),
		                    'alt' => array()
		                ),
		                'em' => array(),
		                'strong' => array(),
		            );
	            ?>
		        <?php if(!empty($blurb)) { ?><p class="marB15"><?php echo wp_kses(stripslashes($blurb), $ct_allowed_html); ?></p><?php } ?>

		        <ul class="contact-info">
		            <?php if(!empty($company) || !empty($street)) { ?>
		            	<li class="company-address">
		            		<?php if($icon_color == "light") { ct_office_svg_white(); } else { ct_office_svg(); } ?>
			            	<?php if(!empty($company)) { 
			            		echo esc_html($company) . '<br />';
		            		} ?>
			            	<?php echo esc_html($street); ?><br />
			            
				            <?php if(!empty($city) || !empty($state) || !empty($postal) || !empty($country)) { ?>
					            <?php echo esc_html($city); ?>, <?php echo esc_html($state); ?> <?php echo esc_html($postal); ?><br /><?php echo esc_html($country); ?>
					        <?php } ?>
				        </li>
				    <?php } ?>
		            <?php if(!empty($phone) || !empty($phone2)) { ?>
		            	<li class="company-phone">
		            		<?php if($icon_color == "light") { ct_phone_svg_white(); } else { ct_phone_svg(); } ?>
		            		<?php if(!empty($phone)) {
			            		echo '<a href="tel:' . esc_html($phone) . '">' . esc_html($phone) . '</a>';
		            		} ?>
		            		<?php if(!empty($phone2)) {
			            		echo '<br /><a href="tel:' . esc_html($phone2) . '">' . esc_html($phone2) . '</a>';
		            		} ?>
		            	</li>
		            <?php } ?>
		           	<?php if(!empty($email) || !empty($email2)) { ?>
		            	<li class="company-email">
		            		<?php if($icon_color == "light") { ct_envelope_svg_white(); } else { ct_envelope_svg(); } ?>
		            		<?php if(!empty($email)) {
		            			echo '<a href="mailto:' . antispambot($email) . '">' . antispambot($email) . '</a>';
	            			} ?>
	            			<?php if(!empty($email2)) {
		            			echo '<br /><a href="mailto:' . antispambot($email2) . '">' . antispambot($email2) . '</a>';
	            			} ?>
	            		</li>
		            <?php } ?>
	            	<?php if(!empty($agent_lic)) { ?><li class="agent-license"><?php _e('Agent #:', 'contempo'); ?> <?php echo esc_html($agent_lic); ?></li><?php } ?>
	            	<?php if(!empty($broker_lic)) { ?><li class="agent-license"><?php _e('Broker #:', 'contempo'); ?> <?php echo esc_html($broker_lic); ?></li><?php } ?>
		        </ul>

		        <?php if(!empty($facebook) || !empty($twitter) || !empty($linkedin) || !empty($pinterest) || !empty($instagram) || !empty($youtube)) { ?>
			        <ul class="contact-social">
						<?php if(!empty($facebook)) { ?>
			                <li class="facebook"><a href="<?php echo esc_url($facebook); ?>" target="_blank"><i class="fab fa-facebook"></i></a></li>
			            <?php } ?>
			            <?php if(!empty($twitter)) { ?>
			                <li class="twitter"><a href="<?php echo esc_url($twitter); ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
			            <?php } ?>
			            <?php if(!empty($linkedin)) { ?>
			                <li class="linkedin"><a href="<?php echo esc_url($linkedin); ?>" target="_blank"><i class="fab fa-linkedin"></i></a></li>
			            <?php } ?>
			            <?php if(!empty($pinterest)) { ?>
			                <li class="pinterest"><a href="<?php echo esc_url($pinterest); ?>" target="_blank"><i class="fab fa-pinterest"></i></a></li>
			            <?php } ?>
			            <?php if(!empty($instagram)) { ?>
			                <li class="instagram"><a href="<?php echo esc_url($instagram); ?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
			            <?php } ?>
			            <?php if(!empty($youtube)) { ?>
			                <li class="youtube"><a href="<?php echo esc_url($youtube); ?>" target="_blank"><i class="fab fa-youtube"></i></a></li>
			            <?php } ?>
			        </ul>
		       <?php } ?>
		    </div>
			<?php echo ct_sanitize_output( $after_widget ); ?>   
	    <?php
	   }

	   function update($new_instance, $old_instance) {                
		   return $new_instance;
	   }

	   function form($instance) {    
	   
			$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';    
			$blurb = isset( $instance['blurb'] ) ? esc_attr( $instance['blurb'] ) : '';
			$company = isset( $instance['company'] ) ? esc_attr( $instance['company'] ) : '';
			$agent_lic = isset( $instance['agent_lic'] ) ? esc_attr( $instance['agent_lic'] ) : '';
			$broker_lic = isset( $instance['broker_lic'] ) ? esc_attr( $instance['broker_lic'] ) : '';
			$street = isset( $instance['street'] ) ? esc_attr( $instance['street'] ) : '';
			$city = isset( $instance['city'] ) ? esc_attr( $instance['city'] ) : '';
			$state = isset( $instance['state'] ) ? esc_attr( $instance['state'] ) : '';
			$postal = isset( $instance['postal'] ) ? esc_attr( $instance['postal'] ) : '';
			$country = isset( $instance['country'] ) ? esc_attr( $instance['country'] ) : '';
			$phone = isset( $instance['phone'] ) ? esc_attr( $instance['phone'] ) : '';
			$phone2 = isset( $instance['phone2'] ) ? esc_attr( $instance['phone2'] ) : '';
			$email = isset( $instance['email'] ) ? esc_attr( $instance['email'] ) : '';
			$email2 = isset( $instance['email2'] ) ? esc_attr( $instance['email2'] ) : '';
			$facebook = isset( $instance['facebook'] ) ? esc_attr( $instance['facebook'] ) : '';
			$twitter = isset( $instance['twitter'] ) ? esc_attr( $instance['twitter'] ) : '';
			$linkedin = isset( $instance['linkedin'] ) ? esc_attr( $instance['linkedin'] ) : '';
			$instagram = isset( $instance['instagram'] ) ? esc_attr( $instance['instagram'] ) : '';
			$pinterest = isset( $instance['pinterest'] ) ? esc_attr( $instance['pinterest'] ) : '';
			$youtube = isset( $instance['youtube'] ) ? esc_attr( $instance['youtube'] ) : '';

			$logo = isset( $instance['logo'] ) ? esc_attr( $instance['logo'] ) : '';
			$icon_color = isset( $instance['icon_color'] ) ? esc_attr( $instance['icon_color'] ) : '';

			?>
			<p>
	            <label for="<?php echo esc_attr($this->get_field_id('logo')); ?>"><?php esc_html_e('Show Logo','contempo'); ?></label>
	            <select name="<?php echo esc_attr($this->get_field_name('logo')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('logo')); ?>">
	                <option value="Yes" <?php if($logo == 'Yes'){ echo "selected='selected'";} ?>><?php esc_html_e('Yes', 'contempo'); ?></option>
	                <option value="No" <?php if($logo == 'No'){ echo "selected='selected'";} ?>><?php esc_html_e('No', 'contempo'); ?></option>
	            </select>
	        </p>
	        <p>
	            <label for="<?php echo esc_attr($this->get_field_id('icon_color')); ?>"><?php esc_html_e('Icon Color','contempo'); ?></label>
	            <select name="<?php echo esc_attr($this->get_field_name('icon_color')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_color')); ?>">
	                <option value="light" <?php if($icon_color == 'light'){ echo "selected='selected'";} ?>><?php esc_html_e('Light', 'contempo'); ?></option>
	                <option value="dark" <?php if($icon_color == 'dark'){ echo "selected='selected'";} ?>><?php esc_html_e('Dark', 'contempo'); ?></option>
	            </select>
	        </p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" />
			</p>
	        <p>
			   <label for="<?php echo esc_attr($this->get_field_id('blurb')); ?>"><?php esc_html_e('Blurb:','contempo'); ?></label>
				<textarea name="<?php echo esc_attr($this->get_field_name('blurb')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('blurb')); ?>"><?php echo esc_html($blurb); ?></textarea>
			</p>
	        <p>
			   <label for="<?php echo esc_attr($this->get_field_id('company')); ?>"><?php esc_html_e('Agent or Company Name:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('company')); ?>"  value="<?php echo esc_attr($company); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('company')); ?>" />
			</p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('agent_lic')); ?>"><?php esc_html_e('Agent License #:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('agent_lic')); ?>" value="<?php echo esc_attr($agent_lic); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('agent_lic')); ?>" />
			</p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('broker_lic')); ?>"><?php esc_html_e('Broker License #:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('broker_lic')); ?>"  value="<?php echo esc_attr($broker_lic); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('broker_lic')); ?>" />
			</p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('street')); ?>"><?php esc_html_e('Street Address:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('street')); ?>" value="<?php echo esc_attr($street); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('street')); ?>" />
			</p>
	        <p>
			   <label for="<?php echo esc_attr($this->get_field_id('city')); ?>"><?php esc_html_e('City:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('city')); ?>" value="<?php echo esc_attr($city); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('city')); ?>" />
			</p>
	        <p>
			   <label for="<?php echo esc_attr($this->get_field_id('state')); ?>"><?php esc_html_e('State:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('state')); ?>"  value="<?php echo esc_attr($state); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('state')); ?>" />
			</p>
	        <p>
			   <label for="<?php echo esc_attr($this->get_field_id('postal')); ?>"><?php esc_html_e('Postal:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('postal')); ?>"  value="<?php echo esc_attr($postal); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('postal')); ?>" />
			</p>
	        <p>
			   <label for="<?php echo esc_attr($this->get_field_id('country')); ?>"><?php esc_html_e('Country:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('country')); ?>"  value="<?php echo esc_attr($country); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('country')); ?>" />
			</p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php esc_html_e('Phone:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('phone')); ?>"  value="<?php echo esc_attr($phone); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" />
			</p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('phone2')); ?>"><?php esc_html_e('Phone 2:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('phone2')); ?>"  value="<?php echo esc_attr($phone2); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('phone2')); ?>" />
			</p>
	        <p>
			   <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php esc_html_e('Email:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('email')); ?>"  value="<?php echo esc_attr($email); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" />
			</p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('email2')); ?>"><?php esc_html_e('Email 2:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('email2')); ?>"  value="<?php echo esc_attr($email2); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('email2')); ?>" />
			</p>
	        <p>
			   <label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>"><?php esc_html_e('Facebook:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>"  value="<?php echo esc_attr($facebook); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" />
			</p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>"><?php esc_html_e('Twitter:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>"  value="<?php echo esc_attr($twitter); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" />
			</p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('linkedin')); ?>"><?php esc_html_e('LinkedIn:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('linkedin')); ?>"  value="<?php echo esc_attr($linkedin); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('linkedin')); ?>" />
			</p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>"><?php esc_html_e('Pinterest:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>"  value="<?php echo esc_attr($pinterest); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" />
			</p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('instagram')); ?>"><?php esc_html_e('Instagram:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('instagram')); ?>"  value="<?php echo esc_attr($instagram); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('instagram')); ?>" />
			</p>
			<p>
			   <label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>"><?php esc_html_e('Youtube:','contempo'); ?></label>
			   <input type="text" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>"  value="<?php echo esc_attr($youtube); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" />
			</p>
			<?php
		}
	} 

	function wpb_load_ct_ContactInfo() {
	    register_widget( 'ct_ContactInfo' );
	}
	add_action( 'widgets_init', 'wpb_load_ct_ContactInfo' );
}

?>