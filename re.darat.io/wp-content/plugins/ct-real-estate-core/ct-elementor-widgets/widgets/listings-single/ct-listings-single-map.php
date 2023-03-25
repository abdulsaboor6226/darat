<?php
namespace CT_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * CT City Links
 *
 * Elementor widget for listings city links.
 *
 * @since 1.0.0
 */
class CT_Listings_Single_Map extends Widget_Base {

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
		return 'ct-listings-single-map';
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
		return __( 'CT Map', 'contempo' );
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
		return 'eicon-google-maps';
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
		return [ 'ct-real-estate-7-listings-single' ];
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
			'options',
			[
				'label' => __( 'Options', 'contempo' ),
			]
		);
			
			$this->add_control(
				'html_tag',
				[
					'label' => __( 'Title HTML Tag', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'h4',
					'options' => [
						'h1'  => __( 'H1', 'contempo' ),
						'h2' => __( 'H2', 'contempo' ),
						'h3' => __( 'H3', 'contempo' ),
						'h4' => __( 'H4', 'contempo' ),
						'h5' => __( 'H5', 'contempo' ),
						'h6' => __( 'H6', 'contempo' ),
						'div' => __( 'div', 'contempo' ),
						'span' => __( 'span', 'contempo' ),
						'p' => __( 'p', 'contempo' ),
					],
				]
			);

			$this->add_control(
				'widget_title',
				[
					'label' => __( 'Title', 'contempo' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Location', 'contempo' ),
					'placeholder' => __( 'Type your title here', 'contempo' ),
				]
			);

			$this->add_responsive_control(
				'text_align',
				[
					'label' => __( 'Alignment', 'contempo' ),
					'type' => \Elementor\Controls_Manager::CHOOSE,
					'default' => 'left',
					'options' => [
						'left' => [
							'title' => __( 'Left', 'contempo' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'contempo' ),
							'icon' => 'eicon-text-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'contempo' ),
							'icon' => 'eicon-text-align-right',
						],
					],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'prefix_class' => 'content-align-%s',
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-floor-plans' => 'text-align: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'driving_directions',
				[
					'label' => __( 'Driving Directions?', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'yes' => __( 'Show', 'contempo' ),
					'no' => __( 'Hide', 'contempo' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_section',
			[
				'label' => __( 'Title', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} #listing-map-heading',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 1.3125, 'unit' => 'em']],
			            'font_weight' => ['default' => 600],
			            'line_height' => ['default' => ['size' => 1.2, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'text_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} #listing-map-heading' => 'color: {{VALUE}}',
					],
					'default' => '#121212',
				]
			);

			$this->add_responsive_control(
				'icon_margin',
				[
					'label' => __( 'Margin', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} #listing-map-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '0',
						'right' => '0',
						'bottom' => '20',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => false,
					]
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => __( 'Border', 'contempo' ),
					'fields_options' => [
						'border' => [
							'default' => 'solid',
						],
						'width' => [
							'default' => [
								'top' => '0',
								'right' => '0',
								'bottom' => '1',
								'left' => '0',
								'isLinked' => false,
							],
						],
						'color' => [
							'default' => '#d5d9dd',
						],
					],
					'selector' => '{{WRAPPER}} #listing-map-heading',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} #listing-map-heading' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '0',
						'right' => '0',
						'bottom' => '0',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => true,
					]
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

		global $ct_options;

		$settings = $this->get_settings_for_display();
		$attributes['is_elementor'] = 1;

		if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
		    $attributes['is_elementor_edit'] = 1;
		}

		$google_maps_api_key = isset( $ct_options['ct_google_maps_api_key'] ) ? stripslashes( $ct_options['ct_google_maps_api_key'] ) : '';
		$ct_single_listing_content_layout_type = isset( $ct_options['ct_single_listing_content_layout_type'] ) ? $ct_options['ct_single_listing_content_layout_type'] : '';

		$ct_latlng = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_latlng', true);
		$ct_city = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'city', '', ', ', '' ) );
        $ct_state = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'state', '', ', ', '' ) );
        $ct_zipcode = strip_tags( get_the_term_list( ct_return_listing_id_elementor($attributes), 'zipcode', '', ', ', '' ) );

		do_action('before_single_listing_map');
		            
		echo '<!-- Map -->';
		echo '<div class="ct-elementor-listings-single">';
			echo '<div id="listing-location">';

				if($ct_single_listing_content_layout_type == 'accordion') {
					echo '<' . $settings['html_tag'] . ' id="listing-map-heading" class="info-toggle">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>';
				} else {
					echo '<' . $settings['html_tag'] . ' id="listing-map-heading">' . $settings['widget_title'] . '</' . $settings['html_tag'] . '>';
				}

				echo '<div class="info-inner">'; ?>

				    <script>
			        function setMapAddress(address) {

			            var geocoder = new google.maps.Geocoder();
			            <?php if(ct_has_type('commercial') || ct_has_type('industrial') || ct_has_type('retail')) { ?>
							var mapPin = {
								url: '<?php echo ct_theme_directory_uri(); ?>/images/map-pin-com.png',
								size: new google.maps.Size(40, 46),
							    scaledSize: new google.maps.Size(40, 46)
							};
						<?php } elseif(ct_has_type('land')) { ?>
							var mapPin = {
								url: '<?php echo ct_theme_directory_uri(); ?>/images/map-pin-land.png',
								size: new google.maps.Size(40, 46),
							    scaledSize: new google.maps.Size(40, 46)
							};
						<?php } elseif(ct_has_type('lot')) { ?>
							var mapPin = {
								url: '<?php echo ct_theme_directory_uri(); ?>/images/map-pin-land.png',
								size: new google.maps.Size(40, 46),
							    scaledSize: new google.maps.Size(40, 46)
							};
						<?php } else { ?>
							var mapPin = {
								url: '<?php echo ct_theme_directory_uri(); ?>/images/map-pin-res.png',
								size: new google.maps.Size(40, 46),
							    scaledSize: new google.maps.Size(40, 46)
							};
						<?php } ?>
			            geocoder.geocode( { address : address }, function( results, status ) {
			                if( status == google.maps.GeocoderStatus.OK ) {
								<?php  if((get_post_meta(get_the_ID(), "_ct_latlng", true))) { ?>
			                    var location = new google.maps.LatLng(<?php echo get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_latlng", true); ?>);
								<?php } else { ?>
								var location = results[0].geometry.location;
								<?php } ?>
			                    var options = {
			                        zoom: 15,
			                        center: location,
									scrollwheel: false,
			                        mapTypeId: google.maps.MapTypeId.<?php echo esc_html(strtoupper($ct_options['ct_contact_map_type'])); ?>,
			                        streetViewControl: true,
			                        <?php
									$ct_gmap_style = isset( $ct_options['ct_google_maps_style'] ) ? $ct_options['ct_google_maps_style']: '';
									$ct_gmap_snazzy_style = isset( $ct_options['ct_google_maps_snazzy_style'] ) ? $ct_options['ct_google_maps_snazzy_style']: '';
									$ct_gmap_snazzy_style = str_replace(array('.', ' ', "\n", "\t", "\r"), '', $ct_gmap_snazzy_style);
									if($ct_gmap_snazzy_style != '') { ?>
										styles: <?php echo ct_sanitize_output( $ct_gmap_snazzy_style ); ?>,
									<?php } ?>
			                    };
			                    var mymap = new google.maps.Map( document.getElementById( 'map-single' ), options );

			                    var marker = new google.maps.Marker({
			                    	map: mymap,
			                    	animation: google.maps.Animation.DROP,
			                   		draggable: false,
									flat: true,
									icon: mapPin,
									<?php  if((get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_latlng", true))) { ?>
									position: new google.maps.LatLng(<?php echo get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_latlng", true); ?>)
									<?php } else { ?>
									position: results[0].geometry.location
									<?php } ?>
			                	});
			            	}
			        	});
			        }

			        <?php if($ct_single_listing_content_layout_type == 'tabbed') { ?>
			        // Trigger map function on opening location tab
			        jQuery('a[href="#listing-location"]').on('click', function() {
				        setTimeout(function(){
				        	var listingMap = document.getElementById("map-single");
				        	setMapAddress();
				            google.maps.event.trigger(listingMap, 'resize');
				            if( typeof map != 'undefined') {
				                map.panTo(google.maps.Marker);
				            }
				        }, 50);
				    });
				    <?php } ?>

			        <?php if(!empty( $ct_latlng )) { ?>
				        setMapAddress( "<?php echo esc_html(get_post_meta( ct_return_listing_id_elementor($attributes), "_ct_latlng", true)); ?>" );
					<?php } else { ?>
						setMapAddress( "<?php echo get_the_title( ct_return_listing_id_elementor($attributes) ); ?> <?php esc_html($ct_city); ?> <?php esc_html($ct_state); ?> <?php esc_html($ct_zipcode); ?>" );
					<?php } ?>

			        </script>

					<?php if(empty( $google_maps_api_key )) { ?>

						<div id="map-wrap" class="no-google-api-key">
							<h5><?php _e('You need to setup the Google Maps API.', 'contempo'); ?></h5>
					        <p class="marB0"><?php _e('Go into Admin > Real Estate 7 Options > Google Maps', 'contempo'); ?></p>
				        </div>

					<?php } else { ?>

					    <div id="map-single" style=""></div>

				    <?php }

				    /* Driving Directions */
				    if($settings['driving_directions'] == 'yes') {

					    echo '<form id="get-directions" action="https://maps.google.com/maps" method="get" target="_blank">';
					    	echo '<div class="col span_9 first">';
								echo '<input type="text" name="saddr" data-type="google-autocomplete" placeholder="' . __('Enter your starting address', 'contempo') . '" autocomplete="off" />';
								echo '<input type="hidden" name="daddr" value="';
									if(!empty($ct_latlng)) {
										echo esc_html($ct_latlng);
									} else {
										echo get_the_title( ct_return_listing_id_elementor($attributes) );
										echo ' ';
										echo esc_html($ct_city);
										echo ' ';
										echo esc_html($ct_state);
										echo ' ';
										echo esc_html($ct_zipcode);
									}
								echo '" />';
							echo '</div>';
							echo '<div class="col span_3">';
								echo '<input type="submit" value="' . __('get directions', 'contempo') . '" />';
							echo '</div>';
								echo '<div class="clear"></div>';
						echo '</form>';
					}
					
				echo '</div>';

			echo '</div>';
			echo '<!-- //Map -->';

			/* Auto Complete for Driving Directions */
			if($settings['driving_directions'] == 'yes') { ?>
				<script>
					jQuery(function() {
						function initAutocomplete() {

							var input = document.querySelector('[data-type=google-autocomplete]');
							var autocomplete = new google.maps.places.Autocomplete(
						      input,
						      { types: ['geocode'] }
							);
						  
							setTimeout(function(){ 
								jQuery(".pac-container").prependTo(input);
							}, 300); 
						}
						initAutocomplete();
					});
				</script>
			<?php } 
			
		echo '</div>';

	}

}
