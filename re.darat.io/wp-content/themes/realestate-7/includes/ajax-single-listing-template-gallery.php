<?php
/**
 * File: ajax-single-listing-template.php
 */
// Count the view.
ct_set_listing_views( get_the_ID() );

$listing_slides = get_post_meta( $post->ID, "_ct_slider", true );

if ( ! empty( $listing_slides ) ) {
	// Grab Slider custom field images.
	$img_attachments = get_post_meta( $post->ID, "_ct_slider", true );
} else {
	// Grab images attached to post via Add Media.
	$img_attachments = get_children(
		array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'post_parent'    => $post->ID
		) );
}
$class = 'single-image';

if ( count( $img_attachments ) > 1 ) {
	$class = 'multi-image';
}

if ( 'idx-api' === get_post_meta( $post->ID, "source", true ) ) {
	$class .= ' idx-listing';
}

?>
<!--backbutton-->
<div id="ct-listing-back--button">
    <i class="fas fa-chevron-left"></i>
</div>
<div id="ajax-listing-modal-flex" class="ajax-listing-modal-flex flexslider"><ul id="ct-listing-single-modal-slides" class="slides"></ul></div>
<figure id="ajax-single-listing-gallery" class="<?php echo esc_attr( $class ); ?>">
	<?php
	if ( count( $img_attachments ) > 1 ) { ?>
        <div id="ajax-single-listing-gallery-wrap">
        	<?php ct_idx_mls_logo(); ?>
			<?php if ( ! empty( $listing_slides ) ) { ?>
                <ul>
					<?php ct_slider_field_images(); ?>
                </ul>
			<?php } else { ?>
                <ul>
					<?php ct_slider_images(); ?>
                </ul>
			<?php } ?>
        </div>
	<?php } else { ?>
		<?php ct_idx_mls_logo(); ?>
		<?php ct_property_type_icon(); ?>
		<?php ct_listing_actions(); ?>
		<?php ct_first_image_lrg(); ?>
	<?php } ?>
</figure>
