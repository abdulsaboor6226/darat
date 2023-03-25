<?php
/**
 * Single Listing Content
 *
 * @package WP Pro Real Estate 7
 * @subpackage Include
 */
 
global $ct_options;

$ct_source = get_post_meta($post->ID, 'source', true);
$ct_idx_rupid = get_post_meta($post->ID, '_ct_idx_rupid', true);

$ct_idx_mls_name = get_post_meta($post->ID, '_ct_idx_mls_name', true);
$ct_idx_agent_name = get_post_meta($post->ID, '_ct_agent_name', true);
$ct_idx_agent_id = get_post_meta($post->ID, '_ct_branding_agent_id', true);
$ct_idx_selling_agent = get_post_meta($post->ID, '_ct_idx_selling_agent', true);
$ct_idx_co_selling_agent = get_post_meta($post->ID, '_ct_idx_co_selling_agent', true);

$ct_cpt_brokerage = get_post_meta($post->ID, '_ct_brokerage', true);
$ct_cpt_brokerage_phone = get_post_meta($post->ID, '_ct_branding_agent_office_phone', true);

if($ct_cpt_brokerage != 0) {

    $brokerage = new WP_Query(array(
        'post_type' => 'brokerage',
        'p' => $ct_cpt_brokerage,
        'nopaging' => true
    ));

    if ( $brokerage->have_posts() ) : while ( $brokerage->have_posts() ) : $brokerage->the_post();
        
        $ct_brokerage_name = strtolower(get_the_title());

    endwhile; endif; wp_reset_postdata();

}

$ct_single_listing_content_layout_type = isset( $ct_options['ct_single_listing_content_layout_type'] ) ? $ct_options['ct_single_listing_content_layout_type'] : '';
$ct_listing_single_require_login_register_for_content = isset( $ct_options['ct_listing_single_require_login_register_for_content'] ) ? esc_html( $ct_options['ct_listing_single_require_login_register_for_content'] ) : '';
$ct_single_listing_content_layout = isset( $ct_options['ct_single_listing_content_layout']['enabled'] ) ? $ct_options['ct_single_listing_content_layout']['enabled'] : '';
$ct_listing_single_content_show_more = isset( $ct_options['ct_listing_single_content_show_more'] ) ? $ct_options['ct_listing_single_content_show_more'] : '';
$ct_listing_idx_mls_compliance_info = isset( $ct_options['ct_listing_idx_mls_compliance_info'] ) ? $ct_options['ct_listing_idx_mls_compliance_info'] : '';
$ct_listing_idx_mls_compliance_info_brokerage_phone = isset( $ct_options['ct_listing_idx_mls_compliance_info_brokerage_phone'] ) ? $ct_options['ct_listing_idx_mls_compliance_info_brokerage_phone'] : '';

$ct_idx_list_date = get_post_meta($post->ID, '_ct_idx_list_date', true);
$ct_idx_list_date = date('n/j/y \a\t g:i a', strtotime($ct_idx_list_date));

$ct_idx_mls_update_date = get_post_meta($post->ID, '_ct_idx_mls_update_date', true);
$ct_idx_mls_update_date = date('n/j/y \a\t g:i a', strtotime($ct_idx_mls_update_date));

do_action('before_single_listing_content');
            
