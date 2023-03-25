<?php
/**
 * 404
 *
 * @package WP Pro Real Estate 7
 * @subpackage Template
 */

get_header(); ?>

<div class="container">

    <article class="col span_12">
    
        <h1><?php esc_html_e('Page Not Found', 'contempo'); ?></h1>
        <p class="lead"><?php esc_html_e('Woah there! Looks like you\'ve gotten lost.', 'contempo'); ?></p>
        <a class="btn" href="<?php echo home_url(); ?>"><?php esc_html_e('Go back to the homepage', 'contempo'); ?></a>

    </article>

</div>

<?php get_footer(); ?>