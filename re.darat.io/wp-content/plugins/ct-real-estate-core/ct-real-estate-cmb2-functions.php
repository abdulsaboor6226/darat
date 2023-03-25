<?php
/**
 * Include and setup custom metaboxes and fields
 *
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Get the bootstrap! 
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

if ( file_exists( dirname( __FILE__ ) . '/cmb2-tabs/cmb2-tabs.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2-tabs/cmb2-tabs.php';
}

/*-----------------------------------------------------------------------------------*/
/*
/* Custom Fields & Conditionals */
/*
/*-----------------------------------------------------------------------------------*/

	/*------------------------------------------------------------------------------------------------------*/
	/* Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter */
	/* @param  CMB2 object $cmb CMB2 object */
	/* @return bool True if metabox should show	*/
	/*------------------------------------------------------------------------------------------------------*/

	function ct_show_if_front_page( $cmb ) {
		// Don't show this metabox if it's not the front page template
		if ( $cmb->object_id !== get_option( 'page_on_front' ) ) {
			return false;
		}
		return true;
	}

	/*-----------------------------------------------------------------------------------*/
	/* Gets a number of posts and displays them as options */
	/* @param array $query_args Optional. Overrides defaults. */
	/*-----------------------------------------------------------------------------------*/

	function ct_get_post_options( $query_args ) {

	    $args = wp_parse_args( $query_args, array(
	        'post_type'   => 'post',
	        'numberposts' => 10,
	        'orderby' => 'title',
			'order' => 'ASC',
	    ) );

	    $posts = get_posts( $args );

	    $post_options = array();
	    $post_options[] = __('Choose a Brokerage', 'contempo');
	    if ( $posts ) {
	        foreach ( $posts as $post ) {
	          $post_options[ $post->ID ] = $post->post_title;
	        }
	    }

	    return $post_options;
	}

	/*-----------------------------------------------------------------------------------*/
	/* Get all brokerage posts */
	/* @return array An array of options that matches the CMB2 options array */
	/*-----------------------------------------------------------------------------------*/

	function ct_get_brokerage_posts() {
	    return ct_get_post_options( array( 'post_type' => 'brokerage', 'numberposts' => -1 ) );
	}

	/*-----------------------------------------------------------------------------------*/
	/* Gets all users that are agents and displays them as options */
	/*-----------------------------------------------------------------------------------*/

	function ct_get_user_options() {

		$args = array(
			'role__in'   => array('agent', 'broker', 'administrator', 'editor', 'author', 'contributor'),
			'order'		 => 'ASC',
			'orderby'	 => 'display_name',
		);

	    $wp_user_query = new WP_User_Query($args);

	    $users = $wp_user_query->get_results();

	    $get_users = array();
	    if ($users) {
		    foreach ($users as $user) {
		        $user_info = get_userdata($user->ID);
		        $get_users[ $user_info->ID ] = $user_info->first_name . ' ' . $user_info->last_name;
		    }
		}

	    return $get_users;
	}

	/*------------------------------------------------------------------------------------------------------*/
	/* CT Text Price Field
	/*------------------------------------------------------------------------------------------------------*/

	// Render
	add_action( 'cmb2_render_ct_text_price', 'sm_cmb_render_ct_text_price', 10, 5 );
	function sm_cmb_render_ct_text_price( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		echo $field_type_object->input( array( 'class' => 'cmb2-text-medium', 'type' => 'number', 'before_field' => ct_currency(), ) );
	}

	// Sanitize
	add_filter( 'cmb2_sanitize_ct_text_price', 'sm_cmb2_sanitize_ct_text_price', 10, 2 );
	function sm_cmb2_sanitize_ct_text_price( $null, $value ) {
		if(is_numeric($value)) {
			$value = round($value);
			$value = preg_replace( '/[^0-9]/', '', $value );

			return $value;
		}
	}

	/*-----------------------------------------------------------------------------------*/
	/* Register Metaboxes */
	/*-----------------------------------------------------------------------------------*/
		
	add_action( 'cmb2_admin_init', 'ct_register_listings_tabs' );

	if(!function_exists('ct_register_listings_tabs')) {
		function ct_register_listings_tabs() {
			
			$prefix = '_ct_';

			$ct_post_cmb = new_cmb2_box( array(
				'id'           => $prefix . 'listing',
				'title'        => __( 'Listing' ),
				'object_types' => array( 'listings', ),
				'priority' => 'high',
				'tabs'      => array(
					'information' => array(
						'label' => __( 'Information', 'contempo' ),
						'icon'  => 'dashicons-admin-home',
					),
					'sliderimages'  => array(
						'label' => __( 'Slider Images', 'contempo' ),
						'icon'  => 'dashicons-format-gallery',
					),
					'filesdocuments'  => array(
						'label' => __( 'Files & Documents', 'contempo' ),
						'icon'  => 'dashicons-media-text',
					),
					'video'  => array(
						'label' => __( 'Video', 'contempo' ),
						'icon'  => 'dashicons-format-video',
					),
					'virtualtour'  => array(
						'label' => __( 'Virtual Tour', 'contempo' ),
						'icon'  => 'dashicons-format-video',
					),
					'openhouse'  => array(
						'label' => __( 'Open House', 'contempo' ),
						'icon'  => 'dashicons-admin-network',
					),
					'multifloorplan'  => array(
						'label' => __( 'Multi-Floor Plans', 'contempo' ),
						'icon'  => 'dashicons-editor-distractionfree',
					),
					'energyefficency'  => array(
						'label' => __( 'Energy Efficiency', 'contempo' ),
						'icon'  => 'dashicons-lightbulb',
					),
					'brokerage'  => array(
						'label' => __( 'Brokerage', 'contempo' ),
						'icon'  => 'dashicons-groups',
					),
				),
				'tab_style'   => 'default',
			) );

			/*-----------------------------------------------------------------------------------*/
			/* Information */
			/*-----------------------------------------------------------------------------------*/

			$ct_post_cmb->add_field( array(
			    'name' => __('Listing Alternate Title', 'contempo'),
				'desc' => __('Enter the listing alternate title here replaces street address, e.g. Downtown Penthouse.', 'contempo'),
				'id' => $prefix . 'listing_alt_title',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Price Prefix Text', 'contempo'),
				'desc' => __('Enter the price prefix text here, e.g. (From, Call for price, Price on ask).', 'contempo'),
				'id' => $prefix . 'price_prefix',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium',
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Price', 'contempo'),
				'desc' => __('Enter the price here, without commas, seperators or decimal points. This field is required.', 'contempo'),
				'id' => $prefix . 'price',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'ct_text_price',
				'attributes' => array(
					'required' => 'required',
				),
			) );

			$ct_post_cmb->add_field( array(
			    'name'       => __( 'Display Price?', 'contempo' ),
			    'desc' => __('Set this to no if you would not like the price displayed on the site.', 'contempo'),
			    'id'         => $prefix . 'display_listing_price',
			    'type'       => 'select',
			    'show_option_none' => false,
			    'options'          => array(
					'yes' => __( 'Yes', 'contempo' ),
					'no' => __( 'No', 'contempo' ),
				),
			    'tab'  => 'information',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Price Postfix Text', 'contempo'),
				'desc' => __('Enter the price postfix text here, e.g. (/month, /week, /per night).', 'contempo'),
				'id' => $prefix . 'price_postfix',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Sq Ft', 'contempo'),
				'desc' => __('Enter the sq ft or sq meters here.', 'contempo'),
				'id' => $prefix . 'sqft',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Lot Size', 'contempo'),
				'desc' => __('Enter the lot size here.', 'contempo'),
				'id' => $prefix . 'lotsize',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Year Built', 'contempo'),
				'desc' => __('Enter the year built here.', 'contempo'),
				'id' => $prefix . 'year_built',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Pets', 'contempo'),
				'desc' => __('Enter pets here, e.g. (Cats, small dogs)', 'contempo'),
				'id' => $prefix . 'pets',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Parking', 'contempo'),
				'desc' => __('Enter parking here, e.g. (Carport, 2 Car Garage, Gated Parking Garage)', 'contempo'),
				'id' => $prefix . 'parking',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Property ID', 'contempo'),
				'desc' => __('Enter the property ID here, e.g. 5648973', 'contempo'),
				'id' => $prefix . 'mls',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Latitude &amp; Longitude', 'contempo'),
				'desc' => __('<strong>OPTIONAL:</strong> Only use the latitude and longitude if the regular full address can\'t be found. (ex: 37.4419, -122.1419)', 'contempo'),
				'id' => $prefix . 'latlng',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Skip Address Geocoding', 'contempo'),
				'desc' => __('<strong>OPTIONAL:</strong> Check this option to disable automatic geocoding of the provided address, and allow for manual input of lat/long coordinates.', 'contempo'),
				'id'   => $prefix . 'skip_geolocation',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'checkbox'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Owner/Agent Notes', 'contempo'),
				'desc' => __('Owner/Agent Notes (*not visible on front end).', 'contempo'),
				'id' => $prefix . 'ownernotes',
				'tab'  => 'information',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'textarea_small'
			) );

			/*-----------------------------------------------------------------------------------*/
			/* Slider Images */
			/*-----------------------------------------------------------------------------------*/

			$ct_post_cmb->add_field( array(
				'name'         => __( 'Slider Images', 'contempo' ),
				'desc'         => __( 'Upload all your slider images here, drag and drop to reorder.', 'contempo' ),
				'id'           => $prefix . 'slider',
				'type'         => 'file_list',
				'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
				'tab'  => 'sliderimages',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

			/*-----------------------------------------------------------------------------------*/
			/* Files & Documents */
			/*-----------------------------------------------------------------------------------*/

			$ct_post_cmb->add_field( array(
			    'name' => __('Files & Documents', 'contempo'),
				'desc' => __('Supported file types are PDF, Word, Excel & PowerPoint.<br />NOTE: The files need to be uploaded/attached to this listing in order for them to show on the frontend.', 'contempo'),
				'id' => $prefix . 'files',
				'type' => 'file_list',
			    'tab'  => 'filesdocuments',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

			/*-----------------------------------------------------------------------------------*/
			/* Video */
			/*-----------------------------------------------------------------------------------*/

			$ct_post_cmb->add_field( array(
			    'name' => __('Video', 'contempo'),
				'desc' => __('Paste your video url here, supports YouTube, Vimeo.', 'contempo'),
				'id' => $prefix . 'video',
				'type' => 'text_medium',
			    'tab'  => 'video',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

			/*-----------------------------------------------------------------------------------*/
			/* Virtual Tour */
			/*-----------------------------------------------------------------------------------*/

			$ct_post_cmb->add_field( array(
			    'name' => __('Virtual Tour (embed)', 'contempo'),
					'desc' => __('Paste your virtual tour embed code here, <strong>NOTE:</strong> this area does not support [shortcodes].', 'contempo'),
					'id' => $prefix . 'virtual_tour',
					'type' => 'textarea_code',
			    'tab'  => 'virtualtour',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Virtual Tour (shortcode)', 'contempo'),
				'desc' => __('Paste your virtual tour [shortcode] code here, <strong>NOTE:</strong> this is only for [shortcodes].', 'contempo'),
				'id' => $prefix . 'virtual_tour_shortcode',
				'type' => 'text_medium',
			    'tab'  => 'virtualtour',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Virtual Tour (URL)', 'contempo'),
				'desc' => __('Paste your virtual tour URL here (e.g. https://somedomain.com/), to be used with an iframe.', 'contempo'),
				'id' => $prefix . 'virtual_tour_url',
				'type' => 'text_medium',
			    'tab'  => 'virtualtour',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

			/*-----------------------------------------------------------------------------------*/
			/* Open House */
			/*-----------------------------------------------------------------------------------*/

			$group_field_id = $ct_post_cmb->add_field( array(
				'id'          => $prefix . 'open_house',
				'type'        => 'group',
				'description' => __( 'Use this area to add open house dates & times. <strong>NOTE:</strong> Make sure you also go into Real Estate 7 Options > Listings > Single Listing > Content Layout > Open House > Enabled <a href="https://cl.ly/0J2v0B0f2b3F">screenshot</a>, otherwise the open house section will not be shown.', 'contempo' ),
				'options'     => array(
					'group_title'   => __( 'Open House {#}', 'contempo' ), // {#} gets replaced by row number
					'add_button'    => __( 'Add Another Open House', 'contempo' ),
					'remove_button' => __( 'Remove Open House', 'contempo' ),
					'sortable'      => true, // beta
					'closed'     => true, // true to have the groups closed by default
				),
				'tab'  => 'openhouse',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name'       => __( 'Date', 'contempo' ),
					'id'         => $prefix . 'open_house_date',
					'type'       => 'text_date_timestamp',
					//'date_format' => 'n/t/Y',
					// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
				) );

				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name'       => __( 'Start Time', 'contempo' ),
					'id'         => $prefix . 'open_house_start_time',
					'type'       => 'text_time',
					'time_format' => 'g:i a',
					// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
				) );
				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name'       => __( 'End Time', 'contempo' ),
					'id'         => $prefix . 'open_house_end_time',
					'type'       => 'text_time',
					'time_format' => 'g:i a',
					// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
				) );

				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name'       => __( 'RSVP', 'contempo' ),
					'desc'             => __('If selected Yes this will add a scrollto link to the contact form.', 'contempo'),
					'id'         => $prefix . 'open_house_rsvp',
					'type'       => 'select',
					'default'          => 'no',
				    'options'          => array(
				        'no' => __('No', 'contempo'),
				        'yes'   => __('Yes', 'contempo'),
				    ),
					// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
				) );

			/*-----------------------------------------------------------------------------------*/
			/* Multi-Floor Plans */
			/*-----------------------------------------------------------------------------------*/

			$group_field_id = $ct_post_cmb->add_field( array(
				'id'          => $prefix . 'multiplan',
				'type'        => 'group',
				'description' => __( 'Use this area to add multiple floor plans to your listing along with pricing and descriptions. <strong>NOTE:</strong> Make sure you also go into Real Estate 7 Options > Listings > Enable Multi-Floorplan & Pricing Fields? > Select Yes <a href="http://cl.ly/3F3y1t1V2Z0u">screenshot</a>, otherwise the floor plans will not be shown.', 'contempo' ),
				'options'     => array(
					'group_title'   => __( 'Floor Plan {#}', 'contempo' ), // {#} gets replaced by row number
					'add_button'    => __( 'Add Another Floor Plan', 'contempo' ),
					'remove_button' => __( 'Remove Floor Plan', 'contempo' ),
					'sortable'      => true, 
					'closed'     => true, // true to have the groups closed by default
				),
				'tab'  => 'multifloorplan',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name'       => __( 'Title', 'contempo' ),
					'id'         => $prefix . 'plan_title',
					'type'       => 'text',
					// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
				) );

				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name'       => __( 'Beds', 'contempo' ),
					'id'         => $prefix . 'plan_beds',
					'type'       => 'text_small',
					// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
				) );

				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name'       => __( 'Baths', 'contempo' ),
					'id'         => $prefix . 'plan_baths',
					'type'       => 'text_small',
					// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
				) );

				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name'       => __( 'Sq Ft or Sq Meters', 'contempo' ),
					'id'         => $prefix . 'plan_size',
					'type'       => 'text_small',
					// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
				) );

				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name'       => __( 'Price', 'contempo' ),
					'id'         => $prefix . 'plan_price',
					'type'       => 'text_money',
					'description' => 'Can be a single price or a range, e.g. 1875-2395',
					// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
				) );

				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name'        => __( 'Availability', 'contempo' ),
					'description' => __( 'Add the availability here, e.g. (Available, Call for Availability)', 'contempo' ),
					'id'          => $prefix . 'plan_availability',
					'type'        => 'text',
				) );

				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name' => __( 'Floor Plan Image', 'contempo' ),
					'desc' => __( 'This image is used for the ', 'contempo' ),
					'id'   => $prefix . 'plan_image',
					'type' => 'file',
				) );

				$ct_post_cmb->add_group_field( $group_field_id, array(
					'name'         => __( 'Floor Plan Gallery Images', 'contempo' ),
					'desc'         => __( 'Upload all your slider images here, drag and drop to reorder. These will be shown in a lightbox gallery.', 'contempo' ),
					'id'           => $prefix . 'floor_plan_gallery',
					'type'         => 'file_list',
					'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
				) );

			/*-----------------------------------------------------------------------------------*/
			/* Energy Efficiency */
			/*-----------------------------------------------------------------------------------*/

			$ct_post_cmb->add_field( array(
			    'name'       => __( 'Energy Class', 'contempo' ),
			    'id'         => $prefix . 'energy_class',
			    'type'       => 'select',
			    'show_option_none' => true,
			    'options'          => array(
					'A+++' => __( 'A+++', 'contempo' ),
					'A++'   => __( 'A++', 'contempo' ),
					'A+'     => __( 'A+', 'contempo' ),
					'A'     => __( 'A', 'contempo' ),
					'B'     => __( 'B', 'contempo' ),
					'C'     => __( 'C', 'contempo' ),
					'D'     => __( 'D', 'contempo' ),
					'E'     => __( 'E', 'contempo' ),
					'F'     => __( 'F', 'contempo' ),
					'G'     => __( 'G', 'contempo' ),
				),
			    'tab'  => 'energyefficency',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Global Energy Performance', 'contempo'),
				'desc' => __('e.g. 91.56 kWh / m<sup>2</sup>a', 'contempo'),
				'id' => $prefix . 'global_energy_performance_index',
				'type' => 'text_medium',
			    'tab'  => 'energyefficency',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Renewable Energy Performance', 'contempo'),
				'desc' => __('e.g. 84.12 kWh / m<sup>2</sup>a', 'contempo'),
				'id' => $prefix . 'renewable_energy_performance_index',
				'type' => 'text_medium',
			    'tab'  => 'energyefficency',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Building Energy Performance', 'contempo'),
				'desc' => __('High, Low, etc…', 'contempo'),
				'id' => $prefix . 'building_energy_performance',
				'type' => 'text_medium',
			    'tab'  => 'energyefficency',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

			/*-----------------------------------------------------------------------------------*/
			/* Brokerage */
			/*-----------------------------------------------------------------------------------*/

			$ct_post_cmb->add_field( array(
			    'name'       => __( 'Brokerage', 'contempo' ),
			    'desc'       => __( 'If the listing agent is affiliated with a brokerage you can select that here.', 'contempo' ),
			    'id'         => $prefix . 'brokerage',
			    'type'       => 'select',
			    'options_cb' => 'ct_get_brokerage_posts',
			    'tab'  => 'brokerage',
				'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
			) );

		}
	}

	/*-----------------------------------------------------------------------------------*/
	/* Brokerage Tabs */
	/*-----------------------------------------------------------------------------------*/

	add_action( 'cmb2_admin_init', 'ct_register_brokerage_tabs' );

	if(!function_exists('ct_register_brokerage_tabs')) {
		function ct_register_brokerage_tabs() {
			
			$prefix = '_ct_';

			$ct_post_cmb = new_cmb2_box( array(
				'id'           => $prefix . 'brokerages',
				'title'        => __( 'Brokerage' ),
				'object_types' => array( 'brokerage', ), // Post type
				'priority' => 'high',
				'tabs'      => array(
					'contact' => array(
						'label' => __( 'Information', 'contempo' ),
						'icon'  => 'dashicons-admin-home', // Dashicon
						//'show_on_cb' => 'yourprefix_show_if_front_page',
					),
					'social'  => array(
						'label' => __( 'Social', 'contempo' ),
						'icon'  => 'dashicons-share', // Dashicon
					),
					'agents'  => array(
						'label' => __( 'Agents', 'contempo' ),
						'icon'  => 'dashicons-groups', // Dashicon
					),
				),
				'tab_style'   => 'default',
			) );

			/*-----------------------------------------------------------------------------------*/
			/* Contact Information */
			/*-----------------------------------------------------------------------------------*/

			$ct_post_cmb->add_field( array(
			    'name' => __('Phone', 'contempo'),
				'desc' => __('Enter the office phone number here.', 'contempo'),
				'id' => $prefix . 'brokerage_phone',
				'tab'  => 'contact',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Fax', 'contempo'),
				'desc' => __('Enter the office fax number here.', 'contempo'),
				'id' => $prefix . 'brokerage_fax',
				'tab'  => 'contact',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Website', 'contempo'),
				'desc' => __('Enter the website URL here.', 'contempo'),
				'id' => $prefix . 'brokerage_url',
				'tab'  => 'contact',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Email', 'contempo'),
				'desc' => __('Enter the office email here.', 'contempo'),
				'id' => $prefix . 'brokerage_email',
				'tab'  => 'contact',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Street Address', 'contempo'),
				'desc' => __('Enter the office street address here e.g. (101 Front Street)', 'contempo'),
				'id' => $prefix . 'brokerage_street_address',
				'tab'  => 'contact',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Address Two', 'contempo'),
				'desc' => __('Address two, e.g. (Suite 100)', 'contempo'),
				'id' => $prefix . 'brokerage_address_two',
				'tab'  => 'contact',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('City', 'contempo'),
				'desc' => __('Enter the office city here.', 'contempo'),
				'id' => $prefix . 'brokerage_city',
				'tab'  => 'contact',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('State or Area', 'contempo'),
				'desc' => __('Enter the office state or area here.', 'contempo'),
				'id' => $prefix . 'brokerage_state',
				'tab'  => 'contact',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Zipcode, Postcode or Postal Code', 'contempo'),
				'desc' => __('Enter the office zipcode, postcode or postal code here.', 'contempo'),
				'id' => $prefix . 'brokerage_zip',
				'tab'  => 'contact',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Country', 'contempo'),
				'desc' => __('Enter the office country here (optional).', 'contempo'),
				'id' => $prefix . 'brokerage_country',
				'tab'  => 'contact',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			/*-----------------------------------------------------------------------------------*/
			/* Social */
			/*-----------------------------------------------------------------------------------*/

			$ct_post_cmb->add_field( array(
			    'name' => __('Twitter', 'contempo'),
				'desc' => __('Enter the office Twitter URL here.', 'contempo'),
				'id' => $prefix . 'brokerage_twitter',
				'tab'  => 'social',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Facebook', 'contempo'),
				'desc' => __('Enter the office Facebook URL here.', 'contempo'),
				'id' => $prefix . 'brokerage_facebook',
				'tab'  => 'social',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('LinkedIn', 'contempo'),
				'desc' => __('Enter the office LinkedIn URL here.', 'contempo'),
				'id' => $prefix . 'brokerage_linkedin',
				'tab'  => 'social',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			$ct_post_cmb->add_field( array(
			    'name' => __('Google+', 'contempo'),
				'desc' => __('Enter the office Google+ URL here.', 'contempo'),
				'id' => $prefix . 'brokerage_gplus',
				'tab'  => 'social',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
				'type' => 'text_medium'
			) );

			/*-----------------------------------------------------------------------------------*/
			/* Agents */
			/*-----------------------------------------------------------------------------------*/

			$ct_post_cmb->add_field( array(
			    'name' => __('Agents', 'contempo'),
				'desc' => __('Assign agents to this brokerage here.', 'contempo'),
				'id' => $prefix . 'agents',
				'tab'  => 'agents',
		        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
		        'options_cb' => 'ct_get_user_options',
				'type' => 'multicheck'
			) );

		}
	}

	/*-----------------------------------------------------------------------------------*/
	/* Expire Metabox for Listings */
	/*-----------------------------------------------------------------------------------*/

	add_action( 'cmb2_admin_init', 'ct_register_listing_expire_metabox' );

	if(!function_exists('ct_register_listing_expire_metabox')) {
		function ct_register_listing_expire_metabox() {

			$prefix = '_ct_';

			/**
			 * Listing Expire Meta Box
			 */
			$ct_post_cmb = new_cmb2_box( array(
				'id'            => $prefix . 'expire_listing',
				'title'         => __( 'Listing Expire Time', 'contempo' ),
				'object_types'  => array( 'listings', ), // Post type
				// 'show_on_cb' => 'ct_show_if_front_page', // function should return a bool value
				'context'    => 'normal',
				'priority'   => 'low',
				'show_names' => false, // Show field names on the left
				// 'cmb_styles' => false, // false to disable the CMB stylesheet
				'closed'     => true, // true to keep the metabox closed by default
			) );

			$ct_post_cmb->add_field( array(
			    'name'       => __( 'Days', 'contempo' ),
			    'desc'       => __( 'The amount of days the listing will be shown.', 'contempo' ),
			    'id'         => $prefix . 'listing_expire',
			    'type'       => 'text',
			) );

		}
	}