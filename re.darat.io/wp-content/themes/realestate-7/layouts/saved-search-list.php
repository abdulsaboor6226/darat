<?php
/**
 * Saved Search List
 *
 * @package    WP Pro Real Estate 7
 * @subpackage Template
 */

global $ct_search_data, $ct_options, $wpdb, $current_user, $wp_query;

wp_get_current_user();

$is_crm_user = false;

if ( function_exists( 'ct_is_crm_user' ) ) {
	$is_crm_user = ct_is_crm_user();
}

$results = $wpdb->get_results(
	$wpdb->prepare(
		"SELECT * FROM {$wpdb->prefix}ct_search WHERE auther_id = %d ORDER BY time DESC",
		absint( $current_user->ID )
	),
	OBJECT
); // db call ok; no-cache ok

$ct_currency = isset( $ct_options['ct_currency'] ) ? esc_attr( $ct_options['ct_currency'] ) : '';

$ct_currency_placement = isset( $ct_options['ct_currency_placement'] ) ? esc_attr( $ct_options['ct_currency_placement'] ) : '';

if(!empty( $results )) {

	foreach($results as $ct_search_data) {

		// Initialize empty attributes
		$attributes = array();

		$ct_wp_date_format = get_option( 'date_format' );

		$format              = 'Y-m-d H:i:s';
		$date                = DateTime::createFromFormat( $format, $ct_search_data->time );
		$time                = $date->format( $ct_wp_date_format );
		$ct_search_args      = $ct_search_data->query;
		$ct_alert_type       = $ct_search_data->url;
		$ct_search_url_parts = wp_parse_url( $ct_alert_type );

		$ct_search_url_query = array();

		if('email-alerts' !== $ct_alert_type) {
			parse_str( $ct_search_url_parts['query'], $ct_search_url_query );
		}

		

		if( ! empty( $ct_search_args ) ) {
			$ct_search_args_decoded = @unserialize( base64_decode( $ct_search_args ) );

			$fields_map = array(
				'ct_beds' => 'beds',
				'ct_baths' => 'baths',
				'ct_property_type' => 'property_type',
				'ct_city' => 'city',
				'ct_city_multi' => 'city_multi',
				'ct_county' => 'county',
				'ct_county_multi' => 'county_multi',
				'ct_state' => 'state',
				'ct_status' => 'status',
				'ct_zipcode' => 'zip',
				'ct_price_from' => 'pricefrom',
				'ct_price_to' => 'priceto',
			);

			foreach ( $fields_map as $native => $form ) {
				if( empty( $ct_search_url_query[$native] ) && isset( $ct_search_args_decoded[$form] ) ) {
					$ct_search_url_query[$native] = $ct_search_args_decoded[$form];
				}
			}
		}

		// Support Multiple Statuses
		$args_status_multi = array();
		$ct_search_url_status = '';

		if(isset( $ct_search_url_query['ct_ct_status_multi'] )) {
			$args_status_multi = $ct_search_url_query['ct_ct_status_multi'];

			$status_multi = ct_parse_multi_params( $args_status_multi, 'ct_status' );

			$ct_search_url_status = implode( ', ', $status_multi );

		} else if( isset( $ct_search_url_query['ct_status'] ) ) {
			$ct_search_url_status = ct_get_status_name( $ct_search_url_query['ct_status'] );
		}

		// Support Additional Features (multi)
		$args_additional_features = array();

		if(isset( $ct_search_url_query['ct_additional_features'] )) {
			$args_additional_features = $ct_search_url_query['ct_additional_features'];
		}

		$additional_features = ct_parse_multi_params( $args_additional_features, 'ct_additional_features' );

		// Support Multiple Property Types
		$args_property_types = array();

		if(isset( $ct_search_url_query['ct_property_type'] )) {
			$args_property_types = $ct_search_url_query['ct_property_type'];
		}

		$property_types_multi = ct_parse_multi_params( $args_property_types, 'property_type' );

		// Support Multiple Cities
		$args_city_multi = array();
		$args_county_multi = array();
		$ct_search_url_city = '';
		$ct_search_url_county = '';

		if(isset( $ct_search_url_query['ct_city_multi'] )) {
			$args_city_multi = $ct_search_url_query['ct_city_multi'];

			$city_multi = ct_parse_multi_params( $args_city_multi, 'city' );

			$ct_search_url_city = implode( ', ', $city_multi );

		} else if( isset( $ct_search_url_query['ct_city'] ) ) {
			$ct_search_url_city = ct_get_city_name( $ct_search_url_query['ct_city'] );
		}

		if( isset( $ct_search_url_query['ct_county'] ) && is_array( $ct_search_url_query['ct_county'] ) ) {
			$ct_search_url_query['ct_county_multi'] = $ct_search_url_query['ct_county'];
		}

		if(isset( $ct_search_url_query['ct_county_multi'] )) {
			$args_county_multi = $ct_search_url_query['ct_county_multi'];

			$county_multi = ct_parse_multi_params( $args_county_multi, 'county' );

			$ct_search_url_county = implode( ', ', $county_multi );

		} else if( isset( $ct_search_url_query['ct_county'] ) ) {
			$ct_search_url_county = ct_get_county_name( $ct_search_url_query['ct_county'] );
		}


		$ct_search_url_beds       = isset( $ct_search_url_query['ct_beds'] ) ? $ct_search_url_query['ct_beds'] : '';
		$ct_search_url_beds_plus  = isset( $ct_search_url_query['ct_beds_plus'] ) ? $ct_search_url_query['ct_beds_plus'] : '';
		$ct_search_url_baths      = isset( $ct_search_url_query['ct_baths'] ) ? $ct_search_url_query['ct_baths'] : '';
		$ct_search_url_baths_plus = isset( $ct_search_url_query['ct_baths_plus'] ) ? $ct_search_url_query['ct_baths_plus'] : '';

		$ct_search_url_property_type = '';

		if(isset( $ct_search_url_query['ct_property_type'] )) {
			if(!is_array( $ct_search_url_query['ct_property_type'] )) {
				$ct_search_url_property_type = ct_get_property_type_name( $ct_search_url_query['ct_property_type'] );
			}
		}
		
	

		$ct_search_url_state      = isset( $ct_search_url_query['ct_state'] ) ? ct_get_state_name( $ct_search_url_query['ct_state'] ) : '';
		$ct_search_url_zipcode    = isset( $ct_search_url_query['ct_zipcode'] ) ? $ct_search_url_query['ct_zipcode'] : '';
		$ct_search_url_price_from = isset( $ct_search_url_query['ct_price_from'] ) ? $ct_search_url_query['ct_price_from'] : '';
		$ct_search_url_price_to   = isset( $ct_search_url_query['ct_price_to'] ) ? $ct_search_url_query['ct_price_to'] : '';
		$ct_search_url_year_from  = isset( $ct_search_url_query['ct_year_from'] ) ? $ct_search_url_query['ct_year_from'] : '';
		$ct_search_url_year_to    = isset( $ct_search_url_query['ct_year_to'] ) ? $ct_search_url_query['ct_year_to'] : '';

		$ct_search_url_price_from = str_replace( ',', '', $ct_search_url_price_from );
		$ct_search_url_price_to   = str_replace( ',', '', $ct_search_url_price_to );
		$ct_search_url_price_from = str_replace( $ct_currency, '', $ct_search_url_price_from );
		$ct_search_url_price_to   = str_replace( $ct_currency, '', $ct_search_url_price_to );

		$labels = array();
		if( function_exists('ct_email_state_labels') ) {
			$labels = ct_email_state_labels();
		}

		if( $ct_search_data->esetting !== 'off' && ! isset($labels[$ct_search_data->esetting]) ) {
			$ct_search_data->esetting = 'on';
		}

		?>
		<li class="saved-search-block card pad30">
			<?php if('off' !== $ct_search_data->esetting) {
				echo '<span class="saved-search-alert-status alert-on"><span class="indicator alert-on"></span><span class="esetting-label">' . esc_html( $labels[$ct_search_data->esetting] ) . '</span></span>';
			} else {
				echo '<span class="saved-search-alert-status alert-off"><span class="indicator alert-off"></span><span class="esetting-label">' . esc_html__( 'Off', 'contempo' ) . '</span></span>';
			} ?>

			<div class="col span_12 first date-alert-saved marB5">
				<small class="muted">
					<?php echo esc_html( $time ); ?>
				</small>
			</div>

			<div class="col span_12 first alert-query marB30">
				<?php

				$saved_search_url = '';

				if('email-alerts' !== $ct_alert_type) {
					// Copy the value of ct_alert_type to saved_search url
					// The $ct_alert_type is actually the url
					$saved_search_url = $ct_alert_type;

				} else {
					// Construct clean and safe url parameters to search listings
					$saved_search_url = ct_generate_saved_search_url(
						array(
							'saved_search'     => 'true',
							'search-listings'  => 'true',
							'ct_beds'          => $ct_search_url_query['ct_beds'] ?? '',
							'ct_baths'         => $ct_search_url_query['ct_baths'] ?? '',
							'ct_property_type' => $ct_search_url_query['ct_property_type'] ?? '',
							'ct_city_multi'  => ! empty( $ct_search_url_query['ct_city'] ) ? [$ct_search_url_query['ct_city']] : '',
							'ct_state'         => $ct_search_url_query['ct_state'] ?? '',
							'ct_ct_status_multi' => ! empty( $ct_search_url_query['ct_status'] ) ? [$ct_search_url_query['ct_status']] : '',
							'ct_zipcode'       => $ct_search_url_query['ct_zipcode'] ?? '',
							'ct_price_from'    => $ct_search_url_query['ct_price_from'] ?? '',
							'ct_price_to'      => $ct_search_url_query['ct_price_to'] ?? '',
						)
					);
				}
				?>
				
				<a href="<?php echo esc_url( $saved_search_url ); ?>" title="<?php echo esc_attr__( 'Run Query', 'contempo' ); ?>">

				<?php
				// Multi Type Attributes
				if(is_array( $property_types_multi ) && ! empty( $property_types_multi )) {
					$attributes['property_types'] = $property_types_multi;
				} else {
					if(!empty( $ct_search_url_property_type )) {
						$attributes['property_types'] = $ct_search_url_property_type;
					}
				}

				// Insert single status into attributes
				if(!empty( $ct_search_url_status )) {
					$attributes['status'] = ucwords( $ct_search_url_status );
				}

				// Insert Beds Attributes
				$beds_label = isset( $ct_options['ct_bed_beds_or_bedrooms'] ) ? $ct_options['ct_bed_beds_or_bedrooms'] : '';
				
				// Beds labels.
				$labels = array(
					'rooms' => __('Rooms', 'contempo'),
					'bedrooms' => __('Bedrooms', 'contempo'),
					'beds' => __('Beds', 'contempo'),
				);
				
				if(!array_key_exists( $beds_label, $labels )) {
					$beds_label = __('Bed', 'contempo');
				} else {
					$beds_label = $labels[$beds_label];
				}

				// Baths labels
				$bath_labels_option = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? $ct_options['ct_bath_baths_or_bathrooms'] : '';
				$bath_labels = array(
					'bathrooms' => __('Bathrooms', 'contempo'),
					'baths' => __('Baths', 'contempo'),
				);
				
				if(!array_key_exists( $bath_labels_option, $bath_labels )) {
					$bath_labels = __('Bath', 'contempo');
				} else {
					$bath_labels = $bath_labels[$bath_labels_option];
				}

				// Beds and Baths coming from saved search
				if(!empty( $ct_search_url_beds )) {
					/* translators: %d: number of beds */
					$attributes['beds'] = sprintf( __( '%s %s', 'contempo' ), $ct_search_url_beds, $beds_label );
				}
				if(!empty( $ct_search_url_baths )) {
					/* translators: %d: number of baths */
					$attributes['baths'] = sprintf( __( '%d %s', 'contempo' ), $ct_search_url_baths, $bath_labels );
				}

				// Insert Beds+/Baths+ attributes
				if(!empty( $ct_search_url_beds_plus )) {
					/* translators: %d: number of beds plus */
					$attributes['beds-plus'] = sprintf( __( '%d + %s', 'contempo' ), $ct_search_url_beds_plus, $beds_label );
				}
				if(!empty( $ct_search_url_baths_plus )) {
					/* translators: %d: number of baths plus */
					$attributes['baths-plus'] = sprintf( __( '%d + %s', 'contempo' ), $ct_search_url_baths_plus, $bath_labels );
				}

				// Insert Year From attributes
				if(!empty( $ct_search_url_year_from )) {
					$attributes['year-from'] = $ct_search_url_year_from;
				}
				if(!empty( $ct_search_url_year_to )) {
					$attributes['year-to'] = $ct_search_url_year_to;
				}

				// Insert Prices attributes
				$final_price_from = $ct_search_url_price_from;
				$final_price_to   = $ct_search_url_price_to;
				
				// Reverse the token depending on currency placement
				if('after' === $ct_currency_placement) {
					$price_token = '%1$s%2$s';
				} else {
					$price_token = '%2$s%1$s';
				}

				if(!empty( $final_price_from )) {
					$attributes['price_from'] = sprintf( $price_token, number_format_i18n( $final_price_from, 0 ), ct_currency( false ) );
				}
				if(!empty( $final_price_to )) {
					$attributes['price_to'] = sprintf( $price_token, number_format_i18n( $final_price_to, 0 ), ct_currency( false ) );
				}

				// Insert City State Zip attributes
				$final_city    = $ct_search_url_city;
				$final_county  = $ct_search_url_county;
				$final_state   = $ct_search_url_state;
				$final_zipcode = $ct_search_url_zipcode;

				// City, State Zipcode
				$attributes[ 'city-state-zip' ] = '';
				if( ! empty( $final_city ) ) {
					$attributes[ 'city-state-zip' ] = $final_city;
				}
				if( ! empty( $final_county ) ) {
					if( ! empty( $attributes[ 'city-state-zip' ] )) {
						$attributes['city-state-zip'] .= ',';
					}

					$attributes[ 'city-state-zip' ] = ' ' . $final_county;
				}
				if( ! empty( $final_state ) || ! empty( $final_zipcode ) ) {
					if( ! empty( $attributes[ 'city-state-zip' ] )) {
						$attributes['city-state-zip'] .= ',';
					}

					if( ! empty( $final_state ) ) {
						$attributes[ 'city-state-zip' ] .= ' ' . $final_state;
					}

					if( ! empty( $final_zipcode ) ) {
						$attributes[ 'city-state-zip' ] .= ' ' . $final_zipcode;
					}
				}

				$attributes[ 'city-state-zip' ] = trim($attributes[ 'city-state-zip' ]);

				// Dump $attributes to debug
				// echo '<pre>'; var_dump( $attributes ); '</pre>';
				// Finally output the correct attributes, separated by comma

				if(isset( $attributes['property_types'] ) && ! empty( $attributes['property_types'] )) {
					if(is_array( $attributes['property_types'] )) {
						$props = array_reverse( $attributes['property_types'] );
						// Put multi types into the beginning of the array
						foreach ( $props as $prop ) {
							array_unshift( $attributes, $prop );
						}
						unset( $attributes['property_types'] );
					}
				}

				// Additional Features
				$additional_features = ct_parse_multi_params( $args_additional_features, 'additional_features' );
				if(!empty( $args_additional_features )) {
					foreach ( $additional_features as $additional_feature ) {
						$attributes[] = $additional_feature;
					}
				}

				// Fix City State Zip.
				if(isset( $attributes['city-state-zip'] ) && ! empty( $attributes['city-state-zip'] )) {
					$city_state_zip__trimmed = str_replace( ' ', '', $attributes['city-state-zip'] );
					if(0 === strlen( $city_state_zip__trimmed )) {
						// Remove City State Zip if its empty
						unset( $attributes['city-state-zip'] );
					}
				}
				
				// Implode to Comma Separated String & Output Attributes 
				$attributes = array_values( $attributes );

				// Remove empty elements to avoid ugly commas.
				$attributes = array_filter($attributes, function($value) { return !is_null($value) && $value !== ''; });
				
				$attributes = esc_html( implode( ', ', $attributes ) );
				echo ucwords($attributes);

				?>
				</a>
			</div>

			<div class="col span_4 first run-search">
				<?php if('email-alerts' !== $ct_alert_type) { ?>
					<a class="btn btn-tertiary" href="<?php echo esc_url( $ct_alert_type ); ?>">
				<?php } else { ?>
					<a class="btn btn-tertiary" href="<?php echo esc_url( $saved_search_url ); ?>">
				<?php } ?>
					<?php esc_html_e( 'Run Search', 'contempo' ); ?>
				</a>
			</div>
			<div class="col span_6 saved-alert-on-off <?php if(function_exists('ctsa_send_sms')) { echo 'ct-sms-active'; } ?>">
				<?php $toggle = array( 'on', 'off' ); ?>     

				<select data-propid='<?php echo intval( $ct_search_data->id ); ?>' class="esetting">
					<option <?php selected( $ct_search_data->esetting, 'on' ); ?> value="<?php esc_attr_e( 'on', 'contempo' ); ?>">
						<?php esc_html_e( 'Email', 'contempo' ); ?>
					</option>
					<?php do_action('ct_email_alerts_state_combo', $ct_search_data->esetting); ?>
					<option <?php selected( $ct_search_data->esetting, 'off' ); ?> value="<?php esc_attr_e( 'off', 'contempo' ); ?>">
						<?php esc_html_e( 'Off', 'contempo' ); ?>
					</option>
				</select>
			</div>  

			<div class="col span_2 ct-delete-saved-alert delete">
				<a class="remove-search btn btn-delete" href="#" data-propertyid='<?php echo intval( $ct_search_data->id ); ?>'>
					<i class="fa fa-trash-o"></i>
				</a>
			</div>

			<?php if( $is_crm_user ) : ?>
				<div class="col first">
					<?php 
						$alert_id = intval( $ct_search_data->id ); 
						$email = $ct_search_data->email;
						
						$leads = array( 
							(array) wp_get_current_user()->data
						);

						if( function_exists( 'ct_leads_list' ) ) {
							$data = ct_leads_list( 0, 100 );

							if( $data && isset( $data->users ) && count( $data->users )) {
								$leads = array_merge( $leads, $data->users );
							}
						}

						usort($leads, function ($lead1, $lead2) {
							$lead1name = ! empty( $lead1['display_name'] ) ? $lead1['display_name'] : $lead1['user_name'];
							$lead2name = ! empty( $lead2['display_name'] ) ? $lead2['display_name'] : $lead2['user_name'];
							return strnatcmp($lead1name, $lead2name);
						});
					?>
					<label for="recipient<?php echo esc_attr_e($alert_id); ?>"><?php _e('Assigned Lead', 'contempo'); ?></label>
					<select id="recipient<?php echo esc_attr_e($alert_id); ?>" 
							data-propid='<?php echo esc_attr_e($alert_id); ?>' 
							data-nonce='<?php echo wp_create_nonce('email-alerts-recipient-' . $alert_id); ?>' 
							class="recipient-select nice-ignore recipient-setting"
							data-placeholder="<?php esc_attr_e('Assign a lead…', 'contempo'); ?>"
					>
						<option value=""><?php esc_html_e('Assign a lead…', 'contempo'); ?></option>
						<?php foreach ( $leads as $lead ): ?>
							<option <?php esc_html_e($lead['user_email'] == $email ? 'selected' : ''); ?> value="<?php esc_attr_e( $lead['user_email'] ); ?>">
								<?php esc_html_e( ! empty( $lead['display_name'] ) ? $lead['display_name'] : $lead['user_name'] ); ?>
								(<?php esc_html_e( $lead['user_email'] ); ?>)
							</option>
						<?php endforeach; ?>
					</select>
				</div>  
			<?php endif; ?>
		</li>
		<?php
	}
}
