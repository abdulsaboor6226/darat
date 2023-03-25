<?php
/**
 * Single Listing Tools
 *
 * @package WP Pro Real Estate 7
 * @subpackage Include
 */

global $ct_options;
global $post;

$ct_single_listing_tools_layout = isset( $ct_options['ct_single_listing_tools_layout']['enabled'] ) ? $ct_options['ct_single_listing_tools_layout']['enabled'] : '';

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

$agent_mobile = get_the_author_meta('mobile', $author_id);

echo '<!-- Listing Tools -->';
echo '<div id="tools">';
    echo '<div id="call-email">';
        echo '<a class="btn marR5" href="tel:' . $agent_mobile . '">' . __('Call', 'contempo') . '</a>';
        echo '<a class="btn" href="#listingscontact">' . __('Email', 'contempo') . '</a>';
    echo '</div>';
    echo '<ul class="right social marB0">';

        if($ct_single_listing_tools_layout) {
                
            foreach($ct_single_listing_tools_layout as $key => $value) {

                switch($key) {

                    // Twitter
                    case 'listing_twitter' : ?>    

                         <li class="twitter"><a href="javascript:void(0);" onclick="popup('https://twitter.com/share?text=<?php esc_html_e('Check out this great listing on', 'contempo'); ?> <?php bloginfo('name'); ?> &mdash; <?php ct_listing_title(); ?>&url=<?php the_permalink(); ?>', 'twitter',500,260);"><i class="fab fa-twitter"></i></a></li>
                    
                    <?php break; ?>

                    <?php case 'listing_facebook' : ?>    
                        <?php 
                            $listing_title = ct_listing_title( false );
                            $params = array(
                                'u' => get_the_permalink( $post->ID ),
                                'quote' => $listing_title,
                            );
                            $fb_sharer_url = add_query_arg( $params, "https://www.facebook.com/sharer/sharer.php" );
                        ?>
                         <li class="facebook">
                            <a href="javascript:void(0);" onclick="popup('<?php echo esc_url( $fb_sharer_url ); ?>', 'facebook', 658,225);">
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                    <?php break; ?>

                    <?php case 'listing_linkedin' : ?>  
                        
                        <?php
                        /**
                         * LinkedIn Sharer.
                         */
                        $params = array(
                            'url' => get_the_permalink( $post->ID )
                        );
                        $linkedin_sharer_url = add_query_arg( $params, "https://www.linkedin.com/sharing/share-offsite");
                        ?>

                         <li class="linkedin"><a href="javascript:void(0);" onclick="popup('<?php echo esc_url( $linkedin_sharer_url ); ?>', 'linkedin',560,600);"><i class="fab fa-linkedin"></i></a></li>
                    
                    <?php break; ?>

                    <?php case 'listing_whatsapp' : ?>    

                         <li class="whatsapp"><a href="whatsapp://send?text=<?php the_permalink() ?> - <?php esc_html_e('Check out this great listing on', 'contempo'); ?> <?php bloginfo('name'); ?> &mdash; <?php ct_listing_title(); ?>"><i class="fab fa-whatsapp"></i></a></li>
                    
                    <?php break; ?>

                    <?php case 'listing_pinterest' : ?>    

                         <li class="pinterest"><a href="javascript:void(0);" onclick="popup('https://pinterest.com/pin/create/link/?url=<?php the_permalink(); ?>?description=<?php esc_html_e('Check out this great listing on', 'contempo'); ?> <?php bloginfo('name'); ?> &mdash; <?php ct_listing_title(); ?>', 'linkedin',560,400);"><i class="fab fa-pinterest"></i></a></li>
                    
                    <?php break; ?>

                    <?php case 'listing_print' : ?>    

                         <li class="print"><a class="print" href="javascript:window.print()"><i class="fas fa-print"></i></a></li>
                    
                    <?php break;

                }
            }

        }
               
    echo '</ul>';
echo '</div>';
echo '<!-- //Listing Tools -->';

?>