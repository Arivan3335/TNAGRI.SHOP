<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!defined('WP_UNINSTALL_PLUGIN')) exit;

/*Check Settings*/
function taily_unin_get_all_settings($opt_name, $key='none', $mode='arr')
{

    $all_settings = get_option($opt_name);
    if(!$all_settings){
        return 'nop';
    }

    if($mode == 'arr'){

        if(!is_array($all_settings)){
            $all_settings = unserialize($all_settings);
        }

    }

    if($key == 'none'){
        return $all_settings;
    }else {
        if(!is_array($all_settings)){
            $all_settings = unserialize($all_settings);
        }

        foreach($all_settings as $set){
            if($set[0] == $key){
                return $set[1];
            }
        }

        return 'nop';
    }
    
}
/***/

$is_db_cleaner = taily_unin_get_all_settings('taily_settings', 'taily-db-clear');

if($is_db_cleaner == 1)
{
    //Clear wp_options table
    /*foreach ( wp_load_alloptions() as $option => $value ) {
        if ( strpos( $option, 'taily' ) === 0 ) {
            delete_option( $option );
        }
    }//Deprecated since 2.0.00
    /***/

    //Clear wp_options table
    global $wpdb, $table_prefix;
    $table_opts = $table_prefix.'options';
    $sql_select = "SELECT * FROM `$table_opts`";


    $sql_res = $wpdb->get_results($sql_select);


    
    foreach ( $sql_res as $opt ) {

        $opt_name = $opt->option_name;

        if ( strpos( $opt_name, 'taily' ) !== false ) {
            delete_option( $opt_name );

        }
    }
    /***/

    //Post-Metas
    delete_post_meta_by_key('taily_productivecss_gen');
    delete_post_meta_by_key('tailybymdz_thisp_mode');
    delete_post_meta_by_key('taily_has_productivecss');
    delete_post_meta_by_key('taily_productivecss');
    delete_post_meta_by_key('taily_cssfile_url');
    delete_post_meta_by_key('taily_thisp_tmpver');
    /***/

    //Remove all-static-css files
    $folder_path = trailingslashit( wp_upload_dir()['basedir'] ) . 'taily';

    $files = glob($folder_path . '/*');

    // Delete all the files of the list
    foreach ($files as $file) {
        if (is_file($file)) {
            // Deleting the given file
            unlink($file);
        }
    }
    rmdir($folder_path);
    /***/

}
