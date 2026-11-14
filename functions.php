<?php
/**
 * NUNDÌA – Sapori di Sicilia · Theme functions
 * Follows the blessingChild theme architecture.
 */
if (!defined('ABSPATH')) exit;

define('NUNDIA_VERSION', '2.0.0');

// Gutenberg ACF custom blocks & Options
require(get_template_directory() . '/_/inc/_gutenberg.php');

// Navigation menus
require(get_template_directory() . '/_/inc/_register-navs.php');

// Custom admin & menus
require(get_template_directory() . '/_/inc/_custom-wpadmin.php');

// 404 ACF options
require(get_template_directory() . '/_/inc/_404-acf.php');

// Disable comments
require(get_template_directory() . '/_/inc/_disable-comment.php');

// Custom image sizes
require(get_template_directory() . '/_/inc/_image-sizes.php');

// Icons, components and ACF fields
require(get_template_directory() . '/_/inc/_icons.php');
require(get_template_directory() . '/_/inc/_components.php');
require(get_template_directory() . '/_/inc/_fields.php');

/**
 * ksa helper function (from blessingChild)
 */
function ksa($cont)
{
    echo wp_kses($cont, '');
}

/* ------------------------------------------------------------------ *
 * Theme Setup & WooCommerce Support
 * ------------------------------------------------------------------ */
function nundia_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style'));
}
add_action('after_setup_theme', 'nundia_theme_setup');

/* ------------------------------------------------------------------ *
 * Styles & Scripts (Maintains 100% exact design & typography)
 * ------------------------------------------------------------------ */
function nundia_enqueue_scripts() {
    $dir = get_template_directory();
    $uri = get_template_directory_uri();

    // Arabic / Japanese fonts (Latin text uses Arial or Calibri – client request).
    wp_enqueue_style('nundia-google-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500&family=Noto+Sans+JP:wght@300;400;500&display=swap', array(), null);
    
    // Core CSS (Tailwind CSS compiled + Custom styles)
    wp_enqueue_style('nundia-style', $uri . '/_/css/nundia.min.css', array(), filemtime($dir . '/_/css/nundia.min.css'));
    wp_enqueue_style('nundia-custom-css', $uri . '/_/css/custom.css', array('nundia-style'), filemtime($dir . '/_/css/custom.css'));
    wp_add_inline_style('nundia-custom-css', ':root{--font-family:' . nundia_font_stack(nd_opt('site_font')) . ';}');

    // Scripts
    wp_enqueue_script('nundia-main-js', $uri . '/_/js/main.js', array(), filemtime($dir . '/_/js/main.js'), true);
    wp_localize_script('nundia-main-js', 'nundia_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('nundia_nonce'),
        'cart_url' => nundia_cart_url(),
        'lang'     => nundia_current_lang(),
    ));
}
add_action('wp_enqueue_scripts', 'nundia_enqueue_scripts', 20);

/** Remove core/WooCommerce front-end CSS that would override theme styles */
function nundia_dequeue_foreign_styles() {
    if (is_admin()) return;
    foreach (array('wp-block-library', 'wp-block-library-theme', 'global-styles', 'classic-theme-styles', 'wc-blocks-style', 'wc-blocks-vendors-style', 'core-block-supports') as $h) {
        wp_dequeue_style($h);
    }
    $keep_wc = function_exists('is_account_page') && is_account_page();
    if (!$keep_wc) {
        foreach (array('woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen', 'woocommerce-inline', 'brands-styles') as $h) wp_dequeue_style($h);
    }
}
add_action('wp_enqueue_scripts', 'nundia_dequeue_foreign_styles', 100);
add_action('wp_footer', function () { wp_dequeue_style('core-block-supports'); }, 1);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
add_filter('woocommerce_enqueue_styles', function ($styles) {
    return (function_exists('is_account_page') && is_account_page()) ? $styles : array();
});

/* ------------------------------------------------------------------ *
 * Content Types: Recipe & FAQ
 * ------------------------------------------------------------------ */
