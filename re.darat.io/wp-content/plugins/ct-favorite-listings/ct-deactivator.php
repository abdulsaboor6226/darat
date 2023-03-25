<?php


if (!defined('ABSPATH')) {
  die;
}

if (!function_exists('ct_fp_is_plugin_active')) {
  function ct_fp_is_plugin_active($name)
  {
    $a = apply_filters('active_plugins', get_option('active_plugins'));
    return
      in_array("$name/$name.php", $a) || in_array("$name", $a);
  }
}

if (!function_exists('ct_fp_deactivated_notice')) {
  function ct_fp_deactivated_notice()
  {
?>
    <div class="notice notice-error">
      <p><?php _e("Contempo Favorite Listings was deactivated by Contempo Favorite Posts.", 'ct-favorite-listings'); ?></p>
    </div>
<?php
  }
}
if (!function_exists('ct_fp_deactivate_plugin')) {
  function ct_fp_deactivate_plugin($name, $silent = false, $network_wide = null)
  {
    add_action('admin_notices', 'ct_fp_deactivated_notice');

    deactivate_plugins(
      array(
        $name
      ),
      $silent,
      $network_wide
    );
  }
}

if (is_admin()) {
  if (ct_fp_is_plugin_active('wp-favorite-posts/wp-favorite-posts.php')) {
    ct_fp_deactivate_plugin('wp-favorite-posts/wp-favorite-posts.php');
  }
}
