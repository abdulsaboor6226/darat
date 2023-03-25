<?php
/**
 * Single Listing Lead Media
 *
 * @package CT Elementor Widgets
 * @subpackage Single Listing Includes
 */
 
global $ct_options;

$attributes['is_elementor'] = 1;

if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
    $attributes['is_elementor_edit'] = 1;
}

$ct_listing_single_layout = isset( $ct_options['ct_listing_single_layout'] ) ? esc_html( $ct_options['ct_listing_single_layout'] ) : '';

$ct_enable_zapier_webhooks = isset( $ct_options['ct_enable_zapier_webhooks'] ) ? $ct_options['ct_enable_zapier_webhooks'] : '';
$ct_zapier_webhook_url = isset( $ct_options['ct_zapier_webhook_url'] ) ? $ct_options['ct_zapier_webhook_url'] : '';
$ct_zapier_webhook_listing_single_form = isset( $ct_options['ct_zapier_webhook_listing_single_form'] ) ? $ct_options['ct_zapier_webhook_listing_single_form'] : '';

$ct_listing_contact_form_7 = isset( $ct_options['ct_listing_contact_form_7'] ) ? esc_html( $ct_options['ct_listing_contact_form_7'] ) : '';
$ct_listing_contact_form_7_shortcode = isset( $ct_options['ct_listing_contact_form_7_shortcode'] ) ? $ct_options['ct_listing_contact_form_7_shortcode'] : '';

$ct_bed_beds_or_bedrooms = isset( $ct_options['ct_bed_beds_or_bedrooms'] ) ? esc_html( $ct_options['ct_bed_beds_or_bedrooms'] ) : '';
$ct_bath_baths_or_bathrooms = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? esc_html( $ct_options['ct_bath_baths_or_bathrooms'] ) : '';

$beds = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'beds', '', ', ', '' ) );
$baths = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'baths', '', ', ', '' ) );

$ct_source = get_post_meta( ct_return_listing_id_elementor($attributes), 'source', true);

$listingslides = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_slider', true);

$ct_idx_pro_assign_agents = get_option( 'ct_idx_pro_assign_agents' );
$ct_idx_pro_assign_agents = isset( $ct_idx_pro_assign_agents ) ? $ct_idx_pro_assign_agents : '';
$ct_idx_pro_assign_agents = json_decode($ct_idx_pro_assign_agents, true);

if(!empty($ct_idx_pro_assign_agents) && $ct_source == 'idx-api') {
    
    foreach($ct_idx_pro_assign_agents as $agent) {
        $ct_agent_first_name = get_user_meta($agent, 'first_name', true);
        $ct_agent_last_name = get_user_meta($agent, 'last_name', true);
        $ct_agent_display_name = $ct_agent_first_name . ' ' . $ct_agent_last_name;
        $ct_agent_name_IDX = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_agent_name', true );

        if($ct_agent_name_IDX == $ct_agent_display_name) {
            $author_id = $agent;
            $user_info = get_userdata($agent);
        } else {
            $author_id = get_the_author_meta('ID');
            $user_info = get_userdata($author_id);
        }
    }

} else {
    $author_id = get_the_author_meta('ID');
    $user_info = get_userdata($author_id);
}

$ct_profile_url = get_user_meta($author_id, 'ct_profile_url', true);
$first_name = get_user_meta($author_id, 'first_name', true);
$last_name = get_user_meta($author_id, 'last_name', true);
$tagline = get_user_meta($author_id, 'tagline', true);
$mobile = get_user_meta($author_id, 'mobile', true);
$office = get_user_meta($author_id, 'office', true);
$fax = get_user_meta($author_id, 'fax', true);
$email = $user_info->user_email;
$agent_license = get_user_meta($author_id, 'agentlicense', true);
$ct_user_url = get_user_meta($author_id, 'user_url', true);
$twitterhandle = get_user_meta($author_id, 'twitterhandle', true);
$facebookurl = get_user_meta($author_id, 'facebookurl', true);
$instagramurl = get_user_meta($author_id, 'instagramurl', true);
$linkedinurl = get_user_meta($author_id, 'linkedinurl', true);
$gplus = get_user_meta($author_id, 'gplus', true);
$youtubeurl = get_user_meta($author_id, 'youtubeurl', true);

echo '<!-- FPO First Image -->';
echo '<figure id="first-image-for-print-only">';
    ct_first_image_lrg();
echo '</figure>';

do_action('before_single_listing_lead_media');

