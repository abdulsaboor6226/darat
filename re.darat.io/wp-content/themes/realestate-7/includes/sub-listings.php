<?php
/**
 * Related sub listings dependant on tagged community for single-listings.php
 *
 * @package WP Pro Real Estate 7
 * @subpackage Include
 */
 
global $ct_options;

$ct_search_results_listing_style = isset( $ct_options['ct_search_results_listing_style'] ) ? $ct_options['ct_search_results_listing_style'] : '';
$ct_sub_listings_exclude_sold = isset( $ct_options['ct_sub_listings_exclude_sold'] ) ? $ct_options['ct_sub_listings_exclude_sold'] : '';
$ct_sub_listings_carousel = isset( $ct_options['ct_sub_listings_carousel'] ) ? $ct_options['ct_sub_listings_carousel'] : '';
$ct_sub_listings_carousel_max_num = isset( $ct_options['ct_sub_listings_carousel_max_num'] ) ? $ct_options['ct_sub_listings_carousel_max_num'] : '';

if($ct_sub_listings_carousel == 'yes') {
	$ct_sub_listings_num = $ct_sub_listings_carousel_max_num;
} else {
	$ct_sub_listings_num = 3;
}

wp_reset_postdata();

if($ct_sub_listings_carousel == 'yes') {
	echo '<div id="ct-listings-carousel-nav-sub-listings" class="ct-listings-carousel-nav"></div>';
	echo '<ul id="owl-listings-carousel-sub-listings" class="col span_12 row first owl-carousel">';
} else {
	echo '<ul>';
}

    global $post;

    $terms = strip_tags( get_the_term_list( $wp_query->post->ID, 'community', '', ', ', '' ) );

    if($ct_sub_listings_exclude_sold == 'yes') {
    	$args = array(
			'post_type' => 'listings',
			'post__not_in' => array($post->ID),
			'showposts'=> $ct_sub_listings_num,
			'tax_query' => array(
	    		'relation' => 'AND',
			        array(
			            'taxonomy' => 'ct_status',
			            'field'    => 'slug',
					    'terms'    => 'sold',
			            'operator' => 'NOT IN'
			        ),
			        array(
						'taxonomy' => 'community',
						'field'    => 'slug',
						'terms'    => $terms,
					),
		    ),
		);
    } else {
		$args = array(
			'post_type' => 'listings',
			'post__not_in' => array($post->ID),
			'showposts'=> $ct_sub_listings_num,
			'tax_query' => array(
				array(
					'taxonomy' => 'community',
					'field'    => 'slug',
					'terms'    => $terms,
				),
			)
		);
	}
	$query = new WP_Query( $args );

	if( $query->have_posts() ) {

	$count = 0; while ($query->have_posts()) : $query->the_post(); ?>
        
        <?php if($ct_sub_listings_carousel == 'yes') { ?>
        	<li class="listing col span_12 first <?php echo esc_html($ct_search_results_listing_style); ?> <?php if(get_post_meta($post->ID, 'source', true) == 'idx-api') { echo 'idx-listing'; } ?>">
        <?php } else { ?>
        	<li class="listing col span_4 <?php echo esc_html($ct_search_results_listing_style); ?> <?php if($count % 3 == 0) { echo 'first'; } ?> <?php if(get_post_meta($post->ID, 'source', true) == 'idx-api') { echo 'idx-listing'; } ?>">
        <?php } ?>

            <figure>
                <?php
	                $status_tags = strip_tags( get_modified_term_list_name( $query->post->ID, 'ct_status', '', ' ', '', array('featured') ) );
					if($status_tags != '') {
						echo '<h6 class="snipe status ';
								$status_terms = get_the_terms( $query->post->ID, 'ct_status', array() );
								if(!empty($status_terms) && ! is_wp_error($status_terms)) {
								     foreach($status_terms as $term) {
								       echo esc_html($term->slug) . ' ';
								     }
								 }
							echo '">';
							echo '<span>';
								echo esc_html($status_tags);
							echo '</span>';
						echo '</h6>';
					}
                ?>
                <?php ct_first_image_linked(); ?>
            </figure>
            <div class="grid-listing-info">
	            <header>
	                <?php if($ct_search_results_listing_style == 'modern_two') { ?>
		                <p class="price"><a class="listing-link" <?php ct_listing_permalink(); ?> rel="<?php the_ID(); ?>"><?php ct_listing_price(); ?></a></p>
		                <?php do_action( 'before_listing_grid_propinfo' ); ?>
		                <div class="propinfo">
		                    <ul>
								<?php ct_propinfo(); ?>
		                    </ul>
		                </div>
		            <?php } ?>

	            	<?php do_action( 'before_listing_grid_title' ); ?>
                
                    <h5 class="listing-title">
                        <a class="listing-link" <?php ct_listing_permalink(); ?> rel="<?php the_ID(); ?>">
							<?php ct_listing_title(); ?>
                        </a>
                    </h5>
					
					<?php do_action( 'before_listing_grid_address' ); ?>

	                <?php
		            	if(taxonomy_exists('city')){
			                $city = strip_tags( get_the_term_list( $query->post->ID, 'city', '', ', ', '' ) );
			            }
			            if(taxonomy_exists('state')){
							$state = strip_tags( get_the_term_list( $query->post->ID, 'state', '', ', ', '' ) );
						}
						if(taxonomy_exists('zipcode')){
							$zipcode = strip_tags( get_the_term_list( $query->post->ID, 'zipcode', '', ', ', '' ) );
						}
						if(taxonomy_exists('country')){
							$country = strip_tags( get_the_term_list( $query->post->ID, 'country', '', ', ', '' ) );
						}
					?>
	                <p class="location muted marB0"><?php echo esc_html($city); ?>, <?php echo esc_html($state); ?> <?php echo esc_html($zipcode); ?> <?php echo esc_html($country); ?></p>
                </header>
                <?php if($ct_search_results_listing_style != 'modern_two') { ?>
	                <p class="price marB0"><?php ct_listing_price(); ?></p>
		            <div class="propinfo">
		                <ul class="propinfo marB0 marL0 padL0">
							<?php 

							$ct_source = get_post_meta($query->post->ID, 'source', true);
						    $ct_idx_rupid = get_post_meta($query->post->ID, '_ct_idx_rupid', true);

							$ct_use_propinfo_icons = isset( $ct_options['ct_use_propinfo_icons'] ) ? esc_html( $ct_options['ct_use_propinfo_icons'] ) : '';
						    $ct_listings_propinfo_property_type = isset( $ct_options['ct_listings_propinfo_property_type'] ) ? esc_html( $ct_options['ct_listings_propinfo_property_type'] ) : '';
						    $ct_listings_propinfo_price_per = isset( $ct_options['ct_listings_propinfo_price_per'] ) ? esc_html( $ct_options['ct_listings_propinfo_price_per'] ) : '';
						    $ct_bed_beds_or_bedrooms = isset( $ct_options['ct_bed_beds_or_bedrooms'] ) ? esc_html( $ct_options['ct_bed_beds_or_bedrooms'] ) : '';
						    $ct_bath_baths_or_bathrooms = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? esc_html( $ct_options['ct_bath_baths_or_bathrooms'] ) : '';
						    $ct_listings_lotsize_format = isset( $ct_options['ct_listings_lotsize_format'] ) ? esc_html( $ct_options['ct_listings_lotsize_format'] ) : '';

						    $ct_property_type = strip_tags( get_the_term_list( $query->post->ID, 'property_type', '', ', ', '' ) );
						    $beds = strip_tags( get_the_term_list( $query->post->ID, 'beds', '', ', ', '' ) );
						    $baths = strip_tags( get_the_term_list( $query->post->ID, 'baths', '', ', ', '' ) );
						    $ct_community = strip_tags( get_the_term_list( $query->post->ID, 'community', '', ', ', '' ) );

						    $ct_lotsize = get_post_meta($query->post->ID, '_ct_lotsize', true);
						    $ct_lot_acres = get_post_meta($query->post->ID, '_ct_idx_overview_acres', true);

						    $ct_walkscore = isset( $ct_options['ct_enable_walkscore'] ) ? esc_html( $ct_options['ct_enable_walkscore'] ) : '';
						    $ct_rentals_booking = isset( $ct_options['ct_rentals_booking'] ) ? esc_html( $ct_options['ct_rentals_booking'] ) : '';
						    $ct_listing_reviews = isset( $ct_options['ct_listing_reviews'] ) ? esc_html( $ct_options['ct_listing_reviews'] ) : '';

						    if($ct_walkscore == 'yes') {
							    /* Walk Score */
							   	$latlong = get_post_meta($query->post->ID, "_ct_latlng", true);
							   	$ct_trans_name = uniqid('ct_');

							   	if($latlong != '' && false === ( $ct_ws = get_transient( $ct_trans_name . '_walkscore_data' ) )) {
									list($lat, $long) = explode(',', $latlong, 2);
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

						    if(get_post_meta($query->post->ID, '_ct_pets', true)) {
							    echo '<li class="row pets">';
									echo '<span class="muted left">';
										if($ct_use_propinfo_icons != 'icons') {
							    			_e('Pets', 'contempo');
							    		} else {
							    			ct_pet_svg();
							    		}
									echo '</span>';
									echo '<span class="right">';
										echo get_post_meta($query->post->ID, '_ct_pets', true);
							        echo '</span>';
							    echo '</li>';
							}

							if(get_post_meta($query->post->ID, '_ct_parking', true)) {
								$ct_parking = get_post_meta($query->post->ID, '_ct_parking', true);
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
							    if(get_post_meta($query->post->ID, '_ct_rental_guests', true)) {
							        echo '<li class="row guests">';
							            echo '<span class="muted left">';
							                if($ct_use_propinfo_icons != 'icons') {
								    			_e('Guests', 'contempo');
								    		} else {
								    			ct_group_svg();
								    		}
							            echo '</span>';
							            echo '<span class="right">';
							                 echo get_post_meta($query->post->ID, '_ct_rental_guests', true);
							            echo '</span>';
							        echo '</li>';
							    }

							    if(get_post_meta($query->post->ID, '_ct_rental_min_stay', true)) {
							        echo '<li class="row min-stay">';
							            echo '<span class="muted left">';
							                if($ct_use_propinfo_icons != 'icons') {
								    			_e('Min Stay', 'contempo');
								    		} else {
								    			ct_calendar_svg();
								    		}
							            echo '</span>';
							            echo '<span class="right">';
							                 echo get_post_meta($query->post->ID, '_ct_rental_min_stay', true);
							            echo '</span>';
							        echo '</li>';
							    }
							}

						    if(get_post_meta($query->post->ID, '_ct_sqft', true) && is_numeric(get_post_meta($query->post->ID, '_ct_sqft', true))) {
						    	if($ct_use_propinfo_icons != 'icons') {
							        echo '<li class="row sqft">';
							            echo '<span class="muted left">';
							    			ct_sqftsqm();
							    		echo '</span>';
										echo '<span class="right">';
											 $value = get_post_meta($query->post->ID, '_ct_sqft', true);
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
							                 echo number_format_i18n(get_post_meta($query->post->ID, '_ct_sqft', true), 0);
							                 echo ' ' . ct_sqftsqm();
							            echo '</span>';
							        echo '</li>';
							    }
						    }

						    $price_meta = get_post_meta($query->post->ID, '_ct_price', true);
							$price_meta = preg_replace('/[\$,]/', '', $price_meta);

							$ct_sqft = get_post_meta($query->post->ID, '_ct_sqft', true);

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

							if(get_post_meta($query->post->ID, '_ct_year_built', true) || get_post_meta($query->post->ID, '_ct_idx_overview_year_built', true)) {
							    echo '<li class="row year-built">';
									echo '<span class="muted left">';
										if($ct_use_propinfo_icons != 'icons') {
							    			_e('Year Built', 'contempo');
							    		} else {
							    			ct_year_built_svg();
							    		}
									echo '</span>';
									echo '<span class="right">';
										if(get_post_meta($query->post->ID, '_ct_year_built', true)) {
											echo get_post_meta($query->post->ID, '_ct_year_built', true);
										} elseif(get_post_meta($query->post->ID, '_ct_idx_overview_year_built', true)) {
											echo get_post_meta($query->post->ID, '_ct_idx_overview_year_built', true);
										}
							        echo '</span>';
							    echo '</li>';
							}

						    if(get_post_meta($query->post->ID, '_ct_lotsize', true) || get_post_meta($query->post->ID, '_ct_idx_overview_acres', true)) {
						        if(get_post_meta($query->post->ID, '_ct_lotsize', true) || get_post_meta($query->post->ID, '_ct_idx_overview_acres', true)) {
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

						        if((get_post_meta($query->post->ID, '_ct_lotsize', true))) {
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
						    			$ct_property_type_terms = get_the_terms($query->post->ID, 'property_type');
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
							?>
	                    </ul>
	                </div>
	            <?php } ?>

                <?php ct_listing_creation_date(); ?>
                <?php ct_listing_grid_agent_info(); ?>
                	<div class="clear"></div>
                <?php ct_brokered_by(); ?>
            </div>
	
        </li>
        
        <?php
		
		$count++;
		
		if($ct_sub_listings_carousel != 'yes') {
			if($count % 3 == 0) {
				echo '<div class="clear"></div>';
			}
		}
		
		endwhile; wp_reset_postdata();
	} ?>
</ul>
    <div class="clear"></div>