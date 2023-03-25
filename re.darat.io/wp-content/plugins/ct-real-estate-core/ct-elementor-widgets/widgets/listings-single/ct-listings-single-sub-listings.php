<?php
namespace CT_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * CT City Links
 *
 * Elementor widget for listings city links.
 *
 * @since 1.0.0
 */
class CT_Listings_Single_Sub_Listings extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ct-listings-single-sub-listings';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'CT Sub Listings', 'contempo' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-carousel';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'ct-real-estate-7-listings-single' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	

	protected function register_controls() {

		$this->start_controls_section(
			'options',
			[
				'label' => __( 'Options', 'contempo' ),
			]
		);

			$this->add_control(
				'exclude_solds',
				[
					'label' => __( 'Exclude Solds', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'on' => __( 'On', 'contempo' ),
					'off' => __( 'off', 'contempo' ),
					'return_value' => 'on',
					'default' => 'on',
				]
			);
			
			$this->add_control(
				'show_as_carousel',
				[
					'label' => __( 'Carousel', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'on' => __( 'On', 'contempo' ),
					'off' => __( 'off', 'contempo' ),
					'return_value' => 'on',
					'default' => 'on',
					'description' => 'If set to off the max number shown is 3.'
				]
			);

			$this->add_control(
				'max_number',
				[
					'label' => __( 'Max Number', 'contempo' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( '9', 'contempo' ),
					'condition' => [
			            'show_as_carousel' => 'on'
			        ],
					'placeholder' => __( 'Enter the max number of sub listings you\'d like to show.', 'contempo' ),
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} #listing-sub-listings',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 1.3125, 'unit' => 'em']],
			            'font_weight' => ['default' => 600],
			            'line_height' => ['default' => ['size' => 1.2, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'title_text_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} #listing-sub-listings' => 'color: {{VALUE}}',
					],
					'default' => '#121212',
				]
			);

		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {

		global $ct_options;

		$settings = $this->get_settings_for_display();
		$attributes['is_elementor'] = 1;

		$ct_search_results_listing_style = isset( $ct_options['ct_search_results_listing_style'] ) ? $ct_options['ct_search_results_listing_style'] : '';

		if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $attributes['is_elementor_edit'] = 1;

            echo '<div class="ct-notice">' . __('Sub Listings will be rendered in frontend (if sub listings are available for the particular listing).', 'contempo') . '</div>';

        } else {

			if($settings['show_as_carousel'] == 'on') {
				$ct_sub_listings_num = $settings['max_number'];
			} else {
				$ct_sub_listings_num = 3;
			}

			wp_reset_postdata();

		    $terms = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'community', '', ', ', '' ) );

		    if($settings['exclude_solds'] == 'on') {
		    	$args = array(
					'post_type' => 'listings',
					'post__not_in' => array(ct_return_listing_id_elementor($attributes)),
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
					'post__not_in' => array(ct_return_listing_id_elementor($attributes)),
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
			$query = new \WP_Query( $args );

			if( $query->have_posts() ) {

				echo '<div class="ct-elementor-listings-single">';

					if($settings['show_as_carousel'] == 'on') {
						echo '<div id="ct-listings-carousel-nav-sub-listings" class="ct-listings-carousel-nav"></div>';
						echo '<ul id="owl-listings-carousel-sub-listings" class="col span_12 row first owl-carousel">';
					} else {
						echo '<ul>';
					}

				$count = 0; while ($query->have_posts()) : $query->the_post(); ?>
			        
			        <?php if($settings['show_as_carousel'] == 'on') { ?>
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
											if ( ! empty( $status_terms ) && ! is_wp_error( $status_terms ) ){
											     foreach ( $status_terms as $term ) {
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
									    
									    $ct_walkscore = isset( $ct_options['ct_enable_walkscore'] ) ? esc_html( $ct_options['ct_enable_walkscore'] ) : '';
									    $ct_rentals_booking = isset( $ct_options['ct_rentals_booking'] ) ? esc_html( $ct_options['ct_rentals_booking'] ) : '';
									    $ct_listing_reviews = isset( $ct_options['ct_listing_reviews'] ) ? esc_html( $ct_options['ct_listing_reviews'] ) : '';

									    if($ct_walkscore == 'yes') {
										    /* Walk Score */
										   	$latlong = get_post_meta($post->ID, "_ct_latlng", true);
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
									    
									    if(get_post_meta($post->ID, "_ct_pets", true)) {
										    echo '<li class="row pets">';
												echo '<span class="muted left">';
													if($ct_use_propinfo_icons != 'icons') {
										    			_e('Pets', 'contempo');
										    		} else {
										    			ct_pet_svg();
										    		}
												echo '</span>';
												echo '<span class="right">';
													echo get_post_meta($post->ID, "_ct_pets", true);
										        echo '</span>';
										    echo '</li>';
										}

										if(get_post_meta($post->ID, "_ct_parking", true)) {
										    echo '<li class="row parking">';
												echo '<span class="muted left">';
													if($ct_use_propinfo_icons != 'icons') {
										    			_e('Parking', 'contempo');
										    		} else {
										    			ct_parking_svg();
										    		}
												echo '</span>';
												echo '<span class="right">';
													echo get_post_meta($post->ID, "_ct_parking", true);
										        echo '</span>';
										    echo '</li>';
										}

										include_once ABSPATH . 'wp-admin/includes/plugin.php';
										if(function_exists('pixreviews_init_plugin') && $ct_listing_reviews == 'yes') {
											global $pixreviews_plugin;
											$ct_rating_avg = $pixreviews_plugin->get_average_rating();
											if($ct_rating_avg != '') {
												echo '<li class="row rating">';
										            echo '<span class="muted left">';
										                if($ct_use_propinfo_icons != 'icons') {
											    			_e('Rating', 'contempo');
											    		} else {
											    			ct_star_svg();
											    		}
										            echo '</span>';
										            echo '<span class="right">';
										                 echo esc_html($pixreviews_plugin->get_average_rating());
										            echo '</span>';
										        echo '</li>';
										    }
										}

									    if($ct_rentals_booking == 'yes' || class_exists('Booking_Calendar')) {
										    if(get_post_meta($post->ID, "_ct_rental_guests", true)) {
										        echo '<li class="row guests">';
										            echo '<span class="muted left">';
										                if($ct_use_propinfo_icons != 'icons') {
											    			_e('Guests', 'contempo');
											    		} else {
											    			ct_group_svg();
											    		}
										            echo '</span>';
										            echo '<span class="right">';
										                 echo get_post_meta($post->ID, "_ct_rental_guests", true);
										            echo '</span>';
										        echo '</li>';
										    }

										    if(get_post_meta($post->ID, "_ct_rental_min_stay", true)) {
										        echo '<li class="row min-stay">';
										            echo '<span class="muted left">';
										                if($ct_use_propinfo_icons != 'icons') {
											    			_e('Min Stay', 'contempo');
											    		} else {
											    			ct_calendar_svg();
											    		}
										            echo '</span>';
										            echo '<span class="right">';
										                 echo get_post_meta($post->ID, "_ct_rental_min_stay", true);
										            echo '</span>';
										        echo '</li>';
										    }
										}
									    
									    if(get_post_meta($post->ID, "_ct_sqft", true)) {
									    	if($ct_use_propinfo_icons != 'icons') {
										        echo '<li class="row sqft">';
										            echo '<span class="muted left">';
										    			ct_sqftsqm();
										    		echo '</span>';
													echo '<span class="right">';
														 $value = get_post_meta($post->ID, "_ct_sqft", true);
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
										                 echo number_format_i18n(get_post_meta($post->ID, "_ct_sqft", true), 0);
										                 echo ' ' . ct_sqftsqm();
										            echo '</span>';
										        echo '</li>';
										    }
									    }

									    $price_meta = get_post_meta(get_the_ID(), '_ct_price', true);
										$price_meta= preg_replace('/[\$,]/', '', $price_meta);

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
									    
									    if(get_post_meta($post->ID, "_ct_lotsize", true)) {
									        if(get_post_meta($post->ID, "_ct_lotsize", true)) {
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
									    			if($ct_listings_lotsize_format == 'yes') {
										                 echo number_format_i18n(get_post_meta($post->ID, "_ct_lotsize", true), 0) . ' ';
										            } else {
										             	echo get_post_meta($post->ID, "_ct_lotsize", true) . ' ';
										            }
									                ct_acres();
									            echo '</span>';
									            
									        if((get_post_meta($post->ID, "_ct_lotsize", true))) {
									            echo '</li>';
									        }
									    }

									    if($ct_walkscore == 'yes' && $ct_ws['status'] == 1) {
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
										    			if(ct_has_type('commercial') || ct_has_type('industrial') || ct_has_type('retail')) { 
															ct_building_svg();
														} elseif(ct_has_type('land') || ct_has_type('lot')) { 
															ct_tree_svg();
														} else {
															ct_property_type_svg();
														}
										    		}
									    		echo '</span>';
									    		echo '<span class="right">';
									               echo esc_html($ct_property_type);
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
					
					if($settings['show_as_carousel'] != 'on') {
						if($count % 3 == 0) {
							echo '<div class="clear"></div>';
						}
					}
					
						endwhile;
					
					echo '</ul>';
				echo '</div>';
					echo '<div class="clear"></div>';

				wp_reset_postdata();
			} ?>

		<?php 	
		}
	}

}