echo '<div id="listing-five-gallery">';

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

    if(count($imgattachments) > 1) {
        echo '<ul class="item-grid grid-three-item">';
            echo '<li class="grid-item col span_8 first" ';
                ct_elementor_first_image_as_bg();
            echo '>';
                echo '<a href="#" class="ct-listings-gallery-modal-show">';
                    ct_elementor_listing_images_count_mobile();
                echo '</a>';
            echo '</li>';
            echo '<li class="grid-item col span_4" ';
                ct_elementor_second_image_as_bg();
            echo '>';
                echo '<a href="#" class="ct-listings-gallery-modal-show">';
            echo '</li>';
            echo '<li id="listings-gallery-w-overlay" class="grid-item col span_4" ';
                ct_elementor_third_image_as_bg();
            echo '>';
                echo '<a href="#" id="listings-gallery-w-overlay" class="ct-listings-gallery-modal-show">';
                    ct_elementor_listing_images_count_more();
                echo '</a>';
            echo '</li>';
        echo '</ul>'; 
    } else {
        ct_property_type_icon();
        ct_listing_actions();
        ct_elementor_first_image_lrg();
    }
    
echo '</div>';

// Gallery & Contact Modal

echo '<div class="ct-modal-mask" role="dialog"></div>';
echo '<div id="listing-gallery-contact-modal" class="ct-modal-modal">';

    echo '<header>';
        echo '<div id="listing-details" class="col span_10">';
            echo '<p class="marB0">';
                ct_elementor_listing_title();
                echo ' | ';
                ct_elementor_listing_price();
                echo ' | ';
                if(ct_has_type('commercial') || ct_has_type('industrial') || ct_has_type('retail') || ct_has_type('lot') || ct_has_type('land')) {
                    // Dont Display Bed/Bath
                } else {
                    if(!empty($beds)) {
                        echo '<span class="beds">';
                           echo esc_html($beds) . ' ';
                           if($ct_bed_beds_or_bedrooms == 'rooms') {
                                _e('Rooms', 'contempo');
                            } elseif($ct_bed_beds_or_bedrooms == 'bedrooms') {
                                _e('Bedrooms', 'contempo');
                            } elseif($ct_bed_beds_or_bedrooms == 'beds') {
                                _e('Beds', 'contempo');
                            } else {
                                _e('Bed', 'contempo');
                            }
                        echo '</span>';
                    }
                    if(!empty($baths)) {
                        echo '<span class="baths">';
                           echo esc_html($baths) . ' ';
                           if($ct_bath_baths_or_bathrooms == 'bathrooms') {
                                _e('Bathrooms', 'contempo');
                            } elseif($ct_bath_baths_or_bathrooms == 'baths') {
                                _e('Baths', 'contempo');
                            } else {
                                _e('Bath', 'contempo');
                            }
                        echo '</span>';
                    }
                }
            echo '</p>';
        echo '</div>';
        ct_fav_listing_btn();
        echo '<button class="ct-modal-close" role="button">';
            ct_close_svg();
        echo '</button>';
    echo '</header>';

    echo '<div class="col span_8 first">';

        $listing_slides = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_slider", true );

        if ( ! empty( $listing_slides ) ) {
            // Grab Slider custom field images.
            $img_attachments = get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_slider", true );
        } else {
            // Grab images attached to post via Add Media.
            $img_attachments = get_children(
                array(
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'post_parent'    => ct_return_listing_id_elementor($attributes)
                ) );
        }
        $class = 'single-image';

        if ( count( $img_attachments ) > 1 ) {
            $class = 'multi-image';
        }

        if ( 'idx-api' === get_post_meta( ct_return_listing_id_elementor($attributes), "source", true ) ) {
            $class .= ' idx-listing';
        }

    ?>

        <figure id="ajax-single-listing-gallery" class="<?php echo esc_attr( $class ); ?>">
            <?php
            if ( count( $img_attachments ) > 1 ) { ?>
                <div id="ajax-single-listing-gallery-wrap">
                    <?php if ( ! empty( $listing_slides ) ) { ?>
                        <ul>
                            <?php ct_elementor_slider_field_images(); ?>
                        </ul>
                    <?php } else { ?>
                        <ul>
                            <?php ct_elementor_slider_images(); ?>
                        </ul>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <?php ct_elementor_first_image_lrg(); ?>
            <?php } ?>
        </figure>

    <?php
    echo '</div>';
    ?>

    <div class="col span_4 agent-contact">

        <div id="listing-agent-info" class="col span_12 first">

            <?php
            echo '<figure class="col span_3 first">';
                echo '<a href="' . get_author_posts_url($author_id) . '">';
                   if(!empty($ct_profile_url)) {  
                        echo '<img class="author-img" src="';
                            echo esc_url($ct_profile_url);
                        echo '" />';
                    } else {
                        echo '<img class="author-img" src="' . get_template_directory_uri() . '/images/user-default.png' . '" />';
                    }
                echo '</a>';
            echo '</figure>';
            ?>

            <div id="agent-info" class="col span_9 marB30">
                <h4 class="<?php if(empty($tagline)) { echo 'marB10'; } else { echo 'marB5'; } ?>"><small id="agent-print-only"><?php _e('Agent', 'contempo'); ?></small><a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo esc_html($first_name); ?> <?php echo esc_html($last_name); ?></a></h4>
                <?php if($tagline) { ?>
                    <p class="muted tagline marB10"><i><?php echo esc_html($tagline); ?></i></p>
                <?php } ?>

                <ul class="marB0">
                    <?php if($mobile) { ?>
                        <li><span class="left"><i class="fa fa-phone"></i></span><span class="right"><a href="tel:<?php echo esc_html($mobile); ?>"><?php echo esc_html($mobile); ?></a></span></li>
                    <?php } ?>
                    <?php if($office) { ?>
                        <li><span class="left"><i class="fa fa-building"></i></span><span class="right"><a href="tel:<?php echo esc_html($office); ?>"><?php echo esc_html($office); ?></a></span></li>
                    <?php } ?>
                </ul>
            </div>

        </div>

            <div class="clear"></div>
        
        <?php if($ct_listing_contact_form_7 == 'yes' && $ct_listing_contact_form_7_shortcode != '') { ?>
        
            <?php echo do_shortcode($ct_listing_contact_form_7_shortcode); ?>
        
        <?php } else { ?>

                
                 <form class="formular listingscontact-form" method="post">

                    <fieldset class="col span_12">

                        <div class="col span_12 first">
                            <select id="ctsubject" name="ctsubject">
                                <option><?php esc_html_e('Tell me more about this listing', 'contempo'); ?></option>
                                <option><?php esc_html_e('Request a showing', 'contempo'); ?></option>
                            </select>
                        </div>

                        <div class="clear"></div>

                        <div class="col span_12 first">
                            <input type="text" name="name" id="name" class="validate[required] text-input" placeholder="<?php esc_html_e('Name', 'contempo'); ?>" />
                        </div>

                        <div class="col span_12 first">
                            <input type="text" name="email" id="email" class="validate[required,custom[email]] text-input" placeholder="<?php esc_html_e('Email', 'contempo'); ?>" />
                        </div>

                        <div class="col span_12 first">
                            <input type="text" name="ctphone" id="ctphone" class="text-input" placeholder="<?php esc_html_e('Phone', 'contempo'); ?>" />
                        </div>

                        <div class="col span_12 first">
                            <textarea class="validate[required,length[2,1000]] text-input" name="message" id="message" rows="5" cols="10"></textarea>
                        </div>

                        <input type="hidden" id="ctyouremail" name="ctyouremail" value="<?php echo esc_attr($email); ?>" />
                        <input type="hidden" id="ctproperty" name="ctproperty" value="<?php the_title(); ?>, <?php city(); ?>, <?php state(); ?> <?php zipcode(); ?>" />
                        <input type="hidden" id="ctpermalink" name="ctpermalink" value="<?php the_permalink(); ?>" />
                        
                        <?php 
                            if($ct_enable_zapier_webhooks == 'yes' && $ct_zapier_webhook_url != '' && $ct_zapier_webhook_listing_single_form == '1') {
                                echo '<input type="hidden" id="ct_zapier_webhook_url" name="ct_zapier_webhook_url" value="' . $ct_zapier_webhook_url . '" />';
                            } 
                        ?>

                        <input data-verification="google-recaptcha-v3" type="submit" name="Submit" value="<?php esc_html_e('Request Information', 'contempo'); ?>" class="btn right" />  
                        
                        <div class="clear"></div>

                    </fieldset>

                </form>

                <?php ct_render_google_recaptcha_v3_script(); ?>


        <?php } ?>

    </div>
    <!-- //Lead Media -->
</div>

<script>
    // Modal 
    jQuery(".ct-listings-gallery-modal-show").on("click", function(event){
        event.preventDefault();
        jQuery('body').addClass('no-scroll');
        jQuery('.ct-modal-mask').addClass('ct-modal-active');
        jQuery('.ct-modal-close').css('display', 'block');
    });

    function closeModal(){
        jQuery('body').removeClass('no-scroll');
        jQuery(".ct-modal-mask").removeClass("ct-modal-active");
        jQuery(".ct-modal-close").css("display", "none");
    }

    jQuery(".ct-modal-close, .ct-modal-mask").on("click", function(){
      closeModal();
    });

    jQuery(document).keyup(function(e) {
      if (e.keyCode == 27) {
        closeModal();
      }
    });
</script>
