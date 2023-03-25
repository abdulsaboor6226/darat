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
class CT_Listings_Single_Status extends Widget_Base {

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
		return 'ct-listings-single-status';
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
		return __( 'CT Status', 'contempo' );
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
		return 'eicon-meta-data';
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
					'label' => __( 'HTML Tag', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'div',
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

			$this->add_responsive_control(
				'text_align',
				[
					'label' => __( 'Alignment', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'default' => 'center',
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
						'{{WRAPPER}} .ct-elementor-listings-single' => 'text-align: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Typography', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .ct-elementor-listings-single-status',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 17]],
			            'font_weight' => ['default' => 400],
			            'line_height' => ['default' => ['size' => 2, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'title_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status' => 'color: {{VALUE}}',
					],
					'default' => '#ffffff',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'background_colors_section',
			[
				'label' => __( 'Background Colors', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'default_bg_color',
				[
					'label' => __( 'Default', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status' => 'background-color: {{VALUE}}',
					],
					'default' => '#34495e',
				]
			);

			$this->add_control(
				'for_sale_bg_color',
				[
					'label' => __( 'For Sale', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status.for-sale' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ct-elementor-listings-single-status.active' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ct-elementor-listings-single-status.back-on-market' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ct-elementor-listings-single-status.for-sale' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ct-elementor-listings-single-status.new-listing' => 'background-color: {{VALUE}}',
					],
					'default' => '#34495e',
				]
			);

			$this->add_control(
				'open_house_bg_color',
				[
					'label' => __( 'Open House', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status.open-house' => 'background-color: {{VALUE}}',
					],
					'default' => '#7faf1b',
				]
			);

			$this->add_control(
				'pending_bg_color',
				[
					'label' => __( 'Pending / Contingent', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status.pending' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ct-elementor-listings-single-status.contingent' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ct-elementor-listings-single-status.sale-pending' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ct-elementor-listings-single-status.under-contract' => 'background-color: {{VALUE}}',
					],
					'default' => '#a84848',
				]
			);

			$this->add_control(
				'reduced_bg_color',
				[
					'label' => __( 'Reduced', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status.reduced' => 'background-color: {{VALUE}}',
					],
					'default' => '#bc0000',
				]
			);

			$this->add_control(
				'reo_bank_owened_bg_color',
				[
					'label' => __( 'REO Bank Owned', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status.reo-bank-owned' => 'background-color: {{VALUE}}',
					],
					'default' => '#6aa378',
				]
			);

			$this->add_control(
				'short_sale_bg_color',
				[
					'label' => __( 'Short Sale', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status.short-sale' => 'background-color: {{VALUE}}',
					],
					'default' => '#bc0000',
				]
			);

			$this->add_control(
				'sold_bg_color',
				[
					'label' => __( 'Sold', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status.sold' => 'background-color: {{VALUE}}',
					],
					'default' => '#ff6400',
				]
			);

			$this->add_control(
				'rental_bg_color',
				[
					'label' => __( 'Rental / For Rent', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status.rental' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ct-elementor-listings-single-status.for-rent' => 'background-color: {{VALUE}}',
					],
					'default' => '#0097d6',
				]
			);

			$this->add_control(
				'leased_bg_color',
				[
					'label' => __( 'Leased / Rented', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status.leased' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .ct-elementor-listings-single-status.rented' => 'background-color: {{VALUE}}',
					],
					'default' => '#09f',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'border_section',
			[
				'label' => __( 'Border', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => __( 'Border', 'contempo' ),
					'selector' => '{{WRAPPER}} .ct-elementor-listings-single-status',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-status' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		global $wp_query;

		$settings = $this->get_settings_for_display();
		$attributes['is_elementor'] = 1;

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $attributes['is_elementor_edit'] = 1;
        }

		$text_align = $settings['text_align'];

		$status_tags = strip_tags( get_modified_term_list_slug( ct_return_listing_id_elementor($attributes), 'ct_status', '', ' ', '', array('featured') ) );
		$status_tags_stripped = str_replace('_', ' ', $status_tags);
		$status_tags_stripped = str_replace('-', ' ', $status_tags_stripped);

		if($status_tags != '') {
			echo '<div class="ct-elementor-listings-single">';
				echo '<' . $settings['html_tag'] . ' class="ct-elementor-listings-single-status ' . esc_html($status_tags) . '">';
					echo ucwords($status_tags_stripped);
				echo '</' . $settings['html_tag'] . '>';
			echo '</div>';
		}

	}

}
