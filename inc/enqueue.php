<?php

/**
 * Enqueue child theme styles and scripts.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
if (!function_exists('justg_child_enqueue_parent_style')) {
    function justg_child_enqueue_parent_style()
    {
        // Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
        $parenthandle = 'parent-style';
        $theme = wp_get_theme();

        // Load the stylesheet
        wp_enqueue_style(
            $parenthandle,
            get_template_directory_uri() . '/style.css',
            array(),  // if the parent theme code has a dependency, copy it to here
            $theme->parent()->get('Version')
        );

        wp_enqueue_style(
            'velocity-sekolah4-inter',
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
            array(),
            null
        );

        // $css_version = $theme->parent()->get('Version') . '.' . filemtime( get_stylesheet_directory() . '/css/custom.css' );
        $css_version = $theme->get('Version') . '.' . filemtime(get_stylesheet_directory() . '/css/custom.css');
        wp_enqueue_style(
            'custom-style',
            get_stylesheet_directory_uri() . '/css/custom.css',
            array($parenthandle, 'velocity-sekolah4-inter'),
            $css_version
        );

        wp_enqueue_style(
            'child-style',
            get_stylesheet_uri(),
            array($parenthandle),
            $theme->get('Version')
        );

        $js_version = $theme->parent()->get('Version') . '.' . filemtime(get_stylesheet_directory() . '/js/custom.js');
        wp_enqueue_script('justg-custom-scripts', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), $js_version, true);
    }
}
