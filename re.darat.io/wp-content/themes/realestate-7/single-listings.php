<?php
/**
 * Single Listings Template
 * Template Post Type: listings
 *
 * @package WP Pro Real Estate 7
 * @subpackage Template
 */

global $ct_options;
global $post;

$ct_single_listing_main_layout = isset( $ct_options['ct_single_listing_main_layout']['enabled'] ) ? $ct_options['ct_single_listing_main_layout']['enabled'] : '';

$ct_listing_single_layout = isset( $ct_options['ct_listing_single_layout'] ) ? esc_html( $ct_options['ct_listing_single_layout'] ) : '';
$ct_listing_single_content_layout = isset( $ct_options['ct_listing_single_content_layout'] ) ? esc_html( $ct_options['ct_listing_single_content_layout'] ) : '';
$ct_listing_single_sticky_sidebar = isset( $ct_options['ct_listing_single_sticky_sidebar'] ) ? $ct_options['ct_listing_single_sticky_sidebar'] : '';
$ct_listing_tools = isset( $ct_options['ct_listing_tools'] ) ? esc_html( $ct_options['ct_listing_tools'] ) : '';
$ct_listings_login_register = isset( $ct_options['ct_listings_login_register'] ) ? esc_html( $ct_options['ct_listings_login_register'] ) : '';

$author_id = $post->post_author;
$agent_mobile = get_the_author_meta('mobile', $author_id);

get_header();
 
if(!empty($_GET['search-listings'])) {
    get_template_part('search-listings');
    return;
}

$cat = get_the_category();

do_action('before_single_listing_header');

?>

<script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "Product",
  "name" : "<?php ct_listing_title(); ?> <?php city(); ?>, <?php state(); ?> <?php zipcode(); ?>",
  "description" : "<?php $content = get_the_content(); echo wp_filter_nohtml_kses( $content ); ?>",
  "offers" : {
    "@type" : "Offer",
    "price" : "<?php ct_listing_price(); ?>"
  }
}
</script>

<?php
// Header
echo '<header id="title-header"';
        if($ct_listing_single_layout == 'listings-two') { echo 'class="marB0"'; }
    echo '>';
    echo '<div class="container">';
        echo '<div class="left">';
            echo '<h5 class="marT0 marB0">';
                esc_html_e('Listings', 'contempo');
            echo '</h5>';
        echo '</div>';
        echo '<div class="breadcrumb breadcrumbs ct-breadcrumbs right">';
            echo '<a id="bread-home" href="'. home_url() . '" title="';
                echo bloginfo('name');
            echo '" rel="home" class="trail-begin">' . __('Home', 'contempo') . '</a>';
                echo ct_chevron_right_svg();
            echo '<a href="' . home_url() . '/?search-listings=true">';
                _e('Listings', 'contempo');
            echo '</a>';
                echo ct_chevron_right_svg();
            echo '<span class="trail-end">';
                ct_listing_title();
            echo '</span>';
        echo '</div>';
            echo '<div class="clear"></div>';
    echo '</div>';
echo '</header>';

do_action('before_single_listing_content'); 

// Start Loop 
if( have_posts() ) : while ( have_posts() ) : the_post(); ct_set_listing_views(get_the_ID());

// Listing Tools
if($ct_listing_tools == 'yes') { 
    get_template_part('includes/single-listing-tools');
} 
?>

<!-- FPO Header --> 
<?php 
    echo '<!-- FPO Site name -->';
    echo '<h4 id="sitename-for-print-only">';
        bloginfo('name');
    echo '</h4>';
?>
<!-- //FPO Header --> 

<!-- Lead Media -->
<?php if($ct_listing_single_layout == 'listings-two') { ?>

    <?php get_template_part('includes/single-listing-lead-media-lrg-carousel'); ?>
    
<?php } ?>

<?php if($ct_listing_single_layout == 'listings-three') { ?>
    
    <div id="single-listing-lead" class="container marB25">
        
        <div id="listings-three-header" class="col span_12 first">
            <?php get_template_part('includes/single-listing-header'); ?>
        </div>

        <div id="listings-three-slider" class="col span_12 first">
            <?php get_template_part('includes/single-listing-lead-media'); ?>
        </div>
            <div class="clear"></div>

    </div>

<?php } ?>

<?php if($ct_listing_single_layout == 'listings-four') { ?>
    
    <div id="single-listing-lead" class="container marB25">
        
        <div id="listings-three-header" class="col span_12 first">
            <?php get_template_part('includes/single-listing-header'); ?>
        </div>

        <div id="listings-four-slider" class="col span_12 first">
            <?php get_template_part('includes/single-listing-lead-media-with-contact'); ?>
        </div>
            <div class="clear"></div>

    </div>

<?php } ?>

