<?php

final class CT_RealEstate7_Helper
{

    /**
     * The wp_mail Wrapper
     */
    public static function send($to, $subject, $msg, $headers)
    {
        wp_mail($to, $subject, $msg, $headers);
    }
    /**
     * MailChimp Curl Setup
     */
    public static function mailchimp_curl_connect($url, $request_type, $api_key, $data = array())
    {

        if ($request_type == 'GET') {
            $url .= '?' . http_build_query($data);
        }

        $mch = curl_init();
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode('user:' . $api_key),
        );
        curl_setopt($mch, CURLOPT_URL, $url);
        curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($mch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($mch, CURLOPT_RETURNTRANSFER, true); // do not echo the result, write it into variable
        curl_setopt($mch, CURLOPT_CUSTOMREQUEST, $request_type); // according to MailChimp API: POST/GET/PATCH/PUT/DELETE
        curl_setopt($mch, CURLOPT_TIMEOUT, 10);
        curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false); // certificate verification for TLS/SSL connection

        if ($request_type != 'GET') {
            curl_setopt($mch, CURLOPT_POST, true);
            curl_setopt($mch, CURLOPT_POSTFIELDS, json_encode($data)); // send data in json
        }

        return curl_exec($mch);
    }

    public static function yelp_request($host, $path, $url_params, $ct_options)
    {

        $API_KEY = isset($ct_options['ct_yelp_api_key']) ? esc_html($ct_options['ct_yelp_api_key']) : '';

        // Send Yelp API Call
        try {
            $curl = curl_init();
            if (false === $curl) {throw new Exception('Failed to initialize');}
            $url = $host . $path . "?" . http_build_query($url_params);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true, // Capture response.
                CURLOPT_ENCODING => "", // Accept gzip/deflate/whatever.
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer " . $API_KEY,
                    "cache-control: no-cache",
                ),
            ));
            $response = curl_exec($curl);
            if (false === $response) {throw new Exception(curl_error($curl), curl_errno($curl));}
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (200 != $http_status) {throw new Exception($response, $http_status);}
            curl_close($curl);
        } catch (Exception $e) {
            echo '<div class="nomatches">';
            if ($e->getCode() == '401') {
                echo '<h5>' . __('You need to setup the Yelp Fusion API.', 'contempo') . '</h5>';
                echo '<p class="marB0">' . __('Go into Admin > Real Estate 7 Options > What\'s Nearby? > Create App', 'contempo') . '</p>';
            } elseif ($e->getCode() == '429') {
                echo '<h5>' . __('You\'ve reached the daily Yelp Fusion API limit.', 'contempo') . '</h5>';
                echo '<p class="marB0">' . __('Please visit https://www.yelp.com/developers/v3/manage_app', 'contempo') . '</p>';
            } elseif ($e->getCode() == '502') {
                echo '<h5>' . __('General 502 Error from Yelp Fusion API', 'contempo') . '</h5>';
                echo '<p class="marB0">' . __('Please check your server error logs.', 'contempo') . '</p>';
            } elseif ($e->getCode() == '500') {
                echo '<h5>' . __('General 500 Error from Yelp Fusion API', 'contempo') . '</h5>';
                echo '<p class="marB0">' . __('Please check your server error logs.', 'contempo') . '</p>';
            } else {
                echo '<h5>' . __('General Error from Yelp Fusion API', 'contempo') . '</h5>';
                echo '<p class="marB0">' . sprintf(
                    'Curl failed with error #%d: %s',
                    $e->getCode(), $e->getMessage()),
                    E_USER_ERROR . '</p>';
            }
            echo '</div>';
        }

        return $response;
    }

    /**
     * Base 64 Encode/Decode Wrapper.
     */
    public static function hash64( $string, $type = "decode")
    {
        if ("decode" === $type) {
            return base64_decode($string);
        }
        return base64_encode($string);
    }

    public static function google_places_client($url)
    {

        $curl = curl_init();

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_RETURNTRANSFER => true,
        );

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        if ($error = curl_error($curl)) {
            throw new \Exception('CURL Error: ' . $error);
        }

        curl_close($curl);

        return $response;

    }

    /**
     * Send request to Zapier.
     */
    public static function zapier_hook_request($ct_zapier_webhook_url, $mailstring, $bypass_ssl = false)
    {
        // Initialize curl.
        $ch = curl_init();

        // Overwrite SSL Checks. 
        if ( $bypass_ssl ) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        // Set additional options.
        curl_setopt($ch, CURLOPT_URL, $ct_zapier_webhook_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $mailstring);

        // Execute our curl request.
        $content = curl_exec($ch);

        // Catch whatever error there is.
        if ($content === false) {
            throw new Exception( curl_error($ch), curl_errno($ch));
        }

        // Close the curl connection.
        curl_close( $ch );

        // Return curl result.
        return $content;

    }

    public static function adminbar_enabler( $is_show = true )
    {
        show_admin_bar( $is_show );
    }

}
