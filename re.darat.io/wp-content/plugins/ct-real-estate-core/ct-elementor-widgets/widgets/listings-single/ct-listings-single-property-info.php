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
class CT_Listings_Single_Property_Info extends Widget_Base {

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
		return 'ct-listings-single-property-info';
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
		return __( 'CT Property Info', 'contempo' );
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
		return 'eicon-editor-list-ul';
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

			$this->add_responsive_control(
				'text_align',
				[
					'label' => __( 'Alignment', 'contempo' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'default' => 'left',
					'options' => [
						'left' => [
							'title' => __( 'Left', 'contempo' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'contempo' ),
							'icon' => 'eicon-text-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'contempo' ),
							'icon' => 'eicon-text-align-right',
						],
					],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'prefix_class' => 'content-align-%s',
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-property-info' => 'text-align: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_content_section',
			[
				'label' => __( 'Item Content', 'contempo' ),
			]
		);

			$repeater = new \Elementor\Repeater();

			$repeater->add_control(
				'item_title', [
					'label' => __( 'Item Title', 'contempo' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					//'default' => __( 'Item Title' , 'contempo' ),
					'label_block' => true,
				]
			);

			$repeater->add_control(
				'item_content',
				[
					'label' => __( 'Item Content', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'beds',
					'options' => [
						'property_type' => __( 'Property Type', 'contempo' ),
						'beds'  => __( 'Beds', 'contempo' ),
						'baths' => __( 'Baths', 'contempo' ),
						'sqft' => __( 'Sq Ft', 'contempo' ),
						'priceper_sqft' => __( 'Price Per Sq Ft', 'contempo' ),
						'year_built' => __( 'Year Built', 'contempo' ),
						'lot_size' => __( 'Lot Size', 'contempo' ),
						'walkscore' => __( 'Walkscore', 'contempo' ),
						'community' => __( 'Community', 'contempo' ),
						'pets' => __( 'Pets', 'contempo' ),
						'parking' => __( 'Parking', 'contempo' ),
						'prop_id' => __( 'Property ID', 'contempo' ),
					],
				]
			);

			$this->add_control(
				'list',
				[
					'label' => __( 'Property Info Items', 'contempo' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [
						[
							'item_title' => __( 'Property Type', 'contempo' ),
							'item_content' => 'property_type',
						],
						[
							'item_title' => __( 'Beds', 'contempo' ),
							'item_content' => 'beds',
						],
						[
							'item_title' => __( 'Baths', 'contempo' ),
							'item_content' => 'baths',
						],
						[
							'item_title' => __( 'Sq Ft', 'contempo' ),
							'item_content' => 'sqft',
						],
						[
							'item_title' => __( 'Price Per Sq Ft', 'contempo' ),
							'item_content' => 'priceper_sqft',
						],
						[
							'item_title' => __( 'Community', 'contempo' ),
							'item_content' => 'community',
						],
						[
							'item_title' => __( 'MLS #', 'contempo' ),
							'item_content' => 'prop_id',
						],
					],
					'title_field' => '{{{ item_title }}}',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'label_section',
			[
				'label' => __( 'Label', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'label_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .left',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 17, 'unit' => 'px']],
			            'font_weight' => ['default' => 400],
			            'line_height' => ['default' => ['size' => 2, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'label_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .left' => 'color: {{VALUE}}',
					],
					'default' => '#75797f',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_section',
			[
				'label' => __( 'Item', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'item_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} li',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 17, 'unit' => 'px']],
			            'font_weight' => ['default' => 400],
			            'line_height' => ['default' => ['size' => 2, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'item_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} li' => 'color: {{VALUE}}',
					],
					'default' => '#121212',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => __( 'Border', 'contempo' ),
					'fields_options' => [
						'border' => [
							'default' => 'solid',
						],
						'width' => [
							'default' => [
								'top' => '0',
								'right' => '0',
								'bottom' => '1',
								'left' => '0',
								'isLinked' => false,
							],
						],
						'color' => [
							'default' => '#d5d9dd',
						],
					],
					'selector' => '{{WRAPPER}} li',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '0',
						'right' => '0',
						'bottom' => '0',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => true,
					]
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

		if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$ct_source = get_post_meta( ct_return_listing_id_elementor($attributes), 'source', true);

	    $ct_use_propinfo_icons = isset( $ct_options['ct_use_propinfo_icons'] ) ? esc_html( $ct_options['ct_use_propinfo_icons'] ) : '';
	    $ct_listings_propinfo_property_type = isset( $ct_options['ct_listings_propinfo_property_type'] ) ? esc_html( $ct_options['ct_listings_propinfo_property_type'] ) : '';
	    $ct_listings_propinfo_price_per = isset( $ct_options['ct_listings_propinfo_price_per'] ) ? esc_html( $ct_options['ct_listings_propinfo_price_per'] ) : '';
	    $ct_bed_beds_or_bedrooms = isset( $ct_options['ct_bed_beds_or_bedrooms'] ) ? esc_html( $ct_options['ct_bed_beds_or_bedrooms'] ) : '';
	    $ct_bath_baths_or_bathrooms = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? esc_html( $ct_options['ct_bath_baths_or_bathrooms'] ) : '';
	    $ct_listings_lotsize_format = isset( $ct_options['ct_listings_lotsize_format'] ) ? esc_html( $ct_options['ct_listings_lotsize_format'] ) : '';

	    // Property Type
	    $ct_property_type = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'property_type', '', ', ', '' ) );
	    $ct_property_type_terms = get_the_terms( ct_return_listing_id_elementor($attributes), 'property_type');
		$ct_property_type_slugs = wp_list_pluck($ct_property_type_terms, 'term_slug');

		if(in_array('single-family-residence', $ct_property_type_slugs)) {
			$ct_property_type = _e('Single Family Residence', 'contempo');
		} else {
			$ct_property_type = $ct_property_type;
		}

	    $ct_beds = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'beds', '', ', ', '' ) );
	    $ct_baths = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'baths', '', ', ', '' ) );
	    $ct_community = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'community', '', ', ', '' ) );

