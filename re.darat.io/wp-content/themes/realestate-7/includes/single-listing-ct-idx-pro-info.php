<?php
/**
 * Single Listing CT IDX Pro Info
 *
 * @package WP Pro Real Estate 7
 * @subpackage Include
 */

global $ct_options;

$ct_single_listing_content_layout_type = isset( $ct_options['ct_single_listing_content_layout_type'] ) ? $ct_options['ct_single_listing_content_layout_type'] : '';
$ct_single_listing_idx_layout = isset( $ct_options['ct_single_listing_idx_layout']['enabled'] ) ? $ct_options['ct_single_listing_idx_layout']['enabled'] : '';
$ct_listings_lotsize_format = isset( $ct_options['ct_listings_lotsize_format'] ) ? esc_html( $ct_options['ct_listings_lotsize_format'] ) : '';

$ct_source = get_post_meta($post->ID, 'source', true);
$ct_idx_rupid = get_post_meta($post->ID, '_ct_idx_rupid', true);

$ct_status = get_post_meta($post->ID, '_ct_status', true);
$ct_sold_date = get_post_meta($post->ID, '_ct_sold_date', true);
$ct_neighborhood = get_post_meta($post->ID, '_ct_idx_overview_neighborhood', true);
$ct_subdivision = get_post_meta($post->ID, '_ct_idx_overview_subdivision', true);
$ct_style = get_post_meta($post->ID, '_ct_idx_overview_style', true);
$ct_year_built = get_post_meta($post->ID, '_ct_idx_overview_year_built', true);
$ct_fencing = get_post_meta($post->ID, '_ct_idx_overview_fencing', true);
$ct_topography = get_post_meta($post->ID, '_ct_idx_overview_topography', true);
$ct_lot_sqft = get_post_meta($post->ID, '_ct_idx_overview_lot_square_feet', true);
$ct_lot_acres = get_post_meta($post->ID, '_ct_idx_overview_acres', true);
$ct_exterior = get_post_meta($post->ID, '_ct_idx_overview_exterior', true);
$ct_roofing = get_post_meta($post->ID, '_ct_idx_overview_roofing', true);
$ct_structures = get_post_meta($post->ID, '_ct_idx_overview_structures', true);
$ct_parking = get_post_meta($post->ID, '_ct_idx_overview_parking_garage', true);
$ct_garage_description = get_post_meta($post->ID, '_ct_idx_overview_garage_description', true);
$ct_parking_spaces = get_post_meta($post->ID, '_ct_idx_overview_parking_garage_spaces', true);
$ct_garage_stalls = get_post_meta($post->ID, '_ct_idx_overview_garage_type_stalls', true);
$ct_idx_overview_utilities_available = get_post_meta($post->ID, '_ct_idx_overview_utilities_available', true);
$ct_heating = get_post_meta($post->ID, '_ct_idx_overview_heating', true);
$ct_heat_type = get_post_meta($post->ID, '_ct_idx_overview_heat_type', true);

$ct_heating_primary = get_post_meta($post->ID, '_ct_idx_overview_primary_heat', true);
$ct_heat_source = get_post_meta($post->ID, '_ct_idx_overview_heat_source', true);

$ct_cooling = get_post_meta($post->ID, '_ct_idx_overview_cooling', true);
$ct_air_conditioning = get_post_meta($post->ID, '_ct_idx_overview_air_conditioning', true);

$ct_water = get_post_meta($post->ID, '_ct_idx_overview_water', true);
$ct_sewer = get_post_meta($post->ID, '_ct_idx_overview_sewer', true);
$ct_sewer_septic = get_post_meta($post->ID, '_ct_idx_overview_sewer_septic', true);

$ct_exterior_features = get_post_meta($post->ID, '_ct_idx_features_exterior_features', true);
$ct_interior_features = get_post_meta($post->ID, '_ct_idx_features_interior_features', true);
$ct_idx_features_miscellaneous_features = get_post_meta($post->ID, '_ct_idx_features_miscellaneous_features', true);
$ct_equipment = get_post_meta($post->ID, '_ct_idx_features_equipment', true);
$ct_fireplace = get_post_meta($post->ID, '_ct_idx_features_fireplace', true);
$ct_fireplace_type = get_post_meta($post->ID, '_ct_idx_features_fireplace_type', true);
$ct_num_of_fireplaces = get_post_meta($post->ID, '_ct_idx_features_number_of_fireplaces', true);
$ct_pool = get_post_meta($post->ID, '_ct_idx_features_pool', true);
$ct_view = get_post_meta($post->ID, '_ct_idx_features_view', true);
$ct_security = get_post_meta($post->ID, '_ct_security', true);
$ct_floor_coverings = get_post_meta($post->ID, '_ct_floor_coverings', true);

