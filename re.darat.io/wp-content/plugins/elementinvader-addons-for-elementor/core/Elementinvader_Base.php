<?php

namespace ElementinvaderAddonsForElementor\Core;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

use ElementinvaderAddonsForElementor\Modules\Forms\Ajax_Handler;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class Elementinvader_Base extends Widget_Base {
        
        public $view_folder = 'categories';
	public  $inline_css = '';
	public  $inline_css_tablet = '';
	public  $inline_css_mobile = '';
        
        public $categories_select = array();

        public function __construct($data = array(), $args = null) {
            parent::__construct($data, $args);
        }
        
        
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'categories';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Widget Name', 'elementinvader-addons-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return '';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'elementinvader' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
            /* TAB_STYLE */ 
            $this->start_controls_section(
                    'section_form_css',
                    [
                            'label' => esc_html__( 'Custom сss', 'elementinvader-addons-for-elementor' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );

            $this->add_control(
                    'custom_css_title',
                    [
                            'raw' => esc_html__( 'Add your own custom CSS here', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::RAW_HTML,
                    ]
            );

            $this->add_control(
                    'custom_css',
                    [
                            'type' => Controls_Manager::CODE,
                            'label' => esc_html__( 'Custom CSS', 'elementinvader-addons-for-elementor' ),
                            'language' => 'css',
                            'render_type' => 'ui',
                            'show_label' => false,
                            'separator' => 'none',
                    ]
            );

            $this->add_control(
                    'custom_css_description',
                    [
                            'raw' => esc_html__( 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::RAW_HTML,
                            'content_classes' => 'elementor-descriptor',
                    ]
            );

            $this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
            $this->add_page_settings_css();
	}

        
        public function view($view_file = '', $element = NULL, $print = false)
        {
            if(empty($view_file)) return false;
            $file = false;
            
            if(is_child_theme() && file_exists(get_stylesheet_directory().'/elementor-elementinvader_addons_for_elementor/views/'.$view_file.'.php'))
            {
                $file = get_stylesheet_directory().'/elementor-elementinvader_addons_for_elementor/views/'.$view_file.'.php';
            }
            elseif(file_exists(get_template_directory().'/elementor-elementinvader_addons_for_elementor/views/'.$this->view_folder.'/'.$view_file.'.php'))
            {
                $file = get_template_directory().'/elementor-elementinvader_addons_for_elementor/views/'.$this->view_folder.'/'.$view_file.'.php';
            }
            elseif(file_exists(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'views/'.$this->view_folder.'/'.$view_file.'.php'))
            {
                $file = ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'views/'.$this->view_folder.'/'.$view_file.'.php';
            }

            if($file)
            {
                extract($element);
                if($print) {
                    include $file;
                } else {
                    ob_start();
                    include $file;
                    return ob_get_clean();
                }
            }
            else
            {
                if($print) {
                    echo 'View file not found in: '.esc_html($file);
                } else {
                    return 'View file not found in: '.esc_html($file);
                } 
            }
        }
                
        public function generate_renders_tabs($object = [], $tab_prefix='', $enable_options = [], $disable_options = []) {
            /* margin */
            //$options = ['margin','align','typo','color','background','border','border_radius','padding','shadow','transition','image_size_control];
            $options = ['typo','color','background','border','border_radius','padding','shadow']; // default
            
            /* defined */
            
            if(is_string($enable_options)){
                switch($enable_options) {
                    case 'block': $enable_options = ['typo','color','background','border','border_radius','padding','shadow','transition'];
                                    break;
                    case 'text-block': $enable_options = ['align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                    break;
                    case 'text': $enable_options = ['align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                    break;
                    case 'full': $enable_options = ['margin','align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                 break;
                    default: $enable_options = ['margin','align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                 break;
                }
            }
            
            /* enable options */
            if(!empty($enable_options)){
                $options = $enable_options;
            }
            $options_flip = array_flip($options);
            /* disable options */
            if(!empty($disable_options)){
                foreach ($disable_options as $value) {
                    if(isset($options_flip[$value]))
                        unset($options[$options_flip[$value]]);
                }
            }
            $tabs_enable = true;
            if($this->sw_count($object) == 1){
                $tabs_enable = false;
            }
            if($tabs_enable)
            $this->start_controls_tabs( $tab_prefix.'_style' );
            foreach($object as $key => $selector)
                $this->_generate_tabs($selector, $key, $tab_prefix, $options, $tabs_enable);
            if($tabs_enable)
            $this->end_controls_tabs();
            
        }
        
        public function _generate_tabs($selector='', $prefix = '', $type='', $options = [], $tabs_enable = true) {
                if(empty($selector)) return false;
                
                if(empty($prefix) || $prefix == 'normal'){
                    $selector = $selector;
                    $prefix = 'normal';
                }
                else 
                    $selector = sprintf($selector,':'.$prefix);
                
                if($tabs_enable)
                    $this->start_controls_tab(
                            $type.'_'.$prefix.'_style',
                            [
                                    'label' => ucfirst($prefix),
                            ]
                    );
                
                if(in_array('typo',$options))
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => $type.'_typo'.$prefix,
                                'selector' => $selector,
                        ]
                );
                  
                if(in_array('align',$options))
                $this->add_responsive_control(
                    $type.'_align'.$prefix,
                    [
                            'label' => esc_html__( 'Alignment', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::CHOOSE,
                            'options' => [
                                    'left' => [
                                            'title' => esc_html__( 'Left', 'elementinvader-addons-for-elementor' ),
                                            'icon' => 'eicon-text-align-left',
                                    ],
                                    'center' => [
                                            'title' => esc_html__( 'Center', 'elementinvader-addons-for-elementor' ),
                                            'icon' => 'eicon-text-align-center',
                                    ],
                                    'right' => [
                                            'title' => esc_html__( 'Right', 'elementinvader-addons-for-elementor' ),
                                            'icon' => 'eicon-text-align-right',
                                    ],
                                    'justify' => [
                                            'title' => esc_html__( 'Justified', 'elementinvader-addons-for-elementor' ),
                                            'icon' => 'eicon-text-align-justify',
                                    ],
                            ],
                            'render_type' => 'template',
                            'selectors' => [
                                $selector => 'text-align: {{VALUE}};',
                            ],
                    ]
                );
                
                if(in_array('color',$options))
                $this->add_responsive_control(
                        $type.'_color'.$prefix,
                        [
                                'label' => esc_html__( 'Color', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        $selector => 'color: {{VALUE}};',
                                ],
                        ]
                );
    
                if(in_array('background',$options))
                $this->add_responsive_control(
                        $type.'_background'.$prefix,
                        [
                                'label' => esc_html__( 'Background', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        $selector => 'background-color: {{VALUE}};',
                                ],
                        ]
                );
                
                if(in_array('border',$options))
                $this->add_group_control(
                        Group_Control_Border::get_type(), [
                                'name' => $type.'_border'.$prefix,
                                'selector' => $selector,
                        ]
                );
                
                if(in_array('border_radius',$options))
                $this->add_responsive_control(
                        $type.'_border_radius'.$prefix,
                        [
                                'label' => esc_html__( 'Border Radius', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
                                'selectors' => [
                                        $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                
                if(in_array('padding',$options))
                $this->add_responsive_control(
                        $type.'_padding'.$prefix,
                        [
                                'label' => esc_html__( 'Padding', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', 'em', '%' ],
                                'selectors' => [
                                        $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                
                
                if(in_array('margin',$options))
                $this->add_responsive_control(
                        $type.'_margin'.$prefix,
                        [
                                'label' => esc_html__( 'Margin', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', 'em', '%' ],
                                'selectors' => [
                                        $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                
                if(in_array('shadow',$options))
                $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                                'name' => $type.'_box_shadow'.$prefix,
                                'exclude' => [
                                        'field_shadow_position',
                                ],
                                'selector' => $selector,
                        ]
                );
                
                if(in_array('transition',$options))
                $this->add_responsive_control(
                        $type.'_transition'.$prefix,
                        [
                                'label' => esc_html__( 'Transition Duration', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::SLIDER,
                                'render_type' => 'template',
                                'range' => [
                                        'px' => [
                                                'min' => 0,
                                                'max' => 3000,
                                        ],
                                ],
                                'selectors' => [
                                    $selector => 'transition-duration: {{SIZE}}ms',
                                ],
                        ]
                );
                if (in_array('image_size_control', $options)) {
                        $this->add_responsive_control(
                             $type.'_image_size_control_max_heigth'.$prefix,
                            [
                                'label' => esc_html__('Max Height', 'wpdirectorykit'),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                    'px' => [
                                        'min' => 10,
                                        'max' => 1500,
                                    ],
                                    'vw' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'size_units' => [ 'px', 'vw','%' ],
                                'selectors' => [
                                    $selector => 'max-height: {{SIZE}}{{UNIT}}',
                                ],
                                
                            ]
                        );
                
                        $this->add_responsive_control(
                             $type.'_image_size_control_max_width'.$prefix,
                            [
                                'label' => esc_html__('Max Width', 'wpdirectorykit'),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                    'px' => [
                                        'min' => 10,
                                        'max' => 1500,
                                    ],
                                    'vw' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'size_units' => [ 'px', 'vw','%' ],
                                'selectors' => [
                                    $selector => 'max-width: {{SIZE}}{{UNIT}}',
                                ],
                                
                            ]
                        );
                
                        $this->add_responsive_control(
                             $type.'_image_size_control_heigth'.$prefix,
                            [
                                'label' => esc_html__('Height', 'wpdirectorykit'),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                    'px' => [
                                        'min' => 10,
                                        'max' => 1500,
                                    ],
                                    'vw' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'size_units' => [ 'px', 'vw','%' ],
                                'selectors' => [
                                    $selector => 'height: {{SIZE}}{{UNIT}}',
                                ],
                                
                            ]
                        );
                
                        $this->add_responsive_control(
                             $type.'_image_size_control_width'.$prefix,
                            [
                                'label' => esc_html__('Width', 'wpdirectorykit'),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                    'px' => [
                                        'min' => 10,
                                        'max' => 1500,
                                    ],
                                    'vw' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'size_units' => [ 'px', 'vw','%' ],
                                'selectors' => [
                                    $selector => 'width: {{SIZE}}{{UNIT}}',
                                ],
                                
                            ]
                        );
                    }
                if($tabs_enable)
                    $this->end_controls_tab();
            }
            
        
        /**
	 * @param $post_css Post
	 */
	public function add_page_settings_css() {
                $settings = $this->get_settings();
		$custom_css = $settings['custom_css'];
		$custom_css = trim( $custom_css );

		if ( empty( $custom_css ) ) {
			return;
		}
                
		// Add a css comment
		$custom_css_file = '/* Start custom CSS for page-settings */' . 
                        str_replace( 'selector', '#elementinvader_addons_for_elementor_' . $this->get_id_int(), $custom_css ) . 
                        str_replace( 'selector', '#eli_' . $this->get_id_int(), $custom_css ).
                    '/* End custom CSS */';

        wp_enqueue_style('eli-custom-inline', plugins_url( '/assets/css/custom-inline.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__ ));
        wp_add_inline_style( 'eli-custom-inline',  $custom_css_file );
	}
        
        public function _ch(&$var, $empty = '')
        {
            if(empty($var))
                return $empty;

            return $var;
        }
        
        public function esc_viewe($content) {
            // @codingStandardsIgnoreStart
            if(function_exists('sw_win_viewe'))
                sw_win_viewe($content); // WPCS: XSS ok, sanitization ok.
            // @codingStandardsIgnoreEnd
        }
        
        private function break_css($css)
        {

            $results = array();

            preg_match_all('/(.+?)\s?\{\s?(.+?)\s?\}/', $css, $matches);
            foreach($matches[0] AS $i=>$original)
                foreach(explode(';', $matches[2][$i]) AS $attr)
                    if (strlen(trim($attr)) > 0) // for missing semicolon on last element, which is legal
                    {
                        list($name, $value) = explode(':', $attr);
                        $results[$matches[1][$i]][trim($name)] = trim($value);
                    }
            return $results;
        }

    function sw_count($mixed='') {
        $count = 0;

        if(!empty($mixed) && (is_array($mixed))) {
            $count = count($mixed);
        } else if(!empty($mixed) && function_exists('is_countable') && version_compare(PHP_VERSION, '7.3', '<') && is_countable($mixed)) {
            $count = count($mixed);
        }
        else if(!empty($mixed) && is_object($mixed)) {
            $count = 1;
        }
        return $count;
    }
    
    function _js($str='')
    {
        $str = str_replace("\\", "", trim($str));
        $str = str_replace("'", "\'", trim($str));
        $str = str_replace('"', '\"', $str);
        
        return $str;
    }

    
    public function generate_icon($icon, $attributes = [], $tag = 'i' ){
                if ( empty( $icon['library'] ) ) {
                        return false;
                }
                $output = '';
                // handler SVG Icon
                if ( 'svg' === $icon['library'] ) {
                        $output = \Elementor\Icons_Manager::render_svg_icon( $icon['value'] );
                } else {
                        $output = $this->render_icon_html( $icon, $attributes, $tag );
                }

                return $output;
        }

        public function render_icon_html( $icon, $attributes = [], $tag = 'i' ) {
                $icon_types = \Elementor\Icons_Manager::get_icon_manager_tabs();
                if ( isset( $icon_types[ $icon['library'] ]['render_callback'] ) && is_callable( $icon_types[ $icon['library'] ]['render_callback'] ) ) {
                        return call_user_func_array( $icon_types[ $icon['library'] ]['render_callback'], [ $icon, $attributes, $tag ] );
                }

                if ( empty( $attributes['class'] ) ) {
                        $attributes['class'] = $icon['value'];
                } else {
                        if ( is_array( $attributes['class'] ) ) {
                                $attributes['class'][] = $icon['value'];
                        } else {
                                $attributes['class'] .= ' ' . $icon['value'];
                        }
                }
                return '<' . $tag . ' ' . Utils::render_html_attributes( $attributes ) . '></' . $tag . '>';
        }

        public static function render_svg_icon( $value ) {
                if ( ! isset( $value['id'] ) ) {
                        return '';
                }

                return Svg_Handler::get_inline_svg( $value['id'] );
        }


}
