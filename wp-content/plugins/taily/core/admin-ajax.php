<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**/

//Array Sanitizer
if(!function_exists('tailybymdz_sanitize_arrfields')){
    function tailybymdz_sanitize_arrfields($value)
    {
        return is_array($value) ? array_map('sanitize_text_field', $value) : sanitize_text_field($value);
    }
}
/***/

/*Save Settings*/
if (!function_exists('tailybymdz_save_settings'))
{

    function tailybymdz_save_settings()
    {

        if ( ! check_ajax_referer( 'taily-seeop7y-xtmj44k', 'xtmjk', false ) ) 
        {
            wp_send_json_error( 'Taily: Ajax-Error!' );
            wp_die();
        }//For the Security

        if(!current_user_can('install_plugins')){
            wp_send_json_error( 'Taily: Ajax-Error!' );
            wp_die();
        }//For the Security

        //$settings = $_REQUEST['settings'];
        $settings = array_map('tailybymdz_sanitize_arrfields', $_REQUEST['settings']);//Sanitize an 2-layered array
        /* Like: $arr = [
                    ['a', 'b', 'c'],
                    ['d', 'f', 'g'],
                    ['z', 'x', 'w'],
                ];
        */

        /*$new_arr = [];
        foreach($settings as $tmp){
            $tmp_arr = [];
            foreach($tmp as $tm){
                $tm = sanitize_text_field($tm);
                array_push($tmp_arr, $tm);
            }
            array_push($new_arr, $tmp_arr);
        }
        $settings = $new_arr;*/

        //Define Tailwind-framework active version
        $tail_framework_ver = 'ver3';
        foreach($settings as $set){

            $name = $set[0];

            if($name == 'taily-tailwind-ver'){
                if(!empty($set[1])){
                    $tail_framework_ver = $set[1];
                }
            }
        }
        /***/

        
        $old_settings = get_option('taily_settings');

        if(!$old_settings){
            $old_settings = [];
        }

        if(!is_array($old_settings)){
            $old_settings = unserialize($old_settings);
        }

        $scanned_opts = [];
        $need_reload = 0;

        //Tmp file contents
        $tmp_js_confs = '';
        $tmp_css_confs = '';

        $all_plugins = [
            'forms',
            'lclamp',//Line-Clamp
            'typo',//Typography
            'aspect',//Aspect-Ratio
            'conq',//Container-Queries
        ];
        $active_plgs = [];

        if(!is_a($core_class, 'tailbymdz_core')){
            $core_class = new tailbymdz_core();
        }

        $all_post_types = get_post_types([
            'public' => true,
        ], 'objects');
        $active_temptype_cpts = [];

        $all_custom_taxs = get_taxonomies([
            'public' => true,
            'show_ui' => true,
        ], 'objects');


        foreach($settings as $set){


            $name = $set[0];

            if($name == 'taily-usertailconfs'){
                $tmp_js_confs = $set[1];
            }

            switch($tail_framework_ver){
                default:
                case 'ver3':
                    if($name == 'taily-usertailconfs2'){
                        $tmp_css_confs = $set[1];
                    }
                    break;
                case 'ver4':
                    if($name == 'taily-usertailconfs3'){
                        $tmp_css_confs = $set[1];
                    }
                    break;
            }
            

            //Manage-Plugins
            foreach($all_plugins as $plg){
                $data_name = 'taily-plgs-'.$plg;
                if($name == $data_name && $set[1] == 1){
                    $active_plgs[] = $plg;
                }
            }
            /***/

            //Manage-PostTypes
            foreach($all_post_types as $ptype){
                $cpt_name = $ptype->name;
                $tmp_dataname = 'taily-template-cpts-'.$cpt_name;
                if($name == $tmp_dataname && $set[1] == 1){
                    $active_temptype_cpts[] = $cpt_name;
                }

            }
            /***/

            $name = sanitize_text_field($name);

            $shoudl_scan_this = 1;

            foreach($scanned_opts as $ckh){

                if(trim($ckh) == trim($name)) {
                    $shoudl_scan_this = 0;
                }
            }

            

            if($shoudl_scan_this == 1){
                
                $value = $set[1];
                $value = str_replace("\n", ' \n ', $value);
                $value = sanitize_text_field($value);

                $type = $set[2];
                $type = sanitize_text_field($type);

                $tmp_arr = [
                    $name,
                    $value,
                    $type,
                ];

                $found_old_opt = 0;
                $indxer = 0;

                if(count($old_settings) > 0){

                    foreach($old_settings as $chk2){

                        if(trim($chk2[0]) == trim($name)) {
    
                            $old_settings[$indxer][1] = $value;
                            $old_settings[$indxer][2] = $type;

                            $found_old_opt = 1;
                        }

                        $indxer++;
                    }

                    if($found_old_opt == 0){
                        array_push($old_settings, $tmp_arr);
                    }

                }else array_push($old_settings, $tmp_arr);
                
                
                array_push($scanned_opts, $name);

            }

        }

        if(is_array($old_settings)){
            $old_settings = serialize($old_settings);
        }

        $res = update_option('taily_settings', $old_settings, false);


        $out_arr = [$res, $need_reload];
        //echo json_encode($out_arr);
        echo wp_json_encode($out_arr);

        /*Do file Changes*/
        $js_confs_file_path = TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'dyna-front-confs.js';

        $css_confs_file_path = TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'dyna-front-confs.css';

        
        if(!is_a($core_class, 'tailbymdz_core')){
            $core_class = new tailbymdz_core();
        }

        $taily_tmp_ver = get_option('taily_tmpversion');
        if(!$taily_tmp_ver){
            update_option('taily_tmpversion', '0.0.0.1', false);
        }else {
            $new_ver = $core_class->version_increaser($taily_tmp_ver);
            update_option('taily_tmpversion', $new_ver, false);
        }

        if(file_exists($js_confs_file_path)){
            file_put_contents($js_confs_file_path, '');
            $tmp_js_confs = str_replace("\\", '', $tmp_js_confs);
            $tmp_js_confs = apply_filters('taily_settings_beforeprint_jsconfs', $tmp_js_confs);
            
            if(trim($tmp_js_confs) == 'nop'){$tmp_js_confs = '';}
            file_put_contents($js_confs_file_path, $tmp_js_confs);
        }

        if(file_exists($css_confs_file_path)){
            file_put_contents($css_confs_file_path, '');
            $tmp_css_confs = str_replace("\\", '', $tmp_css_confs);
            $tmp_css_confs = apply_filters('taily_settings_beforeprint_cssconfs', $tmp_css_confs);

            if(trim($tmp_js_confs) == 'nop'){$tmp_css_confs = '';}
            file_put_contents($css_confs_file_path, $tmp_css_confs);
        }
        /***/

        /*produce tail-cdn url (plugins)*/
        $http = 'http';
        if(is_ssl()){$http = 'https';}
        $default_cdn_url = $http.'://cdn.tailwindcss.com';

        /*$use_local_js = taily_get_all_settings('taily_settings', 'taily-donuse-cdn');
        if($use_local_js == 1 || $use_local_js == 'yes' || $use_local_js == 'on'){
            $default_cdn_url = TAILYBYMDZ_URL.'assets/js/tail-js-locally.js';
        }*///Not Sure!
        
        
        if(count($active_plgs) > 0){

            $seperator_sign = ',';
            if(count($active_plgs) == 1){$seperator_sign = '';}

            $default_cdn_url .= '?plugins=';

            foreach($active_plgs as $plg){
                switch($plg){
                    case 'forms':
                        $default_cdn_url .= 'forms'.$seperator_sign;
                        break;
                    case 'lclamp':
                        $default_cdn_url .= 'line-clamp'.$seperator_sign;
                        break;
                    case 'typo':
                        $default_cdn_url .= 'typography'.$seperator_sign;
                        break;
                    case 'aspect':
                        $default_cdn_url .= 'aspect-ratio'.$seperator_sign;
                        break;
                    case 'conq':
                        $default_cdn_url .= 'container-queries'.$seperator_sign;
                        break;
                }
            }

            //remove the last-char if is `,`
            $sub_str = str_split($default_cdn_url);
            $last_char = count($sub_str)-1;

            if($sub_str[$last_char]  == ','){
                unset($sub_str[$last_char]);
            }

            $default_cdn_url = implode('', $sub_str);
            
            update_option('taily_cdn_url', $default_cdn_url, false);

        }else update_option('taily_cdn_url', '', false);
        /***/

        //Set default-values for template-type cpts when they are not in productive-mode
        if(count($active_temptype_cpts) > 0){
            foreach($settings as $set){

                foreach($active_temptype_cpts as $tmp){

                    $data_name = 'taily-tempcpts-pmode-'.$tmp;
                    if($set[0] == $data_name && $set[1] == 0){

                        $tmp_opt = 'taily-temptype-pcss-gen-'.$tmp;
                        update_option($tmp_opt, 'no', false);

                        $check_css_contents = get_option('taily-temptype-css-'.$tmp);
                        if(!$check_css_contents || trim($check_css_contents) == ''){
                            update_option('taily-temptype-haspcss-'.$tmp, 'no', false);
                        }
                    }

                }
            }
            
        }

        if(count($all_custom_taxs) > 0){//for the custom-tax-types
            
            foreach($settings as $set){

                foreach($all_custom_taxs as $tax){

                    $tax_name = $tax->name;

                    $data_name = 'taily-temptaxs-pmode-'.$tax_name;
                    if($set[0] == $data_name && $set[1] == 0){

                        $tmp_opt = 'taily-temptype-pcss-gen-'.$tax_name;
                        update_option($tmp_opt, 'no', false);

                        $check_css_contents = get_option('taily-temptype-css-'.$tax_name);
                        if(!$check_css_contents || trim($check_css_contents) == ''){
                            update_option('taily-temptype-haspcss-'.$tax_name, 'no', false);
                        }
                    }

                }
            }
        }
        /***/

        wp_die();
        
    }
}
add_action('wp_ajax_tailybymdz_save_settings', 'tailybymdz_save_settings' );
/**/

