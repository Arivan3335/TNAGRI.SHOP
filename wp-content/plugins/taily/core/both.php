<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('tailbymdz_both'))
{
    
    class tailbymdz_both extends tailbymdz_core
    {

        //Props
        private static $instance;

        function __Construct()
        {

            //Add gutenberg admin-JS
            if($this->is_gutenberg_active())
            {
            }

            //Add Elementor Settings
            if($this->check_plg('elementor.php'))
            {
                require_once TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'elementor'.DIRECTORY_SEPARATOR.'taily-textarea.php';
            }

            
            //add_filter('style_loader_tag', [$this, 'change_css_tags'], 10, 2);
            
            
        }

        //Methods
        public static function get_instance() 
        {
            if ( ! isset( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        //Miscs
        private function is_gutenberg_active() 
        {
            
            $gutenberg    = false;
            $block_editor = false;
    
            if ( has_filter( 'replace_editor', 'gutenberg_init' ) ) {
                // Gutenberg is installed and activated.
                $gutenberg = true;
            }
    
            if ( version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' ) ) {
                // Block editor.
                $block_editor = true;
            }
    
            if ( ! $gutenberg && ! $block_editor ) {
                return false;
            }
    
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
    
            if ( ! is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
                return true;
            }
    
            $use_block_editor = ( get_option( 'classic-editor-replace' ) === 'no-replace' );
    
            return $use_block_editor;
        }
        /*public function change_css_tags($tag, $handle)
        {
            // Check if the handle corresponds to the stylesheet you want to modify
            if ($handle == 'taily-cdn-confs' || $handle == 'taily-cdn-confs-css') {
                // Replace the existing type attribute with the new value
                $tag = str_replace("type='text/css'", "type='text/tailwindcss'", $tag);
            }

            return $tag;
        }*/
    }
}

//$tailbymdz_both = new tailbymdz_both();

tailbymdz_both::get_instance();

//close the PHP tag to reduce the blank spaces ?>