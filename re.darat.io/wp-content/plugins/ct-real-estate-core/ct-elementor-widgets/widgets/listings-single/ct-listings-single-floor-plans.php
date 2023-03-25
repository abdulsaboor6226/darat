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
class CT_Listings_Single_Floor_Plans extends Widget_Base {

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
		return 'ct-listings-single-floor-plans';
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
		return __( 'CT Floor Plans', 'contempo' );
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
					'default' => __( 'Floor Plans & Pricing', 'contempo' ),
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
						'{{WRAPPER}} .ct-elementor-listings-single-floor-plans' => 'text-align: {{VALUE}}',
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
					'selector' => '{{WRAPPER}} #listing-floor-plans',
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
						'{{WRAPPER}} #listing-floor-plans' => 'color: {{VALUE}}',
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
						'{{WRAPPER}} #listing-floor-plans' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} #listing-floor-plans',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} #listing-floor-plans' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

	    echo '<!-- Multi Floor Plan -->';
	    echo '<div id="listing-plans">';
	        $ct_floor_entries = get_post_meta( get_the_ID(), '_ct_multiplan', true );
	        $ct_currency_placement = isset( $ct_options['ct_currency_placement'] ) ? esc_html( $ct_options['ct_currency_placement'] ) : '';

	        if($ct_floor_entries != '') {

	            if($ct_single_listing_content_layout_type == 'accordion') {
					echo '<' . $settings['html_tag'] . ' id="listing-floor-plans" class="info-toggle">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>';
				} else {
					echo '<' . $settings['html_tag'] . ' id="listing-floor-plans">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>';
				}

	            echo '<div class="info-inner">';
	                echo '<table id="multi-floor-plan">';

	                    echo '<thead>';
	                        echo '<th>';
	                            echo __('Name', 'contempo');
	                        echo '</th>';
	                        if(ct_has_type('commercial') || ct_has_type('industrial') || ct_has_type('retail') || ct_has_type('lot') || ct_has_type('land')) { 
	                           // Dont display beds/baths
	                        } else {
	                             echo '<th>';
	                                echo __('Beds', 'contempo');
	                            echo '</th>';
	                            echo '<th>';
	                                echo __('Baths', 'contempo');
	                            echo '</th>';
	                        }
	                        echo '<th>';
	                            echo __('Size', 'contempo');
	                        echo '</th>';
	                        echo '<th>';
	                            echo __('Price', 'contempo');
	                        echo '</th>';
	                        echo '<th>';
	                            echo __('Availability', 'contempo');
	                        echo '</th>';
	                        echo '<th>';
	                            echo esc_html('&nbsp;');
	                        echo '</th>';
	                    echo '</thead>';

	                    $i = 0;

	                    foreach ( (array) $ct_floor_entries as $key => $entry ) {

	                        $ct_plan_img = $ct_plan_title = $ct_plan_beds = $ct_plan_baths = $ct_plan_size = $ct_plan_price = $ct_plan_desc = '';

	                        if ( isset( $entry['_ct_plan_title'] ) )
	                            $ct_plan_title = esc_html( $entry['_ct_plan_title'] );

	                        if(ct_has_type('commercial') || ct_has_type('industrial') || ct_has_type('retail') || ct_has_type('lot') || ct_has_type('land')) { 
	                           // Dont display beds/baths
	                        } else {
	                            if ( isset( $entry['_ct_plan_beds'] ) )
	                                $ct_plan_beds = esc_html( $entry['_ct_plan_beds'] );

	                            if ( isset( $entry['_ct_plan_baths'] ) )
	                                $ct_plan_baths = esc_html( $entry['_ct_plan_baths'] );
	                        }

	                        if ( isset( $entry['_ct_plan_size'] ) )
	                            $ct_plan_size = esc_html( $entry['_ct_plan_size'] );

	                        if ( isset( $entry['_ct_plan_price'] ) )
	                            $ct_plan_price = esc_html( $entry['_ct_plan_price'] );
	                            $ct_plan_price= preg_replace('/[\$,]/', '', $ct_plan_price);

	                        if ( isset( $entry['_ct_plan_availability'] ) )
	                            $ct_plan_availability = $entry['_ct_plan_availability'];

	                        if ( isset( $entry['_ct_plan_image'] ) ) {
	                            $ct_plan_image = $entry['_ct_plan_image'];
	                        }

	                        if ( isset( $entry['_ct_floor_plan_gallery'] ) ) {
	                            $ct_floor_plan_gallery = $entry['_ct_floor_plan_gallery'];
	                        }

	                        ?>

	                        <?php if ( $ct_floor_plan_gallery != '' ) { ?>
	                            <script>
	                            jQuery(document).ready(function() {
	                                jQuery('.gallery-item-<?php echo esc_html($i); ?>').magnificPopup({
	                                    items: [
	                                    <?php
	                                        foreach ( (array) $ct_floor_plan_gallery as $attachment_id => $attachement_url ) {
	                                            $ct_img_alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true);
	                                            echo '{';
	                                                echo "src: '" . wp_get_attachment_url( $attachment_id, 'large' ) . "',";
	                                                echo "title: '" . $ct_img_alt . "'";
	                                            echo '},';
	                                        }
	                                    ?>
	                                    ],
	                                    gallery:{
	                                        enabled: true
	                                    },
	                                    type: 'image'
	                                });
	                            });
	                            </script>
	                        <?php } ?>

	                        <?php

	                        echo '<tr>';
	                            echo '<td>';
	                                if($ct_floor_plan_gallery != '') {
	                                    echo '<a class="floorplans gallery-item-' . esc_html($i) . '">';
	                                        echo esc_html($ct_plan_title);
	                                    echo '</a>';
	                                } elseif($ct_plan_image != '') {
	                                    echo '<a href="' . $ct_plan_image . '" class="floorplans gallery-item">';
	                                        echo esc_html($ct_plan_title);
	                                    echo '</a>';
	                                } else {
	                                    echo esc_html($ct_plan_title);
	                                }
	                            echo '</td>';
	                            if(ct_has_type('commercial') || ct_has_type('industrial') || ct_has_type('retail') || ct_has_type('lot') || ct_has_type('land')) { 
	                               // Dont display beds/baths
	                            } else {
	                                echo '<td>';
	                                    echo esc_html($ct_plan_beds);
	                                echo '</td>';
	                                echo '<td>';
	                                    echo esc_html($ct_plan_baths);
	                                echo '</td>';
	                            }
	                            echo '<td>';
	                                echo esc_html($ct_plan_size);
	                            echo '</td>';
	                            echo '<td>';
	                                if($ct_currency_placement == 'after') {
	                                    echo esc_html($ct_plan_price);
	                                    ct_currency();
	                                } else {
	                                    ct_currency();
	                                    echo esc_html($ct_plan_price);
	                                }
	                            echo '</td>';
	                            echo '<td>';
	                                echo esc_html($ct_plan_availability);
	                            echo '</td>';
	                                if($ct_floor_plan_gallery != '') {
	                                    echo '<td>';
	                                        echo '<a class="btn gallery-item-' . esc_html($i) . '">';
	                                            echo __('View', 'contempo');
	                                        echo '</a>';
	                                    echo '</td>';
	                                } elseif($ct_plan_image != '') {
	                                    echo '<td>';
	                                        echo '<a class="btn gallery-item" href="' . $ct_plan_image . '">';
	                                            echo __('View', 'contempo');
	                                        echo '</a>';
	                                    echo '</td>';
	                                }
	                        echo '</tr>';

	                        $i++;
	                    }

	                echo '</table>';
	            echo '</div>';

	        }
	    echo '</div>';
	    echo '<!-- //Multi Floor Plan -->';
	}

}
