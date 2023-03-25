<?php
/*
Plugin Name: Contempo Favorite Listings
Plugin URI: https://contempothemes.com
Description: Allows users to add favorite listings.
Version: 2.0.6
Author: Contempo
Author URI: http://www.contempothemes.com

*/

define('WPFP_PATH', plugins_url() . '/ct-favorite-listings');
define('WPFP_META_KEY', "wpfp_favorites");
define('WPFP_USER_OPTION_KEY', "wpfp_useroptions");
define('WPFP_COOKIE_KEY', "wp-favorite-posts");

// manage default privacy of users favorite post lists by adding this constant to wp-config.php
if (!defined('WPFP_DEFAULT_PRIVACY_SETTING'))
    define('WPFP_DEFAULT_PRIVACY_SETTING', false);


include("ct-deactivator.php");

$ajax_mode = 1;

function ct_fp_load_translation()
{
    load_plugin_textdomain(
        "ct-favorite-listings",
        false,
        dirname(plugin_basename(__FILE__)) . '/lang'
    );
}

add_action('plugins_loaded', 'ct_fp_load_translation');

function ct_fp_favorite_posts()
{
    if (isset($_REQUEST['wpfpaction'])) :
        global $ajax_mode;
        $ajax_mode = isset($_REQUEST['ajax']) ? $_REQUEST['ajax'] : false;
        if ($_REQUEST['wpfpaction'] == 'add') {
            ct_fp_add_favorite();
        } else if ($_REQUEST['wpfpaction'] == 'remove') {
            ct_fp_remove_favorite();
        } else if ($_REQUEST['wpfpaction'] == 'clear') {
            if (ct_fp_clear_favorites()) ct_fp_die_or_go(ct_fp_get_option('cleared'));
            else ct_fp_die_or_go("ERROR");
        }
    endif;
}
add_action('wp_loaded', 'ct_fp_favorite_posts');

function ct_fp_add_favorite($post_id = "")
{
    if (empty($post_id)) $post_id = $_REQUEST['postid'];
    if (ct_fp_get_option('opt_only_registered') && !is_user_logged_in()) {
        ct_fp_die_or_go(ct_fp_get_option('text_only_registered'));
        return false;
    }

    if (ct_fp_do_add_to_list($post_id)) {
        // added, now?
        do_action('ct_fp_add_favorites', $post_id);
        do_action('wpfp_after_add', $post_id);
        if (ct_fp_get_option('statistics')) ct_fp_update_post_meta($post_id, 1);
        if (ct_fp_get_option('added') == 'show remove link') {
            $str = ct_fp_link(1, "remove", 0, array('post_id' => $post_id));
            ct_fp_die_or_go($str);
        } else {
            ct_fp_die_or_go(ct_fp_get_option('added'));
        }
    }
}
function ct_fp_do_add_to_list($post_id)
{
    if (ct_fp_check_favorited($post_id))
        return false;
    if (is_user_logged_in()) {
        return ct_fp_add_to_usermeta($post_id);
    } else {
        return ct_fp_set_cookie($post_id, "added");
    }
}

function ct_fp_remove_favorite($post_id = "")
{
    if (empty($post_id)) $post_id = $_REQUEST['postid'];
    if (ct_fp_do_remove_favorite($post_id)) {
        // removed, now?
        do_action('ct_fp_remove_favorites', $post_id);
        do_action('wpfp_after_remove', $post_id);
        if (ct_fp_get_option('statistics')) ct_fp_update_post_meta($post_id, -1);
        if (ct_fp_get_option('removed') == 'show add link') {
            if (isset($_REQUEST['page']) && $_REQUEST['page'] == 1) :
                $str = '';
            else :
                $str = ct_fp_link(1, "add", 0, array('post_id' => $post_id));
            endif;
            ct_fp_die_or_go($str);
        } else {
            ct_fp_die_or_go(ct_fp_get_option('removed'));
        }
    } else return false;
}

function ct_fp_die_or_go($str)
{
    global $ajax_mode;
    if ($ajax_mode) :
        die($str);
    else :
        wp_redirect($_SERVER['HTTP_REFERER']);
    endif;
}

