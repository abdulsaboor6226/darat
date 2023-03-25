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
class CT_Listings_Single_Energy_Efficiency extends Widget_Base {

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
		return 'ct-listings-single-energy-efficiency';
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
		return __( 'CT Energy Efficiency', 'contempo' );
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
		return 'eicon-flash';
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
					'default' => __( 'Energy Efficiency', 'contempo' ),
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
						'{{WRAPPER}} .ct-elementor-listings-single-open-house' => 'text-align: {{VALUE}}',
					],
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
					'selector' => '{{WRAPPER}} #energy-efficiency',
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
						'{{WRAPPER}} #energy-efficiency' => 'color: {{VALUE}}',
					],
					'default' => '#121212',
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
						'{{WRAPPER}} #energy-efficiency' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} #energy-efficiency',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} #energy-efficiency' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'content_section',
			[
				'label' => __( 'Content', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} li',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 17, 'unit' => 'px']],
			            'font_weight' => ['default' => 400],
			            'line_height' => ['default' => ['size' => 1.2, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'content_color',
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
		
		$ct_energy_class = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_energy_class", true);
		$ct_global_energy_performance_index = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_global_energy_performance_index", true);
		$ct_renewable_energy_performance_index = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_renewable_energy_performance_index", true);
		$ct_building_energy_performance = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_building_energy_performance", true);

		do_action('before_single_listing_energy_efficiency');

		if(!empty($ct_energy_class) || !empty($ct_global_energy_performance_index) || !empty($ct_renewable_energy_performance_index) || !empty($ct_building_energy_performance)) {
		    echo '<!-- Energy Efficiency -->';
		    echo '<div class="ct-elementor-listings-single">';
			    echo '<div id="listing-renergy-efficiency">';
			        if($ct_single_listing_content_layout_type == 'accordion') {
						echo '<' . $settings['html_tag'] . ' id="energy-efficiency" class="info-toggle">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>';
					} else {
						echo '<' . $settings['html_tag'] . ' id="energy-efficiency">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>';
					}

			        if( !empty($ct_energy_class) || !empty($ct_global_energy_performance_index) || !empty($ct_renewable_energy_performance_index) || !empty($ct_building_energy_performance) ) {
			            echo '<div class="info-inner">';
			                echo '<ul class="propinfo marB0 pad0">';
			                    if(!empty($ct_energy_class)) {
			                        echo '<li class="row energy-class"><span class="muted left">' . __('Energy Class', 'contempo') . '</span><span class="right">' . $ct_energy_class . '</span></li>';
			                    }
			                    if(!empty($ct_global_energy_performance_index)) {
			                        echo '<li class="row global-energy-performance"><span class="muted left">' . __('Global Energy Performance', 'contempo') . '</span><span class="right">' . $ct_global_energy_performance_index . '</span></li>';
			                    }
			                    if(!empty($ct_renewable_energy_performance_index)) {
			                        echo '<li class="row renewable-energy-performance"><span class="muted left">' . __('Renewable Energy Performance', 'contempo') . '</span><span class="right">' . $ct_renewable_energy_performance_index . '</span></li>';
			                    }
			                    if(!empty($ct_building_energy_performance)) {
			                        echo '<li class="row building-energy-performance"><span class="muted left">' . __('Building Energy Performance', 'contempo') . '</span><span class="right">' . $ct_building_energy_performance . '</span></li>';
			                    }
			                echo '</ul>';
			            echo '</div>';
			        }

			    echo '</div>';
			echo '</div>';
		    echo '<!-- //Energy Efficiency -->';
		}

	}

}
