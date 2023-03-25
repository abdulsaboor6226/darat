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
class CT_Listings_Single_Contact extends Widget_Base {

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
		return 'ct-listings-single-contact';
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
		return __( 'CT Contact', 'contempo' );
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
		return 'eicon-mail';
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
						'{{WRAPPER}} .ct-elementor-listings-single-contact' => 'text-align: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'form_type',
				[
					'label' => __( 'Form Type', 'contempo' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'builtin',
					'options' => [
						'builtin'  => __( 'Theme Built-in', 'contempo' ),
						'cform7' => __( 'Contact Form 7', 'contempo' ),
					],
				]
			);

			$this->add_control(
				'cform7_shortcode',
				[
					'label' => __( 'Contact Form 7 Shortcode', 'contempo' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'condition' => [
			            'form_type' => 'cform7'
			        ],
					'placeholder' => __( 'Enter your form shortcode here', 'contempo' ),
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'contact_style_section',
			[
				'label' => __( 'Form', 'contempo' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'contact_typography',
					'label' => __( 'Typography', 'contempo' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .ct-elementor-listings-single-contact',
					'fields_options' => [
			            // first mimic the click on Typography edit icon
			            'typography' => ['default' => 'yes'],
			            // then redifine the Elementor defaults
			            'font_size' => ['default' => ['size' => 17]],
			            'font_weight' => ['default' => 400],
			            'line_height' => ['default' => ['size' => 1.2, 'unit' => 'em']],
			        ],
				]
			);

			$this->add_control(
				'contact_text_color',
				[
					'label' => __( 'Text Color', 'contempo' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ct-elementor-listings-single-contact' => 'color: {{VALUE}}',
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

		$settings = $this->get_settings_for_display();
		$attributes['is_elementor'] = 1;

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $attributes['is_elementor_edit'] = 1;
        }

		$ct_source = get_post_meta( ct_return_listing_id_elementor($attributes), 'source', true);

		$ct_enable_zapier_webhooks = isset( $ct_options['ct_enable_zapier_webhooks'] ) ? $ct_options['ct_enable_zapier_webhooks'] : '';
		$ct_zapier_webhook_url = isset( $ct_options['ct_zapier_webhook_url'] ) ? $ct_options['ct_zapier_webhook_url'] : '';
		$ct_zapier_webhook_listing_single_form = isset( $ct_options['ct_zapier_webhook_listing_single_form'] ) ? $ct_options['ct_zapier_webhook_listing_single_form'] : '';

		$ct_listing_contact_form_7 = isset( $ct_options['ct_listing_contact_form_7'] ) ? esc_html( $ct_options['ct_listing_contact_form_7'] ) : '';
		$ct_listing_contact_form_7_shortcode = isset( $ct_options['ct_listing_contact_form_7_shortcode'] ) ? $ct_options['ct_listing_contact_form_7_shortcode'] : '';

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
		$email = isset( $user_info->user_email ) ? $user_info->user_email: '';
        

        echo '<div class="ct-elementor-listings-single">';
		    echo '<div class="ct-elementor-listings-single-contact">';

		        if($settings['form_type'] == 'cform7' && $settings['cform7_shortcode'] != '') {
		        
		            echo do_shortcode( $settings['cform7_shortcode'] );
		        
		        } else { ?>
		        
		            	 <form id="listingscontact" class="formular" method="post">
		                    <input type="hidden" name="listing_id" value="<?php echo esc_attr( ct_return_listing_id_elementor($attributes) ); ?>" />
		    				<fieldset>

		                        <div class="col span_12 first">
		        					<select id="ctsubject" name="ctsubject">
		        						<option><?php esc_html_e('Tell me more about this listing', 'contempo'); ?></option>
		                                <option><?php esc_html_e('Request a showing', 'contempo'); ?></option>
		        					</select>
		                        </div>
		    						<div class="clear"></div>

		                        <div class="col span_12 first">
		        					<input type="text" name="name" id="name" class="validate[required] text-input" placeholder="<?php esc_html_e('Name', 'contempo'); ?>" />
		                        </div>

		                        <div class="col span_12 first">
		        					<input type="text" name="email" id="email" class="validate[required,custom[email]] text-input" placeholder="<?php esc_html_e('Email', 'contempo'); ?>" />
		                        </div>

		                        <div class="col span_12 first">
		        					<input type="text" name="ctphone" id="ctphone" class="text-input" placeholder="<?php esc_html_e('Phone', 'contempo'); ?>" />
		                        </div>

		                        <div class="col span_12 first">
		        					<textarea class="validate[required,length[2,1000]] text-input" name="message" id="message" rows="10" cols="10"></textarea>
		                        </div>

		    					<input type="hidden" id="ctyouremail" name="ctyouremail" value="<?php echo esc_attr($email); ?>" />
		    					<input type="hidden" id="ctproperty" name="ctproperty" value="<?php the_title(); ?>, <?php city(); ?>, <?php state(); ?> <?php zipcode(); ?>" />
		    					<input type="hidden" id="ctpermalink" name="ctpermalink" value="<?php the_permalink(); ?>" />

		                        <?php if($ct_enable_zapier_webhooks == 'yes' && $ct_zapier_webhook_url != '' && $ct_zapier_webhook_listing_single_form == true) {
		                            echo '<input type="hidden" id="ctagentname" name="ctagentname" value="' . $first_name . ' ' . $last_name . '" />';
		                            echo '<input type="hidden" id="ctagentemail" name="ctagentemail" value="' . $email . '" />';
		                            echo '<input type="hidden" id="ct_zapier_webhook_url" name="ct_zapier_webhook_url" value="' . $ct_zapier_webhook_url . '" />';
		                        } ?>

		    					<input data-verification="google-recaptcha-v3" type="submit" name="Submit" value="<?php esc_html_e('Submit', 'contempo'); ?>" id="submit" class="btn" />  
		                        
		                        <?php ct_render_google_recaptcha_v3_script(); ?>
		                    		<div class="clear"></div>
		                    </fieldset>
		    			</form>
		                <?php ct_render_google_recaptcha_v3_script(); ?>        
		        <?php }
		    
		    echo '</div>';
		echo '</div>';

	}

}
