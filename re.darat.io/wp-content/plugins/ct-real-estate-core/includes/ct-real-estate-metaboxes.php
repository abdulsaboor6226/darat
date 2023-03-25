<?php

if ( ! defined('ABSPATH') ) {
    exit;
}
/*-----------------------------------------------------------------------------------*/
/* Moving the featured image meta box.
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_move_meta_box')) {
	function ct_move_meta_box() {
		remove_meta_box( 'postimagediv', 'listings', 'side' );
		add_meta_box('postimagediv', __('Featured Image', 'contempo'), 'post_thumbnail_meta_box', 'listings', 'side', 'high');
		remove_meta_box( 'postimagediv', 'Brokerages', 'side' );
		add_meta_box('postimagediv', __('Brokerage Logo', 'contempo'), 'post_thumbnail_meta_box', 'brokerage', 'side', 'high');
		remove_meta_box( 'postimagediv', 'testimonials', 'side' );
		add_meta_box('postimagediv', __('Featured Image', 'contempo'), 'post_thumbnail_meta_box', 'testimonials', 'side', 'high');
	}
}

add_action('do_meta_boxes', 'ct_move_meta_box');

/*-----------------------------------------------------------------------------------*/
/* Membership Package Status Meta Box */
/*-----------------------------------------------------------------------------------*/

if(!function_exists('ct_add_package_status_metabox')) {

	function ct_add_package_status_metabox() {
		global $ct_options;

		$ct_enable_front_end_paid = isset( $ct_options['ct_enable_front_end_paid'] ) ? esc_attr( $ct_options['ct_enable_front_end_paid'] ) : '';
		$ct_enable_front_end_paid_per_listing_or_mebership_packages = isset( $ct_options['ct_enable_front_end_paid_per_listing_or_mebership_packages'] ) ? esc_attr( $ct_options['ct_enable_front_end_paid_per_listing_or_mebership_packages'] ) : '';

		if($ct_enable_front_end_paid == 'yes' && $ct_enable_front_end_paid_per_listing_or_mebership_packages == 'membership-packages'){
			/*add_meta_box(
				'ct_package_status',
				'User Membership Info',
				'ct_package_status',
				'listings',
				'side',
				'high'
			);*/
		}
	}
}

add_action( 'add_meta_boxes', 'ct_add_package_status_metabox' );