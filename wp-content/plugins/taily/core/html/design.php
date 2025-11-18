<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Check Dev-mode
$this->proccess_tail_admin();
$this->add_admin_cust_css();

$is_plg_dis = taily_get_all_settings('taily_settings', 'taily-full-dis');

/*URls*/
$bg_header = TAILYBYMDZ_URL.'assets/img/bg-head.webp';
$tmp_logo = TAILYBYMDZ_URL.'assets/img/vio-logo.png';
$bg_shape = TAILYBYMDZ_URL.'assets/img/bg-shape.svg';
$bg_image = TAILYBYMDZ_URL.'assets/img/bg-shape2.svg';
$social_cions = TAILYBYMDZ_URL.'assets/img/footer-socials.webp';
$contact_us = 'https://t.me/g_mdz';
$example_1 = TAILYBYMDZ_URL.'assets/img/page-1.webp';
$example_2 = TAILYBYMDZ_URL.'assets/img/page-2.webp';
/***/
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
        'xml:space' => true,
        'xmlns:xlink' => true,
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
//Start-HTML!
?>
<div class="taily-bg-head absolute z-20 opacity-50">
    <img src="<?php echo esc_url($bg_header); ?>" alt="bg">
</div>

<div class="w-full relative py-8 z-30 overflow-x-hidden max-w-[1600px] mx-auto" is-rtl="<?php echo esc_attr(is_rtl()); ?>">

    <div id="taily-hero-temp1" class="w-80perc relative mx-auto bg-[#d1d5dbc2] min-h-[445px] h-auto py-4 px-10 rounded-2xl bg-perish overflow-hidden select-none taily-anima-3 trans-slow mb-8 hover:shadow-2xl max-md:pb-12 max-md:w-full max-md:px-3 max-sm:px-1" curr-hue-deg="0" is-rtl="<?php echo esc_attr(is_rtl()); ?>">

        <div class="w-full relative py-2 px-3 z-50 bg-[#ffffff78] bg-perish rounded-2xl mb-6 trans-base hover:shadow-lg">

            <div class="w-full flex justify-between items-center max-md:flex-col max-md:mb-6">

                <div main-logo class="w-fit cursor-pointer trans-fast flex flex-col items-center border-r-2 border-l-2 border-solid border-[#17c477] taily-anima-2 rounded-3xl hover:bg-[#f9f9f9] hover:-translate-y-2 max-md:mb-3">
                    <div class="w-fit px-1 flex justify-center items-center">
                        <img width="105" height="105" src="<?php echo esc_url($tmp_logo); ?>" alt="mylogo" class="w-20 taily-anima-4">
                        <span class="tracking-wide text-[17px] mx-2"><?php echo esc_html__('Your Logo', 'tailybymdz'); ?></span>
                    </div>

                    <small class="tracking-wide"><span><?php echo esc_html__('Your Slogan...', 'tailybymdz'); ?></span></small>
                </div>

                

                <div class="grow flex justify-end mx-2">
                    <ul class="w-full list-none flex justify-evenly items-center max-lg:justify-center max-md:flex-wrap">

                        <li class="trans-fast py-1 px-3 cursor-pointer hover:-translate-y-1 hover:scale-95 hover:bg-violet-500 hover:text-white hover:rounded-md"><?php echo esc_html__('Home', 'tailybymdz'); ?></li>

                        <li class="trans-fast py-1 px-3 cursor-pointer hover:-translate-y-1 hover:scale-95 hover:bg-violet-500 hover:text-white hover:rounded-md"><?php echo esc_html__('About', 'tailybymdz'); ?></li>

                        <li class="trans-fast py-1 px-3 cursor-pointer hover:-translate-y-1 hover:scale-95 hover:bg-violet-500 hover:text-white hover:rounded-md"><?php echo esc_html__('Products', 'tailybymdz'); ?></li>

                        <li class="trans-fast py-1 px-3 cursor-pointer hover:-translate-y-1 hover:scale-95 hover:bg-violet-500 hover:text-white hover:rounded-md"><?php echo esc_html__('Contact', 'tailybymdz'); ?></li>
                        <li class="fill-[#eaff04] trans-fast py-1 px-3 cursor-pointer hover:-translate-y-1 hover:scale-95 hover:bg-violet-500 hover:text-white hover:rounded-md hover:fill-white max-md:bg-slate-600 max-md:rounded-lg">
                            <div>
                                <i>
                                    <?php echo wp_kses($page_cions['search'], $allowed_tags1); ?>
                                </i>
                            </div>
                        </li>
                    </ul>
                </div>
                

            </div>

        </div><!--Endof Header-->

        <div class="w-full h-[80%] relative flex justify-center items-center z-50 my-4 py-6 max-md:flex-col">

            <div class="cart1-contents w-30perc bg-transparent rounded-2xl max-xl:px-2 max-xl:py-1 max-xl:w-50perc max-xl:text-center max-xl:bg-white max-lg:w-70perc max-lg:py-2 max-md:w-full max-md:bg-[#ffffffba]">

                <div class="w-full flex flex-col items-start">

                    <h2 class="text-[47px] text-center tracking-[10px] leading-[53px] text-slate-800 mb-2 max-md:tracking-[5px] trans-base py-3 px-1 rounded-xl border-4 border-dashed border-transparent hover:text-slate-600 hover:translate-x-2 hover:border-slate-200 max-md:text-[35px] max-sm:text-[23px]">
                        <?php echo esc_html__('Color of the Year -2024-', 'tailybymdz'); ?>
                    </h2>

                    <p class="text-justify">
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatem aut porro praesentium similique veniam?  dolor sit amet consectetur, Moles?
                    </p>

                    <div class="text-center w-full my-4 mb-1">
                        <h5 class="mb-2 text-xl"><?php echo esc_html__('Order Now 💬', 'tailybymdz'); ?></h5>
                        <a href="<?php echo esc_url($contact_us); ?>" target="_blank">
                            <button class="-taily-btn"><?php echo esc_html__('Contact Taily-Team', 'tailybymdz'); ?></button>
                        </a>
                    </div>

                </div>

            </div>

            <div class="w-70perc max-xl:w-50perc max-lg:w-30perc max-md:w-full">

            </div>

        </div><!--Endof Section-1-->

        <div>
            <div shape-1 class="absolute w-full -top-8 -left-6 h-full z-30">
                <img width="440" height="502" src="<?php echo esc_url($bg_shape); ?>" alt="someshape" class="w-50perc scale-105 max-sm:w-full">
            </div>

            <div shape-2 class="absolute w-80perc top-0 right-0 h-full z-20 taily-anima-1 max-md:w-90perc max-sm:w-full">
                <img width="576" height="502" src="<?php echo esc_url($bg_image); ?>" alt="someshape" class="w-full">
            </div>

            <div id="taily-footer-socials" class="w-fit py-1 px-4 absolute bottom-2 right-2 z-40 bg-[#ffffff78] bg-perish rounded-full trans-base hover:shadow-xl hover:-translate-y-1 max-sm:right-1/2 max-sm:translate-x-1/2" curr-hue-deg="0">

                <div class="w-full flex justify-around items-center">
                    <div class="max-w-[135px]">
                        <img width="300" height="75" src="<?php echo esc_url($social_cions); ?>" alt="socials" class="cursor-pointer">
                    </div>
                </div>

            </div><!--Endof Social-cions-->

        </div><!--Endof Absolute-Shapes-->

    </div><!--Endof Sec-1-->

    <div class="w-full mx-auto text-center flex flex-col items-center">
        <div class="w-fit mx-auto flex justify-center items-center max-sm:flex-col">
            <button title="<?php echo esc_html__('click to change', 'tailybymdz'); ?>" class="mb-2 button mx-2" onclick="taily_change_template_colortheme(1)"><?php echo esc_html__('Change Color', 'tailybymdz'); ?></button>
            
            <span class="mx-3"></span>

            <button title="<?php echo esc_html__('click to change', 'tailybymdz'); ?>" class="mb-2 button mx-2" onclick="taily_change_template_colortheme(1, 2)"><?php echo esc_html__('Randomize Color', 'tailybymdz'); ?></button>
        </div>
        
        <div class="mt-2"></div>
        <small><?php echo esc_html__('Using this button, you can change the color theme of the above template', 'tailybymdz'); ?></small>
    </div><!--Endof color-changer-->

    <hr class="w-full my-6">


    <div class="w-full flex flex-col items-center">

        <div go-right-onrtl class="mb-8 w-fit bg-white py-3 px-10 rounded-xl mr-auto trans-base hover:shadow-xl">
            <h4 class="text-2xl mb-1"><?php echo esc_html__('Need a custom design on Wordpress?', 'tailybymdz'); ?></h4>
            <small class="text-[18px]"><?php echo esc_html__('So stay in touch with us 👋', 'tailybymdz'); ?></small>
        </div>
        

        <div class="w-full flex justify-center items-center max-md:flex-col">
            <div class="w-1/2 z-20 max-md:w-full max-md:mb-5">
                <a href="<?php echo esc_url($example_1); ?>" target="_blank">
                    <img style="box-shadow: 38px 0px 10px -15px #1d3a5e33;" width="3000" height="2000" src="<?php echo esc_url($example_1); ?>" alt="image" class="rounded-xl trans-fast hover:scale-90">
                </a>
            </div>

            <div class="w-1/2 z-10 max-md:w-full">
                <a href="<?php echo esc_url($example_2); ?>" target="_blank">
                    <img width="1255" height="602" src="<?php echo esc_url($example_2); ?>" alt="image" class="rounded-xl trans-fast hover:scale-90">
                </a>
            </div>
        </div>

        <div class="w-full my-4 mt-6 flex flex-col items-center">

            <div go-right-onrtl class="w-fit bg-white py-3 px-10 rounded-xl mr-auto trans-base hover:shadow-xl">
                <h5 class="w-full text-[22px] mb-4"><?php echo esc_html__('Our Services < />', 'tailybymdz'); ?></h5>
                <ul style="list-style: inside;" class="w-full list-disc">
                    <li class="my-1"><?php echo esc_html__('Exclusive CMS design', 'tailybymdz'); ?></li>
                    <li class="my-1"><?php echo esc_html__('Exclusive PHP-API design', 'tailybymdz'); ?></li>
                    <li class="my-1"><?php echo esc_html__('Exclusive website design', 'tailybymdz'); ?></li>
                    <li class="my-1"><?php echo esc_html__('Wordpress website design', 'tailybymdz'); ?></li>
                    <li class="my-1"><?php echo esc_html__('Full Front-End design (Tailwind+React)', 'tailybymdz'); ?></li>
                    <li class="my-1"><?php echo esc_html__('Dedicated Plugin and Theme design (wordpress)', 'tailybymdz'); ?></li>
                    <li class="my-1"><?php echo esc_html__('Optimizing and increasing the professional speed of the site', 'tailybymdz'); ?></li>
                </ul>
            </div>

            <div class="my-3">
                <a href="<?php echo esc_url($contact_us); ?>" target="_blank">
                    <button class="-taily-btn"><?php echo esc_html__('Contact Taily-Team', 'tailybymdz'); ?></button>
                </a>
            </div>
            

        </div>

        

    </div><!--Endof Sec-2-->

    <div class="w-full my-4">
        <small class="text-[12px]"><?php echo esc_html__('-- Designed and Powered by Tailwind ©', 'tailybymdz'); ?></small>
    </div><!--Endof Tailwind-Trade-mark-->

</div>

<div>

    <div id="taily-settings-announcer" class="fixed w-fit max-w-[300px] py-3 px-10 rounded-xl bg-white shadow-lg top-12 <?php if(is_rtl()){echo 'left-4';}else{echo 'right-4';} ?> taily-announcer-hidden trans-fast">
        <span></span>
    </div>

</div><!--Endof page-popups-wrapper-->


<script>
var trans7 = "<?php echo esc_html__('Please Wait...', 'tailybymdz'); ?>";
var trans8 = "<?php echo esc_html__('Randomizing! 🥳', 'tailybymdz'); ?>";
</script>

<?php $this->add_js_file('admin', true, ['jquery']); ?>