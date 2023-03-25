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
class WdkListingMap extends WdkElementorBase {

    public function __construct($data = array(), $args = null) {

        \Elementor\Controls_Manager::add_tab(
            'tab_conf',
            esc_html__('Settings', 'wpdirectorykit')
        );

        \Elementor\Controls_Manager::add_tab(
            'tab_layout',
            esc_html__('InfoWindow', 'wpdirectorykit')
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
		return [ 'wdk-elementor-listing-preview' ];
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
        return 'wdk-listing-map';
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
        return esc_html__('Wdk Listing Map', 'wpdirectorykit');
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
        return 'eicon-google-maps';
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
        global $wdk_listing_id;

        $this->data['id_element'] = $this->get_id();
        $this->data['settings'] = $this->get_settings();

        /* default from settings */
        $this->data['lat'] = wdk_get_option('wdk_default_lat', 51.505);
        $this->data['lng'] = wdk_get_option('wdk_default_lng', -0.09);
        $this->data['wdk_listing_id'] = $wdk_listing_id;

        if(!Plugin::$instance->editor->is_edit_mode() && !empty($wdk_listing_id)) {
            $this->data['lat'] =  wdk_field_value ('lat', $wdk_listing_id);
            $this->data['lng'] =  wdk_field_value ('lng', $wdk_listing_id);
        } else {
            
        }     

        $this->data['is_edit_mode']= false;          
        if(Plugin::$instance->editor->is_edit_mode() || empty($wdk_listing_id)) {
            $this->data['is_edit_mode']= true;
        }
      
        echo $this->view('wdk-listing-map', $this->data); 
    }

    private function generate_controls_conf() {
        if(true){
                $this->start_controls_section(
                    'conf_custom_map',
                    [
                        'label' => esc_html__('Map', 'wpdirectorykit'),
                        'tab' => '1',
                    ]
                );

                $this->add_responsive_control(
                        'conf_custom_map_height',
                        [
                            'label' => esc_html__('Height', 'wpdirectorykit'),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 100,
                                    'max' => 1500,
                                ],
                            ],
                            'render_type' => 'template',
                            'default' => [
                                'size' => 350,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .wdk-element #wdk_map_results' => 'height: {{SIZE}}px !important',
                            ],
                            'separator' => 'after',
                        ]
                ); 

                $this->add_control(
                        'conf_custom_map_zoom_index',
                        [
                            'label' => esc_html__('Zoom Index', 'wpdirectorykit'),
                            'description' => esc_html__( 'Only active if auto centering is disabled', 'wpdirectorykit' ),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 1,
                                    'max' => 18,
                                ],
                            ],
                            'render_type' => 'template',
                            'default' => [
                                'size' => 7,
                            ],
                        ]
                );
                
                $this->add_responsive_control(
                    'conf_custom_dragging',
                    [
                            'label' => esc_html__( 'Dragging', 'wpdirectorykit' ),
                            'type' => Controls_Manager::SWITCHER,
                            'none' => esc_html__( 'True', 'wpdirectorykit' ),
                            'block' => esc_html__( 'False', 'wpdirectorykit' ),
                            'render_type' => 'template',
                            'return_value' => 'yes',
                            'default' => 'yes',
                            'separator' => 'before',
                    ]
                );

                                
                $this->add_responsive_control(
                    'conf_custom_popup_enable',
                    [
                            'label' => esc_html__( 'Enable Infobox', 'wpdirectorykit' ),
                            'type' => Controls_Manager::SWITCHER,
                            'none' => esc_html__( 'True', 'wpdirectorykit' ),
                            'block' => esc_html__( 'False', 'wpdirectorykit' ),
                            'render_type' => 'template',
                            'return_value' => 'yes',
                            'default' => 'yes',
                            'separator' => 'before',
                    ]
                );


                $this->add_control(
                    'conf_custom_map_styles_h',
                    [
                        'label' => __( 'Map Styles', 'wpdirectorykit' ),
                        'type' => \Elementor\Controls_Manager::HEADING,
                        'separator' => 'before',
                    ]
                );

                $this->add_control(
                    'conf_custom_map_style',
                    [
                        'label' => esc_html__('Styles List', 'wpdirectorykit'),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            '' => esc_html__('Default', 'wpdirectorykit'),
                            'custom' => esc_html__('Custom', 'wpdirectorykit'),
                            'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png' => esc_html__('Light', 'wpdirectorykit'),
                            'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png' => esc_html__('Osmde', 'wpdirectorykit'),
                            'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png' => esc_html__('OpenTopoMap', 'wpdirectorykit'),
                            'https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}.png' => esc_html__('OpenCycleMap', 'wpdirectorykit'),
                            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}' => esc_html__('WorldImagery', 'wpdirectorykit'),
                            'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png' => esc_html__('Carto DarkMatter', 'wpdirectorykit'),
                            'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png' => esc_html__('Carto Voyager', 'wpdirectorykit'),
                            'https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}{r}.png' => esc_html__('Maptiler dark (demo)', 'wpdirectorykit'),
                        ],
                        'default' => '',
                    ]
                );

                $this->add_control(
                    'conf_custom_map_style_self',
                    [
                        'label' => esc_html__('Link to custom Map Style', 'wpdirectorykit'),
                        'description' => esc_html__( 'You can add some custom map by link example https://leaflet-extras.github.io/leaflet-providers/preview/ or create your custom style and put link for example on maps.cloudmade.com/editor', 'wpdirectorykit' ),
                        'type' => Controls_Manager::TEXTAREA,
                        'render_type' => 'template',
                        'conditions' => [
                            'terms' => [
                                [
                                    'name' => 'conf_custom_map_style',
                                    'operator' => '==',
                                    'value' => 'custom',
                                ]
                            ],
                        ]
                    ]
                );



            $this->end_controls_section();
        }
    }

    private function generate_controls_layout() {

        /* default marker layout */
        $this->generate_controls_layout_default();
    }

    private function generate_controls_styles() {
        /* marker */
        $this->start_controls_section(
                'styles_marker_sec',
                [
                    'label' => esc_html__('Marker', 'wpdirectorykit'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );


        $this->add_control(
                'styles_marker_h',
                [
                    'label' => esc_html__('Marker', 'wpdirectorykit'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        
        $this->start_controls_tabs('marker_button_style');

        $this->start_controls_tab(
                'marker',
                [
                    'label' => esc_html__('Normal', 'wpdirectorykit'),
                ]
        );

        $this->add_control(
                'styles_marker_color',
                [
                    'label' => esc_html__('Marker Border Color', 'wpdirectorykit'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wdk-element .wdk-map .wdk_marker-card::before' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'styles_marker_color_bckg',
                [
                    'label' => esc_html__('Marker Color', 'wpdirectorykit'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wdk-element .wdk_marker-card:after' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'styles_marker_color_text',
                [
                    'label' => esc_html__('Marker Text Color', 'wpdirectorykit'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wdk_face i' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'conf_custom_map_pin_icon',
                [
                    'label' => esc_html__('Icon', 'wpdirectorykit'),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'default' => [
                        'value' => 'fa fa-home',
                        'library' => 'solid',
                    ],
                ]
        );

        $this->add_control(
                'conf_custom_map_pin',
                [
                    'label' => esc_html__('Custom Marker Pin Image', 'wpdirectorykit'),
                    'type' => Controls_Manager::MEDIA,
                ]
        );

        $this->end_controls_tab();
    
        $this->start_controls_tab(
                'marker_hover',
                [
                    'label' => esc_html__('Hover', 'wpdirectorykit'),
                ]
        );

        $this->add_control(
                'styles_marker_color_hover',
                [
                    'label' => esc_html__('Marker Border Color', 'wpdirectorykit'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wdk-element .wdk_marker-container:hover .wdk_marker-card:before' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'styles_marker_color_hover_bckg',
                [
                    'label' => esc_html__('Marker Color', 'wpdirectorykit'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wdk-element .wdk_marker-container:hover .wdk_marker-card:after' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'styles_marker_color_text_hover',
                [
                    'label' => esc_html__('Marker Text Color', 'wpdirectorykit'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wdk_marker-container:hover .wdk_face.back i, .wdk_marker-container:hover .wdk_face i' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'styles_marker_effect_duration',
                [
                    'label' => esc_html__('Transition Duration', 'wpdirectorykit'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .wdk_map-marker-container.clicked .wdk_face.front, .wdk_marker-container:hover .wdk_face.front' => 'transition-duration: {{SIZE}}ms',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function generate_controls_content() {
    }

    private function generate_controls_layout_default() {

    }

    private function generate_controls_styles_default() {
      
    }
        
    public function enqueue_styles_scripts() {
        wp_enqueue_style('leaflet');
        wp_enqueue_style('leaflet-cluster-def');
        wp_enqueue_style('leaflet-cluster');
        wp_enqueue_script('leaflet');
        wp_enqueue_script('leaflet-cluster');
                
        wp_enqueue_style('wdk-notify');
        wp_enqueue_script('wdk-notify');
		wp_enqueue_style( 'wdk-listings-map' );
    }
}
