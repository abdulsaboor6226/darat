<?php
/**
 * Single Listing Lead Media Large Carousel
 *
 * @package WP Pro Real Estate 7
 * @subpackage Include
 */

echo '<!-- FPO First Image -->';
echo '<figure id="first-image-for-print-only">';
    ct_first_image_lrg();
echo '</figure>';

do_action('before_single_listing_lead_media');

$listingslides = get_post_meta($post->ID, "_ct_slider", true);

if(!empty($listingslides)) {
    // Grab Slider custom field images
    $imgattachments = get_post_meta($post->ID, "_ct_slider", true);
} else {
    // Grab images attached to post via Add Media
    $imgattachments = get_children(
    array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'post_parent' => $post->ID
    ));
}
?>
<figure id="lead-carousel" class="<?php if(count($imgattachments) <= 1) { echo 'single-image'; } else { echo 'multi-image'; } ?> <?php if(get_post_meta($post->ID, "source", true) == 'idx-api') { echo 'idx-listing'; } ?>">
    <?php
    if(count($imgattachments) > 1) { ?>
        <div id="lrg-carousel" class="owl-carousel">
            <?php if(!empty($listingslides)) {
                ct_slider_field_images();
            } else {
                ct_slider_images();
            } ?>
        </div>
    <?php } else { ?>
        <?php ct_property_type_icon(); ?>
        <?php ct_listing_actions(); ?>
        <?php ct_first_image_lrg(); ?>
    <?php } ?>
</figure>
<!-- //Lead Carousel -->