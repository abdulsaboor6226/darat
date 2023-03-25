<?php
/**
 * Merlin WP configuration file.
 *
 * @package @@pkg.name
 * @version @@pkg.version
 * @author  @@pkg.author
 * @license @@pkg.license
 */

if ( ! class_exists( 'Merlin' ) ) {
	return;
}

/**
 * Set directory locations, text strings, and other settings for Merlin WP.
 */
$wizard = new Merlin(
	// Configure Merlin with custom settings.
	$config = array(
		'directory'					=> 'admin/merlin', // Location where the 'merlin' directory is placed.
		'merlin_url'				=> 'merlin', // Customize the page URL where Merlin WP loads.
		'child_action_btn_url'		=> 'https://contempothemes.com/wp-real-estate-7/docs/#childthemes',  // The URL for the 'child-action-link'.
		'dev_mode'					=> false, // Enable development mode for testing.
		'license_step'       		=> true, // EDD license activation step.
		'license_help_url'			=> 'https://contempothemes.com/wp-real-estate-7/docs/installation-demo-import/#4-toc-title',
		'license_required'     		=> true, // Require the license activation step.
		'edd_remote_api_url'   		=> 'https://contempothemes.com/wp-real-estate-7/', 
		'edd_item_name'        		=> 'Real Estate 7', // EDD_Theme_Updater_Admin item_name.
		'edd_theme_slug'       		=> 'realestate-7', // EDD_Theme_Updater_Admin item_slug.
	),
	// Text strings.
	$strings = array(
		'admin-menu'               => esc_html__( 'Theme Setup' , '@@textdomain' ),
		'title%s%s%s%s' 		   => esc_html__( '%s%s Themes &lsaquo; Theme Setup: %s%s' , '@@textdomain' ),
		'return-to-dashboard'      => esc_html__( 'Return to the dashboard' , '@@textdomain' ),
		'ignore'                   => esc_html__( 'Disable this wizard', '@@textdomain' ),

		'btn-skip'                 => esc_html__( 'Skip' , '@@textdomain' ),
		'btn-next'                 => esc_html__( 'Next' , '@@textdomain' ),
		'btn-start'                => esc_html__( 'Start' , '@@textdomain' ),
		'btn-no'                   => esc_html__( 'Cancel' , '@@textdomain' ),
		'btn-plugins-install'      => esc_html__( 'Install' , '@@textdomain' ),
		'btn-child-install'        => esc_html__( 'Install' , '@@textdomain' ),
		'btn-content-install'      => esc_html__( 'Install' , '@@textdomain' ),
		'btn-import'               => esc_html__( 'Import' , '@@textdomain' ),

		'license-header%s'         => esc_html__( 'Activate %s', 'contempo' ),
		/* translators: Theme Name */
		'license-header-success%s' => esc_html__( '%s is Activated', 'contempo' ),
		/* translators: Theme Name */
		'license%s'                => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'contempo' ),
		'license-label'            => esc_html__( 'License key', 'contempo' ),
		'license-success%s'        => esc_html__( 'The theme is already registered, so you can go to the next step!', 'contempo' ),
		'license-json-success%s'   => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'contempo' ),
		'license-tooltip'          => esc_html__( 'Need help?', 'contempo' ),
		'btn-license-activate'     => esc_html__( 'Activate', 'contempo' ),
		'btn-license-skip'         => esc_html__( 'Later', 'contempo' ),

		'welcome-header%s'         => esc_html__( 'Welcome to %s' , '@@textdomain' ),
		'welcome-header-success%s' => esc_html__( 'Hi. Welcome back' , '@@textdomain' ),
		'welcome%s'                => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.' , '@@textdomain' ),
		'welcome-success%s'        => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.' , '@@textdomain' ),

		'child-header'             => esc_html__( 'Install Child Theme' , '@@textdomain' ),
		'child-header-success'     => esc_html__( 'You\'re good to go!' , '@@textdomain' ),
		'child'                    => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.' , '@@textdomain' ),
		'child-success%s'          => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.' , '@@textdomain' ),
		'child-action-link'        => esc_html__( 'Learn about child themes' , '@@textdomain' ),
		'child-json-success%s'     => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.' , '@@textdomain' ),
		'child-json-already%s'     => esc_html__( 'Awesome. Your child theme has been created and is now activated.' , '@@textdomain' ),

		'plugins-header'           => esc_html__( 'Install Plugins' , '@@textdomain' ),
		'plugins-header-success'   => esc_html__( 'You\'re up to speed!' , '@@textdomain' ),
		'plugins'                  => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.' , '@@textdomain' ),
		'plugins-success%s'        => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.' , '@@textdomain' ),
		'plugins-action-link'      => esc_html__( 'Advanced' , '@@textdomain' ),

		'import-header'            => esc_html__( 'Import Content' , '@@textdomain' ),
		'import'                   => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme. Keep in mind this could take a little bit depending on your server setup, so let it run and do its thing.' , '@@textdomain' ),
		'import-action-link'       => esc_html__( 'Advanced' , '@@textdomain' ),

		'ready-header'             => esc_html__( 'All done. Have fun!' , '@@textdomain' ),
		'ready%s'                  => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.' , '@@textdomain' ),
		'ready-action-link'        => esc_html__( 'Extras' , '@@textdomain' ),
		'ready-big-button'         => esc_html__( 'View your website' , '@@textdomain' ),
		'ready-link-1'             => wp_kses( sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://contempothemes.com/docs/', esc_html__( 'Get Theme Support', '@@textdomain' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
		'ready-link-2'             => wp_kses( sprintf( '<a href="'.admin_url( 'admin.php?page=WPProRealEstate7Child&tab=1' ).'">%s</a>', esc_html__( 'Start Customizing', '@@textdomain' ) ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
		'ready-link-3'				=> '',
	)
);

function ct_is_import_request() {
	$is_merlin_admin_page = isset($_REQUEST['page']) && $_REQUEST['page'] == 'merlin' && isset($_REQUEST['step']) && in_array($_REQUEST['step'], ['content', 'ready']);

	$is_merlin_ajax_request = isset($_REQUEST['action']) && in_array($_REQUEST['action'], ['merlin_content', 'merlin_get_total_content_import_items', 'merlin_import_finished', 'merlin_update_selected_import_data_info']);

	return $is_merlin_admin_page || $is_merlin_ajax_request;
}

/**
 * Define the demo import files (remote files).
 *
 * To define imports, you just have to add the following code structure,
 * with your own values to your theme (using the 'merlin_import_files' filter).
 */
function merlin_import_files() {
	$exclusives_available = ct_is_import_request() ? ct_exclusives_available() : false;

	return array_filter(array(
		// 0
		$exclusives_available ? array(
			'import_file_name'           => 'Elementor 1',
			'import_file_url'            => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-one-content.xml',
			'import_widget_file_url'     => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-one-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-one-admin-options.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-1-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor1.contempothemes.com/',
		) : null,
		// 1
		$exclusives_available ? array(
			'import_file_name'           => 'Elementor 2',
			'import_file_url'            => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/elementor-two-content.xml',
			'import_widget_file_url'     => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/elementor-two-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/redux-options-elementor-two-demo.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-2-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor2.contempothemes.com/',
		) : null,
		// 2
		array(
			'import_file_name'           => 'Elementor 3',
			'import_file_url'            => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/elementor-three-content.xml',
			'import_widget_file_url'     => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/elementor-three-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/redux-options-elementor-three-demo.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-3-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor5.contempothemes.com/',
		),
		// 3
		array(
			'import_file_name'           => 'Elementor 4',
			'import_file_url'            => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/elementor-four-content.xml',
			'import_widget_file_url'     => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/elementor-four-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/elementor-demo-4-options.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-4-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor4.contempothemes.com/',
		),
		// 4
		array(
			'import_file_name'           => 'Elementor 5',
			'import_file_url'            => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/elementor-five-content.xml',
			'import_widget_file_url'     => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/elementor-five-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://s3-us-west-2.amazonaws.com/re7-demo-files/elementor-five-demo-admin-options.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-5-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor5.contempothemes.com/',
		),
		
		// 5
		array(
			'import_file_name'           => 'Elementor 6',
			'import_file_url'            => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-six-content.xml',
			'import_widget_file_url'     => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-six-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-six-admin-options.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-6-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor6.contempothemes.com/',
		),
		
		// 6
		array(
			'import_file_name'           => 'Elementor 7',
			'import_file_url'            => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-seven-content.xml',
			'import_widget_file_url'     => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-seven-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-seven-admin-options.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-7-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor7.contempothemes.com/',
		),
		
		// 7
		array(
			'import_file_name'           => 'Elementor 8',
			'import_file_url'            => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-eight-content.xml',
			'import_widget_file_url'     => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-eight-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-eight-admin-options.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-8-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor8.contempothemes.com/',
		),

		// 8
		$exclusives_available ? array(
			'import_file_name'           => 'Elementor 9',
			'import_file_url'            => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-nine-content.xml',
			'import_widget_file_url'     => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-nine-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/redux-options-elementor-nine-demo.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-9-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor9.contempothemes.com/',
		) : null,

		// 9
		$exclusives_available ? array(
			'import_file_name'           => 'Elementor 10',
			'import_file_url'            => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-ten-content.xml',
			'import_widget_file_url'     => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-ten-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/redux-options-elementor-ten-demo.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-10-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor10.contempothemes.com/',
		) : null,

		// 10
		$exclusives_available ? array(
			'import_file_name'           => 'Elementor 11',
			'import_file_url'            => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-eleven-content.xml',
			'import_widget_file_url'     => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-eleven-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/redux-options-elementor-eleven-demo.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-11-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor11.contempothemes.com/',
		) : null,

		// 11
		$exclusives_available ? array(
			'import_file_name'           => 'Elementor 12',
			'import_file_url'            => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-twelve-content.xml',
			'import_widget_file_url'     => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/elementor-twelve-widgets.json',
			//'import_rev_slider_file_url' => '',
			//'import_customizer_file_url' => 'http://www.your_domain.com/merlin/customizer.dat',
			'import_redux'               => array(
				array(
					'file_url'    => 'https://re7-demo-files.s3-us-west-2.amazonaws.com/redux-options-elementor-twelve-demo.json',
					'option_name' => 'ct_options',
				),
			),
			//'import_preview_image_url'   => 'https://contempothemes.com/wp-real-estate-7/elementor-demo/wp-content/plugins/aqua-style-switcher/images/screenshots/elementor-11-screenshot.jpg',
			//'import_notice'              => __( 'A special note for this import.', 'contempo' ),
			//'preview_url'                => 'https://elementor11.contempothemes.com/',
		) : null,

	), function ($value) {
		return $value;
	});
}
add_filter( 'merlin_import_files', 'merlin_import_files' );

function merlin_after_import_setup( $selected_index ) {

	// Assign menus to their locations
	$main_menu = get_term_by( 'name', 'Primary', 'nav_menu' );
	$footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
			'primary_left' => $main_menu->term_id,
			'primary_right' => $main_menu->term_id,
			'primary_full_width' => $main_menu->term_id,
			'mobile' => $main_menu->term_id,
			'footer' => $footer_menu->term_id,
		)
	);

	// Assign "Home" to front page
	$front_page_id = get_page_by_title( 'Home' );
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );

    // Deactivate & Delete Plugins For Demos 2-8
    if ( 0 === $selected_index || 1 === $selected_index || 2 === $selected_index || 3 === $selected_index || 4 === $selected_index || 5 === $selected_index || 6 === $selected_index || 7 === $selected_index || 8 === $selected_index ) {
    	deactivate_plugins('booking/wpdev-booking.php');
    	deactivate_plugins('ct-mortgage-calculator/ct-mortgage-calculator.php');
    	deactivate_plugins('comments-ratings/comments-ratings.php');
    	deactivate_plugins('ct-child-theme/ct-child-theme.php');
    	deactivate_plugins('ct-membership-packages/ct-membership-packages.php');
    	deactivate_plugins('ct-real-estate-7-payment-gateways/ct-real-estate-7-payment-gateways.php');
    	deactivate_plugins('js_composer/js_composer.php');
    	deactivate_plugins('revslider/revslider.php');

    	delete_plugins(array('booking/wpdev-booking.php','ct-mortgage-calculator/ct-mortgage-calculator.php','comments-ratings/comments-ratings.php','ct-child-theme/ct-child-theme.php','ct-membership-packages/ct-membership-packages.php','ct-real-estate-7-payment-gateways/ct-real-estate-7-payment-gateways.php','js_composer/js_composer.php','revslider/revslider.php'));
    }

}
add_action( 'merlin_after_all_import', 'merlin_after_import_setup' );

function ct_merlin_unset_default_widgets_args( $widget_areas ) {
	$widget_areas = array(
		'listings-single-right' => array(),
	);
	return $widget_areas;
}
add_filter( 'merlin_unset_default_widgets_args', 'ct_merlin_unset_default_widgets_args' );
