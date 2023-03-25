<?php
/**
 * Single Listing Attachments
 *
 * @package WP Pro Real Estate 7
 * @subpackage Include
 */

global $ct_options;

$ct_single_listing_content_layout_type = isset( $ct_options['ct_single_listing_content_layout_type'] ) ? $ct_options['ct_single_listing_content_layout_type'] : '';
$ct_listing_attachment_logged_in = isset( $ct_options['ct_listing_attachment_logged_in'] ) ? esc_html( $ct_options['ct_listing_attachment_logged_in'] ) : '';

do_action('before_single_listing_attachments');

echo '<!-- Listing Attachments -->';
$fileattachments = get_post_meta( get_the_ID(), '_ct_files', 1 );

if($fileattachments) {
    echo '<div id="listing-attachments">';
        if($ct_single_listing_content_layout_type == 'accordion') {
            echo '<h4 id="listing-attachments-info" class="info-toggle border-bottom marB20">' . __('Attachments', 'contempo') . '</h4>';
        } else {
            echo '<h4 id="listing-attachments-info" class="border-bottom marB20">' . __('Attachments', 'contempo') . '</h4>';
        }

        echo '<div class="info-inner">';
            if($ct_listing_attachment_logged_in == 'yes') {
                if(!is_user_logged_in()) {

                    echo '<div class="must-be-logged-in must-be-logged-in-grey">';
                        echo '<p class="center login-register-btn marB0"><a class="btn login-register" href="#">Login/Register to View Attachments</a></p>';
                    echo '</div>';
                        
                } else {
                    echo '<ul class="attachments col span_4">';
                    $count = 0;
                    
                    foreach ($fileattachments as $attachment_id => $attachment_url) {
                        $attachment_title =  get_the_title($attachment_id);
                        echo '<li>';
                            echo '<a href="' . $attachment_url . '" target="_blank">';
                                echo '<span>';
                                    echo '<i class="far fa-file-' . ct_get_mime_for_attachment($attachment_id) . '"></i>';
                                echo '</span>';
                                echo esc_html($attachment_title);
                            echo '</a>';
                        echo '</li>';
                        $count++;
                        if ($count == 3) {
                            echo '</ul><ul class="attachments col span_4">';
                            $count = 0;
                        }
                    }
                    echo '</ul>';
                        echo '<div class="clear"></div>';
                }
            } else {
                echo '<ul class="attachments col span_4">';
                $count = 0;
                
                foreach ($fileattachments as $attachment_id => $attachment_url) {
                    $attachment_title =  get_the_title($attachment_id);
                    echo '<li>';
                        echo '<a href="' . $attachment_url . '" target="_blank">';
                            echo '<span>';
                                echo '<i class="far fa-file-' . ct_get_mime_for_attachment($attachment_id) . '"></i>';
                            echo '</span>';
                            echo esc_html($attachment_title);
                        echo '</a>';
                    echo '</li>';
                    $count++;
                    if ($count == 3) {
                        echo '</ul><ul class="attachments col span_4">';
                        $count = 0;
                    }
                }
                echo '</ul>';
                    echo '<div class="clear"></div>';
            }
        echo '</div>';
    echo '</div>';
}
echo '<!-- //Listing Attachments -->';

?>