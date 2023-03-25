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
class CT_Listings_Single_Tools extends Widget_Base {

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
		return 'ct-listings-single-tools';
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
		return __( 'CT Tools', 'contempo' );
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
		return 'eicon-social-icons';
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
						'{{WRAPPER}} .ct-elementor-listings-single-tools' => 'text-align: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'tools_content_section',
			[
				'label' => __( 'Buttons', 'contempo' ),
			]
		);

			$repeater = new \Elementor\Repeater();

			$repeater->add_control(
				'tools_list_content',
				[
					'label' => __( 'Item Content', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'twitter',
					'options' => [
						'twitter' => __( 'Twitter', 'contempo' ),
						'facebook'  => __( 'Facebook', 'contempo' ),
						'linkedin' => __( 'Linkedin', 'contempo' ),
						'whatsapp' => __( 'Whatsapp', 'contempo' ),
						'pinterest' => __( 'Pinterest', 'contempo' ),
						'print' => __( 'Print', 'contempo' ),
					],
				]
			);

			$this->add_control(
				'tools_button_list',
				[
					'label' => __( 'Buttons', 'contempo' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [
						[
							'tools_list_content' => 'twitter',
							'tools_list_content' => 'facebook',
							'tools_list_content' => 'linkedin',
							'tools_list_content' => 'whatsapp',
							'tools_list_content' => 'print',
						],
					],
					'title_field' => '{{{ tools_list_content }}}',
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

		echo '<div class="ct-elementor-listings-single">';
			echo '<div class="ct-elementor-listings-single-tools">';   

	        	if ( $settings['tools_button_list'] ) {
	        		echo '<ul class="social">';
		        		foreach ( $settings['tools_button_list'] as $item ) {
		        			if($item['tools_list_content'] == 'twitter') { ?>
		        				<li class="twitter"><a href="javascript:void(0);" onclick="popup('https://twitter.com/share?text=<?php esc_html_e('Check out this great listing on', 'contempo'); ?> <?php bloginfo('name'); ?> &mdash; <?php ct_listing_title(); ?>&url=<?php the_permalink(); ?>', 'twitter',500,260);"><i class="fab fa-twitter"></i></a></li>
		        			<?php } elseif($item['tools_list_content'] == 'facebook') { ?>
		        				<?php 
		                            $listing_title = ct_listing_title( false );
		                            $params = array(
		                                'u' => get_the_permalink( ct_return_listing_id_elementor($attributes) ),
		                                'quote' => $listing_title,
		                            );
		                            $fb_sharer_url = add_query_arg( $params, "https://www.facebook.com/sharer/sharer.php" );
		                        ?>
		                         <li class="facebook">
		                            <a href="javascript:void(0);" onclick="popup('<?php echo esc_url( $fb_sharer_url ); ?>', 'facebook', 658,225);">
		                                <i class="fab fa-facebook"></i>
		                            </a>
		                        </li>
							<?php } elseif($item['tools_list_content'] == 'linkedin') { ?>
		        				<?php
		                        /**
		                         * LinkedIn Sharer.
		                         */
		                        $params = array(
		                            'url' => get_the_permalink( ct_return_listing_id_elementor($attributes) )
		                        );
		                        $linkedin_sharer_url = add_query_arg( $params, "https://www.linkedin.com/sharing/share-offsite");
		                        ?>

		                         <li class="linkedin"><a href="javascript:void(0);" onclick="popup('<?php echo esc_url( $linkedin_sharer_url ); ?>', 'linkedin',560,600);"><i class="fab fa-linkedin"></i></a></li>
							<?php } elseif($item['tools_list_content'] == 'whatsapp') { ?>
		        				<li class="whatsapp"><a href="whatsapp://send?text=<?php echo get_the_permalink( ct_return_listing_id_elementor($attributes) ) ?> - <?php esc_html_e('Check out this great listing on', 'contempo'); ?> <?php bloginfo('name'); ?> &mdash; <?php ct_listing_title(); ?>"><i class="fab fa-whatsapp"></i></a></li>
		        			<?php } elseif($item['tools_list_content'] == 'pinterest') { ?>
		        				<li class="pinterest"><a href="javascript:void(0);" onclick="popup('https://pinterest.com/pin/create/link/?url=<?php echo  get_the_permalink( ct_return_listing_id_elementor($attributes) ); ?>?description=<?php esc_html_e('Check out this great listing on', 'contempo'); ?> <?php bloginfo('name'); ?> &mdash; <?php ct_listing_title(); ?>', 'linkedin',560,400);"><i class="fab fa-pinterest"></i></a></li>
		        			<?php } elseif($item['tools_list_content'] == 'print') { ?>
		        				<li class="print"><a class="print" href="javascript:window.print()"><i class="fas fa-print"></i></a></li>
							<?php }
		        		}
	        		echo '</ul>';
	        	}
					echo '<div class="clear"></div>';
		    echo '</div>';
		echo '</div>';

	}

}
