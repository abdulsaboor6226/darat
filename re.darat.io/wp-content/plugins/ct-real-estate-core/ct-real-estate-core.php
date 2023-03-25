<?php

/**
 *
 * @link              https://contempothemes.com
 * @since             3.4.5
 * @package           CT_Real_Estate_Core
 *
 * @wordpress-plugin
 * Plugin Name:       Contempo Real Estate Core
 * Plugin URI:        http://contempothemes.com
 * Description:       This plugin registers listings, brokerages & testimonials custom post types, related custom fields & taxonomies, and widgets for Real Estate 7.
 * Version:           3.4.5
 * Author:            Contempo
 * Author URI:        https://contempothemes.com
 * Text Domain:       contempo
 * Domain Path:       /languages
 * Requires PHP:      8.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CT_STORE_URL', 'https://contempothemes.com' );
define( 'CT_ITEM_ID', 182173 ); 
define( 'CT_ITEM_NAME', 'Contempo Real Estate Core' ); 

if( !class_exists( 'CTREC_Plugin_Updater' ) ) {
	require plugin_dir_path( __FILE__ ) . 'includes/PluginUpdater.php';
}

/**
 * Initialize the updater. Hooked into `init` to work with the
 * wp_version_check cron job, which allows auto-updates.
 */
function ct_plugin_updater() {

	// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
	$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
	if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
		return;
	}

	$edd_updater = new CTREC_Plugin_Updater( CT_STORE_URL, __FILE__,
		array(
			'version' => '3.4.5',
			'license' => 0,
			'item_id' => CT_ITEM_ID,
			'author'  => 'Contempo Themes',
			'beta'    => false,
		)
	);

}
add_action( 'init', 'ct_plugin_updater' );

/*-----------------------------------------------------------------------------------*/
/* Load Plugin Textdomain */
/*-----------------------------------------------------------------------------------*/

add_action( 'plugins_loaded', 'ct_rec_load_textdomain' );

