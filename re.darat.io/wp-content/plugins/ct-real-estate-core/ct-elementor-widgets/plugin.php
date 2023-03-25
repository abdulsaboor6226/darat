<?php
namespace CT_Elementor_Widgets;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
		//wp_register_script( 'elementor-hello-world', plugins_url( '/assets/js/hello-world.js', __FILE__ ), [ 'jquery' ], false, true );
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once( __DIR__ . '/widgets/ct-listings-search.php' );
		require_once( __DIR__ . '/widgets/ct-listings-grid.php' );
		require_once( __DIR__ . '/widgets/ct-listings-list.php' );
		require_once( __DIR__ . '/widgets/ct-listings-minimal-grid.php' );
		require_once( __DIR__ . '/widgets/ct-listings-carousel.php' );
		require_once( __DIR__ . '/widgets/ct-listings-table.php' );
		require_once( __DIR__ . '/widgets/ct-listings-map.php' );
		require_once( __DIR__ . '/widgets/ct-three-item-grid.php' );
		require_once( __DIR__ . '/widgets/ct-four-item-grid.php' );
		require_once( __DIR__ . '/widgets/ct-modern-four-item-grid.php' );
		require_once( __DIR__ . '/widgets/ct-six-item-grid.php' );
		require_once( __DIR__ . '/widgets/ct-modern-item-grid.php' );
		require_once( __DIR__ . '/widgets/ct-agent.php' );
		require_once( __DIR__ . '/widgets/ct-city-links.php' );
		
		if(function_exists('elementor_pro_load_plugin')) {
			// Header
			require_once( __DIR__ . '/widgets/ct-login-register-user-drop.php' );
			require_once( __DIR__ . '/widgets/ct-header-listings-search.php' );

			// Single Listings
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-breadcrumbs.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-status.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-street-address.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-city-state-zipcode.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-alternate-title.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-price.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-estimated-payment.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-property-type.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-map-button.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-save-button.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-featured-image.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-lead-media.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-property-info.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-content.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-features.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-idx-info.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-open-house.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-floor-plans.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-attachments.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-energy-efficiency.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-video.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-virtual-tour.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-whats-nearby.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-map.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-agent.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-brokerage.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-contact.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-affordability-calculator.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-sub-listings.php' );
			require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-tools.php' );

			if(class_exists('ctIdxPro')) {
				require_once( __DIR__ . '/widgets/listings-single/ct-listings-single-idx-mls-compliance.php' );
			}
		}
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Search() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Grid() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_List() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Minimal_Grid() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Carousel() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Table() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Map() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Agent() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_City_Links() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Three_Item_Grid() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Four_Item_Grid() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Modern_Four_Item_Grid() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Six_Item_Grid() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Modern_Item_Grid() );
		
		if(function_exists('elementor_pro_load_plugin')) {
			// Header
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Login_Register_User_Drop() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Header_Listings_Search() );

			// Listings Single
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Breadcrumbs() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Status() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Street_Address() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_City_State_Zipcode() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Alternate_Title() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Price() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Estimated_Payment() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Property_Type() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Map_Button() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Save_Button() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Featured_Image() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Lead_Media() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Property_Info() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Content() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Features() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_IDX_Info() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Open_House() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Floor_Plans() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Attachments() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Energy_Efficiency() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Video() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Virtual_Tour() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Whats_Nearby() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Map() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Agent() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Brokerage() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Contact() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Affordability_Calculator() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Sub_Listings() );
			\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_Tools() );

			if(class_exists('ctIdxPro')) {
				\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\CT_Listings_Single_IDX_MLS_Compliance() );
			}
		}
	}

	/**
	 * Cleanup Options and transients after plugin deactivation
	 */
	public function doDeactivationCleanup() {
		delete_option( 'contempo-real-estate-custom-posts::version' );
		delete_transient( 'contempo-real-estate-custom-posts::updated' );
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Register widget scripts
		//add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

	}

}

// Instantiate Plugin Class
Plugin::instance();
