<?php
/**
 * Single Listing Lead Media Large Carousel
 *
 * @package CT Elementor Widgets
 * @subpackage Single Listing Includes
 */

$attributes['is_elementor'] = 1;

if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
    $attributes['is_elementor_edit'] = 1;
}

echo '<!-- FPO First Image -->';
echo '<figure id="first-image-for-print-only">';
    ct_first_image_lrg();
echo '</figure>';

do_action('before_single_listing_lead_media');

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
?>
<figure id="lead-carousel" class="<?php if(count($imgattachments) <= 1) { echo 'single-image'; } else { echo 'multi-image'; } ?> <?php if(get_post_meta( ct_return_listing_id_elementor($attributes), "source", true) == 'idx-api') { echo 'idx-listing'; } ?>">
    <?php
    if(count($imgattachments) > 1) { ?>
        <div id="lrg-carousel" class="owl-carousel">
            <?php if(!empty($listingslides)) {
                ct_elementor_slider_field_images();
            } else {
                ct_elementor_slider_images();
            } ?>
        </div>
    <?php } else { ?>
        <?php ct_property_type_icon(); ?>
        <?php ct_listing_actions(); ?>
        <?php ct_elementor_first_image_lrg(); ?>
    <?php } ?>
</figure>
<!-- //Lead Carousel -->