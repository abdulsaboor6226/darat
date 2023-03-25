<?php
/**
 * Search Listings Template
 *
 * @package WP Pro Real Estate 7
 * @subpackage Template
 */
get_header();

?>

<section <?php do_action('ct_wrapper_section_attr'); ?> class="search-listings-wrap">

<?php

global $ct_options;

$ct_home_adv_search_style = isset( $ct_options['ct_home_adv_search_style'] ) ? $ct_options['ct_home_adv_search_style'] : '';
$ct_header_listing_search_hide_homepage = isset( $ct_options['ct_header_listing_search_hide_homepage'] ) ? esc_html( $ct_options['ct_header_listing_search_hide_homepage'] ) : '';
$ct_disable_listing_search_results_adv_search = isset( $ct_options['ct_disable_listing_search_results_adv_search'] ) ? $ct_options['ct_disable_listing_search_results_adv_search'] : '';
$ct_search_results_layout = isset( $ct_options['ct_search_results_layout'] ) ? $ct_options['ct_search_results_layout'] : '';
$ct_search_results_listing_style = isset( $ct_options['ct_search_results_listing_style'] ) ? $ct_options['ct_search_results_listing_style'] : '';
$ct_currency = isset( $ct_options['ct_currency'] ) ? $ct_options['ct_currency'] : '';
$ct_currency_placement = isset( $ct_options['ct_currency_placement'] ) ? $ct_options['ct_currency_placement'] : '';
$ct_sq = isset( $ct_options['ct_sq'] ) ? $ct_options['ct_sq'] : '';
$ct_acres = isset( $ct_options['ct_acres'] ) ? $ct_options['ct_acres'] : '';
$ct_header_listing_search = isset( $ct_options['ct_header_listing_search'] ) ? esc_html( $ct_options['ct_header_listing_search'] ) : '';
$ct_enable_front_end_login = isset( $ct_options['ct_enable_front_end_login'] ) ? esc_html( $ct_options['ct_enable_front_end_login'] ) : '';
$ct_listing_email_alerts_page_id = isset( $ct_options['ct_listing_email_alerts_page_id'] ) ? esc_attr( $ct_options['ct_listing_email_alerts_page_id'] ) : '';

$ct_bed_beds_or_bedrooms = isset( $ct_options['ct_bed_beds_or_bedrooms'] ) ? esc_html( $ct_options['ct_bed_beds_or_bedrooms'] ) : '';
$ct_bath_baths_or_bathrooms = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? esc_html( $ct_options['ct_bath_baths_or_bathrooms'] ) : '';

if($ct_bed_beds_or_bedrooms == 'rooms') {
	$ct_bed_beds_or_bedrooms_label = __('Rooms', 'contempo');
} elseif($ct_bed_beds_or_bedrooms == 'bedrooms') {
	$ct_bed_beds_or_bedrooms_label = __('Bedrooms', 'contempo');
} elseif($ct_bed_beds_or_bedrooms == 'beds') {
	$ct_bed_beds_or_bedrooms_label = __('Beds', 'contempo');
} else {
	$ct_bed_beds_or_bedrooms_label = __('Bed', 'contempo');
}

if($ct_bath_baths_or_bathrooms == 'bathrooms') {
	$ct_bath_baths_or_bathrooms = __('Bathrooms', 'contempo');
} elseif($ct_bath_baths_or_bathrooms == 'baths') {
	$ct_bath_baths_or_bathrooms = __('Baths', 'contempo');
} else {
	$ct_bath_baths_or_bathrooms = __('Bath', 'contempo');
}

/*-----------------------------------------------------------------------------------*/
/* Save the existing query */
/*-----------------------------------------------------------------------------------*/ 

global $wp_query;

$existing_query_obj = $wp_query;

$search_values = getSearchArgs();

$search_values['showposts'] = -1;

//file_put_contents(dirname(__FILE__)."/admin/log.theme-functions", "search-listing one\r\n", FILE_APPEND);

if ( class_exists( 'IDX_Query' ) ) {
	$idx_query = new IDX_Query( $search_values );
}
//file_put_contents(dirname(__FILE__)."/admin/log.theme-functions", "search-listing two\r\n", FILE_APPEND);