/*Set-allto-Production*/
if (!function_exists('tailybymdz_setallto_prod'))
{

    function tailybymdz_setallto_prod()
    {

        if ( ! check_ajax_referer( 'taily-seeop7y-xtmj44k', 'xtmjk', false ) ) 
        {
            wp_send_json_error( 'Taily: Ajax-Error!' );
            wp_die();
        }//For the Security

        if(!current_user_can('install_plugins')){
            wp_send_json_error( 'Taily: Ajax-Error!' );
            wp_die();
        }//For the Security

        //Mode-2 => sets all to non-productive-mode
        $mode = sanitize_text_field($_POST['mode']);

        $all_settings = wp_unslash(sanitize_text_field($_POST['uiop']));
        $all_settings = maybe_unserialize($all_settings);
        $all_settings = array_map('tailybymdz_sanitize_arrfields', $all_settings);

        $what_do = 'on';

        if($mode == 2){
            $what_do = '';
        }

        global $wpdb, $table_prefix;
        //$post_meta_tb = $table_prefix.'postmeta';

        
        $sql = $wpdb->prepare(

            "SELECT * FROM {$wpdb->postmeta} WHERE `meta_key` = 'tailybymdz_thisp_mode'",
        );
        $result = $wpdb->get_results($sql);

        foreach($result as $res){

            $meta_id = $res->meta_id;
            $p_id = $res->post_id;

            $sql2 = $wpdb->prepare(

                "UPDATE {$wpdb->postmeta} SET `meta_value` = '%s' WHERE {$wpdb->postmeta}.`meta_id` = %d",
                array($what_do, $meta_id),
            );
            $wpdb->get_var($sql2);

            
            update_post_meta($p_id, 'taily_productivecss_gen', 'no');
            
            
        }

        

        if(!is_a($core_class, 'tailbymdz_core')){
            $core_class = new tailbymdz_core;
        }

        //Temptype-cpts
        $all_active_temptypes = $core_class->get_active_post_types($all_settings, 2);
        foreach($all_active_temptypes as $cpt){

            switch($mode){
                case '1':
                case 1:
                    $core_class->set_single_opt('taily_settings', 'taily-tempcpts-pmode-'.$cpt, 1);
                    break;
                case '2':
                case 2:
                    $core_class->set_single_opt('taily_settings', 'taily-tempcpts-pmode-'.$cpt, '');
                    break;
            }

            update_option('taily-temptype-pcss-'.$cpt, 'no', false);
        }
        /***/

        //Temptype-taxes
        $all_active_temptypes2 = $core_class->get_active_tax_types($all_settings, 2);
        foreach($all_active_temptypes2 as $tax){

            switch($mode){
                case '1':
                case 1:
                    $core_class->set_single_opt('taily_settings', 'taily-temptaxs-pmode-'.$tax, 1);
                    break;
                case '2':
                case 2:
                    $core_class->set_single_opt('taily_settings', 'taily-temptaxs-pmode-'.$tax, '');
                    break;
            }

            update_option('taily-temptype-pcss-'.$tax, 'no', false);
        }
        /***/



        wp_die();
        
    }
}
add_action('wp_ajax_tailybymdz_setallto_prod', 'tailybymdz_setallto_prod' );
/***/

