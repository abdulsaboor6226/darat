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
class CT_Listings_Single_Whats_Nearby extends Widget_Base {

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
		return 'ct-listings-single-whats-nearby';
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
		return __( 'CT What\'s Nearby', 'contempo' );
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
		return 'eicon-post-list';
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
				'html_tag',
				[
					'label' => __( 'Title HTML Tag', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'h4',
					'options' => [
						'h1'  => __( 'H1', 'contempo' ),
						'h2' => __( 'H2', 'contempo' ),
						'h3' => __( 'H3', 'contempo' ),
						'h4' => __( 'H4', 'contempo' ),
						'h5' => __( 'H5', 'contempo' ),
						'h6' => __( 'H6', 'contempo' ),
						'div' => __( 'div', 'contempo' ),
						'span' => __( 'span', 'contempo' ),
						'p' => __( 'p', 'contempo' ),
					],
				]
			);

			$this->add_control(
				'widget_title',
				[
					'label' => __( 'Title', 'contempo' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'What\'s Nearby', 'contempo' ),
					'placeholder' => __( 'Type your title here', 'contempo' ),
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
						'{{WRAPPER}} .ct-elementor-listings-single-whats-nearby' => 'text-align: {{VALUE}}',
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
						'banks' => __( 'Banks', 'contempo' ),
						'bars'  => __( 'Bars', 'contempo' ),
						'coffee_shop' => __( 'Coffee Shops', 'contempo' ),
						'convenience_store' => __( 'Convenience', 'contempo' ),
						'gas_station' => __( 'Gas Stations', 'contempo' ),
						'grocery' => __( 'Grocery', 'contempo' ),
						'hospitals' => __( 'Hospitals', 'contempo' ),
						'park' => __( 'Park', 'contempo' ),
						'pet_store' => __( 'Pet Store', 'contempo' ),
						'restaurant' => __( 'Restaurants', 'contempo' ),
						'schools' => __( 'Education', 'contempo' ),
						'shopping_mall' => __( 'Shopping Malls', 'contempo' ),
						'store' => __( 'Stores', 'contempo' ),
						'transit_station' => __( 'Transit Stations', 'contempo' ),
						'veterinary_care' => __( 'Veterinary Care', 'contempo' ),
					],
				]
			);

			$repeater->add_control(
				'icon_bg_color',
				[
					'label' => __( 'Icon Background Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'default' => '#03b5c3',
				]
			);

			$repeater->add_control(
				'item_icon',
				[
					'label' => __( 'Icon', 'contempo' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'fas fa-map-marker-alt',
						'library' => 'solid',
					],
				]
			);

			$this->add_control(
				'list',
				[
					'label' => __( 'What\'s Nearby Items', 'contempo' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [
						[
							'item_title' => __( 'Banks', 'contempo' ),
							'item_content' => 'banks',
						],
						[
							'item_title' => __( 'Bars', 'contempo' ),
							'item_content' => 'bars',
						],
						[
							'item_title' => __( 'Coffee Shops', 'contempo' ),
							'item_content' => 'coffee_shop',
						],
						[
							'item_title' => __( 'Convenience', 'contempo' ),
							'item_content' => 'convenience_store',
						],
						[
							'item_title' => __( 'Gas Stations', 'contempo' ),
							'item_content' => 'gas_station',
						],
						[
							'item_title' => __( 'Hospitals', 'contempo' ),
							'item_content' => 'hospitals',
						],
						[
							'item_title' => __( 'Park', 'contempo' ),
							'item_content' => 'park',
						],
						[
							'item_title' => __( 'Restaurants', 'contempo' ),
							'item_content' => 'restaurant',
						],
					],
					'title_field' => '{{{ item_title }}}',
				]
			);

			$this->add_control(
				'number_of_results',
				[
					'label' => __( 'Number of Results', 'contempo' ),
					'description' => __( 'The number of location results displayed per info item.', 'contempo' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 10,
					'step' => 1,
					'default' => 3,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_section',
			[
				'label' => __( 'Title', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} #listing-nearby-heading',
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
				'text_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} #listing-nearby-heading' => 'color: {{VALUE}}',
					],
					'default' => '#121212',
				]
			);

			$this->add_responsive_control(
				'title_margin',
				[
					'label' => __( 'Margin', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} #listing-nearby-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '0',
						'right' => '0',
						'bottom' => '20',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => false,
					]
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'title_border',
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
					'selector' => '{{WRAPPER}} #listing-nearby-heading',
				]
			);

			$this->add_control(
				'title_border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} #listing-nearby-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => __( 'Item Icon', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_responsive_control(
				'icon_size',
				[
					'label' => __( 'Size', 'contempo' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						],
						'em' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 17,
					],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} .item-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);


			$this->add_control(
				'icon_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .item-icon' => 'color: {{VALUE}}',
					],
					'default' => '#ffffff',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'item_icon_border',
					'label' => __( 'Border', 'contempo' ),
					'selector' => '{{WRAPPER}} .item-icon',
				]
			);

			$this->add_control(
				'item_icon_border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .item-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '3',
						'right' => '3',
						'bottom' => '3',
						'left' => '3',
						'unit' => 'px',
						'isLinked' => true,
					]
				]
			);

			$this->add_responsive_control(
				'icon_margin',
				[
					'label' => __( 'Margin', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} .item-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '0',
						'right' => '8',
						'bottom' => '0',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => true,
					]
				]
			);

			$this->add_responsive_control(
				'icon_padding',
				[
					'label' => __( 'Padding', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} .item-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '5',
						'right' => '8',
						'bottom' => '5',
						'left' => '8',
						'unit' => 'px',
						'isLinked' => false,
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_title_style_section',
			[
				'label' => __( 'Item Title', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'item_title_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} h5',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 17, 'unit' => 'px']],
			            'font_weight' => ['default' => 600],
			            'line_height' => ['default' => ['size' => 2, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'item_title_text_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} h5' => 'color: {{VALUE}}',
					],
					'default' => '#121212',
				]
			);

			$this->add_responsive_control(
				'item_title_margin',
				[
					'label' => __( 'Margin', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} #listing-nearby-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '0',
						'right' => '0',
						'bottom' => '20',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => false,
					]
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'item_title_border',
					'label' => __( 'Border', 'contempo' ),
					'selector' => '{{WRAPPER}} h5',
				]
			);

			$this->add_control(
				'item_title_border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} h5' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->start_controls_section(
			'item_content_style_section',
			[
				'label' => __( 'Item Content', 'contempo' ),
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
				'item_text_color',
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
					'name' => 'item_border',
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
				'item_border_radius',
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

		$ct_single_listing_content_layout_type = isset( $ct_options['ct_single_listing_content_layout_type'] ) ? $ct_options['ct_single_listing_content_layout_type'] : '';
		$ct_yelp_api_key = isset( $ct_options['ct_yelp_api_key'] ) ? esc_html( $ct_options['ct_yelp_api_key'] ) : '';
		$ct_yelp_limit = $settings['number_of_results'];

		do_action('before_single_listing_yelp');
            
		if(!empty($ct_yelp_api_key)) {

		    echo '<!-- Nearby -->';
		    echo '<div class="ct-elementor-listings-single">';
			    echo '<div class="listing-nearby" id="listing-nearby">';
			        if($ct_single_listing_content_layout_type == 'accordion') {
			            echo '<div class="right yelp-powered-by yelp-powered-by-toggle"><small class="muted left">' . __('powered by ', 'contempo') . '</small><img class="right yelp-logo" src="' . get_template_directory_uri() . '/images/yelp-logo-small.png" srcset="' . ct_theme_directory_uri() . '/images/yelp-logo-small@2x.png 2x" height="25" width="50" /></div>';
			            echo '<' . $settings['html_tag'] . ' id="listing-nearby-heading" class="info-toggle">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>';
			        } else {
			            echo '<div class="right yelp-powered-by"><small class="muted left">' . __('powered by ', 'contempo') . '</small><img class="right yelp-logo" src="' . get_template_directory_uri() . '/images/yelp-logo-small.png" srcset="' . ct_theme_directory_uri() . '/images/yelp-logo-small@2x.png 2x" height="25" width="50" /></div>';
			            echo '<' . $settings['html_tag'] . ' id="listing-nearby-heading">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>';
			        }
			        
			        $ct_listing_street_address = get_the_title( ct_return_listing_id_elementor($attributes) );
			        $ct_listing_city = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'city', '', ', ', '' ) );
			        $ct_listing_state = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'state', '', ', ', '' ) );
			        $ct_listing_zip = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'zipcode', '', ', ', '' ) );

			        $ct_listing_address = $ct_listing_street_address . ', ' . $ct_listing_city . ', ' . $ct_listing_state . ', ' . $ct_listing_zip;

			        echo '<div class="info-inner">';

				        if ( $settings['list'] ) {
				        	foreach ( $settings['list'] as $item ) {
				        		echo '<h5 class="elementor-whats-nearby-item-' . $item['_id'] . '"><span class="item-icon elementor-whats-nearby-item-icon-' . $item['_id'] . '" style="background-color: ' . $item['icon_bg_color'] . '">';
			        				\Elementor\Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] );
			        			echo '</span> ' . $item['item_title'] . '</h5>';
			                    ct_query_yelp_api( $item['item_content'], $ct_listing_address, $ct_yelp_limit );
				        	}
				        }

			        echo '</div>';

			    echo '</div>';
			echo '</div>';
		    echo '<!-- // Nearby -->';
		} else {
		    echo '<div class="nomatches">';
		        echo '<h5>' . __('You need to setup the Yelp Fusion API.', 'contempo') . '</h5>';
		        echo '<p class="marB0">' . __('Go into Admin > Real Estate 7 Options > What\'s Nearby? > Create App', 'contempo') . '</p>';
		    echo '</div>';
		}

	}

}
