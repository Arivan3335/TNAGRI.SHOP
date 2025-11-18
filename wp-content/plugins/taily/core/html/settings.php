<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Check Dev-mode
$this->proccess_tail_admin();
$this->add_admin_cust_css();

$is_plg_dis = taily_get_all_settings('taily_settings', 'taily-full-dis');
?>
<style>
::-webkit-scrollbar-thumb {
    background: var(--tailyusercolor);
    border-radius: 0px 0px 10px 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: #1d2327;
}
::-webkit-scrollbar-track {
    border-radius: 6px 6px 6px 6px;
}
::-webkit-scrollbar {
    width: 10px;
}
.notice {
    display: none !important;
}
</style>
<?php
/***/

/*Custom Icons*/
$page_cions = $this->get_cust_cions();
$allowed_tags1 = [
    'svg' => [
        'viewbox' => true,
        'class' => true,
        'width' => true,
        'height' => true,
        'xmlns' => true,
        'version' => true,
        'id' => true,
        'x' => true,
        'y' => true,
        'style' => true,
        'xml' => true,
        /*'xml:space' => true,
        'xmlns:xlink' => true,*/
    ],
    'path' => [
        'fill-rule' => true,
        'clip-rule' => true,
        'd' => true,
        'fill' => true,
        'class'=> true,
    ],
    'rect' => [
        'x' => true,
        'fill' => true,
        'width' => true,
        'height' => true,
    ],
    'g' => [
        'fill' => true,
    ],
    'style' => [
        'type' => true,
    ],
];
/***/

/*URls*/
$sample_tail_config = TAILYBYMDZ_URL.'assets/js/tail-cdn-confs.js';
$sample_tail_inpcss = TAILYBYMDZ_URL.'assets/css/admin-inp-old.txt';

$sample_tail_v4_inpcss = TAILYBYMDZ_URL.'assets/css/sample-css-inp.css';
$sample_tail_v4_varscss = TAILYBYMDZ_URL.'assets/css/sample-css-vars.css';

$plugins_help = 'https://tailwindcss.com/docs/plugins';
$bg_header = TAILYBYMDZ_URL.'assets/img/bg-head.webp';
/***/

/*Get all Post-Types*/
$use_custom_posttypes = $this->get_opt('taily-use-cpts');
$all_post_types = get_post_types([
    'public' => true,
], 'objects');

$all_post_types2 = $all_post_types;

if($use_custom_posttypes == '' || !$use_custom_posttypes || $use_custom_posttypes == null){//Default mode (posts + pages)

    foreach($all_post_types2 as $key => $tmp){

        $tmp_name = $tmp->name;

        if($tmp_name != 'post' && $tmp_name != 'page'){
            unset($all_post_types2[$key]);
        }
    }
}else {

    foreach($all_post_types2 as $key => $tmp){

        $tmp_name = $tmp->name;
        $data_name = 'taily-cpts-'.$tmp_name;
        $data_value = $this->get_opt($data_name);

        if($data_value != 'checked' && $data_value != 1){
            unset($all_post_types2[$key]);
        }
    }
}


$all_custom_taxs = get_taxonomies([
    'public' => true,
    'show_ui' => true,
], 'objects')
/***/

//HTML Begins!
?>
<div class="taily-bg-head absolute z-20 opacity-50">
    <img src="<?php echo esc_url($bg_header); ?>" alt="bg">
</div>

