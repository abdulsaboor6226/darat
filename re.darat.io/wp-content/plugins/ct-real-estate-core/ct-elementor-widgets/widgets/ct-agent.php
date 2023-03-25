<?php
namespace CT_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * CT Three Item Grid
 *
 * Elementor widget for listings minimal grid style.
 *
 * @since 1.0.0
 */
class CT_Agent extends Widget_Base {

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
		return 'ct-agent';
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
		return __( 'CT Agent', 'contempo' );
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
		return 'eicon-person';
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
				'label' => __( 'Content', 'contempo' ),
			]
		);

		$this->add_control(
			'manual_or_dynamic',
			[
				'label' => __( 'Manual or Dynamic?', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'manual' => __( 'Manual', 'contempo' ),
					'dynamic' => __( 'Dynamic', 'contempo' ),
				],
				'default' => 'manual',
				'description' => __( 'Select whether you\'d like to manually enter the information or have it pull from a users profile.', 'contempo'),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'details',
			[
				'label' => __( 'Details', 'contempo' ),
			]
		);

		/*-----------------------------------------------------------------------------------*/
		/* Manual */
		/*-----------------------------------------------------------------------------------*/

		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'contempo' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => array(
			      'manual_or_dynamic' => 'manual',
			    ),
			]
		);

		$this->add_control(
			'name',
			[
				'label' => __( 'Name', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Bill Harwick', 'contempo' ),
				'placeholder' => __( 'Add the agents name here', 'contempo' ),
				'condition' => array(
			      'manual_or_dynamic' => 'manual',
			    ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Agent', 'contempo' ),
				'placeholder' => __( 'Add the agents title here', 'contempo' ),
				'condition' => array(
			      'manual_or_dynamic' => 'manual',
			    ),
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'contempo' ),
				'show_external' => true,
				'default' => [
					'url' => 'https://your-link.com',
					'is_external' => false,
					'nofollow' => false,
				],
				'condition' => array(
			      'manual_or_dynamic' => 'manual',
			    ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'View Profile', 'contempo' ),
				'placeholder' => __( 'Add the button text here', 'contempo' ),
				'condition' => array(
			      'manual_or_dynamic' => 'manual',
			    ),
			]
		);

		$this->add_control(
			'description',
			[
				'label' => __( 'Description', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => __( 'Default description', 'contempo' ),
				'placeholder' => __( 'Type your description here', 'contempo' ),
				'condition' => array(
			      'manual_or_dynamic' => 'manual',
			    ),
			]
		);

		/*-----------------------------------------------------------------------------------*/
		/* Dynamic */
		/*-----------------------------------------------------------------------------------*/

		// Gets all users that are agents and displays them as options

		function ct_el_get_user_options() {

			$args = array(
				'role__in'   => array('agent', 'broker', 'administrator', 'editor', 'author', 'contributor'),
				'order'		 => 'ASC',
				'orderby'	 => 'display_name',
			);

		    $wp_user_query = new \WP_User_Query($args);

		    $users = $wp_user_query->get_results();

		    $get_users = array();
		    if ($users) {
			    foreach ($users as $user) {
			        $user_info = get_userdata($user->ID);
			        //if($user_info->isagent == 'yes') {
				        $get_users[ $user_info->ID ] = $user_info->first_name . ' ' . $user_info->last_name;
				    //}
			    }
			}

		    return $get_users;
		}

		$this->add_control(
			'dynamic_user',
			[
				'label' => __( 'User', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => ct_el_get_user_options(),
				'default' => 'manual',
				'description' => __( 'Select the user you\'d like to display information from.', 'contempo'),
			]
		);

		$this->add_control(
			'show_profile_image',
			[
				'label' => __( 'Profile Image', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'contempo' ),
				'label_off' => esc_html__( 'Hide', 'contempo' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => array(
			      'manual_or_dynamic' => 'dynamic',
			    ),
			]
		);

		$this->add_control(
			'show_name',
			[
				'label' => __( 'Name', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'contempo' ),
				'label_off' => esc_html__( 'Hide', 'contempo' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => array(
			      'manual_or_dynamic' => 'dynamic',
			    ),
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Title', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'contempo' ),
				'label_off' => esc_html__( 'Hide', 'contempo' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => array(
			      'manual_or_dynamic' => 'dynamic',
			    ),
			]
		);

		$this->add_control(
			'show_bio',
			[
				'label' => __( 'Bio', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'contempo' ),
				'label_off' => esc_html__( 'Hide', 'contempo' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => array(
			      'manual_or_dynamic' => 'dynamic',
			    ),
			]
		);

		$this->add_control(
			'show_view_profile',
			[
				'label' => __( 'View Profile', 'contempo' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'contempo' ),
				'label_off' => esc_html__( 'Hide', 'contempo' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => array(
			      'manual_or_dynamic' => 'dynamic',
			    ),
			]
		);

		$this->add_control(
			'button_text_dynamic',
			[
				'label' => __( 'Button Text', 'contempo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'View Profile', 'contempo' ),
				'placeholder' => __( 'Add the button text here', 'contempo' ),
				'conditions' => array(
				  'relation' => 'and',
				  'terms'    => array(
				    array(
				      'name'     => 'manual_or_dynamic',
				      'operator' => '==',
				       'value'   => 'dynamic',
				    ),
				    array(
				      'name'     => 'show_view_profile',
				      'operator' => '==',
				      'value'    => 'yes',
				    ),
				  )
				),
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

		if($settings['manual_or_dynamic'] == 'manual') {
			// Link
			$target = $settings['link']['is_external'] ? ' target="_blank"' : '	';
			$nofollow = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
		}

		if($settings['manual_or_dynamic'] == 'dynamic') {
			$user_ID = $settings['dynamic_user'];

			$ct_profile_img_url = get_user_meta($user_ID, 'ct_profile_url', true);
			$ct_first_name = get_user_meta($user_ID, 'first_name', true);
			$ct_last_name = get_user_meta($user_ID, 'last_name', true);
			$ct_title = get_user_meta($user_ID, 'title', true);
			$ct_bio = get_user_meta($user_ID, 'description', true);
			$ct_profile_url = get_author_posts_url($user_ID);
		}

		echo '<div class="ct-agent vc-agent">';

			if($settings['manual_or_dynamic'] == 'dynamic' && $settings['show_profile_image'] == 'yes') {
				echo '<a href="' . esc_url($ct_profile_url) . '">';
					echo '<figure>';
						echo '<img src="' . esc_url($ct_profile_img_url) . '" / >';
					echo '</figure>';
				echo '</a>';
			} else {
				if(!empty($settings['image']['url'])) {
					if(!empty($settings['link']['url'])) {
						echo '<a href="' . $settings['link']['url'] . '"' . $target . $nofollow . '>';
					}
						echo '<figure>';
							echo '<img src="' . $settings['image']['url'] . '" / >';
						echo '</figure>';
					if(!empty($settings['link']['url'])) {
						echo '</a>';
					}
				}
			}
				echo '<div class="ct-agent-info vc-agent-info">';

					echo '<header>';
						if($settings['manual_or_dynamic'] == 'dynamic') {
							if($settings['show_name'] == 'yes') {
								echo '<a href="' . esc_url($ct_profile_url) . '">';
									echo '<h4>' . esc_html($ct_first_name) . ' ' . esc_html($ct_last_name) . '</h4>';
								echo '</a>';
							}
							if($settings['show_title'] == 'yes') {
								echo '<h6 class="muted">' . esc_html($ct_title) . '</h6>';
							}
						} else {
							if(!empty($settings['name'])) {
								if(!empty($settings['link']['url'])) {
									echo '<a href="' . $settings['link']['url'] . '"' . $target . $nofollow . '>';
								}
									echo '<h4>' . $settings['name'] . '</h4>';
								if(!empty($settings['link']['url'])) {
									echo '</a>';
								}
							}
							if(!empty($settings['title'])) {
								echo '<h6 class="muted">' . $settings['title'] . '</h6>';
							}
						}
					echo '</header>';

					if($settings['manual_or_dynamic'] == 'dynamic' && $settings['show_bio'] == 'yes') {
						echo '<p>' . esc_html($ct_bio) . '</p>';
					} else {
						if(!empty($settings['description'])) {
							echo '<p>' . $settings['description'] . '</p>';
						}
					}

					if($settings['manual_or_dynamic'] == 'dynamic' && $settings['show_view_profile'] == 'yes') {
						echo '<a class="btn" href="' . esc_url($ct_profile_url) . '">';
							if(!empty($settings['button_text_dynamic'])) {
								echo $settings['button_text_dynamic'];
							} else {
								echo __('View Profile', 'contempo');
							}
						echo '</a>';
					} else {
						if(!empty($settings['link']['url'])) {
							echo '<a class="btn" href="' . $settings['link']['url'] . '"' . $target . $nofollow . '>';
								if(!empty($settings['button_text'])) {
									echo $settings['button_text'];
								} else {
									echo __('View Profile', 'contempo');
								}
							echo '</a>';
						}
					}
					

				echo '</div>';
			echo '</a>';
		echo '</div>';

	}

}