$ct_idx_room_info_bedrooms = get_post_meta($post->ID, '_ct_idx_room_info_bedrooms', true);
$ct_idx_room_info_total_full_baths = get_post_meta($post->ID, '_ct_idx_room_info_total_full_baths', true);
$ct_idx_room_info_total_3_4_baths = get_post_meta($post->ID, '_ct_idx_room_info_total_3_4_baths', true);
$ct_idx_room_info_total_1_2_baths = get_post_meta($post->ID, '_ct_idx_room_info_total_1_2_baths', true);
$ct_idx_room_info_total_1_4_baths = get_post_meta($post->ID, '_ct_idx_room_info_total_1_4_baths', true);
$ct_idx_room_info_basement_type = get_post_meta($post->ID, '_ct_idx_room_info_basement_type', true);

$ct_idx_schools_school_district = get_post_meta($post->ID, '_ct_idx_schools_school_district', true);
$ct_idx_schools_elementary_school = get_post_meta($post->ID, '_ct_idx_schools_elementary_school', true);
$ct_idx_schools_middle_school = get_post_meta($post->ID, '_ct_idx_schools_middle_school', true);
$ct_idx_schools_high_school = get_post_meta($post->ID, '_ct_idx_schools_high_school', true);

$ct_hoa_fee = get_post_meta($post->ID, '_ct_idx_financial_hoa_fee', true);
$ct_hoa_fee_due = get_post_meta($post->ID, '_ct_idx_financial_hoa_fee_due', true);
$ct_hoa_includes = get_post_meta($post->ID, '_ct_hoa_includes', true);
$ct_idx_features_hoa_fee_includes = get_post_meta($post->ID, '_ct_idx_features_hoa_fee_includes', true);


if(!function_exists('ct_idx_info')) {
    function ct_idx_info($field) {
        global $post;

        $ct_field = get_post_meta($post->ID, $field, true);

        if( is_array($ct_field) ) {
            $ct_field = implode(', ', array_map('ucwords', $ct_field));
            echo preg_replace('/(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', $ct_field);
        } else {
            $ct_field = ucwords(esc_html($ct_field));
            echo preg_replace('/(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', $ct_field);
        }
    }
}

do_action('before_single_listing_ct_idx_pro_info');

