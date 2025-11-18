<?php
// Enqueue parent and child theme styles
add_action('wp_enqueue_scripts', function() {
    // Load parent theme styles
    wp_enqueue_style(
        'twentytwentyfive-parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // Load child theme styles
    wp_enqueue_style(
        'twentytwentyfive-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['twentytwentyfive-parent-style']
    );

    wp_enqueue_script(
        'tailwindcdn',
        'https://cdn.tailwindcss.com',
        [],
        null,
        false
    );

    // Custom Tailwind config
    wp_add_inline_script('tailwindcdn', '
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: "#6C63FF",
                    }
                }
            }
        }
    ');
});
