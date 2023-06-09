<?php
function ct_fp_widget_init()
{
    function ct_fp_widget_view($args)
    {
        extract($args);
        $options = ct_fp_get_options();
        if (isset($options['widget_limit'])) {
            $limit = $options['widget_limit'];
        }
        $title = empty($options['widget_title']) ? 'Most Favorited Posts' : $options['widget_title'];
        echo $before_widget;
        echo $before_title . $title . $after_title;
        ct_fp_list_most_favorited($limit);
        echo $after_widget;
    }

    function ct_fp_widget_control()
    {
        $options = ct_fp_get_options();
        if (isset($_POST["wpfp-widget-submit"])) :
            $options['widget_title'] = strip_tags(stripslashes($_POST['wpfp-title']));
            $options['widget_limit'] = strip_tags(stripslashes($_POST['wpfp-limit']));
            update_option("wpfp_options", $options);
        endif;
        $title = $options['widget_title'];
        $limit = $options['widget_limit'];
?>
        <p>
            <label for="wpfp-title">
                <?php _e('Title:'); ?> <input type="text" value="<?php echo $title; ?>" class="widefat" id="wpfp-title" name="wpfp-title" />
            </label>
        </p>
        <p>
            <label for="wpfp-limit">
                <?php _e('Number of posts to show:'); ?> <input type="text" value="<?php echo $limit; ?>" style="width: 28px; text-align:center;" id="wpfp-limit" name="wpfp-limit" />
            </label>
        </p>
        <?php if (!$options['statistics']) { ?>
            <p>
                You must enable statistics from favorite posts <a href="plugins.php?page=wp-favorite-posts" title="Favorite Posts Configuration">configuration page</a>.
            </p>
        <?php } ?>
        <input type="hidden" name="wpfp-widget-submit" value="1" />
    <?php
    }
    wp_register_sidebar_widget('wpfp-most_favorited_posts', 'Most Favorited Posts', 'ct_fp_widget_view');
    wp_register_widget_control('wpfp-most_favorited_posts', 'Most Favorited Posts', 'ct_fp_widget_control');

    //*** users favorites widget ***//
    function ct_fp_users_favorites_widget_view($args)
    {
        extract($args);
        $options = ct_fp_get_options();
        if (isset($options['uf_widget_limit'])) {
            $limit = $options['uf_widget_limit'];
        }
        $title = empty($options['uf_widget_title']) ? 'Users Favorites' : $options['uf_widget_title'];
        echo $before_widget;
        echo $before_title
            . $title
            . $after_title;
        $favorite_post_ids = ct_fp_get_users_favorites();

        $limit = $options['uf_widget_limit'];
        if (@file_exists(TEMPLATEPATH . '/wpfp-your-favs-widget.php')) :
            include(TEMPLATEPATH . '/wpfp-your-favs-widget.php');
        else :
            include("wpfp-your-favs-widget.php");
        endif;
        echo $after_widget;
    }

    function ct_fp_users_favorites_widget_control()
    {
        $options = ct_fp_get_options();
        if (isset($_POST["wpfp-uf-widget-submit"])) :
            $options['uf_widget_title'] = strip_tags(stripslashes($_POST['wpfp-uf-title']));
            $options['uf_widget_limit'] = strip_tags(stripslashes($_POST['wpfp-uf-limit']));
            update_option("wpfp_options", $options);
        endif;
        $uf_title = $options['uf_widget_title'];
        $uf_limit = $options['uf_widget_limit'];
    ?>
        <p>
            <label for="wpfp-uf-title">
                <?php _e('Title:'); ?> <input type="text" value="<?php echo $uf_title; ?>" class="widefat" id="wpfp-uf-title" name="wpfp-uf-title" />
            </label>
        </p>
        <p>
            <label for="wpfp-uf-limit">
                <?php _e('Number of posts to show:'); ?> <input type="text" value="<?php echo $uf_limit; ?>" style="width: 28px; text-align:center;" id="wpfp-uf-limit" name="wpfp-uf-limit" />
            </label>
        </p>

        <input type="hidden" name="wpfp-uf-widget-submit" value="1" />
<?php
    }
    wp_register_sidebar_widget('wpfp-users_favorites', 'User\'s Favorites', 'ct_fp_users_favorites_widget_view');
    wp_register_widget_control('wpfp-users_favorites', 'User\'s Favorites', 'ct_fp_users_favorites_widget_control');
}
add_action('widgets_init', 'ct_fp_widget_init');
