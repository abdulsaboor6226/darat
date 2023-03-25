<?php
namespace CT_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * CT Six Item Grid
 *
 * Elementor widget for listings minimal grid style.
 *
 * @since 1.0.0
 */
class CT_Four_Item_Grid extends Widget_Base {

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
		return 'ct-four-item-grid';
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
		return __( 'CT 4 Item Grid', 'contempo' );
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
		return 'eicon-gallery-grid';
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
			'style',
			[
				'label' => __( 'Style', 'contempo' ),
			]
		);

		$this->add_control(
			'text_alignment',
			[
				'label' => __( 'Text Alignment', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'grid-info-text-center' => __( 'Center', 'contempo' ),
					'grid-info-text-right' => __( 'Right', 'contempo' ),
					'grid-info-text-left' => __( 'Left', 'contempo' ),
				],
				'default' => 'grid-info-text-center',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_1',
			[
				'label' => __( 'Item 1', 'contempo' ),
			]
		);

		$this->add_control(
			'title_one',
			[
				'label' => __( 'Title', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default title', 'contempo' ),
				'placeholder' => __( 'Type your title here', 'contempo' ),
			]
		);

		$this->add_control(
			'link_one',
			[
				'label' => __( 'Link', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'contempo' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => true,
				],
			]
		);

		$this->add_control(
			'desciption_one',
			[
				'label' => __( 'Description', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __( 'Default description', 'contempo' ),
				'placeholder' => __( 'Type your description here', 'contempo' ),
			]
		);

		$this->add_control(
			'image_one',
			[
				'label' => __( 'Choose Image', 'contempo' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_2',
			[
				'label' => __( 'Item 2', 'contempo' ),
			]
		);

		$this->add_control(
			'title_two',
			[
				'label' => __( 'Title', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default title', 'contempo' ),
				'placeholder' => __( 'Type your title here', 'contempo' ),
			]
		);

		$this->add_control(
			'link_two',
			[
				'label' => __( 'Link', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'contempo' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => true,
				],
			]
		);

		$this->add_control(
			'desciption_two',
			[
				'label' => __( 'Description', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __( 'Default description', 'contempo' ),
				'placeholder' => __( 'Type your description here', 'contempo' ),
			]
		);

		$this->add_control(
			'image_two',
			[
				'label' => __( 'Choose Image', 'contempo' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_3',
			[
				'label' => __( 'Item 3', 'contempo' ),
			]
		);

		$this->add_control(
			'title_three',
			[
				'label' => __( 'Title', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default title', 'contempo' ),
				'placeholder' => __( 'Type your title here', 'contempo' ),
			]
		);

		$this->add_control(
			'link_three',
			[
				'label' => __( 'Link', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'contempo' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => true,
				],
			]
		);

		$this->add_control(
			'desciption_three',
			[
				'label' => __( 'Description', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __( 'Default description', 'contempo' ),
				'placeholder' => __( 'Type your description here', 'contempo' ),
			]
		);

		$this->add_control(
			'image_three',
			[
				'label' => __( 'Choose Image', 'contempo' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item_four',
			[
				'label' => __( 'Item 4', 'contempo' ),
			]
		);

		$this->add_control(
			'title_four',
			[
				'label' => __( 'Title', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Default title', 'contempo' ),
				'placeholder' => __( 'Type your title here', 'contempo' ),
			]
		);

		$this->add_control(
			'link_four',
			[
				'label' => __( 'Link', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'contempo' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => true,
				],
			]
		);

		$this->add_control(
			'desciption_four',
			[
				'label' => __( 'Description', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __( 'Default description', 'contempo' ),
				'placeholder' => __( 'Type your description here', 'contempo' ),
			]
		);

		$this->add_control(
			'image_four',
			[
				'label' => __( 'Choose Image', 'contempo' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
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

		if(empty($settings['title_one']) || empty($settings['title_one']) || empty($settings['link_three']) || empty($settings['link_four'])) {
			$ct_grid_item_class = 'grid-item-no-info';
		} else {
			$ct_grid_item_class = 'grid-item-has-info';
		}

		// Item One Link
		$target_one = $settings['link_one']['is_external'] ? ' target="_blank"' : '	';
		$nofollow_one = $settings['link_one']['nofollow'] ? ' rel="nofollow"' : '';

		// Item Two Link
		$target_two = $settings['link_two']['is_external'] ? ' target="_blank"' : '	';
		$nofollow_two = $settings['link_two']['nofollow'] ? ' rel="nofollow"' : '';

		// Item Three Link
		$target_three = $settings['link_three']['is_external'] ? ' target="_blank"' : '	';
		$nofollow_three = $settings['link_three']['nofollow'] ? ' rel="nofollow"' : '';

		// Item Four Link
		$target_four = $settings['link_four']['is_external'] ? ' target="_blank"' : '	';
		$nofollow_four = $settings['link_four']['nofollow'] ? ' rel="nofollow"' : '';

		echo '<ul class="item-grid grid-four-item ' . $settings['text_alignment'] . '">';
			echo '<div id="grid-four-tall-col" class="col span_4 first">';
					if(!empty($settings['link_one']['url'])) {
						echo '<li class="grid-item ' . $ct_grid_item_class . ' col span_12 first" style="background-image: url(' . $settings['image_one']['url'] . '); background-size: cover;">';
						echo '<a href="' . $settings['link_one']['url'] . '"' . $target_one . $nofollow_one . '>';
					} else {
						echo '<li class="grid-item ' . $ct_grid_item_class . ' col span_12 first no-link" style="background-image: url(' . $settings['image_one']['url'] . '); background-size: cover;">';
					}
						echo '<div class="grid-item-info">';
							echo '<h4>' . $settings['title_one'] . '</h4>';
							echo '<p>' . $settings['desciption_one'] . '</p>';
						echo '</div>';
					if(!empty($settings['link_one']['url'])) {
						echo '</a>';
					}
				echo '</li>';
			echo '</div>';
			echo '<div class="col span_8">';
				echo '<div class="col span_12 first">';
						if(!empty($settings['link_two']['url'])) {
							echo '<li id="grid-item-two" class="grid-item ' . $ct_grid_item_class . '" style="background-image: url(' . $settings['image_two']['url'] . '); background-size: cover;">';
							echo '<a href="' . $settings['link_two']['url'] . '"' . $target_two . $nofollow_two . '>';
						} else {
							echo '<li id="grid-item-two" class="grid-item no-link ' . $ct_grid_item_class . '" style="background-image: url(' . $settings['image_two']['url'] . '); background-size: cover;">';
						}
							echo '<div class="grid-item-info">';
								echo '<h4>' . $settings['title_two'] . '</h4>';
								echo '<p>' . $settings['desciption_two'] . '</p>';
							echo '</div>';
						if(!empty($settings['link_two']['url'])) {
							echo '</a>';
						}
					echo '</li>';
						if(!empty($settings['link_three']['url'])) {
							echo '<li id="grid-item-three" class="grid-item ' . $ct_grid_item_class . ' col span_6 first" style="background-image: url(' . $settings['image_three']['url'] . '); background-size: cover;">';
							echo '<a href="' . $settings['link_three']['url'] . '"' . $target_three . $nofollow_three . '>';
						} else {
							echo '<li id="grid-item-three" class="grid-item no-link ' . $ct_grid_item_class . ' col span_6 first" style="background-image: url(' . $settings['image_three']['url'] . '); background-size: cover;">';
						}
							echo '<div class="grid-item-info">';
								echo '<h4>' . $settings['title_three'] . '</h4>';
								echo '<p>' . $settings['desciption_three'] . '</p>';
							echo '</div>';
						if(!empty($settings['link_three']['url'])) {
							echo '</a>';
						}
					echo '</li>';
						if(!empty($settings['link_four']['url'])) {
							echo '<li id="grid-item-four" class="grid-item ' . $ct_grid_item_class . ' col span_6" style="background-image: url(' . $settings['image_four']['url'] . '); background-size: cover;">';
							echo '<a href="' . $settings['link_four']['url'] . '"' . $target_four . $nofollow_four. '>';
						} else {
							echo '<li id="grid-item-four" class="grid-item no-link ' . $ct_grid_item_class . ' col span_6" style="background-image: url(' . $settings['image_four']['url'] . '); background-size: cover;">';
						}
							echo '<div class="grid-item-info">';
								echo '<h4>' . $settings['title_four'] . '</h4>';
								echo '<p>' . $settings['desciption_four'] . '</p>';
							echo '</div>';
						if(!empty($settings['link_four']['url'])) {
							echo '</a>';
						}
					echo '</li>';
						echo '<div class="clear"></div>';
				echo '</div>';
			echo '</div>';
				echo '<div class="clear"></div>';
		echo '</ul>';	

	}

}
