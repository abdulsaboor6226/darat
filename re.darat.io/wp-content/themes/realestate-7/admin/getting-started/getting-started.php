<?php
/**
 * Getting Started
 *
 * @package WP Pro Real Estate 7
 * @subpackage Admin
 */

function ct_load_admin_scripts() {

	// Load styles only on our page
	global $pagenow;
	if( 'themes.php' != $pagenow )
		return;

	wp_enqueue_script( 'ct-getting-started', get_template_directory_uri() . '/admin/getting-started/getting-started.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'ct-getting-started-fitvid', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_style( 'ct-getting-started', get_template_directory_uri() . '/admin/getting-started/getting-started.css', false, '1.0.1' );
	add_thickbox();
}
add_action( 'admin_enqueue_scripts', 'ct_load_admin_scripts' );

class ct_getting_started_admin {

	protected $theme_slug = null;
	protected $version = null;
	protected $author = null;
	protected $strings = null;

	function __construct( $config = array(), $strings = array() ) {

		$config = wp_parse_args( $config, array(
			'remote_api_url' => '',
			'theme_slug' => get_template(),
			'api_slug' => get_template() . '-wordpress-theme',
			'item_name' => '',
			'license' => '',
			'version' => '',
			'author' => '',
			'download_id' => '',
			'renew_url' => ''
		) );

		// Set config arguments
		$this->item_name = $config['item_name'];
		$this->theme_slug = sanitize_key( $config['theme_slug'] );
		$this->version = $config['version'];
		$this->author = $config['author'];

		// Populate version fallback
		if ( '' == $config['version'] ) {
			$theme = wp_get_theme( $this->theme_slug );
			$this->version = $theme->get( 'Version' );
		}

		// Strings passed in from the updater config
		$this->strings = $strings;

		add_action( 'admin_menu', array( $this, 'ct_getting_started_menu' ) );

	}

	function ct_getting_started_menu() {

		$strings = $this->strings;

		add_theme_page(
			$strings['getting-started'],
			$strings['getting-started'],
			'manage_options',
			$this->theme_slug . '-getting-started',
			array( $this, 'ct_getting_started_page' )
		);
	}

	function ct_getting_started_page() {

		$strings = $this->strings;

		// Theme info
		$theme = wp_get_theme( 'realestate-7' );
		$theme_name_lower = get_template();
	?>


			<div class="wrap getting-started">
				<h2 class="notices"></h2>
				<div class="intro-wrap">
					<img class="theme-image" src="<?php echo get_template_directory_uri() . '/screenshot.png'; ?>" />
					<div class="intro">
						<h3><?php printf( __( 'Getting started with Real Estate 7 v%2$s', 'contempo' ), $theme['Name'], $theme['Version'] ); ?></h3>

						<h4><?php printf( __( 'Thanks for purchasing Real Estate 7! We truly appreciate the support and the opportunity to share our work with you. Please visit the tabs below to get started setting up your theme!', 'contempo' ) ); ?></h4>
					</div>
				</div>

				<div class="panels">
					<ul class="inline-list">
						<li class="current"><a id="help" href="#"><?php _e( 'Start Here', 'contempo' ); ?></a></li>
						<li><a id="plugins" href="#"><?php _e( 'Plugins', 'contempo' ); ?></a></li>
						<li><a id="support" href="#"><?php _e( 'FAQ &amp; Support', 'contempo' ); ?></a></li>
						<li><a id="updates" href="#"><?php _e( 'Latest Updates', 'contempo' ); ?></a></li>
					</ul>

					<div id="panel" class="panel">

						<!-- Help file panel -->
						<div id="help-panel" class="panel-left visible">

							<h3><?php printf( __( 'Installing %s', 'contempo' ), $theme['Name'] ); ?></h3>

							<!-- Installation Video -->
							<iframe width="560" height="315" src="https://www.youtube.com/embed/XVVA5lvCZFI" frameborder="0" allowfullscreen></iframe>
							<!-- //Installation Video -->

							<h3 class="marT25"><?php _e('Documentation', 'contempo'); ?></h3>

							<ul class="toc"><li><a href="https://contempothemes.com/docs/installation-setup-wizard/">Installation &amp; Setup Wizard</a></li><li><a href="https://contempothemes.com/docs/instructional-videos/">Instructional Videos</a></li><li><a href="https://contempothemes.com/docs/homepage-setup/">Homepage Setup</a></li><li><a href="https://contempothemes.com/docs/listings-advanced-search/">Listings Advanced Search</a></li><li><a href="https://contempothemes.com/docs/adding-managing-listings/">Adding &amp; Managing Listings</a></li><li><a href="https://contempothemes.com/docs/custom-taxonomies-and-usage/">Custom Taxonomies and Usage</a></li><li><a href="https://contempothemes.com/docs/building-out-your-navigation-find-a-home/">Building Out Your Navigation, “Find a Home”</a></li><li><a href="https://contempothemes.com/docs/using-mega-menus/">Using Mega Menus</a></li><li><a href="https://contempothemes.com/docs/setting-up-your-blog/">Setting Up Your Blog</a></li><li><a href="https://contempothemes.com/docs/blog-category-header-background-images/">Blog Category Header Images</a></li><li><a href="https://contempothemes.com/docs/uploading-blog-post-featured-images/">Uploading Blog Post Featured Images</a></li><li><a href="https://contempothemes.com/docs/agents/">Agents</a></li><li><a href="https://contempothemes.com/docs/brokerages/">Brokerages</a></li><li><a href="https://contempothemes.com/docs/estimated-payment-affordability-calculator/">Estimated Payments &amp; Affordability Calculator</a></li><li><a href="https://contempothemes.com/docs/open-houses/">Open Houses</a></li><li><a href="https://contempothemes.com/docs/co-listing/">Co-listing</a></li><li><a href="https://contempothemes.com/docs/sub-listings/">Sub Listings</a></li><li><a href="https://contempothemes.com/docs/ct-leads-pro-crm/">CT Leads Pro (CRM)</a></li><li><a href="https://contempothemes.com/docs/ct-idx-pro/">CT IDX Pro</a></li><li><a href="https://contempothemes.com/docs/idx-plugins-third-party/">IDX Plugins (Third-party)</a></li><li><a href="https://contempothemes.com/docs/zapier/">Zapier</a></li><li><a href="https://contempothemes.com/docs/social-login/">Social Login</a></li><li><a href="https://contempothemes.com/docs/elementor/">Elementor</a></li><li><a href="https://contempothemes.com/docs/google-maps/">Google Maps</a></li><li><a href="https://contempothemes.com/docs/google-recaptcha/">Google reCAPTCHA</a></li><li><a href="https://contempothemes.com/docs/listing-analytics/">Listing Analytics</a></li><li><a href="https://contempothemes.com/docs/compare-listings/">Compare Listings</a></li><li><a href="https://contempothemes.com/docs/saved-search-email-alerts/">Saved Search &amp; Email Alerts</a></li><li><a href="https://contempothemes.com/docs/favorite-listings/">Favorite Listings</a></li><li><a href="https://contempothemes.com/docs/listing-reviews/">Listing Reviews</a></li><li><a href="https://contempothemes.com/docs/walk-score/">Walk Score</a></li><li><a href="https://contempothemes.com/docs/setting-up-whats-nearby-for-listings/">Setting up “What’s Nearby?” for Listings</a></li><li><a href="https://contempothemes.com/docs/front-end-listing-system/">Front End Listing System</a></li><li><a href="https://contempothemes.com/docs/membership-packages/">Membership &amp; Packages</a></li><li><a href="https://contempothemes.com/docs/booking-calendar/">Booking Calendar</a></li><li><a href="https://contempothemes.com/docs/wpbakery-page-builder-aka-visual-composer/">WPBakery Page Builder (aka Visual Composer)</a></li><li><a href="https://contempothemes.com/docs/slider-revolution/">Slider Revolution</a></li><li><a href="https://contempothemes.com/docs/wp-all-import/">WP All Import</a></li><li><a href="https://contempothemes.com/docs/using-permalinks/">Using Permalinks</a></li><li><a href="https://contempothemes.com/docs/custom-page-templates/">Custom Page Templates</a></li><li><a href="https://contempothemes.com/docs/custom-widgets/">Custom Widgets</a></li><li><a href="https://contempothemes.com/docs/wpml/">WPML</a></li><li><a href="https://contempothemes.com/docs/translation/">Translation</a></li><li><a href="https://contempothemes.com/docs/css/">CSS</a></li><li><a href="https://contempothemes.com/docs/javascript/">JavaScript</a></li><li><a href="https://contempothemes.com/docs/child-themes/">Child Themes</a></li><li><a href="https://contempothemes.com/docs/advanced-development/">Advanced Development</a></li></ul>
						</div>

						<!-- Updates panel -->
						<div id="plugins-panel" class="panel-left">
							<h4><?php _e( 'Required & Recommended Plugins', 'contempo' ); ?></h4>

							<p><?php _e( 'Below is a list of required and recommended plugins to install that will help you get the most out of WP Pro Real Estate 7. To begin please use the button below or browse through the list to learn about each one in a little more detail.', 'contempo' ); ?></p>

							<p><a class="button button-primary" href="<?php echo admin_url('themes.php?page=tgmpa-install-plugins'); ?>">Install Plugins</a></p>

							<hr/>

							<h4><span class="snipe required"><?php _e('Required', 'contempo'); ?></span> <?php _e( 'Redux Framework', 'contempo' ); ?>
								<?php if ( ! class_exists('Redux') ) { ?>
									<a class="button button-secondary" href="<?php echo admin_url('themes.php?page=tgmpa-install-plugins'); ?>" title="<?php esc_attr_e( 'Install Redux Framework', 'contempo' ); ?>"><i class="fa fa-download"></i> <?php _e( 'Install Now', 'contempo' ); ?></a>
								<?php } else { ?>
									<span class="button button-secondary disabled"><i class="fa fa-check"></i> <?php _e( 'Installed', 'contempo' ); ?></span>
								<?php } ?>
							</h4>

							<p><?php _e( 'This plugin is used for the admin options framework.', 'contempo' ); ?></p>

							<hr/>

							<h4><span class="snipe required"><?php _e('Required', 'contempo'); ?></span> <?php _e( 'Contempo Real Estate Custom Posts', 'contempo' ); ?>
								<?php if ( ! function_exists('ct_recp_load_textdomain') ) { ?>
									<a class="button button-secondary" href="<?php echo admin_url('themes.php?page=tgmpa-install-plugins'); ?>" title="<?php esc_attr_e( 'Install Contempo Real Estate Custom Posts', 'contempo' ); ?>"><i class="fa fa-download"></i> <?php _e( 'Install Now', 'contempo' ); ?></a>
								<?php } else { ?>
									<span class="button button-secondary disabled"><i class="fa fa-check"></i> <?php _e( 'Installed', 'contempo' ); ?></span>
								<?php } ?>
							</h4>

							<p><?php _e( 'This plugin registers the listings, brokerages & testimonials custom post types, along with related custom fields & taxonomies, as well as all the custom Elementor modules.', 'contempo' ); ?></p>

							<hr/>

							<h4><span class="snipe recommended"><?php _e('Recommended', 'contempo'); ?></span> <?php _e( 'Contempo Saved Searches & Email Alerts', 'contempo' ); ?>
								<?php if ( ! function_exists('ctea_load_textdomain') ) { ?>
									<a class="button button-secondary" href="<?php echo admin_url('themes.php?page=tgmpa-install-plugins'); ?>" title="<?php esc_attr_e( 'Install Contempo Saved Searches & Email Alerts', 'contempo' ); ?>"><i class="fa fa-download"></i> <?php _e( 'Install Now', 'contempo' ); ?></a>
								<?php } else { ?>
									<span class="button button-secondary disabled"><i class="fa fa-check"></i> <?php _e( 'Installed', 'contempo' ); ?></span>
								<?php } ?>
							</h4>

							<p><?php _e( 'This plugin allows users be alerted via email when a listing is added.', 'contempo' ); ?></p>

							<hr/>

							<h4><span class="snipe recommended"><?php _e('Recommended', 'contempo'); ?></span> <?php _e( 'Contempo Compare Listings', 'contempo' ); ?>
								<?php if ( ! class_exists('Redq_Alike') ) { ?>
									<a class="button button-secondary" href="<?php echo admin_url('themes.php?page=tgmpa-install-plugins'); ?>" title="<?php esc_attr_e( 'Install Contempo Compare Listings', 'contempo' ); ?>"><i class="fa fa-download"></i> <?php _e( 'Install Now', 'contempo' ); ?></a>
								<?php } else { ?>
									<span class="button button-secondary disabled"><i class="fa fa-check"></i> <?php _e( 'Installed', 'contempo' ); ?></span>
								<?php } ?>
							</h4>

							<p><?php _e( 'This plugin adds compare functionality for listings.', 'contempo' ); ?></p>

							<hr/>

							<h4><span class="snipe recommended"><?php _e('Recommended', 'contempo'); ?></span> <?php _e( 'Contempo Favorite Listings', 'contempo' ); ?>
								<?php if ( ! function_exists('ct_fp_favorite_posts') ) { ?>
									<a class="button button-secondary" href="<?php echo admin_url('themes.php?page=tgmpa-install-plugins'); ?>" title="<?php esc_attr_e( 'Install Contempo Favorite Listings', 'contempo' ); ?>"><i class="fa fa-download"></i> <?php _e( 'Install Now', 'contempo' ); ?></a>
								<?php } else { ?>
									<span class="button button-secondary disabled"><i class="fa fa-check"></i> <?php _e( 'Installed', 'contempo' ); ?></span>
								<?php } ?>
							</h4>

							<p><?php _e( 'This plugin allows users to favorite & save listings from the front end of the site.', 'contempo' ); ?></p>

							<hr/>

							<h4><span class="snipe recommended"><?php _e('Recommended', 'contempo'); ?></span> <?php _e( 'WPML', 'contempo' ); ?>
								<?php if ( ! class_exists('SitePress') ) { ?>
									<a class="button button-secondary" href="https://wpml.org/?aid=9098&affiliate_key=lzBo0CYyVMbn" target="_blank" title="<?php esc_attr_e( 'Install WPML', 'contempo' ); ?>"><i class="fa fa-download"></i> <?php _e( 'Install Now', 'contempo' ); ?></a>
								<?php } else { ?>
									<span class="button button-secondary disabled"><i class="fa fa-check"></i> <?php _e( 'Installed', 'contempo' ); ?></span>
								<?php } ?>
							</h4>

							<p><?php
								$ct_wpml_url = 'https://wpml.org/?aid=9098&affiliate_key=lzBo0CYyVMbn';
								printf( __( '<a href="%s" target="_blank">WPML</a> allows running fully multilingual websites with WordPress, making it easy to translate WordPress pages, posts, tags, categories and themes.', 'contempo' ), esc_url( $ct_wpml_url ) );
							?></p>

							<hr/>

							<h4><span class="snipe recommended"><?php _e('Recommended', 'contempo'); ?></span> <?php _e( 'Miniorange Social Login', 'contempo' ); ?>
								<?php if ( ! function_exists('mo_openid_initialize_social_login') ) { ?>
									<a class="button button-secondary" href="<?php echo admin_url('themes.php?page=tgmpa-install-plugins'); ?>" title="<?php esc_attr_e( 'Install Miniorange Social Login', 'contempo' ); ?>"><i class="fa fa-download"></i> <?php _e( 'Install Now', 'contempo' ); ?></a>
								<?php } else { ?>
									<span class="button button-secondary disabled"><i class="fa fa-check"></i> <?php _e( 'Installed', 'contempo' ); ?></span>
								<?php } ?>
							</h4>

							<p><?php _e( 'This plugin allows users to login/register for your site via social networks like Facebook, Google, Twitter, etc&hellip;', 'contempo' ); ?></p>

							<hr/>

							<h4><span class="snipe recommended"><?php _e('Recommended', 'contempo'); ?></span> <?php _e( 'Co-Authors Plus', 'contempo' ); ?>
								<?php if ( ! class_exists('CoAuthors_Plus') ) { ?>
									<a class="button button-secondary" href="<?php echo admin_url('themes.php?page=tgmpa-install-plugins'); ?>" title="<?php esc_attr_e( 'Install Co-Authors Plus', 'contempo' ); ?>"><i class="fa fa-download"></i> <?php _e( 'Install Now', 'contempo' ); ?></a>
								<?php } else { ?>
									<span class="button button-secondary disabled"><i class="fa fa-check"></i> <?php _e( 'Installed', 'contempo' ); ?></span>
								<?php } ?>
							</h4>

							<p><?php _e( 'This plugin allows you to assign multiple users or agents to listings, perfect for co-listings.', 'contempo' ); ?></p>

							<hr/>
							
						</div><!-- .panel-left -->

						<!-- Support panel -->
						<div id="support-panel" class="panel-left">

							<h3><?php printf( __( 'Support for %s?', 'contempo' ), $theme['Name'] ); ?></h3>

							<p><?php
								$ct_documentation_url = 'https://contempothemes.com/wp-real-estate-7/docs/';
								$ct_knowledge_base_url = 'https://contempothemes.com/wp-real-estate-7/knowledge-base/';
								$ct_support_url = 'https://contempothemes.com/docs/how-do-i-get-support/';
								printf( __( 'If you\'ve read through the <a href="%1$s" target="_blank">documentation</a> and <a href="%2$s" target="_blank">knowledge base</a> and still have questions or are experiencing issues, we\'re happy to help! Simply <a href="%3$s" target="_blank" title="Open a live chat">open a live chat</a> we\'ll personally get back to you, thanks in advance for your patience.', 'contempo' ), esc_url( $ct_documentation_url ), esc_url( $ct_knowledge_base_url ), esc_url( $ct_support_url ) );
							?></p>

							<h3><?php printf( __( 'FAQ for %s?', 'contempo' ), $theme['Name'] ); ?></h3>

							<p class="marB3"><?php
								_e( 'The following articles below cover many frequently asked questions that you may have.', 'contempo' );
							?></p>

							<ul class="toc">
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/how-do-i-update-the-theme/" target="_blank">How do I update the theme?</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/setup-wizard-finished-but-the-demo-isnt-complete/" target="_blank">Setup wizard finished but the demo isn’t complete?</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/skipped-setup-wizard-how-do-i-get-back/" target="_blank">Skipped setup wizard how do I get back?</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/google-maps-arent-working/" target="_blank">Google Maps aren’t working?</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/listing-markers-are-disappearing-when-using-map-pan-zoom-or-draw/" target="_blank">Listing markers are disappearing when using map pan, zoom or draw?</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/mobile-menu-isnt-showing/" target="_blank">Mobile menu isn’t showing?</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/im-being-redirected-back-home-when-trying-to-search-listings/" target="_blank">I’m being redirected back home when trying to search listings?</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/front-end-registration-isnt-working/" target="_blank">Front end registration isn’t working?</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/im-getting-a-404-page-error/" target="_blank">I’m getting a 404 page error?</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/missing-strings-in-language-file/" target="_blank">Missing strings in language file?</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/adding-extra-status-flags-in-real-estate-7/" target="_blank">Adding extra status flags in Real Estate 7</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/running-ct-idx-pro-and-seeing-blank-white-pages-on-site-or-errors-in-admin/" target="_blank">Running CT IDX Pro and seeing blank white pages on site or errors in admin</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/im-seeing-a-flash-of-unstyled-content-when-using-elementor/" target="_blank">I’m seeing a flash of unstyled content when using Elementor?</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/a-quick-guide-on-migrating-to-re7-from-one-of-our-previous-or-other-providers-real-estate-themes/" target="_blank">Quick guide on migrating to Real Estate 7 from one of our previous or other providers real estate themes</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/override-the-parent-theme-footer-sidebar-area-in-child-theme/" target="_blank">Override the parent theme footer sidebar area in child theme</a></li>
								<li><a href="https://contempothemes.com/wp-real-estate-7/docs/using-wpml-to-translate-the-featured-status/" target="_blank">Using WPML to translate the “Featured” status</a></li>
							</ul>

						</div><!-- .panel-left support -->

						<!-- Updates panel -->
						<div id="updates-panel" class="panel-left">
							<?php
								$wp_filesystem = new WP_Filesystem_Direct(null);
								$ct_changelog_contents = $wp_filesystem->get_contents(dirname( __FILE__ ) . '/../ReduxFramework/theme-changelog/index.html');
								echo ct_sanitize_output( $ct_changelog_contents );
							?>
						</div><!-- .panel-left updates -->

						<div class="panel-right">
							<!-- Modifications -->
							<div class="panel-aside">
								
								<h4><?php _e( 'Need Modifications or Customizations?', 'contempo' ); ?></h4>

								<p>
									<?php
										$ct_setup_customizations = 'https://contempothemes.com/real-estate-7-pricing/real-estate-website-setup-and-customizations/';
										printf( __( 'Please see <a href="%s">Setup & Customizations Pricing</a>, we’re available for basic & full site setups, design, maintenance, customizations & development, all direct — never outsourced.', 'contempo' ), esc_url( $ct_setup_customizations ) );
									?>
								</p>

							</div><!-- .panel-aside modifications -->

							<!-- Hosting -->
							<div class="panel-aside">
								
								<h4><?php _e( 'Hosting Recommendations', 'contempo' ); ?></h4>

								<p>We recommend getting <a href="https://www.cloudways.com/en/?id=128386" target="_blank">Cloudways</a> its excellent, easily scalable if needed, very fast, the same platform our demos and IDX Hosted plans are on, free migration and great customer support. You can spin up a 2GB server from them starting at $22/month and we also have a coupon code for 20% off your first 2 months use "CONTEMPO20" on checkout.</p>

							</div><!-- .panel-aside modifications -->

						</div><!-- .panel-right -->
					</div><!-- .panel -->
				</div><!-- .panels -->
			</div><!-- .getting-started -->

		<?php
	}

}