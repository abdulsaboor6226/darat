<?php

/**
 * File: ajax-single-listing.php
 *
 * This file defines the handle for our single listings loaded via ajax.
 *
 * @package real-estate-7
 */

/**
 * Disable direct file access.
 */
if (!defined('ABSPATH')) {
	return;
}

add_action("wp_ajax_re7_load_single_listing", "re7_load_single_listing");

add_action("wp_ajax_nopriv_re7_load_single_listing", "re7_load_single_listing");

if (!function_exists('re7_load_single_listing')) {

	/**
	 * Handler for our listings single queried via ajax.
	 *
	 * @return void
	 */
	function re7_load_single_listing()
	{

		global $post;
		global $wp_query;

		// Check to see if ajax listing single is enabled.
		if (!ct_ajax_listing_single_enabled()) {
			wp_die(0);
		}

		$post_id = filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT);

		if (empty($post_id)) {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => __('There was an error fetching the post. Invalid Post ID.', 'contempo'),
					'html'    => ''
				)
			);
		}

		$args = array(
			'p'         => $post_id,
			'post_type' => 'any'
		);

		$listing_single = new WP_Query($args);

		if ($listing_single->have_posts()) {

			ob_start();

			$listing_single->the_post();

			$wp_query->post = $listing_single->post;

			$template = filter_input(INPUT_GET, 'template', FILTER_SANITIZE_STRING);

			if (!empty($template)) {

				$template_file = INC_PATH . sprintf('ajax-single-listing-template-%s.php', sanitize_file_name($template));

				if (file_exists($template_file)) {
					/**
					 * Allow developers to hook before template file include.
					 */
					do_action('re7_load_single_listing_before_template_require');

					/**
					 * Allow template files to be overwritten by plugin or child theme.
					 */
					require_once apply_filters('re7_load_single_listing_template_' . $template, $template_file);
				}
			}

			$result = array(
				'status'  => 'success',
				'message' => '',
				'html'    => ob_get_clean()
			);

			/**
			 * Allow modifications of result output.
			 */
			wp_send_json(apply_filters('re7_load_single_listing_result', $result, $result));
		}

		wp_reset_query();

		wp_die();
	}
}

add_action('wp_enqueue_scripts', 'ct_single_listing_scripts');

if (!function_exists('ct_single_listing_scripts')) {

	/**
	 * This function registers our custom stylesheets and custom javascripts.
	 *
	 * @return void.
	 */
	function ct_single_listing_scripts()
	{

		// Bail out if ajax listing single is disabled.
		if (!ct_ajax_listing_single_enabled()) {
			return;
		}

		global $ct_options;

		if (isset($_GET['search-listings']) || is_singular('listings') ) {

			global $ct_options;

			$dev_mode = false;

			$version = '1.6.4';

			if ($dev_mode) {
				// Randomize version to prevent local caching.
				$version = rand(1, 100);
			}

			wp_enqueue_style(
				'ct-single-listing-stylesheet',
				get_template_directory_uri() . '/css/ajax-single-listing-template.css',
				array(),
				$version
			);

			wp_enqueue_style('ctLightbox', get_template_directory_uri() . '/css/ct-lightbox.css', '', $version, 'screen, projection');

			wp_enqueue_script('ct-lightbox', get_template_directory_uri() . '/js/ct.lightbox.min.js', array('jquery'), $version, false);

			wp_enqueue_script('ct-listing-single-ajax', get_template_directory_uri() . '/js/ajax-listing-single.js', array('jquery'), $version, false);

			// Setting up few config objects.
			$ct_enable_zapier_webhooks = isset( $ct_options['ct_enable_zapier_webhooks'] ) ? $ct_options['ct_enable_zapier_webhooks'] : '';
			$ct_zapier_webhook_url = isset( $ct_options['ct_zapier_webhook_url'] ) ? $ct_options['ct_zapier_webhook_url'] : '';
			$ct_zapier_webhook_listing_single_form = isset( $ct_options['ct_zapier_webhook_listing_single_form'] ) ? $ct_options['ct_zapier_webhook_listing_single_form'] : '';

			if( $ct_enable_zapier_webhooks == 'yes' && $ct_zapier_webhook_url != '' && $ct_zapier_webhook_listing_single_form == true) {
				$ajax_submit_handler = sprintf('%s/includes/ajax-submit-listings-zapier.php', get_template_directory_uri());
			} else {
				$ajax_submit_handler = sprintf('%s/includes/ajax-submit-listings.php', get_template_directory_uri());
			}

			wp_localize_script('ct-listing-single-ajax', 'ajax_listing_single_config', array(
				'ajax_url'            => admin_url('admin-ajax.php'),
				'ajax_submit_handler' => $ajax_submit_handler,
				'ajax_submit_message' => str_replace(array(
					"\r\n",
					"\r",
					"\n"
				), " ", $ct_options['ct_contact_success'])
			));
		}
	}
}

add_action('wp_footer', 'ct_site_popstate');

