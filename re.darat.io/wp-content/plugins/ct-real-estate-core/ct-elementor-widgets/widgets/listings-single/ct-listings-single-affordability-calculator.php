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
class CT_Listings_Single_Affordability_Calculator extends Widget_Base {

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
		return 'ct-listings-single-affordability-calculator';
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
		return __( 'CT Affordability Calculator', 'contempo' );
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
		return 'eicon-kit-details';
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
					'default' => __( 'Affordability Calculator', 'contempo' ),
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
						'{{WRAPPER}} .ct-elementor-listings-single-virtual-tour' => 'text-align: {{VALUE}}',
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
					'selector' => '{{WRAPPER}} #listing-affordability-calculator',
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
				'title_text_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} #listing-affordability-calculator' => 'color: {{VALUE}}',
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
						'{{WRAPPER}} #listing-affordability-calculator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

			$this->add_responsive_control(
				'title_padding',
				[
					'label' => __( 'Padding', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} #listing-affordability-calculator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} #listing-affordability-calculator',
				]
			);

			$this->add_control(
				'title_border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} #listing-affordability-calculator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'selector' => '{{WRAPPER}} .ct-elementor-listings-single-affordability-calculator',
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
				'content_text_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-affordability-calculator' => 'color: {{VALUE}}',
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

            echo '<' . $settings['html_tag'] . ' id="listing-affordability-calculator">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>';
            echo '<div class="ct-notice">' . __('Affordability Calculator will be rendered in frontend.', 'contempo') . '</div>';

        } else {

			// Get the listing price visibility.
			$ct_display_listing_price = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_display_listing_price', true );

			// Affordability component enable/disable. Disable by default.
			$is_affordability_component_enabled = apply_filters("ct_is_affordability_component_enabled", __return_false() );

			// Enable the affordability component if the following conditions are true.
			if( ! has_term( array('for-rent', 'rental', 'lease'), 'ct_status', ct_return_listing_id_elementor($attributes) ) && $ct_display_listing_price != 'no') {
			    $is_affordability_component_enabled = __return_true();
			}

			// Do not display the markup when component is disabled.
			if ( ! $is_affordability_component_enabled ) {
			    return;
			}

			// The interest rate.
			$interest_rate = isset( $ct_options['ct_listing_est_payment_interest_rate'] ) ? esc_attr( absint( $ct_options['ct_listing_est_payment_interest_rate'] ) ) : 4.00;; // default.

			// Loan Types.
			$loan_types = array(
				"30|" . esc_attr( $interest_rate ) . "|conventional" => __( "30-year Fixed", "contempo" ),
				"20|" . esc_attr( $interest_rate ) . "|conventional" => __( "20-year Fixed", "contempo" ),
				"15|" . esc_attr( $interest_rate ) . "|conventional" => __( "15-year Fixed", "contempo" ),
				"10|" . esc_attr( $interest_rate ) . "|conventional" => __( "10-year Fixed", "contempo" ),
				"30|" . esc_attr( $interest_rate ) . "|fha"          => __( "FHA 30-year Fixed", "contempo" ),
				"15|" . esc_attr( $interest_rate ) . "|fha"          => __( "FHA 15-year-fixed", "contempo" ),
				"30|" . esc_attr( $interest_rate ) . "|va"           => __( "VA 30-year-fixed", "contempo" ),
				"15|" . esc_attr( $interest_rate ) . "|va"           => __( "VA 15-year-fixed", "contempo" ),
			);
			?>

			<div class="clear"></div>

			<div class="ct-elementor-listings-single">
				<div class="ct-elementor-listings-single-contact ct-affordability-calculator col span_12 first">

				    <div class="ct-affordability-calculator__headlines">
						<?php echo '<' . $settings['html_tag'] . ' id="listing-affordability-calculator">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>'; ?>

				        <div id="loanCalc"></div>
				        <!-- Fake the element block so that it eliminates white space on top when smooth scrolling. -->
				        <h5 class="marB5">
							<?php esc_html_e( "Calculate your monthly mortgage payments", "contempo" ); ?>
				        </h5>
				        <h6>
							<?php esc_html_e( "Your est.payment is: ", "contempo" ); ?>
				            <span id="ct-affordability-calculator-est-payment">&mdash;</span><?php esc_html_e( "/month", "contempo" ); ?>
				        </h6>
				    </div>

				    <form>

				        <div class="ct-affordability-calculator__fields">

				            <!-- Home Price -->
				            <div class="ct-affordability-calculator__fields__item home-price">
				                <div class="ct-affordability-calculator__fields__wrap">
				                    <div class="ct-affordability-calculator-column">
				                        <label for="ct-af-form-field-home-price">
											<?php esc_html_e( "Home Price", "contempo" ); ?>
				                        </label>
				                    </div>
				                    <div class="ct-affordability-calculator-column right">
				                        <input type="text" name="home_price" value="&mdash;" id="ct-af-form-field-home-price"
				                               autocomplete="off"/>
				                    </div>
				                </div>
				                <div class="ct-af-form-field-slider">
				                    <div id="ct-af-form-field-listing-price"></div>
				                </div>
				            </div>

				            <!-- Down Payment -->
				            <div class="ct-affordability-calculator__fields__item downpayment">
				                <div class="ct-affordability-calculator__fields__wrap">
				                    <div class="ct-affordability-calculator-column">
				                        <label for="ct-af-form-field-down-payment">
											<?php esc_html_e( "Down Payment", "contempo" ); ?>
				                        </label>
				                    </div>
				                    <div class="ct-affordability-calculator-column right">
				                        <div class="ct-af-form-group-field">
				                            <!-- figure -->
				                            <div class="ct-af-form-group-field__item">
				                                <input autocomplete="off" type="text" name="downpayment_value" value="&mdash;"
				                                       id="ct-af-form-field-downpayment"/>
				                                <!-- percentage -->
				                                <span class="percentage-container">
				                                    <input autocomplete="off" type="text" maxlength="2" name="downpayment_percentage"
				                                           value="&mdash;" id="ct-af-form-field-downpayment-percentage"/>
				                                    <span id="downpayment_percentage_symbol">%</span>
				                                </span>
				                            </div>
				                        </div>
				                    </div>
				                    <div class="ct-af-form-field-slider">
				                        <div id="ct-af-form-field-downpayment-slider"></div>
				                    </div>
				                </div>
				            </div>

				            <!--Interest Rate -->
				            <div class="ct-affordability-calculator__fields__item interest-rate">
				                <div class="ct-affordability-calculator__fields__wrap">
				                    <div class="ct-affordability-calculator-column">
				                        <label for="ct-af-form-field-interest-rate">
											<?php esc_html_e( "Interest Rate", "contempo" ); ?>
				                        </label>
				                    </div>
				                    <div class="ct-affordability-calculator-column right">
				                        <span class="percentage-container">
				                            <input type="text" maxlength="6" name="home_interest_rate" value="&mdash;"
				                                   id="ct-af-form-field-interest-rate" autocomplete="off"/>
				                            <span id="interest_rate_percentage">%</span>
				                        </span>
				                    </div>
				                    <div class="ct-af-form-field-slider">
				                        <div id="ct-af-form-field-downpayment-interest-rate"></div>
				                    </div>
				                </div>
				            </div>

				            <!-- Loan Type -->
				            <div class="ct-affordability-calculator__fields__item loan-type">
				                <div class="ct-affordability-calculator__fields__wrap">
				                    <label for="ct-af-form-field-loan-type">
										<?php esc_html_e( "Loan Type", "contempo" ); ?>
				                    </label>
				                    <select name="loan-type" id="ct-af-form-field-loan-type">
										<?php foreach ( $loan_types as $key => $value ): ?>
				                            <option value="<?php echo esc_attr( $key ); ?>">
												<?php echo esc_html( $value ); ?>
				                            </option>
										<?php endforeach; ?>
				                    </select>
				                </div>
				            </div>
				        </div>

				    </form>

				    <div id="ct-affordability-calculator-result">
				        <div class="ct-affordability-calculator-donut-chart">
				            <canvas id="ct-affordability-calculator-chart" width="250" height="250"></canvas>
				            <div id="donut-chart-label">
				                <span id="donut-chart-label-figure"> &mdash; </span>
				                <span id="donut-chart-label-postfix"> <?php esc_html_e( "/Month", "contempo" ); ?> </span>

				            </div>
				        </div>
				        <div class="ct-affordability-calculator-details">
				            <ul>
				                <li>
				                    <div class="label-ct-af-calc"><?php esc_html_e( "Principal & Interest", "contempo" ); ?></div>
				                    <div class="result-ct-af-calc" id="ct-af-calc-result-principal-interest">&mdash;</div>
				                </li>
				                <li>
				                    <div class="label-ct-af-calc"><?php esc_html_e( "Property Taxes", "contempo" ); ?></div>
				                    <div class="result-ct-af-calc" id="ct-af-calc-result-property-taxes">&mdash;</div>
				                </li>
				                <li>
				                    <div class="label-ct-af-calc"><?php esc_html_e( "Home Insurance", "contempo" ); ?></div>
				                    <div class="result-ct-af-calc" id="ct-af-calc-result-home-insurance">&mdash;</div>
				                </li>
				            </ul>
				        </div>
				    </div>
				</div>
			</div>
				<div class="clear"></div>

	<?php 	
		}
	}

}
