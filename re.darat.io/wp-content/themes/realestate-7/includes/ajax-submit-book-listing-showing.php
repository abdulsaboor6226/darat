<?php

/**
 * Ajax Submit - Listing Book Showing Widget
 *
 * @package WP Pro Real Estate 7
 * @subpackage Include
 */


/** Load WordPress Bootstrap, TODO: use admin-ajax.php */
$inc = dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php';

if (file_exists($inc) && is_readable($inc)) {

	require_once($inc);

} else {
	
	$inc = filter_input( INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING ) . '/wp-load.php';

	if (file_exists($inc) && is_readable($inc)) {

		require_once( filter_input( INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING ) . '/wp-load.php');

	}
}

global $ct_options;
$ct_listing_agent_contact_cc_email_address = isset($ct_options['ct_listing_agent_contact_cc_email_address']) ? esc_html($ct_options['ct_listing_agent_contact_cc_email_address']) : '';

$email = $_POST['email'];
$name = $_POST['name'];
$email = $_POST['email'];
$selectedDate = $_POST['selectedDate'];
$showing_start = $_POST['showing_start'];
$showing_end = $_POST['showing_end'];
$youremail = $_POST['ctyouremail'];
$subject = $_POST['ctsubject'];
$ctproperty = $_POST['ctproperty'];
$ctpermalink = $_POST['ctpermalink'];
$ctphone = $_POST['ctphone'];

if (function_exists('do_action')) {
	do_action("ajax-submit-book-listing-showing");
}

$isValidate = true;

if ($isValidate == true) {

	$to = "$youremail";
	$subject = "$subject";
	$msg = "Date: $selectedDate" . "\n" .
		"Time Start: $showing_start" . "\n" .
		"Time End: $showing_end" . "\n" .
		"Phone: $ctphone" . "\n" .
		"Email: $email" . "\n" .
		"Property Address: $ctproperty" . "\n" .
		"Permalink: $ctpermalink" . "\n";
	$headers = "From: $name <$email>" . "\r\n" .
		"Reply-To: $email" . "\r\n" .
		"CC: $ct_listing_agent_contact_cc_email_address" . "\r\n" .
		"X-Mailer: PHP/" . phpversion();
	
	if ( class_exists('CT_RealEstate7_Helper') ) {
		CT_RealEstate7_Helper::send($to, $subject, $msg, "From: $name <$email>");
	}

	echo "true";

} else {
	echo '{"jsonValidateReturn":' . json_encode($arrayError) . '}';
}
