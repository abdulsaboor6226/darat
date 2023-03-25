<?php
namespace CT_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * CT Listings List
 *
 * Elementor widget for listings list style.
 *
 * @since 1.0.0
 */
class CT_Listings_List extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ct-listings-list';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'CT Listings List', 'contempo' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'ct-real-estate-7' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Query', 'contempo' ),
			]
		);

		if(class_exists('IDX')) {
			$this->add_control(
				'idx',
				[
					'label' => __( 'Display IDX Listings?', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'no' => __( 'No', 'contempo' ),
						'yes' => __( 'Yes', 'contempo' ),
					],
					'default' => 'no',
				]
			);
		}

		$this->add_control(
			'number',
			[
				'label' => __( 'Number', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '6', 'contempo'),
				'description' => __( 'Enter the number to show per page, if you\'d like to show all enter -1.', 'contempo'),
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'ASC' => __( 'Ascending', 'contempo' ),
					'DESC' => __( 'Descending', 'contempo' ),
				],
				'default' => 'ASC',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order by', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'date' => __( 'Date', 'contempo' ),
					'price' => __( 'Price', 'contempo' ),
					'rand' => __( 'Random', 'contempo' ),
				],
				'default' => 'date',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_listing_parameters',
			[
				'label' => __( 'Listing Parameters', 'contempo' ),
			]
		);

		if(class_exists('IDX')) {
			$this->add_control(
				'idx_agent',
				[
					'label' => __( 'Agent Name', 'contempo' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => __( 'Mary Sanders', 'contempo'),
					'description' => __( 'Show only a specific agents MLS listings, e.g. Mary Sanders, Bill Johnson, Ryan Serhant.', 'contempo'),
				]
			);
		}

		if(class_exists('IDX')) {
			$this->add_control(
				'mls_number',
				[
					'label' => __( 'MLS Number', 'contempo' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => __( '123456', 'contempo'),
					'description' => __( 'Show only a single specific listing by MLS Number.', 'contempo'),
				]
			);
		}

		$this->add_control(
			'type',
			[
				'label' => __( 'Type', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'single-family', 'contempo'),
				'description' => __( 'Enter the type, e.g. single-family, condo, commercial.', 'contempo'),
			]
		);

		$this->add_control(
			'price_min',
			[
				'label' => __( 'Price Min', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'default' => '0',
				'placeholder' => __( 'No Minimum', 'contempo'),
				'description' => __( 'Enter the price without currency or separators, e.g. 250000', 'contempo'),
			]
		);

		$this->add_control(
			'price_max',
			[
				'label' => __( 'Price Max', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'No Maximum', 'contempo'),
				'description' => __( 'Enter the price without currency or separators, e.g. 950000', 'contempo'),
			]
		);

		$this->add_control(
			'beds',
			[
				'label' => __( 'Beds', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '3', 'contempo'),
				//'description' => __( 'Enter the beds, e.g. 2, 3, 4.', 'contempo'),
			]
		);

		$this->add_control(
			'baths',
			[
				'label' => __( 'Baths', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '2', 'contempo'),
				//'description' => __( 'Enter the baths, e.g. 2, 3, 4.', 'contempo'),
			]
		);

		$this->add_control(
			'status',
			[
				'label' => __( 'Status', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'for-sale', 'contempo'),
				'description' => __( 'Enter the status, e.g. for-sale, for-rent, open-house.', 'contempo'),
			]
		);

		$this->add_control(
			'exclude_sold',
			[
				'label' => __( 'Exclude Sold?', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => __( 'No', 'contempo' ),
					'sold' => __( 'Yes', 'contempo' ),
				],
				'default' => '',
			]
		);

		$this->add_control(
			'city',
			[
				'label' => __( 'City', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'san-diego', 'contempo'),
				'description' => __( 'Enter the city, e.g. san-diego, los-angeles, new-york.', 'contempo'),
			]
		);

		$this->add_control(
			'state',
			[
				'label' => __( 'State', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'ca', 'contempo'),
				'description' => __( 'Enter the state, e.g. ca, tx, ny.', 'contempo'),
			]
		);

		$this->add_control(
			'zipcode',
			[
				'label' => __( 'Zip or Postcode', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '92101', 'contempo'),
				'description' => __( 'Enter the zip or postcode, e.g. 92101, 92065, 94027.', 'contempo'),
			]
		);

		$this->add_control(
			'county',
			[
				'label' => __( 'County', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '', 'contempo'),
				'description' => __( 'Enter the county, e.g. alpine-county, imperial-county, napa-county.', 'contempo'),
			]
		);

		$this->add_control(
			'country',
			[
				'label' => __( 'Country', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '', 'contempo'),
				'description' => __( 'Enter the country, e.g. usa, england, greece.', 'contempo'),
			]
		);

		$this->add_control(
			'community',
			[
				'label' => __( 'Community', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '', 'contempo'),
				'description' => __( 'Enter the community, e.g. the-grand-estates, broadstone-apartments.', 'contempo'),
			]
		);

		$this->add_control(
			'additional_features',
			[
				'label' => __( 'Additional Features', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '', 'contempo'),
				'description' => __( 'Enter the additional features, e.g. pool, gated, beach-frontage.', 'contempo'),
			]
		);

		$this->add_control(
			'brokerage_id',
			[
				'label' => __( 'Brokerage ID', 'contempo' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '', 'contempo'),
				'description' => __( 'Enter the ID of the Brokerage here, e.g. 36, you can get this in your Admin > Brokerages area.', 'contempo'),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Output Listings
		echo '<ul class="col span_12 row first">';

			echo '<style>.virtual-tour-badge { position: absolute; top: 45px; right: 10px; background-color: rgba(0,0,0,0.4); color: #fff; font-size: 10px; line-height: 1; width: 36px; padding: 6px 3px; border-radius: 3px; text-align: center; font-weight: 400; z-index: 10;} .virtual-tour-badge.no-status { top: 16px;} .listing.minimal .virtual-tour-badge { top: 50px; right: 15px;} .virtual-tour-badge svg { margin: 0 0 4px 0;} .virtual-tour-text-wrap { display: inline-block;}</style>';;

			global $post, $wp_query, $wpdb;
			global $ct_options;

			if(empty($settings['number'])) {
				$number = 3;
			} else {
				$number = $settings['number'];
			}

			$ct_price_from = str_replace(',', '', $settings['price_min']);
			$ct_price_to = str_replace(',', '', $settings['price_max']);

			if($ct_price_from == '' || $ct_price_to == '') {
				$ct_price_from = '0';
				$ct_price_to = '200000000';
			}

			if(!class_exists('IDX')) {
				$settings['idx'] = '';
				$settings['mls_number'] = '';
			}

			if(!empty($settings['mls_number'])) {

				$args = array(
        			'post_type' => 'listings',
        			'orderby' => $settings['orderby'],
					'order' => $settings['order'],
					'meta_query' => array(
						array(
					        'key' => 'source',
					        'value' => 'idx-api',
					    	'type' => 'char',
							'compare' => '='
					    ),
						array(
							'key' => '_ct_mls',
							'value' => $settings['mls_number'],
							'type' => 'char',
							'compare' => '='
						),
					),
        			'posts_per_page' => 1
    			);

			} else {

				if($settings['orderby'] == 'price') {
					$ct_price = get_post_meta($post->ID, "_ct_price", true);
					if($settings['idx'] == 'yes') {
						$args = array(
		       				'ct_status' => $settings['status'],
				            'property_type' => $settings['type'],
		        			'beds' => $settings['beds'],
				            'baths' => $settings['baths'],
		        			'city' => $settings['city'],
				            'state' => $settings['state'],
		        			'zipcode' => $settings['zipcode'],
		        			'country' => $settings['country'],
		        			'county' => $settings['county'],
		        			'community' => $settings['community'],
		        			'additional_features' => $settings['additional_features'],
		        			'post_type' => 'listings',
				            'orderby' => 'meta_value',
							'meta_key' => '_ct_price',
							'meta_type' => 'numeric',
							'meta_query' => array(
							    array(
							        'key' => 'source',
							        'value' => 'idx-api',
							    	'type' => 'char',
									'compare' => '='
							    ),
							    array(
							        'key' => '_ct_agent_name',
							        'value' => $settings['idx_agent'],
							    	'type' => 'char',
									'compare' => 'LIKE'
							    ),
							    array(
									'key' => '_ct_price',
									'value' => array( $ct_price_from, $ct_price_to ),
									'type' => 'NUMERIC',
									'compare' => 'BETWEEN'
								),
								array(
									'key' => '_ct_brokerage',
									'value' => $settings['brokerage_id'],
									'type' => 'NUMERIC',
									'compare' => 'LIKE'
								),
							),
							'tax_query' => array(
						        array(
						            'taxonomy' => 'ct_status',
						            'field'     => 'slug',
								    'terms'     => $settings['exclude_sold'],
						            'operator' => 'NOT IN'
						        )
						    ),
							'order' => $settings['order'],
				            'posts_per_page' => $number
		    			);
					} else {
						$args = array(
		       				'ct_status' => $settings['status'],
				            'property_type' => $settings['type'],
		        			'beds' => $$settings['beds'],
				            'baths' => $settings['baths'],
		        			'city' => $settings['city'],
				            'state' => $settings['state'],
		        			'zipcode' => $settings['zipcode'],
		        			'country' => $settings['country'],
		        			'county' => $settings['county'],
		        			'community' => $settings['community'],
		        			'additional_features' => $settings['additional_features'],
		        			'post_type' => 'listings',
				            'orderby' => 'meta_value',
							'meta_key' => '_ct_price',
							'meta_type' => 'numeric',
							'meta_query' => array(
								array(
							        'key' => 'source',
							        'value' => 'idx-api',
							    	'compare' => 'NOT EXISTS'
							    ),
								array(
									'key' => '_ct_price',
									'value' => array( $ct_price_from, $ct_price_to ),
									'type' => 'NUMERIC',
									'compare' => 'BETWEEN'
								),
								array(
									'key' => '_ct_brokerage',
									'value' => $settings['brokerage_id'],
									'type' => 'NUMERIC',
									'compare' => 'LIKE'
								),
							),
							'tax_query' => array(
						        array(
						            'taxonomy' => 'ct_status',
						            'field'     => 'slug',
								    'terms'     => $settings['exclude_sold'],
						            'operator' => 'NOT IN'
						        )
						    ),
							'order' => $settings['order'],
				            'posts_per_page' => $number
		    			);
					}
				} else {
					if($settings['idx'] == 'yes') {
		    			$args = array(
		        			'ct_status' => $settings['status'],
		        			'property_type' => $settings['type'],
		        			'beds' => $settings['beds'],
				            'baths' => $settings['baths'],
		        			'city' => $settings['city'],
				            'state' => $settings['state'],
		        			'zipcode' => $settings['zipcode'],
		        			'country' => $settings['country'],
		        			'county' => $settings['county'],
		        			'community' => $settings['community'],
		        			'additional_features' => $settings['additional_features'],
		        			'post_type' => 'listings',
		        			'orderby' => $settings['orderby'],
							'order' => $settings['order'],
							'meta_query' => array(
							    array(
							        'key' => 'source',
							        'value' => 'idx-api',
							    	'type' => 'char',
									'compare' => '='
							    ),
							    array(
							        'key' => '_ct_agent_name',
							        'value' => $settings['idx_agent'],
							    	'type' => 'char',
									'compare' => 'LIKE'
							    ),
							    array(
									'key' => '_ct_price',
									'value' => array( $ct_price_from, $ct_price_to ),
									'type' => 'NUMERIC',
									'compare' => 'BETWEEN'
								),
								array(
									'key' => '_ct_brokerage',
									'value' => $settings['brokerage_id'],
									'type' => 'NUMERIC',
									'compare' => 'LIKE'
								),
							),
							'tax_query' => array(
						        array(
						            'taxonomy' => 'ct_status',
						            'field'     => 'slug',
								    'terms'     => $settings['exclude_sold'],
						            'operator' => 'NOT IN'
						        )
						    ),
		        			'posts_per_page' => $number
		    			);
					} else {
						$args = array(
		        			'ct_status' => $settings['status'],
		        			'property_type' => $settings['type'],
		        			'beds' => $settings['beds'],
				            'baths' => $settings['baths'],
		        			'city' => $settings['city'],
				            'state' => $settings['state'],
		        			'zipcode' => $settings['zipcode'],
		        			'country' => $settings['country'],
		        			'county' => $settings['county'],
		        			'community' => $settings['community'],
		        			'additional_features' => $settings['additional_features'],
		        			'post_type' => 'listings',
		        			'orderby' => $settings['orderby'],
							'order' => $settings['order'],
							'meta_query' => array(
								array(
							        'key' => 'source',
							        'value' => 'idx-api',
							    	'compare' => 'NOT EXISTS'
							    ),
								array(
									'key' => '_ct_price',
									'value' => array( $ct_price_from, $ct_price_to ),
									'type' => 'NUMERIC',
									'compare' => 'BETWEEN'
								),
								array(
									'key' => '_ct_brokerage',
									'value' => $settings['brokerage_id'],
									'type' => 'NUMERIC',
									'compare' => 'LIKE'
								),
							),
							'tax_query' => array(
						        array(
						            'taxonomy' => 'ct_status',
						            'field'     => 'slug',
								    'terms'     => $settings['exclude_sold'],
						            'operator' => 'NOT IN'
						        )
						    ),
		        			'posts_per_page' => $number
		    			);
					}
				}
			}

			if( isset($args['meta_query']) && is_array($args['meta_query']) ){
				foreach( $args['meta_query'] as $k => $meta_data ){
					if( $meta_data['value'] == '' ){
						unset($args['meta_query'][$k]);
					}
				}
			}
		
		 	if( isset($args['tax_query']) && is_array($args['tax_query']) ){
				foreach( $args['tax_query'] as $k => $tax_data ){
					if( empty($tax_data['terms']) ){
						unset($args['tax_query'][$k]);
					}
				}
			}
			
			$wp_query = new \wp_query( $args );
	        
	        $count = 0;

	        if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();

	        if(taxonomy_exists('city')){
		        $city = strip_tags( get_the_term_list( $wp_query->post->ID, 'city', '', ', ', '' ) );
		    }
		    if(taxonomy_exists('state')){
		        $state = strip_tags( get_the_term_list( $wp_query->post->ID, 'state', '', ', ', '' ) );
		    }
		    if(taxonomy_exists('zipcode')){
		        $zipcode = strip_tags( get_the_term_list( $wp_query->post->ID, 'zipcode', '', ', ', '' ) );
		    }
		    if(taxonomy_exists('country')){
		        $country = strip_tags( get_the_term_list( $wp_query->post->ID, 'country', '', ', ', '' ) );
		    }
		    if(taxonomy_exists('county')){
		        $county = strip_tags( get_the_term_list( $wp_query->post->ID, 'county', '', ', ', '' ) );
		    }

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

	        $first_name = get_user_meta($author_id, 'first_name', true);
	        $last_name = get_user_meta($author_id, 'last_name', true);
	        $ct_profile_url = get_user_meta($author_id, 'ct_profile_url', true);

	        $beds = strip_tags( get_the_term_list( $wp_query->post->ID, 'beds', '', ', ', '' ) );
		    $baths = strip_tags( get_the_term_list( $wp_query->post->ID, 'baths', '', ', ', '' ) );

		    $ct_use_propinfo_icons = isset( $ct_options['ct_use_propinfo_icons'] ) ? esc_html( $ct_options['ct_use_propinfo_icons'] ) : '';
			$ct_search_results_listing_style = isset( $ct_options['ct_search_results_listing_style'] ) ? $ct_options['ct_search_results_listing_style'] : '';
			$ct_listing_virtual_tour_badge = isset( $ct_options['ct_listing_virtual_tour_badge'] ) ? $ct_options['ct_listing_virtual_tour_badge'] : '';
			$ct_listing_stats_on_off = isset( $ct_options['ct_listing_stats_on_off'] ) ? esc_attr( $ct_options['ct_listing_stats_on_off'] ) : '';
			$ct_bed_beds_or_bedrooms = isset( $ct_options['ct_bed_beds_or_bedrooms'] ) ? esc_html( $ct_options['ct_bed_beds_or_bedrooms'] ) : '';
		    $ct_bath_baths_or_bathrooms = isset( $ct_options['ct_bath_baths_or_bathrooms'] ) ? esc_html( $ct_options['ct_bath_baths_or_bathrooms'] ) : '';
			$ct_listings_lotsize_format = isset( $ct_options['ct_listings_lotsize_format'] ) ? esc_html( $ct_options['ct_listings_lotsize_format'] ) : '';
		    
		    $ct_walkscore = isset( $ct_options['ct_enable_walkscore'] ) ? esc_html( $ct_options['ct_enable_walkscore'] ) : '';
		    $ct_rentals_booking = isset( $ct_options['ct_rentals_booking'] ) ? esc_html( $ct_options['ct_rentals_booking'] ) : '';
		    $ct_listing_reviews = isset( $ct_options['ct_listing_reviews'] ) ? esc_html( $ct_options['ct_listing_reviews'] ) : '';

		    $ct_mls = get_post_meta($wp_query->post->ID, "_ct_mls", true);

		    if($ct_walkscore == 'yes') {
			    /* Walk Score */
			   	$latlong = get_post_meta($wp_query->post->ID, "_ct_latlng", true);
			   	if($latlong != '') {
					list($lat, $long) = explode(',',$latlong,2);
					$address = get_the_title() . ct_taxonomy_return('city') . ct_taxonomy_return('state') . ct_taxonomy_return('zipcode');
					$json = ct_get_walkscore($lat,$long,$address);

					$ct_ws = json_decode($json);
				}
			} ?>

				<li <?php if(!empty($ct_mls)) { echo 'id="mls-' . esc_html($ct_mls) . '"'; } ?> class="listing listing-list col span_12 first <?php if(get_post_meta($post->ID, "source", true) == 'idx-api') { echo 'idx-listing'; } ?>">

			        <?php do_action('before_listing_list_img'); ?>

			        <figure class="col span_4 first">
			        	<?php if( function_exists('ct_idx_mls_logo') ) {
		                	ct_idx_mls_logo();
		            	} ?>
			        	<?php
		           			if(has_term( 'featured', 'ct_status' ) ) {
								echo '<h6 class="snipe featured">';
									echo '<span>';
										echo __('Featured', 'contempo');
									echo '</span>';
								echo '</h6>';
							}
						?>
			            <?php
			                $status_tags = strip_tags( get_modified_term_list_name( $wp_query->post->ID, 'ct_status', '', ' ', '', array('featured') ) );
							if($status_tags != '') {
								echo '<h6 class="snipe status ';
										$status_terms = get_the_terms( $wp_query->post->ID, 'ct_status', array() );
										if ( ! empty( $status_terms ) && ! is_wp_error( $status_terms ) ){
										     foreach ( $status_terms as $term ) {
										       echo esc_html($term->slug) . ' ';
										     }
										 }
									echo '">';
									echo '<span>';
										echo esc_html($status_tags);
									echo '</span>';
								echo '</h6>';
							}

							$ct_virtual_tour = get_post_meta($wp_query->post->ID, "_ct_virtual_tour", true);
							$ct_virtual_tour_shortcode = get_post_meta($wp_query->post->ID, "_ct_virtual_tour_shortcode", true);
							$ct_virtual_tour_url = get_post_meta($wp_query->post->ID, "_ct_virtual_tour_url", true);

							if($ct_listing_virtual_tour_badge == 'yes') {
								if(!empty($ct_virtual_tour) || !empty($ct_virtual_tour_shortcode) || !empty($ct_virtual_tour_url)) {
									if(empty($status_tags)) {
										echo '<span class="virtual-tour-badge no-status">';
									} else {
										echo '<span class="virtual-tour-badge">';
									}
										ct_virtual_tour_svg_white();
										echo '<span class="virtual-tour-text-wrap">' . __('Virtual Tour', 'contempo') . '</span>';
									echo '</span>';
								}
							}
		                ?>
		                <?php if( function_exists('ct_property_type_icon') ) {
		                	ct_property_type_icon();
		            	}

							echo '<ul class="listing-actions">';

								// Count Total images
						        $attachments = get_children(
						            array(
						                'post_type' => 'attachment',
						                'post_mime_type' => 'image',
						                'post_parent' => get_the_ID()
						            )
						        );

						        $img_count = count($attachments);

						        $feat_img = 1;
						        $total_imgs = $img_count + $feat_img;

						        if(get_post_meta($post->ID, "source", true) != 'idx-api') { 
									echo '<li>';
										echo '<span class="listing-images-count" data-tooltip="' . $img_count . __(' Photos','contempo') . '">';
											echo '<i class="fa fa-image"></i>';
										echo '</span>';
									echo '</li>';
								}
								
								if (function_exists('wpfp_link')) {
									echo '<li>';
										echo '<span class="save-this" data-tooltip="' . __('Favorite','contempo') . '">';
											wpfp_link();
										echo '</span>';
									echo '</li>';
								}

								if(class_exists('Redq_Alike')) {
									echo '<li>';
										echo '<span class="compare-this" data-tooltip="' . __('Compare','contempo') . '">';
											echo do_shortcode('[alike_link vlaue="compare" show_icon="true" icon_class="fa fa-plus-square-o"]');
										echo '</span>';
									echo '</li>';
								}

								if(function_exists('ct_get_listing_views') && $ct_listing_stats_on_off != 'no') {
									echo '<li>';
										echo '<span class="listing-views" data-tooltip="' . ct_get_listing_views(get_the_ID()) . __(' Views','contempo') . '">';
											echo '<i class="fa fa-bar-chart"></i>';
										echo '</span>';
									echo '</li>';
								}

							echo '</ul>';
						?>
		                <?php if( function_exists('ct_first_image_linked') ) {
		                	ct_first_image_linked();
		                } ?>
			        </figure>

			        <?php do_action('before_listing_list_info'); ?>

			        <div class="list-listing-info col span_8 first">
			            <div class="list-listing-info-inner">
			                <header>
				                <h4 class="marT0 marB0"><a href="<?php the_permalink(); ?>"><?php if( function_exists('ct_listing_title') ) { ct_listing_title(); } ?></a></h5>
				                <p class="location muted marB0"><?php echo esc_html($city); ?>, <?php echo esc_html($state); ?> <?php echo esc_html($zipcode); ?> <?php echo esc_html($country); ?></p>
			                </header>
			                
			                <p class="price marB10"><?php if( function_exists('ct_listing_price') ) { ct_listing_price(); } ?></p>
			                
		                	<?php do_action('before_elementor_listing_list_excerpt'); ?>

			                <p class="listing-list-excerpt marB0"><?php if( function_exists('ct_excerpt') ) { echo ct_excerpt(25); } ?></p>

			                <ul class="propinfo propinfo-list marB0 padT0">
			                	
			                	<?php do_action('before_elementor_listing_list_propinfo'); ?>

								<?php if(function_exists('ct_propinfo')) { ct_propinfo(); } ?>
								
			                    	<div class="clear"></div>
							    <?php do_action('after_elementor_listing_list_propinfo'); ?>
		                    </ul>

		                    <div class="col span_12 first list-agent-info">
			                    <?php
			                    echo '<figure class="col span_1 first list-agent-image">';
			                        echo '<a href="' . get_author_posts_url($author_id) . '">';
			                           if(!empty($ct_profile_url)) {  
			                                echo '<img class="authorimg" src="';
			                                    echo esc_url($ct_profile_url);
			                                echo '" />';
			                            } else {
			                                echo '<img class="author-img" src="' . get_template_directory_uri() . '/images/user-default.png' . '" />';
			                            }
			                        echo '</a>';
			                    echo '</figure>';
			                    ?>
			                    <?php do_action('before_elementor_listing_list_agent_broker'); ?>
			                    <div class="col span_5">
		                            <p class="muted marB0"><small><?php _e('Agent', 'contempo'); ?></small></p>
		                            <p class="marB0"><a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo esc_html($first_name); ?> <?php echo esc_html($last_name); ?></a></p>
		                        </div>
			                    <div class="col span_6">
			                        <?php if(function_exists('ct_brokered_by')) { ct_brokered_by(); } ?>
			                    </div>
			                </div>
			                    <div class="clear"></div>

		                    <?php do_action('after_elementor_listing_list_info_inner'); ?>

			            </div>

			            <?php do_action('after_elementor_listing_list_info'); ?>
			        </div>
				
			    </li>

	        <?php 

	        $count++;
			
			if($count % 3 == 0) {
				echo '<div class="clear"></div>';
			}
			
			endwhile;

			else:
			
				echo '<div class="nomatches">';
					echo '<h4 class="marB5"><strong>' . __('No results for those listing parameters.','contempo') . '</strong></h4>';
					echo '<p class="marB0">' . __('Try different settings or refreshing/changing existing ones.', 'contempo') . '</p>';
				echo '</div>';

			endif;

			wp_reset_query();
			wp_reset_postdata();
			
		echo '</ul>';
	
		    echo '<div class="clear"></div>';

	}

}