function ct_fp_add_to_usermeta($post_id)
{

    $wpfp_favorites = ct_fp_get_user_meta();

    if (empty($wpfp_favorites) || !is_array($wpfp_favorites)) {
        $wpfp_favorites = array();
    }

    $wpfp_favorites[] = $post_id;
    ct_fp_update_user_meta($wpfp_favorites);
    return true;
}

function ct_fp_check_favorited($cid)
{
    if (is_user_logged_in()) {
        $favorite_post_ids = ct_fp_get_user_meta();
        if ($favorite_post_ids)
            foreach ($favorite_post_ids as $fpost_id)
                if ($fpost_id == $cid) return true;
    } else {
        if (ct_fp_get_cookie()) :
            foreach (ct_fp_get_cookie() as $fpost_id => $val)
                if ($fpost_id == $cid) return true;
        endif;
    }
    return false;
}

function ct_fp_link($return = 0, $action = "", $show_span = 1, $args = array())
{
    global $post;
    //print_r($post);
    $post_id = &$post->ID;
    extract($args);
    $str = "";
    if ($show_span)
        $str = "<span class='wpfp-span'>";
    $str .= ct_fp_before_link_img();
    $str .= ct_fp_loading_img();
    if ($action == "remove") :
        $str .= ct_fp_link_html($post_id, ct_fp_get_option('remove_favorite'), "remove");
    elseif ($action == "add") :
        $str .= ct_fp_link_html($post_id, ct_fp_get_option('add_favorite'), "add");
    elseif (ct_fp_check_favorited($post_id)) :
        $str .= ct_fp_link_html($post_id, ct_fp_get_option('remove_favorite'), "remove");
    else :
        $str .= ct_fp_link_html($post_id, ct_fp_get_option('add_favorite'), "add");
    endif;
    if ($show_span)
        $str .= "</span>";
    if ($return) {
        return $str;
    } else {
        echo $str;
    }
}

function ct_fp_link_text($return = 0, $action = "", $show_span = 1, $args = array())
{
    global $post;
    
    $ct_remove_favorite_text = '<i class="fa fa-heart"></i>' . __('Saved', 'ct-favorite-listings');
    $ct_add_favorite_text = '<i class="fa fa-heart-o"></i>' . __('Save', 'ct-favorite-listings');

    //print_r($post);
    $post_id = &$post->ID;
    extract($args);
    $str = "";
    if ($show_span)
        $str = "<span class='wpfp-span'>";
    $str .= ct_fp_before_link_img();
    $str .= ct_fp_loading_img();
    if ($action == "remove") :
        $str .= ct_fp_link_html($post_id, $ct_remove_favorite_text, "remove");
    elseif ($action == "add") :
        $str .= ct_fp_link_html($post_id, $ct_add_favorite_text, "add");
    elseif (ct_fp_check_favorited($post_id)) :
        $str .= ct_fp_link_html($post_id, $ct_remove_favorite_text, "remove");
    else :
        $str .= ct_fp_link_html($post_id, $ct_add_favorite_text, "add");
    endif;
    if ($show_span)
        $str .= "</span>";
    if ($return) {
        return $str;
    } else {
        echo $str;
    }
}

if (!function_exists('wpfp_link')) {
    function wpfp_link($return = 0, $action = "", $show_span = 1, $args = array())
    {
        return ct_fp_link($return, $action, $show_span, $args);
    }
}

if (!function_exists('wpfp_link_text')) {
    function wpfp_link_text($return = 0, $action = "", $show_span = 1, $args = array())
    {
        return ct_fp_link_text($return, $action, $show_span, $args);
    }
}

function ct_fp_link_html($post_id, $opt, $action)
{
    $link = "<a class='wpfp-link' href='?wpfpaction=" . $action . "&amp;postid=" . esc_attr($post_id) . "' title='" . $opt . "' rel='nofollow'>" . $opt . "</a>";
    $link = apply_filters('wpfp_link_html', $link);
    return $link;
}

function ct_fp_get_users_favorites($user = "")
{
    $favorite_post_ids = array();

    if (!empty($user)) :
        return ct_fp_get_user_meta($user);
    endif;

    # collect favorites from cookie and if user is logged in from database.
    if (is_user_logged_in()) :
        $favorite_post_ids = ct_fp_get_user_meta();
    else :
        if (ct_fp_get_cookie()) :
            foreach (ct_fp_get_cookie() as $post_id => $post_title) {
                array_push($favorite_post_ids, $post_id);
            }
        endif;
    endif;
    return $favorite_post_ids;
}

