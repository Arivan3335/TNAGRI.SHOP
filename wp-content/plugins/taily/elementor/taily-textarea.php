<?php


use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Plugin;
use Elementor\Core\Settings\Manager as SettingsManager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('tailbymdz_elem_textarea'))
{
class tailbymdz_elem_textarea extends tailbymdz_core
{

    //Props
    private static $instance;
    

    function __Construct()
	{

        add_action( 'elementor/element/container/section_layout/after_section_end', [$this, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/column/section_advanced/after_section_end', [$this, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [$this, 'add_controls_section' ], 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [$this, 'add_controls_section' ], 1 );

		add_action( 'elementor/frontend/before_render', [$this, 'before_section_render' ], 1 );

		add_action( 'elementor/documents/register_controls', [$this, 'register_page_controls'] );

		add_action( 'elementor/document/before_save', [$this, 'save_elementor_post'], 10, 2 );

		add_filter( 'elementor/document/config', [$this, 'init_elementor_post'], 10, 2);

    }

    //Methods
    public static function get_instance() 
	{
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    

	public function add_controls_section( Element_Base $element) 
	{

		$tabs = Controls_Manager::TAB_CONTENT;

		if ( 'section' === $element->get_name() || 'column' === $element->get_name()  || 'container' === $element->get_name() ) {
			$tabs = Controls_Manager::TAB_LAYOUT;
		}

		$element->start_controls_section(
			/*'_section_ha_wrapper_link',
			[
				'label' => esc_html__( 'Taily-CSS', 'tailybymdz' ),
				'tab'   => $tabs,
			]*///Deprecated due to name conflicts!

			'_section_taily_wrapper_link',
			[
				'label' => esc_html__( 'Taily-CSS', 'tailybymdz' ),
				'tab'   => $tabs,
			]
		);

        $element->add_control(
			'taily_element_css',
			[
				'label' => esc_html__( 'CSS-Classes', 'tailybymdz' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'ai' => [
					'active' => false,
				],
				'dynamic' => [
					'active' => true,
				],
				'prefix_class' => '',
				'title' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: w-full text-center ...', 'tailybymdz' ),
				'classes' => 'elementor-control-direction-ltr',
			]
		);

		$element->end_controls_section();
	}

	public function before_section_render( Element_Base $element ) 
	{

		$css_classes = $element->get_settings_for_display( 'taily_element_css' );
        $css_classes = esc_html($css_classes);

		if ( isset($css_classes) && ! empty( $css_classes) ) {
			$element->add_render_attribute(
				'_wrapper',
				[
					'class' => $css_classes,
				]
			);
		}
	}

	//Add to page Settings
	public function register_page_controls( $document ) 
	{

		if ( ! $document instanceof \Elementor\Core\DocumentTypes\PageBase || ! $document::get_property( 'has_elements' ) ) {
			return;
		}

		$meta = get_post_meta($document->get_main_id(), 'tailybymdz_thisp_mode', true);
		if($meta != 'on'){
			$meta = 'off';
		}
	
		$document->start_controls_section(
			'taily_section',
			[
				'label' => esc_html__( 'Taily Page Settings', 'tailybymdz' ),
				'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);
	
		$document->add_control(//Productive Mode?
            'taily_prod_mode',
            [
                'label' => esc_html__('Productive Mode?', 'tailybymdz'),
				'description' => esc_html__('Help: Whenever the design of this page is finished, you can activate this option so that all the CSS codes of this page are added to the page more optimally and do not slow down the rendering speed of the page.', 'tailybymdz'),
                'type' => \Elementor\Controls_Manager::SELECT,
				'default' => $meta,
				'options' => [
                    'on'       => esc_html__('Active', 'tailybymdz'),
                    'off'     => esc_html__('InActive', 'tailybymdz'),
				],
				
            ]
        );
	
		$document->end_controls_section();
	}
	public function init_elementor_post($data, $post_id)
	{

		$meta = get_post_meta($post_id, 'tailybymdz_thisp_mode', true);
		if($meta != 'on'){
			$meta = 'off';
		}

		$data['settings']['settings']['taily_prod_mode'] = $meta;

		return $data;
		
	}
	public function save_elementor_post($obj, $data): void
	{


		$taily_prod_mode = '';

		if(isset($data['settings'])){
			$taily_prod_mode = $data['settings']['taily_prod_mode'];
		}
		
		$post_id = $obj->get_main_id();

		if($taily_prod_mode == 'yes' || $taily_prod_mode == 'on'){
			update_post_meta($post_id, 'tailybymdz_thisp_mode', 'on');
		}else {
			update_post_meta($post_id, 'tailybymdz_thisp_mode', '');
			update_post_meta($post_id, 'taily_productivecss_gen', 'no');
		}

		//$this->logger($post_id);

	}

}
}

tailbymdz_elem_textarea::get_instance();
//close the PHP tag to reduce the blank spaces ?>