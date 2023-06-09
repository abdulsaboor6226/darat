<?php
/**
 * Header Advanced Search
 *
 * @package WP Pro Real Estate 7
 * @subpackage Include
 */
 
global $ct_options;

$ct_header_adv_search_fields = isset( $ct_options['ct_header_adv_search_fields']['enabled'] ) ? $ct_options['ct_header_adv_search_fields']['enabled'] : '';
$ct_home_adv_search_fields = isset( $ct_options['ct_home_adv_search_fields']['enabled'] ) ? $ct_options['ct_home_adv_search_fields']['enabled'] : '';
$ct_adv_search_more_layout = isset( $ct_options['ct_adv_search_more_layout'] ) ? esc_html( $ct_options['ct_adv_search_more_layout'] ) : '';
$ct_keyword_suggested_results = isset( $ct_options['ct_keyword_suggested_results'] ) ? esc_html( $ct_options['ct_keyword_suggested_results'] ) : '';
$ct_currency = isset( $ct_options['ct_currency'] ) ? $ct_options['ct_currency'] : '';
$ct_currency_placement = $ct_options['ct_currency_placement'];
$ct_sq = isset( $ct_options['ct_sq'] ) ? $ct_options['ct_sq'] : '';
$ct_acres = isset( $ct_options['ct_acres'] ) ? $ct_options['ct_acres'] : '';
$ct_city_town_or_village = isset($ct_options['ct_city_town_or_village']) ? $ct_options['ct_city_town_or_village'] : '';
$ct_bed_beds_or_bedrooms = isset( $ct_options['ct_bed_beds_or_bedrooms'] ) ? esc_html( $ct_options['ct_bed_beds_or_bedrooms'] ) : '';
$ct_bath_baths_or_bathrooms = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? esc_html( $ct_options['ct_bath_baths_or_bathrooms'] ) : '';

$ct_adv_search_price_slider_min_value = isset( $ct_options['ct_adv_search_price_slider_min_value'] ) ? $ct_options['ct_adv_search_price_slider_min_value'] : '';
$ct_adv_search_price_slider_max_value = isset( $ct_options['ct_adv_search_price_slider_max_value'] ) ? $ct_options['ct_adv_search_price_slider_max_value'] : '';

$ct_adv_search_size_slider_min_value = isset( $ct_options['ct_adv_search_size_slider_min_value'] ) ? $ct_options['ct_adv_search_size_slider_min_value'] : '';
$ct_adv_search_size_slider_max_value = isset( $ct_options['ct_adv_search_size_slider_max_value'] ) ? $ct_options['ct_adv_search_size_slider_max_value'] : '';

$ct_adv_search_lot_size_slider_min_value = isset( $ct_options['ct_adv_search_lot_size_slider_min_value'] ) ? $ct_options['ct_adv_search_lot_size_slider_min_value'] : '';
$ct_adv_search_lot_size_slider_max_value = isset( $ct_options['ct_adv_search_lot_size_slider_max_value'] ) ? $ct_options['ct_adv_search_lot_size_slider_max_value'] : '';

$ct_years = array('2023','2022','2021','2020','2019','2018','2017','2016','2015','2010','2005','2000','1990','1980','1970','1960','1950','1940','1930','1920','1910','1900');

$ct_filters = array('"' => '"', '=' => '=', '>' => '>', '<' => '<', '\\' => '\\', '/' => '/', '(' => '(', ')' => ')', 'autofocus' => 'autofocus', 'onfocus' => 'onfocus', 'alert' => 'alert', 'XSS' => 'XSS', '%3D' => '%3D', '%3B' => '%3B', 'style' => 'style', '%3B' => '%3B', '%20' => '%20', 'z-index' => 'z-index');

/* Get Current Search Values for Inputs */
$ct_keyword = isset( $_GET['ct_keyword']) ? $_GET['ct_keyword'] : '';
$ct_keyword = strip_tags($ct_keyword);
$ct_keyword = str_replace($ct_filters, '', $ct_keyword);

$ct_mobile_keyword = isset( $_GET['ct_mobile_keyword']) ? $_GET['ct_mobile_keyword'] : '';
$ct_mobile_keyword = strip_tags($ct_mobile_keyword);
$ct_mobile_keyword = str_replace($ct_filters, '', $ct_mobile_keyword);

$ct_beds_plus = isset( $_GET['ct_beds_plus']) ? $_GET['ct_beds_plus'] : '';
$ct_baths_plus = isset( $_GET['ct_baths_plus']) ? $_GET['ct_baths_plus'] : '';

$ct_price_from = isset( $_GET['ct_price_from']) ? $_GET['ct_price_from'] : '';
$ct_price_from = str_replace($ct_filters, '', $ct_price_from);
$ct_price_to = isset( $_GET['ct_price_to']) ? $_GET['ct_price_to'] : '';
$ct_price_to = str_replace($ct_filters, '', $ct_price_to);

$ct_price_from = str_replace($ct_currency, '', $ct_price_from);
$ct_price_to = str_replace($ct_currency, '', $ct_price_to);

$ct_sqft_from = isset( $_GET['ct_sqft_from']) ? $_GET['ct_sqft_from'] : '';
$ct_sqft_from = str_replace($ct_filters, '', $ct_sqft_from);
$ct_sqft_from = str_replace($ct_sq, '', $ct_sqft_from);
$ct_sqft_to = isset( $_GET['ct_sqft_to']) ? $_GET['ct_sqft_to'] : '';
$ct_sqft_to = str_replace($ct_filters, '', $ct_sqft_to);
$ct_sqft_to = str_replace($ct_sq, '', $ct_sqft_to);

$ct_lotsize_from = isset( $_GET['ct_lotsize_from']) ? $_GET['ct_lotsize_from'] : '';
$ct_lotsize_from = str_replace($ct_filters, '', $ct_lotsize_from);
$ct_lotsize_from = str_replace($ct_acres, '', $ct_lotsize_from);
$ct_lotsize_to = isset( $_GET['ct_lotsize_to']) ? $_GET['ct_lotsize_to'] : '';
$ct_lotsize_to = str_replace($ct_filters, '', $ct_lotsize_to);
$ct_lotsize_to = str_replace($ct_acres, '', $ct_lotsize_to);

$ct_year_from = isset( $_GET['ct_year_from']) ? $_GET['ct_year_from'] : '';
$ct_year_from = str_replace($ct_filters, '', $ct_year_from);
$ct_year_to = isset( $_GET['ct_year_to']) ? $_GET['ct_year_to'] : '';
$ct_year_to = str_replace($ct_filters, '', $ct_year_to);

$ct_community = isset( $_GET['ct_community']) ? $_GET['ct_community'] : '';
$ct_community = str_replace($ct_filters, '', $ct_community);
$ct_community = preg_replace('/[^A-Za-z0-9\-]/', '', $ct_community);

$ct_school_district = isset( $_GET['ct_school_district']) ? $_GET['ct_school_district'] : '';
$ct_school_district = str_replace($ct_filters, '', $ct_school_district);
$ct_school_district = preg_replace('/[^A-Za-z0-9\-]/', '', $ct_school_district);

$ct_mls = isset( $_GET['ct_mls']) ? $_GET['ct_mls'] : '';
$ct_mls = str_replace($ct_filters, '', $ct_mls);
$ct_rental_guests = isset( $_GET['ct_rental_guests']) ? $_GET['ct_rental_guests'] : '';
$ct_rental_guests = str_replace($ct_filters, '', $ct_rental_guests);

?>

