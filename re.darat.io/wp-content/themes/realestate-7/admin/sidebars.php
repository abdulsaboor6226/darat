<?php
/**
 * Register Sidebars
 *
 * @package WP Pro Real Estate 7
 * @subpackage Admin
 */


if ( ! function_exists( 'ct_register_sidebars' ) ) {
    /**
     * Hook to widgets init so theme check is happy.
     */
    add_action('widgets_init', 'ct_register_sidebars');
    
    /**
     * Registers Sidebar Locations.
     *
     * @return void 
     */
    function ct_register_sidebars() {

        if ( function_exists('register_sidebar') )
            register_sidebar(array(
                'name' => 'Listing Single Right',
                'id' => 'listings-single-right',
                'description' => 'Widgets in this area will be shown in the right sidebar area on the listings single view.',
                'before_widget' => '<aside id="%1$s" class="widget %2$s left">',
                'after_widget' => '</aside>',
                'before_title' => '<h5>',
                'after_title' => '</h5>',
        ));

        if ( function_exists('register_sidebar') )
            register_sidebar(array(
                'name' => 'User Sidebar',
                'id' => 'user-sidebar',
                'description' => 'Widgets in this area will be shown in the sidebar area for logged in user pages, Submit Listing, Account Settings, etc…',
                'before_widget' => '<aside id="%1$s" class="widget %2$s left">',
                'after_widget' => '</aside>',
                'before_title' => '<h5>',
                'after_title' => '</h5>',
        ));

        if ( function_exists('register_sidebar') )
            register_sidebar(array(
                'name' => 'Left Sidebar Pages',
                'id' => 'left-sidebar-pages',
                'description' => 'Widgets in this area will be shown in the left sidebar area of pages with the left sidebar page template.',
                'before_widget' => '<aside id="%1$s" class="widget %2$s left">',
                'after_widget' => '</aside>',
                'before_title' => '<h5>',
                'after_title' => '</h5>',
        ));

        if ( function_exists('register_sidebar') )
            register_sidebar(array(
                'name' => 'Right Sidebar Pages',
                'id' => 'right-sidebar-pages',
                'description' => 'Widgets in this area will be shown in the right sidebar area of pages.',
                'before_widget' => '<aside id="%1$s" class="widget %2$s left">',
                'after_widget' => '</aside>',
                'before_title' => '<h5>',
                'after_title' => '</h5>',
        ));
        
        if ( function_exists('register_sidebar') )
            register_sidebar(array(
                'name' => 'Right Sidebar Blog',
                'id' => 'right-sidebar-blog',
                'description' => 'Widgets in this area will be shown in the right sidebar area of archives.',
                'before_widget' => '<aside id="%1$s" class="widget %2$s left">',
                'after_widget' => '</aside>',
                'before_title' => '<h5>',
                'after_title' => '</h5>',
        ));
        
        if ( function_exists('register_sidebar') )
            register_sidebar(array(
                'name' => 'Right Sidebar Single',
                'id' => 'right-sidebar-single',
                'description' => 'Widgets in this area will be shown in the right sidebar area of single posts.',
                'before_widget' => '<aside id="%1$s" class="widget %2$s left">',
                'after_widget' => '</aside>',
                'before_title' => '<h5>',
                'after_title' => '</h5>',
        ));

        if ( function_exists('register_sidebar') )
            register_sidebar(array(
                'name' => 'Right Sidebar Contact Page',
                'id' => 'right-sidebar-contact',
                'description' => 'Widgets in this area will be shown in the right sidebar area of the contact page template.',
                'before_widget' => '<aside id="%1$s" class="widget %2$s left">',
                'after_widget' => '</aside>',
                'before_title' => '<h5>',
                'after_title' => '</h5>',
        ));

        if(class_exists('Redq_Alike')) {
            if ( function_exists('register_sidebar') )
                register_sidebar(array(
                    'name' => 'Compare',
                    'id' => 'compare',
                    'description' => 'If using the Contempo Compare Posts plugin add the CT Compare widget here.',
                    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                    'after_widget' => '</aside>',
                    'before_title' => '<h5>',
                    'after_title' => '</h5>',
            ));
        }

        if ( function_exists('register_sidebar') )
            register_sidebar(array(
                'name' => 'Footer',
                'id' => 'footer',
                'description' => 'Widgets in this area will be shown in the footer of design option one.',
                'before_widget' => '<aside id="%1$s" class="widget col span_3 %2$s">',
                'after_widget' => '</aside>',
                'before_title' => '<h5 class="footer-widget-title">',
                'after_title' => '</h5>',
        ));

        if ( function_exists('register_sidebar') )
            register_sidebar(array(
                'name' => 'Footer Two',
                'id' => 'footer-two',
                'description' => 'Widgets in this area will be shown in the footer of design option two.',
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget' => '</aside>',
                'before_title' => '<h5 class="footer-widget-title">',
                'after_title' => '</h5>',
        ));
    }
}