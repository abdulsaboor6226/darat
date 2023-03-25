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
class CT_Listings_Single_Lead_Media extends Widget_Base {

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
		return 'ct-listings-single-lead-media';
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
		return __( 'CT Lead Media', 'contempo' );
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
		return 'eicon-photo-library';
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
				'lead_media_type',
				[
					'label' => __( 'Type', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'gallery-modal-w-contact',
					'options' => [
						'slider-w-carousel'  => __( 'Slider w/Carousel', 'contempo' ),
						'large-carousel' => __( 'Large Carousel', 'contempo' ),
						'slider-w-contact' => __( 'Slider w/Contact', 'contempo' ),
						'gallery-modal-w-contact' => __( 'Gallery Modal w/Contact', 'contempo' ),
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'background_section',
			[
				'label' => __( 'Background', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'background',
					'label' => __( 'Background', 'contempo' ),
					'types' => [ 'classic', 'gradient', 'video' ],
					'selector' => '{{WRAPPER}} .ct-elementor-listings-single-lead-media',
				]
			);

			$this->add_responsive_control(
				'background_padding',
				[
					'label' => __( 'Padding', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-lead-media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '5',
						'right' => '5',
						'bottom' => '5',
						'left' => '5',
						'unit' => 'px',
						'isLinked' => true,
					]
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
					'selector' => '{{WRAPPER}} .ct-elementor-listings-single-lead-media',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-lead-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$settings = $this->get_settings_for_display();

		echo '<div class="ct-elementor-listings-single">';
			echo '<div class="ct-elementor-listings-single-lead-media">';
				if ( 'slider-w-carousel' === $settings['lead_media_type'] ) {
					if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
						echo '<div class="ct-notice">' . __('Slider w/Carousel content will be rendered in frontend.', 'contempo') . '</div>';
					} else {
						include_once ( WP_PLUGIN_DIR . '/ct-real-estate-core/ct-elementor-widgets/includes/single-listing/single-listing-lead-media.php');
					}
				} elseif ( 'large-carousel' === $settings['lead_media_type'] ) {
					if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
						echo '<div class="ct-notice">' . __('Large Carousel content will be rendered in frontend.', 'contempo') . '</div>';
					} else {
						include_once ( WP_PLUGIN_DIR . '/ct-real-estate-core/ct-elementor-widgets/includes/single-listing/single-listing-lead-media-lrg-carousel.php');
					}
				} elseif ( 'slider-w-contact' === $settings['lead_media_type'] ) {
					echo '<div id="listings-four-slider">';
						include_once ( WP_PLUGIN_DIR . '/ct-real-estate-core/ct-elementor-widgets/includes/single-listing/single-listing-lead-media-with-contact.php');
					echo '</div>';
				} elseif ( 'gallery-modal-w-contact' === $settings['lead_media_type'] ) {
					echo '<div id="listings-five-gallery">';
						include_once ( WP_PLUGIN_DIR . '/ct-real-estate-core/ct-elementor-widgets/includes/single-listing/single-listing-lead-media-with-contact-gallery-modal.php');
					echo '<div>';
				}
			echo '</div>';	
	    echo '</div>';
	}

}
