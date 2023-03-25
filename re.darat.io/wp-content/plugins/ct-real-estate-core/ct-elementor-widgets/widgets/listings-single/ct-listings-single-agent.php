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
class CT_Listings_Single_Agent extends Widget_Base {

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
		return 'ct-listings-single-agent';
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
						'{{WRAPPER}} .ct-elementor-listings-single-agent' => 'text-align: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'agent_image_content_section',
			[
				'label' => __( 'Agent Image', 'contempo' ),
			]
		);

			$this->add_control(
				'agent_image',
				[
					'label' => __( 'Profile Image', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'yes' => __( 'Show', 'contempo' ),
					'no' => __( 'Hide', 'contempo' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'agent_name_slogan_content_section',
			[
				'label' => __( 'Agent Name & Tagline', 'contempo' ),
			]
		);

			$this->add_control(
				'agent_name',
				[
					'label' => __( 'Name', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'yes' => __( 'Show', 'contempo' ),
					'no' => __( 'Hide', 'contempo' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'agent_tagline',
				[
					'label' => __( 'Tagline', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'yes' => __( 'Show', 'contempo' ),
					'no' => __( 'Hide', 'contempo' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'agent_contact_content_section',
			[
				'label' => __( 'Agent Contact', 'contempo' ),
			]
		);

			$this->add_control(
				'agent_contact_info_list',
				[
					'label' => __( 'Agent Contact List', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'yes' => __( 'Show', 'contempo' ),
					'no' => __( 'Hide', 'contempo' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'agent_contact_mobile',
				[
					'label' => __( 'Mobile', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'yes' => __( 'Show', 'contempo' ),
					'no' => __( 'Hide', 'contempo' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'agent_contact_office',
				[
					'label' => __( 'Office', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'yes' => __( 'Show', 'contempo' ),
					'no' => __( 'Hide', 'contempo' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'agent_contact_fax',
				[
					'label' => __( 'Fax', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'yes' => __( 'Show', 'contempo' ),
					'no' => __( 'Hide', 'contempo' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'agent_contact_email',
				[
					'label' => __( 'Email', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'yes' => __( 'Show', 'contempo' ),
					'no' => __( 'Hide', 'contempo' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'agent_social_content_section',
			[
				'label' => __( 'Agent Social', 'contempo' ),
			]
		);

			$repeater = new \Elementor\Repeater();

			$repeater->add_control(
				'agent_social_list_content',
				[
					'label' => __( 'Item Content', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'twitter',
					'options' => [
						'twitter' => __( 'Twitter', 'contempo' ),
						'facebook'  => __( 'Facebook', 'contempo' ),
						'instagram' => __( 'Instagram', 'contempo' ),
						'linkedin' => __( 'Linkedin', 'contempo' ),
						'youtube' => __( 'Youtube', 'contempo' ),
					],
				]
			);

			$this->add_control(
				'agent_social_list',
				[
					'label' => __( 'Social Links', 'contempo' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [
						[
							'agent_social_list_content' => 'twitter',
							'agent_social_list_content' => 'facebook',
							'agent_social_list_content' => 'instagram',
							'agent_social_list_content' => 'linkedin',
							'agent_social_list_content' => 'youtube',
						],
					],
					'title_field' => '{{{ agent_social_list_content }}}',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'agent_image_section',
			[
				'label' => __( 'Agent Image', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'agent_image_margin',
				[
					'label' => __( 'Margin', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} .agent-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '0',
						'right' => '0',
						'bottom' => '0',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => false,
					]
				]
			);

			$this->add_responsive_control(
				'agent_image_padding',
				[
					'label' => __( 'Padding', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} .agent-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'agent_image_border',
					'label' => __( 'Border', 'contempo' ),
					'selector' => '{{WRAPPER}} .agent-image',
				]
			);

			$this->add_control(
				'agent_image_border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .agent-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '3',
						'right' => '3',
						'bottom' => '3',
						'left' => '3',
						'unit' => 'px',
						'isLinked' => true,
					]
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'agent_name_section',
			[
				'label' => __( 'Agent Name', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'agent_name_html_tag',
				[
					'label' => __( 'HTML Tag', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'h5',
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

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'agent_name_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .agent-name',
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
				'agent_name_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .agent-name' => 'color: {{VALUE}}',
					],
					'default' => '#121212',
				]
			);

			$this->add_responsive_control(
				'agent_name_margin',
				[
					'label' => __( 'Margin', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} .agent-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '20',
						'right' => '0',
						'bottom' => '0',
						'left' => '0',
						'unit' => 'px',
						'isLinked' => false,
					]
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'agent_name_border',
					'label' => __( 'Border', 'contempo' ),
					'selector' => '{{WRAPPER}} .agent-name',
				]
			);

			$this->add_control(
				'agent_name_border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .agent-name' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'agent_tagline_section',
			[
				'label' => __( 'Agent Tagline', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'agent_tagline_html_tag',
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

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'agent_tagline_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .agent-tagline',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 1, 'unit' => 'em']],
			            'font_weight' => ['default' => 400],
			            'line_height' => ['default' => ['size' => 1.2, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'agent_tagline_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .agent-tagline' => 'color: {{VALUE}}',
					],
					'default' => '#75797f',
				]
			);

			$this->add_responsive_control(
				'agent_tagline_margin',
				[
					'label' => __( 'Margin', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} .agent-tagline' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'name' => 'agent_tagline_border',
					'label' => __( 'Border', 'contempo' ),
					'selector' => '{{WRAPPER}} .agent-name',
				]
			);

			$this->add_control(
				'agent_tagline_border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .agent-tagline' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'agent_contact_list_section',
			[
				'label' => __( 'Agent Contact', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'agent_contact_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .agent-contact-info',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 1, 'unit' => 'em']],
			            'font_weight' => ['default' => 400],
			            'line_height' => ['default' => ['size' => .5, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'agent_contact_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .agent-contact-info' => 'color: {{VALUE}}',
					],
					'default' => '#75797f',
				]
			);

			$this->add_responsive_control(
				'agent_contact_margin',
				[
					'label' => __( 'Margin', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'selectors' => [
						'{{WRAPPER}} .agent-contact-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'name' => 'agent_contact_border',
					'label' => __( 'Border', 'contempo' ),
					'selector' => '{{WRAPPER}} .agent-contact-info',
				]
			);

			$this->add_control(
				'agent_contact_border_radius',
				[
					'label' => __( 'Border Radius', 'contempo' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .agent-contact-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
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

		$ct_source = get_post_meta( ct_return_listing_id_elementor($attributes), 'source', true);

		$ct_idx_pro_assign_agents = get_option( 'ct_idx_pro_assign_agents' );
		$ct_idx_pro_assign_agents = isset( $ct_idx_pro_assign_agents ) ? $ct_idx_pro_assign_agents : '';
		$ct_idx_pro_assign_agents = json_decode($ct_idx_pro_assign_agents, true);

		if(!empty($ct_idx_pro_assign_agents) && $ct_source == 'idx-api') {
		    
		    foreach($ct_idx_pro_assign_agents as $agent) {
		        $ct_agent_first_name = get_user_meta($agent, 'first_name', true);
		        $ct_agent_last_name = get_user_meta($agent, 'last_name', true);
		        $ct_agent_display_name = $ct_agent_first_name . ' ' . $ct_agent_last_name;
		        $ct_agent_name_IDX = get_post_meta( ct_return_listing_id_elementor($attributes), '_ct_agent_name', true );

		        if($ct_agent_name_IDX == $ct_agent_display_name) {
		            $author_id = $agent;
		            $user_info = get_userdata($agent);
		        } else {
		            $author_id = get_post_field( 'post_author', ct_return_listing_id_elementor($attributes) );
		            $user_info = get_userdata($author_id);
		        }
		    }

		} else {
		    $author_id = get_post_field( 'post_author', ct_return_listing_id_elementor($attributes) );
		    $user_info = get_userdata($author_id);
		}

		$ct_profile_url = get_user_meta($author_id, 'ct_profile_url', true);
		$first_name = get_user_meta($author_id, 'first_name', true);
		$last_name = get_user_meta($author_id, 'last_name', true);
		$tagline = get_user_meta($author_id, 'tagline', true);
		$mobile = get_user_meta($author_id, 'mobile', true);
		$office = get_user_meta($author_id, 'office', true);
		$fax = get_user_meta($author_id, 'fax', true);
		$email = $user_info->user_email;
		$agent_license = get_user_meta($author_id, 'agentlicense', true);
		$ct_user_url = get_user_meta($author_id, 'user_url', true);
		$twitterhandle = get_user_meta($author_id, 'twitterhandle', true);
		$facebookurl = get_user_meta($author_id, 'facebookurl', true);
		$instagramurl = get_user_meta($author_id, 'instagramurl', true);
		$linkedinurl = get_user_meta($author_id, 'linkedinurl', true);
		$gplus = get_user_meta($author_id, 'gplus', true);
		$youtubeurl = get_user_meta($author_id, 'youtubeurl', true);

		echo '<div class="ct-elementor-listings-single">';
			echo '<div class="ct-elementor-listings-single-agent">';   
	        	
	        	if($settings['agent_image'] == 'yes') {
	        		echo '<figure class="col span_12 first row">';
						echo '<a href="' . get_author_posts_url($author_id) . '">';
							if(!empty($ct_profile_url)) {
								echo '<img class="agent-image" src="' . esc_url($ct_profile_url) . '" />';
							} else {
								echo '<img class="agent-image" src="' . get_template_directory_uri() . '/images/user-default.png' . '" />';
							}
						echo '</a>';
					echo '</figure>';
				}
					
				?>
			    
			    <div class="details col span_12 first row">
			    	<?php
				    	if ( $settings['agent_name'] == 'yes' ) {
					    	echo '<' . $settings['agent_name_html_tag'] . ' class="agent-name"><a href="' . get_author_posts_url($author_id) . '">' . esc_html($first_name) . ' ' . esc_html($last_name) . '</a></' . $settings['agent_name_html_tag'] . '>';
					    }
				    ?>
			        <?php if ( $settings['agent_tagline'] == 'yes' && !empty($tagline) ) { ?>
			        	<?php echo '<' . $settings['agent_tagline_html_tag'] . ' class="agent-tagline">' . esc_html($tagline) . '</' . $settings['agent_tagline_html_tag'] . '>'; ?>
			        <?php } ?>

			        <?php if ( $settings['agent_contact_info_list'] == 'yes' ) {
			        	echo '<ul class="agent-contact-info">';
			        		if($settings['agent_contact_mobile'] == 'yes' && !empty($mobile)) {
								echo '<li class="row">';
									echo '<span class="left">' . ct_phone_svg() . '</span>';
									echo '<span class="right">' . esc_html($mobile) . '</span>';
								echo '</li>';
							}
							if($settings['agent_contact_office'] == 'yes' && !empty($office)) {
								echo '<li class="row">';
									echo '<span class="left">' . ct_office_svg() . '</span>';
									echo '<span class="right">' . esc_html($office) . '</span>';
								echo '</li>';
							}
							if($settings['agent_contact_fax'] == 'yes' && !empty($fax)) {
								echo '<li class="row">';
									echo '<span class="left">' . ct_printer_svg() . '</span>';
									echo '<span class="right">' . esc_html($fax) . '</span>';
								echo '</li>';
							}
							if($settings['agent_contact_email'] == 'yes' && !empty($email)) {
								echo '<li class="row">';
									echo '<span class="left">' . ct_envelope_svg() . '</span>';
									echo '<span class="right">' . esc_html($email) . '</span>';
								echo '</li>';
							}
						echo '</ul>';
		        	} ?>

		        	<?php if ( $settings['agent_social_list'] ) {
		        		echo '<ul class="agent-social social">';
			        		foreach ( $settings['agent_social_list'] as $item ) {
			        			if($item['agent_social_list_content'] == 'twitter' && !empty($twitterhandle)) {
			        				echo '<li class="agent-social-link twitter">';
										echo '<a href="https://twitter.com/#!/' . esc_url($twitterhandle) . '" target="_blank"><i class="fa fa-twitter"></i></a>';
									echo '</li>';
			        			} elseif($item['agent_social_list_content'] == 'facebook' && !empty($facebookurl)) {
			        				echo '<li class="agent-social-link facebook">';
										echo '<a href="' . esc_url($facebookurl) . '" target="_blank"><i class="fa fa-facebook"></i></a>';
									echo '</li>';
								} elseif($item['agent_social_list_content'] == 'instagram' && !empty($instagramurl)) {
			        				echo '<li class="agent-social-link instagram">';
										echo '<a href="' . esc_url($instagramurl) . '" target="_blank"><i class="fa fa-instagram"></i></a>';
									echo '</li>';
								} elseif($item['agent_social_list_content'] == 'linkedin' && !empty($linkedinurl)) {
			        				echo '<li class="agent-social-link linkedin">';
										echo '<a href="' . esc_url($linkedinurl) . '" target="_blank"><i class="fa fa-linkedin"></i></a>';
									echo '</li>';
								} elseif($item['agent_social_list_content'] == 'youtube' && !empty($youtubeurl)) {
			        				echo '<li class="agent-social-link youtube">';
										echo '<a href="' . esc_url($youtubeurl) . '" target="_blank"><i class="fa fa-youtube"></i></a>';
									echo '</li>';
								}
			        		}
		        		echo '</ul>';
		        	} ?>

			    </div>
				    <div class="clear"></div>
		    </div>
		</div>

	<?php
	}

}
