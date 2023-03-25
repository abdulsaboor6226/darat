<?php
ob_start();
/**
 * Header Template
 *
 * @package WP Pro Real Estate 7
 * @subpackage Template
 */
 
// Load Theme Options
global $ct_options;

$current_user = wp_get_current_user();

$url = 'http://' . ct_get_server_info('SERVER_NAME') . ct_get_server_info('REQUEST_URI');

$ct_boxed = isset( $ct_options['ct_boxed'] ) ? esc_attr( $ct_options['ct_boxed'] ) : '';
$ct_top_bar = isset( $ct_options['ct_top_bar'] ) ? esc_attr( $ct_options['ct_top_bar'] ) : '';
$ct_trans_header = isset( $ct_options['ct_trans_header'] ) ? esc_html( $ct_options['ct_trans_header'] ) : '';
$header_layout = isset( $ct_options['ct_header_layout'] ) ? esc_html( $ct_options['ct_header_layout'] ) : '';
$ct_header_style = isset( $ct_options['ct_header_style'] ) ? esc_html( $ct_options['ct_header_style'] ) : '';
$ct_logo = isset( $ct_options['ct_logo']['url'] ) ? esc_html( $ct_options['ct_logo']['url'] ) : '';
$ct_logo_highres = isset( $ct_options['ct_logo_highres']['url'] ) ? esc_html( $ct_options['ct_logo_highres']['url'] ) : '';
$ct_logo_trans = isset( $ct_options['ct_logo_trans']['url'] ) ? esc_html( $ct_options['ct_logo_trans']['url'] ) : '';
$ct_logo_highres_trans = isset( $ct_options['ct_logo_highres_trans']['url'] ) ? esc_html( $ct_options['ct_logo_highres_trans']['url'] ) : '';
$ct_logo_sticky = isset( $ct_options['ct_logo_sticky']['url'] ) ? esc_html( $ct_options['ct_logo_sticky']['url'] ) : '';
$ct_logo_highres_sticky = isset( $ct_options['ct_logo_highres_sticky']['url'] ) ? esc_html( $ct_options['ct_logo_highres_sticky']['url'] ) : '';
$ct_header_listing_search = isset( $ct_options['ct_header_listing_search'] ) ? esc_html( $ct_options['ct_header_listing_search'] ) : '';
$ct_header_listing_search_hide_homepage = isset( $ct_options['ct_header_listing_search_hide_homepage'] ) ? esc_html( $ct_options['ct_header_listing_search_hide_homepage'] ) : '';
$ct_header_info_one_title = isset( $ct_options['ct_header_info_one_title'] ) ? esc_html( $ct_options['ct_header_info_one_title'] ) : '';
$ct_header_info_one_text = isset( $ct_options['ct_header_info_one_text'] ) ? esc_html( $ct_options['ct_header_info_one_text'] ) : '';
$ct_header_info_one_icon = isset( $ct_options['ct_header_info_one_icon'] ) ? esc_html( $ct_options['ct_header_info_one_icon'] ) : '';
$ct_header_info_two_title = isset( $ct_options['ct_header_info_two_title'] ) ? esc_html( $ct_options['ct_header_info_two_title'] ) : '';
$ct_header_info_two_text = isset( $ct_options['ct_header_info_two_text'] ) ? esc_html( $ct_options['ct_header_info_two_text'] ) : '';
$ct_header_info_two_icon = isset( $ct_options['ct_header_info_two_icon'] ) ? esc_html( $ct_options['ct_header_info_two_icon'] ) : '';
$ct_header_info_three_title = isset( $ct_options['ct_header_info_three_title'] ) ? esc_html( $ct_options['ct_header_info_three_title'] ) : '';
$ct_header_info_three_text = isset( $ct_options['ct_header_info_three_text'] ) ? esc_html( $ct_options['ct_header_info_three_text'] ) : '';
$ct_header_info_three_icon = isset( $ct_options['ct_header_info_three_icon'] ) ? esc_html( $ct_options['ct_header_info_three_icon'] ) : '';
$ct_enable_front_end_login = isset( $ct_options['ct_enable_front_end_login'] ) ? esc_html( $ct_options['ct_enable_front_end_login'] ) : '';
$ct_enable_front_end_registration = isset( $ct_options['ct_enable_front_end_registration'] ) ? esc_html( $ct_options['ct_enable_front_end_registration'] ) : '';
$ct_listings_login_register_after_x_views = isset( $ct_options['ct_listings_login_register_after_x_views'] ) ? esc_html( $ct_options['ct_listings_login_register_after_x_views'] ) : '';
$ct_disable_admin_bar = isset( $ct_options['ct_disable_admin_bar'] ) ? esc_attr( $ct_options['ct_disable_admin_bar'] ) : '';