if($ct_listing_single_require_login_register_for_content == 'yes' && !is_user_logged_in()) {

    if($ct_single_listing_content_layout_type == 'tabbed') {
        echo '<script>';
            echo 'jQuery(document).ready(function() {';
                echo 'jQuery("#listing-sections-tab a").removeAttr("href").css("cursor","pointer");';
            echo '});';
        echo '</script>';
    }

    echo '<div class="must-be-logged-in-listing-content">';
        echo '<p class="center">' . __('You must be registered to view listing details', 'contempo') . '</p>';
        echo '<p class="center login-register-btn marB0"><a class="btn login-register" href="#">Login/Register</a></p>';
    echo '</div>';

} else {

    if($ct_single_listing_content_layout_type == 'tabbed') {
        echo '<div class="post-content inside">';
    } else {
        echo '<div class="post-content">';
    }

        if(!empty($ct_single_listing_content_layout)) {

            foreach ($ct_single_listing_content_layout as $key => $value) {
            
                switch($key) {

                    // Content
                    case 'listing_content' :   

                        do_action('before_single_listing_inner_content');

                        if($ct_listing_single_content_show_more == 'yes' && $ct_single_listing_content_layout_type != 'tabbed') { ?>
                            <script>
                            jQuery(function() {
                                var collapsedSize = '170px';
                                jQuery('#listing-content-show-more').each(function() {
                                    var h = this.scrollHeight;
                                    var div = jQuery(this);
                                    if (h > 170) {
                                        div.css('height', collapsedSize);
                                        div.after('<div id="content-show-more"><span id="show-more-btn"><?php _e('Read more', 'contempo'); ?></span></div>');
                                        var showMore = jQuery('#content-show-more');
                                        var link = jQuery('#show-more-btn');
                                        link.click(function(e) {
                                            e.stopPropagation();
                                            if (link.text() != 'Collapse') {
                                                showMore.addClass('show-more-expanded');
                                                link.text('<?php _e('Collapse', 'contempo'); ?>');
                                                div.animate({
                                                    'height': h
                                                });
                                            } else {
                                                div.animate({
                                                    'height': collapsedSize
                                                });
                                                showMore.removeClass('show-more-expanded');
                                                link.text('<?php _e('Read more', 'contempo'); ?>');
                                            }
                                        });
                                    }
                                });
                            });
                            </script>
                            <?php
                            echo '<div id="listing-content-show-more">';
                        } else {
                             echo '<div id="listing-content">';
                        }
                            the_content();

                            // New IDX API
                            if( $ct_source == 'idx-api' && !empty($ct_idx_rupid) ) {
                                
                                // MLS Compliance Info
                                if($ct_listing_idx_mls_compliance_info == 'yes') {
                                    if(!empty($ct_idx_agent_name)) {
                                        echo '<p class="marB5">';
                                            echo '<small class="muted">Listing Agent:</small>';
                                            echo ucwords($ct_idx_agent_name);
                                            if(!empty($ct_idx_agent_id)) {
                                                echo '<span class="ct-mls-compliance-agent-id"><br />';
                                                echo '#' . esc_html($ct_idx_agent_id) . '</span>';
                                            }
                                        echo '</p>';
                                    }
                                    if(has_term(array('sold'), 'ct_status', get_the_ID()) && !empty($ct_idx_selling_agent)) {
                                        echo '<p class="marB5">';
                                            echo '<small class="muted">Listing Sold by:</small>';
                                            echo ucwords($ct_idx_selling_agent) . '<br />';
                                            if(!empty($ct_brokerage_name)) {
                                                echo ucwords($ct_idx_co_selling_agent);
                                            }
                                        echo '</p>';
                                    }
                                    if(!empty($ct_brokerage_name)) {
                                        echo '<p>';
                                            if(has_term(array('sold'), 'ct_status', get_the_ID())) {
                                                echo '<small class="muted">Selling Office:</small>';
                                            } else {
                                                echo '<small class="muted">Offered By:</small>';
                                            }
                                            echo ucwords($ct_brokerage_name);
                                            if(!empty($ct_cpt_brokerage_phone) && $ct_listing_idx_mls_compliance_info_brokerage_phone == 'yes') {
                                                echo '<br />';
                                                echo esc_html($ct_cpt_brokerage_phone);
                                            }
                                        echo '</p>';
                                    }
                                }

                                // MLS List & Updated Dates
                                if(!empty($ct_idx_list_date) || !empty($ct_idx_mls_update_date)) {
                                    echo '<p>';
                                        if(!empty($ct_idx_list_date)) {
                                            echo '<span class="muted">' . __('Date Added:', 'contempo') . '</span> ' . esc_html($ct_idx_list_date);
                                        }
                                        if(!empty($ct_idx_mls_update_date)) {
                                            echo '<br /><span class="muted">' . __('Last Update:', 'contempo') . '</span> ' . esc_html($ct_idx_mls_update_date);
                                        }
                                    echo '</p>';
                                }
                            }

                            do_action('inside_single_listing_inner_content');
                            
                        echo '</div>';
                            echo '<div class="clear"></div>';

                        do_action('after_single_listing_inner_content');
                
                    break;

                    // CT IDX Pro Info
                    case 'listing_ct_idx_pro_info' :   

                        get_template_part('includes/single-listing-ct-idx-pro-info');
                
                    break;

                    // Floorplans
                    case 'listing_open_house' :

                        get_template_part('includes/single-listing-open-house');
                        
                    break;

                    // Floorplans
                    case 'listing_floorplans' :

                        get_template_part('includes/single-listing-multi-floorplan');
                        
                    break;

                    // Energy Efficiency
                    case 'listing_energy_efficiency' :

                       get_template_part('includes/single-listing-energy-efficiency');
                        
                    break;

                    // Rental Info
                    case 'listing_rental_info' :

                       get_template_part('includes/single-listing-rental-info');
                        
                    break;

                    // Features
                    case 'listing_features' :

                        if( empty($ct_idx_rupid) ) {
                            get_template_part('includes/single-listing-features');
                        }
                        
                    break;

                    // Booking Calendar
                    case 'listing_booking_calendar' :

                       get_template_part('includes/single-listing-booking-calendar');
                        
                    break;

                    // Attachments
                    case 'listing_attachments' :

                        get_template_part('includes/single-listing-attachments');
                        
                    break;

                    // Video
                    case 'listing_video' :

                        get_template_part('includes/single-listing-video');
                        
                    break;

                    // Virtual Tour
                    case 'listing_virtual_tour' :

                        get_template_part('includes/single-listing-virtual-tour');
                        
                    break;

                    // Virtual Tour
                    case 'listing_map' :

                        get_template_part('includes/single-listing-map');
                        
                    break;

                    // Yelp
                    case 'listing_yelp' :

                        get_template_part('includes/single-listing-yelp');

                    break;

                    // Reviews
                    case 'listing_reviews' :

                        get_template_part('includes/single-listing-reviews');
                        
                    break;
                
                }

            }
            
        }

    echo '</div>';

}