<?php
/**
 * Ajax Submit - Contact Template
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

$email = $_POST['email'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$message = $_POST['message'];
$youremail = $_POST['ctyouremail'];
$subject = $_POST['ctsubject'];

$isValidate = true;

if ($isValidate == true) {

    $to = $youremail;
    $subject = $subject;
    $msg = "$message" . "\n\n" .
        "Phone: $phone" . "\n" .
        "Email: $email" . "\n";
    $headers = "From:" . $name . "<" . $email . ">" . "\r\n";
    $headers .= "Reply-To:" . $email . "\r\n";
    //$headers = "Bcc: someone@domain.com" . "\r\n";
    $headers = "X-Mailer: PHP/" . phpversion();

    if (class_exists('CT_RealEstate7_Helper')) {

        CT_RealEstate7_Helper::send($to, $subject, $msg, "From: $name <$email>");

        echo "true";
    }

    if (function_exists('do_action')) {
        do_action("ajax-submit-contact");
	}
	
} else {
    echo '{"jsonValidateReturn":' . json_encode($arrayError) . '}';
}