?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie ie9" <?php language_attributes(); ?>><![endif]-->
<!--[if (gte IE 9)|!(IE)]><html <?php language_attributes(); ?>><![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<?php wp_head(); ?>
    
</head>

<body<?php ct_body_id(); ?> <?php body_class('cbp-spmenu-push'); ?>>

<?php if(function_exists('wp_body_open')) {
	wp_body_open(); 
} else {
    do_action('wp_body_open');
} ?>

<?php do_action('after_body'); ?>

<?php if($ct_boxed == 'boxed') {
echo '<div id="boxed-layout-wrap" class="container main">';
} ?>

	<?php do_action('before_wrapper'); ?>
    
    <!-- Wrapper -->
    <div id="wrapper" <?php if($ct_boxed == "boxed") { echo 'class="boxed"'; } elseif($ct_boxed == "full-width-two") { echo 'class="full-width-two"'; } ?>>
    
        <div id="masthead-anchor"></div>

        <?php do_action('before_top_bar'); ?>
        
        <?php if($ct_top_bar == 'yes') { ?>
	        <!-- Top Bar -->
	        <div id="topbar-wrap" class="muted">
	            <div class="container">

		            <?php
		            $ct_contact_phone_header_display_icon = isset( $ct_options['ct_contact_phone_header_display_icon'] ) ? $ct_options['ct_contact_phone_header_display_icon'] : '';
		            $ct_contact_phone_header_icon = isset( $ct_options['ct_contact_phone_header_icon'] ) ? $ct_options['ct_contact_phone_header_icon'] : '';
					$phone = isset( $ct_options['ct_contact_phone_header'] ) ? $ct_options['ct_contact_phone_header'] : '';
		            
		            if($ct_options['ct_header_social'] == 'yes') {
		            	$ct_social_new_tab = isset( $ct_options['ct_social_new_tab'] ) ? esc_html( $ct_options['ct_social_new_tab'] ) : '';
						$facebook = isset( $ct_options['ct_fb_url'] ) ? esc_url( $ct_options['ct_fb_url'] ) : '';    
						$twitter = isset( $ct_options['ct_twitter_url'] ) ? esc_url( $ct_options['ct_twitter_url'] ) : '';  
						$youtube = isset( $ct_options['ct_youtube_url'] ) ? esc_url( $ct_options['ct_youtube_url'] ) : '';  
						$linkedin = isset( $ct_options['ct_linkedin_url'] ) ? esc_url( $ct_options['ct_linkedin_url'] ) : '';
						$pinterest = isset( $ct_options['ct_pinterest_url'] ) ? esc_url( $ct_options['ct_pinterest_url'] ) : '';  
						$instagram = isset( $ct_options['ct_instagram_url'] ) ? esc_url( $ct_options['ct_instagram_url'] ) : ''; 
						$vk = isset( $ct_options['ct_vk_url'] ) ? esc_url( $ct_options['ct_vk_url'] ) : '';  
						$contact = isset( $ct_options['ct_contact_url'] ) ? esc_url( $ct_options['ct_contact_url'] ) : '';   	
					} ?>
	                
	                <?php if($phone != '' || $ct_contact_phone_header_display_icon != '') { ?>
		                <div class="contact-phone left">
							<?php
								if($ct_contact_phone_header_display_icon == 'yes') {
									if(!empty($ct_contact_phone_header_icon)) {
										echo '<i class="fa ' . $ct_contact_phone_header_icon . '"></i>';
									} else {
										ct_phone_svg();
									}
								}
								echo stripslashes($phone);
							?>
	                    </div>
                    <?php } ?>
	                
	                <div class="top-links right">
	                    <?php if($ct_options['ct_header_social'] == 'yes') { ?>
	                    <ul class="social left">
							<?php if($facebook != '') { ?>
		                        <li class="facebook"><a href="<?php echo esc_url($facebook); ?>" <?php if($ct_social_new_tab == 'yes') { echo 'target="_blank"'; } ?>><i class="fab fa-facebook"></i></a></li>
		                    <?php } ?>
		                    <?php if($twitter != '') { ?>
		                        <li class="twitter"><a href="<?php echo esc_url($twitter); ?>" <?php if($ct_social_new_tab == 'yes') { echo 'target="_blank"'; } ?>><i class="fab fa-twitter"></i></a></li>
		                    <?php } ?>
		                    <?php if($linkedin != '') { ?>
		                        <li class="linkedin"><a href="<?php echo esc_url($linkedin); ?>" <?php if($ct_social_new_tab == 'yes') { echo 'target="_blank"'; } ?>><i class="fab fa-linkedin"></i></a></li>
		                    <?php } ?>
		                    <?php if($youtube != '') { ?>
		                        <li class="youtube"><a href="<?php echo esc_url($youtube); ?>" <?php if($ct_social_new_tab == 'yes') { echo 'target="_blank"'; } ?>><i class="fab fa-youtube-square"></i></a></li>
		                    <?php } ?>
		                    <?php if($pinterest != '') { ?>
		                        <li class="pinterest"><a href="<?php echo esc_url($pinterest); ?>" <?php if($ct_social_new_tab == 'yes') { echo 'target="_blank"'; } ?>><i class="fab fa-pinterest"></i></a></li>
		                    <?php } ?>
		                    <?php if($instagram != '') { ?>
		                        <li class="instagram"><a href="<?php echo esc_url($instagram); ?>" <?php if($ct_social_new_tab == 'yes') { echo 'target="_blank"'; } ?>><i class="fab fa-instagram"></i></a></li>
		                    <?php } ?>
		                    <?php if($vk != '') { ?>
		                        <li class="vk"><a href="<?php echo esc_url($vk); ?>" <?php if($ct_social_new_tab == 'yes') { echo 'target="_blank"'; } ?>><i class="fab fa-vk"></i></a></li>
		                    <?php } ?>
		                    <?php if($contact != '') { ?>
		                        <li class="contact"><a href="<?php echo esc_url($contact); ?>"><i class="fa fa-envelope"></i></a></li>
		                    <?php } ?>
	                    </ul>
	                    <?php } ?>
	                    <?php
	                        if(class_exists('SitePress')) {
	                            echo '<div class="wpml-lang left">';
	                                do_action('icl_language_selector');
	                            echo '</div>';
	                        }
	                    ?>
	                    <?php if($ct_header_style != 'three' && $ct_enable_front_end_login != 'no') { ?>
		                    <?php if(function_exists('ct_login_register_user_drop')) {
								ct_login_register_user_drop();
							} ?>
	                    <?php } ?>
	                    
	                </div>
	                    <div class="clear"></div>
	                    
	            </div>
	        </div>
	        <!-- //Top Bar -->
	    <?php } ?>

	    <?php do_action('before_header'); ?>

		<?php if($ct_header_style == 'two') { ?>

			<!-- Header -->
			<div id="header-wrap-placeholder">
		        <div id="header-wrap" class="<?php echo 'header-style-' . $ct_header_style; ?><?php if($ct_trans_header == 'yes' && is_front_page() && strpos($url,'search-listings=true') != true) { echo ' trans-header'; } ?>">
		            <div class="container">
		                <header id="masthead" class="layout-<?php echo esc_html($header_layout); ?>">
		                	<?php do_action('inner_header'); ?>

		                	<div class="col span_12 first">
			                	 <!-- Logo -->
			                    <div class="logo-wrap col span_3 first<?php if(!empty($ct_options['ct_logo_sticky']['url'])) { echo ' has-sticky-logo'; } ?>">        
			                        <?php if($ct_options['ct_text_logo'] == "yes") { ?>
			                            
			                            <div id="logo" class="left">
			                                <h2><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h2>
			                            </div>
			                            
			                        <?php } else { ?>

			                        	<?php if($ct_trans_header == 'yes' && is_front_page() && strpos($url,'search-listings=true') != true) { ?>

			                        		<a href="<?php echo home_url(); ?>">
				                        		<?php if(!empty($ct_options['ct_logo']['url'])) { ?>
					                        		<img class="logo left" src="<?php echo esc_url($ct_options['ct_logo_trans']['url']); ?>" <?php if(!empty($ct_logo_highres_trans)) { ?>srcset="<?php echo esc_url($ct_logo_highres_trans); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" />
					                        	<?php } else { ?>
					                        		<img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue-white.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue-white@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
					                        	<?php } ?>
					                        </a>

			                        	<?php } else { ?>
			                            
				                            <?php if(!empty($ct_options['ct_logo']['url'])) { ?>
				                                <a href="<?php echo home_url(); ?>"><img class="logo left" src="<?php echo esc_url($ct_options['ct_logo']['url']); ?>" <?php if(!empty($ct_logo_highres)) { ?>srcset="<?php echo esc_url($ct_logo_highres); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" /></a>
				                            <?php } else { ?>
				                                <a href="<?php echo home_url(); ?>">
							                    	<?php if($ct_options['ct_skin'] == 'minimal') { ?>
								                        <img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
							                        <?php } else { ?>
							                        	<img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
							                        <?php } ?>
							                    </a>
				                            <?php } ?>

				                        <?php } ?>

				                        <?php if(!empty($ct_options['ct_logo_sticky']['url'])) { ?>
			                        		<a class="sticky-logo" href="<?php echo home_url(); ?>">
				                        		<img class="logo left" src="<?php echo esc_url($ct_options['ct_logo_trans']['url']); ?>" <?php if(!empty($ct_logo_highres_sticky)) { ?>srcset="<?php echo esc_url($ct_logo_highres_sticky); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" />
				                        	</a>
			                        	<?php } ?>
			                            
			                        <?php } ?>
			                    </div>
			                    <!-- //Logo -->

			                    <?php if($ct_header_info_one_title != '' || $ct_header_info_one_text != '' || $ct_header_info_one_icon != '') { ?>
			                    <!-- Header Info -->
			                    <div class="col span_3 header-info">
			                    	<div class="col span_1 first">
					                    	<i class="fa <?php echo esc_html($ct_header_info_one_icon); ?>"></i>
			                    	</div>
			                    	<div class="col span_11">
				                    	<div class="header-info-inner">
					                    	<h5 class="marB0"><?php echo esc_html($ct_header_info_one_title); ?></h5>
					                    	<p class="muted marB0"><?php echo esc_html($ct_header_info_one_text); ?></p>
				                    	</div>
			                    	</div>
			                    </div>
			                    <!-- //Header Info -->
			                    <?php } ?>

			                    <?php if($ct_header_info_two_title != '' || $ct_header_info_two_text != '' || $ct_header_info_two_icon != '') { ?>
			                    <!-- Header Info -->
			                    <div class="col span_3 header-info">
			                    	<div class="col span_1 first">
					                    	<i class="fa <?php echo esc_html($ct_header_info_two_icon); ?>"></i>
			                    	</div>
			                    	<div class="col span_11">
				                    	<div class="header-info-inner">
					                    	<h5 class="marB0"><?php echo esc_html($ct_header_info_two_title); ?></h5>
					                    	<p class="muted marB0"><?php echo esc_html($ct_header_info_two_text); ?></p>
				                    	</div>
			                    	</div>
			                    </div>
			                    <!-- //Header Info -->
			                    <?php } ?>

			                    <?php if($ct_header_info_three_title != '' || $ct_header_info_three_text != '' || $ct_header_info_three_icon != '') { ?>
			                    <!-- Header Info -->
			                    <div class="col span_3 header-info">
			                    	<div class="col span_1 first">
					                    	<i class="fa <?php echo esc_html($ct_header_info_three_icon); ?>"></i>
			                    	</div>
			                    	<div class="col span_11">
				                    	<div class="header-info-inner">
					                    	<h5 class="marB0"><?php echo esc_html($ct_header_info_three_title); ?></h5>
					                    	<p class="muted marB0"><?php echo esc_html($ct_header_info_three_text); ?></p>
				                    	</div>
			                    	</div>
			                    </div>
			                    <!-- //Header Info -->
			                    <?php } ?>
		                    </div>

		                    
		                    	<div class="clear"></div>

	                	</header>
                	</div>
                	<!-- Nav -->
                    <div id="nav-full-width" class="col span_12 first">
	                    <div class="container">
							<?php ct_nav_full_width(); ?>
	                    </div>
                    </div>
                    <!-- //Nav -->

						<div class="clear"></div>
                    
            	</div>
			</div>
            <!-- //Header -->

            <!-- Mobile Header -->
			<?php ct_mobile_header(); ?>
			<!-- //Mobile Header -->

        <?php } elseif($ct_header_style == 'three') { ?>

			<!-- Header -->
			<div id="header-wrap-placeholder">
		        <div id="header-wrap" class="<?php echo 'header-style-' . $ct_header_style; ?><?php if($ct_trans_header == 'yes' && is_front_page() && strpos($url,'search-listings=true') != true) { echo ' trans-header'; } ?> <?php if(!is_user_logged_in()) { echo 'not-logged-in'; } ?>">
		            <div class="container">
		                <header id="masthead" class="layout-<?php echo esc_html($header_layout); ?>">
		                	<?php do_action('inner_header'); ?>

		                	<div class="col span_12 first">
			                	 <!-- Logo -->
			                    <div class="logo-wrap col span_2 first<?php if(!empty($ct_options['ct_logo_sticky']['url'])) { echo ' has-sticky-logo'; } ?>">        
			                        <?php if($ct_options['ct_text_logo'] == "yes") { ?>
			                            
			                            <div id="logo" class="left">
			                                <h2><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h2>
			                            </div>
			                            
			                        <?php } else { ?>

			                        	<?php if($ct_trans_header == 'yes' && is_front_page() && strpos($url,'search-listings=true') != true) { ?>

			                        		<a href="<?php echo home_url(); ?>">
				                        		<?php if(!empty($ct_options['ct_logo']['url'])) { ?>
					                        		<img class="logo left" src="<?php echo esc_url($ct_options['ct_logo_trans']['url']); ?>" <?php if(!empty($ct_logo_highres_trans)) { ?>srcset="<?php echo esc_url($ct_logo_highres_trans); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" />
					                        	<?php } else { ?>
					                        		<img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue-white.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue-white@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
					                        	<?php } ?>
					                        </a>

			                        	<?php } else { ?>
			                            
				                            <?php if(!empty($ct_options['ct_logo']['url'])) { ?>
				                                <a href="<?php echo home_url(); ?>"><img class="logo left" src="<?php echo esc_url($ct_options['ct_logo']['url']); ?>" <?php if(!empty($ct_logo_highres)) { ?>srcset="<?php echo esc_url($ct_logo_highres); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" /></a>
				                            <?php } else { ?>
				                               <a href="<?php echo home_url(); ?>">
							                    	<?php if($ct_options['ct_skin'] == 'minimal') { ?>
								                        <img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
							                        <?php } else { ?>
							                        	<img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
							                        <?php } ?>
							                    </a>
				                            <?php } ?>

				                        <?php } ?>

				                        <?php if(!empty($ct_options['ct_logo_sticky']['url'])) { ?>
			                        		<a class="sticky-logo" href="<?php echo home_url(); ?>">
				                        		<img class="logo left" src="<?php echo esc_url($ct_options['ct_logo_trans']['url']); ?>" <?php if(!empty($ct_logo_highres_sticky)) { ?>srcset="<?php echo esc_url($ct_logo_highres_sticky); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" />
				                        	</a>
			                        	<?php } ?>
			                            
			                        <?php } ?>
			                    </div>
			                    <!-- //Logo -->

			                    <!-- Nav -->
			                    <div class="col span_7">
				                    <div class="container">
										<?php ct_nav_left(); ?>
				                    </div>
			                    </div>
			                    <!-- //Nav -->

								<div class="col span_3">
									<?php if(function_exists('ct_login_register_user_drop')) {
										ct_login_register_user_drop();
									} ?>
								</div>
			                    
		                    </div>
		                    
		                    	<div class="clear"></div>

	                	</header>
                	</div>

						<div class="clear"></div>
                    
            	</div>
			</div>
	        <!-- //Header -->

            <!-- Mobile Header -->
			<?php ct_mobile_header(); ?>
			<!-- //Mobile Header -->

		<?php } else { ?>

			<!-- Header -->
			<div id="header-wrap-placeholder">
		        <div id="header-wrap" <?php if($ct_trans_header == 'yes' && is_front_page() && strpos($url,'search-listings=true') != true) { echo 'class="trans-header"'; } ?>>
		            <div class="container">
		                <header id="masthead" class="layout-<?php echo esc_html($header_layout); ?>">
		                	<?php do_action('inner_header'); ?>
			                <?php

			                if($header_layout == 'left') { ?>

				                <!-- Logo -->
			                    <div class="logo-wrap col span_3 first<?php if(!empty($ct_options['ct_logo_sticky']['url'])) { echo ' has-sticky-logo'; } ?>">        
			                        <?php if($ct_options['ct_text_logo'] == "yes") { ?>
			                            
			                            <div id="logo" class="left">
			                                <h2><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h2>
			                            </div>
			                            
			                        <?php } else { ?>

			                        	<?php if($ct_trans_header == 'yes' && is_front_page() && strpos($url,'search-listings=true') != true) { ?>

			                        		<a href="<?php echo home_url(); ?>">
				                        		<?php if(!empty($ct_options['ct_logo']['url'])) { ?>
					                        		<img class="logo left" src="<?php echo esc_url($ct_options['ct_logo_trans']['url']); ?>" <?php if(!empty($ct_logo_highres_trans)) { ?>srcset="<?php echo esc_url($ct_logo_highres_trans); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" />
					                        	<?php } else { ?>
					                        		<img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue-white.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue-white@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
					                        	<?php } ?>
					                        </a>

			                        	<?php } else { ?>
			                            
				                            <?php if(!empty($ct_options['ct_logo']['url'])) { ?>
				                                <a href="<?php echo home_url(); ?>"><img class="logo left" src="<?php echo esc_url($ct_options['ct_logo']['url']); ?>" <?php if(!empty($ct_logo_highres)) { ?>srcset="<?php echo esc_url($ct_logo_highres); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" /></a>
				                            <?php } else { ?>
				                                <a href="<?php echo home_url(); ?>">
							                    	<?php if($ct_options['ct_skin'] == 'minimal') { ?>
								                        <img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
							                        <?php } else { ?>
							                        	<img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
							                        <?php } ?>
							                    </a>
				                            <?php } ?>

				                        <?php } ?>

				                        <?php if(!empty($ct_options['ct_logo_sticky']['url'])) { ?>
			                        		<a class="sticky-logo" href="<?php echo home_url(); ?>">
				                        		<img class="logo left" src="<?php echo esc_url($ct_options['ct_logo_trans']['url']); ?>" <?php if(!empty($ct_logo_highres_sticky)) { ?>srcset="<?php echo esc_url($ct_logo_highres_sticky); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" />
				                        	</a>
			                        	<?php } ?>
			                            
			                        <?php } ?>
			                    </div>
			                    <!-- //Logo -->

			                    <!-- Nav -->
			                    <div class="col span_9">
									<?php ct_nav_right(); ?>
			                    </div>
			                    <!-- //Nav -->		               

		                    <?php } elseif($header_layout == 'center') { ?>

			                    <!-- Nav -->
			                    <div class="col span_5 first">
									<?php ct_nav_left(); ?>
			                    </div>
			                    <!-- //Nav -->

		                    	<!-- Logo -->
			                    <div class="logo-wrap col span_2<?php if(!empty($ct_options['ct_logo_sticky']['url'])) { echo ' has-sticky-logo'; } ?>">        
			                        <?php if($ct_options['ct_text_logo'] == "yes") { ?>
			                            
			                            <div id="logo" class="left">
			                                <h2><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h2>
			                            </div>
			                            
			                        <?php } else { ?>
			                            
			                            <?php if(!empty($ct_options['ct_logo']['url'])) { ?>
			                                <a href="<?php echo home_url(); ?>"><img class="logo left" src="<?php echo esc_url($ct_options['ct_logo']['url']); ?>" <?php if(!empty($ct_logo_highres)) { ?>srcset="<?php echo esc_url($ct_logo_highres); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" /></a>
			                            <?php } else { ?>
			                                <a href="<?php echo home_url(); ?>">
						                    	<?php if($ct_options['ct_skin'] == 'minimal') { ?>
							                        <img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
						                        <?php } else { ?>
						                        	<img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
						                        <?php } ?>
						                    </a>
			                            <?php } ?>

			                            <?php if(!empty($ct_options['ct_logo_sticky']['url'])) { ?>
			                        		<a class="sticky-logo" href="<?php echo home_url(); ?>">
				                        		<img class="logo left" src="<?php echo esc_url($ct_options['ct_logo_trans']['url']); ?>" <?php if(!empty($ct_logo_highres_sticky)) { ?>srcset="<?php echo esc_url($ct_logo_highres_sticky); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" />
				                        	</a>
			                        	<?php } ?>
			                            
			                        <?php } ?>
			                    </div>
			                    <!-- //Logo -->
			                    
			                    <!-- Nav -->
			                    <div class="col span_5">
									<?php ct_nav_right(); ?>
			                    </div>
			                    <!-- //Nav -->

		                    <?php } elseif($header_layout == 'right') { ?>

			                     <!-- Nav -->
			                    <div class="col span_9 first">
									<?php ct_nav_left(); ?>
			                    </div>
			                    <!-- //Nav -->

			                	<!-- Logo -->
			                    <div class="logo-wrap col span_3<?php if(!empty($ct_options['ct_logo_sticky']['url'])) { echo ' has-sticky-logo'; } ?>">        
			                        <?php if($ct_options['ct_text_logo'] == "yes") { ?>
			                            
			                            <div id="logo" class="right">
			                                <h2><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h2>
			                            </div>
			                            
			                        <?php } else { ?>
			                            
			                            <?php if(!empty($ct_options['ct_logo']['url'])) { ?>
			                                <a href="<?php echo home_url(); ?>"><img class="logo right" src="<?php echo esc_url($ct_options['ct_logo']['url']); ?>" <?php if(!empty($ct_logo_highres)) { ?>srcset="<?php echo esc_url($ct_logo_highres); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" /></a>
			                            <?php } else { ?>
			                                <a href="<?php echo home_url(); ?>">
						                    	<?php if($ct_options['ct_skin'] == 'minimal') { ?>
							                        <img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo-blue@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
						                        <?php } else { ?>
						                        	<img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" />
						                        <?php } ?>
						                    </a>
			                            <?php } ?>

			                            <?php if(!empty($ct_options['ct_logo_sticky']['url'])) { ?>
			                        		<a class="sticky-logo" href="<?php echo home_url(); ?>">
				                        		<img class="logo left" src="<?php echo esc_url($ct_options['ct_logo_trans']['url']); ?>" <?php if(!empty($ct_logo_highres_sticky)) { ?>srcset="<?php echo esc_url($ct_logo_highres_sticky); ?> 2x"<?php } ?> alt="<?php bloginfo('name'); ?>" />
				                        	</a>
			                        	<?php } ?>
			                            
			                        <?php } ?>
			                    </div>
			                    <!-- //Logo -->

		                    <?php } elseif($header_layout == 'none') { ?>
		                    	
		                    	<?php // No header ?>

		                    <?php } ?>
	                    
	                        <div class="clear"></div>

		                </header>
		            </div>
		        </div>
			</div>
	        <!-- //Header -->

	        <!-- Mobile Header -->
			<?php ct_mobile_header(); ?>
			<!-- //Mobile Header -->

	    <?php } ?>

	    <?php
	    if(!class_exists('Redux')) { ?>
	    	<script>
			jQuery(window).load(function() {
				jQuery("div.ct-menu ul").addClass("ct-menu");
			});
	    	</script>
			<!-- Header -->
			<div id="header-wrap-placeholder">
		        <div id="header-wrap" class="<?php echo 'header-style-' . $ct_header_style; ?><?php if($ct_trans_header == 'yes' && is_front_page() && strpos($url,'search-listings=true') == true) { echo ' trans-header'; } ?>">
					<div class="container">
			        	<header id="masthead" class="layout-<?php echo esc_html($header_layout); ?>">
		                	<?php do_action('inner_header'); ?>

				                <!-- Logo -->
			                    <div class="logo-wrap col span_3 first">        
			                        <a href="<?php echo home_url(); ?>"><img class="logo left" src="<?php echo get_template_directory_uri(); ?>/images/re7-logo.png" srcset="<?php echo get_template_directory_uri(); ?>/images/re7-logo@2x.png 2x" alt="WP Pro Real Estate 7, a WordPress theme by Contempo" /></a>
			                    </div>
			                    <!-- //Logo -->

			                    <!-- Nav -->
			                    <div id="default-header" class="col span_9">
									<nav class="right">
								    	<?php wp_nav_menu (
								    		array(
												'menu'            => "primary-right",
												'menu_id'         => "ct-menu",
												'menu_class'      => "ct-menu",
												'echo'            => true,
												'container'       => '',
												'container_class' => '',
												'container_id'    => 'nav-left',
												'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
												'container_id'    => 'nav-right',
												'theme_location'  => 'primary_right',
												'fallback_cb'	  => false,
												'walker'          => new CT_Menu_Class_Walker
											)
								    	); ?>
								    </nav>
			                    </div>
			                    <!-- //Nav -->	

				            <div class="clear"></div>
			            </header>
	                </div>
	        	</div>
	    	</div>
	        <!-- //Header -->

	<?php } ?>

	<?php do_action('before_header_search'); ?>

    <?php if($ct_header_listing_search == 'yes') { ?>
    	
    	<?php if($ct_header_listing_search_hide_homepage == 'yes') {

    		if(is_front_page()) {
    			echo '<style>';
    				echo '#header-search-wrap { display: none;}';
    			echo '</style>';
    		}
	    	get_template_part('/includes/header-advanced-search');

    	} else {

    		get_template_part('/includes/header-advanced-search');

    	} ?>

    <?php } ?>

    	<div class="clear"></div>

    <?php do_action('before_main_content'); ?>

    <!-- Main Content -->
    <section id="main-content" class="<?php if(!is_user_logged_in()) { echo 'not-logged-in'; } ?> <?php if($ct_trans_header == 'yes' && is_front_page() && strpos($url,'search-listings=true') != true) { echo 'trans-header'; } ?>">