<?php if($ct_listing_single_layout == 'listings-five') { ?>
    
    <div id="single-listing-lead" class="container marB25">
        
        <div id="listings-five-header" class="col span_12 first">
            <?php get_template_part('includes/single-listing-header'); ?>
        </div>

        <div id="listings-five-gallery" class="col span_12 first">
            <?php get_template_part('includes/single-listing-lead-media-with-contact-gallery-modal'); ?>
        </div>
            <div class="clear"></div>

    </div>

<?php } ?>

<?php

echo '<div id="single-listing-content" class="' . $ct_listing_single_layout . ' container">'; ?>

    <article class="col <?php if($ct_listing_single_content_layout == 'full-width') { echo 'span_12'; } else { echo 'span_9'; } ?> marB60">

    <?php 

        if($ct_single_listing_main_layout) {
    
                foreach($ct_single_listing_main_layout as $key => $value) {
                
                    switch($key) {

                        // Header
                        case 'listing_header' :    

                            if($ct_listing_single_layout != 'listings-three' && $ct_listing_single_layout != 'listings-four' && $ct_listing_single_layout != 'listings-five') {
                                get_template_part('includes/single-listing-header');
                            }
                        
                        break;

                        // Price
                        case 'listing_price' :    

                            if($ct_listing_single_layout != 'listings-three' && $ct_listing_single_layout != 'listings-four' && $ct_listing_single_layout != 'listings-five') {
                                get_template_part('includes/single-listing-price');
                            }
                        
                        break;

                        // Estimated Payment
                        case 'listing_est_payment' :    

                            if($ct_listing_single_layout != 'listings-three' && $ct_listing_single_layout != 'listings-four' && $ct_listing_single_layout != 'listings-five') {
                                get_template_part('includes/single-listing-estimated-payment');
                            }
                        
                        break;

                        // Prop Info
                        case 'listing_prop_info' :

                            get_template_part('includes/single-listing-propinfo');
                                echo '<div class="clear"></div>';
                            
                        break;

                        // Lead Media
                        case 'listing_lead_media' :

                            if($ct_listing_single_layout != 'listings-three' && $ct_listing_single_layout != 'listings-four' && $ct_listing_single_layout != 'listings-five') {
                                get_template_part('includes/single-listing-lead-media');
                            }
                            
                        break;

                        // Sub Navigation
                        case 'listing_nav' :

                           get_template_part('includes/single-listing-sub-navigation');
                            
                        break;

                        // Content
                        case 'listing_content' :

                            get_template_part('includes/single-listing-content');
                            
                        break;

                        // Contact
                        case 'listing_contact' :

                           get_template_part('includes/single-listing-contact');
                            
                        break;

                        // Creation Date
                        case 'listing_creation_date':

                            ct_listing_creation_date();
                    
                        break;

                        // Brokerage
                        case 'listing_brokerage' :

                            get_template_part('includes/single-listing-brokerage');
                            
                        break;

                        // Sub Listings
                        case 'listing_sub_listings' :

                            get_template_part('includes/single-listing-sub-listings');
                            
                        break;
                        
                        // Affordability Calculator
                        case 'ct_affordability_calculator' :
                            
                            get_template_part('includes/single-listing-affordability-calculator');

                        break;
                    
                    }
                }
            
            } ?>
        
                <div class="clear"></div>

        <?php do_action('after_single_listing_content'); ?>

    </article>

    <?php do_action('before_single_listing_sidebar'); ?>
    
    <?php if($ct_listing_single_content_layout != 'full-width') { ?>

        <div id="sidebar" class="col span_3<?php if($ct_listing_single_sticky_sidebar == 'yes') { echo ' sidebar-sticky'; } ?>">
            <div id="sidebar-inner">
                <?php if (is_active_sidebar('listings-single-right')) {
                    dynamic_sidebar('listings-single-right');
                } ?>
                    <div class="clear"></div>
            </div>
        </div>
    <?php } ?>

    <?php do_action('after_single_listing_sidebar'); ?>

        <div class="clear"></div>

</div>

<?php endwhile; endif; ?>
<!-- End Loop -->

<?php
wp_reset_postdata();

// IDX Disclaimers
if(class_exists('IDX')) {
    $oIDX = new IDX();
    $disclaimer = $oIDX->ct_idx_disclaimer_text();

    if($disclaimer != '') {
        echo '<div class="container">';
            echo '<div id="disclaimer" class="muted col span_12 first">';
                print wp_kses_post( $disclaimer, 'post' );
            echo '</div>';
        echo '</div>';
    }
}

get_footer(); ?>