<!-- Header Search -->
<div id="header-search-wrap">
	<div class="container">
        <form id="advanced_search" class="col span_12 first header-search" name="search-listings" action="<?php echo home_url(); ?>">

        <div id="header-mobile-search" class="col span_12 first">
        	
        	<div id="suggested-search" class="col span_8 first">
            	<div id="keyword-wrap">					
	                <label for="ct_mobile_keyword"><?php _e('Keyword', 'contempo'); ?></label>
	                <input type="text" id="ct_mobile_keyword" class="number header_keyword_search" name="ct_mobile_keyword" size="8" placeholder="<?php esc_html_e('Street, City, State, or Zip', 'contempo'); ?>" <?php if($ct_mobile_keyword != '') { echo 'value="' . ucfirst($ct_mobile_keyword) . '"'; } ?> autocomplete="off" />
                </div>
                <?php if($ct_keyword_suggested_results != 'no') { ?>
					<div class="listing-search" style="display: none"><span id="listing-search-loader"></span><?php _e('Searching...', 'contempo'); ?></div>
					<div class="listing-search-suggestion-box" id="suggestion-box" style="display: none;"></div>
				<?php } ?>
            </div>

            <div class="col span_4">
	            <button class="col span_7 first"><?php ct_search_svg(); ?></button>
		        <span id="filters-search-options-toggle" class="btn col span_5"><?php ct_filters_svg(); ?></span>
	       </div>
		       	<div class="clear"></div>
        </div>

        	<div class="clear"></div>

        <div id="header-search-inner-wrap">
			<?php
	        		if ($ct_header_adv_search_fields) {			    
				    foreach ($ct_header_adv_search_fields as $field=>$value) {			    
				        switch($field) {						
						
						// Type            
				        case 'header_type' : ?>
				            <div id="property_type" class="col span_2">
				                <label for="ct_type"><?php _e('Type', 'contempo'); ?></label>
				                <?php ct_search_form_select('property_type'); ?>
				            </div>
				        <?php
						break;
						
						// City
						case 'header_city' : ?>
						<div id="city_code" class="col span_2">
							<label for="ct_city"><?php _e('City', 'contempo'); ?></label>
							<?php ct_search_form_select('city'); ?>
							<div class="my_old_city" style=" display: none;"></div>
							
						</div>
				        <?php
						break;

						// City Multi        
				        case 'header_city_multi' :
				        	if(!in_array('City', $ct_home_adv_search_fields) || !in_array('City', $ct_header_adv_search_fields) || !in_array('City (multi)', $ct_home_adv_search_fields)) { ?>
					            <div id="header_city_multi" class="col span_2">
					                <label for="header_city_multi"><span id="ct-city-text"></span><span id="ct-city-count"></span></label>
					                <?php ct_search_form_checkboxes_toggles_city('city'); ?>
					            </div>
				            <?php }
						break;
						
				        // State            
				        case 'header_state' : ?>
				            <div id="state_code" class="col span_2">
								<?php ct_search_form_select('state'); ?>
								<div class="my_old_state" style=" display: none;"></div>
								
				            </div>
				        <?php
						break;

						// State Multi        
				        case 'header_state_multi' :
				        	if(!in_array('State', $ct_home_adv_search_fields) || !in_array('State', $ct_header_adv_search_fields) || !in_array('State (multi)', $ct_home_adv_search_fields)) { ?>
					            <div id="header_state_multi" class="col span_2">
					                <label for="header_state_multi"><span id="ct-state-text"></span><span id="ct-state-count"></span></label>
					                <?php ct_search_form_checkboxes_toggles_state('state'); ?>
					            </div>
				            <?php }
						break;

						// Zipcode            
				        case 'header_zipcode' : ?>
				            <div id="zip_code" class="col span_2 ">
								<?php ct_search_form_select('zipcode'); ?>
								<div class="my_old_data" style=" display: none;"></div>
				            </div>
				        <?php
						break;

				        // Country            
				        case 'header_country' : ?>
				            <div id="country_code" class="col span_2">
				                <label for="ct_country"><?php _e('Country', 'contempo'); ?></label>
				                <?php ct_search_form_select('country'); ?>
								<div class="my_old_country" style=" display: none;"></div>
				            </div>
				        <?php
				        break;

				        // County            
				        case 'header_county' : ?>
				            <div id="county" class="col span_2">
				                <label for="ct_county"><?php _e('County', 'contempo'); ?></label>
				                <?php ct_search_form_select('county'); ?>
				            </div>
				        <?php
				        break;

				        // County Multi        
				        case 'header_county_multi' :
				        	if(!in_array('County', $ct_home_adv_search_fields) || !in_array('County', $ct_header_adv_search_fields) || !in_array('County (multi)', $ct_home_adv_search_fields)) { ?>
					            <div id="header_county_multi" class="col span_2">
					                <label for="header_county_multi"><span id="ct-county-text"></span><span id="ct-county-count"></span></label>
					                <?php ct_search_form_checkboxes_toggles_county('county'); ?>
					            </div>
				            <?php }
						break;

				        // Community            
				        case 'header_community' : ?>
				            <div id="ct_community" class="col span_2">
				                <label for="ct_community"><?php _e('Community', 'contempo'); ?></label>
				                <?php ct_search_form_select('community'); ?>
				            </div>
				        <?php
				        break;
						
						// Beds            
				        case 'header_beds' : ?>
				            <div class="col span_2">
				                <label for="ct_beds"><?php _e('Beds', 'contempo'); ?></label>
								<?php ct_search_form_select('beds'); ?>
				            </div>
				        <?php
						break;
						
						// Baths            
				        case 'header_baths' : ?>
				            <div class="col span_2">
				                <label for="ct_baths"><?php _e('Baths', 'contempo'); ?></label>
								<?php ct_search_form_select('baths'); ?>
				            </div>
				        <?php
						break;

						// Beds            
				        case 'header_beds_plus' : ?>
				            <div class="col span_2">
				                <label for="ct_beds_plus"><?php _e('Beds +', 'contempo'); ?></label>
								<select id="ct_beds_plus" name="ct_beds_plus">
									<option value="">
										<?php if($ct_bed_beds_or_bedrooms == 'rooms') {
							    			_e('All Rooms', 'contempo');
							    		} elseif($ct_bed_beds_or_bedrooms == 'bedrooms') {
							    			_e('All Bedrooms', 'contempo');
							    		} elseif($ct_bed_beds_or_bedrooms == 'beds') {
							    			_e('All Beds', 'contempo');
								    	} else {
								    		_e('All Bed', 'contempo');
								    	} ?>
								    	<option value="1" <?php if($ct_beds_plus == 1) { echo 'selected'; } ?>>1+</option>
								    	<option value="2" <?php if($ct_beds_plus == 2) { echo 'selected'; } ?>>2+</option>
								    	<option value="3" <?php if($ct_beds_plus == 3) { echo 'selected'; } ?>>3+</option>
								    	<option value="4" <?php if($ct_beds_plus == 4) { echo 'selected'; } ?>>4+</option>
								    	<option value="5" <?php if($ct_beds_plus == 5) { echo 'selected'; } ?>>5+</option>
									</option>
								</select>
				            </div>
				        <?php
						break;
						
						// Baths            
				        case 'header_baths_plus' : ?>
				            <div class="col span_2">
				                <label for="ct_baths_plus"><?php _e('Baths +', 'contempo'); ?></label>
								<select id="ct_baths_plus" name="ct_baths_plus">
									<option value="">
										<?php if($ct_bath_baths_or_bathrooms == 'bathrooms') {
							    			_e('All Bathrooms', 'contempo');
							    		} elseif($ct_bath_baths_or_bathrooms == 'baths') {
							    			_e('All Baths', 'contempo');
							    		} else {
								    		_e('All Bath', 'contempo');
								    	} ?>
								    	<option value="1" <?php if($ct_baths_plus == 1) { echo 'selected'; } ?>>1+</option>
								    	<option value="2" <?php if($ct_baths_plus == 2) { echo 'selected'; } ?>>2+</option>
								    	<option value="3" <?php if($ct_baths_plus == 3) { echo 'selected'; } ?>>3+</option>
								    	<option value="4" <?php if($ct_baths_plus == 4) { echo 'selected'; } ?>>4+</option>
								    	<option value="5" <?php if($ct_baths_plus == 5) { echo 'selected'; } ?>>5+</option>
									</option>
								</select>
				            </div>
				        <?php
						break;
						
						// Status            
				        case 'header_status' : ?>
				            <div id="status" class="col span_2">
				                <label for="ct_status"><?php _e('Status', 'contempo'); ?></label>
								<?php ct_search_form_select('ct_status'); ?>
				            </div>
				        <?php
						break;

						// Status Multi        
				        case 'header_status_multi' :
				        	if(!in_array('Status', $ct_home_adv_search_fields) || !in_array('Status', $ct_header_adv_search_fields) || !in_array('Status (multi)', $ct_home_adv_search_fields)) { ?>
					            <div id="header_status_multi" class="col span_2">
					                <label for="ct_status_multi"><span id="ct-status-text"></span><span id="ct-status-count"></span></label>
					                <?php ct_search_form_checkboxes_toggles_status('ct_status'); ?>
					            </div>
				            <?php }
						break;

						// Brokerage            
				        case 'header_brokerage' : ?>
				            <div id="ct_brokerage" class="col span_2">
				                <label for="ct_brokerage"><?php _e('Brokerage', 'contempo'); ?></label>
								<?php ct_search_form_brokerage_select('ct_brokerage'); ?>
				            </div>
				        <?php
						break;

				        // Community          
				        case 'header_community' : ?>
				            <div id="ct_community" class="col span_2">
				                <?php
				                global $ct_options;
				                $ct_community_neighborhood_or_district = isset( $ct_options['ct_community_neighborhood_or_district'] ) ? $ct_options['ct_community_neighborhood_or_district'] : '';

				                if($ct_community_neighborhood_or_district == 'neighborhood') { ?>
				                    <label for="ct_community"><?php _e('Neighborhood', 'contempo'); ?></label>
				                <?php } elseif($ct_community_neighborhood_or_district == 'district') { ?>
				                    <label for="ct_community"><?php _e('District', 'contempo'); ?></label>
				                <?php } else { ?>
				                    <label for="ct_community"><?php _e('Community', 'contempo'); ?></label>
				                <?php } ?>
				                <?php ct_search_form_select('community'); ?>
				            </div>
				        <?php
				        break;
						
						// Price From            
				        case 'header_price_from' : ?>
				            <div class="col span_2">
				                <label for="ct_price_from"><?php _e('Price From', 'contempo'); ?> (<?php ct_currency(); ?>)</label>
				                <input type="number" id="ct_price_from" class="number" name="ct_price_from" size="8" placeholder="<?php esc_html_e('Price From', 'contempo'); ?> (<?php ct_currency(); ?>)" <?php if($ct_price_from != '') { echo 'value="' . esc_html($ct_price_from) . '"'; } ?> />
				            </div>
				        <?php
						break;
						
						// Price To            
				        case 'header_price_to' : ?>
				            <div class="col span_2">
				                <label for="ct_price_to"><?php _e('Price To', 'contempo'); ?> (<?php ct_currency(); ?>)</label>
				                <input type="number" id="ct_price_to" class="number" name="ct_price_to" size="8" placeholder="<?php esc_html_e('Price To', 'contempo'); ?> (<?php ct_currency(); ?>)" <?php if($ct_price_to != '') { echo 'value="' . esc_html($ct_price_to) . '"'; } ?> />
				            </div>
				        <?php
						break;

						// Price Slider            
				        case 'header_price_from_to_slider' : ?>
				            <div id="price-from-to-slider" class="col span_3">
					            <div class="col span_12 first">
					            	<span class="slider-label"><?php _e('Price range:', 'contempo'); ?></span>
					            	<span class="min-range">
						            	<?php if($ct_currency_placement == 'after') {
					            			if(!empty($ct_adv_search_price_slider_min_value)) { 
												echo esc_html( $ct_adv_search_price_slider_min_value ); 
											} else {
												 echo '100,000'; 
											}
					            			ct_currency();
					            		} else {
					            			ct_currency();
					            			if(!empty($ct_adv_search_price_slider_min_value)) { 
												echo esc_html( $ct_adv_search_price_slider_min_value ); 
											} else { 
												echo '100,000'; 
											}
					            		} ?>
				            		</span>
					            	<span class="slider-label"><?php _e('to', 'contempo'); ?></span>
								    <span class="max-range">
								    	<?php if($ct_currency_placement == 'after') {
								    		if(!empty($ct_adv_search_price_slider_max_value)) { 
												echo esc_html( $ct_adv_search_price_slider_max_value ); 
											} else { 
												echo '5,000,000'; 
											}
								    		ct_currency();
								    	} else {
								    		ct_currency();
								    		if(!empty($ct_adv_search_price_slider_max_value)) { 
												echo esc_html( $ct_adv_search_price_slider_max_value ); 
											} else { 
												echo '5,000,000'; 
											}
								    	} ?>
								    </span>
					            </div>
				            	<div class="slider-range-wrap col span_12 first">
								    <div id="slider-range"></div>
								</div>
				                <input type="hidden" id="ct_price_from" class="number" name="ct_price_from" size="8" <?php if($ct_price_from != '') { echo 'value="' . esc_html($ct_price_from) . '"'; } ?> />
				                <input type="hidden" id="ct_price_to" class="number" name="ct_price_to" size="8" <?php if($ct_price_to != '') { echo 'value="' . esc_html($ct_price_to) . '"'; } ?> />
				            </div>
				        <?php
						break;

				        // Sq Ft From            
				        case 'header_sqft_from' : ?>
				            <div class="col span_2">
				                <label for="ct_sqft_from"><?php ct_sqftsqm(); ?> <?php _e('From', 'contempo'); ?></label>
				                <input type="number" id="ct_sqft_from" class="number" name="ct_sqft_from" size="8" placeholder="<?php _e('Size From', 'contempo'); ?> - <?php ct_sqftsqm(); ?>" <?php if($ct_sqft_from != '') { echo 'value="' . $ct_sqft_from . '"'; } ?> />
				            </div>
				        <?php
				        break;
				        
				        // Sq Ft To            
				        case 'header_sqft_to' : ?>
				            <div class="col span_2">
				                <label for="ct_sqft_to"><?php ct_sqftsqm(); ?> <?php _e('To', 'contempo'); ?></label>
				                <input type="number" id="ct_sqft_to" class="number" name="ct_sqft_to" size="8" placeholder="<?php _e('Size To', 'contempo'); ?> - <?php ct_sqftsqm(); ?>" <?php if($ct_sqft_to != '') { echo 'value="' . $ct_sqft_to. '"'; } ?> />
				            </div>
				        <?php
				        break;

				        // Sq Ft Slider            
				        case 'header_sqft_from_to_slider' : ?>
				            <div id="size-from-to-slider" class="col span_3">
				            	<div class="col span_12 first">
					            	<span class="slider-label"><?php _e('Size range:', 'contempo'); ?></span>
					            	<span class="min-range">
										<?php 
											if( ! empty( $ct_adv_search_size_slider_min_value ) ) {
												echo esc_html( $ct_adv_search_size_slider_min_value ); 
											} else { 
												echo '100';
											} 
										?>
									</span>
					            	<span class="slider-label"><?php _e('to', 'contempo'); ?></span>
								    <span class="max-range">
										<?php 
											if( ! empty( $ct_adv_search_size_slider_max_value ) ) { 
												echo esc_html( $ct_adv_search_size_slider_max_value );
											} else { 
												echo '10,000'; 
											} 
										?>
										</span>
					            </div>
				            	<div class="slider-range-wrap col span_12 first">
								    <div id="slider-range-two"></div>
								</div>
				                <input type="hidden" id="ct_sqft_from" class="number" name="ct_sqft_from" size="8" <?php if($ct_sqft_from != '') { echo 'value="' . esc_attr( $ct_sqft_from ) . '"'; } ?> />
				                <input type="hidden" id="ct_sqft_to" class="number" name="ct_sqft_to" size="8" <?php if($ct_sqft_to != '') { echo 'value="' . esc_attr( $ct_sqft_to ) . '"'; } ?> />
				            </div>
				        <?php
				        break;

				        // Lot Size From            
				        case 'header_lotsize_from' : ?>
				            <div class="col span_2">
				                <label for="ct_lotsize_from"><?php _e('Lot Size From', 'contempo'); ?> <?php ct_sqftsqm(); ?></label>
				                <input type="number" id="ct_lotsize_from" class="number" name="ct_lotsize_from" size="8" placeholder="<?php _e('Lot Size From', 'contempo'); ?> - <?php ct_acres(); ?>" <?php if($ct_lotsize_from != '') { echo 'value="' . esc_attr( $ct_lotsize_from ) . '"'; } ?> />
				            </div>
				        <?php
				        break;
				        
				        // Lot Size To            
				        case 'header_lotsize_to' : ?>
				            <div class="col span_2">
				                <label for="ct_lotsize_to"><?php _e('Lot Size To', 'contempo'); ?> <?php ct_sqftsqm(); ?></label>
				                <input type="number" id="ct_lotsize_to" class="number" name="ct_lotsize_to" size="8" placeholder="<?php _e('Lot Size To', 'contempo'); ?> - <?php ct_acres(); ?>" <?php if($ct_lotsize_to != '') { echo 'value="' . esc_attr( $ct_lotsize_to ) . '"'; } ?> />
				            </div>
				        <?php
				        break;

				        // Lot Size Slider            
				        case 'header_lotsize_from_to_slider' : ?>
				            <div id="lotsize-from-to-slider" class="col span_3">
				            	<div class="col span_12 first">
					            	<?php _e('Lot Size', 'contempo'); ?>
					            	<span class="slider-label"><?php _e('Lot size range:', 'contempo'); ?></span>
					            	<span class="min-range"><?php if(!empty($ct_adv_search_lot_size_slider_min_value)) { echo esc_html( $ct_adv_search_lot_size_slider_min_value ); } else { echo '0'; } ?></span>
					            	<span class="slider-label"><?php _e('to', 'contempo'); ?></span>
								    <span class="max-range"><?php if(!empty($ct_adv_search_lot_size_slider_max_value)) { echo esc_html( $ct_adv_search_lot_size_slider_max_value ); } else { echo '100'; } ?></span>
					            </div>
				            	<div class="slider-range-wrap col span_12 first">
								    <div id="slider-range-three"></div>
								</div>
				                <input type="hidden" id="ct_lotsize_from" class="number" name="ct_lotsize_from" size="8" <?php if($ct_lotsize_from != '') { echo 'value="' . esc_attr( $ct_lotsize_from ) . '"'; } ?> />
				                <input type="hidden" id="ct_lotsize_to" class="number" name="ct_lotsize_to" size="8" <?php if($ct_lotsize_to != '') { echo 'value="' . esc_attr( $ct_lotsize_to ) . '"'; } ?> />
				            </div>
				        <?php
				        break;

				        // Year From            
				        case 'header_year_from' : ?>
				            <div id="ct_year_from" class="col span_2">
				                <label for="ct_year_from"><?php _e('Year From', 'contempo'); ?></label>
				                <?php echo '<select name="ct_year_from">';
				                	echo '<option value="">' . __('Year Min', 'contempo') . '</option>';
									foreach ($ct_years as $year) {
										if ( isset( $_GET['ct_year_from'] ) && $_GET['ct_year_from'] == $year ) { 
											$selected = 'selected=selected '; 
										} else { 
											$selected = ''; 
										}
										echo '<option '.esc_html($selected).' value="' . $year . '">' . esc_html( $year ) . '</option>';
									}
								echo '</select>'; ?>
				            </div>
				        <?php
				        break;
				        
				        // Year To            
				        case 'header_year_to' : ?>
				            <div id="ct_year_to" class="col span_2">
				                <label for="ct_year_to"><?php _e('Year To', 'contempo'); ?></label>
				                <?php echo '<select name="ct_year_to">';
				                	echo '<option value="">' . __('Year Max', 'contempo') . '</option>';
									foreach ($ct_years as $year) {
										if ( isset( $_GET['ct_year_to'] ) && $_GET['ct_year_to'] == $year ) { 
											$selected = 'selected=selected '; 
										} else { 
											$selected = ''; 
										}
										echo '<option '.esc_html($selected).' value="' . esc_attr( $year ) . '">' . esc_html( $year ) . '</option>';
									}
								echo '</select>'; ?>
				            </div>
				        <?php
				        break;
						
						// MLS            
				        case 'header_mls' : ?>
				            <div class="col span_2">
				                <?php if(class_exists('IDX')) { ?>
				                    <label for="ct_mls"><?php _e('MLS ID', 'contempo'); ?></label>
				                    <input type="text" id="ct_mls" name="ct_mls" size="12" placeholder="<?php esc_html_e('MLS ID', 'contempo'); ?>" <?php if($ct_mls != '') { echo 'value="' . $ct_mls . '"'; } ?> />
				                <?php } else { ?>
				                    <label for="ct_mls"><?php _e('Property ID', 'contempo'); ?></label>
				                    <input type="text" id="ct_mls" name="ct_mls" size="12" placeholder="<?php esc_html_e('Property ID', 'contempo'); ?>" <?php if($ct_mls != '') { echo 'value="' . $ct_mls . '"'; } ?> />
				                <?php } ?>
				            </div>
				        <?php
						break;

				        // Number of Guests            
				        case 'header_numguests' : ?>
				            <div class="col span_2">
				                <label for="ct_rental_guests"><?php _e('Number of Guests', 'contempo'); ?></label>
				                <input type="text" id="ct_rental_guests" name="ct_rental_guests" size="12" placeholder="<?php esc_html_e('Number of Guests', 'contempo'); ?>" <?php if($ct_rental_guests != '') { echo 'value="' . $ct_rental_guests . '"'; } ?> />
				            </div>
				        <?php
				        break;

				        // Keyword           
				        case 'header_keyword' : ?>
				            <div id="suggested-search" class="col span_3">
				            	<div id="keyword-wrap">					
				            		<span id="search-icon"><?php echo ct_search_svg_muted(); ?></span>
					                <label for="ct_keyword"><?php _e('Street, City, State, or Zip', 'contempo'); ?></label>
					                <input type="text" id="ct_keyword" class="number header_keyword_search" name="ct_keyword" size="8" placeholder="<?php esc_html_e('Street, City, State, or Zip', 'contempo'); ?>" <?php if($ct_keyword != '') { echo 'value="' . ucfirst($ct_keyword) . '"'; } ?> autocomplete="off" />
				                </div>
								<div class="listing-search" style="display: none"><span id="listing-search-loader"><i class="fas fa-circle-notch fa-spin fa-fw"></i></span><?php _e('Searching...', 'contempo'); ?></div>
								<div class="listing-search-suggestion-box" id="suggestion-box" style="display: none;"></div>
				            </div>
				        <?php
				        break;
				        }
				    
				    } ?>

				    <?php
				        if(class_exists('SitePress')) {

				            $lang =  ICL_LANGUAGE_CODE;

				            //echo '<input type="hidden" name="lang" value="' . $lang . '" />';
				        }
				    ?>

				    <input type="hidden" name="search-listings" value="true" />


		            <div id="header-search-submit-more" class="col span_3">
						<input id="submit" class="btn col span_8 first" type="submit" value="<?php esc_html_e('Search', 'contempo'); ?>" />
				        <span id="more-search-options-toggle" class="btn col span_4"><?php esc_html_e('More', 'contempo'); ?></span>
				    </div>

			            <div class="clear"></div>

				<?php } ?>

		        <div id="more-search-options" class="<?php if(in_array('Type (multi)', $ct_home_adv_search_fields) || in_array('Status (multi)', $ct_home_adv_search_fields) || in_array('Popular Features', $ct_home_adv_search_fields) || in_array('Beds +', $ct_home_adv_search_fields) || in_array('Baths +', $ct_home_adv_search_fields) || in_array('Additional Features', $ct_home_adv_search_fields) || in_array('Popular Features', $ct_home_adv_search_fields)) { echo 'adv-search-more-two'; } else { echo esc_attr($ct_adv_search_more_layout); } ?>">

			        <?php
					
				    if ($ct_home_adv_search_fields) :
				    
				    foreach ($ct_home_adv_search_fields as $field=>$value) {
				    
				        switch($field) {
							
						// Type            
				        case 'type' :
				        	if(!in_array('Type (multi)', $ct_home_adv_search_fields) || !in_array('Type', $ct_header_adv_search_fields)) { ?>
					            <div id="property_type" class="col span_3">
					                <label for="ct_type"><?php _e('Type', 'contempo'); ?></label>
					                <?php ct_search_form_select('property_type'); ?>
					            </div>
				            <?php }
						break;

						// Type Multi        
				        case 'type_multi' :
				        	if(!in_array('Type', $ct_home_adv_search_fields) || !in_array('Type', $ct_header_adv_search_fields)) { ?>
					            <div id="property_type" class="col span_3">
					                <label for="ct_type"><?php _e('Type', 'contempo'); ?></label>
					                <?php ct_search_form_checkboxes_toggles_property_type('property_type'); ?>
					            </div>
				            <?php }
						break;
						
						// City
						case 'city' :
							if(!in_array('City', $ct_header_adv_search_fields)) { ?>
								<div id="city_code" class="col span_3">
									<label for="ct_city"><?php _e('City', 'contempo'); ?></label>
									<?php ct_search_form_select('city'); ?>
								</div>
							<?php }
						break;
						
				        // State            
				        case 'state' :
				        	if(!in_array('State', $ct_header_adv_search_fields)) { ?>
					            <div id="state_code" class="col span_3">
									<?php ct_search_form_select('state'); ?>
					            </div>
					        <?php }
					    break;
					
						// Zipcode            
				        case 'zipcode' :
				        	if(!in_array('Zipcode', $ct_header_adv_search_fields)) { ?>
					            <div id="zip_code" class="col span_3">
									<?php ct_search_form_select('zipcode'); ?>
					            </div>
					        <?php }
						break;

						// County            
				        case 'county' :
				        	//if(!in_array('County', $ct_header_adv_search_fields)) { ?>
					            <div id="county" class="col span_3">
					                <label for="ct_country"><?php _e('County', 'contempo'); ?></label>
					                <?php ct_search_form_select('county'); ?>
					            </div>
					        <?php //}
				        break;

				        // Country            
				        case 'country' :
				        	if(!in_array('Country', $ct_header_adv_search_fields)) { ?>
					            <div id="country_code" class="col span_3">
					                <label for="ct_country"><?php _e('Country', 'contempo'); ?></label>
					                <?php ct_search_form_select('country'); ?>
					            </div>
					        <?php }
				        break;
						
						// Beds            
				        case 'beds' :
				        	if(!in_array('Beds', $ct_header_adv_search_fields)) { ?>
					            <div id="beds" class="col span_3">
					                <label for="ct_beds"><?php _e('Beds', 'contempo'); ?></label>
									<?php ct_search_form_select('beds'); ?>
					            </div>
					        <?php }
						break;
						
						// Baths            
				        case 'baths' :
				        	if(!in_array('Baths', $ct_header_adv_search_fields)) { ?>
					            <div id="baths" class="col span_3">
					                <label for="ct_baths"><?php _e('Baths', 'contempo'); ?></label>
									<?php ct_search_form_select('baths'); ?>
					            </div>
					        <?php }
						break;

						// Beds Plus            
				        case 'beds_plus' : ?>
				            <div class="col span_3">
				            	<p class="ct-radio-group-label">
					            	<?php if($ct_bed_beds_or_bedrooms == 'rooms') {
						    			_e('Rooms', 'contempo');
						    		} elseif($ct_bed_beds_or_bedrooms == 'bedrooms') {
						    			_e('Bedrooms', 'contempo');
						    		} elseif($ct_bed_beds_or_bedrooms == 'beds') {
						    			_e('Beds', 'contempo');
							    	} else {
							    		_e('Bed', 'contempo');
							    	} ?>
						    	</p>
						    	<div class="ct-radio-group-wrap">
						            <input type="radio" class="ct-radio-group" name="ct_beds_plus" value="" id="ct-beds-plus-radio-button-1" checked />
						            <label for="ct-beds-plus-radio-button-1"><?php _e('Any', 'contempo'); ?></label>

						            <input type="radio" class="ct-radio-group" name="ct_beds_plus" value="1" id="ct-beds-plus-radio-button-2" <?php if($ct_beds_plus == 1) { echo 'checked'; } ?> />
						            <label for="ct-beds-plus-radio-button-2"><?php _e('1+', 'contempo'); ?></label>

						            <input type="radio" class="ct-radio-group" name="ct_beds_plus" value="2" id="ct-beds-plus-radio-button-3" <?php if($ct_beds_plus == 2) { echo 'checked'; } ?> />
						            <label for="ct-beds-plus-radio-button-3"><?php _e('2+', 'contempo'); ?></label>

						            <input type="radio" class="ct-radio-group" name="ct_beds_plus" value="3" id="ct-beds-plus-radio-button-4" <?php if($ct_beds_plus == 3) { echo 'checked'; } ?> />
						            <label for="ct-beds-plus-radio-button-4"><?php _e('3+', 'contempo'); ?></label>

						            <input type="radio" class="ct-radio-group" name="ct_beds_plus" value="4" id="ct-beds-plus-radio-button-5" <?php if($ct_beds_plus == 4) { echo 'checked'; } ?> />
						            <label for="ct-beds-plus-radio-button-5"><?php _e('4+', 'contempo'); ?></label>

						            <input type="radio" class="ct-radio-group" name="ct_beds_plus" value="5" id="ct-beds-plus-radio-button-6" <?php if($ct_beds_plus == 5) { echo 'checked'; } ?> />
						            <label for="ct-beds-plus-radio-button-6"><?php _e('5+', 'contempo'); ?></label>
						        </div>
				            </div>
				        <?php
						break;
						
						// Baths Plus           
				        case 'baths_plus' : ?>
				            <div class="col span_3">
				            	<p class="ct-radio-group-label">
					            	<?php if($ct_bath_baths_or_bathrooms == 'bathrooms') {
						    			_e('Bathrooms', 'contempo');
						    		} elseif($ct_bath_baths_or_bathrooms == 'baths') {
						    			_e('Baths', 'contempo');
						    		} else {
							    		_e('Bath', 'contempo');
							    	} ?>
						    	</p>
						    	<div class="ct-radio-group-wrap">
						            <input type="radio" class="ct-radio-group" name="ct_baths_plus" value="" id="ct-baths-plus-radio-button-1" checked />
						            <label for="ct-baths-plus-radio-button-1"><?php _e('Any', 'contempo'); ?></label>

						            <input type="radio" class="ct-radio-group" name="ct_baths_plus" value="1" id="ct-baths-plus-radio-button-2" <?php if($ct_baths_plus == 1) { echo 'checked'; } ?> />
						            <label for="ct-baths-plus-radio-button-2"><?php _e('1+', 'contempo'); ?></label>

						            <input type="radio" class="ct-radio-group" name="ct_baths_plus" value="2" id="ct-baths-plus-radio-button-3" <?php if($ct_baths_plus == 2) { echo 'checked'; } ?> />
						            <label for="ct-baths-plus-radio-button-3"><?php _e('2+', 'contempo'); ?></label>

						            <input type="radio" class="ct-radio-group" name="ct_baths_plus" value="3" id="ct-baths-plus-radio-button-4" <?php if($ct_baths_plus == 3) { echo 'checked'; } ?> />
						            <label for="ct-baths-plus-radio-button-4"><?php _e('3+', 'contempo'); ?></label>

						            <input type="radio" class="ct-radio-group" name="ct_baths_plus" value="4" id="ct-baths-plus-radio-button-5" <?php if($ct_baths_plus == 4) { echo 'checked'; } ?> />
						            <label for="ct-baths-plus-radio-button-5"><?php _e('4+', 'contempo'); ?></label>

						            <input type="radio" class="ct-radio-group" name="ct_baths_plus" value="5" id="ct-baths-plus-radio-button-6" <?php if($ct_baths_plus == 5) { echo 'checked'; } ?> />
						            <label for="ct-baths-plus-radio-button-6"><?php _e('5+', 'contempo'); ?></label>
						        </div>
				            </div>
				        <?php
						break;
						
						// Status            
				        case 'status' :
				        	if(!in_array('Status', $ct_header_adv_search_fields)) { ?>
					            <div id="status" class="col span_3">
					                <label for="ct_status"><?php _e('Status', 'contempo'); ?></label>
									<?php ct_search_form_select('ct_status'); ?>
					            </div>
					        <?php }
						break;

						// Status Multi        
				        case 'status_multi' :
				        	if(!in_array('Status', $ct_home_adv_search_fields) || !in_array('Status', $ct_header_adv_search_fields) || !in_array('Status (multi)', $ct_header_adv_search_fields)) { ?>
					            <div id="status" class="col span_3">
					                <label for="ct_status"><?php _e('Status', 'contempo'); ?></label>
					                <?php ct_search_form_checkboxes_toggles_status('ct_status'); ?>
					            </div>
				            <?php }
						break;

						// Brokerage            
				        case 'brokerage' :
				        	if(!in_array('Brokerage', $ct_header_adv_search_fields)) { ?>
				            <div id="ct_brokerage" class="col span_3">
				                <label for="ct_brokerage"><?php _e('Brokerage', 'contempo'); ?></label>
								<?php ct_search_form_brokerage_select('ct_brokerage'); ?>
				            </div>
				        <?php }
						break;

						// Popular Features            
				        case 'popular_features' : ?>
				            <div id="popular-features" class="col span_12 first additional-features marT10 marB10">
				                <label for="ct_popular_features"><?php _e('Popular Features', 'contempo'); ?></label>
								<?php $checked_popular_features = ct_search_form_checkboxes_popular_features('additional_features'); ?>
				            </div>
						<?php
						break;
						
						// Additional Features            
				        case 'additional_features' :
				        	if(!in_array('Additional Features', $ct_header_adv_search_fields)) {
								ct_search_form_keywords_additional_features( $checked_popular_features );
					        }
						break;

				        // Community          
				        case 'community' : ?>
				            <div id="ct_community" class="col span_3">
				                <?php
				                global $ct_options;
				                $ct_community_neighborhood_or_district = isset( $ct_options['ct_community_neighborhood_or_district'] ) ? $ct_options['ct_community_neighborhood_or_district'] : '';

				                if($ct_community_neighborhood_or_district == 'neighborhood') { ?>
				                    <label for="ct_community"><?php _e('Neighborhood', 'contempo'); ?></label>
				                <?php } elseif($ct_community_neighborhood_or_district == 'district') { ?>
				                    <label for="ct_community"><?php _e('District', 'contempo'); ?></label>
				                <?php } else { ?>
				                    <label for="ct_community"><?php _e('Community', 'contempo'); ?></label>
				                <?php } ?>
				                <?php ct_search_form_select('community'); ?>
				            </div>
				        <?php
				        break;

				        // School District for IDX
				        case 'school_district' :
				        	if(class_exists('ctIdxPro')) { ?>
					        	<div id="ct_school_district" class="col span_3">
					                <label for="ct_school_district"><?php _e('School District', 'contempo'); ?></label>
									<?php ct_search_form_school_district_select(); ?>
					            </div>
				            <?php }
				        break;
						
						// Price From            
				        case 'price_from' : ?>
				            <div class="col <?php if($ct_adv_search_more_layout == 'adv-search-more-two') { echo 'span_5'; } else { echo 'span_3'; } ?>">
				                <label for="ct_price_from"><?php _e('Price From', 'contempo'); ?> (<?php ct_currency(); ?>)</label>
				                <input type="number" id="ct_price_from" class="number" name="ct_price_from" size="8" placeholder="<?php esc_html_e('Price From', 'contempo'); ?> (<?php ct_currency(); ?>)" <?php if($ct_price_from != '') { echo 'value="' . esc_html($ct_price_from) . '"'; } ?> />
				            </div>
				            <?php if($ct_adv_search_more_layout == 'adv-search-more-two') {
				            	echo '<div class="col span_2 col-separator muted"> — </div>';
				            } ?>
				        <?php
						break;
						
						// Price To            
				        case 'price_to' : ?>
				            <div class="col <?php if($ct_adv_search_more_layout == 'adv-search-more-two') { echo 'span_5'; } else { echo 'span_3'; } ?>">
				                <label for="ct_price_to"><?php _e('Price To', 'contempo'); ?> (<?php ct_currency(); ?>)</label>
				                <input type="number" id="ct_price_to" class="number" name="ct_price_to" size="8" placeholder="<?php esc_html_e('Price To', 'contempo'); ?> (<?php ct_currency(); ?>)" <?php if($ct_price_to != '') { echo 'value="' . esc_html($ct_price_to) . '"'; } ?> />
				            </div>
				        <?php
						break;

						// Price Slider            
				        case 'price_from_to_slider' : ?>
				            <div id="price-from-to-slider" class="col span_6">
					            <div class="col span_12 first">

                                    <span class="slider-label"><?php _e('Price range:', 'contempo'); ?></span>

					            	<span class="min-range">
                                        <?php if(!is_numeric( $ct_adv_search_price_slider_min_value )) {

                                            $ct_adv_search_price_slider_min_value = '100000';

                                        } ?>
                                        <?php $requested_price_fr_options = array('options'=>array('default'=> absint($ct_adv_search_price_slider_min_value) )); ?>
                                        <?php $requested_price_fr = filter_input(INPUT_GET,'ct_price_from', FILTER_VALIDATE_INT, $requested_price_fr_options); ?>
                                        <?php if($ct_currency_placement == 'after') {
	                                        echo esc_html( number_format( floatval( $requested_price_fr ), 0 ) );
	                                        ct_currency();
	                                    } else {
	                                    	ct_currency();
	                                    	echo esc_html( number_format( floatval( $requested_price_fr ), 0 ) );
	                                    } ?>
                                    </span>

                                    <span class="slider-label"><?php _e('to', 'contempo'); ?></span>

								    <span class="max-range">
                                        <?php if(!is_numeric( $ct_adv_search_price_slider_max_value )) {

                                            $ct_adv_search_price_slider_max_value = '5000000';

                                        } ?>
                                        <?php $requested_price_to_options = array('options'=>array('default'=> absint($ct_adv_search_price_slider_max_value) )); ?>
                                        <?php $requested_price_to = filter_input(INPUT_GET,'ct_price_to', FILTER_VALIDATE_INT, $requested_price_to_options); ?>
                                        <?php if($ct_currency_placement == 'after') {
	                                        echo esc_html( number_format( floatval( $requested_price_to ), 0 ) );
	                                        ct_currency();
	                                    } else {
	                                    	ct_currency();
	                                    	echo esc_html( number_format( floatval( $requested_price_to ), 0 ) );
	                                    } ?>
                                    </span>

					            </div>
				            	<div class="slider-range-wrap col span_11 first">
								    <div id="slider-range"></div>
								</div>
				                <input type="hidden" id="ct_price_from" class="number" name="ct_price_from" size="8" <?php if($ct_price_from != '') { echo 'value="' . esc_html($ct_price_from) . '"'; } ?> />
				                <input type="hidden" id="ct_price_to" class="number" name="ct_price_to" size="8" <?php if($ct_price_to != '') { echo 'value="' . esc_html($ct_price_to) . '"'; } ?> />
				            </div>
				        <?php
						break;

				        // Sq Ft From            
				        case 'sqft_from' : ?>
				            <div class="col <?php if($ct_adv_search_more_layout == 'adv-search-more-two') { echo 'span_5'; } else { echo 'span_3'; } ?>">
				                <label for="ct_sqft_from"><?php ct_sqftsqm(); ?> <?php _e('From', 'contempo'); ?></label>
				                <input type="number" id="ct_sqft_from" class="number" name="ct_sqft_from" size="8" placeholder="<?php _e('Size From', 'contempo'); ?> -<?php ct_sqftsqm(); ?>" <?php if($ct_sqft_from != '') { echo 'value="' . $ct_sqft_from . '"'; } ?> />
				            </div>
				            <?php if($ct_adv_search_more_layout == 'adv-search-more-two') { 
				            	echo '<div class="col span_2 col-separator muted"> — </div>';
				            } ?>
				        <?php
				        break;
				        
				        // Sq Ft To            
				        case 'sqft_to' : ?>
				            <div class="col <?php if($ct_adv_search_more_layout == 'adv-search-more-two') { echo 'span_5'; } else { echo 'span_3'; } ?>">
				                <label for="ct_sqft_to"><?php ct_sqftsqm(); ?> <?php _e('To', 'contempo'); ?></label>
				                <input type="number" id="ct_sqft_to" class="number" name="ct_sqft_to" size="8" placeholder="<?php _e('Size To', 'contempo'); ?> -<?php ct_sqftsqm(); ?>" <?php if($ct_sqft_to != '') { echo 'value="' . $ct_sqft_to . '"'; } ?> />
				            </div>
				        <?php
				        break;

				        // Sq Ft Slider            
				        case 'sqft_from_to_slider' : ?>
				            <div id="size-from-to-slider" class="col span_6">
				            	<div class="col span_12 first">
						            <?php

						            $requested_ct_sqft_to_options = array('options'=>array('default'=> $ct_adv_search_size_slider_max_value ));
						            $requested_ct_sqft_to = filter_input(INPUT_GET,'ct_sqft_to', FILTER_VALIDATE_INT, $requested_ct_sqft_to_options);
						            ?>
					            	<span class="slider-label">
                                        <?php _e('Size range:', 'contempo'); ?>
                                    </span>
					            	<span class="min-range">
                                        <?php

                                        if ( ! is_numeric( $ct_adv_search_size_slider_min_value ) ) {

                                            $ct_adv_search_size_slider_min_value = 100;

                                        }

                                        $requested_ct_sqft_from_options = array( 'options' => array( 'default' => $ct_adv_search_size_slider_min_value ) );

                                        $requested_ct_sqft_from = filter_input(INPUT_GET,'ct_sqft_from', FILTER_VALIDATE_INT, $requested_ct_sqft_from_options);

                                        echo esc_html( absint( $requested_ct_sqft_from ) );

                                        ?>
                                    </span>
					            	<span class="slider-label">
                                        <?php _e('to', 'contempo'); ?>
                                    </span>
								    <span class="max-range">
                                        <?php

                                        if( ! is_numeric( $ct_adv_search_size_slider_max_value ) ) {

                                            $ct_adv_search_size_slider_max_value = 1000;

                                        }

                                        $requested_ct_sqft_to_options = array( 'options' => array( 'default' => $ct_adv_search_size_slider_max_value ) );

                                        $requested_ct_sqft_to = filter_input(INPUT_GET,'ct_sqft_to', FILTER_VALIDATE_INT, $requested_ct_sqft_to_options);

                                        echo esc_html( absint( $requested_ct_sqft_to )  );

                                        ?>
                                        </span>
					            </div>
				            	<div class="slider-range-wrap col span_11 first">
								    <div id="slider-range-two"></div>
								</div>
				                <input type="hidden" id="ct_sqft_from" class="number" name="ct_sqft_from" size="8" <?php if($ct_sqft_from != '') { echo 'value="' . $ct_sqft_from . '"'; } ?> />
				                <input type="hidden" id="ct_sqft_to" class="number" name="ct_sqft_to" size="8" <?php if($ct_sqft_to != '') { echo 'value="' . $ct_sqft_to . '"'; } ?> />
				            </div>
				        <?php
				        break;

				        // Lot Size From            
				        case 'lotsize_from' : ?>
				            <div class="col <?php if($ct_adv_search_more_layout == 'adv-search-more-two') { echo 'span_5'; } else { echo 'span_3'; } ?>">
				                <label for="ct_lotsize_from"><?php _e('Lot Size From', 'contempo'); ?> <?php ct_sqftsqm(); ?></label>
				                <input type="number" id="ct_lotsize_from" class="number" name="ct_lotsize_from" size="8" placeholder="<?php _e('Lot Size From', 'contempo'); ?> - <?php ct_acres(); ?>" <?php if($ct_lotsize_from != '') { echo 'value="' . $ct_lotsize_from . '"'; } ?> />
				            </div>
				             <?php if($ct_adv_search_more_layout == 'adv-search-more-two') { 
				            	echo '<div class="col span_2 col-separator muted"> — </div>';
				            } ?>
				        <?php
				        break;
				        
				        // Lot Size To            
				        case 'lotsize_to' : ?>
				            <div class="col <?php if($ct_adv_search_more_layout == 'adv-search-more-two') { echo 'span_5'; } else { echo 'span_3'; } ?>">
				                <label for="ct_lotsize_to"><?php _e('Lot Size To', 'contempo'); ?> <?php ct_sqftsqm(); ?></label>
				                <input type="number" id="ct_lotsize_to" class="number" name="ct_lotsize_to" size="8" placeholder="<?php _e('Lot Size To', 'contempo'); ?> - <?php ct_acres(); ?>" <?php if($ct_lotsize_to != '') { echo 'value="' . $ct_lotsize_to . '"'; } ?> />
				            </div>
				        <?php
				        break;

				        // Lot Size Slider            
				        case 'lotsize_from_to_slider' : ?>
					        <?php

					            $requested_lot_size_to_options = array('options'=>array('default'=> $ct_adv_search_lot_size_slider_max_value ));
					            $requested_lot_size_to = filter_input(INPUT_GET,'ct_lotsize_to', FILTER_VALIDATE_INT, $requested_lot_size_to_options);
					        ?>
				            <div id="lotsize-from-to-slider" class="col span_6">
				            	<div class="col span_12 first">
					            	<span class="slider-label"><?php _e('Lot size range:', 'contempo'); ?></span>
					            	<span class="min-range">
                                        <?php
                                            if ( ! is_numeric( $ct_adv_search_lot_size_slider_min_value ) ) {
                                                $ct_adv_search_lot_size_slider_min_value = 0;
                                            }

                                            $requested_lot_size_from_options = array('options'=>array('default'=> $ct_adv_search_lot_size_slider_min_value ));
                                            $requested_lot_size_from = filter_input(INPUT_GET,'ct_lotsize_from', FILTER_VALIDATE_INT, $requested_lot_size_from_options);

                                            echo esc_html( absint($requested_lot_size_from) );

                                        ?>
                                    </span>

                                    <span class="slider-label">
                                        <?php _e('to', 'contempo'); ?>
                                    </span>

								    <span class="max-range">
                                        <?php
                                            if ( ! is_numeric( $ct_adv_search_lot_size_slider_max_value ) ) {
                                                $ct_adv_search_lot_size_slider_max_value = 100;
                                            }

                                            $requested_lot_size_to_options = array( 'options' => array( 'default' => absint( $ct_adv_search_lot_size_slider_max_value ) ));

                                            $requested_lot_size_to = filter_input(INPUT_GET,'ct_lotsize_to', FILTER_VALIDATE_INT, $requested_lot_size_to_options);

                                            echo esc_html( absint( $requested_lot_size_to ) );
                                        ?>
                                    </span>
					            </div>
				            	<div class="slider-range-wrap col span_11 first">
								    <div id="slider-range-three"></div>
								</div>
				                <input type="hidden" id="ct_lotsize_from" class="number" name="ct_lotsize_from" size="8" <?php if($ct_lotsize_from != '') { echo 'value="' . $ct_lotsize_from . '"'; } ?> />
				                <input type="hidden" id="ct_lotsize_to" class="number" name="ct_lotsize_to" size="8" <?php if($ct_lotsize_to != '') { echo 'value="' . $ct_lotsize_to . '"'; } ?> />
				            </div>
				        <?php
				        break;

				        // Year From            
				        case 'year_from' :
				        	if(!in_array('Year From', $ct_header_adv_search_fields)) { ?>
				            <div id="ct_year_from" class="col <?php if($ct_adv_search_more_layout == 'adv-search-more-two') { echo 'span_5'; } else { echo 'span_3'; } ?>">
				                <label for="ct_year_from"><?php _e('Year Min', 'contempo'); ?></label>
				                <?php echo '<select name="ct_year_from">';
				                	echo '<option value="">' . __('Year Min', 'contempo') . '</option>';
									foreach ($ct_years as $year) {
										if ( isset( $_GET['ct_year_from'] ) && $_GET['ct_year_from'] == $year ) { 
											$selected = 'selected=selected '; 
										} else { 
											$selected = ''; 
										}
										echo '<option '.esc_html($selected).' value="' . $year . '">' . $year . '</option>';
									}
								echo '</select>'; ?>
				            </div>
				            <?php if($ct_adv_search_more_layout == 'adv-search-more-two') { 
				            	echo '<div class="col span_2 col-separator muted"> — </div>';
				            } ?>
					        <?php }
				        break;
				        
				        // Year To            
				        case 'year_to' :
				        	if(!in_array('Year To', $ct_header_adv_search_fields)) { ?>
				            <div id="ct_year_to" class="col <?php if($ct_adv_search_more_layout == 'adv-search-more-two') { echo 'span_5'; } else { echo 'span_3'; } ?>">
				                <label for="ct_year_to"><?php _e('Year To', 'contempo'); ?></label>
				                <?php echo '<select name="ct_year_to">';
				                	echo '<option value="">' . __('Year Max', 'contempo') . '</option>';
									foreach ($ct_years as $year) {
										if ( isset( $_GET['ct_year_to'] ) && $_GET['ct_year_to'] == $year ) { 
											$selected = 'selected=selected '; 
										} else { 
											$selected = ''; 
										}
										echo '<option '.esc_html($selected).' value="' . $year . '">' . $year . '</option>';
									}
								echo '</select>'; ?>
				            </div>
					        <?php }
				        break;
						
						// MLS            
				        case 'mls' : ?>
				            <div class="col span_3">
				                <?php if(class_exists('IDX')) { ?>
				                    <label for="ct_mls"><?php _e('MLS ID', 'contempo'); ?></label>
				                    <input type="text" id="ct_mls" name="ct_mls" size="12" placeholder="<?php esc_html_e('MLS ID', 'contempo'); ?>" <?php if($ct_mls != '') { echo 'value="' . $ct_mls . '"'; } ?> />
				                <?php } else { ?>
				                    <label for="ct_mls"><?php _e('Property ID', 'contempo'); ?></label>
				                    <input type="text" id="ct_mls" name="ct_mls" size="12" placeholder="<?php esc_html_e('Property ID', 'contempo'); ?>" <?php if($ct_mls != '') { echo 'value="' . $ct_mls . '"'; } ?> />
				                <?php } ?>
				            </div>
				        <?php
						break;

				        // Number of Guests            
				        case 'numguests' : ?>
				            <div class="col span_3">
				                <label for="ct_rental_guests"><?php _e('Number of Guests', 'contempo'); ?></label>
				                <input type="text" id="ct_rental_guests" name="ct_rental_guests" size="12" placeholder="<?php esc_html_e('Number of Guests', 'contempo'); ?>" <?php if($ct_rental_guests != '') { echo 'value="' . $ct_rental_guests . '"'; } ?> />
				            </div>
				        <?php
				        break;

				        }
				    
				    } endif; ?>

				         <div class="clear"></div>

				        <button type="reset" id="ct-reset" class="btn btn-delete"><?php _e('Reset All Filters', 'contempo'); ?></button>

				</div>

				<input type="hidden" name="lat" id="search-latitude">
				<input type="hidden" name="lng" id="search-longitude">
			
			</div>

        </form>
	        <div class="clear"></div>
    </div>
</div>
<!-- //Header Search -->

<?php if($ct_keyword_suggested_results != 'no') { ?>
<script>
jQuery(".header_keyword_search").keyup(function($){
	var keyword_value = jQuery(this).val();
	
	var data = {
		action: 'street_keyword_search',
		keyword_value: keyword_value,
		nonce: "<?php echo wp_create_nonce('street_keyword_search'); ?>",
	};

	jQuery(".listing-search").show();

	jQuery.ajax({
		type: "POST",
		url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",		
		data: data,	
		success: function(data){
			//console.log(data);
			jQuery(".listing-search").hide();
			jQuery(".listing-search-suggestion-box").show();
			jQuery(".listing-search-suggestion-box").html(data);
		}
	}); 
});

jQuery(document).on("click",'.listing_media',function(){	
	var list_title = jQuery(this).attr('att_id');
	jQuery(".header_keyword_search").val(list_title);
	jQuery("#suggestion-box").hide();
	
});
</script>
<?php } ?>
