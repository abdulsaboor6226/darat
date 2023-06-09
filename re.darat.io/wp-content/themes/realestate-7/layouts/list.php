<?php 
/**
 * Listings Loop
 *
 * @package WP Pro Real Estate 7
 * @subpackage Template
 */

global $ct_options;
global $paged;
global $wp_query;
$paged = ct_currentPage();

$ct_use_propinfo_icons = isset( $ct_options['ct_use_propinfo_icons'] ) ? esc_html( $ct_options['ct_use_propinfo_icons'] ) : '';
$ct_bed_beds_or_bedrooms = isset( $ct_options['ct_bed_beds_or_bedrooms'] ) ? esc_html( $ct_options['ct_bed_beds_or_bedrooms'] ) : '';
$ct_bath_baths_or_bathrooms = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? esc_html( $ct_options['ct_bath_baths_or_bathrooms'] ) : '';

echo '<ul id="search-listing-mapper">';

    if ( !$wp_query->have_posts() ) :
        
            echo '<div class="clear"></div>';
        
        if ( is_author() ) { 
            echo '<p class="nomatches">';
                echo '<strong>' . __( 'This agent currently has no active listings.', 'contempo' ) . '</strong>';
                echo '<br/>' . __( 'Check back soon.', 'contempo' );
            echo '</p>';
        } elseif ( 'brokerage' == get_post_type() ) {
            echo '<p class="nomatches">';
                echo '<strong>' . __( 'This brokerage currently has no active listings.', 'contempo' ) . '</strong>';
                echo '<br/>' . __( 'Check back soon.', 'contempo' );
            echo '</p>';
        } else {
            echo '<p class="nomatches">';
                echo '<strong>' . __( 'No properties were found which match your search criteria.', 'contempo' ) . '</strong>';
                echo '<br/>' . __( 'Try broadening your search to find more results.', 'contempo' );
            echo '</p>';
        }

    elseif ( $wp_query->have_posts() ) : 
            
        while ( $wp_query->have_posts() ) : $wp_query->the_post();

            $ct_source = get_post_meta($post->ID, 'source', true);

            $ct_idx_pro_assign_agents = get_option( 'ct_idx_pro_assign_agents' );
            $ct_idx_pro_assign_agents = isset( $ct_idx_pro_assign_agents ) ? $ct_idx_pro_assign_agents : '';
            $ct_idx_pro_assign_agents = json_decode($ct_idx_pro_assign_agents, true);

            if(!empty($ct_idx_pro_assign_agents) && $ct_source == 'idx-api') {
                
                foreach($ct_idx_pro_assign_agents as $agent) {
                    $ct_agent_first_name = get_user_meta($agent, 'first_name', true);
                    $ct_agent_last_name = get_user_meta($agent, 'last_name', true);
                    $ct_agent_display_name = $ct_agent_first_name . ' ' . $ct_agent_last_name;
                    $ct_agent_name_IDX = get_post_meta( $post->ID, '_ct_agent_name', true );

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

            $first_name = get_user_meta($author_id, 'first_name', true);
            $last_name = get_user_meta($author_id, 'last_name', true);
            $ct_profile_url = get_user_meta($author_id, 'ct_profile_url', true);

            $ct_property_type = strip_tags( get_the_term_list( $wp_query->post->ID, 'property_type', '', ', ', '' ) );
            $beds = strip_tags( get_the_term_list( $wp_query->post->ID, 'beds', '', ', ', '' ) );
            $baths = strip_tags( get_the_term_list( $wp_query->post->ID, 'baths', '', ', ', '' ) );

            ?>

        	 <?php $listing_data = array( 'title' => get_the_title() ); ?>

             <li data-listing-attribute="<?php echo esc_attr( wp_json_encode( $listing_data ) ); ?>" class="listing listing-list col span_12 first <?php if(get_post_meta($post->ID, "source", true) == 'idx-api') { echo 'idx-listing'; } ?>"  data-listing-id="<?php echo absint( $post->ID ); ?>">

                <?php do_action('before_listing_list_img'); ?>

                <figure class="col span_4 first">
                    <?php ct_idx_mls_logo(); ?>
                    <?php ct_status_featured(); ?>
                    <?php ct_status(); ?>
                    <?php ct_property_type_icon(); ?>
                    <?php ct_listing_actions(); ?>
                    <?php ct_first_image_linked(); ?>
                </figure>

                <?php do_action('before_listing_list_info'); ?>

                <div class="list-listing-info col span_8 first">
                    <div class="list-listing-info-inner">
                        <header>
                            <?php do_action('before_listing_list_title'); ?>
                            <h4 class="marT0 marB0"><a class="list-listing-title-a" rel="<?php echo esc_attr($post->ID); ?>" <?php ct_listing_permalink(); ?>><?php ct_listing_title(); ?></a></h4>
                            <?php do_action('before_listing_grid_address'); ?>
                            <p class="location muted marB0"><?php if(function_exists('city')) { city(); } ?>, <?php if(function_exists('state')) { state(); } ?> <?php if(function_exists('zipcode')) { zipcode(); } ?> <?php if(function_exists('country')) { country(); } ?></p>
                        </header>
                        
                        <?php do_action('before_listing_list_price'); ?>
                        
                        <p class="price marB10"><?php ct_listing_price(); ?></p>

                        <?php do_action('before_listing_list_propinfo'); ?>
                        
                        <p class="listing-list-excerpt marB0"><?php echo ct_excerpt(); ?></p>

                        <ul class="propinfo propinfo-list marB0 padT0">
                            <?php if($ct_property_type != 'commercial' || $ct_property_type != 'industrial' || $ct_property_type != 'retail' || $ct_property_type != 'lot' || $ct_property_type != 'land') { 
                                $ct_bath_baths_or_bathrooms = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? esc_html( $ct_options['ct_bath_baths_or_bathrooms'] ) : '';
                                if(!empty($beds)) {
                                        echo '<li class="row beds">';
                                            echo '<span class="muted left">';
                                                if($ct_use_propinfo_icons != 'icons') {
                                                    if($ct_bed_beds_or_bedrooms == 'rooms') {
                                                        _e('Rooms', 'contempo');
                                                    } elseif($ct_bed_beds_or_bedrooms == 'bedrooms') {
                                                        _e('Bedrooms', 'contempo');
                                                    } elseif($ct_bed_beds_or_bedrooms == 'beds') {
                                                        _e('Beds', 'contempo');
                                                    } else {
                                                        _e('Bed', 'contempo');
                                                    }
                                                } else {
                                                    echo '<i class="fa fa-bed"></i>';
                                                }
                                            echo '</span>';
                                            echo '<span class="right">';
                                               echo esc_html($beds);
                                            echo '</span>';
                                        echo '</li>';
                                    }   
                                    if(!empty($baths)) {
                                        echo '<li class="row baths">';
                                            echo '<span class="muted left">';
                                                if($ct_use_propinfo_icons != 'icons') {
                                                    if($ct_bath_baths_or_bathrooms == 'bathrooms') {
                                                        _e('Bathrooms', 'contempo');
                                                    } elseif($ct_bath_baths_or_bathrooms == 'baths') {
                                                        _e('Baths', 'contempo');
                                                    } else {
                                                        _e('Bath', 'contempo');
                                                    }
                                                } else {
                                                    echo '<i class="fa fa-bath"></i>';
                                                }
                                            echo '</span>';
                                            echo '<span class="right">';
                                               echo esc_html($baths);
                                            echo '</span>';
                                        echo '</li>';
                                }
                            } ?>
                            <?php if(!empty($ct_property_type)) {
                                echo '<li class="property-type">';
                                    echo '<span class="muted left">';
                                        _e('Type:', 'contempo');
                                    echo '</span>';
                                    echo '<span class="right">';
                                       echo esc_html($ct_property_type);
                                    echo '</span>';
                                echo '</li>';
                            } ?>
                        </ul>

                        <div class="col span_12 first list-agent-info">
                            <?php
                            echo '<figure class="col span_1 first list-agent-image">';
                                echo '<a href="' . get_author_posts_url($author_id) . '">';
                                   if(!empty($ct_profile_url)) {  
                                        echo '<img class="authorimg" src="';
                                            echo esc_url($ct_profile_url);
                                        echo '" />';
                                    } else {
                                        echo '<img class="author-img" src="' . get_template_directory_uri() . '/images/user-default.png' . '" />';
                                    }
                                echo '</a>';
                            echo '</figure>';
                            ?>
                            <div class="col span_4">
                                <p class="muted marB0"><small><?php _e('Agent', 'contempo'); ?></small></p>
                                <p class="marB0"><a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo esc_html($first_name); ?> <?php echo esc_html($last_name); ?></a></p>
                            </div>
                            <div class="col span_6">
                                <?php ct_brokered_by(); ?>
                            </div>
                        </div>
                            <div class="clear"></div>
                    </div>
                </div>

                <?php do_action('after_listing_list_info'); ?>
        	
            </li>
            
        <?php

            echo '<div class="clear"></div>';

        endwhile;

        ct_numeric_pagination();

            echo '<div class="clear"></div>';

    endif;

    wp_reset_postdata();

echo '</ul>';