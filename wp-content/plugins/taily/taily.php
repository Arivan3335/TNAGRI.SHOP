<?php
/**
 * @link              https://profiles.wordpress.org/mdesignfa/
 * @since             1.0.0
 * @package           tailybymdz
 * 
 * @wordpress-plugin
 * Plugin Name:      Taily
 * Plugin URI:       https://taily.com
 * Description:      Tailwind-Css Framework for Wordpress => 🎨 (very fast & optimized system)
 * Version:          2.0.06
 * Author:           M_Design
 * License:          GPLv2 or later
 * License URI:      http://www.gnu.org/licenses/gpl-2.0.txt
 * Author URI:       https://landing.mdezign.ir/en/
 * Text Domain:      tailybymdz
 * Domain Path:      /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*Define Consts*/
if (!defined('TAILYBYMDZ_VER')) { define('TAILYBYMDZ_VER', '2.0.06'); }
if (!defined('TAILYBYMDZ_PATH')) { define('TAILYBYMDZ_PATH', trailingslashit(plugin_dir_path(__FILE__))); }
if (!defined('TAILYBYMDZ_URL')) { define('TAILYBYMDZ_URL', trailingslashit(plugin_dir_url(__FILE__))); }
if (!defined('TAILYBYMDZ_PLG')) { define('TAILYBYMDZ_PLG', __FILE__); }//def self plugin
/**/

/*add settings btn*/
if(!function_exists('tailybymdz_addsettingsbtn'))
{
    function tailybymdz_addsettingsbtn( array $links ) 
    {
        $url = get_admin_url() . 'admin.php?page=tailysettings_main';
        $settings_link = '<a href="' . $url . '">' . esc_html__('Settings', 'tailybymdz'). '</a>';
        $links[] = $settings_link;
        return $links;
    }
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'tailybymdz_addsettingsbtn' );
/**/

/*Translations*/
if (!function_exists('tailybymdz_i18n'))
{
    function tailybymdz_i18n() 
    {
        $test = load_plugin_textdomain( 'tailybymdz', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }
}
add_action('init', 'tailybymdz_i18n');
/**/

/*Check Settings*/
if(!function_exists('taily_get_all_settings'))
{
function taily_get_all_settings($opt_name, $key='none', $mode='arr', $nodb=0)
{

    if($nodb == 0){
        $all_settings = get_option($opt_name);
    }else $all_settings = $nodb;
    
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

        if($all_settings){
            foreach($all_settings as $set){
                if($set[0] == $key){
                    return $set[1];
                }
            }
        }

        return 'nop';
    }
    
}
}
$taily_all_settings = taily_get_all_settings('taily_settings');//Single Fetch (Used all-along the plugin `Best Performance`)
$is_taily_disable = taily_get_all_settings('', 'taily-full-dis', 'arr', $taily_all_settings);
/***/

/*run the plugin core*/
include_once 'core'.DIRECTORY_SEPARATOR.'class-core.php';

if($is_taily_disable != 1 || $is_taily_disable == 'nop')
{
    include_once 'core'.DIRECTORY_SEPARATOR.'front-ajax.php';
    include_once 'core'.DIRECTORY_SEPARATOR.'both.php';
}

if(is_admin())
{
    include_once 'core'.DIRECTORY_SEPARATOR.'admin-ajax.php';
    include_once 'core'.DIRECTORY_SEPARATOR.'admin.php';

}else {
    if($is_taily_disable != 1 || $is_taily_disable == 'nop')
    {
        include_once 'core'.DIRECTORY_SEPARATOR.'front.php';
    }
}


//Gutenberg-Block(s)
if($is_taily_disable != 1 || $is_taily_disable == 'nop')
{

    $is_gut_dis = taily_get_all_settings('', 'taily-dec-gut-comp', 'arr', $taily_all_settings);
    if($is_gut_dis != 1 || $is_gut_dis == 'nop')
    {
        require_once TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'blck-1.php'; 
    }
    
}

/* Omit closing PHP tag to avoid "Headers already sent" issues. */