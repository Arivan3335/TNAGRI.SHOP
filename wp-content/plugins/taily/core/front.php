<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('tailbymdz_front'))
{

    class tailbymdz_front extends tailbymdz_core 
    {

        //Props
        private static $instance;
        private $tail_cdn_js;

        private $tail_framework_ver;

        private $taily_all_settings;

        private $taily_plugins_arr;//Array

        function __Construct($taily_all_settings=[])
        {

            $this->taily_all_settings = $taily_all_settings;
            $this->tail_framework_ver = taily_get_all_settings('', 'taily-tailwind-ver', 'arr', $taily_all_settings);

            //Add tail-css Productive-CSS
            add_action('wp_head', [$this, 'add_prod_css'], 9999);
            /***/

            $this->taily_plugins_arr = 
            [
                'forms',
                'lclamp',//Line-Clamp
                'typo',//Typography
                'aspect',//Aspect-Ratio
                'conq',//Container-Queries
            ];

            if(is_ssl()){

                switch($this->tail_framework_ver){
                    default:
                    case 'ver3':
                        $this->tail_cdn_js = 'https://cdn.tailwindcss.com';
                        break;
                    case 'ver4':
                        $this->tail_cdn_js = 'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4';
                        break;
                }
                
            }else {
                switch($this->tail_framework_ver){
                    default:
                    case 'ver3':
                        $this->tail_cdn_js = 'http://cdn.tailwindcss.com';
                        break;
                    case 'ver4':
                        $this->tail_cdn_js = 'http://cdn.jsdelivr.net/npm/@tailwindcss/browser@4';
                        break;
                }
            }

            //$use_local_js = taily_get_all_settings('taily_settings', 'taily-donuse-cdn');
            $use_local_js = taily_get_all_settings('', 'taily-donuse-cdn', 'arr', $taily_all_settings);
            if($use_local_js == 1 || $use_local_js == 'yes' || $use_local_js == 'on'){

                //$this->tail_cdn_js = TAILYBYMDZ_URL.'assets/js/tail-js-locally.js';//Deprecated
                switch($this->tail_framework_ver){
                    default:
                    case 'ver3':
                        $this->tail_cdn_js = TAILYBYMDZ_URL.'assets/js/tail-js-locally-v3.js';
                        break;
                    case 'ver4':
                        $this->tail_cdn_js = TAILYBYMDZ_URL.'assets/js/tail-js-locally.js';
                        break;
                }
            }

            $cdn_url_opt = get_option('taily_cdn_url');
            if(trim($cdn_url_opt) != ''){
                $this->tail_cdn_js = $cdn_url_opt;
            }
            


            //Add front Assets
            add_action('wp_enqueue_scripts', [$this, 'add_assets']);
            
        
        }

        //Methods
        public static function get_instance($taily_all_settings) 
        {
            if ( ! isset( self::$instance ) ) {
                self::$instance = new self($taily_all_settings);
            }
            return self::$instance;
        }

        private function manage_post_types(): bool
        {

            $taily_all_settings = $this->taily_all_settings;//get settings
            
            if(!is_single() && !is_page()){
                return true;
            }


            global $post;
            $is_post = is_single();
            $is_page = is_page();
            $post_id = $this->get_currpage_id($post, $is_post, $is_page);
            

            /*if(isset($post->ID)){
                $post_id = $post->ID;
            }*/

            

            if($post_id != 'none'){

                //$use_custom_cpts = taily_get_all_settings('taily_settings', 'taily-use-cpts');
                $use_custom_cpts = taily_get_all_settings('', 'taily-use-cpts', 'arr', $taily_all_settings);

                //$this_page_posttype = 'none';
                $this_page_posttype = get_post_type($post_id);
                if($use_custom_cpts == 1){
                    $data_name = 'taily-cpts-'.$this_page_posttype;

                    //$is_act_data = taily_get_all_settings('taily_settings', $data_name);
                    $is_act_data = taily_get_all_settings('', $data_name, 'arr', $taily_all_settings);
                    if($is_act_data != 1){
                        return false;
                    }
                }else {

                    if($this_page_posttype == 'page' || $this_page_posttype == 'post'){
                        return true;
                    }else return false;
                }

                /*if($this_page_posttype != 'none'){

                    
                }*/
            }

            return true;

        }

        private function add_cdn_assets($dyna_ver, $jsfile_url, $has_jsconfs, $has_cssconfs, $taily_all_settings=''): void
        {
            wp_enqueue_script('taily-cdn', $jsfile_url, [], $dyna_ver, false);

            if($has_jsconfs != ''){
                wp_enqueue_script('taily-cdn-confs', TAILYBYMDZ_URL.'assets/js/dyna-front-confs.js', [], $dyna_ver, false);
            }

            /*if($has_cssconfs != ''){
                wp_enqueue_style('taily-cdn-confs', TAILYBYMDZ_URL.'assets/css/dyna-front-confs.css', [], $dyna_ver);
            }*/
            ?>
            <style type="text/tailwindcss" id="taily-cdn-confs-css">
                <?php
                $tmp_file = TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'dyna-front-confs.css';
                echo esc_html(file_get_contents($tmp_file)); 
                ?>
            </style>
            <?php

            if($this->tail_framework_ver == 'ver4' && !empty($taily_all_settings)):
            ?>
            <style type="text/css" id="taily-user-globalcss-vars">
            <?php echo taily_get_all_settings('', 'taily-usertailconfs4', 'arr', $taily_all_settings); ?>
            </style>
            <?php
            endif;

        }
        public function add_assets()
        {

            $taily_all_settings = $this->taily_all_settings;//get settings

            $temp_post_type = 'simple';

            $do_the_rest = $this->manage_post_types();
            if(!$do_the_rest){return;}

            //Productive mode 
            $jsfile_url = $this->tail_cdn_js;

            //$has_jsconfs = trim(taily_get_all_settings('taily_settings', 'taily-usertailconfs'));
            $has_jsconfs = taily_get_all_settings('', 'taily-usertailconfs', 'arr', $taily_all_settings);

            //$has_cssconfs = trim(taily_get_all_settings('taily_settings', 'taily-usertailconfs2'));
            $has_cssconfs = taily_get_all_settings('', 'taily-usertailconfs2', 'arr', $taily_all_settings);

            global $post;
            $is_post = is_single();
            $is_page = is_page();
            $pid = $this->get_currpage_id($post, $is_post, $is_page);
            //if(!isset($pid)) return;//Deprecated from 1.0.15

            if(!isset($pid) || $pid == '' || $pid == null){
                $this->add_cdn_assets($dyna_ver, $jsfile_url, $has_jsconfs, $has_cssconfs, $this->taily_all_settings);
                return;
            }

            //Get temp-post-types
            $all_temp_post_types = $this->get_active_post_types($taily_all_settings, 2);
            $all_temp_tax_types = $this->get_active_tax_types($taily_all_settings, 1);

            $this_is_post = false;
            $this_is_tax = false;
            if($is_post || $is_page){
                $this_is_post = true;
                $this_post_type = $post->post_type;
            }else if(is_tax()){
                $this_is_tax = true;
                $this_post_type = get_term( $pid )->taxonomy;//if is tax
            }

            if($this_is_post){

                foreach($all_temp_post_types as $tmp){
                    if($this_post_type == $tmp){
                        $temp_post_type = 'template';
                    }
                }

            }else if($this_is_tax){

                foreach($all_temp_tax_types as $tmp){
                    if($this_post_type == $tmp){
                        $temp_post_type = 'template';
                    }
                }
            }
            

            if($temp_post_type == 'simple'){

                $productive_mode = get_post_meta($pid, 'tailybymdz_thisp_mode', true);
                $has_productive_css = get_post_meta($pid, 'taily_has_productivecss', true);
                $productive_css_gen = get_post_meta($pid, 'taily_productivecss_gen', true);

            }else if ($temp_post_type == 'template'){

                if($this_is_post){

                    //$productive_mode = taily_get_all_settings('taily_settings', 'taily-tempcpts-pmode-'.$this_post_type);

                    $productive_mode = taily_get_all_settings('', 'taily-tempcpts-pmode-'.$this_post_type, 'arr', $taily_all_settings);
                }else if($this_is_tax){
                    //$productive_mode = taily_get_all_settings('taily_settings', 'taily-temptaxs-pmode-'.$this_post_type);

                    $productive_mode = taily_get_all_settings('', 'taily-temptaxs-pmode-'.$this_post_type, 'arr', $taily_all_settings);
                }
                
                $has_productive_css = get_option('taily-temptype-haspcss-'.$this_post_type, true);
                $productive_css_gen = get_option('taily-temptype-pcss-gen-'.$this_post_type, true);
            }
        

            $tmp_ver = get_option('taily_tmpversion');
            if($tmp_ver){
                $dyna_ver = TAILYBYMDZ_VER.'.'.$tmp_ver;
            }else $dyna_ver = TAILYBYMDZ_VER;
             
            if(
                trim($productive_mode) == 'on' || 
                trim($productive_mode) == 1 //For template type posts
            )
            {

                if(trim($productive_css_gen) == '' || $productive_css_gen == 'no'){

                    wp_enqueue_script('taily-front', TAILYBYMDZ_URL.'assets/js/front-checker.js', array('jquery'), $dyna_ver, true);

                    wp_localize_script(
                        'taily-front',
                        'taily_frnt_ajx_obj',
                        [
                            'mnopg'  => wp_create_nonce( 'taily-unxmlhtc-mnopg' ),
                        ],
                    );//For Ajax-call Security

                    $this->add_cdn_assets($dyna_ver, $jsfile_url, $has_jsconfs, $has_cssconfs, $this->taily_all_settings);
                    
                }

            }else $this->add_cdn_assets($dyna_ver, $jsfile_url, $has_jsconfs, $has_cssconfs, $this->taily_all_settings);

            //Cache-Compatibility
            //$use_cache_compat = taily_get_all_settings('taily_settings', 'taily-cache-comp');
            $use_cache_compat = taily_get_all_settings('', 'taily-cache-comp', 'arr', $taily_all_settings);
            if($use_cache_compat == 1 || $use_cache_compat == 'yes' || $use_cache_compat == 'on'){

                wp_enqueue_script('taily-front', TAILYBYMDZ_URL.'assets/js/front-checker.js', array('jquery'), $dyna_ver, true);

                wp_localize_script(
                    'taily-front',
                    'taily_frnt_ajx_obj',
                    [
                        'mnopg'  => wp_create_nonce( 'taily-unxmlhtc-mnopg' ),
                    ],
                );//For Ajax-call Security
            }

            
            /***/       
        }

        public function add_prod_css()
        {
            $taily_all_settings = $this->taily_all_settings;//get settings

            $serializes_settings = $this->seperate_opt($taily_all_settings);
            $serializes_settings = maybe_serialize($serializes_settings);

            echo '<script type="text/javascript" id="taily-def-ajaxurl"> var taily_ajaxurl = "' . esc_url(admin_url('admin-ajax.php')) . '";var taily_all_sets=`'.$serializes_settings.'`;</script><!--Added by Taily-->';

            $do_the_rest = $this->manage_post_types();
            if(!$do_the_rest){return;}

            global $post;
            $is_post = is_single();
            $is_page = is_page();
            $pid_res = $this->get_currpage_id($post, $is_post, $is_page, 2);
            $pid_res_arr = explode('_', $pid_res);

            $pid = $pid_res_arr[0];

            $temp_post_type = 'simple';

            //Get temp-post-types
            $all_temp_post_types = $this->get_active_post_types($taily_all_settings, 2);
            $all_temp_tax_types = $this->get_active_tax_types($taily_all_settings, 1);


            $this_is_post = false;
            $this_is_tax = false;
            if($pid_res_arr[1] == 'post'){

                $this_post_type = $post->post_type;//if is post
                $this_is_post = true;

            }else if($pid_res_arr[1] == 'tax'){

                $this_post_type = get_term($pid)->taxonomy;//if is tax
                $this_is_tax = true;

            }else $this_post_type = 'none';

            
            
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

                $productive_mode = get_post_meta($pid, 'tailybymdz_thisp_mode', true);
                $has_productive_css = get_post_meta($pid, 'taily_has_productivecss', true);
                $productive_css_gen = get_post_meta($pid, 'taily_productivecss_gen', true);

            }else if ($temp_post_type == 'template'){

                if($this_is_post){
                    //$productive_mode = taily_get_all_settings('taily_settings', 'taily-tempcpts-pmode-'.$this_post_type);

                    $productive_mode = taily_get_all_settings('', 'taily-tempcpts-pmode-'.$this_post_type, 'arr', $taily_all_settings);
                }else if($this_is_tax){
                    //$productive_mode = taily_get_all_settings('taily_settings', 'taily-temptaxs-pmode-'.$this_post_type);

                    $productive_mode = taily_get_all_settings('', 'taily-temptaxs-pmode-'.$this_post_type, 'arr', $taily_all_settings);
                }
                
                $has_productive_css = get_option('taily-temptype-haspcss-'.$this_post_type, true);
                $productive_css_gen = get_option('taily-temptype-pcss-gen-'.$this_post_type, true);

            }

            echo '<script type="text/javascript" id="taily-def-xkcp">var taily_xkcp="'.esc_html($pid_res).'"; var taily_is_pmode="'.esc_html($productive_mode).'"</script><!--Added by Taily-->';

            if($has_productive_css == 'yes' && ($productive_mode == 'on' || $productive_mode == 1) && $productive_css_gen == 'yes'){

                //$css_mode = taily_get_all_settings('taily_settings', 'taily-print-css-type');
                $css_mode = taily_get_all_settings('', 'taily-print-css-type', 'arr', $taily_all_settings);
                if(!$css_mode || $css_mode == '' || $css_mode == null){
                    $css_mode = 'inline';
                }//Default is inline-mode

                if(trim($css_mode) == 'inline'){

                    if($temp_post_type == 'simple'){
                        $css_codes = get_post_meta($pid, 'taily_productivecss', true);
                    }else if ($temp_post_type == 'template'){
                        $css_codes = get_option('taily-temptype-css-'.$this_post_type);
                    }
                    
                    ?>
                    <style id="taily-page-css">
                    <?php echo esc_html($css_codes); ?>
                    </style>
                    <?php

                }else if(trim($css_mode) == 'ext'){

                    if($temp_post_type == 'simple'){
                        $css_file_url = get_post_meta($pid, 'taily_cssfile_url', true);
                        $has_tmp_ver = get_post_meta($pid, 'taily_thisp_tmpver', true);
                    }else if($temp_post_type == 'template'){
                        $css_file_url = get_option('taily-temptype-cssfile-'.$this_post_type);
                        $has_tmp_ver =  get_option('taily-temptype-tmpver-'.$this_post_type);
                    }
                    
                    $dyna_ver = TAILYBYMDZ_VER;

                    if($has_tmp_ver && trim($has_tmp_ver) != ''){
                        $dyna_ver = $dyna_ver.'.'.$has_tmp_ver;
                    }
                    wp_enqueue_style('taily-thisp-ext', $css_file_url, [], $dyna_ver);
                }
            }
        }

        public function get_currpage_id($post, $is_post, $is_page, $mode=1)
        {

            /**
             * Mode-1 => return the ID
             * Mode-2 => retturns ID+(is-post|is-tax)
             */

            $pid = 'none';
            $current_type = 'post';

            if($is_post || $is_page){
                $pid = $post->ID;
            }else {
                //Woocommerce
                if($this->check_plg('woocommerce.php')){

                    if(is_shop()){

                        $pid = woocommerce_get_page_id('shop');
                        $current_type = 'woo-shop';
                        
                    }else if(is_product_category() || is_tax()){

                        $cate = get_queried_object();
                        $pid = $cate->term_id;

                        $current_type = 'tax';
                    }

                }else if(is_tax()){
                    $cate = get_queried_object();
                    $pid = $cate->term_id;

                    $current_type = 'tax';
                }
                /***/
            }

            if($mode == 1){
                return $pid;
            }
            if($mode == 2){
                return $pid.'_'.$current_type;
            }
            
        }
  

    }
}

//$tailbymdz_front = new tailbymdz_front();
tailbymdz_front::get_instance($taily_all_settings);

//close the PHP tag to reduce the blank spaces ?>