/*Set single to non-productive*/
if (!function_exists('tailybymdz_setsingle_nonprod'))
{

    function tailybymdz_setsingle_nonprod()
    {

        if ( ! check_ajax_referer( 'taily-soypu9q-bkh00yn', 'bkh76yn', false ) ) 
        {
            wp_send_json_error( 'Taily: Ajax-Error!' );
            wp_die();
        }//For the Security

        if(!current_user_can('edit_posts')){
            wp_send_json_error( 'Taily: Ajax-Error!' );
            wp_die();
        }//For the Security

        $post_id = sanitize_text_field($_POST['post_id']);

        $temptype_cpts = [];

        $has_meta = get_post_meta($post_id, 'tailybymdz_thisp_mode', true);
        if($has_meta){
            update_post_meta($post_id, 'tailybymdz_thisp_mode', '');
            update_post_meta($post_id, 'taily_productivecss_gen', 'no');
        }
        wp_die();
        
    }
}
add_action('wp_ajax_tailybymdz_setsingle_nonprod', 'tailybymdz_setsingle_nonprod' );
/***/

/*Clear-All CSS-files*/
if (!function_exists('tailybymdz_clearall_cssfiles'))
{

    function tailybymdz_clearall_cssfiles()
    {
        if ( ! check_ajax_referer( 'taily-seeop7y-xtmj44k', 'xtmjk', false ) ) 
        {
            wp_send_json_error( 'Taily: Ajax-Error!' );
            wp_die();
        }//For the Security

        if(!current_user_can('install_plugins')){
            wp_send_json_error( 'Taily: Ajax-Error!' );
            wp_die();
        }//For the Security

        $folder_path = trailingslashit( wp_upload_dir()['basedir'] ) . 'taily';

        $files = glob($folder_path . '/*');

        // Delete all the files of the list
        foreach ($files as $file) {
            if (is_file($file)) {
                // Deleting the given file
                unlink($file);
            }
        }
        wp_die();
        
    }
}
add_action('wp_ajax_tailybymdz_clearall_cssfiles', 'tailybymdz_clearall_cssfiles' );
/***/
//close the PHP tag to reduce the blank spaces ?>