<div class="taily-mother-wrapper relative w-full py-3 z-30 max-w-[1600px] mx-auto" ajx-url="<?php echo esc_url(admin_url( 'admin-ajax.php')); ?>">

    <div class="w-80perc mx-auto -taily-col bg-[#ffffffb0] rounded-xl py-2 px-8 mb-5 bg-perish max-md:w-90perc max-sm:w-full">

        <div class="w-full -taily-row justify-evenly items-stretch max-sm:flex-col max-sm:items-center">

            <div class="bg-[#ffffffb0] w-28 rounded-xl">
                <img src="<?php echo esc_url(TAILYBYMDZ_URL.'assets/img/taily-logo.png'); ?>" alt="logo" class="w-auto trans-fast cursor-pointer hover:bg-white hover:p-2 hover:shadow-md hover:-translate-y-1 hover:rounded-lg">
            </div>
            
            <div class="-taily-col justify-evenly">
                <h4 class="text-xl"><?php echo esc_html__('Taily Settings', 'tailybymdz'); ?></h4>
                <div class="-taily-col">
                    <small><?php echo esc_html__('Tailwind Framework for Wordpress', 'tailybymdz'); ?></small>
                    <div class="-taily-row">
                       
                        <small><?php echo esc_html__('Version:', 'tailybymdz'); ?></small>
                        <small class="mx-2"><?php echo esc_html(TAILYBYMDZ_VER); ?></small>
                        
                    </div>
                    
                </div>
            </div>

            

        </div>

    </div><!--Endof Settings-Header-->

    <div taily-main-settings class="w-90perc mx-auto mt-2 py-2 bg-[#ffffffb0] rounded-xl px-8 max-xl:px-5 max-md:px-3 max-sm:px-1 max-md:w-full max-sm:overflow-x-auto max-sm:overflow-y-hidden">

        <div taily-inner-settings class="w-full -taily-row py-3 items-start max-md:flex-col" taily-stick>

            <div taily-settings-side class="w-30perc mx-1 max-xl:w-40perc max-md:w-full">

                <div taily-settings-side-sticky is-rtl="<?php echo esc_attr(is_rtl()); ?>" class="w-full trans-fast" taily-sticky="45" taily-sticky-stop-wid="767">
                    <div taily-settings-side-inner class="w-full -taily-col p-2">

                        <?php if($is_plg_dis != 1 || $is_plg_dis == "nop"): ?>
                        <div class="menu-side-elem w-60perc h-12 -taily-row cursor-pointer justify-between py-1 px-3 bg-slate-200 rounded-xl my-2 trans-base hover:bg-slate-50 border-2 border-solid border-transparent max-xl:w-80perc max-lg:w-90perc max-md:w-full" data-menu="1" default-menu-active is-active-menu="0">
                            <i class="mx-1"><?php echo wp_kses($page_cions['tail_cion'], $allowed_tags1); ?></i>
                            <span class="grow text-center"><?php echo esc_html__('Tailwind Settings', 'tailybymdz'); ?></span>
                        </div>

                        <div class="menu-side-elem w-60perc h-12 -taily-row cursor-pointer justify-between py-1 px-3 bg-slate-200 rounded-xl my-2 trans-base hover:bg-slate-50 border-2 border-solid border-transparent max-xl:w-80perc max-lg:w-90perc max-md:w-full" data-menu="2" default-menu-active is-active-menu="0">
                            <i class="mx-1"><?php echo wp_kses($page_cions['plg'], $allowed_tags1); ?></i>
                            <span class="grow text-center"><?php echo esc_html__('Tailwind Plugins', 'tailybymdz'); ?></span>
                        </div>

                        <div class="menu-side-elem w-60perc h-12 -taily-row cursor-pointer justify-between py-1 px-3 bg-slate-200 rounded-xl my-2 trans-base hover:bg-slate-50 border-2 border-solid border-transparent max-xl:w-80perc max-lg:w-90perc max-md:w-full" data-menu="3" is-active-menu="0">
                            <i class="mx-1"><?php echo wp_kses($page_cions['post_types'], $allowed_tags1); ?></i>
                            <span class="grow text-center"><?php echo esc_html__('Post Types', 'tailybymdz'); ?></span>
                        </div>

                        <div class="menu-side-elem w-60perc h-12 -taily-row cursor-pointer justify-between py-1 px-3 bg-slate-200 rounded-xl my-2 trans-base hover:bg-slate-50 border-2 border-solid border-transparent max-xl:w-80perc max-lg:w-90perc max-md:w-full" data-menu="4" default-menu-active is-active-menu="0">
                            <i class="mx-1"><?php echo wp_kses($page_cions['adv'], $allowed_tags1); ?></i>
                            <span class="grow text-center"><?php echo esc_html__('Advanced', 'tailybymdz'); ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="menu-side-elem w-60perc h-12 -taily-row cursor-pointer justify-between py-1 px-3 bg-slate-200 rounded-xl my-2 trans-base hover:bg-slate-50 border-2 border-solid border-transparent max-xl:w-80perc max-lg:w-90perc max-md:w-full" data-menu="5" default-menu-active is-active-menu="0">
                            <i class="mx-1"><?php echo wp_kses($page_cions['taily'], $allowed_tags1); ?></i>
                            <span class="grow text-center"><?php echo esc_html__('Plugin Activity', 'tailybymdz'); ?></span>
                        </div>

                        <div id="taily-saver-btn" class="w-60perc h-12 -taily-row cursor-pointer justify-between py-1 px-3 user-bg-color rounded-xl my-2 trans-base hover:saturate-50 border-2 border-solid border-transparent max-md:w-full">
                            <i class="mx-1"><?php echo wp_kses($page_cions['save'], $allowed_tags1); ?></i>
                            <span class="grow text-center text-white"><?php echo esc_html__('Save Settings', 'tailybymdz'); ?></span>
                        </div>

                    </div>
                </div>

            </div><!--Endof Settings-Sidebar-->

            <div taily-settings-con class="w-70perc mx-3 max-xl:w-60perc max-md:w-full">

                <div taily-settings-con-inner class="w-full -taily-col">

                    <div data-menu-con="1" class="single-inner-menu w-full my-4 taily-hidden trans-opacity z-20">
                        <div class="w-full -taily-col">

                            <div class="w-full -taily-col my-4 mb-10">
                                <label class="w-full">

                                    <span><?php echo esc_html__('Select Tailwind Version:', 'tailybymdz'); ?></span>
                                    <div class="taily-selector relative w-full max-w-[400px] -taily-col cursor-pointer my-2 py-3 px-6 border-2 user-border-color border-dashed rounded-2xl overflow-hidden trans-base hover:border-solid hover:bg-white hover:-translate-y-1 hover:shadow-md" taily-select-status="normal" data-name="taily-tailwind-ver" data-saver="1" datasave-type="cust-select" title="<?php echo esc_html__('Click', 'tailybymdz'); ?>"  curr-val="<?php echo esc_attr($this->get_opt('taily-tailwind-ver')); ?>">

                                        <span is-active-val can-select-me this-val="ver3" class="text-center my-1 trans-fast" opt-hidener-classes="opt-tailver4-show" opt-shower-classes="opt-tailver3-show"><?php echo esc_html__('Tailwind v3 (deprecated)', 'tailybymdz'); ?></span>

                                        <span can-select-me this-val="ver4" class="text-center my-1 trans-fast" opt-hidener-classes="opt-tailver3-show" opt-shower-classes="opt-tailver4-show"><?php echo esc_html__('Tailwind v4 (most-recommended)', 'tailybymdz'); ?></span>


                                        <div class="close-cion absolute top-1 right-2 trans-base hover:scale-125" title="<?php echo esc_html__('exit', 'tailybymdz'); ?>"> 
                                            <i><?php echo wp_kses($page_cions['exit'], $allowed_tags1); ?></i>
                                        </div>

                                        <little-cion class="absolute top-1/3 left-2 trans-fast"><i><?php echo wp_kses($page_cions['arrow'], $allowed_tags1); ?></i></little-cion>
                                    </div>

                                    <small>
                                        <?php echo esc_html__('Important Note: In version 4 of the Tailwind framework, many optimizations have been made, and its speed and performance have been greatly improved. Also, the `tailwind.config.js` file has been completely removed and standard CSS variables have been used in all component definitions, etc.', 'tailybymdz'); ?>
                                    </small>

                                </label>
                            </div><!--Endof single-setting-->

                            <div id="sets-con-1" class="settings-hide-wrapper">

                                <div class="opt-tailver4-show w-full -taily-col my-4">

                                    <label class="w-full">
                                        <span class="user-colo bg-slate-200 trans-fast rounded-lg py-2 px-8 mb-3 hover:rounded-xl"><?php echo esc_html__('Input CSS Configs (input.css)', 'tailybymdz'); ?></span>

                                        <textarea style="direction: ltr;" class="w-full mt-3 min-h-[120px] px-2 py-1" placeholder="<?php echo esc_html__('Please do not use any backslash (\) in this section. Do not use of `@import "tailwindcss"` at here', 'tailybymdz'); ?>" data-saver="1" data-name="taily-usertailconfs3" datasave-type="textarea" rows="10"><?php echo esc_html($this->get_opt('taily-usertailconfs3')); ?></textarea>
                                    </label>

                                    <div class="mt-5 bg-slate-100 rounded-lg py-2 px-3 cursor-help">
                                        <h6 class="text-md mb-2"><?php echo esc_html__('Help:', 'tailybymdz'); ?></h6>
                                        <p class="px-3">
                                            <?php echo esc_html__('This section refers to the contents of the input.css file of the Tailwind framework (Version: 4), in this section, for example, you can define your own components (@layer components) or set some custome theme properties (@theme).', 'tailybymdz'); ?>
                                        </p>

                                        <div class="mt-4 -taily-col">
                                            <small><?php echo esc_html__('Click the below button to download a sample tailwind input.css file', 'tailybymdz'); ?></small>
                                            <a href="<?php echo esc_url($sample_tail_v4_inpcss); ?>" target="_blank" class="my-2 user-color"><?php echo esc_html__('Download', 'tailybymdz'); ?></a>
                                        </div>
                                    </div>

                                </div><!--Endof single-setting-->

                                <hr class="opt-tailver4-show w-full my-2">

                                <div class="opt-tailver4-show w-full -taily-col my-4">

                                    <label class="w-full">
                                        <span class="user-colo bg-slate-200 trans-fast rounded-lg py-2 px-8 mb-3 hover:rounded-xl"><?php echo esc_html__('Your Global CSS Variables:', 'tailybymdz'); ?></span>

                                        <textarea style="direction: ltr;" class="w-full mt-3 min-h-[120px] px-2 py-1" placeholder="<?php echo esc_html__('Please do not use any backslash (\) in this section', 'tailybymdz'); ?>" data-saver="1" data-name="taily-usertailconfs4" datasave-type="textarea" rows="10"><?php echo esc_html($this->get_opt('taily-usertailconfs4')); ?></textarea>
                                    </label>

                                    <div class="mt-5 bg-slate-100 rounded-lg py-2 px-3 cursor-help">
                                        <h6 class="text-md mb-2"><?php echo esc_html__('Help:', 'tailybymdz'); ?></h6>
                                        <p class="px-3">
                                            <?php echo esc_html__('In this section, you can define your global CSS variables globally so that all parts of your code are more compatible with Tailwind version 4.', 'tailybymdz'); ?>
                                        </p>

                                        <div class="mt-4 -taily-col">
                                            <small><?php echo esc_html__('Click the below button to download a sample css file with codes (::root)', 'tailybymdz'); ?></small>
                                            <a href="<?php echo esc_url($sample_tail_v4_varscss); ?>" target="_blank" class="my-2 user-color"><?php echo esc_html__('Download', 'tailybymdz'); ?></a>
                                        </div>
                                    </div>

                                </div><!--Endof single-setting-->

                                <div class="opt-tailver3-show w-full -taily-col my-4">

                                    <label class="w-full">

                                        <span class="user-colo bg-slate-200 trans-fast rounded-lg py-2 px-8 mb-3 hover:rounded-xl"><?php echo esc_html__('Your Configs (JS):', 'tailybymdz'); ?></span>

                                        <textarea style="direction: ltr;" class="w-full mt-3 min-h-[120px] px-2 py-1" placeholder="<?php echo esc_html__('Please do not use any backslash (\) in this section', 'tailybymdz'); ?>" data-saver="1" data-name="taily-usertailconfs" datasave-type="textarea" rows="10"><?php echo esc_html($this->get_opt('taily-usertailconfs')); ?></textarea>

                                    </label>

                                    <div class="mt-5 bg-slate-100 rounded-lg py-2 px-3 cursor-help">
                                        <h6 class="text-md mb-2"><?php echo esc_html__('Help:', 'tailybymdz'); ?></h6>
                                        <p class="px-3">
                                            <?php echo esc_html__('The meaning of this section is the same information in the tailwind.config.js file that you can enter in this section in a personalized way.', 'tailybymdz'); ?>
                                        </p>

                                        <div class="mt-4 -taily-col">
                                            <small><?php echo esc_html__('Click the below button to download a sample tailwind.config.js file', 'tailybymdz'); ?></small>
                                            <a href="<?php echo esc_url($sample_tail_config); ?>" target="_blank" class="my-2 user-color"><?php echo esc_html__('Download', 'tailybymdz'); ?></a>
                                        </div>
                                    </div>

                                </div><!--Endof single-setting-->

                                <hr class="opt-tailver3-show w-full my-2">

                                <div class="opt-tailver3-show w-full -taily-col my-4">

                                    <label class="w-full">
                                        <span class="user-colo bg-slate-200 trans-fast rounded-lg py-2 px-8 mb-3 hover:rounded-xl"><?php echo esc_html__('Your Configs (CSS):', 'tailybymdz'); ?></span>

                                        <textarea style="direction: ltr;" class="w-full mt-3 min-h-[120px] px-2 py-1" placeholder="<?php echo esc_html__('Please do not use any backslash (\) in this section', 'tailybymdz'); ?>" data-saver="1" data-name="taily-usertailconfs2" datasave-type="textarea" rows="10"><?php echo esc_html($this->get_opt('taily-usertailconfs2')); ?></textarea>
                                    </label>

                                    <div class="mt-5 bg-slate-100 rounded-lg py-2 px-3 cursor-help">
                                        <h6 class="text-md mb-2"><?php echo esc_html__('Help:', 'tailybymdz'); ?></h6>
                                        <p class="px-3">
                                            <?php echo esc_html__('This section refers to the contents of the input.css file of the Tailwind framework, in this section, for example, you can define your own components.', 'tailybymdz'); ?>
                                        </p>

                                        <div class="mt-4 -taily-col">
                                            <small><?php echo esc_html__('Click the below button to download a sample tailwind input.css file', 'tailybymdz'); ?></small>
                                            <a href="<?php echo esc_url($sample_tail_inpcss); ?>" target="_blank" class="my-2 user-color"><?php echo esc_html__('Download', 'tailybymdz'); ?></a>
                                        </div>
                                    </div>

                                </div><!--Endof single-setting-->
                            </div>

                        </div>
                    </div><!--Endof Tailwind-Settings-Sec-->

                    <div data-menu-con="2" class="single-inner-menu w-full my-4 taily-hidden trans-opacity z-20">

                        <div class="opt-tailver3-show w-full -taily-col">

                            <div class="w-full -taily-col my-4 items-start">

                                
                                <span class="user-colo bg-slate-200 trans-fast rounded-lg py-2 px-8 mb-3 hover:rounded-xl"><?php echo esc_html__('Official Plugins:', 'tailybymdz'); ?></span>

                                <label class="-taily-row justify-center my-3 cursor-pointer">
                                    <span class="mx-1"><?php echo esc_html__('Forms', 'tailybymdz'); ?></span>
                                    <input class="mx-1" type="checkbox" data-saver="1" data-name="taily-plgs-forms" datasave-type="checkbox" <?php echo esc_attr($this->get_opt('taily-plgs-forms')); ?>>
                                </label>

                                <label class="-taily-row justify-center my-3 cursor-pointer">
                                    <span class="mx-1"><?php echo esc_html__('Line-Clamp', 'tailybymdz'); ?></span>
                                    <input class="mx-1" type="checkbox" data-saver="1" data-name="taily-plgs-lclamp" datasave-type="checkbox" <?php echo esc_attr($this->get_opt('taily-plgs-lclamp')); ?>>
                                </label>

                                <label class="-taily-row justify-center my-3 cursor-pointer">
                                    <span class="mx-1"><?php echo esc_html__('Typography', 'tailybymdz'); ?></span>
                                    <input class="mx-1" type="checkbox" data-saver="1" data-name="taily-plgs-typo" datasave-type="checkbox" <?php echo esc_attr($this->get_opt('taily-plgs-typo')); ?>>
                                </label>
                                
                                <label class="-taily-row justify-center my-3 cursor-pointer">
                                    <span class="mx-1"><?php echo esc_html__('Aspect-Ratio', 'tailybymdz'); ?></span>
                                    <input class="mx-1" type="checkbox" data-saver="1" data-name="taily-plgs-aspect" datasave-type="checkbox" <?php echo esc_attr($this->get_opt('taily-plgs-aspect')); ?>>
                                </label>

                                <label class="-taily-row justify-center my-3 cursor-pointer">
                                    <span class="mx-1"><?php echo esc_html__('Container-Queries', 'tailybymdz'); ?></span>
                                    <input class="mx-1" type="checkbox" data-saver="1" data-name="taily-plgs-conq" datasave-type="checkbox" <?php echo esc_attr($this->get_opt('taily-plgs-conq')); ?>>
                                </label>


                                <div class="mt-5 bg-slate-100 rounded-lg py-2 px-3 cursor-help">
                                    <h6 class="text-md mb-2"><?php echo esc_html__('Help:', 'tailybymdz'); ?></h6>
                                    <p class="px-3">
                                        <?php echo esc_html__('If you dont know what Tailwind framework plugins are and how to use them, you can get more information using the guide link below.', 'tailybymdz'); ?>
                                    </p>

                                    <div class="mt-4 -taily-col">
                                        <small><?php echo esc_html__('Click the below button for more information about Tailwind-Plugins', 'tailybymdz'); ?></small>
                                        <a href="<?php echo esc_url($plugins_help); ?>" target="_blank" class="my-2 user-color"><?php echo esc_html__('Plugin-Docs', 'tailybymdz'); ?></a>
                                    </div>
                                </div>

                            </div><!--Endof single-setting-->
                        </div>

                        <div class="opt-tailver4-show mt-5 bg-slate-100 rounded-lg py-2 px-3 cursor-help">
                            <h6 class="text-md mb-2"><?php echo esc_html__('Help:', 'tailybymdz'); ?></h6>
                            <p class="px-3 w-full text-center">
                                <?php echo esc_html__('Note: Plugins have been removed in Tailwind version 4 and are no longer useful', 'tailybymdz'); ?>
                            </p>
                        </div>

                    </div><!--Endof Plugins-Sec-->

                    <div data-menu-con="3" class="single-inner-menu w-full my-4 taily-hidden trans-opacity z-20">
                        <div class="w-full -taily-col">

                            
                            <div class="taily-checkbox-btn-wrapper max-md:text-center">
                                <span style="font-size: 16px;"><?php echo esc_html__('Use Post-types?', 'tailybymdz') ?></span>
                                <label class="switch">
                                    <input type="checkbox" hiddener="show-cpt-settings" hiddener-stats="inactive" data-saver="1" data-name="taily-use-cpts" datasave-type="checkbox" <?php echo esc_attr($this->get_opt('taily-use-cpts')); ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <small><?php echo esc_html__('By default, this plugin works only on WordPress pages and posts, but you can change this according to your needs.', 'tailybymdz'); ?></small>

                            <div class="w-full -taily-col my-4 taily-hidden trans-opacity" hidden-blck="show-cpt-settings">

                                <div class="w-full flex flex-wrap items-center justify-evenly bg-slate-200 py-2 px-4 rounded-xl border border-solid user-border-color">
                                <?php
                                foreach($all_post_types as $cpt){
                                    $cpt_name = $cpt->name;
                                    $cpt_label = $cpt->label;

                                    if(strtolower($cpt_name) == 'attachment'){
                                        continue;
                                    }

                                    $data_name = 'taily-cpts-'.strtolower($cpt_name);

                                    ?>
                                    <label class="w-30perc -taily-row cursor-pointer justify-between my-3 mx-1 bg-white py-1 px-2 pt-2 rounded-md trans-fast hover:shadow-md hover:-translate-y-1 max-lg:w-40perc max-sm:w-full">
                                        <span class="mx-1"><?php echo esc_html($cpt_label); ?></span>
                                        <input class="mx-1" type="checkbox" data-saver="1" data-name="<?php echo esc_attr($data_name); ?>" datasave-type="checkbox" <?php echo esc_attr($this->get_opt($data_name)); ?>>
                                    </label>
                                    <?php
                                } 
                                ?>
                                </div>

                                <div class="mt-5 bg-slate-100 rounded-lg py-3 px-3 cursor-help">
                                    <h6 class="text-md mb-2"><?php echo esc_html__('Help:', 'tailybymdz'); ?></h6>
                                    <p class="px-3">
                                        <?php echo esc_html__('It is better to select and activate only post types that are active and visible on the front-end of your site', 'tailybymdz'); ?>
                                    </p>
                                </div>

                            </div><!--Endof single-setting-->

                        </div>
                    </div><!--Endof PostTypes-Sec-->

                    <div data-menu-con="4" class="single-inner-menu w-full my-4 taily-hidden trans-opacity z-20">
                        <div class="w-full -taily-col z-20">

                            <div class="w-full -taily-col z-20">
                                <span><?php echo esc_html__('How to add Productive CSS files?', 'tailybymdz'); ?></span>

                                <div class="taily-selector relative w-full max-w-[300px] -taily-col cursor-pointer my-2 py-3 px-6 border-2 user-border-color border-dashed rounded-2xl overflow-hidden trans-base hover:border-solid hover:bg-white hover:-translate-y-1 hover:shadow-md" taily-select-status="normal" data-name="taily-print-css-type" data-saver="1" datasave-type="cust-select" title="<?php echo esc_html__('Click', 'tailybymdz'); ?>"  curr-val="<?php echo esc_attr($this->get_opt('taily-print-css-type')); ?>"   selector-hiddener="show-template-cpts" selector-hiddener-val="ext">

                                    <span is-active-val can-select-me this-val="inline" class="text-center my-1 trans-fast"><?php echo esc_html__('inline (head-tag)', 'tailybymdz'); ?></span>

                                    <span can-select-me this-val="ext" class="text-center my-1 trans-fast"><?php echo esc_html__('External CSS-file', 'tailybymdz'); ?></span>


                                    <div class="close-cion absolute top-1 right-2 trans-base hover:scale-125" title="<?php echo esc_html__('exit', 'tailybymdz'); ?>"> 
                                        <i><?php echo wp_kses($page_cions['exit'], $allowed_tags1); ?></i>
                                    </div>

                                    <little-cion class="absolute top-1/3 left-2 trans-fast"><i><?php echo wp_kses($page_cions['arrow'], $allowed_tags1); ?></i></little-cion>
                                </div>

                                <small>
                                    <?php echo esc_html__('Note: External CSS file type creates one CSS file per page or post and consumes more (disk space) of your server.', 'tailybymdz'); ?>
                                </small>

                                <div class="w-full my-5 -taily-col trans-opacity" selector-hidden="">

                                    <h4 class="w-full mt-3 mb-2"><?php echo esc_html__('Select Template-Type Post-types:','tailybymdz'); ?></h4>
                                    
                                    <div class="w-full flex flex-wrap items-center justify-evenly user-bg-color py-2 px-4 rounded-xl border border-solid border-slate-300 [&>label:nth-child(odd)]:bg-[#dfdfdf] [&>label:nth-child(odd)]:shadow-lg z-20">
                                    <?php
                                    if(count($all_post_types2) > 0){
                                        foreach($all_post_types2 as $cpt){

                                            $cpt_name = $cpt->name;
                                            $cpt_label = $cpt->label;

                                            if(strtolower($cpt_name) == 'attachment'){
                                                continue;
                                            }

                                            $data_name = 'taily-template-cpts-'.strtolower($cpt_name);
                                            $data_name_prod = 'taily-tempcpts-pmode-'.strtolower($cpt_name);

                                            ?>
                                            <label class="w-30perc cursor-pointer relative -taily-row justify-between my-3 mx-1 bg-white py-1 px-2 pt-2 rounded-md mb-18 trans-fast hover:shadow-md hover:-translate-y-1 max-lg:w-full max-lg:mb-20">
                                                <span class="mx-1 pt-2 pb-4"><?php echo esc_html($cpt_label); ?></span>
                                                <input class="mx-1" type="checkbox" data-saver="1" data-name="<?php echo esc_attr($data_name); ?>" datasave-type="checkbox" <?php echo esc_attr($this->get_opt($data_name)); ?>>

                                                <div class="inner-pmode-toggles bg-white absolute w-full bottom-[-72px] left-0 scale-75 px-2 rounded-b-xl z-10">
                                                    <div class="taily-checkbox-btn-wrapper w-full flex justify-between">
                                                        <span class="text-[12px]"><?php echo esc_html__('Productive Mode?', 'tailybymdz') ?></span>
                                                        <label class="switch scale-90">
                                                            <input type="checkbox" data-saver="1" data-name="<?php echo esc_attr($data_name_prod); ?>" datasave-type="checkbox" <?php echo esc_attr($this->get_opt($data_name_prod)); ?>>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </label>
                                            <?php
                                        } 
                                    }else{
                                        ?>
                                        <div class="flex flex-col items-center">
                                            <p class="text-center text-white">
                                                <?php echo esc_html__('It seems that you have not activated any post type! To view and use this section, you must activate at least one option in the post types section!', 'tailybymdz'); ?>
                                            </p>

                                            <button onclick="location.reload()" class="-taily-btn my-4 mt-6">
                                                <?php echo esc_html__('Re-check', 'tailybymdz'); ?>
                                            </button>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    </div>

                                    <div class="mt-5 bg-slate-100 rounded-lg py-3 px-3 cursor-help">
                                        <h6 class="text-md mb-2"><?php echo esc_html__('Help:', 'tailybymdz'); ?></h6>
                                        <p class="px-3">
                                            <?php echo esc_html__('In the external CSS file generation mode, you can generate a common CSS file for each post type by selecting post types that act as templates (such as WooCommerce products), not a separate file for each product.', 'tailybymdz'); ?>
                                        </p>

                                        <div class="mt-4 -taily-col">
                                            <small><?php echo esc_html__('Setting this up will help further optimize your site and server resources', 'tailybymdz'); ?></small>
                                        </div>
                                    </div>

                                    <div class="mt-5 bg-slate-100 rounded-lg py-3 px-3 cursor-help">
                                        <h6 class="text-md mb-2"><?php echo esc_html__('Warning:', 'tailybymdz'); ?></h6>
                                        <p class="px-3">
                                            <?php echo esc_html__('Use template mode for the post-types only if the design of all its pages has the same HTML code', 'tailybymdz'); ?>
                                        </p>
                                    </div>

                                    <button class="cssfiles-cleaner-btn -taily-btn bg-rose-400 my-4">
                                        <?php echo esc_html__('Clear-All Static-CSS-files', 'tailybymdz'); ?>
                                    </button>

                                    <div class="mt-5 bg-slate-100 rounded-lg py-3 px-3 cursor-help">
                                        <h6 class="text-md mb-2"><?php echo esc_html__('Help:', 'tailybymdz'); ?></h6>

                                        <?php $uploads_url = home_url().'/wp-content/uploads/taily/'; ?>

                                        <p class="px-3">
                                            <?php echo esc_html__('Use with caution: Use the button above to clear all CSS files generated by this plugin.', 'tailybymdz'); ?>
                                        </p>

                                        <p class="px-3">
                                            <?php echo esc_html__('Note: After cleaning, it is better to disable the production mode for all your posts and pages (you can use the buttons below in the -- Production Mode -- Section)', 'tailybymdz'); ?>

                                            <a href="#page-pd-mode" class="mx-2"><?php echo esc_html__('Go to Section', 'tailybymdz'); ?></a>
                                        </p>

                                        <div class="w-full my-2">
                                            <span class="mx-1">
                                                <?php echo esc_html__('Folder-url:', 'tailybymdz'); ?>
                                            </span>

                                            <span class="mx-1">
                                                <?php echo esc_html(esc_url($uploads_url)); ?>
                                            </span>
                                        </div>

                                        <div class="mt-4 -taily-col">
                                            <small><?php echo esc_html__('Setting this up will help further optimize your site and server resources', 'tailybymdz'); ?></small>
                                        </div>
                                    </div>

                                    <h4 class="w-full mt-6 mb-2"><?php echo esc_html__('Select Template-Type Custom-taxonomies:','tailybymdz'); ?></h4>
                                    
                                    <div style="filter: hue-rotate(80deg);" class="w-full flex flex-wrap items-center justify-evenly user-bg-color py-2 px-4 rounded-xl border border-solid border-slate-300 [&>label:nth-child(odd)]:bg-[#dfdfdf] [&>label:nth-child(odd)]:shadow-lg z-20">
                                    <?php
                                    foreach($all_custom_taxs as $tax){
                                        
                                        $tax_name = $tax->name;
                                        $tax_label = $tax->label;

                                        $data_name = 'taily-template-taxs-'.strtolower($tax_name);
                                        $data_name_prod = 'taily-temptaxs-pmode-'.strtolower($tax_name);

                                        ?>
                                        <label class="w-30perc cursor-pointer relative -taily-row justify-between my-3 mx-1 bg-white py-1 px-2 pt-2 rounded-md mb-18 trans-fast hover:shadow-md hover:-translate-y-1 max-lg:w-full max-lg:mb-20">
                                            <span class="mx-1 pt-2 pb-4"><?php echo esc_html($tax_label); ?></span>
                                            <input class="mx-1" type="checkbox" data-saver="1" data-name="<?php echo esc_attr($data_name); ?>" datasave-type="checkbox" <?php echo esc_attr($this->get_opt($data_name)); ?>>

                                            <div class="inner-pmode-toggles bg-white absolute w-full bottom-[-72px] left-0 scale-75 px-2 rounded-b-xl z-10">
                                                <div class="taily-checkbox-btn-wrapper w-full flex justify-between">
                                                    <span class="text-[12px]"><?php echo esc_html__('Productive Mode?', 'tailybymdz') ?></span>
                                                    <label class="switch scale-90">
                                                        <input type="checkbox" data-saver="1" data-name="<?php echo esc_attr($data_name_prod); ?>" datasave-type="checkbox" <?php echo esc_attr($this->get_opt($data_name_prod)); ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </label>
                                        <?php
                                    } 
                                    ?>
                                    </div>

                                    <div class="mt-4 -taily-col">
                                        <small><?php echo esc_html__('Tip: You can put WordPress custom taxonomies into the production CSS mode only in the template mode', 'tailybymdz'); ?></small>
                                    </div>

                                    <div class="mt-5 bg-slate-100 rounded-lg py-3 px-3 cursor-help">
                                        <h6 class="text-md mb-2"><?php echo esc_html__('Warning:', 'tailybymdz'); ?></h6>
                                        <p class="px-3">
                                            <?php echo esc_html__('Use template mode for the Taxonomy only if the design of all its pages has the same HTML code', 'tailybymdz'); ?>
                                        </p>
                                    </div>

                                </div>

                                

                            </div>

                            <hr class="w-full my-2">

                            <div class="taily-checkbox-btn-wrapper max-md:text-center">
                                <span style="font-size: 16px;"><?php echo esc_html__('Deactivate Elementor Compatibility?', 'tailybymdz') ?></span>
                                <label class="switch">
                                    <input type="checkbox" data-saver="1" data-name="taily-dec-elem-comp" datasave-type="checkbox" <?php echo esc_attr($this->get_opt('taily-dec-elem-comp')); ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <small><?php echo esc_html__('If you dont have Elementor page builder or if you dont use this feature, activate the above option', 'tailybymdz'); ?></small>

                            <small><?php echo esc_html__('Note: By default, a special section (textarea) for writing Tailwind classes has been added to all Elementor widgets of your site', 'tailybymdz'); ?></small>

                            <hr class="w-full my-2">

                            <div class="taily-checkbox-btn-wrapper max-md:text-center">
                                <span style="font-size: 16px;"><?php echo esc_html__('Deactivate Gutenberg Compatibility?', 'tailybymdz') ?></span>
                                <label class="switch">
                                    <input type="checkbox" data-saver="1" data-name="taily-dec-gut-comp" datasave-type="checkbox" <?php echo esc_attr($this->get_opt("taily-dec-gut-comp")); ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <small><?php echo esc_html__('If you are not using the default WordPress page builder (Gutenberg) or if you have disabled it, enable the above option.', 'tailybymdz'); ?></small>

                            <small><?php echo esc_html__('Note: By default, a special section (textarea) for writing Tailwind classes has been added to all Gutenberg widgets of your site', 'tailybymdz'); ?></small>

                            <hr class="w-full my-2">

                            <h4 id="page-pd-mode" class="text-xl my-4 pt-3"><?php echo esc_html__('-- Production Mode --', 'tailybymdz'); ?></h4>

                            <button data-mode="1" class="prod-mode-changer-btn -taily-btn bg-green-500">
                                <?php echo esc_html__('Set all to Productive-Mode', 'tailybymdz'); ?>
                            </button>

                            <div class="mt-5 bg-slate-100 rounded-lg py-3 px-3 cursor-help">
                                <h6 class="text-md mb-2"><?php echo esc_html__('Help:', 'tailybymdz'); ?></h6>
                                <p class="px-3">
                                    <?php echo esc_html__('Converts all posts, pages and other post types that use Taily to productive-mode', 'tailybymdz'); ?>
                                </p>

                                <div class="mt-4 -taily-col">
                                    <small><?php echo esc_html__('If your design is complete, you can use the above option to load pages faster', 'tailybymdz'); ?></small>
                                </div>
                            </div>

                            <hr class="w-full my-6">


                            <button data-mode="2" class="prod-mode-changer-btn -taily-btn bg-amber-600">
                                <?php echo esc_html__('Set all to Non-Productive-Mode', 'tailybymdz'); ?>
                            </button>

                            <div class="mt-5 bg-slate-100 rounded-lg py-3 px-3 cursor-help">
                                <h6 class="text-md mb-2"><?php echo esc_html__('Help:', 'tailybymdz'); ?></h6>
                                <p class="px-3">
                                    <?php echo esc_html__('Converts all posts, pages and other post types that use Taily to non-productive-mode (Editing Mode)', 'tailybymdz'); ?>
                                </p>
                            </div>

                            <hr class="w-full my-6">

                            <div class="taily-checkbox-btn-wrapper max-md:text-center">
                                <span style="font-size: 16px;"><?php echo esc_html__('Change to non-productive mode when editing post?', 'tailybymdz') ?></span>
                                <label class="switch">
                                    <input type="checkbox" data-saver="1" data-name="taily-gonon-onedit" datasave-type="checkbox" <?php echo esc_attr($this->get_opt('taily-gonon-onedit')); ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <small><?php echo esc_html__('Every time you hit the edit button on a post or page, it automatically changes its mode to non-productive mode.', 'tailybymdz'); ?></small>

                            <hr class="w-full my-6">

                            <div class="taily-checkbox-btn-wrapper max-md:text-center">
                                <span style="font-size: 16px;"><?php echo esc_html__('Add Tailwind-JS file locally', 'tailybymdz') ?></span>
                                <label class="switch">
                                    <input type="checkbox" data-saver="1" data-name="taily-donuse-cdn" datasave-type="checkbox" <?php echo esc_attr($this->get_opt('taily-donuse-cdn')); ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <small><?php echo esc_html__('You can use this option if using Tailwind CDN slows down your site', 'tailybymdz'); ?></small>

                            <small><?php echo esc_html__('Note: Activating this item can interfere with the functionality of Tailwind plugins', 'tailybymdz'); ?></small>

                            <div class="opt-tailver3-show">
                                <small><?php echo 'Tailwind-Version: 3.4.4'; ?></small>
                            </div>

                            <div class="opt-tailver4-show">
                                <small><?php echo 'Tailwind-Version: 4.1'; ?></small>
                            </div>
                            

                            <hr class="w-full my-6">

                            <h5 class="w-full text-[16px]"><?php echo esc_html__('Wordpress Cache', 'tailybymdz'); ?></h5>

                            <div class="taily-checkbox-btn-wrapper max-md:text-center">
                                <span style="font-size: 16px;"><?php echo esc_html__('Cache Compatibility', 'tailybymdz') ?></span>
                                <label class="switch">
                                    <input type="checkbox" data-saver="1" data-name="taily-cache-comp" datasave-type="checkbox" <?php echo esc_attr($this->get_opt('taily-cache-comp')); ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <small><?php echo esc_html__('Note: If you use cache plugins such as Wp-Rocket, enable this option', 'tailybymdz'); ?></small>



                        </div>
                    </div><!--Endof Advanced-Sec-->

                    <div data-menu-con="5" class="single-inner-menu w-full my-4 taily-hidden trans-opacity z-20">
                        <div class="w-full -taily-col">

                            <div class="taily-checkbox-btn-wrapper max-md:text-center">
                                <span style="font-size: 16px;"><?php echo esc_html__('Disable Plugin?', 'tailybymdz') ?></span>
                                <label class="switch">
                                    <input type="checkbox" data-saver="1" data-name="taily-full-dis" datasave-type="checkbox" <?php echo esc_attr($this->get_opt("taily-full-dis")); ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <small><?php echo esc_html__('This option is used for temporary deactivations or for troubleshooting, if you dont want to use this plugin completely, its better to delete it!', 'tailybymdz'); ?></small>

                            <hr class="w-full my-2">

                            <div class="taily-checkbox-btn-wrapper max-md:text-center">
                                <span style="font-size: 16px;"><?php echo esc_html__('Clearing while Uninstall', 'tailybymdz') ?></span>
                                <label class="switch">
                                    <input type="checkbox" data-saver="1" data-name="taily-db-clear" datasave-type="checkbox" <?php echo esc_attr($this->get_opt('taily-db-clear')); ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <small><?php echo esc_html__('By activating this option, when deleting this plugin, all its data stored in your database will be deleted forever', 'tailybymdz'); ?></small>

                            <hr class="w-full my-2">

                        </div>
                    </div><!--Endof Plugin-Activity-Sec-->

                </div>

            </div><!--Endof Settings-con-->


        </div>

    </div><!--Endof Main-Settings-Wrapper-->

    <div class="relative w-80perc my-5 mx-auto bg-[#ffffffb0] py-2 px-5 rounded-xl z-30 max-md:w-90perc max-sm:w-full">

        <div class="w-full -taily-row justify-around py-4 max-sm:flex-col">
            <div class="max-sm:mb-8">
                <a href="<?php echo esc_url(get_admin_url() . 'admin.php?page=tailysettings_help'); ?>" class="-taily-btn user-bg-color text-white-force"><?php echo esc_html__('help', 'tailybymdz'); ?></a>
            </div>

            <div class="max-sm:mb-8">
                <a href="<?php echo esc_url(get_admin_url() . 'plugin-install.php?s=mdesign&tab=search&type=author'); ?>" class="-taily-btn user-bg-color text-white-force"><?php echo esc_html__('Free Plugins', 'tailybymdz'); ?></a>
            </div>

            <div class="max-sm:mb-2">
                <a href="<?php echo esc_url(get_admin_url() . 'admin.php?page=tailysettings_desgin'); ?>" class="-taily-btn user-bg-color text-white-force"><?php echo esc_html__('Contact Us', 'tailybymdz'); ?></a>
            </div>
        </div>

    </div><!--Endof Other-Links-->

    <div class="relative w-70perc my-5 mx-auto bg-[#ffffffb0] py-2 px-5 rounded-xl z-30 max-md:w-80perc max-sm:w-full">

        <h6 class="mb-4 mt-3"><?php echo esc_html__('About Taily »', 'tailybymdz') ?></h6>

        <p class="text-justify px-4 mb-2">
            <?php echo esc_html__('This completely free plugin is written with the goal of faster and easier WordPress front-end development by the popular Tailwind framework. We hope that with the help of this plugin you will be able to use the Tailwind CSS framework.', 'tailybymdz') ?>
        </p>

        <p class="text-justify px-4">
            <?php echo esc_html__('This free plugin has been coded in an object-oriented way, and during the coding process, maintaining the speed and optimality of the clients site has been and will be our Main Priority.', 'tailybymdz') ?>
        </p>

        <p class="text-justify px-4 mb-2">
            <?php echo esc_html__('This free plugin has been coded in an object-oriented way, and during the coding process, maintaining the speed and optimality of the clients site has been and will be our Main Priority.', 'tailybymdz') ?>
        </p>

        <p class="text-justify px-4">
            <?php echo esc_html__('Important: If your design process is finished, in order to optimize the speed of the pages made with Taily as much as possible, use the production mode button to convert all your pages to this mode', 'tailybymdz') ?>
        </p>

        <div class="mt-8"></div>
        <small><?php echo esc_html__('Supported Languages: Arabic, English, Greek, Persian, French, Italian, Spanish, Russian, Japanese, Chinese', 'tailybymdz'); ?></small>
        <br>
        <small><?php echo esc_html__('Created by: < Mdesign />', 'tailybymdz'); ?></small>

    </div><!--Endof About-->

    <div class="w-full my-4">
        <small class="text-[12px]"><?php echo esc_html__('-- Designed and Powered by Tailwind ©', 'tailybymdz'); ?></small>
    </div><!--Endof Tailwind-Trade-mark-->

    <div>

        <div id="taily-settings-announcer" class="fixed w-fit max-w-[300px] py-3 px-10 rounded-xl bg-white shadow-lg top-12 <?php if(is_rtl()){echo 'left-4';}else{echo 'right-4';} ?> taily-announcer-hidden trans-fast">
            <span></span>
        </div>

    </div><!--Endof page-popups-wrapper-->

</div>
<script>
var trans1 = "<?php echo esc_html__('Saved Successfully!', 'tailybymdz'); ?>";
var trans2 = "<?php echo esc_html__('No changes to save. But it was saved anyway!', 'tailybymdz'); ?>";
var trans3 = "<?php echo esc_html__('Please wait for the page to reload...', 'tailybymdz'); ?>";
var trans4 = "<?php echo esc_html__('Done!', 'tailybymdz'); ?>";
var trans5 = "<?php echo esc_html__('Selected', 'tailybymdz'); ?>";
var trans6 = "<?php echo esc_html__('Are you sure? => note that this operation is irreversible', 'tailybymdz'); ?>";
</script>

<?php $this->add_js_file('admin', true, ['jquery']); ?>