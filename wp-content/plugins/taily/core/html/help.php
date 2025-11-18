<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Check Dev-mode
$this->proccess_tail_admin();
$this->add_admin_cust_css();

$is_plg_dis = taily_get_all_settings('taily_settings', 'taily-full-dis');

//URLs
$bg_header = TAILYBYMDZ_URL.'assets/img/bg-head.webp';
$tailwind_base = 'https://tailwindcss.com/';
$tailwind_docs = 'https://tailwindcss.com/docs/';
$taily_documents = '#';
$our_email = 'mdesign.fa@gmail.com';
$our_tel = 'https://t.me/g_mdz';
$settings_page = esc_url(get_admin_url() . 'admin.php?page=tailysettings_main');
/***/
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

<div class="-taily-cos relative w-full my-8 z-30" is-rtl="<?php echo esc_attr(is_rtl()); ?>">
    <div class="w-80perc mx-auto bg-[#ffffff80] bg-perish py-3 px-10 rounded-xl flex flex-col items-center trans-base hover:shadow-xl">

        <h2 class="w-full text-xl mb-6"><?php echo esc_html__('Need any Help? 🤝', 'tailybymdz'); ?></h2>

        <ul class="w-full list-disc text-[14px]">
            <li>
                <span><a href="<?php echo esc_url($tailwind_base); ?>" target="_blank"><?php echo esc_html__('Visit Tailwinds Official Website', 'tailybymdz'); ?></a></span>
            </li>

            <li>
                <span><a href="<?php echo esc_url($tailwind_docs); ?>" target="_blank"><?php echo esc_html__('Tailwinds Documents', 'tailybymdz'); ?></a></span>
            </li>

            <li>
                <span><a href="<?php echo esc_url($taily_documents); ?>" target="_blank"><?php echo esc_html__('Taily Documents (Soon...)', 'tailybymdz'); ?></a></span>
            </li>
        </ul>

        <hr class="w-full my-3">

        <h4 class="w-full mb-6 text-[17px]"><?php echo esc_html__('Contact us', 'tailybymdz'); ?></h4>

        <ul class="w-full list-disc text-[14px]">
            <li>
                <div>
                    <span><?php echo esc_html__('Email:', 'tailybymdz'); ?></span>
                    <span class="mx-1"><a href="<?php echo esc_url('mailto:'.$our_email); ?>" target="_blank"><?php echo esc_html($our_email); ?></a></span>
                </div>
            </li>

            <li>
                <div>
                    <span><?php echo esc_html__('Official Telegram:', 'tailybymdz'); ?></span>
                    <span class="mx-1"><a href="<?php echo esc_url($our_tel); ?>" target="_blank">@g_mdz</a></span>
                </div>
            </li>
        </ul>

        <hr class="w-full my-3">

        <h4 class="w-full mb-2 text-[15px]"><?php echo esc_html__('--Database', 'tailybymdz'); ?></h4>
        <h6 class="w-full mb-6"><?php echo esc_html__('The information that Taily plugin adds to your sites database and you should know them', 'tailybymdz'); ?></h6>

        <p class="w-full mb-3">
            <?php echo esc_html__('-Post-Meta table (wp_postmeta)', 'tailybymdz'); ?>
        </p>
        <ul class="w-full list-disc">
            <li>tailybymdz_thisp_mode</li>
            <li>taily_productivecss_gen</li>
            <li>taily_has_productivecss</li>
            <li>taily_productivecss</li>
            <li>taily_cssfile_url</li>
            <li>taily_thisp_tmpver</li>
        </ul>

        <hr go-right-onrtl class="my-6 w-30perc mr-auto">

        <p class="w-full mb-3">
            <?php echo esc_html__('-Options table (wp_options)', 'tailybymdz'); ?>
        </p>
        <span class="w-full"><?php echo esc_html__('In general, any option with taily name prefix is ​​like: taily_settings', 'tailybymdz'); ?></span>

        <hr go-right-onrtl class="my-6 w-30perc mr-auto">

        <span>
            <?php echo esc_html__('Note: To clear all this information when deleting the plugin, go to the plugin settings page and activate the `Clearing while Uninstall` option in the plugin activity section.', 'tailybymdz'); ?>
        </span>

        <div class="my-3">
            <a class="button" href="<?php echo esc_url($settings_page); ?>"><?php echo esc_html__('Go to Settings', 'tailybymdz'); ?></a>
        </div>

    </div>
</div>

<div class="w-full my-4">
    <small class="text-[12px]"><?php echo esc_html__('-- Designed and Powered by Tailwind ©', 'tailybymdz'); ?></small>
</div><!--Endof Tailwind-Trade-mark-->