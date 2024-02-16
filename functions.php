<?php

// Remove the original action
remove_action('neve_after_slot_component', ['HFG\Core\Builder\Footer', 'add_footer_component'], 10, 3);



//styles
function cargar_estilos_tema_hijo()
{
    wp_enqueue_style('style-css-child', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'cargar_estilos_tema_hijo');

// scripts
function cargar_scripts_personalizados()
{
    wp_register_script('custom-script', get_stylesheet_directory_uri() . '/custom-script.js', array('jquery'), '1.0', true);
    wp_enqueue_script('custom-script');
}
add_action('wp_enqueue_scripts', 'cargar_scripts_personalizados');

// add webp
function enable_upload_webp($mimes)
{
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'enable_upload_webp');

// head clean
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

//  jquery defer
function defer_parsing_of_js($url)
{
    if (is_user_logged_in())
        return $url; //don't break WP Admin
    if (FALSE === strpos($url, '.js'))
        return $url;
    if (strpos($url, 'jquery.min.js'))
        return $url;
    return str_replace(' src', ' defer src', $url);
}
add_filter('script_loader_tag', 'defer_parsing_of_js', 10);


// remove styles gutenberg
function remove_gutenberg_styles()
{
    // wp_dequeue_style('wp-block-library'); // Estilos de Gutenberg en el backend
    wp_dequeue_style('wp-block-library-theme'); // Estilos de Gutenberg en el frontend
    wp_dequeue_style('wc-block-style'); // Estilos de WooCommerce para Gutenberg
    wp_dequeue_style('classic-theme-styles');
}

add_action('wp_enqueue_scripts', 'remove_gutenberg_styles', 100);


// remove emojis
function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    // Remove from TinyMCE
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}
add_action('init', 'disable_emojis');
function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}