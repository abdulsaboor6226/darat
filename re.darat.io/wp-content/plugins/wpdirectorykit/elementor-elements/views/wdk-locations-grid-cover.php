<?php
/**
 * The template for Element Locations Grid Cover.
 * This is the template that elementor element locations, images, links
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<?php
$results_page = wmvc_show_data('conf_link', $settings);
if(!is_array($results_page) && !empty($results_page)) {
    $results_page = get_permalink($results_page);
} else {
    $results_page = get_permalink(wdk_get_option('wdk_results_page'));
}
?>

<div class="wdk-element" id="wdk_el_<?php echo esc_html($id_element);?>">
    <div class="wdk-locations-grid-cover">
        <div class="wdk-row">
            <?php if(count($results) > 0):?>
                <?php foreach ($results as $key => $value):?>
                <div class="wdk-col">
                    <div class="wdk-locations-card-cover">
                        <?php if(wmvc_show_data('layout_image_type', $settings) == 'icon'):?>
                            <img src="<?php echo esc_url(wdk_image_src($value, 'full',NULL,'icon_id'));?>" alt="<?php echo wmvc_show_data('location_title', $value);?>" class="wdk-icon">
                        <?php else:?>
                            <img src="<?php echo esc_url(wdk_image_src($value, 'full',NULL,'image_id'));?>" alt="<?php echo wmvc_show_data('location_title', $value);?>" class="wdk-image">
                        <?php endif;?>
                        <div class="wdk-locations-card-body">
                            <div class="wdk-action-left">
                                <?php if(wmvc_show_data('content_icon_type', $settings) == 'image' && wdk_image_src($value, 'full', NULL,'icon_id')):?>
                                    <img src="<?php echo esc_url(wdk_image_src($value, 'full',NULL,'icon_id'));?>" alt="<?php echo wmvc_show_data('category_title', $value);?>">
                                <?php elseif(wmvc_show_data('content_icon_type', $settings) == 'font'):?>
                                    <i class="<?php echo wmvc_show_data('font_icon_code', $value,'');?>"></i>
                                <?php endif;?>
                            </div>
                            <div class="wdk-left-content">
                                <h3 class="wdk-title"><?php echo wmvc_show_data('location_title', $value);?></h3>
                                <span class="wdk-listings-count">
                                    <?php
                                        echo esc_html(sprintf(_nx(
                                                '%1$s Listing',
                                                '%1$s Listings',
                                                wmvc_show_data('listings_counter', $value, '0'),
                                                'profile listings count',
                                                'wpdirectorykit'
                                        ), wmvc_show_data('listings_counter', $value, '0')));
                                    ?>
                                </span>
                            </div>
                            <div class="wdk-action-right">
                                <a class="wdk-location-btn" href="<?php echo esc_url(wdk_url_suffix($results_page,'search_location='.wmvc_show_data('idlocation', $value)));?>#results">
                                    <?php \Elementor\Icons_Manager::render_icon( $settings['link_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                </a>
                            </div>
                        </div>
                        <a href="<?php echo esc_url(wdk_url_suffix($results_page,'search_location='.wmvc_show_data('idlocation', $value)));?>#results"  class="wdk-link"></a>
                        <div class="mask"></div>
                        <div class="overlay"></div>
                    </div>
                </div>
                <?php endforeach;?>
            <?php else:?>
                <div class="wdk-col wdk-col-full wdk-col-full-always">
                    <p class="wdk_alert wdk_alert-danger"><?php echo esc_html__('Locations not found', 'wpdirectorykit');?></p>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>
