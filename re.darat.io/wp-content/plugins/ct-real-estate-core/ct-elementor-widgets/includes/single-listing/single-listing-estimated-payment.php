<?php
/**
 * Single Listing Estimated Payment
 *
 * @package CT Elementor Widgets
 * @subpackage Single Listing Includes
 */

global $ct_options;

$ct_display_listing_price = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_display_listing_price', true);

$attributes['is_elementor'] = 1;

if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
    $attributes['is_elementor_edit'] = 1;
}

do_action('before_single_ct_listing_estimated_payment');

if( ! has_term( array('for-rent', 'rental', 'lease'), 'ct_status', ct_return_listing_id_elementor($attributes)) && $ct_display_listing_price != 'no') {

	echo '<!-- Estimated Payment -->';
	echo __('Est. Payment', 'contempo') . ' ';

	echo '<a href="#loanCalc">';
	    ct_elementor_listing_estimated_payment();
    echo '</a>';

}


?>