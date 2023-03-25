<?php
/**
 * Disable direct file access.
 */
if ( ! defined('ABSPATH') ) {
	return;
}

global $ct_options;
$ct_listing_tools = isset( $ct_options['ct_listing_tools'] ) ? esc_html( $ct_options['ct_listing_tools'] ) : '';

if($ct_listing_tools == 'yes') { 
	get_template_part( 'includes/single-listing-tools' );
}
get_template_part( 'includes/single-listing-estimated-payment' );
get_template_part( 'includes/single-listing-propinfo' );
?>
    <div class="clear" ></div >
<?php
//get_template_part( 'includes/single-listing-lead-media' );