if (!function_exists('ct_site_popstate')) {
	/**
	 * This function handles the popstate event for our ajax listing.
	 *
	 * @return void
	 */
	function ct_site_popstate()
	{
		// Bail out if ajax listing single is disabled.
		if (!ct_ajax_listing_single_enabled()) {
			return;
		}

		if (!isset($_GET['search-listings'])) {
?>
			<script>
				/**
				 * Reload the browser when back button is clicked.
				 * We need to reload the browser here since there is not "state".
				 */
				jQuery(document).ready(function($) {
					$(window).on("popstate", function(e) {
						location.reload();
					});
				});
			</script>
	<?php
		}
	}
}

add_action('wp_footer', 'ct_ajax_listing_single_template');

/**
 * This function constructs and outputs the HTML template of our modal.
 *
 * @return void
 */
function ct_ajax_listing_single_template()
{ ?>
	<?php
	// Bail out if ajax listing single is disabled.
	if (!ct_ajax_listing_single_enabled()) {
		return;
	}

	if ( isset( $_GET['search-listings'] ) || is_singular( 'listings' ) ) { ?>
        <div id="ct-listing-single-modal-template">
            <div id="ct-listing-single-modal" class="">
                <span id="single-listing-close-modal"><?php ct_close_svg(); ?></span>
                <div id="single-listing-ajax-wrap" class="single-listings">
                    <div id="single-listing-content-wrap">
                        <div id="single-listing-content" class="container">
                            <div id="single-listing-content-gallery" class="ajax-listing-column">
                                <div id="ajax-single-listing-gallery-outer-wrap">
                                    <!--backbutton-->
                                    <div id="ct-listing-back--button">
                                        <i class="fas fa-chevron-left"></i>
                                    </div>
                                    <!--flexslider-->
                                    <div id="ajax-listing-modal-flex"
                                         class="preloading ajax-listing-modal-flex flexslider">
                                        <div class="dummy"><i class="fa fa-circle-notch fa-spin fa-fw"></i></div>
                                        <ul id="ct-listing-single-modal-slides" class="slides"></ul>
                                    </div>
                                    <!--gallery here-->
                                    <figure id="ajax-single-listing-gallery" class="multi-image">
                                        <div id="ajax-single-listing-gallery-wrap">
                                            <ul>
                                                <li>
                                                    <a href="#preview">
                                                        <img width="600" height="370" id="listing-first-image" src=""
                                                             class="listings-slider-image">
                                                    </a>
                                                </li>
                                                <li>
                                                    <div class="dummy"><i class="fa fa-circle-notch fa-spin fa-fw"></i></div>
                                                </li>
                                                <li>
                                                    <div class="dummy"><i class="fa fa-circle-notch fa-spin fa-fw"></i></div>
                                                </li>
                                                <li>
                                                    <div class="dummy"><i class="fa fa-circle-notch fa-spin fa-fw"></i></div>
                                                </li>
                                                <li>
                                                    <div class="dummy"><i class="fa fa-circle-notch fa-spin fa-fw"></i></div>
                                                </li>
                                                <li>
                                                    <div class="dummy"><i class="fa fa-circle-notch fa-spin fa-fw"></i></div>
                                                </li>
                                            </ul>
                                        </div>
                                    </figure>
                                </div>
                            </div><!--.ajax-listing-column-->
                            <article id="single-listing-content-content" class="ajax-listing-column padR30 padB60 padL30">
                                <div id="ajax-single-listing-heading-wrap">
                                    <!--heading here-->
                                    <header class="listing-location">
                                        <div class="snipe-wrap" id="snipe-wrap-local">
                                        </div>
                                        <h1 id="listing-title" class="marT24 marB0"></h1>
                                        <p id="listing-location" class="location marB0"></p>
                                    </header>
                                </div>
                                <div id="ajax-single-listing-price-wrap">
                                    <!--price here-->
                                    <h4 id="listing-price-local" class="price marT0 marB0">

                                    </h4>
                                </div>
                                <div id="ajax-single-listing-chunk-1-wrap" class="ajax-single-listing-skeleton-ui">
									<?php require_once trailingslashit( get_template_directory() ) . '/includes/ajax-single-listing-template-preloader.php'; ?>
                                </div>
                                <div id="ajax-single-listing-chunk-2-wrap" class="ajax-single-listing-skeleton-ui-2">
		                            <?php require_once trailingslashit( get_template_directory() ) . '/includes/ajax-single-listing-template-preloader-2.php'; ?>
                                </div>
                            </article>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php } ?>
	<!-- Lead Carousel -->
<?php }

/**
 * Enable/disable ajax listing single feature.
 *
 * @return mixed|void True to enable. False to disable. Default set to True.
 */
function ct_ajax_listing_single_enabled()
{

	return apply_filters('ct_ajax_listing_single_enabled', __return_true());
}

/**
 * Reads the settings from RE7 Options > Listins > Listings Search > Enable Modal View.
 * Returns boolean boolean depending on the user's choice.
 *
 * @return bool Enables ajax listing modal on true, otherwise, false.
 */
function ct_ajax_re7_theme_options_listing_single()
{

	global $ct_options;

	$ct_enable_modal_view = "no";
	
	if ( isset( $ct_options['ct_enable_modal_view'] ) ) {
		$ct_enable_modal_view = $ct_options['ct_enable_modal_view'];
	}	
	if ("no" === $ct_enable_modal_view) {

		return false;
	}

	return true;
}

add_filter('ct_ajax_listing_single_enabled', 'ct_ajax_re7_theme_options_listing_single');
