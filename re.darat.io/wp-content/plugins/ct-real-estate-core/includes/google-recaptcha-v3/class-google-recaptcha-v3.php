<?php
namespace Contempo_RE_Custom_Posts;

use Exception as Exception;

class Google_Recaptcha_V3 {

    const ENDPOINT = 'ct-re-google-verifier';

    const GOOGLE_ENDPOINT = 'https://www.google.com/recaptcha/api/siteverify';

    private $secret = '';

    public function __construct() {

        $action = self::ENDPOINT;

        $this->secret = apply_filters('contempo-re-custom-posts-google-recaptcha-v3-site-key', '');

        add_action("wp_ajax_nopriv_{$action}", $this );

        add_action("wp_ajax_{$action}", $this );

    }


    private function serve_endpoint() {

        // Secret must be overwritten in the theme.
        $secret = apply_filters("re7_google_recaptcha_v3_secret", ""); 
        
        $response = filter_input( INPUT_POST, 'token', FILTER_SANITIZE_STRING );

        try {
            $gv3response = $this->maybe_verify_user_request_from_google(
                $secret,
                $response
            );
        } catch ( Exception $e ) {
            wp_send_json([
                'type' => 'error',
                'message' => $e->getMessage(),
                'response' => array()
            ]);
        }

        if ( empty( $gv3response ) ) {

           wp_send_json([
               'type' => 'error',
               'message' =>  esc_html__('The request has returned an empty response. Check if PHP Curl Extension is installed and is working properly', 'contempo'),
               'response' => array()
           ]);

        } else {
           
            if ( false === $gv3response['success'] ) {

                $error_codes = implode( ',', $gv3response['error-codes'] );

                wp_send_json([
                    'type' => 'error',
                    'message' => $error_codes,
                    'response' => array()
                ]);

            }

        }

        wp_send_json([
            'type' => 'success',
            'message' => esc_html__('Recaptcha Verification Successful. Sending Request...', 'contempo'),
            'response' => $gv3response
        ]);

        die;
        
    }

    private function maybe_verify_user_request_from_google( $secret, $response ) {
    
        $verification_endpoint = "";
    
        $ch = curl_init();
    
        $post_fields = array(
            "secret={$secret}",
            "response={$response}"
        );

        $post_fields = implode( "&", $post_fields );

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        curl_setopt($ch, CURLOPT_URL, self::GOOGLE_ENDPOINT );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields );
    
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $server_output = curl_exec($ch);

        // Catch whatever error there is.
        if ($server_output === false) {
            throw new Exception( curl_error($ch), curl_errno($ch));
        }
    
        curl_close ($ch);
        
        return (array)json_decode( $server_output );
    
    }

    public function __invoke() {

        $this->serve_endpoint();

    }

}