$wp_query = new WP_Query( $search_values ); 

//file_put_contents(dirname(__FILE__)."/admin/log.theme-functions", "search-listing three\r\n", FILE_APPEND);

// save the query for later because our consumers, e.g. grid, map previous next buttons
// etc reset the global query

$queryBuffer = $wp_query;

$total_results = $wp_query->found_posts;

//file_put_contents(dirname(__FILE__)."/admin/log.theme-functions", "total_results: ".$total_results."\r\n", FILE_APPEND);

/*-----------------------------------------------------------------------------------*/
/* Prepare the title string by looping through all
/* the values we're going to query and put them together
/*-----------------------------------------------------------------------------------*/                             

$search_params = array(); 

$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS', '%3D' => '%3D', '%3B' => '%3B', 'style' => 'style', '%3B' => '%3B', '%20' => '%20', 'z-index' => 'z-index');

if(!empty($_GET['ct_property_type']) && !is_numeric($_GET['ct_property_type'])) {

	if(is_array($_GET['ct_property_type'])) {
		$property_types = $_GET['ct_property_type'];
		foreach($property_types as $type) {
			$search_params[] = $type;
		}
	} else {
		$search_params[] = isset( $_GET['ct_property_type'] ) ? $_GET['ct_property_type'] : '';
	}

}

if(!empty($_GET['ct_ct_status_multi']) && !is_numeric($_GET['ct_ct_status_multi'])) {

	if(is_array($_GET['ct_ct_status_multi'])) {
		$statuses = $_GET['ct_ct_status_multi'];
		foreach($statuses as $status) {
			$search_params[] = $status;
		}
	} else {
		$search_params[] = isset( $_GET['ct_ct_status_multi'] ) ? $_GET['ct_ct_status_multi'] : '';
	}

}

if(!empty($_GET['ct_ct_status']) && !is_numeric($_GET['ct_ct_status'])) {
	$ct_ct_status = isset( $_GET['ct_ct_status'] ) ? $_GET['ct_ct_status'] : '';
	$ct_ct_status = preg_replace('/[^A-Za-z0-9\-]/', '', $ct_ct_status);
	$search_params[] = $ct_ct_status;
}

if(!empty($_GET['ct_beds']) && $_GET['ct_beds'] > 0) {
	$ct_beds = isset( $_GET['ct_beds'] ) ? $_GET['ct_beds'] : '';
	$search_params[] = $ct_beds . ' ' . $ct_bed_beds_or_bedrooms_label;
}

if(!empty($_GET['ct_baths']) && $_GET['ct_baths'] > 0) {
	$ct_baths = isset( $_GET['ct_baths'] ) ? $_GET['ct_baths'] : '';
	$search_params[] = $ct_baths . ' ' . $ct_bath_baths_or_bathrooms;
}

if(!empty($_GET['ct_beds_plus']) && $_GET['ct_beds_plus'] > 0) {
	$ct_beds_plus = isset( $_GET['ct_beds_plus'] ) ? $_GET['ct_beds_plus'] : '';
	$search_params[] = $ct_beds_plus . '+ ' . $ct_bed_beds_or_bedrooms_label;
}

if(!empty($_GET['ct_baths_plus']) && $_GET['ct_baths_plus'] > 0) {
	$ct_baths_plus = isset( $_GET['ct_baths_plus'] ) ? $_GET['ct_baths_plus'] : '';
	$search_params[] = $ct_baths_plus . '+ ' . $ct_bath_baths_or_bathrooms;

}

if(!empty($_GET['ct_city_multi']) && !is_numeric($_GET['ct_city_multi'])) {

	if(is_array($_GET['ct_city_multi'])) {
		$cities = $_GET['ct_city_multi'];
		foreach($cities as $city) {
			$search_params[] = $city;
		}
	} else {
		$search_params[] = isset( $_GET['ct_city_multi'] ) ? $_GET['ct_city_multi'] : '';
	}

}

if(!empty($_GET['ct_city']) && !is_numeric($_GET['ct_city'])) {
	$ct_city = isset( $_GET['ct_city'] ) ? $_GET['ct_city'] : '';
	$ct_city = preg_replace('/[^A-Za-z0-9\-]/', '', $ct_city);
	$search_params[] = $ct_city;
}

