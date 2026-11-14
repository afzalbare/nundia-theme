<?php
// =================================================================
// ====== Custom admin + menus (Mirrors blessingChild/_/inc/_custom-wpadmin.php)
// =================================================================

/* Theme support for menus */
add_theme_support('menus');
add_post_type_support('page', 'excerpt');
add_theme_support('title-tag');
add_theme_support('post-thumbnails');

// Remove width and height attributes from images via WYSIWYG/admin
add_filter('post_thumbnail_html', 'remove_width_attribute', 10);
add_filter('image_send_to_editor', 'remove_width_attribute', 10);

function remove_width_attribute($html)
{
    $html = preg_replace('/(width|height)="\d*"\s/', "", $html);
    return $html;
}

// Image link default = none
update_option('image_default_link_type', 'none');

// Logo link
function wpc_url_login()
{
    return "/";
}
add_filter('login_headerurl', 'wpc_url_login');

// Admin login logo
function my_login_logo()
{ ?>
    <style type="text/css">
        #login h1 a,
        .login h1 a {
            background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/_/images/logo.png');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            height: 80px;
            width: 250px;
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'my_login_logo');

/**
 * Custom admin login header link alt text
 */
function custom_login_title()
{
    return get_option('blogname');
}
add_filter('login_headertext', 'custom_login_title');

/**
 * Top-level Navigation menu shortcut
 */
function new_nav_menu()
{
    add_menu_page(__('Navigation', 'nav-menus'), __('Navigation', 'nav-menus'), 'edit_themes', 'nav-menus.php', '', 'dashicons-menu', 60);
}
add_action('admin_menu', 'new_nav_menu');

// Footer admin text
function remove_footer_admin()
{
    echo '<p>' . esc_html(get_bloginfo("name")) . '</p>';
}
add_filter('admin_footer_text', 'remove_footer_admin');

/*
    Disable Default Dashboard Widgets
*/
function disable_default_dashboard_widgets()
{
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
}
add_action('wp_dashboard_setup', 'disable_default_dashboard_widgets', 999);

// Welcome to Wordpress widget
remove_action('welcome_panel', 'wp_welcome_panel');

// Dashboard widget
function add_dashboard_widget_function()
{
    echo "<p style='text-align:center;font-size: 18px;text-transform:uppercase;'>";
    echo esc_html(get_bloginfo('name'));
    echo "</p>";
}

function wpc_add_dashboard_widgets()
{
    wp_add_dashboard_widget('wp_dashboard_widget', '&nbsp;', 'add_dashboard_widget_function');
}
add_action('wp_dashboard_setup', 'wpc_add_dashboard_widgets');

// Admin bar cleanup
function remove_admin_bar_links()
{
    global $wp_admin_bar;
    if (!$wp_admin_bar) return;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('about');
    $wp_admin_bar->remove_menu('wporg');
    $wp_admin_bar->remove_menu('documentation');
    $wp_admin_bar->remove_menu('support-forums');
    $wp_admin_bar->remove_menu('feedback');
    $wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'remove_admin_bar_links');

// Clean columns
function my_manage_columns($columns)
{
    unset($columns['comments']);
    return $columns;
}
function my_column_init()
{
    add_filter('manage_posts_columns', 'my_manage_columns');
    add_filter('manage_pages_columns', 'my_manage_columns');
}
add_action('admin_init', 'my_column_init');