function ct_rec_load_textdomain() {
  load_plugin_textdomain( 'contempo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}

/*-----------------------------------------------------------------------------------*/
/* Display admin notice for CT IDX Pro
/*-----------------------------------------------------------------------------------*/

// Display Notice
function ct_rec_idx_admin_notices() {
	global $current_user;
	$user_id = $current_user->ID;

	if(!class_exists('ctIdxPro') && !get_user_meta($user_id, 'ct_idx_notice_dismiss')) {
		
		$ct_idx_link = 'https://contempothemes.com/real-estate-idx/';

		echo '<div class="ct-notice updated notice is-dismissible">';
			echo '<div class="ct-notice-col-one">';
		    	echo '<img src="https://contempo-media.s3.amazonaws.com/root/uploads/2019/10/elementor-demo-2.png" />';
		    echo '</div>';
			echo '<div class="ct-notice-col-two">';
		        echo '<h3><strong>' . __('Want to connect and display listings from your local MLS?', 'contempo') . '</strong></h3>';
		        echo '<p class="tagline">' . __('Tired of confusing third-party IDX plugins that look nothing like your site? <em>We are too.</em>', 'contempo') . '</p>';
		        echo '<p style="margin-bottom: 1em;">' . __('We’ve developed an exclusive IDX plugin that directly integrates into Real Estate 7 with MLS data coverage across all 50 states, Washington D.C., and Canada. Thats 650+ MLS Markets and we’re adding more all the time.', 'contempo') . '</p>';
		        echo '<p><a class="button button-primary" href="' . esc_url($ct_idx_link) . '" target="_blank">' . __('Learn More', 'contempo') . '</a></p>';
		    echo '</div>';
	        echo '<a class="ct-notice-dismiss dismiss-notice" href="' . site_url() . '/wp-admin/admin.php?ct_idx_notice_dismiss=0" target="_parent">' . __('Dismiss this notice', 'contempo') . '</a>';
	        	echo '<div class="clear"></div>';
	    echo '</div>';

	}
}

// Set Dismiss Referer
function ct_rec_idx_admin_notices_init() {
    if ( isset($_GET['ct_idx_notice_dismiss']) && '0' == $_GET['ct_idx_notice_dismiss'] ) {
        $user_id = get_current_user_id();
        add_user_meta($user_id, 'ct_idx_notice_dismiss', 'true', true);
        if (wp_get_referer()) {
            /* Redirects user to where they were before */
            wp_safe_redirect(wp_get_referer());
        } else {
            /* if there is no referrer you redirect to home */
            wp_safe_redirect(home_url());
        }
    }
}

add_action('admin_init', 'ct_rec_idx_admin_notices_init');
add_action( 'admin_notices', 'ct_rec_idx_admin_notices' );

/*-----------------------------------------------------------------------------------*/
/* Display admin notice for CT Leads Pro
/*-----------------------------------------------------------------------------------*/

// Display Notice
function ct_rec_leads_pro_admin_notices() {
	global $current_user;
	$user_id = $current_user->ID;

	if(!function_exists('ct_check_leads_pro_extensions') && !get_user_meta($user_id, 'ct_leads_pro_notice_dismiss')) {
		
		$ct_leads_pro_plugin_path = WP_PLUGIN_DIR . '/ct-leads-pro/ct-leads-pro.php';
		$ct_leads_pro_install_link = home_url() . '/wp-admin/plugins.php?plugin_status=inactive';
		$ct_leads_pro_link = 'https://contempothemes.com/wp-real-estate-7/ct-leads-pro-plugin/';

		echo '<div class="ct-notice updated notice is-dismissible">';
			echo '<div class="ct-notice-col-one">';
		    	echo '<img src="https://contempo-media.s3.amazonaws.com/root/uploads/2021/01/ct-leads-pro-tn.png" />';
		    echo '</div>';
			echo '<div class="ct-notice-col-two">';
		        echo '<h3><strong>' . __('Want to increase your lead capture & conversion?', 'contempo') . '</strong></h3>';
		        echo '<p class="tagline"><em>' . __('Capture, qualify, nurture, & manage leads all from one place.</em>', 'contempo') . '</em></p>';
		        echo '<p style="margin-bottom: 1em;">' . __('Don’t let the term CRM scare you or your team, our CT Leads Pro system was developed by us exclusively for Real Estate 7 and designed to be intuitive, with a zero learning curve interface, just the things you need to follow up, nurture, and close deals.', 'contempo') . '</p>';
		        if(file_exists($ct_leads_pro_plugin_path)) {
		        	echo '<p class="learn-more">';
			        	echo '<a class="button button-primary install-plugin" href="' . esc_url($ct_leads_pro_install_link) . '">' . __('Activate Plugin', 'contempo') . '</a>';
			        	echo '<a class="button button-secondary" href="' . esc_url($ct_leads_pro_link) . '" target="_blank">' . __('Learn More', 'contempo') . '</a>';
		        	echo '</p>';
		        } else {
			        echo '<p class="learn-more"><a class="button button-primary" href="' . esc_url($ct_leads_pro_link) . '" target="_blank">' . __('Learn More', 'contempo') . '</a></p>';
			    }
		    echo '</div>';
	        echo '<a class="ct-notice-dismiss dismiss-notice" href="' . site_url() . '/wp-admin/admin.php?ct_leads_pro_notice_dismiss=0" target="_parent">' . __('Dismiss this notice', 'contempo') . '</a>';
	        	echo '<div class="clear"></div>';
	    echo '</div>';

	}
}

// Set Dismiss Referer
function ct_rec_leads_pro_admin_notices_init() {
    if ( isset($_GET['ct_leads_pro_notice_dismiss']) && '0' == $_GET['ct_leads_pro_notice_dismiss'] ) {
        $user_id = get_current_user_id();
        add_user_meta($user_id, 'ct_leads_pro_notice_dismiss', 'true', true);
        if (wp_get_referer()) {
            /* Redirects user to where they were before */
            wp_safe_redirect(wp_get_referer());
        } else {
            /* if there is no referrer you redirect to home */
            wp_safe_redirect(home_url());
        }
    }
}

add_action('admin_init', 'ct_rec_leads_pro_admin_notices_init');
add_action( 'admin_notices', 'ct_rec_leads_pro_admin_notices' );

/*-----------------------------------------------------------------------------------*/
/* Display admin notice for CT Lead Pro > Live Chat Extension
/*-----------------------------------------------------------------------------------*/

function ct_rec_leads_pro_live_chat_admin_notice() {

	global $current_user;
	$user_id = $current_user->ID;

	if(function_exists('ct_check_leads_pro_extensions') && ct_check_leads_pro_extensions('live-chat') != 'true') {
		if(!get_user_meta($user_id, 'ct_re7_leads_pro_live_chat_nag_ignore')) {
			
			$ct_leads_pro_live_chat_link = 'https://contempothemes.com/wp-real-estate-7/ct-leads-pro-plugin/#live-chat';

			echo '<div class="ct-notice updated notice is-dismissible">';
				echo '<div class="ct-notice-col-one">';
					echo '<img src="https://contempo-media.s3.amazonaws.com/root/uploads/2021/02/live-chat-tn.png" />';
				echo '</div>';
				echo '<div class="ct-notice-col-two">';
			        echo '<h3><strong>' . __('Level Up with Live Chat', 'contempo') . '</strong></h3>';
					echo '<p class="tagline">' . __('<em>Engage Your Leads in Real-time.</em>', 'contempo') . '</p>';
			        echo '<p>' . __('Speed to lead is paramount, don\'t make them wait for a follow-up, engage them with a live personal conversation, so you can capture, qualify, and convert in real-time, day or night.', 'contempo') . '</p>';
		        	echo '<p class="learn-more"><a class="button button-primary" href="' . esc_url($ct_leads_pro_live_chat_link) . '" target="_blank">' . __('Learn More', 'contempo') . '</a></p>';
			    echo '</div>';
				echo '<a class="ct-notice-dismiss dismiss-notice" href="' . site_url() . '/wp-admin/admin.php?ct_re7_leads_pro_live_chat_nag_ignore=0" target="_parent">' . __('Dismiss this notice', 'contempo') . '</a>';
			    	echo '<div class="clear"></div>';
		    echo '</div>';

		}

	}
}

// Set Dismiss Referer
function ct_rec_leads_pro_live_chat_admin_notice_init() {
    if ( isset($_GET['ct_re7_leads_pro_live_chat_nag_ignore']) && '0' == $_GET['ct_re7_leads_pro_live_chat_nag_ignore'] ) {
        $user_id = get_current_user_id();
        add_user_meta($user_id, 'ct_re7_leads_pro_live_chat_nag_ignore', 'true', true);
        if (wp_get_referer()) {
            // Redirects user to where they were before
            wp_safe_redirect(wp_get_referer());
        } else {
            // if there is no referrer you redirect to home
            wp_safe_redirect(home_url());
        }
    }
}

add_action('admin_init', 'ct_rec_leads_pro_live_chat_admin_notice_init');
add_action('admin_notices', 'ct_rec_leads_pro_live_chat_admin_notice');

/*-----------------------------------------------------------------------------------*/
/* Display admin notice for CT Lead Pro > SMS Extension
/*-----------------------------------------------------------------------------------*/

function ct_rec_leads_pro_sms_admin_notice() {

	global $current_user;
	$user_id = $current_user->ID;

	if(function_exists('ct_check_leads_pro_extensions') && ct_check_leads_pro_extensions('sms') != 'true') {
		if(!get_user_meta($user_id, 'ct_re7_leads_pro_sms_nag_ignore')) {
			
			$ct_leads_pro_sms_link = 'https://contempothemes.com/wp-real-estate-7/ct-leads-pro-plugin/#sms-messaging';

			echo '<div class="ct-notice updated notice is-dismissible">';
				echo '<div class="ct-notice-col-one">';
					echo '<img src="https://contempo-media.s3.amazonaws.com/root/uploads/2021/01/sms-tn.png" />';
				echo '</div>';
				echo '<div class="ct-notice-col-two">';
			        echo '<h3><strong>' . __('Go Direct with SMS Messaging', 'contempo') . '</strong></h3>';
					echo '<p class="tagline">' . __('<em>Don\'t let your messages get lost in someone\'s inbox.</em>', 'contempo') . '</p>';
			        echo '<p>' . __('Quickly send your leads messages or algorithm matched property shortlists via text, instead of email. ', 'contempo') . '</p>';
		        	echo '<p class="learn-more"><a class="button button-primary" href="' . esc_url($ct_leads_pro_sms_link) . '" target="_blank">' . __('Learn More', 'contempo') . '</a></p>';
			    echo '</div>';
				echo '<a class="ct-notice-dismiss dismiss-notice" href="' . site_url() . '/wp-admin/admin.php?ct_re7_leads_pro_sms_nag_ignore=0" target="_parent">' . __('Dismiss this notice', 'contempo') . '</a>';
			    	echo '<div class="clear"></div>';
		    echo '</div>';

		}

	}
}

// Set Dismiss Referer
function ct_rec_leads_pro_sms_admin_notice_init() {
    if ( isset($_GET['ct_re7_leads_pro_sms_nag_ignore']) && '0' == $_GET['ct_re7_leads_pro_sms_nag_ignore'] ) {
        $user_id = get_current_user_id();
        add_user_meta($user_id, 'ct_re7_leads_pro_sms_nag_ignore', 'true', true);
        if (wp_get_referer()) {
            // Redirects user to where they were before
            wp_safe_redirect(wp_get_referer());
        } else {
            // if there is no referrer you redirect to home
            wp_safe_redirect(home_url());
        }
    }
}

add_action('admin_init', 'ct_rec_leads_pro_sms_admin_notice_init');
add_action('admin_notices', 'ct_rec_leads_pro_sms_admin_notice');

/*-----------------------------------------------------------------------------------*/
/* Display admin notice for CT Leads Pro > Lead Routing Extension
/* Only when site has multiple agents or brokers
/*-----------------------------------------------------------------------------------*/

function ct_rec_lead_routing_admin_notice() {
	global $current_user;
	$user_id = $current_user->ID;
	$user_counts = count_users();

	$agents = isset( $user_counts['avail_roles']['agent'] ) ? esc_html( $user_counts['avail_roles']['agent'] ) : '';
	$brokers = isset( $user_counts['avail_roles']['broker'] ) ? esc_html( $user_counts['avail_roles']['broker'] ) : '';
	$agent_brokers_combined = '';

	if(is_numeric($agents) || is_numeric($brokers)) {
		if(!empty($agents) && !empty($brokers)) {
			$agent_brokers_combined = $agents + $brokers;
		} elseif(!empty($agents)) {
			$agent_brokers_combined = $agents;
		} elseif(!empty($brokers)) {
			$agent_brokers_combined = $brokers;
		}
	}
	
	if(function_exists('ct_check_leads_pro_extensions') && ct_check_leads_pro_extensions('leads_routing') != 'true') {
		if(!get_user_meta($user_id, 'ct_re7_lead_routing_nag_ignore') && $agent_brokers_combined >= 1) {

			$ct_leads_routing_link = 'http://contempothemes.com/wp-real-estate-7/ct-leads-pro-plugin#lead-routing';

			echo '<div class="ct-notice updated notice is-dismissible">';
				echo '<div class="ct-notice-col-one">';
					echo '<img src="https://contempo-media.s3.amazonaws.com/root/uploads/2020/11/leads-routing-admin-tn.png" />';
				echo '</div>';
				echo '<div class="ct-notice-col-two">';
			        echo '<h3><strong>' . __('Automate Your Teams Workflow with Lead Routing', 'contempo') . '</strong></h3>';
					echo '<p class="tagline">' . __('<em>Gain back your valuable time and let the system do the work.</em>', 'contempo') . '</p>';
			        echo '<p>' . __('Automatically assign incoming leads to team members by creating rules based on lead attributes, including location (city, state, or zip), price, and specific MLS numbers.', 'contempo') . '</p>';
		        	echo '<p class="learn-more"><a class="button button-primary" href="' . esc_url($ct_leads_routing_link) . '" target="_blank">' . __('Learn More', 'contempo') . '</a></p>';
			    echo '</div>';
				echo '<a class="ct-notice-dismiss dismiss-notice" href="' . site_url() . '/wp-admin/admin.php?ct_re7_lead_routing_nag_ignore=0" target="_parent">' . __('Dismiss this notice', 'contempo') . '</a>';
			    	echo '<div class="clear"></div>';
		    echo '</div>';

		}
	}
}

// Set Dismiss Referer
function ct_rec_lead_routing_admin_notices_init() {
    if ( isset($_GET['ct_re7_lead_routing_nag_ignore']) && '0' == $_GET['ct_re7_lead_routing_nag_ignore'] ) {
        $user_id = get_current_user_id();
        add_user_meta($user_id, 'ct_re7_lead_routing_nag_ignore', 'true', true);
        if (wp_get_referer()) {
            // Redirects user to where they were before
            wp_safe_redirect(wp_get_referer());
        } else {
            // if there is no referrer you redirect to home
            wp_safe_redirect(home_url());
        }
    }
}

add_action('admin_init', 'ct_rec_lead_routing_admin_notices_init');
add_action('admin_notices', 'ct_rec_lead_routing_admin_notice');

/*-----------------------------------------------------------------------------------*/
/* Admin CSS */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_rec_admin_css')) {
	function ct_rec_admin_css() {
		echo '<style>';
			echo '.ct-notice { display: flex; align-items: center;}';
				echo '.ct-notice-col-one { width: 20%; margin: 0 30px 5px 0; padding: 10px 0;}';
					echo '.ct-notice-col-one img { width: 100%; border-radius: 3px;}';
				echo '.ct-notice-col-two { width: 80%;}';
					echo '.ct-notice-col-two h3 { margin: 0 0 0.5em 0;}';
					echo '.ct-notice-col-two p { max-width: 70%;}';
					echo '.ct-notice-col-two p.tagline { margin-bottom: 1em; padding-top: 0 !important;}';
					echo '.ct-notice-col-two p.learn-more { margin-top: 1em;}';
				echo '.ct-notice-btn, .learn-more .button-primary { margin-right: 10px;}';
				echo '.ct-notice-dismiss { display: inline-block; position: absolute; float: right; opacity: 0.6; text-decoration: none; font-size: 10px; letter-spacing: 0.01em; line-height: 20px; height: 24px; bottom: 24px; right: 24px; background: #f7f7f7; border-radius: 3px; text-align: center; padding: 6px 12px;}';
			echo '@media only screen and (min-width: 2560px) and (max-width: 6016px) {';
				echo '.ct-notice-col-one { width: 10%;}';
				echo '.ct-notice-col-two { width: 90%;}';
					echo '.ct-notice-col-two p { max-width: 60%;}';
			echo '}';
			echo '@media only screen and (min-width: 1920px) and (max-width: 2559px) {';
				echo '.ct-notice-col-one { width: 12%;}';
				echo '.ct-notice-col-two { width: 85%;}';
					echo '.ct-notice-col-two p { max-width: 60%;}';
			echo '}';
			echo '@media only screen and (min-width: 1441px) and (max-width: 1919px) {';
				echo '.ct-notice-col-one { width: 17%;}';
				echo '.ct-notice-col-two { width: 83%;}';
					echo '.ct-notice-col-two p { max-width: 60%;}';
			echo '}';
			echo '@media only screen and (max-width: 1024px) {';
				echo '.ct-notice-col-one { width: 30%;}';
				echo '.ct-notice-col-two { width: 70%;}';
					echo '.ct-notice-col-two p { max-width: 90%;}';
			echo '}';
			echo '@media only screen and (max-width: 768px) {';
				echo '.ct-notice { display: flex; align-items: start;}';
					echo '.ct-notice-col-one { width: 15%;}';
					echo '.ct-notice-col-two { width: 85%;}';
						echo '.ct-notice-col-two p { max-width: 90%;}';
			echo '}';
			echo '@media only screen and (max-width: 767px) {';
				echo '.ct-notice { flex-wrap: wrap; justify-content: left; padding: 0 20px 20px 20px;}';
				echo '.ct-notice-col-one, .ct-notice-col-two { width: 100%; margin: 0; padding: 10px;}';
				echo '.ct-notice-btn { margin-top: 10px;}';
			echo '}';
		echo '</style>';
	}
}
add_action('admin_head', 'ct_rec_admin_css');

/*-----------------------------------------------------------------------------------*/
/* Load Metaboxes, CPT, Shortcodes, Taxonomies, Default Widgets, Elementor Widgets */
/*-----------------------------------------------------------------------------------*/

require plugin_dir_path( __FILE__ ) . 'ct-real-estate-cmb2-functions.php';

if(!function_exists( 'ct_metaboxes')) {
	require plugin_dir_path( __FILE__ ) . 'includes/metabox/metaboxes.php';
}

require plugin_dir_path( __FILE__ ) . 'includes/ct-real-estate-custom-post-types.php';
require plugin_dir_path( __FILE__ ) . 'includes/ct-real-estate-custom-shortcodes.php';
require plugin_dir_path( __FILE__ ) . 'includes/ct-real-estate-custom-taxonomies.php';

require plugin_dir_path( __FILE__ ) . 'includes/ct-social/ct-social.php';
require plugin_dir_path( __FILE__ ) . 'includes/ct-real-estate-custom-widgets.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-ct-real-estate-7-helper.php';
require plugin_dir_path( __FILE__ ) . 'includes/ct-real-estate-metaboxes.php';
require plugin_dir_path( __FILE__ ) . 'includes/ct-real-estate-front-end-attachments.php';

// Only load CT Elementor Widgets if plugin has been activated
if(!did_action('elementor/loaded')) {
	require plugin_dir_path( __FILE__ ) . 'ct-elementor-widgets/ct-elementor-widgets.php';
}

/*-----------------------------------------------------------------------------------*/
/* Get Modified Term List Slug */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('get_modified_term_list_slug')) {
	function get_modified_term_list_slug( $id = 0, $taxonomy = 'category', $before = '', $sep = '', $after = '', $exclude = array() ) {
	    $terms = get_the_terms( $id, $taxonomy );

	    if ( is_wp_error( $terms ) )
	        return $terms;

	    if ( empty( $terms ) )
	        return false;

	    foreach ( $terms as $term ) {

	        if(!in_array($term->slug,$exclude)) {
	            $link = get_term_link( $term, $taxonomy );
	            if ( is_wp_error( $link ) )
	                return $link;
	            $term_links[] = $term->slug . ' ';
	        }
	    }

	    if( !isset( $term_links ) )
	        return false;

	    return $before . join( $sep, $term_links ) . $after;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Get Modified Term List Name */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('get_modified_term_list_name')) {
	function get_modified_term_list_name( $id = 0, $taxonomy = 'category', $before = '', $sep = '', $after = '', $exclude = array() ) {
	    $terms = get_the_terms( $id, $taxonomy );

	    if ( is_wp_error( $terms ) )
	        return $terms;

	    if ( empty( $terms ) )
	        return false;

	    foreach ( $terms as $term ) {

	        if(!in_array($term->slug,$exclude)) {
	            $link = get_term_link( $term, $taxonomy );
	            if ( is_wp_error( $link ) )
	                return $link;
	            $term_links[] = $term->name . ' ';
	        }
	    }

	    if( !isset( $term_links ) )
	        return false;

	    return $before . join( $sep, $term_links ) . $after;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Return Listings ID for use in Elementor Single Listings Widgets */
/*-----------------------------------------------------------------------------------*/

function ct_return_elementor_id(){
    
    $latest_post = get_posts("post_type='listings'&numberposts=1&fields='ids'");
    $id = $latest_post[0]->ID;

    return $id;

}

function ct_return_listing_id_elementor($attributes){

	$listing_id = get_the_ID();

	if(isset( $attributes['is_elementor_edit']) && $attributes['is_elementor_edit'] == 1 ){
		$listing_id = ct_return_elementor_id();
	}

	return $listing_id;
}

/*-----------------------------------------------------------------------------------*/
/* Remove Gravatar from User Profile Admin Columns */
/*-----------------------------------------------------------------------------------*/

function ct_rec_remove_avatar_from_users_list($avatar) {
    if (is_admin()) {
        global $current_screen; 
        if ( $current_screen->base == 'users' ) {
            $avatar = '';
        }
    }
    return $avatar;
}
add_filter( 'get_avatar', 'ct_rec_remove_avatar_from_users_list' );

/*-----------------------------------------------------------------------------------*/
/* Add "Profile Image" to User Profile Admin Columns */
/*-----------------------------------------------------------------------------------*/

function ct_rec_add_user_profile_image_column($columns) {
    $columns['profile_img'] = 'Profile Image';
    return $columns;
}
add_filter('manage_users_columns', 'ct_rec_add_user_profile_image_column');
 
function ct_rec_show_user_profile_image_column_content($val, $column_name, $user_id) {
    $user = get_userdata( $user_id );
    $user_profile_image = get_the_author_meta('ct_profile_url', $user_id);
					                    		
	if(empty($user_profile_image)) {
      $user_profile_image = get_avatar_url($user_id);
    } elseif(empty($user_profile_image)) {
      $user_profile_image = get_template_directory_uri() . '/images/blank-user.png';
    }

    switch ($column_name) {
        case 'profile_img' :
            $val = '<img src="';
                $val .= $user_profile_image;
            $val .= '" height="50" width="50" />';
        default:
    }
    return $val;
}
add_action('manage_users_custom_column',  'ct_rec_show_user_profile_image_column_content', 10, 3);

/*-----------------------------------------------------------------------------------*/
/* Google reCAPTCHA v3 */
/*-----------------------------------------------------------------------------------*/

require_once plugin_dir_path( __FILE__ ) . '/includes/google-recaptcha-v3/class-google-recaptcha-v3.php';

new Contempo_RE_Custom_Posts\Google_Recaptcha_V3();
