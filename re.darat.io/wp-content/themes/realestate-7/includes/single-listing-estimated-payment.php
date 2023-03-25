<?php
/**
 * Single Listing Estimated Payment
 *
 * @package WP Pro Real Estate 7
 * @subpackage Include
 */

global $ct_options;

$ct_listing_single_layout = isset( $ct_options['ct_listing_single_layout'] ) ? esc_html( $ct_options['ct_listing_single_layout'] ) : '';
$ct_single_listing_main_layout = isset( $ct_options['ct_single_listing_main_layout']['enabled'] ) ? $ct_options['ct_single_listing_main_layout']['enabled'] : '';

$ct_display_listing_price = get_post_meta(get_the_ID(), '_ct_display_listing_price', true);

do_action('before_single_ct_listing_estimated_payment');

if(in_array('Est Payment', (array) $ct_single_listing_main_layout)) {

	if(!has_term( array('for-rent', 'rental', 'lease'), 'ct_status', get_the_ID()) && $ct_display_listing_price != 'no') {

		echo '<!-- Estimated Payment -->';

		if( $ct_listing_single_layout != 'listings-three' && $ct_listing_single_layout != 'listings-four' && $ct_listing_single_layout != 'listings-five' ) {
			echo '<p class="est-payment marT0 marB0 muted padL30">';
		} else {
			echo '<p class="est-payment marT0 marB20 muted">';
		}

			echo __('Est. Payment', 'contempo') . ' ';

			echo '<a href="#loanCalc">';
			    ct_listing_estimated_payment();
		    echo '</a>';

		echo '</p>';
	}

} ?>