if(!empty($_GET['ct_state_multi']) && !is_numeric($_GET['ct_state_multi'])) {

	if(is_array($_GET['ct_state_multi'])) {
		$states = $_GET['ct_state_multi'];
		foreach($states as $state) {
			$search_params[] = strtoupper($state);
		}
	} else {
		$state = isset( $_GET['ct_state_multi'] ) ? $_GET['ct_state_multi'] : '';
		$search_params[] = strtoupper($state);
	}

}

if(!empty($_GET['ct_state']) && !is_numeric($_GET['ct_state'])) {
	$ct_state = isset( $_GET['ct_state'] ) ? $_GET['ct_state'] : '';
	$ct_state = preg_replace('/[^A-Za-z0-9\-]/', '', $ct_state);
	$search_params[] = strtoupper($ct_state);
}

if(!empty($_GET['ct_zipcode']) && $_GET['ct_zipcode'] > 0) {
	$ct_zipcode = isset( $_GET['ct_zipcode'] ) ? $_GET['ct_zipcode'] : '';
	$ct_zipcode = preg_replace('/[^A-Za-z0-9\-]/', '', $ct_zipcode);
	$search_params[] = $ct_zipcode;
}

if(!empty($_GET['ct_county_multi']) && !is_numeric($_GET['ct_county_multi'])) {

	if(is_array($_GET['ct_county_multi'])) {
		$counties = $_GET['ct_county_multi'];
		foreach($counties as $county) {
			$search_params[] = $county;
		}
	} else {
		$search_params[] = isset( $_GET['ct_county_multi'] ) ? $_GET['ct_county_multi'] : '';
	}

}

if(!empty($_GET['ct_county']) && !is_numeric($_GET['ct_county'])) {
	$ct_county = isset( $_GET['ct_county'] ) ? $_GET['ct_county'] : '';
	$ct_county = preg_replace('/[^A-Za-z0-9\-]/', '', $ct_county);
	$search_params[] = $ct_county;
}

if(!empty($_GET['ct_country']) && !is_numeric($_GET['ct_country'])) {
	$ct_country = isset( $_GET['ct_country'] ) ? $_GET['ct_country'] : '';
	$ct_country = preg_replace('/[^A-Za-z0-9\-]/', '', $ct_country);
	$search_params[] = $ct_country;
}

if(!empty($_GET['ct_community']) && !is_numeric($_GET['ct_community'])) {
	$ct_community = isset( $_GET['ct_community'] ) ? $_GET['ct_community'] : '';
	$ct_community = preg_replace('/[^A-Za-z0-9\-]/', '', $ct_community);
	$search_params[] = $ct_community;
}

if(!empty($_GET['ct_school_district']) && !is_numeric($_GET['ct_school_district'])) {
	$ct_school_district = isset( $_GET['ct_school_district'] ) ? $_GET['ct_school_district'] : '';
	$ct_school_district = preg_replace('/[^A-Za-z0-9\-]/', ' ', $ct_school_district);
	$search_params[] = $ct_school_district;
}

if(!empty($_GET['ct_brokerage']) && is_numeric($_GET['ct_brokerage'])) {
	$ct_brokerage_id = isset( $_GET['ct_brokerage'] ) ? $_GET['ct_brokerage'] : '';
	$ct_brokerage_id = preg_replace('/[^A-Za-z0-9\-]/', ' ', $ct_brokerage_id);
	$ct_brokerage = get_the_title($ct_brokerage_id);
	$search_params[] = $ct_brokerage;
}

if(!empty($_GET['ct_year_from']) && is_numeric($_GET['ct_year_from'])) {
	$ct_year_from = isset( $_GET['ct_year_from'] ) ? $_GET['ct_year_from'] : '';
	$ct_year_from = preg_replace('/[^A-Za-z0-9\-]/', '', $ct_year_from);
	$search_params[] = $ct_year_from;
}

if(!empty($_GET['ct_year_to']) && is_numeric($_GET['ct_year_to'])) {
	$ct_year_to = isset( $_GET['ct_year_to'] ) ? $_GET['ct_year_to'] : '';
	$ct_year_to = preg_replace('/[^A-Za-z0-9\-]/', '', $ct_year_to);
	$search_params[] = $ct_year_to;
}

