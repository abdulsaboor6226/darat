<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       listing-themes.com
 * @since      1.0.0
 *
 * @package    Wpdirectorykit
 * @subpackage Wpdirectorykit/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpdirectorykit
 * @subpackage Wpdirectorykit/public
 * @author     listing-themes.com <dev@listing-themes.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class Wpdirectorykit_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpdirectorykit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpdirectorykit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		/* load tree dropdown */
		wp_register_style( 'wdk-listing-carousel', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-listing-carousel.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-listing-sliders-carousel', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-listing-sliders-carousel.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-listing-sliders-more-grid-images', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-listing-sliders-more-grid-images.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-listing-sliders-grid-images', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-listing-sliders-grid-images.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-listing-sliders', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-listing-sliders.css', array(), $this->version, 'all' );

		wp_register_style( 'wdk-suggestion', plugin_dir_url( __FILE__ ) . 'js/wdk_suggestion/wdk_suggestion.css', array(), '1.0.0' );
		wp_register_style( 'wdk-treefield', plugin_dir_url( __FILE__ ) . 'js/wdk_treefield/treefield.css', array(), '1.0.0' );
		wp_register_style( 'wdk-treefield-checkboxes', plugin_dir_url( __FILE__ ) . 'js/wdk_treefield_checkboxes/wdk_treefield_checkboxes.css', array(), '1.0.0' );

		wp_register_style( 'wdk-element-button', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-element-button.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-listing-agent-listings', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-listing-agent-listings.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-listing-agent', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-listing-agent.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-listings-list', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-listings-list.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-listing-fields-section', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-listing-fields-section.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-listing-slider', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-listing-slider.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-locations-carousel', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-locations-carousel.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-categories-carousel', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-categories-carousel.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-field-files', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-field-files.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-field-files-list', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-field-files-list.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-field-images', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-field-images.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-locations-grid', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-locations-grid.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-categories-grid-cover', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-categories-grid-cover.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-locations-grid-cover', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-locations-grid-cover.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-categories-grid', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-categories-grid.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-categories-list', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-categories-list.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-locations-list', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-locations-list.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-listings-map', WPDIRECTORYKIT_URL. 'elementor-elements/assets/css/widgets/wdk-map.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-notify', plugin_dir_url( __FILE__ ) . 'css/wdk-notify.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-modal', plugin_dir_url( __FILE__ ) . 'css/wdk-modal.css', array(), $this->version, 'all' );
		wp_register_style( 'wdk-hover', plugin_dir_url( __FILE__ ) . 'css/wdk-hover.css', array(), $this->version, 'all' );
		wp_register_style( 'leaflet', plugin_dir_url( __FILE__ ) . 'js/openstreetmap/leaflet.css', array(), '1.7.1', 'all' );
		wp_register_style( 'leaflet-cluster-def', plugin_dir_url( __FILE__ ) . 'js/openstreetmap/MarkerCluster.Default.css', array(), '1.7.1', 'all' );
		wp_register_style( 'leaflet-cluster', plugin_dir_url( __FILE__ ) . 'js/openstreetmap/MarkerCluster.css', array(), '1.7.1', 'all' );
		wp_register_style( 'ion.range-slider', plugin_dir_url( __FILE__ ) . 'js/ion.range-slider/css/ion.range-slider.min.css', array(), '2.3.1', 'all' );
		wp_register_style( 'wdk-slider-range', plugin_dir_url( __FILE__ ) . 'css/wdk-slider-range.css', array(), '1.0', 'all' );
		wp_register_style( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2/css/select2.min.css', array(), '4.0.13', 'all' );
		wp_register_style( 'wdk-treefield-dropdown', plugin_dir_url( __FILE__ ) . 'js/wdk_treefield_dropdown/wdk_treefield_dropdown.css', array(), '1.0', 'all' );
	
		wp_register_style('blueimp-gallery',  plugin_dir_url( __FILE__ ).'js/blueimp-gallery/css/blueimp-gallery.min.css', array(), '1.8', 'all');

		wp_register_style('slick',  plugin_dir_url( __FILE__ ).'js/slick/slick.css', array(), '1.8', 'all');
        wp_register_style('slick-theme',  plugin_dir_url( __FILE__ ).'js/slick/slick-theme.css', array(), '1.8', 'all');

        wp_register_style('jquery-confirm',  plugin_dir_url( __FILE__ ).'js/jquery-confirm/css/jquery-confirm.css', array(), '3.3.4', 'all');

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpdirectorykit-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-responsive', plugin_dir_url( __FILE__ ) . 'css/wpdirectorykit-public-responsive.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-conflicts', plugin_dir_url( __FILE__ ) . 'css/wpdirectorykit-public-conflicts.css', array(), $this->version, 'all' );
		
		if(is_rtl())
			wp_enqueue_style( $this->plugin_name.'-rtl', plugin_dir_url( __FILE__ ) . 'css/wpdirectorykit-public-rtl.css', array(), $this->version, 'all' );

	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpdirectorykit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpdirectorykit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( 'wdk-dependfields-submitform', plugin_dir_url( __FILE__ ) . 'js/wdk-dependfields-submitform.js', array( 'jquery' ), false, false );
		wp_register_script( 'wdk-dependfields-search', plugin_dir_url( __FILE__ ) . 'js/wdk-dependfields-search.js', array( 'jquery' ), false, false );

		/* load tree dropdown */
		wp_register_script( 'wdk-suggestion', plugin_dir_url( __FILE__ ) . 'js/wdk_suggestion/wdk_suggestion.js', array( 'jquery' ), false, false );
		wp_register_script( 'wdk-treefield', plugin_dir_url( __FILE__ ) . 'js/wdk_treefield/treefield.js', array( 'jquery' ), false, false );
		wp_register_script( 'wdk-treefield-checkboxes', plugin_dir_url( __FILE__ ) . 'js/wdk_treefield_checkboxes/wdk_treefield_checkboxes.js', array( 'jquery' ), false, false );
		
		wp_register_script( 'wdk-notify', plugin_dir_url( __FILE__ ) . 'js/wdk-notify.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'wdk-modal', plugin_dir_url( __FILE__ ) . 'js/wdk-modal.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'leaflet', plugin_dir_url( __FILE__ ) . 'js/openstreetmap/leaflet.js', array( 'jquery' ), '1.7.1', false );
		wp_register_script( 'leaflet-cluster', plugin_dir_url( __FILE__ ) . 'js/openstreetmap/leaflet.markercluster.js', array( 'jquery' ), '1.7.1', false );
		wp_register_script('blueimp-gallery', plugin_dir_url( __FILE__ ).'js/blueimp-gallery/js/blueimp-gallery.min.js', array( 'jquery' ), '3.4', false );
		wp_register_script('wdk-blueimp-gallery', plugin_dir_url( __FILE__ ).'js/wdk-blueimp-gallery.js', array( 'jquery' ), '1.0', false );
		wp_register_script('wdk-blueimp-slider', plugin_dir_url( __FILE__ ).'js/wdk-blueimp-slider.js', array( 'jquery' ), '1.0', false );
		wp_register_script('slick', plugin_dir_url( __FILE__ ).'js/slick/slick.min.js', array( 'jquery' ), '1.8', false );
		wp_register_script('ion.range-slider', plugin_dir_url( __FILE__ ).'js/ion.range-slider/js/ion.range-slider.min.js', array( 'jquery' ), '2.3.1', false );
		wp_register_script('wdk-slider-range', plugin_dir_url( __FILE__ ).'js/wdk-slider-range.js', array( 'jquery' ), '1.0', false );
		wp_register_script('select2', plugin_dir_url( __FILE__ ).'js/select2/js/select2.min.js', array( 'jquery' ), '4.0.13', false );
		wp_register_script('wdk-select2', plugin_dir_url( __FILE__ ).'js/wdk-select2.js', array( 'jquery' ), '4.0.13', false );

		
		$params = array(
            'ajax_url' => admin_url( 'admin-ajax.php' )
        );
		wp_register_script('wdk-treefield-dropdown', plugin_dir_url( __FILE__ ).'js/wdk_treefield_dropdown/wdk_treefield_dropdown.js', array( 'jquery' ), '1.0', false );
		wp_localize_script( 'wdk-treefield-dropdown', 'script_parameters', $params);

		wp_register_script('jquery-confirm', plugin_dir_url( __FILE__ ).'js/jquery-confirm/js/jquery-confirm.js', array( 'jquery' ), '3.3.4', false );

		$params = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'text' =>array(
				'price' => esc_html__('Price', 'wpdirectorykit'),
				'total_price' => esc_html__('Total', 'wpdirectorykit'),
				'loading' => esc_html__('Price loading...', 'wpdirectorykit'),
			),
        );
		wp_register_script('wdk-booking-calculator-price', plugin_dir_url( __FILE__ ).'js/wdk-booking-calculator-price.js', array( 'jquery' ), '1.0', false );
		wp_localize_script('wdk-booking-calculator-price', 'wdk_booking_script_parameters', $params);

		$params = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
			'format_date' => wdk_convert_date_format_js(get_option('date_format')),
			'format_datetime' => wdk_convert_date_format_js(get_option('date_format').' '.get_option('time_format')),
			'format_date_js' => wdk_convert_date_format_jquery(get_option('date_format')),
			'format_datetime_js' => wdk_convert_date_format_jquery(get_option('date_format').' '.get_option('time_format')),
        );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpdirectorykit-public.js', array( 'jquery' ), $this->version, false );
        wp_localize_script( $this->plugin_name, 'script_parameters', $params);

		wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css', array(), null );
	}

	
	public function ajax_public()
	{
		$page = '';
		$function = '';

		if(isset($_POST['page']))$page = sanitize_text_field($_POST['page']);
		if(isset($_POST['function']))$function = sanitize_text_field($_POST['function']);

		$WMVC = &wdk_get_instance();
		$WMVC->load_controller($page, $function, array());
	}

}
