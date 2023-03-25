<?php
/**
 * Ajax Submit - Agent Contact Modal Zapier
 *
 * @package WP Pro Real Estate 7
 * @subpackage Include
 */

$ct_email = $_POST['email'];
$ct_name = $_POST['name'];
$ct_message = $_POST['message'];
$ct_subject = $_POST['ctsubject'];
$ct_phone = $_POST['ctphone'];

$ct_agent_name = $_POST['ctagentname'];
$ct_agent_email = $_POST['ctagentemail'];

$ct_zapier_webhook_url = $_POST['ct_zapier_webhook_url'];

$mailstring = 'name=' . $ct_name . '&email=' . $ct_email . '&phone=' . $ct_phone . '&subject=' . $ct_subject . '&message=' . $ct_message . '&agentname=' . $ct_agent_name . '&agentemail=' . $ct_agent_email;

/** Load the WordPress Environment so that we can call our plugin */
require_once '../../../../wp-load.php';

if ( class_exists('CT_RealEstate7_Helper') ) {

    global $ct_options;

    $is_ssl_enabled = isset( $ct_options['ct_zapier_ssl_true_false'] ) ? $ct_options['ct_zapier_ssl_true_false']: "true";

    $bypass_ssl_check = false; 
    
    if ( "true" === $is_ssl_enabled ) {
        
       $bypass_ssl_check = true;

    }

    $status = [
        'zapier'  => 'true',
        'status'  => 'fail',
        'message' => '',
    ];

    try {

        $result = CT_RealEstate7_Helper::zapier_hook_request( 
            $ct_zapier_webhook_url, 
            $mailstring,
            $bypass_ssl_check
        );

        
    } catch ( Exception $e ) {
        
        $status['status'] = 'fail';
        $status['message'] = $e->getMessage();

        // Send json response.
        wp_send_json( $status );

    }
    
}