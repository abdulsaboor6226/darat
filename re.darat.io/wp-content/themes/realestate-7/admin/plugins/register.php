<?php

/**
 * @package    TGM-Plugin-Activation
 * @version    2.6.1
 */

require_once get_template_directory() . '/admin/plugins/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'ct_register_required_plugins');

if(!function_exists('ct_register_required_plugins')) {
	function ct_register_required_plugins() {
		$updater = CT_Theme_Updater_Admin::getInstance();
		$item_id = $updater->get_license_item_id();
		$exclusives_available = in_array($item_id, [9797, 2867]);

		if($exclusives_available) {

			$plugins = array(

				array(
					'name' 		=> __('Redux Framework', 'contempo'),
					'slug' 		=> 'redux-framework',
					'required' 	=> true,
				),

				array(
					'name'     				=> __('Contempo Real Estate Core', 'contempo'),
					'slug'     				=> 'ct-real-estate-core',
					'source'   				=> 'https://contempothemes.com/?edd_action=download&item_id=182173',
					'required' 				=> true,
					'version' 				=> '3.4.2',
					'force_activation' 		=> false,
					'force_deactivation' 	=> false,
					'external_url' 			=> '',
				),

				array(
					'name' 		=> __('Elementor Page Builder', 'contempo'),
					'slug' 		=> 'elementor',
					'required' 	=> true,
				),

				array(
					'name' 		=> __('miniOrange Social Login', 'contempo'),
					'slug' 		=> 'miniorange-login-openid',
					'required' 	=> false,
				),

				array(
					'name' 		=> __('Co-Authors Plus', 'contempo'),
					'slug' 		=> 'co-authors-plus',
					'required' 	=> false,
				),

				array(
					'name' 		=> __('Contact Form 7', 'contempo'),
					'slug' 		=> 'contact-form-7',
					'required' 	=> false,
				),

				array(
					'name'     				=> __('Contempo Leads Pro', 'contempo'),
					'slug'     				=> 'ct-leads-pro',
					'source'   				=> 'https://contempothemes.com/?edd_action=download&item_id=6726',
					'required' 				=> false,
					'version' 				=> '1.0.23',
					'force_activation' 		=> false,
					'force_deactivation' 	=> false,
					'external_url' 			=> '',
				),

				array(
					'name'     				=> __('Contempo Listing Analytics', 'contempo'),
					'slug'     				=> 'ct-listing-analytics',
					'source'   				=> 'https://s3-us-west-2.amazonaws.com/re7-demo-files/ct-listing-analytics.zip',
					'required' 				=> false,
					'version' 				=> '1.2.8',
					'force_activation' 		=> false,
					'force_deactivation' 	=> false,
					'external_url' 			=> '',
				),

				array(
					'name'     				=> __('Contempo Favorite Listings', 'contempo'),
					'slug'     				=> 'ct-favorite-listings',
					'source'   				=> 'https://s3-us-west-2.amazonaws.com/re7-demo-files/ct-favorite-listings.zip',
					'required' 				=> false,
					'version' 				=> '2.0.6',
					'force_activation' 		=> false,
					'force_deactivation' 	=> false,
					'external_url' 			=> '',
				),

				array(
					'name'     				=> __('Contempo Compare Listings', 'contempo'),
					'slug'     				=> 'ct-compare-listings',
					'source'   				=> 'https://s3-us-west-2.amazonaws.com/re7-demo-files/ct-compare-listings.zip',
					'required' 				=> false,
					'version' 				=> '1.0.8',
					'force_activation' 		=> false,
					'force_deactivation' 	=> false,
					'external_url' 			=> '',
				),

				array(
					'name'     				=> __('Contempo Saved Searches & Email Alerts', 'contempo'),
					'slug'     				=> 'contempo-saved-searches-email-alerts',
					'source'   				=> 'https://s3-us-west-2.amazonaws.com/re7-demo-files/contempo-saved-searches-email-alerts.zip',
					'required' 				=> false,
					'version' 				=> '1.1.1',
					'force_activation' 		=> false,
					'force_deactivation' 	=> false,
					'external_url' 			=> '',
				),

				array(
					'name'     				=> __('Contempo SMS Alerts', 'contempo'),
					'slug'     				=> 'ct-sms-alerts',
					'source'   				=> 'https://contempothemes.com/?edd_action=download&item_id=185931',
					'required' 				=> false,
					'version' 				=> '1.0.2',
					'force_activation' 		=> false,
					'force_deactivation' 	=> false,
					'external_url' 			=> '',
				),

			);

		} else {

			$plugins = array(

				array(
					'name' 		=> __('Redux Framework', 'contempo'),
					'slug' 		=> 'redux-framework',
					'required' 	=> true,
				),

				array(
					'name'     				=> __('Contempo Real Estate Core', 'contempo'),
					'slug'     				=> 'ct-real-estate-core',
					'source'   				=> 'https://contempothemes.com/?edd_action=download&item_id=182173',
					'required' 				=> true,
					'version' 				=> '3.4.2',
					'force_activation' 		=> false,
					'force_deactivation' 	=> false,
					'external_url' 			=> '',
				),

				array(
					'name' 		=> __('Elementor Page Builder', 'contempo'),
					'slug' 		=> 'elementor',
					'required' 	=> true,
				),

				array(
					'name' 		=> __('miniOrange Social Login', 'contempo'),
					'slug' 		=> 'miniorange-login-openid',
					'required' 	=> false,
				),

				array(
					'name' 		=> __('Co-Authors Plus', 'contempo'),
					'slug' 		=> 'co-authors-plus',
					'required' 	=> false,
				),

				array(
					'name' 		=> __('Contact Form 7', 'contempo'),
					'slug' 		=> 'contact-form-7',
					'required' 	=> false,
				),

				array(
					'name'     				=> __('Contempo Favorite Listings', 'contempo'),
					'slug'     				=> 'ct-favorite-listings',
					'source'   				=> 'https://s3-us-west-2.amazonaws.com/re7-demo-files/ct-favorite-listings.zip',
					'required' 				=> false,
					'version' 				=> '2.0.6',
					'force_activation' 		=> false,
					'force_deactivation' 	=> false,
					'external_url' 			=> '',
				),

				array(
					'name'     				=> __('Contempo Compare Listings', 'contempo'),
					'slug'     				=> 'ct-compare-listings',
					'source'   				=> 'https://s3-us-west-2.amazonaws.com/re7-demo-files/ct-compare-listings.zip',
					'required' 				=> false,
					'version' 				=> '1.0.8',
					'force_activation' 		=> false,
					'force_deactivation' 	=> false,
					'external_url' 			=> '',
				),

				array(
					'name'     				=> __('Contempo Saved Searches & Email Alerts', 'contempo'),
					'slug'     				=> 'contempo-saved-searches-email-alerts',
					'source'   				=> 'https://s3-us-west-2.amazonaws.com/re7-demo-files/contempo-saved-searches-email-alerts.zip',
					'required' 				=> false,
					'version' 				=> '1.1.1',
					'force_activation' 		=> false,
					'force_deactivation' 	=> false,
					'external_url' 			=> '',
				),

			);

		}
		
		/*-----------------------------------------------------------------------------------*/
		/* Configuration settings */
		/*-----------------------------------------------------------------------------------*/

		$config = array(
			'id'           => 'realestate-7',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
		);

		tgmpa($plugins, $config);
	}
}
