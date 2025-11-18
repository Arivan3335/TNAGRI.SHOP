<?php 
namespace blocks_taily_settings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!function_exists('taily_enqueue_block_editor1'))
{
    function taily_enqueue_block_editor1()
    {
    
        $asset_file = include(plugin_dir_path(__FILE__).'blocks-taily-settings'.DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'index.asset.php');
    
        wp_enqueue_script(
            'taily-custom-css-adder-block', 
            plugins_url('blocks-taily-settings/build/index.js', __FILE__), 
            $asset_file['dependencies'], 
            $asset_file['version'], 
            true,
        );
    }
}
add_action('enqueue_block_editor_assets', __NAMESPACE__.'\taily_enqueue_block_editor1');


if(!function_exists('taily_block_core_func1'))
{
    function taily_block_core_func1($block_content, $block)
    {


        if(isset($block['attrs']['tailyCustomCss'])){

            $p = new \WP_HTML_Tag_Processor($block_content);

            if(isset($block['attrs']['tailyCustomTag'])){
                $has_inner_tag = $block['attrs']['tailyCustomTag'];
            }else $has_inner_tag = '';
            

            if($has_inner_tag == NULL || $has_inner_tag == 'null' || trim($has_inner_tag) == ''){
                /*if($p->next_tag()){
                    $p->add_class($block['attrs']['tailyCustomCss']);//Deprecated (due to doubel css-class rendering)
                }*/
            }else {
                if($p->next_tag($has_inner_tag)){
                    $p->add_class($block['attrs']['tailyCustomCss']);
                }
            }

            $block_content = $p->get_updated_html();
        }

        

        return $block_content;
    }
}

add_filter('render_block', __NAMESPACE__.'\taily_block_core_func1', 10, 2);

//close the PHP tag to reduce the blank spaces ?>