	    // Sq Ft
	    $ct_sqft = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_sqft", true);

	    if(is_numeric($ct_sqft)) {
			$ct_sqft_output = number_format_i18n($ct_sqft, 0);
		} else {
		 	$ct_sqft_output = esc_html($ct_sqft);
		}

		// Price Per Sq Ft
		$ct_listing_price = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_price', true);
		$ct_listing_price = preg_replace('/[\$,]/', '', $ct_listing_price);

		$ct_currency_placement = isset( $ct_options['ct_currency_placement'] ) ? esc_attr( $ct_options['ct_currency_placement'] ) : '';
		$ct_currency_decimal = isset( $ct_options['ct_currency_decimal'] ) ? esc_attr( $ct_options['ct_currency_decimal'] ) : '';
		
		$ct_currency = "$";

		if( isset( $ct_options['ct_currency'] ) ) {
			$ct_currency = esc_html($ct_options['ct_currency']);
		}

	    if(!empty($ct_listing_price) && !empty($ct_sqft)) {
	    	if(is_numeric($ct_listing_price) && is_numeric($ct_sqft)) {

	    		if(has_term('for-rent', 'ct_status') || has_term('rental', 'ct_status') || has_term('leased', 'ct_status') || has_term('lease', 'ct_status') || has_term('let', 'ct_status') || has_term('sold', 'ct_status')) {
	    			$ct_price_per_sqft = '';
	    		} else {
	    			$ct_price_per_sqft = $ct_listing_price / $ct_sqft;

					if($ct_currency_placement == 'after') {
						$ct_price_per_sqft = number_format_i18n($ct_price_per_sqft, 2) . $ct_currency;
					} else {
						$ct_price_per_sqft = $ct_currency . number_format_i18n($ct_price_per_sqft, 2);
					}
	    		}
			}
	    }

	    // Lot Size
	    $ct_lotsize = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_lotsize", true);
	    $ct_lot_acres = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_idx_overview_acres", true);

	    if($ct_source == 'idx-api' && is_numeric($ct_lotsize)) {
            $ct_lotsize = number_format($ct_lotsize / 43560, 1);
        } else {
        	$ct_lotsize = $ct_lotsize;
        }

        if ( isset( $ct_options['ct_acres'] ) ) {
			if($ct_options['ct_acres'] == "acres") {
				$ct_lotsize_unit_of_measurement = __('Acres', 'contempo');
			} elseif($ct_options['ct_acres'] == "sqft") {
				$ct_lotsize_unit_of_measurement = __('Sq Ft', 'contempo');
			} elseif($ct_options['ct_acres'] == "sqmeters") {
				$ct_lotsize_unit_of_measurement = __('m²', 'contempo');
			} elseif($ct_options['ct_acres'] == "area") {
				$ct_lotsize_unit_of_measurement = __('Area', 'contempo');
			}
		}

        if($ct_source == 'idx-api') {
			if(!empty($ct_lot_acres)) {
    			$ct_lotsize = $ct_lot_acres . ' ' . $ct_lotsize_unit_of_measurement;
    		} else {
    			$ct_lotsize = $ct_lotsize . ' ' . $ct_lotsize_unit_of_measurement;
    		}
        } else {
        	if($ct_listings_lotsize_format == 'yes') {
                 $ct_lotsize = number_format_i18n($ct_lotsize, 0) . ' ' . $ct_lotsize_unit_of_measurement;
            } else {
             	$ct_lotsize = $ct_lotsize . ' ' . $ct_lotsize_unit_of_measurement;
            }
        }

	    // Walkscore
	    $ct_walkscore = isset( $ct_options['ct_enable_walkscore'] ) ? esc_html( $ct_options['ct_enable_walkscore'] ) : '';

