<?php
/**
 * Core Filters defined in RE7
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*-----------------------------------------------------------------------------------*/
/* Remove Demo Mode for Redux Framework */
/*-----------------------------------------------------------------------------------*/
function ct_remove_redux_demo_link() {

    if(class_exists('ReduxFrameworkPlugin')) {
        remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2);
    }

    delete_transient( 'elementor_activation_redirect' );
}

add_action('init', 'ct_remove_redux_demo_link');

/*-----------------------------------------------------------------------------------*/
/* Removes the demo link and the notice of integrated demo from the redux-framework plugin */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'remove_demo' ) ) {
    function remove_demo() {
        // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            remove_filter( 'plugin_row_meta', array(
                ReduxFrameworkPlugin::instance(),
                'plugin_metalinks'
            ), null, 2 );

            // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
            remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
        }
    }
}