function ct_fp_list_favorite_posts($args = array())
{
    $user = isset($_REQUEST['user']) ? $_REQUEST['user'] : "";
    extract($args);
    global $favorite_post_ids;
    if (!empty($user)) {
        if (ct_fp_is_user_favlist_public($user))
            $favorite_post_ids = ct_fp_get_users_favorites($user);
    } else {
        $favorite_post_ids = ct_fp_get_users_favorites();
    }

    if (@file_exists(TEMPLATEPATH . '/wpfp-page-template.php') || @file_exists(STYLESHEETPATH . '/wpfp-page-template.php')) :
        if (@file_exists(TEMPLATEPATH . '/wpfp-page-template.php')) :
            include(TEMPLATEPATH . '/wpfp-page-template.php');
        else :
            include(STYLESHEETPATH . '/wpfp-page-template.php');
        endif;
    else :
        include("wpfp-page-template.php");
    endif;
}

function ct_fp_list_most_favorited($limit = 5)
{
    global $wpdb;
    $query = "SELECT post_id, meta_value, post_status FROM $wpdb->postmeta";
    $query .= " LEFT JOIN $wpdb->posts ON post_id=$wpdb->posts.ID";
    $query .= " WHERE post_status='publish' AND meta_key='" . WPFP_META_KEY . "' AND meta_value > 0 ORDER BY ROUND(meta_value) DESC LIMIT 0, $limit";
    $results = $wpdb->get_results($query);
    if ($results) {
        echo "<ul>";
        foreach ($results as $o) :
            $p = get_post($o->post_id);
            echo "<li>";
            echo "<a href='" . get_permalink($o->post_id) . "' title='" . $p->post_title . "'>" . $p->post_title . "</a> ($o->meta_value)";
            echo "</li>";
        endforeach;
        echo "</ul>";
    }
}

include("wpfp-widgets.php");

function ct_fp_loading_img()
{
    return "<img src='" . WPFP_PATH . "/img/loading.gif' alt='Loading' title='Loading' class='wpfp-hide wpfp-img' />";
}

function ct_fp_before_link_img()
{
    $options = ct_fp_get_options();
    $option = $options['before_image'];
    if ($option == '') {
        return "";
    } else if ($option == 'custom') {
        return "<img src='" . $options['custom_before_image'] . "' alt='Favorite' title='Favorite' class='wpfp-img' />";
    } else {
        return "<img src='" . WPFP_PATH . "/img/" . $option . "' alt='Favorite' title='Favorite' class='wpfp-img' />";
    }
}

function ct_fp_clear_favorites()
{
    if (ct_fp_get_cookie()) :
        foreach (ct_fp_get_cookie() as $post_id => $val) {
            ct_fp_set_cookie($post_id, "");
            ct_fp_update_post_meta($post_id, -1);
        }
    endif;
    if (is_user_logged_in()) {
        $favorite_post_ids = ct_fp_get_user_meta();
        if ($favorite_post_ids) :
            foreach ($favorite_post_ids as $post_id) {
                ct_fp_update_post_meta($post_id, -1);
            }
        endif;
        if (!delete_user_meta(ct_fp_get_user_id(), WPFP_META_KEY)) {
            return false;
        }
    }
    return true;
}

function ct_fp_do_remove_favorite($post_id)
{
    if (!ct_fp_check_favorited($post_id))
        return true;

    $a = true;
    if (is_user_logged_in()) {
        $user_favorites = ct_fp_get_user_meta();
        $user_favorites = array_diff($user_favorites, array($post_id));
        $user_favorites = array_values($user_favorites);
        $a = ct_fp_update_user_meta($user_favorites);
    }
    if ($a) $a = ct_fp_set_cookie($_REQUEST['postid'], "");
    return $a;
}

