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
class CT_Listings_Single_Open_House extends Widget_Base {

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
		return 'ct-listings-single-open-house';
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
		return __( 'CT Open House', 'contempo' );
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
		return 'eicon-table';
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
					'default' => __( 'Open House', 'contempo' ),
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
					'selector' => '{{WRAPPER}} #open-house-info',
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
						'{{WRAPPER}} #open-house-info' => 'color: {{VALUE}}',
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
						'{{WRAPPER}} #open-house-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} #open-house-info',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} #open-house-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} table',
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
						'{{WRAPPER}} table' => 'color: {{VALUE}}',
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

		$post_id = ct_return_listing_id_elementor($attributes);
		$ct_open_house_entries = get_post_meta( $post_id, '_ct_open_house', true );
		$ct_todays_date = date("mdY");
		$ct_open_house_date_formatted = '';

		foreach ( (array) $ct_open_house_entries as $key => $entry ) {

		    $ct_open_house_date = '';

		    if ( isset( $entry['_ct_open_house_date'] ) ) {
		        $ct_open_house_date = esc_html( $entry['_ct_open_house_date'] );
		        $ct_open_house_date_formatted = strftime('%m%d%Y', $ct_open_house_date);
		    }

		    if(isset( $entry['_ct_open_house_date'] ) && $ct_open_house_date_formatted < $ct_todays_date) {
		        ct_update_open_house_status($post_id);
		    }

		}

		if($ct_open_house_date_formatted >= $ct_todays_date && $ct_open_house_entries != '' && $ct_open_house_date != '') {
    	
    		echo '<div class="ct-elementor-listings-single">';
			    echo '<!-- Open House -->';
			    echo '<div id="listing-open-house" class="ct-elementor-listings-single-open-house">';
			        if($ct_single_listing_content_layout_type == 'accordion') {
						echo '<' . $settings['html_tag'] . ' id="open-house-info" class="info-toggle">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>';
					} else {
						echo '<' . $settings['html_tag'] . ' id="open-house-info">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>';
					}

			        echo '<div class="info-inner">';
			            echo '<table id="open-house">';

			                echo '<thead>';
			                    echo '<th>';
			                        echo __('Date', 'contempo');
			                    echo '</th>';
			                    echo '<th>';
			                        echo __('Time', 'contempo');
			                    echo '</th>';
			                    echo '<th>';
			                        echo __('RSVP', 'contempo');
			                    echo '</th>';
			                echo '</thead>';

			                foreach ( (array) $ct_open_house_entries as $key => $entry ) {

			                    $ct_open_house_date = $ct_open_house_start_time = $ct_open_house_end_time = $open_house_rsvp = '';

			                    if ( isset( $entry['_ct_open_house_date'] ) )
			                        $ct_open_house_date = esc_html( $entry['_ct_open_house_date'] );
			                        $ct_open_house_date_formatted = strftime('%m%d%Y', $ct_open_house_date);

			                    if ( isset( $entry['_ct_open_house_start_time'] ) )
			                        $ct_open_house_start_time = esc_html( $entry['_ct_open_house_start_time'] );

			                    if ( isset( $entry['_ct_open_house_end_time'] ) )
			                        $ct_open_house_end_time = esc_html( $entry['_ct_open_house_end_time'] );

			                    if ( isset( $entry['_ct_open_house_rsvp'] ) ) {
			                        $ct_open_house_rsvp = $entry['_ct_open_house_rsvp'];
			                    }

			                    if($ct_open_house_date_formatted >= $ct_todays_date) {

			                        echo '<tr>';
			                            echo '<td>';
			                                if($ct_open_house_date != '') {
			                                    echo strftime('%-m/%d/%Y', $ct_open_house_date);
			                                }
			                            echo '</td>';
			                            echo '<td>';
			                                if($ct_open_house_start_time != '') {
			                                    echo esc_html($ct_open_house_start_time);  
			                                }
			                                if($ct_open_house_end_time != '') {
			                                    echo ' - ';
			                                    echo esc_html($ct_open_house_end_time);  
			                                }
			                            echo '</td>';
			                            echo '<td>';
			                                if($ct_open_house_rsvp == 'yes') {
			                                    echo '<a class="btn" href="#listing-contact">';
			                                        echo __('RSVP', 'contempo');
			                                    echo '</a>';  
			                                } else {
			                                    echo '-';
			                                }
			                            echo '</td>';
			                        echo '</tr>';
			                    }
			                }

			            echo '</table>';
			        echo '</div>';  
			    echo '</div>';
		 	echo '</div>';
		    echo '<!-- //Open House -->';
		}

	}

}
