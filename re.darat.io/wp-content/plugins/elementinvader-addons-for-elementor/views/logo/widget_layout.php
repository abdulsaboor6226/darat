<div class="widget-eli eli-logo" id="eli_<?php echo esc_html($this->get_id_int());?>">
    <div class="eli_container">
        <?php if (!empty($settings['custom_logo_image']['url']) ) : ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link">
                <img src="<?php echo esc_url( $settings['custom_logo_image']['url'] ) ?>" alt="<?php bloginfo( 'name' ); ?>">
            </a>
        <?php elseif (has_custom_logo() ) : ?>
            <?php if($settings['logo_image_footer_enable'] == 'yes' && get_theme_mod( 'footer_logo' )) :?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link">
                    <img src="<?php echo esc_url( get_theme_mod( 'footer_logo' ) ) ?>" alt="<?php bloginfo( 'name' ); ?>">
                </a>
            <?php else:?>
                <?php the_custom_logo(); ?>
            <?php endif;?>
        <?php else : ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="eli-logo-link-text" title="<?php bloginfo( 'name' ); ?>">
                <?php bloginfo( 'name' ); ?>
            </a>
        <?php endif; ?>
    </div>
</div>