function ct_fp_content_filter($content)
{
    if (is_page()) :
        if (strpos($content, '{{wp-favorite-posts}}') !== false) {
            $content = str_replace('{{wp-favorite-posts}}', ct_fp_list_favorite_posts(), $content);
        }
    endif;

    if (strpos($content, '[wpfp-link]') !== false) {
        $content = str_replace('[wpfp-link]', ct_fp_link(1), $content);
    }

    if (is_single()) {
        if (ct_fp_get_option('autoshow') == 'before') {
            $content = ct_fp_link(1) . $content;
        } else if (ct_fp_get_option('autoshow') == 'after') {
            $content .= ct_fp_link(1);
        }
    }
    return $content;
}
add_filter('the_content', 'ct_fp_content_filter');

function ct_fp_shortcode_func()
{
    ct_fp_list_favorite_posts();
}
add_shortcode('wp-favorite-posts', 'ct_fp_shortcode_func');


function ct_fp_add_js_script()
{
    if (!ct_fp_get_option('dont_load_js_file'))
        wp_enqueue_script("wp-favorite-posts", WPFP_PATH . "/wpfp.js", array('jquery'));
}
add_action('wp_print_scripts', 'ct_fp_add_js_script');

function ct_fp_wp_print_styles()
{
    if (!ct_fp_get_option('dont_load_css_file'))
        echo "<link rel='stylesheet' id='wpfp-css' href='" . WPFP_PATH . "/wpfp.css' type='text/css' />" . "\n";
}
add_action('wp_print_styles', 'ct_fp_wp_print_styles');

function ct_fp_init()
{
    $wpfp_options = array();
    $wpfp_options['add_favorite'] = "&lt;i class=&quot;fa fa-heart-o&quot;&gt;&lt;/i&gt;";
    $wpfp_options['added'] = "&lt;i class=&quot;fa fa-heart&quot;&gt;&lt;/i&gt;";
    $wpfp_options['remove_favorite'] = "&lt;i class=&quot;fa fa-heart&quot;&gt;&lt;/i&gt;";
    $wpfp_options['removed'] = "&lt;i class=&quot;fa fa-heart-o&quot;&gt;&lt;/i&gt;";
    $wpfp_options['clear'] = "Clear favorites";
    $wpfp_options['cleared'] = "<p class=\"marT5\">Favorites cleared!</p>";
    $wpfp_options['favorites_empty'] = "Favorite list is empty.";
    $wpfp_options['cookie_warning'] = "Your favorite listings are saved to your browsers cookies. If you clear cookies also favorite listings will be deleted.";
    $wpfp_options['rem'] = "remove";
    $wpfp_options['text_only_registered'] = "Only registered users can favorite!";
    $wpfp_options['statistics'] = 1;
    $wpfp_options['widget_title'] = '';
    $wpfp_options['widget_limit'] = 5;
    $wpfp_options['uf_widget_limit'] = 5;
    $wpfp_options['before_image'] = '';
    $wpfp_options['custom_before_image'] = '';
    $wpfp_options['dont_load_js_file'] = 1;
    $wpfp_options['dont_load_css_file'] = 0;
    $wpfp_options['post_per_page'] = 20;
    $wpfp_options['autoshow'] = '';
    $wpfp_options['opt_only_registered'] = 0;
    add_option('wpfp_options', $wpfp_options);
}
add_action('activate_ct-favorite-listings/ct-favorite-listings.php', 'ct_fp_init');

function ct_fp_config()
{
    include('wpfp-admin.php');
}

function ct_fp_config_page()
{
    if (function_exists('add_submenu_page'))
        add_options_page(__('Contempo Favorite Listings'), __('CT Favorite Listings'), 'manage_options', 'wp-favorite-listings', 'ct_fp_config');
}
add_action('admin_menu', 'ct_fp_config_page');

function ct_fp_update_user_meta($arr)
{
    return update_user_meta(ct_fp_get_user_id(), WPFP_META_KEY, $arr);
}

function ct_fp_update_post_meta($post_id, $val)
{
    $oldval = ct_fp_get_post_meta($post_id);
    $oldval = is_numeric($oldval) ? $oldval : 0;
    
    if ($val == -1 && $oldval == 0) {
        $val = 0;
    } else {
        $val = $oldval + $val;
    }
    return add_post_meta($post_id, WPFP_META_KEY, $val, true) or update_post_meta($post_id, WPFP_META_KEY, $val);
}

function ct_fp_delete_post_meta($post_id)
{
    return delete_post_meta($post_id, WPFP_META_KEY);
}