function nundia_register_post_types() {
    register_post_type('recipe', array(
        'labels' => array(
            'name' => 'Ricette', 'singular_name' => 'Ricetta', 'add_new' => 'Aggiungi ricetta',
            'add_new_item' => 'Aggiungi nuova ricetta', 'edit_item' => 'Modifica ricetta', 'all_items' => 'Tutte le ricette',
        ),
        'public' => true,
        'has_archive' => 'ricette',
        'rewrite' => array('slug' => 'ricette', 'with_front' => false),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-food',
        'show_in_rest' => true,
    ));
    register_taxonomy('recipe_category', 'recipe', array(
        'labels' => array('name' => 'Categorie ricetta', 'singular_name' => 'Categoria ricetta'),
        'hierarchical' => true,
        'rewrite' => array('slug' => 'categoria-ricette', 'with_front' => false),
        'show_in_rest' => true,
        'show_admin_column' => true,
    ));

    register_post_type('faq', array(
        'labels' => array(
            'name' => 'FAQ', 'singular_name' => 'FAQ', 'add_new' => 'Aggiungi FAQ',
            'add_new_item' => 'Aggiungi nuova FAQ', 'edit_item' => 'Modifica FAQ', 'all_items' => 'Tutte le FAQ',
        ),
        'public' => true,
        'publicly_queryable' => false,
        'has_archive' => false,
        'rewrite' => false,
        'supports' => array('title', 'editor', 'page-attributes'),
        'menu_icon' => 'dashicons-editor-help',
        'show_in_rest' => true,
    ));
    register_taxonomy('faq_category', 'faq', array(
        'labels' => array('name' => 'Categorie FAQ', 'singular_name' => 'Categoria FAQ'),
        'hierarchical' => true,
        'rewrite' => false,
        'show_in_rest' => true,
        'show_admin_column' => true,
    ));
}
add_action('init', 'nundia_register_post_types');

/** Journal posts live under /journal/{slug}/ */
function nundia_post_permalink($permalink, $post) {
    if ($post->post_type === 'post' && get_option('permalink_structure')) {
        return home_url('/journal/' . $post->post_name . '/');
    }
    return $permalink;
}
add_filter('post_link', 'nundia_post_permalink', 10, 2);
add_action('init', function () {
    add_rewrite_rule('^journal/([^/]+)/?$', 'index.php?name=$matches[1]', 'top');
});

/** Sensible archive sorting */
add_action('pre_get_posts', function ($q) {
    if (is_admin() || !$q->is_main_query()) return;
    if ($q->is_post_type_archive('recipe')) $q->set('posts_per_page', -1);
    if ($q->is_home()) $q->set('posts_per_page', -1);
    if ($q->is_post_type_archive('product') || $q->is_tax('product_cat')) $q->set('posts_per_page', -1);
});

/* ------------------------------------------------------------------ *
 * WooCommerce Integration
 * ------------------------------------------------------------------ */
function nundia_cart_url() {
    return function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/carrello/');
}
function nundia_cart_count() {
    return (function_exists('WC') && WC()->cart) ? (int) WC()->cart->get_cart_contents_count() : 0;
}

/** Theme's custom cart & checkout templates */
add_filter('template_include', function ($template) {
    if (!function_exists('is_cart')) return $template;
    if (is_cart()) return get_template_directory() . '/woocommerce-cart.php';
    if (is_checkout()) return get_template_directory() . '/woocommerce-checkout.php';
    return $template;
}, 99);

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

function nundia_cart_payload($extra = array()) {
    return array_merge(array(
        'count'    => nundia_cart_count(),
        'subtotal' => (function_exists('WC') && WC()->cart) ? nundia_price(WC()->cart->get_subtotal()) : nundia_price(0),
    ), $extra);
}

function nundia_ajax_add_to_cart() {
    check_ajax_referer('nundia_nonce', 'nonce');
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? max(1, absint($_POST['quantity'])) : 1;
    if ($product_id && function_exists('WC')) {
        $added = WC()->cart->add_to_cart($product_id, $quantity);
        if ($added) wp_send_json_success(nundia_cart_payload(array('message' => 'Aggiunto al carrello')));
    }
    wp_send_json_error(array('message' => 'Impossibile aggiungere al carrello.'));
}
add_action('wp_ajax_nundia_add_to_cart', 'nundia_ajax_add_to_cart');
add_action('wp_ajax_nopriv_nundia_add_to_cart', 'nundia_ajax_add_to_cart');

