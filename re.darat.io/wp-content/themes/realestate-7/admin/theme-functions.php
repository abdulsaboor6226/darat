<?php
/**
 * Theme Functions
 *
 * @package WP Pro Real Estate 7
 * @subpackage Admin
 */

global $wpdb, $ct_options;

function custom_author_archive( &$query ) {
    if($query->is_author) {
    	$query->set( 'post_type', array( 'listings' ) );
    }
}
add_action( 'pre_get_posts', 'custom_author_archive' );

function ct_remove_elementor_activation_redirect() {
	delete_transient( 'elementor_activation_redirect' );
}
add_action('init', 'ct_remove_elementor_activation_redirect');

/*-----------------------------------------------------------------------------------*/
/* Redirect to Setup Wizard on theme activation */
/*-----------------------------------------------------------------------------------*/

if(is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'])) {

	$ct_merlin_completed_one = get_option( 'merlin_wpthemesrealestate_completed' );
	$ct_merlin_completed_two = get_option( 'merlin_realestate_completed' );

	if(empty($ct_merlin_completed_one) || empty($ct_merlin_completed_two)) {
		wp_redirect(admin_url('themes.php?page=merlin'));
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display admin notice when RE7 Library is updated
/*-----------------------------------------------------------------------------------*/

function ct_re7_library_update_admin_notices() {
	global $current_user;

	$user_id = $current_user->ID;

	$updater = CT_Theme_Updater_Admin::getInstance();
	$item_id = $updater->get_license_item_id();
	$exclusives_available = in_array($item_id, [9797, 2867]);

	if(!get_user_meta($user_id, 'ct_re7_library_update_nag_ignore') && $exclusives_available) {

		echo '<div class="updated notice is-dismissible">';
	        echo '<p><strong>' . __('Real Estate 7 Library', 'contempo') . '</strong>' . __(' — 40+ All-New Blocks added, including 2 new lead gen landing pages! <a href="https://contempothemes.com/changelog/" target="_blank">View Changelog</a>', 'contempo');
	        echo '<a class="ct-notice-dismiss ct-notice-dismiss-inline dismiss-notice" href="' . site_url() . '/wp-admin/admin.php?ct_re7_library_update_nag_ignore=0" target="_parent">' . __(' Dismiss this notice', 'contempo') . '</a></p>';
	    echo '</div>';

	}
}

// Set Dismiss Referer
function ct_re7_library_update_admin_notices_init() {
    if(isset($_GET['ct_re7_library_update_nag_ignore']) && '0' == $_GET['ct_re7_library_update_nag_ignore']) {
        $user_id = get_current_user_id();
        add_user_meta($user_id, 'ct_re7_library_update_nag_ignore', 'true', true);
        if(wp_get_referer()) {
            /* Redirects user to where they were before */
            wp_safe_redirect(wp_get_referer());
        } else {
            /* if there is no referrer you redirect to home */
            wp_safe_redirect(home_url());
        }
    }
}

//add_action('admin_init', 'ct_re7_library_update_admin_notices_init');
//add_action( 'admin_notices', 'ct_re7_library_update_admin_notices', 0 );

/*-----------------------------------------------------------------------------------*/
/* Display admin notice for core plugin changeover
/*-----------------------------------------------------------------------------------*/

function ct_re7_plugin_changeover_admin_notices() {
	global $current_user;

	$user_id = $current_user->ID;

	if ( is_plugin_active('ct-real-estate-core/ct-real-estate-core.php') ) {
		delete_plugins( array('contempo-real-estate-custom-posts/ct-real-estate-custom-posts.php') ); 
	} else {

		if(!get_user_meta($user_id, 'ct_re7_plugin_changeover_update_nag_ignore') ) {

			$ct_plugin_update_link = admin_url('themes.php?page=tgmpa-install-plugins');

			echo '<div class="error notice is-dismissible" style="padding: 25px 20px; background: #fcf9e8; border-left-color: #dba617;">';
		        echo '<p>';
		        	printf(__('<strong>IMPORTANT:</strong> "Contempo Real Estate Custom Posts" has been replaced by "Contempo Real Estate Core". Install and activate new plugin <a href="%s"><strong>here</strong></a>.', 'contempo'), admin_url('themes.php?page=tgmpa-install-plugins'));
		    echo '</div>';

		}
	}
}

// Set Dismiss Referer
function ct_re7_plugin_changeover_admin_notices_init() {
    if(isset($_GET['ct_re7_library_update_nag_ignore']) && '0' == $_GET['ct_re7_library_update_nag_ignore']) {
        $user_id = get_current_user_id();
        add_user_meta($user_id, 'ct_re7_library_update_nag_ignore', 'true', true);
        if(wp_get_referer()) {
            /* Redirects user to where they were before */
            wp_safe_redirect(wp_get_referer());
        } else {
            /* if there is no referrer you redirect to home */
            wp_safe_redirect(home_url());
        }
    }
}

add_action('admin_init', 'ct_re7_plugin_changeover_admin_notices_init');
add_action( 'admin_notices', 'ct_re7_plugin_changeover_admin_notices', -101 );

/*-----------------------------------------------------------------------------------*/
/* Display admin notice when Contempo Compare Listings plugin is activated
/*-----------------------------------------------------------------------------------*/

if (class_exists('Redq_Alike')) {
	// Display Notice
	function ct_compare_admin_notices() {
		global $current_user;

		$user_id = $current_user->ID;

		if(!get_user_meta($user_id, 'ct_re7_compare_nag_ignore')) {

			$ct_re7_compare_doc_link = 'http://contempothemes.com/wp-real-estate-7/documentation/#compare';

			echo '<div class="updated notice is-dismissible">';
		        echo '<h3><strong>' . __('Contempo Compare Listings Needs to be Setup', 'contempo') . '</strong></h3>';
		        echo '<p>' . __('Just takes a few seconds to setup the plugin properly please see the ', 'contempo') . '<a href="' . esc_url($ct_re7_compare_doc_link) . '" target="_blank">' . __('documentation', 'contempo') . '</a>, if you\'ve already done this just dismiss the notice below.</p>';
		        echo '<p><strong><a class="dismiss-notice" href="' . site_url() . '/wp-admin/admin.php?ct_re7_compare_nag_ignore=0" target="_parent">' . __('Dismiss this notice', 'contempo') . '</a></strong></p>';
		    echo '</div>';

		}
	}

	// Set Dismiss Referer
	function ct_compare_admin_notices_init() {
	    if( isset($_GET['ct_re7_compare_nag_ignore']) && '0' == $_GET['ct_re7_compare_nag_ignore'] ) {
	        $user_id = get_current_user_id();
	        add_user_meta($user_id, 'ct_re7_compare_nag_ignore', 'true', true);
	        if(wp_get_referer()) {
	            /* Redirects user to where they were before */
	            wp_safe_redirect(wp_get_referer());
	        } else {
	            /* if there is no referrer you redirect to home */
	            wp_safe_redirect(home_url());
	        }
	    }
	}

	add_action('admin_init', 'ct_compare_admin_notices_init');
	add_action( 'admin_notices', 'ct_compare_admin_notices' );
}

/*-----------------------------------------------------------------------------------*/
/* Display admin notice when WP Social Login plugin is activated
/*-----------------------------------------------------------------------------------*/

if(function_exists('wsl_activate')) {
	// Display Notice
	function ct_social_login_admin_notices() {
		global $current_user;

		$user_id = $current_user->ID;

		if(!get_user_meta($user_id, 'ct_re7_social_login_nag_ignore')) {

			$ct_re7_compare_doc_link = 'http://contempothemes.com/wp-real-estate-7/documentation/#sociallogin';

			echo '<div class="updated notice is-dismissible">';
		        echo '<h3><strong>' . __('Social Login Needs to be Setup', 'contempo') . '</strong></h3>';
		        echo '<p>' . __('Just takes a few minutes to setup the miniOrange Social Login plugin properly please see the ', 'contempo') . '<a href="' . esc_url($ct_re7_compare_doc_link) . '" target="_blank">' . __('documentation', 'contempo') . '</a>, if you\'ve already done this just dismiss the notice below.</p>';
		        echo '<p><strong><a class="dismiss-notice" href="' . site_url() . '/wp-admin/admin.php?ct_re7_social_login_nag_ignore=0" target="_parent">' . __('Dismiss this notice', 'contempo') . '</a></strong></p>';
		    echo '</div>';

		}
	}

	// Set Dismiss Referer
	function ct_social_login_admin_notices_init() {
	    if( isset($_GET['ct_re7_social_login_nag_ignore']) && '0' == $_GET['ct_re7_social_login_nag_ignore'] ) {
	        $user_id = get_current_user_id();
	        add_user_meta($user_id, 'ct_re7_social_login_nag_ignore', 'true', true);
	        if(wp_get_referer()) {
	            /* Redirects user to where they were before */
	            wp_safe_redirect(wp_get_referer());
	        } else {
	            /* if there is no referrer you redirect to home */
	            wp_safe_redirect(home_url());
	        }
	    }
	}

	add_action('admin_init', 'ct_social_login_admin_notices_init');
	add_action( 'admin_notices', 'ct_social_login_admin_notices' );
}

/*-----------------------------------------------------------------------------------*/
/* Admin CSS */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_admin_css')) {
	function ct_admin_css() {
		echo '<style>';
			echo '.ct-notice-dismiss-inline { position: relative; top: -4px; right: auto; height: 20px; line-height: 16px; float: right;}';
			echo '.redux-message.redux-notice { display: none !important;}';
			echo '.theme-browser .theme.wrap-importer .theme-actions, .theme-browser .theme.active.wrap-importer .theme-actions { bottom: -24px !important; top: inherit !important;}';
			echo '.package-status { display: block; padding: 6px 10px; color: #fff; font-size: 10px; border-radius: 3px; text-transform: uppercase; text-align: center; background: #29333d;}';
			echo '#package-active { background: #7faf1b;}';
			echo '#package-limit-reached { background: #bc0000;}';
			echo '#package-expired { background: #bc0000;}';
			echo '#atlt_strings_model .modal-content { margin: 0 !important;}';
			echo '.ct-note { width: 410px; margin: 0 0 60px 0; padding: 15% 20px; text-align: center; border: 1px solid #efefef; border-radius: 3px; background: #f9f9ed;}';
			echo '.ct-note-sm { margin: 0 0 7px 0; padding: 1.15%; font-size: 12px; line-height: 1.15em;}';
			echo '.ct-user-img-wrap { position: relative; width: 100px;}';
            echo '.ct-user-img-wrap img { border: 1px solid #dfdfdf; border-radius: 3px; background: #fff;}';
            echo '.ct-btn-trash { all: unset; position: absolute; right: 5px; bottom: 5px; width: 18px; height: 18px; line-height: 18px; padding: 3px; background: #bc0000; z-index: 2; border-radius: 3px; text-align: center;}';
            echo '.ct-btn-trash { bottom: 10px;}';
            echo '.ct-btn-trash:hover { cursor: pointer;}';
            echo '.ct-btn-trash svg { height: 12px; width: 12px; fill: #fff;}';
			echo '@media only screen and (max-width: 767px) {';
				echo '.ct-notice-dismiss-inline { float: none; top: 0; margin-top: 10px;}';
			echo '}';
		echo '</style>';
	}
}
add_action('admin_head', 'ct_admin_css');

/*-----------------------------------------------------------------------------------*/
/* Body IDs */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_body_id')) {
	function ct_body_id() {
		if (is_home() || is_front_page()) {
			echo ' id="home"';
		} elseif (is_single()) {
			echo ' id="single"';
		} elseif (is_page()) {
			echo ' id="page"';
		} elseif (is_search()) {
			echo ' id="search"';
		} elseif (is_archive()) {
			echo ' id="archive"';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Body Classes */
/*-----------------------------------------------------------------------------------*/

function ct_body_classes($classes) {
	global $ct_options;

	$url = 'http://' . ct_get_server_info('SERVER_NAME') . ct_get_server_info('REQUEST_URI');
	$ct_boxed = isset( $ct_options['ct_boxed'] ) ? esc_attr( $ct_options['ct_boxed'] ) : '';
	$ct_listing_single_layout = isset( $ct_options['ct_listing_single_layout'] ) ? esc_html( $ct_options['ct_listing_single_layout'] ) : '';
	$ct_search_results_layout = isset( $ct_options['ct_search_results_layout'] ) ? esc_html( $ct_options['ct_search_results_layout'] ) : '';

 	// Listing Single Layout
    if(is_singular('listings')) {
        $classes[] = $ct_listing_single_layout;
    }

    if(!empty($ct_boxed)) {
    	$classes[] = $ct_boxed;
    }

    if(!empty($ct_search_results_layout)) {
    	$classes[] = $ct_search_results_layout;
    }

    // Listings Search
	if(strpos($url,'search-listings=true') !== false) {
	    $classes[] = 'search-listings';
	}

    return $classes;
}
add_filter( 'body_class','ct_body_classes' );

/*-----------------------------------------------------------------------------------*/
/* Add Automatic Feed Links */
/*-----------------------------------------------------------------------------------*/

add_theme_support( 'automatic-feed-links' );

/*-----------------------------------------------------------------------------------*/
/* Add Title Tag Support */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_title_tag')) {
	function ct_title_tag() {
	   add_theme_support( 'title-tag' );
	}
}
add_action( 'after_setup_theme', 'ct_title_tag' );

/*-----------------------------------------------------------------------------------*/
/* Disabled Widget Block Editor for the time being */
/* Waiting for better support in future WordPress core update */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_disable_block_widgets')) {
	function ct_disable_block_widgets() {
	   remove_theme_support( 'widgets-block-editor' );
	}
}
add_action( 'after_setup_theme', 'ct_disable_block_widgets' );

/*-----------------------------------------------------------------------------------*/
/* Add Editor Stylesheet Support */
/*-----------------------------------------------------------------------------------*/

if(function_exists('add_editor_style')) {
	add_editor_style();
}

/*-----------------------------------------------------------------------------------*/
/* Add Post Thumbnail Support */
/*-----------------------------------------------------------------------------------*/

add_theme_support('post-thumbnails');

/*-----------------------------------------------------------------------------------*/
/* Set Content Width */
/*-----------------------------------------------------------------------------------*/

if(!isset($content_width)) {
	$content_width = 1100;
}

/*-----------------------------------------------------------------------------------*/
/* Remove Default Image Sizes */
/*-----------------------------------------------------------------------------------*/

function ct_remove_default_images( $sizes ) {
	unset($sizes['small']); // 150px
	unset($sizes['medium']); // 300px
	unset($sizes['large']); // 1024px
	unset($sizes['medium_large']); // 768px
	return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'ct_remove_default_images' );
add_filter( 'wp_lazy_loading_enabled', '__return_false' );

/*-----------------------------------------------------------------------------------*/
/* Add Image Sizes */
/*-----------------------------------------------------------------------------------*/

add_image_size('listings-featured-image', 818, 540, true);
add_image_size('listings-slider-image', 1200, 1000, true);

/* Add Custom Sizes to Attachment Display Settings */
if(!function_exists('ct_custom_image_sizes')) {
	function ct_custom_image_sizes( $sizes ) {
	    return array_merge( $sizes, array(
	        'listings-featured-image' => __('Listing Large', 'contempo'),
	    ) );
	}
}
add_filter( 'image_size_names_choose', 'ct_custom_image_sizes' );

/*-----------------------------------------------------------------------------------*/
/* Add WordPress 3.0 Menu Support */
/*-----------------------------------------------------------------------------------*/

if(function_exists('register_nav_menu')) {
	register_nav_menus( array( 'primary_left' => __( 'Primary Left Menu', 'contempo' ) ) );
	register_nav_menus( array( 'primary_right' => __( 'Primary Right Menu', 'contempo' ) ) );
	register_nav_menus( array( 'primary_full_width' => __( 'Primary Full Width', 'contempo' ) ) );
	register_nav_menus( array( 'mobile' => __( 'Mobile', 'contempo' ) ) );
	register_nav_menus( array( 'footer' => __( 'Footer Menu', 'contempo' ) ) );
}

/*-----------------------------------------------------------------------------------*/
/* Enqueue Admin Scripts */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_enqueue_admin_scripts')) {
	function ct_enqueue_admin_scripts() {
		wp_enqueue_script('megaMenu', get_template_directory_uri() . '/js/ct.megamenu.js', '', '1.0.1', true);
	}
	add_action('admin_enqueue_scripts', 'ct_enqueue_admin_scripts');
}

/*-----------------------------------------------------------------------------------*/
/* Custom Nav Fallback */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_nav_fallback')) {
	function ct_nav_fallback() {
		$ct_admin_url = admin_url();
		if(!has_nav_menu( 'primary_left' ) || !has_nav_menu( 'primary_right' || !has_nav_menu('primary_full_width')) ) {
			echo '<nav class="right">';
				echo '<ul id="ct-menu" class="ct-menu">';
					echo '<li><a href="' . esc_url($ct_admin_url) . 'nav-menus.php">' . __('Menu doesn\'t exist, please create one by clicking here.', 'contempo') . '</a></li>';
				echo '</ul>';
			echo '</nav>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Mobile Nav */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_nav_mobile')) {
	function ct_nav_mobile() {
		echo '<nav class="left">';
	    	wp_nav_menu (
	    		array(
					'menu'            => "mobile",
					'menu_id'         => "ct-menu",
					'menu_class'      => "ct-menu",
					'echo'            => true,
					'container'       => '',
					'container_class' => '',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'container_id'    => 'nav-left',
					'theme_location'  => 'mobile',
					'fallback_cb'	  => false,
					'walker'          => new CT_Menu_Class_Walker
				)
			);
	    echo '</nav>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Mega Menu Walker */
/*-----------------------------------------------------------------------------------*/

class CT_Menu_Class_Walker extends Walker_Nav_Menu {
	/**
	 * Start the element output.
	 *
	 * @param  string $output Passed by reference. Used to append additional content.
	 * @param  object $item   Menu item data object.
	 * @param  int $depth     Depth of menu item. May be used for padding.
	 * @param  array $args    Additional strings.
	 *
	 * @return void
	 */

	function start_el(&$output, $item, $depth=0, $args =array(), $id = 0) {
		if(!is_object( $args )) {
			return ;
		}

		global $first_item_counter;

		if(!isset($first_item_counter) ) {$first_item_counter = 0;}

		$classes     = empty ( $item->classes ) ? array () : (array) $item->classes;

		$class_names = join(
			' '
		,   apply_filters(
				'nav_menu_css_class'
			,   array_filter( $classes ), $item
			)
		);

		// find multi column class name and find the column count
		$re = '/(multicolumn-)+(\d)/U';
		$matches  = preg_grep ($re, $classes);

		$column_count = isset( $matches ) && is_array( $matches ) && count( $matches ) > 0 ? explode("-", reset( $matches ) ) : array(1=>0);
		$column_count = is_array( $column_count ) ? $column_count[1] : $column_count;

		if( $depth == 0 ){
			$class_names = ( 0 < $column_count ) ? $class_names.' multicolumn ': $class_names;
		}

		$sub_title = esc_attr( $item->description );
		$title = apply_filters( 'the_title', $item->title, $item->ID );


		//add class name to li if item has description
		$class_names .= ! empty( $sub_title ) ? " has-sub-title" : "";

		//find if an icon used as class name - remove from li - use for a
		if(!empty ( $class_names )){

			if( strpos( $class_names, "icon-" ) !== false ) {

				$new_class_names = "";
				$icon_name = "";

				foreach (explode(" ", $class_names) as $value) {
					if ( strpos(  $value, "icon-" ) === false ) {
						$new_class_names .= " ". $value ;
					}else{
						$icon_name = $value;
					}
				}

				$class_names = ' class="'. esc_attr( $new_class_names ) . '"';

			} else {
				$class_names = ' class="'. esc_attr( $class_names ) . '"';
			}
		}

		$output .= "<li id='menu-item-$item->ID' $class_names data-depth='$depth' data-column-size='$column_count'>";

		$attributes  = "";

		!empty( $icon_name )
			and $attributes .= ' class="'  . esc_attr( $icon_name ) .'"';

		!empty( $item->attr_title )
			and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';

		!empty( $item->target )
			and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';

		!empty( $item->xfn )
			and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';

		!empty( $item->url )
			and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';

			if( !empty($sub_title) ){
				$item_output = $args->before
					. "<a $attributes>"
					. $args->link_before
					. $title
					//. '<span>'.$sub_title.'</span>'
					. '</a> '
					. $args->link_after
					. $args->after;
			} else {
				$item_output = $args->before
					. "<a $attributes>"
					. $args->link_before
					. $title
					. '</a> '
					. $args->link_after
					. $args->after;
			}

			// Since $output is called by reference we don't need to return anything.
			$output .= apply_filters(
				'walker_nav_menu_start_el'
			,   $item_output
			,   $item
			,   $depth
			,   $args
			);

	}

}

/*-----------------------------------------------------------------------------------*/
/* Main Navigation
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_nav_left')) {
	function ct_nav_left() {
		echo '<nav class="left">';
	    	wp_nav_menu (
	    		array(
					'menu'            => "primary-left",
					'menu_id'         => "ct-menu",
					'menu_class'      => "ct-menu",
					'echo'            => true,
					'container'       => '',
					'container_class' => '',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'container_id'    => 'nav-left',
					'theme_location'  => 'primary_left',
					'fallback_cb'	  => false,
					'walker'          => new CT_Menu_Class_Walker
				)
			);
	    echo '</nav>';
	}
}

if(!function_exists('ct_nav_right')) {
	function ct_nav_right() {
		echo '<nav class="right">';
	    	wp_nav_menu (
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
	    	);
	    echo '</nav>';
	}
}

if(!function_exists('ct_nav_full_width')) {
	function ct_nav_full_width() {
		echo '<nav>';
	    	wp_nav_menu (
	    		array(
					'menu'            => "primary-full-width",
					'menu_id'         => "ct-menu",
					'menu_class'      => "ct-menu",
					'echo'            => true,
					'container'       => '',
					'container_class' => '',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'container_id'    => 'nav-full-width',
					'theme_location'  => 'primary_full_width',
					'fallback_cb'	  => false,
					'walker'          => new CT_Menu_Class_Walker
				)
			);
	    echo '</nav>';
	}
}

if(!function_exists('ct_footer_nav')) {
	function ct_footer_nav() {
	    echo '<nav class="left">';
			wp_nav_menu( array( 'container_id' => 'footer-nav', 'theme_location' => 'footer', 'fallback_cb' => false) );
	    echo '</nav>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Mobile Header
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_mobile_header')) {
	function ct_mobile_header() {
	    echo '<div id="cbp-spmenu" class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right">';
	    	wp_nav_menu( array( 'theme_location' => 'mobile', 'fallback_cb' => false) );
	    echo '</div>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* DNS Prefetch
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_dns_prefetch')) {
	function ct_dns_prefetch() {

		echo '<link rel="preconnect" href="//fonts.gstatic.com/" crossorigin>';

	}
}
add_action('wp_enqueue_scripts', 'ct_dns_prefetch');

/*-----------------------------------------------------------------------------------*/
/* Google Fonts
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_heading_fonts_url')) {
	function ct_heading_fonts_url() {
		global $ct_options;

	    $font_url = '';
	    $ct_heading_font = isset( $ct_options['ct_heading_font'] ) ? esc_attr( $ct_options['ct_heading_font'] ) : '';
		$ct_heading_font = str_replace(' ','+', $ct_heading_font);

		if(!empty($ct_heading_font)) {
		    $font_url = add_query_arg( 'family', esc_html($ct_heading_font) . ':300,400,700', "//fonts.googleapis.com/css" );
		}

	    return $font_url;
	}
}

if(!function_exists('ct_body_fonts_url')) {
	function ct_body_fonts_url() {
		global $ct_options;

	    $font_url = '';
		$ct_body_font = isset( $ct_options['ct_body_font'] ) ? esc_attr( $ct_options['ct_body_font'] ) : '';
		$ct_body_font = str_replace(' ','+', $ct_body_font);

		if(!empty($ct_body_font)) {
		    $font_url = add_query_arg( 'family', esc_html($ct_body_font) . ':300,400,700', "//fonts.googleapis.com/css" );
		}

	    return $font_url;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Get Post IDs from Coordinates */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('getPostIdsFromCoords')) {
	function getPostIdsFromCoords( $swLat, $swLng, $neLat, $neLng )	{

		global $wpdb;

		$lngCase = '';

		if ( ($neLng > 0 && $swLng > 0 ) && ($swLng > $neLng) ) {
			$lngCase = " b.meta_value BETWEEN 0 AND ".$neLng." OR b.meta_value BETWEEN ".$swLng." AND 180 OR b.meta_value BETWEEN -180 AND 0";
		} else if ( $neLng > 0 && $swLng > 0 ) {
			$lngCase = " b.meta_value BETWEEN ".$swLng." AND ".$neLng;
		} else if ( $neLng < 0 && $swLng > 0 ) {
			$lngCase = " b.meta_value BETWEEN -180 AND ".$neLng." OR b.meta_value BETWEEN ".$swLng." AND 180";
		} else if ( $neLng < 0 && $swLng < 0 ) {
			$lngCase = " b.meta_value BETWEEN ".$swLng." AND ".$neLng;
		} else if ( $neLng > 0 && $swLng < 0 ) {
			$lngCase = " b.meta_value BETWEEN 0 AND ".$neLng." OR b.meta_value BETWEEN ".$swLng." AND 0";
		}

		$sql = "SELECT a.post_id FROM ".$wpdb->prefix ."posts, ".$wpdb->prefix ."postmeta a, ".$wpdb->prefix ."postmeta b 
		WHERE (CASE WHEN ".$neLat." < ".$swLat."
		    THEN a.meta_value BETWEEN ".$neLat." AND ".$swLat."
		    ELSE a.meta_value BETWEEN ".$swLat." AND ".$neLat."
		END) 
		AND (
		".$lngCase."
		)
		AND a.post_id = ".$wpdb->prefix ."posts.ID 
		AND ".$wpdb->prefix ."posts.post_status = 'publish' 
		AND ".$wpdb->prefix ."posts.post_type = 'listings' 
		AND a.post_id = b.post_id
		AND a.meta_key = 'lat'
		AND b.meta_key = 'lng';";

		$postIds = $wpdb->get_col( $sql );

		return $postIds;

	}
}

/*-----------------------------------------------------------------------------------*/
/* Mapping Ajax Methods */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('map_listing_update')) {
	function map_listing_update() {
		global $page;
		global $post;
		global $ct_options;
		global $ajaxArgs;

		$drawing_mode = false;
		$draw_markers = array();
		$ne           = '';
		$neLat        = '';
		$neLng        = '';

		// Check if Drawing mode
		if( isset( $_GET['drawMode'] ) ) {
			$drawing_mode = true;

			if ( isset( $_GET['drawMarkers'] ) ) {
				$draw_markers = json_decode( $_GET['drawMarkers'] );
			}
		}

		if( isset( $_GET['ne'] ) ) {
			$ne    = filter_var( $_GET['ne'], FILTER_SANITIZE_STRING );
			$neLat = trim( substr( $ne, 1, strpos( $ne, ', ' ) - 1 ) );
			$neLng = substr( $ne, strpos( $ne, ',' ) + 1 );
			$neLng = trim( substr( $neLng, 0, strlen( $neLng ) - 1 ) );
		}

		$sw    = '';
		$swLat = '';
		$swLng = '';

		if( isset( $_GET['sw'] ) ) {
			$sw    = filter_var( $_GET['sw'], FILTER_SANITIZE_STRING );
			$swLat = trim( substr( $sw, 1, strpos( $sw, ', ' ) - 1 ) );
			$swLng = substr( $sw, strpos( $sw, ',' ) + 1 );
			$swLng = trim( substr( $swLng, 0, strlen( $swLng ) - 1 ) );
		}

		$ct_search_results_listing_style = isset( $ct_options['ct_search_results_listing_style'] ) ? $ct_options['ct_search_results_listing_style'] : '';

		$count = 0;

		$search_num = $ct_options['ct_listing_search_num'];

		//file_put_contents(dirname(__FILE__)."/log.theme-functions", "calling getSearchArgs\r\n", FILE_APPEND);
        $skip_location_taxonomy_meta_query = false;

		$args = getSearchArgs( $skip_location_taxonomy_meta_query );

		//file_put_contents(dirname(__FILE__)."/log.theme-functions", "done calling getSearchArgs\r\n", FILE_APPEND);
		//file_put_contents(dirname(__FILE__)."/log.theme-functions", "Search args\r\n" .print_r( $args, true )."\r\n", FILE_APPEND);

		if ( $drawing_mode ) {
			$args['post__in'] = $draw_markers;
		} else {
			$args['post__in'] = getPostIdsFromCoords( $swLat, $swLng, $neLat, $neLng  );
		}

		$return['coords'] = 'swLat: '.$swLat.'; swLng: '.$swLng.'; neLat: '.$neLat.'; neLng: '.$neLng;

		//$return["post__in"] = print_r($args["post__in"], true);

		//file_put_contents(dirname(__FILE__)."/log.theme-functions", "one\r\n", FILE_APPEND);

		//$count = count( $args["post__in"] );

		if ( empty( $args['post__in'] ) ) {
			$args['post__in'] = [1];  // Do this so we get a no result instead of an all result
			$count = 0;
		}

		$args['post_type'] = 'listings';

		$args['showposts'] = -1; //$search_num;

		global $wp_query;

		$wp_query = new wp_query( $args );

		//file_put_contents(dirname(__FILE__)."/log.theme-functions", "two\r\n", FILE_APPEND);

		$ajaxArgs = $args;

		//$return["args"] = print_r($args, true);
		//$return["wp_query"] = print_r($wp_query, true);

		$count = 0;

		if ( isset($wp_query->post_count) ) {
			$count = intVal( $wp_query->post_count );
		}

		$map = '';
		ob_start();

		$newMarkers = '';
		$latlngs = array();
		$siteUrl = ct_theme_directory_uri();
		$ct_use_propinfo_icons = isset( $ct_options['ct_use_propinfo_icons'] ) ? $ct_options['ct_use_propinfo_icons'] : '';

		//$count = 0;
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			$lat = get_post_meta( $post->ID, 'lat', true );
			$lng = get_post_meta( $post->ID, 'lng', true );

			if ( $lat == '' || $lng == '' ) {
				continue;
			}
			ob_start();
			trim(ct_status_slug());
			$ct_status = ob_get_contents();
			ob_end_clean();
			$markerPrice = ct_listing_marker_price();

			$property = [
				'thumb'       => ct_first_image_map_tn( false ),
				'title'       => ct_listing_title( false ),
				'fullPrice'   => ct_listing_price( false ),
				'markerPrice' => $markerPrice,
				'bed'         => ct_taxonomy_return( 'beds' ),
				'bath'        => ct_taxonomy_return( 'baths' ),
				'size'        => get_post_meta( $post->ID, '_ct_sqft', true ).' '.ct_sqftsqm(),
				'parking'     => get_post_meta( $post->ID, '_ct_parking', true ),
				'street'      => get_the_title( ),
				'city'        => ct_taxonomy_return( 'city' ),
				'state'       => ct_taxonomy_return( 'state' ),
				'zip'         => ct_taxonomy_return( 'zipcode' ),
				//'latlong' => get_post_meta(get_the_ID(), '_ct_latlng', true),
				'permalink'   => get_the_permalink(),
				'isHome'      => is_home()?'false':'true',
				'commercial'  => ct_has_type('commercial')?'commercial':'',
				'industrial'  => ct_has_type('industrial')?'industrial':'',
				'retail'	  => ct_has_type('retail')?'retail':'',
				'land'        => ct_has_type('land')?'land':'',
				'siteURL'     => $siteUrl,
				'useIcons'    => $ct_use_propinfo_icons,
				'listingID'   => $post->ID,
				'ctStatus'    => $ct_status,
			];


			$latlngs[] = ['lat'=>$lat, 'lng'=>$lng, 'property'=>$property];
			//$count++;

		endwhile; endif;
		ob_end_clean();


		$args['paged'] = 1;
		if( isset( $_GET['paged'] ) ) {
			$args['paged'] = intVal( $_GET['paged'] );
		}

		$page = $args['paged'] + 1;

		$args['showposts'] = $search_num;

		//$return["paged"] = $args['paged'];
		//$return["showposts"] = $search_num;
		if( class_exists( 'IDX_Query' ) ) {
			$idx_query = new IDX_Query( $args );
		}

		$wp_query = new wp_query( $args );

		ob_start();

		if($ct_search_results_listing_style == 'list') {
			get_template_part( 'layouts/list');
		} else {
			get_template_part( 'layouts/grid');
		}

		$listings = ob_get_clean();

		$return['siteURL']  = $siteUrl;
		$return['listings'] = $listings;
		$return['count']    = $count;
		$return['latlngs']  = $latlngs;

		//$return["postIds"]  = $args["post__in"];

		print json_encode( $return );
		wp_die();

	}
}
add_action( 'wp_ajax_map_listing_update', 'map_listing_update' );
add_action( 'wp_ajax_nopriv_map_listing_update', 'map_listing_update' );

/*-----------------------------------------------------------------------------------*/
/* Disable Sticky Header for User Front End Pages */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_disable_sticky_header_for_crm')) {
	function ct_disable_sticky_header_for_crm($enabled) {
		if($enabled == false) {
			return $enabled;
		}

		return in_array(get_page_template_slug(), array(
			'template-favorite-listings.php',
			'template-edit-listing.php',
			'template-edit-profile.php',
			'template-listing-analytics.php',
			'template-listing-email-alerts.php',
			'template-membership.php',
			'template-packages-thank-you.php',
			'template-submit-listing.php',
			'template-user-dashboard.php',
			'template-view-invoices.php',
			'template-view-listings.php',
			'template-leads-pro.php'
		)) ? false : true;
	}
}
add_filter('ct_enqueue_sticky_header', 'ct_disable_sticky_header_for_crm');

/*-----------------------------------------------------------------------------------*/
/* Init Scripts & Styles */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_init_scripts')) {
	function ct_init_scripts() {
		global $ct_options, $post;

		$ct_listing_search_cluster_zoom_level        = isset( $ct_options['ct_listing_search_marker_cluster_zoom_level'] ) ? $ct_options['ct_listing_search_marker_cluster_zoom_level'] : '14';
		$ct_enable_marker_price                      = isset( $ct_options['ct_enable_marker_price'] ) ? $ct_options['ct_enable_marker_price'] : 'no';
		$ct_listing_search_marker_price_display_zoom = isset( $ct_options['ct_listing_search_marker_price_display_zoom_level'] ) ? $ct_options['ct_listing_search_marker_price_display_zoom_level'] : '10';
		$ct_listing_marker_type                      = isset( $ct_options['ct_listing_marker_type'] ) ? $ct_options['ct_listing_marker_type'] : 'svg';
		$ct_listing_marker_svg_size                  = isset( $ct_options['ct_listing_marker_svg_size'] ) ? $ct_options['ct_listing_marker_svg_size'] : '12';

		$google_maps_api_key = isset( $ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key'] ) : '';

		/*-----------------------------------------------------------------------------------*/
		/* Enqueue Styles */
		/*-----------------------------------------------------------------------------------*/

		wp_enqueue_style('base', get_template_directory_uri() . '/css/base.css', '', '2.2.7', 'screen, projection');
		wp_enqueue_style('headingFont', ct_heading_fonts_url(), array(), '1.0.0' );
		wp_enqueue_style('bodyFont', ct_body_fonts_url(), array(), '1.0.0' );
		wp_enqueue_style('framework', get_template_directory_uri() . '/css/responsive-gs-12col.css', '', '', 'screen, projection');
		wp_enqueue_style('ie', get_template_directory_uri() . '/css/ie.css', '', '', 'screen, projection');
		wp_enqueue_style('layout', get_template_directory_uri() . '/css/layout.css', '', '4.3.1.0', 'screen, projection');
		wp_enqueue_style('ctFlexslider', get_template_directory_uri() . '/css/flexslider.css', '', '1.0.5', 'screen, projection');
		wp_enqueue_style('ctFlexsliderNav', get_template_directory_uri() . '/css/flexslider-direction-nav.css', '', '1.0.7', 'screen, projection');
		wp_enqueue_style('fontawesome', get_template_directory_uri() . '/css/all.min.css', '', '1.0.1', 'screen, projection');
		wp_enqueue_style('fontawesomeShim', get_template_directory_uri() . '/css/v4-shims.min.css', '', '1.0.1', 'screen, projection');
		wp_enqueue_style('animate', get_template_directory_uri() . '/css/animate.min.css', '', '', 'screen, projection');
		wp_enqueue_style('ctModal', get_template_directory_uri() . '/css/ct-modal-overlay.css', '', '1.2.4', 'screen, projection');
		wp_enqueue_style('ctSlidePush', get_template_directory_uri() . '/css/ct-sp-menu.css', '', '1.2.6', 'screen, projection');
		
		// Affordability Calculator
		if(is_singular('listings') || isset($_GET['search-listings'])) {
			
			global $ct_options; 

			// Price.
			$price = 0;
			if(is_single()) {
				$price = get_post_meta(get_the_ID(), '_ct_price', true);
				if(empty( $price)) {
					$price = 0;
				}
			}
			// The decimal place. 
			$ct_currency_decimal = isset($ct_options['ct_currency_decimal'] ) ? esc_attr( $ct_options['ct_currency_decimal']) : ''; 
			// The default downpayment percentage value. 
			$downpayment_percentage = isset($ct_options['ct_listing_est_payment_percent_down'] ) ? esc_attr( $ct_options['ct_listing_est_payment_percent_down']) : '20'; 
			// The downpayment actual value. 
			$downpayment_figure = (absint($downpayment_percentage ) / 100) * absint($price); 
			// Interest Rate. 
			$interest_rate = isset($ct_options['ct_listing_est_payment_interest_rate'] ) ? esc_attr(floatval($ct_options['ct_listing_est_payment_interest_rate'])) : 4.00;; // default. 
			// Price From.
			$slider_price_from = isset($ct_options['ct_adv_search_price_slider_min_value'] ) ? esc_attr(absint($ct_options['ct_adv_search_price_slider_min_value'])) : 10000;
			// Price To.
			$slider_price_to = isset($ct_options['ct_adv_search_price_slider_max_value'] ) ? esc_attr(absint($ct_options['ct_adv_search_price_slider_max_value'])) : 5000000; 
			// Tax Rate.
			$tax_rate = isset($ct_options['ct_listing_est_payment_tax_rate'] ) ? esc_attr(floatval($ct_options['ct_listing_est_payment_tax_rate'])) : 2.80; 
			// Home insurance.
			$home_insurance = isset($ct_options['ct_listing_est_payment_home_insurance'] ) ? esc_attr($ct_options['ct_listing_est_payment_home_insurance']) : 900; 
			// Main layout.
			$main_layout = isset($ct_options['ct_single_listing_main_layout'] ) ? $ct_options['ct_single_listing_main_layout']: array("disabled" => [], "enabled" => []);
			
			// Check to see if block is enabled.
			if(array_key_exists('ct_affordability_calculator', $main_layout['enabled'])) {
				wp_enqueue_style('ct-affordability-calculator', get_template_directory_uri() . '/css/ct-affordability-calculator.css', '', '1.0.5', 'screen, projection');
				wp_enqueue_script('ct-affordability-calculator', get_template_directory_uri() . '/js/ct.affordability.calculator.js', array('jquery'), '1.0.4', true );
				
				wp_localize_script( 'ct-affordability-calculator', 'ct_affordability_calculator', array(
					'currency' => ct_currency( false ),
					'price_from' => $slider_price_from,
					'price_to' => $slider_price_to,
					'chart' => '0',
					'chart_url' => apply_filters("ct_affordability_calculator_chart_uri", get_template_directory_uri() . '/js/ct.affordability.chart.js'),
					'model' => array(
						'loan_term' => 30,
						'loan_type' => 'conventional',
						'interest_rate' => $interest_rate,
						'interest_rate_formatted' => $interest_rate,
						'tax_rate' => $tax_rate,
						'price' => $price,
						'price_formatted' => ct_currency( false ) . $price,
						'downpayment' => esc_attr( $downpayment_figure ),
						'downpayment_formatted' => ct_currency( false ) . number_format_i18n( $downpayment_figure, $ct_currency_decimal ),
						'downpayment_percentage' => $downpayment_percentage,
						'home_insurance' => $home_insurance
					),
				) );
			}
		
		}

	    if(is_singular('listings')) {
			wp_enqueue_style('print-base', get_template_directory_uri() . '/css/base.css', '', '1.0.6', 'print');
			wp_enqueue_style('print', get_template_directory_uri() . '/css/listing-print.css', '', '1.0.3', 'print');
			wp_enqueue_style('ctLightbox', get_template_directory_uri() . '/css/ct-lightbox.css', '', '1.0.6', 'screen, projection');
		}

		wp_enqueue_style('owlCarousel', get_template_directory_uri() . '/css/owl-carousel.css', '', '', 'screen, projection');

		if(is_single() || is_page()) {
			wp_enqueue_style('comments', get_template_directory_uri() . '/css/comments.css', '', '1.0.2', 'screen, projection');
		}

		if(is_single() || is_author() || is_page_template('template-contact.php') || is_page_template('template-favorite-listings.php') || is_page_template('template-submit-listing.php') || is_front_page() || is_page_template('template-agents.php') || is_page_template('template-brokerages.php')) {
			wp_enqueue_style('validationEngine', get_template_directory_uri() . '/css/validationEngine.jquery.css', '', '', 'screen, projection');
		}

		wp_enqueue_style('dropdowns', get_template_directory_uri() . '/css/ct-dropdowns.css', '', '1.1.2', 'screen, projection');

		if(isset($ct_options['ct_skin'])) {
            if ($ct_options['ct_skin'] == 'minimal') {
                wp_enqueue_style('minimalSkin', get_template_directory_uri() . '/css/minimal-skin.css', '', '1.3.2', 'screen, projection');
            }
		}

		if(isset($ct_options['ct_rtl'])) {
			if ($ct_options['ct_rtl'] == 'yes') {
				wp_enqueue_style('rtl', get_template_directory_uri() . '/rtl.css', '', '', 'screen, projection');
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/* Enqueue Scripts */
		/*-----------------------------------------------------------------------------------*/

		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('touchPunch', get_template_directory_uri() . '/js/jquery.ui.touch-punch.min.js', array('jquery'), '1.0', true);
		wp_enqueue_script('mobileMenu', get_template_directory_uri() . '/js/ct.mobile.menu.js', array('jquery'), '1.2.5', true);
		wp_register_script('sticky-js', get_template_directory_uri() . '/js/sticky.min.js', array(), '1.3.0', false);
		wp_enqueue_script('advSearch', get_template_directory_uri() . '/js/ct.advanced.search.js', array('jquery'), '1.0', false );
		wp_enqueue_script('owlCarousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), '1.0', false);

	    if(is_singular('listings')) {
			wp_enqueue_script('ctLightbox', get_template_directory_uri() . '/js/ct.lightbox.min.js', 'jquery', '1.0', false);
		}

		if(is_page_template('template-edit-profile.php')) {
			wp_enqueue_script( 'password-strength-meter' );
			wp_enqueue_script('ctPassStrength', get_template_directory_uri() . '/js/ct.passwordstrength.js', array('jquery'), '1.0', false);
		}

		if(!is_page_template('template-idx.php') || !is_page_template('template-idx-full-width.php')) {
	        wp_enqueue_script('ctNiceSelect', get_template_directory_uri() . '/js/jquery.nice-select.min.js', array('jquery'), '1.0', false);
	        wp_enqueue_style('ctNiceSelect', get_template_directory_uri() . '/css/nice-select.css', '', '1.0.6', 'screen, projection');
	        wp_enqueue_script('ctSelect', get_template_directory_uri() . '/js/ct.select.js', array('jquery'), '1.2.8', false);

	        $current_user = wp_get_current_user();
	        $user_role = ( array ) $current_user->roles;
	        $user_mobile = get_user_meta($current_user->ID, 'mobile', true);
	        $account_settings_page_id = isset( $ct_options['ct_profile'] ) ? esc_html( $ct_options['ct_profile'] ) : '';
	        $account_settings_url = get_permalink($account_settings_page_id);

	        wp_localize_script( 'ctSelect', 'ct_select', array(
				'user_role' => $user_role,
				'user_mobile' => $user_mobile,
				'account_settings_url' => $account_settings_url,
			) );
		}

		if(function_exists('wp_favorite_posts') || function_exists('ct_fp_favorite_posts')) {
			wp_enqueue_script('ctWPFP', get_template_directory_uri() . '/js/ct.wpfp.js', array('jquery'), '1.0.5', true);
			$fav_strings = [
				'save' => __('Save', 'contempo'),
				'saved' => __('Saved', 'contempo')
			];
			wp_localize_script('ctWPFP', 'ct_favorite_config', $fav_strings );
		}

		wp_enqueue_script('jsCookie', get_template_directory_uri() . '/js/js.cookie.js', array('jquery'), '1.0', true);
		wp_enqueue_script('fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), '1.0', true);
		wp_enqueue_script('cycle', get_template_directory_uri() . '/js/jquery.cycle.lite.js', array('jquery'), '1.0', true);
		wp_enqueue_script('flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array('jquery'), '1.0', true);

		if(is_page_template('template-submit-listing.php') || (is_page_template('template-edit-listing.php'))) {
			// Do nothing
		} else {
			$google_maps_api_key = isset( $ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key'] ) : '';

			if($google_maps_api_key != '') {
				$google_maps_api_key_output = '?key=' . $google_maps_api_key;
			} else {
				$google_maps_api_key_output = '';
			}

			if($google_maps_api_key != '') {
				wp_enqueue_script('gmaps', '//maps.google.com/maps/api/js' . $google_maps_api_key_output . '&v=3.51&libraries=places', '', '1.0.4', false);
			}
		}

		if(is_singular() && get_option( 'thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		if(is_page_template('template-submit-listing.php')){
			$google_maps_api_key = isset( $ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key'] ) : '';
			if($google_maps_api_key != '') {
				$google_maps_api_key_output = '&key=' . $google_maps_api_key;
			} else {
				$google_maps_api_key_output = '';
			}
			if($google_maps_api_key != '') {
				wp_enqueue_script('gmapsPlaces', '//maps.googleapis.com/maps/api/js?v=3.exp&libraries=places' . $google_maps_api_key_output, '', '1.0', false);
			}
			wp_enqueue_script('geoComplete', get_template_directory_uri() . '/js/jquery.geocomplete.js', array('jquery'), '1.0', false);
			wp_enqueue_script('parsley', get_template_directory_uri() . '/js/parsley.js', array('jquery'), '1.0', false);
			wp_enqueue_script('multiStepForm', get_template_directory_uri() . '/js/ct.multi.step.form.js', array('jquery'), '1.0', false);
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('plUpload', get_template_directory_uri() . '/js/plupload.full.min.js', array('jquery'), '1.0', false);
			wp_enqueue_script('plupload-handlers');
			wp_enqueue_script('wp-plupload');
			wp_enqueue_script('submit-listing', get_template_directory_uri() . '/js/ct.submit.listing.js', array('jquery'), '1.0.1', false);
			$admin_url = array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ) );
			$template_url = array( 'templateUrl' => get_stylesheet_directory_uri() );
			wp_localize_script( 'submit-listing', 'PostID' , array( 'post_id'=>(isset($_GET['listings'])? $_GET['listings'] : $post->ID) ) );
			wp_localize_script( 'submit-listing', 'AdminURL', $admin_url  );
			wp_localize_script( 'submit-listing', 'TemplatePath', $template_url );
		}

		if(is_page_template('template-edit-listing.php')){
			$google_maps_api_key = isset( $ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key'] ) : '';
			if($google_maps_api_key != '') {
				$google_maps_api_key_output = '&key=' . $google_maps_api_key;
			} else {
				$google_maps_api_key_output = '';
			}
			if($google_maps_api_key != '') {
				wp_enqueue_script('gmapsPlaces', '//maps.googleapis.com/maps/api/js?v=3.exp&libraries=places' . $google_maps_api_key_output, '', '1.0', false);
			}
			wp_enqueue_script('geoComplete', get_template_directory_uri() . '/js/jquery.geocomplete.js', array('jquery'), '1.0', false);
			wp_enqueue_script('parsley', get_template_directory_uri() . '/js/parsley.js', array('jquery'), '1.0', false);
			wp_enqueue_script('multiStepForm', get_template_directory_uri() . '/js/ct.multi.step.form.js', array('jquery'), '1.0', false);
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('plupload');
			wp_enqueue_script('plupload-handlers');
			wp_enqueue_script('wp-plupload');
			wp_enqueue_script('edit-listing', get_template_directory_uri() . '/js/ct.edit.listing.js', array('jquery'), '1.0.1', false);
			$admin_url = array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ) );
			$template_url = array( 'templateUrl' => get_stylesheet_directory_uri() );
			wp_localize_script( 'edit-listing', 'PostID' , array( 'post_id'=>(isset($_GET['listings'])? $_GET['listings'] : $post->ID) ) );
			wp_localize_script( 'edit-listing', 'AdminURL', $admin_url  );
			wp_localize_script( 'edit-listing', 'TemplatePath', $template_url );
		}

		if($google_maps_api_key != '') {
			
			$gmap_dependency = array();

			if(!is_singular( 'listings' )) {
				$gmap_dependency = array('gmaps');
			}

			wp_enqueue_script('infobox', get_template_directory_uri() . '/js/ct.infobox.js', $gmap_dependency, '1.0', true);
			wp_enqueue_script('marker', get_template_directory_uri() . '/js/markerwithlabel.js', $gmap_dependency, '1.0', true);
			wp_enqueue_script('markerCluster', get_template_directory_uri() . '/js/markerclusterer.js', $gmap_dependency, '1.0', true);
			wp_enqueue_script('mapping', get_template_directory_uri() . '/js/ct.mapping.js', $gmap_dependency, '2.0.6', true);

			wp_localize_script( 'mapping', 'mapping_ajax_object',
				array(
					'ajax_url'                      => admin_url( 'admin-ajax.php' ),
					'search_cluster_zoom_level'      => $ct_listing_search_cluster_zoom_level,
					'search_marker_price_zoom_level' => $ct_listing_search_marker_price_display_zoom,
					'ct_enable_marker_price'         => $ct_enable_marker_price,
					'listing_marker_type'            => $ct_listing_marker_type,
					'ct_listing_marker_svg_size'     => $ct_listing_marker_svg_size,
					'ct_listing_search_enabled'      => $ct_options['ct_disable_geolocation'],
					'click_drag_draw'				 =>  __('Click and drag to draw your search', 'contempo'),
					'cancel_draw'					 => __('Cancel', 'contempo'),
					'clear_draw'					 => __('Clear', 'contempo'),
				)
			);
		}

		$ct_sticky_header = isset( $ct_options['ct_sticky_header'] ) ? esc_attr( $ct_options['ct_sticky_header'] ) : '';
		if($ct_sticky_header == 'yes') {
			wp_register_script('sticky-js', get_template_directory_uri() . '/js/sticky-js.min.js', array(), '1.0', false);

			if(apply_filters('ct_enqueue_sticky_header', true) !== false) {
				wp_enqueue_script('sticky-header', get_template_directory_uri() . '/js/ct.sticky-header.js', array('jquery', 'sticky-js'), '1.1.0', false);
				wp_localize_script('sticky-header', 'ct_sticky_header_config', 
					array(
						'show_type' => isset( $ct_options['ct_sticky_header_type'] ) ? $ct_options['ct_sticky_header_type'] : 'always_sticky'
					)
				);
			}
		}

		wp_enqueue_script('modernizer', get_template_directory_uri() . '/js/modernizr.custom.js', array('jquery'), '1.0', true);
		wp_enqueue_script('classie', get_template_directory_uri() . '/js/classie.js', array('jquery'), '1.0', true);
		wp_enqueue_script('hammer', get_template_directory_uri() . '/js/jquery.hammer.min.js', array('jquery'), '1.0', true);
		wp_enqueue_script('touchEffects', get_template_directory_uri() . '/js/toucheffects.js', array('jquery'), '1.0', true);
		wp_enqueue_script('base', get_template_directory_uri() . '/js/base.js', array('jquery'), '1.4.7', true);
		wp_localize_script('base', 'ct_base', array( 'ajax_url' => admin_url( 'admin-ajax.php') ) );
		wp_enqueue_script('ctaccount', get_template_directory_uri() . '/js/ct.account.js', array('jquery'), '1.0.2', true);

		// Localize the script with new data
		$ct_city_town_or_village = isset($ct_options['ct_city_town_or_village']) ? $ct_options['ct_city_town_or_village'] : '';
		$ct_state_or_area = isset($ct_options['ct_state_or_area']) ? $ct_options['ct_state_or_area'] : '';

		$ct_price_slider_min_value = isset( $ct_options['ct_adv_search_price_slider_min_value'] ) && is_numeric( $ct_options['ct_adv_search_price_slider_min_value'] ) ? $ct_options['ct_adv_search_price_slider_min_value'] : '100000';
		$ct_price_slider_max_value = isset( $ct_options['ct_adv_search_price_slider_max_value'] ) && is_numeric( $ct_options['ct_adv_search_price_slider_max_value'] ) ? $ct_options['ct_adv_search_price_slider_max_value'] : '5000000';
		$ct_currency = isset( $ct_options['ct_currency'] ) ? $ct_options['ct_currency'] : '';
		$ct_currency_placement = isset( $ct_options['ct_currency_placement'] ) ? $ct_options['ct_currency_placement'] : '';
		$ct_size_slider_min_value = isset( $ct_options['ct_adv_search_size_slider_min_value'] ) && is_numeric( $ct_options['ct_adv_search_size_slider_min_value'] ) ? $ct_options['ct_adv_search_size_slider_min_value'] : '100';
		$ct_size_slider_max_value = isset( $ct_options['ct_adv_search_size_slider_max_value'] ) && is_numeric( $ct_options['ct_adv_search_size_slider_max_value'] ) ? $ct_options['ct_adv_search_size_slider_max_value'] : '1000';


		if(!isset( $ct_options['ct_sq'] )) {
			$ct_options['ct_sq'] = 'sqft'; // set default setting to set value.
		}

		if(isset( $ct_options['ct_sq'] )) {
			if($ct_options['ct_sq'] == 'sqft') {
				$ct_sqftsm = __(' Sq Ft', 'contempo');
			} else if($ct_options['ct_sq'] == 'sqmeters') {
				$ct_sqftsm = __(' m²', 'contempo');
			} else if($ct_options['ct_sq'] == 'area') {
				$ct_sqftsm = __('Area', 'contempo');
			}
		}
		$ct_lot_size_slider_min_value = isset( $ct_options['ct_adv_search_lot_size_slider_min_value'] ) && is_numeric( $ct_options['ct_adv_search_lot_size_slider_min_value'] ) ? $ct_options['ct_adv_search_lot_size_slider_min_value'] : '0';
		$ct_lot_size_slider_max_value = isset( $ct_options['ct_adv_search_lot_size_slider_max_value'] ) && is_numeric( $ct_options['ct_adv_search_lot_size_slider_max_value'] ) ? $ct_options['ct_adv_search_lot_size_slider_max_value'] : '100';


		if(!isset( $ct_options['ct_acres'] )) {
			$ct_options['ct_acres'] = 'acres'; // set default setting to set value.
		}

		if(isset( $ct_options['ct_acres'] )) {
			if($ct_options['ct_acres'] == 'acres') {
				$ct_acres = __('Acres', 'contempo');
			} elseif($ct_options['ct_acres'] == 'sqft') {
				$ct_acres = __('Sq Ft', 'contempo');
			} elseif($ct_options['ct_acres'] == 'sqmeters') {
				$ct_acres = __('m²', 'contempo');
			} elseif($ct_options['ct_acres'] == 'area') {
				$ct_acres = __('Area', 'contempo');
			}
		}

		$ct_status_all = __('All Statuses', 'contempo');
		$ct_status_label = __('Statuses', 'contempo');

		if($ct_city_town_or_village == 'town') {
			$ct_city_all = __('All Towns', 'contempo');
			$ct_city_label = __('Towns', 'contempo');
		} elseif($ct_city_town_or_village == 'village') {
			$ct_city_all = __('All Villages', 'contempo');
			$ct_city_label = __('Villages', 'contempo');
		} else {
			$ct_city_all = __('All Cities', 'contempo');
			$ct_city_label = __('Cities', 'contempo');
		}

		if($ct_state_or_area == 'area') {
			$ct_state_all = __('All Areas', 'contempo');
			$ct_state_label = __('Areas', 'contempo');
		} elseif($ct_state_or_area == 'suburb') {
			$ct_state_all = __('All Suburbs', 'contempo');
			$ct_state_label = __('Suburbs', 'contempo');
		} elseif($ct_state_or_area == 'province') {
			$ct_state_all = __('All Provinces', 'contempo');
			$ct_state_label = __('Provinces', 'contempo');
		} elseif($ct_state_or_area == 'region') {
			$ct_state_all = __('All Regions', 'contempo');
			$ct_state_label = __('Regions', 'contempo');
		} elseif($ct_state_or_area == 'parish') {
			$ct_state_all = __('All Parishes', 'contempo');
			$ct_state_label = __('Parishes', 'contempo');
		} else {
			$ct_state_all = __('All States', 'contempo');
			$ct_state_label = __('States', 'contempo');
		}

		$ct_county_all = __('All Counties', 'contempo');
		$ct_county_label = __('Counties', 'contempo');

		$translation_array = array(
			'all_statuses' => $ct_status_all,
			'status_label' => $ct_status_label,
			'all_cities' => $ct_city_all,
			'city_label' => $ct_city_label,
			'all_states' => $ct_state_all,
			'state_label' => $ct_state_label,
			'all_counties' => $ct_county_all,
			'county_label' => $ct_county_label,
			'price_slider_min_value' => $ct_price_slider_min_value,
			'price_slider_max_value' => $ct_price_slider_max_value,
			'currency' => $ct_currency,
			'currency_placement' => $ct_currency_placement,
			'size_slider_min_value' => $ct_size_slider_min_value,
			'size_slider_max_value' => $ct_size_slider_max_value,
			'sqftsm' => $ct_sqftsm,
			'lot_size_slider_min_value' => $ct_lot_size_slider_min_value,
			'lot_size_slider_max_value' => $ct_lot_size_slider_max_value,
			'acres' => $ct_acres,
			'close_map' => __( 'Close Map', 'contempo' ),
			'open_map' => __( 'Open Map', 'contempo' ),
			'close_search' => __( 'Close Search', 'contempo' ),
			'open_search' => __( 'Open Search', 'contempo' ),
			'close_tools' => __( 'Close', 'contempo' ),
			'open_tools' => __( 'Open', 'contempo' ),
			'search_saved' => __( 'Search Saved', 'contempo'),
			'read_more' => __('Read more', 'contempo'),
			'collapse' => __('Collapse', 'contempo'),
			'a_value' => '10',
			'ct_ajax_url' => admin_url( 'admin-ajax.php' )
		);
		wp_localize_script( 'base', 'object_name', $translation_array );

		// Localize Advanced Search
		$ct_city_town_or_village = isset($ct_options['ct_city_town_or_village']) ? $ct_options['ct_city_town_or_village'] : '';
		$ct_state_or_area = isset($ct_options['ct_state_or_area']) ? $ct_options['ct_state_or_area'] : '';
		$ct_zip_or_post = isset($ct_options['ct_zip_or_post']) ? $ct_options['ct_zip_or_post'] : '';

	    if($ct_city_town_or_village == 'town') {
			$ct_city = __('All Towns', 'contempo');
		} elseif($ct_city_town_or_village == 'village') {
			$ct_city = __('All Villages', 'contempo');
		} else {
			$ct_city = __('All Cities', 'contempo');
		}

		if($ct_state_or_area == 'area') {
			$ct_state = __('All Areas', 'contempo');
		} elseif($ct_state_or_area == 'suburb') {
			$ct_state = __('All Suburbs', 'contempo');
		} elseif($ct_state_or_area == 'province') {
			$ct_state = __('All Provinces', 'contempo');
		} elseif($ct_state_or_area == 'region') {
			$ct_state = __('All Regions', 'contempo');
		} elseif($ct_state_or_area == 'parish') {
			$ct_state = __('All Parishes', 'contempo');
		} else {
			$ct_state = __('All States', 'contempo');
		}

		if($ct_zip_or_post == 'postcode') {
			$ct_zip_post = __('All Postcodes', 'contempo');
		} elseif($ct_zip_or_post == 'postalcode') {
			$ct_zip_post = __('All Postal Codes', 'contempo');
		} else {
			$ct_zip_post = __('All Zipcodes', 'contempo');
		}

	    $ct_advanced_search_translation_array = array(
			'all_cities' => $ct_city,
			'all_states' => $ct_state,
			'all_zip_post' => $ct_zip_post
		);
		wp_localize_script( 'advSearch', 'searchLabel', $ct_advanced_search_translation_array );

		// Localize Validation Errors.
		if(is_single() || is_author() || is_front_page() || is_page()) {
			
			wp_enqueue_script('validationEngine', get_template_directory_uri() . '/js/jquery.validationEngine.js', array('jquery'), '1.0.7', true); 
			
			// Localize the script with new data.
			$ct_validationEngine_errors = array(
				'required' => __('* This field is required', 'contempo'),
				'requiredCheckboxMulti' => __('* Please select an option', 'contempo'),
				'requiredCheckbox' => __('* This checkbox is required', 'contempo'),
				'invalidTelephone' => __('* Invalid phone number', 'contempo'),
				'invalidEmail' => __('* Invalid email address', 'contempo'),
				'invalidDate' => __('* Invalid date, must be in YYYY-MM-DD format', 'contempo'),
				'numbersOnly' => __('* Numbers only', 'contempo'),
				'noSpecialChar' => __('* No special caracters allowed', 'contempo'),
				'letterOnly' => __('* Letters only', 'contempo'),
			);

			// Google Recaptcha V3.
			$google_recaptcha_v3_args = array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'public_key' => ct_get_google_recaptcha_v3_site_key(),
				'is_enabled' => isset( $ct_options['ct_use_google_recaptcha'] ) ? $ct_options['ct_use_google_recaptcha']: 'no',
				'gettext' => array(
					'verifying' => esc_html__('Verifying Request...', 'contempo'),
					'requesting' => esc_html__('Request Information', 'contempo')
				)
			);

			wp_localize_script('validationEngine', 're7GoogleRecaptchaV3', $google_recaptcha_v3_args);
			wp_localize_script('validationEngine', 'validationError', $ct_validationEngine_errors);
		}
	}
}
add_action('wp_enqueue_scripts', 'ct_init_scripts');

/*-----------------------------------------------------------------------------------*/
/* Enqueue main stylesheet
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_theme_style')) {
	function ct_theme_style() {
	    wp_enqueue_style( 'ct-theme-style', get_bloginfo( 'stylesheet_url' ), array(), '1.0', 'screen, projection', 99 );
	}
}
add_action( 'wp_enqueue_scripts', 'ct_theme_style' );

/*-----------------------------------------------------------------------------------*/
/* CT Head */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_wp_head')) {
	function ct_wp_head() {
		global $ct_options;

		$ct_adv_search_price_slider_min_value = isset( $ct_options['ct_adv_search_price_slider_min_value'] ) && is_numeric( $ct_options['ct_adv_search_price_slider_min_value'] ) ? $ct_options['ct_adv_search_price_slider_min_value'] : '100000';
		$ct_adv_search_price_slider_max_value = isset( $ct_options['ct_adv_search_price_slider_max_value'] ) && is_numeric( $ct_options['ct_adv_search_price_slider_max_value'] ) ? $ct_options['ct_adv_search_price_slider_max_value'] : '5000000';

		$ct_currency_placement = isset( $ct_options['ct_currency_placement'] ) ? $ct_options['ct_currency_placement'] : '';

		$ct_adv_search_size_slider_min_value = isset( $ct_options['ct_adv_search_size_slider_min_value'] ) && is_numeric( $ct_options['ct_adv_search_size_slider_min_value'] ) ? $ct_options['ct_adv_search_size_slider_min_value'] : '100';
		$ct_adv_search_size_slider_max_value = isset( $ct_options['ct_adv_search_size_slider_max_value'] ) && is_numeric( $ct_options['ct_adv_search_size_slider_max_value'] ) ? $ct_options['ct_adv_search_size_slider_max_value'] : '1000';

		$ct_adv_search_lot_size_slider_min_value = isset( $ct_options['ct_adv_search_lot_size_slider_min_value'] ) && is_numeric( $ct_options['ct_adv_search_lot_size_slider_min_value'] ) ? $ct_options['ct_adv_search_lot_size_slider_min_value'] : '0';
		$ct_adv_search_lot_size_slider_max_value = isset( $ct_options['ct_adv_search_lot_size_slider_max_value'] ) && is_numeric( $ct_options['ct_adv_search_lot_size_slider_max_value'] ) ? $ct_options['ct_adv_search_lot_size_slider_max_value'] : '100';

		?>

	    <!--[if lt IE 9]>
	    <script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
	    <![endif]-->
				
		<script>
			function numberWithCommas(x) {
			    if (x !== null) {
			        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			    }
			}

			jQuery(function($) {

				var $currency = "<?php ct_currency(); ?>";
				var $sqftsm = "<?php ct_sqftsqm(); ?>";
				var $acres = "<?php ct_acres(); ?>";

                <?php $requested_price_fr_options = array('options'=>array('default'=> $ct_adv_search_price_slider_min_value )); ?>
                <?php $requested_price_fr = filter_input(INPUT_GET,'ct_price_from', FILTER_VALIDATE_INT, $requested_price_fr_options); ?>

                <?php $requested_price_to_options = array('options'=>array('default'=> $ct_adv_search_price_slider_max_value )); ?>
                <?php $requested_price_to = filter_input(INPUT_GET,'ct_price_to', FILTER_VALIDATE_INT, $requested_price_to_options); ?>

			    // Price From / To
			    $( "#slider-range" ).slider({
			        range: true,
			        min: <?php echo absint($ct_adv_search_price_slider_min_value); ?>,
			        step: 5000,
			        max: <?php echo absint($ct_adv_search_price_slider_max_value); ?>,
			        values: [  <?php echo absint($requested_price_fr); ?>, <?php echo absint($requested_price_to); ?> ],
			        slide: function( event, ui ) {
			        	<?php if($ct_currency_placement == 'after') { ?>
			            $( "#slider-range #min" ).html( numberWithCommas(ui.values[ 0 ]) + $currency);
			            $( "#slider-range #max" ).html( numberWithCommas(ui.values[ 1 ]) + $currency);
			            $( "#price-from-to-slider .min-range" ).html( numberWithCommas( ui.values[ 0 ] ) + $currency );
			            $( "#price-from-to-slider .max-range" ).html( numberWithCommas( ui.values[ 1 ] ) + $currency );
			            <?php } else { ?>
			            $( "#slider-range #min" ).html( $currency + numberWithCommas(ui.values[ 0 ]) );
			            $( "#slider-range #max" ).html( $currency + numberWithCommas(ui.values[ 1 ]) );
			            $( "#price-from-to-slider .min-range" ).html( $currency + numberWithCommas( ui.values[ 0 ] ) );
			            $( "#price-from-to-slider .max-range" ).html( $currency + numberWithCommas( ui.values[ 1 ] ) );
			            <?php } ?>
			            $( "#ct_price_from" ).val(ui.values[ 0 ]);
			            $( "#ct_price_to" ).val(ui.values[ 1 ]);
			        }
			    });

			    // slider range data tooltip set
			    var $handler = $("#slider-range .ui-slider-handle");

			    <?php if($ct_currency_placement == 'after') { ?>
				    $handler.eq(0).append("<b class='amount'><span id='min'>"+numberWithCommas($( "#slider-range" ).slider( "values", 0 ))+$currency+"</span></b>");
				    $handler.eq(1).append("<b class='amount'><span id='max'>"+numberWithCommas($( "#slider-range" ).slider( "values", 1 ))+$currency+"</span></b>");
				<?php } else { ?>
				    $handler.eq(0).append("<b class='amount'><span id='min'>"+$currency+numberWithCommas($( "#slider-range" ).slider( "values", 0 ))+"</span></b>");
				    $handler.eq(1).append("<b class='amount'><span id='max'>"+$currency+numberWithCommas($( "#slider-range" ).slider( "values", 1 ))+"</span></b>");
				<?php } ?>

			    // slider range pointer mousedown event
			    $handler.on("mousedown",function(e){
			        e.preventDefault();
			        $(this).children(".amount").fadeIn(300);
			    });

			    // slider range pointer mouseup event
			    $handler.on("mouseup",function(e){
			        e.preventDefault();
			        $(this).children(".amount").fadeOut(300);
			    });

			    // Size From / To
			    // Square feet
			    <?php
			    $requested_ct_sqft_from_options = array('options'=>array('default'=> $ct_adv_search_size_slider_min_value ));
                $requested_ct_sqft_from = filter_input(INPUT_GET,'ct_sqft_from', FILTER_VALIDATE_INT, $requested_ct_sqft_from_options);

                $requested_ct_sqft_to_options = array('options'=>array('default'=> $ct_adv_search_size_slider_max_value ));
                $requested_ct_sqft_to = filter_input(INPUT_GET,'ct_sqft_to', FILTER_VALIDATE_INT, $requested_ct_sqft_to_options);
			    ?>
			    $( "#slider-range-two" ).slider({
			        range: true,
			        min: <?php echo absint( $ct_adv_search_size_slider_min_value ); ?>,
			        step: 100,
			        max: <?php echo absint( $ct_adv_search_size_slider_max_value ) ?>,
			        values: [ <?php echo absint( $requested_ct_sqft_from ); ?>,<?php echo absint( $requested_ct_sqft_to ); ?>],
			        slide: function( event, ui ) {
			            $( "#slider-range-two #min" ).html(numberWithCommas(ui.values[ 0 ]) );
			            $( "#slider-range-two #max" ).html(numberWithCommas(ui.values[ 1 ]) );
			            $( "#size-from-to-slider .min-range" ).html( numberWithCommas( ui.values[ 0 ] ) + $sqftsm);
			            $( "#size-from-to-slider .max-range" ).html( numberWithCommas( ui.values[ 1 ] ) + $sqftsm);
			            $( "#ct_sqft_from" ).val(ui.values[ 0 ]);
			            $( "#ct_sqft_to" ).val(ui.values[ 1 ]);
			        }
			    });

			    // slider range data tooltip set
			    var $handlertwo = $("#slider-range-two .ui-slider-handle");

			    $( "#size-from-to-slider .min-range" ).append( $sqftsm );
			    $( "#size-from-to-slider .max-range" ).append( $sqftsm );

			    $handlertwo.eq(0).append("<b class='amount'><span id='min'>"+numberWithCommas($( "#slider-range-two" ).slider( "values", 0 )) +"</span>" +$sqftsm+ "</b>");
			    $handlertwo.eq(1).append("<b class='amount'><span id='max'>"+numberWithCommas($( "#slider-range-two" ).slider( "values", 1 )) +"</span>" +$sqftsm+ "</b>");

			    // slider range pointer mousedown event
			    $handlertwo.on("mousedown",function(e){
			        e.preventDefault();
			        $(this).children(".amount").fadeIn(300);
			    });

			    // slider range pointer mouseup event
			    $handlertwo.on("mouseup",function(e){
			        e.preventDefault();
			        $(this).children(".amount").fadeOut(300);
			    });

			    <?php
			    $requested_lot_size_from_options = array('options'=>array('default'=> $ct_adv_search_lot_size_slider_min_value ));
                $requested_lot_size_from = filter_input(INPUT_GET,'ct_lotsize_from', FILTER_VALIDATE_INT, $requested_lot_size_from_options);

                $requested_lot_size_to_options = array('options'=>array('default'=> $ct_adv_search_lot_size_slider_max_value ));
                $requested_lot_size_to = filter_input(INPUT_GET,'ct_lotsize_to', FILTER_VALIDATE_INT, $requested_lot_size_to_options);
			    ?>
			    // Lotsize From / To
			    $( "#slider-range-three" ).slider({
			        range: true,
			        min: <?php echo absint($ct_adv_search_lot_size_slider_min_value); ?>,
			        step: 1,
			        max: <?php echo absint($ct_adv_search_lot_size_slider_max_value); ?>,
			        values: [ <?php echo absint( $requested_lot_size_from ); ?>, <?php echo absint( $requested_lot_size_to ); ?>],
			        slide: function( event, ui ) {
			            $( "#slider-range-three #min" ).html(numberWithCommas(ui.values[ 0 ]) );
			            $( "#slider-range-three #max" ).html(numberWithCommas(ui.values[ 1 ]) );
			            $( "#lotsize-from-to-slider .min-range" ).html( numberWithCommas( numberWithCommas( ui.values[ 0 ] ) + " " +$acres) );
			            $( "#lotsize-from-to-slider .max-range" ).html( numberWithCommas( numberWithCommas( ui.values[ 1 ] ) + " " +$acres) );
			            $( "#ct_lotsize_from" ).val(ui.values[ 0 ]);
			            $( "#ct_lotsize_to" ).val(ui.values[ 1 ]);
			        }
			    });

			    // slider range data tooltip set
			    var $handlerthree = $("#slider-range-three .ui-slider-handle");

			    $( "#lotsize-from-to-slider .min-range" ).append( " " + $acres );
			    $( "#lotsize-from-to-slider .max-range" ).append( " " + $acres );

			    $( "#lotsize-from-to-slider .min-range" ).replaceWith( "<span class='min-range'>" + numberWithCommas($( "#slider-range-three" ).slider( "values", 0 )) + " " + $acres + "</span>" );

			    $handlerthree.eq(0).append("<b class='amount'><span id='min'>"+numberWithCommas($( "#slider-range-three" ).slider( "values", 0 )) +"</span> " +$acres+ "</b>");
			    $handlerthree.eq(1).append("<b class='amount'><span id='max'>"+numberWithCommas($( "#slider-range-three" ).slider( "values", 1 )) +"</span> " +$acres+ "</b>");

			    // slider range pointer mousedown event
			    $handlerthree.on("mousedown",function(e){
			        e.preventDefault();
			        $(this).children(".amount").fadeIn(300);
			    });

			    // slider range pointer mouseup event
			    $handlerthree.on("mouseup",function(e){
			        e.preventDefault();
			        $(this).children(".amount").fadeOut(300);
			    });

			});

	        jQuery(window).load(function() {

				<?php

				$ct_home_adv_search_fields = isset( $ct_options['ct_home_adv_search_fields']['enabled'] ) ? $ct_options['ct_home_adv_search_fields']['enabled'] : [];
				$ct_adv_search_more_layout = isset( $ct_options['ct_adv_search_more_layout'] ) ? esc_html( $ct_options['ct_adv_search_more_layout'] ) : '';

				/*-----------------------------------------------------------------------------------*/
				/* Custom JS */
				/*-----------------------------------------------------------------------------------*/

				if(!empty( $ct_options['ct_custom_js'])) {
					echo ct_sanitize_output( $ct_options['ct_custom_js'] );
				} 
				?>

				jQuery('#filters-search-options-toggle').click(function(event){
					jQuery('#header-search-inner-wrap').toggle();
				});

				jQuery('#more-search-options-toggle').click(function(event){
					<?php
					if($ct_adv_search_more_layout == 'adv-search-more-two' || in_array('Type (multi)', $ct_home_adv_search_fields) || in_array('Status (multi)', $ct_home_adv_search_fields) || in_array('Popular Features', $ct_home_adv_search_fields) || in_array('Beds +', $ct_home_adv_search_fields) || in_array('Baths +', $ct_home_adv_search_fields) || in_array('Additional Features', $ct_home_adv_search_fields) || in_array('Popular Features', $ct_home_adv_search_fields)) { ?>
						jQuery('#more-search-options').toggle();
						jQuery('body').addClass('noscroll');
						console.log('more clicked');
					<?php } else { ?>
						jQuery('#more-search-options').slideToggle('fast');
					<?php } ?>
				});

				<?php
					if($ct_adv_search_more_layout == 'adv-search-more-two' || in_array('Type (multi)', $ct_home_adv_search_fields) || in_array('Status (multi)', $ct_home_adv_search_fields) || in_array('Popular Features', $ct_home_adv_search_fields) || in_array('Beds +', $ct_home_adv_search_fields) || in_array('Baths +', $ct_home_adv_search_fields) || in_array('Additional Features', $ct_home_adv_search_fields) || in_array('Popular Features', $ct_home_adv_search_fields)) { ?>
					jQuery(document).on('mouseup', function(e) {
		                if(!jQuery(e.target).closest('#more-search-options').length) {
		                    jQuery('#more-search-options').each(function(){
		                        jQuery(this).hide();
		                        jQuery('body').removeClass('noscroll');
		                    });
		                }
		           	});
		        <?php } ?> 

				<?php
				$ct_listing_single_layout = isset( $ct_options['ct_listing_single_layout'] ) ? esc_html( $ct_options['ct_listing_single_layout'] ) : '';
				if($ct_listing_single_layout == 'listings-two' && is_singular( 'listings' )) { ?>
					// Single Listing Carousel
					var owl = jQuery('.owl-carousel');
					owl.owlCarousel({
					    items: 3,
					    loop: true,
					    margin: 0,
					    nav: false,
					    dots: false,
					    autoplay: true,
					    autoplayTimeout: 4000,
					    autoplayHoverPause: true,
					    responsive:{
					        0:{
					            items: 1,
					            nav: false
					        },
					        600:{
					            items: 2,
					            nav: false
					        },
					        1000:{
					            items: 3,
					            nav: false,
					            loop: false
					        }
					    }
					});
				<?php } ?>

				<?php
				$ct_sub_listings_carousel = isset( $ct_options['ct_sub_listings_carousel'] ) ? $ct_options['ct_sub_listings_carousel'] : '';
				if($ct_sub_listings_carousel == 'yes') { ?>
					var owl = jQuery('#owl-listings-carousel-sub-listings');
					owl.owlCarousel({
					    items: 3,
					    slideBy: 3,
					    loop: false,
					    //rewind: true,
					    margin: 20,
					    nav: true,
					    navContainer: '#ct-listings-carousel-nav-sub-listings',
					    navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
					    dots: false,
					    autoplay: false,
					    responsive:{
					        0:{
					            items: 1,
					            nav: false
					        },
					        600:{
					            items: 2,
					            nav: false
					        },
					        1000:{
					            items: 3,
					            nav: true,
					        }
					    }
					});
				<?php } ?>

		        <?php
		        if( !is_singular('listings') ) { ?>
		            jQuery('.flexslider').flexslider({
		                animation: "<?php echo strtolower($ct_options['ct_flex_animation']); ?>",
		                slideDirection: "<?php echo strtolower($ct_options['ct_flex_direction']); ?>",
		                <?php if(!empty($ct_options['ct_flex_autoplay'])) { ?>
		                slideshow: "<?php echo strtolower($ct_options['ct_flex_autoplay']); ?>",
		                <?php } else { ?>
	                	slideshow: true,
	                	<?php } ?>
		                <?php if(!empty($ct_options['ct_flex_speed'])) { ?>
		                slideshowSpeed: <?php echo esc_html($ct_options['ct_flex_speed']); ?>,
			            <?php } ?>
			            <?php if(!empty($ct_options['ct_flex_duration'])) { ?>
		                animationDuration: <?php echo esc_html($ct_options['ct_flex_duration']); ?>,
		                <?php } ?>
		                controlNav: false,
		            	directionNav: true,
		                keyboardNav: true,
		                <?php if(!empty($ct_options['ct_flex_randomize'])) { ?>
		                randomize: <?php echo esc_html($ct_options['ct_flex_randomize']); ?>,
		                <?php } ?>
		                pauseOnAction: true,
		                pauseOnHover: false,
		                animationLoop: true,
		                smoothHeight: true,
		            });
	            <?php } ?>
	        });
	    </script>

	    <?php
		    $ct_enable_zapier_webhooks = isset( $ct_options['ct_enable_zapier_webhooks'] ) ? $ct_options['ct_enable_zapier_webhooks'] : '';
			$ct_zapier_webhook_url = isset( $ct_options['ct_zapier_webhook_url'] ) ? $ct_options['ct_zapier_webhook_url'] : '';
			$ct_zapier_webhook_contact_page_form = isset( $ct_options['ct_zapier_webhook_contact_page_form'] ) ? $ct_options['ct_zapier_webhook_contact_page_form'] : '';
			
			if(is_page_template('template-contact.php')) { ?>
				<?php if($ct_enable_zapier_webhooks == 'yes' && $ct_zapier_webhook_url != '' && $ct_zapier_webhook_contact_page_form == true) {
					$ajaxSubmitFile = get_template_directory_uri() . '/includes/ajax-submit-contact-zapier.php';
				} else {
					$ajaxSubmitFile = get_template_directory_uri() . '/includes/ajax-submit-contact.php';
				} ?>
				<script>
				jQuery(document).ready(function() {
					jQuery("#contactform").validationEngine({
						ajaxSubmit: true,
						ajaxSubmitFile: "<?php echo esc_url( $ajaxSubmitFile ); ?>",
						ajaxSubmitMessage: "<?php $contact_success = str_replace(array("\r\n", "\r", "\n"), " ", $ct_options['ct_contact_success']); echo esc_html($contact_success); ?>",
						success :  false,
						failure : function() {}
					});
				});
				</script>
		<?php } ?>

	    <?php
		    $ct_enable_zapier_webhooks = isset( $ct_options['ct_enable_zapier_webhooks'] ) ? $ct_options['ct_enable_zapier_webhooks'] : '';
			$ct_zapier_webhook_url = isset( $ct_options['ct_zapier_webhook_url'] ) ? $ct_options['ct_zapier_webhook_url'] : '';
			$ct_zapier_webhook_listing_single_form = isset( $ct_options['ct_zapier_webhook_listing_single_form'] ) ? $ct_options['ct_zapier_webhook_listing_single_form'] : '';
		
	    	if( is_singular('listings') ) { ?>
				<script>
				jQuery(window).load(function() {
					jQuery('#carousel').flexslider({
						animation: "slide",
						controlNav: false,
						animateHeight: true,
						directionNav: true,
						animationLoop: false,
						<?php if(!empty($ct_options['ct_flex_autoplay'])) { ?>
		                slideshow: "<?php echo strtolower($ct_options['ct_flex_autoplay']); ?>",
		                <?php } else { ?>
	                	slideshow: true,
	                	<?php } ?>
						<?php if(!empty($ct_options['ct_flex_speed'])) { ?>
						slideshowSpeed: <?php echo esc_html($ct_options['ct_flex_speed']); ?>,
						<?php } ?>
						<?php if(!empty($ct_options['ct_flex_duration'])) { ?>
						animationDuration: <?php echo esc_html($ct_options['ct_flex_duration']); ?>,
						<?php } ?>
						itemWidth: 120,
						itemMargin: 0,
						asNavFor: '#slider'
					});

					jQuery('#slider').flexslider({
						<?php if(!empty($ct_options['ct_flex_animation'])) { ?>
		                animation: "<?php echo strtolower($ct_options['ct_flex_animation']); ?>",
		                <?php } ?>
		                <?php if(!empty($ct_options['ct_flex_direction'])) { ?>
		                slideDirection: "<?php echo strtolower($ct_options['ct_flex_direction']); ?>",
		                <?php } ?>
		                <?php if(!empty($ct_options['ct_flex_autoplay'])) { ?>
		                slideshow: "<?php echo strtolower($ct_options['ct_flex_autoplay']); ?>",
		                <?php } else { ?>
	                	slideshow: true,
	                	<?php } ?>
		                <?php if(!empty($ct_options['ct_flex_speed'])) { ?>
		                slideshowSpeed: <?php echo esc_html($ct_options['ct_flex_speed']); ?>,
		                <?php } ?>
		                <?php if(!empty($ct_options['ct_flex_duration'])) { ?>
		                animationDuration: <?php echo esc_html($ct_options['ct_flex_duration']); ?>,
		                <?php } ?>
						smoothHeight: true,
						controlNav: false,
						animationLoop: false,
						slideshow: false,
						sync: "#carousel"
					});

		            jQuery('.flexslider').flexslider({
		            	<?php if(!empty($ct_options['ct_flex_animation'])) { ?>
		                animation: "<?php echo strtolower($ct_options['ct_flex_animation']); ?>",
		                <?php } ?>
		                <?php if(!empty($ct_options['ct_flex_direction'])) { ?>
		                slideDirection: "<?php echo strtolower($ct_options['ct_flex_direction']); ?>",
		                <?php } ?>
		                <?php if(!empty($ct_options['ct_flex_autoplay'])) { ?>
		                slideshow: "<?php echo strtolower($ct_options['ct_flex_autoplay']); ?>",
		                <?php } else { ?>
	                	slideshow: true,
	                	<?php } ?>
		                <?php if(!empty($ct_options['ct_flex_speed'])) { ?>
		                slideshowSpeed: <?php echo esc_html($ct_options['ct_flex_speed']); ?>,
		                <?php } ?>
		                <?php if(!empty($ct_options['ct_flex_duration'])) { ?>
		                animationDuration: <?php echo esc_html($ct_options['ct_flex_duration']); ?>,
		                <?php } ?>
		                controlNav: false,
		                directionNav: true,
		                keyboardNav: true,
		                <?php if(!empty($ct_options['ct_flex_randomize'])) { ?>
		                randomize: <?php echo esc_html($ct_options['ct_flex_randomize']); ?>,
		                <?php } ?>
		                pauseOnAction: true,
		                pauseOnHover: false,
		                animationLoop: true
		            });
				});

				<?php 
					if($ct_enable_zapier_webhooks == 'yes' && $ct_zapier_webhook_url != '' && $ct_zapier_webhook_listing_single_form == true) {
						$ajaxSubmitFile = get_template_directory_uri() . '/includes/ajax-submit-listings-zapier.php';
					} else {
						$ajaxSubmitFile = get_template_directory_uri() . '/includes/ajax-submit-listings.php';
					}
				?>
				<?php $ct_contact_success = ''; ?>
				<?php if ( isset( $ct_options['ct_contact_success'] ) ): ?>
					<?php $ct_contact_success = $ct_options['ct_contact_success']; ?>
				<?php endif; ?>
				jQuery(document).ready(function() {
					jQuery("#listingscontact,.listingscontact-form").validationEngine({
						ajaxSubmit: true,
						ajaxSubmitFile: "<?php echo esc_url( $ajaxSubmitFile ); ?>",
						ajaxSubmitMessage: "<?php $contact_success = str_replace(array("\r\n", "\r", "\n"), " ", $ct_contact_success); echo esc_html($contact_success); ?>",
						success :  false,
						failure : function() {}
					});
					jQuery('.gallery-item').magnificPopup({
						type: 'image',
						gallery:{
							enabled: true
						}
					});
				});
				</script>
	    <?php } ?>

	    <?php if(is_page_template('template-submit-listing.php') || is_page_template('template-edit-listing.php')) { ?>
			<script>
				jQuery(function() {

					function Onnextload(){
						var customcords = jQuery('#customMetaLatLng').val();
						var ccords=customcords.split(",");
						var mlat=ccords[0];
						var mlng=ccords[1];
						jQuery("#pac-input").geocomplete({
							map: "#map-canvas",
							details: "form",
							zoom:10,
							location: [mlat, mlng],
							markerOptions: {
					            draggable: true
					        },
							types: ["geocode", "establishment"],
						});
						jQuery("#pac-input").geocomplete("find", jQuery("input[name=customMetaLatLng]").val());

						setTimeout(function(){
							jQuery(".pac-container").prependTo("#autocomplete-results");
							}, 300);
						}

						var customcords = jQuery('#customMetaLatLng').val();
						var ccords = customcords.split(",");
						var mlat = ccords[0];
						var mlng = ccords[1];
						jQuery("#pac-input").geocomplete({
							map: "#map-canvas",
							details: "form",
							zoom:10,
							location: [mlat, mlng],
							markerOptions: {
					            draggable: true
					        },
							types: ["geocode", "establishment"],
						});
						jQuery("#pac-input").geocomplete("find", jQuery("input[name=customMetaLatLng]").val());

						setTimeout(function(){
							jQuery(".pac-container").prependTo("#autocomplete-results");
						}, 300);

						jQuery("#pac-input").bind("geocode:dragged", function(event, latLng){
						$("input[name=lat]").val(latLng.lat());
						$("input[name=lng]").val(latLng.lng());
						$("#pac-input").geocomplete("find", latLng.toString());
							var latlong = latLng.lat() + ', ' + latLng.lng();
							jQuery("input[name=customMetaLatLng]").val(latlong);
								var options = {
								map: "#map-canvas",
								mapOptions: {
									streetViewControl: false,
									mapTypeId : google.maps.MapTypeId.HYBRID
								},
								markerOptions: {
									draggable: true
								}
							};
						var map = $("#pac-input").geocomplete("map");
					    map.panTo(latLng);
					    var geocoder = new google.maps.Geocoder();
					    geocoder.geocode({'latLng': latLng }, function(results, status) {
							if (status == google.maps.GeocoderStatus.OK) {
								if (results[0]) {
									//$('#pac-input').val(results[0].formatted_address);
									//var zipcode = results[0].address_components[0].long_name;
									//$('#customTaxZip').val(zipcode);
								}
							}
						});
					});

					window.Onnextload = Onnextload;

					jQuery("#pac-input").click(function(){
							jQuery("#pac-input").trigger("geocode");
						});
					});
					</script>
	    <?php } ?>

		<?php
		$current_user = wp_get_current_user();
		$ct_header_listing_search = isset( $ct_options['ct_header_listing_search'] ) ? esc_html( $ct_options['ct_header_listing_search'] ) : '';
		$ct_header_listing_search_hide_user_loggedin = isset( $ct_options['ct_header_listing_search_hide_user_loggedin'] ) ? esc_html( $ct_options['ct_header_listing_search_hide_user_loggedin'] ) : '';
		$ct_use_propinfo_icons = isset( $ct_options['ct_use_propinfo_icons'] ) ? esc_html( $ct_options['ct_use_propinfo_icons'] ) : '';

		/*-----------------------------------------------------------------------------------*/
		/* Custom Google Fonts & iHomeFinder CSS & IDX Broker */
		/*-----------------------------------------------------------------------------------*/

		echo '<style type="text/css">';
			if(isset($ct_options['ct_heading_font'])) {
				echo 'h1, h2, h3, h4, h5, h6 { font-family: "' . esc_html($ct_options['ct_heading_font']) . '";}';
			}
			if(isset($ct_options['ct_body_font'])) {
				echo 'body, .slider-wrap, input[type="submit"].btn { font-family: "' . esc_html($ct_options['ct_body_font']) . '";}';
			}
			echo '.fa-close:before { content: "\f00d";}';
			if($ct_header_listing_search == 'yes') { echo '.search-listings #map-wrap { margin-bottom: 0; background-color: #fff;} span.map-toggle, span.search-toggle { border-bottom-right-radius: 3px;} span.searching { border-bottom-left-radius: 3px;}'; }
			if($ct_header_listing_search_hide_user_loggedin == 'yes' && in_array('administrator', (array) $current_user->roles) || in_array('editor', (array) $current_user->roles) || in_array('author', (array) $current_user->roles) || in_array('contributor', (array) $current_user->roles) || in_array('seller', (array) $current_user->roles) || in_array('agent', (array) $current_user->roles) || in_array('broker', (array) $current_user->roles)) {
				echo '.page-template-template-leads-pro #header-search-wrap #advanced_search,
				.page-template-ct-leads-pro-page-template #header-search-wrap #advanced_search,
				.page-template-template-user-dashboard #header-search-wrap,
				.page-template-template-submit-listing #header-search-wrap,
				.page-template-template-edit-listing #header-search-wrap,
				.page-template-template-view-listings #header-search-wrap,
				.page-template-template-membership #header-search-wrap,
				.page-template-template-view-invoices #header-search-wrap,
				.page-template-template-listing-analytics #header-search-wrap,
				.page-template-template-edit-profile #header-search-wrap { display: none;}';
				echo '.page-template-template-recently-viewed #main-content,
				.page-template-template-favorite-listings #main-content,
				.page-template-template-listing-email-alerts #main-content,
				.page-template-template-user-dashboard #main-content,
				.page-template-template-submit-listing #main-content,
				.page-template-template-edit-listing #main-content,
				.page-template-template-view-listings #main-content,
				.page-template-template-membership #main-content,
				.page-template-template-view-invoices #main-content,
				.page-template-template-listing-analytics #main-content,
				.page-template-template-edit-profile #main-content { border-top: 1px solid #d5d9dd;}';
					echo '.page-template-template-leads-pro #header-search-wrap,
					.page-template-ct-leads-pro-page-template #header-search-wrap { padding: 10px 0 0 0; background: #edeff0;}';
			}
			if($ct_use_propinfo_icons == 'icons') { echo '.propinfo li { line-height: 2.35em;} .row.baths svg { position: relative; top: 3px; left: -2px;} .row.sqft svg { position: relative; top: 3px;}'; }
			// Styles for iHomeFinder
			if(function_exists('optima_express_register_widgets')) {
				echo '#searchProfile select { z-index: 99;}';
				echo '.ihf-widget { padding: 20px; overflow: visible;}';
				echo '.widget.widget_ihomefinderpropertiesgallery .gallery-prop-info { padding: 20px;}';
				echo '#ihf-main-container .modal { z-index: 9999999;}';
				echo '.ihf-container-modal .modal-backdrop { z-index: 999999;}';
				echo '#ihf-main-container .modal.in .modal-dialog { transform: translate(0,260px);}';
				echo '#ihf-main-container .customSelect.form-control { display: none !important;}';
				echo '#ihf-main-container .carousel-control { height: auto; background: none; border: none;}';
				echo '#ihf-main-container .carousel-caption { background: none;}';
				echo '#ihf-main-container .modal { width: auto; margin-left: 0; background-color: transparent; border: 0;}';
				echo '.ihf-results-links > a:nth-child(1) { display: none;}';
				echo '#ihf-main-container .btn { height: initial !important; line-height: initial !important;}';
				echo '#ihf-main-container .ihf-social-share .btn { height: 24px !important;}';
				echo '.widget .dsidx-resp-search-form { padding: 20px 20px 0 20px;}';
				echo '.widget .dsidx-resp-area input[type="text"], .dsidx-resp-area select { position: relative !important; opacity: 100 !important;}';
				echo '.widget .dsidx-resp-search-form .dsidx-resp-area .customSelect { display: none !important;}';
			}
			// Styles for IDX Broker 
			if(class_exists('Idx_Broker_Plugin')) {
				echo '.idx-omnibar-form input { line-height: 2em;}';
				echo '.idx-omnibar-form button.idx-omnibar-extra-button { height: auto; margin-bottom: 10px; padding: 1% 0; font-size: .9em; background: #29333d; color: #fff; vertical-align: initial; border: none;}';
				echo '.IDX-qsFieldWrap { float: left !important; padding: 0 !important; margin: 0 20px 20px 0 !important; text-align: left !important;}';
				echo '.IDX-quicksearchWrapper input, .IDX-quicksearchWrapper select { width: auto !important;}';
			}
			echo '.form-group { width: 49.0%;}';
			if(!empty($ct_options['ct_header_listing_search_hide_specific_pages'])) {
				foreach($ct_options['ct_header_listing_search_hide_specific_pages'] as $exclude_page_ID) {
					echo '.page-id-' . $exclude_page_ID . ' #header-search-wrap { display: none;}';
				}
			}
		echo '</style>';

		// iHomefinder noConflict
		if(function_exists('optima_express_register_widgets')) {
			echo '<script>';
			echo '(function () {';
			    echo '"use strict";';
			    echo 'jQuery.getScript("/wp-content/plugins/optima-express/js/bootstrap-libs/bootstrap.min.js");';
			echo '}());';
			echo '</script>';
		}

		/*-----------------------------------------------------------------------------------*/
		/* Custom Stylesheet */
		/*-----------------------------------------------------------------------------------*/

		$ct_use_styles = isset( $ct_options['ct_use_styles'] ) ? esc_attr( $ct_options['ct_use_styles'] ) : '';
		if($ct_use_styles == 'yes') {
			include(TEMPLATEPATH . '/includes/custom-stylesheet.php');
	    }

	    /*-----------------------------------------------------------------------------------*/
		/* Custom CSS */
		/*-----------------------------------------------------------------------------------*/

		if(!empty($ct_options['ct_custom_css'])) {
			echo '<style type="text/css">';
				echo ct_sanitize_output( $ct_options['ct_custom_css'] );
			echo '</style>';
		}

		/*-----------------------------------------------------------------------------------*/
		/* Boxed Layout */
		/*-----------------------------------------------------------------------------------*/

		$ct_boxed = isset( $ct_options['ct_boxed'] ) ? esc_attr( $ct_options['ct_boxed'] ) : '';

		if($ct_boxed == 'boxed') {
			echo '<style type="text/css">';
			echo 'body { background-color: #ececec;} #wrapper { background: #fff;} .container { padding-right: 20px !important; padding-left: 20px !important;} #top #top-inner { width: 1020px;} footer { padding-left: 0; padding-right: 0;}';
			echo '</style>';
		}

		/*-----------------------------------------------------------------------------------*/
		/* Full Width Two Layout */
		/*-----------------------------------------------------------------------------------*/

		$ct_boxed = isset( $ct_options['ct_boxed'] ) ? esc_attr( $ct_options['ct_boxed'] ) : '';

		if($ct_boxed == 'full-width-two') {
			echo '<style type="text/css">';
			echo '.container { max-width: 100%; margin-right: 30px; margin-left: 30px;}';
				echo '.side-results .container { margin: 0;}';
				echo '#single-listing-lead.container, #single-listing-content.container, #page-content.container { margin-right: auto; margin-left: auto;}';
			echo '.listing.idx-listing figure img { width: auto;}';
			echo '</style>';
		}

		/*-----------------------------------------------------------------------------------*/
		/* Listing Modal & Listing Single Facebook OG */
		/*-----------------------------------------------------------------------------------*/

		if(is_singular('listings')) { 
			$ct_listing_id = get_queried_object_id();
			ct_facebook_og_listing($ct_listing_id);
		}

		if(is_singular('post')) {
			ct_single_post_display_og_meta();
		}

		/*-----------------------------------------------------------------------------------*/
		/* Login/Register after X Views */
		/*-----------------------------------------------------------------------------------*/

		$ct_listings_login_register_after_x_views = isset( $ct_options['ct_listings_login_register_after_x_views'] ) ? esc_html( $ct_options['ct_listings_login_register_after_x_views'] ) : '';

		if($ct_listings_login_register_after_x_views == 'yes' && !is_user_logged_in()) {
			ct_display_login_register_after_x_views();
		}

	}
}
add_action('wp_head', 'ct_wp_head');

/*-----------------------------------------------------------------------------------*/
/* CT Footer */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_wp_footer')) {
	function ct_wp_footer() {
		global $ct_options;

		$ct_secondary_bg_color = isset( $ct_options['ct_secondary_bg_color']['color'] ) ? esc_attr( $ct_options['ct_secondary_bg_color']['color'] ) : '';

		if(is_page_template('template-user-dashboard.php')) {
			echo '<script>';
				// Lead Activity
				if(!empty($ct_secondary_bg_color)) {
					echo 'var secondaryBGColor = "' . $ct_secondary_bg_color . '";';
				} else {
					echo 'var secondaryBGColor = "#03b5c3;"';
				}
		    echo '</script>';
	    }

    	// IDX Footer
        if(class_exists('IDX')) {
            
            $oIDX = new IDX();
            $idxFooter = $oIDX->ct_idx_footer_text();

            if($idxFooter != '') {
            	echo '<footer id="ct-idx-footer">';
	                echo '<div class="container padT0">';
	                    echo ct_sanitize_output( $idxFooter );
	                    echo ' - Powered by RealtyWatch Solutions';
	                echo '</div>';
                echo '</footer>';
            }
        }

	}
}
add_action('wp_footer', 'ct_wp_footer');

/*-----------------------------------------------------------------------------------*/
/* Add Buyer User Role */
/*-----------------------------------------------------------------------------------*/

$ct_add_buyer_role = add_role(
	'buyer',
	__('Buyer', 'contempo'),
	array(
		'read'						=> true,
	)
);

/*-----------------------------------------------------------------------------------*/
/* Add Seller User Role */
/*-----------------------------------------------------------------------------------*/

$ct_add_seller_role = add_role(
	'seller',
	__('Seller', 'contempo'),
	array(
		'delete_posts'				=> true,
        'delete_published_posts'	=> true,
        'edit_posts'				=> true,
        'edit_published_posts'		=> true,
        'edit_others_posts'			=> false,
        'publish_posts'				=> true,
		'read'						=> true,
        'upload_files'				=> true,
	)
);

/*-----------------------------------------------------------------------------------*/
/* Add Agent User Role */
/*-----------------------------------------------------------------------------------*/

$ct_add_agent_role = add_role(
	'agent',
	__('Agent', 'contempo'),
	array(
		'delete_posts'				=> true,
        'delete_published_posts'	=> true,
        'edit_posts'				=> true,
        'edit_published_posts'		=> true,
        'edit_others_posts'			=> false,
        'publish_posts'				=> true,
		'read'						=> true,
        'upload_files'				=> true,
	)
);

/*-----------------------------------------------------------------------------------*/
/* Add Broker User Role */
/*-----------------------------------------------------------------------------------*/

$ct_add_broker_role = add_role(
	'broker',
	__('Broker', 'contempo'),
	array(
		'delete_posts'				=> true,
        'delete_published_posts'	=> true,
        'edit_posts'				=> true,
        'edit_published_posts'		=> true,
        'edit_others_posts'			=> false,
        'publish_posts'				=> true,
		'read'						=> true,
        'upload_files'				=> true,
	)
);

/*-----------------------------------------------------------------------------------*/
/* Login/Register User Dropdown */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_login_register_user_drop')) {
	function ct_login_register_user_drop() {
		global $ct_options;

		$current_user = wp_get_current_user();

		$url = 'http://' . ct_get_server_info('SERVER_NAME') . ct_get_server_info('REQUEST_URI');

		$ct_enable_front_end_login = isset( $ct_options['ct_enable_front_end_login'] ) ? esc_html( $ct_options['ct_enable_front_end_login'] ) : '';
		$ct_enable_front_end_registration = isset( $ct_options['ct_enable_front_end_registration'] ) ? esc_html( $ct_options['ct_enable_front_end_registration'] ) : '';
		$ct_front_end_login_registration_text_or_icon = isset( $ct_options['ct_front_end_login_registration_text_or_icon'] ) ? esc_html( $ct_options['ct_front_end_login_registration_text_or_icon'] ) : '';	

		if($ct_enable_front_end_login != 'no') { ?>
	        <ul class="user-frontend right <?php if(!is_user_logged_in()) { echo 'not-logged-in'; } ?>">
	            <?php if(is_user_logged_in()) {
	            		$ct_user_dashboard = isset( $ct_options['ct_user_dashboard'] ) ? esc_html( $ct_options['ct_user_dashboard'] ) : '';
	                    $ct_saved_listings = isset( $ct_options['ct_saved_listings'] ) ? esc_html( $ct_options['ct_saved_listings'] ) : '';
	                    $ct_listing_email_alerts_page_id = isset( $ct_options['ct_listing_email_alerts_page_id'] ) ? esc_attr( $ct_options['ct_listing_email_alerts_page_id'] ) : '';
	                    $ct_submit_listing = isset( $ct_options['ct_submit'] ) ? esc_html( $ct_options['ct_submit'] ) : '';
	                    $ct_view_listings = isset( $ct_options['ct_view'] ) ? esc_html( $ct_options['ct_view'] ) : '';
	                    $ct_membership = isset( $ct_options['ct_membership'] ) ? esc_html( $ct_options['ct_membership'] ) : '';
	                    $ct_invoices = isset( $ct_options['ct_invoices'] ) ? esc_html( $ct_options['ct_invoices'] ) : '';
	                    $ct_listing_analytics = isset( $ct_options['ct_listing_analytics'] ) ? esc_html( $ct_options['ct_listing_analytics'] ) : '';
	                    $ct_user_profile = isset( $ct_options['ct_profile'] ) ? esc_html( $ct_options['ct_profile'] ) : '';
	                ?>
	            	<li class="user-logged-in">
	            		<a href="#">
	                		<span class="user-name"><?php esc_html_e('Hi, ','contempo'); ?><?php echo esc_html($current_user->user_firstname); ?></span>
	                		<figure>		                    		
	                    		<?php

	                    		$profile_img = $current_user->ct_profile_url;
	                    		
	                    		if(empty($profile_img)) {
	                              $profile_img = get_avatar_url($current_user->ID);
	                            } elseif(empty($profile_img)) {
	                              $profile_img = get_template_directory_uri() . '/images/blank-user.png';
	                            } ?>

	                    		<img class="author-img" src="<?php echo esc_url($profile_img); ?>" rel="<?php echo esc_html($current_user->user_firstname); ?>" />
			                </figure>

			                <?php
								$count_value = apply_filters('ct_header_author_count', false);						

								if(is_super_admin() || in_array('administrator', (array) $current_user->roles) || in_array('editor', (array) $current_user->roles) || in_array('author', (array) $current_user->roles) || in_array('contributor', (array) $current_user->roles) || in_array('seller', (array) $current_user->roles) || in_array('agent', (array) $current_user->roles) || in_array('broker', (array) $current_user->roles)) {

									$ct_user_listings = ct_listing_post_count($current_user->ID, 'listings');
									if($count_value === false && $ct_user_listings >= 1 && $ct_options['ct_enable_front_end'] == 'yes') {
										$count_value = $ct_user_listings;
										
									}
	            	
									if($count_value > 0) {
										echo '<span class="user-data-count-alert">' . $count_value . '</span>';
									}
								} elseif(in_array('buyer', (array) $current_user->roles) || in_array('subscriber', (array) $current_user->roles)) {
									if(function_exists('ctea_get_saved_alerts_count_alert')) {
										 ctea_get_saved_alerts_count_alert();
									}
								}
			                ?>
		                </a>
	                	<ul class="user-drop">
	                		<?php if(is_super_admin() || in_array('administrator', (array) $current_user->roles) || in_array('editor', (array) $current_user->roles) || in_array('author', (array) $current_user->roles) || in_array('contributor', (array) $current_user->roles) || in_array('seller', (array) $current_user->roles) || in_array('agent', (array) $current_user->roles) || in_array('broker', (array) $current_user->roles)) { ?>
			                    <?php if($ct_user_dashboard != '') { ?>
			                        <li class="dashboard">
										
										<a <?php if(is_page($ct_user_dashboard)) { echo 'class="current"'; } ?> href="<?php echo get_permalink($ct_user_dashboard); ?>"><?php ct_filters_svg(); ?> 
											<?php esc_html_e('Dashboard', 'contempo'); ?>
										</a>
									</li>
			                    <?php } ?>
			                <?php } ?>
			                <?php do_action('first_user_dropdown_menu'); ?>
			                <?php if(in_array('buyer', (array) $current_user->roles) || in_array('subscriber', (array) $current_user->roles)) { ?>
		                    	<?php if($ct_saved_listings != '' && function_exists('wpfp_link')) { ?>
	                                <li class="saved-listings"><a href="<?php echo get_permalink($ct_saved_listings); ?>"><?php ct_heart_outline_svg(); ?> <?php esc_html_e('Favorite Listings', 'contempo'); ?></a></li>
	                            <?php } ?>
                           	<?php } ?>
                           	<?php if($ct_listing_email_alerts_page_id != '' && function_exists('ctea_show_alert_creation')) { ?>
	                            <li class="listing-email-alerts">
	                            	<a href="<?php echo get_permalink($ct_listing_email_alerts_page_id); ?>">
		                            	<?php ct_alert_svg(); ?>
		                            	<?php if(is_super_admin() || in_array('administrator', (array) $current_user->roles) || in_array('editor', (array) $current_user->roles) || in_array('author', (array) $current_user->roles) || in_array('contributor', (array) $current_user->roles) || in_array('seller', (array) $current_user->roles) || in_array('agent', (array) $current_user->roles) || in_array('broker', (array) $current_user->roles)) { ?>
		                            		<?php _e('Lead Alerts', 'contempo'); ?>
		                            	<?php } else { ?>
		                            		<?php _e('Saved Searches', 'contempo'); ?>
		                            	<?php } ?>
		                            	<?php if(function_exists('ctea_get_saved_alerts_count_alert')) { ctea_get_saved_alerts_count(); } ?>
	                            	</a>
	                            </li>
                            <?php } ?>
	                        <?php do_action('middle_user_dropdown_menu'); ?>
	                        <?php if(is_super_admin() || in_array('administrator', (array) $current_user->roles) || in_array('editor', (array) $current_user->roles) || in_array('author', (array) $current_user->roles) || in_array('contributor', (array) $current_user->roles) || in_array('seller', (array) $current_user->roles) || in_array('agent', (array) $current_user->roles) || in_array('broker', (array) $current_user->roles)) { ?>
	                            <?php if(!empty($ct_submit_listing) && $ct_options['ct_enable_front_end'] == 'yes') { ?>
				                    <li class="submit-listing"><a href="<?php echo get_permalink($ct_submit_listing); ?>"><?php ct_plus_circle_svg(); ?> <?php esc_html_e('Submit Listing', 'contempo'); ?></a></li>
			                    <?php } ?>
			                    <?php if(!empty($ct_view_listings) && $ct_options['ct_enable_front_end'] == 'yes') { ?>
				                    <li class="my-listings"><a href="<?php echo get_permalink($ct_view_listings); ?>"><?php ct_listings_svg(); ?> <?php esc_html_e('My Listings', 'contempo'); ?> <span class="user-data-count"><?php echo esc_html($ct_user_listings); ?></span></a></li>
			                    <?php } ?>
			                    <?php if(!empty($ct_membership) && function_exists('ct_create_packages')) { ?>
				                    <li class="membership"><a href="<?php echo get_permalink($ct_membership); ?>"><?php ct_membership_svg(); ?> <?php esc_html_e('Membership', 'contempo'); ?></a></li>
			                    <?php } ?>
			                    <?php if(!empty($ct_invoices) && function_exists('ct_create_packages')) { ?>
				                    <li class="invoices"><a href="<?php echo get_permalink($ct_invoices); ?>"><?php ct_invoice_svg(); ?> <?php esc_html_e('Invoices', 'contempo'); ?></a></li>
			                    <?php } ?>
			                    <?php if(shortcode_exists('ct_listing_analytics') && !empty($ct_listing_analytics)) { ?>
				                    <li class="listing-analytics"><a <?php if(is_page($ct_listing_analytics)) { echo 'class="current"'; } ?> href="<?php echo get_permalink($ct_listing_analytics); ?>"><?php ct_chart_bars_svg_muted(); ?> <?php esc_html_e('Analytics', 'contempo'); ?></a></li>
				                <?php } ?>
		                   <?php } ?>
		                   	
		                    <?php if(!empty($ct_user_profile)) { ?>
			                    <li class="my-account"><a href="<?php echo get_permalink($ct_user_profile); ?>"><?php ct_account_svg(); ?> <?php esc_html_e('Account Settings', 'contempo'); ?></a></li>
		                    <?php } ?>
		                    <?php do_action('last_user_dropdown_menu'); ?>
		                    <li class="logout"><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php ct_logout_svg(); ?> <?php esc_html_e('Logout', 'contempo'); ?></a></li>
	                    </ul>
	                </li>
	            <?php } else { 

	            		$submit_listing = isset( $ct_options['ct_submit'] ) ? esc_html( $ct_options['ct_submit'] ) : '';

	            	?>
	            	<?php if($ct_front_end_login_registration_text_or_icon == 'icon') { ?>
	            		<li class="login-register"><a href="#"><?php ct_user_circle_svg(); ?></a></li>
	            	<?php } else { ?>
	            		<li class="login-register"><a href="#"><?php if($ct_enable_front_end_registration != 'no') { esc_html_e('Login / Register', 'contempo'); } else { esc_html_e('Login', 'contempo'); } ?></a></li>
	            	<?php } ?>
	            	<?php if(!empty($submit_listing) && $ct_options['ct_enable_front_end'] == 'yes') { ?>
	                	<li class="submit-listing"><a class="btn-outline" href="<?php echo get_permalink($submit_listing); ?>"><?php esc_html_e('Submit Listing', 'contempo'); ?></a></li>
	            	<?php } ?>
	            <?php } ?>
	        </ul>
	    <?php }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Delete uploaded files from Edit listing page */
/*-----------------------------------------------------------------------------------*/

add_action('wp_ajax_delete_files', 'ct_delete_files_callback');
add_action('wp_ajax_nopriv_delete_files', 'ct_delete_files_callback');

if(!function_exists('ct_delete_files_callback')) {
	function ct_delete_files_callback(){

		$file_id = $_POST['old_field'];

		if(!empty($file_id)){
			$current_post_id = $_POST['current_post'];
			$current_post_meta = get_post_meta($current_post_id, '_ct_files', true);
			
			unset($current_post_meta[$file_id]);
			
			$update_files_data = update_post_meta($current_post_id, '_ct_files', $current_post_meta);
			
			if(!empty($update_files_data)){
				echo 'true';
			} else {
				echo 'false';
			}
		}
		die;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Ajax Listing Suggest Search with Keyword */
/*-----------------------------------------------------------------------------------*/

add_action('wp_ajax_street_keyword_search', 'ct_street_keyword_search_callback');
add_action('wp_ajax_nopriv_street_keyword_search', 'ct_street_keyword_search_callback');

if(!function_exists('ct_street_keyword_search_callback')) {
	function ct_street_keyword_search_callback(){
		
		/**
		 * Verify the nonce here.
		 */
		$nonce = filter_input( INPUT_POST, 'nonce', FILTER_DEFAULT );

		if(!wp_verify_nonce( $nonce, 'street_keyword_search')) {
			die();
		}
		
		global $wpdb;
		global $ct_options;

		$html = '';

		$ct_exclude_pending_listing_search = isset( $ct_options['ct_exclude_pending_listing_search'] ) ? $ct_options['ct_exclude_pending_listing_search']: '';
		$ct_exclude_sold_listing_search = isset( $ct_options['ct_exclude_sold_listing_search'] ) ? $ct_options['ct_exclude_sold_listing_search']: '';
		$ct_listings_title_formatting = isset( $ct_options['ct_listings_title_formatting'] ) ? esc_html( $ct_options['ct_listings_title_formatting'] ) : '';

		$post_keyword = $_POST['keyword_value'];

		if(!empty($post_keyword)){
			$prepared_posts_data_query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."posts 
				WHERE post_type= 'listings'
				AND post_status= 'publish'
				AND (post_content LIKE %s OR post_title LIKE %s) 
				ORDER BY post_title",
				'%' . $wpdb->esc_like( $post_keyword ) . '%',
				'%' . $wpdb->esc_like( $post_keyword ) . '%'
			);
			$posts_data = $wpdb->get_results( $prepared_posts_data_query );

			$prepared_post_meta_data = $wpdb->prepare("
				SELECT * FROM ".$wpdb->prefix ."posts 
				WHERE ".$wpdb->prefix ."posts.post_status ='publish' 
				AND ".$wpdb->prefix ."posts.post_type= 'listings' 
				AND ".$wpdb->prefix ."posts.ID = (
					SELECT ".$wpdb->prefix ."postmeta.post_id  
						FROM ".$wpdb->prefix ."postmeta  
						WHERE ".$wpdb->prefix ."postmeta.meta_key = '_ct_listing_alt_title'  
						AND ".$wpdb->prefix ."postmeta.meta_value LIKE %s OR ".$wpdb->prefix ."postmeta.meta_key = '_ct_rental_title'  
						AND ".$wpdb->prefix ."postmeta.meta_value LIKE %s )",
						$wpdb->esc_like( "%".$post_keyword. "%" ),
						$wpdb->esc_like( "%".$post_keyword. "%" )
			);
			$post_meta_data = $wpdb->get_results( $prepared_post_meta_data );

			if($ct_exclude_pending_listing_search == 'yes' && $ct_exclude_sold_listing_search == 'yes') {
				$post_terms = get_posts(array(
					'showposts' => -1,
					'post_type' => 'listings',
					'post_status' => 'publish',
					'tax_query' => array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'city',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						 array(
							'taxonomy' => 'zipcode',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'country',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'state',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'community',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
					),
					'tax_query' => array(
						'relation' => 'OR',
						array(
							'taxonomy'  => 'ct_status',
							'field'     => 'slug',
							'terms'     => 'pending',
							'operator'  => 'NOT IN'
						),
						array(
							'taxonomy'  => 'ct_status',
							'field'     => 'slug',
							'terms'     => 'sold',
							'operator'  => 'NOT IN'
						)
					)
				));
			} elseif($ct_exclude_sold_listing_search == 'yes') {
				$post_terms = get_posts(array(
					'showposts' => -1,
					'post_type' => 'listings',
					'post_status' => 'publish',
					'tax_query' => array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'city',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						 array(
							'taxonomy' => 'zipcode',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'country',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'state',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'community',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
					),
					'tax_query' => array(
						array(
							'taxonomy'  => 'ct_status',
							'field'     => 'slug',
							'terms'     => 'sold',
							'operator'  => 'NOT IN'
						)
					)
				));
			} elseif($ct_exclude_pending_listing_search == 'yes') {
				$post_terms = get_posts(array(
					'showposts' => -1,
					'post_type' => 'listings',
					'post_status' => 'publish',
					'tax_query' => array(
						'relation' => 'OR',
						array(
							'taxonomy' => 'city',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						 array(
							'taxonomy' => 'zipcode',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'country',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'state',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'community',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
					),
					'tax_query' => array(
						array(
							'taxonomy'  => 'ct_status',
							'field'     => 'slug',
							'terms'     => 'pending',
							'operator'  => 'NOT IN'
						)
					)
				));
			} else {
				$post_terms = get_posts(array(
					'showposts' => -1,
					'post_type' => 'listings',
					'post_status' => 'publish',
					'tax_query' => array(
					'relation' => 'OR',
						array(
							'taxonomy' => 'city',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						 array(
							'taxonomy' => 'zipcode',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'country',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'state',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
						array(
							'taxonomy' => 'community',
							'field' => 'name',
							'terms' => array($post_keyword)
						),
					)
				));
			}

			if(!empty($posts_data)) {
				$html .= '<ul class="listing-records">';
				 foreach($posts_data as $records){
					$img_src = wp_get_attachment_image_src( get_post_thumbnail_id($records->ID), 'thumbnail_size' );

					$out = '';
					$source = get_post_meta( $records->ID, 'source', true );

					if($source == 'idx-api') {

						$photos = get_post_meta($records->ID, '_ct_slider', true);

						if(!empty($photos)) {

							foreach($photos as $attachment_id => $attachment_url) {
								$out = '<img src="' . esc_url($attachment_url) . '" width="50" />';
								break;
							}
						}
					}

					if($out == '') {
						$thumb_id = get_post_thumbnail_id($records->ID);
						$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id($records->ID),'medium', true);
						$theme_directory_url = get_template_directory_uri();
						if(!empty($thumb_id)) {
							$out = '<img src="' . $thumb_url[0] . '" width="50" />';
						} else {
							$out = '<img src="' . $theme_directory_url . '/images/no-image.png" srcset="' . $theme_directory_url . '/images/no-image@2x.png 2x" width="50" />';
						}
					}

					$beds = strip_tags( get_the_term_list( $records->ID, 'beds', '', ', ', '' ) );
					$baths = strip_tags( get_the_term_list( $records->ID, 'baths', '', ', ', '' ) );
					$sqft = get_post_meta($records->ID, '_ct_sqft', true);

					$property_type = strip_tags( get_the_term_list( $records->ID, 'property_type', '', ', ', '' ) );
					
					if($ct_listings_title_formatting == 'yes') {
						$ct_record_title = ct_sanitize_output($records->post_title);
					} else {
						$ct_record_title = ucwords(strtolower($records->post_title));
					}

					$html .= '<li class="listing_media" att_id ="' . $records->post_title . '">
                            <div class="media-left">
                                <a class="media-object" href="' . get_permalink($records->ID) . '">' . $out . '</a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="' . get_permalink($records->ID) . '">' . $ct_record_title . '</a></h4> 
								<ul class="amenities">';
									if(!empty($beds)) {
										$html .= '<li><strong>'. __('Beds: ', 'contempo') . '</strong>' . $beds.'</li>';
									}
									if(!empty($baths)) {
										$html .= '<li><strong>'. __('Baths: ', 'contempo') . '</strong>' . $baths . '</li>';
									}
									if(!empty($sqft)) {
										$html .= '<li><strong>' . __('Sq Ft: ', 'contempo') . '</strong>' . $sqft . '</li>';
									}
									if(empty($beds) && empty($baths) && empty($sqft)) {
										$html .= '<li>' . ucfirst($property_type) . '</li>';
									}
						$html .= '</ul>								
                            </div>
                        </li>';

				}
				$html .= '</ul>';
				$html .= '<div class="search-listingfooter">';
				if(count($posts_data) == 1){
					$html .= '<span class="search-listingcount">' . __('1 Listing found', 'contempo') . '</span>';
				} else {
					$html .= '<span class="search-listingcount">' . count($posts_data) . __(' Listings found', 'contempo') . '</span>';
				}
				$html .= '</div>';
			} elseif(!empty($post_meta_data)) {
				$html .= '<ul>';
				 foreach($post_meta_data as $metarecords){

					$img_src = wp_get_attachment_image_src( get_post_thumbnail_id($metarecords->ID), 'thumbnail_size' );

					$out = '';
					$source = get_post_meta( $metarecords->ID, 'source', true );

					if($source == 'idx-api') {

						$photos = get_post_meta($metarecords->ID, '_ct_slider', true);

						if(!empty($photos)) {

							foreach($photos as $attachment_id => $attachment_url) {
								$out = '<img src="' . esc_url($attachment_url) . '" width="50" />';
								break;
							}
						}
					}

					if($out == '') {
						$thumb_id = get_post_thumbnail_id($metarecords->ID);
						$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id($metarecords->ID), 'medium', true);
						$theme_directory_url = get_template_directory_uri();
						if(!empty($thumb_id)) {
							$out = '<img src="' . $thumb_url[0] . '" width="50" />';
						} else {
							$out = '<img src="' . $theme_directory_url . '/images/no-image.png" srcset="' . $theme_directory_url . '/images/no-image@2x.png 2x" width="50" />';
						}
					}

					$beds = strip_tags( get_the_term_list( $metarecords->ID, 'beds', '', ', ', '' ) );
					$baths = strip_tags( get_the_term_list($metarecords->ID, 'baths', '', ', ', '' ) );
					$sqft = get_post_meta($metarecords->ID, '_ct_sqft', true);

					$property_type = strip_tags( get_the_term_list( $metarecords->ID, 'property_type', '', ', ', '' ) );

					if($ct_listings_title_formatting == 'yes') {
						$ct_record_title = ct_sanitize_output($metarecords->post_title);
					} else {
						$ct_record_title = ucwords(strtolower($metarecords->post_title));
					}

					$html .= '<li class="listing_media" att_id ="' . get_the_title($metarecords->ID) . '">
                            <div class="media-left">
                                <a class="media-object" href="' . get_permalink($metarecords->ID) . '">' . $out . '</a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="'.get_permalink($metarecords->ID).'">' . $ct_record_title . '</a></h4> 
								<ul class="amenities">';
									if(!empty($beds)) {
										$html .= '<li><strong>'. __('Beds: ', 'contempo') . '</strong>' . $beds.'</li>';
									}
									if(!empty($baths)) {
										$html .= '<li><strong>'. __('Baths: ', 'contempo') . '</strong>' . $baths . '</li>';
									}
									if(!empty($sqft)) {
										$html .= '<li><strong>' . __('Sq Ft: ', 'contempo') . '</strong>' . $sqft . '</li>';
									}
									if(empty($beds) && empty($baths) && empty($sqft)) {
										$html .= '<li>' . ucfirst($property_type) . '</li>';
									}
						$html .= '</ul>								
                            </div>
                        </li>';

				}
				$html .= '</ul>';
				$html .= '<div class="search-listingfooter">';
				if(count($post_meta_data) == 1){
					$html .= '<span class="search-listingcount">' . __('1 Listing found', 'contempo') . '</span>';
				} else {
					$html .= '<span class="search-listingcount">' . count($post_meta_data) . __(' Listings found', 'contempo') . '</span>';
				}
				$html .= '</div>';
			} elseif(!empty($post_terms)) {
				$html .= '<ul class="listing-records">';
				 foreach($post_terms as $terms_records){
					$img_src = wp_get_attachment_image_src( get_post_thumbnail_id($terms_records->ID), 'thumbnail_size' );

					$out = '';
					$source = get_post_meta( $terms_records->ID, 'source', true );

					if($source == 'idx-api') {

						$photos = get_post_meta($terms_records->ID, '_ct_slider', true);

						if(!empty($photos)) {

							foreach($photos as $attachment_id => $attachment_url) {
								$out = '<img src="' . esc_url($attachment_url) . '" width="50" />';
								break;
							}
						}
					}

					if($out == '') {
						$thumb_id = get_post_thumbnail_id($terms_records->ID);
						$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id($terms_records->ID),'medium', true);
						$theme_directory_url = get_template_directory_uri();
						if(!empty($thumb_id)) {
							$out = '<img src="' . $thumb_url[0] . '" width="50" />';
						} else {
							$out = '<img src="' . $theme_directory_url . '/images/no-image.png" srcset="' . $theme_directory_url . '/images/no-image@2x.png 2x" width="50"/>';
						}
					}

					$beds = strip_tags( get_the_term_list( $terms_records->ID, 'beds', '', ', ', '' ) );
					$baths = strip_tags( get_the_term_list( $terms_records->ID, 'baths', '', ', ', '' ) );
					$sqft = get_post_meta($terms_records->ID, '_ct_sqft', true);

					$property_type = strip_tags( get_the_term_list( $terms_records->ID, 'property_type', '', ', ', '' ) );

					if($ct_listings_title_formatting == 'yes') {
						$ct_record_title = ct_sanitize_output($terms_records->post_title);
					} else {
						$ct_record_title = ucwords(strtolower($terms_records->post_title));
					}

					$html .= '<li class="listing_media" att_id ="' . $terms_records->post_title . '">
                            <div class="media-left">
                                <a class="media-object" href="' . get_permalink($terms_records->ID) . '">' . $out .'</a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="' . get_permalink($terms_records->ID) .'">' . $ct_record_title . '</a></h4> 
								<ul class="amenities">';
									if(!empty($beds)) {
										$html .= '<li><strong>'. __('Beds: ', 'contempo') . '</strong>' . $beds.'</li>';
									}
									if(!empty($baths)) {
										$html .= '<li><strong>'. __('Baths: ', 'contempo') . '</strong>' . $baths . '</li>';
									}
									if(!empty($sqft)) {
										$html .= '<li><strong>' . __('Sq Ft: ', 'contempo') . '</strong>' . $sqft . '</li>';
									}
									if(empty($beds) && empty($baths) && empty($sqft)) {
										$html .= '<li>' . ucfirst($property_type) . '</li>';
									}
					$html .=	'</ul>								
                            </div>
                        </li>';
				}
				
				$html .= '</ul>';
				$html .= '<div class="search-listingfooter">';

				if(count($post_terms) == 1) {
					$html .= '<span class="search-listingcount">' . __('1 Listing found', 'contempo') . '</span>';
				} else {
					$html .= '<span class="search-listingcount">' . count($post_terms) . __(' Listings found', 'contempo') . '</span>';
				}
				$html .= '</div>';

			} else {
				$html .= '<ul><li id="no-listings-found">' . __('No Listings Found', 'contempo') . '</li></ul>';
			}
		}

		echo ct_sanitize_output( $html );

		die;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Add Option to Disable Admin Bar for non-Admins Only */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_disable_admin_bar')) {
	function ct_disable_admin_bar() {
		global $ct_options;

		$ct_disable_admin_bar = isset( $ct_options['ct_disable_admin_bar'] ) ? esc_attr( $ct_options['ct_disable_admin_bar'] ) : '';

		if(!current_user_can('administrator') && !is_admin() && $ct_disable_admin_bar == 'yes') {
			if ( class_exists( 'CT_RealEstate7_Helper' ) ) {
				CT_RealEstate7_Helper::adminbar_enabler( false );
			}
		}
	}
	add_action('after_setup_theme', 'ct_disable_admin_bar');
}

/*-----------------------------------------------------------------------------------*/
/* Add Listing Search to Admin */
/*-----------------------------------------------------------------------------------*/

if(is_admin()) {
	function listing_search_where($where){

	    if(isset($_GET['post_type']) && $_GET['post_type'] == 'listings') {
	        // Overwrite the where clause
	        $where = str_replace("AND wp_posts.post_type IN ('post', 'page')", "AND wp_posts.post_type IN ('post', 'page', 'listings')", $where);
	    }

	    return $where;
	}
	add_filter( 'posts_where', 'listing_search_where');
}

/*-----------------------------------------------------------------------------------*/
/* Expire Listing after X Days */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_expire_listings')) {
	function ct_expire_listings() {
		global $ct_options, $wpdb, $post, $wp_query;

		if(!is_object($post)) {
			return;
		}

		$author_level = get_the_author_meta('user_level');

		$ct_enable_front_end_paid = isset( $ct_options['ct_enable_front_end_paid'] ) ? esc_attr( $ct_options['ct_enable_front_end_paid'] ) : '';
		$ct_registered_user_role = isset( $ct_options['ct_registered_user_role'] ) ? esc_attr( $ct_options['ct_registered_user_role'] ) : '';
		$ct_listing_trans_id = get_post_meta($post->ID, "_ct_listing_paid_transaction_id", true);
		$ct_listing_expiration = isset( $ct_options['ct_listing_expiration'] ) ? esc_attr( $ct_options['ct_listing_expiration'] ) : '';

		if($ct_registered_user_role == 'subscriber' || $ct_registered_user_role == 'buyer') {
			$ct_user_level = '1';
		} elseif($ct_registered_user_role == 'contributor') {
			$ct_user_level = '2';
		} elseif($ct_registered_user_role == 'author' || $ct_registered_user_role == 'seller' || $ct_registered_user_role == 'agent' || $ct_registered_user_role == 'broker') {
			$ct_user_level = '4';
		} elseif($ct_registered_user_role == 'editor') {
			$ct_user_level = '5';
		}

		if($ct_enable_front_end_paid == 'yes' && $author_level <= $ct_user_level && $ct_listing_expiration != '') {

			$ct_daystogo = $ct_listing_expiration;

			$sql =
			"UPDATE {$wpdb->posts}
			SET post_status = 'pending'
			WHERE (post_type = 'listings' AND post_status = 'publish')
			AND DATEDIFF(NOW(), post_date) > %d";

			$wpdb->query( $wpdb->prepare( $sql, $ct_daystogo ) );
		}

	}
	add_action('wp_head', 'ct_expire_listings');
}

/*-----------------------------------------------------------------------------------*/
/* WPML Language Switcher */
/*-----------------------------------------------------------------------------------*/

if(class_exists('SitePress')) { 
    function wpml_ls_filter($languages) {
        global $sitepress;

        // If a query variable is in the URL
        if(strpos(basename( ct_get_server_info('REQUEST_URI') ), '?') !== false){
            foreach($languages as $lang_code => $language){
                $orig_url = 'http://' . ct_get_server_info('SERVER_NAME') . ct_get_server_info('REQUEST_URI');
                $languages[$lang_code]['url'] = $sitepress->convert_url($orig_url, $language['language_code']);
            }
        }
        return $languages;
    }
    add_filter('icl_ls_languages', 'wpml_ls_filter');
}

/*-----------------------------------------------------------------------------------*/
/* Blur Agent Details for Non Logged In Users */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_blur_agent_details')) {
	function ct_blur_agent_details() {
		global $ct_options;

		$ct_listing_agent_contact_logged_in = isset( $ct_options['ct_listing_agent_contact_logged_in'] ) ? $ct_options['ct_listing_agent_contact_logged_in'] : '';

		if($ct_listing_agent_contact_logged_in == 'yes') {
			if(!is_user_logged_in()) {
				echo 'blur';
			}
		}

	}
}

/*-----------------------------------------------------------------------------------*/
/* Get translated slugs for WPML */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_get_taxo_translated')) {
	function ct_get_taxo_translated() {
		$get_term = get_term_by( 'name', 'featured', 'ct_status' );
		$get_term_id = apply_filters( 'wpml_object_id', $get_term->term_id, 'category', true );
		$real_term = get_term_by( 'id', $get_term_id, 'ct_status' );
		
		return $real_term->slug;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Get Current Page */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_currentPage')) {
	function ct_currentPage() {
		global $page;
		return $page ? $page : 1;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Custom Excerpt Length */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_excerpt')) {
	function ct_excerpt() {
		global $ct_options;

		$limit = isset( $ct_options['ct_excerpt_length'] ) ? $ct_options['ct_excerpt_length']: 25;
		$excerpt = explode(' ', get_the_excerpt(), $limit);

		if(count($excerpt) >= $limit && $limit != 0) {
			array_pop($excerpt);
			$excerpt = implode(' ', $excerpt) . '&hellip;';
		} elseif($limit == 0) {
			$excerpt = '';
		} else {
			$excerpt = implode(' ', $excerpt);
		}

		$excerpt = preg_replace('`[[^]]*]`','', $excerpt);

		return $excerpt;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Sort By */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_sort_by')) {
	function ct_sort_by() { ?>

		<form action="<?php get_site_url(); ?>"  name="order "class="formsrch right col span_12 first marB0" method="get">
		    <?php
			    $extraVars = '';
				foreach($_GET as $key=>$value) {
					echo '<input type="hidden" name="' . (int)$key . '" value="' . (int)$value . '" />';
				}
		    ?>
		    <select class="ct_orderby" id="ct_orderby" name="ct_orderby">
			    <option value=""><?php esc_html_e('Sort By', 'contempo'); ?></option>
			    <option value="&nbsp;" <?php if(isset($_GET['ct_orderby']) && $_GET['ct_orderby'] == ''){ ?> selected="selected" <?php } ?>><?php esc_html_e('Default Order', 'contempo'); ?></option>
			    <option value="priceASC" <?php if(isset($_GET['ct_orderby']) && $_GET['ct_orderby'] == 'priceASC'){ ?> selected="selected" <?php } ?>><?php esc_html_e('Price - Low to High', 'contempo'); ?></option>
		        <option value="priceDESC" <?php if(isset($_GET['ct_orderby']) && $_GET['ct_orderby'] == 'priceDESC'){ ?> selected="selected" <?php } ?>><?php esc_html_e('Price - High to Low', 'contempo'); ?></option>
		        <option value="sizeASC" <?php if(isset($_GET['ct_orderby']) && $_GET['ct_orderby'] == 'sizeASC'){ ?> selected="selected" <?php } ?>><?php esc_html_e('Size - Low to High', 'contempo'); ?></option>
		        <option value="sizeDESC" <?php if(isset($_GET['ct_orderby']) && $_GET['ct_orderby'] == 'sizeDESC'){ ?> selected="selected" <?php } ?>><?php esc_html_e('Size - High to Low', 'contempo'); ?></option>
		        <option value="lotASC" <?php if(isset($_GET['ct_orderby']) && $_GET['ct_orderby'] == 'lotASC'){ ?> selected="selected" <?php } ?>><?php esc_html_e('Lot - Low to High', 'contempo'); ?></option>
		        <option value="lotDESC" <?php if(isset($_GET['ct_orderby']) && $_GET['ct_orderby'] == 'lotDESC'){ ?> selected="selected" <?php } ?>><?php esc_html_e('Lot - High to Low', 'contempo'); ?></option>
		        <option value="yearASC" <?php if(isset($_GET['ct_orderby']) && $_GET['ct_orderby'] == 'yearASC'){ ?> selected="selected" <?php } ?>><?php esc_html_e('Year - Low to High', 'contempo'); ?></option>
		        <option value="yearDESC" <?php if(isset($_GET['ct_orderby']) && $_GET['ct_orderby'] == 'yearDESC'){ ?> selected="selected" <?php } ?>><?php esc_html_e('Year - High to Low', 'contempo'); ?></option>
		        <option value="dateASC" <?php if(isset($_GET['ct_orderby']) && $_GET['ct_orderby'] == 'dateASC'){ ?> selected="selected" <?php } ?>><?php esc_html_e('Date - Old to New', 'contempo'); ?></option>
		        <option value="dateDESC" <?php if(isset($_GET['ct_orderby']) && $_GET['ct_orderby'] == 'dateDESC'){ ?> selected="selected" <?php } ?>><?php esc_html_e('Date - New to Old', 'contempo'); ?></option>
		    </select>
		</form>

	<?php }
}

/*-----------------------------------------------------------------------------------*/
/* Theme Directory URI */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_theme_directory_uri')) {
	function ct_theme_directory_uri() {

		$images = get_stylesheet_directory() . '/images/';

		if(is_child_theme() && file_exists($images)) {
			return get_stylesheet_directory_uri();
		} else {
			return get_template_directory_uri();
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Geocode Address */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_geocode_address')) {
	function ct_geocode_address($post_id) {
		global $ct_options;

		$ct_listing_lat_long = isset($ct_options['ct_listing_lat_long'] ) ? stripslashes( $ct_options['ct_listing_lat_long']) : '';
		$ct_google_maps_api_key = isset($ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key']) : '';

		if($ct_options['ct_listing_lat_long'] == 'on') {

			global $post;
			global $wp_query;

			$skip_geocoding = false;

			if(!isset($post->post_type)) {
				return;
			}

			$field_ct_skip_geolocation = filter_input( INPUT_POST, '_ct_skip_geolocation', FILTER_SANITIZE_STRING );
			
			if('on' === $field_ct_skip_geolocation) {
				$skip_geocoding = true;
			}

			if($post->post_type == 'listings' && ! $skip_geocoding){

			    if(isset( $_POST['post_type'] ) && $_POST['post_type'] != 'listings') {
			    	return;
			    }

				$city = wp_get_post_terms($post_id, 'city');

				if(isset($city[0])) {
					$city = $city[0];
					$city = $city->name;
				}

				$state = wp_get_post_terms($post_id, 'state');

				if(isset($state[0])) {
					$state = $state[0];
					$state = $state->name;
				}

				$zip = wp_get_post_terms($post_id, 'zipcode');

				if(isset($zip[0])) {
					$zip = $zip[0];
					$zip = $zip->name;
				}

			    $street = get_the_title($post_id);

			    if($street && $city) {
			        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($street . ' ' . $city . ', ' . $state . ' ' . $zip) . '&key=' . $ct_google_maps_api_key;
			        $resp = wp_remote_get($url);
			        if(200 == $resp['response']['code']) {
			            $body = $resp['body'];
			            $data = json_decode($body);
			            if($data->status== 'OK'){
			                $latitude = $data->results[0]->geometry->location->lat;
			                $longitude = $data->results[0]->geometry->location->lng;
							update_post_meta($post_id, '_ct_latlng', $latitude . ',' . $longitude);
							update_post_meta($post_id, 'lat', $latitude);
							update_post_meta($post_id, 'lng', $longitude);

			            }
			        }
			    }
			}

		}

		// Synchronize hidden latitude and longitude with _ct_latlng
		// This would allow users to manually enter the coordinates without city
		// If geolocation is turned on, this will just sync with the _ct_latlng data above
		// So it won't generate too much of a conflict

        $latlng = get_post_meta($post_id, '_ct_latlng', true);

        $lat = '';
        $lng = '';

        if(!empty($latlng)) {

            $latlng = explode(',', $latlng);

            if(is_array( $latlng ) && !empty( $latlng )) {

                if(isset( $latlng[0] ) && !empty($latlng[1] )) {
                    $lat = $latlng[0];
                }

                if(isset( $latlng[1] ) && !empty( $latlng[1] )) {
                    $lng = $latlng[1];
                }
            }
        }

        // Update the lat and long regardless if _ct_latlng is empty or not
        update_post_meta( $post_id, 'lat', $lat );
        update_post_meta( $post_id, 'lng', $lng );

        // Call to update all Listings isn't needed
		//updateLatLngsForAllListings();

	}
	add_action('save_post', 'ct_geocode_address', 999);
}

/*-----------------------------------------------------------------------------------*/
/* Admin notice for conflicting and empty latitude and longitude */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_latlng_validation_notice')) {

    add_action( 'admin_notices', 'ct_latlng_validation_notice', 999 );

    function ct_latlng_validation_notice() {

        global $post;

        $screen = get_current_screen();
        
        if($screen->parent_base !== 'edit') {
            return;
        }

        // Check the post type. But first check if $post is set
        if(!isset( $post )) {
            return;
        }

        // Only enable for listings
        if('listings' !== $post->post_type) {
            return;
        }

        // Get the post id
        $post_id = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );

        if(empty( $post_id )) {
            return;
        }

        // Fetch hidden lng, and hidden lat
        $hidden_lat = get_post_meta($post_id, 'lat', true);
        $hidden_lng = get_post_meta($post_id, 'lng', true);
       
        // Fetch the latlng
        $latlng = get_post_meta($post_id, '_ct_latlng', true);

        $error_empty = false;
        $error_conflict = false;

        if(empty( $hidden_lat ) || empty( $hidden_lng ) || empty( $latlng )) {
           $error_empty = true;
        }

        $lat = '';
        $lng = '';

        $_latlng = explode(',', $latlng);

            if(is_array( $_latlng ) && !empty( $_latlng )) {

                if(isset( $_latlng[0] ) && !empty( $_latlng[0] )) {
                    $lat = $_latlng[0];
                }

                if(isset( $_latlng[1] ) && !empty( $_latlng[1]) ) {
                    $lng = $_latlng[1];
                }
            }

        if($lat !== $hidden_lat || $lng !== $hidden_lng) {
            $error_conflict = true;
        }

        if($error_empty || $error_conflict) {
            if($error_empty) {
            	echo '<div class="notice notice-error ct-lat-lng-notice">';
                    echo '<p>';
                        echo '<strong>' . __('Error:', 'contempo') . '</strong>';
                        echo '<em>' . __('Google Maps Geocoding API Failed to Automatically Generate the Coordinates for this Listing', 'contempo') . '</em>';
                        //echo sprintf( esc_html__('_ct_latlng: %s, lat: %s, lng: %s'), $latlng, $hidden_lat, $hidden_lng ) ;
                    echo '</p>';
                    echo '<ol>';
                    	echo '<li>' . __('Can be due to missing, bad or improper address information', 'contempo') . '</li>';
                    	echo '<li>' . sprintf( esc_html__('Not having the Geocoding API enabled for the site, refer to %s', 'contempo'), '<a href="https://contempothemes.com/docs/google-maps/" target="_blank">documentation</a>' ) . '</li>';
                	echo '</ol>';
                    echo '<p>';
                        echo __('Please check the information or enter the latitude and longitude manually. If not entered this can cause display issues with mapping, most importantly the pan/zoom, draw and geolocation functions.', 'contempo');
                    echo '</p>';
                echo '</div>';
            }
            
            if($error_conflict) {
            	echo '<div class="notice notice-warning ct-lat-lng-notice">';
                    echo '<p>';
                        echo '<strong>' . __('Warning:', 'contempo') . '</strong>';
                        echo '<em>' . __('Conflicting Coordinates', 'contempo') . '</em>';
                    echo '</p>';
                    echo '<p>';
                    	echo sprintf( esc_html__('Manually Entered: %s', 'contempo'), $latlng );
                        echo '<br />';
                        echo sprintf( esc_html__('Generated from Address: %s, %s', 'contempo'), $hidden_lat, $hidden_lng );
                    echo '</p>';
                    echo '<p>';
                        echo __('Please check the Latitude & Longitude field and click on the update button to fix.', 'contempo');
                    echo '</p>';
                echo '</div>';
            }
        }
    }
}

/*-----------------------------------------------------------------------------------*/
/* Remove Open House Status if Date has Passed */
/* Is this function still necessary? */
/* Removed for now */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_update_open_house_status')) {
	function ct_update_open_house_status($post_id) {
		/*global $post;

		$ct_open_house_entries = get_post_meta( $post_id, '_ct_open_house', true );
		$ct_todays_date = date('mdY');
		$ct_open_house_date_formatted = '';

		foreach( (array) $ct_open_house_entries as $key => $entry ) {

			if( isset( $entry['_ct_open_house_date'] ) ) {
		        $ct_open_house_date = esc_html( $entry['_ct_open_house_date'] );
		        $ct_open_house_date_formatted = strftime('%m%d%Y', $ct_open_house_date);
		    }

		    if(isset( $entry['_ct_open_house_date'] ) && $ct_open_house_date_formatted < $ct_todays_date) {

			    $remove_tag = 'open-house';
			    $total_tags = get_the_terms($post_id, 'ct_status');

			    foreach($total_tags as $tag){
			        if($tag->slug != $remove_tag) {
			            $updated_tags[] = $tag->slug;
			        }
			    }
			    wp_set_post_terms( $post_id, $updated_tags, 'ct_status', true);
			    wp_set_post_terms( $post_id, 'For Sale', 'ct_status', true);
			}
		}*/
	}
	//add_action('save_post', 'ct_update_open_house_status', 999);
}

/*-----------------------------------------------------------------------------------*/
/* Update lat longs for listings */
/*-----------------------------------------------------------------------------------*/

function updateLatLngsForAllListings() {

	if(!is_admin()) {
		return;
	}

	$args = array(
		'posts_per_page' 	=> -1,
		'post_type' 		=> 'listings',
		'post_status' 		=> 'publish'
	);

	$listings = get_posts($args);

	foreach($listings as $listing) {
		$lat = get_post_meta($listing->ID, 'lat', true);
		if($lat == '') {

			$latLng = get_post_meta($listing->ID, '_ct_latlng', true);
			if($latLng != '') {
				$lat = trim(substr($latLng, 0, strpos( $latLng, ',')));
				$lng = trim(substr($latLng, strpos( $latLng, ',') + 1));

				update_post_meta($listing->ID, 'lat', $lat);
				update_post_meta($listing->ID, 'lng', $lng);

			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display Login/Register after X amount of Views */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_display_login_register_after_x_views')) {
	function ct_display_login_register_after_x_views() {
		global $ct_options;
		$ct_listings_login_register_after_x_views = isset( $ct_options['ct_listings_login_register_after_x_views'] ) ? esc_html( $ct_options['ct_listings_login_register_after_x_views'] ) : '';
		$ct_listings_login_register_after_x_views_num = isset( $ct_options['ct_listings_login_register_after_x_views_num'] ) ? esc_html( $ct_options['ct_listings_login_register_after_x_views_num'] ) : '';

		if($ct_listings_login_register_after_x_views) { ?>
			<script>
			jQuery(document).ready(function() {

			  var VisitedSet = Cookies.get('visited');

				if(!VisitedSet) {
					Cookies.set('visited', '0');
					VisitedSet = 0;
				}

				VisitedSet++;

				if(VisitedSet == <?php echo esc_html($ct_listings_login_register_after_x_views_num); ?>) {
					jQuery(window).load(function() {
						jQuery('#overlay').addClass('open');
						jQuery('html, body').animate({scrollTop : 0},800);
						return false;
					});
				} else {
					Cookies.set('visited', VisitedSet, {
					expires: 1
				});

					//console.log('Page Views: ' + VisitedSet);

					return false;
				}
			});

			(function($){
			  $("#resetCounter").click(function(){
			    Cookies.set('visited', '0');
			    location.reload();
			  });
			})( jQuery );
			</script>
		<?php }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Contact Us Map */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_contact_us_map')) {
	function ct_contact_us_map() {
		global $ct_options;
		$google_maps_api_key = isset( $ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key'] ) : '';
		if($ct_options['ct_contact_map'] == 'yes') { ?>
			<script>
	        function setMapAddress(address) {
	            var geocoder = new google.maps.Geocoder();
	            var mapPin = {
					url: '<?php echo get_template_directory_uri(); ?>/images/map-pin-com.png',
					size: new google.maps.Size(40, 46),
				    scaledSize: new google.maps.Size(40, 46)
				};
	            geocoder.geocode( { address : address }, function( results, status ) {
	                if( status == google.maps.GeocoderStatus.OK ) {
	                    var location = results[0].geometry.location;
	                    var options = {
	                        zoom: 15,
	                        center: location,
	                        mapTypeId: google.maps.MapTypeId.<?php echo esc_html(strtoupper($ct_options['ct_contact_map_type'])); ?>,
	                        streetViewControl: true,
							scrollwheel: false,
							draggable: false,
							<?php
							$ct_gmap_style = isset( $ct_options['ct_google_maps_style'] ) ? $ct_options['ct_google_maps_style']: '';
							$ct_gmap_snazzy_style = isset( $ct_options['ct_google_maps_snazzy_style'] ) ? $ct_options['ct_google_maps_snazzy_style']: '';
							$ct_gmap_snazzy_style = str_replace(array('.', ' ', "\n", "\t", "\r"), '', $ct_gmap_snazzy_style);
							if($ct_gmap_snazzy_style != '') { ?>
								styles: <?php echo ct_sanitize_output( $ct_gmap_snazzy_style ); ?>,
							<?php } ?>
	                    };
	                    var mymap = new google.maps.Map( document.getElementById( 'map' ), options );
	                    var marker = new google.maps.Marker({
	                    	map: mymap,
	                    	animation: google.maps.Animation.DROP,
							flat: true,
							icon: mapPin,
							position: results[0].geometry.location
	                	});
	            	}
	        	});
	        }
	        setMapAddress( "<?php echo esc_html($ct_options['ct_contact_map_location']); ?>" );
	        </script>

	       <?php if(empty($google_maps_api_key)) {

				echo '<div id="map-wrap" class="no-google-api-key">';
					echo '<h5>' . __('You need to setup the Google Maps API.', 'contempo') . '</h5>';
			        echo '<p class="marB0">' . __('Go into Admin > Real Estate 7 Options > Google Maps', 'contempo') . '</p>';
		        echo '</div>';

			} else {

			    echo '<div id="location">';
			       echo '<div id="map" style="height: 300px;"></div>';
			    echo '</div>';

		    }
	    }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Brokerage Single Map */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_brokerage_single_map')) {
	function ct_brokerage_single_map($brokerage_address) {
		global $ct_options;
		$google_maps_api_key = isset( $ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key'] ) : '';
		?>
		<script>
        function setMapAddress(address) {
            var geocoder = new google.maps.Geocoder();
            var mapPin = {
					url: '<?php echo get_template_directory_uri(); ?>/images/map-pin-com.png',
					size: new google.maps.Size(40, 46),
				    scaledSize: new google.maps.Size(40, 46)
				};
            geocoder.geocode( { address : address }, function( results, status ) {
                if( status == google.maps.GeocoderStatus.OK ) {
                    var location = results[0].geometry.location;
                    var options = {
                        zoom: 15,
                        center: location,
                        mapTypeId: google.maps.MapTypeId.<?php echo esc_html(strtoupper($ct_options['ct_contact_map_type'])); ?>,
                        streetViewControl: true,
						scrollwheel: false,
						draggable: false,
						<?php
						$ct_gmap_style = isset( $ct_options['ct_google_maps_style'] ) ? $ct_options['ct_google_maps_style']: '';
						$ct_gmap_snazzy_style = isset( $ct_options['ct_google_maps_snazzy_style'] ) ? $ct_options['ct_google_maps_snazzy_style']: '';
						$ct_gmap_snazzy_style = str_replace(array('.', ' ', "\n", "\t", "\r"), '', $ct_gmap_snazzy_style);
						if($ct_gmap_snazzy_style != '') { ?>
							styles: <?php echo ct_sanitize_output( $ct_gmap_snazzy_style ); ?>,
						<?php } ?>
                    };

                	jQuery('ul.tabs li.brokerage-map').click(function(e) {
						setTimeout(function() {
							var mymap = new google.maps.Map( document.getElementById( 'map' ), options );

		                    var marker = new google.maps.Marker({
		                    	map: mymap,
		                    	animation: google.maps.Animation.DROP,
								flat: true,
								icon: mapPin,
								position: results[0].geometry.location
		                	});
				            google.maps.event.trigger(mymap, "resize");
				            marker.setMap(mymap);
				        }, 1000);
					});
            	}
        	});
        }
        setMapAddress( "<?php echo esc_html($brokerage_address); ?>" );
        </script>

        <?php
		if(empty($google_maps_api_key)) {

			echo '<div id="map-wrap" class="no-google-api-key">';
				echo '<h5>' . __('You need to setup the Google Maps API.', 'contempo') . '</h5>';
		        echo '<p class="marB0">' . __('Go into Admin > Real Estate 7 Options > Google Maps', 'contempo') . '</p>';
	        echo '</div>';

		} else {

		    echo '<div id="location">';
		       echo '<div id="map" style="height: 300px;"></div>';
		    echo '</div>';

		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Single Listing Map */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_map')) {
	function ct_listing_map() {
		global $ct_options;
		$google_maps_api_key = isset( $ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key'] ) : '';
		$ct_single_listing_content_layout_type = isset( $ct_options['ct_single_listing_content_layout_type'] ) ? $ct_options['ct_single_listing_content_layout_type'] : '';
		?>
		<script>
        function setMapAddress(address) {

            var geocoder = new google.maps.Geocoder();
            <?php if(ct_has_type('commercial') || ct_has_type('industrial') || ct_has_type('retail')) { ?>
				var mapPin = {
					url: '<?php echo ct_theme_directory_uri(); ?>/images/map-pin-com.png',
					size: new google.maps.Size(40, 46),
				    scaledSize: new google.maps.Size(40, 46)
				};
			<?php } elseif(ct_has_type('land')) { ?>
				var mapPin = {
					url: '<?php echo ct_theme_directory_uri(); ?>/images/map-pin-land.png',
					size: new google.maps.Size(40, 46),
				    scaledSize: new google.maps.Size(40, 46)
				};
			<?php } elseif(ct_has_type('lot')) { ?>
				var mapPin = {
					url: '<?php echo ct_theme_directory_uri(); ?>/images/map-pin-land.png',
					size: new google.maps.Size(40, 46),
				    scaledSize: new google.maps.Size(40, 46)
				};
			<?php } else { ?>
				var mapPin = {
					url: '<?php echo ct_theme_directory_uri(); ?>/images/map-pin-res.png',
					size: new google.maps.Size(40, 46),
				    scaledSize: new google.maps.Size(40, 46)
				};
			<?php } ?>
            geocoder.geocode( { address : address }, function( results, status ) {
                if( status == google.maps.GeocoderStatus.OK ) {
					<?php  if((get_post_meta(get_the_ID(), "_ct_latlng", true))) { ?>
                    var location = new google.maps.LatLng(<?php echo get_post_meta(get_the_ID(), "_ct_latlng", true); ?>);
					<?php } else { ?>
					var location = results[0].geometry.location;
					<?php } ?>
                    var options = {
                        zoom: 15,
                        center: location,
						scrollwheel: false,
                        mapTypeId: google.maps.MapTypeId.<?php echo esc_html(strtoupper($ct_options['ct_contact_map_type'])); ?>,
                        streetViewControl: true,
                        <?php
						$ct_gmap_style = isset( $ct_options['ct_google_maps_style'] ) ? $ct_options['ct_google_maps_style']: '';
						$ct_gmap_snazzy_style = isset( $ct_options['ct_google_maps_snazzy_style'] ) ? $ct_options['ct_google_maps_snazzy_style']: '';
						$ct_gmap_snazzy_style = str_replace(array('.', ' ', "\n", "\t", "\r"), '', $ct_gmap_snazzy_style);
						if($ct_gmap_snazzy_style != '') { ?>
							styles: <?php echo ct_sanitize_output( $ct_gmap_snazzy_style ); ?>,
						<?php } ?>
                    };
                    var mymap = new google.maps.Map( document.getElementById( 'map-single' ), options );

                    var marker = new google.maps.Marker({
                    	map: mymap,
                    	animation: google.maps.Animation.DROP,
                   		draggable: false,
						flat: true,
						icon: mapPin,
						<?php  if((get_post_meta(get_the_ID(), "_ct_latlng", true))) { ?>
						position: new google.maps.LatLng(<?php echo get_post_meta(get_the_ID(), "_ct_latlng", true); ?>)
						<?php } else { ?>
						position: results[0].geometry.location
						<?php } ?>
                	});
            	}
        	});
        }

        <?php if($ct_single_listing_content_layout_type == 'tabbed') { ?>
        // Trigger map function on opening location tab
        jQuery('a[href="#listing-location"]').on('click', function() {
	        setTimeout(function(){
	        	var listingMap = document.getElementById("map-single");
	        	setMapAddress();
	            google.maps.event.trigger(listingMap, 'resize');
	            if( typeof map != 'undefined') {
	                map.panTo(google.maps.Marker);
	            }
	        }, 50);
	    });
	    <?php } ?>

        <?php if((get_post_meta(get_the_ID(), "_ct_latlng", true))) { ?>
	        setMapAddress( "<?php echo esc_html(get_post_meta(get_the_ID(), "_ct_latlng", true)); ?>" );
		<?php } elseif(is_page_template('template-edit-listing.php')) { ?>
			setMapAddress( "<?php esc_html($title); ?> <?php esc_html($city); ?> <?php esc_html($state); ?> <?php esc_html($zipcode); ?>" );
		<?php } else { ?>
			setMapAddress( "<?php the_title(); ?> <?php ct_taxonomy('city'); ?> <?php ct_taxonomy('state'); ?> <?php ct_taxonomy('zipcode'); ?>" );
		<?php } ?>

        </script>

		<?php
		if(empty($google_maps_api_key)) {

			echo '<div id="map-wrap" class="no-google-api-key">';
				echo '<h5>' . __('You need to setup the Google Maps API.', 'contempo') . '</h5>';
		        echo '<p class="marB0">' . __('Go into Admin > Real Estate 7 Options > Google Maps', 'contempo') . '</p>';
	        echo '</div>';

		} else {

		    echo '<div id="location">';
		       echo '<div id="map-single" style="height: 300px;"></div>';
		    echo '</div>';

		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Multi Marker Map */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_multi_marker_map')) {
	function ct_multi_marker_map() {
	    global $ct_options;
	    global $post;

	    $count = 0;

	    $google_maps_api_key = isset( $ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key'] ) : '';
	    $ct_gmap_style = isset( $ct_options['ct_google_maps_style'] ) ? $ct_options['ct_google_maps_style']: '';
	    $ct_gmap_snazzy_style = isset( $ct_options['ct_google_maps_snazzy_style'] ) ? $ct_options['ct_google_maps_snazzy_style']: '';
	    $ct_gmap_snazzy_style = str_replace(array('.', ' ', "\n", "\t", "\r"), '', $ct_gmap_snazzy_style);
	    $ct_gmap_type = isset( $ct_options['ct_contact_map_type'] ) ? $ct_options['ct_contact_map_type']: '';
	    $ct_exclude_sold_listing_search = isset( $ct_options['ct_exclude_sold_listing_search'] ) ? $ct_options['ct_exclude_sold_listing_search']: '';
	    $ct_listings_lotsize_format = isset( $ct_options['ct_listings_lotsize_format'] ) ? esc_html( $ct_options['ct_listings_lotsize_format'] ) : '';

	    if($ct_exclude_sold_listing_search == 'yes') {
		    query_posts(array(
				'post_type' => 'listings',
		        'posts_per_page' => 1000,
		        'tax_query' => array(
					array(
					    'taxonomy'  => 'ct_status',
					    'field'     => 'slug',
					    'terms'     => 'sold',
					    'operator'  => 'NOT IN'
				    ),
			    ),
		        'tax_query' => array(
					array(
					    'taxonomy'  => 'ct_status',
					    'field'     => 'slug',
					    'terms'     => 'ghost',
					    'operator'  => 'NOT IN'
				    ),
			    ),
		        'order' => 'DSC'
		    ));
		} else {
			query_posts(array(
				'post_type' => 'listings',
		        'posts_per_page' => 1000,
		        'tax_query' => array(
					array(
					    'taxonomy'  => 'ct_status',
					    'field'     => 'slug',
					    'terms'     => 'ghost',
					    'operator'  => 'NOT IN'
				    ),
			    ),
		        'order' => 'DSC'
		    ));
		}

	    ?>

	    <script>
	    var property_list = [];
		var default_mapcenter = [];
		var ctMapGlobal = {
			<?php if($ct_gmap_snazzy_style != '') { ?>
				mapCustomStyles: '<?php echo ct_sanitize_output( $ct_gmap_snazzy_style ); ?>',
			<?php } ?>
			mapStyle: '<?php echo esc_html($ct_gmap_style); ?>',
			mapType: '<?php echo esc_html($ct_gmap_type); ?>'
		}

	    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

	    	$ct_source = get_post_meta($post->ID, 'source', true);
	    	$ct_idx_rupid = get_post_meta($post->ID, '_ct_idx_rupid', true);

		    $ct_lotsize = get_post_meta($post->ID, '_ct_lotsize', true);
		    $ct_lotsize_acres = get_post_meta($post->ID, '_ct_idx_overview_acres', true);

			// Legacy IDX API
			if($ct_source == 'idx-api' && is_numeric($ct_lotsize) && empty($ct_idx_rupid)) {
	            $ct_lotsize = number_format($ct_lotsize / 43560, 1);
	        } else {
	        	$ct_lotsize = $ct_lotsize;
	        }
	
			$count++; ?>

			var property = {
				thumb:       '<?php ct_first_image_map_tn(); ?>',
				title:       '<?php ct_listing_title(); ?>',
				fullPrice:   "<?php ct_listing_price(); ?>",
				markerPrice: "<?php echo ct_listing_marker_price(); ?>",
				bed:         "<?php ct_taxonomy('beds'); ?>",
				bath:        "<?php ct_taxonomy('baths'); ?>",
				<?php if(is_numeric($ct_sqft)) { ?>
					size: "<?php echo number_format_i18n($ct_sqft, 0); ?> <?php ct_sqftsqm(); ?>",
				<?php } else { ?>
					size: "<?php echo esc_html($ct_sqft); ?> <?php ct_sqftsqm(); ?>",
				<?php } ?>
				<?php if($ct_source == 'idx-api') {
    				if(!empty($ct_lotsize_acres)) { ?>
		    			lotsize: "<?php echo ct_sanitize_output( $ct_lotsize_acres ); ?> <?php ct_acres(); ?>",
		    		<?php } elseif(!empty($ct_lotsize)) { ?>
		    			lotsize: "<?php echo ct_sanitize_output( $ct_lotsize ); ?> <?php ct_acres(); ?>",
		    		<?php }
		        } else {
		        	if(!empty($ct_lotsize) && $ct_listings_lotsize_format == 'yes' && is_numeric($ct_lotsize)) { ?>
		                lotsize: "<?php echo number_format_i18n($ct_lotsize, 0); ?> <?php ct_acres(); ?>",
		            <?php } elseif(!empty($ct_lotsize)) { ?>
		             	lotsize: "<?php echo ct_sanitize_output( $ct_lotsize ); ?> <?php ct_acres(); ?>",
		            <?php }
		        } ?>
				street:      "<?php the_title(); ?>",
				city:        "<?php ct_taxonomy('city'); ?>",
				state:       "<?php ct_taxonomy('state'); ?>",
				zip:         "<?php ct_taxonomy('zipcode'); ?>",
				latlong:     "<?php echo get_post_meta(get_the_ID(), "_ct_latlng", true); ?>",
				permalink:   "<?php the_permalink(); ?>",
				isHome:      "<?php if(is_home()) { echo "false"; } else { echo "true"; } ?>",
				commercial:  "<?php if(ct_has_type('commercial')) { echo 'commercial'; } ?>",
				industrial:  "<?php if(ct_has_type('industrial')) { echo 'industrial'; } ?>",
				retail:  	 "<?php if(ct_has_type('retail')) { echo 'retail'; } ?>",
				land:        "<?php if(ct_has_type('land')) { echo 'land'; } ?>",
				siteURL:     "<?php echo ct_theme_directory_uri(); ?>",
				listingID:   "<?php echo get_the_ID(); ?>",
				ctStatus:    "<?php trim(ct_status_slug()); ?>",
			}

			property_list.push(property);

	<?php
	    endwhile; endif;
		wp_reset_query();
	?>
	    </script>
	    <script>var defaultmapcenter = {mapcenter: ""}; google.maps.event.addDomListener(window, 'load', function(){ estateMapping.init_property_map(property_list, defaultmapcenter, "<?php echo ct_theme_directory_uri(); ?>"); });</script>

	    <?php
		if(empty($google_maps_api_key)) {

			echo '<div id="map-wrap" class="no-google-api-key">';
				echo '<h5>' . __('You need to setup the Google Maps API.', 'contempo') . '</h5>';
		        echo '<p class="marB0">' . __('Go into Admin > Real Estate 7 Options > Google Maps', 'contempo') . '</p>';
	        echo '</div>';

		} else {

		    echo '<div id="location">';
		       echo '<div id="map" style="height: 300px;"></div>';
		    echo '</div>';

		}    
	}
}

/*-----------------------------------------------------------------------------------*/
/* Featured Listings Map */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_featured_listings_map')) {
	function ct_featured_listings_map() {
	    global $ct_options;
	    global $post;

	    $count = 0;

	    $google_maps_api_key = isset( $ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key'] ) : '';
	    $ct_gmap_style = isset( $ct_options['ct_google_maps_style'] ) ? $ct_options['ct_google_maps_style']: '';
	    $ct_gmap_snazzy_style = isset( $ct_options['ct_google_maps_snazzy_style'] ) ? $ct_options['ct_google_maps_snazzy_style']: '';
	    $ct_gmap_snazzy_style = str_replace(array('.', ' ', "\n", "\t", "\r"), '', $ct_gmap_snazzy_style);
	    $ct_gmap_type = isset( $ct_options['ct_contact_map_type'] ) ? $ct_options['ct_contact_map_type']: '';

	    query_posts(array(
			'post_type' 		=> 'listings',
			'status' 			=> 'featured',
	        'posts_per_page' 	=> 1000,
	        'order' 			=> 'DESC'
	    )); ?>

	    <script>
	    var property_list = [];
		var default_mapcenter = [];
		var ctMapGlobal = {
			<?php if($ct_gmap_snazzy_style != '') { ?>
				mapCustomStyles: '<?php echo ct_sanitize_output( $ct_gmap_snazzy_style ); ?>',
			<?php } ?>
			mapStyle: '<?php echo esc_html($ct_gmap_style); ?>',
			mapType: '<?php echo esc_html($ct_gmap_type); ?>'
		}

	    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

			$count++; ?>

	        var property = {
	            thumb: '<?php ct_first_image_map_tn(); ?>',
	            title: '<?php ct_listing_title(); ?>',
				fullPrice: "<?php ct_listing_price(); ?>",
				markerPrice: "<?php echo ct_listing_marker_price(); ?>",
	            bed: "<?php ct_taxonomy('beds'); ?>",
	            bath: "<?php ct_taxonomy('baths'); ?>",
	            size: "<?php echo get_post_meta($post->ID, "_ct_sqft", true); ?> <?php ct_sqftsqm(); ?>",
	            street: "<?php the_title(); ?>",
	            city: "<?php ct_taxonomy('city'); ?>",
	            state: "<?php ct_taxonomy('state'); ?>",
	            zip: "<?php ct_taxonomy('zipcode'); ?>",
				latlong: "<?php echo get_post_meta(get_the_ID(), "_ct_latlng", true); ?>",
	            permalink: "<?php the_permalink(); ?>",
				isHome: "<?php if(is_home()) { echo "false"; } else { echo "true"; } ?>",
				commercial: "<?php if(ct_has_type('commercial')) { echo 'commercial'; } ?>",
				industrial:  "<?php if(ct_has_type('industrial')) { echo 'industrial'; } ?>",
				retail:  	 "<?php if(ct_has_type('retail')) { echo 'retail'; } ?>",
				land: "<?php if(ct_has_type('land')) { echo 'land'; } ?>",
				siteURL: "<?php echo ct_theme_directory_uri(); ?>",
				listingID: "<?php echo get_the_ID(); ?>",
				ctStatus:    "<?php ct_status_slug(); ?> ",
	        }
	        property_list.push(property);

	<?php
	    endwhile; endif;
		wp_reset_query();
	?>
	    </script>

	    <?php if(!empty($google_maps_api_key)) { ?>
		    <script>var defaultmapcenter = {mapcenter: ""}; google.maps.event.addDomListener(window, 'load', function(){ estateMapping.init_property_map(property_list, defaultmapcenter, "<?php echo ct_theme_directory_uri(); ?>"); });</script>
		<?php } ?>

	    <?php
		if(empty($google_maps_api_key)) {

			echo '<div id="map-wrap" class="no-google-api-key">';
				echo '<h5>' . __('You need to setup the Google Maps API.', 'contempo') . '</h5>';
		        echo '<p class="marB0">' . __('Go into Admin > Real Estate 7 Options > Google Maps', 'contempo') . '</p>';
	        echo '</div>';

		} else {

		    echo '<div id="location">';
		       echo '<div id="map" style="height: 300px;"></div>';
		    echo '</div>';

		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Search Results Map */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_results_map')) {
	function ct_search_results_map( $pageLoaded = false ) {
	   	global $wp_query;
	    global $ct_options;
	    global $post;

	    $count = 0;

	    $google_maps_api_key = isset( $ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key'] ) : '';
	    $ct_gmap_style = isset( $ct_options['ct_google_maps_style'] ) ? $ct_options['ct_google_maps_style']: '';
	    $ct_gmap_snazzy_style = isset( $ct_options['ct_google_maps_snazzy_style'] ) ? $ct_options['ct_google_maps_snazzy_style']: '';
	    $ct_gmap_snazzy_style = str_replace(array('.', ' ', "\n", "\t", "\r"), '', $ct_gmap_snazzy_style);
		$ct_gmap_type = isset( $ct_options['ct_contact_map_type'] ) ? $ct_options['ct_contact_map_type']: '';
		$ct_listings_lotsize_format = isset( $ct_options['ct_listings_lotsize_format'] ) ? esc_html( $ct_options['ct_listings_lotsize_format'] ) : '';

		if( $pageLoaded === false ) {
	    	echo '<script>';
		}

		?>

	    var property_list = [];
		var default_mapcenter = [];

		var ctMapGlobal = {
			<?php if( $ct_gmap_snazzy_style != '' ) { ?>
				mapCustomStyles: '<?php echo ct_sanitize_output( $ct_gmap_snazzy_style ); ?>',
			<?php } ?>
			mapStyle: '<?php echo esc_html($ct_gmap_style); ?>',
			mapType: '<?php echo esc_html($ct_gmap_type); ?>'
		}

	    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

	    	$ct_source = get_post_meta(get_the_ID(), 'source', true);
	    	$ct_idx_rupid = get_post_meta(get_the_ID(), '_ct_idx_rupid', true);

	    	$ct_sqft = get_post_meta(get_the_ID(), '_ct_sqft', true);
		    $ct_lotsize = get_post_meta(get_the_ID(), '_ct_lotsize', true);
		    $ct_lotsize_acres = get_post_meta(get_the_ID(), '_ct_idx_overview_acres', true);

		    // Legacy IDX API
			if($ct_source == 'idx-api' && is_numeric($ct_lotsize) && empty($ct_idx_rupid)) {
	            $ct_lotsize = number_format($ct_lotsize / 43560, 1);
	        } else {
	        	$ct_lotsize = $ct_lotsize;
	        }
	
			$count++; ?>

	        var property = {
	            thumb: '<?php ct_first_image_map_tn(); ?>',
	            title: '<?php ct_listing_title(); ?>',
	            fullPrice: "<?php ct_listing_price(); ?>",
	            markerPrice: "<?php echo ct_listing_marker_price(); ?>",
	            bed: "<?php ct_taxonomy('beds'); ?>",
	            bath: "<?php ct_taxonomy('baths'); ?>",
				<?php if(is_numeric($ct_sqft)) { ?>
					size: "<?php echo number_format_i18n($ct_sqft, 0); ?> <?php ct_sqftsqm(); ?>",
				<?php } else { ?>
					size: "<?php echo esc_html($ct_sqft); ?> <?php ct_sqftsqm(); ?>",
				<?php } ?>
	            <?php if($ct_source == 'idx-api') {
    				if(!empty($ct_lotsize_acres)) { ?>
		    			lotsize: "<?php echo ct_sanitize_output( $ct_lotsize_acres ); ?> <?php ct_acres(); ?>",
		    		<?php } elseif(!empty($ct_lotsize)) { ?>
		    			lotsize: "<?php echo ct_sanitize_output( $ct_lotsize ); ?> <?php ct_acres(); ?>",
		    		<?php }
		        } else {
		        	if(!empty($ct_lotsize) && $ct_listings_lotsize_format == 'yes' && is_numeric($ct_lotsize)) { ?>
		                 lotsize: "<?php echo number_format_i18n($ct_lotsize, 0); ?> <?php ct_acres(); ?>",
		            <?php } elseif(!empty($ct_lotsize)) { ?>
		             	lotsize: "<?php echo ct_sanitize_output( $ct_lotsize ); ?> <?php ct_acres(); ?>",
		            <?php }
		        } ?>
	            street: "<?php the_title(); ?>",
	            city: "<?php ct_taxonomy('city'); ?>",
	            state: "<?php ct_taxonomy('state'); ?>",
	            zip: "<?php ct_taxonomy('zipcode'); ?>",
				latlong: "<?php echo get_post_meta(get_the_ID(), "_ct_latlng", true); ?>",
	            permalink: "<?php the_permalink(); ?>",
				isHome: "<?php if(is_home()) { echo "false"; } else { echo "true"; } ?>",
				commercial: "<?php if(ct_has_type('commercial')) { echo 'commercial'; } ?>",
				industrial:  "<?php if(ct_has_type('industrial')) { echo 'industrial'; } ?>",
				retail:  	 "<?php if(ct_has_type('retail')) { echo 'retail'; } ?>",
				land: "<?php if(ct_has_type('land')) { echo 'land'; } ?>",
				siteURL: "<?php echo ct_theme_directory_uri(); ?>",
				listingID: "<?php echo absint( $post->ID ); ?>",
				ctStatus:    "<?php trim( ct_status_slug() ); ?> ",
	        }
	        property_list.push(property);

		<?php
		
		endwhile; endif;

		if( $pageLoaded == false ) {
			wp_reset_query();
		}

		if( $pageLoaded === false ) {
			echo '</script>';
			echo '<script>';
		}
		?>

		var defaultmapcenter = {mapcenter: ""};

		<?php
		if( $pageLoaded == false ) {
			?>
			google.maps.event.addDomListener(window, 'load', function() {
				estateMapping.init_property_map(property_list, defaultmapcenter, "<?php echo ct_theme_directory_uri(); ?>");
			});
			<?php
		} else {
			?>
			estateMapping.init_property_map(property_list, null, "<?php echo ct_theme_directory_uri(); ?>");
			<?php
		}

		if( $pageLoaded === false ) {

			echo '</script>';

			if(empty($google_maps_api_key)) {
				echo '<div id="map-wrap" class="no-google-api-key">';
					echo '<h5>' . __('You need to setup the Google Maps API.', 'contempo') . '</h5>';
			        echo '<p class="marB0">' . __('Go into Admin > Real Estate 7 Options > Google Maps', 'contempo') . '</p>';
			    echo '</div>';
			} else {

				    echo '<div id="map"></div>';

			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* User Circle SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_user_circle_svg')) {
	function ct_user_circle_svg() {
		$ct_user_circle_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve"> <path d="M10,19.5c-2.5,0-4.9-1-6.7-2.8S0.5,12.5,0.5,10c0-2.5,1-4.9,2.8-6.7S7.5,0.5,10,0.5s4.9,1,6.7,2.8s2.8,4.2,2.8,6.7 s-1,4.9-2.8,6.7S12.5,19.5,10,19.5z M10,1.5c-4.7,0-8.5,3.8-8.5,8.5s3.8,8.5,8.5,8.5s8.5-3.8,8.5-8.5S14.7,1.5,10,1.5z"/> <g> <path d="M10,11.8c-2.3,0-4.2-1.9-4.2-4.2S7.7,3.3,10,3.3s4.2,1.9,4.2,4.2S12.3,11.8,10,11.8z M10,4.3c-1.8,0-3.2,1.4-3.2,3.2 s1.4,3.2,3.2,3.2s3.2-1.4,3.2-3.2S11.8,4.3,10,4.3z"/> <path d="M4,16.6c0.1-0.4,0.3-0.8,0.6-1.2c1-1.3,2.9-2,5.4-2c2.5,0,4.4,0.7,5.4,2c0.3,0.4,0.5,0.9,0.6,1.2c0-0.6,0.1-0.6,0.9-0.6 c-0.1-0.3-0.3-0.7-0.6-1c-0.5-0.7-1.3-1.3-2.2-1.7c-1.1-0.5-2.5-0.8-4.1-0.8s-3,0.3-4.1,0.8c-0.9,0.4-1.7,1-2.2,1.7 c-0.3,0.3-0.4,0.7-0.6,1C3.9,16,4,16,4,16.6z"/> </g> </svg>';

		echo apply_filters('ct_user_circle_svg', $ct_user_circle_svg );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Heart SVG Icon - Outline */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_heart_outline_svg')) {
	function ct_heart_outline_svg() {
		$ct_heart_outline_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M9.5 19c-0.084 0-0.167-0.021-0.243-0.063-0.094-0.052-2.326-1.301-4.592-3.347-1.341-1.21-2.411-2.448-3.183-3.68-0.984-1.571-1.482-3.139-1.482-4.66 0-2.895 2.355-5.25 5.25-5.25 0.98 0 2.021 0.367 2.931 1.034 0.532 0.39 0.985 0.86 1.319 1.359 0.334-0.499 0.787-0.969 1.319-1.359 0.91-0.667 1.951-1.034 2.931-1.034 2.895 0 5.25 2.355 5.25 5.25 0 1.521-0.499 3.089-1.482 4.66-0.771 1.232-1.842 2.47-3.182 3.68-2.266 2.046-4.498 3.295-4.592 3.347-0.076 0.042-0.159 0.063-0.243 0.063zM5.25 3c-2.343 0-4.25 1.907-4.25 4.25 0 3.040 2.35 5.802 4.321 7.585 1.76 1.592 3.544 2.708 4.179 3.087 0.635-0.379 2.419-1.495 4.179-3.087 1.971-1.782 4.321-4.545 4.321-7.585 0-2.343-1.907-4.25-4.25-4.25-1.703 0-3.357 1.401-3.776 2.658-0.068 0.204-0.259 0.342-0.474 0.342s-0.406-0.138-0.474-0.342c-0.419-1.257-2.073-2.658-3.776-2.658z" fill="#878c92"></path> </svg>';

		echo apply_filters('ct_heart_outline_svg', $ct_heart_outline_svg );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Heart SVG Icon - Outline - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_heart_outline_svg_white')) {
	function ct_heart_outline_svg_white() {
		$ct_heart_outline_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M9.5 19c-0.084 0-0.167-0.021-0.243-0.063-0.094-0.052-2.326-1.301-4.592-3.347-1.341-1.21-2.411-2.448-3.183-3.68-0.984-1.571-1.482-3.139-1.482-4.66 0-2.895 2.355-5.25 5.25-5.25 0.98 0 2.021 0.367 2.931 1.034 0.532 0.39 0.985 0.86 1.319 1.359 0.334-0.499 0.787-0.969 1.319-1.359 0.91-0.667 1.951-1.034 2.931-1.034 2.895 0 5.25 2.355 5.25 5.25 0 1.521-0.499 3.089-1.482 4.66-0.771 1.232-1.842 2.47-3.182 3.68-2.266 2.046-4.498 3.295-4.592 3.347-0.076 0.042-0.159 0.063-0.243 0.063zM5.25 3c-2.343 0-4.25 1.907-4.25 4.25 0 3.040 2.35 5.802 4.321 7.585 1.76 1.592 3.544 2.708 4.179 3.087 0.635-0.379 2.419-1.495 4.179-3.087 1.971-1.782 4.321-4.545 4.321-7.585 0-2.343-1.907-4.25-4.25-4.25-1.703 0-3.357 1.401-3.776 2.658-0.068 0.204-0.259 0.342-0.474 0.342s-0.406-0.138-0.474-0.342c-0.419-1.257-2.073-2.658-3.776-2.658z" fill="#ffffff"></path> </svg>';

		echo apply_filters('ct_heart_outline_svg_white', $ct_heart_outline_svg_white);
	}
}

/*-----------------------------------------------------------------------------------*/
/* Heart SVG Icon - Solid */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_heart_solid_svg')) {
	function ct_heart_solid_svg() {
		$ct_heart_solid_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M9.5,19c-0.1,0-0.2,0-0.2-0.1c-0.1-0.1-2.3-1.3-4.6-3.3c-1.3-1.2-2.4-2.4-3.2-3.7C0.5,10.3,0,8.8,0,7.2C0,4.4,2.4,2,5.2,2 c1,0,2,0.4,2.9,1c0.5,0.4,1,0.9,1.3,1.4c0.3-0.5,0.8-1,1.3-1.4c0.9-0.7,2-1,2.9-1C16.6,2,19,4.4,19,7.2c0,1.5-0.5,3.1-1.5,4.7 c-0.8,1.2-1.8,2.5-3.2,3.7c-2.3,2-4.5,3.3-4.6,3.3C9.7,19,9.6,19,9.5,19L9.5,19z" fill="#ffffff"></path> </svg>';

		echo apply_filters('ct_heart_solid_svg', $ct_heart_solid_svg);
	}
}

/*-----------------------------------------------------------------------------------*/
/* Close SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_close_svg')) {
	function ct_close_svg() {
		$ct_close_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M10.707 10.5l8.646-8.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-8.646 8.646-8.646-8.646c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l8.646 8.646-8.646 8.646c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l8.646-8.646 8.646 8.646c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-8.646-8.646z"></path> </svg>';

		echo wp_kses( $ct_close_svg, ct_kses_svg() );
	}
}

function ct_fa_close_opt_two_svg() {
	$ct_fa_close_opt_two_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"/></svg>';
	echo wp_kses( $ct_fa_close_opt_two_svg, ct_kses_svg() );
}

function ct_fa_trash_can_svg() {
	$ct_fa_trash_can_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69zM31.1 128H416V448C416 483.3 387.3 512 352 512H95.1C60.65 512 31.1 483.3 31.1 448V128zM111.1 208V432C111.1 440.8 119.2 448 127.1 448C136.8 448 143.1 440.8 143.1 432V208C143.1 199.2 136.8 192 127.1 192C119.2 192 111.1 199.2 111.1 208zM207.1 208V432C207.1 440.8 215.2 448 223.1 448C232.8 448 240 440.8 240 432V208C240 199.2 232.8 192 223.1 192C215.2 192 207.1 199.2 207.1 208zM304 208V432C304 440.8 311.2 448 320 448C328.8 448 336 440.8 336 432V208C336 199.2 328.8 192 320 192C311.2 192 304 199.2 304 208z"/></svg>';
	echo wp_kses( $ct_fa_trash_can_svg, ct_kses_svg() );
}

/*-----------------------------------------------------------------------------------*/
/* Arrow Right SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_arrow_right_svg')) {
	function ct_arrow_right_svg() {
		$ct_arrow_right_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M19.354 10.146l-6-6c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l5.146 5.146h-16.293c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h16.293l-5.146 5.146c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l6-6c0.195-0.195 0.195-0.512 0-0.707z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_arrow_right_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* User SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_user_svg')) {
	function ct_user_svg() {
		$ct_user_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M9.5 11c-3.033 0-5.5-2.467-5.5-5.5s2.467-5.5 5.5-5.5 5.5 2.467 5.5 5.5-2.467 5.5-5.5 5.5zM9.5 1c-2.481 0-4.5 2.019-4.5 4.5s2.019 4.5 4.5 4.5c2.481 0 4.5-2.019 4.5-4.5s-2.019-4.5-4.5-4.5z" fill="#878c92"></path> <path d="M17.5 20h-16c-0.827 0-1.5-0.673-1.5-1.5 0-0.068 0.014-1.685 1.225-3.3 0.705-0.94 1.67-1.687 2.869-2.219 1.464-0.651 3.283-0.981 5.406-0.981s3.942 0.33 5.406 0.981c1.199 0.533 2.164 1.279 2.869 2.219 1.211 1.615 1.225 3.232 1.225 3.3 0 0.827-0.673 1.5-1.5 1.5zM9.5 13c-3.487 0-6.060 0.953-7.441 2.756-1.035 1.351-1.058 2.732-1.059 2.746 0 0.274 0.224 0.498 0.5 0.498h16c0.276 0 0.5-0.224 0.5-0.5-0-0.012-0.023-1.393-1.059-2.744-1.382-1.803-3.955-2.756-7.441-2.756z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_user_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* User SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_user_svg_white')) {
	function ct_user_svg_white() {
		$ct_user_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M9.5 11c-3.033 0-5.5-2.467-5.5-5.5s2.467-5.5 5.5-5.5 5.5 2.467 5.5 5.5-2.467 5.5-5.5 5.5zM9.5 1c-2.481 0-4.5 2.019-4.5 4.5s2.019 4.5 4.5 4.5c2.481 0 4.5-2.019 4.5-4.5s-2.019-4.5-4.5-4.5z" fill="#ffffff"></path> <path d="M17.5 20h-16c-0.827 0-1.5-0.673-1.5-1.5 0-0.068 0.014-1.685 1.225-3.3 0.705-0.94 1.67-1.687 2.869-2.219 1.464-0.651 3.283-0.981 5.406-0.981s3.942 0.33 5.406 0.981c1.199 0.533 2.164 1.279 2.869 2.219 1.211 1.615 1.225 3.232 1.225 3.3 0 0.827-0.673 1.5-1.5 1.5zM9.5 13c-3.487 0-6.060 0.953-7.441 2.756-1.035 1.351-1.058 2.732-1.059 2.746 0 0.274 0.224 0.498 0.5 0.498h16c0.276 0 0.5-0.224 0.5-0.5-0-0.012-0.023-1.393-1.059-2.744-1.382-1.803-3.955-2.756-7.441-2.756z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_user_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Clock SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_clock_svg')) {
	function ct_clock_svg() {
		$ct_clock_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.218 3.782c-1.794-1.794-4.18-2.782-6.718-2.782s-4.923 0.988-6.718 2.782c-1.794 1.794-2.782 4.18-2.782 6.717s0.988 4.923 2.782 6.718 4.18 2.782 6.718 2.782 4.923-0.988 6.718-2.782 2.782-4.18 2.782-6.718-0.988-4.923-2.782-6.717zM9.5 19c-4.687 0-8.5-3.813-8.5-8.5s3.813-8.5 8.5-8.5 8.5 3.813 8.5 8.5-3.813 8.5-8.5 8.5z" fill="#878c92"></path> <path d="M15.129 7.25c-0.138-0.239-0.444-0.321-0.683-0.183l-4.92 2.841-3.835-2.685c-0.226-0.158-0.538-0.103-0.696 0.123s-0.103 0.538 0.123 0.696l4.096 2.868c0.001 0.001 0.002 0.001 0.003 0.002 0.008 0.006 0.017 0.011 0.026 0.016 0.002 0.001 0.005 0.003 0.007 0.004 0.009 0.005 0.018 0.010 0.027 0.014 0.002 0.001 0.004 0.002 0.006 0.003 0.010 0.005 0.020 0.009 0.031 0.014 0.006 0.002 0.012 0.005 0.019 0.007 0.005 0.002 0.009 0.003 0.014 0.005 0.007 0.002 0.013 0.004 0.020 0.006 0.004 0.001 0.009 0.002 0.013 0.003 0.007 0.002 0.014 0.003 0.020 0.005 0.005 0.001 0.009 0.002 0.014 0.003 0.006 0.001 0.013 0.002 0.019 0.003s0.012 0.001 0.018 0.002c0.005 0.001 0.011 0.001 0.016 0.002 0.012 0.001 0.023 0.001 0.035 0.001 0.019 0 0.038-0.001 0.057-0.003 0-0 0.001-0 0.001-0 0.018-0.002 0.037-0.006 0.055-0.010 0.001-0 0.003-0.001 0.004-0.001 0.017-0.004 0.034-0.009 0.051-0.016 0.003-0.001 0.005-0.002 0.007-0.003 0.016-0.006 0.032-0.013 0.047-0.021 0.003-0.002 0.006-0.003 0.009-0.005 0.006-0.003 0.011-0.006 0.017-0.009l5.196-3c0.239-0.138 0.321-0.444 0.183-0.683z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_clock_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Clock SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_clock_svg_white')) {
	function ct_clock_svg_white() {
		$ct_clock_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.218 3.782c-1.794-1.794-4.18-2.782-6.718-2.782s-4.923 0.988-6.718 2.782c-1.794 1.794-2.782 4.18-2.782 6.717s0.988 4.923 2.782 6.718 4.18 2.782 6.718 2.782 4.923-0.988 6.718-2.782 2.782-4.18 2.782-6.718-0.988-4.923-2.782-6.717zM9.5 19c-4.687 0-8.5-3.813-8.5-8.5s3.813-8.5 8.5-8.5 8.5 3.813 8.5 8.5-3.813 8.5-8.5 8.5z" fill="#ffffff"></path> <path d="M15.129 7.25c-0.138-0.239-0.444-0.321-0.683-0.183l-4.92 2.841-3.835-2.685c-0.226-0.158-0.538-0.103-0.696 0.123s-0.103 0.538 0.123 0.696l4.096 2.868c0.001 0.001 0.002 0.001 0.003 0.002 0.008 0.006 0.017 0.011 0.026 0.016 0.002 0.001 0.005 0.003 0.007 0.004 0.009 0.005 0.018 0.010 0.027 0.014 0.002 0.001 0.004 0.002 0.006 0.003 0.010 0.005 0.020 0.009 0.031 0.014 0.006 0.002 0.012 0.005 0.019 0.007 0.005 0.002 0.009 0.003 0.014 0.005 0.007 0.002 0.013 0.004 0.020 0.006 0.004 0.001 0.009 0.002 0.013 0.003 0.007 0.002 0.014 0.003 0.020 0.005 0.005 0.001 0.009 0.002 0.014 0.003 0.006 0.001 0.013 0.002 0.019 0.003s0.012 0.001 0.018 0.002c0.005 0.001 0.011 0.001 0.016 0.002 0.012 0.001 0.023 0.001 0.035 0.001 0.019 0 0.038-0.001 0.057-0.003 0-0 0.001-0 0.001-0 0.018-0.002 0.037-0.006 0.055-0.010 0.001-0 0.003-0.001 0.004-0.001 0.017-0.004 0.034-0.009 0.051-0.016 0.003-0.001 0.005-0.002 0.007-0.003 0.016-0.006 0.032-0.013 0.047-0.021 0.003-0.002 0.006-0.003 0.009-0.005 0.006-0.003 0.011-0.006 0.017-0.009l5.196-3c0.239-0.138 0.321-0.444 0.183-0.683z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_clock_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Chevron Right SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_chevron_right_svg')) {
	function ct_chevron_right_svg() {
		$ct_chevron_right_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M5 20c-0.128 0-0.256-0.049-0.354-0.146-0.195-0.195-0.195-0.512 0-0.707l8.646-8.646-8.646-8.646c-0.195-0.195-0.195-0.512 0-0.707s0.512-0.195 0.707 0l9 9c0.195 0.195 0.195 0.512 0 0.707l-9 9c-0.098 0.098-0.226 0.146-0.354 0.146z" fill="#000000"></path> </svg>';

		echo wp_kses( $ct_chevron_right_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Chevron Left SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_chevron_left_svg')) {
	function ct_chevron_left_svg() {
		$ct_chevron_left_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M14 20c0.128 0 0.256-0.049 0.354-0.146 0.195-0.195 0.195-0.512 0-0.707l-8.646-8.646 8.646-8.646c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.707 0l-9 9c-0.195 0.195-0.195 0.512 0 0.707l9 9c0.098 0.098 0.226 0.146 0.354 0.146z" fill="#000000"></path> </svg>';

		echo wp_kses( $ct_chevron_left_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Virtual Tour SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_virtual_tour_svg_white')) {
	function ct_virtual_tour_svg_white() {
		$ct_virtual_tour_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M9.5 20c-2.538 0-4.923-0.988-6.718-2.782s-2.782-4.18-2.782-6.717c0-2.538 0.988-4.923 2.782-6.718s4.18-2.783 6.718-2.783c2.538 0 4.923 0.988 6.718 2.783s2.782 4.18 2.782 6.718-0.988 4.923-2.782 6.717c-1.794 1.794-4.18 2.782-6.718 2.782zM9.5 2c-4.687 0-8.5 3.813-8.5 8.5s3.813 8.5 8.5 8.5c4.687 0 8.5-3.813 8.5-8.5s-3.813-8.5-8.5-8.5z" fill="#ffffff"></path> <path d="M6.5 16c-0.083 0-0.167-0.021-0.242-0.063-0.159-0.088-0.258-0.256-0.258-0.437v-10c0-0.182 0.099-0.349 0.258-0.437s0.353-0.083 0.507 0.013l8 5c0.146 0.091 0.235 0.252 0.235 0.424s-0.089 0.333-0.235 0.424l-8 5c-0.081 0.051-0.173 0.076-0.265 0.076zM7 6.402v8.196l6.557-4.098-6.557-4.098z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_virtual_tour_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Images SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_images_svg_white')) {
	function ct_images_svg_white() {
		$ct_virtual_tour_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.5 20h-14c-0.827 0-1.5-0.673-1.5-1.5v-13c0-0.827 0.673-1.5 1.5-1.5h14c0.827 0 1.5 0.673 1.5 1.5v13c0 0.827-0.673 1.5-1.5 1.5zM2.5 5c-0.276 0-0.5 0.224-0.5 0.5v13c0 0.276 0.224 0.5 0.5 0.5h14c0.276 0 0.5-0.224 0.5-0.5v-13c0-0.276-0.224-0.5-0.5-0.5h-14z" fill="#ffffff"></path> <path d="M16.5 3h-14c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h14c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M15.5 1h-12c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h12c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M11.5 13c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5 1.5 0.673 1.5 1.5-0.673 1.5-1.5 1.5zM11.5 11c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5 0.5-0.224 0.5-0.5-0.224-0.5-0.5-0.5z" fill="#ffffff"></path> <path d="M14.5 8h-10c-0.276 0-0.5 0.224-0.5 0.5v7c0 0.276 0.224 0.5 0.5 0.5h10c0.276 0 0.5-0.224 0.5-0.5v-7c0-0.276-0.224-0.5-0.5-0.5zM5 13.675l1.266-1.582c0.073-0.091 0.164-0.142 0.259-0.144s0.189 0.044 0.266 0.131l2.596 2.92h-4.387v-1.325zM14 15h-3.275l-3.187-3.585c-0.272-0.306-0.651-0.476-1.039-0.466s-0.758 0.199-1.014 0.519l-0.485 0.606v-3.075h9v6z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_virtual_tour_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Leads SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_leads_svg')) {
	function ct_leads_svg() {
		$ct_leads_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M18.5 18h-11c-0.827 0-1.5-0.673-1.5-1.5 0-0.048 0.011-1.19 0.924-2.315 0.525-0.646 1.241-1.158 2.128-1.522 1.071-0.44 2.4-0.662 3.948-0.662s2.876 0.223 3.948 0.662c0.887 0.364 1.603 0.876 2.128 1.522 0.914 1.125 0.924 2.267 0.924 2.315 0 0.827-0.673 1.5-1.5 1.5zM7 16.503c0.001 0.275 0.225 0.497 0.5 0.497h11c0.275 0 0.499-0.223 0.5-0.497-0.001-0.035-0.032-0.895-0.739-1.734-0.974-1.157-2.793-1.768-5.261-1.768s-4.287 0.612-5.261 1.768c-0.707 0.84-0.738 1.699-0.739 1.734z" fill="#878c92"></path> <path d="M13 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4c0 2.206-1.794 4-4 4zM13 4c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3z" fill="#878c92"></path> <path d="M4.5 18h-3c-0.827 0-1.5-0.673-1.5-1.5 0-0.037 0.008-0.927 0.663-1.8 0.378-0.505 0.894-0.904 1.533-1.188 0.764-0.34 1.708-0.512 2.805-0.512 0.179 0 0.356 0.005 0.527 0.014 0.276 0.015 0.487 0.25 0.473 0.526s-0.25 0.488-0.526 0.473c-0.153-0.008-0.312-0.012-0.473-0.012-3.894 0-3.997 2.379-4 2.503 0.001 0.274 0.225 0.497 0.5 0.497h3c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M5 12c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3-1.346 3-3 3zM5 7c-1.103 0-2 0.897-2 2s0.897 2 2 2 2-0.897 2-2c0-1.103-0.897-2-2-2z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_leads_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Leads SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_leads_svg_white')) {
	function ct_leads_svg_white() {
		$ct_leads_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M18.5 18h-11c-0.827 0-1.5-0.673-1.5-1.5 0-0.048 0.011-1.19 0.924-2.315 0.525-0.646 1.241-1.158 2.128-1.522 1.071-0.44 2.4-0.662 3.948-0.662s2.876 0.223 3.948 0.662c0.887 0.364 1.603 0.876 2.128 1.522 0.914 1.125 0.924 2.267 0.924 2.315 0 0.827-0.673 1.5-1.5 1.5zM7 16.503c0.001 0.275 0.225 0.497 0.5 0.497h11c0.275 0 0.499-0.223 0.5-0.497-0.001-0.035-0.032-0.895-0.739-1.734-0.974-1.157-2.793-1.768-5.261-1.768s-4.287 0.612-5.261 1.768c-0.707 0.84-0.738 1.699-0.739 1.734z" fill="#ffffff"></path> <path d="M13 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4c0 2.206-1.794 4-4 4zM13 4c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3z" fill="#ffffff"></path> <path d="M4.5 18h-3c-0.827 0-1.5-0.673-1.5-1.5 0-0.037 0.008-0.927 0.663-1.8 0.378-0.505 0.894-0.904 1.533-1.188 0.764-0.34 1.708-0.512 2.805-0.512 0.179 0 0.356 0.005 0.527 0.014 0.276 0.015 0.487 0.25 0.473 0.526s-0.25 0.488-0.526 0.473c-0.153-0.008-0.312-0.012-0.473-0.012-3.894 0-3.997 2.379-4 2.503 0.001 0.274 0.225 0.497 0.5 0.497h3c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M5 12c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3-1.346 3-3 3zM5 7c-1.103 0-2 0.897-2 2s0.897 2 2 2 2-0.897 2-2c0-1.103-0.897-2-2-2z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_leads_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Leads SVG Icon - Green */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_leads_svg_green')) {
	function ct_leads_svg_green() {
		$ct_leads_svg_green = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M18.5 18h-11c-0.827 0-1.5-0.673-1.5-1.5 0-0.048 0.011-1.19 0.924-2.315 0.525-0.646 1.241-1.158 2.128-1.522 1.071-0.44 2.4-0.662 3.948-0.662s2.876 0.223 3.948 0.662c0.887 0.364 1.603 0.876 2.128 1.522 0.914 1.125 0.924 2.267 0.924 2.315 0 0.827-0.673 1.5-1.5 1.5zM7 16.503c0.001 0.275 0.225 0.497 0.5 0.497h11c0.275 0 0.499-0.223 0.5-0.497-0.001-0.035-0.032-0.895-0.739-1.734-0.974-1.157-2.793-1.768-5.261-1.768s-4.287 0.612-5.261 1.768c-0.707 0.84-0.738 1.699-0.739 1.734z" fill="#5cb400"></path> <path d="M13 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4c0 2.206-1.794 4-4 4zM13 4c-1.654 0-3 1.346-3 3s1.346 3 3 3 3-1.346 3-3-1.346-3-3-3z" fill="#5cb400"></path> <path d="M4.5 18h-3c-0.827 0-1.5-0.673-1.5-1.5 0-0.037 0.008-0.927 0.663-1.8 0.378-0.505 0.894-0.904 1.533-1.188 0.764-0.34 1.708-0.512 2.805-0.512 0.179 0 0.356 0.005 0.527 0.014 0.276 0.015 0.487 0.25 0.473 0.526s-0.25 0.488-0.526 0.473c-0.153-0.008-0.312-0.012-0.473-0.012-3.894 0-3.997 2.379-4 2.503 0.001 0.274 0.225 0.497 0.5 0.497h3c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#5cb400"></path> <path d="M5 12c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3-1.346 3-3 3zM5 7c-1.103 0-2 0.897-2 2s0.897 2 2 2 2-0.897 2-2c0-1.103-0.897-2-2-2z" fill="#5cb400"></path> </svg>';

		echo wp_kses( $ct_leads_svg_green, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Leads Routing SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_leads_routing_svg')) {
	function ct_leads_routing_svg() {
		$ct_leads_routing_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M13 6.467c-0.004-0.166-0.038-0.909-0.351-1.659-0.214-0.514-0.514-0.928-0.891-1.229-0.096-0.077-0.198-0.146-0.303-0.208 0.338-0.358 0.545-0.84 0.545-1.37 0-1.103-0.897-2-2-2s-2 0.897-2 2c0 0.53 0.208 1.012 0.545 1.37-0.105 0.062-0.206 0.131-0.303 0.208-0.377 0.302-0.677 0.716-0.891 1.229-0.347 0.833-0.351 1.658-0.351 1.692 0 0.276 0.224 0.5 0.5 0.5h5c0 0 0 0 0.001 0 0.276 0 0.5-0.224 0.5-0.5 0-0.011-0-0.022-0.001-0.033zM9 2c0-0.551 0.449-1 1-1s1 0.449 1 1-0.449 1-1 1c-0.551 0-1-0.449-1-1zM8.050 6c0.038-0.234 0.106-0.523 0.224-0.808 0.334-0.802 0.899-1.192 1.726-1.192 1.429 0 1.837 1.268 1.953 2h-3.904z" fill="#878c92"></path> <path d="M6 17.467c-0.004-0.166-0.038-0.909-0.351-1.659-0.214-0.514-0.514-0.927-0.891-1.229-0.096-0.077-0.198-0.146-0.303-0.208 0.338-0.358 0.545-0.84 0.545-1.37 0-1.103-0.897-2-2-2s-2 0.897-2 2c0 0.53 0.208 1.012 0.545 1.37-0.105 0.062-0.206 0.131-0.303 0.208-0.377 0.302-0.677 0.716-0.891 1.229-0.347 0.833-0.351 1.658-0.351 1.692 0 0.276 0.224 0.5 0.5 0.5h5c0-0 0-0 0.001 0 0.276 0 0.5-0.224 0.5-0.5 0-0.011-0-0.022-0.001-0.033zM2 13c0-0.551 0.449-1 1-1s1 0.449 1 1-0.449 1-1 1-1-0.449-1-1zM1.050 17c0.038-0.234 0.106-0.523 0.224-0.808 0.334-0.802 0.899-1.192 1.726-1.192 1.429 0 1.837 1.268 1.953 2h-3.904z" fill="#878c92"></path> <path d="M20 17.467c-0.004-0.166-0.038-0.909-0.351-1.659-0.214-0.514-0.514-0.927-0.891-1.229-0.096-0.077-0.198-0.146-0.303-0.208 0.338-0.358 0.545-0.84 0.545-1.37 0-1.103-0.897-2-2-2s-2 0.897-2 2c0 0.53 0.208 1.012 0.545 1.37-0.105 0.062-0.206 0.131-0.303 0.208-0.377 0.302-0.677 0.716-0.891 1.229-0.347 0.833-0.351 1.658-0.351 1.692 0 0.276 0.224 0.5 0.5 0.5h5c0 0 0 0 0.001 0 0.276 0 0.5-0.224 0.5-0.5 0-0.011-0-0.022-0.001-0.033zM16 13c0-0.551 0.449-1 1-1s1 0.449 1 1-0.449 1-1 1-1-0.449-1-1zM15.050 17c0.038-0.234 0.106-0.523 0.224-0.808 0.334-0.802 0.899-1.192 1.726-1.192 1.429 0 1.837 1.268 1.953 2h-3.904z" fill="#878c92"></path> <path d="M10 20c-1.3 0-2.591-0.319-3.734-0.923-0.244-0.129-0.337-0.432-0.208-0.676s0.432-0.337 0.676-0.208c0.999 0.528 2.128 0.807 3.266 0.807s2.267-0.279 3.266-0.807c0.244-0.129 0.547-0.036 0.676 0.208s0.036 0.547-0.208 0.676c-1.142 0.604-2.433 0.923-3.734 0.923z" fill="#878c92"></path> <path d="M17.23 10.5c-0.219 0-0.421-0.145-0.482-0.367-0.464-1.68-1.535-3.12-3.015-4.056-0.233-0.148-0.303-0.456-0.156-0.69s0.456-0.303 0.69-0.156c1.692 1.069 2.915 2.715 3.445 4.635 0.073 0.266-0.083 0.542-0.349 0.615-0.044 0.012-0.089 0.018-0.133 0.018z" fill="#878c92"></path> <path d="M2.77 10.5c-0.044 0-0.089-0.006-0.133-0.018-0.266-0.073-0.422-0.349-0.349-0.615 0.53-1.92 1.754-3.566 3.445-4.635 0.233-0.147 0.542-0.078 0.69 0.156s0.078 0.542-0.156 0.69c-1.481 0.935-2.552 2.376-3.015 4.056-0.061 0.222-0.262 0.367-0.482 0.367z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_leads_routing_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Leads Routing SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_leads_routing_svg_white')) {
	function ct_leads_routing_svg_white() {
		$ct_leads_routing_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M13 6.467c-0.004-0.166-0.038-0.909-0.351-1.659-0.214-0.514-0.514-0.928-0.891-1.229-0.096-0.077-0.198-0.146-0.303-0.208 0.338-0.358 0.545-0.84 0.545-1.37 0-1.103-0.897-2-2-2s-2 0.897-2 2c0 0.53 0.208 1.012 0.545 1.37-0.105 0.062-0.206 0.131-0.303 0.208-0.377 0.302-0.677 0.716-0.891 1.229-0.347 0.833-0.351 1.658-0.351 1.692 0 0.276 0.224 0.5 0.5 0.5h5c0 0 0 0 0.001 0 0.276 0 0.5-0.224 0.5-0.5 0-0.011-0-0.022-0.001-0.033zM9 2c0-0.551 0.449-1 1-1s1 0.449 1 1-0.449 1-1 1c-0.551 0-1-0.449-1-1zM8.050 6c0.038-0.234 0.106-0.523 0.224-0.808 0.334-0.802 0.899-1.192 1.726-1.192 1.429 0 1.837 1.268 1.953 2h-3.904z" fill="#ffffff"></path> <path d="M6 17.467c-0.004-0.166-0.038-0.909-0.351-1.659-0.214-0.514-0.514-0.927-0.891-1.229-0.096-0.077-0.198-0.146-0.303-0.208 0.338-0.358 0.545-0.84 0.545-1.37 0-1.103-0.897-2-2-2s-2 0.897-2 2c0 0.53 0.208 1.012 0.545 1.37-0.105 0.062-0.206 0.131-0.303 0.208-0.377 0.302-0.677 0.716-0.891 1.229-0.347 0.833-0.351 1.658-0.351 1.692 0 0.276 0.224 0.5 0.5 0.5h5c0-0 0-0 0.001 0 0.276 0 0.5-0.224 0.5-0.5 0-0.011-0-0.022-0.001-0.033zM2 13c0-0.551 0.449-1 1-1s1 0.449 1 1-0.449 1-1 1-1-0.449-1-1zM1.050 17c0.038-0.234 0.106-0.523 0.224-0.808 0.334-0.802 0.899-1.192 1.726-1.192 1.429 0 1.837 1.268 1.953 2h-3.904z" fill="#ffffff"></path> <path d="M20 17.467c-0.004-0.166-0.038-0.909-0.351-1.659-0.214-0.514-0.514-0.927-0.891-1.229-0.096-0.077-0.198-0.146-0.303-0.208 0.338-0.358 0.545-0.84 0.545-1.37 0-1.103-0.897-2-2-2s-2 0.897-2 2c0 0.53 0.208 1.012 0.545 1.37-0.105 0.062-0.206 0.131-0.303 0.208-0.377 0.302-0.677 0.716-0.891 1.229-0.347 0.833-0.351 1.658-0.351 1.692 0 0.276 0.224 0.5 0.5 0.5h5c0 0 0 0 0.001 0 0.276 0 0.5-0.224 0.5-0.5 0-0.011-0-0.022-0.001-0.033zM16 13c0-0.551 0.449-1 1-1s1 0.449 1 1-0.449 1-1 1-1-0.449-1-1zM15.050 17c0.038-0.234 0.106-0.523 0.224-0.808 0.334-0.802 0.899-1.192 1.726-1.192 1.429 0 1.837 1.268 1.953 2h-3.904z" fill="#ffffff"></path> <path d="M10 20c-1.3 0-2.591-0.319-3.734-0.923-0.244-0.129-0.337-0.432-0.208-0.676s0.432-0.337 0.676-0.208c0.999 0.528 2.128 0.807 3.266 0.807s2.267-0.279 3.266-0.807c0.244-0.129 0.547-0.036 0.676 0.208s0.036 0.547-0.208 0.676c-1.142 0.604-2.433 0.923-3.734 0.923z" fill="#ffffff"></path> <path d="M17.23 10.5c-0.219 0-0.421-0.145-0.482-0.367-0.464-1.68-1.535-3.12-3.015-4.056-0.233-0.148-0.303-0.456-0.156-0.69s0.456-0.303 0.69-0.156c1.692 1.069 2.915 2.715 3.445 4.635 0.073 0.266-0.083 0.542-0.349 0.615-0.044 0.012-0.089 0.018-0.133 0.018z" fill="#ffffff"></path> <path d="M2.77 10.5c-0.044 0-0.089-0.006-0.133-0.018-0.266-0.073-0.422-0.349-0.349-0.615 0.53-1.92 1.754-3.566 3.445-4.635 0.233-0.147 0.542-0.078 0.69 0.156s0.078 0.542-0.156 0.69c-1.481 0.935-2.552 2.376-3.015 4.056-0.061 0.222-0.262 0.367-0.482 0.367z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_leads_routing_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Activity SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_activity_svg')) {
	function ct_activity_svg() {
		$ct_activity_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M5.5 20c-0.098 0-0.197-0.029-0.283-0.088-0.199-0.136-0.272-0.395-0.174-0.615l3.243-7.297h-4.786c-0.202 0-0.385-0.122-0.462-0.309s-0.035-0.402 0.108-0.545l10-10c0.17-0.17 0.438-0.195 0.637-0.059s0.272 0.395 0.174 0.615l-3.243 7.297h4.786c0.202 0 0.385 0.122 0.462 0.309s0.035 0.402-0.108 0.545l-10 10c-0.097 0.097-0.225 0.146-0.354 0.146zM4.707 11h4.348c0.169 0 0.327 0.086 0.419 0.228s0.106 0.321 0.038 0.476l-2.462 5.539 7.242-7.242h-4.348c-0.169 0-0.327-0.086-0.419-0.228s-0.106-0.321-0.038-0.476l2.462-5.539-7.242 7.242z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_activity_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Activity SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_activity_svg_white')) {
	function ct_activity_svg_white() {
		$ct_activity_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M5.5 20c-0.098 0-0.197-0.029-0.283-0.088-0.199-0.136-0.272-0.395-0.174-0.615l3.243-7.297h-4.786c-0.202 0-0.385-0.122-0.462-0.309s-0.035-0.402 0.108-0.545l10-10c0.17-0.17 0.438-0.195 0.637-0.059s0.272 0.395 0.174 0.615l-3.243 7.297h4.786c0.202 0 0.385 0.122 0.462 0.309s0.035 0.402-0.108 0.545l-10 10c-0.097 0.097-0.225 0.146-0.354 0.146zM4.707 11h4.348c0.169 0 0.327 0.086 0.419 0.228s0.106 0.321 0.038 0.476l-2.462 5.539 7.242-7.242h-4.348c-0.169 0-0.327-0.086-0.419-0.228s-0.106-0.321-0.038-0.476l2.462-5.539-7.242 7.242z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_activity_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Activity SVG Icon - Orange */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_activity_svg_orange')) {
	function ct_activity_svg_orange() {
		$ct_activity_svg_orange = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M5.5 20c-0.098 0-0.197-0.029-0.283-0.088-0.199-0.136-0.272-0.395-0.174-0.615l3.243-7.297h-4.786c-0.202 0-0.385-0.122-0.462-0.309s-0.035-0.402 0.108-0.545l10-10c0.17-0.17 0.438-0.195 0.637-0.059s0.272 0.395 0.174 0.615l-3.243 7.297h4.786c0.202 0 0.385 0.122 0.462 0.309s0.035 0.402-0.108 0.545l-10 10c-0.097 0.097-0.225 0.146-0.354 0.146zM4.707 11h4.348c0.169 0 0.327 0.086 0.419 0.228s0.106 0.321 0.038 0.476l-2.462 5.539 7.242-7.242h-4.348c-0.169 0-0.327-0.086-0.419-0.228s-0.106-0.321-0.038-0.476l2.462-5.539-7.242 7.242z" fill="#ff6200"></path> </svg>';

		echo wp_kses( $ct_activity_svg_orange, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Dashboard SVG Icon - Solid */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_dashboard_svg_white')) {
	function ct_dashboard_svg_white() {
		$ct_dashboard_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M0.5 12c-0.12 0-0.24-0.043-0.335-0.129-0.205-0.185-0.221-0.501-0.035-0.706l8.829-9.758c0.274-0.303 0.644-0.47 1.042-0.47 0 0 0 0 0 0 0.397 0 0.767 0.167 1.042 0.47l8.829 9.758c0.185 0.205 0.169 0.521-0.035 0.706s-0.521 0.169-0.706-0.035l-8.829-9.758c-0.082-0.091-0.189-0.141-0.3-0.141s-0.218 0.050-0.3 0.141l-8.829 9.758c-0.099 0.109-0.235 0.165-0.371 0.165z" fill="#ffffff"></path> <path d="M15.5 20h-11c-0.827 0-1.5-0.673-1.5-1.5v-8c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v8c0 0.276 0.224 0.5 0.5 0.5h11c0.276 0 0.5-0.224 0.5-0.5v-8c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v8c0 0.827-0.673 1.5-1.5 1.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_dashboard_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Sources SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_sources_svg_white')) {
	function ct_sources_svg_white() {
		$ct_sources_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M17 13.050v-1.55c0-0.827-0.673-1.5-1.5-1.5h-5.5v-2.050c1.14-0.232 2-1.242 2-2.45 0-1.378-1.122-2.5-2.5-2.5s-2.5 1.122-2.5 2.5c0 1.207 0.86 2.217 2 2.45v2.050h-5.5c-0.827 0-1.5 0.673-1.5 1.5v1.55c-1.14 0.232-2 1.242-2 2.45 0 1.378 1.122 2.5 2.5 2.5s2.5-1.122 2.5-2.5c0-1.207-0.86-2.217-2-2.45v-1.55c0-0.276 0.224-0.5 0.5-0.5h5.5v2.050c-1.14 0.232-2 1.242-2 2.45 0 1.378 1.122 2.5 2.5 2.5s2.5-1.122 2.5-2.5c0-1.207-0.86-2.217-2-2.45v-2.050h5.5c0.276 0 0.5 0.224 0.5 0.5v1.55c-1.14 0.232-2 1.242-2 2.45 0 1.378 1.122 2.5 2.5 2.5s2.5-1.122 2.5-2.5c0-1.207-0.86-2.217-2-2.45zM8 5.5c0-0.827 0.673-1.5 1.5-1.5s1.5 0.673 1.5 1.5-0.673 1.5-1.5 1.5c-0.827 0-1.5-0.673-1.5-1.5zM4 15.5c0 0.827-0.673 1.5-1.5 1.5s-1.5-0.673-1.5-1.5 0.673-1.5 1.5-1.5 1.5 0.673 1.5 1.5zM11 15.5c0 0.827-0.673 1.5-1.5 1.5s-1.5-0.673-1.5-1.5 0.673-1.5 1.5-1.5c0.827 0 1.5 0.673 1.5 1.5zM16.5 17c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5 1.5 0.673 1.5 1.5-0.673 1.5-1.5 1.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_sources_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* To-Do SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_todo_svg')) {
	function ct_todo_svg() {
		$ct_todo_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.5 20h-14c-0.827 0-1.5-0.673-1.5-1.5v-14c0-0.827 0.673-1.5 1.5-1.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5 0.224-0.5 0.5v14c0 0.276 0.224 0.5 0.5 0.5h14c0.276 0 0.5-0.224 0.5-0.5v-14c0-0.276-0.224-0.5-0.5-0.5h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.827 0 1.5 0.673 1.5 1.5v14c0 0.827-0.673 1.5-1.5 1.5z" fill="#878c92"></path> <path d="M13.501 5c-0 0-0 0-0.001 0h-8c-0.276 0-0.5-0.224-0.5-0.5 0-1.005 0.453-1.786 1.276-2.197 0.275-0.138 0.547-0.213 0.764-0.254 0.213-1.164 1.235-2.049 2.459-2.049s2.246 0.885 2.459 2.049c0.218 0.041 0.489 0.116 0.764 0.254 0.816 0.408 1.268 1.178 1.276 2.17 0.001 0.009 0.001 0.018 0.001 0.027 0 0.276-0.224 0.5-0.5 0.5zM6.060 4h6.88c-0.096-0.356-0.307-0.617-0.638-0.79-0.389-0.203-0.8-0.21-0.805-0.21-0.276 0-0.497-0.224-0.497-0.5 0-0.827-0.673-1.5-1.5-1.5s-1.5 0.673-1.5 1.5c0 0.276-0.224 0.5-0.5 0.5-0.001 0-0.413 0.007-0.802 0.21-0.331 0.173-0.542 0.433-0.638 0.79z" fill="#878c92"></path> <path d="M9.5 3c-0.132 0-0.261-0.053-0.353-0.147s-0.147-0.222-0.147-0.353 0.053-0.261 0.147-0.353c0.093-0.093 0.222-0.147 0.353-0.147s0.261 0.053 0.353 0.147c0.093 0.093 0.147 0.222 0.147 0.353s-0.053 0.26-0.147 0.353c-0.093 0.093-0.222 0.147-0.353 0.147z" fill="#878c92"></path> <path d="M8 14c-0.128 0-0.256-0.049-0.354-0.146l-1.5-1.5c-0.195-0.195-0.195-0.512 0-0.707s0.512-0.195 0.707 0l1.146 1.146 4.146-4.146c0.195-0.195 0.512-0.195 0.707 0s0.195 0.512 0 0.707l-4.5 4.5c-0.098 0.098-0.226 0.146-0.354 0.146z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_todo_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* To-Do SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_todo_svg_white')) {
	function ct_todo_svg_white() {
		$ct_todo_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.5 20h-14c-0.827 0-1.5-0.673-1.5-1.5v-14c0-0.827 0.673-1.5 1.5-1.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5 0.224-0.5 0.5v14c0 0.276 0.224 0.5 0.5 0.5h14c0.276 0 0.5-0.224 0.5-0.5v-14c0-0.276-0.224-0.5-0.5-0.5h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.827 0 1.5 0.673 1.5 1.5v14c0 0.827-0.673 1.5-1.5 1.5z" fill="#ffffff"></path> <path d="M13.501 5c-0 0-0 0-0.001 0h-8c-0.276 0-0.5-0.224-0.5-0.5 0-1.005 0.453-1.786 1.276-2.197 0.275-0.138 0.547-0.213 0.764-0.254 0.213-1.164 1.235-2.049 2.459-2.049s2.246 0.885 2.459 2.049c0.218 0.041 0.489 0.116 0.764 0.254 0.816 0.408 1.268 1.178 1.276 2.17 0.001 0.009 0.001 0.018 0.001 0.027 0 0.276-0.224 0.5-0.5 0.5zM6.060 4h6.88c-0.096-0.356-0.307-0.617-0.638-0.79-0.389-0.203-0.8-0.21-0.805-0.21-0.276 0-0.497-0.224-0.497-0.5 0-0.827-0.673-1.5-1.5-1.5s-1.5 0.673-1.5 1.5c0 0.276-0.224 0.5-0.5 0.5-0.001 0-0.413 0.007-0.802 0.21-0.331 0.173-0.542 0.433-0.638 0.79z" fill="#ffffff"></path> <path d="M9.5 3c-0.132 0-0.261-0.053-0.353-0.147s-0.147-0.222-0.147-0.353 0.053-0.261 0.147-0.353c0.093-0.093 0.222-0.147 0.353-0.147s0.261 0.053 0.353 0.147c0.093 0.093 0.147 0.222 0.147 0.353s-0.053 0.26-0.147 0.353c-0.093 0.093-0.222 0.147-0.353 0.147z" fill="#ffffff"></path> <path d="M8 14c-0.128 0-0.256-0.049-0.354-0.146l-1.5-1.5c-0.195-0.195-0.195-0.512 0-0.707s0.512-0.195 0.707 0l1.146 1.146 4.146-4.146c0.195-0.195 0.512-0.195 0.707 0s0.195 0.512 0 0.707l-4.5 4.5c-0.098 0.098-0.226 0.146-0.354 0.146z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_todo_svg_white, ct_kses_svg() );
	}
}


/*-----------------------------------------------------------------------------------*/
/* Files SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_files_svg')) {
	function ct_files_svg() {
		$ct_files_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M18.5 18h-17c-0.827 0-1.5-0.673-1.5-1.5v-10.5c0-0.352 0.119-0.856 0.276-1.171l0.553-1.106c0.206-0.413 0.71-0.724 1.171-0.724h7c0.461 0 0.964 0.311 1.171 0.724l0.553 1.106c0.038 0.077 0.191 0.171 0.276 0.171h7.5c0.827 0 1.5 0.673 1.5 1.5v10c0 0.827-0.673 1.5-1.5 1.5zM2 4c-0.086 0-0.238 0.094-0.276 0.171l-0.553 1.106c-0.088 0.176-0.171 0.527-0.171 0.724v10.5c0 0.276 0.224 0.5 0.5 0.5h17c0.276 0 0.5-0.224 0.5-0.5v-10c0-0.276-0.224-0.5-0.5-0.5h-7.5c-0.461 0-0.965-0.311-1.171-0.724l-0.553-1.106c-0.038-0.077-0.191-0.171-0.276-0.171h-7z" fill="#878c92"></path> <path d="M12.5 11h-2.5v-2.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v2.5h-2.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h2.5v2.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-2.5h2.5c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_files_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Files SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_files_svg_white')) {
	function ct_files_svg_white() {
		$ct_files_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M18.5 18h-17c-0.827 0-1.5-0.673-1.5-1.5v-10.5c0-0.352 0.119-0.856 0.276-1.171l0.553-1.106c0.206-0.413 0.71-0.724 1.171-0.724h7c0.461 0 0.964 0.311 1.171 0.724l0.553 1.106c0.038 0.077 0.191 0.171 0.276 0.171h7.5c0.827 0 1.5 0.673 1.5 1.5v10c0 0.827-0.673 1.5-1.5 1.5zM2 4c-0.086 0-0.238 0.094-0.276 0.171l-0.553 1.106c-0.088 0.176-0.171 0.527-0.171 0.724v10.5c0 0.276 0.224 0.5 0.5 0.5h17c0.276 0 0.5-0.224 0.5-0.5v-10c0-0.276-0.224-0.5-0.5-0.5h-7.5c-0.461 0-0.965-0.311-1.171-0.724l-0.553-1.106c-0.038-0.077-0.191-0.171-0.276-0.171h-7z" fill="#ffffff"></path> <path d="M12.5 11h-2.5v-2.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v2.5h-2.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h2.5v2.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-2.5h2.5c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_files_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Funnel SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_funnel_svg_white')) {
	function ct_funnel_svg_white() {
		$ct_funnel_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.23 3.307c-0.396-0.268-0.949-0.504-1.643-0.702-1.366-0.39-3.172-0.605-5.087-0.605s-3.722 0.215-5.087 0.605c-0.694 0.198-1.246 0.434-1.643 0.702-0.637 0.43-0.77 0.886-0.77 1.193v0.5c0 0.428 0.321 1.133 0.639 1.609l4.891 7.336c0.251 0.376 0.471 1.103 0.471 1.555v3c0 0.173 0.090 0.334 0.237 0.425 0.080 0.050 0.171 0.075 0.263 0.075 0.076 0 0.153-0.018 0.224-0.053l2-1c0.169-0.085 0.276-0.258 0.276-0.447v-2c0-0.452 0.22-1.179 0.471-1.555l4.891-7.336c0.317-0.476 0.639-1.182 0.639-1.609v-0.5c0-0.307-0.134-0.763-0.77-1.193zM4.688 3.567c1.279-0.365 2.988-0.567 4.812-0.567s3.534 0.201 4.812 0.567c1.378 0.394 1.688 0.816 1.688 0.933s-0.31 0.54-1.688 0.933c-1.279 0.365-2.988 0.567-4.812 0.567s-3.534-0.201-4.812-0.567c-1.378-0.394-1.688-0.816-1.688-0.933s0.31-0.54 1.688-0.933zM10.639 13.391c-0.358 0.537-0.639 1.464-0.639 2.109v1.691l-1 0.5v-2.191c0-0.646-0.281-1.572-0.639-2.109l-4.88-7.32c0.274 0.117 0.585 0.226 0.932 0.324 1.366 0.39 3.172 0.605 5.087 0.605s3.722-0.215 5.087-0.605c0.346-0.099 0.658-0.207 0.932-0.325l-4.88 7.32z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_funnel_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Logout SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_logout_svg')) {
	function ct_logout_svg() {
		$ct_logout_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M11.5 8c0.276 0 0.5-0.224 0.5-0.5v-4c0-0.827-0.673-1.5-1.5-1.5h-9c-0.827 0-1.5 0.673-1.5 1.5v12c0 0.746 0.537 1.56 1.222 1.853l5.162 2.212c0.178 0.076 0.359 0.114 0.532 0.114 0.213-0 0.416-0.058 0.589-0.172 0.314-0.207 0.495-0.575 0.495-1.008v-1.5h2.5c0.827 0 1.5-0.673 1.5-1.5v-4c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v4c0 0.276-0.224 0.5-0.5 0.5h-2.5v-9.5c0-0.746-0.537-1.56-1.222-1.853l-3.842-1.647h7.564c0.276 0 0.5 0.224 0.5 0.5v4c0 0.276 0.224 0.5 0.5 0.5zM6.384 5.566c0.322 0.138 0.616 0.584 0.616 0.934v12c0 0.104-0.028 0.162-0.045 0.173s-0.081 0.014-0.177-0.027l-5.162-2.212c-0.322-0.138-0.616-0.583-0.616-0.934v-12c0-0.079 0.018-0.153 0.051-0.22l5.333 2.286z" fill="#878c92"></path> <path d="M18.354 9.146l-3-3c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l2.146 2.146h-6.293c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h6.293l-2.146 2.146c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l3-3c0.195-0.195 0.195-0.512 0-0.707z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_logout_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Logout SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_logout_svg_white')) {
	function ct_logout_svg_white() {
		$ct_logout_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M11.5 8c0.276 0 0.5-0.224 0.5-0.5v-4c0-0.827-0.673-1.5-1.5-1.5h-9c-0.827 0-1.5 0.673-1.5 1.5v12c0 0.746 0.537 1.56 1.222 1.853l5.162 2.212c0.178 0.076 0.359 0.114 0.532 0.114 0.213-0 0.416-0.058 0.589-0.172 0.314-0.207 0.495-0.575 0.495-1.008v-1.5h2.5c0.827 0 1.5-0.673 1.5-1.5v-4c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v4c0 0.276-0.224 0.5-0.5 0.5h-2.5v-9.5c0-0.746-0.537-1.56-1.222-1.853l-3.842-1.647h7.564c0.276 0 0.5 0.224 0.5 0.5v4c0 0.276 0.224 0.5 0.5 0.5zM6.384 5.566c0.322 0.138 0.616 0.584 0.616 0.934v12c0 0.104-0.028 0.162-0.045 0.173s-0.081 0.014-0.177-0.027l-5.162-2.212c-0.322-0.138-0.616-0.583-0.616-0.934v-12c0-0.079 0.018-0.153 0.051-0.22l5.333 2.286z" fill="#ffffff"></path> <path d="M18.354 9.146l-3-3c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l2.146 2.146h-6.293c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h6.293l-2.146 2.146c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l3-3c0.195-0.195 0.195-0.512 0-0.707z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_logout_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Alert SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_alert_svg')) {
	function ct_alert_svg() {
		$ct_alert_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M10.5 0h-4c-0.276 0-0.5 0.224-0.5 0.5v5.586c-0.582 0.206-1 0.762-1 1.414 0 0.827 0.673 1.5 1.5 1.5s1.5-0.673 1.5-1.5c0-0.652-0.418-1.208-1-1.414v-3.086h3.5c0.276 0 0.5-0.224 0.5-0.5v-2c0-0.276-0.224-0.5-0.5-0.5zM6.5 8c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5 0.5 0.224 0.5 0.5-0.224 0.5-0.5 0.5zM10 2h-3v-1h3v1z" fill="#878c92"></path> <path d="M19.088 6.945c-0.354-0.916-0.818-1.628-1.38-2.118-0.628-0.548-1.38-0.826-2.234-0.826h-6.974c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h6.974c2.79 0 3.469 4.236 3.522 8h-15.997v-8h1.5c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-1.5v-0.5c0-0.276-0.224-0.5-0.5-0.5h-2c-0.276 0-0.5 0.224-0.5 0.5v11c0 0.276 0.224 0.5 0.5 0.5h2c0.276 0 0.5-0.224 0.5-0.5v-0.5h5v5.5c0 0.276 0.224 0.5 0.5 0.5h3c0.276 0 0.5-0.224 0.5-0.5v-5.5h7.5c0.276 0 0.5-0.224 0.5-0.5 0-2.785-0.307-4.99-0.912-6.555zM2 14h-1v-10h1v10zM11 19h-2v-5h2v5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_alert_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Alert SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_alert_svg_white')) {
	function ct_alert_svg_white() {
		$ct_alert_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M10.5 0h-4c-0.276 0-0.5 0.224-0.5 0.5v5.586c-0.582 0.206-1 0.762-1 1.414 0 0.827 0.673 1.5 1.5 1.5s1.5-0.673 1.5-1.5c0-0.652-0.418-1.208-1-1.414v-3.086h3.5c0.276 0 0.5-0.224 0.5-0.5v-2c0-0.276-0.224-0.5-0.5-0.5zM6.5 8c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5 0.5 0.224 0.5 0.5-0.224 0.5-0.5 0.5zM10 2h-3v-1h3v1z" fill="#ffffff"></path> <path d="M19.088 6.945c-0.354-0.916-0.818-1.628-1.38-2.118-0.628-0.548-1.38-0.826-2.234-0.826h-6.974c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h6.974c2.79 0 3.469 4.236 3.522 8h-15.997v-8h1.5c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-1.5v-0.5c0-0.276-0.224-0.5-0.5-0.5h-2c-0.276 0-0.5 0.224-0.5 0.5v11c0 0.276 0.224 0.5 0.5 0.5h2c0.276 0 0.5-0.224 0.5-0.5v-0.5h5v5.5c0 0.276 0.224 0.5 0.5 0.5h3c0.276 0 0.5-0.224 0.5-0.5v-5.5h7.5c0.276 0 0.5-0.224 0.5-0.5 0-2.785-0.307-4.99-0.912-6.555zM2 14h-1v-10h1v10zM11 19h-2v-5h2v5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_alert_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Invoice SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_invoice_svg')) {
	function ct_invoice_svg() {
		$ct_invoice_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M17.5 20c-0.076 0-0.153-0.017-0.224-0.053l-1.776-0.888-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.776 0.888c-0.155 0.077-0.339 0.069-0.486-0.022s-0.237-0.252-0.237-0.425v-18c0-0.189 0.107-0.363 0.276-0.447l2-1c0.141-0.070 0.306-0.070 0.447 0l1.776 0.888 1.776-0.888c0.141-0.070 0.306-0.070 0.447 0l1.776 0.888 1.776-0.888c0.141-0.070 0.306-0.070 0.447 0l1.776 0.888 1.776-0.888c0.141-0.070 0.306-0.070 0.447 0l2 1c0.169 0.085 0.276 0.258 0.276 0.447v18c0 0.173-0.090 0.334-0.237 0.425-0.080 0.050-0.171 0.075-0.263 0.075zM11.5 18c0.077 0 0.153 0.018 0.224 0.053l1.776 0.888 1.776-0.888c0.141-0.070 0.306-0.070 0.447 0l1.276 0.638v-16.882l-1.5-0.75-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.5 0.75v16.882l1.276-0.638c0.141-0.070 0.306-0.070 0.447 0l1.776 0.888 1.776-0.888c0.141-0.070 0.306-0.070 0.447 0l1.776 0.888 1.776-0.888c0.070-0.035 0.147-0.053 0.224-0.053z" fill="#878c92"></path> <path d="M11.5 13h-3.5v-1h3.5c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-1.5v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-1.5c-0.276 0-0.5 0.224-0.5 0.5v2c0 0.276 0.224 0.5 0.5 0.5h3.5v1h-3.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h1.5v0.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-0.5h1.5c0.276 0 0.5-0.224 0.5-0.5v-2c0-0.276-0.224-0.5-0.5-0.5z" fill="#878c92"></path> <path d="M12.5 5h-6c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h6c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M14.5 7h-10c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h10c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M14.5 9h-10c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h10c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_invoice_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Invoice SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_invoice_svg_white')) {
	function ct_invoice_svg_white() {
		$ct_invoice_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M17.5 20c-0.076 0-0.153-0.017-0.224-0.053l-1.776-0.888-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.776 0.888c-0.155 0.077-0.339 0.069-0.486-0.022s-0.237-0.252-0.237-0.425v-18c0-0.189 0.107-0.363 0.276-0.447l2-1c0.141-0.070 0.306-0.070 0.447 0l1.776 0.888 1.776-0.888c0.141-0.070 0.306-0.070 0.447 0l1.776 0.888 1.776-0.888c0.141-0.070 0.306-0.070 0.447 0l1.776 0.888 1.776-0.888c0.141-0.070 0.306-0.070 0.447 0l2 1c0.169 0.085 0.276 0.258 0.276 0.447v18c0 0.173-0.090 0.334-0.237 0.425-0.080 0.050-0.171 0.075-0.263 0.075zM11.5 18c0.077 0 0.153 0.018 0.224 0.053l1.776 0.888 1.776-0.888c0.141-0.070 0.306-0.070 0.447 0l1.276 0.638v-16.882l-1.5-0.75-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.776 0.888c-0.141 0.070-0.306 0.070-0.447 0l-1.776-0.888-1.5 0.75v16.882l1.276-0.638c0.141-0.070 0.306-0.070 0.447 0l1.776 0.888 1.776-0.888c0.141-0.070 0.306-0.070 0.447 0l1.776 0.888 1.776-0.888c0.070-0.035 0.147-0.053 0.224-0.053z" fill="#ffffff"></path> <path d="M11.5 13h-3.5v-1h3.5c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-1.5v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-1.5c-0.276 0-0.5 0.224-0.5 0.5v2c0 0.276 0.224 0.5 0.5 0.5h3.5v1h-3.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h1.5v0.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-0.5h1.5c0.276 0 0.5-0.224 0.5-0.5v-2c0-0.276-0.224-0.5-0.5-0.5z" fill="#ffffff"></path> <path d="M12.5 5h-6c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h6c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M14.5 7h-10c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h10c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M14.5 9h-10c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h10c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_invoice_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Membership SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_membership_svg')) {
	function ct_membership_svg() {
		$ct_membership_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.5 20h-14c-0.827 0-1.5-0.673-1.5-1.5v-14c0-0.827 0.673-1.5 1.5-1.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5 0.224-0.5 0.5v14c0 0.276 0.224 0.5 0.5 0.5h14c0.276 0 0.5-0.224 0.5-0.5v-14c0-0.276-0.224-0.5-0.5-0.5h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.827 0 1.5 0.673 1.5 1.5v14c0 0.827-0.673 1.5-1.5 1.5z" fill="#878c92"></path> <path d="M13.501 5c-0 0-0 0-0.001 0h-8c-0.276 0-0.5-0.224-0.5-0.5 0-1.005 0.453-1.786 1.276-2.197 0.275-0.138 0.547-0.213 0.764-0.254 0.213-1.164 1.235-2.049 2.459-2.049s2.246 0.885 2.459 2.049c0.218 0.041 0.489 0.116 0.764 0.254 0.816 0.408 1.268 1.178 1.276 2.17 0.001 0.009 0.001 0.018 0.001 0.027 0 0.276-0.224 0.5-0.5 0.5zM6.060 4h6.88c-0.096-0.356-0.307-0.617-0.638-0.79-0.389-0.203-0.8-0.21-0.805-0.21-0.276 0-0.497-0.224-0.497-0.5 0-0.827-0.673-1.5-1.5-1.5s-1.5 0.673-1.5 1.5c0 0.276-0.224 0.5-0.5 0.5-0.001 0-0.413 0.007-0.802 0.21-0.331 0.173-0.542 0.433-0.638 0.79z" fill="#878c92"></path> <path d="M9.5 3c-0.132 0-0.261-0.053-0.353-0.147s-0.147-0.222-0.147-0.353 0.053-0.261 0.147-0.353c0.093-0.093 0.222-0.147 0.353-0.147s0.261 0.053 0.353 0.147c0.093 0.093 0.147 0.222 0.147 0.353s-0.053 0.26-0.147 0.353c-0.093 0.093-0.222 0.147-0.353 0.147z" fill="#878c92"></path> <path d="M12.5 17h-6c-0.425 0-0.796-0.177-1.019-0.486s-0.273-0.717-0.139-1.12c0.022-0.065 0.229-0.649 0.849-1.232 0.564-0.53 1.596-1.161 3.309-1.161s2.745 0.631 3.309 1.161c0.62 0.583 0.827 1.167 0.849 1.232 0.134 0.403 0.084 0.811-0.139 1.12s-0.594 0.486-1.019 0.486zM9.5 14c-2.609 0-3.204 1.692-3.209 1.709-0.031 0.093-0.030 0.175 0.002 0.219s0.11 0.071 0.208 0.071h6c0.098 0 0.176-0.027 0.208-0.071s0.033-0.125 0.003-0.217c-0.032-0.089-0.651-1.712-3.21-1.712z" fill="#878c92"></path> <path d="M9.5 12c-1.378 0-2.5-1.122-2.5-2.5s1.122-2.5 2.5-2.5 2.5 1.122 2.5 2.5-1.122 2.5-2.5 2.5zM9.5 8c-0.827 0-1.5 0.673-1.5 1.5s0.673 1.5 1.5 1.5c0.827 0 1.5-0.673 1.5-1.5s-0.673-1.5-1.5-1.5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_membership_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Membership SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_membership_svg_white')) {
	function ct_membership_svg_white() {
		$ct_membership_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.5 20h-14c-0.827 0-1.5-0.673-1.5-1.5v-14c0-0.827 0.673-1.5 1.5-1.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5 0.224-0.5 0.5v14c0 0.276 0.224 0.5 0.5 0.5h14c0.276 0 0.5-0.224 0.5-0.5v-14c0-0.276-0.224-0.5-0.5-0.5h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.827 0 1.5 0.673 1.5 1.5v14c0 0.827-0.673 1.5-1.5 1.5z" fill="#ffffff"></path> <path d="M13.501 5c-0 0-0 0-0.001 0h-8c-0.276 0-0.5-0.224-0.5-0.5 0-1.005 0.453-1.786 1.276-2.197 0.275-0.138 0.547-0.213 0.764-0.254 0.213-1.164 1.235-2.049 2.459-2.049s2.246 0.885 2.459 2.049c0.218 0.041 0.489 0.116 0.764 0.254 0.816 0.408 1.268 1.178 1.276 2.17 0.001 0.009 0.001 0.018 0.001 0.027 0 0.276-0.224 0.5-0.5 0.5zM6.060 4h6.88c-0.096-0.356-0.307-0.617-0.638-0.79-0.389-0.203-0.8-0.21-0.805-0.21-0.276 0-0.497-0.224-0.497-0.5 0-0.827-0.673-1.5-1.5-1.5s-1.5 0.673-1.5 1.5c0 0.276-0.224 0.5-0.5 0.5-0.001 0-0.413 0.007-0.802 0.21-0.331 0.173-0.542 0.433-0.638 0.79z" fill="#ffffff"></path> <path d="M9.5 3c-0.132 0-0.261-0.053-0.353-0.147s-0.147-0.222-0.147-0.353 0.053-0.261 0.147-0.353c0.093-0.093 0.222-0.147 0.353-0.147s0.261 0.053 0.353 0.147c0.093 0.093 0.147 0.222 0.147 0.353s-0.053 0.26-0.147 0.353c-0.093 0.093-0.222 0.147-0.353 0.147z" fill="#ffffff"></path> <path d="M12.5 17h-6c-0.425 0-0.796-0.177-1.019-0.486s-0.273-0.717-0.139-1.12c0.022-0.065 0.229-0.649 0.849-1.232 0.564-0.53 1.596-1.161 3.309-1.161s2.745 0.631 3.309 1.161c0.62 0.583 0.827 1.167 0.849 1.232 0.134 0.403 0.084 0.811-0.139 1.12s-0.594 0.486-1.019 0.486zM9.5 14c-2.609 0-3.204 1.692-3.209 1.709-0.031 0.093-0.030 0.175 0.002 0.219s0.11 0.071 0.208 0.071h6c0.098 0 0.176-0.027 0.208-0.071s0.033-0.125 0.003-0.217c-0.032-0.089-0.651-1.712-3.21-1.712z" fill="#ffffff"></path> <path d="M9.5 12c-1.378 0-2.5-1.122-2.5-2.5s1.122-2.5 2.5-2.5 2.5 1.122 2.5 2.5-1.122 2.5-2.5 2.5zM9.5 8c-0.827 0-1.5 0.673-1.5 1.5s0.673 1.5 1.5 1.5c0.827 0 1.5-0.673 1.5-1.5s-0.673-1.5-1.5-1.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_membership_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Account SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_account_svg')) {
	function ct_account_svg() {
		$ct_account_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M18.5 17h-17c-0.827 0-1.5-0.673-1.5-1.5v-11c0-0.827 0.673-1.5 1.5-1.5h17c0.827 0 1.5 0.673 1.5 1.5v11c0 0.827-0.673 1.5-1.5 1.5zM1.5 4c-0.276 0-0.5 0.224-0.5 0.5v11c0 0.276 0.224 0.5 0.5 0.5h17c0.276 0 0.5-0.224 0.5-0.5v-11c0-0.276-0.224-0.5-0.5-0.5h-17z" fill="#878c92"></path> <path d="M8.501 14c-0-0-0-0-0.001 0h-5c-0.276 0-0.5-0.224-0.5-0.5 0-0.066 0.011-0.661 0.388-1.265 0.352-0.563 1.091-1.235 2.612-1.235s2.259 0.672 2.612 1.235c0.338 0.541 0.382 1.074 0.388 1.227 0.001 0.012 0.001 0.025 0.001 0.038 0 0.276-0.224 0.5-0.5 0.5zM4.117 13h3.766c-0.035-0.086-0.081-0.177-0.14-0.267-0.322-0.487-0.908-0.733-1.743-0.733s-1.421 0.247-1.743 0.733c-0.059 0.090-0.105 0.18-0.14 0.267z" fill="#878c92"></path> <path d="M16.5 8h-5c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h5c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M15.5 10h-4c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h4c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M15.5 12h-4c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h4c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M6 10c-1.103 0-2-0.897-2-2s0.897-2 2-2 2 0.897 2 2c0 1.103-0.897 2-2 2zM6 7c-0.551 0-1 0.449-1 1s0.449 1 1 1 1-0.449 1-1-0.449-1-1-1z" fill="#878c92"></path> <path d="M16.5 14h-5c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h5c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_account_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Account SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_account_svg_white')) {
	function ct_account_svg_white() {
		$ct_account_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M18.5 17h-17c-0.827 0-1.5-0.673-1.5-1.5v-11c0-0.827 0.673-1.5 1.5-1.5h17c0.827 0 1.5 0.673 1.5 1.5v11c0 0.827-0.673 1.5-1.5 1.5zM1.5 4c-0.276 0-0.5 0.224-0.5 0.5v11c0 0.276 0.224 0.5 0.5 0.5h17c0.276 0 0.5-0.224 0.5-0.5v-11c0-0.276-0.224-0.5-0.5-0.5h-17z" fill="#ffffff"></path> <path d="M8.501 14c-0-0-0-0-0.001 0h-5c-0.276 0-0.5-0.224-0.5-0.5 0-0.066 0.011-0.661 0.388-1.265 0.352-0.563 1.091-1.235 2.612-1.235s2.259 0.672 2.612 1.235c0.338 0.541 0.382 1.074 0.388 1.227 0.001 0.012 0.001 0.025 0.001 0.038 0 0.276-0.224 0.5-0.5 0.5zM4.117 13h3.766c-0.035-0.086-0.081-0.177-0.14-0.267-0.322-0.487-0.908-0.733-1.743-0.733s-1.421 0.247-1.743 0.733c-0.059 0.090-0.105 0.18-0.14 0.267z" fill="#ffffff"></path> <path d="M16.5 8h-5c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h5c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M15.5 10h-4c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h4c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M15.5 12h-4c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h4c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M6 10c-1.103 0-2-0.897-2-2s0.897-2 2-2 2 0.897 2 2c0 1.103-0.897 2-2 2zM6 7c-0.551 0-1 0.449-1 1s0.449 1 1 1 1-0.449 1-1-0.449-1-1-1z" fill="#ffffff"></path> <path d="M16.5 14h-5c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h5c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_account_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Listings SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listings_svg')) {
	function ct_listings_svg() {
		$ct_listings_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M18.5 7h-12c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h12c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM6.5 5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h12c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-12z" fill="#878c92"></path> <path d="M18.5 12h-12c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h12c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM6.5 10c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h12c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-12z" fill="#878c92"></path> <path d="M18.5 17h-12c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h12c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM6.5 15c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h12c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-12z" fill="#878c92"></path> <path d="M1.5 7c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5 1.5 0.673 1.5 1.5-0.673 1.5-1.5 1.5zM1.5 5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5 0.5-0.224 0.5-0.5-0.224-0.5-0.5-0.5z" fill="#878c92"></path> <path d="M1.5 12c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5 1.5 0.673 1.5 1.5-0.673 1.5-1.5 1.5zM1.5 10c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5 0.5-0.224 0.5-0.5-0.224-0.5-0.5-0.5z" fill="#878c92"></path> <path d="M1.5 17c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5 1.5 0.673 1.5 1.5-0.673 1.5-1.5 1.5zM1.5 15c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5 0.5-0.224 0.5-0.5-0.224-0.5-0.5-0.5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_listings_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Listings SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listings_svg_white')) {
	function ct_listings_svg_white() {
		$ct_listings_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M18.5 7h-12c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h12c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM6.5 5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h12c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-12z" fill="#ffffff"></path> <path d="M18.5 12h-12c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h12c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM6.5 10c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h12c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-12z" fill="#ffffff"></path> <path d="M18.5 17h-12c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h12c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM6.5 15c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h12c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-12z" fill="#ffffff"></path> <path d="M1.5 7c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5 1.5 0.673 1.5 1.5-0.673 1.5-1.5 1.5zM1.5 5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5 0.5-0.224 0.5-0.5-0.224-0.5-0.5-0.5z" fill="#ffffff"></path> <path d="M1.5 12c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5 1.5 0.673 1.5 1.5-0.673 1.5-1.5 1.5zM1.5 10c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5 0.5-0.224 0.5-0.5-0.224-0.5-0.5-0.5z" fill="#ffffff"></path> <path d="M1.5 17c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5 1.5 0.673 1.5 1.5-0.673 1.5-1.5 1.5zM1.5 15c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5 0.5-0.224 0.5-0.5-0.224-0.5-0.5-0.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_listings_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Plus Square SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_plus_square_svg')) {
	function ct_plus_square_svg() {
		$ct_plus_square_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 20 20"> <path d="M17.5 20h-16c-0.827 0-1.5-0.673-1.5-1.5v-16c0-0.827 0.673-1.5 1.5-1.5h16c0.827 0 1.5 0.673 1.5 1.5v16c0 0.827-0.673 1.5-1.5 1.5zM1.5 2c-0.276 0-0.5 0.224-0.5 0.5v16c0 0.276 0.224 0.5 0.5 0.5h16c0.276 0 0.5-0.224 0.5-0.5v-16c0-0.276-0.224-0.5-0.5-0.5h-16z" fill="#ffffff"></path> <path d="M14.5 10h-4.5v-4.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v4.5h-4.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h4.5v4.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-4.5h4.5c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_plus_square_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Plus Circle SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_plus_circle_svg_white')) {
	function ct_plus_circle_svg_white() {
		$ct_plus_circle_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.218 3.782c-1.794-1.794-4.18-2.782-6.718-2.782s-4.923 0.988-6.718 2.782-2.782 4.18-2.782 6.717 0.988 4.923 2.782 6.718 4.18 2.782 6.718 2.782 4.923-0.988 6.718-2.782 2.782-4.18 2.782-6.718-0.988-4.923-2.782-6.717zM9.5 19c-4.687 0-8.5-3.813-8.5-8.5s3.813-8.5 8.5-8.5c4.687 0 8.5 3.813 8.5 8.5s-3.813 8.5-8.5 8.5z" fill="#ffffff"></path> <path d="M15.5 10h-5.5v-5.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v5.5h-5.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h5.5v5.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-5.5h5.5c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_plus_circle_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Plus Circle SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_plus_circle_svg')) {
	function ct_plus_circle_svg() {
		$ct_plus_circle_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.218 3.782c-1.794-1.794-4.18-2.782-6.718-2.782s-4.923 0.988-6.718 2.782-2.782 4.18-2.782 6.717 0.988 4.923 2.782 6.718 4.18 2.782 6.718 2.782 4.923-0.988 6.718-2.782 2.782-4.18 2.782-6.718-0.988-4.923-2.782-6.717zM9.5 19c-4.687 0-8.5-3.813-8.5-8.5s3.813-8.5 8.5-8.5c4.687 0 8.5 3.813 8.5 8.5s-3.813 8.5-8.5 8.5z" fill="#878c92"></path> <path d="M15.5 10h-5.5v-5.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v5.5h-5.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h5.5v5.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-5.5h5.5c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_plus_circle_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Graph SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_graph_svg')) {
	function ct_graph_svg() {
		$ct_graph_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 20 20"> <path d="M19.5 20h-19c-0.276 0-0.5-0.224-0.5-0.5v-19c0-0.232 0.159-0.433 0.385-0.487s0.458 0.056 0.562 0.263l1 2c0.123 0.247 0.023 0.547-0.224 0.671s-0.547 0.023-0.671-0.224l-0.053-0.106v16.382h16.382l-0.106-0.053c-0.247-0.123-0.347-0.424-0.224-0.671s0.424-0.347 0.671-0.224l2 1c0.207 0.104 0.316 0.336 0.263 0.562s-0.255 0.385-0.487 0.385z" fill="#ffffff"></path> <path d="M17 4.5c0-0.827-0.673-1.5-1.5-1.5s-1.5 0.673-1.5 1.5c0 0.48 0.227 0.908 0.579 1.182l-2.106 6.318c-0.28 0.005-0.541 0.087-0.764 0.226l-2.792-2.234c0.054-0.154 0.084-0.32 0.084-0.493 0-0.827-0.673-1.5-1.5-1.5s-1.5 0.673-1.5 1.5c0 0.404 0.161 0.77 0.421 1.040l-1.736 3.472c-0.061-0.008-0.122-0.012-0.185-0.012-0.827 0-1.5 0.673-1.5 1.5s0.673 1.5 1.5 1.5 1.5-0.673 1.5-1.5c0-0.404-0.161-0.77-0.421-1.040l1.736-3.472c0.061 0.008 0.122 0.012 0.185 0.012 0.29 0 0.562-0.083 0.791-0.227l2.792 2.234c-0.054 0.154-0.084 0.32-0.084 0.493 0 0.827 0.673 1.5 1.5 1.5s1.5-0.673 1.5-1.5c0-0.48-0.227-0.908-0.579-1.183l2.106-6.318c0.814-0.015 1.473-0.681 1.473-1.499zM15.5 4c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5-0.5-0.224-0.5-0.5 0.224-0.5 0.5-0.5zM7.5 9c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5-0.5-0.224-0.5-0.5 0.224-0.5 0.5-0.5zM4.5 16c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5 0.5 0.224 0.5 0.5-0.224 0.5-0.5 0.5zM12.5 14c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5 0.5 0.224 0.5 0.5-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_graph_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Chart Bars SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_chart_bars_svg')) {
	function ct_chart_bars_svg() {
		$ct_chart_bars_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M17.5 20h-16c-0.827 0-1.5-0.673-1.5-1.5v-16c0-0.827 0.673-1.5 1.5-1.5h16c0.827 0 1.5 0.673 1.5 1.5v16c0 0.827-0.673 1.5-1.5 1.5zM1.5 2c-0.276 0-0.5 0.224-0.5 0.5v16c0 0.276 0.224 0.5 0.5 0.5h16c0.276 0 0.5-0.224 0.5-0.5v-16c0-0.276-0.224-0.5-0.5-0.5h-16z" fill="#ffffff"></path> <path d="M6.5 17h-2c-0.276 0-0.5-0.224-0.5-0.5v-9c0-0.276 0.224-0.5 0.5-0.5h2c0.276 0 0.5 0.224 0.5 0.5v9c0 0.276-0.224 0.5-0.5 0.5zM5 16h1v-8h-1v8z" fill="#ffffff"></path> <path d="M10.5 17h-2c-0.276 0-0.5-0.224-0.5-0.5v-12c0-0.276 0.224-0.5 0.5-0.5h2c0.276 0 0.5 0.224 0.5 0.5v12c0 0.276-0.224 0.5-0.5 0.5zM9 16h1v-11h-1v11z" fill="#ffffff"></path> <path d="M14.5 17h-2c-0.276 0-0.5-0.224-0.5-0.5v-5c0-0.276 0.224-0.5 0.5-0.5h2c0.276 0 0.5 0.224 0.5 0.5v5c0 0.276-0.224 0.5-0.5 0.5zM13 16h1v-4h-1v4z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_chart_bars_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Chart Bars SVG Icon - Muted */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_chart_bars_svg_muted')) {
	function ct_chart_bars_svg_muted() {
		$ct_chart_bars_svg_muted = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M17.5 20h-16c-0.827 0-1.5-0.673-1.5-1.5v-16c0-0.827 0.673-1.5 1.5-1.5h16c0.827 0 1.5 0.673 1.5 1.5v16c0 0.827-0.673 1.5-1.5 1.5zM1.5 2c-0.276 0-0.5 0.224-0.5 0.5v16c0 0.276 0.224 0.5 0.5 0.5h16c0.276 0 0.5-0.224 0.5-0.5v-16c0-0.276-0.224-0.5-0.5-0.5h-16z" fill="#878c92"></path> <path d="M6.5 17h-2c-0.276 0-0.5-0.224-0.5-0.5v-9c0-0.276 0.224-0.5 0.5-0.5h2c0.276 0 0.5 0.224 0.5 0.5v9c0 0.276-0.224 0.5-0.5 0.5zM5 16h1v-8h-1v8z" fill="#878c92"></path> <path d="M10.5 17h-2c-0.276 0-0.5-0.224-0.5-0.5v-12c0-0.276 0.224-0.5 0.5-0.5h2c0.276 0 0.5 0.224 0.5 0.5v12c0 0.276-0.224 0.5-0.5 0.5zM9 16h1v-11h-1v11z" fill="#878c92"></path> <path d="M14.5 17h-2c-0.276 0-0.5-0.224-0.5-0.5v-5c0-0.276 0.224-0.5 0.5-0.5h2c0.276 0 0.5 0.224 0.5 0.5v5c0 0.276-0.224 0.5-0.5 0.5zM13 16h1v-4h-1v4z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_chart_bars_svg_muted, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Chart Growth SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_chart_growth_svg')) {
	function ct_chart_growth_svg() {
		$ct_chart_growth_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 20 20"> <path d="M3.5 20h-3c-0.276 0-0.5-0.224-0.5-0.5v-7c0-0.276 0.224-0.5 0.5-0.5h3c0.276 0 0.5 0.224 0.5 0.5v7c0 0.276-0.224 0.5-0.5 0.5zM1 19h2v-6h-2v6z" fill="#ffffff"></path> <path d="M8.5 20h-3c-0.276 0-0.5-0.224-0.5-0.5v-10c0-0.276 0.224-0.5 0.5-0.5h3c0.276 0 0.5 0.224 0.5 0.5v10c0 0.276-0.224 0.5-0.5 0.5zM6 19h2v-9h-2v9z" fill="#ffffff"></path> <path d="M13.5 20h-3c-0.276 0-0.5-0.224-0.5-0.5v-9c0-0.276 0.224-0.5 0.5-0.5h3c0.276 0 0.5 0.224 0.5 0.5v9c0 0.276-0.224 0.5-0.5 0.5zM11 19h2v-8h-2v8z" fill="#ffffff"></path> <path d="M18.5 20h-3c-0.276 0-0.5-0.224-0.5-0.5v-13c0-0.276 0.224-0.5 0.5-0.5h3c0.276 0 0.5 0.224 0.5 0.5v13c0 0.276-0.224 0.5-0.5 0.5zM16 19h2v-12h-2v12z" fill="#ffffff"></path> <path d="M17.854 1.146c-0.134-0.134-0.332-0.181-0.512-0.121l-3 1c-0.262 0.087-0.404 0.37-0.316 0.632s0.371 0.404 0.632 0.316l0.991-0.33-4.295 4.295c-0.213 0.213-0.612 0.242-0.854 0.061l-2.4-1.8c-0.624-0.468-1.587-0.448-2.191 0.046l-4.726 3.867c-0.214 0.175-0.245 0.49-0.070 0.704 0.099 0.121 0.242 0.183 0.387 0.183 0.111 0 0.223-0.037 0.316-0.113l4.726-3.867c0.246-0.202 0.703-0.211 0.957-0.020l2.4 1.8c0.643 0.482 1.592 0.415 2.161-0.154l4.295-4.295-0.33 0.991c-0.087 0.262 0.054 0.545 0.316 0.632 0.052 0.018 0.106 0.026 0.158 0.026 0.209 0 0.404-0.133 0.474-0.342l1-3c0.060-0.18 0.013-0.378-0.121-0.512z" fill="#ffffff"></path> </svg> ';

		echo wp_kses( $ct_chart_growth_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Search SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_svg')) {
	function ct_search_svg() {
		$ct_search_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M18.869 19.162l-5.943-6.484c1.339-1.401 2.075-3.233 2.075-5.178 0-2.003-0.78-3.887-2.197-5.303s-3.3-2.197-5.303-2.197-3.887 0.78-5.303 2.197-2.197 3.3-2.197 5.303 0.78 3.887 2.197 5.303 3.3 2.197 5.303 2.197c1.726 0 3.362-0.579 4.688-1.645l5.943 6.483c0.099 0.108 0.233 0.162 0.369 0.162 0.121 0 0.242-0.043 0.338-0.131 0.204-0.187 0.217-0.503 0.031-0.706zM1 7.5c0-3.584 2.916-6.5 6.5-6.5s6.5 2.916 6.5 6.5-2.916 6.5-6.5 6.5-6.5-2.916-6.5-6.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_search_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Search SVG Icon - Muted */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_svg_muted')) {
	function ct_search_svg_muted() {
		$ct_search_svg_muted = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M18.869 19.162l-5.943-6.484c1.339-1.401 2.075-3.233 2.075-5.178 0-2.003-0.78-3.887-2.197-5.303s-3.3-2.197-5.303-2.197-3.887 0.78-5.303 2.197-2.197 3.3-2.197 5.303 0.78 3.887 2.197 5.303 3.3 2.197 5.303 2.197c1.726 0 3.362-0.579 4.688-1.645l5.943 6.483c0.099 0.108 0.233 0.162 0.369 0.162 0.121 0 0.242-0.043 0.338-0.131 0.204-0.187 0.217-0.503 0.031-0.706zM1 7.5c0-3.584 2.916-6.5 6.5-6.5s6.5 2.916 6.5 6.5-2.916 6.5-6.5 6.5-6.5-2.916-6.5-6.5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_search_svg_muted, ct_kses_svg() );

	}
}

/*-----------------------------------------------------------------------------------*/
/* Filters SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_filters_svg')) {
	function ct_filters_svg() {
		$ct_filters_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M2.5 20c-0.276 0-0.5-0.224-0.5-0.5v-8c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v8c0 0.276-0.224 0.5-0.5 0.5z" fill="#333"></path> <path d="M2.5 6c-0.276 0-0.5-0.224-0.5-0.5v-5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v5c0 0.276-0.224 0.5-0.5 0.5z" fill="#333"></path> <path d="M3.5 10h-2c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h2c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM1.5 8c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h2c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-2z" fill="#333"></path> <path d="M9.5 20c-0.276 0-0.5-0.224-0.5-0.5v-4c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v4c0 0.276-0.224 0.5-0.5 0.5z" fill="#333"></path> <path d="M9.5 10c-0.276 0-0.5-0.224-0.5-0.5v-9c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v9c0 0.276-0.224 0.5-0.5 0.5z" fill="#333"></path> <path d="M10.5 14h-2c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h2c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM8.5 12c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h2c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-2z" fill="#333"></path> <path d="M16.5 20c-0.276 0-0.5-0.224-0.5-0.5v-10c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v10c0 0.276-0.224 0.5-0.5 0.5z" fill="#333"></path> <path d="M16.5 4c-0.276 0-0.5-0.224-0.5-0.5v-3c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v3c0 0.276-0.224 0.5-0.5 0.5z" fill="#333"></path> <path d="M17.5 8h-2c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h2c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM15.5 6c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h2c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-2z" fill="#333"></path> </svg>';

		echo wp_kses( $ct_filters_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Filters SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_filters_svg_white')) {
	function ct_filters_svg_white() {
		$ct_filters_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M2.5 20c-0.276 0-0.5-0.224-0.5-0.5v-8c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v8c0 0.276-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M2.5 6c-0.276 0-0.5-0.224-0.5-0.5v-5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v5c0 0.276-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M3.5 10h-2c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h2c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM1.5 8c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h2c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-2z" fill="#ffffff"></path> <path d="M9.5 20c-0.276 0-0.5-0.224-0.5-0.5v-4c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v4c0 0.276-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M9.5 10c-0.276 0-0.5-0.224-0.5-0.5v-9c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v9c0 0.276-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M10.5 14h-2c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h2c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM8.5 12c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h2c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-2z" fill="#ffffff"></path> <path d="M16.5 20c-0.276 0-0.5-0.224-0.5-0.5v-10c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v10c0 0.276-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M16.5 4c-0.276 0-0.5-0.224-0.5-0.5v-3c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5v3c0 0.276-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M17.5 8h-2c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5h2c0.827 0 1.5 0.673 1.5 1.5s-0.673 1.5-1.5 1.5zM15.5 6c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h2c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-2z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_filters_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Pencil Draw SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_pencil_draw_svg')) {
	
	function ct_pencil_draw_svg() {

		$ct_pencil_draw_svg = '<svg class="muted" style="height: 16px; width: 16px;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" x="0px" y="0px" viewBox="0 0 590 590" style="enable-background:new 0 0 590 590;" xml:space="preserve"> <path d="M497.9,142.1l-46.1,46.1c-4.7,4.7-12.3,4.7-17,0l-111-111c-4.7-4.7-4.7-12.3,0-17l46.1-46.1c18.7-18.7,49.1-18.7,67.9,0 l60.1,60.1C516.7,92.9,516.7,123.3,497.9,142.1z M284.2,99.8L21.6,362.4L0.4,483.9c-2.9,16.4,11.4,30.6,27.8,27.8l121.5-21.3 l262.6-262.6c4.7-4.7,4.7-12.3,0-17l-111-111C296.5,95.1,288.9,95.1,284.2,99.8L284.2,99.8z M124.1,339.9c-5.5-5.5-5.5-14.3,0-19.8 l154-154c5.5-5.5,14.3-5.5,19.8,0s5.5,14.3,0,19.8l-154,154C138.4,345.4,129.6,345.4,124.1,339.9L124.1,339.9z M88,424h48v36.3 l-64.5,11.3l-31.1-31.1L51.7,376H88V424z"/> <rect y="542" width="590" height="48"/> </svg>';

		echo wp_kses( $ct_pencil_draw_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Bed SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_bed_svg')) {
	function ct_bed_svg() {
		$ct_bed_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20">
		<path d="M17.5 18h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path>
		<path d="M2.5 18h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path>
		<path d="M18.658 11.393l-2.368-7.103c-0.199-0.596-0.768-1.086-1.388-1.24-0.022-0.095-0.046-0.186-0.074-0.27-0.227-0.68-0.616-0.781-0.828-0.781h-4c-0.127 0-0.318 0.037-0.5 0.213-0.182-0.176-0.373-0.213-0.5-0.213h-4c-0.212 0-0.601 0.102-0.828 0.781-0.028 0.084-0.053 0.174-0.074 0.27-0.621 0.154-1.19 0.643-1.388 1.24l-2.368 7.103c-0.192 0.575-0.342 1.501-0.342 2.107v2c0 0.827 0.673 1.5 1.5 1.5h16c0.827 0 1.5-0.673 1.5-1.5v-2c0-0.606-0.15-1.532-0.342-2.107zM10.157 3h3.686c0.070 0.157 0.157 0.514 0.157 1s-0.087 0.843-0.157 1h-3.686c-0.070-0.157-0.157-0.514-0.157-1s0.087-0.843 0.157-1zM5.157 3h3.686c0.070 0.157 0.157 0.514 0.157 1s-0.087 0.843-0.157 1h-3.686c-0.070-0.157-0.157-0.514-0.157-1s0.087-0.843 0.157-1zM3.658 4.607c0.054-0.162 0.185-0.317 0.345-0.429 0.014 0.388 0.072 0.752 0.169 1.041 0.227 0.68 0.616 0.781 0.828 0.781h4c0.127 0 0.318-0.037 0.5-0.213 0.182 0.176 0.373 0.213 0.5 0.213h4c0.212 0 0.601-0.102 0.828-0.781 0.096-0.289 0.155-0.654 0.169-1.041 0.16 0.113 0.291 0.267 0.345 0.429l0.798 2.393h-13.279l0.798-2.393zM2.527 8h13.946l1.236 3.709c0.032 0.095 0.062 0.204 0.091 0.321-0.097-0.020-0.197-0.030-0.3-0.030h-16c-0.103 0-0.203 0.010-0.3 0.030 0.029-0.117 0.059-0.226 0.091-0.321l1.237-3.709zM18 15.5c0 0.276-0.224 0.5-0.5 0.5h-16c-0.276 0-0.5-0.224-0.5-0.5v-2c0-0.276 0.224-0.5 0.5-0.5h16c0.276 0 0.5 0.224 0.5 0.5v2z" fill="#878c92"></path>
		</svg>';

		echo wp_kses( $ct_bed_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Bath SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_bath_svg')) {
	function ct_bath_svg() {
		$ct_bath_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M16.5 20h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M4.5 20h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M20 12.5c0-0.827-0.673-1.5-1.5-1.5h-15.5v-9.5c0-0.276 0.224-0.5 0.5-0.5h1.5c0.142 0 0.399 0.106 0.5 0.207l0.126 0.126c-0.929 1.27-0.821 3.068 0.326 4.215 0.094 0.094 0.221 0.146 0.354 0.146s0.26-0.053 0.354-0.146l3.889-3.889c0.195-0.195 0.195-0.512 0-0.707-0.614-0.614-1.43-0.952-2.298-0.952-0.699 0-1.364 0.219-1.918 0.625l-0.125-0.125c-0.29-0.29-0.797-0.5-1.207-0.5h-1.5c-0.827 0-1.5 0.673-1.5 1.5v9.5h-0.5c-0.827 0-1.5 0.673-1.5 1.5 0 0.652 0.418 1.208 1 1.414v2.586c0 1.378 1.122 2.5 2.5 2.5h13c1.378 0 2.5-1.122 2.5-2.5v-2.586c0.582-0.206 1-0.762 1-1.414zM9.448 1.345l-3.103 3.103c-0.546-0.869-0.442-2.033 0.314-2.789 0.425-0.425 0.99-0.659 1.591-0.659 0.431 0 0.843 0.12 1.198 0.345zM16.5 18h-13c-0.827 0-1.5-0.673-1.5-1.5v-2.5h16v2.5c0 0.827-0.673 1.5-1.5 1.5zM18.5 13h-17c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h17c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M10 6.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M10 4.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M12 4.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M12 6.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M14 4.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M10 8.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M14 6.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M12 8.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_bath_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Size SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_size_svg')) {
	function ct_size_svg() {
		$ct_size_svg = '<svg id="ico-size" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M17.5 3h-8.5v-0.5c0-0.827-0.673-1.5-1.5-1.5h-2c-0.827 0-1.5 0.673-1.5 1.5v0.5h-2.5c-0.827 0-1.5 0.673-1.5 1.5v2c0 0.827 0.673 1.5 1.5 1.5h2.5v10.5c0 0.827 0.673 1.5 1.5 1.5h2c0.827 0 1.5-0.673 1.5-1.5v-10.5h8.5c0.827 0 1.5-0.673 1.5-1.5v-2c0-0.827-0.673-1.5-1.5-1.5zM5 2.5c0-0.276 0.224-0.5 0.5-0.5h2c0.276 0 0.5 0.224 0.5 0.5v0.5h-3v-0.5zM8 10h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v2h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v2h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v1.5c0 0.276-0.224 0.5-0.5 0.5h-2c-0.276 0-0.5-0.224-0.5-0.5v-10.5h3v2zM18 6.5c0 0.276-0.224 0.5-0.5 0.5h-1.5v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-1.5c-0.276 0-0.5-0.224-0.5-0.5v-2c0-0.276 0.224-0.5 0.5-0.5h16c0.276 0 0.5 0.224 0.5 0.5v2z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_size_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Year Built SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_year_built_svg')) {
	function ct_year_built_svg() {
		$ct_year_built_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M18.5 2h-2.5v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-10v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2.5c-0.827 0-1.5 0.673-1.5 1.5v14c0 0.827 0.673 1.5 1.5 1.5h17c0.827 0 1.5-0.673 1.5-1.5v-14c0-0.827-0.673-1.5-1.5-1.5zM1.5 3h2.5v1.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-1.5h10v1.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-1.5h2.5c0.276 0 0.5 0.224 0.5 0.5v2.5h-18v-2.5c0-0.276 0.224-0.5 0.5-0.5zM18.5 18h-17c-0.276 0-0.5-0.224-0.5-0.5v-10.5h18v10.5c0 0.276-0.224 0.5-0.5 0.5z" fill="#878c92"></path> <path d="M8.5 15.5c-0.128 0-0.256-0.049-0.354-0.146l-2-2c-0.195-0.195-0.195-0.512 0-0.707s0.512-0.195 0.707 0l1.646 1.646 4.646-4.646c0.195-0.195 0.512-0.195 0.707 0s0.195 0.512 0 0.707l-5 5c-0.098 0.098-0.226 0.146-0.354 0.146z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_year_built_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Lot Size SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_lotsize_svg')) {
	function ct_lotsize_svg() {
		$ct_lotsize_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M3 6.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M10 6.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M17 6.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M18.854 2.646l-2-2c-0.195-0.195-0.512-0.195-0.707 0l-2 2c-0.094 0.094-0.146 0.221-0.146 0.354v1h-2v-1c0-0.133-0.053-0.26-0.146-0.354l-2-2c-0.195-0.195-0.512-0.195-0.707 0l-2 2c-0.094 0.094-0.146 0.221-0.146 0.354v1h-2v-1c0-0.133-0.053-0.26-0.146-0.354l-2-2c-0.195-0.195-0.512-0.195-0.707 0l-2 2c-0.094 0.094-0.146 0.221-0.146 0.354v16.5c0 0.276 0.224 0.5 0.5 0.5h4c0.276 0 0.5-0.224 0.5-0.5v-1.5h2v1.5c0 0.276 0.224 0.5 0.5 0.5h4c0.276 0 0.5-0.224 0.5-0.5v-1.5h2v1.5c0 0.276 0.224 0.5 0.5 0.5h4c0.276 0 0.5-0.224 0.5-0.5v-16.5c0-0.133-0.053-0.26-0.146-0.354zM12 9h2v4h-2v-4zM14 5v3h-2v-3h2zM5 9h2v4h-2v-4zM7 5v3h-2v-3h2zM4 19h-3v-15.793l1.5-1.5 1.5 1.5v15.793zM5 17v-3h2v3h-2zM11 19h-3v-15.793l1.5-1.5 1.5 1.5v15.793zM12 17v-3h2v3h-2zM18 19h-3v-15.793l1.5-1.5 1.5 1.5v15.793z" fill="#878c92"></path> <path d="M3 15.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M10 15.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> <path d="M17 15.5c0 0.276-0.224 0.5-0.5 0.5s-0.5-0.224-0.5-0.5c0-0.276 0.224-0.5 0.5-0.5s0.5 0.224 0.5 0.5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_lotsize_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Walkscore SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_walkscore_svg')) {
	function ct_walkscore_svg() {
		$ct_walscore_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M9 4c-1.103 0-2-0.897-2-2s0.897-2 2-2c1.103 0 2 0.897 2 2s-0.897 2-2 2zM9 1c-0.551 0-1 0.449-1 1s0.449 1 1 1 1-0.449 1-1-0.449-1-1-1z" fill="#878c92"></path> <path d="M12.5 20c-0.198 0-0.386-0.119-0.464-0.314l-1.942-4.856-2.871-1.914c-0.16-0.107-0.245-0.296-0.218-0.487l0.865-6.055-2.941 1.47-0.944 3.777c-0.067 0.268-0.338 0.431-0.606 0.364s-0.431-0.338-0.364-0.606l1-4c0.035-0.142 0.131-0.261 0.261-0.326l4-2c0.166-0.083 0.365-0.067 0.516 0.042s0.229 0.292 0.203 0.476l-0.955 6.688 2.738 1.825c0.084 0.056 0.149 0.136 0.187 0.23l2 5c0.103 0.256-0.022 0.547-0.279 0.65-0.061 0.024-0.124 0.036-0.186 0.036z" fill="#878c92"></path> <path d="M3.5 20c-0.095 0-0.192-0.027-0.277-0.084-0.23-0.153-0.292-0.464-0.139-0.693l1.983-2.974 0.986-1.972c0.123-0.247 0.424-0.347 0.671-0.224s0.347 0.424 0.224 0.671l-1 2c-0.009 0.019-0.020 0.037-0.031 0.054l-2 3c-0.096 0.144-0.255 0.223-0.417 0.223z" fill="#878c92"></path> <path d="M15.5 10c-0.040 0-0.081-0.005-0.122-0.015l-4-1c-0.088-0.022-0.168-0.067-0.232-0.132l-1-1c-0.195-0.195-0.195-0.512 0-0.707s0.512-0.195 0.707 0l0.902 0.902 3.866 0.966c0.268 0.067 0.431 0.338 0.364 0.606-0.057 0.227-0.261 0.379-0.485 0.379z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_walscore_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Review SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_review_svg')) {
	function ct_review_svg() {
		$ct_review_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M15.5 19c-0.082 0-0.164-0.020-0.239-0.061l-5.261-2.869-5.261 2.869c-0.168 0.092-0.373 0.079-0.529-0.032s-0.235-0.301-0.203-0.49l0.958-5.746-3.818-3.818c-0.132-0.132-0.18-0.328-0.123-0.506s0.209-0.31 0.394-0.341l5.749-0.958 2.386-4.772c0.085-0.169 0.258-0.276 0.447-0.276s0.363 0.107 0.447 0.276l2.386 4.772 5.749 0.958c0.185 0.031 0.337 0.162 0.394 0.341s0.010 0.374-0.123 0.506l-3.818 3.818 0.958 5.746c0.031 0.189-0.048 0.379-0.203 0.49-0.086 0.061-0.188 0.093-0.29 0.093zM10 15c0.082 0 0.165 0.020 0.239 0.061l4.599 2.508-0.831-4.987c-0.027-0.159 0.025-0.322 0.14-0.436l3.313-3.313-5.042-0.84c-0.158-0.026-0.293-0.127-0.365-0.27l-2.053-4.106-2.053 4.106c-0.072 0.143-0.207 0.243-0.365 0.27l-5.042 0.84 3.313 3.313c0.114 0.114 0.166 0.276 0.14 0.436l-0.831 4.987 4.599-2.508c0.075-0.041 0.157-0.061 0.239-0.061z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_review_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Featured SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_featured_svg')) {
	function ct_featured_svg() {
		$ct_featured_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M15.5 19c-0.082 0-0.164-0.020-0.239-0.061l-5.261-2.869-5.261 2.869c-0.168 0.092-0.373 0.079-0.529-0.032s-0.235-0.301-0.203-0.49l0.958-5.746-3.818-3.818c-0.132-0.132-0.18-0.328-0.123-0.506s0.209-0.31 0.394-0.341l5.749-0.958 2.386-4.772c0.085-0.169 0.258-0.276 0.447-0.276s0.363 0.107 0.447 0.276l2.386 4.772 5.749 0.958c0.185 0.031 0.337 0.162 0.394 0.341s0.010 0.374-0.123 0.506l-3.818 3.818 0.958 5.746c0.031 0.189-0.048 0.379-0.203 0.49-0.086 0.061-0.188 0.093-0.29 0.093zM10 15c0.082 0 0.165 0.020 0.239 0.061l4.599 2.508-0.831-4.987c-0.027-0.159 0.025-0.322 0.14-0.436l3.313-3.313-5.042-0.84c-0.158-0.026-0.293-0.127-0.365-0.27l-2.053-4.106-2.053 4.106c-0.072 0.143-0.207 0.243-0.365 0.27l-5.042 0.84 3.313 3.313c0.114 0.114 0.166 0.276 0.14 0.436l-0.831 4.987 4.599-2.508c0.075-0.041 0.157-0.061 0.239-0.061z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_featured_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Featured SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_pending_svg')) {
	function ct_pending_svg() {
		$ct_pending_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.5 20h-13c-0.827 0-1.5-0.673-1.5-1.5v-16c0-0.827 0.673-1.5 1.5-1.5h13c0.827 0 1.5 0.673 1.5 1.5v16c0 0.827-0.673 1.5-1.5 1.5zM3.5 2c-0.276 0-0.5 0.224-0.5 0.5v16c0 0.276 0.224 0.5 0.5 0.5h13c0.276 0 0.5-0.224 0.5-0.5v-16c0-0.276-0.224-0.5-0.5-0.5h-13z" fill="#ffffff"></path> <path d="M12.5 5h-7c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h7c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M14.5 7h-9c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h9c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M14.5 9h-9c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h9c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M10.5 11h-5c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h5c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M14.5 15h-9c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h9c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> <path d="M12.5 17h-7c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h7c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_pending_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Views SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_views_svg')) {
	function ct_views_svg() {
		$ct_views_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M19.872 10.166c-0.047-0.053-1.182-1.305-2.956-2.572-1.047-0.748-2.1-1.344-3.13-1.773-1.305-0.544-2.579-0.82-3.786-0.82s-2.481 0.276-3.786 0.82c-1.030 0.429-2.083 1.026-3.13 1.773-1.774 1.267-2.909 2.52-2.956 2.572-0.171 0.19-0.171 0.479 0 0.669 0.047 0.053 1.182 1.305 2.956 2.572 1.047 0.748 2.1 1.344 3.13 1.773 1.305 0.544 2.579 0.82 3.786 0.82s2.481-0.276 3.786-0.82c1.030-0.429 2.083-1.026 3.13-1.773 1.774-1.267 2.909-2.52 2.956-2.572 0.171-0.19 0.171-0.479 0-0.669zM12.574 6.438c0.907 0.763 1.426 1.873 1.426 3.062 0 2.206-1.794 4-4 4s-4-1.794-4-4c0-1.188 0.519-2.299 1.426-3.062 0.822-0.268 1.691-0.438 2.574-0.438s1.752 0.17 2.574 0.438zM16.317 12.606c-1.533 1.092-3.873 2.394-6.317 2.394s-4.784-1.302-6.317-2.394c-1.157-0.824-2.042-1.658-2.489-2.106 0.447-0.448 1.332-1.281 2.489-2.106 0.53-0.378 1.156-0.78 1.85-1.145-0.347 0.688-0.533 1.455-0.533 2.251 0 2.757 2.243 5 5 5s5-2.243 5-5c0-0.796-0.186-1.563-0.533-2.251 0.694 0.365 1.32 0.768 1.85 1.145 1.157 0.824 2.042 1.658 2.489 2.106-0.447 0.448-1.332 1.281-2.489 2.106z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_views_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Views SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_downloads_svg')) {
	function ct_downloads_svg() {
		$ct_downloads_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M16.5 20h-14c-0.827 0-1.5-0.673-1.5-1.5v-14c0-0.827 0.673-1.5 1.5-1.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5 0.224-0.5 0.5v14c0 0.276 0.224 0.5 0.5 0.5h14c0.276 0 0.5-0.224 0.5-0.5v-14c0-0.276-0.224-0.5-0.5-0.5h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.827 0 1.5 0.673 1.5 1.5v14c0 0.827-0.673 1.5-1.5 1.5z" fill="#ffffff"></path> <path d="M13.501 5c-0 0-0 0-0.001 0h-8c-0.276 0-0.5-0.224-0.5-0.5 0-1.005 0.453-1.786 1.276-2.197 0.275-0.138 0.547-0.213 0.764-0.254 0.213-1.164 1.235-2.049 2.459-2.049s2.246 0.885 2.459 2.049c0.218 0.041 0.489 0.116 0.764 0.254 0.816 0.408 1.268 1.178 1.276 2.17 0.001 0.009 0.001 0.018 0.001 0.027 0 0.276-0.224 0.5-0.5 0.5zM6.060 4h6.88c-0.096-0.356-0.307-0.617-0.638-0.79-0.389-0.203-0.8-0.21-0.805-0.21-0.276 0-0.497-0.224-0.497-0.5 0-0.827-0.673-1.5-1.5-1.5s-1.5 0.673-1.5 1.5c0 0.276-0.224 0.5-0.5 0.5-0.001 0-0.413 0.007-0.802 0.21-0.331 0.173-0.542 0.433-0.638 0.79z" fill="#ffffff"></path> <path d="M9.5 3c-0.132 0-0.261-0.053-0.353-0.147s-0.147-0.222-0.147-0.353 0.053-0.261 0.147-0.353c0.093-0.093 0.222-0.147 0.353-0.147s0.261 0.053 0.353 0.147c0.093 0.093 0.147 0.222 0.147 0.353s-0.053 0.26-0.147 0.353c-0.093 0.093-0.222 0.147-0.353 0.147z" fill="#ffffff"></path> <path d="M11.854 12.646c-0.195-0.195-0.512-0.195-0.707 0l-1.146 1.146v-5.293c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v5.293l-1.146-1.146c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l2 2c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l2-2c0.195-0.195 0.195-0.512-0-0.707z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_downloads_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Property Type SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_property_type_svg')) {
	function ct_property_type_svg() {
		$ct_property_type_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M19.871 12.165l-8.829-9.758c-0.274-0.303-0.644-0.47-1.042-0.47-0 0 0 0 0 0-0.397 0-0.767 0.167-1.042 0.47l-8.829 9.758c-0.185 0.205-0.169 0.521 0.035 0.706 0.096 0.087 0.216 0.129 0.335 0.129 0.136 0 0.272-0.055 0.371-0.165l2.129-2.353v8.018c0 0.827 0.673 1.5 1.5 1.5h11c0.827 0 1.5-0.673 1.5-1.5v-8.018l2.129 2.353c0.185 0.205 0.501 0.221 0.706 0.035s0.221-0.501 0.035-0.706zM12 19h-4v-4.5c0-0.276 0.224-0.5 0.5-0.5h3c0.276 0 0.5 0.224 0.5 0.5v4.5zM16 18.5c0 0.276-0.224 0.5-0.5 0.5h-2.5v-4.5c0-0.827-0.673-1.5-1.5-1.5h-3c-0.827 0-1.5 0.673-1.5 1.5v4.5h-2.5c-0.276 0-0.5-0.224-0.5-0.5v-9.123l5.7-6.3c0.082-0.091 0.189-0.141 0.3-0.141s0.218 0.050 0.3 0.141l5.7 6.3v9.123z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_property_type_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Property Type SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_property_type_svg_white')) {
	function ct_property_type_svg_white() {
		$ct_property_type_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 20 20"> <path d="M19.871 12.165l-8.829-9.758c-0.274-0.303-0.644-0.47-1.042-0.47-0 0 0 0 0 0-0.397 0-0.767 0.167-1.042 0.47l-8.829 9.758c-0.185 0.205-0.169 0.521 0.035 0.706 0.096 0.087 0.216 0.129 0.335 0.129 0.136 0 0.272-0.055 0.371-0.165l2.129-2.353v8.018c0 0.827 0.673 1.5 1.5 1.5h11c0.827 0 1.5-0.673 1.5-1.5v-8.018l2.129 2.353c0.185 0.205 0.501 0.221 0.706 0.035s0.221-0.501 0.035-0.706zM12 19h-4v-4.5c0-0.276 0.224-0.5 0.5-0.5h3c0.276 0 0.5 0.224 0.5 0.5v4.5zM16 18.5c0 0.276-0.224 0.5-0.5 0.5h-2.5v-4.5c0-0.827-0.673-1.5-1.5-1.5h-3c-0.827 0-1.5 0.673-1.5 1.5v4.5h-2.5c-0.276 0-0.5-0.224-0.5-0.5v-9.123l5.7-6.3c0.082-0.091 0.189-0.141 0.3-0.141s0.218 0.050 0.3 0.141l5.7 6.3v9.123z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_property_type_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Price Per Sq Ft/Sq M SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_price_per_sqftsqm_svg')) {
	function ct_price_per_sqftsqm_svg() {
		$ct_price_per_sqftsqm_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M9,7.8L1.9,0.6C1.5,0.3,1.1,0.2,0.7,0.4S0,1,0,1.4v17.1C0,19.3,0.7,20,1.5,20h17.1c0.5,0,0.9-0.3,1-0.7s0.1-0.9-0.3-1.2l0,0 L12.2,11 M9.5,13.7l2.3,2.3H4.5C4.2,16,4,15.8,4,15.5V8.2l2.3,2.3 M7,11.2 M8.8,13 M18.6,18.9c0,0,0.1,0.1,0,0.1l0,0 c0,0-0.1,0-0.1,0H1.5C1.2,19,1,18.8,1,18.5V17h0.5C1.8,17,2,16.8,2,16.5S1.8,16,1.5,16H1v-2h0.5C1.8,14,2,13.8,2,13.5S1.8,13,1.5,13 H1v-2h0.5C1.8,11,2,10.8,2,10.5S1.8,10,1.5,10H1V8h0.5C1.8,8,2,7.8,2,7.5S1.8,7,1.5,7H1V1.4c0,0,0-0.1,0-0.1s0.1,0,0.1,0l10.4,10.4 M4.6,7.4C4.1,6.9,3.7,7,3.5,7S3,7.3,3,8v7.5C3,16.3,3.7,17,4.5,17H12c0.7,0,0.9-0.4,1-0.5s0.2-0.6-0.3-1L10.2,13" fill="#878c92"></path> <path d="M16.8,4.5h-4.4V3.2h4.4c0.3,0,0.6-0.3,0.6-0.6s-0.3-0.6-0.6-0.6h-1.9V1.3c0-0.3-0.3-0.6-0.6-0.6c-0.3,0-0.6,0.3-0.6,0.6v0.6 h-1.9c-0.3,0-0.6,0.3-0.6,0.6v2.5c0,0.3,0.3,0.6,0.6,0.6h4.4V7h-4.4c-0.3,0-0.6,0.3-0.6,0.6s0.3,0.6,0.6,0.6h1.9v0.6 c0,0.3,0.3,0.6,0.6,0.6c0.3,0,0.6-0.3,0.6-0.6V8.3h1.9c0.3,0,0.6-0.3,0.6-0.6V5.1C17.5,4.8,17.2,4.5,16.8,4.5z" fill="#878c92"></path> </svg> ';

		echo wp_kses( $ct_price_per_sqftsqm_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Pet SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_pet_svg')) {
	function ct_pet_svg() {
		$ct_pet_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M3 12c-1.122 0-2-1.322-2-3.011s0.878-3.011 2-3.011 2 1.322 2 3.011-0.878 3.011-2 3.011zM3 6.978c-0.472 0-1 0.86-1 2.011s0.528 2.011 1 2.011 1-0.86 1-2.011-0.528-2.011-1-2.011z" fill="#878c92"></path> <path d="M6.998 8c-1.12 0-1.998-1.318-1.998-3s0.878-3 1.998-3 1.998 1.318 1.998 3-0.878 3-1.998 3zM6.998 3c-0.471 0-0.998 0.855-0.998 2s0.527 2 0.998 2 0.998-0.855 0.998-2-0.527-2-0.998-2z" fill="#878c92"></path> <path d="M12 8c-1.122 0-2-1.318-2-3s0.878-3 2-3 2 1.318 2 3-0.878 3-2 3zM12 3c-0.472 0-1 0.855-1 2s0.528 2 1 2 1-0.855 1-2-0.528-2-1-2z" fill="#878c92"></path> <path d="M16 12c-1.122 0-2-1.322-2-3.011s0.878-3.011 2-3.011 2 1.322 2 3.011-0.878 3.011-2 3.011zM16 6.978c-0.472 0-1 0.86-1 2.011s0.528 2.011 1 2.011 1-0.86 1-2.011-0.528-2.011-1-2.011z" fill="#878c92"></path> <path d="M13 18c-0.868 0-1.455-0.294-1.972-0.553-0.48-0.24-0.894-0.447-1.528-0.447-0.631 0-1.045 0.207-1.525 0.447-0.519 0.259-1.107 0.553-1.975 0.553-0.556 0-1.079-0.303-1.437-0.831-0.627-0.926-0.637-2.331-0.028-3.855 1.097-2.742 2.906-4.314 4.964-4.314s3.868 1.572 4.964 4.314c0.609 1.524 0.599 2.929-0.028 3.855-0.357 0.528-0.881 0.831-1.437 0.831zM9.5 16c0.869 0 1.457 0.294 1.975 0.553 0.479 0.24 0.893 0.447 1.525 0.447 0.218 0 0.44-0.143 0.609-0.391 0.432-0.637 0.404-1.73-0.073-2.923-0.937-2.342-2.408-3.686-4.036-3.686s-3.099 1.343-4.036 3.686c-0.477 1.193-0.504 2.286-0.073 2.923 0.168 0.249 0.39 0.391 0.609 0.391 0.632 0 1.047-0.207 1.528-0.448 0.518-0.259 1.106-0.553 1.972-0.553z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_pet_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Parking SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_parking_svg')) {
	function ct_parking_svg() {
		$ct_parking_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M20.001 15.51c0-0.011-0-0.023-0.001-0.034-0.006-0.258-0.094-1.833-1.276-2.424-0.171-0.085-0.525-0.262-3.883-0.524-0.122-0.28-0.327-0.731-0.576-1.203-0.779-1.478-1.27-1.817-1.572-1.943-1.057-0.442-4.242-0.542-6.314-0.024-1.297 0.324-2.459 1.088-3.36 2.209-0.513 0.638-0.807 1.223-0.924 1.483-0.297 0.234-0.565 0.506-0.803 0.817-0.857 1.115-1.291 2.673-1.291 4.632 0 0.133 0.053 0.26 0.146 0.354s0.221 0.146 0.354 0.146l1.035-0c0.249-0 0.46-0.183 0.495-0.429 0.028-0.197 0.076-0.388 0.141-0.57 0.413 1.164 1.525 2 2.828 2s2.415-0.836 2.828-2c0.065 0.183 0.113 0.373 0.141 0.57 0.035 0.246 0.246 0.429 0.495 0.429l6.071 0c0.249 0 0.46-0.183 0.495-0.429 0.017-0.119 0.042-0.236 0.073-0.35 0.311 1.028 1.267 1.779 2.395 1.779 1.38 0 2.502-1.122 2.502-2.502 0-0.555-0.181-1.071-0.491-1.489 0.272-0.005 0.491-0.226 0.491-0.5zM6.621 10.329c1.914-0.479 4.898-0.353 5.686-0.024 0.038 0.016 0.39 0.192 1.069 1.48 0.122 0.23 0.233 0.458 0.328 0.66-0.605-0.042-1.285-0.086-2.050-0.132-3.094-0.186-6.104-0.312-6.134-0.313-0.007-0-0.014-0-0.021-0-0.621 0-1.194 0.072-1.717 0.215 0.583-0.73 1.502-1.552 2.839-1.886zM5 19c-1.103 0-2-0.897-2-2 0-0.082 0.006-0.163 0.015-0.244 0.534-0.471 1.232-0.756 1.985-0.756s1.451 0.285 1.985 0.756c0.010 0.081 0.015 0.162 0.015 0.244 0 1.103-0.897 2-2 2zM14.126 18l-5.253-0c-0.18-0.698-0.548-1.337-1.065-1.847-0.005-0.005-0.011-0.011-0.016-0.016-0.052-0.051-0.106-0.101-0.161-0.149-0.728-0.637-1.663-0.987-2.632-0.987s-1.903 0.351-2.632 0.987c-0.043 0.038-0.085 0.076-0.126 0.116-0.029 0.023-0.055 0.048-0.078 0.077-0.502 0.505-0.861 1.134-1.037 1.82h-0.116c0.105-2.387 1.013-4.995 4.479-5 0.197 0.008 3.083 0.131 6.058 0.309 5.681 0.34 6.588 0.575 6.728 0.638 0.398 0.199 0.577 0.677 0.658 1.053l-0.934 0c-0.1 0-0.2 0.004-0.299 0.011-0.006 0-0.012 0.001-0.018 0.001-0.651 0.052-1.278 0.261-1.826 0.61-0.006 0.003-0.011 0.007-0.017 0.011-0.165 0.106-0.323 0.224-0.472 0.354-0.61 0.534-1.042 1.237-1.242 2.012zM17.498 19c-0.828 0-1.502-0.674-1.502-1.502 0-0.393 0.149-0.76 0.419-1.041 0.388-0.243 0.833-0.4 1.307-0.444 0.732 0.109 1.278 0.739 1.278 1.485 0 0.828-0.674 1.502-1.502 1.502z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_parking_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Group SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_group_svg')) {
	function ct_group_svg() {
		$ct_group_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M18.927 16.158c-0.357-0.381-0.791-0.713-1.296-0.997-0.303-0.604-0.898-1.399-2.058-2.033-0.475-0.26-1.012-0.475-1.607-0.646 1.213-0.688 2.034-1.991 2.034-3.482 0-1.619-0.967-3.017-2.354-3.645-0.297-0.658-0.763-1.221-1.36-1.637-0.203-0.142-0.418-0.263-0.64-0.364-0.297-0.657-0.763-1.22-1.36-1.636-0.673-0.469-1.463-0.717-2.286-0.717-2.206 0-4 1.794-4 4 0 0.823 0.248 1.613 0.717 2.286 0.416 0.597 0.979 1.063 1.636 1.36 0.101 0.223 0.222 0.437 0.364 0.64 0.188 0.269 0.406 0.511 0.648 0.724-1.498 0.047-2.797 0.263-3.869 0.644-1.009 0.359-1.824 0.865-2.424 1.504-1.061 1.131-1.073 2.293-1.073 2.342 0 0.827 0.673 1.5 1.5 1.5h0.568c-0.066 0.291-0.068 0.481-0.068 0.5 0 0.827 0.673 1.5 1.5 1.5h0.568c-0.066 0.291-0.068 0.481-0.068 0.5 0 0.827 0.673 1.5 1.5 1.5h13c0.827 0 1.5-0.673 1.5-1.5 0-0.049-0.012-1.211-1.073-2.342zM15 9c0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3 3 1.346 3 3zM5 5c0-1.654 1.346-3 3-3 0.866 0 1.679 0.382 2.24 1.008-0.080-0.005-0.159-0.008-0.24-0.008-2.206 0-4 1.794-4 4 0 0.080 0.003 0.16 0.008 0.24-0.626-0.561-1.008-1.374-1.008-2.24zM7 7c0-1.654 1.346-3 3-3 0.865 0 1.678 0.382 2.239 1.007-0.079-0.005-0.159-0.007-0.239-0.007-2.206 0-4 1.794-4 4 0 0.080 0.003 0.16 0.007 0.239-0.626-0.561-1.007-1.374-1.007-2.239zM1.5 15c-0.275 0-0.498-0.223-0.5-0.497 0.001-0.055 0.037-0.879 0.855-1.716 0.797-0.815 2.51-1.787 6.145-1.787 0.183 0 0.364 0.003 0.542 0.008 0.22 0.378 0.5 0.716 0.827 1.002-1.499 0.047-2.799 0.263-3.872 0.644-1.009 0.359-1.824 0.865-2.424 1.504-0.267 0.285-0.467 0.572-0.618 0.842h-0.955zM3.5 17c-0.275 0-0.498-0.223-0.5-0.497 0.002-0.056 0.038-0.88 0.855-1.716 0.797-0.815 2.51-1.787 6.145-1.787 3.035 0 4.753 0.668 5.725 1.417-1.067-0.277-2.315-0.417-3.725-0.417-1.768 0-3.283 0.22-4.503 0.654-1.009 0.359-1.824 0.865-2.424 1.504-0.267 0.285-0.467 0.572-0.618 0.842h-0.955zM18.5 19h-13c-0.275 0-0.498-0.223-0.5-0.497 0.001-0.055 0.037-0.879 0.855-1.716 0.797-0.815 2.51-1.787 6.145-1.787s5.348 0.972 6.145 1.787c0.818 0.837 0.853 1.661 0.855 1.716-0.002 0.274-0.225 0.497-0.5 0.497z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_group_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Calendar SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_calendar_svg')) {
	function ct_calendar_svg() {
		$ct_calendar_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M18.5 2h-2.5v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-10v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2.5c-0.827 0-1.5 0.673-1.5 1.5v14c0 0.827 0.673 1.5 1.5 1.5h17c0.827 0 1.5-0.673 1.5-1.5v-14c0-0.827-0.673-1.5-1.5-1.5zM1.5 3h2.5v1.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-1.5h10v1.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-1.5h2.5c0.276 0 0.5 0.224 0.5 0.5v2.5h-18v-2.5c0-0.276 0.224-0.5 0.5-0.5zM18.5 18h-17c-0.276 0-0.5-0.224-0.5-0.5v-10.5h18v10.5c0 0.276-0.224 0.5-0.5 0.5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_calendar_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Calendar SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_calendar_svg_white')) {
	function ct_calendar_svg_white() {
		$ct_calendar_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M18.5 2h-2.5v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-10v-0.5c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v0.5h-2.5c-0.827 0-1.5 0.673-1.5 1.5v14c0 0.827 0.673 1.5 1.5 1.5h17c0.827 0 1.5-0.673 1.5-1.5v-14c0-0.827-0.673-1.5-1.5-1.5zM1.5 3h2.5v1.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-1.5h10v1.5c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-1.5h2.5c0.276 0 0.5 0.224 0.5 0.5v2.5h-18v-2.5c0-0.276 0.224-0.5 0.5-0.5zM18.5 18h-17c-0.276 0-0.5-0.224-0.5-0.5v-10.5h18v10.5c0 0.276-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_calendar_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Building SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_building_svg')) {
	function ct_building_svg() {
		$ct_building_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M14 6h1v1h-1v-1z" fill="#878c92"></path> <path d="M14 8h1v1h-1v-1z" fill="#878c92"></path> <path d="M14 10h1v1h-1v-1z" fill="#878c92"></path> <path d="M14 12h1v1h-1v-1z" fill="#878c92"></path> <path d="M14 16h1v1h-1v-1z" fill="#878c92"></path> <path d="M14 14h1v1h-1v-1z" fill="#878c92"></path> <path d="M6 6h1v1h-1v-1z" fill="#878c92"></path> <path d="M6 8h1v1h-1v-1z" fill="#878c92"></path> <path d="M6 10h1v1h-1v-1z" fill="#878c92"></path> <path d="M6 12h1v1h-1v-1z" fill="#878c92"></path> <path d="M6 16h1v1h-1v-1z" fill="#878c92"></path> <path d="M6 14h1v1h-1v-1z" fill="#878c92"></path> <path d="M4 6h1v1h-1v-1z" fill="#878c92"></path> <path d="M4 8h1v1h-1v-1z" fill="#878c92"></path> <path d="M4 10h1v1h-1v-1z" fill="#878c92"></path> <path d="M4 12h1v1h-1v-1z" fill="#878c92"></path> <path d="M4 16h1v1h-1v-1z" fill="#878c92"></path> <path d="M4 14h1v1h-1v-1z" fill="#878c92"></path> <path d="M8 6h1v1h-1v-1z" fill="#878c92"></path> <path d="M8 8h1v1h-1v-1z" fill="#878c92"></path> <path d="M8 10h1v1h-1v-1z" fill="#878c92"></path> <path d="M8 12h1v1h-1v-1z" fill="#878c92"></path> <path d="M8 16h1v1h-1v-1z" fill="#878c92"></path> <path d="M8 14h1v1h-1v-1z" fill="#878c92"></path> <path d="M18.5 19h-0.5v-13.5c0-0.763-0.567-1.549-1.291-1.791l-4.709-1.57v-1.64c0-0.158-0.075-0.307-0.202-0.401s-0.291-0.123-0.442-0.078l-9.042 2.713c-0.737 0.221-1.314 0.997-1.314 1.766v14.5h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h18c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5zM16.393 4.658c0.318 0.106 0.607 0.507 0.607 0.842v13.5h-5v-15.806l4.393 1.464zM2 4.5c0-0.329 0.287-0.714 0.602-0.808l8.398-2.52v17.828h-9v-14.5z" fill="#878c92"></path> </svg>';

		echo wp_kses( $ct_building_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Building SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_building_svg_white')) {
	function ct_building_svg_white() {
		$ct_building_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 20 20"> <path d="M14 6h1v1h-1v-1z" fill="#ffffff"></path> <path d="M14 8h1v1h-1v-1z" fill="#ffffff"></path> <path d="M14 10h1v1h-1v-1z" fill="#878c92"></path> <path d="M14 12h1v1h-1v-1z" fill="#ffffff"></path> <path d="M14 16h1v1h-1v-1z" fill="#ffffff"></path> <path d="M14 14h1v1h-1v-1z" fill="#ffffff"></path> <path d="M6 6h1v1h-1v-1z" fill="#ffffff"></path> <path d="M6 8h1v1h-1v-1z" fill="#ffffff"></path> <path d="M6 10h1v1h-1v-1z" fill="#ffffff"></path> <path d="M6 12h1v1h-1v-1z" fill="#ffffff"></path> <path d="M6 16h1v1h-1v-1z" fill="#ffffff"></path> <path d="M6 14h1v1h-1v-1z" fill="#ffffff"></path> <path d="M4 6h1v1h-1v-1z" fill="#ffffff"></path> <path d="M4 8h1v1h-1v-1z" fill="#ffffff"></path> <path d="M4 10h1v1h-1v-1z" fill="#ffffff"></path> <path d="M4 12h1v1h-1v-1z" fill="#ffffff"></path> <path d="M4 16h1v1h-1v-1z" fill="#ffffff"></path> <path d="M4 14h1v1h-1v-1z" fill="#ffffff"></path> <path d="M8 6h1v1h-1v-1z" fill="#ffffff"></path> <path d="M8 8h1v1h-1v-1z" fill="#878c92"></path> <path d="M8 10h1v1h-1v-1z" fill="#878c92"></path> <path d="M8 12h1v1h-1v-1z" fill="#878c92"></path> <path d="M8 16h1v1h-1v-1z" fill="#878c92"></path> <path d="M8 14h1v1h-1v-1z" fill="#ffffff"></path> <path d="M18.5 19h-0.5v-13.5c0-0.763-0.567-1.549-1.291-1.791l-4.709-1.57v-1.64c0-0.158-0.075-0.307-0.202-0.401s-0.291-0.123-0.442-0.078l-9.042 2.713c-0.737 0.221-1.314 0.997-1.314 1.766v14.5h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h18c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5zM16.393 4.658c0.318 0.106 0.607 0.507 0.607 0.842v13.5h-5v-15.806l4.393 1.464zM2 4.5c0-0.329 0.287-0.714 0.602-0.808l8.398-2.52v17.828h-9v-14.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_building_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Tree SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_tree_svg')) {
	function ct_tree_svg() {
		$ct_tree_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M17.874 15.168l-3.245-3.651c0.56-0.183 0.964-0.402 1.225-0.663 0.185-0.185 0.196-0.481 0.026-0.679l-3.107-3.625c0.447-0.191 0.809-0.424 1.081-0.696 0.18-0.18 0.196-0.467 0.037-0.666l-4-5c-0.095-0.119-0.239-0.188-0.39-0.188s-0.296 0.069-0.39 0.188l-4 5c-0.159 0.199-0.143 0.486 0.037 0.666 0.272 0.272 0.634 0.505 1.081 0.696l-3.107 3.625c-0.17 0.198-0.159 0.494 0.026 0.679 0.261 0.261 0.665 0.48 1.225 0.663l-3.245 3.651c-0.096 0.108-0.141 0.253-0.122 0.397s0.099 0.272 0.22 0.352c0.829 0.547 3.902 0.942 6.775 1.049v2.533c0 0.276 0.224 0.5 0.5 0.5h2c0.276 0 0.5-0.224 0.5-0.5v-2.531c1.259-0.044 2.517-0.143 3.648-0.288 1.12-0.144 2.573-0.394 3.13-0.765 0.12-0.080 0.2-0.209 0.219-0.352s-0.026-0.288-0.122-0.396zM10 19h-1v-2.008c0.169 0.002 0.335 0.004 0.5 0.004h0.014c0 0 0 0 0 0 0.161 0 0.323-0.001 0.485-0.003v2.007zM14.52 15.689c-1.537 0.198-3.315 0.307-5.005 0.307-0 0-0 0-0 0h-0.014c-3.112-0.001-5.923-0.367-7.151-0.699l3.308-3.722c0.118-0.133 0.157-0.318 0.102-0.487s-0.195-0.296-0.368-0.334c-0.546-0.12-0.907-0.248-1.143-0.359l3.216-3.752c0.111-0.13 0.148-0.307 0.099-0.47s-0.179-0.29-0.343-0.336c-0.404-0.114-0.747-0.256-1.013-0.42l3.294-4.118 3.294 4.118c-0.267 0.164-0.609 0.306-1.013 0.42-0.164 0.046-0.294 0.173-0.343 0.336s-0.012 0.341 0.099 0.47l3.216 3.752c-0.237 0.111-0.598 0.239-1.143 0.359-0.173 0.038-0.313 0.165-0.368 0.334s-0.016 0.354 0.102 0.487l3.31 3.723c-0.447 0.123-1.145 0.263-2.132 0.391z" fill="#878c92"></path> </svg> ';

		echo wp_kses( $ct_tree_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Tree SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_tree_svg_white')) {
	function ct_tree_svg_white() {
		$ct_tree_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 20 20"> <path d="M17.874 15.168l-3.245-3.651c0.56-0.183 0.964-0.402 1.225-0.663 0.185-0.185 0.196-0.481 0.026-0.679l-3.107-3.625c0.447-0.191 0.809-0.424 1.081-0.696 0.18-0.18 0.196-0.467 0.037-0.666l-4-5c-0.095-0.119-0.239-0.188-0.39-0.188s-0.296 0.069-0.39 0.188l-4 5c-0.159 0.199-0.143 0.486 0.037 0.666 0.272 0.272 0.634 0.505 1.081 0.696l-3.107 3.625c-0.17 0.198-0.159 0.494 0.026 0.679 0.261 0.261 0.665 0.48 1.225 0.663l-3.245 3.651c-0.096 0.108-0.141 0.253-0.122 0.397s0.099 0.272 0.22 0.352c0.829 0.547 3.902 0.942 6.775 1.049v2.533c0 0.276 0.224 0.5 0.5 0.5h2c0.276 0 0.5-0.224 0.5-0.5v-2.531c1.259-0.044 2.517-0.143 3.648-0.288 1.12-0.144 2.573-0.394 3.13-0.765 0.12-0.080 0.2-0.209 0.219-0.352s-0.026-0.288-0.122-0.396zM10 19h-1v-2.008c0.169 0.002 0.335 0.004 0.5 0.004h0.014c0 0 0 0 0 0 0.161 0 0.323-0.001 0.485-0.003v2.007zM14.52 15.689c-1.537 0.198-3.315 0.307-5.005 0.307-0 0-0 0-0 0h-0.014c-3.112-0.001-5.923-0.367-7.151-0.699l3.308-3.722c0.118-0.133 0.157-0.318 0.102-0.487s-0.195-0.296-0.368-0.334c-0.546-0.12-0.907-0.248-1.143-0.359l3.216-3.752c0.111-0.13 0.148-0.307 0.099-0.47s-0.179-0.29-0.343-0.336c-0.404-0.114-0.747-0.256-1.013-0.42l3.294-4.118 3.294 4.118c-0.267 0.164-0.609 0.306-1.013 0.42-0.164 0.046-0.294 0.173-0.343 0.336s-0.012 0.341 0.099 0.47l3.216 3.752c-0.237 0.111-0.598 0.239-1.143 0.359-0.173 0.038-0.313 0.165-0.368 0.334s-0.016 0.354 0.102 0.487l3.31 3.723c-0.447 0.123-1.145 0.263-2.132 0.391z" fill="#ffffff"></path> </svg> ';

		echo wp_kses( $ct_tree_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Star SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_star_svg')) {
	function ct_star_svg() {
		$ct_star_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"> <path d="M15.5 19c-0.082 0-0.164-0.020-0.239-0.061l-5.261-2.869-5.261 2.869c-0.168 0.092-0.373 0.079-0.529-0.032s-0.235-0.301-0.203-0.49l0.958-5.746-3.818-3.818c-0.132-0.132-0.18-0.328-0.123-0.506s0.209-0.31 0.394-0.341l5.749-0.958 2.386-4.772c0.085-0.169 0.258-0.276 0.447-0.276s0.363 0.107 0.447 0.276l2.386 4.772 5.749 0.958c0.185 0.031 0.337 0.162 0.394 0.341s0.010 0.374-0.123 0.506l-3.818 3.818 0.958 5.746c0.031 0.189-0.048 0.379-0.203 0.49-0.086 0.061-0.188 0.093-0.29 0.093zM10 15c0.082 0 0.165 0.020 0.239 0.061l4.599 2.508-0.831-4.987c-0.027-0.159 0.025-0.322 0.14-0.436l3.313-3.313-5.042-0.84c-0.158-0.026-0.293-0.127-0.365-0.27l-2.053-4.106-2.053 4.106c-0.072 0.143-0.207 0.243-0.365 0.27l-5.042 0.84 3.313 3.313c0.114 0.114 0.166 0.276 0.14 0.436l-0.831 4.987 4.599-2.508c0.075-0.041 0.157-0.061 0.239-0.061z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_star_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Phone SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_phone_svg')) {
	function ct_phone_svg() {
		$ct_phone_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M16 20c-1.771 0-3.655-0.502-5.6-1.492-1.793-0.913-3.564-2.22-5.122-3.78s-2.863-3.333-3.775-5.127c-0.988-1.946-1.49-3.83-1.49-5.601 0-1.148 1.070-2.257 1.529-2.68 0.661-0.609 1.701-1.32 2.457-1.32 0.376 0 0.816 0.246 1.387 0.774 0.425 0.394 0.904 0.928 1.383 1.544 0.289 0.372 1.73 2.271 1.73 3.182 0 0.747-0.845 1.267-1.739 1.816-0.346 0.212-0.703 0.432-0.961 0.639-0.276 0.221-0.325 0.338-0.333 0.364 0.949 2.366 3.85 5.267 6.215 6.215 0.021-0.007 0.138-0.053 0.363-0.333 0.207-0.258 0.427-0.616 0.639-0.961 0.55-0.894 1.069-1.739 1.816-1.739 0.911 0 2.81 1.441 3.182 1.73 0.616 0.479 1.15 0.958 1.544 1.383 0.528 0.57 0.774 1.011 0.774 1.387 0 0.756-0.711 1.799-1.319 2.463-0.424 0.462-1.533 1.537-2.681 1.537zM3.994 1c-0.268 0.005-0.989 0.333-1.773 1.055-0.744 0.686-1.207 1.431-1.207 1.945 0 6.729 8.264 15 14.986 15 0.513 0 1.258-0.465 1.944-1.213 0.723-0.788 1.051-1.512 1.056-1.781-0.032-0.19-0.558-0.929-1.997-2.037-1.237-0.952-2.24-1.463-2.498-1.469-0.018 0.005-0.13 0.048-0.357 0.336-0.197 0.251-0.408 0.594-0.613 0.926-0.56 0.911-1.089 1.772-1.858 1.772-0.124 0-0.246-0.024-0.363-0.071-2.625-1.050-5.729-4.154-6.779-6.779-0.126-0.315-0.146-0.809 0.474-1.371 0.33-0.299 0.786-0.579 1.228-0.851 0.332-0.204 0.676-0.415 0.926-0.613 0.288-0.227 0.331-0.339 0.336-0.357-0.007-0.258-0.517-1.261-1.469-2.498-1.108-1.439-1.847-1.964-2.037-1.997z" fill="#191919"></path> </svg>';

		echo wp_kses( $ct_phone_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Phone SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_phone_svg_white')) {
	function ct_phone_svg_white() {
		$ct_phone_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M16 20c-1.771 0-3.655-0.502-5.6-1.492-1.793-0.913-3.564-2.22-5.122-3.78s-2.863-3.333-3.775-5.127c-0.988-1.946-1.49-3.83-1.49-5.601 0-1.148 1.070-2.257 1.529-2.68 0.661-0.609 1.701-1.32 2.457-1.32 0.376 0 0.816 0.246 1.387 0.774 0.425 0.394 0.904 0.928 1.383 1.544 0.289 0.372 1.73 2.271 1.73 3.182 0 0.747-0.845 1.267-1.739 1.816-0.346 0.212-0.703 0.432-0.961 0.639-0.276 0.221-0.325 0.338-0.333 0.364 0.949 2.366 3.85 5.267 6.215 6.215 0.021-0.007 0.138-0.053 0.363-0.333 0.207-0.258 0.427-0.616 0.639-0.961 0.55-0.894 1.069-1.739 1.816-1.739 0.911 0 2.81 1.441 3.182 1.73 0.616 0.479 1.15 0.958 1.544 1.383 0.528 0.57 0.774 1.011 0.774 1.387 0 0.756-0.711 1.799-1.319 2.463-0.424 0.462-1.533 1.537-2.681 1.537zM3.994 1c-0.268 0.005-0.989 0.333-1.773 1.055-0.744 0.686-1.207 1.431-1.207 1.945 0 6.729 8.264 15 14.986 15 0.513 0 1.258-0.465 1.944-1.213 0.723-0.788 1.051-1.512 1.056-1.781-0.032-0.19-0.558-0.929-1.997-2.037-1.237-0.952-2.24-1.463-2.498-1.469-0.018 0.005-0.13 0.048-0.357 0.336-0.197 0.251-0.408 0.594-0.613 0.926-0.56 0.911-1.089 1.772-1.858 1.772-0.124 0-0.246-0.024-0.363-0.071-2.625-1.050-5.729-4.154-6.779-6.779-0.126-0.315-0.146-0.809 0.474-1.371 0.33-0.299 0.786-0.579 1.228-0.851 0.332-0.204 0.676-0.415 0.926-0.613 0.288-0.227 0.331-0.339 0.336-0.357-0.007-0.258-0.517-1.261-1.469-2.498-1.108-1.439-1.847-1.964-2.037-1.997z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_phone_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Printer SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_printer_svg')) {
	function ct_printer_svg() {
		$ct_printer_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M18.5 4h-1.5v-2.5c0-0.827-0.673-1.5-1.5-1.5h-11c-0.827 0-1.5 0.673-1.5 1.5v2.5h-1.5c-0.827 0-1.5 0.673-1.5 1.5v9c0 0.827 0.673 1.5 1.5 1.5h1.5v2.5c0 0.827 0.673 1.5 1.5 1.5h11c0.827 0 1.5-0.673 1.5-1.5v-2.5h1.5c0.827 0 1.5-0.673 1.5-1.5v-9c0-0.827-0.673-1.5-1.5-1.5zM4 1.5c0-0.276 0.224-0.5 0.5-0.5h11c0.276 0 0.5 0.224 0.5 0.5v2.5h-12v-2.5zM15.5 19h-11c-0.276 0-0.5-0.224-0.5-0.5v-6.5h12v6.5c0 0.276-0.224 0.5-0.5 0.5zM19 14.5c0 0.276-0.224 0.5-0.5 0.5h-1.5v-3h0.5c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-15c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h0.5v3h-1.5c-0.276 0-0.5-0.224-0.5-0.5v-9c0-0.276 0.224-0.5 0.5-0.5h17c0.276 0 0.5 0.224 0.5 0.5v9z" fill="#191919"></path> <path d="M14.5 14h-9c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h9c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#191919"></path> <path d="M14.5 16h-9c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h9c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#191919"></path> <path d="M14.5 18h-9c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h9c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5z" fill="#191919"></path> <path d="M16.5 9c-0.827 0-1.5-0.673-1.5-1.5s0.673-1.5 1.5-1.5 1.5 0.673 1.5 1.5-0.673 1.5-1.5 1.5zM16.5 7c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5 0.5-0.224 0.5-0.5-0.224-0.5-0.5-0.5z" fill="#191919"></path> </svg>';

		echo wp_kses( $ct_printer_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Envelope SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_envelope_svg')) {
	function ct_envelope_svg() {
		$ct_envelope_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M17.5 6h-16c-0.827 0-1.5 0.673-1.5 1.5v9c0 0.827 0.673 1.5 1.5 1.5h16c0.827 0 1.5-0.673 1.5-1.5v-9c0-0.827-0.673-1.5-1.5-1.5zM17.5 7c0.030 0 0.058 0.003 0.087 0.008l-7.532 5.021c-0.29 0.193-0.819 0.193-1.109 0l-7.532-5.021c0.028-0.005 0.057-0.008 0.087-0.008h16zM17.5 17h-16c-0.276 0-0.5-0.224-0.5-0.5v-8.566l7.391 4.927c0.311 0.207 0.71 0.311 1.109 0.311s0.798-0.104 1.109-0.311l7.391-4.927v8.566c0 0.276-0.224 0.5-0.5 0.5z" fill="#191919"></path> </svg>';

		echo wp_kses( $ct_envelope_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Envelope SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_envelope_svg_white')) {
	function ct_envelope_svg_white() {
		$ct_envelope_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M17.5 6h-16c-0.827 0-1.5 0.673-1.5 1.5v9c0 0.827 0.673 1.5 1.5 1.5h16c0.827 0 1.5-0.673 1.5-1.5v-9c0-0.827-0.673-1.5-1.5-1.5zM17.5 7c0.030 0 0.058 0.003 0.087 0.008l-7.532 5.021c-0.29 0.193-0.819 0.193-1.109 0l-7.532-5.021c0.028-0.005 0.057-0.008 0.087-0.008h16zM17.5 17h-16c-0.276 0-0.5-0.224-0.5-0.5v-8.566l7.391 4.927c0.311 0.207 0.71 0.311 1.109 0.311s0.798-0.104 1.109-0.311l7.391-4.927v8.566c0 0.276-0.224 0.5-0.5 0.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_envelope_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Office SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_office_svg')) {
	function ct_office_svg() {
		$ct_office_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M14 6h1v1h-1v-1z" fill="#191919"></path> <path d="M14 8h1v1h-1v-1z" fill="#191919"></path> <path d="M14 10h1v1h-1v-1z" fill="#191919"></path> <path d="M14 12h1v1h-1v-1z" fill="#191919"></path> <path d="M14 16h1v1h-1v-1z" fill="#191919"></path> <path d="M14 14h1v1h-1v-1z" fill="#191919"></path> <path d="M6 6h1v1h-1v-1z" fill="#191919"></path> <path d="M6 8h1v1h-1v-1z" fill="#191919"></path> <path d="M6 10h1v1h-1v-1z" fill="#191919"></path> <path d="M6 12h1v1h-1v-1z" fill="#191919"></path> <path d="M6 16h1v1h-1v-1z" fill="#878c92"></path> <path d="M6 14h1v1h-1v-1z" fill="#878c92"></path> <path d="M4 6h1v1h-1v-1z" fill="#191919"></path> <path d="M4 8h1v1h-1v-1z" fill="#191919"></path> <path d="M4 10h1v1h-1v-1z" fill="#191919"></path> <path d="M4 12h1v1h-1v-1z" fill="#191919"></path> <path d="M4 16h1v1h-1v-1z" fill="#191919"></path> <path d="M4 14h1v1h-1v-1z" fill="#191919"></path> <path d="M8 6h1v1h-1v-1z" fill="#191919"></path> <path d="M8 8h1v1h-1v-1z" fill="#191919"></path> <path d="M8 10h1v1h-1v-1z" fill="#191919"></path> <path d="M8 12h1v1h-1v-1z" fill="#191919"></path> <path d="M8 16h1v1h-1v-1z" fill="#191919"></path> <path d="M8 14h1v1h-1v-1z" fill="#191919"></path> <path d="M18.5 19h-0.5v-13.5c0-0.763-0.567-1.549-1.291-1.791l-4.709-1.57v-1.64c0-0.158-0.075-0.307-0.202-0.401s-0.291-0.123-0.442-0.078l-9.042 2.713c-0.737 0.221-1.314 0.997-1.314 1.766v14.5h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h18c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5zM16.393 4.658c0.318 0.106 0.607 0.507 0.607 0.842v13.5h-5v-15.806l4.393 1.464zM2 4.5c0-0.329 0.287-0.714 0.602-0.808l8.398-2.52v17.828h-9v-14.5z" fill="#191919"></path> </svg>';

		echo wp_kses( $ct_office_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Office SVG Icon - White */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_office_svg_white')) {
	function ct_office_svg_white() {
		$ct_office_svg_white = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M14 6h1v1h-1v-1z" fill="#878c92"></path> <path d="M14 8h1v1h-1v-1z" fill="#878c92"></path> <path d="M14 10h1v1h-1v-1z" fill="#ffffff"></path> <path d="M14 12h1v1h-1v-1z" fill="#ffffff"></path> <path d="M14 16h1v1h-1v-1z" fill="#878c92"></path> <path d="M14 14h1v1h-1v-1z" fill="#ffffff"></path> <path d="M6 6h1v1h-1v-1z" fill="#878c92"></path> <path d="M6 8h1v1h-1v-1z" fill="#ffffff"></path> <path d="M6 10h1v1h-1v-1z" fill="#ffffff"></path> <path d="M6 12h1v1h-1v-1z" fill="#ffffff"></path> <path d="M6 16h1v1h-1v-1z" fill="#ffffff"></path> <path d="M6 14h1v1h-1v-1z" fill="#878c92"></path> <path d="M4 6h1v1h-1v-1z" fill="#ffffff"></path> <path d="M4 8h1v1h-1v-1z" fill="#ffffff"></path> <path d="M4 10h1v1h-1v-1z" fill="#ffffff"></path> <path d="M4 12h1v1h-1v-1z" fill="#ffffff"></path> <path d="M4 16h1v1h-1v-1z" fill="#ffffff"></path> <path d="M4 14h1v1h-1v-1z" fill="#ffffff"></path> <path d="M8 6h1v1h-1v-1z" fill="#ffffff"></path> <path d="M8 8h1v1h-1v-1z" fill="#ffffff"></path> <path d="M8 10h1v1h-1v-1z" fill="#ffffff"></path> <path d="M8 12h1v1h-1v-1z" fill="#878c92"></path> <path d="M8 16h1v1h-1v-1z" fill="#ffffff"></path> <path d="M8 14h1v1h-1v-1z" fill="#ffffff"></path> <path d="M18.5 19h-0.5v-13.5c0-0.763-0.567-1.549-1.291-1.791l-4.709-1.57v-1.64c0-0.158-0.075-0.307-0.202-0.401s-0.291-0.123-0.442-0.078l-9.042 2.713c-0.737 0.221-1.314 0.997-1.314 1.766v14.5h-0.5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h18c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5zM16.393 4.658c0.318 0.106 0.607 0.507 0.607 0.842v13.5h-5v-15.806l4.393 1.464zM2 4.5c0-0.329 0.287-0.714 0.602-0.808l8.398-2.52v17.828h-9v-14.5z" fill="#ffffff"></path> </svg>';

		echo wp_kses( $ct_office_svg_white, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Globe SVG Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_globe_svg')) {
	function ct_globe_svg() {
		$ct_globe_svg = '<svg class="globe-svg-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 20 20"> <path d="M17.071 2.929c-1.889-1.889-4.4-2.929-7.071-2.929s-5.182 1.040-7.071 2.929c-1.889 1.889-2.929 4.4-2.929 7.071s1.040 5.182 2.929 7.071c1.889 1.889 4.4 2.929 7.071 2.929s5.182-1.040 7.071-2.929c1.889-1.889 2.929-4.4 2.929-7.071s-1.040-5.182-2.929-7.071zM18.397 6.761c-0.195-0.351-0.685-0.518-1.325-0.736-0.687-0.234-0.93-0.94-1.211-1.758-0.244-0.71-0.496-1.443-1.095-1.899 1.639 1.027 2.924 2.567 3.631 4.393zM15.591 10.191c0.076 0.677 0.154 1.378-0.687 2.322-0.227 0.255-0.36 0.61-0.501 0.986-0.326 0.871-0.634 1.694-1.946 1.706-0.037-0.044-0.141-0.21-0.234-0.733-0.085-0.482-0.134-1.106-0.187-1.765-0.080-1.012-0.171-2.16-0.421-3.112-0.32-1.217-0.857-1.936-1.641-2.198-0.342-0.114-0.692-0.17-1.068-0.17-0.278 0-0.53 0.030-0.752 0.056-0.173 0.020-0.337 0.040-0.475 0.040 0 0-0 0-0 0-0.234 0-0.499 0-0.826-0.748-0.469-1.075-0.123-2.798 1.254-3.707 0.755-0.498 1.276-0.711 1.742-0.711 0.372 0 0.773 0.129 1.342 0.433 0.672 0.358 1.199 0.404 1.583 0.404 0.152 0 0.29-0.008 0.423-0.016 0.112-0.007 0.217-0.013 0.315-0.013 0.22 0 0.398 0.029 0.607 0.171 0.385 0.263 0.585 0.844 0.796 1.458 0.32 0.932 0.683 1.988 1.835 2.38 0.155 0.053 0.421 0.143 0.61 0.222-0.163 0.168-0.435 0.411-0.702 0.649-0.172 0.154-0.367 0.328-0.583 0.525-0.624 0.569-0.55 1.235-0.484 1.822zM1.001 9.923c0.108 0.019 0.224 0.042 0.344 0.067 0.562 0.12 0.825 0.228 0.94 0.289-0.053 0.103-0.16 0.255-0.231 0.355-0.247 0.351-0.555 0.788-0.438 1.269 0.079 0.325 0.012 0.723-0.103 1.091-0.332-0.938-0.513-1.946-0.513-2.996 0-0.026 0.001-0.051 0.001-0.077zM10 19c-3.425 0-6.41-1.924-7.93-4.747 0.262-0.499 0.748-1.603 0.521-2.569 0.016-0.097 0.181-0.331 0.28-0.472 0.271-0.385 0.608-0.863 0.358-1.37-0.175-0.356-0.644-0.596-1.566-0.804-0.214-0.048-0.422-0.087-0.599-0.118 0.536-4.455 4.338-7.919 8.935-7.919 1.578 0 3.062 0.409 4.352 1.125-0.319-0.139-0.608-0.161-0.84-0.161-0.127 0-0.253 0.007-0.375 0.015-0.119 0.007-0.242 0.014-0.364 0.014-0.284 0-0.638-0.034-1.112-0.287-0.724-0.385-1.266-0.55-1.812-0.55-0.676 0-1.362 0.262-2.293 0.876-0.805 0.531-1.411 1.343-1.707 2.288-0.289 0.921-0.258 1.864 0.087 2.654 0.407 0.932 0.944 1.348 1.742 1.348 0 0 0 0 0 0 0.197 0 0.389-0.023 0.592-0.047 0.205-0.024 0.416-0.049 0.635-0.049 0.271 0 0.51 0.038 0.751 0.118 0.439 0.147 0.763 0.639 0.991 1.504s0.314 1.966 0.391 2.936c0.064 0.81 0.124 1.574 0.257 2.151 0.081 0.35 0.185 0.616 0.32 0.813 0.201 0.294 0.489 0.456 0.811 0.456 0.884 0 1.59-0.285 2.099-0.847 0.423-0.467 0.639-1.044 0.813-1.508 0.102-0.273 0.208-0.556 0.311-0.672 1.137-1.277 1.020-2.329 0.934-3.098-0.063-0.564-0.064-0.764 0.164-0.972 0.212-0.193 0.405-0.366 0.575-0.518 0.363-0.324 0.625-0.558 0.809-0.758 0.126-0.138 0.422-0.461 0.34-0.865-0.001-0.004-0.002-0.007-0.002-0.011 0.343 0.951 0.53 1.976 0.53 3.044 0 4.963-4.037 9-9 9z" fill="#191919"></path> </svg>';

		echo wp_kses( $ct_globe_svg, ct_kses_svg() );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Search Results Map Navigation */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_results_map_navigation')) {
	function ct_search_results_map_navigation() {
	    global $ct_options;

	    $user_geo_disable_selection = $ct_options['ct_disable_geolocation'];

		echo '<div id="ct-map-navigation">';
		    // Disable geo location when user disables it by selecting "Yes"
		    if( 'no' === $user_geo_disable_selection ) {
			    echo '<button id="search-by-user-location" class="map-btn"><i class="fa fa-crosshairs"></i></button>';
			}

			echo '<button id="ct-gmap-draw" class="map-btn"><span>' . __('', 'contempo') . '</span>';
				ct_pencil_draw_svg();
			echo '</button>';
			echo '<button id="ct-gmap-prev" class="map-btn"><i class="fas fa-chevron-left"></i></button>';
			echo '<button id="ct-gmap-next" class="map-btn"></span> <i class="fas fa-chevron-right"></i></button>';
		echo '</div>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Remove Gravatar Display - User Profile Fields */
/*-----------------------------------------------------------------------------------*/

add_filter( 'option_show_avatars', '__return_false' );

/*-----------------------------------------------------------------------------------*/
/* Ajax Delete Profile Image - User Profile Fields */
/*-----------------------------------------------------------------------------------*/

function ct_ajax_delete_profile_img() {

    $current_user_id = get_current_user_id();

    delete_user_meta( $current_user_id, 'ct_profile_url', '');

}
add_action( 'wp_ajax_ct_ajax_delete_profile_img', 'ct_ajax_delete_profile_img' );

/*-----------------------------------------------------------------------------------*/
/* Ajax Delete Broker Logo - User Profile Fields */
/*-----------------------------------------------------------------------------------*/

function ct_ajax_delete_broker_logo() {

    $current_user_id = get_current_user_id();

    delete_user_meta( $current_user_id, 'ct_broker_logo', '');

}
add_action( 'wp_ajax_ct_ajax_delete_broker_logo', 'ct_ajax_delete_broker_logo' );

/*-----------------------------------------------------------------------------------*/
/* Front End Featured Image Uploads */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_insert_attachment')) {
	function ct_insert_attachment($file_handler, $post_id,$setthumb = 'false') {
		// Check to make sure its a successful upload
		if($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) {
			__return_false();
		}

		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');

		$attach_id = media_handle_upload( $file_handler, $post_id );

		if($setthumb) {
			update_post_meta($post_id, '_thumbnail_id', $attach_id);
		}
		return $attach_id;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Front End Delete Attachment */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_delete_attachment')) {
	function ct_delete_attachment( $post ) {
	    if( wp_delete_attachment( $_POST['att_ID'], true )) {
	        echo 'Attachment ID [' . $_POST['att_ID'] . '] has been deleted!';
	    }
	    die();
	}
}
add_action( 'wp_ajax_delete_attachment', 'ct_delete_attachment' );

/*-----------------------------------------------------------------------------------*/
/* User Listing Post Count */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_post_count')) {
	function ct_listing_post_count( $userid, $post_type ) {
		if( empty( $userid ) ) {
			return false;
		}

		$args = array(
		    'post_type'		=> $post_type,
		    'author'		=> $userid,
		    'meta_query' => array(
			    array(
			        'key' => 'source',
			        'value' => 'idx-api',
			    	'compare' => 'NOT EXISTS'
			    )
			),
		    'post_status'	=> array('draft', 'pending', 'publish')
		);

		$the_query = new WP_Query( $args );
		$user_post_count = $the_query->found_posts;

		return $user_post_count;
	}
}

/*-----------------------------------------------------------------------------------*/
/* User Listing Featured Post Count */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_featured_post_count')) {
	function ct_listing_featured_post_count( $userid, $post_type ) {
		if( empty( $userid ) ) {
			return false;
		}

		$args = array(
		    'post_type'		=> $post_type,
		    'author'		=> $userid,
		    'ct_status'		=> 'featured',
		    'post_status'	=> array('draft', 'pending', 'publish')
		);

		$the_query = new WP_Query( $args );
		$user_post_count = $the_query->found_posts;

		return $user_post_count;
	}
}

/*-----------------------------------------------------------------------------------*/
/* User Listing Pending Post Count */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_pending_post_count')) {
	function ct_listing_pending_post_count( $userid, $post_type ) {
		if( empty( $userid ) ) {
			return false;
		}

		$args = array(
		    'post_type'		=> $post_type,
		    'author'		=> $userid,
		    'post_status'	=> array('draft', 'pending')
		);

		$the_query = new WP_Query( $args );
		$user_post_count = $the_query->found_posts;

		return $user_post_count;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Front End Delete Post */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_delete_post_link')) {
	function ct_delete_post_link ($link = 'Delete This', $before = '', $after = '') {
		global $post;
		
	    $message = __('Are you sure you want to delete ', 'contempo') . get_the_title($post->ID) . __('?', 'contempo');
	    $delLink = wp_nonce_url( esc_url( home_url() ) . '/wp-admin/post.php?action=delete&amp;post=' . $post->ID, 'delete-post_' . $post->ID);
		$htmllink = '<a class="btn delete-listing" href="' . $delLink . '" data-tooltip="' . __('Delete', 'contempo') . '" onclick = "if ( confirm(\'' . $message . '\' ) ) { execute(); return true; } return false;" />' . $link . '</a>';
		
		echo ct_sanitize_output( $before . $htmllink . $after );
		
	}
}

/*-----------------------------------------------------------------------------------*/
/* Submit Listing from Front End */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_submit_listing')) {
	function ct_submit_listing() {
		global $ct_options;

		$view = $ct_options['ct_view'];
		$ct_auto_publish = $ct_options['ct_auto_publish'];

		if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

			if(trim($_POST['postTitle']) === '') {
				$postTitleError = 'Please enter an address.';
				$hasError = true;
			} else {
				$postTitle = trim($_POST['postTitle']);
			}

			$post_information = array(
			    'post_title' => wp_strip_all_tags( $_POST['postTitle'] ),
			    'post_content' => $_POST['postContent'],
			    'post_type' => 'listings',
			    'post_status' => $ct_auto_publish
			);

			$post_id = wp_insert_post($post_information);

			if($_FILES) {
				foreach ($_FILES as $file => $array) {
					$newupload = ct_insert_attachment($file,$post_id);
				}
			}

			if($post_id) {

				// Update Custom Meta
				update_post_meta($post_id, '_ct_listing_alt_title', esc_attr(strip_tags($_POST['customMetaAltTitle'])));
		        update_post_meta($post_id, '_ct_price', esc_attr(strip_tags($_POST['customMetaPrice'])));
				update_post_meta($post_id, '_ct_price_prefix', esc_attr(strip_tags($_POST['customMetaPricePrefix'])));
				update_post_meta($post_id, '_ct_price_postfix', esc_attr(strip_tags($_POST['customMetaPricePostfix'])));
				update_post_meta($post_id, '_ct_sqft', esc_attr(strip_tags($_POST['customMetaSqFt'])));
				update_post_meta($post_id, '_ct_video', esc_attr(strip_tags($_POST['customMetaVideoURL'])));
		        update_post_meta($post_id, '_ct_mls', esc_attr(strip_tags($_POST['customMetaMLS'])));
		        update_post_meta($post_id, '_ct_rental_guests', esc_attr(strip_tags($_POST['customMetaMaxGuests'])));
		        update_post_meta($post_id, '_ct_rental_min_stay', esc_attr(strip_tags($_POST['customMetaMinStay'])));
		        update_post_meta($post_id, '_ct_rental_checkin', esc_attr(strip_tags($_POST['customMetaCheckIn'])));
		        update_post_meta($post_id, '_ct_rental_checkout', esc_attr(strip_tags($_POST['customMetaCheckOut'])));
		        update_post_meta($post_id, '_ct_rental_extra_people', esc_attr(strip_tags($_POST['customMetaExtraPerson'])));
		        update_post_meta($post_id, '_ct_rental_cleaning', esc_attr(strip_tags($_POST['customMetaCleaningFee'])));
		        update_post_meta($post_id, '_ct_rental_cancellation', esc_attr(strip_tags($_POST['customMetaCancellationFee'])));
		        update_post_meta($post_id, '_ct_rental_deposit', esc_attr(strip_tags($_POST['customMetaSecurityDeposit'])));

				// Update Custom Taxonomies
				wp_set_post_terms($post_id,array($_POST['ct_property_type']),'property_type',true);
				wp_set_post_terms($post_id,array($_POST['customTaxBeds']),'beds',true);
				wp_set_post_terms($post_id,array($_POST['customTaxBaths']),'baths',true);
				wp_set_post_terms($post_id,array($_POST['ct_ct_status']),'ct_status',true);
				wp_set_post_terms($post_id,array($_POST['customTaxCity']),'city',true);
				wp_set_post_terms($post_id,array($_POST['customTaxState']),'state',true);
				wp_set_post_terms($post_id,array($_POST['customTaxZip']),'zipcode',true);
				wp_set_post_terms($post_id,array($_POST['customTaxFeat']),'additional_features',true);

				// Redirect
				$the_page_url = home_url('/?page_id=' . $view);
				wp_redirect( $the_page_url ); exit;
			}
		}

	}
}

/*-----------------------------------------------------------------------------------*/
/* Login & Registration */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_registration_form')) {
	function ct_registration_form() {
		global $ct_options;

		// Only show the registration form to non-logged-in members
		if(!is_user_logged_in()) {

			// Check to make sure user registration is enabled
			$registration_enabled = get_option('users_can_register');
			$ct_enable_front_end_registration = isset( $ct_options['ct_enable_front_end_registration'] ) ? esc_html( $ct_options['ct_enable_front_end_registration'] ) : '';

			// Only show the registration form if allowed
			if($ct_enable_front_end_registration != 'no') {
				$output = ct_registration_form_fields();
			} else {
				$output = '<p class="no-registration">' . __('User registration is not enabled', 'contempo') . '</p>';
			}
			return $output;
		}
	}
}

// User login form
if(!function_exists('ct_login_form')) {
	function ct_login_form() {

		if(!is_user_logged_in()) {
			$output = ct_login_form_fields();
		} else {
			// Could show some logged in user info here
			$output = '<!-- logged in -->';
		}
		return $output;
	}
}

// Login Form Fields
if(!function_exists('ct_login_form_fields')) {
	function ct_login_form_fields() {

		global $ct_options;

		$ct_enable_front_end_registration = isset( $ct_options['ct_enable_front_end_registration'] ) ? esc_html( $ct_options['ct_enable_front_end_registration'] ) : '';
		$ct_login_redirect_page_or_url = isset( $ct_options['ct_login_redirect_page_or_url'] ) ? esc_attr( $ct_options['ct_login_redirect_page_or_url'] ) : '';

		ob_start(); ?>
			<h4 class="marB20"><?php esc_html_e('Log in', 'contempo'); ?></h4>
			<?php if($ct_enable_front_end_registration != 'no') { ?>
				<p class="muted marB20"><?php esc_html_e('Don\'t have an account?', 'contempo'); ?> <span class="ct-registration"><?php esc_html_e('Create your account,', 'contempo'); ?></span> <?php esc_html_e('it takes less than a minute.', 'contempo'); ?></p>
			<?php } ?>
				<div class="clear"></div>

			<div id="ct_account_errors">
				<?php ct_show_error_messages(); ?>
			</div>

			<form id="ct_login_form"  class="ct_form" action="" method="post">
				<fieldset>
					<label for="ct_user_Login"><?php esc_html_e('Username', 'contempo'); ?></label>
					<input name="ct_user_login" id="ct_user_login" class="required" type="text" required />

					<label for="ct_user_pass"><?php esc_html_e('Password', 'contempo'); ?></label>
					<input name="ct_user_pass" id="ct_user_pass" class="required" type="password" required />

						<div class="clear"></div>
					<input type="hidden" name="ct_login_nonce" value="<?php echo wp_create_nonce('ct-login-nonce'); ?>"/>
					<input type="hidden" name="ct_current_url" value="<?php echo ct_get_server_info('REQUEST_URI'); ?>">
					<input type="hidden" name="action" value="ct_login_member_ajax"/>
					<button data-verification="google-recaptcha-v3" class="btn marT10" id="ct_login_submit" type="submit" value="Login"><i id="login-register-progress" class="fas fa-circle-notch fa-spin fa-fw"></i><?php _e('Login', 'contempo'); ?></button>
					
					<?php ct_render_google_recaptcha_v3_script(); ?>

				</fieldset>
			</form>
			<?php
				if(function_exists('mo_openid_initialize_social_login')) {
					echo do_shortcode('[miniorange_social_login theme="default"]');
				} elseif(function_exists('wsl_install')) {
					do_action('wordpress_social_login');
				}
			?>
			<p class="marB0"><small><span class="muted ct-lost-password" title="Lost your password?"><?php _e('Lost your password?', 'contempo'); ?></span></small></p>
		<?php
		return ob_get_clean();
	}
}

// Lost Password Fields
if(!function_exists('ct_lost_password_fields')) {
	function ct_lost_password_fields() { ?>
		<h4 class="marB20"><?php esc_html_e('Lost Password?', 'contempo'); ?></h4>
		<p class="muted"><?php _e('Enter your email address and we\'ll send you a link you can use to pick a new password.', 'contempo'); ?></p>
		<form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
            <label for="user_login"><?php _e( 'Username or Email', 'contempo' ); ?></label>
            <input type="text" name="user_login" id="user_login">
            <input type="submit" name="user-submit" class="btn marT10" value="<?php _e( 'Get New Password', 'contempo' ); ?>"/>
	    </form>
	<?php }
}

if(!function_exists('ct_password_lost')) {
	function ct_password_lost() {
		if('POST' == ct_get_server_info('REQUEST_METHOD') ) {
		    $errors = retrieve_password();
		    if(is_wp_error( $errors )) {
		        // Errors found
		        $redirect_url = home_url();
		        $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
		    } else {
		        // Email sent
		        $redirect_url = home_url();
		        $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
		    }

		    wp_redirect( $redirect_url );
		    exit;
		}
	}
}

// Registration form fields
if(!function_exists('ct_registration_form_fields')) {
	function ct_registration_form_fields() {
		global $ct_options;

		$ct_enable_front_end = isset( $ct_options['ct_enable_front_end'] ) ? esc_attr( $ct_options['ct_enable_front_end'] ) : '';
		$ct_registration_terms_conditions_page = isset( $ct_options['ct_registration_terms_conditions_page'] ) ? esc_attr( $ct_options['ct_registration_terms_conditions_page'] ) : '';

		ob_start(); ?>
			<h4 class="marB20"><?php esc_html_e('Create an account', 'contempo'); ?></h4>
			<p class="muted marB20"><?php esc_html_e('It takes less than a minute. If you already have an account ', 'contempo'); ?><span class="ct-login"><?php esc_html_e('login', 'contempo'); ?></span>.</p>

			<div id="ct_account_errors">
				<?php ct_show_error_messages(); ?>
			</div>

			<div id="ct_account_register_errors"></div>

			<?php do_action('before_registration_form'); ?>

			<form id="ct_registration_form" class="ct_form" action="" method="POST">
				<fieldset>
					<div id="register_user_login">
						<label for="ct_user_Login"><?php esc_html_e('Username', 'contempo'); ?><span class="ct-required">*</span></label>
						<input name="ct_user_login" id="ct_user_login" class="required" type="text"/>
					</div>

					<div id="register_user_email">
						<label for="ct_user_email"><?php esc_html_e('Email', 'contempo'); ?><span class="ct-required">*</span></label>
						<input name="ct_user_email" id="ct_user_email" class="required" type="email"/>
					</div>

					<div id="register_user_firstname" class="col span_6 first">
						<label for="ct_user_first"><?php esc_html_e('First Name', 'contempo'); ?></label>
						<input name="ct_user_first" id="ct_user_first" type="text"/>
					</div>

					<div id="register_user_lastname" class="col span_6">
						<label for="ct_user_last"><?php esc_html_e('Last Name', 'contempo'); ?></label>
						<input name="ct_user_last" id="ct_user_last" type="text"/>
					</div>

					<div id="register_user_mobile" class="col span_12 first">
						<label for="ct_user_mobile"><?php esc_html_e('Mobile', 'contempo'); ?></label>
						<input name="ct_user_mobile" id="ct_user_mobile" type="tel"/>
					</div>

					<?php if($ct_enable_front_end == 'yes') { ?>
					<div id="register_user_role" class="col span_12 first">
						<label for="ct_user_role"><?php esc_html_e('Buyer, Seller or Agent?', 'contempo'); ?></label>
						<select name="ct_user_role" id="ct_user_role">
							<option value=""><?php _e('Please choose', 'contempo'); ?></option>
							<option value="buyer"><?php _e('Buyer', 'contempo'); ?></option>
							<option value="seller"><?php _e('Seller', 'contempo'); ?></option>
							<option value="agent"><?php _e('Agent', 'contempo'); ?></option>
						</select>
					</div>
					<?php } ?>

					<div id="register_user_password" class="col span_6 first">
						<label for="password"><?php esc_html_e('Password', 'contempo'); ?><span class="ct-required">*</span></label>
						<input name="ct_user_pass" id="password" class="required" type="password"/>
					</div>

					<div id="register_user_password_confirm" class="col span_6">
						<label for="password_again"><?php esc_html_e('Password Again', 'contempo'); ?><span class="ct-required">*</span></label>
						<input name="ct_user_pass_confirm" id="password_again" class="required" type="password"/>
					</div>

					<?php if(!empty($ct_registration_terms_conditions_page)) { ?>
					<div id="register_user_terms" class="col span_12 marB20">
						<input id="ct_user_terms" class="marR10" name="ct_user_terms" type="checkbox" /><small><span class="muted"><?php _e('I accept the', 'contempo'); ?></span> <a href="<?php echo get_page_link($ct_registration_terms_conditions_page); ?>" target="_blank"><?php _e('Terms &amp; Conditions', 'contempo'); ?></a></small>
					</div>
					<?php } ?>

					<input type="hidden" name="ct_register_nonce" value="<?php echo wp_create_nonce('ct-register-nonce'); ?>"/>
					<button id="ct_register_submit" class="btn marT10" type="submit" value="<?php esc_html_e('Register', 'contempo'); ?>"><i id="register-progress" class="fas fa-circle-notch fa-spin fa-fw"></i><?php esc_html_e('Register', 'contempo'); ?></i></button>
				</fieldset>
			</form>

			<?php do_action('after_registration_form'); ?>
		<?php
		$content = ob_get_clean();
		$content = apply_filters( 'ct_registration_form_fields', $content );

		return $content;
	}
}

// Register a new user
if(!function_exists('ct_add_new_member')) {

    add_action('wp_ajax_nopriv_ct_add_new_member', 'ct_add_new_member');

	function ct_add_new_member() {
		global $ct_options;

		$ct_registration_redirect_page = isset( $ct_options['ct_registration_redirect_page'] ) ? esc_attr( $ct_options['ct_registration_redirect_page'] ) : '';
		$ct_registration_terms_conditions_page = isset( $ct_options['ct_registration_terms_conditions_page'] ) ? esc_attr( $ct_options['ct_registration_terms_conditions_page'] ) : '';
		$ct_registered_user_role = isset( $ct_options['ct_registered_user_role'] ) ? esc_attr( $ct_options['ct_registered_user_role'] ) : 'contributor';

	  	if(isset( $_POST['ct_user_login'] ) && wp_verify_nonce($_POST['ct_register_nonce'], 'ct-register-nonce')) {

			$user_login		= $_POST['ct_user_login'];
			$user_email		= $_POST['ct_user_email'];
			$user_first 	= $_POST['ct_user_first'];
			$user_last	 	= $_POST['ct_user_last'];
			$user_mobile	= $_POST['ct_user_mobile'];
			$user_role   	= $_POST['ct_user_role'];
			$user_pass		= $_POST['ct_user_pass'];
			$pass_confirm 	= $_POST['ct_user_pass_confirm'];
			$user_terms     = $_POST['ct_user_terms'];

			// This is required for username checks
			require_once(ABSPATH . WPINC . '/registration.php');

			if(username_exists($user_login)) {
				// Username already registered
				ct_errors()->add('username_unavailable', __('Username already taken', 'contempo'));
			}
			if(!validate_username($user_login)) {
				// Invalid username
				ct_errors()->add('username_invalid', __('Invalid username', 'contempo'));
			}
			if($user_login == '') {
				// Empty username
				ct_errors()->add('username_empty', __('Please enter a username', 'contempo'));
			}
			if(!is_email($user_email)) {
				// Invalid email
				ct_errors()->add('email_invalid', __('Invalid email', 'contempo'));
			}
			if(email_exists($user_email)) {
				// Email address already registered
				ct_errors()->add('email_used', __('Email already registered', 'contempo'));
			}
			if($user_pass == '') {
				// Passwords do not match
				ct_errors()->add('password_empty', __('Please enter a password', 'contempo'));
			}
			if($user_pass != $pass_confirm) {
				// Passwords do not match
				ct_errors()->add('password_mismatch', __('Passwords do not match', 'contempo'));
			}

			if($ct_registration_terms_conditions_page != '' && $user_terms == '') {
				ct_errors()->add('user_terms_empty', __('Terms & Conditions must be checked', 'contempo'));
			}

			$errors = ct_errors()->get_error_messages();

			if(!empty ( $errors )) {
			    // Send errors back to client in form of JSON
			    wp_send_json(array(
			            'success' => false,
			            'errors' => $errors,
			            'redirect' => false
			        ));
			    die;
			} else {
			    if( empty( $user_role ) ) {
			        // If user role is empty, try pulling the one set in theme options
			        $user_role = $ct_registered_user_role;
			    }
			    // No errors
			    $new_user_id = wp_insert_user(array(
			            'user_login'		=> $user_login,
                        'user_pass'	 		=> $user_pass,
                        'user_email'		=> $user_email,
                        'first_name'		=> $user_first,
                        'last_name'			=> $user_last,
                        'user_registered'	=> date('Y-m-d H:i:s'),
                        'role'				=> $user_role
                    )
                );

			    if( $new_user_id ) {
                    // Get the user object.
				    $user = get_user_by( 'login', $user_login );

			        // Send an email to the admin alerting them of the registration
					wp_new_user_notification($new_user_id);

					// Update User Meta
					update_user_meta( $new_user_id, 'mobile', $user_mobile );
    				
    				// Log the new user in
					wp_setcookie($user_login, $user_pass, true);

					wp_set_current_user($new_user_id, $user_login);

					// Check if the user object is valid and is not empty
					if ( is_object( $user ) && ! empty( $user ) ) {

                        // Pass the $user object to 'wp_login' action
					    do_action('wp_login', $user_login, $user);

					}

					$redirect_url = get_home_url();

                    if( ! empty( $ct_registration_redirect_page ) ) {
					    $redirect_url = get_permalink( $ct_registration_redirect_page );
					}

                    // Send success back to client
                     wp_send_json(array(
			            'success' => true,
			            'errors' => false,
			            'redirect' => $redirect_url
			        ));
                    die;
			    } else {
			        // Send error back to client
                     wp_send_json(array(
			            'success' => false,
			            'errors' => array(
			                    __('There was an error while trying to create your account', 'contempo')
			                ),
			            'redirect' => false
			        ));
			    }
			}
	  	}
	  	die();
	}
}

// Logs a member in after submitting a form
if(!function_exists('ct_login_member')) {
	function ct_login_member() {

	    // Disable the call of this function if we pass the parameters via ajax, this is to prevent conflict
	    if ( wp_doing_ajax() ) {
	        return;
	    }

		global $ct_options;

		$ct_login_redirect_page_or_url = isset( $ct_options['ct_login_redirect_page_or_url'] ) ? esc_attr( $ct_options['ct_login_redirect_page_or_url'] ) : '';
		$ct_login_redirect_page = isset( $ct_options['ct_login_redirect_page'] ) ? esc_attr( $ct_options['ct_login_redirect_page'] ) : '';

		if(isset($_POST['ct_user_login']) && wp_verify_nonce($_POST['ct_login_nonce'], 'ct-login-nonce')) {

			// This returns the user ID and other info from the user name
			$user = get_userdatabylogin($_POST['ct_user_login']);

			if(!$user) {
				// If the user name doesn't exist
				ct_errors()->add('empty_username', __('Invalid username', 'contempo'));
			}

			if(!isset($_POST['ct_user_pass']) || $_POST['ct_user_pass'] == '') {
				// If no password was entered
				ct_errors()->add('empty_password', __('Please enter a password', 'contempo'));
			}

			// Check the user's login with their password
			if(!wp_check_password($_POST['ct_user_pass'], $user->user_pass, $user->ID)) {
				// If the password is incorrect for the specified user
				ct_errors()->add('empty_password', __('Incorrect password', 'contempo'));
			}

			// Retrieve all error messages
			$errors = ct_errors()->get_error_messages();

			// Only log the user in if there are no errors
			if(empty($errors)) {

				wp_setcookie($_POST['ct_user_login'], $_POST['ct_user_pass'], true);
				wp_set_current_user($user->ID, $_POST['ct_user_login']);
				do_action('wp_login', $_POST['ct_user_login']);

				if($ct_login_redirect_page_or_url == 'current_url') {
					$current_url = html_entity_decode($_POST['ct_current_url']);
					wp_redirect( $current_url ); exit;
				} elseif($ct_login_redirect_page_or_url == 'page') {
					$the_page_id = $ct_login_redirect_page;
					$page_url = get_permalink( $the_page_id );
					wp_redirect( $page_url ); exit;
				} else {
					wp_redirect(home_url()); exit;
				}

			}
		}
	}
}
add_action('init', 'ct_login_member');

if(!function_exists('ct_login_member_ajax')) {
	function ct_login_member_ajax() {
	    global $ct_options;

		if(isset($_POST['ct_user_login']) && wp_verify_nonce($_POST['ct_login_nonce'], 'ct-login-nonce')) {

			// This returns the user ID and other info from the user name
			$user = get_userdatabylogin($_POST['ct_user_login']);

			if( ! $user ) {
				// If the user name doesn't exist
				ct_errors()->add('empty_username', __('Invalid username', 'contempo'));
			}

			if(!isset($_POST['ct_user_pass']) || $_POST['ct_user_pass'] == '') {
				// If no password was entered
				ct_errors()->add('empty_password', __('Please enter a password', 'contempo'));
			}

			// Check the user's login with their password
			if(!wp_check_password($_POST['ct_user_pass'], $user->user_pass, $user->ID)) {
				// If the password is incorrect for the specified user
				ct_errors()->add('empty_password', __('Incorrect password', 'contempo'));
			}

			// Retrieve all error messages
			$errors = ct_errors()->get_error_messages();

			// Only log the user in if there are no errors
			if(empty($errors)) {

				$ct_login_redirect_page_or_url = isset( $ct_options['ct_login_redirect_page_or_url'] ) ? esc_attr( $ct_options['ct_login_redirect_page_or_url'] ) : '';
			    $ct_login_redirect_page = isset( $ct_options['ct_login_redirect_page'] ) ? esc_attr( $ct_options['ct_login_redirect_page'] ) : '';

				$creds = array();

				$creds['user_login'] = $_POST['ct_user_login'];

				$creds['user_password'] = $_POST['ct_user_pass'];

				$creds['remember'] = true;

				if($ct_login_redirect_page_or_url == 'current_url') {
					$url = home_url( urldecode( $_POST['ct_current_url'] ) );
				} elseif ($ct_login_redirect_page_or_url == 'page') {
					$url = get_permalink( $ct_login_redirect_page );
				} else {
					$url = get_home_url();
				}

                // Logged the user
				$user = wp_signon( $creds, false );
				// Send good response back to client for reading
				wp_send_json(array('success'=>true, 'redirect'=> $url ));

			} else {
				wp_send_json(array('success'=>false, 'errors'=>ct_get_errors()));
			}
		} else {
			wp_send_json(array('success'=>false, 'errors'=>array('Invalid session')));
		}
	}
}
add_action( 'wp_ajax_nopriv_ct_login_member_ajax', 'ct_login_member_ajax' );

// Displays error messages from form submissions
if(!function_exists('ct_show_error_messages')) {
	function ct_show_error_messages() {
		if($codes = ct_errors()->get_error_codes()) {
			echo '<div class="ct_errors">';
			    // Loop error codes and display errors
			   foreach($codes as $code){
			        $message = ct_errors()->get_error_message($code);
			        echo '<span class="error"><strong>' . __('Error', 'contempo') . '</strong>: ' . $message . '</span><br/>';
			    }
			echo '</div>';
		}
	}
}

if(!function_exists('ct_get_errors')) {
	function ct_get_errors() {
		$errors = array();
		if($codes = ct_errors()->get_error_codes()) {
		    // Loop error codes and display errors
		   foreach($codes as $code){
		        $message = ct_errors()->get_error_message($code);
		        array_push($errors, $message);
		    }
		}
		return $errors;
	}
}

// Used for tracking error messages
if(!function_exists('ct_errors')) {
	function ct_errors(){
	    static $wp_error; // Will hold global variable safely
	    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
	}
}

/*-----------------------------------------------------------------------------------*/
/* New User Admin Email */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_wp_new_user_admin_notification_email')) {
	function ct_wp_new_user_admin_notification_email( $wp_new_user_notification_email_admin, $user, $blogname ) {
	    $wp_new_user_notification_email_admin['subject'] = sprintf( '[%s] New user %s registered.', $blogname, $user->user_email );
	    $wp_new_user_notification_email_admin['message'] = sprintf( "%s %s - %s has registered to your site %s, %s", $user->first_name, $user->last_name, $user->user_email, $blogname, site_url() );
	    return $wp_new_user_notification_email_admin;
	}
}
add_filter( 'wp_new_user_notification_email_admin', 'ct_wp_new_user_admin_notification_email', 10, 3 );

/*-----------------------------------------------------------------------------------*/
/* New User Welcome Email */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_wp_new_user_notification_email')) {
	function ct_wp_new_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {
	    $user_login = stripslashes( $user->user_login );
	    $user_email = stripslashes( $user->user_email );
	    $user_first = stripslashes( $user->user_first );
	    $login_url  = site_url();

	    $message  = sprintf( __( 'Hi %s,', 'contempo' ), $user_first ) . '<br><br>';
	    $message .= sprintf( __( "Welcome to %s! Here's how to log in:", "contempo" ), get_option('blogname') ) . '<br><br>';
	    $message .= wp_login_url() . '<br>';
	    $message .= sprintf( __('Username: %s', 'contempo'), $user_login ) . '<br>';
	    $message .= sprintf( __('Email: %s', 'contempo'), $user_email ) . '<br>';

		if( isset( $wp_new_user_notification_email['regenerate_password'] ) && $wp_new_user_notification_email['regenerate_password'] ) {
			$password = \wp_generate_password( 12, true );
			wp_set_password($password, $user->ID);
			$message .= __( 'Password: ', 'contempo' ) . $password . '<br><br>';
		} else {
			$message .= __( 'Password: The one you entered in the registration form. (For security, we save encrypted passwords)', 'contempo' ) . '<br><br>';
		}
	    $message .= sprintf( __('If you have any problems, please contact us at %s.', 'contempo'), get_option('admin_email') ) . '<br><br>';
	 
	    $wp_new_user_notification_email['subject'] = sprintf( '[%s] Your credentials.', $blogname );
	    $wp_new_user_notification_email['headers'] = array('Content-Type: text/html; charset=UTF-8');
	    $wp_new_user_notification_email['message'] = $message;
	 
	    return $wp_new_user_notification_email;
	}
}
add_filter( 'wp_new_user_notification_email', 'ct_wp_new_user_notification_email', 10, 3 );

/*-----------------------------------------------------------------------------------*/
/* Delete User Front End */
/*-----------------------------------------------------------------------------------*/

function ct_delete_user() {
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'ct_delete_user') {
		include( './wp-admin/includes/user.php' );

		$current_user_id = wp_get_current_user();
		$user_id = intval($_REQUEST['user_id']);

		if($current_user_id->ID == $user_id) {
			wp_delete_user($user_id);
			wp_redirect(home_url());
			exit();
		} else {
			wp_redirect(home_url());
		}
	}
}
add_action('init', 'ct_delete_user');

/*-----------------------------------------------------------------------------------*/
/* MailChimp CURL Connect */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_mailchimp_curl_connect')) {
	function ct_mailchimp_curl_connect( $url, $request_type, $api_key, $data = array() ) {

		if(class_exists('CT_RealEstate7_Helper')) {
			return CT_RealEstate7_Helper::mailchimp_curl_connect( $url, $request_type, $api_key, $data = array() );
		}
		
		return '';
	}
}

/*-----------------------------------------------------------------------------------*/
/* MailChimp Subscribe/Unsubscribe Users */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_mailchimp_subscribe_unsubscribe')) {
	function ct_mailchimp_subscribe_unsubscribe( $email, $status, $merge_fields = array( 'FNAME' => '', 'LNAME' => '' ) ){
		global $ct_options;

		$ct_mailchimp_apikey = isset( $ct_options['ct_mailchimp_apikey'] ) ? esc_attr( $ct_options['ct_mailchimp_apikey'] ) : '';
		$ct_mailchimp_list_id = isset( $ct_options['ct_mailchimp_list_id'] ) ? esc_attr( $ct_options['ct_mailchimp_list_id'] ) : '';

		// MailChimp API URL
		$url = 'https://' . substr($ct_mailchimp_apikey,strpos($ct_mailchimp_apikey,'-')+1) . '.api.mailchimp.com/3.0/lists/' . $ct_mailchimp_list_id . '/members/' . md5(strtolower($email));
		
		// MailChimp POST data
		$data = array(
			'apikey'        => $ct_mailchimp_apikey,
	    	'email_address' => $email,
			'status'        => $status, // In this post we will use only 'subscribed' and 'unsubscribed'
			'merge_fields'  => $merge_fields // In this post we will use only FNAME and LNAME
		);
		return json_decode( ct_mailchimp_curl_connect( $url, 'PUT', $ct_mailchimp_apikey, $data ) );
	}
}

if(!function_exists('ct_user_register_hook')) {
	function ct_user_register_hook( $user_id ){

		global $ct_options;

		$ct_enable_mailchimp = isset( $ct_options['ct_enable_mailchimp'] ) ? esc_attr( $ct_options['ct_enable_mailchimp'] ) : '';

		$user = get_user_by('id', $user_id ); // Feel free to use get_userdata() instead

	 	if($ct_enable_mailchimp == 'yes') {
			$subscription = ct_mailchimp_subscribe_unsubscribe( $user->user_email, 'subscribed', array( 'FNAME' => $user->first_name,'LNAME' => $user->last_name ) );
		}

		// If user subscription was failed you can try to store the errors the following way 
		if( isset( $subscription->status ) && $subscription->status != 'subscribed' ) {
			update_user_meta( $user_id, '_subscription_error', 'User was not subscribed because:' . $subscription->detail );
		}

	}
}
add_action('user_register', 'ct_user_register_hook', 20, 1 );

function ct_user_delete_hook( $user_id ){
	$user = get_user_by( 'id', $user_id );
	$subscription = ct_mailchimp_subscribe_unsubscribe( $user->user_email, 'unsubscribed', array( 'FNAME' => $user->first_name,'LNAME' => $user->last_name ) );
}
add_action( 'delete_user', 'ct_user_delete_hook', 20, 1 );

/*-----------------------------------------------------------------------------------*/
/* WPML Flags */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_language_selector_flags')) {
	function ct_language_selector_flags(){
	    $languages = icl_get_languages('skip_missing=0&orderby=code');
	    if(!empty($languages)){
			echo '<ul>';
				foreach($languages as $l){
					echo '<li>';
						if(!$l['active']) {echo '<a href="'.$l['url'].'">';}
							echo '<img src="'.$l['country_flag_url'].'" height="12" alt="'.$l['language_code'].'" width="18" />';
						if(!$l['active']) {echo '</a>';}
					echo '</li>';
				}
			echo '</ul>';
	    }
	}
}

/*-----------------------------------------------------------------------------------*/
/* WPML Lang */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_language_selector_lang')) {
	function ct_language_selector_lang(){
	    $languages = icl_get_languages('skip_missing=0&orderby=code');
	    if(!empty($languages)){
			echo '<ul>';
				foreach($languages as $l){
					echo '<li>';
						if(!$l['active']) {echo '<a href="'.$l['url'].'">';}
							echo esc_html($l['language_code']);
						if(!$l['active']) {echo '</a>';}
					echo '</li>';
				}
			echo '</ul>';
	    }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Only Show Posts in Blog Search */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_only_show_posts_in_default_blog_search')) {
	function ct_only_show_posts_in_default_blog_search( $query ) {
		if(!$query->is_admin && $query->is_search && $query->is_main_query()) {
			$query->set( 'post_type', array( 'post' ) );
		}
	}
}
add_action( 'pre_get_posts', 'ct_only_show_posts_in_default_blog_search' );

/*-----------------------------------------------------------------------------------*/
/* Only Show Posts in Taxonomy Archive */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_only_show_posts_in_tax_archive')) {
	function ct_only_show_posts_in_tax_archive( $query ) {
		if(!$query->is_admin && $query->is_tax && $query->is_main_query()) {
			$query->set( 'post_type', array( 'post' ) );
		}
	}
}
add_action( 'pre_get_posts', 'ct_only_show_posts_in_tax_archive', 999 );

/*-----------------------------------------------------------------------------------*/
/* Disable Gutenberg for Everything Except Posts */
/*-----------------------------------------------------------------------------------*/

add_filter('use_block_editor_for_page', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);

/*-----------------------------------------------------------------------------------*/
/* Deregister Visual Composer Flexslider CSS */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_remove_vc_styles')) {
	function ct_remove_vc_styles() {
	    wp_deregister_style( 'flexslider' );
	}
}
add_action( 'wp_enqueue_scripts', 'ct_remove_vc_styles', 99 );

/*-----------------------------------------------------------------------------------*/
/* Memberhip & Packages — Package Status */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_package_status')) {
	function ct_package_status() {
		global $post;
		global $wpdb;

		$uid = $post->post_author;
		$today = strtotime(date('Y-m-d'));
		$ct_user_listings_count = ct_listing_post_count($post->post_author, 'listings');

		$events = $wpdb->get_results ("SELECT * FROM ".$wpdb->prefix."posts WHERE post_author = $uid AND post_type = 'package_order' order by id" );

		foreach($events as $data){
			$post_id = $data->ID;
		}

		$package_orders = array(
			'post_type' => 'package_order',
			'post_status' => 'any',
			'posts_per_page' => -1,
			'author' =>  $uid
		);

	    $package_order_loop = new WP_Query($package_orders);

		$post_listing = array(
			'post_type' => 'listings',
			'post_status' => 'any',
			'posts_per_page' => -1,
			'author' =>  $uid
		);

	    $post_loop = new WP_Query($post_listing);

		$post_listing_count = $post_loop->post_count;

		// Featured listings
		$featured_listing = array(
			'post_type' => 'listings',
			'post_status' => 'any',
			'posts_per_page' => -1,
			'author' =>  $uid,
			'tax_query' => array(
				array(
					'taxonomy' => 'ct_status',
					'field' => 'slug',
					'terms' => 'featured'
				)
			)
		);

	    $loop = new WP_Query($featured_listing);

		$featured_listing_count = $loop->post_count;

		if(empty($post_id)) {
			echo '<h4 id="no-package" class="marB20">' . esc_html('User hasn\'t chosen a package yet…', 'contempo') . '</h4>';
		}

		if(!empty($package_order_loop)) {
			$purchased_date = get_post_meta($post_id,'package_current_date',true);
			$expiration_date = get_post_meta($post_id,'package_expire_date',true);
			$post_meta_id = get_post_meta($post_id,'packageID',true);
			$post_data = get_post($post_meta_id);
			$package_name = $post_data->post_title;
			$package_id = $post_data->ID;
			$listing_included = get_post_meta($package_id,'listing',true);
			$listing_remaining = $listing_included - $post_listing_count;
			$featured_listing = get_post_meta($package_id,'featured_listing',true);
			$featured_listing_remaining = $featured_listing - $featured_listing_count;

			if($post_listing_count >= $listing_included) {
				echo '<div id="package-limit-reached" class="package-status">' . __('Limit Reached', 'contempo') . '</div>';
			}

			if($today >= strtotime($expiration_date) && !empty($expiration_date)) {
				echo '<div id="package-expired" class="package-status">' . __('Expired', 'contempo') . '</div>';
			}

			if(empty($expiration_date)) {
				echo '<h4 class="marB20">' . __('User hasn\'t chosen a package yet…', 'contempo') . '</h4>';
			}

			if(!empty($expiration_date)) {
		        echo '<h3 class="marT0 marB0">' . __('Current Package Details', 'contempo') . '</h3>';
				echo '<p class="muted">' . __('Overview of users existing membership & package information.', 'contempo') . '</p>';

				echo '<div id="package-active" class="package-status">' . __('Active', 'contempo') . '</div>';

				echo '<ul id="membership-package-information">';
					echo '<li id="package-name" class="clr"><strong>' . __('Package Name:', 'contempo') . ' ' . esc_html($package_name) . '</li>';
					echo '<li id="listings-remaining" class="clr muted"><span class="left"><strong>' . __('Listings Remaining:', 'contempo') . '</strong></span> <span class="right">' . esc_html($listing_remaining) . '</span></li>';
					echo '<li id="featured-remaining" class="clr muted"><span class="left"><strong>' . __('Featured Listings Remaining:', 'contempo') . '</strong></span> <span class="right">' . esc_html($featured_listing_remaining) . '</span></li>';
				echo '</ul>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Filter "Enter title here" with custom text */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_change_default_title')) {
	function ct_change_default_title($title){
	     $screen = get_current_screen();

	     if('listings' == $screen->post_type) {
	          $title = __('Enter the listing street address here', 'contempo');
	     }

	     return $title;
	}
}
add_filter( 'enter_title_here', 'ct_change_default_title' );

/*-----------------------------------------------------------------------------------*/
/* Add note under Listing Title */
/*-----------------------------------------------------------------------------------*/

function ct_edit_form_after_title() {
	$screen = get_current_screen();

    if('listings' == $screen->post_type) {
		echo '<p class="cmb2-metabox-description">' . __('NOTE: The Listing Title needs to be <strong>only the Street Address</strong> (e.g. 123 Somewhere St.) otherwise the mapping will not work properly, use the "Listing Alternate Title" field below for Listing Names, etc…the alternate title field will override the street address display on the front end of your site.', 'contempo') . '</p>';
	}

}
add_action( 'edit_form_after_title', 'ct_edit_form_after_title' );

/*-----------------------------------------------------------------------------------*/
/* Progress Bar for Front End Listing Submit & Edit */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listings_progress_bar')) {
	function ct_listings_progress_bar() {
		global $ct_options;

		$ct_submit_rental_info = isset( $ct_options['ct_submit_rental_info'] ) ? esc_attr( $ct_options['ct_submit_rental_info'] ) : '';
        $ct_rentals_booking = isset( $ct_options['ct_rentals_booking'] ) ? esc_html( $ct_options['ct_rentals_booking'] ) : '';

		echo '<ul id="progress-bar" class="col span_12 first">';
			echo '<li>' . __('Price & Description', 'contempo') . '</li>';
			echo '<li>' . __('Photos & Files', 'contempo') . '</li>';
			echo '<li>' . __('Details', 'contempo') . '</li>';
			if($ct_rentals_booking == 'yes' || class_exists('Booking_Calendar') && $ct_submit_rental_info == 'yes') {
				echo '<li>' . __('Rental Info', 'contempo') . '</li>';
			}
			echo '<li>' . __('Location', 'contempo') . '</li>';
			echo '<li>' . __('Private Notes', 'contempo') . '</li>';
		echo '</ul>';

	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Title */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_title')) {
	function ct_listing_title( $echo=true) {
		global $post;
		global $ct_options;

		$ct_listings_title_formatting = isset( $ct_options['ct_listings_title_formatting'] ) ? esc_html( $ct_options['ct_listings_title_formatting'] ) : '';
		$ct_rentals_booking = isset( $ct_options['ct_rentals_booking'] ) ? esc_html( $ct_options['ct_rentals_booking'] ) : '';

		$title = '';

		if($ct_rentals_booking == 'yes' || class_exists('Booking_Calendar')) {

			$listing_alt_title = get_post_meta($post->ID, '_ct_listing_alt_title', true);
			$rental_title = get_post_meta($post->ID, '_ct_rental_title', true);

			if(!empty($listing_alt_title)) {
			    $title = esc_html($listing_alt_title);
			} elseif(!empty($rental_title)) {
			    $title = esc_html($rental_title);
			} else {
				$title = get_the_title();
			}

		} else {

			$listing_alt_title = get_post_meta($post->ID, '_ct_listing_alt_title', true);

			if(!empty($listing_alt_title)) {
			    $title = esc_html($listing_alt_title);
			} else {
				$title = get_the_title();
			}
		}

		if($ct_listings_title_formatting == 'yes') {
			if($echo) {
				echo ct_sanitize_output($title);
			} else {
				return $title;
			}
		} else {
			if($echo) {
				echo ucwords(strtolower($title));
			} else {
				return ucwords(strtolower($title));
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Title */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_listing_title')) {
	function ct_elementor_listing_title( $echo=true) {
		global $ct_options;

		$attributes['is_elementor'] = 1;

		if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$ct_listings_title_formatting = isset( $ct_options['ct_listings_title_formatting'] ) ? esc_html( $ct_options['ct_listings_title_formatting'] ) : '';
		$ct_rentals_booking = isset( $ct_options['ct_rentals_booking'] ) ? esc_html( $ct_options['ct_rentals_booking'] ) : '';

		$title = '';

		if($ct_rentals_booking == 'yes' || class_exists('Booking_Calendar')) {

			$listing_alt_title = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_listing_alt_title", true);
			$rental_title = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_rental_title", true);

			if(!empty($listing_alt_title)) {
			    $title = esc_html($listing_alt_title);
			} elseif(!empty($rental_title)) {
			    $title = esc_html($rental_title);
			} else {
				$title = get_the_title();
			}

		} else {

			$listing_alt_title = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_listing_alt_title", true);

			if(!empty($listing_alt_title)) {
			    $title = esc_html($listing_alt_title);
			} else {
				$title = get_the_title();
			}
		}

		if($ct_listings_title_formatting == 'yes') {
			if($echo) {
				echo ct_sanitize_output($title);
			} else {
				return $title;
			}
		} else {
			if($echo) {
				echo ucwords(strtolower($title));
			} else {
				return ucwords(strtolower($title));
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Permalink */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_permalink')) {
	function ct_listing_permalink() {
		global $ct_options;

		$ct_listings_login_register = isset( $ct_options['ct_listings_login_register'] ) ? $ct_options['ct_listings_login_register'] : '';
		$ct_listing_single_new_tab = isset( $ct_options['ct_listing_single_new_tab'] ) ? $ct_options['ct_listing_single_new_tab'] : '';

		if($ct_listings_login_register == 'yes' && !is_user_logged_in()) {
			echo 'class="login-register"';
		} else {
			echo 'href="';
				the_permalink();
			echo '"';

			if($ct_listing_single_new_tab == 'yes') {
				echo ' target="_blank"';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Add meta_value_num parameter for User Query Orderby */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_pre_user_query')) {
	function ct_pre_user_query( &$query ) {
	    global $wpdb;

	    if( isset( $query->query_vars['orderby'] ) && 'meta_value_num' == $query->query_vars['orderby'] ) {
	    	$query->query_orderby = str_replace( 'user_login', '$wpdb->usermeta.meta_value+0', $query->query_orderby );
	    }
	}
}
add_action( 'pre_user_query', 'ct_pre_user_query' );

/*-----------------------------------------------------------------------------------*/
/* Favorite Listings */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_fav_listing')) {
	function ct_fav_listing() {
		global $ct_options;

		$ct_fav_only_reg_users = isset( $ct_options['ct_fav_only_reg_users'] ) ? esc_html( $ct_options['ct_fav_only_reg_users'] ) : '';

		if(function_exists('wpfp_link')) {

			if($ct_fav_only_reg_users == 'yes') {
				if(is_user_logged_in()) {
					echo '<span class="save-this" data-tooltip="' . __('Favorite', 'contempo') . '">';
						wpfp_link();
					echo '</span>';
				} else {
					echo '<span class="login-register save-this" data-tooltip="' . __('Favorite', 'contempo') . '">';
						echo '<a href="#"><i class="fa fa-heart-o"></i></a>';
					echo '</span>';
				}
			} else {
				echo '<span class="save-this" data-tooltip="' . __('Favorite', 'contempo') . '">';
					wpfp_link();
				echo '</span>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Favorite Listings - Text */
/*-----------------------------------------------------------------------------------*/

function __wpfp_link_text( $post_id ) {
	
	$saved = false;

	if(function_exists( 'ct_fp_check_favorited')) {
		$saved = ct_fp_check_favorited( $post_id );
	}

	if( empty($post_id) ) {
		return;
	}
	
	if(!$saved) {
		echo '<a method="add" id="ct-btn-fav" rel="' . esc_attr( $post_id ) . '" onClick="return false" href="#" title="' . __('Save', 'contempo') . '">';
			echo '<i class="fa fa-heart-o"></i>';
			echo __('Save', 'contempo');
		echo '</a>';
	} else {
		echo '<a method="remove" id="ct-btn-fav" rel="' . esc_attr( $post_id ) . '" onClick="return false" href="#" title="' . __('Saved', 'contempo') . '">';
			echo '<i class="fa fa-heart"></i>';
			echo __('Saved', 'contempo');
		echo '</a>';
	}
	return;
}

if(!function_exists('ct_fav_listing_btn')) {
	function ct_fav_listing_btn() {
		global $ct_options;

		$ct_fav_only_reg_users = isset( $ct_options['ct_fav_only_reg_users'] ) ? esc_html( $ct_options['ct_fav_only_reg_users'] ) : '';

		if(function_exists('wpfp_link_text')) {
			if($ct_fav_only_reg_users == 'yes') {
				if(is_user_logged_in()) {
					echo '<span class="save-this-btn btn">';
						__wpfp_link_text( get_the_ID() );
					echo '</span>';
				} else {
					echo '<span class="login-register save-this-btn">';
						echo '<a class="btn" href="#">' . __('Save', 'contempo') . '</a>';
					echo '</span>';
				}
			} else {
				echo '<span class="save-this-btn btn">';
					__wpfp_link_text( get_the_ID() );
				echo '</span>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Output All Favorite Listings Permalinks */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_fav_listings_permalinks')) {
	function ct_fav_listings_permalinks() {

		$favorite_post_ids = wpfp_get_users_favorites();

        if($favorite_post_ids) {
	        foreach($favorite_post_ids as $o) {
	            echo get_permalink($o) . ', ';
		    }
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Compare Listings */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_compare_listing')) {
	function ct_compare_listing() {
		if(class_exists('Redq_Alike')) {
			echo '<span class="compare-this" data-tooltip="' . __('Compare', 'contempo') . '">';
				echo do_shortcode('[alike_link vlaue="compare" show_icon="true" icon_class="fa fa-plus-square-o"]');
			echo '</span>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Views */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_get_listing_views')) {
	function ct_get_listing_views($postID){
	    
	    $count_key = 'post_views_count';
	    $count = get_post_meta($postID, $count_key, true);
	    
	    if($count == ''){
	        delete_post_meta($postID, $count_key);
	        add_post_meta($postID, $count_key, '0');
	        return "0";
	    }
	    return $count;
	}
}

if(!function_exists('ct_set_listing_views')) {
	function ct_set_listing_views($postID) {

		if(!isset($_SESSION)) {
	        session_start();
	    }

		$count_key = 'post_views_count';
		$count = get_post_meta($postID, $count_key, true);

		if($count == '') {
			$count = 0;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
		} else {
			if(!isset($_SESSION['post_views_count-'. $postID])){
				$_SESSION['post_views_count-'. $postID] = 'si';
				$count++;
				update_post_meta($postID, $count_key, $count);
			}
		}
	}
}
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

/*-----------------------------------------------------------------------------------*/
/* Listing Actions */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_actions')) {
	function ct_listing_actions() {
		global $post, $ct_options;

		$ct_listing_stats_on_off = isset( $ct_options['ct_listing_stats_on_off'] ) ? esc_attr( $ct_options['ct_listing_stats_on_off'] ) : '';

		// Count Total images
        $attachments = get_children(
            array(
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'post_parent' => get_the_ID()
            )
        );

        $img_count = count($attachments);

        $total_imgs = $img_count;

        if($ct_listing_stats_on_off == 'no') {
			echo '<ul class="listing-actions listing-views-off">';
		} else {
			echo '<ul class="listing-actions">';
		}

			if(get_post_meta($post->ID, 'source', true) != 'idx-api') {
				echo '<li>';
					if($total_imgs === 1) {
						echo '<span class="listing-images-count" data-tooltip="' . $total_imgs . __(' Photo','contempo') . '">';
					} else {
						echo '<span class="listing-images-count" data-tooltip="' . $total_imgs . __(' Photos','contempo') . '">';
					}
						echo '<i class="fa fa-image"></i>';
					echo '</span>';
				echo '</li>';
			}

			if(function_exists('wpfp_link')) {
				echo '<li class="listing-favorite">';
					ct_fav_listing();
				echo '</li>';
			}

			if(class_exists('Redq_Alike')) {
				echo '<li class="listing-compare">';
					ct_compare_listing();
				echo '</li>';
			}

			if(function_exists('ct_get_listing_views') && $ct_listing_stats_on_off != 'no') {
				echo '<li>';
					echo '<span class="listing-views" data-tooltip="' . ct_get_listing_views(get_the_ID()) . __(' Views','contempo') . '">';
						echo '<i class="fa fa-bar-chart"></i>';
					echo '</span>';
				echo '</li>';
			}

		echo '</ul>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Actions - Favorite Remove */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_actions_fav_remove')) {
	function ct_listing_actions_fav_remove() {
		global $post, $ct_options;

		$ct_listing_stats_on_off = isset( $ct_options['ct_listing_stats_on_off'] ) ? esc_attr( $ct_options['ct_listing_stats_on_off'] ) : '';

		// Count Total images
        $attachments = get_children(
            array(
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'post_parent' => get_the_ID()
            )
        );

        $img_count = count($attachments);

        $total_imgs = $img_count;

		echo '<ul class="listing-actions">';

			if(function_exists('wpfp_link')) {
				echo '<li>';
					echo '<span class="save-this" data-tooltip="' . __('Remove', 'contempo') . '">';
						wpfp_remove_favorite_link(get_the_ID());
					echo '</span>';
				echo '</li>';
			}

		echo '</ul>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Return Taxonomy */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_taxonomy_return')) {
	function ct_taxonomy_return( $name ){
		global $post;
		global $wp_query;
		
		if(taxonomy_exists($name)){
			$terms_as_text = strip_tags( get_the_term_list( $wp_query->post->ID, $name, '', ', ', '' ) );
			if($terms_as_text != '') {
				return esc_html($terms_as_text);
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Bath SVG Icon (left for backwards compatibility, not used in v2.5.7+) */
/*-----------------------------------------------------------------------------------*/

function ct_bath_icon() { ?>
	<svg class="muted" style="width: 25px;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="-240.6 138.8 142.1 107.1" enable-background="new -240.6 138.8 142.1 107.1" xml:space="preserve">
		<g>
			<path fill="#878c92" d="M-121.3,188.2c0,0,0-17.3,0-33.6c0-16.3-17.8-14.6-17.8-14.6c-12.5,0-13.3,10.7-13.4,12.5
				c-9.2,3.7-8.7,11.5-8.7,11.5h24c0-7.3-5.5-10.1-7.6-11.1c1.5-4.9,4.6-4.6,4.6-4.6c0.3,0,1.3,0,4.6,0c5.7,0,6.5,6.5,6.5,6.5v33.5
				h-89.2c-3.8,0-3.7,3.7-3.7,3.7s0,0.5,0,4.3c0,3.7,4.1,4.1,4.1,4.1s0.2,1.1,0.2,14.3c0,13.2,12.2,16.5,15.8,17.6
				c0.2,4.3-3.3,4.7-3.3,4.7c-4.2-0.1-4.6,2.5-4.6,3.7c0.2,3.7,3.3,4.1,3.3,4.1h2.1c9.9,0,10.3-11.9,10.3-11.9s39.6,0,49.1,0
				c1.8,11.7,10.1,12.1,11.3,12.1c1.2,0,4.6-0.6,4.6-4.2c0-3.6-3.3-3.7-4.6-3.7c-2.3-0.3-3.3-3.5-3.5-4.9
				c16.9-4.1,16.2-17.5,16.2-17.5v-14.3c3.8,0,4.1-3.7,4.1-3.7s0-0.4,0-4.2S-121.3,188.2-121.3,188.2z M-140.3,224.7
				c-11.2,0-57.8,0-57.8,0S-210,224.8-210,213s0-12.5,0-12.5h80.6v13.3C-129.3,213.8-129.2,224.7-140.3,224.7z"/>
			<circle fill="#878c92" cx="-157.8" cy="168.4" r="2.2"/>
			<circle fill="#878c92" cx="-159.6" cy="176.1" r="2.6"/>
			<circle fill="#878c92" cx="-161.5" cy="183.6" r="2.8"/>
			<circle fill="#878c92" cx="-141.1" cy="168.4" r="2.2"/>
			<circle fill="#878c92" cx="-149.4" cy="168.4" r="2.2"/>
			<circle fill="#878c92" cx="-139.2" cy="176.1" r="2.6"/>
			<circle fill="#878c92" cx="-149.5" cy="176.1" r="2.6"/>
			<circle fill="#878c92" cx="-137.3" cy="183.6" r="2.8"/>
			<circle fill="#878c92" cx="-149.2" cy="183.6" r="2.8"/>
		</g>
	</svg>
<?php }

/*-----------------------------------------------------------------------------------*/
/* Listing Size SVG Icon (left for backwards compatibility, not used in v2.5.7+) */
/*-----------------------------------------------------------------------------------*/

function ct_listing_size_icon() { ?>
	<svg class="muted" style="width: 17px;" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
	<style type="text/css">
		.st0{fill:#878C92;stroke:#878C92;stroke-width:36;stroke-miterlimit:10;}
	</style>
	<path class="st0" d="M492.1,10H19.9c-5.5,0-9.9,4.4-9.9,9.9v472.2c0,5.5,4.4,9.9,9.9,9.9h288c5.5,0,9.9-4.4,9.9-9.9
		s-4.4-9.9-9.9-9.9H29.8V251.8h137.9v94.8c0,5.5,4.4,9.9,9.9,9.9c5.5,0,9.9-4.4,9.9-9.9v-207c0-5.5-4.4-9.9-9.9-9.9
		c-5.5,0-9.9,4.4-9.9,9.9V232H29.8V29.8H298v123.8c0,5.5,4.4,9.9,9.9,9.9h104.5c5.5,0,9.9-4.4,9.9-9.9s-4.4-9.9-9.9-9.9h-94.7V29.8
		h164.4v306.9H307.9c-5.5,0-9.9,4.4-9.9,9.9s4.4,9.9,9.9,9.9h174.3v125.8h-69.8c-5.5,0-9.9,4.4-9.9,9.9s4.4,9.9,9.9,9.9h79.7
		c5.5,0,9.9-4.4,9.9-9.9V19.9C502,14.4,497.6,10,492.1,10z"/>
	</svg>
<?php }

/*-----------------------------------------------------------------------------------*/
/* Get Agent Info */
/*-----------------------------------------------------------------------------------*/

function ct_get_agent_info() {
	$author_id = get_the_author_meta('ID');

	$ct_profile_url = get_user_meta($author_id, 'ct_profile_url', true);
	$first_name = get_user_meta($author_id, 'first_name', true);
	$last_name = get_user_meta($author_id, 'last_name', true);
	$tagline = get_user_meta($author_id, 'tagline', true);
	$mobile = get_user_meta($author_id, 'mobile', true);
	$office = get_user_meta($author_id, 'office', true);
	$fax = get_user_meta($author_id, 'fax', true);
	$email = get_user_meta($author_id, 'email', true);
	$agent_license = get_user_meta($author_id, 'agentlicense', true);
	$ct_user_url = get_user_meta($author_id, 'user_url', true);
	$twitterhandle = get_user_meta($author_id, 'twitterhandle', true);
	$facebookurl = get_user_meta($author_id, 'facebookurl', true);
	$instagramurl = get_user_meta($author_id, 'instagramurl', true);
	$linkedinurl = get_user_meta($author_id, 'linkedinurl', true);
	$gplus = get_user_meta($author_id, 'gplus', true);
	$youtubeurl = get_user_meta($author_id, 'youtubeurl', true);
}

/*-----------------------------------------------------------------------------------*/
/* Listing Property Info */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_propinfo')) {
	function ct_propinfo() {
	    global $post;
	    global $wp_query;
	    global $ct_options;

	    $ct_source = get_post_meta($post->ID, 'source', true);
	    $ct_idx_rupid = get_post_meta($post->ID, '_ct_idx_rupid', true);

	    $ct_use_propinfo_icons = isset( $ct_options['ct_use_propinfo_icons'] ) ? esc_html( $ct_options['ct_use_propinfo_icons'] ) : '';
	    $ct_listings_propinfo_property_type = isset( $ct_options['ct_listings_propinfo_property_type'] ) ? esc_html( $ct_options['ct_listings_propinfo_property_type'] ) : '';
	    $ct_listings_propinfo_price_per = isset( $ct_options['ct_listings_propinfo_price_per'] ) ? esc_html( $ct_options['ct_listings_propinfo_price_per'] ) : '';
	    $ct_bed_beds_or_bedrooms = isset( $ct_options['ct_bed_beds_or_bedrooms'] ) ? esc_html( $ct_options['ct_bed_beds_or_bedrooms'] ) : '';
	    $ct_bath_baths_or_bathrooms = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? esc_html( $ct_options['ct_bath_baths_or_bathrooms'] ) : '';
	    $ct_listings_lotsize_format = isset( $ct_options['ct_listings_lotsize_format'] ) ? esc_html( $ct_options['ct_listings_lotsize_format'] ) : '';

	    $ct_property_type = strip_tags( get_the_term_list( $post->ID, 'property_type', '', ', ', '' ) );
	    $beds = strip_tags( get_the_term_list( $post->ID, 'beds', '', ', ', '' ) );
	    $baths = strip_tags( get_the_term_list( $post->ID, 'baths', '', ', ', '' ) );
	    $ct_community = strip_tags( get_the_term_list( $post->ID, 'community', '', ', ', '' ) );

	    $ct_lotsize = get_post_meta($post->ID, '_ct_lotsize', true);
	    $ct_lot_acres = get_post_meta($post->ID, '_ct_idx_overview_acres', true);

	    $ct_walkscore = isset( $ct_options['ct_enable_walkscore'] ) ? esc_html( $ct_options['ct_enable_walkscore'] ) : '';
	    $ct_rentals_booking = isset( $ct_options['ct_rentals_booking'] ) ? esc_html( $ct_options['ct_rentals_booking'] ) : '';
	    $ct_listing_reviews = isset( $ct_options['ct_listing_reviews'] ) ? esc_html( $ct_options['ct_listing_reviews'] ) : '';

	    if($ct_walkscore == 'yes') {
		    /* Walk Score */
		   	$latlong = get_post_meta($post->ID, '_ct_latlng', true);
		   	$ct_trans_name = uniqid('ct_');

		   	if($latlong != '' && false === ( $ct_ws = get_transient( $ct_trans_name . '_walkscore_data' ) )) {
				list($lat, $long) = explode(',',$latlong,2);
				$address = get_the_title() . ct_taxonomy_return('city') . ct_taxonomy_return('state') . ct_taxonomy_return('zipcode');
				$json = ct_get_walkscore($lat,$long,$address);

				$ct_ws = json_decode($json, true);

				set_transient( $ct_trans_name . '_walkscore_data', $ct_ws, 7 * DAY_IN_SECONDS );
			}
		}

	    if(ct_has_type('commercial') || ct_has_type('industrial') || ct_has_type('retail') || ct_has_type('lot') || ct_has_type('land')) {
	        // Dont Display Bed/Bath
	    } else {
	    	if(!empty($beds)) {
		    	echo '<li class="row beds">';
		    		echo '<span class="muted left">';
		    			if($ct_use_propinfo_icons != 'icons') {
		    				if($ct_bed_beds_or_bedrooms == 'rooms') {
				    			_e('Rooms', 'contempo');
				    		} elseif($ct_bed_beds_or_bedrooms == 'bedrooms') {
				    			_e('Bedrooms', 'contempo');
				    		} elseif($ct_bed_beds_or_bedrooms == 'beds') {
				    			_e('Beds', 'contempo');
					    	} else {
					    		_e('Bed', 'contempo');
					    	}
			    		} else {
			    			ct_bed_svg();
			    		}
		    		echo '</span>';
		    		echo '<span class="right">';
		               echo esc_html($beds);
		            echo '</span>';
		        echo '</li>';
		    }
		    if(!empty($baths)) {
		        echo '<li class="row baths">';
		            echo '<span class="muted left">';
		    			if($ct_use_propinfo_icons != 'icons') {
			    			if($ct_bath_baths_or_bathrooms == 'bathrooms') {
				    			_e('Bathrooms', 'contempo');
				    		} elseif($ct_bath_baths_or_bathrooms == 'baths') {
				    			_e('Baths', 'contempo');
				    		} else {
					    		_e('Bath', 'contempo');
					    	}
			    		} else {
			    			ct_bath_svg();
			    		}
		    		echo '</span>';
		    		echo '<span class="right">';
		               echo esc_html($baths);
		            echo '</span>';
		        echo '</li>';
		    }
	    }

	    if(get_post_meta($post->ID, '_ct_pets', true)) {
		    echo '<li class="row pets">';
				echo '<span class="muted left">';
					if($ct_use_propinfo_icons != 'icons') {
		    			_e('Pets', 'contempo');
		    		} else {
		    			ct_pet_svg();
		    		}
				echo '</span>';
				echo '<span class="right">';
					echo get_post_meta($post->ID, '_ct_pets', true);
		        echo '</span>';
		    echo '</li>';
		}

		if(get_post_meta($post->ID, '_ct_parking', true)) {
			$ct_parking = get_post_meta($post->ID, '_ct_parking', true);
			$ct_parking = preg_replace('/(?<!\ )[A-Z]/', ' $0', $ct_parking);
			$ct_parking = mb_strimwidth($ct_parking, 0, 60, '&#8230;');
		    echo '<li class="row parking">';
				echo '<span class="muted left">';
					if($ct_use_propinfo_icons != 'icons') {
		    			_e('Parking', 'contempo');
		    		} else {
		    			ct_parking_svg();
		    		}
				echo '</span>';
				echo '<span class="right">';
					echo esc_html($ct_parking);
		        echo '</span>';
		    echo '</li>';
		}

		if(function_exists('pixreviews_init_plugin') && $ct_listing_reviews == 'yes') {
			global $pixreviews_plugin;
			$ct_rating_avg = $pixreviews_plugin->get_average_rating();
			if($ct_rating_avg != '') {
				echo '<li class="row rating">';
		            echo '<span class="muted left">';
		                if($ct_use_propinfo_icons != 'icons') {
			    			_e('Rating', 'contempo');
			    		} else {
			    			ct_review_svg();
			    		}
		            echo '</span>';
		            echo '<span class="right">';
		                 echo esc_html($pixreviews_plugin->get_average_rating());
		            echo '</span>';
		        echo '</li>';
		    }
		}

	    if($ct_rentals_booking == 'yes' || class_exists('Booking_Calendar')) {
		    if(get_post_meta($post->ID, '_ct_rental_guests', true)) {
		        echo '<li class="row guests">';
		            echo '<span class="muted left">';
		                if($ct_use_propinfo_icons != 'icons') {
			    			_e('Guests', 'contempo');
			    		} else {
			    			ct_group_svg();
			    		}
		            echo '</span>';
		            echo '<span class="right">';
		                 echo get_post_meta($post->ID, '_ct_rental_guests', true);
		            echo '</span>';
		        echo '</li>';
		    }

		    if(get_post_meta($post->ID, '_ct_rental_min_stay', true)) {
		        echo '<li class="row min-stay">';
		            echo '<span class="muted left">';
		                if($ct_use_propinfo_icons != 'icons') {
			    			_e('Min Stay', 'contempo');
			    		} else {
			    			ct_calendar_svg();
			    		}
		            echo '</span>';
		            echo '<span class="right">';
		                 echo get_post_meta($post->ID, '_ct_rental_min_stay', true);
		            echo '</span>';
		        echo '</li>';
		    }
		}

	    if(get_post_meta($post->ID, '_ct_sqft', true) && is_numeric(get_post_meta($post->ID, '_ct_sqft', true))) {
	    	if($ct_use_propinfo_icons != 'icons') {
		        echo '<li class="row sqft">';
		            echo '<span class="muted left">';
		    			ct_sqftsqm();
		    		echo '</span>';
					echo '<span class="right">';
						 $value = get_post_meta($post->ID, '_ct_sqft', true);
						 if(is_numeric($value)) {
							 echo number_format_i18n($value, 0);
						 } else {
						 	echo esc_html($value);
						 }
		            echo '</span>';
		        echo '</li>';
		    } else {
		    	echo '<li class="row sqft">';
		            echo '<span class="muted left">';
			            ct_size_svg();
		    		echo '</span>';
		    		echo '<span class="right">';
		                 echo number_format_i18n(get_post_meta($post->ID, '_ct_sqft', true), 0);
		                 echo ' ' . ct_sqftsqm();
		            echo '</span>';
		        echo '</li>';
		    }
	    }

	    $price_meta = get_post_meta(get_the_ID(), '_ct_price', true);
		$price_meta = preg_replace('/[\$,]/', '', $price_meta);

		$ct_sqft = get_post_meta(get_the_ID(), '_ct_sqft', true);

	    if(has_term('for-rent', 'ct_status') || has_term('rental', 'ct_status') || has_term('leased', 'ct_status') || has_term('lease', 'ct_status') || has_term('let', 'ct_status') || has_term('sold', 'ct_status')) {
	    	// Do Nothing
	    } elseif($ct_listings_propinfo_price_per != 'yes' && !empty($price_meta) && !empty($ct_sqft)) {
		    echo '<li class="row price-per">';
				echo '<span class="muted left">';
					if($ct_use_propinfo_icons != 'icons') {
		    			_e('Price Per', 'contempo');
						ct_sqftsqm();
		    		} else {
		    			ct_price_per_sqftsqm_svg();
		    		}
				echo '</span>';
				echo '<span class="right">';
					ct_listing_price_per_sqft();
		        echo '</span>';
		    echo '</li>';
		}

		if(get_post_meta($post->ID, '_ct_year_built', true) || get_post_meta($post->ID, '_ct_idx_overview_year_built', true)) {
		    echo '<li class="row year-built">';
				echo '<span class="muted left">';
					if($ct_use_propinfo_icons != 'icons') {
		    			_e('Year Built', 'contempo');
		    		} else {
		    			ct_year_built_svg();
		    		}
				echo '</span>';
				echo '<span class="right">';
					if(get_post_meta($post->ID, '_ct_year_built', true)) {
						echo get_post_meta($post->ID, '_ct_year_built', true);
					} elseif(get_post_meta($post->ID, '_ct_idx_overview_year_built', true)) {
						echo get_post_meta($post->ID, '_ct_idx_overview_year_built', true);
					}
		        echo '</span>';
		    echo '</li>';
		}

		// Legacy IDX API
		if($ct_source == 'idx-api' && is_numeric($ct_lotsize) && empty($ct_idx_rupid)) {
            $ct_lotsize = number_format($ct_lotsize / 43560, 1);
        } else {
        	$ct_lotsize = $ct_lotsize;
        }

	    if(get_post_meta($post->ID, '_ct_lotsize', true) || get_post_meta($post->ID, '_ct_idx_overview_acres', true)) {
	        if(get_post_meta($post->ID, '_ct_lotsize', true) || get_post_meta($post->ID, '_ct_idx_overview_acres', true)) {
	            echo '<li class="row lotsize">';
	        }
	            echo '<span class="muted left">';
	    			if($ct_use_propinfo_icons != 'icons') {
		    			_e('Lot Size', 'contempo');
		    		} else {
		    			ct_lotsize_svg();
		    		}
	    		echo '</span>';
	    		echo '<span class="right">';
	    			if($ct_source == 'idx-api') {
	    				if(!empty($ct_lot_acres)) {
			    			echo ct_sanitize_output( $ct_lot_acres ) . ' ';
			    		} else {
			    			echo ct_sanitize_output( $ct_lotsize ) . ' ';
			    		}
			        } else {
			        	if($ct_listings_lotsize_format == 'yes') {
			                 echo number_format_i18n($ct_lotsize, 0) . ' ';
			            } else {
			             	echo ct_sanitize_output( $ct_lotsize ) . ' ';
			            }
			        }
	                ct_acres();
	            echo '</span>';

	        if((get_post_meta($post->ID, '_ct_lotsize', true))) {
	            echo '</li>';
	        }
	    }

	    if($ct_walkscore == 'yes' && !empty($ct_ws['walkscore']) && $ct_ws['status'] == 1) {
			echo '<li class="row walkscore">';
	            echo '<span class="muted left">';
	                if($ct_use_propinfo_icons != 'icons') {
		    			_e('Walk Score&reg;', 'contempo');
		    		} else {
		    			ct_walkscore_svg();
		    		}
	            echo '</span>';
	            echo '<span class="right" data-tooltip="' . $ct_ws['description'] . '">';
					echo '<a href="' . $ct_ws['ws_link'] . '" target="_blank">';
						echo esc_html($ct_ws['walkscore']);
					echo '</a>';
	            echo '</span>';
	        echo '</li>';
		}

		if(!empty($ct_community)) {
	    	echo '<li class="row community">';
	    		echo '<span class="muted left">';
	    			if($ct_use_propinfo_icons != 'icons') {
		    			ct_community_neighborhood_or_district();
		    		} else {
		    			ct_group_svg();
		    		}
	    		echo '</span>';
	    		echo '<span class="right">';
	               echo esc_html($ct_community);
	            echo '</span>';
	        echo '</li>';
	    }

	    if(!empty($ct_property_type) && $ct_listings_propinfo_property_type != 'yes') {
	        echo '<li class="row property-type">';
	            echo '<span class="muted left">';
	    			if($ct_use_propinfo_icons != 'icons') {
		    			_e('Type', 'contempo');
		    		} else {
		    			if(ct_has_type('commercial')) {
							ct_building_svg();
						} elseif(ct_has_type('land') || ct_has_type('lot')) {
							ct_tree_svg();
						} else {
							ct_property_type_svg();
						}
		    		}
	    		echo '</span>';
	    		echo '<span class="right">';
	    			$ct_property_type_terms = get_the_terms($post->ID, 'property_type');
	    			$ct_property_type_slugs = wp_list_pluck($ct_property_type_terms, 'term_slug');
	    			$ct_property_type = preg_replace('/(?<!\ )[A-Z]/', ' $0', $ct_property_type);
	    			if(in_array('single-family-residence', $ct_property_type_slugs)) {
	    				_e('Single Family', 'contempo');
	    			} else {
		               echo ucfirst($ct_property_type);
		           	}
	            echo '</span>';
	        echo '</li>';
	    }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Rental Info */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_rental_info')) {
	function ct_rental_info() {
		global $post;
		
		if(get_post_meta($post->ID, '_ct_rental_checkin', true)) {
	        echo '<li class="row rental-checkin">';
	            echo '<span class="muted left"><strong>';
	                esc_html_e('Check In', 'contempo');
	            echo '</strong></span>';
	            echo '<span class="right">';
		            $checkin = get_post_meta(get_the_ID(), '_ct_rental_checkin', true);
		            echo date("g:i A", strtotime($checkin));
	            echo '</span>';
	        echo '</li>';
	    }

	    if(get_post_meta($post->ID, '_ct_rental_checkout', true)) {
	        echo '<li class="row rental-checkout">';
	            echo '<span class="muted left"><strong>';
	                esc_html_e('Check Out', 'contempo');
	            echo '</strong></span>';
	            echo '<span class="right">';
	            	$checkout = get_post_meta(get_the_ID(), '_ct_rental_checkout', true);
		            echo date("g:i A", strtotime($checkout));
	            echo '</span>';
	        echo '</li>';
	    }

	    if(get_post_meta($post->ID, '_ct_rental_guests', true)) {
	        echo '<li class="row rental-max-guests">';
	            echo '<span class="muted left"><strong>';
	                esc_html_e('Max Guests', 'contempo');
	            echo '</strong></span>';
	            echo '<span class="right">';
	            	$ct_rental_guests = get_post_meta(get_the_ID(), '_ct_rental_guests', true);
		            echo esc_html($ct_rental_guests);
	            echo '</span>';
	        echo '</li>';
	    }

	    if(get_post_meta($post->ID, '_ct_rental_min_stay', true)) {
	        echo '<li class="row rental-min-stay">';
	            echo '<span class="muted left"><strong>';
	                esc_html_e('Min Stay', 'contempo');
	            echo '</strong></span>';
	            echo '<span class="right">';
	            	$ct_rental_min_stay = get_post_meta(get_the_ID(), '_ct_rental_min_stay', true);
		            echo esc_html($ct_rental_min_stay);
	            echo '</span>';
	        echo '</li>';
	    }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Rental Costs */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_rental_costs')) {
	function ct_rental_costs() {
		global $post;
		
		if((get_post_meta($post->ID, '_ct_rental_extra_people', true))) {
	        echo '<li class="row rental-cancel">';
	            echo '<span class="muted left">';
	                esc_html_e('Extra People', 'contempo');
	            echo '</span>';
	            echo '<span class="right">';
	                ct_currency();
	                echo get_post_meta($post->ID, '_ct_rental_extra_people', true);
	            echo '</span>';
	        echo '</li>';
	    }

	    if((get_post_meta($post->ID, '_ct_rental_cleaning', true))) {
	        echo '<li class="row cleaning-fee">';
	            echo '<span class="muted left">';
	                esc_html_e('Cleaning Fee', 'contempo');
	            echo '</span>';
	            echo '<span class="right">';
	                ct_currency();
	                echo get_post_meta($post->ID, '_ct_rental_cleaning', true);
	            echo '</span>';
	        echo '</li>';
	    }

	    if((get_post_meta($post->ID, '_ct_rental_cancellation', true))) {
	        echo '<li class="row cleaning-fee">';
	            echo '<span class="muted left">';
	                esc_html_e('Cancellation Fee', 'contempo');
	            echo '</span>';
	            echo '<span class="right">';
	                ct_currency();
	                echo get_post_meta($post->ID, '_ct_rental_cancellation', true);
	            echo '</span>';
	        echo '</li>';
	    }

	    if((get_post_meta($post->ID, '_ct_rental_deposit', true))) {
	        echo '<li class="row rental-deposit">';
	            echo '<span class="muted left">';
	                esc_html_e('Security Deposit', 'contempo');
	            echo '</span>';
	            echo '<span class="right">';
		            ct_currency();
	                echo get_post_meta($post->ID, '_ct_rental_deposit', true);
	            echo '</span>';
	        echo '</li>';
	    }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Brokerage Address */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_brokerage_address')) {
	function ct_brokerage_address($postID) {
		global $post;

		$brokerage = new WP_Query(array(
            'post_type' => 'brokerage',
            'p' => $postID,
            'nopaging' => true
        ));

        if( $brokerage->have_posts() ) : while ( $brokerage->have_posts() ) : $brokerage->the_post();

        $ct_brokerage_street_address = get_post_meta($post->ID, '_ct_brokerage_street_address', true);
		$ct_brokerage_address_two = get_post_meta($post->ID, '_ct_brokerage_address_two', true);
		$ct_brokerage_city = get_post_meta($post->ID, '_ct_brokerage_city', true);
		$ct_brokerage_state = get_post_meta($post->ID, '_ct_brokerage_state', true);
		$ct_brokerage_zip = get_post_meta($post->ID, '_ct_brokerage_zip', true);
		$ct_brokerage_country = get_post_meta($post->ID, '_ct_brokerage_country', true);

        	if($ct_brokerage_street_address != '') {
	            echo esc_html($ct_brokerage_street_address) . ', ';
	        }
	        
	        if($ct_brokerage_address_two != '') {
	            echo esc_html($ct_brokerage_address_two) . ', ';
	        }
	        
	        if($ct_brokerage_city != '') {
	            echo esc_html($ct_brokerage_city) . ', ';
	        }
	        
	        if($ct_brokerage_state != '') {
	            echo esc_html($ct_brokerage_state) . ', ';
	        }
	        
	        if($ct_brokerage_zip != '') {
	            echo esc_html($ct_brokerage_zip) . ' ';
	        }

	        if($ct_brokerage_country != '') {
	            echo esc_html($ct_brokerage_country);
	        }
        
        endwhile; endif; wp_reset_postdata();

	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Grid Agent Info */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_grid_agent_info')) {
	function ct_listing_grid_agent_info() {
		global $ct_options, $post;

		$ct_source = get_post_meta($post->ID, 'source', true);

		$ct_idx_pro_assign_agents = get_option( 'ct_idx_pro_assign_agents' );
		$ct_idx_pro_assign_agents = isset( $ct_idx_pro_assign_agents ) ? $ct_idx_pro_assign_agents : '';
		$ct_idx_pro_assign_agents = json_decode($ct_idx_pro_assign_agents, true);

		if(!empty($ct_idx_pro_assign_agents) && $ct_source == 'idx-api') {
			
			foreach($ct_idx_pro_assign_agents as $agent) {
				$ct_agent_first_name = get_user_meta($agent, 'first_name', true);
				$ct_agent_last_name = get_user_meta($agent, 'last_name', true);
				$ct_agent_display_name = $ct_agent_first_name . ' ' . $ct_agent_last_name;
				$ct_agent_name_IDX = get_post_meta( $post->ID, '_ct_agent_name', true );

				if($ct_agent_name_IDX == $ct_agent_display_name) {
					$author_id = $agent;
				} else {
					$author_id = get_the_author_meta('ID');
				}
			}

		} else {
			$author_id = get_the_author_meta('ID');
		}

        $first_name = get_user_meta($author_id, 'first_name', true);
		$last_name = get_user_meta($author_id, 'last_name', true);
		$ct_profile_url = get_user_meta($author_id, 'ct_profile_url', true);

        $ct_listing_agent_contact_logged_in = isset( $ct_options['ct_listing_agent_contact_logged_in'] ) ? $ct_options['ct_listing_agent_contact_logged_in'] : '';
        $ct_listing_agent_grid_information = isset( $ct_options['ct_listing_agent_grid_information'] ) ? $ct_options['ct_listing_agent_grid_information'] : '';

		if($ct_listing_agent_grid_information == 'yes') {
	        echo '<div class="grid-agent-info col span_12 first">';

		        echo '<div class="left">';
			        if($ct_listing_agent_contact_logged_in == 'yes') {
			        	if(!is_user_logged_in()) {
				            echo '<p class="marB0">' . esc_html($first_name) . ' ' . esc_html($last_name) . '</p>';
				        } else {
				        	echo '<p class="marB0"><a href="' . get_author_posts_url($author_id) . '">' . esc_html($first_name) . ' ' . esc_html($last_name) . '</a></p>';
				        }
				    } else {
				    	echo '<p class="marB0"><a href="' . get_author_posts_url($author_id) . '">' . esc_html($first_name) . ' ' . esc_html($last_name) . '</a></p>';
				    }
		        echo '</div>';
		        echo '<div class="right">';
			        echo '<figure class="grid-agent-image">';
			        	if($ct_listing_agent_contact_logged_in == 'yes') {
				        	if(!is_user_logged_in()) {
				               if(!empty($$ct_profile_url)) {
				                    echo '<img src="';
				                        echo esc_url($ct_profile_url);
				                    echo '" />';
				                } else {
				                    echo '<img src="' . get_template_directory_uri() . '/images/user-default.png' . '" />';
				                }
					        } else {
					        	echo '<a href="' . get_author_posts_url($author_id) . '">';
					               if(!empty($ct_profile_url)) {
					                    echo '<img src="';
					                        echo esc_url($ct_profile_url);
					                    echo '" />';
					                } else {
					                    echo '<img src="' . get_template_directory_uri() . '/images/user-default.png' . '" />';
					                }
					            echo '</a>';
					        }
					    } else {
					    	echo '<a href="' . get_author_posts_url($author_id) . '">';
				               if(!empty($ct_profile_url)) {
				                    echo '<img src="';
				                        echo esc_url($ct_profile_url);
				                    echo '" />';
				                } else {
				                    echo '<img src="' . get_template_directory_uri() . '/images/user-default.png' . '" />';
				                }
				            echo '</a>';
					    }
			        echo '</figure>';
			    echo '</div>';

		    echo '</div>';
		}
    }
}

/*-----------------------------------------------------------------------------------*/
/* Time Elapsed X hours/days/weeks/months ago for IDX */
/*-----------------------------------------------------------------------------------*/

function ct_idx_list_date_time_elapsed($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );

    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

/*-----------------------------------------------------------------------------------*/
/* Listing Creation Date */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_creation_date')) {
	function ct_listing_creation_date() {
		global $ct_options, $post;

		$ct_listing_creation_date = isset( $ct_options['ct_listing_creation_date'] ) ? $ct_options['ct_listing_creation_date'] : '';

		$ct_source = get_post_meta($post->ID, 'source', true);
		$ct_idx_rupid = get_post_meta($post->ID, '_ct_idx_rupid', true);

		$ct_idx_list_date = get_post_meta($post->ID, '_ct_idx_list_date', true);

		if($ct_listing_creation_date == 'yes') {
			echo '<div class="creation-date col span_12 first">';
				echo '<span class="left muted">';
					echo ct_calendar_svg();
				echo '</span>';
				echo '<span class="right">';
					if( $ct_source == 'idx-api' && !empty($ct_idx_rupid) ) {
						echo ct_idx_list_date_time_elapsed($ct_idx_list_date);
					} else {
						printf( __( '%s ago', 'contempo' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
					}
				echo '</span>';
					echo '<div class="clear"></div>';
			echo '</div>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Updated Time */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_updated_time')) {
	function ct_listing_updated_time() {
		global $ct_options, $post;

		$ct_listing_updated_time = isset( $ct_options['ct_listing_updated_time'] ) ? $ct_options['ct_listing_updated_time'] : '';

		if($ct_listing_updated_time == 'yes') {
			echo '<p class="listing-updated muted">';
				echo __( 'Updated on', 'contempo' ) . ' ';
					the_modified_time('F j, Y');
				echo ' ' . __( 'at', 'contempo' ) . ' ';
					the_modified_time('g:i a');
			echo '</p>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Open House */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_upcoming_open_house')) {
	function ct_upcoming_open_house() {
		global $ct_options, $post;

		$ct_listing_upcoming_open_house = isset( $ct_options['ct_listing_upcoming_open_house'] ) ? $ct_options['ct_listing_upcoming_open_house'] : '';

		if($ct_listing_upcoming_open_house != 'no') {

			$ct_open_house_entries = get_post_meta( get_the_ID(), '_ct_open_house', true );
			$ct_todays_date = date('mdY');

			foreach( (array) $ct_open_house_entries as $key => $entry ) {
			    $ct_open_house_date = '';
			    if( isset( $entry['_ct_open_house_date'] ) ) {
			    	$ct_open_house_date = esc_html( $entry['_ct_open_house_date'] );
			    }
			}

			if($ct_open_house_entries != '' && $ct_open_house_date != '') {

				foreach( (array) $ct_open_house_entries as $key => $entry ) {

					reset($ct_open_house_entries);

					$ct_open_house_date = $ct_open_house_start_time = $ct_open_house_end_time = '';

		            if( isset( $entry['_ct_open_house_date'] ) )
		                {$ct_open_house_date = esc_html( $entry['_ct_open_house_date'] );}
			            $ct_open_house_date_formatted = strftime('%m%d%Y', $ct_open_house_date);

		            if( isset( $entry['_ct_open_house_start_time'] ) )
		                {$ct_open_house_start_time = esc_html( $entry['_ct_open_house_start_time'] );}

		            if( isset( $entry['_ct_open_house_end_time'] ) )
		                {$ct_open_house_end_time = esc_html( $entry['_ct_open_house_end_time'] );}

					if($ct_open_house_date_formatted >= $ct_todays_date) {
						echo '<div class="upcoming-open-house">';

							echo '<span class="left muted">';
								_e('Open House', 'contempo');
							echo '</span>';

							echo '<span class="right">';

				                if($ct_open_house_date != '') {
				                    echo strftime('%b %e', $ct_open_house_date);
				                }

				                if($ct_open_house_start_time != '') {
		                            echo ', ' . $ct_open_house_start_time;
		                        }

		                        if($ct_open_house_end_time != '') {
		                            echo ' - ';
		                            echo esc_html($ct_open_house_end_time);
		                        }

							echo '</span>';

								echo '<div class="clear"></div>';

						echo '</div>';

						end($ct_open_house_entries);

					    if($key === key($ct_open_house_entries)) {

							echo '<div class="upcoming-open-house hosted-by">';

	                            echo '<span class="muted left">';
	                                _e('Hosted By', 'contempo');
	                            echo '</span>';

	                            echo '<span class="right">';
	                                $first_name = get_the_author_meta('first_name');
	                                $last_name = get_the_author_meta('last_name');
	                                $mobile = get_the_author_meta('mobile');
	                                echo esc_html($first_name) . ' ' . esc_html($last_name) . ' ' . esc_html($mobile);
	                            echo '</span>';

	                                echo '<div class="clear"></div>';

	                        echo '</div>';
	                    }
					}
				}
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Broker Info */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_brokered_by')) {
	function ct_brokered_by() {
		global $ct_options, $post;

		$ct_listing_brokerage_name = isset( $ct_options['ct_listing_brokerage_name'] ) ? $ct_options['ct_listing_brokerage_name'] : '';
		$ct_user_meta_brokerage = get_the_author_meta( 'brokerage' );
		$ct_cpt_brokerage = get_post_meta( get_the_ID(), '_ct_brokerage', true );

		$ct_source = get_post_meta($post->ID, 'source', true);

		if($ct_listing_brokerage_name == 'yes' && $ct_cpt_brokerage != 0) {

			$brokerage = new WP_Query(array(
	            'post_type' => 'brokerage',
	            'p' => $ct_cpt_brokerage,
	            'nopaging' => true
	        ));

	        if( $brokerage->have_posts() ) : while ( $brokerage->have_posts() ) : $brokerage->the_post();
	            $ct_brokerage_permalink = get_permalink();
            	$ct_brokerage_name = strtolower(get_the_title());
	        endwhile; endif; wp_reset_postdata();

			if(!empty($ct_brokerage_name)) {
				echo '<div class="brokerage col span_12 first">';
					echo '<p class="muted marB0">';
						echo '<small>';
							if($ct_source == 'idx-api') {
								_e('Listing Office', 'contempo');
							} else {
								_e('Brokered by', 'contempo');
							}
						echo '</small>';
					echo '</p>';
					if($ct_source == 'idx-api') {
						echo '<p class="marB0">' . ucwords($ct_brokerage_name) . '</p>';
					} else {
						echo '<p class="marB0"><a href="' . esc_url($ct_brokerage_permalink) . '">' . ucwords($ct_brokerage_name) . '</a></p>';
					}
				echo '</div>';
			}
		} elseif($ct_listing_brokerage_name == 'yes' && $ct_user_meta_brokerage != '') {
			echo '<div class="brokerage col span_12 first">';
				echo '<p class="muted marB0">';
					echo '<small>';
						if($ct_source == 'idx-api') {
							_e('Listing Office', 'contempo');
						} else {
							_e('Brokered by', 'contempo');
						}
					echo '</small>';
				echo '</p>';
				echo '<p class="marB0">';
					ucwords(the_author_meta( 'brokerage' ));
				echo '</p>';
			echo '</div>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display Brokerage Logo */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_brokerage_logo')) {
	function ct_brokerage_logo($postID) {

		$brokerage = new WP_Query(array(
            'post_type' => 'brokerage',
            'p' => $postID,
            'nopaging' => true
        ));

        if( $brokerage->have_posts() ) : while ( $brokerage->have_posts() ) : $brokerage->the_post();
            if(has_post_thumbnail()) {
                the_post_thumbnail('thumb');
            }
        endwhile; endif; wp_reset_postdata();

	}
}

/*-----------------------------------------------------------------------------------*/
/* Display Brokerage Name */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_brokerage_name')) {
	function ct_brokerage_name($postID) {

		$brokerage = new WP_Query(array(
            'post_type' => 'brokerage',
            'p' => $postID,
            'nopaging' => true
        ));

        if( $brokerage->have_posts() ) : while ( $brokerage->have_posts() ) : $brokerage->the_post();
            the_title();
        endwhile; endif; wp_reset_postdata();

	}
}

/*-----------------------------------------------------------------------------------*/
/* Display IDX MLS Logo for Compliance */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_idx_mls_logo')) {
	function ct_idx_mls_logo() {
		global $ct_options;
		global $post;

		$ct_listing_idx_mls_logo = isset( $ct_options['ct_listing_idx_mls_logo'] ) ? $ct_options['ct_listing_idx_mls_logo'] : '';

		$source = get_post_meta($post->ID, 'source', true);
		$ct_idx_mls_idx_logo_small = get_post_meta($post->ID, '_ct_idx_mls_idx_logo_small', true);

		if($ct_listing_idx_mls_logo == 'yes' && $source == 'idx-api' && !empty($ct_idx_mls_idx_logo_small)) {
			echo '<img class="idx-logo-overlay" src="' . esc_url($ct_idx_mls_idx_logo_small) . '" />';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Status Snipes */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_has_status')) {
	function ct_has_status( $status, $_post = null ) {
		
		if(empty( $status )) {
			return false;
		}

		if($_post) {
			$_post = get_post( $_post );
		} else {
			$_post =& $GLOBALS['post'];
		}

		if(!$_post) {
			return false;
		}

		$r = is_object_in_term( $_post->ID, 'ct_status', $status );

		if(is_wp_error( $r )) {
			return false;
		}

		return $r;
	}
}

if(!function_exists('ct_status_slug')) {
	if(function_exists('get_modified_term_list_slug')) {
		function ct_status_slug() {
			global $post;
			global $wp_query;

			$status_terms = strip_tags( get_modified_term_list_slug( $wp_query->post->ID, 'ct_status', '', ' ', '', array('featured') ) );

			if(!empty( $status_terms ) && !is_wp_error( $status_terms ) ){
			    echo esc_html($status_terms) . ' ';
			}
		}
	}
}

if(!function_exists('ct_status')) {
	if(function_exists('get_modified_term_list_name')) {
		function ct_status() {
			global $ct_options;
			global $post;
			global $wp_query;

			$ct_listing_virtual_tour_badge = isset( $ct_options['ct_listing_virtual_tour_badge'] ) ? $ct_options['ct_listing_virtual_tour_badge'] : '';

			$status_tags = strip_tags( get_modified_term_list_slug( $wp_query->post->ID, 'ct_status', '', ' ', '', array('featured') ) );
			$status_tags_stripped = str_replace('_', ' ', $status_tags);
			$status_tags_stripped = str_replace('-', ' ', $status_tags_stripped);

			if($status_tags != '') {
				echo '<h6 class="snipe status ';
						ct_status_slug();
					echo '">';
					echo '<span>';
						echo ucwords($status_tags_stripped);
					echo '</span>';
				echo '</h6>';
			}

			$ct_virtual_tour = get_post_meta($post->ID, '_ct_virtual_tour', true);
			$ct_virtual_tour_shortcode = get_post_meta($post->ID, '_ct_virtual_tour_shortcode', true);
			$ct_virtual_tour_url = get_post_meta($post->ID, '_ct_virtual_tour_url', true);

			if($ct_listing_virtual_tour_badge == 'yes') {
				if(!empty($ct_virtual_tour) || !empty($ct_virtual_tour_shortcode) || !empty($ct_virtual_tour_url)) {
					if(empty($status_tags)) {
						echo '<span class="virtual-tour-badge no-status">';
					} else {
						echo '<span class="virtual-tour-badge">';
					}
						ct_virtual_tour_svg_white();
						echo '<span class="virtual-tour-text-wrap">' . __('Virtual Tour', 'contempo') . '</span>';
					echo '</span>';
				}
			}
		}
	}
}

if(!function_exists('ct_status_featured')) {
	function ct_status_featured() {
		global $post;
		global $wp_query;

		if(has_term( 'featured', 'ct_status' ) ) {
			echo '<h6 class="snipe featured">';
				echo '<span>';
					echo __('Featured', 'contempo');
				echo '</span>';
			echo '</h6>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Property Type Icon */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_property_type_icon')) {
	function ct_property_type_icon() {
		global $post;

		if(ct_has_type('commercial') || ct_has_type('industrial') || ct_has_type('retail')) {
			echo '<span class="prop-type-icon">';
				ct_building_svg_white();
			echo '</span>';
		} elseif(ct_has_type('land') || ct_has_type('lot')) {
			echo '<span class="prop-type-icon">';
				ct_tree_svg_white();
			echo '</span>';
		} else {
			echo '<span class="prop-type-icon">';
				ct_property_type_svg_white();
			echo '</span>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Currency */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_currency')) {
	function ct_currency( $echo=true ) {
		global $ct_options;
		
		$currency = '$';

		if( isset( $ct_options['ct_currency'] ) ) {
			$currency = esc_html($ct_options['ct_currency']);
		}

		if($echo) {
			echo esc_html($currency);
		} else {
			return $currency;
		}

	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Marker Price */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_marker_price')) {
	function ct_listing_marker_price() {
		global $post;
		global $ct_options;

		$price      = '';
		$price_meta = get_post_meta(get_the_ID(), '_ct_price', true);
		$price      = preg_replace('/[\$,]/', '', $price_meta);

		if($price == '0' || $price == ''){
			$price = '-';
		} else {
			$units = ['', 'K', 'M', 'B', 'T'];
			for($i = 0; $price >= 1000; $i++) {
				$price /= 1000;
			}
			$price = round( $price, 1 ) . $units[$i];
		}

		return $price;

	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Price */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_price')) {
	function ct_listing_price( $echo = true ) {
		global $post;
		global $ct_options;

		$price = '';

		$ct_currency_placement = isset( $ct_options['ct_currency_placement'] ) ? esc_attr( $ct_options['ct_currency_placement'] ): '';
		$ct_currency_decimal = isset( $ct_options['ct_currency_decimal'] ) ? esc_attr( $ct_options['ct_currency_decimal'] ) : '';

		$price_prefix = get_post_meta(get_the_ID(), '_ct_price_prefix', true);
		$price_postfix = get_post_meta(get_the_ID(), '_ct_price_postfix', true);

		$price_meta = get_post_meta(get_the_ID(), '_ct_price', true);
		$price_meta = preg_replace('/[\$,]/', '', $price_meta);

		$ct_display_listing_price = get_post_meta(get_the_ID(), '_ct_display_listing_price', true);

		if($price_meta == '0' || $price_meta == ''){
			$price = '';
		} else {
			if($ct_currency_placement == 'after') {
				if(!empty($price_prefix)) {
					$price = $price . "<span class='listing-price-prefix'>";
					$price = $price . esc_html($price_prefix) . ' ';
					$price = $price . '</span>';
				}
				if(!empty($price_meta) && $ct_display_listing_price != 'no') {
					$price = $price . "<span class='listing-price'>";
					$price = $price . number_format_i18n($price_meta, $ct_currency_decimal);
					$price = $price . ct_currency( false );
					$price = $price . '</span>';
				}
				if(!empty($price_postfix)) {
					$price = $price . "<span class='listing-price-postfix'>";
					$price = $price .  ' ' . esc_html($price_postfix);
					$price = $price . '</span>';
				}
			} else {
				if(!empty($price_prefix)) {
					$price = $price . "<span class='listing-price-prefix'>";
					$price = $price . esc_html($price_prefix) . ' ';
					$price = $price . '</span>';
				}
				if(!empty($price_meta) && $ct_display_listing_price != 'no') {
					$price = $price . "<span class='listing-price'>";
					$price = $price . ct_currency( false );
					$price = $price . number_format_i18n($price_meta, $ct_currency_decimal);
					$price = $price . '</span>';
				}
				if(!empty($price_postfix)) {
					$price = $price . "<span class='listing-price-postfix'>";
					$price = $price .  ' ' . esc_html($price_postfix);
					$price = $price . '</span>';
				}
			}
		}

		if($echo) {
			echo ct_sanitize_output( $price );
		} else {
			return $price;
		}

	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Price - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_listing_price')) {
	function ct_elementor_listing_price( $echo=true ) {

		global $ct_options;

		$attributes['is_elementor'] = 1;

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $attributes['is_elementor_edit'] = 1;
        }

		$price = '';

		$ct_currency_placement = isset( $ct_options['ct_currency_placement'] ) ? esc_attr( $ct_options['ct_currency_placement'] ): '';
		$ct_currency_decimal = isset( $ct_options['ct_currency_decimal'] ) ? esc_attr( $ct_options['ct_currency_decimal'] ) : '';

		$price_prefix = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_price_prefix', true);
		$price_postfix = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_price_postfix', true);

		$price_meta = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_price', true);
		$price_meta = preg_replace('/[\$,]/', '', $price_meta);

		$ct_display_listing_price = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_display_listing_price', true);

		if($ct_currency_placement == 'after') {
			if(!empty($price_prefix)) {
				$price = $price. "<span class='listing-price-prefix'>";
				$price = $price. esc_html($price_prefix) . ' ';
				$price = $price. '</span>';
			}
			if(!empty($price_meta) && $ct_display_listing_price != 'no') {
				$price = $price. "<span class='listing-price'>";
				$price = $price. number_format_i18n($price_meta, $ct_currency_decimal);
				$price = $price.ct_currency( false );
				$price = $price. '</span>';
			}
			if(!empty($price_postfix)) {
				$price = $price. "<span class='listing-price-postfix'>";
				$price = $price.  ' ' . esc_html($price_postfix);
				$price = $price. '</span>';
			}
		} else {
			if(!empty($price_prefix)) {
				$price = $price. "<span class='listing-price-prefix'>";
				$price = $price. esc_html($price_prefix) . ' ';
				$price = $price. '</span>';
			}
			if(!empty($price_meta) && $ct_display_listing_price != 'no') {
				$price = $price. "<span class='listing-price'>";
				$price = $price.ct_currency( false );
					$price = $price. number_format_i18n($price_meta, $ct_currency_decimal);
					$price = $price. '</span>';
			}
			if(!empty($price_postfix)) {
				$price = $price. "<span class='listing-price-postfix'>";
				$price = $price.  ' ' . esc_html($price_postfix);
				$price = $price. '</span>';
			}
		}

		if($echo) {
			echo ct_sanitize_output( $price );
		} else {
			return $price;
		}

	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Estimated Payment */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_estimated_payment')) {
	function ct_listing_estimated_payment() {
		global $post;
		global $ct_options;

		$est_payment = '';

		$down_payment = 0;

		$ct_currency_placement = $ct_options['ct_currency_placement'];
		$ct_currency_decimal = isset( $ct_options['ct_currency_decimal'] ) ? esc_attr( $ct_options['ct_currency_decimal'] ) : '';

		$price_meta = get_post_meta(get_the_ID(), '_ct_price', true);
		$price_meta = preg_replace('/[\$,]/', '', $price_meta);

		$ct_listing_est_payment_percent_down = isset( $ct_options['ct_listing_est_payment_percent_down'] ) ? esc_attr( $ct_options['ct_listing_est_payment_percent_down'] ) : '20';
		$ct_listing_est_payment_interest_rate = isset( $ct_options['ct_listing_est_payment_interest_rate'] ) ? esc_attr( $ct_options['ct_listing_est_payment_interest_rate'] ) : '4';
		$ct_listing_est_payment_term = isset( $ct_options['ct_listing_est_payment_term'] ) ? esc_attr( $ct_options['ct_listing_est_payment_term'] ) : '30';

		// Convert Interest Rate to Percent
		if(is_numeric($ct_listing_est_payment_percent_down)) {
			$ct_listing_est_payment_percent_down = $ct_listing_est_payment_percent_down / 100;
		}

		if(is_numeric($price_meta)) {
			$down_payment = $ct_listing_est_payment_percent_down * $price_meta;
		}

		$monthly_interest_rate = $ct_listing_est_payment_interest_rate / 12 + 1;

		$n = $ct_listing_est_payment_term * 12; // Total Monthly Payments
		$c = floatval($ct_listing_est_payment_interest_rate); // Rate
		
		$L = 0;

		if(is_numeric($price_meta)) {
			$L = $price_meta - $down_payment; // Loan Amount
		}

		// If 0 or no interest, fake it with a tiny number to avoid NaN error
		if($c == 0) {
			$c = .00001;
		}
		$c = $c / 1200;

		$P = ($L * ($c * pow(1 + $c, $n))) / (pow( 1 + $c, $n) - 1);

		// Add the property tax and home insurance.
		// So that both estimate is correct.

		// The tax rate from CT Theme Options.
		$tax_rate = isset( $ct_options['ct_listing_est_payment_tax_rate'] ) ? esc_attr( floatval( $ct_options['ct_listing_est_payment_tax_rate'] ) ) : 2.80;

		// Home insurance.
		$home_insurance = isset( $ct_options['ct_listing_est_payment_home_insurance'] ) ? esc_attr( $ct_options['ct_listing_est_payment_home_insurance'] ) : 900;

        // Calculate the monthly tax rate payment.
        $tax_rate_calculated = ( ( $tax_rate / 100 ) * floatval( $price_meta ) ) / 12;

        // Add the calculated monthly tax rate to principal amount.
		$P += $tax_rate_calculated;

		// Add the Home Insurance to principal amount.
		if ( is_float( $home_insurance ) || is_numeric( $home_insurance ) ) {
		    $P += floatval( $home_insurance ) / 12;
		}

		$ct_display_listing_price = get_post_meta(get_the_ID(), '_ct_display_listing_price', true);

		if($ct_currency_placement == 'after') {
			if(!empty($price_meta) && $ct_display_listing_price != 'no') {
				$est_payment .= "<span class='listing-est-payment'>";
				$est_payment .= number_format_i18n($P, $ct_currency_decimal);
				$est_payment .= ct_currency( false );
				$est_payment .= '</span>';
			}
		} else {
			if(!empty($price_meta) && $ct_display_listing_price != 'no') {
				$est_payment .= "<span class='listing-est-payment'>";
				$est_payment .= ct_currency( false );
				$est_payment .= number_format_i18n($P, $ct_currency_decimal);
				$est_payment .= '</span>';
			}
		}

		printf( __('%s/mo', 'contempo'), $est_payment );

		echo '<script>';
			echo 'jQuery( document ).ready(function($) {';
				echo "$('#mcRate').val('" . $ct_listing_est_payment_interest_rate . '%' . "');";
				echo "$('#mcTerm').val('" . $ct_listing_est_payment_term . "');";
				echo "$('#mcDown').val('" . $down_payment . "');";
				echo "$('#mcTerm').niceSelect('update');";
			echo '});';
		echo '</script>';

	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Estimated Payment - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_listing_estimated_payment')) {
	function ct_elementor_listing_estimated_payment() {
		
		global $ct_options;

		$attributes['is_elementor'] = 1;

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $attributes['is_elementor_edit'] = 1;
        }

		$est_payment = '';

		$down_payment = 0;

		$ct_currency_placement = $ct_options['ct_currency_placement'];
		$ct_currency_decimal = isset( $ct_options['ct_currency_decimal'] ) ? esc_attr( $ct_options['ct_currency_decimal'] ) : '';

		$price_meta = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_price', true);
		$price_meta = preg_replace('/[\$,]/', '', $price_meta);

		$ct_listing_est_payment_percent_down = isset( $ct_options['ct_listing_est_payment_percent_down'] ) ? esc_attr( $ct_options['ct_listing_est_payment_percent_down'] ) : '20';
		$ct_listing_est_payment_interest_rate = isset( $ct_options['ct_listing_est_payment_interest_rate'] ) ? esc_attr( $ct_options['ct_listing_est_payment_interest_rate'] ) : '4';
		$ct_listing_est_payment_term = isset( $ct_options['ct_listing_est_payment_term'] ) ? esc_attr( $ct_options['ct_listing_est_payment_term'] ) : '30';

		// Convert Interest Rate to Percent
		if(is_numeric($ct_listing_est_payment_percent_down)) {
			$ct_listing_est_payment_percent_down = $ct_listing_est_payment_percent_down / 100;
		}

		if(is_numeric($price_meta)) {
			$down_payment = $ct_listing_est_payment_percent_down * $price_meta;
		}

		$monthly_interest_rate = $ct_listing_est_payment_interest_rate / 12 + 1;

		$n = $ct_listing_est_payment_term * 12; // Total Monthly Payments
		$c = floatval($ct_listing_est_payment_interest_rate); // Rate
		
		$L = 0;

		if(is_numeric($price_meta)) {
			$L = $price_meta - $down_payment; // Loan Amount
		}

		// If 0 or no interest, fake it with a tiny number to avoid NaN error
		if($c == 0) {
			$c = .00001;
		}
		$c = $c / 1200;

		$P = ($L * ($c * pow(1 + $c, $n))) / (pow( 1 + $c, $n) - 1);

		// Add the property tax and home insurance.
		// So that both estimate is correct.

		// The tax rate from CT Theme Options.
		$tax_rate = isset( $ct_options['ct_listing_est_payment_tax_rate'] ) ? esc_attr( floatval( $ct_options['ct_listing_est_payment_tax_rate'] ) ) : 2.80;

		// Home insurance.
		$home_insurance = isset( $ct_options['ct_listing_est_payment_home_insurance'] ) ? esc_attr( $ct_options['ct_listing_est_payment_home_insurance'] ) : 900;

        // Calculate the monthly tax rate payment.
        $tax_rate_calculated = ( ( $tax_rate / 100 ) * floatval( $price_meta ) ) / 12;

        // Add the calculated monthly tax rate to principal amount.
		$P += $tax_rate_calculated;

		// Add the Home Insurance to principal amount.
		if ( is_float( $home_insurance ) || is_numeric( $home_insurance ) ) {
		    $P += floatval( $home_insurance ) / 12;
		}

		$ct_display_listing_price = get_post_meta(get_the_ID(), '_ct_display_listing_price', true);

		if($ct_currency_placement == 'after') {
			if(!empty($price_meta) && $ct_display_listing_price != 'no') {
				$est_payment .= "<span class='listing-est-payment'>";
					$est_payment .= number_format_i18n($P, $ct_currency_decimal);
					$est_payment .= ct_currency( false );
				$est_payment .= '</span>';
			}
		} else {
			if(!empty($price_meta) && $ct_display_listing_price != 'no') {
				$est_payment .= "<span class='listing-est-payment'>";
					$est_payment .= ct_currency( false );
					$est_payment .= number_format_i18n($P, $ct_currency_decimal);
				$est_payment .= '</span>';
			}
		}

		printf( __('%s/mo', 'contempo'), $est_payment );

		echo '<script>';
			echo 'jQuery( document ).ready(function($) {';
				echo "$('#mcRate').val('" . $ct_listing_est_payment_interest_rate . '%' . "');";
				echo "$('#mcTerm').val('" . $ct_listing_est_payment_term . "');";
				echo "$('#mcDown').val('" . $down_payment . "');";
				echo "$('#mcTerm').niceSelect('update');";
			echo '});';
		echo '</script>';

	}
}

/*-----------------------------------------------------------------------------------*/
/* Listing Price Per Sq Ft/Meter */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_price_per_sqft')) {
	function ct_listing_price_per_sqft() {
		global $post;
		global $ct_options;

		$ct_currency_placement = isset( $ct_options['ct_currency_placement'] ) ? esc_attr( $ct_options['ct_currency_placement'] ) : '';
		$ct_currency_decimal = isset( $ct_options['ct_currency_decimal'] ) ? esc_attr( $ct_options['ct_currency_decimal'] ) : '';

		$price_meta = get_post_meta(get_the_ID(), '_ct_price', true);
		$price_meta= preg_replace('/[\$,]/', '', $price_meta);

		$ct_sqft = get_post_meta(get_the_ID(), '_ct_sqft', true);

		if(!empty($price_meta) && !empty($ct_sqft)) {
			if(is_numeric($price_meta) && is_numeric($ct_sqft)) {
				$ct_price_per_sqft = $price_meta / $ct_sqft;

				if($ct_currency_placement == 'after') {
					echo number_format_i18n($ct_price_per_sqft, 2);
					ct_currency();
				} else {
					ct_currency();
					echo number_format_i18n($ct_price_per_sqft, 2);
				}
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Sq Ft or Sq Meters */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_sqftsqm')) {
	function ct_sqftsqm() {
		global $ct_options;
		
		if(isset( $ct_options['ct_sq'] )) {
			if($ct_options['ct_sq'] == "sqft") {
				_e(' Sq Ft', 'contempo');
			} else if($ct_options['ct_sq'] == "sqmeters") {
				_e(' m²', 'contempo');
			} else if($ct_options['ct_sq'] == "area") {
				_e('Area', 'contempo');
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Acres or Sq Meters */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_acres')) {
	function ct_acres() {
		global $ct_options;
		
		if(isset( $ct_options['ct_acres'] )) {
			if($ct_options['ct_acres'] == "acres") {
				_e('Acres', 'contempo');
			} elseif($ct_options['ct_acres'] == "sqft") {
				_e('Sq Ft', 'contempo');
			} elseif($ct_options['ct_acres'] == "sqmeters") {
				_e('m²', 'contempo');
			} elseif($ct_options['ct_acres'] == "area") {
				_e('Area', 'contempo');
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Bed, Beds, Bedrooms or Rooms */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_bed_beds_or_bedrooms')) {
	function ct_bed_beds_or_bedrooms() {
		global $ct_options;
		
		$ct_bed_beds_or_bedrooms = isset( $ct_options['ct_bed_beds_or_bedrooms'] ) ? $ct_options['ct_bed_beds_or_bedrooms'] : '';

        if($ct_bed_beds_or_bedrooms == 'rooms') {
			_e('Rooms', 'contempo');
		} elseif($ct_bed_beds_or_bedrooms == 'bedrooms') {
			_e('Bedrooms', 'contempo');
		} elseif($ct_bed_beds_or_bedrooms == 'beds') {
			_e('Beds', 'contempo');
    	} else {
    		_e('Bed', 'contempo');
    	}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Bath, Baths or Bathrooms */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_bath_baths_or_bathrooms')) {
	function ct_bath_baths_or_bathrooms() {
		global $ct_options;
		$ct_bath_baths_or_bathrooms = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? $ct_options['ct_bath_baths_or_bathrooms'] : '';

        if($ct_bath_baths_or_bathrooms == 'bathrooms') {
			_e('Bathrooms', 'contempo');
		} elseif($ct_bath_baths_or_bathrooms == 'baths') {
			_e('Baths', 'contempo');
		} else {
    		_e('Bath', 'contempo');
    	}
	}
}

/*-----------------------------------------------------------------------------------*/
/* City, Town or Village */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_city_town_or_village')) {
	function ct_city_town_or_village() {
		global $ct_options;
		
		$ct_city_town_or_village = isset( $ct_options['ct_city_town_or_village'] ) ? $ct_options['ct_city_town_or_village'] : '';

        if($ct_city_town_or_village == 'village') {
			_e('Village', 'contempo');
		} elseif($ct_city_town_or_village == 'town') {
			_e('Town', 'contempo');
		} else {
    		_e('City', 'contempo');
    	}
	}
}

/*-----------------------------------------------------------------------------------*/
/* State, Area, Suburb, Province, Region or Parish */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_state_or_area')) {
	function ct_state_or_area() {
		global $ct_options;
		
		$ct_state_or_area = isset( $ct_options['ct_state_or_area'] ) ? $ct_options['ct_state_or_area'] : '';

        if($ct_state_or_area == 'parish') {
			_e('Parish', 'contempo');
		} elseif($ct_state_or_area == 'region') {
			_e('Region', 'contempo');
		} elseif($ct_state_or_area == 'province') {
			_e('Province', 'contempo');
		} elseif($ct_state_or_area == 'suburb') {
			_e('Suburb', 'contempo');
		} elseif($ct_state_or_area == 'area') {
			_e('Area', 'contempo');
		} else {
    		_e('State', 'contempo');
    	}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Zip or Postcode */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_zip_or_post')) {
	function ct_zip_or_post() {
		global $ct_options;
		
		$ct_zip_or_post = isset( $ct_options['ct_zip_or_post'] ) ? esc_html( $ct_options['ct_zip_or_post'] ) : '';

        if($ct_zip_or_post == 'postalcode') {
        	_e('Postal Code', 'contempo');
        } elseif($ct_zip_or_post == 'postcode') {
        	_e('Postcode', 'contempo');
        } else {
        	_e('Zipcode', 'contempo');
        }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Community, Neighborhood or District */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_community_neighborhood_or_district')) {
	function ct_community_neighborhood_or_district() {
		global $ct_options;
		
		$ct_community_neighborhood_or_district = isset( $ct_options['ct_community_neighborhood_or_district'] ) ? esc_html( $ct_options['ct_community_neighborhood_or_district'] ) : '';

        if($ct_community_neighborhood_or_district == 'municipality') {
        	_e('Municipality', 'contempo');
        } elseif($ct_community_neighborhood_or_district == 'neighborhood') {
        	_e('Neighborhood', 'contempo');
        } elseif($ct_community_neighborhood_or_district == 'suburb') {
        	_e('Suburb', 'contempo');
        } elseif($ct_community_neighborhood_or_district == 'district') {
        	_e('District', 'contempo');
        } elseif($ct_community_neighborhood_or_district == 'schooldistrict') {
        	_e('School District', 'contempo');
        } elseif($ct_community_neighborhood_or_district == 'building') {
        	_e('Building', 'contempo');
        } elseif($ct_community_neighborhood_or_district == 'complex') {
        	_e('Complex', 'contempo');
        } elseif($ct_community_neighborhood_or_district == 'borough') {
        	_e('Borough', 'contempo');
        } elseif($ct_community_neighborhood_or_district == 'sector') {
        	_e('Sector', 'contempo');
        } else {
        	_e('Community', 'contempo');
        }

	}
}

/*-----------------------------------------------------------------------------------*/
/* Property Type Tags */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_has_type')) {
	function ct_has_type( $type, $_post = null ) {
		if(empty( $type )) {
			return false;
		}

		if($_post )	{
			$_post = get_post($_post);
		} else {
			$_post =& $GLOBALS['post'];
		}

		if(!$_post ){
			return false;
		}

		$r = is_object_in_term( $_post->ID, 'property_type', $type );

		if(is_wp_error( $r )) {
			return false;
		}

		return $r;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Generate Random Listing ID */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_generate_listing_id')) {
	function ct_generate_listing_id() {

		$ct_listing_id = mt_rand(10000000, 99999999);
		echo esc_html($ct_listing_id);

	}
}

/*-----------------------------------------------------------------------------------*/
/* Agents Search Field First */
/*-----------------------------------------------------------------------------------*/

function ct_agents_search_fields_first() {
    global $ct_options;

    $ct_agents_search_fields = isset( $ct_options['ct_agents_search_fields']['enabled'] ) ? $ct_options['ct_agents_search_fields']['enabled'] : '';

    if(current($ct_agents_search_fields) == $ct_agents_search_fields[0]) {
        echo 'first';
    }
}

/*-----------------------------------------------------------------------------------*/
/* Agents Search Field Span */
/*-----------------------------------------------------------------------------------*/

function ct_agents_search_fields_span() {
    global $ct_options;

    $ct_agents_search_fields = isset( $ct_options['ct_agents_search_fields']['enabled'] ) ? $ct_options['ct_agents_search_fields']['enabled'] : '';
    $ct_agents_search_fields_count = count($ct_agents_search_fields);

    if($ct_agents_search_fields_count == 4) {
        echo 'span_3';
    } elseif($ct_agents_search_fields_count == 3) {
        echo 'span_5';
    } elseif($ct_agents_search_fields_count == 2) {
        echo 'span_9';
    }
}

/*-----------------------------------------------------------------------------------*/
/* Agents Search Input Span */
/*-----------------------------------------------------------------------------------*/

function ct_agents_search_button_span() {
    global $ct_options;

    $ct_agents_search_fields = isset( $ct_options['ct_agents_search_fields']['enabled'] ) ? $ct_options['ct_agents_search_fields']['enabled'] : '';
    $ct_agents_search_fields_count = count($ct_agents_search_fields);

    if($ct_agents_search_fields_count == 4) {
        echo 'span_3';
    } elseif($ct_agents_search_fields_count == 3) {
        echo 'span_2';
    } elseif($ct_agents_search_fields_count == 2) {
        echo 'span_3';
    }
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search Select */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_select')) {
	function ct_search_form_select($name, $taxonomy_name = null) {

		if( class_exists('WPDevCache')) {

			global $oWPDevCache;

			$cache = $oWPDevCache->get( 'ct_search_form_select_' . $name, false ); // use the ID as part of the cache name to make a per page cache

			if($cache !== false) {
				echo ct_sanitize_output( $cache );
				return;
			}

		}

		global $search_values;
		global $ct_options;

	    $ct_bed_beds_or_bedrooms = isset( $ct_options['ct_bed_beds_or_bedrooms'] ) ? $ct_options['ct_bed_beds_or_bedrooms'] : '';
	    $ct_bath_baths_or_bathrooms = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? $ct_options['ct_bath_baths_or_bathrooms'] : '';
	    $ct_zip_or_post = isset( $ct_options['ct_zip_or_post'] ) ? $ct_options['ct_zip_or_post'] : '';
	    $ct_city_town_or_village = isset( $ct_options['ct_city_town_or_village'] ) ? $ct_options['ct_city_town_or_village'] : '';
	    $ct_state_or_area = isset( $ct_options['ct_state_or_area'] ) ? $ct_options['ct_state_or_area'] : '';
	    $ct_community_neighborhood_or_district = isset( $ct_options['ct_community_neighborhood_or_district'] ) ? $ct_options['ct_community_neighborhood_or_district'] : '';

		if(!$taxonomy_name) {
			$taxonomy_name = $name;
			$tax_label = str_replace('_', ' ', $name);
			$tax_name_stripped = str_replace('ct ', '', $tax_label);

			if($tax_name_stripped == 'property type') {
				$tax_name = __('All Property Types', 'contempo');
			} elseif($tax_name_stripped == 'country') {
				$tax_name = __('All Countries', 'contempo');
			} elseif($tax_name_stripped == 'county') {
				$tax_name = __('All Counties', 'contempo');
			} elseif($tax_name_stripped == 'city' && $ct_city_town_or_village == 'town') {
				$tax_name = __('All Towns', 'contempo');
			} elseif($tax_name_stripped == 'city' && $ct_city_town_or_village == 'village') {
				$tax_name = __('All Villages', 'contempo');
			} elseif($tax_name_stripped == 'city') {
				$tax_name = __('All Cities', 'contempo');
			} elseif($tax_name_stripped == 'state' && $ct_state_or_area == 'area') {
				$tax_name = __('All Areas', 'contempo');
			} elseif($tax_name_stripped == 'state' && $ct_state_or_area == 'suburb') {
				$tax_name = __('All Suburbs', 'contempo');
			} elseif($tax_name_stripped == 'state' && $ct_state_or_area == 'province') {
				$tax_name = __('All Provinces', 'contempo');
			} elseif($tax_name_stripped == 'state' && $ct_state_or_area == 'region') {
				$tax_name = __('All Regions', 'contempo');
			} elseif($tax_name_stripped == 'state' && $ct_state_or_area == 'parish') {
				$tax_name = __('All Parishes', 'contempo');
			} elseif($tax_name_stripped == 'state') {
				$tax_name = __('All States', 'contempo');
			} elseif($tax_name_stripped == 'beds' && $ct_bed_beds_or_bedrooms == 'rooms') {
				$tax_name = __('Rooms', 'contempo');
			} elseif($tax_name_stripped == 'beds' && $ct_bed_beds_or_bedrooms == 'bedrooms') {
				$tax_name = __('Bedrooms', 'contempo');
			} elseif($tax_name_stripped == 'beds' && $ct_bed_beds_or_bedrooms == 'bed') {
				$tax_name = __('Bed', 'contempo');
			} elseif($tax_name_stripped == 'beds') {
				$tax_name = __('Beds', 'contempo');
			} elseif($tax_name_stripped == 'baths' && $ct_bath_baths_or_bathrooms == 'bathrooms') {
				$tax_name = __('Bathrooms', 'contempo');
			} elseif($tax_name_stripped == 'baths' && $ct_bath_baths_or_bathrooms == 'bath') {
				$tax_name = __('Bath', 'contempo');
			} elseif($tax_name_stripped == 'baths') {
				$tax_name = __('Baths', 'contempo');
			} elseif($tax_name_stripped == 'status') {
				$tax_name = __('All Statuses', 'contempo');
			} elseif($tax_name_stripped == 'additional features') {
				$tax_name = __('All Additional Features', 'contempo');
			} elseif($tax_name_stripped == 'community' && $ct_community_neighborhood_or_district == 'neighborhood') {
				$tax_name = __('All Neighborhoods', 'contempo');
			} elseif($tax_name_stripped == 'community' && $ct_community_neighborhood_or_district == 'district') {
				$tax_name = __('All Districts', 'contempo');
			} elseif($tax_name_stripped == 'community' && $ct_community_neighborhood_or_district == 'schooldistrict') {
				$tax_name = __('All School Districts', 'contempo');
			} elseif($tax_name_stripped == 'community' && $ct_community_neighborhood_or_district == 'suburb') {
				$tax_name = __('All Suburbs', 'contempo');
			} elseif($tax_name_stripped == 'community' && $ct_community_neighborhood_or_district == 'building') {
				$tax_name = __('All Buildings', 'contempo');
			} elseif($tax_name_stripped == 'community' && $ct_community_neighborhood_or_district == 'complex') {
				$tax_name = __('All Complexes', 'contempo');
			} elseif($tax_name_stripped == 'community' && $ct_community_neighborhood_or_district == 'sector') {
				$tax_name = __('All Sectors', 'contempo');
			} elseif($tax_name_stripped == 'community') {
				$tax_name = __('All Communities', 'contempo');
			} elseif($tax_name_stripped == 'zipcode' && $ct_zip_or_post == 'postcode') {
				$tax_name = __('All Postcodes', 'contempo');
			} elseif($tax_name_stripped == 'zipcode' && $ct_zip_or_post == 'postalcode') {
				$tax_name = __('All Postal Codes', 'contempo');
			} elseif($tax_name_stripped == 'zipcode') {
				$tax_name = __('All Zipcodes', 'contempo');
			} elseif($tax_name_stripped == 'pet friendly') {
				$tax_name = __('Pet Friendly?', 'contempo');
			} elseif($tax_name_stripped == 'furnished unfurnished') {
				$tax_name = __('Furnished/Unfurnished?', 'contempo');
			}
		}

		$ct_header_listing_search_ajaxify_country_state_city = isset( $ct_options['ct_header_listing_search_ajaxify_country_state_city'] ) ? esc_html( $ct_options['ct_header_listing_search_ajaxify_country_state_city'] ) : '';

		$class = '';

		if ( $ct_header_listing_search_ajaxify_country_state_city == 'yes' ) {

			$ct_header_adv_search_fields = isset( $ct_options['ct_header_adv_search_fields']['enabled'] ) ? $ct_options['ct_header_adv_search_fields']['enabled'] : '';


			if ( $name == 'state' && array_key_exists( 'header_country', $ct_header_adv_search_fields ) ) {
				$class = ' disabled ';
			} else if ( $name == 'city' && array_key_exists( 'header_state', $ct_header_adv_search_fields ) ) {
				$class = ' disabled ';
			} else if ( $name == 'zipcode' && array_key_exists( 'header_city', $ct_header_adv_search_fields ) ) {
				$class = ' disabled ';
			}

		}

		$cache = '<select class="'.$class.'" id="ct_'.esc_html($name).'" name="ct_'.esc_html($name).'">
			<option value="">'.esc_html(ucfirst($tax_name)).'</option>';

		$select_dropdown_terms = get_terms($taxonomy_name, 
			array(
				'hide_empty' => 'true',
				'orderby' => 'name',
	            'order' => 'ASC',
		) );
	
		if($class == '' && !is_wp_error( $select_dropdown_terms )) {

			foreach( $select_dropdown_terms as $t ) {

				if( isset( $_GET['ct_' . $name] ) && $_GET['ct_' . $name] == $t->slug) {
					$selected = 'selected=selected ';
				} else {
					$selected = '';
				}

				$tax_name = str_replace('-', ' ', $t->name);
				$tax_name = strtolower($tax_name);

				if($name == 'state') {
					$cache = $cache.'<option '.esc_html($selected).' value="'.esc_attr($t->slug).'">'.strtoupper($tax_name).'</option>';
				} else {
					$cache = $cache.'<option '.esc_html($selected).' value="'.esc_attr($t->slug).'">'.ucwords($tax_name).'</option>';
				}

				$childterms = get_terms($taxonomy_name, array('orderby' => 'slug', 'parent' => $t->term_id));
				
				// Check to see if there any any error thrown by wp
				if(!is_wp_error( $childterms )) {
					if($childterms) {
						foreach($childterms as $key => $childterm) {
							$childterm_name = str_replace('-', ' ', $childterm->name);
							$cache = $cache.'<option class="ct-child-term" '.esc_html($selected). ' value="' . esc_attr($childterm->slug). '"> — ' . ucwords($childterm_name) . '</option>';
						}
					}
				}

			}
		}

		$cache = $cache . '</select>';

		if(class_exists('WPDevCache')) {
			$oWPDevCache->set( 'ct_search_form_select_' . $name, $cache, 900 ); // expire in 15 minutes
		}

		echo ct_sanitize_output( $cache );

	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search Brokerage Select */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_brokerage_select')) {
	function ct_search_form_brokerage_select() {

		if( class_exists('WPDevCache')) {

			global $oWPDevCache;

			$cache = $oWPDevCache->get( 'ct_search_form_select_brokerages', false ); 

			if($cache !== false) {
				echo ct_sanitize_output( $cache );
				return;
			}

		}

		global $post;

		$brokerage = new WP_Query(array(
	        'post_type' => 'brokerage',
	        'posts_per_page' => -1,
	        'orderby' => 'name',
            'order' => 'ASC',
	    ));

		$cache = '<select id="ct_brokerage" name="ct_brokerage">';
        	$cache .= '<option value="">' . __('All Brokerages', 'contempo') . '</option>';

		    if($brokerage->have_posts()) : while($brokerage->have_posts()) : $brokerage->the_post();
	            $ct_brokerage_id = get_the_ID();
	            $ct_brokerage_permalink = get_permalink();
	        	$ct_brokerage_name = strtolower(get_the_title());

	        	if(isset($_GET['ct_brokerage']) && $_GET['ct_brokerage'] == $ct_brokerage_id) {
					$selected = 'selected=selected ';
				} else {
					$selected = '';
				}

		        $cache .= '<option value="' . esc_attr($ct_brokerage_id) .'" '. esc_html($selected) . '>' . ucwords($ct_brokerage_name) . '</option>';

		    endwhile; endif; wp_reset_postdata();

	    $cache .= '</select>';

	    if(class_exists('WPDevCache')) {
			$oWPDevCache->set( 'ct_search_form_select_brokerages', $cache, 900 ); // expire in 15 minutes
		}

		echo ct_sanitize_output( $cache );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search School District Select */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_school_district_select')) {
	function ct_search_form_school_district_select() {

		if( class_exists('WPDevCache')) {

			global $oWPDevCache;

			$cache = $oWPDevCache->get( 'ct_search_form_select_school_districts', false ); 

			if($cache !== false) {
				echo ct_sanitize_output( $cache );
				return;
			}

		}

		global $wpdb;

        $meta_key = '_ct_idx_schools_school_district';
        $data = $wpdb->get_results($wpdb->prepare( "SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s ORDER BY meta_value ASC", $meta_key) , ARRAY_N  );

	    $cache = '<select id="ct_school_district" name="ct_school_district">';
		    $cache .= '<option value="">' . __('All School Districts', 'contempo') . '</option>';
		    foreach( $data as $value ) { 
		    	if(isset($_GET['ct_school_district']) && $_GET['ct_school_district'] == $value[0]) {
					$selected = 'selected=selected ';
				} else {
					$selected = '';
				}
		    
		        $cache .= '<option value="' . $value[0] . '"' . esc_html($selected) . '>' . $value[0] . '</option>';
		    }
		$cache .= '</select>';

		if(class_exists('WPDevCache')) {
			$oWPDevCache->set( 'ct_search_form_select_school_districts', $cache, 900 ); // expire in 15 minutes
		}

		echo ct_sanitize_output( $cache );

	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search Checkboxes - Toggles - Property Type */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_checkboxes_toggles_property_type')) {
	function ct_search_form_checkboxes_toggles_property_type($name, $taxonomy_name = null) {
		global $search_values;
		global $ct_options;

		if(!$taxonomy_name) {
			$taxonomy_name = $name;
		}
		
		$checkboxes_toggle = get_terms($taxonomy_name, 'parent=0&hide_empty=true');

		if(!is_wp_error( $checkboxes_toggle )) {
			echo '<ul class="toggles">';
				$count = 0;
				foreach( $checkboxes_toggle as $t ) {

					if(isset($_GET['ct_property_type']) && is_array($_GET['ct_property_type'])) {
						$property_types = $_GET['ct_property_type'];
					} elseif(isset($_GET['ct_property_type'])) {
						$property_types = $_GET['ct_property_type'];
					}

					if(isset($_GET['ct_property_type']) && is_array($_GET['ct_property_type']) && in_array($t->slug, $property_types) ) {
						$checked = 'checked';
					} elseif(isset($_GET['ct_property_type']) && $t->slug === $property_types) {	
						$checked = 'checked';
					} else {
						$checked = '';
					}

					$tax_name = str_replace('-', ' ', $t->name);
					$tax_name = strtolower($tax_name);

					echo '<li class="toggle col span_12 first">';
						echo '<span class="left">' . ucwords($tax_name) . '</span>';
						echo '<div class="switch right">';
							echo '<input class="switch-input" type="checkbox" class="ct_' . esc_html($name) . '" name="ct_' . esc_html($name) . '[]" value="' . esc_attr($t->slug) . '" ' . esc_html($checked) . '><label class="switch-label">' . ucwords($tax_name) . '</label>';
						echo '</div>';
					echo '</li>';

					$count++;

					if($count % 4 == 0) {
						//echo '<div class="clear"></div>';
					}
				}
			echo '</ul>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search Checkboxes - Toggles - Status */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_checkboxes_toggles_status')) {
	function ct_search_form_checkboxes_toggles_status($name, $taxonomy_name = null) {
		global $search_values;
		global $ct_options;

		if(!$taxonomy_name) {
			$taxonomy_name = 'ct_status';
		}
		
		$checkboxes_toggle = get_terms($taxonomy_name, 'parent=0&hide_empty=true');

		if(!is_wp_error( $checkboxes_toggle )) {
			echo '<ul class="toggles">';
				$count = 0;
				foreach( $checkboxes_toggle as $t ) {

					if(isset($_GET['ct_ct_status_multi']) && is_array($_GET['ct_ct_status_multi'])) {
						$status = $_GET['ct_ct_status_multi'];
					} elseif(isset($_GET['ct_ct_status_multi'])) {
						$status = $_GET['ct_ct_status_multi'];
					}

					if(isset($_GET['ct_ct_status_multi']) && in_array($t->slug, $status) ) {
						$checked = 'checked';
					} else {
						$checked = '';
					}

					$tax_name = str_replace('-', ' ', $t->name);
					$tax_name = strtolower($tax_name);

					echo '<li class="toggle col span_12 first">';
						echo '<span class="left">' . ucwords($tax_name) . '</span>';
						echo '<div class="switch right">';
							echo '<input class="switch-input" type="checkbox" class="ct_ct_status_multi" name="ct_ct_status_multi[]" value="' . esc_attr($t->slug) . '" ' . esc_html($checked) . '><label class="switch-label">' . ucwords($tax_name) . '</label>';
						echo '</div>';
					echo '</li>';

					$count++;

					if($count % 4 == 0) {
						//echo '<div class="clear"></div>';
					}
				}
			echo '</ul>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search Checkboxes - Toggles - City */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_checkboxes_toggles_city')) {
	function ct_search_form_checkboxes_toggles_city($name, $taxonomy_name = null) {
		global $search_values;
		global $ct_options;

		if(!$taxonomy_name) {
			$taxonomy_name = 'city';
		}
		
		$checkboxes_toggle = get_terms($taxonomy_name, 'parent=0&hide_empty=true');

		if(!is_wp_error( $checkboxes_toggle )) {
			echo '<ul class="toggles">';
				$count = 0;
				foreach( $checkboxes_toggle as $t ) {

					if(isset($_GET['ct_city_multi']) && is_array($_GET['ct_city_multi'])) {
						$city = $_GET['ct_city_multi'];
					} elseif(isset($_GET['ct_city_multi'])) {
						$city = $_GET['ct_city_multi'];
					}

					if(isset($_GET['ct_city_multi']) && in_array($t->slug, $city) ) {
						$checked = 'checked';
					} else {
						$checked = '';
					}

					$tax_name = str_replace('-', ' ', $t->name);
					$tax_name = strtolower($tax_name);

					echo '<li class="toggle col span_12 first">';
						echo '<span class="left">' . ucwords($tax_name) . '</span>';
						echo '<div class="switch right">';
							echo '<input class="switch-input" type="checkbox" class="ct_city_multi" name="ct_city_multi[]" value="' . esc_attr($t->slug) . '" ' . esc_html($checked) . '><label class="switch-label">' . ucwords($tax_name) . '</label>';
						echo '</div>';
					echo '</li>';

					$count++;

					if($count % 4 == 0) {
						//echo '<div class="clear"></div>';
					}
				}
			echo '</ul>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search Checkboxes - Toggles - State */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_checkboxes_toggles_state')) {
	function ct_search_form_checkboxes_toggles_state($name, $taxonomy_name = null) {
		global $search_values;
		global $ct_options;

		if(!$taxonomy_name) {
			$taxonomy_name = 'state';
		}
		
		$checkboxes_toggle = get_terms($taxonomy_name, 'parent=0&hide_empty=true');

		if(!is_wp_error( $checkboxes_toggle )) {
			echo '<ul class="toggles">';
				$count = 0;
				foreach( $checkboxes_toggle as $t ) {

					if(isset($_GET['ct_state_multi']) && is_array($_GET['ct_state_multi'])) {
						$city = $_GET['ct_state_multi'];
					} elseif(isset($_GET['ct_state_multi'])) {
						$city = $_GET['ct_state_multi'];
					}

					if(isset($_GET['ct_state_multi']) && in_array($t->slug, $city) ) {
						$checked = 'checked';
					} else {
						$checked = '';
					}

					$tax_name = str_replace('-', ' ', $t->name);
					$tax_name = strtolower($tax_name);

					echo '<li class="toggle col span_12 first">';
						echo '<span class="left">' . strtoupper($tax_name) . '</span>';
						echo '<div class="switch right">';
							echo '<input class="switch-input" type="checkbox" class="ct_state_multi" name="ct_state_multi[]" value="' . esc_attr($t->slug) . '" ' . esc_html($checked) . '><label class="switch-label">' . strtoupper($tax_name) . '</label>';
						echo '</div>';
					echo '</li>';

					$count++;

					if($count % 4 == 0) {
						//echo '<div class="clear"></div>';
					}
				}
			echo '</ul>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search Checkboxes - Toggles - County */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_checkboxes_toggles_county')) {
	function ct_search_form_checkboxes_toggles_county($name, $taxonomy_name = null) {
		global $search_values;
		global $ct_options;

		if(!$taxonomy_name) {
			$taxonomy_name = 'county';
		}
		
		$checkboxes_toggle = get_terms($taxonomy_name, 'parent=0&hide_empty=true');

		if(!is_wp_error( $checkboxes_toggle )) {
			echo '<ul class="toggles">';
				$count = 0;
				foreach( $checkboxes_toggle as $t ) {

					if(isset($_GET['ct_county_multi']) && is_array($_GET['ct_county_multi'])) {
						$city = $_GET['ct_county_multi'];
					} elseif(isset($_GET['ct_county_multi'])) {
						$city = $_GET['ct_county_multi'];
					}

					if(isset($_GET['ct_county_multi']) && in_array($t->slug, $city) ) {
						$checked = 'checked';
					} else {
						$checked = '';
					}

					$tax_name = str_replace('-', ' ', $t->name);
					$tax_name = strtolower($tax_name);

					echo '<li class="toggle col span_12 first">';
						echo '<span class="left">' . ucwords($tax_name) . '</span>';
						echo '<div class="switch right">';
							echo '<input class="switch-input" type="checkbox" class="ct_county_multi" name="ct_county_multi[]" value="' . esc_attr($t->slug) . '" ' . esc_html($checked) . '><label class="switch-label">' . ucwords($tax_name) . '</label>';
						echo '</div>';
					echo '</li>';

					$count++;

					if($count % 4 == 0) {
						//echo '<div class="clear"></div>';
					}
				}
			echo '</ul>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search Checkboxes - Header */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_checkboxes_header')) {
	function ct_search_form_checkboxes_header($name, $taxonomy_name = null) {
		global $search_values;
		global $ct_options;

		if(!$taxonomy_name) {
			$taxonomy_name = $name;
		}
		$form_checkboxes_taxonomy = get_terms($taxonomy_name, 'hide_empty=true');

		if(!is_wp_error( $form_checkboxes_taxonomy )) {
			echo '<ul class="check-list">';
				$count = 0;
				foreach( $form_checkboxes_taxonomy as $t ) {
					if(isset($_GET['ct_additional_features']) && is_array($_GET['ct_additional_features'])) {
						$additional_features = $_GET['ct_additional_features'];
					} elseif(isset($_GET['ct_additional_features'])) {
						$additional_features = $_GET['ct_additional_features'];
					}

					if( isset($_GET['ct_additional_features']) && in_array($t->slug, $additional_features) ) {
						$checked = 'checked';
					} else {
						$checked = '';
					}

					echo '<li class="col span_3';
						if($count % 4 == 0) {
							echo ' first';
						}
					echo '">';
						echo '<input class="custom-select" type="checkbox" class="ct_' . esc_html($name) .'" name="ct_' . esc_html($name) . '[]" value="' . esc_attr($t->slug) .'" ' . esc_html($checked) . '><span>' . esc_html($t->name) . '</span>';
					echo '</li>';
					
					$count++;

					if($count % 4 == 0) {
						echo '<div class="clear"></div>';
					}
				}
			echo '</ul>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search Checkboxes - Popular Features */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_checkboxes_popular_features')) {
	function ct_search_form_checkboxes_popular_features($name, $taxonomy_name = null) {
		global $search_values;
		global $ct_options;

		if(!$taxonomy_name) {
			$taxonomy_name = $name;
		}

		$features_to_exclude = get_terms(
		    array(
		        'fields'  => 'ids',
		        'slug'    => array( 
		            '0-0',
		            '000-1',
		            '125',
		            'fireplaces-0',
		            'n-k', 
		            'none-known', 
		            'none-unknown',
		            'one',
		            'seeremarks',
		        ),
		        'taxonomy' => 'additional_features',
		    )
		);

		if( !is_wp_error( $features_to_exclude ) && count($features_to_exclude) > 0){
		    $ids_to_exclude = $features_to_exclude; 
		}

		$popular_features = get_terms(array(
		    'taxonomy' => 'additional_features',
		    'number'     => 6,
		    'orderby' => 'count',
		    'order' => 'DESC',
		    'exclude' => $ids_to_exclude,
		));

		$checked_features = array();
		
		if(!is_wp_error( $popular_features )) {
			echo '<ul class="check-list">';
				$count = 0;
				foreach($popular_features as $pf) {

					if(isset($_GET['ct_additional_features']) && is_array($_GET['ct_additional_features'])) {
						$additional_features = $_GET['ct_additional_features'];
					} elseif(isset($_GET['ct_additional_features'])) {
						$additional_features = $_GET['ct_additional_features'];
					}

					if( isset($_GET['ct_additional_features']) && in_array($pf->slug, $additional_features) ) {
						$checked = 'checked';
						$checked_features[] = $pf->slug;
					} else {
						$checked = '';
					}

					$pf_name = $pf->name;

					echo '<li class="col span_6';
						if($count % 4 == 0) {
							echo ' first';
						}
					echo '">';
						echo '<input class="custom-select" type="checkbox" class="ct_' . esc_html($name) . '" name="ct_' . esc_html($name) . '[]" value="' . esc_attr($pf->slug) . '" ' . esc_html($checked) . '><span>' . mb_strimwidth($pf_name, 0, 26, '&#8230;') . '</span>';
					echo '</li>';
					
					$count++;

					if($count % 4 == 0) {
						echo '<div class="clear"></div>';
					}
				}
			echo '</ul>';
		}
		return $checked_features;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search Checkboxes */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_checkboxes')) {
	function ct_search_form_checkboxes($name, $taxonomy_name = null) {
		global $search_values;
		global $ct_options;

		if(!$taxonomy_name) {
			$taxonomy_name = $name;
		}

		echo '<ul class="check-list">';
			$count = 0;
			foreach( get_terms($taxonomy_name, 'hide_empty=true') as $t ) {
				if(isset( $_GET['ct_' . $name] ) && $_GET['ct_' . $name] == $t->slug) {
					$checked = 'checked';
				} else {
					$checked = '';
				}

				echo '<li class="col span_4';
					if($count % 3 == 0) {
						echo ' first';
					}
				echo '">';
					echo '<input type="checkbox" id="ct_' . esc_html($name) . '" name="ct_' . esc_html($name) . '[]" value="' . esc_attr($t->slug) . '" ' . esc_html($checked) . '><span>' . esc_html($t->name) . '</span>';
				echo '</li>';
				
				$count++;

				if($count % 3 == 0) {
					echo '<div class="clear"></div>';
				}
			}
		echo '</ul>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Edit Listings Select */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_edit_listing_form_select')) {
	function ct_edit_listing_form_select($name, $taxonomy_name = null) {
		global $search_values;

		if(!$taxonomy_name) {
			$taxonomy_name = $name;
		}

		echo '<select id="ct_' . esc_html($name) . '" name="ct_' . esc_html($name) . '">';
			echo '<option value="0">' . __('Any', 'contempo') . '</option>';
			foreach( get_terms($taxonomy_name, 'hide_empty=true') as $t ) {
				if($ct_property_type == $t->slug) {
					$selected = 'selected="selected" ';
				} else {
					$selected = '';
				}
				echo '<option ' . esc_html($selected) . ' value="' . esc_attr($t->slug) . '">' . esc_html($t->name) . '</option>';
			}
		echo '</select>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Submit Listings Select */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_submit_listing_form_select')) {
	function ct_submit_listing_form_select($name, $taxonomy_name = null) {
		global $ct_options, $search_values;

		$ct_front_submit_type = isset( $ct_options['ct_front_submit_type'] ) ? esc_html( $ct_options['ct_front_submit_type'] ) : '';
		$ct_front_submit_status = isset( $ct_options['ct_front_submit_status'] ) ? esc_html( $ct_options['ct_front_submit_status'] ) : '';

		$ct_property_type = '';

		if(!$taxonomy_name) {
			$taxonomy_name = $name;
		}

		$terms = get_terms([
		    'taxonomy' => $taxonomy_name,
		    'hide_empty' => false,
		]);

		echo '<select id="ct_' . esc_html($name) . '" name="ct_' . esc_html($name) . '"';
			if($ct_front_submit_type == 'required' && $name == 'property_type' || $ct_front_submit_status == 'required' && $name == 'ct_status') { echo 'required'; }
		echo '>';
			echo '<option value="0">' . __('Any', 'contempo') . '</option>';
			foreach( $terms as $t ) {
				if ($ct_property_type == $t->slug) { $selected = 'selected="selected" '; } else { $selected = ''; }
				echo '<option ' . esc_html($selected) . 'value="' . esc_attr($t->slug) . '">' . ucfirst(esc_html($t->name)) . '</option>';
			}
		echo '</select>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Yelp Fusion API */
/*-----------------------------------------------------------------------------------*/

// API constants
$API_HOST = 'https://api.yelp.com';
$SEARCH_PATH = '/v3/businesses/search';
$BUSINESS_PATH = '/v3/businesses/';

/**
 * Makes a request to the Yelp API and returns the response
 *
 * @param    $host    The domain host of the API
 * @param    $path    The path of the API after the domain.
 * @param    $url_params    Array of query-string parameters.
 *
 * @return   The JSON response from the request
 */
function request($host, $path, $url_params = array()) {

	global $ct_options;
	
	if(class_exists( 'CT_RealEstate7_Helper' )) {
		return CT_RealEstate7_Helper::yelp_request( $host, $path, $url_params, $ct_options );
	}
	
	return '';
}

/**
 * Query the Search API by a search term and location
 *
 * @param    $term        The search term passed to the API
 * @param    $location    The search location passed to the API
 *
 * @return   The JSON response from the request
 */

function search($term, $location, $limit) {
    global $ct_options;
    $radius = isset($ct_options['ct_google_places_radius'] ) ? esc_html( $ct_options['ct_google_places_radius']) : '';

    $url_params = array();

    $url_params['term'] = $term;
    $url_params['location'] = $location;
    $url_params['limit'] = $limit;

    return request($GLOBALS['API_HOST'], $GLOBALS['SEARCH_PATH'], $url_params);
}

/**
 * Query the Business API by business_id
 *
 * @param    $business_id    The ID of the business to query
 *
 * @return   The JSON response from the request
 */

function get_business($business_id) {
    $business_path = $GLOBALS['BUSINESS_PATH'] . urlencode($business_id);

    return request($GLOBALS['API_HOST'], $business_path);
}

/**
 * Queries the API by the input values from the user
 *
 * @param    $term        The search term to query
 * @param    $location    The location of the business to query
 * @param    $limit    	  The number of the businesses to query
 */

if(!function_exists('ct_query_yelp_api')) {
	function ct_query_yelp_api($term, $location, $limit) {
	    $response = json_decode(search($term, $location, $limit));

	    if(!empty($response)){
		    $business_id = $response->businesses[0]->id;

		    global $ct_options;

			$ct_yelp_miles_kilometers = isset($ct_options['ct_yelp_miles_kilometers'] ) ? esc_html( $ct_options['ct_yelp_miles_kilometers'] ) : '';
			$ct_yelp_links = isset($ct_options['ct_yelp_links'] ) ? esc_html( $ct_options['ct_yelp_links'] ) : '';
			$ct_yelp_cc = isset($ct_options['ct_yelp_cc'] ) ? esc_html( $ct_options['ct_yelp_cc'] ) : '';

		    if(!empty($response->businesses)) {
			    echo '<ul class="marB20 yelp-nearby">';
				    $i = 0;
			    	foreach($response->businesses as $business) {

			    		$business_distance_meters = $response->businesses[$i]->distance;

			    		if($ct_yelp_miles_kilometers == 'kilometers') {
				    		$business_distance_miles = $business_distance_meters * 0.001;
				    	} else {
				    		$business_distance_miles = $business_distance_meters * 0.000621371192;
				    	}

					    echo '<li>';
					    	echo '<div class="col span_9 first">';
						    	echo '<a href="' . $response->businesses[$i]->url . '" target="' . $ct_yelp_links . '">' . $response->businesses[$i]->name . '</a> <span class="business-distance muted">(' . round($business_distance_miles, 2);
						    	 if($ct_yelp_miles_kilometers == 'km') {
						    	 	_e(' km', 'contempo');
						    	 } else {
						    	 	_e(' mi', 'contempo');
						    	 }
						    	 echo ')</span>';
					    	echo '</div>';
					    	echo '<div class="col span_3">';
						    	echo '<span class="yelp-rating left">';
						    		$float_rating = (float)$response->businesses[$i]->rating;
									$has_half_star = ($float_rating * 10) % 10;
									$star_count = (int)$float_rating;
									if($has_half_star) {
									    echo '<img src="' . get_template_directory_uri() . '/images/stars/small_' . $star_count . '_half.png" srcset="' . get_template_directory_uri() . '/images/stars/small_' . $star_count . '_half@2x.png 2x" />';
									} else {
									    echo '<img src="' . get_template_directory_uri() . '/images/stars/small_' . $star_count . '.png" srcset="' . get_template_directory_uri() . '/images/stars/small_' . $star_count . '@2x.png 2x" />';
									}
							   	echo '</span>';
						    	echo '<span class="review-count muted right">' . $response->businesses[$i]->review_count . ' ' . __('reviews', 'contempo') . '</span></a>';
						    echo '</div>';
						    	echo '<div class="clear"></div>';
					    echo '</li>';
					    $i++;
					}
			    echo '</ul>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Walk Score */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_get_walkscore')) {
	
	function ct_get_walkscore($lat, $lon, $address) {

		global $ct_options;

		$ct_walkscore_api_key = isset( $ct_options['ct_walkscore_apikey'] ) ? esc_html( $ct_options['ct_walkscore_apikey'] ) : '';

		$address = urlencode($address);
		$url = 'https://api.walkscore.com/score?format=json&address=$address';
		$url .= '&lat=$lat&lon=$lon&wsapikey=$ct_walkscore_api_key';
		
		$request = wp_remote_get($url);
		$str = wp_remote_retrieve_body( $request );
	
		return $str;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Numeric Pagination */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_numeric_pagination')) {

	function ct_numeric_pagination() {
		
		if(is_singular()) {
			return;
		}

		global $wp_query;

		// Stop execution if there's only 1 page
		if( $wp_query->max_num_pages <= 1 ) {
			return;
		}

		global $paged;

		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$max   = intval( $wp_query->max_num_pages );

		// Add current page to the array
		if( $paged >= 1 ) {
			$links[] = $paged;
		}
		
		$pos = strpos( ct_get_server_info('REQUEST_URI'), 'wp-admin/admin-ajax.php');

		$original_request = ct_get_server_info('REQUEST_URI');

		//	Add the pages around the current page to the array
		if($paged >= 3) {
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}

		if(($paged + 2 ) <= $max) {
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}

		echo '<div class="pagination"><ul>' . "\n";

		// Previous Post Link
		if( get_previous_posts_link() ) {
			printf( '<li id="prev-page-link">%s</li>' . "\n", get_previous_posts_link() );
		}

		// Link to first page, plus ellipses if necessary
		if(!in_array( 1, $links)) {
			$class = 1 == $paged ? ' class="current"' : '';

			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

			if(!in_array( 2, $links)) {
				echo '<li>&#8230;</li>';
			}
		}

		// Link to current page, plus 2 pages in either direction if necessary
		sort($links);		
		foreach((array) $links as $link) {
			$class = $paged == $link ? ' class="current"' : '';
			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
		}

		// Link to last page, plus ellipses if necessary
		if(!in_array( $max, $links )) {
			if(!in_array( $max - 1, $links )) {
				echo '<li>&#8230;</li>' . "\n";
			}

			$class = $paged == $max ? ' class="current"' : '';
			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
		}

		// Next Post Link
		if( get_next_posts_link() ) {
			printf( '<li id="next-page-link">%s</li>' . "\n", get_next_posts_link() );
		}

		echo '<div class="clear"></div>';
		echo '</ul></div>' . "\n";

	}
}

/*-----------------------------------------------------------------------------------*/
/* Pagination */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_pagination')) {
	function ct_pagination($pages = '', $range = 2) {
	     $showitems = ($range * 2)+1;

	     global $paged;

	     if(empty($paged)) {$paged = 1;}

	     if($pages == '') {
	         global $wp_query;
	         
	         $pages = $wp_query->max_num_pages;

	         if(!$pages) {
	             $pages = 1;
	         }
	     }

	     if(1 != $pages) {
	         echo '<div class="pagination">';
		         if($paged > 2 && $paged > $range+1 && $showitems < $pages) {
		         	echo '<a href="' . get_pagenum_link(1) . '">&laquo;</a>';
		         }
		         if($paged > 1 && $showitems < $pages) {
		         	echo '<a href="' . get_pagenum_link($paged - 1) . '">&lsaquo;</a>';
		         }

		         for ($i=1; $i <= $pages; $i++) {
		             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
		             	if($paged == $i){
			                 echo '<span class="current">' . $i . '</span>';
			             } else {
			             	echo '<a href="' . get_pagenum_link($i) . '" class="inactive">' . $i . '</a>';
		             	}
		             }
		         }

		         if ($paged < $pages && $showitems < $pages) {
		         	echo '<a href="' . get_pagenum_link($paged + 1) . '">&rsaquo;</a>';
		         }
		         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) {
		         	echo '<a href="' . get_pagenum_link($pages) . '">&raquo;</a>';
		         }
				 	echo '<div class="clear"></div>';
	         echo '</div>';
	     }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Get the Slug */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_the_slug')) {
	function ct_the_slug() {
	    $post_data = get_post($post->ID, ARRAY_A);
	    $slug = $post_data['post_name'];
	    return $slug;
	}
}

/*-----------------------------------------------------------------------------------*/
/*	Get image ID from URL
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_get_attachment_id_from_src')) {
	function ct_get_attachment_id_from_src($image_src) {
		global $wpdb;
		
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
		$id = $wpdb->get_var($query);
		return $id;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Read More Link */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_read_more_link')) {
	function ct_read_more_link() {
		global $ct_options;
		
		$ct_read_more = $ct_options['ct_read_more']; ?>
		
		<a class="read-more right" href="<?php the_permalink(); ?>'">
			<?php if($ct_read_more) {
				echo esc_html($ct_read_more);
			} else {
				echo __('Read more', 'contempo') . ' <em>&rarr;</em>';
			} ?>
		</a>
	<?php }
}

/*-----------------------------------------------------------------------------------*/
/* Custom Author */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_author')) {
	function ct_author() {
		global $post;
		
		if(get_post_meta($post->ID, '_ct_custom_author', true)) {
			echo get_post_meta($post->ID, '_ct_custom_author', true);
		} else {
			the_author_meta('display_name');
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Archive & Search Header */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_archive_header')) {
	function ct_archive_header() {

		global $post;

		if ( is_category() ) :
			single_cat_title();

		elseif(is_home() || is_front_page() ) :
			_e('Home', 'contempo');
		elseif(is_search() ) :
			printf( __( 'Search Results for: %s', 'contempo' ), '<span>' . get_search_query() . '</span>' );

		elseif ( is_tag() ) :
			single_tag_title();

		elseif ( is_author() ) :
			printf( __( 'Author: %s', 'contempo' ), '<span class="vcard">' . get_the_author() . '</span>' );

		elseif ( is_day() ) :
			printf( __( 'Day: %s', 'contempo' ), '<span>' . get_the_date() . '</span>' );

		elseif ( is_month() ) :
			printf( __( 'Month: %s', 'contempo' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'contempo' ) ) . '</span>' );

		elseif ( is_year() ) :
			printf( __( 'Year: %s', 'contempo' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'contempo' ) ) . '</span>' );

		else :
			_e('Archives', 'contempo');

		endif;

	}
}

/*-----------------------------------------------------------------------------------*/
/* Change Author/Agent Permalink  */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_change_author_permalinks')) {
	function ct_change_author_permalinks() {
	  global $ct_options, $wp_rewrite;
	  
	  $ct_agent_single_slug = isset( $ct_options['ct_agent_single_slug'] ) ? esc_html( $ct_options['ct_agent_single_slug'] ) : 'agents';

	  $wp_rewrite->author_base = $ct_agent_single_slug;
	  $wp_rewrite->flush_rules();
	}
}
add_action('init', 'ct_change_author_permalinks');

/*-----------------------------------------------------------------------------------*/
/* Add Categories to Attachments */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_add_categories_to_attachments')) {
	function ct_add_categories_to_attachments() {
		register_taxonomy_for_object_type( 'category', 'attachment' );
	}
}
add_action( 'init' , 'ct_add_categories_to_attachments' );

/*-----------------------------------------------------------------------------------*/
/* Display Featured Category Image */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_display_category_image')) {
	function ct_display_category_image() {

		global $post;
		global $wp_query;

		$args = null;

		if( !is_object($post) ){
			return;
		}

		if(is_archive()) {
			$currentcat = get_queried_object();
			if(!empty($currentcat)) {

				$currentcatname = $currentcat->slug;

				$args = array(
					'post_type' => 'attachment',
					'post_status'=>'inherit',
					'category_name' => $currentcatname,
				);
			}
		} elseif(is_search()) {
			$args = array(
				'post_type' => 'attachment',
				'post_status'=>'inherit'
			);
		}

		$query = new WP_Query( $args );

		while( $query->have_posts() ) : $query->the_post();

			echo'<style type="text/css">';
			echo '#archive-header { background: url(';
				echo wp_get_attachment_url( $post->ID, 'large' );
			echo ') no-repeat center center; background-size: cover;}';
			echo '</style>';

		endwhile;

		wp_reset_postdata();
	}
}

/*-----------------------------------------------------------------------------------*/
/* Post Social */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_post_social')) {
	function ct_post_social() { 
		global $post;
		?>

		<div class="col span_12 first post-social">
			<h6><?php esc_html_e('Share This', 'contempo'); ?></h6>

			<ul class="social">
		        <li class="facebook">
					<?php 
						// Facebook Sharer
						$fb_t = esc_html__('Check out this great article on', 'contempo');
						$fb_t .= ' ' . get_bloginfo('name') . ' &mdash; ' . get_the_title( $post->ID );
						$params = array(
							'u' => get_the_permalink( $post->ID ),
							'quote' => $fb_t
						);
						$fb_sharer_url = add_query_arg( $params, 'https://www.facebook.com/sharer/sharer.php' );
					?>
					<a href="javascript:void(0);" onclick="popup('<?php echo esc_url( $fb_sharer_url ); ?>', 'facebook',658,225);">
						<i class="fa fa-facebook"></i>
					</a>
				</li>
		        <li class="twitter">
					<?php 
						// Twitter Sharer
						$twitter_text = esc_html__('Check out this great article on', 'contempo');
						$twitter_text .= ' ' . get_bloginfo('name') . ' &mdash; ' . get_the_title( $post->ID ) . ' &mdash; ';

						$params = array(
							'url' => get_the_permalink( $post->ID ),
							'text' => $twitter_text
						);
						$twitter_sharer_url = add_query_arg( $params, 'https://www.twitter.com/share' );
					?>
					<a href="javascript:void(0);" onclick="popup('<?php echo esc_url( $twitter_sharer_url ); ?>', 'twitter',500,260);">
						<i class="fa fa-twitter"></i>
					</a>
				</li>
		        <li class="linkedin">
					<?php
						// LinkedIn Sharer
						$params = array(
							'url' => get_the_permalink( $post->ID )
						);
						$linkedin_sharer_url = add_query_arg( $params, 'https://www.linkedin.com/sharing/share-offsite');
					?>
					<a href="javascript:void(0);" onclick="popup('<?php echo esc_url( $linkedin_sharer_url ); ?>', 'linkedin',560,600);">
						<i class="fa fa-linkedin"></i>
					</a>
				</li>
		    </ul>
	    </div>
	    	<div class="clear"></div>

	<?php }
}

/*-----------------------------------------------------------------------------------*/
/* Post Tags */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_post_tags')) {
	function ct_post_tags() {
		if(get_the_tag_list()) {
		    echo get_the_tag_list('<ul class="tags"><li><i class="fa fa-tag"></i></li><li>',',</li><li> ','</li></ul>');
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Author Info */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_author_info')) {
	function ct_author_info() {

		global $ct_options;

		$ct_author_img = isset( $ct_options['ct_author_img'] ) ? $ct_options['ct_author_img'] : '';
		$facebookurl = get_the_author_meta('facebookurl');
		$twitterhandle = get_the_author_meta('twitterhandle');
		$linkedinurl = get_the_author_meta('linkedinurl');
		$gplus = get_the_author_meta('gplus');

		echo '<div id="authorinfo">';
			if($ct_author_img == 'yes') {
				echo '<h5 class="marB30">' . __('About The Author', 'contempo') . '</h5>';
				echo '<div class="col span_3 first">';
			       if(get_the_author_meta('ct_profile_url')) {
						echo '<a href="';
							echo site_url() . '/?author=';
							echo the_author_meta('ID');
						echo '">';
							echo '<img class="authorimg" src="';
								echo the_author_meta('ct_profile_url');
							echo '" />';
						echo '</a>';
					} else {
						echo '<a href="';
							echo site_url() . '/?author=';
							echo the_author_meta('ID');
						echo '">';
						echo get_avatar( get_the_author_meta('email'), '80' );
						echo '</a>';
					}
		        echo '</div>';
	        }

		    echo '<div class="col ';
		    	if($ct_author_img == 'yes') { echo 'span_9'; } else { echo 'span_12 first'; }
		    echo '">';
			    echo '<div class="author-inner ';
			    	if($ct_author_img == 'no') { echo 'pad0'; }
			    echo '">';
			        echo '<h5 class="the-author marB10">';
			        	echo '<a href="';
			        		the_author_meta('url');
			        	echo '">';
			        		the_author();
			        echo '</a></h5>';
			        echo '<p>';
			        	the_author_meta('description');
			        echo '</p>';
			        echo '<ul class="social">';
			            if($facebookurl != '') {
			                echo '<li class="facebook"><a href="' . esc_url($facebookurl) . '"><i class="fa fa-facebook"></i></a></li>';
			            }
			            if($twitterhandle != '') {
			                echo '<li class="twitter"><a href="https://twitter.com/' . esc_url($twitterhandle) . '"><i class="fa fa-twitter"></i></a></li>';
			            }
			            if($linkedinurl != '') {
			                echo '<li class="linkedin"><a href="' . esc_url($linkedinurl) . '"><i class="fa fa-linkedin"></i></a></li>';
			            }
			            if($gplus != '') {
			                echo '<li class="google"><a href="' . esc_url($gplus) . '"><i class="fa fa-google-plus"></i></a></li>';
			            }
			        echo '</ul>';
		        echo '</div>';
		    echo '</div>';
		        echo '<div class="clear"></div>';
		echo '</div>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Related Posts */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_related_posts')) {
	function ct_related_posts() {
		global $post;
		
		$tags = wp_get_post_tags($post->ID);
		
		if($tags) {
			echo '<h5 class="related-title marT40">' . __('Related Posts', 'contempo') . '</h5>';
			echo '<ul class="related">';
		  
		  	$first_tag = $tags[0]->term_id;
		  
		  	$args = array(
				'tag__in' => array($first_tag),
				'post__not_in' => array($post->ID),
				'showposts' => 3,
				'ignore_sticky_posts' => 1
		   	);
		  	$my_query = new WP_Query($args);
		  
		  	if($my_query->have_posts()) {
				while($my_query->have_posts()) : $my_query->the_post();

					echo '<li class="col span_4">';
						echo '<figure>';
							echo '<a href="';
								the_permalink();
							echo '">';
								the_post_thumbnail( array(600, 380) );
							echo '</a>';
						echo '</figure>';
		                echo '<h6><a href="';
		                	the_permalink();
		                echo '" rel="bookmark" title="Permanent Link to';
		                	the_title_attribute();
		                echo '">';
		                	the_title();
		                echo '</a></h6>';
		                ct_custom_length_excerpt(12);
		            echo '</li>';

				endwhile; wp_reset_query();
		  	}
		  	echo '</ul>';
			  	echo '<div class="clear"></div>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Custom Length Excerpt */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_custom_length_excerpt')) {
	function ct_custom_length_excerpt($word_count_limit) {
	    echo '<p>' . wp_trim_words(get_the_content(), $word_count_limit) . '</p>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Content Navigation */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_content_nav')) {
	function ct_content_nav() {
	        echo '<div class="clear"></div>';
	    echo '<nav class="content-nav">';
	        echo '<div class="nav-prev left">';
	        	next_posts_link( __( '<span class="meta-nav">&larr;</span> Older', 'contempo' ) );
	        echo '</div>';
	        echo '<div class="nav-next right">';
	        	previous_posts_link( __( 'Newer <span class="meta-nav">&rarr;</span>', 'contempo' ) );
	        echo '</div>';
	    echo '</nav>';
	    	echo '<div class="clear"></div>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Post Navigation */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_post_nav')) {
	function ct_post_nav() {
	    echo '<nav class="post-nav">';
	        echo '<div class="nav-prev left">';
	        	next_post_link('%link', '<span><i class="fas fa-chevron-left"></i></span> %title');
	        echo '</div>';
	        echo '<div class="nav-next right">';
	        	previous_post_link('%link', '%title <span><i class="fas fa-chevron-right"></i></span>');
	        echo '</div>';
	    echo '</nav>';
	        echo '<div class="clear"></div>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Allow Shortcodes to be used in widgets */
/*-----------------------------------------------------------------------------------*/

add_filter('widget_text', 'do_shortcode');

/*-----------------------------------------------------------------------------------*/
/* Get the Attachment MIME Type */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_get_mime_for_attachment')) {
	function ct_get_mime_for_attachment( $attID ) {
		global $wp_query;

	    $type = get_post_mime_type( $attID );

	    if( ! $type ){
	    	return '';
	    }

	    switch( $type ) {

	        case 'application/doc':
	        case 'application/msword':
	            return "word";

	        case 'application/excel':
	        case 'application/x-excel':
	        case 'application/x-msexcel':
	        case 'application/vnd.ms-excel':
	            return "excel";

	        case 'application/powerpoint':
	        case 'application/mspowerpoint':
	        case 'application/vnd.ms-powerpoint':
	        return "powerpoint";

	        case 'application/pdf':
	            return "pdf";

	        case 'application/zip':
		        return "zip";

	        default:
	            return "text";
	    }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Remove height & width from post thumbnails */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_remove_thumbnail_dimensions')) {
	function ct_remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
	    $html = preg_replace( '/(width|height)=\"\d*\"\s/', '', $html );
	    return $html;
	}
}
add_filter( 'post_thumbnail_html', 'ct_remove_thumbnail_dimensions', 10, 3 );

/*-----------------------------------------------------------------------------------*/
/* Get all of the images attached to the current post */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_get_images')) {
	function ct_get_images($size = 'full') {
		global $post;
		
		$photos = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
		$results = array();

		if($photos) {
			foreach ($photos as $photo) {
				// get the correct image html for the selected size
				$results[$photo->ID] = wp_get_attachment_url($photo->ID);
			}
		}
		return $results;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Get all of the images attached to the current post - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_get_images')) {
	function ct_elementor_get_images($size = 'full') {
		
		$attributes['is_elementor'] = 1;

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}
		
		$photos = get_children( array('post_parent' => ct_return_listing_id_elementor($attributes), 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
		$results = array();

		if($photos) {
			foreach ($photos as $photo) {
				// get the correct image html for the selected size
				$results[$photo->ID] = wp_get_attachment_url($photo->ID);
			}
		}
		return $results;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display all images attached to post - detail */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_gallery_images')) {
	function ct_gallery_images() {
		$photos = ct_get_images('full');
		
		if($photos) {
			foreach ($photos as $photo) {
	            echo '<img class="marB18" src="' . esc_url($photo) . '" />';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display all images attached to post - thumbs */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_gallery_images_thumb')) {
	function ct_gallery_images_thumb() {
		$photos = ct_get_images('full');
		
		if($photos) {
			foreach ($photos as $photo) {
				echo '<figure class="col span_3 gallery-thumb">';
		            echo '<img src="' . esc_url($photo) . '" />';
	            echo '</figure>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display first image thumbnail - float right */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_first_image_tn_right')) {
	function ct_first_image_tn_right() {
		global $post;
		
		if(has_post_thumbnail()) {
	        echo '<div class="tn">';
	            echo '<a href="';
	            	the_permalink();
            	echo '">';
	                the_post_thumbnail(69,40);
	            echo '</a>';
	        echo '</div>';
	    }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Get the first image attached to the current post */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_get_post_image')) {
	function ct_get_post_image() {
		global $post;
		
		$photos = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));
		
		if($photos) {
			$photo = array_shift($photos);
			return wp_get_attachment_url($photo->ID);
		}
		return false;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display first image thumb */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_first_image_tn')) {
	function ct_first_image_tn() {
	    echo '<a href="';
	    	the_permalink();
	    echo '">';
	        the_post_thumbnail(array(150,150));
	    echo '</a>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Single Posts Open Graph for Facebook and LinkedIn */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_single_post_display_og_meta')) {
	function ct_single_post_display_og_meta() {
		global $post;

		echo '<meta property="og:title" content="';
			ct_listing_title();
		echo '"/>';
		echo '<meta property="og:description" content="' . esc_attr( get_the_excerpt( $post->ID ) ) . '"/>';
		echo '<meta property="og:url" content="' . esc_url( get_the_permalink( $post->ID ) ) . '" />';
		if(has_post_thumbnail()) {
			echo '<meta property="og:image" content="' . esc_url( get_the_post_thumbnail_url( $post->ID, 'full ') ) . '"/>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Facebook Open Graph for Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_facebook_og_listing')) {

	function ct_facebook_og_listing($ct_listing_id) {

		global $ct_options;
		global $post;

		$ct_listing_permalink = get_the_permalink($ct_listing_id);
		$ct_listing_street_address = get_the_title($ct_listing_id);
		$ct_listing_excerpt = get_the_excerpt($ct_listing_id);

		$listing_alt_title = get_post_meta($ct_listing_id, '_ct_listing_alt_title', true);
		$rental_title = get_post_meta($ct_listing_id, '_ct_rental_title', true);

		if(!empty($listing_alt_title)) {
		    $ct_listing_title = esc_html($listing_alt_title);
		} elseif(!empty($rental_title)) {
		    $ct_listing_title = esc_html($rental_title);
		} else {
			$ct_listing_title = esc_html($ct_listing_street_address);
		}

		$source = get_post_meta($ct_listing_id, 'source', true);
		$photos = get_post_meta($ct_listing_id, '_ct_slider', true);

		if(!empty($ct_listing_permalink)) {
			echo '<meta property="og:url" content="' . esc_url( $ct_listing_permalink ) . '" />';
			echo '<meta property="og:type" content="product" />';
			echo '<meta property="og:title" content="' . esc_attr( $ct_listing_title ) . '" />';
			echo '<meta property="og:description" content="' . esc_attr( $ct_listing_excerpt ) . '" />';
		}

		if($source == 'idx-api') {

			$imageURL = '';
			$images = get_post_meta( $ct_listing_id, '_ct_slider' );

			if ( !empty( $images[0] ) && is_array( $images[0] ) ) {
				foreach( $images[0] as $key => $value ) {
					$imageURL = $value;
					break;
				}
			}

			if($imageURL != '') {
				echo '<meta property="og:image" content="' . esc_url( $imageURL ) . '" />';
			} else {
				echo '<meta property="og:image" content="' . get_template_directory_uri() . '/images/no-image.png" />';
			}

		} else {

			if(has_post_thumbnail()) {
				$imageURL = get_the_post_thumbnail_url( $post->ID, 'full');
				echo '<meta property="og:image" content="' . esc_url( $imageURL ) . '" />';
			} else {
				echo '<meta property="og:image" content="' . get_template_directory_uri() . '/images/no-image.png" />';
			}

		}

	}
}


/*-----------------------------------------------------------------------------------*/
/* Display first image large */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_first_image_lrg')) {
	function ct_first_image_lrg() {

		global $ct_options;
		global $post;

		$source = get_post_meta( $post->ID, "source", true );
		$photos = get_post_meta($post->ID, "_ct_slider", true);

		$ct_listing_featured_image_cropping = isset( $ct_options['ct_listing_featured_image_cropping'] ) ? $ct_options['ct_listing_featured_image_cropping'] : '';
		$ct_listing_single_layout = isset( $ct_options['ct_listing_single_layout'] ) ? $ct_options['ct_listing_single_layout'] : '';
		$ct_listing_single_content_layout = isset( $ct_options['ct_listing_single_content_layout'] ) ? $ct_options['ct_listing_single_content_layout'] : '';

		$theme_directory_url = get_template_directory_uri();

		if( $source == 'idx-api' ) {

			$imageUrl = '';
			$images = get_post_meta( $post->ID, '_ct_slider' );

			if( !empty( $images[0] ) && is_array( $images[0] ) ) {
				foreach( $images[0] as $key => $value ) {
					$imageUrl = $value;
					break;
				}
			}

			if( $imageUrl != '' ) {
				?>
					<a href="#" class="ct-listings-gallery-modal-show"><img src="<?php print esc_url( $imageUrl ); ?>" id="listings-first-image" class="listings-featured-image"></a>
				<?php
			} else {
				?>
					<a href="#" class="ct-listings-gallery-modal-show"><img src="<?php echo esc_url( $theme_directory_url ); ?>/images/no-image.png" srcset="<?php echo esc_url( $theme_directory_url ); ?>/images/no-image@2x.png 2x" id="listings-first-image" /></a>
				<?php
			}

		} else {

			if(has_post_thumbnail()) {
				if($ct_listing_single_layout == 'listings-three' || $ct_listing_single_content_layout == 'full-width') {
					echo '<a href="#" class="ct-listings-gallery-modal-show">';
						the_post_thumbnail('full');
					echo '</a>';
				} elseif($ct_listing_featured_image_cropping != 'no') {
					echo '<a href="#" class="ct-listings-gallery-modal-show">';
						the_post_thumbnail('listings-featured-image');
					echo '</a>';
				} else {
					echo '<a href="#" class="ct-listings-gallery-modal-show">';
						the_post_thumbnail('full');
					echo '</a>';
				}
			} else {
				'<a href="#" class="ct-listings-gallery-modal-show"><img src="<?php echo esc_url( $theme_directory_url ); ?>/images/no-image.png" srcset="<?php echo esc_url( $theme_directory_url ); ?>/images/no-image@2x.png 2x" /></a>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display first image large - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_first_image_lrg')) {
	function ct_elementor_first_image_lrg() {

		global $ct_options;
		
		$attributes['is_elementor'] = 1;

		if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$source = get_post_meta( ct_return_listing_id_elementor($attributes), "source", true );
		$photos = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_slider", true);

		$ct_listing_featured_image_cropping = isset( $ct_options['ct_listing_featured_image_cropping'] ) ? $ct_options['ct_listing_featured_image_cropping'] : '';
		$ct_listing_single_layout = isset( $ct_options['ct_listing_single_layout'] ) ? $ct_options['ct_listing_single_layout'] : '';
		$ct_listing_single_content_layout = isset( $ct_options['ct_listing_single_content_layout'] ) ? $ct_options['ct_listing_single_content_layout'] : '';

		$theme_directory_url = get_template_directory_uri();

		if( $source == 'idx-api' ) {

			$imageUrl = '';
			$images = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_slider' );

			if( !empty( $images[0] ) && is_array( $images[0] ) ) {
				foreach( $images[0] as $key => $value ) {
					$imageUrl = $value;
					break;
				}
			}

			if( $imageUrl != '' ) {
				echo '<a href="#" class="ct-listings-gallery-modal-show"><img src="';
					print esc_url( $imageUrl );
				echo '" id="listings-first-image" class="listings-featured-image"></a>';
			} else {
				echo '<a href="#" class="ct-listings-gallery-modal-show"><img src="' . esc_url( $theme_directory_url ) . '/images/no-image.png" srcset="' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png 2x" id="listings-first-image" /></a>';
			}

		} else {

			if(has_post_thumbnail( ct_return_listing_id_elementor($attributes) )) {
				echo get_the_post_thumbnail( ct_return_listing_id_elementor($attributes), 'full');
			} else {
				'<a href="#" class="ct-listings-gallery-modal-show"><img src="<?php echo esc_url( $theme_directory_url ); ?>/images/no-image.png" srcset="' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png 2x" /></a>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display first image large (Not Linked) - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_first_image_lrg_not_linked')) {
	function ct_elementor_first_image_lrg_not_linked() {

		global $ct_options;
		
		$attributes['is_elementor'] = 1;

		if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$source = get_post_meta( ct_return_listing_id_elementor($attributes), "source", true );
		$photos = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_slider", true);

		$ct_listing_featured_image_cropping = isset( $ct_options['ct_listing_featured_image_cropping'] ) ? $ct_options['ct_listing_featured_image_cropping'] : '';
		$ct_listing_single_layout = isset( $ct_options['ct_listing_single_layout'] ) ? $ct_options['ct_listing_single_layout'] : '';
		$ct_listing_single_content_layout = isset( $ct_options['ct_listing_single_content_layout'] ) ? $ct_options['ct_listing_single_content_layout'] : '';

		$theme_directory_url = get_template_directory_uri();

		if( $source == 'idx-api' ) {

			$imageUrl = '';
			$images = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_slider' );

			if( !empty( $images[0] ) && is_array( $images[0] ) ) {
				foreach( $images[0] as $key => $value ) {
					$imageUrl = $value;
					break;
				}
			}

			if( $imageUrl != '' ) {
				echo '<img src="';
					print esc_url( $imageUrl );
				echo '" id="listings-first-image" class="listings-featured-image">';
			} else {
				echo '<img src="' . esc_url( $theme_directory_url ) . '/images/no-image.png" srcset="' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png 2x" id="listings-first-image" />';
			}

		} else {

			if(has_post_thumbnail( ct_return_listing_id_elementor($attributes) )) {
				echo get_the_post_thumbnail( ct_return_listing_id_elementor($attributes), 'full');
			} else {
				'<img src="<?php echo esc_url( $theme_directory_url ); ?>/images/no-image.png" srcset="' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png 2x" />';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display first image large - BG */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_first_image_as_bg')) {
	function ct_first_image_as_bg() {

		global $post;

		$theme_directory_url = get_template_directory_uri();

		$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
		$listing_slider_meta = get_post_meta( $post->ID, '_ct_slider', true ); 
		$listing_slider_meta_arr_values = array_values($listing_slider_meta); 

		if( ! empty( $listing_slider_meta_arr_values ) && is_array( $listing_slider_meta_arr_values ) ) {
			if( isset( $listing_slider_meta_arr_values[0] ) && ! empty( $listing_slider_meta_arr_values[0] ) ) {
				$first_feat_img = $listing_slider_meta_arr_values[0];
			}
		}

		if( !empty( $first_feat_img ) ) {
			echo 'style="background-image: url(\'' . esc_url( $first_feat_img ) . '\'); background-size: cover;"';
		} elseif ( !empty( $featured_img_url ) ) {
			echo 'style="background-image: url(\'' . esc_url( $featured_img_url ) . '\'); background-size: cover;"';
		} else {
			echo 'style="background-image: url(' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png); background-size: cover;"';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display first image large - BG - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_first_image_as_bg')) {
	function ct_elementor_first_image_as_bg() {

		$attributes['is_elementor'] = 1;

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$theme_directory_url = get_template_directory_uri();

		$featured_img_url = get_the_post_thumbnail_url( ct_return_listing_id_elementor($attributes),'full');
		$listing_slider_meta = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_slider', true ); 
		$listing_slider_meta_arr_values = array_values($listing_slider_meta); 

		if( ! empty( $listing_slider_meta_arr_values ) && is_array( $listing_slider_meta_arr_values ) ) {
			if( isset( $listing_slider_meta_arr_values[0] ) && ! empty( $listing_slider_meta_arr_values[0] ) ) {

				$first_feat_img = $listing_slider_meta_arr_values[0];
			}
		}

		if( !empty( $first_feat_img ) ) {
			echo 'style="background-image: url(\'' . esc_url( $first_feat_img ) . '\'); background-size: cover;"';
		} elseif ( !empty( $featured_img_url ) ) {
			echo 'style="background-image: url(\'' . esc_url( $featured_img_url ) . '\'); background-size: cover;"';
		} else {
			echo 'style="background-image: url(' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png); background-size: cover;"';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display second image large - BG */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_second_image_as_bg')) {
	function ct_second_image_as_bg() {

		global $post;

		$theme_directory_url = get_template_directory_uri();

		$listing_slider_meta = get_post_meta( $post->ID, '_ct_slider', true ); 
		$listing_slider_meta_arr_values = array_values($listing_slider_meta); 

		if( ! empty( $listing_slider_meta_arr_values ) && is_array( $listing_slider_meta_arr_values ) ) {
			if( isset( $listing_slider_meta_arr_values[1] ) && ! empty( $listing_slider_meta_arr_values[1] ) ) {
				$second_feat_img = $listing_slider_meta_arr_values[1];
			}
		}

		if( !empty( $second_feat_img ) ) {
			echo 'style="background-image: url(\'' . esc_url( $second_feat_img ) . '\'); background-size: cover;"';
		} else {
			echo 'style="background-image: url(' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png); background-size: cover;"';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display second image large - BG - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_second_image_as_bg')) {
	function ct_elementor_second_image_as_bg() {

		$attributes['is_elementor'] = 1;

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$theme_directory_url = get_template_directory_uri();

		$listing_slider_meta = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_slider', true ); 
		$listing_slider_meta_arr_values = array_values($listing_slider_meta); 

		if( ! empty( $listing_slider_meta_arr_values ) && is_array( $listing_slider_meta_arr_values ) ) {
			if( isset( $listing_slider_meta_arr_values[1] ) && ! empty( $listing_slider_meta_arr_values[1] ) ) {
				$second_feat_img = $listing_slider_meta_arr_values[1];
			}
		}

		if( !empty( $second_feat_img ) ) {
			echo 'style="background-image: url(\'' . esc_url( $second_feat_img ) . '\'); background-size: cover;"';
		} else {
			echo 'style="background-image: url(' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png); background-size: cover;"';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display third image large - BG */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_third_image_as_bg')) {
	function ct_third_image_as_bg() {

		global $post;

		$theme_directory_url = get_template_directory_uri();

		$listing_slider_meta = get_post_meta( $post->ID, '_ct_slider', true ); 
		$listing_slider_meta_arr_values = array_values($listing_slider_meta); 
		$listing_slider_meta_count = count($listing_slider_meta_arr_values) - 3;

		if( ! empty( $listing_slider_meta_arr_values ) && is_array( $listing_slider_meta_arr_values ) ) {
			if( isset( $listing_slider_meta_arr_values[2] ) && ! empty( $listing_slider_meta_arr_values[2] ) ) {
				$third_feat_img = $listing_slider_meta_arr_values[2];

			}
		}

		if( !empty( $third_feat_img ) ) {
			echo 'style="background-image: url(\'' . esc_url( $third_feat_img ) . '\'); background-size: cover;"';
		} else {
			echo 'style="background-image: url(' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png); background-size: cover;"';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display third image large - BG - Elementor */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_third_image_as_bg')) {
	function ct_elementor_third_image_as_bg() {

		$attributes['is_elementor'] = 1;

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$theme_directory_url = get_template_directory_uri();

		$listing_slider_meta = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_slider', true ); 
		$listing_slider_meta_arr_values = array_values($listing_slider_meta); 
		$listing_slider_meta_count = count($listing_slider_meta_arr_values) - 3;

		if( ! empty( $listing_slider_meta_arr_values ) && is_array( $listing_slider_meta_arr_values ) ) {
			if( isset( $listing_slider_meta_arr_values[2] ) && ! empty( $listing_slider_meta_arr_values[2] ) ) {
				$third_feat_img = $listing_slider_meta_arr_values[2];
			}
		}

		if( !empty( $third_feat_img ) ) {
			echo 'style="background-image: url(\'' . esc_url( $third_feat_img ) . '\'); background-size: cover;"';
		} else {
			echo 'style="background-image: url(' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png); background-size: cover;"';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display Listing Images Count - Mobile */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_images_count_mobile')) {
	function ct_listing_images_count_mobile() {

		global $post;

		$listing_slider_meta = get_post_meta( $post->ID, '_ct_slider', true ); 
		$listing_slider_meta_arr_values = array_values($listing_slider_meta); 
		$listing_slider_meta_count = count($listing_slider_meta_arr_values);

		echo '<div id="mobile-listing-gallery-count">';
			echo '<i class="far fa-images"></i>';
			echo esc_html($listing_slider_meta_count);
		echo '</div>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display Listing Images Count - Mobile - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_listing_images_count_mobile')) {
	function ct_elementor_listing_images_count_mobile() {

		$attributes['is_elementor'] = 1;

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$listing_slider_meta = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_slider', true ); 
		$listing_slider_meta_arr_values = array_values($listing_slider_meta); 
		$listing_slider_meta_count = count($listing_slider_meta_arr_values);

		echo '<div id="mobile-listing-gallery-count">';
			echo '<i class="far fa-images"></i>';
			echo esc_html($listing_slider_meta_count);
		echo '</div>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display Listing Images Count */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listing_images_count_more')) {
	function ct_listing_images_count_more() {

		global $post;

		$listing_slider_meta = get_post_meta( $post->ID, '_ct_slider', true ); 
		$listing_slider_meta_arr_values = array_values($listing_slider_meta); 
		$listing_slider_meta_count = count($listing_slider_meta_arr_values) - 3;

		echo '<div id="listings-gallery-count">' . $listing_slider_meta_count . ' ' . __('More', 'contempo') . '</div>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display Listing Images Count - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_listing_images_count_more')) {
	function ct_elementor_listing_images_count_more() {

		$attributes['is_elementor'] = 1;

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$listing_slider_meta = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_slider', true ); 
		$listing_slider_meta_arr_values = array_values($listing_slider_meta); 
		$listing_slider_meta_count = count($listing_slider_meta_arr_values) - 3;

		echo '<div id="listings-gallery-count">' . $listing_slider_meta_count . ' ' . __('More', 'contempo') . '</div>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display all images attached to post */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_slider_images')) {
	function ct_slider_images() {
		global $post;

		$photos = ct_get_images('listings-featured-image');
		$position = get_post_meta($post->ID, '_ct_images_position', true);

		if($position = '') {
			if($photos) {
				foreach($photos as $attachment_id => $attachment_url ) {
					$alt_text = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
						echo '<a href="' . esc_url($attachment_url) . '" class="gallery-item">';
			                echo wp_get_attachment_image( $attachment_id, '', '', array('class' => 'listings-slider-image', 'alt' => $alt_text) );
		                echo '</a>';
					echo '</li>';
				}
			}
		} else {
			$position = explode(',', $position);
	       	foreach($position as $pos) {
	       		if($pos != '' && isset($photos[$pos])) {
	       			$photo = $photos[$pos];
	       			unset($photos[$pos]);
		       		echo '<li data-thumb="' . esc_url($photo) . '">';
			       		echo '<a href="' . esc_url($photo) . '" class="gallery-item">';
			                echo '<img src="' . esc_url($photo) . '" title="' . get_the_title() .'" />';
		                echo '</a>';
					echo '</li>';
				}
			}

			foreach($photos as $attachment_id => $attachment_url ) {
	       		echo '<li data-thumb="' . esc_url($attachment_url) . '">';
		       		echo '<a href="' . esc_url($attachment_url) . '" class="gallery-item">';
		                echo wp_get_attachment_image( $attachment_id, 'listings-slider-image' );
	                echo '</a>';
				echo '</li>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display all images attached to post - Elementor Single */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_slider_images')) {
	function ct_elementor_slider_images() {

		$attributes['is_elementor'] = 1;

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$photos = ct_elementor_get_images('listings-featured-image');
		$position = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_images_position', true);

		if($position = '') {
			if($photos) {
				foreach($photos as $attachment_id => $attachment_url ) {
					$alt_text = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
						echo '<a href="' . esc_url($attachment_url) . '" class="gallery-item">';
			                echo wp_get_attachment_image( $attachment_id, '', '', array('class' => 'listings-slider-image', 'alt' => $alt_text) );
		                echo '</a>';
					echo '</li>';
				}
			}
		} else {
			$position = explode(',', $position);
	       	foreach($position as $pos) {
	       		if($pos != '' && isset($photos[$pos])) {
	       			$photo = $photos[$pos];
	       			unset($photos[$pos]);
		       		echo '<li data-thumb="' . esc_url($photo) . '">';
			       		echo '<a href="' . esc_url($photo) . '" class="gallery-item">';
			                echo '<img src="' . esc_url($photo) . '" title="' . get_the_title() . '" />';
		                echo '</a>';
					echo '</li>';
				}
			}

			foreach($photos as $attachment_id => $attachment_url ) {
	       		echo '<li data-thumb="' . esc_url($attachment_url) . '">';
		       		echo '<a href="' . esc_url($attachment_url) . '" class="gallery-item">';
		                echo wp_get_attachment_image( $attachment_id, 'listings-slider-image' );
	                echo '</a>';
				echo '</li>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display all images uploaded to slides custom field */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_slider_field_images')) {
	function ct_slider_field_images() {

		global $post;

		$source = get_post_meta( $post->ID, 'source', true );
		$photos = get_post_meta($post->ID, '_ct_slider', true);
		$position = get_post_meta($post->ID, '_ct_images_position', true);

		if($position = '') {
			if($photos) {
				foreach($photos as $attachment_id => $attachment_url ) {
					$alt_text = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
						echo '<a href="' . esc_url($attachment_url) . '" class="gallery-item">';
			                echo wp_get_attachment_image( $attachment_id, 'listings-slider-image', '', array('class' => 'listings-slider-image', 'alt' => $alt_text) );
		                echo '</a>';
					echo '</li>';
				}
			}
		} else {
			$position = explode(',', $position);
	       	foreach($position as $pos) {
	       		if($pos != '' && isset($photos[$pos])) {
	       			$photo = $photos[$pos];
	       			unset($photos[$pos]);
		       		echo '<li data-thumb="' . esc_url($photo) . '">';
			       		echo '<a href="' . esc_url($photo) . '" class="gallery-item">';
			                echo '<img src="' . esc_url($photo) . '" title="' . get_the_title() . '" />';
		                echo '</a>';
					echo '</li>';
				}
			}

			foreach($photos as $attachment_id => $attachment_url) {
				$alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
				if($source == 'idx-api') {
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
						echo '<a href="' . esc_url($attachment_url) . '" class="gallery-item">';
							echo '<img class="attachment-listings-slider-image size-listings-slider-image" src="' . esc_url($attachment_url) . '" title="' . get_the_title() . '" />';
						echo '</a>';
					echo '</li>';
				} else {
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
						echo '<a href="' . esc_url($attachment_url) . '" class="gallery-item">';
							echo wp_get_attachment_image($attachment_id, 'listings-slider-image', '', array('class' => 'listings-slider-image', 'alt' => $alt_text));
		                echo '</a>';
					echo '</li>';
				}
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display all images uploaded to slides custom field - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_slider_field_images')) {
	function ct_elementor_slider_field_images() {

		$attributes['is_elementor'] = 1;

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$source = get_post_meta( ct_return_listing_id_elementor($attributes), 'source', true );
		$photos = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_slider', true);
		$position = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_images_position', true);

		if($position = '') {
			if($photos) {
				foreach($photos as $attachment_id => $attachment_url ) {
					$alt_text = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
						echo '<a href="' . esc_url($attachment_url) . '" class="gallery-item">';
			                echo wp_get_attachment_image( $attachment_id, 'listings-slider-image', '', array('class' => 'listings-slider-image', 'alt' => $alt_text) );
		                echo '</a>';
					echo '</li>';
				}
			}
		} else {
			$position = explode(',', $position);
	       	foreach($position as $pos) {
	       		if($pos != '' && isset($photos[$pos])) {
	       			$photo = $photos[$pos];
	       			unset($photos[$pos]);
		       		echo '<li data-thumb="' . esc_url($photo) . '">';
			       		echo '<a href="' . esc_url($photo) . '" class="gallery-item">';
			                echo '<img src="' . esc_url($photo) . '" title="' . get_the_title() . '" />';
		                echo '</a>';
					echo '</li>';
				}
			}

			foreach($photos as $attachment_id => $attachment_url) {
				$alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
				if($source == 'idx-api') {
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
						echo '<a href="' . esc_url($attachment_url) . '" class="gallery-item">';
							echo '<img class="attachment-listings-slider-image size-listings-slider-image" src="' . esc_url($attachment_url) . '" title="' . get_the_title() . '" />';
						echo '</a>';
					echo '</li>';
				} else {
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
						echo '<a href="' . esc_url($attachment_url) . '" class="gallery-item">';
							echo wp_get_attachment_image($attachment_id, 'listings-slider-image', '', array('class' => 'listings-slider-image', 'alt' => $alt_text));
		                echo '</a>';
					echo '</li>';
				}
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display all images attached to post - Single Home Layout */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_sh_slider_images')) {
	function ct_sh_slider_images() {
		$photos = ct_get_images('full');
		
		if($photos) {
			foreach($photos as $attachment_id => $attachment_url ) {
				echo '<li data-thumb="' . esc_url($attachment_url) . '">';
					echo '<a href="' . esc_url($attachment_url) . '" class="gallery-item">';
		                echo wp_get_attachment_image( $attachment_id, 'listings-slider-image' );
	                echo '</a>';
				echo '</li>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display all images attached to post */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_slider_carousel_images')) {
	function ct_slider_carousel_images() {
		global $post;

		$photos = ct_get_images('listings-featured-image');
		$position = get_post_meta($post->ID, '_ct_images_position', true);

		if($position == '') {
			if($photos) {
				foreach($photos as $attachment_id => $attachment_url ) {
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
		                echo wp_get_attachment_image( $attachment_id, 'listings-featured-image' );
					echo '</li>';
				}
			}
		} else {
			$position = explode(',', $position);
	       	foreach ($position as $pos) {
	       		if($pos != '' && isset($photos[$pos])) {
	       			$photo = $photos[$pos];
	       			unset($photos[$pos]);
		       		echo '<li data-thumb="' . esc_url($photo) . '">';
		                echo '<img src="' . esc_url($photo) . '" title="' . get_the_title() . '" />';
					echo '</li>';
				}
			}

			foreach($photos as $attachment_id => $attachment_url ) {
	       		echo '<li data-thumb="' . esc_url($attachment_url) . '">';
	                echo wp_get_attachment_image( $attachment_id, 'listings-featured-image' );
				echo '</li>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display all images attached to post - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_slider_carousel_images')) {
	function ct_elementor_slider_carousel_images() {
		
		$attributes['is_elementor'] = 1;

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$photos = ct_elementor_get_images('listings-featured-image');
		$position = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_images_position', true);

		if($position == '') {
			if($photos) {
				foreach($photos as $attachment_id => $attachment_url ) {
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
		                echo wp_get_attachment_image( $attachment_id, 'listings-featured-image' );
					echo '</li>';
				}
			}
		} else {
			$position = explode(',', $position);
	       	foreach ($position as $pos) {
	       		if($pos != '' && isset($photos[$pos])) {
	       			$photo = $photos[$pos];
	       			unset($photos[$pos]);
		       		echo '<li data-thumb="' . esc_url($photo) . '">';
		                echo '<img src="' . esc_url($photo) . '" title="' . get_the_title() . '" />';
					echo '</li>';
				}
			}

			foreach($photos as $attachment_id => $attachment_url ) {
	       		echo '<li data-thumb="' . esc_url($attachment_url) . '">';
	                echo wp_get_attachment_image( $attachment_id, 'listings-featured-image' );
				echo '</li>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display all images uploaded to slides custom field */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_slider_field_carousel_images')) {
	function ct_slider_field_carousel_images() {
		global $post;

		$source = get_post_meta( $post->ID, "source", true );
		$photos = get_post_meta($post->ID, "_ct_slider", true);
		$position = get_post_meta($post->ID, '_ct_images_position', true);

		if($position = '') {
			if($photos) {
				foreach($photos as $attachment_id => $attachment_url ) {
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
		               echo wp_get_attachment_image( $attachment_id, 'listings-featured-image' );
					echo '</li>';
				}
			}
		} else {
			$position = explode(',', $position);
	       	foreach ($position as $pos) {
	       		if($pos != '' && isset($photos[$pos])) {
	       			$photo = $photos[$pos];
	       			unset($photos[$pos]);
		       		echo '<li data-thumb="' . esc_url($photo) . '">';
		                echo '<img src="' . esc_url($photo) . '" title="' . get_the_title() . '" />';
					echo '</li>';
				}
			}

			foreach($photos as $attachment_id => $attachment_url ) {
				echo '<li data-thumb="' . esc_url($attachment_url) . '">';
					if( $source == 'idx-api' ) {
						echo '<img class="attachment-listings-featured-image size-listings-featured-image" src="' . esc_url($attachment_url) . '" title="' . get_the_title() . '" />';
					} else {
						echo wp_get_attachment_image( $attachment_id, 'listings-featured-image' );
					}
				echo '</li>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display all images uploaded to slides custom field - Elementor Single Listing */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_elementor_slider_field_carousel_images')) {
	function ct_elementor_slider_field_carousel_images() {
		
		$attributes['is_elementor'] = 1;

		if(\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$source = get_post_meta( ct_return_listing_id_elementor($attributes), "source", true );
		$photos = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_slider", true);
		$position = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_images_position', true);

		if($position = '') {
			if($photos) {
				foreach($photos as $attachment_id => $attachment_url ) {
					echo '<li data-thumb="' . esc_url($attachment_url) . '">';
		               echo wp_get_attachment_image( $attachment_id, 'listings-featured-image' );
					echo '</li>';
				}
			}
		} else {
			$position = explode(',', $position);
	       	foreach ($position as $pos) {
	       		if($pos != '' && isset($photos[$pos])) {
	       			$photo = $photos[$pos];
	       			unset($photos[$pos]);
		       		echo '<li data-thumb="' . esc_url($photo) . '">';
		                echo '<img src="' . esc_url($photo) . '" title="' . get_the_title() . '" />';
					echo '</li>';
				}
			}

			foreach($photos as $attachment_id => $attachment_url ) {
				echo '<li data-thumb="' . esc_url($attachment_url) . '">';
					if( $source == 'idx-api' ) {
						echo '<img class="attachment-listings-featured-image size-listings-featured-image" src="' . esc_url($attachment_url) . '" title="' . get_the_title() . '" />';
					} else {
						echo wp_get_attachment_image( $attachment_id, 'listings-featured-image' );
					}
				echo '</li>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display first image linked */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_first_image_linked')) {
	
	function ct_first_image_linked() {

		global $ct_options;
		global $post;
	
		$ct_listing_featured_image_cropping = isset( $ct_options['ct_listing_featured_image_cropping'] ) ? $ct_options['ct_listing_featured_image_cropping'] : '';

		$source = get_post_meta( $post->ID, 'source', true );
		$theme_directory_url = get_template_directory_uri();

		/**
		 * Parse Second image manually to prevent additional calls to DB.
		 * Make sure to check if enabled/disabled in theme options
		 */

		$show_second_img_class = 'show-secondary-image-false';
		
		if( isset( $ct_options['ct_listing_display_secondary_image_on_hover'] ) && "yes" === $ct_options['ct_listing_display_secondary_image_on_hover'] ) {
			
			$show_second_img_class= "show-secondary-image-true";

			$second_feat_img = ''; 
			$second_feat_img_thumb = '';
			$listing_slider_meta = get_post_meta( get_the_ID(), '_ct_slider', true ); 

			if(is_array($listing_slider_meta)) {
				$listing_slider_meta_arr_values = array_values($listing_slider_meta); 
			}

			if( !empty( $listing_slider_meta_arr_values ) && is_array( $listing_slider_meta_arr_values ) ) {
				if( isset( $listing_slider_meta_arr_values[1] ) && ! empty( $listing_slider_meta_arr_values[1] ) ) {
					$second_feat_img = $listing_slider_meta_arr_values[1];
				}
			}
		}

		/**
		 * Parse Second image End
		 */

		if($source == 'idx-api') {
			
			$imageUrl = '';
			$images = get_post_meta( $post->ID, '_ct_slider' );
			if( !empty( $images[0] ) && is_array( $images[0] ) ) {
				foreach( $images[0] as $key => $value ) {
					$imageUrl = $value;
					break;
				}
			}
			// Disable for no secondary image
			if( empty( $second_feat_img ) ) {
				$show_second_img_class = 'show-secondary-image-false';
			}

			if($imageUrl != '') {
				echo '<a rel="' . esc_attr( $post->ID ) . '" class="' . esc_attr( $show_second_img_class ) . ' listing-featured-image"';
					ct_listing_permalink();
				echo '>';
						echo '<img data-secondary-img="' . esc_attr( $show_second_img_class ) . '"  src="' . esc_url( $imageUrl ) . '" class="listings-featured-image">';
					if( !empty( $second_feat_img ) ) {
						echo '<img class="__show_on_hover" src="' . esc_url( $second_feat_img ) . '" title="'. __('Secondary Image', 'contempo') . '" />';
					}
				echo '</a>';
			} else {
				echo '<a class="show-secondary-image-false listing-featured-image"';
					ct_listing_permalink();
				echo '>';
					echo '<img src="' . esc_url( $theme_directory_url ) . '/images/no-image.png" srcset="' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png 2x" /></a>';
			}
		
		} else {

			if( has_post_thumbnail() ) {
				// Disable for no secondary image
				if( empty( $second_feat_img ) ) {
					$show_second_img_class = 'show-secondary-image-false';
				}
				if( $ct_listing_featured_image_cropping != 'no' ) {
					echo '<a rel="' . esc_attr( $post->ID ) . '" class="' . esc_attr( $show_second_img_class ) . ' listing-featured-image"';
						ct_listing_permalink();
					echo '>';
						the_post_thumbnail( 'listings-featured-image', ['data-secondary-img'=> $show_second_img_class ] );
						if( ! empty( $second_feat_img ) ) {
							echo '<img class="__show_on_hover" src="' . esc_url( $second_feat_img ) . '" title="'. __('Secondary Image', 'contempo') . '" />';
						}
					echo '</a>';
		 		} else {
					echo '<a rel="' . esc_attr( $post->ID ) . '" class="' . esc_attr( $show_second_img_class ) . ' listing-featured-image"';
						ct_listing_permalink();
					echo '>';
						the_post_thumbnail('large', ['data-secondary-img'=> $show_second_img_class ] );
						if( !empty( $second_feat_img ) ) {
							echo '<img class="__show_on_hover" src="' . esc_url( $second_feat_img ) . '" title="'. __('Secondary Image', 'contempo') . '" />';
						}
					echo '</a>';
				}
				
			} else {
				echo '<a class="show-secondary-image-false listing-featured-image"';
					ct_listing_permalink();
				echo '>';
					echo '<img src="' . esc_url( $theme_directory_url ) . '/images/no-image.png" srcset="' . esc_url( $theme_directory_url ) . '/images/no-image@2x.png 2x" />';
				echo '</a>';
			}
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display first image linked widget */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_first_image_linked_widget')) {
	function ct_first_image_linked_widget() {

		global $ct_options;
		
		$ct_listing_featured_image_cropping = isset( $ct_options['ct_listing_featured_image_cropping'] ) ? $ct_options['ct_listing_featured_image_cropping'] : '';

		if($ct_listing_featured_image_cropping != 'no') {
			echo '<a ';
				ct_listing_permalink();
			echo '>';
				the_post_thumbnail('listings-featured-image');
			echo '</a>';
		} else {
			echo '<a ';
				ct_listing_permalink();
			echo '>';
				the_post_thumbnail('large');
			echo '</a>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Display first image map thumb */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_first_image_map_tn')) {
	function ct_first_image_map_tn($echo=true) {

		global $post;
		global $ct_options;

		$ct_listing_idx_mls_logo = isset( $ct_options['ct_listing_idx_mls_logo'] ) ? $ct_options['ct_listing_idx_mls_logo'] : '';

		$ct_idx_mls_idx_logo_small = get_post_meta($post->ID, '_ct_idx_mls_idx_logo_small', true);

		$out = '';
		$source = get_post_meta( $post->ID, 'source', true );

		if($source == 'idx-api') {
			$photos = get_post_meta($post->ID, '_ct_slider', true);

			if(!empty($photos)) {
				foreach($photos as $attachment_id => $attachment_url ) {
					$out = '<img src="' . esc_url($attachment_url) . '" width="250" />';
					break;
				}
			}
			if($ct_listing_idx_mls_logo == 'yes' && $source == 'idx-api' && !empty($ct_idx_mls_idx_logo_small)) {
				$out .= '<img class="idx-logo-overlay" src="' . esc_url($ct_idx_mls_idx_logo_small) . '" />';
			}
		}

		if($out == '') {
			$thumb_id = get_post_thumbnail_id();
			$thumb_url = wp_get_attachment_image_src($thumb_id,'medium', true);
			
			if(!empty($thumb_id)) {
				$out = '<img src="' . $thumb_url[0] . '" width="250" />';
			} else {
				$out = '<img src="' . get_template_directory_uri() . '/images/no-image.png" srcset="' . get_template_directory_uri() . '/images/no-image@2x.png 2x" width="250"/>';
			}
		}

		if($echo) {
			echo ct_sanitize_output( $out );
		} else {
			return $out;
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Get users */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_get_users')) {
	function ct_get_users($users_per_page = 10, $paged = 1, $role = '', $orderby = 'login', $order = 'ASC', $usersearch = '' ) {

		global $blog_id;

		$args = array(
				'number' => $users_per_page,
				'offset' => ( $paged-1 ) * $users_per_page,
				'role' => $role,
				'search' => $usersearch,
				'fields' => 'all_with_meta',
				'blog_id' => $blog_id,
				'orderby' => $orderby,
				'order' => $order
			);

		$wp_user_search = new WP_User_Query( $args );
		$user_results = $wp_user_search->get_results();

		return $user_results;

	}
}

/*-----------------------------------------------------------------------------------*/
/* Listings Navigation */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_listings_nav')) {
	function ct_listings_nav() {
	        echo '<div class="clear"></div>';
	    echo '<nav class="content-nav marB30">';
	        echo '<div class="nav-previous left">';
	        	next_posts_link( __( '<span class="meta-nav"><i class="fas fa-chevron-left"></i></span> Older listings', 'contempo' ) );
	        echo '</div>';
	        echo '<div class="nav-next right">';
	        	previous_posts_link( __( 'Newer listings <span class="meta-nav"><i class="fas fa-chevron-right"></i></span>', 'contempo' ) );
	        echo '</div>';
	            echo '<div class="clear"></div>';
	    echo '</nav>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Content Navigation */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_archive_content_nav')) {
	function ct_archive_content_nav() {
        echo '<div class="nav-previous">';
        	previous_posts_link('Previous');
        echo '</div>';
        echo '<div class="nav-next">';
        	next_posts_link('Next','');
        echo '</div>';
	}
}

if(!function_exists('ct_single_content_nav')) {
	function ct_single_content_nav() {
		echo '<div class="nav-previous">';
			previous_post_link( __( '%link', 'contempo' ) );
		echo '</div>';
	    echo '<div class="nav-next">';
	    	next_post_link( __( '%link', 'contempo' ) );
	    echo '</div>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Browser Detection */
/*-----------------------------------------------------------------------------------*/

class ctBrowserDetect {

  private static $known_browsers = array(
      'msie', 'firefox', 'safari',
      'webkit', 'opera', 'netscape',
      'konqueror', 'gecko', 'chrome'
  );

  private function __construct() {}

  static public function get_info ($agent = null) {
    // Clean up agent and build regex that matches phrases for known browsers
    // (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
    // version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0"
    $agent = strtolower($agent ? $agent : ct_get_server_info('HTTP_USER_AGENT') );

    // This pattern throws an exception if server is not up to date on regex lib
    //$pattern = '#(?<browser>' . join('|', $known) .
    //           ')[/ ]+(?<version>[0-9]+(?:.[0-9]+)?)#';
    // So we use this one
    $pattern = '#(' . join('|',self::$known_browsers) .
               ')[/ ]+([0-9]+(?:.[0-9]+)?)#';

    // Find all phrases (or return empty array if none found)
    if(!preg_match_all($pattern, $agent, $matches)) {return array();}

    // Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
    // Opera 7,8 have a MSIE phrase), use the last two found (the right-most one
    // in the UA).  That's usually the most correct.

    $i = count($matches[1])-1;
    $r = array($matches[1][$i] => $matches[2][$i]);
    if ($i) {$r[$matches[1][$i-1]] = $matches[2][$i-1];}

    return $r;
  }

/******************************************************************************/

  /**
   * Is the user's browser IE ?
   * @return boolean
   */
  static public function isIE () {
    $bi = self::get_info();
    return (!empty($bi['msie']));
  }
  static public function isIE6 () {
    $bi = self::get_info();
    return (!empty($bi['msie']) && $bi['msie'] == 6.0);
  }
  static public function isIE7 () {
    $bi = self::get_info();
    return (!empty($bi['msie']) && $bi['msie'] == 7.0);
  }
  static public function isIE8 () {
    $bi = self::get_info();
    return (!empty($bi['msie']) && $bi['msie'] == 8.0);
  }
  static public function isIE9 () {
    $bi = self::get_info();
    return (!empty($bi['msie']) && $bi['msie'] == 9.0);
  }

  /**
   * Is the user's browser Firefox ?
   * @return boolean
   */
  static public function isFirefox () {
    return (strpos ( ct_get_server_info('HTTP_USER_AGENT'), "Firefox") !== false);
  }

  /**
   * Is the user's browser Chrome ?
   * @return boolean
   */
  static public function isChrome () {
    $bi = self::get_info();
    return (!empty($bi['chrome']));
  }

  /**
   * Is the user's browser Safari ?
   * @return boolean
   */
  static public function isSafari () {
    $bi = self::get_info();
    return (!empty($bi['safari']) && !empty($bi['webkit']));
  }

  /**
   * Is the user's browser Opera ?
   * @return boolean
   */
  static public function isOpera () {
    $bi = self::get_info();
    return ( strpos(strtolower( ct_get_server_info('HTTP_USER_AGENT') ),'opera') !== false );
  }

  /**
   * Is the user's platform iPhone ?
   * @return boolean
   */
  static public function isIphone () {
    return ( strpos(strtolower( ct_get_server_info('HTTP_USER_AGENT') ),'iphone') !== false );
  }

  /**
   * Is the user's platform iPad ?
   * @return boolean
   */
  static public function isIpad () {
    return ( strpos(strtolower( ct_get_server_info('HTTP_USER_AGENT') ),'ipad') !== false );
  }

  /**
   * Is the user's platform the Android ?
   * @return boolean
   */
  static public function isAndroid () {
    return ( strpos(strtolower( ct_get_server_info('HTTP_USER_AGENT') ),'android') !== false );
  }

}

/*-----------------------------------------------------------------------------------*/
/* Breadcrumbs */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_breadcrumbs')) {
	function ct_breadcrumbs( $args = array() ) {
		global $wp_query, $wp_rewrite;

		/* Create an empty variable for the breadcrumb. */
		$breadcrumb = '';

		/* Create an empty array for the trail. */
		$trail = array();
		$path = '';

		/* Set up the default arguments for the breadcrumb. */
		$defaults = array(
			'separator' => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="12" height="12" viewBox="0 0 20 20"> <path d="M5 20c-0.128 0-0.256-0.049-0.354-0.146-0.195-0.195-0.195-0.512 0-0.707l8.646-8.646-8.646-8.646c-0.195-0.195-0.195-0.512 0-0.707s0.512-0.195 0.707 0l9 9c0.195 0.195 0.195 0.512 0 0.707l-9 9c-0.098 0.098-0.226 0.146-0.354 0.146z" fill="#75797f"></path> </svg>',
			'before' => '<span class="breadcrumb-title"></span>',
			'after' => false,
			'front_page' => true,
			'show_home' => __( 'Home', 'contempo' ),
			'echo' => true
		);

		/* Allow singular post views to have a taxonomy's terms prefixing the trail. */
		if ( is_singular() )
			{$defaults["singular_{$wp_query->post->post_type}_taxonomy"] = false;}

		/* Apply filters to the arguments. */
		$args = apply_filters( 'ct_breadcrumbs_args', $args );

		/* Parse the arguments and extract them for easy variable naming. */
		extract( wp_parse_args( $args, $defaults ) );

		/* If $show_home is set and we're not on the front page of the site, link to the home page. */
		if ( !is_front_page() && $show_home )
			{$trail[] = '<a id="bread-home" href="' . home_url() . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home" class="trail-begin">' . $show_home . '</a>';}

		/* If viewing the front page of the site. */
		if ( is_front_page() ) {
			if ( !$front_page )
				{$trail = false;}
			elseif ( $show_home )
				{$trail['trail_end'] = "{$show_home}";}
		}

		/* If viewing the "home"/posts page. */
		elseif ( is_home() ) {
			$home_page = get_page( $wp_query->get_queried_object_id() );
			$trail = array_merge( $trail, ct_breadcrumbs_get_parents( $home_page->post_parent, '' ) );
			$trail['trail_end'] = get_the_title( $home_page->ID );
		}

		/* If viewing a singular post (page, attachment, etc.). */
		elseif ( is_singular() ) {

			/* Get singular post variables needed. */
			$post = $wp_query->get_queried_object();
			$post_id = absint( $wp_query->get_queried_object_id() );
			$post_type = $post->post_type;
			$parent = $post->post_parent;

			/* If a custom post type, check if there are any pages in its hierarchy based on the slug. */
			if ( 'page' !== $post_type ) {

				$post_type_object = get_post_type_object( $post_type );

				$rewrite['with_front'] = isset( $rewrite['with_front'] ) ? $rewrite['with_front'] : '';

				/* If $front has been set, add it to the $path. */
				if ( 'post' == $post_type || 'attachment' == $post_type || ( /*$post_type_object->rewrite['with_front'] &&*/ $wp_rewrite->front ) )
					{$path .= trailingslashit( $wp_rewrite->front );}

				/* If there's a slug, add it to the $path. */
				if ( !empty( $post_type_object->rewrite['slug'] ) )
					{$path .= $post_type_object->rewrite['slug'];}

				/* If there's a path, check for parents. */
				if ( !empty( $path ) )
					{$trail = array_merge( $trail, ct_breadcrumbs_get_parents( '', $path ) );}

				/* If there's an archive page, add it to the trail. */
				if ( !empty( $post_type_object->rewrite['archive'] ) && function_exists( 'get_post_type_archive_link' ) )
					{$trail[] = '<a href="' . get_post_type_archive_link( $post_type ) . '" title="' . esc_attr( $post_type_object->labels->name ) . '">' . $post_type_object->labels->name . '</a>';}
			}

			/* If the post type path returns nothing and there is a parent, get its parents. */
			if ( empty( $path ) && 0 !== $parent || 'attachment' == $post_type )
				{$trail = array_merge( $trail, ct_breadcrumbs_get_parents( $parent, '' ) );}

			/* Display terms for specific post type taxonomy if requested. */
			if ( isset( $args["singular_{$post_type}_taxonomy"] ) && $terms = get_the_term_list( $post_id, $args["singular_{$post_type}_taxonomy"], '', ', ', '' ) )
				{$trail[] = $terms;}

			/* End with the post title. */
			$post_title = get_the_title( $post_id ); // Force the post_id to make sure we get the correct page title.
			if ( !empty( $post_title ) )
				{$trail['trail_end'] = $post_title;}
		}

		/* If we're viewing any type of archive. */
		elseif ( is_archive() ) {

			/* If viewing a taxonomy term archive. */
			if ( is_tax() || is_category() || is_tag() ) {

				/* Get some taxonomy and term variables. */
				$term = $wp_query->get_queried_object();
				$taxonomy = get_taxonomy( $term->taxonomy );

				/* Get the path to the term archive. Use this to determine if a page is present with it. */
				if ( is_category() )
					{$path = get_option( 'category_base' );}
				elseif ( is_tag() )
					{$path = get_option( 'tag_base' );}
				else {
					if ( $taxonomy->rewrite['with_front'] && $wp_rewrite->front )
						{$path = trailingslashit( $wp_rewrite->front );}
					$path .= $taxonomy->rewrite['slug'];
				}

				/* Get parent pages by path if they exist. */
				if ( $path )
					{$trail = array_merge( $trail, ct_breadcrumbs_get_parents( '', $path ) );}

				/* If the taxonomy is hierarchical, list its parent terms. */
				if ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent )
					{$trail = array_merge( $trail, ct_breadcrumbs_get_term_parents( $term->parent, $term->taxonomy ) );}

				/* Add the term name to the trail end. */
				$trail['trail_end'] = $term->name;
			}

			/* If viewing a post type archive. */
			elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {

				/* Get the post type object. */
				$post_type_object = get_post_type_object( get_query_var( 'post_type' ) );

				/* If $front has been set, add it to the $path. */
				if ( $post_type_object->rewrite['with_front'] && $wp_rewrite->front )
					{$path .= trailingslashit( $wp_rewrite->front );}

				/* If there's a slug, add it to the $path. */
				if ( !empty( $post_type_object->rewrite['archive'] ) )
					{$path .= $post_type_object->rewrite['archive'];}

				/* If there's a path, check for parents. */
				if ( !empty( $path ) )
					{$trail = array_merge( $trail, ct_breadcrumbs_get_parents( '', $path ) );}

				/* Add the post type [plural] name to the trail end. */
				$trail['trail_end'] = $post_type_object->labels->name;
			}

			/* If viewing an author archive. */
			elseif ( is_author() ) {

				/* If $front has been set, add it to $path. */
				if ( !empty( $wp_rewrite->front ) )
					{$path .= trailingslashit( $wp_rewrite->front );}

				/* If an $author_base exists, add it to $path. */
				if ( !empty( $wp_rewrite->author_base ) )
					{$path .= $wp_rewrite->author_base;}

				/* If $path exists, check for parent pages. */
				if ( !empty( $path ) )
					{$trail = array_merge( $trail, ct_breadcrumbs_get_parents( '', $path ) );}

				/* Add the author's display name to the trail end. */
				$trail['trail_end'] = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
			}

			/* If viewing a time-based archive. */
			elseif ( is_time() ) {

				if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
					{$trail['trail_end'] = get_the_time( __( 'g:i a', 'contempo' ) );}

				elseif ( get_query_var( 'minute' ) )
					{$trail['trail_end'] = sprintf( __( 'Minute %1$s', 'contempo' ), get_the_time( __( 'i', 'contempo' ) ) );}

				elseif ( get_query_var( 'hour' ) )
					{$trail['trail_end'] = get_the_time( __( 'g a', 'contempo' ) );}
			}

			/* If viewing a date-based archive. */
			elseif ( is_date() ) {

				/* If $front has been set, check for parent pages. */
				if ( $wp_rewrite->front )
					{$trail = array_merge( $trail, ct_breadcrumbs_get_parents( '', $wp_rewrite->front ) );}

				if ( is_day() ) {
					$trail[] = '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', 'contempo' ) ) . '">' . get_the_time( __( 'Y', 'contempo' ) ) . '</a>';
					$trail[] = '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '" title="' . get_the_time( esc_attr__( 'F', 'contempo' ) ) . '">' . get_the_time( __( 'F', 'contempo' ) ) . '</a>';
					$trail['trail_end'] = get_the_time( __( 'j', 'contempo' ) );
				}

				elseif ( get_query_var( 'w' ) ) {
					$trail[] = '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', 'contempo' ) ) . '">' . get_the_time( __( 'Y', 'contempo' ) ) . '</a>';
					$trail['trail_end'] = sprintf( __( 'Week %1$s', 'contempo' ), get_the_time( esc_attr__( 'W', 'contempo' ) ) );
				}

				elseif ( is_month() ) {
					$trail[] = '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', 'contempo' ) ) . '">' . get_the_time( __( 'Y', 'contempo' ) ) . '</a>';
					$trail['trail_end'] = get_the_time( __( 'F', 'contempo' ) );
				}

				elseif ( is_year() ) {
					$trail['trail_end'] = get_the_time( __( 'Y', 'contempo' ) );
				}
			}
		}

		/* If viewing search results. */
		elseif ( is_search() )
			{$trail['trail_end'] = sprintf( __( 'Search results for &quot;%1$s&quot;', 'contempo' ), esc_attr( get_search_query() ) );}

		/* If viewing a 404 error page. */
		elseif ( is_404() )
			{$trail['trail_end'] = __( '404 Not Found', 'contempo' );}

		/* Connect the breadcrumb trail if there are items in the trail. */
		if ( is_array( $trail ) ) {

			/* Open the breadcrumb trail containers. */
			$breadcrumb = '<div class="breadcrumb breadcrumbs ct-breadcrumbs right muted"><div class="breadcrumb-trail">';

			/* If $before was set, wrap it in a container. */
			if ( !empty( $before ) )
				{$breadcrumb .= '<span class="trail-before">' . $before . '</span> ';}

			/* Wrap the $trail['trail_end'] value in a container. */
			if ( !empty( $trail['trail_end'] ) )
				{$trail['trail_end'] = '<span class="trail-end">' . $trail['trail_end'] . '</span>';}

			/* Format the separator. */
			if ( !empty( $separator ) )
				{$separator = '<span class="sep">' . $separator . '</span>';}

			/* Join the individual trail items into a single string. */
			$breadcrumb .= join( " {$separator} ", $trail );

			/* If $after was set, wrap it in a container. */
			if ( !empty( $after ) )
				{$breadcrumb .= ' <span class="trail-after">' . $after . '</span>';}

			/* Close the breadcrumb trail containers. */
			$breadcrumb .= '</div></div>';
		}

		/* Allow developers to filter the breadcrumb trail HTML. */
		$breadcrumb = apply_filters( 'ct_breadcrumbs', $breadcrumb );

		/* Output the breadcrumb. */
		if ( $echo )
			{echo ct_sanitize_output( $breadcrumb );}
		else
			{return $breadcrumb;}

	}
}

/*-----------------------------------------------------------------------------------*/
/* Get parents */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_breadcrumbs_get_parents')) {
	function ct_breadcrumbs_get_parents( $post_id = '', $path = '' ) {

		/* Set up an empty trail array. */
		$trail = array();

		/* If neither a post ID nor path set, return an empty array. */
		if ( empty( $post_id ) && empty( $path ) )
			{return $trail;}

		/* If the post ID is empty, use the path to get the ID. */
		if ( empty( $post_id ) ) {

			/* Get parent post by the path. */
			$parent_page = get_page_by_path( $path );

			if( empty( $parent_page ) )
			        // search on page name (single word)
				{$parent_page = get_page_by_title ( $path );}

			if( empty( $parent_page ) )
				// search on page title (multiple words)
				{$parent_page = get_page_by_title ( str_replace( array('-', '_'), ' ', $path ) );}

			/* End Modification */

			/* If a parent post is found, set the $post_id variable to it. */
			if ( !empty( $parent_page ) )
				{$post_id = $parent_page->ID;}
		}

		/* If a post ID and path is set, search for a post by the given path. */
		if ( $post_id == 0 && !empty( $path ) ) {

			/* Separate post names into separate paths by '/'. */
			$path = trim( $path, '/' );
			preg_match_all( "/\/.*?\z/", $path, $matches );

			/* If matches are found for the path. */
			if ( isset( $matches ) ) {

				/* Reverse the array of matches to search for posts in the proper order. */
				$matches = array_reverse( $matches );

				/* Loop through each of the path matches. */
				foreach ( $matches as $match ) {

					/* If a match is found. */
					if ( isset( $match[0] ) ) {

						/* Get the parent post by the given path. */
						$path = str_replace( $match[0], '', $path );
						$parent_page = get_page_by_path( trim( $path, '/' ) );

						/* If a parent post is found, set the $post_id and break out of the loop. */
						if ( !empty( $parent_page ) && $parent_page->ID > 0 ) {
							$post_id = $parent_page->ID;
							break;
						}
					}
				}
			}
		}

		/* While there's a post ID, add the post link to the $parents array. */
		while ( $post_id ) {

			/* Get the post by ID. */
			$page = get_page( $post_id );

			/* Add the formatted post link to the array of parents. */
			$parents[]  = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . get_the_title( $post_id ) . '</a>';

			/* Set the parent post's parent to the post ID. */
			$post_id = $page->post_parent;
		}

		/* If we have parent posts, reverse the array to put them in the proper order for the trail. */
		if ( isset( $parents ) )
			{$trail = array_reverse( $parents );}

		/* Return the trail of parent posts. */
		return $trail;

	}
}

/*-----------------------------------------------------------------------------------*/
/* Get term parents */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_breadcrumbs_get_term_parents')) {
	function ct_breadcrumbs_get_term_parents( $parent_id = '', $taxonomy = '' ) {

		/* Set up some default arrays. */
		$trail = array();
		$parents = array();

		/* If no term parent ID or taxonomy is given, return an empty array. */
		if ( empty( $parent_id ) || empty( $taxonomy ) )
			{return $trail;}

		/* While there is a parent ID, add the parent term link to the $parents array. */
		while ( $parent_id ) {

			/* Get the parent term. */
			$parent = get_term( $parent_id, $taxonomy );

			/* Add the formatted term link to the array of parent terms. */
			$parents[] = '<a href="' . get_term_link( $parent, $taxonomy ) . '" title="' . esc_attr( $parent->name ) . '">' . $parent->name . '</a>';

			/* Set the parent term's parent as the parent ID. */
			$parent_id = $parent->parent;
		}

		/* If we have parent terms, reverse the array to put them in the proper order for the trail. */
		if ( !empty( $parents ) )
			{$trail = array_reverse( $parents );}

		/* Return the trail of parent terms. */
		return $trail;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search Ajax Chaining */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_returnPostsTax')) {
	function ct_returnPostsTax($posts,$taxonomy) {
		if($taxonomy == '') {
			return array();
		}
		
		$r = array();
		
		foreach($posts as $post_t){
			$tax = wp_get_post_terms($post_t->ID, $taxonomy);
			if(!is_wp_error($tax) && (isset($tax[0]) && $tax[0]->name != null)) {
				$r[$tax[0]->slug] = $tax[0]->name;
			}
		}
		return $r;
	}
}

if(!function_exists('ct_returnPostsByTax')) {
	function ct_returnPostsByTax($taxonomies) {
		
		$taxonomies_q = array();
		
		foreach($taxonomies as $k => $tax){
			$taxonomies_q[] = array( 'taxonomy' => $k, 'field' => 'slug', 'terms' => $tax );
		}
		
		$args = array(
			'posts_per_page' => -1,
	        'post_type' => 'listings',
			'tax_query' => array( $taxonomies_q )
		);
		$posts = get_posts( $args );
		
		return $posts;
	}
}

if(!function_exists('ct_getTaxonomiesRelational')) {
	function ct_getTaxonomiesRelational($current, $filters = '') {

		$args = array();

		if($filters == '') {

			$args = array (
				'state'=>'',
				'city'=>'',
				'zipcode'=>''
			);

		} else {

			foreach( $filters as $k=>$f ) {

				if ( $f != "" && $f != "0" ) {
					$args[$k] = $f;
				}

			}

		}

		$posts = ct_returnPostsByTax($args);
		$return = ct_returnPostsTax($posts, $current);

		return $return;
	}
}

if(!function_exists('ct_getAllTerms')) {
	function ct_getAllTerms($taxonomy_name) {
		$return = array();

		$terms = get_terms($taxonomy_name, 
			array(
				'hide_empty' => 'true',
				'orderby' => 'name',
	            'order' => 'ASC',
		) );

		foreach( $terms as $k=>$term ) {
			$return[$term->slug] = $term->name;
		}
		return $return;
	}
}

add_action( 'wp_ajax_nopriv_country_ajax', 'ct_country_ajax_callback' );
add_action( 'wp_ajax_country_ajax', 'ct_country_ajax_callback' );

add_action( 'wp_ajax_nopriv_communityajax', 'ct_community_ajax_callback' );
add_action( 'wp_ajax_community_ajax', 'ct_community_ajax_callback' );

add_action( 'wp_ajax_nopriv_state_ajax', 'ct_state_ajax_callback' );
add_action( 'wp_ajax_state_ajax', 'ct_state_ajax_callback' );

add_action( 'wp_ajax_nopriv_city_ajax', 'ct_city_ajax_callback' );
add_action( 'wp_ajax_city_ajax', 'ct_city_ajax_callback' );

add_action( 'wp_ajax_nopriv_zipcode_ajax', 'ct_zipcode_ajax_callback' );
add_action( 'wp_ajax_zipcode_ajax', 'ct_zipcode_ajax_callback' );

if(!function_exists('ct_country_ajax_callback')) {
	function ct_country_ajax_callback() {
		global $wpdb;
		global $ct_options;

		if( class_exists('WPDevCache') ) {

			global $oWPDevCache;

			$cache = $oWPDevCache->get( 'ct_country_ajax_callback_' . $_POST['country'], false ); // Use the ID as part of the cache name to make a per page cache

			if ( $cache !== false ) {
				echo ct_sanitize_output( $cache );
				wp_die();
			}

		}

		$ct_header_listing_search_ajaxify_country_state_city = isset( $ct_options['ct_header_listing_search_ajaxify_country_state_city'] ) ? esc_html( $ct_options['ct_header_listing_search_ajaxify_country_state_city'] ) : '';

		$return['success'] = true;
		$return['city'] = '';
		$return['zipcode'] = '';

		if($_POST['country'] == '0'){

			if ( $ct_header_listing_search_ajaxify_country_state_city != 'yes' ) {
				$return['state'] = ct_getAllTerms('state');
				$return['city'] = ct_getAllTerms('city');
				$return['zipcode'] = ct_getAllTerms('zipcode');
			}
		} else {
			$return['state'] = ct_getTaxonomiesRelational('state', array('country' => $_POST['country']));

			if( $ct_header_listing_search_ajaxify_country_state_city != 'yes' ) {
				$return['city'] = ct_getTaxonomiesRelational('city', array('country' => $_POST['country']));
				$return['zipcode'] = ct_getTaxonomiesRelational('zipcode', array('country' => $_POST['country']));
			}
		}

		echo json_encode($return);

		if( class_exists('WPDevCache') ) {

			global $oWPDevCache;

			$oWPDevCache->set( 'ct_country_ajax_callback_' . $_POST['country'], json_encode($return), 3600 );
		}

		wp_die();
	}
}

if(!function_exists('ct_community_ajax_callback')) {
	function ct_community_ajax_callback() {
		global $wpdb;

		if( class_exists('WPDevCache') ) {

			global $oWPDevCache;

			$cache = $oWPDevCache->get( 'ct_community_ajax_callback_' . $_POST['community'], false );

			if ( $cache !== false ) {
				echo ct_sanitize_output( $cache );
				wp_die();
			}

		}

		$return['success'] = true;

		if($_POST['community'] == '0'){
			$return['state'] = ct_getAllTerms('state');
			$return['city'] = ct_getAllTerms('city');
			$return['zipcode'] = ct_getAllTerms('zipcode');
		} else {
			$return['state'] = ct_getTaxonomiesRelational('state', array('community' => $_POST['community']));
			$return['city'] = ct_getTaxonomiesRelational('city', array('community' => $_POST['community']));
			$return['zipcode'] = ct_getTaxonomiesRelational('zipcode', array('community' => $_POST['community']));
		}
		echo json_encode($return);

		if( class_exists('WPDevCache') ) {
			$oWPDevCache->set( 'ct_community_ajax_callback_' . $_POST['community'], json_encode($return), 3600 );
		}

		wp_die();
	}
}

if(!function_exists('ct_state_ajax_callback')) {
	function ct_state_ajax_callback() {
		global $wpdb;
		global $ct_options;

		if( class_exists('WPDevCache') ) {

			global $oWPDevCache;

			$cache = $oWPDevCache->get( 'ct_state_ajax_callback_' . $_POST['firstsearch'] . '_' . $_POST['country'] . '_' . $_POST['state'], false );

			if ( $cache !== false ) {
				echo ct_sanitize_output( $cache );
				wp_die();
			}

		}

		$ct_header_listing_search_ajaxify_country_state_city = isset( $ct_options['ct_header_listing_search_ajaxify_country_state_city'] ) ? esc_html( $ct_options['ct_header_listing_search_ajaxify_country_state_city'] ) : '';

		$return['success']=true;

		$country = '';
		if( isset( $_POST['country'] ) ) {
			$country = filter_var( $_POST['country'], FILTER_SANITIZE_STRING );
		}

		$state = '';
		if( isset( $_POST['state'] ) ) {
			$state = filter_var( $_POST['state'], FILTER_SANITIZE_STRING );
		}

		if( $state == '0' && ( $country == '' || $country == '0' ) ) {
			$return['state'] = array();
			$return['city'] = array();
			$return['zipcode'] = array();

			if ( $ct_header_listing_search_ajaxify_country_state_city != 'yes' ) {
				$return['state'] = ct_getAllTerms('state');
				$return['city'] = ct_getAllTerms('city');
				$return['zipcode'] = ct_getAllTerms('zipcode');
			}

		} else {

			$return['city'] = ct_getTaxonomiesRelational( 'city', array( 'country' => $country, 'state' => $state ) );
			$return['zipcode'] = ct_getTaxonomiesRelational('zipcode', array( 'country' => $country, 'state' => $state ) );

		}

		echo json_encode($return);

		if( class_exists('WPDevCache') ) {
			$oWPDevCache->set( 'ct_state_ajax_callback_' . $_POST['firstsearch'] . '_' . $_POST['country'] . '_' . $_POST['state'], json_encode($return), 3600 );
		}

		wp_die();
	}
}

if(!function_exists('ct_city_ajax_callback')) {
	function ct_city_ajax_callback() {
		global $wpdb;
		global $ct_options;


		$country = '';
		if( isset( $_POST['country'] ) ) {
			$country = filter_var( $_POST['country'], FILTER_SANITIZE_STRING );
		}

		$state = '';
		if( isset( $_POST['state'] ) ) {
			$state = filter_var( $_POST['state'], FILTER_SANITIZE_STRING );
		}

		$city = '';
		if( isset( $_POST['city'] ) ) {
			$city = filter_var( $_POST['city'], FILTER_SANITIZE_STRING );
		}

		$cacheName = 'ct_city_ajax_callback_' . $country . '_' . $state . '_' . $city;

		if( class_exists('WPDevCache') ) {

			global $oWPDevCache;

			$cache = $oWPDevCache->get($cacheName , false );

			if ( $cache !== false ) {
				echo ct_sanitize_output( $cache );
				wp_die();
			}

		}

		$ct_header_listing_search_ajaxify_country_state_city = isset( $ct_options['ct_header_listing_search_ajaxify_country_state_city'] ) ? esc_html( $ct_options['ct_header_listing_search_ajaxify_country_state_city'] ) : '';


		if( $city == '0' && ( $state == '' || $state == '0' ) && ( $country == '' || $country == '0' ) ) {
			$return['state'] = array();
			$return['city'] = array();
			$return['zipcode'] = array();

			if( $ct_header_listing_search_ajaxify_country_state_city != 'yes' ) {
				$return['state'] = ct_getAllTerms('state');
				$return['city'] = ct_getAllTerms('city');
				$return['zipcode'] = ct_getAllTerms('zipcode');
			}

		} else {

			$return['zipcode'] = ct_getTaxonomiesRelational('zipcode',array( 'country' => $country, 'state' => $state, 'city' => $city ) );
		}

		$return['success'] = true;

		echo json_encode($return);

		if( class_exists('WPDevCache') ) {
			$oWPDevCache->set( $cacheName, json_encode($return), 3600 );
		}

		wp_die();
	}
}

if(!function_exists('ct_zipcode_ajax_callback')) {
	function ct_zipcode_ajax_callback() {
		global $wpdb;

		if( class_exists('WPDevCache') ) {

			global $oWPDevCache;

			$cache = $oWPDevCache->get( 'ct_zipcode_ajax_callback_' . $_POST['country'] . '_' . $_POST['zipcode'], false );

			if( $cache !== false ) {
				echo ct_sanitize_output( $cache );
				wp_die();
			}

		}

		$return['success'] = true;
		$return['country'] = ct_getTaxonomiesRelational('country',array('zipcode' => $_POST['zipcode']));
		$return['state'] = ct_getTaxonomiesRelational('state',array('zipcode' => $_POST['zipcode']));
		$return['city'] = ct_getTaxonomiesRelational('city',array('zipcode' => $_POST['zipcode']));
		
		if($_POST['zipcode'] == '0'){
			$return['zipcode'] = ct_getTaxonomiesRelational('zipcode', array('country' => $_POST['country']));
		}
		echo json_encode($return);

		if( class_exists('WPDevCache') ) {
			$oWPDevCache->set( 'ct_zipcode_ajax_callback_' . $_POST['country'] . '_' . $_POST['zipcode'], json_encode($return), 3600 );
		}

		wp_die();
	}
}

if(!function_exists('ct_add_localize_to_head')) {
	function ct_add_localize_to_head(){ 
		echo '<script type="text/javascript">';
			echo 'var ajax_link = "' . admin_url( 'admin-ajax.php' ) . '";';
		echo '</script>';
	}
}
add_action('wp_head','ct_add_localize_to_head');

/*-----------------------------------------------------------------------------------*/
/* Get Search Args */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('getSearchArgs')) {
	function getSearchArgs($skipLocationData=false, $params = false) {
		if( false === $params ) {
			$params = $_GET;
		}

		//file_put_contents(dirname(__FILE__)."/log.theme-functions", "Hit on getSearchArgs\r\n", FILE_APPEND);

		global $ct_options;
		global $paged;
		global $wp_query;
		global $wpdb;

		$ct_exclude_pending_listing_search = isset( $ct_options['ct_exclude_pending_listing_search'] ) ? $ct_options['ct_exclude_pending_listing_search']: '';
		$ct_exclude_off_market_listing_search = isset( $ct_options['ct_exclude_off_market_listing_search'] ) ? $ct_options['ct_exclude_off_market_listing_search']: '';
		$ct_exclude_sold_listing_search = isset( $ct_options['ct_exclude_sold_listing_search'] ) ? $ct_options['ct_exclude_sold_listing_search']: '';
		$ct_listing_search_exclude_mls_ids = isset( $ct_options['ct_listing_search_exclude_mls_ids'] ) ? $ct_options['ct_listing_search_exclude_mls_ids']: '';

		/*-----------------------------------------------------------------------------------*/
		/* Query multiple taxonomies */
		/*-----------------------------------------------------------------------------------*/

		$taxonomies_to_search = array(
			'beds' => 'Bedrooms',
			'baths' => 'Bathrooms',
			'ct_status' => 'Status',
		);

		if( $skipLocationData == false ) {
			$taxonomies_to_search['state'] = 'State';
			$taxonomies_to_search['zipcode'] = 'Zipcode';
			$taxonomies_to_search['city'] = 'City';
			$taxonomies_to_search['country'] = 'Country';
			$taxonomies_to_search['county'] = 'county';
			$taxonomies_to_search['community'] = 'Community';
		}

		$search_values = array();
		$tax_query = array();
		$meta_query = array();

		foreach($taxonomies_to_search as $t => $l) {
			$var_name = 'ct_'. $t;

			if(!empty($params[$var_name]) && $params[$var_name] != '<img') {
				$search_values[$t] = $params[$var_name];
			}
		}

		$search_values['post_type'] = 'listings';
		$search_values['order'] = 'DESC';
		$search_values['orderby'] = 'date';
		$search_values['paged'] = ct_currentPage();
		$search_num = isset( $ct_options['ct_listing_search_num'] ) ? $ct_options['ct_listing_search_num']: 6;
		$search_values['showposts'] = $search_num;
		//$search_values['no_found_rows'] = true;

		$search_values['tax_query'] = array();

		/*-----------------------------------------------------------------------------------*/
		/* Property Types Search */
		/*-----------------------------------------------------------------------------------*/

		if(isset($params['ct_property_type']) && !empty($params['ct_property_type'])) {
			if(is_array($params['ct_property_type'])) {
				$property_types = $params['ct_property_type'];

				foreach($property_types as $type) {
					$t = get_term_by('slug', $type, 'property_type');
					$type_ids[] = $t->term_id;
				}

				$search_values['tax_query'] = array('relation' => 'AND');

				array_push( $search_values['tax_query'],
					array(
						'taxonomy' => 'property_type',
						'field' => 'term_id',
						'terms' => $type_ids,
						'operator' => 'IN'
					)
				);

			} else {

				$property_type = $params['ct_property_type'];

				$search_values['tax_query'] = array('relation' => 'AND');

				array_push( $search_values['tax_query'],
					array(
						'taxonomy' => 'property_type',
						'field' => 'slug',
						'terms' => $property_type,
						'operator' => 'IN'
					)
				);
			}
		}

		/*-----------------------------------------------------------------------------------*/
		/* Status Search Multi */
		/*-----------------------------------------------------------------------------------*/

		if(isset($params['ct_ct_status_multi']) && !empty($params['ct_ct_status_multi'])) {
			if(is_array($params['ct_ct_status_multi'])) {
				$statuses = $params['ct_ct_status_multi'];

				foreach($statuses as $status) {
					$s = get_term_by('slug', $status, 'ct_status');
					$status_ids[] = $s->term_id;
				}

				array_push( $search_values['tax_query'],
					array(
						'taxonomy' => 'ct_status',
						'field' => 'term_id',
						'terms' => $status_ids,
						'operator' => 'IN'
					)
				);

			}
		}

		/*-----------------------------------------------------------------------------------*/
		/* City Search Multi */
		/*-----------------------------------------------------------------------------------*/

		if(isset($params['ct_city_multi']) && !empty($params['ct_city_multi'])) {
			if(is_array($params['ct_city_multi'])) {
				$cities = $params['ct_city_multi'];

				foreach($cities as $city) {
					$c = get_term_by('slug', $city, 'city');
					$city_ids[] = $c->term_id;
				}

				array_push( $search_values['tax_query'],
					array(
						'taxonomy' => 'city',
						'field' => 'term_id',
						'terms' => $city_ids,
						'operator' => 'IN'
					)
				);

			}
		}

		/*-----------------------------------------------------------------------------------*/
		/* State Search Multi */
		/*-----------------------------------------------------------------------------------*/

		if(isset($params['ct_state_multi']) && !empty($params['ct_state_multi'])) {
			if(is_array($params['ct_state_multi'])) {
				$states = $params['ct_state_multi'];

				foreach($states as $state) {
					$st = get_term_by('slug', $state, 'state');
					$state_ids[] = $st->term_id;
				}

				array_push( $search_values['tax_query'],
					array(
						'taxonomy' => 'state',
						'field' => 'term_id',
						'terms' => $state_ids,
						'operator' => 'IN'
					)
				);

			}
		}

		/*-----------------------------------------------------------------------------------*/
		/* County Search Multi */
		/*-----------------------------------------------------------------------------------*/

		if(isset($params['ct_county_multi']) && !empty($params['ct_county_multi'])) {
			if(is_array($params['ct_county_multi'])) {
				$counties = $params['ct_county_multi'];

				foreach($counties as $county) {
					$co = get_term_by('slug', $county, 'county');
					$county_ids[] = $co->term_id;
				}

				array_push( $search_values['tax_query'],
					array(
						'taxonomy' => 'county',
						'field' => 'term_id',
						'terms' => $county_ids,
						'operator' => 'IN'
					)
				);

			}
		}

		/*-----------------------------------------------------------------------------------*/
		/* Additional Features Search */
		/*-----------------------------------------------------------------------------------*/

		if(isset($params['ct_additional_features']) && !empty($params['ct_additional_features'])) {
			if (is_array($params['ct_additional_features'])) {
				$additional_features = $params['ct_additional_features'];

				foreach($additional_features as $feature) {
					$f = get_term_by('slug', $feature, 'additional_features');
					$feature_ids[] = $f->term_id;
				}

				array_push( $search_values['tax_query'],
					array(
						'taxonomy' => 'additional_features',
						'field' => 'term_id',
						'terms' => $feature_ids,
						'operator' => 'AND'
					)
				);

			}
		}

		/*-----------------------------------------------------------------------------------*/
		/* Beds+ */
		/*-----------------------------------------------------------------------------------*/

		if(isset($params['ct_beds_plus']) && !empty($params['ct_beds_plus'])) {
			$ct_beds_start = $params['ct_beds_plus'];
			$ct_beds_end = 20;
			array_push( $search_values['tax_query'],
				array(
					'taxonomy'  => 'beds',
					'field'     => 'name',
					'terms'     => range($ct_beds_start, $ct_beds_end),
					'operator'  => 'IN'
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Baths+ */
		/*-----------------------------------------------------------------------------------*/

		if(isset($params['ct_baths_plus']) && !empty($params['ct_baths_plus'])) {
			$ct_baths_start = $params['ct_baths_plus'];
			$ct_beds_end = 20;
			array_push( $search_values['tax_query'],
				array(
					'taxonomy'  => 'baths',
					'field'     => 'name',
					'terms'     => range($ct_baths_start, $ct_beds_end),
					'operator'  => 'IN'
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Exclude Pending Status */
		/*-----------------------------------------------------------------------------------*/

		if($ct_exclude_pending_listing_search == 'yes') {
			array_push( $search_values['tax_query'],
				array(
					'taxonomy'  => 'ct_status',
					'field'     => 'slug',
					'terms'     => 'pending',
					'operator'  => 'NOT IN'
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Exclude Off Market Status */
		/*-----------------------------------------------------------------------------------*/

		if($ct_exclude_off_market_listing_search == 'yes') {
			array_push( $search_values['tax_query'],
				array(
					'taxonomy'  => 'ct_status',
					'field'     => 'slug',
					'terms'     => 'off-market',
					'operator'  => 'NOT IN'
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Exclude Sold Status */
		/*-----------------------------------------------------------------------------------*/

		if($ct_exclude_sold_listing_search == 'yes') {
			array_push( $search_values['tax_query'],
				array(
					'taxonomy'  => 'ct_status',
					'field'     => 'slug',
					'terms'     => 'sold',
					'operator'  => 'NOT IN'
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Exclude Ghost Status */
		/*-----------------------------------------------------------------------------------*/

		array_push( $search_values['tax_query'],
			array(
				'taxonomy'  => 'ct_status',
				'field'     => 'slug',
				'terms'     => 'ghost',
				'operator'  => 'NOT IN'
			)
		);

		/*-----------------------------------------------------------------------------------*/
		/* Keyword Search on Title and Content */
		/*-----------------------------------------------------------------------------------*/

		add_action( 'pre_get_posts', function( $q ) {
			if($title = $q->get('_meta_or_title')) {
				add_filter( 'get_meta_sql', function($sql) use ($title) {
					global $wpdb;

					// Only run once
					static $nr = 0;
					if(0 != $nr++) {
						return $sql;
					}

					// Modify WHERE
					$sql['where'] = sprintf(
						" AND ( %s OR %s ) ",
						$wpdb->prepare("{$wpdb->posts}.post_title like '%%%s%%'", $title),
						$wpdb->prepare("{$wpdb->posts}.post_content like '%%%s%%'", $title),
						mb_substr( $sql['where'], 5, mb_strlen( $sql['where'] ) )
					);
					return $sql;
				});
			}
		});

		if( $skipLocationData == false && !empty($params['ct_keyword']) || $skipLocationData == false && !empty($params['ct_mobile_keyword']) ) {

			$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS');

			if(!empty($params['ct_mobile_keyword'])) {
				$ct_keyword = $params['ct_mobile_keyword'];
				$ct_keyword = strip_tags($ct_keyword);
				$ct_keyword = str_replace($ct_filters, '', $ct_keyword);
			} else {
				$ct_keyword = $params['ct_keyword'];
				$ct_keyword = strip_tags($ct_keyword);
				$ct_keyword = str_replace($ct_filters, '', $ct_keyword);
			}

			$search_values['_meta_or_title'] = $ct_keyword;

			if(!empty($params['ct_mobile_keyword'])) {
				$post_keyword = $_REQUEST['ct_mobile_keyword'];
				$post_keyword = strip_tags($post_keyword);
				$post_keyword = str_replace($ct_filters, '', $post_keyword);
			} else {
				$post_keyword = $_REQUEST['ct_keyword'];
				$post_keyword = strip_tags($post_keyword);
				$post_keyword = str_replace($ct_filters, '', $post_keyword);
			}

			global $wpdb;

			$posts_data = $wpdb->get_results ("SELECT * FROM ".$wpdb->prefix ."posts WHERE post_type= 'listings' AND post_status= 'publish' AND (post_content like '%" .$post_keyword. "%' OR post_title like '%".$post_keyword. "%') ORDER BY post_title" );
			$post_meta_data = $wpdb->get_results ("SELECT * FROM ".$wpdb->prefix ."posts WHERE ".$wpdb->prefix ."posts.post_status ='publish' AND ".$wpdb->prefix ."posts.post_type= 'listings' AND ".$wpdb->prefix ."posts.ID = (SELECT ".$wpdb->prefix ."postmeta.post_id  FROM ".$wpdb->prefix ."postmeta  WHERE ".$wpdb->prefix ."postmeta.meta_key = '_ct_listing_alt_title'  AND ".$wpdb->prefix ."postmeta.meta_value LIKE '%".$post_keyword. "%' OR ".$wpdb->prefix ."postmeta.meta_key = '_ct_rental_title'  AND ".$wpdb->prefix ."postmeta.meta_value LIKE '%".$post_keyword. "%' )");

			$post_terms = get_posts(array(
				'showposts' => -1,
				'post_type' => 'listings',
				'post_status' => 'publish',
				'tax_query' => array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'community',
						'field' => 'name',
						'compare' => 'LIKE',
						'terms' => array($post_keyword)
					),
					array(
						'taxonomy' => 'city',
						'field' => 'name',
						'compare' => 'LIKE',
						'terms' => array($post_keyword)
					),
					array(
						'taxonomy' => 'zipcode',
						'field' => 'name',
						'compare' => 'LIKE',
						'terms' => array($post_keyword)
					),
					array(
						'taxonomy' => 'country',
						'field' => 'name',
						'compare' => 'LIKE',
						'terms' => array($post_keyword)
					),
					array(
						'taxonomy' => 'state',
						'field' => 'name',
						'compare' => 'LIKE',
						'terms' => array($post_keyword)
					),
				))
			);

			$id_array_post = array();
			foreach($posts_data as $post_terms_id) {
				array_push($id_array_post,$post_terms_id->ID);
			};

			$id_array_post_meta = array();
			foreach($post_meta_data as $post_terms_id) {
				array_push($id_array_post_meta,$post_terms_id->ID);
			};

			$id_array = array();
			foreach($post_terms as $post_terms_id) {
				array_push($id_array,$post_terms_id->ID);
			};

			$ids = array_unique(array_merge($id_array_post,$id_array_post_meta,$id_array));
			if(empty( $ids )) {
				$ids = array(1); // set it to 1 to get no posts in results rather than all posts
			}
			$search_values['post_type'] = array('listings');
			$search_values['post__in'] = $ids;

		}

		/*-----------------------------------------------------------------------------------*/
		/* Order by Price */
		/*-----------------------------------------------------------------------------------*/

		if(!empty($params['ct_orderby_price'])) {

			$order = utf8_encode($params['ct_orderby_price']);

			$search_values['orderby'] = 'meta_value';
			$search_values['meta_key'] = '_ct_price';
			$search_values['meta_type'] = 'numeric';
			$search_values['order'] = $order;

		}

		/*-----------------------------------------------------------------------------------*/
		/* Order by (Title, Price or upload date) */
		/*-----------------------------------------------------------------------------------*/

		if(!empty($params['ct_orderby'])) {
			$orderBy = $params['ct_orderby'];

			if($orderBy == 'priceASC') {
				$search_values['orderby'] = 'meta_value';
				$search_values['meta_key'] = '_ct_price';
				$search_values['meta_type'] = 'numeric';
				$search_values['order'] = 'ASC';
			} elseif($orderBy == 'priceDESC') {
				$search_values['orderby'] = 'meta_value';
				$search_values['meta_key'] = '_ct_price';
				$search_values['meta_type'] = 'numeric';
				$search_values['order'] = 'DESC';
			} elseif($orderBy == 'sizeASC') {
				$search_values['orderby'] = 'meta_value';
				$search_values['meta_key'] = '_ct_sqft';
				$search_values['meta_type'] = 'numeric';
				$search_values['order'] = 'ASC';
			} elseif($orderBy == 'sizeDESC') {
				$search_values['orderby'] = 'meta_value';
				$search_values['meta_key'] = '_ct_sqft';
				$search_values['meta_type'] = 'numeric';
				$search_values['order'] = 'DESC';
			} elseif($orderBy == 'lotASC') {
				$search_values['orderby'] = 'meta_value';
				$search_values['meta_key'] = '_ct_lotsize';
				$search_values['meta_type'] = 'numeric';
				$search_values['order'] = 'ASC';
			} elseif($orderBy == 'lotDESC') {
				$search_values['orderby'] = 'meta_value';
				$search_values['meta_key'] = '_ct_lotsize';
				$search_values['meta_type'] = 'numeric';
				$search_values['order'] = 'DESC';
			} elseif($orderBy == 'yearASC') {
				$search_values['orderby'] = 'meta_value';
				$search_values['meta_key'] = '_ct_year_built';
				$search_values['meta_type'] = 'numeric';
				$search_values['order'] = 'ASC';
			} elseif($orderBy == 'yearDESC') {
				$search_values['orderby'] = 'meta_value';
				$search_values['meta_key'] = '_ct_year_built';
				$search_values['meta_type'] = 'numeric';
				$search_values['order'] = 'DESC';
			} elseif($orderBy == 'dateDESC') {
				$search_values['orderby'] = 'date';
				$search_values['order'] = 'DESC';
			} elseif($orderBy == 'dateASC') {
				$search_values['orderby'] = 'date';
				$search_values['order'] = 'ASC';
			} else { 
				$search_values['orderby'] = 'title';
				$search_values['order'] = 'ASC';
			}
		}

		$mode = 'search';

		$search_values['meta_query'] = array();

		/*-----------------------------------------------------------------------------------*/
		/* Check Price From/To */
		/*-----------------------------------------------------------------------------------*/

		if (!empty($params['ct_price_from']) && !empty($params['ct_price_to'])) {

			$ct_currency = '$';
			$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS');

			if($ct_options['ct_currency']) {
				$ct_currency = esc_html($ct_options['ct_currency']);
			}

			if(!empty($params['ct_price_from'])) {
				$ct_price_from = $params['ct_price_from'];
				$ct_price_from = strip_tags($ct_price_from);
				$ct_price_from = str_replace($ct_filters, '', $ct_price_from);
			}

			if(!empty($params['ct_price_to'])) {
				$ct_price_to = $params['ct_price_to'];
				$ct_price_to = strip_tags($ct_price_to);
				$ct_price_to = str_replace($ct_filters, '', $ct_price_to);
			}
			
			$ct_price_from = str_replace(',', '', $ct_price_from);
			$ct_price_to = str_replace(',', '', $ct_price_to);
			$ct_price_from = str_replace($ct_currency, '', $ct_price_from);
			$ct_price_to = str_replace($ct_currency, '', $ct_price_to);
			
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_price',
					'value' => array( $ct_price_from, $ct_price_to ),
					'type' => 'NUMERIC',
					'compare' => 'BETWEEN'
				)
			);
		}
		else if (!empty($params['ct_price_from'])) {
			$ct_currency = "$";
			$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS');
			
			if($ct_options['ct_currency']) {
				$ct_currency = esc_html($ct_options['ct_currency']);
			}

			if(!empty($params['ct_price_from'])) {
				$ct_price_from = $params['ct_price_from'];
				$ct_price_from = strip_tags($ct_price_from);
				$ct_price_from = str_replace($ct_filters, '', $ct_price_from);
			}
			$ct_price_from = str_replace($ct_currency, '', $ct_price_from);
			$ct_price_from = str_replace(',', '', $ct_price_from);
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_price',
					'value' => $ct_price_from,
					'type' => 'NUMERIC',
					'compare' => '>='
				)
			);
		}
		else if (!empty($params['ct_price_to'])) {
			$ct_currency = "$";
			$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS');

			if($ct_options['ct_currency']) {
				$ct_currency = esc_html($ct_options['ct_currency']);
			}

			if(!empty($params['ct_price_to'])) {
				$ct_price_to = $params['ct_price_to'];
				$ct_price_to = strip_tags($ct_price_to);
				$ct_price_to = str_replace($ct_filters, '', $ct_price_to);
			}
			$ct_price_to = str_replace($ct_currency, '', $ct_price_to);
			$ct_price_to = str_replace(',', '', $ct_price_to);
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_price',
					'value' => $params['ct_price_to'],
					'type' => 'NUMERIC',
					'compare' => '<='
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Check Dwelling Size From/To */
		/*-----------------------------------------------------------------------------------*/

		if (!empty($params['ct_sqft_from']) && !empty($params['ct_sqft_to'])) {

			$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS');

			if(!empty($params['ct_sqft_from'])) {
				$ct_sqft_from = $params['ct_sqft_from'];
				$ct_sqft_from = strip_tags($ct_sqft_from);
				$ct_sqft_from = str_replace($ct_filters, '', $ct_sqft_from);
			}

			if(!empty($params['ct_sqft_to'])) {
				$ct_sqft_to = $params['ct_sqft_to'];
				$ct_sqft_to = strip_tags($ct_sqft_to);
				$ct_sqft_to = str_replace($ct_filters, '', $ct_sqft_to);
			}

			$ct_sqft_from = str_replace(',', '', $ct_sqft_from);
			$ct_sqft_to = str_replace(',', '', $ct_sqft_to);
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_sqft',
					'value' => array( $ct_sqft_from, $ct_sqft_to ),
					'type' => 'NUMERIC',
					'compare' => 'BETWEEN'
				)
			);
		}
		else if (!empty($params['ct_sqft_from'])) {
			$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS');

			if(!empty($params['ct_sqft_from'])) {
				$ct_sqft_from = $params['ct_sqft_from'];
				$ct_sqft_from = strip_tags($ct_sqft_from);
				$ct_sqft_from = str_replace($ct_filters, '', $ct_sqft_from);
			}
			
			$ct_sqft_from = str_replace(',', '', $ct_sqft_from);

			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_sqft',
					'value' => $ct_sqft_from,
					'type' => 'NUMERIC',
					'compare' => '>='
				)
			);
		}
		else if (!empty($params['ct_sqft_to'])) {
			$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS');
			if(!empty($params['ct_sqft_to'])) {
				$ct_sqft_to = $params['ct_sqft_to'];
				$ct_sqft_to = strip_tags($ct_sqft_to);
				$ct_sqft_to = str_replace($ct_filters, '', $ct_sqft_to);
			}
			$ct_sqft_to = str_replace(',', '', $ct_sqft_to);
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_sqft',
					'value' => $ct_sqft_to,
					'type' => 'NUMERIC',
					'compare' => '<='
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Check Lot Size From/To */
		/*-----------------------------------------------------------------------------------*/

		if (!empty($params['ct_lotsize_from']) && !empty($params['ct_lotsize_to'])) {
			$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS');

			if(!empty($params['ct_lotsize_from'])) {
				$ct_lotsize_from = $params['ct_lotsize_from'];
				$ct_lotsize_from = strip_tags($ct_lotsize_from);
				$ct_lotsize_from = str_replace($ct_filters, '', $ct_lotsize_from);
			}

			if(!empty($params['ct_lotsize_to'])) {
				$ct_lotsize_to = $params['ct_lotsize_to'];
				$ct_lotsize_to = strip_tags($ct_lotsize_to);
				$ct_lotsize_to = str_replace($ct_filters, '', $ct_lotsize_to);
			}

			$ct_lotsize_from = str_replace(',', '', $ct_lotsize_from);
			$ct_lotsize_to = str_replace(',', '', $ct_lotsize_to);
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_lotsize',
					'value' => array( $ct_lotsize_from, $ct_lotsize_to ),
					'type' => 'NUMERIC',
					'compare' => 'BETWEEN'
				)
			);
		}
		else if (!empty($params['ct_lotsize_from'])) {
			$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS');

			if(!empty($params['ct_lotsize_from'])) {
				$ct_lotsize_from = $params['ct_lotsize_from'];
				$ct_lotsize_from = strip_tags($ct_lotsize_from);
				$ct_lotsize_from = str_replace($ct_filters, '', $ct_lotsize_from);
			}
			$ct_lotsize_from = str_replace(',', '', $ct_lotsize_from);
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_lotsize',
					'value' => $ct_lotsize_from,
					'type' => 'NUMERIC',
					'compare' => '>='
				)
			);
		}
		else if (!empty($params['ct_lotsize_to'])) {
			if(!empty($params['ct_lotsize_to'])) {
				$ct_lotsize_to = $params['ct_lotsize_to'];
				$ct_lotsize_to = strip_tags($ct_lotsize_to);
				$ct_lotsize_to = str_replace($ct_filters, '', $ct_lotsize_to);
			}
			$ct_lotsize_to = str_replace(',', '', $ct_lotsize_to);
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_lotsize',
					'value' => $ct_lotsize_to,
					'type' => 'NUMERIC',
					'compare' => '<='
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Check Year From/To */
		/*-----------------------------------------------------------------------------------*/

		if (!empty($params['ct_year_from']) && !empty($params['ct_year_to'])) {
			$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS');

			if(!empty($params['ct_year_from'])) {
				$ct_year_from = $params['ct_year_from'];
				$ct_year_from = strip_tags($ct_year_from);
				$ct_year_from = str_replace($ct_filters, '', $ct_year_from);
			}

			if(!empty($params['ct_year_to'])) {
				$ct_year_to = $params['ct_year_to'];
				$ct_year_to = strip_tags($ct_year_to);
				$ct_year_to = str_replace($ct_filters, '', $ct_year_to);
			}
			$ct_year_from = str_replace(',', '', $ct_year_from);
			$ct_year_to = str_replace(',', '', $ct_year_to);
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_idx_overview_year_built',
					'value' => array( $ct_year_from, $ct_year_to ),
					'type' => 'NUMERIC',
					'compare' => 'BETWEEN'
				)
			);
		}
		else if (!empty($params['ct_year_from'])) {
			$ct_year_from = str_replace(',', '', $params['ct_year_from']);
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_idx_overview_year_built',
					'value' => $ct_year_from,
					'type' => 'NUMERIC',
					'compare' => '>='
				)
			);
		}
		else if (!empty($params['ct_year_to'])) {
			$ct_year_to = str_replace(',', '', $params['ct_year_to']);
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_idx_overview_year_built',
					'value' => $ct_year_to,
					'type' => 'NUMERIC',
					'compare' => '<='
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Check School District matches */
		/*-----------------------------------------------------------------------------------*/

		if(!empty($params['ct_school_district'])) {
			$ct_school_district = $params['ct_school_district'];
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_idx_schools_school_district',
					'value' => $ct_school_district,
					'type' => 'char',
					'compare' => '='
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Check to see if reference number matches */
		/*-----------------------------------------------------------------------------------*/

		if(!empty($params['ct_mls'])) {
			$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS');

			if(!empty($params['ct_mls'])) {
				$ct_mls = $params['ct_mls'];
				$ct_mls = strip_tags($ct_mls);
				$ct_mls = str_replace($ct_filters, '', $ct_mls);
			}
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_mls',
					'value' => $ct_mls,
					'type' => 'char',
					'compare' => '='
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Check if agent name matches */
		/*-----------------------------------------------------------------------------------*/

		if(!empty($params['ct_idx_agent'])) {
			$ct_idx_agent = $params['ct_idx_agent'];
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_agent_name',
					'value' => $ct_idx_agent,
					'type' => 'char',
					'compare' => '='
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Check if brokerage ID matches */
		/*-----------------------------------------------------------------------------------*/

		if(!empty($params['ct_brokerage'])) {
			$ct_brokerage = $params['ct_brokerage'];
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_brokerage',
					'value' => $ct_brokerage,
					'type' => 'NUMERIC',
					'compare' => '='
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Check to see if number of guests matches */
		/*-----------------------------------------------------------------------------------*/

		if(!empty($params['ct_rental_guests'])) {
			$ct_rental_guests = $params['ct_rental_guests'];
			array_push( $search_values['meta_query'],
				array(
					'key' => '_ct_rental_guests',
					'value' => $ct_rental_guests,
					'type' => 'NUMERIC',
					'compare' => '<='
				)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Display IDX results if plugin is active and license valid
		/* Else display only regular listings 
		/*-----------------------------------------------------------------------------------*/

		$displayIDX = '!=';

		if( class_exists( 'IDX_Query' ) ) {
			// Get only idx rows when this plugin is active
			$oIDXQuery = new IDX_Query();
			if( $oIDXQuery->validateEddKey() === true ) {
				$displayIDX = '=';
			}
		}

		if( !isset( $search_values['meta_query'] ) ) {
			$search_values['meta_query'] = array();
		}

		if( $displayIDX == '=' ) {
			array_push( $search_values['meta_query'], array(
				/* Commented out so when CT IDX Pro is active it searches on ALL Listings including Manually Entered
				'relation' => 'AND',
				array(
					'key' => 'source',
					'value' => 'idx-api',
					'type' => 'char',
					'compare' => $displayIDX
				)*/
			)
			);
		} else {
			array_push( $search_values['meta_query'], array(
				'relation' => 'AND',
				array(
					'key' => 'source',
					'value' => 'idx-api',
					'compare' => 'NOT EXISTS'
				)
			)
			);
		}

		/*-----------------------------------------------------------------------------------*/
		/* Exclude MLS ID's From Search */
		/*-----------------------------------------------------------------------------------*/

		if(!empty($ct_listing_search_exclude_mls_ids)) {
			$mls_ids = explode(',', $ct_listing_search_exclude_mls_ids);

			foreach($mls_ids as $mls_id) {
				array_push( $search_values['meta_query'], array(
					'relation' => 'AND',
					array(
						'key' => '_ct_mls',
						'value' => $mls_id,
						'compare' => 'NOT IN'
					)
				)
				);
			}

		}

		//file_put_contents(dirname(__FILE__)."/log.theme-functions", "done in getSearchArgs\r\n", FILE_APPEND);

		/*echo '<pre>';
		print_r($search_values);
		echo '</pre>';*/

		return $search_values;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Update Listing Coordinates on Theme Update */
/*-----------------------------------------------------------------------------------

function re7_upgrade_functions( ) {

	$thisTheme = wp_get_theme();
	$version = str_replace('.', '_', $thisTheme->Version);

	$updated = get_option( 'latlngUpdated_' . $version, '' );

	if( $updated == '' ) {
		updateLatLngsForAllListings();
		update_option( 'latlngUpdated_' . $version, 'true' );
	}

}
add_action( 'admin_init', 're7_upgrade_functions');

/**
 * This function adds global accessible objects for our filter.
 * To be used as the parameter which will be passed later when doing doAjax().
 *
 * @return void
 */

if(!function_exists('re7_localized_filters_type_multiple')) {

	add_action('wp_enqueue_scripts', 're7_localized_filters_type_multiple');

	function re7_localized_filters_type_multiple() {

	    $filters = array( 'filters'=> array() );

	    $multi_fields = re7_define_filters_multiple_types();

	    $field_keyval = array();

	    foreach( $multi_fields as $field ){

	        $field_value = filter_input(INPUT_GET, $field, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

	        if ( empty ( $field_value ) ) {
	            $field_value = [];
	        }

	        $field_keyval[$field] = $field_value;

	    }

	    $filters['filters'] = $field_keyval;

	    wp_localize_script('mapping', 're7_localized_multiple_filters', $filters );

	    return;

	}
}

/**
 * This function allows you to define all multiple types of fields.
 *
 * @return mixed|void The array collections of fields that are multiple.
 */

function re7_define_filters_multiple_types() {

    $default = array(
            'ct_ct_status_multi',
            'ct_city_multi',
            'ct_additional_features',
            'ct_property_type'
    );

    return apply_filters('re7_multiple_filters_types', $default);

}

/*-----------------------------------------------------------------------------------*/
/* Adds section attributes to section#main-content */
/*-----------------------------------------------------------------------------------*/

add_action('ct_wrapper_section_attr', 'ct_wrapper_section_attr__add_attr');

function ct_wrapper_section_attr__add_attr() {

    // Use the url var to check if we are in search listings page.
    $search_listing = filter_input(INPUT_GET, 'search-listings', FILTER_SANITIZE_STRING );

    if( isset( $search_listing ) && ! empty( $search_listing )) {
        echo "data-layout='". esc_attr( ct_get_search_listing_layout() ) ."'";
    }

    return;

}

/**
 * Get the layout of the search listing.
 * @return mixed|string the Layout.
 */

function ct_get_search_listing_layout() {
	global $ct_options;

	$ct_search_default_view = isset( $ct_options['ct_search_default_view'] ) ? esc_html( $ct_options['ct_search_default_view'] ) : 'map';
    
    $layout = $ct_search_default_view;

    if( isset( $_COOKIE ) ) {
        if( array_key_exists("ct_search_listing_layout", $_COOKIE ) ) {
            $layout = $_COOKIE['ct_search_listing_layout'];
        }
    }
    // Added sanitize title just to harden it a little bit.
    return sanitize_title( $layout );

}

/*-----------------------------------------------------------------------------------*/
/* Escape svg markup */
/*-----------------------------------------------------------------------------------*/

function ct_kses_svg() {

	$kses_defaults = wp_kses_allowed_html( 'post' );

	$svg_args = array(
		'svg'   => array(
			'class' => true,
			'aria-hidden' => true,
			'aria-labelledby' => true,
			'role' => true,
			'xmlns' => true,
			'style' => true,
			'width' => true,
			'height' => true,
			'version' => true,
			'viewbox' => true, 
			'xml:space' => true,
		),
		'g'     => array( 'fill' => true ),
		'title' => array( 'title' => true ),
		'path'  => array( 'd' => true, 'fill' => true,  ),
		'rect'	=> array( 'y' => true, 'height' => true, 'width' => true )
	);

	$allowed_tags = array_merge( $kses_defaults, $svg_args );

	return $allowed_tags;

}

/**
 * Handles false positives in theme check.
 * 
 * @return mixed The data on if not empty. Otherwise, boolean false.
 */

function ct_sanitize_output( $data ) {

	if(!empty( $data )) {
		return $data;
	}

	return false;

}

/**
 * Handles _SERVER in theme check.
 * 
 * @return string The value of the requested var.
 */

function ct_get_server_info( $var_name ) {
	
	if(!$var_name) {
		return '';
	}

	$filter_mode = FILTER_SANITIZE_STRING;

	return filter_input( INPUT_SERVER, $var_name, $filter_mode );

}

/*-----------------------------------------------------------------------------------*/
/* Moved registration modal to wp_body_open action instead of directly
/* injecting in header.php
/* For RE7 Theme and Elementor Pro Header Builder compatibility
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_register_modal')) {

	/**
	 * wp_footer callback function that calls for register template.
	 * 
	 * @since 3.0.7
	 * @return void
	 */

	function ct_register_modal() {

		global $ct_options;
		
		$ct_enable_front_end_login = isset( $ct_options['ct_enable_front_end_login'] ) ? esc_html( $ct_options['ct_enable_front_end_login'] ) : '';

		if ( $ct_enable_front_end_login != 'no' ) {

        	get_template_part('includes/login-register-modal');

        }
       
	}

	add_action('wp_footer', 'ct_register_modal');

}

/*-----------------------------------------------------------------------------------*/
/* Moved compare panel to wp_body_open action instead of directly
/* injecting in header.php
/* For RE7 Theme and Elementor Pro Header Builder compatibility
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_compare_panel')) {

	/**
	 * wp_footer callback function that calls for register template.
	 * 
	 * @since 3.0.7
	 * @return void
	 */

	function ct_compare_panel() {

		if(class_exists('Redq_Alike')) {
        	echo '<div id="compare-panel">';
		        echo '<div id="compare-list">';
		        echo '<button id="compare-panel-btn"><i class="fas fa-chevron-left"></i></button>';
		            if(is_active_sidebar('compare')) {
		                dynamic_sidebar('Compare');
		            } else {
		            	echo '<p class="nomatches">' . __('Add "CT Compare" Widget via Appearance > Widgets > Compare.', 'contempo') . '</p>';
		            }
		        echo '</div>';
	        echo '</div>';
        }
       
	}

	add_action('wp_footer', 'ct_compare_panel');

}

/*-----------------------------------------------------------------------------------*/
/* Parse multiple parameters in save search */
/* @return array the multiple parameters result */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_parse_multi_params')) {

	function ct_parse_multi_params( $array, $taxonomy ) {

		$status_multi = array();
		
		// Bailout if there is no taxonomy passed
		if(empty( $taxonomy )) {
			return $status_multi;
		}

		// Parse the given parameter
		if(isset( $array )) {

			$type_multi = $array;

			if( is_array( $type_multi ) && ! empty( $type_multi )) {

				foreach( $type_multi as $term_slug ) {

					$status =  get_term_by('slug', $term_slug, $taxonomy );
					
					// Prevent any errors. Skip invalid terms, etc
					if ( ! is_wp_error( $status ) ) {
						$status_multi[] = ucfirst( $status->name );
					}

				}
			}

		}

		// Sort alphabetically
		sort( $status_multi );

		// Added filter to make it easier for child themes to overwrite the value without overwriting the function itself
		return apply_filters('ct_parse_multi_params', $status_multi);
		
	}
}

/*-----------------------------------------------------------------------------------*/
/* Generates a safe url for save search */
/* @return string The url */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_generate_saved_search_url')) {

	function ct_generate_saved_search_url( $params = array() ) {
		
		return add_query_arg( $params, get_home_url() );
		
	}
}

/*-----------------------------------------------------------------------------------*/
/* Render Google reCAPTCHA v3 Script */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_render_google_recaptcha_v3_script')) {
	
	function ct_render_google_recaptcha_v3_script() {

		global $ct_options;
		
		$recaptcha_enabled = isset( $ct_options['ct_use_google_recaptcha'] ) ? $ct_options['ct_use_google_recaptcha']: '';
		
		if( empty( $recaptcha_enabled ) || 'no' === $recaptcha_enabled ) {
			return;
		}
		
		$site_key = ct_get_google_recaptcha_v3_site_key();

		$endpoint_uri = add_query_arg(
			['render' => $site_key ],
			'https://www.google.com/recaptcha/api.js'
		);

		?>
		<script src="<?php echo esc_url( $endpoint_uri ); ?>"></script>
		<?php
	}
	
}

/*-----------------------------------------------------------------------------------*/
/* Get Google reCAPTCHA v3 Site Key */
/*-----------------------------------------------------------------------------------*/

function ct_get_google_recaptcha_v3_site_key() {

	global $ct_options;

	$site_key = isset( $ct_options['ct_google_recaptcha_public_key'] ) ? $ct_options['ct_google_recaptcha_public_key']: null;
	
	if( empty( $site_key ) ) {
		return;
	}

	return apply_filters('ct_get_google_recaptcha_v3_site_key', $site_key);
}

/*-----------------------------------------------------------------------------------*/
/* Get Google reCAPTCHA v3 Secret Key */
/*-----------------------------------------------------------------------------------*/

function ct_get_google_recaptcha_v3_secret_key() {

	global $ct_options;

	$secret = isset( $ct_options['ct_google_recaptcha_secret_key'] ) ? $ct_options['ct_google_recaptcha_secret_key']: null;
	
	if( empty( $secret ) ) {
		return;
	}

	return apply_filters('ct_get_google_recaptcha_v3_site_key', $secret);
}
add_filter( 're7_google_recaptcha_v3_secret', 'ct_get_google_recaptcha_v3_secret_key');

/*-----------------------------------------------------------------------------------*/
/* Advanced Search  - Suggest Additional Features */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_search_form_keywords_additional_features')) {
	function ct_search_form_keywords_additional_features( $checked_popular_features = array() ) {
		echo '<div id="ct_additional_features_close_tpl" style="display: none;"><span class="tag-close">';
			ct_close_svg();
		echo '</span></div>';
	    echo '<div class="col span_3 first additional-features">';
			echo '<label for="ct_additional_features">' . __('Keywords', 'contempo') . '</label>';
	        echo '<div class="col span_3 first">';
	        	echo '<div class="col span_10 first">';
			    	echo '<input type="text" autocomplete="off" id="ct_additional_features_keyword_search" name="ct_additional_features_keyword_search" size="12" placeholder="' . __('Pool, Parking, Central Air…', 'contempo') . '">';
			    	echo '<div id="ct_keywords_container"></div>';
			    echo '</div>';
			    echo '<div class="col span_2">';
			    	echo '<button type="button" id="feature-add-btn">' . __('Add','contempo') . '</button>';
			    echo '</div>';
			echo '</div>';
			echo '<div id="additional-features-tags">';
				
				$additional_features = array();
				if (isset($_GET['ct_additional_features']) && is_array($_GET['ct_additional_features'])) {
					$additional_features = (array) $_GET['ct_additional_features'];
				} elseif(isset($_GET['ct_additional_features'])) {
					$additional_features = (array)  $_GET['ct_additional_features'];
				}

				if( !empty($additional_features) ){

					if( ! is_array($checked_popular_features) ){
						$checked_popular_features = array();
					}

					foreach( $additional_features as $feature ) {
						if( ! in_array($feature, $checked_popular_features) ){
							echo '<span class="additional-feature-tag">';
								echo '<input style="display:none;" checked="checked" type="checkbox" class="ct_additional_features" name="ct_additional_features[]" value="' . esc_attr(strtolower($feature)) . '" />';
									echo '<span  class="ct-keyword-name">' . ucwords($feature) . '</span>  <span class="tag-close">';
										ct_close_svg();
									echo '</span>';
							echo '</span>';
						}
					}
				} 
			echo '</div>';
	  	echo '</div>';
	}
}

if(!function_exists('ct_text_search_highlight')) {
	function ct_text_search_highlight( $str, $search ) {
	    $occurrences = substr_count(strtolower($str), strtolower($search));
	    $newstring = $str;
	    $match = array();
	 
	    for( $i=0 ; $i < $occurrences; $i++ ) {
	        $match[$i] = stripos($str, $search, $i);
	        $match[$i] = substr($str, $match[$i], strlen($search));
	        $newstring = str_replace($match[$i], '[#]'.$match[$i].'[@]', strip_tags($newstring));
	    }
	 
	    $newstring = str_replace('[#]', '<span class="ct-additional-features-highlighted">', $newstring);
	    $newstring = str_replace('[@]', '</span>', $newstring);
	    return $newstring;
	 
	}
}

/*-----------------------------------------------------------------------------------*/
/* Advanced Search  - Additional Features AJAX Handler */
/*-----------------------------------------------------------------------------------*/

add_action( 'wp_ajax_nopriv_ct_additional_feature_search', 'ct_additional_feature_search' );
add_action( 'wp_ajax_ct_additional_feature_search', 'ct_additional_feature_search' );

if(!function_exists('ct_additional_feature_search')) {
	function ct_additional_feature_search() {
		global $wpdb;

		$html = '';

		if( isset( $_POST[ 'keyword' ] )){
			$keyword = esc_sql( $_POST[ 'keyword' ] );

			$args = array();

			if( strlen($keyword) <= 1 ){ // Get results by first character
				$args = array(
					'ct_first_character' => $keyword,
					'orderby' 	=> 'name',
					'order' 	=> 'ASC',
					'taxonomy'  => array( 'additional_features' ), 
					'number' 	=> 5, // Limit
				);
			} else {
				$args = array(
					'taxonomy'      => array( 'additional_features' ), 
					'number' 		=> 5, // Limit
					'hide_empty'    => true,
					'fields'        => 'all',
					'name__like'    => $keyword
				); 
			}

			$terms = get_terms( $args );

			if( !empty($terms) ){
				$html = '<ul id="additional-features-suggested-results" class="ct-additional-features-list">';

				$distance_keywords = array();
	
				// Sort keywords based on input distance
				foreach( $terms as $k => $term ){
					$distance = levenshtein( $keyword, $term->name );
					if( ! isset($distance_keywords[ $distance ]) ){
						$distance_keywords[ $distance ] = array();
					}

					$distance_keywords[ $distance ][$term->term_id] = $term->name;
				}

				// Sort array keys
				ksort( $distance_keywords );

				foreach( $distance_keywords as $distance => $keywords ){
					foreach( $keywords as $term_id => $raw_name ){
						$highlighted = ct_text_search_highlight( $raw_name, $keyword );
						$html .= '<li class="ct-keyword" data-id="' . esc_attr($term_id) . '" data-str="' . strtolower($raw_name) . '">' . $highlighted . '</li>';
					}
				}

				$html .= '</ul>';
			}
		}

		if( $html == '' ){
			$html = '<ul id="additional-features-suggested-results">';
			$html .= '<li class="ct-keywords-no-results">' . __('No matches found.', 'contempo') . '</li>';
			$html .= '</ul>';
		}

		$output = array( 'html' => $html );

		wp_send_json($output);
	}
}

if(!function_exists('ct_additional_feature_search_clause')) {
	function ct_additional_feature_search_clause( $clauses, $taxonomies, $args ) {
	    if( isset( $args['ct_first_character'] )) {
		    if( ! empty( $args['ct_first_character'] )) {
		        global $wpdb;

		        $ct_first_character = $wpdb->esc_like( $args['ct_first_character'] );

		        if( !isset( $clauses['where'] )){
		            $clauses['where'] = '1=1';
		        }

		        $clauses['where'] .= $wpdb->prepare( " AND t.name LIKE %s", $ct_first_character ."%" );
		    }
	    }

	    return $clauses;
	}
}
add_filter( 'terms_clauses', 'ct_additional_feature_search_clause', 20, 3 );