function ct_fp_get_cookie()
{
    if (!isset($_COOKIE[WPFP_COOKIE_KEY])) return;
    return $_COOKIE[WPFP_COOKIE_KEY];
}

function ct_fp_get_options()
{
    return get_option('wpfp_options');
}

function ct_fp_get_user_id()
{
    global $current_user;
    $current_user = wp_get_current_user();
    return $current_user->ID;
}

function ct_fp_get_user_meta($user = "")
{
    if (!empty($user)) :
        $userdata = get_user_by('login', $user);
        $user_id = $userdata->ID;
        return get_user_meta($user_id, WPFP_META_KEY, true);
    else :
        return get_user_meta(ct_fp_get_user_id(), WPFP_META_KEY, true);
    endif;
}

function ct_fp_get_post_meta($post_id)
{
    $val = get_post_meta($post_id, WPFP_META_KEY, true);
    if ($val < 0) $val = 0;
    return $val;
}

function ct_fp_set_cookie($post_id, $str)
{
    $expire = time() + 60 * 60 * 24 * 30;
    return setcookie("wp-favorite-posts[$post_id]", $str, $expire, "/");
}

function ct_fp_is_user_favlist_public($user)
{
    $user_opts = ct_fp_get_user_options($user);
    if (empty($user_opts)) return WPFP_DEFAULT_PRIVACY_SETTING;
    if ($user_opts["is_wpfp_list_public"])
        return true;
    else
        return false;
}


if (!function_exists('wpfp_is_user_favlist_public')) {
    function wpfp_is_user_favlist_public($user)
    {
        return ct_fp_is_user_favlist_public($user);
    }
}

if (!function_exists('wpfp_get_options')) {
    function wpfp_get_options()
    {
        return ct_fp_get_options();
    }
}
if (!function_exists('wpfp_get_option')) {
    function wpfp_get_option($opt)
    {
        return ct_fp_get_option($opt);
    }
}
if (!function_exists('wpfp_remove_favorite_link')) {
    function wpfp_remove_favorite_link($post_id)
    {
        return ct_fp_remove_favorite_link($post_id);
    }
}
if (!function_exists('wpfp_clear_list_link')) {
    function wpfp_clear_list_link()
    {
        return ct_fp_clear_list_link();
    }
}
if (!function_exists('wpfp_get_users_favorites')) {
    function wpfp_get_users_favorites($user = '')
    {
        return ct_fp_get_users_favorites($user);
    }
}

function ct_fp_get_user_options($user)
{
    $userdata = get_user_by('login', $user);
    $user_id = $userdata->ID;
    return get_user_meta($user_id, WPFP_USER_OPTION_KEY, true);
}

function ct_fp_is_user_can_edit()
{
    if (isset($_REQUEST['user']) && $_REQUEST['user'])
        return false;
    return true;
}

function ct_fp_remove_favorite_link($post_id)
{
    if (ct_fp_is_user_can_edit()) {
        $wpfp_options = ct_fp_get_options();
        $class = 'wpfp-link remove-parent';
        $link = "<a id='rem_$post_id' class='$class' href='?wpfpaction=remove&amp;page=1&amp;postid=" . $post_id . "' title='" . ct_fp_get_option('rem') . "' rel='nofollow'><i class=\"fa fa-heart\"></i></a>";
        $link = apply_filters('wpfp_remove_favorite_link', $link);
        echo $link;
    }
}

function ct_fp_clear_list_link()
{
    if (ct_fp_is_user_can_edit()) {
        $wpfp_options = ct_fp_get_options();
        echo ct_fp_before_link_img();
        echo ct_fp_loading_img();
        echo "<a class='wpfp-link btn btn-delete' href='?wpfpaction=clear' rel='nofollow'>" . ct_fp_get_option('clear') . "</a>";
    }
}

function ct_fp_cookie_warning()
{
    if (!is_user_logged_in() && !isset($_GET['user'])) :
        echo "<p>" . ct_fp_get_option('cookie_warning') . "</p>";
    endif;
}

function ct_fp_get_option($opt)
{
    $wpfp_options = ct_fp_get_options();
    return htmlspecialchars_decode(stripslashes($wpfp_options[$opt]));
}
