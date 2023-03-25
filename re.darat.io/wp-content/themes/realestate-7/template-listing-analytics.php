<?php
/**
 * Template Name: Listing Analytics
 *
 * @package WP Pro Real Estate 7
 * @subpackage Template
 */

if (session_status() == PHP_SESSION_NONE) { session_start(); } 

global $ct_options; 

$ct_boxed = isset( $ct_options['ct_boxed'] ) ? esc_attr( $ct_options['ct_boxed'] ) : '';
$submit_listing = isset( $ct_options['ct_submit'] ) ? esc_html( $ct_options['ct_submit'] ) : '';
$ct_enable_front_end_paid = isset( $ct_options['ct_enable_front_end_paid'] ) ? esc_attr( $ct_options['ct_enable_front_end_paid'] ) : '';
$ct_listing_stats_on_off = isset( $ct_options['ct_listing_stats_on_off'] ) ? esc_attr( $ct_options['ct_listing_stats_on_off'] ) : '';
$inside_page_title = get_post_meta($post->ID, "_ct_inner_page_title", true);
$edit = $ct_options['ct_edit'];
$userID = get_current_user_id();

get_header(); ?>

<div id="page-content" class="front-end-user-page <?php if($ct_boxed != 'full-width-two') { echo 'container'; } ?> <?php if(!is_user_logged_in()) { echo 'not-logged-in'; } ?>">

    <?php if(is_user_logged_in()) {
        get_template_part('/includes/user-sidebar');
    } ?>

    <article id="listing-analytics-wrap" class="col <?php if(is_user_logged_in()) { echo 'span_10'; } else { echo 'span_12 first card no-border no-hover-style'; } ?> marB60">

        <?php if(!is_user_logged_in()) {
                
                echo '<div class="inner-content">';
                    echo '<div class="must-be-logged-in">';
                        echo '<h4 class="center marB20">' . __('You must be logged in to view this page.', 'contempo') . '</h4>';
                        echo '<p class="center login-register-btn marB0"><a class="btn login-register" href="#">Login/Register</a></p>';
                    echo '</div>';
                echo '</div>';

            } else { ?>

            <?php the_content(); ?>
            
        <?php } ?>

    </article>
	
		<div class="clear"></div>

</div>

<?php get_footer(); ?>