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
class CT_Listings_Single_Content extends Widget_Base {

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
		return 'ct-listings-single-content';
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
		return __( 'CT Content', 'contempo' );
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
				'html_tag',
				[
					'label' => __( 'HTML Tag', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'p',
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
						'{{WRAPPER}} .ct-elementor-listings-single-content' => 'text-align: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Typography', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} #listing-content',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 17]],
			            'font_weight' => ['default' => 400],
			            'line_height' => ['default' => ['size' => 1.6, 'unit' => 'em']],
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
						'{{WRAPPER}} #listing-content' => 'color: {{VALUE}}',
					],
					'default' => '#121212',
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

		$ct_listing_single_require_login_register_for_content = isset( $ct_options['ct_listing_single_require_login_register_for_content'] ) ? esc_html( $ct_options['ct_listing_single_require_login_register_for_content'] ) : '';
		$ct_listing_single_content_show_more = isset( $ct_options['ct_listing_single_content_show_more'] ) ? $ct_options['ct_listing_single_content_show_more'] : '';

		$settings = $this->get_settings_for_display();
		$attributes['is_elementor'] = 1;

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $attributes['is_elementor_edit'] = 1;
        }

        $ct_listing_content = apply_filters('the_content', get_post_field('post_content', ct_return_listing_id_elementor($attributes)));

		echo '<div class="ct-elementor-listings-single">';
			echo '<' . $settings['html_tag'] . ' class="ct-elementor-listings-single-content">';
				if($ct_listing_single_require_login_register_for_content == 'yes' && !is_user_logged_in()) {

					echo '<div class="must-be-logged-in-listing-content">';
			            echo '<p class="center">' . __('You must be registered to view listing details', 'contempo') . '</p>';
			            echo '<p class="center login-register-btn marB0"><a class="btn login-register" href="#">Login/Register</a></p>';
			        echo '</div>';

			    } else {

					do_action('before_single_listing_inner_content');

		            if($ct_listing_single_content_show_more == 'yes') { ?>
                        <script>
                        jQuery(function() {
                            var collapsedSize = '170px';
                            jQuery('#listing-content-show-more').each(function() {
                                var h = this.scrollHeight;
                                console.log(h);
                                var div = jQuery(this);
                                if (h > 170) {
                                    div.css('height', collapsedSize);
                                    div.after('<div id="content-show-more"><span id="show-more-btn"><?php _e('Read more', 'contempo'); ?></span></div>');
                                    var showMore = jQuery('#content-show-more');
                                    var link = jQuery('#show-more-btn');
                                    link.click(function(e) {
                                        e.stopPropagation();
                                        if (link.text() != 'Collapse') {
                                            showMore.addClass('show-more-expanded');
                                            link.text('<?php _e('Collapse', 'contempo'); ?>');
                                            div.animate({
                                                'height': h
                                            });
                                        } else {
                                            div.animate({
                                                'height': collapsedSize
                                            });
                                            showMore.removeClass('show-more-expanded');
                                            link.text('<?php _e('Read more', 'contempo'); ?>');
                                        }
                                    });
                                }
                            });
                        });
                        </script>
                        <?php
                        echo '<div id="listing-content-show-more">';
                    } else {
		                 echo '<div id="listing-content">';
		            }
		                print( $ct_listing_content );

		                do_action('inside_single_listing_inner_content');
		                
		            //echo '</div>';
		                echo '<div class="clear"></div>';

		            do_action('after_single_listing_inner_content');
	           }
	        echo '</div>';
        echo '</div>';

	}

}
