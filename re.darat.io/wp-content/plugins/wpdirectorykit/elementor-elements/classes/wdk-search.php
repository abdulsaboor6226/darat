<?php

namespace Wdk\Elementor\Widgets;

use Wdk\Elementor\Widgets\WdkElementorBase;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class WdkSearch extends WdkElementorBase {

    public function __construct($data = array(), $args = null) {

        \Elementor\Controls_Manager::add_tab(
            'tab_conf',
            esc_html__('Settings', 'wpdirectorykit')
        );

        \Elementor\Controls_Manager::add_tab(
            'tab_layout',
            esc_html__('Layout', 'wpdirectorykit')
        );

        \Elementor\Controls_Manager::add_tab(
            'tab_content',
            esc_html__('Main', 'wpdirectorykit')
        );

		if ($this->is_edit_mode_load()) {
            $this->enqueue_styles_scripts();
        }

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
        return 'wdk-search';
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
        return esc_html__('Wdk Search', 'wpdirectorykit');
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
        return 'eicon-search';
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
        $this->generate_controls_conf();
        $this->generate_controls_layout();
        $this->generate_controls_styles();
        $this->generate_controls_content();
        
        $this->insert_pro_message('1');
        parent::register_controls();
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
        parent::render();
        $this->data['id_element'] = $this->get_id();
        $this->data['settings'] = $this->get_settings();

        wp_enqueue_script('select2');
        wp_enqueue_script('wdk-select2');
        wp_enqueue_style('select2');
        global $wdk_visible_filters_limit;
        global $wdk_enable_search_fields_toggle;

        global $wdk_search_fields_toggle_reset;
        $wdk_search_fields_toggle_reset = true;

        if($this->data['settings']['section_config_more'])
            $wdk_enable_search_fields_toggle = true;
        
        if(!empty($this->data['settings']['section_config_more_limit'])) {
            $wdk_visible_filters_limit = $this->data['settings']['section_config_more_limit'] + 1;
        } else {
            $wdk_visible_filters_limit = 0;
        }
        $this->data['tab_field'] = NULL;
        
        if(!empty($this->data['settings']['tab_field'])) {
            $this->data['tab_field'] = substr($this->data['settings']['tab_field'], strpos($this->data['settings']['tab_field'],'__')+2);
        }
  
        $qr_string = trim($this->data['settings']['conf_predefields_query'],'?');
        $string_par = array();
        parse_str($qr_string, $string_par);
        $this->data['predefields_query'] = array_map('trim', $string_par);

        if(!empty($this->data['settings']['custom_category_root'])) {
            $this->data['predefields_query']['custom_category_root'] = 
                                substr($this->data['settings']['custom_category_root'], strpos($this->data['settings']['custom_category_root'],'__')+2);
        }

        if(!empty($this->data['settings']['custom_location_root'])) {
            $this->data['predefields_query']['custom_location_root'] = 
                                substr($this->data['settings']['custom_location_root'], strpos($this->data['settings']['custom_location_root'],'__')+2);
        }

        $this->data['is_edit_mode'] = false;          
        if(Plugin::$instance->editor->is_edit_mode()) {
            $this->data['is_edit_mode'] = true;
        }

        echo $this->view('wdk-search', $this->data); 
    }


    private function generate_controls_conf() {
        $this->start_controls_section(
			'section_config',
			[
				'label' => __( 'Configuration', 'wpdirectorykit' ),
			]
		);
                
		$this->add_control(
			'section_config_more',
			[
				'label' => __( 'Calllaps filters and add "more" button', 'wpdirectorykit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wpdirectorykit' ),
				'label_off' => __( 'Hide', 'wpdirectorykit' ),
				'return_value' => 'none',
				'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wdk-search-additional-btn' => 'display: {{VALUE}};',
                ],
			]
		);
                
		$this->add_control(
			'section_config_more_limit',
			[
				'label' => __( 'Limit Visible Fields', 'wpdirectorykit' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'step' => 1,
				'default' => 3,
				'conditions' => [
					'terms' => [
						[
							'name' => 'section_config_more',
							'operator' => '==',
							'value' => '',
						],
					],
				],
			]
		);

        $pages = array('' => __('Not Selected', 'wpdirectorykit'));
        foreach(get_pages(array('sort_column' => 'post_title')) as $page)
        {
            $pages[$page->ID] = $page->post_title.' #'.$page->ID;
        }
        
		$this->add_control(
			'conf_link',
			[
				'label' => __( 'Open results on page', 'wpdirectorykit' ),
                'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' =>  $pages
			]
		);
        
		$this->add_control(
			'text_search_button',
			[
				'label' => __( 'Search button text', 'wpdirectorykit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Search', 'wpdirectorykit' ),
			]
		);

		$this->add_control(
			'text_more_button',
			[
				'label' => __( 'More button text', 'wpdirectorykit' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'More', 'wpdirectorykit' ),
			]
		);

        
        $WMVC = &wdk_get_instance();
        $WMVC->model('field_m');
		$fields = $WMVC->field_m->get_by(array('field_type' => 'DROPDOWN'));

        $fields_list = array('' => esc_html__('Not Selected', 'wpdirectorykit'));
        $order_i = 0;
        foreach($fields as $field)
        {
            $fields_list[(++$order_i).'__'.wmvc_show_data('idfield', $field)] = '#'.wmvc_show_data('idfield', $field).' '.wmvc_show_data('field_label', $field);
        }
        $this->add_control(
            'tab_field',
            [
                    'label' => __( 'Tab', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => $fields_list,
                    'default' => 'results', 
            ]
        );
        
        $this->add_control(
            'important_note',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(__( 'Manage Search Form <a href="%1$s" target="_blank"> open </a>', 'wpdirectorykit' ), admin_url('admin.php?page=wdk_searchform')),
                'content_classes' => 'wdk_elementor_hint',
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'conf_predefields_query',
            [
                'label' => __( 'Default Search Fields Values', 'wpdirectorykit' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 5,
                'default' => '',
                'placeholder' => __( 'Type your query here, example xxx', 'wpdirectorykit' ),
                'description' => '<span style="word-break: break-all;">'.__( 'Example (same like on url): ', 'wpdirectorykit' ).
                                  'field_6_min=100&field_6_max=200&field_5=rent&is_featured=on&search_category=3&search_location=4'.
                                  '</span>',
            ]
        );

        $WMVC = &wdk_get_instance();
        $WMVC->model('category_m');
        $WMVC->model('location_m');
		$categories_data = $WMVC->category_m->get_by(array('(parent_id = 0 OR parent_id IS NULL)' => NULL));
        $categories_list = array('' => esc_html__('Not Selected', 'wpdirectorykit'));
        $order_i = 0;

        foreach($categories_data as $category)
        {
            $categories_list[(++$order_i).'__'.wmvc_show_data('idcategory', $category)] = '#'.wmvc_show_data('idcategory', $category).' '.wmvc_show_data('category_title', $category);
        }
        $this->add_control(
            'custom_category_root',
            [
                    'label' => __( 'Custom Category Root', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => $categories_list,
                    'default' => 'results', 
            ]
        );
        
		$locations_data = $WMVC->location_m->get_by(array('(parent_id = 0 OR parent_id IS NULL)' => NULL));
        $locations_list = array('' => esc_html__('Not Selected', 'wpdirectorykit'));
        $order_i = 0;

        foreach($locations_data as $location)
        {
            $locations_list[(++$order_i).'__'.wmvc_show_data('idlocation', $location)] = '#'.wmvc_show_data('idlocation', $location).' '.wmvc_show_data('location_title', $location);
        }
        $this->add_control(
            'custom_location_root',
            [
                    'label' => __( 'Custom Location Root', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => $locations_list,
                    'default' => 'results', 
            ]
        );
        
        $this->end_controls_section();
    }

    private function generate_controls_layout() {
        /* TAB_STYLE */ 
        $this->start_controls_section(
            'section_form_style',
            [
                'label' => __( 'Form', 'wpdirectorykit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'search_scroll',
            [
                    'label' => __( 'On search scroll to', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('No scroll', 'wpdirectorykit'),
                        'results' => esc_html__('Results', 'wpdirectorykit'),
                        'wdk_map_results' => esc_html__('Map', 'wpdirectorykit'),
                    ],
                    'default' => 'results', 
            ]
        );

        $this->add_responsive_control(
            'design_layout',
            [
                    'label' => __( 'Design Layout', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Not Selected', 'wpdirectorykit'),
                        'vertical' => esc_html__('Vertical', 'wpdirectorykit'),
                        'inline' => esc_html__('Horizontal', 'wpdirectorykit'),
                    ],
                    'default' => 'inline', 
            ]
        );

        $this->add_responsive_control(
            'f_label_hide',
                [
                        'label' => esc_html__( 'Field Label Hide', 'wpdirectorykit' ),
                        'type' => Controls_Manager::SWITCHER,
                        'none' => esc_html__( 'Hide', 'wpdirectorykit' ),
                        'block' => esc_html__( 'Show', 'wpdirectorykit' ),
                        'return_value' => 'none',
                        'default' => 'none',
                        'selectors' => [
                            '{{WRAPPER}} .wdk-field:not(.CHECKBOX) .wdk-field-label' => 'display: {{VALUE}};',
                        ],
                        'separator' => 'before',
                ] 
        );
                    
        $this->add_responsive_control(
            'row_gap_col_inline',
            [
                    'label' => __( 'Columns', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'wpdirectorykit'),
                        'auto' => esc_html__('Auto', 'wpdirectorykit'),
                        '100%' => '1',
                        '50%' => '2',
                        'calc(100% / 3)' => '3',
                        '25%' => '4',
                        '20%' => '5',
                        'auto_flexible' => 'auto flexible',
                    ],
                    'selectors_dictionary' => [
                        'auto' => 'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        '100%' =>  'width:100%;-webkit-flex:0 0 100%;flex:0 0 100%',
                        '50%' =>  'width:50%;-webkit-flex:0 0 50%;flex:0 0 50%',
                        'calc(100% / 3)' =>  'width:calc(100% / 3);-webkit-flex:0 0 calc(100% / 3);flex:0 0 calc(100% / 3)',
                        '25%' =>  'width:25%;-webkit-flex:0 0 25%;flex:0 0 25%',
                        '20%' =>  'width:20%;-webkit-flex:0 0 20%;flex:0 0 20%',
                        'auto' =>  'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        'auto_flexible' =>  'width:auto;-webkit-flex:1 2 auto;flex:1 2 auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wdk-search.layout_inline .wdk-row .wdk-col' => '{{UNIT}}',
                    ],
                    'default' => '', 
                    'separator' => 'before',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'design_layout',
                                'operator' => '==',
                                'value' => 'inline',
                            ]
                        ],
                    ],
            ]
        );
                    
        $this->add_responsive_control(
            'row_gap_col_hor',
            [
                    'label' => __( 'Columns', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'wpdirectorykit'),
                        'auto' => esc_html__('Auto', 'wpdirectorykit'),
                        '100%' => '1',
                        '50%' => '2',
                        'calc(100% / 3)' => '3',
                        '25%' => '4',
                        '20%' => '5',
                        'auto_flexible' => 'auto flexible',
                    ],
                    'selectors_dictionary' => [
                        'auto' => 'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        '100%' =>  'width:100%;-webkit-flex:0 0 100%;flex:0 0 100%',
                        '50%' =>  'width:50%;-webkit-flex:0 0 50%;flex:0 0 50%',
                        'calc(100% / 3)' =>  'width:calc(100% / 3);-webkit-flex:0 0 calc(100% / 3);flex:0 0 calc(100% / 3)',
                        '25%' =>  'width:25%;-webkit-flex:0 0 25%;flex:0 0 25%',
                        '20%' =>  'width:20%;-webkit-flex:0 0 20%;flex:0 0 20%',
                        'auto' =>  'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        'auto_flexible' =>  'width:auto;-webkit-flex:1 2 auto;flex:1 2 auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wdk-search:not(.layout_inline) .wdk-row .wdk-col' => '{{UNIT}}',
                    ],
                    'default' => '100%', 
                    'separator' => 'before',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'design_layout',
                                'operator' => '==',
                                'value' => 'vertical',
                            ]
                        ],
                    ],
            ]
        );

        $this->add_responsive_control(
            'button_column_inline',
            [
                    'label' => __( 'Button Columns', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'wpdirectorykit'),
                        'auto' => esc_html__('Auto', 'wpdirectorykit'),
                        '100%' => '1',
                        '50%' => '2',
                        'calc(100% / 3)' => '3',
                        '25%' => '4',
                        '20%' => '5',
                        'auto_flexible' => 'auto flexible',
                    ],
                    'selectors_dictionary' => [
                        'auto' => 'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        '100%' =>  'width:100%;-webkit-flex:0 0 100%;flex:0 0 100%',
                        '50%' =>  'width:50%;-webkit-flex:0 0 50%;flex:0 0 50%',
                        'calc(100% / 3)' =>  'width:calc(100% / 3);-webkit-flex:0 0 calc(100% / 3);flex:0 0 calc(100% / 3)',
                        '25%' =>  'width:25%;-webkit-flex:0 0 25%;flex:0 0 25%',
                        '20%' =>  'width:20%;-webkit-flex:0 0 20%;flex:0 0 20%',
                        'auto' =>  'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        'auto_flexible' =>  'width:auto;-webkit-flex:1 2 auto;flex:1 2 auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wdk-search.layout_inline .wdk-col:not(.wdk-field)' => '{{UNIT}}',
                    ],
                    'default' => '', 
                    'separator' => 'before',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'design_layout',
                                'operator' => '==',
                                'value' => 'inline',
                            ]
                        ],
                    ],
            ]
        );
        $this->add_responsive_control(
            'button_column_horizontal',
            [
                    'label' => __( 'Button Columns', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'wpdirectorykit'),
                        'auto' => esc_html__('Auto', 'wpdirectorykit'),
                        '100%' => '1',
                        '50%' => '2',
                        'calc(100% / 3)' => '3',
                        '25%' => '4',
                        '20%' => '5',
                        'auto_flexible' => 'auto flexible',
                    ],
                    'selectors_dictionary' => [
                        'auto' => 'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        '100%' =>  'width:100%;-webkit-flex:0 0 100%;flex:0 0 100%',
                        '50%' =>  'width:50%;-webkit-flex:0 0 50%;flex:0 0 50%',
                        'calc(100% / 3)' =>  'width:calc(100% / 3);-webkit-flex:0 0 calc(100% / 3);flex:0 0 calc(100% / 3)',
                        '25%' =>  'width:25%;-webkit-flex:0 0 25%;flex:0 0 25%',
                        '20%' =>  'width:20%;-webkit-flex:0 0 20%;flex:0 0 20%',
                        'auto' =>  'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        'auto_flexible' =>  'width:auto;-webkit-flex:1 2 auto;flex:1 2 auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wdk-search:not(.layout_inline) .wdk-col:not(.wdk-field)' => '{{UNIT}}',
                    ],
                    'default' => '', 
                    'separator' => 'before',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'design_layout',
                                'operator' => '==',
                                'value' => 'vertical',
                            ]
                        ],
                    ],
            ]
        );


        $this->add_responsive_control(
                'column_gap',
                [
                    'label' => esc_html__('Columns Gap', 'wpdirectorykit'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wdk-search.layout_inline .wdk-row .wdk-col' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};;',
                        '{{WRAPPER}} .wdk-search.layout_inline .wdk-row' => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                    ],
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'design_layout',
                                'operator' => '==',
                                'value' => 'inline',
                            ]
                        ],
                    ],

                ]
        );

        $this->add_responsive_control(
                'column_gap_v',
                [
                    'label' => esc_html__('Columns Gap', 'wpdirectorykit'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wdk-search:not(.layout_inline) .wdk-row .wdk-col' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};;',
                        '{{WRAPPER}} .wdk-search:not(.layout_inline) .wdk-row' => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                    ],
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'design_layout',
                                'operator' => '==',
                                'value' => 'vertical',
                            ]
                        ],
                    ],

                ]
        );

        $this->add_responsive_control(
                'row_gap_inline',
                [
                    'label' => esc_html__('Rows Gap', 'wpdirectorykit'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                     
                    'selectors' => [
                        '{{WRAPPER}} .wdk-search.layout_inline .wdk-row .wdk-col' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wdk-search.layout_inline .wdk-row' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                    ],
                    
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'design_layout',
                                'operator' => '==',
                                'value' => 'inline',
                            ]
                        ],
                    ],
                ]
        );

        $this->add_responsive_control(
                'row_gap_horizontal',
                [
                    'label' => esc_html__('Rows Gap', 'wpdirectorykit'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'default' => [
                        'size' => 10,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wdk-search:not(.layout_inline) .wdk-row  .wdk-col' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wdk-search:not(.layout_inline) .wdk-row' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                    ],
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'design_layout',
                                'operator' => '==',
                                'value' => 'vertical',
                            ]
                        ],
                    ],
                ]
        );

        $this->add_control(
			'heading_suc_message',
			[
				'label' => __( 'Fields', 'wpdirectorykit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
		$this->add_control(
			'fields_height',
			[
                'label' => __( 'Fields height', 'wpdirectorykit' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 400,
					],
				],
                'selectors' => [
					'{{WRAPPER}} .wdk-field label.checkbox, {{WRAPPER}} .wdk-field input[type="text"],{{WRAPPER}} .wdk-field input[type="number"], {{WRAPPER}} .wdk-field select' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wdk_dropdown_tree > .btn-group' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wdk-field.CHECKBOX' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wdk-field button.wdk-search-additional-btn, {{WRAPPER}} .wdk-field button.wdk-search-start' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
                
		$this->add_control(
			'fields_height_mult',
			[
                'label' => __( 'Select Multiple', 'wpdirectorykit' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 400,
					],
				],
                'selectors' => [
					'{{WRAPPER}} .wdk-field select[multiple="multiple"]' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'heading_button',
			[
				'label' => __( 'Button', 'wpdirectorykit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        
        $this->add_responsive_control(
            'more_btn_direction',
            [
                    'label' => __( 'Direction btn group', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'wpdirectorykit'),
                        'column' => esc_html__('Column', 'wpdirectorykit'),
                        'column-reverse' => esc_html__('Column Reverse', 'wpdirectorykit'),
                        'row' => esc_html__('Row', 'wpdirectorykit'),
                        'row-reverse' => esc_html__('Row Reverse', 'wpdirectorykit'),
                    ],
                    'selectors_dictionary' => [
                        'column' => 'display: flex; flex-direction: column;',
                        'column-reverse' =>  'display: flex; flex-direction: column-reverse;',
                        'row' =>  'display: flex; flex-direction: row-reverse;',
                        'row-reverse' =>  'display: flex; flex-direction: row;',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wdk-field-btn' => '{{UNIT}}',
                    ],
                    'default' => '100%', 
                    'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'more_btn_row_gap_col_inline',
            [
                    'label' => __( 'Columns btn group', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'wpdirectorykit'),
                        'auto' => esc_html__('Auto', 'wpdirectorykit'),
                        '100%' => '1',
                        '50%' => '2',
                        'calc(100% / 3)' => '3',
                        '25%' => '4',
                        '20%' => '5',
                        'auto_flexible' => 'auto flexible',
                    ],
                    'selectors_dictionary' => [
                        'auto' => 'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        '100%' =>  'width:100%;-webkit-flex:0 0 100%;flex:0 0 100%',
                        '50%' =>  'width:50%;-webkit-flex:0 0 50%;flex:0 0 50%',
                        'calc(100% / 3)' =>  'width:calc(100% / 3);-webkit-flex:0 0 calc(100% / 3);flex:0 0 calc(100% / 3)',
                        '25%' =>  'width:25%;-webkit-flex:0 0 25%;flex:0 0 25%',
                        '20%' =>  'width:20%;-webkit-flex:0 0 20%;flex:0 0 20%',
                        'auto' =>  'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        'auto_flexible' =>  'width:auto;-webkit-flex:1 2 auto;flex:1 2 auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wdk-search:not(.layout_inline) .wdk-field-btn .wdk-field-group' => '{{UNIT}}',
                    ],
                    'default' => '', 
                    'separator' => 'before',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'more_btn_direction',
                                'operator' => '!=',
                                'value' => '',
                            ],
                            [
                                'name' => 'design_layout',
                                'operator' => '==',
                                'value' => 'inline',
                            ]
                        ],
                    ],
            ]
        );
        $this->add_responsive_control(
            'more_btn_row_gap_col_hor',
            [
                    'label' => __( 'Columns btn group', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'wpdirectorykit'),
                        'auto' => esc_html__('Auto', 'wpdirectorykit'),
                        '100%' => '1',
                        '50%' => '2',
                        'calc(100% / 3)' => '3',
                        '25%' => '4',
                        '20%' => '5',
                        'auto_flexible' => 'auto flexible',
                    ],
                    'selectors_dictionary' => [
                        'auto' => 'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        '100%' =>  'width:100%;-webkit-flex:0 0 100%;flex:0 0 100%',
                        '50%' =>  'width:50%;-webkit-flex:0 0 50%;flex:0 0 50%',
                        'calc(100% / 3)' =>  'width:calc(100% / 3);-webkit-flex:0 0 calc(100% / 3);flex:0 0 calc(100% / 3)',
                        '25%' =>  'width:25%;-webkit-flex:0 0 25%;flex:0 0 25%',
                        '20%' =>  'width:20%;-webkit-flex:0 0 20%;flex:0 0 20%',
                        'auto' =>  'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        'auto_flexible' =>  'width:auto;-webkit-flex:1 2 auto;flex:1 2 auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wdk-search:not(.layout_inline) .wdk-field-btn .wdk-field-group' => '{{UNIT}}',
                    ],
                    'default' => '100%', 
                    'separator' => 'before',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'more_btn_direction',
                                'operator' => '!=',
                                'value' => '',
                            ],
                            [
                                'name' => 'design_layout',
                                'operator' => '==',
                                'value' => 'vertical',
                            ]
                        ],
                    ],
            ]
        );

        $this->add_responsive_control(
            'more_btn_column_gap',
            [
                'label' => esc_html__('Columns btn group Gap', 'wpdirectorykit'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}  .wdk-field-btn .wdk-field-group' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};;',
                    '{{WRAPPER}}  .wdk-field-btn' => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'more_btn_direction',
                            'operator' => '!=',
                            'value' => '',
                        ]
                    ],
                ],
            ]
        );

        
        $this->add_responsive_control(
            'more_btn_row_gap_inline',
            [
                'label' => esc_html__('Rows btn group Gap', 'wpdirectorykit'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wdk-search.layout_inline .wdk-field-btn .wdk-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wdk-search.layout_inline .wdk-field-btn' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'more_btn_direction',
                            'operator' => '!=',
                            'value' => '',
                        ],
                        [
                            'name' => 'design_layout',
                            'operator' => '==',
                            'value' => 'inline',
                        ]
                    ],
                ],
                
            ]
        );
        
        $this->add_responsive_control(
            'more_btn_row_gap_horizontal',
            [
                'label' => esc_html__('Rows btn group Gap', 'wpdirectorykit'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wdk-search:not(.layout_inline) .wdk-field-btn .wdk-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wdk-search:not(.layout_inline) .wdk-field-btn' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'design_layout',
                            'operator' => '==',
                            'value' => 'vertical',
                        ]
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'button_column_more',
            [
                    'label' => __( 'Columns', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'wpdirectorykit'),
                        'auto' => esc_html__('Auto', 'wpdirectorykit'),
                        '100%' => '1',
                        '50%' => '2',
                        'calc(100% / 3)' => '3',
                        '25%' => '4',
                        '20%' => '5',
                        'auto_flexible' => 'auto flexible',
                    ],
                    'selectors_dictionary' => [
                        'auto' => 'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        '100%' =>  'width:100%;-webkit-flex:0 0 100%;flex:0 0 100%',
                        '50%' =>  'width:50%;-webkit-flex:0 0 50%;flex:0 0 50%',
                        'calc(100% / 3)' =>  'width:calc(100% / 3);-webkit-flex:0 0 calc(100% / 3);flex:0 0 calc(100% / 3)',
                        '25%' =>  'width:25%;-webkit-flex:0 0 25%;flex:0 0 25%',
                        '20%' =>  'width:20%;-webkit-flex:0 0 20%;flex:0 0 20%',
                        'auto' =>  'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        'auto_flexible' =>  'width:auto;-webkit-flex:1 2 auto;flex:1 2 auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}}  #wdk-form-additional .wdk-row .wdk-col' => '{{UNIT}}',
                    ],
                    'separator' => 'before',
            ]
        );
        $this->end_controls_section();  

        /* TAB_STYLE */ 
        $this->start_controls_section(
            'section_form_more_style',
            [
                'label' => __( 'Form More', 'wpdirectorykit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
                'column_gap_more',
                [
                    'label' => esc_html__('Columns Gap', 'wpdirectorykit'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} #wdk-form-additional .wdk-row .wdk-col' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};;',
                        '{{WRAPPER}} #wdk-form-additional .wdk-row' => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_responsive_control(
                'row_gap_more',
                [
                    'label' => esc_html__('Rows Gap', 'wpdirectorykit'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} #wdk-form-additional .wdk-row .wdk-col' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} #wdk-form-additional .wdk-row' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->end_controls_section();  

        /* TAB_STYLE */ 
        $this->start_controls_section(
            'section_tab_field_style',
            [
                'label' => __( 'Tabs', 'wpdirectorykit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
					'terms' => [
						[
							'name' => 'tab_field',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
            ]
        );

        $this->add_responsive_control(
			'section_tab_field_style_hide',
			[
				'label' => __( 'Hide', 'wpdirectorykit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wpdirectorykit' ),
				'label_off' => __( 'Hide', 'wpdirectorykit' ),
				'return_value' => 'none',
				'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wdk-search .wdk-search-tabs' => 'display: {{VALUE}};',
                ],
			]
		);

        $this->add_responsive_control (
            'section_tab_field_style_align',
            [
                'label' => __( 'Position', 'wpdirectorykit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                            'title' => esc_html__( 'Left', 'wpdirectorykit' ),
                            'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                            'title' => esc_html__( 'Center', 'wpdirectorykit' ),
                            'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                            'title' => esc_html__( 'Right', 'wpdirectorykit' ),
                            'icon' => 'eicon-text-align-right',
                    ],
                ],
                'render_type' => 'ui',
                'selectors_dictionary' => [
                    'left' => 'left:0;transform:initial',
                    'center' => 'left:50%;transform:translateX(-50%)',
                    'right' => 'right:0;left:initial;transform:initial',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wdk-search .wdk-search-tabs' => '{{VALUE}};',
                ],
            ]
        );

        $selectors = array(
            'normal' => '{{WRAPPER}} .wdk-search .wdk-search-tabs label',
            'hover'=>'{{WRAPPER}} .wdk-search .wdk-search-tabs label:hover',
            'active'=>'{{WRAPPER}} .wdk-search .wdk-search-tabs input:checked + label '
        );
        
        $this->generate_renders_tabs($selectors, 'section_tab_field_style_dynamic', ['margin','typo','color','background','border','border_radius','padding','shadow','transition']);

        
        $this->add_control(
			'section_tab_field_arrows_style_header',
			[
				'label' => __( 'Arrows', 'wpdirectorykit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_responsive_control(
			'section_tab_field_arrows_style_hide',
			[
				'label' => __( 'Hide', 'wpdirectorykit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wpdirectorykit' ),
				'label_off' => __( 'Hide', 'wpdirectorykit' ),
				'return_value' => 'none',
				'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wdk-search .wdk-search-tabs label::after' => 'display: {{VALUE}};',
                ],
			]
		);

        $this->add_responsive_control(
                'section_tab_field_arrows_style_normal',
                [
                        'label' => esc_html__( 'Color', 'wpdirectorykit' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .wdk-search .wdk-search-tabs label::after' => 'border-top-color: {{VALUE}};',
                        ],
                ]
        );

        $this->add_responsive_control(
                'section_tab_field_arrows_style_hover',
                [
                        'label' => esc_html__( 'Color Hover', 'wpdirectorykit' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .wdk-search .wdk-search-tabs label:hover::after' => 'border-top-color: {{VALUE}};',
                        ],
                ]
        );

        $this->add_responsive_control(
                'section_tab_field_arrows_style_active',
                [
                        'label' => esc_html__( 'Color Active', 'wpdirectorykit' ),
                        'type' => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .wdk-search .wdk-search-tabs input:checked + label::after' => 'border-top-color: {{VALUE}};',
                        ],
                ]
        );

        $this->end_controls_section();  
    }

    private function generate_controls_styles() {
        $items = [
            [
                'key'=>'field_label',
                'label'=> esc_html__('Field Label', 'wpdirectorykit'),
                'selector'=>'.wdk-field:not(.CHECKBOX) .wdk-field-label',
                'options'=>'full',
            ],
            [
                'key'=>'field_text',
                'label'=> esc_html__('Field Text/Integer', 'wpdirectorykit'),
                'selector'=>'{{WRAPPER}} .wdk-field input[type="text"],{{WRAPPER}} .wdk-field input[type="number"]',
                'focus'=>'{{WRAPPER}} .wdk-field input[type="text"]:focus,{{WRAPPER}} .wdk-field input[type="number"]:focus',
                'options'=>'full',
            ],
            [
                'key'=>'field_select',
                'label'=> esc_html__('Field Select', 'wpdirectorykit'),
                'selector'=>'.wdk-field select',
                'options'=>'full',
            ],
            [
                'key'=>'field_checkbox',
                'label'=> esc_html__('Field Checkbox', 'wpdirectorykit'),
                'selector'=>'.wdk-field.CHECKBOX .wdk-field-label ',
                'options'=>'full',
            ],
            [
                'key'=>'field_tree',
                'label'=> esc_html__('Field Category / Location', 'wpdirectorykit'),
                'selector'=>'{{WRAPPER}} .wdk_dropdown_tree .btn-group, {{WRAPPER}} .wdk-field.CATEGORY  .select2, {{WRAPPER}} .wdk-field.LOCATION  .select2',
                'selector_hover'=>'{{WRAPPER}} .wdk_dropdown_tree .btn-group%1$s, {{WRAPPER}} .wdk-field.CATEGORY  .select2%1$s, {{WRAPPER}} .wdk-field.LOCATION  .select2%1$s',
                'options'=> ['margin','background','border','border_radius','padding','shadow','transition'],
            ],
            [
                'key'=>'field_select2',
                'label'=> esc_html__('Dropdown Multi-Select', 'wpdirectorykit'),
                'selector'=>'{{WRAPPER}} .wdk-field.DROPDOWNMULTIPLE .select2',
                'selector_hover'=>'{{WRAPPER}} .wdk-field.DROPDOWNMULTIPLE .select2%1$s',
                'options'=>['margin','background','border','border_radius','padding','shadow','transition'],
            ],
            [
                'key'=>'field_slider_range',
                'label'=> esc_html__('Field Slider Range', 'wpdirectorykit'),
                'selector'=>'',
                'selector_hover'=>'',
                'options'=>'',
            ],
            [
                'key'=>'field_button',
                'label'=> esc_html__('Search Button', 'wpdirectorykit'),
                'selector'=>'.wdk-field button.wdk-search-start',
                'options'=>'full',
            ],
            [
                'key'=>'field_button_more',
                'label'=> esc_html__('Button More', 'wpdirectorykit'),
                'selector'=>'.wdk-field button.wdk-search-additional-btn',
                'options'=>'full',
            ],
            /* additon box fields */
            [
                'key'=>'fields_addition_box',
                'label'=> esc_html__('Additional fields box', 'wpdirectorykit'),
                'selector'=>'#wdk-form-additional',
                'options'=>['background','padding','border'],
            ],
            [
                'key'=>'addition_box_field_label',
                'label'=> esc_html__('Additional box Field Label', 'wpdirectorykit'),
                'selector'=>'#wdk-form-additional .wdk-field:not(.CHECKBOX) .wdk-field-label',
                'options'=>'full',
            ],
            [
                'key'=>'addition_box_field_text',
                'label'=> esc_html__('Additional box Field Text/Integer', 'wpdirectorykit'),
                'selector'=>'#wdk-form-additional .wdk-field input[type="text"]',
                'focus'=>'#wdk-form-additional .wdk-field input[type="text"]:focus',
                'options'=>'full',
            ],
            [
                'key'=>'addition_box_field_select',
                'label'=> esc_html__('Additional box Field Select', 'wpdirectorykit'),
                'selector'=>'#wdk-form-additional .wdk-field select',
                'options'=>'full',
            ],
            [
                'key'=>'addition_box_field_checkbox',
                'label'=> esc_html__('Additional box Field Checkbox', 'wpdirectorykit'),
                'selector'=>'#wdk-form-additional .wdk-field.CHECKBOX .wdk-field-label ',
                'options'=>'full',
            ],
        ];

        foreach ($items as $item) {
                $this->start_controls_section(
                    $item['key'].'_section',
                    [
                        'label' => $item['label'],
                        'tab' => 'tab_layout'
                    ]
                );
                
            if($item['key'] !='field_label' && $item['key'] !='field_slider_range')
                $this->add_responsive_control(
                    $item['key'].'_hide',
                        [
                                'label' => esc_html__( 'Hide Element', 'wpdirectorykit' ),
                                'type' => Controls_Manager::SWITCHER,
                                'none' => esc_html__( 'Hide', 'wpdirectorykit' ),
                                'block' => esc_html__( 'Show', 'wpdirectorykit' ),
                                'return_value' => 'none',
                                'default' => '',
                                'selectors' => [
                                    '{{WRAPPER}} '.$item['selector'] => 'display: {{VALUE}};',
                                ],
                        ]
                );

            if($item['key'] !='field_slider_range'){
                $selectors = array();

                if(strpos($item['selector'], '{{WRAPPER}}') === FALSE) {
                    $selectors['normal'] = '{{WRAPPER}} '.$item['selector'];
                    $selectors['hover'] = '{{WRAPPER}} '.$item['selector'].'%1$s';
                } else {
                    $selectors['normal'] = $item['selector'];
                    if(isset($item['selector_hover'])) {
                        $selectors['hover'] = $item['selector_hover'];
                    }
                }
        
                    
                if(isset($item['focus'])) {
                    $selectors['focus'] = '{{WRAPPER}} '.$item['focus'];
                }
            

                $this->generate_renders_tabs($selectors, $item['key'].'_dynamic', $item['options']);
            }

            if($item['key'] =='field_slider_range') {
                    
                $this->add_responsive_control(
                    'field_slider_range_color_circle',
                    [
                            'label' => esc_html__( 'Circle Color', 'wpdirectorykit' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .irs--round .irs-handle' => 'border-color: {{VALUE}};',
                            ],
                    ]
                );
                    
                $this->add_responsive_control(
                    'field_slider_range_color_line',
                    [
                            'label' => esc_html__( 'Line Color', 'wpdirectorykit' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .irs--round .irs-bar' => 'background-color: {{VALUE}};',
                            ],
                    ]
                );
                    
                $this->add_responsive_control(
                    'field_slider_range_color_label',
                    [
                            'label' => esc_html__( 'Label Color', 'wpdirectorykit' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .irs--round .irs-from, {{WRAPPER}} .irs--round .irs-to, {{WRAPPER}} .irs--round .irs-single' => 'background-color: {{VALUE}};',
                                    '{{WRAPPER}} .irs--round .irs-from::before,{{WRAPPER}} .irs--round .irs-to::before,{{WRAPPER}} .irs--round .irs-single::before' => 'border-top-color: {{VALUE}};',
                            ],
                    ]
                );
                    
                $this->add_responsive_control(
                    'field_slider_range_color_text_label',
                    [
                            'label' => esc_html__( 'Label Text Color', 'wpdirectorykit' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .irs--round .irs-from, {{WRAPPER}} .irs--round .irs-to, {{WRAPPER}} .irs--round .irs-single' => 'color: {{VALUE}};',
                            ],
                    ]
                );

                $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                            'name' => 'field_slider_range_color_typo',
                            'selector' =>  '{{WRAPPER}} .irs--round .irs-from, {{WRAPPER}} .irs--round .irs-to, {{WRAPPER}} .irs--round .irs-single',
                    ]
                );

                $this->add_responsive_control(
                    'field_slider_range_color_text_line',
                    [
                            'label' => esc_html__( 'Line Text Color', 'wpdirectorykit' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                    '{{WRAPPER}} .wdk-slider-range-field .irs--round .irs-grid-text' => 'color: {{VALUE}};',
                            ],
                    ]
                );

            }

            if($item['key'] =='field_tree') {
                    
                $this->add_control(
                    'styles_field_tree_pl_hr',
                    [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                    ]
                );
    
                $this->add_control(
                    'styles_field_tree_pl_header',
                    [
                        'label' => __( 'Placeholder', 'wpdirectorykit' ),
                        'type' => Controls_Manager::HEADING,
                    ]
                );

                      
                $this->add_control(
                    'styles_field_tree_pl_hr2',
                    [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                    ]
                );

                $selectors = array(
                    'normal' => '{{WRAPPER}} .wdk-field.CATEGORY .select2 .select2-search__field::placeholder,{{WRAPPER}} .wdk-field.LOCATION .select2 .select2-search__field::placeholder',
                    'hover'=>'{{WRAPPER}} .wdk-field.CATEGORY .select2 .select2-search__field%1$s::placeholder,{{WRAPPER}} .wdk-field.LOCATION .select2 .select2-search__field%1$s::placeholder',
                );
             
                $this->generate_renders_tabs($selectors, 'styles_field_tree_pl_list_dynamic', ['align','typo','color']);


                $this->add_control(
                    'styles_field_tree_hr',
                    [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                    ]
                );
    
                $this->add_control(
                    'styles_field_tree_header',
                    [
                        'label' => __( 'List Items', 'wpdirectorykit' ),
                        'type' => Controls_Manager::HEADING,
                    ]
                );

                      
                $this->add_control(
                    'styles_field_tree_hr2',
                    [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                    ]
                );
    
                $selectors = array(
                    'normal' => '.select_multi_dropdown_tree .select2-dropdown .select2-results__options .select2-results__option',
                    'hover'=>'.select_multi_dropdown_tree .select2-dropdown .select2-results__options .select2-results__option%1$s',
                );
             
                $this->generate_renders_tabs($selectors, 'styles_field_tree_list_dynamic', ['margin','align','typo','color','background','border','padding','transition']);

                if(wdk_get_option('wdk_multi_categories_search_field_type')=='select2' || wdk_get_option('wdk_multi_locations_search_field_type')=='select2' ) {
                    $this->add_control(
                        'styles_field_tree_items_hr',
                        [
                                'type' => \Elementor\Controls_Manager::DIVIDER,
                        ]
                    );
        
                    $this->add_control(
                        'styles_field_tree_items_header',
                        [
                            'label' => __( 'Multi Items for Multiple Dropdowns', 'wpdirectorykit' ),
                            'type' => Controls_Manager::HEADING,
                        ]
                    );

                        
                    $this->add_control(
                        'styles_field_tree_items_hr2',
                        [
                                'type' => \Elementor\Controls_Manager::DIVIDER,
                        ]
                    );
                
                    $selectors = array(
                        'normal' => '{{WRAPPER}} .wdk-field.CATEGORY .select2 .select2-selection__choice, {{WRAPPER}} .wdk-field.LOCATION .select2 .select2-selection__choice',
                        'hover'=>'{{WRAPPER}} .wdk-field.CATEGORY .select2 .select2-selection__choice%1$s, {{WRAPPER}} .wdk-field.LOCATION .select2 .select2-selection__choice%1$s',
                    );
                
                    $this->generate_renders_tabs($selectors, 'styles_field_tree_list_items_dynamic', ['margin','typo','color','background','border','border_radius','padding','transition']);
                }
            }

            if($item['key'] =='field_select2') {
                    
                $this->add_control(
                    'styles_field_select2_pl_hr',
                    [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                    ]
                );
    
                $this->add_control(
                    'styles_field_select2_pl_header',
                    [
                        'label' => __( 'Placeholder', 'wpdirectorykit' ),
                        'type' => Controls_Manager::HEADING,
                    ]
                );

                      
                $this->add_control(
                    'styles_field_select2_pl_hr2',
                    [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                    ]
                );

                $selectors = array(
                    'normal' => '{{WRAPPER}} .wdk-field.DROPDOWNMULTIPLE .select2 .select2-search__field::placeholder',
                    'hover'=>'{{WRAPPER}} .wdk-field.DROPDOWNMULTIPLE .select2 .select2-search__field%1$s::placeholder',
                );
             
                $this->generate_renders_tabs($selectors, 'styles_field_select2_pl_list_dynamic', ['align','typo','color']);

                $this->add_control(
                    'styles_field_select2_hr',
                    [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                    ]
                );
    
                $this->add_control(
                    'styles_field_select2_header',
                    [
                        'label' => __( 'List Items', 'wpdirectorykit' ),
                        'type' => Controls_Manager::HEADING,
                    ]
                );

                      
                $this->add_control(
                    'styles_field_select2_hr2',
                    [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                    ]
                );
    

                $selectors = array(
                    'normal' => '.select_multi_dropdown .select2-dropdown .select2-results__options .select2-results__option',
                    'hover'=>'.select_multi_dropdown .select2-dropdown .select2-results__options .select2-results__option%1$s',
                );
             
                $this->generate_renders_tabs($selectors, 'styles_field_select2_list_dynamic', ['margin','align','typo','color','background','border','padding','transition']);

                $this->add_control(
                    'styles_field_select2_items_hr',
                    [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                    ]
                );
    
                $this->add_control(
                    'styles_field_select2_items_header',
                    [
                        'label' => __( 'Multi Items', 'wpdirectorykit' ),
                        'type' => Controls_Manager::HEADING,
                    ]
                );

                    
                $this->add_control(
                    'styles_field_select2_items_hr2',
                    [
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                    ]
                );
            
                $selectors = array(
                    'normal' => '{{WRAPPER}} .wdk-field.DROPDOWNMULTIPLE .select2 .select2-selection__choice',
                    'hover'=>'{{WRAPPER}} .wdk-field.DROPDOWNMULTIPLE .select2 .select2-selection__choice%1$s',
                );
            
                $this->generate_renders_tabs($selectors, 'styles_field_select2_list_items_dynamic', ['margin','typo','color','background','border','border_radius','padding','transition']);

            }

            $this->end_controls_section();
            /* END special for some elements */

        }
    }

    private function generate_controls_content() {
        
    }
            
    public function enqueue_styles_scripts() {
        wp_enqueue_style('slick');
        wp_enqueue_style('wdk-suggestion');
        wp_enqueue_style('slick-theme');
        wp_enqueue_script('slick');
        wp_enqueue_script('wdk-treefield');
        wp_enqueue_script('wdk-suggestion');
        wp_enqueue_style('wdk-treefield');

        
        wp_enqueue_script('select2');
        wp_enqueue_script('wdk-select2');
        wp_enqueue_style('select2');

        wp_enqueue_script( 'ion.range-slider' );
        wp_enqueue_style('ion.range-slider');
        wp_enqueue_style('wdk-slider-range');
        wp_enqueue_script('wdk-slider-range');

        wp_enqueue_style( 'wdk-treefield-checkboxes');
        wp_enqueue_script( 'wdk-treefield-checkboxes');
    }
}
