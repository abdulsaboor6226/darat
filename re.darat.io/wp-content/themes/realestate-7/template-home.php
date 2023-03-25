<?php
/**
 * Template Name: Home
 *
 * @package WP Pro Real Estate 7
 * @subpackage Template
 */
 
if (!empty($_GET['search-listings'])) {
	get_template_part('search-listings');
	return;
}

$ct_home_elementor_id = isset( $ct_options['ct_home_elementor_id'] ) ? esc_attr( $ct_options['ct_home_elementor_id'] ) : '';

get_header();
	         	
 	echo '<!-- Elementor -->';
    echo '<section class="elementor ' . esc_html($ct_home_elementor_id) . '">';
			$args = array(
				'post_type' => 'page',
				'post__in' => array($ct_home_elementor_id)
			);
			$query = new WP_Query( $args );
			while ($query -> have_posts()) : $query -> the_post();
				the_content();
			endwhile; wp_reset_postdata();
		echo'<div class="clear"></div>';
    echo'</section>';
    echo '<!-- //Elementor -->';
	
get_footer(); ?>