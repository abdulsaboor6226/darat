<?php
/**
 * The template for Element Button Add Listing.
 * This is the template that elementor element add listing button
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="wdk-element" id="wdk_el_<?php echo esc_html($id_element);?>">
    <?php if(!empty($this->data['settings']['custom_link']['url'])):?>
        <a <?php echo $this->get_render_attribute_string( 'custom_link'); ?> class="wdk-element-button">
    <?php else:?>
        <a href="<?php echo esc_attr(wmvc_show_data('link_url', $settings));?>" id="<?php echo esc_attr(wmvc_show_data('link_id', $settings));?>" class="wdk-element-button">
    <?php endif;?>

        <?php if(wmvc_show_data('link_icon_position', $settings) == 'left') :?>
            <?php \Elementor\Icons_Manager::render_icon( $settings['link_icon'], [ 'aria-hidden' => 'true' ] ); ?>
        <?php endif;?>
        <?php echo esc_html(wmvc_show_data('link_text', $settings));?>
        <?php if(wmvc_show_data('link_icon_position', $settings) == 'right') :?>
            <?php \Elementor\Icons_Manager::render_icon( $settings['link_icon'], [ 'aria-hidden' => 'true' ] ); ?>
        <?php endif;?>
    </a>
</div>

