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
class CT_Listings_Single_IDX_MLS_Compliance extends Widget_Base {

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
		return 'ct-listings-single-idx-mls-compliance';
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
		return __( 'CT IDX Compliance', 'contempo' );
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
		return 'eicon-text-align-left';
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
				'important_note',
				[
					'label' => __( '', 'contempo' ),
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw' => __( 'Use this module to display MLS compliance information. For use with the CT IDX Pro plugin.', 'contempo' ),
					'content_classes' => 'important-note',
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

		$ct_source = get_post_meta(ct_return_listing_id_elementor($attributes), 'source', true);

		$ct_idx_mls_name = get_post_meta(ct_return_listing_id_elementor($attributes), '_ct_idx_mls_name', true);
		$ct_idx_agent_name = get_post_meta(ct_return_listing_id_elementor($attributes), '_ct_agent_name', true);
		$ct_idx_agent_id = get_post_meta(ct_return_listing_id_elementor($attributes), '_ct_branding_agent_id', true);
		$ct_idx_selling_agent = get_post_meta(ct_return_listing_id_elementor($attributes), '_ct_idx_selling_agent', true);
		$ct_idx_co_selling_agent = get_post_meta(ct_return_listing_id_elementor($attributes), '_ct_idx_co_selling_agent', true);

		$ct_cpt_brokerage = get_post_meta(ct_return_listing_id_elementor($attributes), '_ct_brokerage', true);
		$ct_cpt_brokerage_phone = get_post_meta(ct_return_listing_id_elementor($attributes), '_ct_branding_agent_office_phone', true);

		if($ct_cpt_brokerage != 0) {

			$brokerage = new \WP_Query(array(
	            'post_type' => 'brokerage',
	            'p' => $ct_cpt_brokerage,
	            'nopaging' => true
	        ));

			if ( $brokerage->have_posts() ) : while ( $brokerage->have_posts() ) : $brokerage->the_post();
	            
            	$ct_brokerage_name = strtolower(get_the_title());

	        endwhile; endif; wp_reset_postdata();

	    }

		if(!empty($ct_source == 'idx-api')) {

			// IDX Disclaimers
			if(class_exists('IDX')) {
			    $oIDX = new \IDX();
			    $disclaimer = $oIDX->ct_idx_disclaimer_text();

			    if($disclaimer != '') {
			        echo '<div class="container">';
			            echo '<div id="disclaimer" class="muted col span_12 first">';
			                print wp_kses_post( $disclaimer, 'post' );

		                	echo '<div class="row marT10">';

				                if(!empty($ct_idx_agent_name)) {
				                	echo '<div class="col span_3">';
										echo '<p class="marB5">';
											echo '<strong>Listing Agent:</strong><br /><br />';
											echo ucwords($ct_idx_agent_name);
											if(!empty($ct_idx_agent_id)) {
						                        echo '<br />';
						                        echo '#' . esc_html($ct_idx_agent_id);
						                    }
										echo '</p><br /><br />';
									echo '</div>';
								}
								if(has_term(array('sold'), 'ct_status', get_the_ID()) && !empty($ct_idx_selling_agent)) {
									echo '<div class="col span_3">';
										echo '<p class="marB5">';
											echo '<strong>Listing Sold by:</strong><br />';
											echo ucwords($ct_idx_selling_agent) . '<br />';
											if(!empty($ct_brokerage_name)) {
												echo ucwords($ct_idx_co_selling_agent);
											}
										echo '</p><br /><br />';
									echo '</div>';
								}
								if(!empty($ct_brokerage_name)) {
									echo '<div class="col span_3">';
										echo '<p class="marB0">';
											if(has_term(array('sold'), 'ct_status', get_the_ID())) {
												echo '<strong>Selling Office:</strong><br />';
											} else {
												echo '<strong>Listing Office:</strong><br />';
											}
											echo ucwords($ct_brokerage_name);
											//if(!empty($ct_cpt_brokerage_phone) && $show_brokerage_number == 'yes') {
												echo '<br />';
												echo esc_html($ct_cpt_brokerage_phone);
											//}
										echo '</p><br />';
									echo '</div>';
								}
							echo '</div>';
								echo '<div class="clear"></div>';
			            echo '</div>';
			        echo '</div>';
			    }
			}

		}

	}

}
