<?php
/**
 * tnagri child theme functions
 */

function tnagri_enqueue_styles() {
    wp_enqueue_style(
        'tnagri-style',
        get_stylesheet_directory_uri() . '/build/styles.css',
        array(),
        filemtime( get_stylesheet_directory() . '/build/styles.css' )
    );
}
add_action('wp_enqueue_scripts', 'tnagri_enqueue_styles');
