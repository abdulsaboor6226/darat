<?php

/**
 * Ajax Submit - Agent Contact Modal
 *
 * @package WP Pro Real Estate 7
 * @subpackage Include
 */

/** Load WordPress Bootstrap, TODO: use admin-ajax.php */
$wp_load = realpath( '../../../../wp-load.php' );

if ( file_exists ( $wp_load ) ) {
	require_once $wp_load;
}

$email 		= $_POST['email'];
$name 		= $_POST['name'];
$message 	= $_POST['message'];
$youremail 	= $_POST['ctyouremail'];
$subject 	= $_POST['ctsubject'];
$ctphone 	= $_POST['ctphone'];

$isValidate = true;

if ( $isValidate === true ) {

	$to = "$youremail";
	$subject = "$subject";
	$msg = "$message" . "\n\n" . "Phone: $ctphone" . "\n" . "Email: $email" . "\n";
	$headers = "From: $name <$email>" . "\r\n" . "Reply-To: $email" . "\r\n" . "X-Mailer: PHP/" . phpversion();

	if ( class_exists('CT_RealEstate7_Helper') ) {

		CT_RealEstate7_Helper::send($to, $subject, $msg, "From: $name <$email>");

	}

	echo "true";

	if ( function_exists('do_action') ) {
		do_action("ajax-submit-agent");
	}

} else {

	echo '{"jsonValidateReturn":' . json_encode($arrayError) . '}';

}