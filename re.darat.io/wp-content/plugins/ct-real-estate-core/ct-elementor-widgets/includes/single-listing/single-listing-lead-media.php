<?php
/**
 * Single Listing Lead Media
 *
 * @package CT Elementor Widgets
 * @subpackage Single Listing Includes
 */
 
global $ct_options;

$ct_listing_single_layout = isset( $ct_options['ct_listing_single_layout'] ) ? esc_html( $ct_options['ct_listing_single_layout'] ) : '';

$attributes['is_elementor'] = 1;

if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
    $attributes['is_elementor_edit'] = 1;
}

echo '<!-- FPO First Image -->';
echo '<figure id="first-image-for-print-only">';
    ct_first_image_lrg();
echo '</figure>';

do_action('before_single_listing_lead_media');

if($ct_listing_single_layout != 'listings-two') {

    $listingslides = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_slider", true);

    if(!empty($listingslides)) {
        // Grab Slider custom field images
        $imgattachments = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_slider", true);
    } else {
        // Grab images attached to post via Add Media
        $imgattachments = get_children(
        array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_parent' => ct_return_listing_id_elementor($attributes)
        ));
    }
    echo '<figure id="lead-media"';
            if(count($imgattachments) <= 1) { echo 'class="single-image"'; }
        echo '>';
        if(count($imgattachments) > 1) {
            echo '<div id="slider" class="flexslider">';
                ct_property_type_icon();
                ct_listing_actions();
                echo '<ul class="slides">';
                    if(!empty($listingslides)) {
                        ct_elementor_slider_field_images();
                    } else {
                        ct_elementor_slider_images();
                    }
                echo '</ul>';
            echo '</div>';
            echo '<div id="carousel" class="flexslider">';
                echo '<ul class="slides">';
                    if(!empty($listingslides)) {
                        ct_elementor_slider_field_carousel_images();
                    } else {
                        ct_elementor_slider_carousel_images();
                    }
                echo '</ul>';
            echo '</div>';
        } else {
            ct_property_type_icon();
            ct_listing_actions();
            ct_elementor_first_image_lrg();
        }
    echo '</figure>';
    echo '<!-- //Lead Media -->';
} ?>