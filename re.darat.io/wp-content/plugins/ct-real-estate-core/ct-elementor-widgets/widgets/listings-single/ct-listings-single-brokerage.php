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
class CT_Listings_Single_Brokerage extends Widget_Base {

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
		return 'ct-listings-single-brokerage';
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
		return __( 'CT Brokerage', 'contempo' );
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
		return 'eicon-text-field';
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
						'{{WRAPPER}} .ct-elementor-listings-single-brokerage' => 'text-align: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'offered_by_section',
			[
				'label' => __( 'Offered By', 'contempo' ),
			]
		);

			$this->add_control(
				'offered_by_show',
				[
					'label' => __( 'Display', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'yes' => __( 'Show', 'contempo' ),
					'no' => __( 'Hide', 'contempo' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'offered_by_label',
				[
					'label' => __( 'Label', 'contempo' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Offered By', 'contempo' ),
					'placeholder' => __( 'Type your label here', 'contempo' ),
				]
			);

			$this->add_control(
				'offered_by_html_tag',
				[
					'label' => __( 'HTML Tag', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'p',
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

		$this->end_controls_section();

		$this->start_controls_section(
			'brokerage_name_section',
			[
				'label' => __( 'Brokerage Name', 'contempo' ),
			]
		);

			$this->add_control(
				'brokerage_name_html_tag',
				[
					'label' => __( 'HTML Tag', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'p',
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

		$this->end_controls_section();

		$this->start_controls_section(
			'offered_by_style_section',
			[
				'label' => __( 'Offered By', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'offered_by_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .offered-by',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 17]],
			            'font_weight' => ['default' => 400],
			            'line_height' => ['default' => ['size' => 1.2, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'offered_by_text_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .offered-by' => 'color: {{VALUE}}',
					],
					'default' => '#75797f',
				]
			);

			$this->add_responsive_control(
				'offered_by_margin',
				[
					'label' => __( 'Margin', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} .offered-by' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '0',
						'right' => '0',
						'bottom' => '5',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => false,
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'brokerage_name_style_section',
			[
				'label' => __( 'Brokerage Name', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'brokerage_name_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .brokerage-name',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 17]],
			            'font_weight' => ['default' => 600],
			            'line_height' => ['default' => ['size' => 1.2, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'brokerage_name_text_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .brokerage-name' => 'color: {{VALUE}}',
					],
					'default' => '#121212',
				]
			);

			$this->add_responsive_control(
				'brokerage_name_margin',
				[
					'label' => __( 'Margin', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} .brokerage-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$settings = $this->get_settings_for_display();
		$attributes['is_elementor'] = 1;

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $attributes['is_elementor_edit'] = 1;
        }

		$source = get_post_meta( ct_return_listing_id_elementor($attributes), 'source', true);

		$ct_user_meta_brokerage = get_the_author_meta( 'brokerage' );
		$ct_cpt_brokerage = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_brokerage', true );

		echo '<div class="ct-elementor-listings-single-brokerage">';
			if ( $ct_cpt_brokerage != 0 ) {

				$brokerage = new \WP_Query(array(
		            'post_type' => 'brokerage',
		            'p' => $ct_cpt_brokerage,
		            'nopaging' => true
		        ));

		        if ( $brokerage->have_posts() ) : while ( $brokerage->have_posts() ) : $brokerage->the_post();
		            
		            $ct_brokerage_permalink = get_permalink();
	            	$ct_brokerage_name = strtolower(get_the_title());
		        
		        endwhile; endif; wp_reset_postdata();

		        	if ($settings['offered_by_show'] == 'yes' ) {
						echo '<' . $settings['offered_by_html_tag'] . ' class="offered-by">';
							echo esc_html($settings['offered_by_label']);
						echo '</' . $settings['offered_by_html_tag'] . '>';
					}

					if($source == 'idx-api') {
						echo '<' . $settings['brokerage_name_html_tag'] . ' class="brokerage-name">';
							echo ucwords($ct_brokerage_name);
						echo '</' . $settings['brokerage_name_html_tag'] . '>';
					} else {
						echo '<' . $settings['brokerage_name_html_tag'] . ' class="brokerage-name">';
							echo '<a href="' . esc_url($ct_brokerage_permalink) . '">' . ucwords($ct_brokerage_name) . '</a>';
						echo '</' . $settings['brokerage_name_html_tag'] . '>';
					}

			} elseif ( $ct_user_meta_brokerage != '' ) {

				if ($settings['offered_by_show'] == 'yes' ) {
					echo '<' . $settings['offered_by_html_tag'] . ' class="offered-by">';
						echo esc_html($settings['offered_by_label']);
					echo '</' . $settings['offered_by_html_tag'] . '>';
				}

				echo '<' . $settings['brokerage_name_html_tag'] . ' class="brokerage-name">';
					echo '<a href="' . esc_url($ct_brokerage_permalink) . '">' . ucwords(the_author_meta( 'brokerage' )) . '</a>';
				echo '</' . $settings['brokerage_name_html_tag'] . '>';

			}
		echo '</div>';


	}

}
