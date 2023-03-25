<?php
/**
 * Template Name: User Dashboard
 *
 * @package WP Pro Real Estate 7
 * @subpackage Template
 */
 
global $ct_options, $current_user, $wp_roles;

$ct_boxed = isset( $ct_options['ct_boxed'] ) ? esc_attr( $ct_options['ct_boxed'] ) : '';
$current_user = wp_get_current_user();
$inside_page_title = get_post_meta($post->ID, '_ct_inner_page_title', true);

get_header();

if( !function_exists('wp_handle_upload') ) {
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

?>

<div id="page-content" class="front-end-user-page <?php if($ct_boxed != 'full-width-two') { echo 'container'; } ?> <?php if(!is_user_logged_in()) { echo 'not-logged-in'; } ?>">

	<?php do_action('before_user_dashboard'); ?>

	<?php if(is_user_logged_in()) {
        get_template_part('/includes/user-sidebar');
    } ?>

    <article class="col <?php if(is_user_logged_in()) { echo 'span_10'; } else { echo 'span_12 first'; } ?> marB60">

    	<?php if(!is_user_logged_in()) {
            echo '<div class="must-be-logged-in">';
				echo '<h4 class="center marB20">' . __('You must be logged in to view this page.', 'contempo') . '</h4>';
                echo '<p class="center login-register-btn marB0"><a class="btn login-register" href="#">Login/Register</a></p>';
            echo '</div>';
        } else {

        	$ct_saved_listings = isset( $ct_options['ct_saved_listings'] ) ? esc_html( $ct_options['ct_saved_listings'] ) : '';
            $ct_listing_email_alerts_page_id = isset( $ct_options['ct_listing_email_alerts_page_id'] ) ? esc_attr( $ct_options['ct_listing_email_alerts_page_id'] ) : '';
            $ct_submit_listing = isset( $ct_options['ct_submit'] ) ? esc_html( $ct_options['ct_submit'] ) : '';
            $ct_view_listings = isset( $ct_options['ct_view'] ) ? esc_html( $ct_options['ct_view'] ) : '';
            $ct_listing_analytics = isset( $ct_options['ct_listing_analytics'] ) ? esc_html( $ct_options['ct_listing_analytics'] ) : '';
            $ct_user_profile = isset( $ct_options['ct_profile'] ) ? esc_html( $ct_options['ct_profile'] ) : '';
        	
        	the_content();

			if( function_exists( 'ct_lp_dashboard' ) && apply_filters( 'ct_leads_active', true )) {
				ct_lp_dashboard();

				$ct_lp_page = ct_get_lp_page();

				echo '<a class="card-link" href="' . get_permalink($ct_lp_page) . '#/leads' . '">';
					echo '<div class="card col span_3 first card-leads-active">';
						echo '<div class="card-inner">';
							echo '<p id="leads-active-head" class="muted">' . __('Leads Active', 'contempo') . '</p>';
							echo '<p class="muted small">' . __('Past 30 Days', 'contempo') . '</p>';
							echo '<div id="ct_lp_dashboard_lead_active"></div>';
						echo  '</div>';
					echo '</div>';
				echo '</a>';
			
				echo '<a class="card-link" href="' . get_permalink($ct_lp_page) . '#/leads' . '">';
					echo '<div class="card col span_3 card-lead-sources">';
						echo '<div class="card-inner">';
							echo '<p class="card-title muted" id="lead-sources-head">' . __('Lead Sources', 'contempo') . '</p>';
							echo '<p  class="card-subtitle muted small">' . __('Past 30 Days', 'contempo') . '</p>';
							echo '<div id="ct_lp_dashboard_lead_sources"></div>';
						echo  '</div>';
					echo '</div>';
				echo '</a>';
				
				echo '<a class="card-link" href="' . get_permalink($ct_lp_page) . '#/leads' . '">';
					echo '<div class="card col span_6 card-lead-funnel">';
						echo '<div class="card-inner">';
							echo '<div class="lrg-icon">';
								if(function_exists('ct_funnel_svg_white')) {
									ct_funnel_svg_white();
								} else {
									echo '<i class="fas fa-filter"></i>';
								}  		
							echo '</div>';
							echo '<p id="leads-funnel-head" class="muted">' . __('Leads Funnel', 'contempo') . '</p>';
							echo '<div id="ct_lp_dashboard_lead_funnel"></div>';
						echo  '</div>';
					echo '</div>';
				echo '</a>';
			}

			// User Listings # for Front End Listing System
        	$ct_user_listings = ct_listing_post_count($current_user->ID, 'listings');
        	if($ct_user_listings >= 0 && isset( $ct_options['ct_enable_front_end']  ) && $ct_options['ct_enable_front_end'] == 'yes') {
        		echo '<a class="card-link" href="' . get_permalink($ct_view_listings) . '">';
                	echo '<div class="card col span_4 card-my-listings">';
                		echo '<div class="card-inner">';
	                		echo '<div class="lrg-icon">';
	                			if(function_exists('ct_listings_svg_white')) {
	                				ct_listings_svg_white();
	                			} else {
	                				echo '<i class="fas fa-th-list"></i>';
	                			}  		
		                	echo '</div>';
							echo '<h1>';
								echo esc_html($ct_user_listings);
							echo '</h1>';
							echo '<p class="muted">' . __('My Listings', 'contempo') . '</p>';
		               	echo  '</div>';
                	echo '</div>';
            	echo '</a>';
            }

            // User Featured Listing # for Front End Listing System
        	$ct_user_featured_listings = ct_listing_featured_post_count($current_user->ID, 'listings', 'featured');
        	if($ct_user_featured_listings >= 0 && isset( $ct_options['ct_enable_front_end'] )  && $ct_options['ct_enable_front_end'] == 'yes') {
        		echo '<a class="card-link" href="' . get_permalink($ct_view_listings) . '">';
                	echo '<div class="card col span_4 card-featured-listings">';
                		echo '<div class="card-inner">';
	                		echo '<div class="lrg-icon">';
	                			if(function_exists('ct_featured_svg')) {
	                				ct_featured_svg();
	                			} else {
	                				echo '<i class="fas fa-star"></i>';
	                			} 
		                	echo '</div>';
							echo '<h1>';
								echo esc_html($ct_user_featured_listings);
							echo '</h1>';
							echo '<p class="muted">' . __('Featured Listings', 'contempo') . '</p>';
		               	echo  '</div>';
                	echo '</div>';
            	echo '</a>';
            }

            // User Pending Listing # for Front End Listing System
        	$ct_user_pending_listings = ct_listing_pending_post_count($current_user->ID, 'listings');
        	if($ct_user_pending_listings >= 0 && isset( $ct_options['ct_enable_front_end'] ) && $ct_options['ct_enable_front_end'] == 'yes') {
        		echo '<a class="card-link" href="' . get_permalink($ct_view_listings) . '">';
                	echo '<div class="card col span_4 card-pending-listings">';
                		echo '<div class="card-inner">';
	                		echo '<div class="lrg-icon">';
	                			if(function_exists('ct_pending_svg')) {
	                				ct_pending_svg();
	                			} else {
	                				echo '<i class="fas fa-file"></i>';
	                			} 
		                	echo '</div>';
							echo '<h1>';
								echo esc_html($ct_user_pending_listings);
							echo '</h1>';
							echo '<p class="muted">' . __('Pending Listings', 'contempo') . '</p>';
		               	echo  '</div>';
                	echo '</div>';
            	echo '</a>';
            }

			if(class_exists('ctListingAnalytics')) {

				echo do_shortcode('[ct_listing_analytics_dashboard_views]');
			
				echo do_shortcode('[ct_listing_analytics_dashboard_downloads]');

			}
    
            echo '<div class="clear"></div>';

		} ?>
        
            <div class="clear"></div>

        <?php do_action('after_user_dashboard_content'); ?>             

    </article>

    <?php do_action('after_user_dashboard'); ?>

<?php 
	echo '<div class="clear"></div>';
echo '</div>';

get_footer(); ?>