if(!empty($_GET['ct_price_from']) && $_GET['ct_price_from'] > 0) {
	$ct_price_from = isset( $_GET['ct_price_from'] ) ? $_GET['ct_price_from'] : '';
	$ct_price_from = strip_tags($ct_price_from);
	$ct_price_from = str_replace($ct_filters, '', $ct_price_from);

	if($ct_currency_placement == 'after') {
		$search_params[] = $ct_price_from . $ct_currency;
	} else {
		$search_params[] = $ct_currency . $ct_price_from;
	}
}

if(!empty($_GET['ct_price_to']) && $_GET['ct_price_to'] > 0) {

	$ct_price_to = isset( $_GET['ct_price_to'] ) ? $_GET['ct_price_to'] : '';
	$ct_price_to = strip_tags($ct_price_to);
	$ct_price_to = str_replace($ct_filters, '', $ct_price_to);
	
	if($ct_currency_placement == 'after') {
		$search_params[] = $ct_price_to . $ct_currency;
	} else {
		$search_params[] = $ct_currency . $ct_price_to;
	}
}

if(!empty($_GET['ct_sqft_from']) && $_GET['ct_sqft_from'] > 0) {
	$ct_sqft_from = isset( $_GET['ct_sqft_from'] ) ? $_GET['ct_sqft_from'] : '';

	$ct_sqft_from = strip_tags($ct_sqft_from);
	$ct_sqft_from = str_replace($ct_filters, '', $ct_sqft_from);
	$ct_sqft_from = str_replace($ct_sq, '', $ct_sqft_from);

	$search_params[] = $ct_sqft_from . ' ' . ucfirst($ct_sq);
}

if(!empty($_GET['ct_sqft_to']) && $_GET['ct_sqft_to'] > 0) {
	$ct_sqft_to = isset( $_GET['ct_sqft_to'] ) ? $_GET['ct_sqft_to'] : '';
	$ct_sqft_to = strip_tags($ct_sqft_to);
	$ct_sqft_to = str_replace($ct_filters, '', $ct_sqft_to);
	$ct_sqft_to = str_replace($ct_sq, '', $ct_sqft_to);
	$search_params[] = $ct_sqft_to .  ' ' . ucfirst($ct_sq);
}

if(!empty($_GET['ct_lotsize_from']) && $_GET['ct_lotsize_from'] > 0) {
	$ct_lotsize_from = isset( $_GET['ct_lotsize_from'] ) ? $_GET['ct_lotsize_from'] : '';
	$ct_lotsize_from = strip_tags($ct_lotsize_from);
	$ct_lotsize_from = str_replace($ct_filters, '', $ct_lotsize_from);
	$ct_lotsize_from = str_replace($ct_acres, '', $ct_lotsize_from);
	$search_params[] = $ct_lotsize_from . ' ' . ucfirst($ct_acres);
}

if(!empty($_GET['ct_lotsize_to']) && $_GET['ct_lotsize_to'] > 0) {
	$ct_lotsize_to = isset( $_GET['ct_lotsize_to'] ) ? $_GET['ct_lotsize_to'] : '';
	$ct_lotsize_to = strip_tags($ct_lotsize_to);
	$ct_lotsize_to = str_replace($ct_filters, '', $ct_lotsize_to);
	$ct_lotsize_to = str_replace($ct_acres, '', $ct_lotsize_to);
	$search_params[] = $ct_lotsize_to . ' ' . ucfirst($ct_acres);
}

if(!empty($_GET['ct_mls'])) {
	$ct_mls = isset( $_GET['ct_mls'] ) ? $_GET['ct_mls'] : '';
	$ct_mls = strip_tags($ct_mls);
	$ct_mls = str_replace($ct_filters, '', $ct_mls);
	$search_params[] = $ct_mls;
}

if(!empty($_GET['ct_keyword'])) {
	$ct_keyword = isset( $_GET['ct_keyword'] ) ? $_GET['ct_keyword'] : '';
	$ct_keyword = strip_tags($ct_keyword);
	$ct_keyword = str_replace($ct_filters, '', $ct_keyword);
	$search_params[] = $ct_keyword;
}

