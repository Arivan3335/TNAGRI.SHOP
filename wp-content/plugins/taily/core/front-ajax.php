<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**/

//Functions
if(!function_exists('tailybymdz_sanitize_arrfields')){
    function tailybymdz_sanitize_arrfields($value)
    {
        return is_array($value) ? array_map('sanitize_text_field', $value) : sanitize_text_field($value);
    }
}

if (!function_exists('tailybymdz_meta_adder'))
{
    function tailybymdz_meta_adder($post_id, $meta_key, $meta_val, $mode=1)
    {

        /**
         * Mode-2 => injects into `wp_options` table
         */

        global $wpdb, $table_prefix;
        $post_meta_table = $table_prefix.'postmeta';
        $options_table = $table_prefix.'options';

        if($mode == 1){

            $has_meta = metadata_exists('post', $post_id, $meta_key);

            if(!$has_meta || $has_meta == null){//Add new


                $sql_insert = $wpdb->prepare(
                    "INSERT INTO `$post_meta_table` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES (NULL, %d, %s, %s)",
                    [$post_id, $meta_key, $meta_val],
                );
                $wpdb->get_var($sql_insert);

            }else {//Update-old

                $sql_update = $wpdb->prepare(
                    "UPDATE `$post_meta_table` SET `meta_value` = %s WHERE `$post_meta_table`.`meta_key` = %s AND `$post_meta_table`.`post_id` = %d",
                    [$meta_val, $meta_key, $post_id],
                );
                $wpdb->get_var($sql_update);
            }

        }else if($mode == 2){

            if ( 'not-exists' === get_option( $post_id, 'not-exists' ) ) {//Add new

                
                $sql_insert = $wpdb->prepare(
                    "INSERT INTO `$options_table` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES (NULL, %s, %s, 'yes')",
                    [$post_id, $meta_val],
                );
                $wpdb->get_var($sql_insert);

            }else {//Update-Old

                $sql_update = $wpdb->prepare(
                    "UPDATE `$options_table` SET `option_value` = %s WHERE `digdoog_options`.`option_name` = %s",
                    [$meta_val, $post_id],
                );
                $wpdb->get_var($sql_update);

            }
        }
        
    }
}
/***/

