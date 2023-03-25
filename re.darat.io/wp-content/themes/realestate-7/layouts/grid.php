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
$paged                           = ct_currentPage();
$count                           = 0;
$ct_search_results_layout        = isset( $ct_options['ct_search_results_layout'] ) ? $ct_options['ct_search_results_layout'] : '';
$ct_search_results_listing_style = isset( $ct_options['ct_search_results_listing_style'] ) ? $ct_options['ct_search_results_listing_style'] : '';

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

			$listing_data = array( 'title' => get_the_title() ); ?>

	        <li data-listing-attribute="<?php echo esc_attr( wp_json_encode( $listing_data ) ); ?>"
	            class="listing col span_4 <?php echo esc_html( $ct_search_results_listing_style ); ?> 
				<?php if ( is_author() || strpos( ct_get_server_info('REQUEST_URI'), '/brokerage/') !== false) {
				    if ( $count % 3 == 0 ) {
					    echo 'first';
				    }
			    } else {
				    if ( $ct_search_results_layout == 'sidebyside' ) {
					    if ( $count % 2 == 0 ) {
						    echo 'first';
					    }
				    } else {
					    if ( $count % 3 == 0 ) {
						    echo 'first';
					    }
				    }
			    } ?> <?php if ( get_post_meta( $post->ID, "source", true ) == 'idx-api' ) {
				    echo 'idx-listing';
			    } ?>" data-listing-id="<?php echo absint( $post->ID ); ?>">

				<?php do_action( 'before_listing_grid_img' ); ?>

	            <figure class="listing-image">
	            	<?php ct_idx_mls_logo(); ?>
					<?php ct_status_featured(); ?>
					<?php ct_status(); ?>
					<?php ct_property_type_icon(); ?>
					<?php ct_listing_actions(); ?>
					<?php ct_first_image_linked(); ?>
	            </figure>

				<?php do_action( 'before_listing_grid_info' ); ?>

	            <div class="clear"></div>

	            <div class="grid-listing-info">
	                <header>
	                	<?php if($ct_search_results_listing_style == 'modern_two') { ?>
			                <p class="price"><a class="listing-link" <?php ct_listing_permalink(); ?> rel="<?php the_ID(); ?>"><?php ct_listing_price(); ?></a></p>
			                <?php do_action( 'before_listing_grid_propinfo' ); ?>
			                <div class="propinfo">
			                    <ul>
									<?php ct_propinfo(); ?>
			                    </ul>
			                </div>
			            <?php } ?>

		            	<?php do_action( 'before_listing_grid_title' ); ?>
	                
	                    <h5 class="listing-title">
	                        <a class="listing-link" <?php ct_listing_permalink(); ?> rel="<?php the_ID(); ?>">
								<?php ct_listing_title(); ?>
	                        </a>
	                    </h5>
						
						<?php do_action( 'before_listing_grid_address' ); ?>

	                    <p class="location muted marB0">
	                    	<?php if ( function_exists( 'city' ) ) {
								city();
							} ?>, <?php if ( function_exists( 'state' ) ) {
								state();
							} ?> <?php if ( function_exists( 'zipcode' ) ) {
								zipcode();
							} ?><?php if ( function_exists( 'country' ) ) {
								country();
							} ?>
						</p>
	                </header>

					<?php do_action( 'before_listing_grid_price' ); ?>

					<?php if($ct_search_results_listing_style != 'modern_two') { ?>
		                <p class="price marB0"><?php ct_listing_price(); ?></p>

						<?php do_action( 'before_listing_grid_propinfo' ); ?>

		                <div class="propinfo">
		                    <p><?php echo ct_excerpt(); ?></p>
		                    <ul class="marB0">
								<?php ct_propinfo(); ?>
		                    </ul>
		                </div>
		            <?php } ?>

					<?php if($ct_search_results_listing_style == 'modern' || $ct_search_results_listing_style == 'modern_two') {
						echo '<a rel="' . esc_attr( $post->ID ) . '" class="search-view-listing btn" ';
							ct_listing_permalink();
						echo '>';
							echo '<span>' . __( 'View', 'contempo' ) . '<i class="fas fa-chevron-right"></i></span>';
						echo '</a>';
					} ?>

					<?php do_action( 'after_listing_grid_info' ); ?>

					<?php ct_upcoming_open_house(); ?>
					<?php ct_listing_creation_date(); ?>
					<?php ct_listing_grid_agent_info(); ?>

	            </div>

				<?php ct_brokered_by(); ?>

	        </li>

			<?php

			$count ++;

			if(is_author() || strpos( ct_get_server_info('REQUEST_URI'), '/brokerage/') !== false) {
				if( $count % 3 == 0) {
					echo '<div class="clear"></div>';
				}
			} else {
				if($ct_search_results_layout == 'sidebyside') {
					if($count % 2 == 0) {
						echo '<div class="clear"></div>';
					}
				} else {
					if($count % 3 == 0) {
						echo '<div class="clear"></div>';
					}
				}
			}

		endwhile;

			ct_numeric_pagination();

	    echo '<div class="clear"></div>';

	endif;

	wp_reset_postdata();

echo '</ul>';