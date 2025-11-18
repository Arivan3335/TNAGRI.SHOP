<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('tailbymdz_admin')) 
{

    class tailbymdz_admin extends tailbymdz_core 
    {

        //Props
        private static $instance;

        private $dev_mode;
        private $tail_cdn_js;
        private $tail_admin_cdn_js;
        private $tail_css;

        private $tail_framework_ver;

        private $is_plg_deactivated;
        private $act_post_types;//Array

        private $taily_all_settings;

        public function __Construct($taily_all_settings)
        {

            $this->taily_all_settings = $taily_all_settings;

            $this->dev_mode = 0;//Must be 0 in final-productive output

            //Add settings for admin-ajax (JS)
            add_action('admin_head', [$this, 'add_admin_head']);
            /***/

            $this->tail_framework_ver = taily_get_all_settings('', 'taily-tailwind-ver', 'arr', $taily_all_settings);

            
            
            $is_plg_dis = taily_get_all_settings('', 'taily-full-dis', 'arr', $taily_all_settings);
            $this->is_plg_deactivated = $is_plg_dis;

            //Active & Deactive Hooks
            register_activation_hook(TAILYBYMDZ_PLG, array($this, 'onactivate'));
            register_deactivation_hook(TAILYBYMDZ_PLG, array($this, 'ondeactivate'));

            if(is_ssl())
            {

                $this->tail_admin_cdn_js = 'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4';


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

                $this->tail_admin_cdn_js = 'http://cdn.jsdelivr.net/npm/@tailwindcss/browser@4';
                
                switch($this->tail_framework_ver){
                    default:
                    case 'ver3':
                        $this->tail_cdn_js = 'https://cdn.tailwindcss.com';
                        break;
                    case 'ver4':
                        $this->tail_cdn_js = 'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4';
                        break;
                }
            }



            //$use_local_js = taily_get_all_settings('taily_settings', 'taily-donuse-cdn');
            $use_local_js = taily_get_all_settings('', 'taily-donuse-cdn', 'arr', $taily_all_settings);
            if($use_local_js == 1 || $use_local_js == 'yes' || $use_local_js == 'on'){

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

            $this->tail_css = TAILYBYMDZ_URL.'assets/css/admin-tailwind.css';//just used for admin-settings page

            //add menue
            add_action('admin_menu', array($this, 'add_menue'));

            if($is_plg_dis == 1 && $is_plg_dis != 'nop')
            {
                return;
            }

            //Check plugin verison
            $version = get_option('taily_plugin_version'); 
            if(!$version){
                update_option('taily_plugin_version', TAILYBYMDZ_VER, false);
            }

            if(version_compare($version, TAILYBYMDZ_VER, '<') && $version != '') {//Just runs after an update

                update_option('taily_plugin_version', TAILYBYMDZ_VER, false);

                //tmp-ver reversion
                $woosbymdz_tmp_ver = get_option('taily_tmpversion');
                if($woosbymdz_tmp_ver){
                    update_option('taily_tmpversion', '0.0.0.1', false);
                }

                //Reset-css&js files
                $js_file = TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'dyna-front-confs.js';
                $css_file = TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'dyna-front-confs.css';

                //$js_codes = taily_get_all_settings('taily_settings', 'taily-usertailconfs');
                $js_codes = taily_get_all_settings('', 'taily-usertailconfs', 'arr', $taily_all_settings);

                //$css_codes = taily_get_all_settings('taily_settings', 'taily-usertailconfs2');
                switch($this->tail_framework_ver){
                    default:
                    case 'ver3':
                        $css_codes = taily_get_all_settings('', 'taily-usertailconfs2', 'arr', $taily_all_settings);
                        break;
                    case 'ver4':
                        $css_codes = taily_get_all_settings('', 'taily-usertailconfs3', 'arr', $taily_all_settings);
                        break;
                }
                

                file_put_contents($js_file, '');
                file_put_contents($js_file, $js_codes);

                file_put_contents($css_file, '');
                file_put_contents($css_file, $css_codes);

                //TMP for(just version 1.2.00)
                if(TAILYBYMDZ_VER == '1.2.00'){
                    update_option('taily_settings', '', false);
                }
                /***/

            }
            /***/


            

            
            
            //wp_enqueue_script('taily-cdn', $jsfile_url, [], $TAILYBYMDZ_VER, false);

            //Manage Post-types
            $do_the_rest = $this->manage_post_types();
            /***/


            //Add Admin-Gutenberg (Wordpress-BlockEditor tailwind!~)
            $is_dis_guten = taily_get_all_settings('', 'taily-dec-gut-comp', 'arr', $taily_all_settings);
            if(($is_dis_guten != 1 || $is_dis_guten == 'nop') && $do_the_rest){

                add_action('current_screen', function(){

                    $current_screen = get_current_screen();
    
                    if($current_screen->is_block_editor == 1){
    

                        //Tailwindcss CDN
                        /*if(is_ssl()){
                            $this->tail_cdn_js = 'https://cdn.tailwindcss.com';
                        }else $this->tail_cdn_js = 'http://cdn.tailwindcss.com';*/
    
                        wp_enqueue_script('taily-cdn', $this->tail_cdn_js, [], TAILYBYMDZ_VER, false);

                        switch($this->tail_framework_ver){
                            default:
                            case 'ver3':
                                $has_jsconfs = trim(taily_get_all_settings('taily_settings', 'taily-usertailconfs'));
                                if(!empty($has_jsconfs)){
                                    wp_enqueue_script('taily-cdn-confs', TAILYBYMDZ_URL.'assets/js/dyna-front-confs.js', [], TAILYBYMDZ_VER, false);
                                }

                                $has_cssconfs = trim(taily_get_all_settings('taily_settings', 'taily-usertailconfs2'));
                                if(!empty($has_cssconfs)){

                                    add_action('admin_head', function()
                                    {
                                        ?>
                                        <style type="text/tailwindcss" id="taily-cdn-confs-css">
                                            <?php
                                            $tmp_file = TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'dyna-front-confs.css';
                                            echo esc_html(file_get_contents($tmp_file)); 
                                            ?>
                                        </style>
                                        <?php
                                    });
                                
                                }

                                break;
                            case 'ver4':
                                $has_inp_css = trim(taily_get_all_settings('taily_settings', 'taily-usertailconfs3'));
                                if(!empty($has_inp_css)){

                                    add_action('admin_head', function()
                                    {
                                        ?>
                                        <style type="text/tailwindcss" id="taily-cdn-confs-css">
                                            <?php
                                            $tmp_file = TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'dyna-front-confs.css';

                                            $input_css = file_get_contents($tmp_file);
                                            if(strpos($input_css, '@import "tailwindcss";') !== false){
                                                $input_css = str_replace('@import "tailwindcss";', '', $input_css);
                                            }

                                            echo esc_html(file_get_contents($input_css)); 
                                            ?>
                                        </style>
                                        <?php

                                    });
                                }

                                $has_vars_css = trim(taily_get_all_settings('taily_settings', 'taily-usertailconfs4'));
                                if(!empty($has_vars_css)){

                                    add_action('admin_head', function()
                                    {
                                        ?>
                                        <style type="text/tailwindcss" id="taily-global-vars-css">
                                            <?php
                                            echo esc_html($has_vars_css); 
                                            ?>
                                        </style>
                                        <?php

                                    });
                                }
                                break;
                        }


                        add_action('admin_head', function(){

                            switch($this->tail_framework_ver){
                                case 'ver3':
                                    $opt_1 = 'taily-usertailconfs';
                                    $opt_2 = 'taily-usertailconfs2';
                                    break;
                                case 'ver4':
                                    $opt_1 = 'taily-usertailconfs3';
                                    $opt_2 = 'taily-usertailconfs4';
                                    break;
                            }

                            ?>
                            <script type="text/javascript" id="taily-guten-settings">
                                var taily_guten_cdn_url = '<?php echo esc_html($this->tail_cdn_js); ?>';
                                var taily_guten_tail_ver = '<?php echo esc_html($this->tail_framework_ver); ?>';
                                var taily_user_js_confs = '<?php echo taily_get_all_settings('taily_settings', $opt_1); ?>'
                                var taily_user_css_confs = '<?php echo taily_get_all_settings('taily_settings', $opt_2); ?>'
                            </script>
                            <?php
                        });

                        wp_enqueue_script('taily-cdn-guten-adder', TAILYBYMDZ_URL.'assets/js/add-guten-iframe.js', ['jquery'], TAILYBYMDZ_VER, true);
                        
    
                    }
    
                    
                });
            }
            /***/
            


            //Auto-goon-non-productive mode (when hit edit button)
            //$is_autogoon_active = taily_get_all_settings('taily_settings', 'taily-gonon-onedit');
            $is_autogoon_active = taily_get_all_settings('', 'taily-gonon-onedit', 'arr', $taily_all_settings);
            if($is_autogoon_active == 1){

                if($do_the_rest){

                    add_action('admin_head', function()
                    {
                        ?>
                        <style>.taily-inload {pointer-events: none !important;opacity: .5;}</style>
                        <script>var taily_ajx_url = "<?php echo esc_url(admin_url( 'admin-ajax.php')); ?>";</script>
                        <?php

                    });

                    add_action('admin_enqueue_scripts', [$this, 'add_autogon_assts']);
                }
            }
            /***/

            //Set active post-types
            //$use_cpts = taily_get_all_settings('taily_settings', 'taily-use-cpts');
            $use_cpts = taily_get_all_settings('', 'taily-use-cpts', 'arr', $taily_all_settings);
            if($use_cpts != 1){
                $this->act_post_types[] = 'page';
                $this->act_post_types[] = 'post';
            }else {

                add_action('wp_loaded', function()
                {
                    $taily_all_settings = $this->taily_all_settings;//get settings
                    $this->act_post_types = $this->get_active_post_types($taily_all_settings, 1);
                });
                
            }
            
            //Add custom meta-boxes
            add_action('add_meta_boxes', [$this, 'all_posts_metabox']);
            if($do_the_rest){
                add_action('save_post', [$this, 'meta_box_save'], 1, 2);
            }
            /***/
        }

        //Methods
        public static function get_instance($taily_all_settings) 
        {
            if ( ! isset( self::$instance ) ) {
                self::$instance = new self($taily_all_settings);
            }
            return self::$instance;
        }
        public function onactivate()
        {

            //Create upload-dir (for external css-files)
            $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'taily';
            wp_mkdir_p( $uploads_dir );
        }
        public function ondeactivate()
        {


        }

        //Main-Menu
        public function add_menue()
        {

            $is_plg_dis = $this->is_plg_deactivated;

            $menu_cion = include_once TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'taily-menu-cion.php';

            add_menu_page(
                'Taily Settings', 
                esc_html__('Taily', 'tailybymdz'), 
                'install_plugins', 
                'tailysettings_main', 
                [$this, 'main_menu_func'], 
                "{$menu_cion}",
                '99',
            );

            if($is_plg_dis != 1 || $is_plg_dis == 'nop'){

                add_submenu_page(
                    'tailysettings_main',
                    'Help',
                    esc_html__('Help', 'tailybymdz'),
                    'install_plugins',
                    'tailysettings_help',
                    [$this, 'menu_help'],
                );
    
                add_submenu_page(
                    'tailysettings_main',
                    esc_html__('Free plugins', 'tailybymdz'),
                    esc_html__('Free plugins', 'tailybymdz'),
                    'install_plugins',
                    'plugin-install.php?s=mdesign&tab=search&type=author',
                    ''
                );
    
                add_submenu_page(
                    'tailysettings_main',
                    'exclusive design',
                    esc_html__('Exclusive design', 'tailybymdz'),
                    'install_plugins',
                    'tailysettings_desgin',
                    [$this, 'menu_design'],
                );

            }
            
        }
        private function get_user_color()
        {

            $user_id = get_current_user_id();
            $user_color = get_user_option( 'admin_color', $user_id );

            $admin_color_themes = array
            (

                'modern' => '#3858e9',
                'fresh' => '#2271b1',
                'light' => '#04a4cc',
                'blue' => '#e1a948',
                'coffee' => '#c7a589',
                'ectoplasm' => '#a3b745',
                'midnight' => '#e14d43',
                'ocean' => '#9ebaa0',
                'sunrise' => '#dd823b',
            
                //admin color schemes themes
                'modern-evergreen' => '#0F4232',
                'lawn' => '#A7B145',
                'seashore' => '#456A7F',
                'primary' => '#D8B230',
                'vinyard' => '#CC9E14',
                'kirk' => '#BD3854',
                'flat' => '#1ABC9C',
                'cruise' => '#79B591',
                'contrast-blue' => '#264D79',
                'aubergine' => '#C99416',
                'adderley' => '#1730E5',
                '80s-kid' => '#D13674',
            
            );

            ?>
            <style id="taily-usercolors-confs">
                :root {
                --tailyusercolor: <?php if(isset($admin_color_themes[$user_color])){ echo wp_kses($admin_color_themes[$user_color], array()); }else{echo wp_kses('#192229', array());}?>;
                }
            </style>
            <?php

        }
        private function get_cust_cions()
        {
            $cions = include TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'all-cions.php';

            return $cions;
        }
        public function main_menu_func()
        {
            
            $this->get_user_color();

            include_once TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'settings.php';

        }
        public function menu_help()
        {

            $this->get_user_color();

            include_once TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'help.php';
        }
        public function menu_design()
        {

            $this->get_user_color();

            include_once TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'design.php';
        }

        private function proccess_tail_admin()
        {

            $dev_mode = $this->dev_mode;
            $tail_cdn = $this->tail_admin_cdn_js;
            $tail_css_file = $this->tail_css;

            if($dev_mode  == 1){

                wp_enqueue_script('taily-admin-dev', $tail_cdn , [], TAILYBYMDZ_VER, false);
                //wp_enqueue_script('taily-admin-cdnconfs', TAILYBYMDZ_URL.'assets/js/tail-cdn-confs.js', [], TAILYBYMDZ_VER, false);//Deprecated

                ?>
                <style type="text/tailwindcss">
                    <?php 
                    $tmp_file = TAILYBYMDZ_PATH.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'admin-input.css';

                    $input_css = file_get_contents($tmp_file);
                    if(strpos($input_css, '@import "tailwindcss";') !== false){
                        $input_css = str_replace('@import "tailwindcss";', '', $input_css);
                    }
                    echo esc_html($input_css); 
                    ?>
                </style>
                <?php

            }else wp_enqueue_style('taily-admin-prod', $tail_css_file, [], TAILYBYMDZ_VER);
        }
        private function add_admin_cust_css()
        {
            wp_enqueue_style('taily-admin-custom', TAILYBYMDZ_URL.'assets/css/admin.css', [], TAILYBYMDZ_VER);
        }
        private function add_js_file($file_name, $in_footer=true, $deps=[])
        {
            $file_src = TAILYBYMDZ_URL.'assets/js/'.$file_name.'.js';
            $file_id = 'taily-'.$file_name;

            wp_enqueue_script($file_id, $file_src, $deps, TAILYBYMDZ_VER, $in_footer);

            wp_localize_script(
                $file_id,
                'taily_admn_ajx_obj',
                [
                    'xtmjk'  => wp_create_nonce( 'taily-seeop7y-xtmj44k' ),
                ],
            );//For Ajax-call Security
        }
        private function get_opt($opt_name, $opt_opt='taily_settings', $mode=1, $is_visual=1)
        {

            $sample = get_option('taily_settings');

            if(!$sample){return false;}

            if(!is_array($sample)){
                $sample = unserialize($sample);
            }

            foreach($sample as $opt){
                if($opt[0] == $opt_name){
                    
                    $out_put_type = $opt[2];
                    $out_put = $opt[$mode];

                    if($is_visual == 1){
                        $out_put = str_replace(' \n ', "\n", $out_put);
                        $out_put = str_replace("\\", '', $out_put);
                        $out_put = trim($out_put);
                    }

                    switch($out_put_type){

                        default:
                        case 'text':
                        case 'textarea':
                            return $out_put;
                            break;
                        case 'checkbox':
                            if($is_visual == 1){

                                if($out_put == '1'){

                                    return 'checked';

                                }else return '';

                            }else return $out_put;
                            break;

                    }
                    

                    
                }
            }

        }

        //Post-Meta
        private function manage_post_types(): bool
        {

            $taily_all_settings = $this->taily_all_settings;//get settings

            //$use_custom_cpts = taily_get_all_settings('taily_settings', 'taily-use-cpts');
            $use_custom_cpts = taily_get_all_settings('', 'taily-use-cpts', 'arr', $taily_all_settings);

            $this_page_posttype = 'none';
            if($use_custom_cpts == 1){

                if(isset($_GET['post_type'])){
                    $this_page_posttype = sanitize_text_field($_GET['post_type']);
                }else {

                    $post_id = 'none';
                    if(isset($_GET['post'])){

                        $post_id = sanitize_text_field($_GET['post']);
                    }

                    if($post_id != 'none'){

                        $this_page_posttype = get_post_type($post_id);
                    }
                }

                if($this_page_posttype != 'none'){

                    $data_name = 'taily-cpts-'.$this_page_posttype;

                    //$is_act_data = taily_get_all_settings('taily_settings', $data_name);
                    $is_act_data = taily_get_all_settings('', $data_name, 'arr', $taily_all_settings);
                    if($is_act_data != 1){
                        return false;
                    }
                }
                
            }

            return true;


        }
        public function meta_box_html()
        {

            global $post;

            $pid = $post->ID;

            $productive_mode = get_post_meta($pid, 'tailybymdz_thisp_mode', true);

            if (trim($productive_mode) == 'on') {
                $productive_mode_check = 'checked';
            } else {
                $productive_mode_check = '';
            }

            wp_enqueue_style('taily-admin', TAILYBYMDZ_URL . 'assets/css/admin.css', [], TAILYBYMDZ_VER);
            ?>

		    <fieldset>
			    <div>

                <div class="taily-checkbox-btn-wrapper">
                    <span style="font-size: 16px;"><?php echo esc_html__('Productive Mode?', 'tailybymdz') ?></span>
                    <label class="switch">
                        <input type="checkbox" <?php echo esc_attr($productive_mode_check); ?> name='taily-productive-mode' id='taily-productive-mode'>
                        <span class="slider round"></span>
                    </label>
                </div>
                <p>
                   <?php echo esc_html__('Help: Whenever the design of this page is finished, you can activate this option so that all the CSS codes of this page are added to the page more optimally and do not slow down the rendering speed of the page.', 'tailybymdz') ?>
                </p>

			    </div>
		    </fieldset>

	        <?php
            // Security field
            // This validates that submission came from the
            // actual dashboard and not the front end or
            // a remote server.
            wp_nonce_field('tailymetabox_save_nounce', 'tailymetabox_save_proccess');
        }
        public function all_posts_metabox()
        {

            $taily_all_settings = $this->taily_all_settings;//get settings

            $post_types = $this->act_post_types;
            $temptye_ptypes = $this->get_active_post_types($taily_all_settings, 2);
            

            foreach($post_types as $ptype){

                $add_this = true;

                foreach($temptye_ptypes as $temp_type){
                    if($temp_type == $ptype){
                        $add_this = false;
                    }
                }

                if($add_this){
                    add_meta_box(
                        'taily_posts_opts', 
                        esc_html__('Taily (WP-TailwindCss)', 'tailybymdz'), 
                        [$this, 'meta_box_html'], 
                        $ptype, 
                        'normal', 
                        'default'
                    );
                }
                
            }

        }
        public function meta_box_save($post_id, $post)
        {

            if (!isset($_POST['tailymetabox_save_proccess'])) {
                return;
            }

            // Verify data came from edit/dashboard screen
            /*if (!wp_verify_nonce($_POST['tailymetabox_save_proccess'], 'tailymetabox_save_nounce')) {
                return $post->ID;
            }(More Old-Fashion for WP_Security!)*/

            if ( ! isset( $_POST['tailymetabox_save_proccess'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['tailymetabox_save_proccess'] ) ) , 'tailymetabox_save_nounce' ) ){
                return $post->ID;
            }

            // Verify user has permission to edit post
            if (!current_user_can('edit_post', $post->ID)) {
                return $post->ID;
            }

            $sanitized = wp_filter_post_kses(sanitize_text_field($_POST['taily-productive-mode']));
            if (trim($sanitized) == '') {
                
                update_post_meta($post->ID, 'taily_productivecss_gen', 'no');

                $css_contents = get_post_meta($post->ID, 'taily_productivecss', true);
                if(!$css_contents || trim($css_contents) == ''){
                    update_post_meta($post->ID, 'taily_has_productivecss', 'no');
                }
            }
            // Save our submissions to the database
            update_post_meta($post->ID, 'tailybymdz_thisp_mode', $sanitized);

        }

        //Miscs
        public function add_autogon_assts()
        {

            wp_enqueue_script("taily-admin-autogoon", TAILYBYMDZ_URL."assets/js/auto-goon-nonprp.js", ['jquery'], TAILYBYMDZ_VER, true);

            wp_localize_script(
                'taily-admin-autogoon',
                'taily_admn2_ajx_obj',
                [
                    'bkh76yn'  => wp_create_nonce( 'taily-soypu9q-bkh00yn' ),
                ],
            );//For Ajax-call Security
        }
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
        public function add_admin_head()
        {

            $taily_all_settings = $this->taily_all_settings;//get settings
            
            $serializes_settings = $this->seperate_opt($taily_all_settings);
            $serializes_settings = maybe_serialize($serializes_settings);

            echo '<script type="text/javascript" id="taily-def-settings"> var adm_taily_all_sets=`'.$serializes_settings.'`</script><!--Added by Taily-->';
        }

    }
}

//$tailbymdz_admin = new tailbymdz_admin();

tailbymdz_admin::get_instance($taily_all_settings);

//close the PHP tag to reduce the blank spaces ?>