function nundia_ajax_update_cart_item() {
    check_ajax_referer('nundia_nonce', 'nonce');
    $key = isset($_POST['cart_item_key']) ? sanitize_text_field(wp_unslash($_POST['cart_item_key'])) : '';
    $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    if ($key && function_exists('WC')) {
        if ($qty <= 0) WC()->cart->remove_cart_item($key);
        else WC()->cart->set_quantity($key, $qty);
        wp_send_json_success(nundia_cart_payload());
    }
    wp_send_json_error();
}
add_action('wp_ajax_nundia_update_cart_item', 'nundia_ajax_update_cart_item');
add_action('wp_ajax_nopriv_nundia_update_cart_item', 'nundia_ajax_update_cart_item');

/** Checkout: create a WooCommerce order (bank transfer) */
function nundia_handle_checkout() {
    if (empty($_POST['nundia_checkout']) || !function_exists('WC')) return;
    if (!isset($_POST['_nd_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_nd_nonce'])), 'nundia_checkout')) {
        wc_add_notice('Sessione scaduta: riprova.', 'error');
        return;
    }
    $cart = WC()->cart;
    if (!$cart || $cart->is_empty()) return;

    $f = function ($k) { return isset($_POST[$k]) ? sanitize_text_field(wp_unslash($_POST[$k])) : ''; };
    $name = $f('nd_name');
    $email = isset($_POST['nd_email']) ? sanitize_email(wp_unslash($_POST['nd_email'])) : '';
    if (!$name || !is_email($email) || !$f('nd_address') || !$f('nd_city')) {
        wc_add_notice('Compila i campi obbligatori.', 'error');
        return;
    }
    $parts = explode(' ', $name, 2);
    $address = array(
        'first_name' => $parts[0],
        'last_name'  => isset($parts[1]) ? $parts[1] : '',
        'email'      => $email,
        'phone'      => $f('nd_phone'),
        'address_1'  => $f('nd_address'),
        'city'       => $f('nd_city'),
        'postcode'   => $f('nd_postcode'),
        'country'    => 'IT',
    );
    $country_label = $f('nd_country');

    $order = wc_create_order(array('customer_id' => get_current_user_id()));
    foreach ($cart->get_cart() as $item) {
        $order->add_product($item['data'], $item['quantity']);
    }
    $order->set_address($address, 'billing');
    $order->set_address($address, 'shipping');
    $order->set_payment_method('bacs');
    $order->set_payment_method_title('Bonifico bancario');
    $note = isset($_POST['nd_message']) ? sanitize_textarea_field(wp_unslash($_POST['nd_message'])) : '';
    if ($country_label) $note = trim('Paese: ' . $country_label . "\n" . $note);
    if ($note) $order->set_customer_note($note);
    $order->calculate_totals();
    $order->update_status('on-hold', 'Ordine dal sito: in attesa del bonifico.');
    $cart->empty_cart();

    wp_safe_redirect($order->get_checkout_order_received_url());
    exit;
}
add_action('template_redirect', 'nundia_handle_checkout');

/* ------------------------------------------------------------------ *
 * Forms: B2B, Contact, Newsletter
 * ------------------------------------------------------------------ */
function nundia_post_field($k, $textarea = false) {
    if (!isset($_POST[$k])) return '';
    $v = wp_unslash($_POST[$k]);
    return $textarea ? sanitize_textarea_field($v) : sanitize_text_field($v);
}

