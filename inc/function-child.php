<?php
/** Child theme features and template helpers. */
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('velocity_sekolah4_customize_register')) {
    function velocity_sekolah4_customize_register($wp_customize)
    {
        $wp_customize->remove_section('header_image');

        $wp_customize->add_section('velocity_sekolah4_header_options', array(
            'title'       => wp_get_theme()->get('Name'),
            'description' => __('Pengaturan foto untuk komposisi header.', 'justg'),
            'priority'    => 30,
        ));
        $photos = array(
            'velocity_sekolah4_header_photo_1' => array(__('Foto Header Kiri', 'justg'), content_url('/uploads/2017/03/mahsiswa.jpg')),
            'velocity_sekolah4_header_photo_2' => array(__('Foto Header Tengah', 'justg'), content_url('/uploads/2017/03/Spaces_workshop.jpg')),
            'velocity_sekolah4_header_photo_3' => array(__('Foto Header Kanan', 'justg'), content_url('/uploads/2015/06/sma-velocity-developer.jpg')),
        );
        foreach ($photos as $setting => $photo) {
            $wp_customize->add_setting($setting, array(
                'default'           => $photo[1],
                'sanitize_callback' => 'esc_url_raw',
            ));
            if (class_exists('WP_Customize_Image_Control')) {
                $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $setting, array(
                    'label'   => $photo[0],
                    'section' => 'velocity_sekolah4_header_options',
                )));
            }
        }
    }
}
add_action('customize_register', 'velocity_sekolah4_customize_register', 20);

if (!function_exists('velocity_sekolah4_get_header_photos')) {
    function velocity_sekolah4_get_header_photos()
    {
        $defaults = array(
            content_url('/uploads/2017/03/mahsiswa.jpg'),
            content_url('/uploads/2017/03/Spaces_workshop.jpg'),
            content_url('/uploads/2015/06/sma-velocity-developer.jpg'),
        );
        return array(
            get_theme_mod('velocity_sekolah4_header_photo_1', $defaults[0]),
            get_theme_mod('velocity_sekolah4_header_photo_2', $defaults[1]),
            get_theme_mod('velocity_sekolah4_header_photo_3', $defaults[2]),
        );
    }
}

if (!function_exists('velocity_sekolah4_theme_setup')) {
    function velocity_sekolah4_theme_setup()
    {
        add_action('wp_enqueue_scripts', 'justg_child_enqueue_parent_style', 20);
        remove_action('justg_header', 'justg_header_menu');
        remove_action('justg_do_footer', 'justg_the_footer_open');
        remove_action('justg_do_footer', 'justg_the_footer_content');
        remove_action('justg_do_footer', 'justg_the_footer_close');
        remove_theme_support('widgets-block-editor');
    }
}
add_action('after_setup_theme', 'velocity_sekolah4_theme_setup', 9);

if (!function_exists('velocity_sekolah4_remove_archive_breadcrumb')) {
    function velocity_sekolah4_remove_archive_breadcrumb()
    {
        if (!is_single()) {
            remove_action('justg_before_title', 'justg_breadcrumb');
        }
    }
}
add_action('wp_head', 'velocity_sekolah4_remove_archive_breadcrumb');

if (!function_exists('justg_header_open')) {
    function justg_header_open()
    {
        echo '<header id="wrapper-header"><div id="wrapper-navbar" class="container px-2 px-md-0" itemscope itemtype="https://schema.org/WebSite">';
    }
}
if (!function_exists('justg_header_close')) {
    function justg_header_close()
    {
        echo '</div></header>';
    }
}
if (!function_exists('velocity_sekolah4_header')) {
    function velocity_sekolah4_header()
    {
        require get_stylesheet_directory() . '/inc/part-header.php';
    }
}
add_action('justg_header', 'velocity_sekolah4_header');

if (!function_exists('velocity_sekolah4_footer')) {
    function velocity_sekolah4_footer()
    {
        require get_stylesheet_directory() . '/inc/part-footer.php';
    }
}
add_action('justg_do_footer', 'velocity_sekolah4_footer');

