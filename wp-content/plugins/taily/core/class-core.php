<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('tailbymdz_core'))
{
    class tailbymdz_core 
    {

        //Methods
        protected function check_plg($php_file) 
        {

            $all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
    
            if (stripos(implode($all_plugins), $php_file)) {
    
                return true;
    
            } else return false;
    
        }

        //Version Increaser
        public function version_increaser($ver)
        {

            $ver_arr = explode('.', $ver);
            $indexer = count($ver_arr)-1;

            $outputter = [];

            for($i = $indexer; $i >= 0; $i--){
                
                $tmp = intval($ver_arr[$i]);
                $tmp2 = intval($ver_arr[$i-1]);

                if($tmp <= 999){

                    $tmp++;
                    $ver_arr[$i] = $tmp;
                    break;

                }else if($i != 0 && $tmp > 999){
                    $tmp = 0;
                    $tmp2++;
                    $ver_arr[$i] = $tmp;
                    $ver_arr[$i-1] = $tmp2;
                }
            }

            $outputter = implode('.', $ver_arr);
            return $outputter;
        }

        public function posts_ver_manager($post_id, $meta_key, $mode=1)
        {
            /**
             * mode-1 => for simple post-types
             * mode-1 => for template-type post-types
            */

            if($mode == 1){
                $has_tmp_ver = get_post_meta($post_id, $meta_key, true);
            }else $has_tmp_ver = get_option($meta_key.$post_id);
            

            if(!$has_tmp_ver || trim($has_tmp_ver) == ''){
                if($mode == 1){
                    update_post_meta($post_id, $meta_key, '0.0.0.1', false);
                }else update_option($meta_key.$post_id, '0.0.0.1',false);
                
            }else {

                $new_tmp_ver = $this->version_increaser($has_tmp_ver);

                if($mode == 1){
                    update_post_meta($post_id, $meta_key, $new_tmp_ver);
                }else update_option($meta_key.$post_id, $new_tmp_ver, false);
                

            }
            
        }

        //Settings-Manager
        public function set_single_opt($opt_main, $opt_name, $opt_val, $opt_type='checkbox')
        {

            $opt = get_option($opt_main);
            if(!is_array($opt)){
                $opt = unserialize($opt);
            }

            $indexer = 0;
            $this_found_times = 0;
            foreach($opt as $op){

                if($op[0] == $opt_name){//Found
                    $opt[$indexer][1] = $opt_val;
                    $this_found_times++;
                }

                $indexer++;
            }

            if($this_found_times == 0){//Not-found
                $opt[] = [$opt_name, $opt_val, $opt_type];
            }

            if(is_array($opt)){
                $opt = serialize($opt);
            }

            update_option($opt_main, $opt, false);

        }

        //Post_types
        public function get_active_post_types($all_settings, $mode=1): array
        {
            /**
             * mode-1 => returns active custom cpts
             * mode-2 => returns active template-type cpts 
            */
            
            if($mode == 1){
                $key = 'taily-cpts-';
            }else $key = 'taily-template-cpts-';

            $tmp_arr = [];
            $all_post_types = get_post_types([
                'public' => true,
            ], 'objects');

            foreach($all_post_types as $cpt){

                $cpt_name = $cpt->name;


                if(strtolower($cpt_name) == 'attachment'){
                    continue;
                }

                $data_name = $key.strtolower($cpt_name);
                //$tmp_set = taily_get_all_settings('taily_settings', $data_name);
                $tmp_set = taily_get_all_settings('', $data_name, 'arr', $all_settings);

                if($tmp_set == 1){
                    $tmp_arr[] = $cpt_name;
                }

            } 

            return $tmp_arr;

        }
        //Tax_types
        public function get_active_tax_types($all_settings, $mode=1): array
        {
            /**
             * mode-1 => returns active template-type taxes 
            */
            
            $key = 'taily-template-taxs-';

            $tmp_arr = [];
            $all_custom_taxs = get_taxonomies([
                'public' => true,
                'show_ui' => true,
            ], 'objects');

            foreach($all_custom_taxs as $tax){

                $tax_name = $tax->name;


                $data_name = $key.strtolower($tax_name);
                //$tmp_set = taily_get_all_settings('taily_settings', $data_name);
                $tmp_set = taily_get_all_settings('', $data_name, 'arr', $all_settings);

                if($tmp_set == 1){
                    $tmp_arr[] = $tax_name;
                }

            } 

            return $tmp_arr;

        }

        //Miscs
        protected function logger($content, $mode=1, $erase='no')
        {
            $log_file = TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'log.txt';
            if(file_exists($log_file)){

                $olds = file_get_contents($log_file);

                if($erase == 'no'){
                    $news = $olds."\n".$content;
                }else $news = $content;
                
                if($mode == 1){
                    file_put_contents($log_file, $news);
                }else {

                    $log_data = print_r($news, true);

                    file_put_contents($log_file, $log_data, FILE_APPEND);
                }
                
            }
        }

        /*This Special method seperate js-configs & css-configs from the main-settings array*/
        protected function seperate_opt($option)
        {
            if(is_array($option)){

                $inx = 0;
                foreach($option as $opt){

                    if($opt[0] == 'taily-usertailconfs' || $opt[0] == 'taily-usertailconfs2'){
                        unset($option[$inx]);
                    }

                    $inx++;
                }

                return $option;

            }else return $option;

        }


    }
}
//close the PHP tag to reduce the blank spaces ?>