	    if($ct_walkscore == 'yes') {
		   	$latlong = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_latlng", true);
		   	$ct_trans_name = uniqid('ct_');

		   	if($latlong != '' && false === ( $ct_ws = get_transient( $ct_trans_name . '_walkscore_data' ) )) {
				list($lat, $long) = explode(',',$latlong,2);
				$address = get_the_title() . ct_taxonomy_return('city') . ct_taxonomy_return('state') . ct_taxonomy_return('zipcode');
				$json = ct_get_walkscore($lat,$long,$address);

				$ct_ws = json_decode($json, true);

				set_transient( $ct_trans_name . '_walkscore_data', $ct_ws, 7 * DAY_IN_SECONDS );
			}
		}

		// Pets
		$ct_pets = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_pets", true);

		// Parking
		$ct_parking = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_parking", true);

		// Property ID
		$ct_property_id = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_mls", true);


		// Output Property Info List 

		do_action('before_single_listing_propinfo');

		if ( $settings['list'] ) {
			echo '<div class="ct-elementor-listings-single">';
				echo '<ul class="ct-elementor-listings-single-property-info">';
				foreach ( $settings['list'] as $item ) {

						if($item['item_content'] == 'property_type' && !empty($ct_property_type)) {
							echo '<li class="elementor-repeater-item-' . $item['_id'] . ' row">';
								echo '<span class="left">' . $item['item_title'] . '</span>';
								echo '<span class="right">' . esc_html($ct_property_type) . '</span>';
							echo '</li>';
						} elseif($item['item_content'] == 'beds' && !empty($ct_beds)) {
							echo '<li class="elementor-repeater-item-' . $item['_id'] . ' row">';
								echo '<span class="left">' . $item['item_title'] . '</span>';
								echo '<span class="right">' . esc_html($ct_beds) . '</span>';
							echo '</li>';
						} elseif($item['item_content'] == 'baths' && !empty($ct_baths)) {
							echo '<li class="elementor-repeater-item-' . $item['_id'] . ' row">';
								echo '<span class="left">' . $item['item_title'] . '</span>';
								echo '<span class="right">' . esc_html($ct_baths) . '</span>';
							echo '</li>';
						} elseif($item['item_content'] == 'sqft' && !empty($ct_sqft)) {
							echo '<li class="elementor-repeater-item-' . $item['_id'] . ' row">';
								echo '<span class="left">' . $item['item_title'] . '</span>';
								echo '<span class="right">' . esc_html($ct_sqft_output) . '</span>';
							echo '</li>';
						} elseif($item['item_content'] == 'priceper_sqft' && !empty($ct_price_per_sqft)) {
							echo '<li class="elementor-repeater-item-' . $item['_id'] . ' row">';
								echo '<span class="left">' . $item['item_title'] . '</span>';
								echo '<span class="right">' . esc_html($ct_price_per_sqft) . '</span>';
							echo '</li>';
						} elseif($item['item_content'] == 'lot_size' && !empty($ct_lotsize)) {
							echo '<li class="elementor-repeater-item-' . $item['_id'] . ' row">';
								echo '<span class="left">' . $item['item_title'] . '</span>';
								echo '<span class="right">' . esc_html($ct_lotsize) . '</span>';
							echo '</li>';
						} elseif($item['item_content'] == 'walkscore' && !empty($ct_walkscore)) {
							echo '<li class="elementor-repeater-item-' . $item['_id'] . ' row">';
								echo '<span class="left">' . $item['item_title'] . '</span>';
								echo '<span class="right">' . esc_html($ct_walkscore) . '</span>';
							echo '</li>';
						} elseif($item['item_content'] == 'community' && !empty($ct_community)) {
							echo '<li class="elementor-repeater-item-' . $item['_id'] . ' row">';
								echo '<span class="left">' . $item['item_title'] . '</span>';
								echo '<span class="right">' . esc_html($ct_community) . '</span>';
							echo '</li>';
						} elseif($item['item_content'] == 'pets' && !empty($ct_pets)) {
							echo '<li class="elementor-repeater-item-' . $item['_id'] . ' row">';
								echo '<span class="left">' . $item['item_title'] . '</span>';
								echo '<span class="right">' . esc_html($ct_pets) . '</span>';
							echo '</li>';
						} elseif($item['item_content'] == 'parking' && !empty($ct_parking)) {
							echo '<li class="elementor-repeater-item-' . $item['_id'] . ' row">';
								echo '<span class="left">' . $item['item_title'] . '</span>';
								echo '<span class="right">' . esc_html($ct_parking) . '</span>';
							echo '</li>';
						} elseif($item['item_content'] == 'prop_id' && !empty($ct_property_id)) {
							echo '<li class="elementor-repeater-item-' . $item['_id'] . ' row">';
								echo '<span class="left">' . $item['item_title'] . '</span>';
								echo '<span class="right">' . esc_html($ct_property_id) . '</span>';
							echo '</li>';
						}
					
				}
				echo '</ul>';
			echo '</div>';
		}

	}

}