if (!function_exists('velocity_sekolah4_before_wrapper_content')) {
    function velocity_sekolah4_before_wrapper_content()
    {
        echo '<div class="px-2"><div class="card rounded-0 border-light border-top-0 border-bottom-0 shadow px-0 px-md-2 container">';
    }
}
add_action('justg_before_wrapper_content', 'velocity_sekolah4_before_wrapper_content');

if (!function_exists('velocity_sekolah4_after_wrapper_content')) {
    function velocity_sekolah4_after_wrapper_content()
    {
        echo '</div></div>';
    }
}
add_action('justg_after_wrapper_content', 'velocity_sekolah4_after_wrapper_content');

/** Get the featured image, first content image, or bundled fallback. */
if (!function_exists('velocity_sekolah4_get_post_image')) {
    function velocity_sekolah4_get_post_image($post_id = 0, $size = 'large')
    {
        $post_id = $post_id ? absint($post_id) : get_the_ID();
        $thumbnail_id = get_post_thumbnail_id($post_id);
        if ($thumbnail_id) {
            $image = wp_get_attachment_image_url($thumbnail_id, $size);
            if ($image) {
                return $image;
            }
        }

        $content = (string) get_post_field('post_content', $post_id);
        if (function_exists('has_blocks') && function_exists('parse_blocks') && has_blocks($content)) {
            foreach (parse_blocks($content) as $block) {
                if ('core/image' === $block['blockName'] && !empty($block['attrs']['id'])) {
                    $image = wp_get_attachment_image_url(absint($block['attrs']['id']), $size);
                    if ($image) {
                        return $image;
                    }
                }
            }
        }
        if (preg_match('/<img[^>]+src=[\'\"]([^\'\"]+)[\'\"]/i', $content, $match)) {
            return esc_url_raw($match[1]);
        }
        return get_stylesheet_directory_uri() . '/img/no-image.webp';
    }
}

/** Render a linked Bootstrap 5 ratio thumbnail. */
if (!function_exists('velocity_sekolah4_post_thumbnail')) {
    function velocity_sekolah4_post_thumbnail($post_id = 0, $ratio = 'ratio-4x3', $class = '')
    {
        $post_id = $post_id ? absint($post_id) : get_the_ID();
        printf(
            '<a class="ratio %1$s overflow-hidden bg-light %2$s" href="%3$s" aria-label="%4$s"><img class="w-100 h-100 object-fit-cover" src="%5$s" alt="%6$s" loading="lazy"></a>',
            esc_attr(sanitize_html_class($ratio)),
            esc_attr($class),
            esc_url(get_permalink($post_id)),
            esc_attr(sprintf(__('Baca %s', 'justg'), get_the_title($post_id))),
            esc_url(velocity_sekolah4_get_post_image($post_id, 'medium_large')),
            esc_attr(get_the_title($post_id))
        );
    }
}

if (!function_exists('velocity_sekolah4_widgets_init')) {
    function velocity_sekolah4_widgets_init()
    {
        foreach (range(1, 4) as $footer_widget) {
            unregister_sidebar('footer-widget-' . $footer_widget);
        }

        register_sidebar(array(
            'name'          => __('Main Sidebar', 'justg'),
            'id'            => 'main-sidebar',
            'description'   => __('Main sidebar widget area', 'justg'),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title position-relative"><span class="widget-title-icon" aria-hidden="true"><svg viewBox="0 0 16 16" width="16" height="16" fill="currentColor"><path d="M4.5 2a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7zm-2 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11zm2 3a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7zm-2 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11z"/></svg></span><span class="vd-title bg-gradient">',
            'after_title'   => '</span></h3>',
            'show_in_rest'  => false,
        ));
    }
}
add_action('widgets_init', 'velocity_sekolah4_widgets_init', 20);

if (!function_exists('velocity_sekolah4_bootstrap5_menu_attributes')) {
    function velocity_sekolah4_bootstrap5_menu_attributes($atts)
    {
        unset($atts['data-toggle']);
        return $atts;
    }
}
add_filter('nav_menu_link_attributes', 'velocity_sekolah4_bootstrap5_menu_attributes', 20);