/*Proccess inline-css*/ 
if (!function_exists('tailybymdz_proccess_css'))
{

    function tailybymdz_proccess_css() 
    {

        if ( ! check_ajax_referer( 'taily-unxmlhtc-mnopg', 'mnopg', false ) ) 
        {
            wp_send_json_error( 'Taily: Ajax-Error!' );
            wp_die();
        }//For the Security

        require_once TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'front.php';
        $front_class = new tailbymdz_front;

        $post_id_res = sanitize_text_field($_POST['pid']);//Like=> 17_tax | 55_post
        $post_id_arr = explode('_', $post_id_res);
        $post_id = $post_id_arr[0];
        $post_id_type = $post_id_arr[1];

        $css = wp_unslash(sanitize_text_field($_POST['css']));

        
        $all_settings = wp_unslash(sanitize_text_field($_POST['sttrx']));
        $all_settings = maybe_unserialize($all_settings);
        $all_settings = array_map('tailybymdz_sanitize_arrfields', $all_settings);

        

        

        $temp_post_type = 'simple';

        //Get temp-post-types
        if (is_a($core_class, 'tailbymdz_core')) {

            $all_temp_post_types = $core_class->get_active_post_types($all_settings, 2);
            $all_temp_tax_types = $core_class->get_active_tax_types($all_settings, 1);

        }else {
            $core_class = new tailbymdz_core;
            $all_temp_post_types = $core_class->get_active_post_types($all_settings, 2);
            $all_temp_tax_types = $core_class->get_active_tax_types($all_settings, 1);
        }

        $this_is_post = false;
        $this_is_tax = false;
        if($post_id_type == 'post'){

            $this_is_post = true;
            $this_post_type = get_post_type($post_id);//if is post

        }else if($post_id_type == 'tax'){
            
            $this_is_tax = true;
            $this_post_type = get_term($post_id)->taxonomy;//if is tax
        }

        
        
        if($this_is_post){

            foreach($all_temp_post_types as $tmp){
                if($this_post_type == $tmp){
                    $temp_post_type = 'template';
                }
            }

        }else if($this_is_tax){

            foreach($all_temp_tax_types as $tax){
                if($this_post_type == $tax){
                    $temp_post_type = 'template';
                }
            }
        }

        if($temp_post_type == 'simple'){
            update_post_meta($post_id, 'taily_has_productivecss', 'yes');
            update_post_meta($post_id, 'taily_productivecss_gen', 'yes');

            //inject-post css Directly (due to wordpress security on `update_post_meta`)
            tailybymdz_meta_adder($post_id, 'taily_productivecss', $css);
            //update_post_meta($post_id, 'taily_productivecss', $css);//Deprecated
            /***/
            
        }else if($temp_post_type == 'template'){

            update_option('taily-temptype-haspcss-'.$this_post_type, 'yes', false);
            update_option('taily-temptype-pcss-gen-'.$this_post_type, 'yes', false);

            //inject-option Directly (due to wordpress security on `update_option`)
            tailybymdz_meta_adder('taily-temptype-css-'.$this_post_type, '', $css, 2);
            //update_option('taily-temptype-css-'.$this_post_type, $css);//Deprecated
            /***/
            
        }
        

        //Proccess External CSS-file
        //$is_add_externalcss = taily_get_all_settings('taily_settings', 'taily-print-css-type');
        $is_add_externalcss = taily_get_all_settings('', 'taily-print-css-type', 'arr', $all_settings);
        if(trim($is_add_externalcss) == 'ext'){

            $uploads_url = home_url().'/wp-content/uploads/taily/';

            if($temp_post_type == 'simple'){

                $has_css_file_generated = get_post_meta($post_id, 'taily_cssfile_url', true);
                $css_file_name = 'taily-'.$post_id.'.css';

            }else if($temp_post_type == 'template'){

                $has_css_file_generated = get_option('taily-temptype-cssfile-'.$this_post_type);
                $css_file_name = 'taily-general-'.$this_post_type.'.css';

            }
        
            $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'taily';
            $new_file_dir = $uploads_dir . '/'.$css_file_name;

            if(!$has_css_file_generated || trim($has_css_file_generated) == '' || !file_exists($new_file_dir)){

                if(!file_exists($new_file_dir)){

                    $new_file = fopen($new_file_dir, 'w');

                    if($new_file){
                        $new_file_url = $uploads_url.$css_file_name;

                        if($temp_post_type == 'simple'){

                            update_post_meta($post_id, 'taily_cssfile_url', $new_file_url);

                        }else if($temp_post_type == 'template'){

                            update_option('taily-temptype-cssfile-'.$this_post_type, $new_file_url, false);

                        }

                        

                        file_put_contents($new_file_dir, '');
                        file_put_contents($new_file_dir, $css);

                        if(!is_a($core_class, 'tailbymdz_core')){
                            $core_class = new tailbymdz_core();
                        }

                        if($temp_post_type == 'simple'){
                            $core_class->posts_ver_manager($post_id, 'taily_thisp_tmpver');
                        }else if($temp_post_type == 'template'){
                            $core_class->posts_ver_manager($this_post_type, 'taily-temptype-tmpver-', 2);
                        }
                    }

                }
                
            }

            if(file_exists($new_file_dir)){

                $last_file_url = $uploads_url.$css_file_name;

                if($temp_post_type == 'simple'){

                    update_post_meta($post_id, 'taily_cssfile_url', $last_file_url);

                }else if($temp_post_type == 'template'){

                    update_option('taily-temptype-cssfile-'.$this_post_type, $last_file_url, false);

                }

                file_put_contents($new_file_dir, '');
                file_put_contents($new_file_dir, $css);

                if(!is_a($core_class, 'tailbymdz_core')){
                    $core_class = new tailbymdz_core();
                }

                if($temp_post_type == 'simple'){
                    $core_class->posts_ver_manager($post_id, 'taily_thisp_tmpver');
                }else if($temp_post_type == 'template'){
                    $core_class->posts_ver_manager($this_post_type, 'taily-temptype-tmpver-', 2);
                }
            }

        }
        /***/
       
        wp_die();
    }

}
add_action( 'wp_ajax_tailybymdz_proccess_css', 'tailybymdz_proccess_css' );
add_action( 'wp_ajax_nopriv_tailybymdz_proccess_css', 'tailybymdz_proccess_css' );
/**/
//close the PHP tag to reduce the blank spaces ?>