if($ct_source == 'idx-api') {

    echo '<!-- Listing IDX Info -->';
    echo '<div id="listing-idx-info">';

    // New IDX API
    if( !empty($ct_idx_rupid) ) {

        if( !empty($ct_single_listing_idx_layout) ) {

            foreach ($ct_single_listing_idx_layout as $key => $value) {
            
                switch($key) {

                    // Features
                    case 'listing_idx_overview' :   

                        $grouped_features = get_post_meta( $post->ID, '_ct_grouped_features', true );

                        $output = '';

                        if( '' === $grouped_features ){
                            return $output;
                        }

                        $grouped_features = apply_filters( 'idx_get_grouped_features', json_decode( $grouped_features, true ), $grouped_features );
                        
                        if( is_array( $grouped_features ) && ! empty($grouped_features) ){
                            foreach( $grouped_features as $feature_group => $features_data ){
                                
                                if($ct_single_listing_content_layout_type == 'accordion') {
                                    $output .= '<h4 id="idx-'. strtolower( $feature_group ) .'" class="info-toggle border-bottom marB20">'. esc_html( $feature_group ) .'</h4>';
                                } else {
                                    $output .= '<h4 id="idx-'. strtolower( $feature_group ) .'" class="border-bottom marB20">'. esc_html( $feature_group ) .'</h4>';
                                }

                                $output .= '<div class="info-inner">';
                                    $output .= '<ul class="propinfo idx-info">';
                                        foreach( $features_data as $feature_section => $features ){
                                            $feature_parts = explode(',', $features );
                                            $feature_parts = array_map('trim', $feature_parts);

                                            if( strtolower($feature_group) == 'location' && strtolower($feature_section) == 'hoa amenities' ){
                                                $hoa_lookups = [ 'monthly', 'quarterly', 'yearly', 'annual', 'weekly' ];

                                                foreach( $hoa_lookups as $lookup_name ){
                                                    $hoa_index = array_search($lookup_name, array_map( 'strtolower', $feature_parts ) );
                                                    if( false !== $hoa_index ){
                                                        $previous_index = $hoa_index - 1;
                                                        $formatted_str = $lookup_name;

                                                        if( isset($feature_parts[$previous_index]) && is_numeric($feature_parts[$previous_index]) ) {
                                                            $formatted_str = '$' . number_format($feature_parts[$previous_index], 0) . ' ' . ucfirst($formatted_str);
                                                            $feature_parts[$previous_index] = $formatted_str;
                                                            unset($feature_parts[$hoa_index]);
                                                        }
                                                    }
                                                }
                                            }

                                            if( $feature_section === array_key_first($features_data) && strtolower($feature_group) == 'location') {
                                                if(strtolower($feature_section) == 'hoa amenities' ){
                                                    $output .= '<li class="row"><span class="muted left">'. __( 'HOA', 'contempo' ) .'</span><span class="right">'. __('Yes', 'contempo') .'</span></li>';
                                                }
                                            }

                                            if( $feature_section === array_key_first($features_data) && strtolower($feature_group) == 'lot') {
                                                if(!empty($ct_lotsize)) {
                                                    $output .= '<li class="row"><span class="muted left">'. __( 'Lot Size', 'contempo' ) .'</span><span class="right">'. $ct_lotsize . ' ' . __('Acres', 'contempo') . '</span></li>';
                                                }
                                            }

                                            $feature_parts = preg_replace('/(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', $feature_parts);

                                            $output .= '<li class="row"><span class="muted left">'. esc_html( $feature_section ) .'</span><span class="right">' . implode(', ', $feature_parts) . '</span></li>';

                                            if( $feature_section === array_key_last($features_data) && strtolower($feature_group) == 'interior') {
                                                if(!empty($ct_idx_total_full_baths)) {
                                                    $output .= '<li class="row"><span class="muted left">'. __( 'Full Baths', 'contempo' ) .'</span><span class="right">'. $ct_idx_total_full_baths .'</span></li>';
                                                }
                                                if(!empty($ct_idx_room_info_total_1_2_baths)) {
                                                    $output .= '<li class="row"><span class="muted left">'. __( 'Half Baths', 'contempo' ) .'</span><span class="right">'. $ct_idx_room_info_total_1_2_baths .'</span></li>';
                                                }
                                                if(!empty($ct_idx_room_info_total_1_4_baths)) {
                                                    $output .= '<li class="row"><span class="muted left">'. __( 'Quarter Baths', 'contempo' ) .'</span><span class="right">'. $ct_idx_room_info_total_1_4_baths .'</span></li>';
                                                }
                                            }

                                            if( $feature_section === array_key_last($features_data) && strtolower($feature_group) == 'exterior') {
                                                if(!empty($ct_idx_overview_parking_garage)) {
                                                    $output .= '<li class="row"><span class="muted left">'. __( 'Attached Garage', 'contempo' ) .'</span><span class="right">'. __('Yes', 'contempo') .'</span></li>';
                                                }
                                                if(!empty($ct_idx_overview_parking_garage_spaces)) {
                                                    $output .= '<li class="row"><span class="muted left">'. __( 'Parking Spaces', 'contempo' ) .'</span><span class="right">'. $ct_idx_overview_parking_garage_spaces .'</span></li>';
                                                }
                                            }

                                        }
                                    $output .= '</ul>';
                                $output .= '</div>';
                            }
                        }

                        echo ct_sanitize_output($output);
                
                    break;

                    // Schools
                    case 'listing_idx_schools' :   

                        if(!empty($ct_idx_schools_school_district) || !empty($ct_idx_schools_elementary_school) || !empty($ct_idx_schools_middle_school) || !empty($ct_idx_schools_high_school)) { 
                            echo '<!-- Listing IDX Schools -->';
                            if($ct_single_listing_content_layout_type == 'accordion') {
                                echo '<h4 id="idx-schools" class="info-toggle border-bottom marB20">' . __('Schools', 'contempo') . '</h4>';
                            } else {
                                echo '<h4 id="idx-schools" class="border-bottom marB20">' . __('Schools', 'contempo') . '</h4>';
                            }
                            echo '<div class="info-inner">';
                                echo '<ul class="propinfo idx-info">';
                                    
                                    if(!empty($ct_idx_schools_school_district)) {
                                        echo '<li class="row district">';
                                            echo '<span class="muted left">';
                                                _e('District', 'contempo');
                                            echo '</span>';
                                            echo '<span class="right">';
                                                 echo esc_html($ct_idx_schools_school_district);
                                            echo '</span>';
                                        echo '</li>';
                                    }

                                    if(!empty($ct_idx_schools_elementary_school)) {
                                        echo '<li class="row elementary-school">';
                                            echo '<span class="muted left">';
                                                _e('Elementary', 'contempo');
                                            echo '</span>';
                                            echo '<span class="right">';
                                                 echo esc_html($ct_idx_schools_elementary_school);
                                            echo '</span>';
                                        echo '</li>';
                                    }

                                    if(!empty($ct_idx_schools_middle_school)) {
                                        echo '<li class="row middle-school">';
                                            echo '<span class="muted left">';
                                                _e('Middle', 'contempo');
                                            echo '</span>';
                                            echo '<span class="right">';
                                                 echo esc_html($ct_idx_schools_middle_school);
                                            echo '</span>';
                                        echo '</li>';
                                    }

                                    if(!empty($ct_idx_schools_high_school)) {
                                        echo '<li class="row high-school">';
                                            echo '<span class="muted left">';
                                                _e('High', 'contempo');
                                            echo '</span>';
                                            echo '<span class="right">';
                                                 echo esc_html($ct_idx_schools_high_school);
                                            echo '</span>';
                                        echo '</li>';
                                    }

                                echo '</ul>';
                                    echo '<div class="clear"></div>';
                            echo '</div>';
                            echo '<!-- //Listing IDX Schools -->';
                        }
                
                    break;
                
                }

            }

        }

    }

    echo '</div>';
        
}

?>