if (isset( $_GET['ct_additional_features'] ) && ! empty( $_GET['ct_additional_features'] ) ) {
	if ( is_array( $_GET['ct_additional_features']  ) ) {
		$_GET['ct_additional_features'] = array_unique( $_GET['ct_additional_features'] );
		foreach( $_GET['ct_additional_features']  as $ct_additional_features ) {
			$search_params[] = esc_html( $ct_additional_features );
		}
	}
}

$search_params = str_replace('-', ' ', $search_params);
$search_params = array_map('ucwords', $search_params);
$search_params = implode(', ', $search_params);
$search_params = htmlspecialchars($search_params);

	do_action('ct_on_search_results', $search_values, $search_params);
	do_action('before_listings_search_header');

	if($ct_header_listing_search_hide_homepage == 'yes') {
		echo '<style>';
			echo '#header-search-wrap { display: block;}';
		echo '</style>';
	}
	
	if($ct_header_listing_search != 'yes') {
		echo '<!-- Title Header -->';
	    echo '<header id="title-header" class="marB0">';
	        echo '<div class="container">';
	            echo '<h5 class="marT0 marB0 left muted">';
				echo '<span id="number-listings-found">'.esc_html($total_results).'</span>';
				echo ' ';
				if($total_results != '1') { esc_html_e('listings found', 'contempo'); } else { esc_html_e('listing found', 'contempo'); }
				if ( isset( $ct_options['ct_disable_google_maps_search'] ) ) {
					if($ct_options['ct_disable_google_maps_search'] == 'no') {
						echo '<div id="number-listings-progress">';
							echo '<span class="left"><i class="fas fa-circle-notch fa-spin fa-fw"></i></span>';
							echo __('Loading Results…', 'contempo');
						echo '</div>';
					}
				}
				echo '</h5>';
				echo '<div class="muted right">';
					esc_html_e('Find A Home', 'contempo');
				echo '</div>';
			echo '<div class="clear"></div>';
	        echo '</div>';
	    echo '</header>';
	    echo '<!-- //Title Header -->';
	}

    if($ct_disable_listing_search_results_adv_search != 'no' &&  $ct_search_results_layout == 'sidebyside') {
		
		if($ct_header_listing_search != 'yes') {
			do_action('before_listings_adv_search');

			echo '<section class="side-by-side search-results advanced-search ' . $ct_home_adv_search_style . '">';
				echo '<div class="container">';
					get_template_part('/includes/advanced-search');
				echo '</div>';
			echo'</section>';
			echo '<div class="clear"></div>';
		}

	}

    do_action('before_listings_search_map');
	
	if(isset( $ct_options['ct_disable_google_maps_search'] ) && $ct_options['ct_disable_google_maps_search'] == 'no') {

		echo '<!-- Map -->';
		if($ct_search_results_layout == 'sidebyside') {
			echo '<div id="map-wrap" class="listings-results-map col span_6 side-map">';
			if($ct_options['ct_disable_google_maps_search'] == 'no') {
				echo '<div id="number-listings-progress">';
					echo '<span class="left"><i class="fas fa-circle-notch fa-spin fa-fw"></i></span>';
					echo __('Loading Results…', 'contempo');
				echo '</div>';
			}
		} else {
			echo '<div id="map-wrap" class="listings-results-map stacked">';
		}

			// Marker Navigation
			ct_search_results_map_navigation();
			
			// Map
			ct_search_results_map();

			// restore the query:
			$wp_query = $queryBuffer;
			
		echo '</div>';
		echo '<!-- //Map -->';

	}

	if($ct_header_listing_search == 'yes' && $ct_search_results_layout != 'sidebyside') {
		echo '<!-- Title Header -->';
	    echo '<header id="title-header" class="marT0 marB0">';
	        echo '<div class="container">';
	            echo '<h5 class="marT0 marB0 left muted">';
				echo '<span id="number-listings-found">'.esc_html($total_results).'</span>';
				echo ' ';
				if($total_results != '1') { esc_html_e('listings found', 'contempo'); } else { esc_html_e('listing found', 'contempo'); }
				if($ct_options['ct_disable_google_maps_search'] == 'no') {
					echo '<div id="number-listings-progress">';
						echo '<span class="left"><i class="fas fa-circle-notch fa-spin fa-fw"></i></span>';
						echo __('Loading Results…', 'contempo');
					echo '</div>';
				}
				echo '</h5>';
				echo '<div class="muted right">';
					esc_html_e('Find A Home', 'contempo');
				echo '</div>';
			echo '<div class="clear"></div>';
	        echo '</div>';
	    echo '</header>';
	    echo '<!-- //Title Header -->';

	    if($ct_search_results_layout != 'sidebyside') {
		    echo '<!-- Searching On -->';
			echo '<div class="searching-on ' . $ct_home_adv_search_style . '">';
				echo '<div class="container">';
					echo '<span class="searching">' . __('Searching:', 'contempo') . '</span>';
					if(!empty($search_params)) {
						echo '<span class="search-params">' . $search_params . '</span>';
					} else { 
						echo '<span class="search-params">' . __('All listings', 'contempo') . '</span>';
					}
					if($ct_options['ct_disable_google_maps_search'] == 'no') {
						echo '<span class="map-toggle"><span id="text-toggle">' . __('Close Map', 'contempo') . '</span><i class="fa fa-minus-square-o"></i></span>';
					}
					echo '</div>';
			echo '</div>';
			echo '<!-- //Searching On -->';
		}
	}

	do_action('before_listings_searching_on');
	
	echo '<!-- Search Results -->';
		if($ct_search_results_layout == 'sidebyside') {
			echo '<div class="col span_6 side-results">';
				echo '<div id="searching-on" class="border-bottom">';
					echo '<h5 id="searching-on" class="marT20 marB0 left"><strong>' . __('Searching:', 'contempo') . '</strong> ';
						if(!empty($search_params)) {
							echo '<span id="search-params">' . $search_params . '</span>';
						} else { 
							echo '<span id="search-params">' . __('All listings', 'contempo') . '</span>';
						}
					echo '</h5>';
					echo '<h5 class="marT20 marB0 right muted"><span id="number-listings-found">' . esc_html($total_results) . '</span> ';
						if($total_results != '1') { esc_html_e('listings found', 'contempo'); } else { esc_html_e('listing found', 'contempo'); }
					echo '</h5>';
						echo '<div class="clear"></div>';
				echo '</div>';
				
				echo '<div id="search-results-layout-toggle-mobile" class="col span_5 first">';
                    $current_layout = ct_get_search_listing_layout();
					echo '<button id="map-layout" class="' . sanitize_html_class( $current_layout === 'map' ? 'current': '') . '">';
                        echo __('Map', 'contempo');
                    echo '</button>';
					echo '<button id="grid-layout" class="' . sanitize_html_class( $current_layout === 'grid' ? 'current': '') . '">';
                        echo __('List', 'contempo');
                    echo '</button>';
				echo '</div>';
		}
			if($ct_disable_listing_search_results_adv_search == 'no' &&  $ct_search_results_layout != 'sidebyside') {
				echo '<!-- Searching On -->';
				echo '<div class="searching-on ' . $ct_home_adv_search_style . '">';
					echo '<div class="container">';
						echo '<span class="searching">' . __('Searching:', 'contempo') . '</span>';
						if(!empty($search_params)) {
							echo '<span class="search-params">' . $search_params . '</span>';
						} else { 
							echo '<span class="search-params">' . __('All listings', 'contempo') . '</span>';
						}
						echo '<span class="map-toggle"><span id="text-toggle">' . __('Close Map', 'contempo') . '</span><i class="fa fa-minus-square-o"></i></span>';
						echo '</div>';
				echo '</div>';
				echo '<!-- //Searching On -->';
			
				do_action('before_listings_adv_search');

				if($ct_disable_listing_search_results_adv_search == 'no') {
					echo '<section class="search-results advanced-search ' . $ct_home_adv_search_style . '">';
						echo '<div class="container">';
							get_template_part('/includes/advanced-search');
						echo '</div>';
					echo'</section>';
					echo '<div class="clear"></div>';
				}
			} ?>

			<?php do_action('before_listing_search_results'); ?>

			<div class="container">
				<!-- Listing Results -->
				<div id="listings-results" class="listing-search-results col span_12 first">

					<div id="listing-search-tools" class="col span_12 <?php if($ct_disable_listing_search_results_adv_search == 'yes' && $ct_search_results_layout != 'sidebyside') { echo 'marT30'; } ?>">

						<div class="col span_8 first">
							
							<?php if($ct_search_results_layout == 'sidebyside') { ?>
								<div id="search-results-layout-toggle" class="col span_5 first">
                                    <?php $current_layout = ct_get_search_listing_layout(); ?>
									<button id="map-layout" class="<?php echo sanitize_html_class( $current_layout === 'map' ? 'current': ''); ?>">
                                        <?php esc_html_e('Map', 'contempo'); ?>
                                    </button>
									<button id="grid-layout" class="<?php echo sanitize_html_class( $current_layout === 'grid' ? 'current': ''); ?>">
                                        <?php esc_html_e('Grid', 'contempo'); ?>
                                    </button>
								</div>
							<?php } ?>

							<?php if(function_exists('ctea_show_alert_creation')) {
								echo '<div id="save-search" class="col span_7">';
									if(is_user_logged_in()) { ?>
										<form method="post" action="" class="form-searched-save-search left">
											<?php
												$hashed_search_args = '';
												if( class_exists('CT_RealEstate7_Helper') ) {
													$hashed_search_args = CT_RealEstate7_Helper::hash64('encode', serialize( $search_values ) );
												} 
											?>
											<input type="hidden" name="search_args" value="<?php echo esc_attr( $hashed_search_args ); ?>">
											<input type="hidden" name="search_URI" value="<?php echo esc_html( ct_get_server_info('REQUEST_URI') ); ?>">
											<input type="hidden" name="action" value='ct_searched_save_search'>
											<input type="hidden" name="ct_searched_save_search_ajax" value="<?php echo wp_create_nonce('ct-searched-save-search-nounce')?>">
											<a id="searched-save-search" class="btn save-btn"><?php _e('Save Search', 'contempo'); ?></a>
										</form>
									
										<a id="view-saved" class="btn" href="<?php echo get_page_link($ct_listing_email_alerts_page_id); ?>"><?php _e('View Saved', 'contempo'); ?></a>
									<?php } elseif($ct_enable_front_end_login != 'no') { ?>
										<a id="searched-save-search" class="btn login-register save-btn"><?php _e('Save Search', 'contempo'); ?></a>
									<?php }
								echo '</div>';
							} ?>
						</div>
						<div id="sort-by" class="col span_4">
							<?php ct_sort_by(); ?>
						</div>
					</div>

					<?php
	                
						$search_values['paged'] = ct_currentPage();
						$search_num = isset( $ct_options['ct_listing_search_num'] ) ? $ct_options['ct_listing_search_num']: 6;
						$search_values['showposts'] = $search_num;
				
						$wp_query = new wp_query( $search_values ); 
						
						if($ct_search_results_listing_style == 'list') {
							get_template_part( 'layouts/list');
						} else {
							get_template_part( 'layouts/grid');
						}
					
						echo '<div class="clear"></div>';
				echo '</div>';
				echo '<!-- Listing Results -->';

			// Restore WP_Query object
			$wp_query = $existing_query_obj;

			echo '<div class="clear"></div>';
		echo '</div>';
	if($ct_search_results_layout == 'sidebyside') {
	echo '</div>';
	}
	echo '<!-- //Search Results -->';

	do_action('after_listing_search_results');

echo '</section>';
echo '<!-- // Search Listings Wrap -->';
	echo '<div class="clear"></div>';

wp_reset_postdata();

// IDX Disclaimers
if(class_exists('IDX')) {
    $oIDX = new IDX();
    $disclaimer = $oIDX->ct_idx_disclaimer_text();

    if($disclaimer != '') {
        echo '<div class="container">';
            echo '<div id="disclaimer" class="muted col span_12 first">';
                print wp_kses_post( $disclaimer, 'post' );
            echo '</div>';
        echo '</div>';
    }
}

get_footer(); ?>