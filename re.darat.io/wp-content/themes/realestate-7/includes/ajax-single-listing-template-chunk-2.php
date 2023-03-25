<?php
/**
 * Disable direct file access.
 */
if ( ! defined('ABSPATH') ) {
	return;
}

get_template_part( 'includes/single-listing-sub-navigation' );
get_template_part( 'includes/single-listing-content' );
get_template_part( 'includes/single-listing-contact' );
ct_listing_creation_date();
get_template_part( 'includes/single-listing-brokerage' );
echo '<div style="position:relative;" class="ajax-modal-sub-listing">';
get_template_part( 'includes/single-listing-sub-listings' );
echo '</div>';
get_template_part( 'includes/single-listing-affordability-calculator' );
	
echo '<div class="clear"></div>';

if ( class_exists('ct_MortgageCalculator') ) {
	echo '<div id="loanCalc-wrap">';
		echo '<h4 class="border-bottom marB18">' . __('Mortgage Calculator', 'contempo') . '</h4>';
		the_widget('ct_MortgageCalculator');
		echo '<div id="loanCald"></div>';
	echo '</div>';
}

?>

<div class="clear"></div>

<?php do_action( 'after_single_listing_content' ); ?>