<?php

if (!defined('ABSPATH')) {
  die;
}

if (!function_exists('ct_deprecate_old_plugin')) {
  function ct_deprecate_old_plugin($name)
  {
    $a = apply_filters('active_plugins', get_option('active_plugins'));
    return
      in_array("$name/$name.php", $a) || in_array("$name", $a);
  }
}

if (!function_exists('ct_notice_need_fp_install')) {
  function ct_notice_need_fp_install()
  {
    if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'tgmpa-install-plugins') {
      return;
    }
?>
    <div class="notice notice-error">
      <p><?php printf(__("WP Favorite Posts was replaced by Contempo Favorite Listings. Please install and activate new plugin <a href='%s'>here</a>", 'contempo'), admin_url('themes.php?page=tgmpa-install-plugins')); ?></p>
    </div>
<?php
  }
}

if (is_admin()) {
  if ( ct_deprecate_old_plugin('wp-favorite-posts/wp-favorite-posts.php') ) {
    ct_notice_need_fp_install();
  }
  if ( ct_deprecate_old_plugin('contempo-real-estate-custom-posts/ct-real-estate-custom-posts.php') ) {
    deactivate_plugins('contempo-real-estate-custom-posts/ct-real-estate-custom-posts.php');   
  }
}