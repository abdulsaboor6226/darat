<?php
/**
 * The template for Element Listings Carousel.
 * This is the template that elementor element carousel
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="wdk-element" id="wdk_el_<?php echo esc_html($id_element);?>">
    <div class="wdk-listings-results <?php echo wmvc_show_data('styles_thmbn_des_type',$settings, '');?>">
        <?php if(count($results) > 0):?>
            <?php if(!empty($results) && wmvc_show_data('layout_carousel_columns', $settings,1) < wmvc_count($results)):?>
                <div class="wdk_results_listings_slider_box <?php echo esc_attr($settings['layout_carousel_animation_style']).'_animation';?> <?php echo esc_attr(join(' ', [$settings['styles_carousel_dots_position_style'], $settings['styles_carousel_arrows_position_style'],$settings['styles_carousel_arrows_position'],$settings['styles_carousel_arrows_position_style']]));?>">
                <div class="wdk_results_listings_slider_ini">
            <?php else:?>
                <div class="wdk-row">
            <?php endif;?>
            <?php foreach($results as $listing):?>
                <div class="wdk-col">
                    <?php echo wdk_listing_card($listing, $settings);?>
                </div>
            <?php endforeach;?> 

            <?php if(!empty($results) && wmvc_show_data('layout_carousel_columns', $settings,1) < wmvc_count($results)):?>
                </div>
                    <div class="wdk-categories-carousel_arrows">
                        <a class="wdk-slider-prev wdk_lr_slider_arrow">
                            <?php \Elementor\Icons_Manager::render_icon( $settings['styles_carousel_arrows_icon_left'], [ 'aria-hidden' => 'true' ] ); ?>
                        </a>
                        <a class="wdk-slider-next wdk_lr_slider_arrow">
                            <?php \Elementor\Icons_Manager::render_icon( $settings['styles_carousel_arrows_icon_right'], [ 'aria-hidden' => 'true' ] ); ?>
                        </a>
                    </div>
                </div>
            <?php else:?>
                </div>
            <?php endif;?>
        <?php else:?>
            <p class="wdk_alert wdk_alert-danger"><?php echo esc_html__('Results not found', 'wpdirectorykit');?></p>
        <?php endif;?>
    </div>
    <script>
        jQuery(document).ready(function($){
            $('#wdk_el_<?php echo esc_html($id_element);?> .wdk_results_listings_slider_ini').slick({
                <?php if(!empty($results) && wmvc_show_data('layout_carousel_columns', $settings,1) < wmvc_count($results)):?>
                dots: true,
                arrows: true,
                <?php else:?>
                dots: false,
                arrows: false,
                <?php endif;?>
                slidesToShow: <?php echo wmvc_show_data('layout_carousel_columns', $settings, 1);?>,
                slidesToScroll: <?php echo wmvc_show_data('layout_carousel_columns', $settings,1);?>,
                <?php if(!empty(wmvc_show_data('layout_carousel_is_infinite', $settings))):?>
                infinite: <?php echo wmvc_show_data('layout_carousel_is_infinite', $settings, 'true');?>,
                <?php endif;?>
                <?php if(!empty(wmvc_show_data('layout_carousel_is_autoplay', $settings))):?>
                autoplay: <?php echo wmvc_show_data('layout_carousel_is_autoplay', $settings, 'false');?>,
                <?php endif;?>
                nextArrow: $('#wdk_el_<?php echo esc_html($id_element);?> .wdk-categories-carousel_arrows .wdk-slider-next'),
                prevArrow: $('#wdk_el_<?php echo esc_html($id_element);?> .wdk-categories-carousel_arrows .wdk-slider-prev'),
                customPaging: function(slider, i) {
                    // this example would render "tabs" with titles
                    return '<span class="wdk_lr_dot"><?php \Elementor\Icons_Manager::render_icon( $settings['styles_carousel_dots_position_style'], [ 'aria-hidden' => 'true' ] ); ?></span>';
                },
                responsive: [
                    {
                    breakpoint: 600,
                    settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                ]
            });
        })
    </script>
</div>

