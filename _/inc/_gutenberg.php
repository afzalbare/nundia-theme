<?php
/**
 * Gutenberg ACF custom blocks & ACF Options
 * Mirrors blessingChild/_/inc/_gutenberg.php
 */

// Colour palette
function nundia_gutenberg_color_palette()
{
    add_theme_support(
        'editor-color-palette',
        array(
            array(
                'name'  => esc_html__('Primary Red', 'nundia'),
                'slug'  => 'primary',
                'color' => '#8B1B1B',
            ),
            array(
                'name'  => esc_html__('Dark', 'nundia'),
                'slug'  => 'dark',
                'color' => '#1A1A1A',
            ),
            array(
                'name'  => esc_html__('White', 'nundia'),
                'slug'  => 'white',
                'color' => '#FFFFFF',
            ),
        )
    );
}
add_action('after_setup_theme', 'nundia_gutenberg_color_palette');

// Allowed block types filter
add_filter('allowed_block_types_all', 'nundia_allowed_block_types_all', 10, 2);
function nundia_allowed_block_types_all($allowed_blocks, $post)
{
    $allowed_blocks = array(
        'core/paragraph',
        'core/image',
        'core/heading',
        'core/list',
        'core/list-item',
        'core/quote',
        'core/file',
        'core/video',
        'core/table',
        'core/code',
        'core/separator',
        'core/spacer',
        'core/embed',
        'core-embed/youtube',
        'core-embed/vimeo',
        'core/block',
        'core/template',

        // Custom ACF blocks
        'acf/accordion-block',
        'acf/services-block',
        'acf/our-speciality-block',
        'acf/full-image-block',
        'acf/features-block',
        'acf/contact-block',
        'acf/promo-block',
        'acf/team-block',
        'acf/courses-block',
        'acf/counter-block',
        'acf/testimonials-block',
        'acf/gallery-block',
        'acf/text-media-block',
        'acf/team-grid-block',
        'acf/gallery-grid-block',
    );
    return $allowed_blocks;
}

// ACF custom block loader
function acf_load_blocks()
{
    $blocks = get_blocks();
    foreach ($blocks as $block) {
        if (file_exists(get_template_directory() . '/blocks/' . $block . '/block.json')) {
            register_block_type(get_template_directory() . '/blocks/' . $block . '/block.json');
        }
    }
}
add_action('init', 'acf_load_blocks', 5);

function get_blocks()
{
    $dir = get_template_directory() . '/blocks/';
    if (!is_dir($dir)) return array();
    $blocks = scandir($dir);
    $blocks = array_values(array_diff($blocks, array('..', '.', '.DS_Store', '_base-block')));
    return $blocks;
}

// ACF Options Page (exact slug 'site-settings' as blessingChild)
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Site Settings',
        'menu_title' => 'Site Settings',
        'menu_slug'  => 'site-settings',
        'capability' => 'edit_posts',
        'redirect'   => false,
        'icon_url'   => 'dashicons-admin-generic',
        'position'   => 3,
    ));
}

// Top Admin Bar link for Site Settings
add_action('admin_bar_menu', function ($wp_admin_bar) {
    if (!current_user_can('edit_posts')) return;
    $wp_admin_bar->add_node(array(
        'id'    => 'nd_site_settings',
        'title' => '⚙️ Site Settings',
        'href'  => admin_url('admin.php?page=site-settings'),
    ));
}, 80);

// Template redirect fallback for frontend /site-settings/
add_action('template_redirect', function () {
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    if ($path === 'site-settings' || $path === 'site-setting') {
        wp_redirect(admin_url('admin.php?page=site-settings'));
        exit;
    }
});
