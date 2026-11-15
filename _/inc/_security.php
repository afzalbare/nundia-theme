<?php
/**
 * Security Hardening Module
 * Protects against User Enumeration, XML-RPC attacks, Version Leaks,
 * and sets essential HTTP Security Headers.
 */
if (!defined('ABSPATH')) exit;

/* ------------------------------------------------------------------ *
 * 1. Disable XML-RPC & Pingbacks
 * ------------------------------------------------------------------ */
add_filter('xmlrpc_enabled', '__return_false');
add_filter('wp_headers', function ($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

// Immediately halt direct xmlrpc.php execution with 403 Forbidden
if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
    status_header(403);
    wp_die('XML-RPC is disabled on this server.', 'Forbidden', array('response' => 403));
}

/* ------------------------------------------------------------------ *
 * 2. Block REST API User Enumeration for unauthenticated requests
 * ------------------------------------------------------------------ */
add_filter('rest_endpoints', function ($endpoints) {
    if (!is_user_logged_in()) {
        if (isset($endpoints['/wp/v2/users'])) {
            unset($endpoints['/wp/v2/users']);
        }
        if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
            unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
        }
    }
    return $endpoints;
});

/* ------------------------------------------------------------------ *
 * 3. Block Author Archive & Query Enumeration (?author=N, /author/slug)
 * ------------------------------------------------------------------ */
// Intercept author query at request parsing to prevent canonical redirect leaks
add_filter('request', function ($query_vars) {
    if (!is_admin() && (isset($query_vars['author']) || isset($query_vars['author_name']))) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
    return $query_vars;
});

add_action('template_redirect', function () {
    if (is_author()) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
});

// Disable Core User Sitemaps
add_filter('wp_sitemaps_add_provider', function ($provider, $name) {
    if ($name === 'users') {
        return false;
    }
    return $provider;
}, 10, 2);

/* ------------------------------------------------------------------ *
 * 4. HTTP Security Headers
 * ------------------------------------------------------------------ */
add_action('send_headers', function () {
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), camera=(), microphone=()');
        if (is_ssl() || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
        }
    }
});

/* ------------------------------------------------------------------ *
 * 5. Remove WordPress & WooCommerce Version Information
 * ------------------------------------------------------------------ */
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');
add_filter('woocommerce_generator_tag', '__return_empty_string');
remove_action('wp_head', 'wc_generator_tag');

// Remove WordPress version query strings from static assets
function nundia_remove_version_strings($src) {
    if (!is_admin() && strpos($src, 'ver=' . get_bloginfo('version')) !== false) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'nundia_remove_version_strings', 9999);
add_filter('script_loader_src', 'nundia_remove_version_strings', 9999);

/* ------------------------------------------------------------------ *
 * 6. Mask Login Errors (prevent username enumeration via wp-login.php)
 * ------------------------------------------------------------------ */
add_filter('login_errors', function () {
    return 'Credenziali non valide. Riprova.';
});
