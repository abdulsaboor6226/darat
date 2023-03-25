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
class CT_City_Links extends Widget_Base {

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
		return 'ct-city-links';
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
		return __( 'CT City Links', 'contempo' );
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
		return 'eicon-columns';
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
		return [ 'ct-real-estate-7' ];
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
			'columns',
			[
				'label' => __( 'Columns', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'span_12' => __( '1', 'contempo' ),
					'span_6' => __( '2', 'contempo' ),
					'span_4' => __( '3', 'contempo' ),
					'span_3' => __( '4', 'contempo' ),
					'span_2' => __( '6', 'contempo' ),
				],
				'default' => 'span_3',
			]
		);

		$this->add_control(
			'number_per_column',
			[
				'label' => __( 'Number Per Column', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '6', 'contempo'),
				'default' => '6',
				'description' => __( 'Enter the number to show per column.', 'contempo'),
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label' => __( 'Hide Empty?', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'yes' => __( 'Yes', 'contempo' ),
					'no' => __( 'No', 'contempo' ),
				],
				'description' => __( 'Select whether you\'d like to hide cities with no data or not.', 'contempo'),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Link Color', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'dark' => __( 'Dark', 'contempo' ),
					'light' => __( 'Light', 'contempo' ),
				],
				'default' => 'dark',
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

		$count = 0;

		if($settings['hide_empty'] == 'no') {
			$ct_cities = get_terms(array(
			    'taxonomy' => 'city',
			    'hide_empty' => false
			));
		} else {
			$ct_cities = get_terms('city');
		}

		echo '<div class="city-links col ' . $settings['columns'] . ' ' . $settings['link_color'] . '">';
			echo '<ul>';
				foreach ($ct_cities as $taxindex => $taxitem) {
					echo '<li><a href="' . home_url() . '/?ct_city=' . $taxitem->name . '&search-listings=true">' . $taxitem->name . '</a></li>';
					$count++;
					if($count == $settings['number_per_column']) {
						echo '</ul></div><div class="city-links col ' . $settings['columns'] . ' ' . $settings['link_color'] . '"><ul>';
						$count = 0;
					}
				}
			echo '</ul>';
		echo '</div>';
			echo '<div class="clear"></div>';

	}

}
