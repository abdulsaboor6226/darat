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
use Elementor\Group_Control_Css_Filter;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class WdkCategoriesGridCover extends WdkElementorBase {

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
        return 'wdk-categories-grid-cover';
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
        return esc_html__('Wdk Categories Grid Cover', 'wpdirectorykit');
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
        return 'eicon-site-logo';
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

        $controller = 'category';
        $this->WMVC->model($controller.'_m');
        
        $this->data['results'] = array();
        
        $controller = 'category';
        $this->WMVC->model($controller.'_m');
        $this->WMVC->model('listing_m');

        $this->data['results'] = false;
        
        if($this->data['settings']['conf_results_type'] == 'custom_categories') {

            $categories_ids = array();
            foreach($this->data['settings']['conf_custom_results'] as $category) {
                if(isset($category['category_id']) && !empty($category['category_id'])) {
                    $categories_ids [] = $category['category_id'];
                }
            }
            
            /* where in */
            if(!empty($categories_ids)){

                $this->WMVC->db->select($this->WMVC->{$controller.'_m'}->_table_name.'.*, COUNT('.$this->WMVC->listing_m->_table_name.'.post_id) AS listings_counter');
                $this->WMVC->db->join($this->WMVC->listing_m->_table_name.' ON '.$this->WMVC->listing_m->_table_name.'.category_id = '.$this->WMVC->{$controller.'_m'}->_table_name.'.idcategory', TRUE, 'LEFT');
                $this->WMVC->db->where($this->WMVC->{$controller.'_m'}->_table_name.'.idcategory IN(' . implode(',', $categories_ids) . ')', null, false);
                $this->WMVC->db->order_by('FIELD('.$this->WMVC->{$controller.'_m'}->_table_name.'.idcategory, '. implode(',', $categories_ids) . ')');
                $this->WMVC->db->group_by($this->WMVC->{$controller.'_m'}->_primary_key);
               
                $this->data['results'] = $this->WMVC->{$controller.'_m'}->get();
            }

        } else {
            $order_by = NULL;
            /* deprecated, added column listings_counter for this */
            if($this->data['settings']['conf_order_by'] == 'order_most') {
                /* get category with most listings */
                global $wpdb;
                $order_by = 'listings_counter '.$this->data['settings']['conf_order'];
                $this->data['results'] = $this->WMVC->{$controller.'_m'}->get_pagination($this->data['settings']['conf_limit'], null, array(), $order_by);
            } else {
                if (!empty($this->data['settings']['conf_order_by'])) {
                    $order_by = $this->data['settings']['conf_order_by'].' '.$this->data['settings']['conf_order'];
                }
                
                $this->data['results'] = $this->WMVC->{$controller.'_m'}->get_pagination($this->data['settings']['conf_limit'], null, array(), $order_by);
            }
        }

        $this->data['is_edit_mode']= false;          
        if(Plugin::$instance->editor->is_edit_mode())
            $this->data['is_edit_mode']= true;
      
        echo $this->view('wdk-categories-grid-cover', $this->data); 
    }


    private function generate_controls_conf() {
        $this->start_controls_section(
            'tab_conf_main_section',
            [
                'label' => esc_html__('Main', 'wpdirectorykit'),
                'tab' => '1',
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
            'conf_results_type',
            [
                'label' => __( 'Show type', 'wpdirectorykit' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'results_categories',
                'options' => [
                    'results_categories'  => __( 'All Categories', 'wpdirectorykit' ),
                    'custom_categories' => __( 'Specific', 'wpdirectorykit' ),
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'important_note',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(__( 'Edit Categories <a href="%1$s" target="_blank"> open </a>', 'wpdirectorykit' ), admin_url('admin.php?page=wdk_category')),
                'content_classes' => 'wdk_elementor_hint',
            ]
        );

        $this->add_control(
            'conf_limit',
            [
                'label' => __( 'Limit Categories', 'wpdirectorykit' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 50,
                'step' => 1,
                'default' => 3,
                'separator' => 'before',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'conf_results_type',
                            'operator' => '==',
                            'value' => 'results_categories',
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'conf_order_by',
            [
                'label'         => __('Order By Column', 'wpdirectorykit'),
                'type'          => Controls_Manager::SELECT,
                'label_block'   => true,
                'options'       => [
                    ''  => __('None', 'wpdirectorykit'),
                    'category_title' => __('Title', 'wpdirectorykit'),
                    'idcategory' => __('Location id', 'wpdirectorykit'),
                    'order_index' => __('Order index', 'wpdirectorykit'),
                    'listings_counter' => __('Most Listings', 'wpdirectorykit'),
                ],
                'default' => 'order_index',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'conf_results_type',
                            'operator' => '==',
                            'value' => 'results_categories',
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'conf_order',
            [
                'label'         => __('Order', 'wpdirectorykit'),
                'type'          => Controls_Manager::SELECT,
                'label_block'   => true,
                'options'       => [
                    'asc'           => __('Ascending', 'wpdirectorykit'),
                    'desc'          => __('Descending', 'wpdirectorykit')
                ],
                'default'       => 'desc',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'conf_results_type',
                            'operator' => '==',
                            'value' => 'results_categories',
                        ]
                    ],
                ],
            ]
        );

        if(true){
            $repeater = new Repeater();
            $repeater->start_controls_tabs( 'categories' );
            $repeater->add_control(
                'category_id',
                [
                    'label' => __( 'ID category', 'wpdirectorykit' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'step' => 1,
                ]
            );
            $repeater->end_controls_tabs();

                            
            $this->add_control(
                'conf_custom_results',
                [
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                    ],
                    'title_field' => '{{{ category_id }}}',
                    'conditions' => [
                        'terms' => [
                            [
                                'name' => 'conf_results_type',
                                'operator' => '==',
                                'value' => 'custom_categories',
                            ]
                        ],
                    ],
                ]
            );
        }

        $this->end_controls_section();

    }

    private function generate_controls_layout() {
    }

    private function generate_controls_styles() {
            $this->start_controls_section(
                'styles_thmbn_section',
                [
                    'label' => esc_html__('Main', 'wpdirectorykit'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_responsive_control(
                'row_gap_col',
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
                            'auto' => '-webkit-flex:1 2 auto;flex:1 2 auto',
                            '100%' =>  '-webkit-flex:1 2 100%;flex:1 2 100%',
                            '50%' =>  '-webkit-flex:1 2 50%;flex:1 2 50%',
                            'calc(100% / 3)' =>  '-webkit-flex:1 2 calc(100% / 3);flex:1 2 calc(100% / 3)',
                            '25%' =>  '-webkit-flex:1 2 25%;flex:1 2 25%',
                            '20%' =>  '-webkit-flex:1 2 20%;flex:1 2 20%',
                            'auto' =>  '-webkit-flex:1 2 auto;flex:1 2 auto',
                            'auto_flexible' =>  '-webkit-flex:1 2 auto;flex:1 2 auto',
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .wdk-row .wdk-col' => '{{UNIT}}',
                        ],
                        'default' => 'calc(100% / 3)', 
                        'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'column_gap',
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
                        '{{WRAPPER}} .wdk-row .wdk-col' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};;',
                        '{{WRAPPER}} .wdk-row' => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_responsive_control(
                'row_gap',
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
                        '{{WRAPPER}} .wdk-row  .wdk-col' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .wdk-row' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'content_thumbnail_section',
            [
                'label' => esc_html__('Colors', 'wpdirectorykit'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_thumbnail_section_header',
            [
                'label' => esc_html__('Color Hover Thumbnail', 'wpdirectorykit'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'content_thumbnail_section_d_background',
            [
                'label' => esc_html__( 'Color', 'wpdirectorykit' ),
                'description' => esc_html__( 'Set some opacity for color', 'wpdirectorykit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wdk-categories-card-cover::before, {{WRAPPER}} .wdk-categories-card-cover::after,{{WRAPPER}} .wdk-categories-card-cover .overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section(
            'styles_card',
            [
                'label' => esc_html__('Card', 'wpdirectorykit'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $selectors = array(
            'normal' => '{{WRAPPER}} .wdk-categories-card-cover',
            'hover' => '{{WRAPPER}} .wdk-categories-card-cover%1$s',
        );
        $this->generate_renders_tabs($selectors, 'styles_card_dynamic', ['background','border','border_radius','padding','shadow','transition']);

        $this->add_responsive_control (
            'styles_card_height',
            [
                'label' => esc_html__('Height', 'wpdirectorykit'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                    ],   
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => [ 'px', 'vw' ],
                'selectors' => [
                    '{{WRAPPER}} .wdk-categories-card-cover' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            't_content_basic_position_x',
            [
                'label' => __( 'Position Content', 'wpdirectorykit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                            'title' => esc_html__( 'Top', 'wpdirectorykit' ),
                            'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                            'title' => esc_html__( 'Center', 'wpdirectorykit' ),
                            'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                            'title' => esc_html__( 'bottom', 'wpdirectorykit' ),
                            'icon' => ' eicon-v-align-bottom',
                    ],
                    'stretch' => [
                            'title' => esc_html__( 'Stretch', 'wpdirectorykit' ),
                            'icon' => 'eicon-v-align-stretch',
                    ],
                ],
                'default' => 'left',
                'render_type' => 'template',
                'selectors_dictionary' => [
                    'top' => 'align-items: flex-start;',
                    'center' => 'align-items: center;',
                    'bottom' => 'align-items: flex-end;',
                    'stretch' => 'align-items: stretch;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wdk-categories-card-cover' => '{{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .wdk-categories-card-cover .wdk-image',
			]
		);

        $this->add_responsive_control(
            't_content_basic_mask',
            [
                'label' => esc_html__( 'Mask', 'wpdirectorykit' ),
                'description' => esc_html__( 'Set mask for thumbnail color', 'wpdirectorykit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wdk-categories-card-cover .mask' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            't_content_basic_mask_hover',
            [
                'label' => esc_html__( 'Mask Hover', 'wpdirectorykit' ),
                'description' => esc_html__( 'Set mask for thumbnail color', 'wpdirectorykit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wdk-categories-card-cover:hover .mask' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        $items = [
            [
                'key'=>'content',
                'label'=> esc_html__('Content', 'wpdirectorykit'),
                'selector'=>'.wdk-categories-card-body',
                'selector_hover'=>'.wdk-categories-card-cover%1$s .wdk-categories-card-body',
                'options'=>['background','border','border_radius','padding','shadow','transition'],
            ],
            [
                'key'=>'content_title',
                'label'=> esc_html__('Title', 'wpdirectorykit'),
                'selector'=>'.wdk-categories-card-cover .wdk-categories-card-body .wdk-title',
                'selector_hover'=>'.wdk-categories-card-cover%1$s .wdk-categories-card-body .wdk-title',
                'options'=>'full',
            ],
            [
                'key'=>'content_subtitle',
                'label'=> esc_html__('Count', 'wpdirectorykit'),
                'selector'=>'.wdk-categories-card-cover .wdk-categories-card-body .wdk-listings-count',
                'selector_hover'=>'.wdk-categories-card-cover%1$s .wdk-categories-card-body .wdk-listings-count',
                'options'=>'full',
            ],
            [
                'key'=>'content_button',
                'label'=> esc_html__('Button', 'wpdirectorykit'),
                'selector'=>'.wdk-categories-card-cover .wdk-categories-card-body .wdk-category-btn',
                'selector_hover'=>'.wdk-categories-card-cover%1$s .wdk-categories-card-body .wdk-category-btn',
                'options'=>['margin','align','color','background','border','border_radius','padding','shadow','transition'],
            ],
        ];

        foreach ($items as $item) {
            $this->start_controls_section(
                $item['key'].'_section',
                [
                    'label' => $item['label'],
                    'tab' =>  Controls_Manager::TAB_STYLE,
                ]
            );
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
            
            $selectors = array(
                'normal' => '{{WRAPPER}} '.$item['selector'],
                'hover'=>'{{WRAPPER}} '.$item['selector_hover'],
            );
            $this->generate_renders_tabs($selectors, $item['key'].'_dynamic', $item['options']);

            if( $item['key'] =='content') {
                        
                $this->add_control(
                    $item['key'].'_dynamic_padding',
                    [
                        'label' => esc_html__('Text Part', 'wpdirectorykit'),
                        'type' => Controls_Manager::HEADING,
                    ]

                );
                $selectors = array(
                    'normal' => '{{WRAPPER}} .wdk-categories-card-cover .wdk-categories-card-body .wdk-left-content',
                    'hover'=>'{{WRAPPER}} .wdk-categories-card-cover .wdk-categories-card-body .wdk-left-content'
                );
                $this->generate_renders_tabs($selectors, $item['key'].'_dynamic_padding', ['padding']);
            }

            if( $item['key'] =='content_button') {
                $this->add_control(
                        'link_icon',
                        [
                            'label' => esc_html__('Icon', 'wpdirectorykit'),
                            'type' => Controls_Manager::ICONS,
                            'label_block' => true,
                            'default' => [
                                'value' => 'fa fa-angle-right',
                                'library' => 'solid',
                            ],
                        ]
                );

                $this->add_responsive_control (
                    'link_icon_height',
                    [
                        'label' => esc_html__('Height', 'wpdirectorykit'),
                        'type' => Controls_Manager::SLIDER,
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 1500,
                            ],   
                            'vw' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'size_units' => [ 'px', 'vw' ],
                        'selectors' => [
                            '{{WRAPPER}} .wdk-categories-card-cover .wdk-categories-card-body .wdk-category-btn' => 'font-size: {{SIZE}}{{UNIT}}',
                        ],
                        'separator' => 'after',
                    ]
                );

            }

            $this->end_controls_section();
        }

        $this->start_controls_section(
            'content_icon_section',
            [
                'label' => esc_html__( 'Icon', 'wpdirectorykit' ),
                'tab' =>  Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'content_icon_show',
                [
                    'label' => esc_html__( 'Show Element', 'wpdirectorykit' ),
                    'type' => Controls_Manager::SWITCHER,
                    'none' => esc_html__( 'Hide', 'wpdirectorykit' ),
                    'block' => esc_html__( 'Show', 'wpdirectorykit' ),
                    'return_value' => 'flex',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .wdk-categories-card-cover .wdk-categories-card-body .wdk-action-left' => 'display: flex;',
                    ],
                ]
        );

        
        $this->add_control(
            'content_icon_type',
            [
                'label' => __( 'Show type', 'wpdirectorykit' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'font',
                'options' => [
                    'font'  => __( 'Font icon code', 'wpdirectorykit' ),
                    'image' => __( 'Image Icon', 'wpdirectorykit' ),
                ],
            ]
        );
        
        $selectors = array(
            'normal' => '{{WRAPPER}} .wdk-categories-card-cover .wdk-categories-card-body .wdk-action-left',
            'hover'=>'{{WRAPPER}} .wdk-categories-card-cover%1$s .wdk-categories-card-body .wdk-action-left',
        );
        $this->generate_renders_tabs($selectors, 'content_icon_dynamic', ['margin','color','background','border','border_radius','padding','shadow','transition']);
        
        $this->add_control(
            'content_icon_im_tab',
            [
                'label' => esc_html__('Image Icon', 'wpdirectorykit'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $selectors = array(
            'normal' => '{{WRAPPER}} .wdk-categories-card-cover .wdk-categories-card-body .wdk-action-left img',
        );
        $this->generate_renders_tabs($selectors, 'content_icon_im_dynamic', ['image_size_control']);

        $this->add_control(
            'content_icon_f_tab',
            [
                'label' => esc_html__('Font Icon', 'wpdirectorykit'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $selectors = array(
            'normal' => '{{WRAPPER}} .wdk-categories-card-cover .wdk-categories-card-body .wdk-action-left i',
        );
        $this->generate_renders_tabs($selectors, 'content_icon_f_dynamic', ['font-size']);


        $this->end_controls_section();
    }

    private function generate_controls_content() {

    }
            
    public function enqueue_styles_scripts() {
        wp_enqueue_style('wdk-categories-grid-cover');
    }
}
