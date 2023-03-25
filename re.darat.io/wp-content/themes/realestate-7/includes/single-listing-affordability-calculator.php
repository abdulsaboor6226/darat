<?php
/**
 * This file represents the template for our mortgage calculator.
 *
 * @package real-estate-7
 * @since 3.0.5
 * @var $ct_options The RE7 Theme Options Data.
 */

global $ct_options;

// Get the listing price visibility.
$ct_display_listing_price = get_post_meta( get_the_ID(), '_ct_display_listing_price', true );

// Affordability component enable/disable. Disable by default.
$is_affordability_component_enabled = apply_filters("ct_is_affordability_component_enabled", __return_false() );

// Get Pre-Qualified Button
$ct_single_listing_get_prequalified_link_type = isset( $ct_options['ct_single_listing_get_prequalified_link_type'] ) ? esc_attr( $ct_options['ct_single_listing_get_prequalified_link_type'] ) : '';
$ct_single_listing_get_prequalified_page = isset( $ct_options['ct_single_listing_get_prequalified_page'] ) ? esc_attr( $ct_options['ct_single_listing_get_prequalified_page'] ) : '';
$ct_single_listing_get_prequalified_external_url = isset( $ct_options['ct_single_listing_get_prequalified_external_url'] ) ? esc_attr( $ct_options['ct_single_listing_get_prequalified_external_url'] ) : '';
$ct_single_listing_get_prequalified_link_target = isset( $ct_options['ct_single_listing_get_prequalified_link_target'] ) ? esc_attr( $ct_options['ct_single_listing_get_prequalified_link_target'] ) : '';

if($ct_single_listing_get_prequalified_link_type == 'internal_page') {
    $ct_single_listing_get_prequalified_page_url = get_page_link( $ct_single_listing_get_prequalified_page );
} elseif($ct_single_listing_get_prequalified_link_type == 'external_url') {
    $ct_single_listing_get_prequalified_page_url = $ct_single_listing_get_prequalified_external_url;
}

// Enable the affordability component if the following conditions are true.
if( ! has_term( array('for-rent', 'rental', 'lease'), 'ct_status', get_the_ID()) && $ct_display_listing_price != 'no') {
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

<div class="ct-affordability-calculator marb20 col span_12 first">

    <div class="ct-affordability-calculator__headlines">
        <h4 class="border-bottom marB20">
			<?php esc_html_e( "Affordability", "contempo" ); ?>
        </h4>
        <div id="loanCalc"></div>
        <!-- Fake the element block so that it eliminates white space on top when smooth scrolling. -->
        <h5 class="marB5">
			<?php esc_html_e( "Calculate your monthly mortgage payments", "contempo" ); ?>
        </h5>
        <h6>
			<?php esc_html_e( "Your est. payment is: ", "contempo" ); ?>
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
                <?php if($ct_single_listing_get_prequalified_link_type == 'internal_page' || $ct_single_listing_get_prequalified_link_type == 'external_url') { ?>
                    <li>
                        <a class="btn btn-full-width" href="<?php echo esc_url($ct_single_listing_get_prequalified_page_url); ?>" target="<?php echo esc_html($ct_single_listing_get_prequalified_link_target); ?>"><?php _e('Get Pre-Qualified', 'contempo'); ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
    <div class="clear"></div>