function nundia_ajax_b2b_handler() {
    check_ajax_referer('nundia_nonce', 'nonce');
    $email = sanitize_email(nundia_post_field('email'));
    $company = nundia_post_field('company');
    $name = nundia_post_field('name');
    if (!$company || !$name || !is_email($email)) {
        wp_send_json_error(array('message' => 'Compila i campi obbligatori.'));
    }
    $lines = array(
        'Azienda' => $company, 'Nome' => $name, 'Email' => $email, 'Telefono' => nundia_post_field('phone'),
        'Paese' => nundia_post_field('country'), 'Tipo di attività' => nundia_post_field('business_type'),
        'Prodotti di interesse' => nundia_post_field('products'), 'Volume stimato' => nundia_post_field('volume'),
        'Messaggio' => nundia_post_field('message', true),
    );
    $body = '';
    foreach ($lines as $k => $v) $body .= $k . ': ' . $v . "\n";
    wp_mail(nd_opt('email_orders'), '[NUNDÌA B2B] Richiesta da ' . $company, $body, array('Reply-To: ' . $name . ' <' . $email . '>'));
    wp_send_json_success(array('message' => 'Grazie. Abbiamo ricevuto la richiesta e ti risponderemo a breve.'));
}
add_action('wp_ajax_nundia_ajax_b2b', 'nundia_ajax_b2b_handler');
add_action('wp_ajax_nopriv_nundia_ajax_b2b', 'nundia_ajax_b2b_handler');

function nundia_ajax_contact_handler() {
    check_ajax_referer('nundia_nonce', 'nonce');
    $name = nundia_post_field('name');
    $email = sanitize_email(nundia_post_field('email'));
    $message = nundia_post_field('message', true);
    if (!$name || !$message || !is_email($email)) {
        wp_send_json_error(array('message' => 'Compila i campi obbligatori.'));
    }
    $body = "Nome: {$name}\nEmail: {$email}\nTelefono: " . nundia_post_field('phone') . "\nOggetto: " . nundia_post_field('subject') . "\n\n{$message}";
    wp_mail(nd_opt('email_general'), '[NUNDÌA Contatti] ' . (nundia_post_field('subject') ?: 'Messaggio da ' . $name), $body, array('Reply-To: ' . $name . ' <' . $email . '>'));
    wp_send_json_success(array('message' => 'Grazie. Ti risponderemo al più presto.'));
}
add_action('wp_ajax_nundia_ajax_contact', 'nundia_ajax_contact_handler');
add_action('wp_ajax_nopriv_nundia_ajax_contact', 'nundia_ajax_contact_handler');

function nundia_ajax_newsletter_handler() {
    check_ajax_referer('nundia_nonce', 'nonce');
    $email = sanitize_email(nundia_post_field('email'));
    if (!is_email($email)) wp_send_json_error(array('message' => 'Indirizzo email non valido.'));
    $list = get_option('nundia_newsletter', array());
    if (!in_array($email, $list, true)) {
        $list[] = $email;
        update_option('nundia_newsletter', $list, false);
    }
    wp_send_json_success(array('message' => 'Grazie, iscrizione registrata.'));
}
add_action('wp_ajax_nundia_ajax_newsletter', 'nundia_ajax_newsletter_handler');
add_action('wp_ajax_nopriv_nundia_ajax_newsletter', 'nundia_ajax_newsletter_handler');

// Newsletter admin menu under Tools
add_action('admin_menu', function () {
    add_submenu_page('tools.php', 'Iscritti newsletter', 'Newsletter NUNDÌA', 'edit_posts', 'nundia-newsletter', function () {
        $list = get_option('nundia_newsletter', array());
        echo '<div class="wrap"><h1>Iscritti newsletter (' . count($list) . ')</h1><textarea readonly rows="20" style="width:100%;max-width:640px">' . esc_textarea(implode("\n", $list)) . '</textarea></div>';
    });
}, 20);

/* ------------------------------------------------------------------ *
 * Misc
 * ------------------------------------------------------------------ */
add_filter('body_class', function ($c) {
    $c[] = 'nundia';
    return $c;
});

add_filter('document_title_separator', function () { return '|'; });
add_filter('pre_get_document_title', function ($t) {
    if (is_front_page()) return 'NUNDÌA – Sapori di Sicilia | Pasticceria e specialità siciliane';
    return $t;
});
