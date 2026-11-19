<?php

/**
 * Shared markup components – class strings mirror the reference design 1:1.
 */
if (!defined('ABSPATH')) exit;

/** Theme image URL. */
function nundia_img($filename)
{
    return get_template_directory_uri() . '/assets/images/' . ltrim($filename, '/');
}

/** Get .webp version of a URL if it exists on disk */
function nundia_webp_candidate($url)
{
    if (empty($url) || !is_string($url)) return '';
    if (substr(strtolower($url), -5) === '.webp') return $url;

    $theme_uri = get_template_directory_uri();
    $theme_dir = get_template_directory();
    if (strpos($url, $theme_uri) === 0) {
        $rel = substr($url, strlen($theme_uri));
        $candidate = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $theme_dir . $rel);
        if (file_exists($candidate)) {
            return preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $url);
        }
    }

    if (function_exists('wp_upload_dir')) {
        $upload = wp_upload_dir();
        if (strpos($url, $upload['baseurl']) === 0) {
            $rel = substr($url, strlen($upload['baseurl']));
            $candidate = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $upload['basedir'] . $rel);
            if (file_exists($candidate)) {
                return preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $url);
            }
        }
    }

    return '';
}

/**
 * Image wrapper identical to the reference (<span wrap><span><img></span></span>).
 * $src may be a URL or an attachment ID.
 */
function nundia_image($src, $alt = '', $wrap = 'relative w-full', $size = 'large', $eager = false, $fit = 'object-cover')
{
    $srcset = '';
    $width = 0;
    $height = 0;
    if (is_numeric($src)) {
        $id = (int) $src;
        $image_data = wp_get_attachment_image_src($id, $size);
        if ($image_data) {
            $src = $image_data[0];
            $width = (int) $image_data[1];
            $height = (int) $image_data[2];
        } else {
            $src = wp_get_attachment_image_url($id, $size);
        }
        $srcset = wp_get_attachment_image_srcset($id, $size);
    }
    if (!$src) return '';
    $wrap = trim($wrap);
    if (strpos(' ' . $wrap . ' ', ' inline-block ') === false) $wrap = 'inline-block ' . $wrap;
    if (strpos(' ' . $wrap . ' ', ' relative ') === false && strpos(' ' . $wrap . ' ', ' absolute ') === false) $wrap .= ' relative';

    $webp_src = nundia_webp_candidate($src);
    $dim_attr = ($width && $height) ? ' width="' . $width . '" height="' . $height . '"' : '';
    $loading_attr = $eager ? ' fetchpriority="high"' : ' loading="lazy" decoding="async"';
    $sizes_attr = $srcset ? ' sizes="(min-width: 1280px) 25vw, (min-width: 768px) 50vw, 100vw"' : '';

    $img_tag = '<img src="' . esc_url($src) . '"'
        . ($srcset ? ' srcset="' . esc_attr($srcset) . '"' . $sizes_attr : '')
        . ' alt="' . esc_attr($alt) . '"'
        . $dim_attr . $loading_attr . ' class="w-full h-full inset-0 absolute ' . esc_attr($fit) . '">';

    if ($webp_src && $webp_src !== $src && empty($srcset)) {
        $img_tag = '<picture><source srcset="' . esc_url($webp_src) . '" type="image/webp">' . $img_tag . '</picture>';
    }

    return '<span class="' . esc_attr($wrap) . '"><span class="block relative w-full h-full overflow-hidden">' . $img_tag . '</span></span>';
}

/** Featured image of a post (ID) or a theme fallback. */
function nundia_post_image($post_id, $alt = null, $wrap = 'relative w-full', $size = 'large', $fallback = '', $fit = 'object-cover')
{
    $thumb = get_post_thumbnail_id($post_id);
    if (!$thumb && empty($fallback)) return '';
    if ($alt === null) $alt = get_the_title($post_id);
    $src = $thumb ? $thumb : (strpos($fallback, 'http') === 0 ? $fallback : nundia_img($fallback));
    return nundia_image($src, $alt, $wrap, $size, false, $fit);
}

/** "3,80 €" */
function nundia_price($amount)
{
    return number_format((float) $amount, 2, ',', '.') . ' €';
}

/** Eyebrow line with red/white rule, as in the reference. */
function nundia_eyebrow($text, $class = 'text-muted-foreground', $rule = 'bg-primary', $center = false)
{
    if (empty($text) && $text !== '0') return '';
    $rule_html = '<span class="h-[2px] w-9 ' . esc_attr($rule) . '" aria-hidden="true"></span>';
    return '<p class="flex items-center ' . ($center ? 'justify-center ' : '') . 'gap-3 text-[10px] uppercase tracking-[0.3em] ' . esc_attr($class) . '">'
        . $rule_html . esc_html($text) . ($center ? $rule_html : '') . '</p>';
}

/** Dark photo hero used by interior pages. */
function nundia_page_hero($hero)
{
    if (empty($hero['title']) && empty($hero['image']) && empty($hero['eyebrow']) && empty($hero['subtitle'])) {
        return;
    }
?>
    <section class="relative overflow-hidden bg-foreground text-white">
        <?php if (!empty($hero['image'])): ?>
            <div class="absolute inset-0" aria-hidden="true">
                <?php echo nundia_image($hero['image'], '', 'relative h-full w-full', 'full', true); ?>
                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-black/45"></div>
            </div>
        <?php endif; ?>
        <div class="container-ed relative flex min-h-[70vh] lg:min-h-[70vh] flex-col justify-end pb-14 pt-32 lg:pb-16">
            <?php if (!empty($hero['eyebrow'])): ?>
                <?php echo nundia_eyebrow($hero['eyebrow'], 'text-white/75', 'bg-white'); ?>
            <?php endif; ?>
            <?php if (!empty($hero['title'])): ?>
                <h1 class="mt-5 max-w-4xl text-[clamp(2.3rem,5.6vw,4.2rem)] leading-[0.98] text-white"><?php echo esc_html($hero['title']); ?></h1>
            <?php endif; ?>
            <?php if (!empty($hero['subtitle'])): ?>
                <p class="mt-5 max-w-xl text-[15px] leading-relaxed text-white/80"><?php echo esc_html($hero['subtitle']); ?></p>
            <?php endif; ?>
        </div>
    </section>
<?php
}

/** Hero used by legal / cart / checkout pages. */
function nundia_light_hero($hero)
{
    if (empty($hero['title']) && empty($hero['image']) && empty($hero['eyebrow']) && empty($hero['subtitle'])) {
        return;
    }
?>
    <section class="relative overflow-hidden bg-foreground text-white">
        <?php if (!empty($hero['image'])): ?>
            <div class="absolute inset-0" aria-hidden="true">
                <?php echo nundia_image($hero['image'], '', 'relative h-full w-full', 'full', true); ?>
                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-black/45"></div>
            </div>
        <?php endif; ?>
        <div class="container-ed relative flex min-h-[35vh] lg:min-h-[42vh] flex-col justify-end pb-14 pt-32 lg:pb-16">
            <?php if (!empty($hero['eyebrow'])): ?>
                <?php echo nundia_eyebrow($hero['eyebrow'], 'text-white/75', 'bg-white'); ?>
            <?php endif; ?>
            <?php if (!empty($hero['title'])): ?>
                <h1 class="mt-5 max-w-4xl text-[clamp(2.3rem,5.6vw,4.2rem)] leading-[0.98] text-white"><?php echo esc_html($hero['title']); ?></h1>
            <?php endif; ?>
            <?php if (!empty($hero['subtitle'])): ?>
                <p class="mt-5 max-w-xl text-[15px] leading-relaxed text-white/80"><?php echo esc_html($hero['subtitle']); ?></p>
            <?php endif; ?>
        </div>
    </section>
<?php
}

/**
 * Does this view use a transparent hero header like the home page?
 * True for the home page and interior pages/archives with a hero section.
 * False for single product, post, and recipe views without a top hero banner.
 */
function nundia_has_transparent_header()
{
    if (is_singular(array('product', 'post', 'recipe'))) {
        return false;
    }
    return true;
}

/**
 * Normalize a raw weight/format string so it reports ONLY the weight and unit of measure
 * (e.g. "360 g", "240 gr", "1,2 kg") without "barattolo", "confezione", "jar", etc.
 */
function nundia_format_net_weight($raw)
{
    $raw = trim((string) $raw);
    if ($raw === '') return '';

    if (preg_match('/(?<![a-zA-Z0-9])(\d+(?:[.,]\d+)?)\s*(kg|gr|g|mg|ml|cl|l|oz|lb)\b\.?/i', $raw, $m)) {
        return $m[1] . ' ' . strtolower($m[2]);
    }

    if (preg_match('/^\d+(?:[.,]\d+)?$/', $raw)) {
        return $raw . ' g';
    }

    return $raw;
}

/** Get all available formats/sizes for a product (ACF repeater only; no hardcoded defaults). */
function nundia_get_product_formats($product)
{
    if (is_numeric($product)) $product = wc_get_product($product);
    if (!$product) return array();
    $id = $product->get_id();
    $formats = array();

    if (function_exists('get_field')) {
        $acf_formats = get_field('product_formats', $id);
        if (is_array($acf_formats) && !empty($acf_formats)) {
            foreach ($acf_formats as $fmt) {
                if (empty($fmt['format_label'])) continue;
                $raw_net = !empty($fmt['format_net_weight']) ? trim($fmt['format_net_weight']) : trim($fmt['format_label']);
                $formats[] = array(
                    'label'      => trim($fmt['format_label']),
                    'price'      => !empty($fmt['format_price']) ? floatval(str_replace(',', '.', $fmt['format_price'])) : floatval($product->get_price()),
                    'in_stock'   => isset($fmt['format_in_stock']) ? (bool) $fmt['format_in_stock'] : true,
                    'net_weight' => nundia_format_net_weight($raw_net),
                );
            }
        }
    }

    return $formats;
}

/** Shipping rate definitions */
function nundia_shipping_rates()
{
    return array(
        'IT' => array(
            'label' => 'Italia',
            'cost'  => floatval(nd_opt('shipping_cost_it') ?: 6.90),
        ),
        'FR' => array(
            'label' => 'Francia',
            'cost'  => floatval(nd_opt('shipping_cost_fr') ?: 12.00),
        ),
        'DE' => array(
            'label' => 'Germania',
            'cost'  => floatval(nd_opt('shipping_cost_de') ?: 12.00),
        ),
        'EU' => array(
            'label' => 'Altri Paesi UE',
            'cost'  => floatval(nd_opt('shipping_cost_eu') ?: 15.00),
        ),
    );
}

/** Free shipping threshold */
function nundia_free_shipping_threshold()
{
    $th = nd_opt('shipping_free_threshold');
    return ($th !== '' && is_numeric($th)) ? floatval($th) : 50.0;
}

/** Get product net weight from ACF unit_label or WooCommerce Shipping weight only. */
function nundia_product_weight($product)
{
    if (is_numeric($product)) $product = wc_get_product($product);
    if (!$product) return '';
    $id = $product->get_id();

    $w = trim((string) get_post_meta($id, 'unit_label', true));
    if ($w === '' && method_exists($product, 'has_weight') && $product->has_weight()) {
        $w = $product->get_weight() . ' ' . get_option('woocommerce_weight_unit', 'g');
    }
    return nundia_format_net_weight($w);
}

/** Product card (shop grid, home carousel, related). */
function nundia_product_card($product, $extra_attrs = '')
{
    if (is_numeric($product)) $product = wc_get_product($product);
    if (!$product) return;
    $id = $product->get_id();
    $link = get_permalink($id);
    $name = $product->get_name();
    $short = wp_strip_all_tags($product->get_short_description());
    $in_stock = $product->is_in_stock() && $product->is_purchasable();

    // Primary product category
    $cats = wp_get_post_terms($id, 'product_cat', array('fields' => 'names'));
    $cat_name = (!empty($cats) && !is_wp_error($cats)) ? $cats[0] : '';
?>
    <article class="nd-product-card group" <?php echo $extra_attrs; ?>>
        <a class="nd-product-media" href="<?php echo esc_url($link); ?>" aria-label="<?php echo esc_attr($name); ?>">
            <?php echo nundia_post_image($id, $name, 'relative h-full w-full', 'woocommerce_single'); ?>
            <?php if (!$in_stock): ?>
                <span class="nd-product-badge-soldout">Esaurito</span>
            <?php endif; ?>
        </a>
        <div class="nd-product-body">
            <div class="nd-product-info">
                <?php if ($cat_name): ?>
                    <span class="nd-product-category"><?php echo esc_html($cat_name); ?></span>
                <?php endif; ?>
                <a class="block" href="<?php echo esc_url($link); ?>">
                    <h3 class="nd-product-title"><?php echo esc_html($name); ?></h3>
                </a>
                <?php if ($short): ?>
                    <p class="nd-product-desc"><?php echo esc_html($short); ?></p>
                <?php endif; ?>
            </div>
            <div class="nd-product-footer">
                <div class="nd-product-price-row">
                    <span class="nd-product-price-label">Prezzo</span>
                    <span class="nd-product-price-value"><?php echo esc_html(nundia_price($product->get_price())); ?></span>
                </div>
                <p class="nd-product-tax-note text-[9.5px] leading-tight text-muted-foreground/85 -mt-2 mb-2.5 text-right font-normal">
                    (IVA inclusa) - escluse spese di trasporto
                </p>
                <button type="button" class="js-add-to-cart nd-product-btn" data-product-id="<?php echo esc_attr($id); ?>" data-label="Aggiungi al carrello" data-done="Aggiunto!" data-check="1" <?php disabled(!$in_stock); ?>>
                    <?php if ($in_stock): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="opacity-95" aria-hidden="true">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                            <path d="M3 6h18" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                        <span>Aggiungi al carrello</span>
                    <?php else: ?>
                        <span>Esaurito</span>
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </article>
<?php
}

/** Supported languages (Google Translate codes) */
function nundia_languages()
{
    return array(
        'it' => array('Italiano', 'it'),
        'en' => array('English', 'en'),
        'de' => array('Deutsch', 'de'),
        'fr' => array('Français', 'fr'),
        'ru' => array('Русский', 'ru'),
        'ar' => array('العربية', 'ar'),
        'ja' => array('日本語', 'ja'),
    );
}

/** Local SVG flag URL */
function nundia_flag_url($code)
{
    $map = array(
        'it' => 'it',
        'en' => 'en',
        'gb' => 'en',
        'de' => 'de',
        'fr' => 'fr',
        'ru' => 'ru',
        'ar' => 'ar',
        'sa' => 'ar',
        'ja' => 'ja',
        'jp' => 'ja',
    );
    $key = strtolower(trim($code));
    $file = isset($map[$key]) ? $map[$key] : 'it';
    return get_template_directory_uri() . '/assets/images/flags/' . $file . '.svg';
}

/** Current language from explicit companion cookie or googtrans cookie */
function nundia_current_lang()
{
    $langs = nundia_languages();

    // 1. Explicit theme companion cookie
    if (!empty($_COOKIE['nundia_lang'])) {
        $c = strtolower(trim($_COOKIE['nundia_lang']));
        if ($c === 'it') {
            return 'it';
        }
        if (isset($langs[$c])) {
            return $c;
        }
    }

    // 2. Google Translate cookie (/it/en, /auto/en, it/en, auto/en, /it|en, etc.)
    if (!empty($_COOKIE['googtrans'])) {
        $val = trim(urldecode($_COOKIE['googtrans']), " \t\n\r\0\x0B\"'/");
        if (preg_match('#(?:^|/|\|)([a-z]{2}(?:-[a-z]{2})?)$#i', $val, $m)) {
            $code = strtolower($m[1]);
            if ($code === 'it') {
                return 'it';
            }
            if (isset($langs[$code])) {
                return $code;
            }
        }
    }

    return 'it';
}

/**
 * Language dropdown: shows only the language in use; the others open in a menu.
 */
function nundia_language_switcher($id = 'lang')
{
    $langs = nundia_languages();
    $cur = nundia_current_lang();
    $flag_url = nundia_flag_url($cur);
?>
    <div class="nd-lang relative notranslate" data-lang-switcher translate="no">
        <button type="button" class="nd-lang-btn flex items-center gap-1.5 text-[10px] uppercase tracking-[0.2em] opacity-80 transition-opacity hover:opacity-100" aria-haspopup="listbox" aria-expanded="false" aria-controls="<?php echo esc_attr($id); ?>-menu" aria-label="Lingua / Language">
            <span class="nd-flag h-[18px] w-[18px] overflow-hidden rounded-full"><img src="<?php echo esc_url($flag_url); ?>" alt="<?php echo esc_attr($langs[$cur][0]); ?>" width="18" height="18" class="js-lang-flag h-full w-full object-cover"></span>
            <span class="js-lang-code"><?php echo esc_html(strtoupper($cur)); ?></span>
            <?php echo nundia_icon('chevron-down', 'nd-lang-chevron h-3 w-3', 1.8); ?>
        </button>
        <ul id="<?php echo esc_attr($id); ?>-menu" class="nd-lang-menu" role="listbox" hidden>
            <?php foreach ($langs as $code => $l): ?>
                <li>
                    <button type="button" role="option" data-lang="<?php echo esc_attr($code); ?>" data-lang-current="<?php echo esc_attr($cur); ?>" data-flag="<?php echo esc_url(nundia_flag_url($code)); ?>" aria-selected="<?php echo $code === $cur ? 'true' : 'false'; ?>" class="nd-lang-item">
                        <span class="h-[18px] w-[18px] shrink-0 overflow-hidden rounded-full"><img src="<?php echo esc_url(nundia_flag_url($code)); ?>" alt="" width="18" height="18" loading="lazy" class="h-full w-full object-cover"></span>
                        <span><?php echo esc_html($l[0]); ?></span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php
}

/** Primary navigation items (WP menu "Menu Principale" or fallback in the client's order). */
function nundia_nav_items()
{
    $items = array();
    $locations = get_nav_menu_locations();
    if (!empty($locations['primary'])) {
        $menu_items = wp_get_nav_menu_items($locations['primary']);
        if ($menu_items) {
            foreach ($menu_items as $mi) {
                if ((int) $mi->menu_item_parent === 0) {
                    $title = $mi->title;
                    $url = $mi->url;
                    // Remove Notizie per client instruction
                    if (stripos($title, 'Notizie') !== false || stripos($url, '/journal') !== false) {
                        continue;
                    }
                    $items[] = array('title' => $title, 'url' => $url);
                }
            }
        }
    }
    if (empty($items)) {
        foreach (
            array(
                array('Home', '/'),
                array('Chi siamo', '/chi-siamo/'),
                array('Ricette', '/ricette/'),
                array('B2B', '/b2b/'),
                array('Shop', '/shop/'),
                array('FAQ', '/faq/'),
                array('Diventa Partner', '/diventa-partner/'),
                array('Contatti', '/contatti/'),
            ) as $i
        ) $items[] = array('title' => $i[0], 'url' => home_url($i[1]));
    } else {
        // Ensure Diventa Partner is inserted between FAQ and Contatti
        $has_partner = false;
        foreach ($items as $it) {
            if (stripos($it['title'], 'Diventa Partner') !== false || stripos($it['url'], 'diventa-partner') !== false) {
                $has_partner = true;
                break;
            }
        }
        if (!$has_partner) {
            $new_items = array();
            $inserted = false;
            foreach ($items as $it) {
                if (!$inserted && (stripos($it['title'], 'Contatti') !== false || stripos($it['url'], '/contatti') !== false)) {
                    $new_items[] = array('title' => 'Diventa Partner', 'url' => home_url('/diventa-partner/'));
                    $inserted = true;
                }
                $new_items[] = $it;
            }
            if (!$inserted) {
                $new_items[] = array('title' => 'Diventa Partner', 'url' => home_url('/diventa-partner/'));
            }
            $items = $new_items;
        }
    }
    return $items;
}

/** Is a nav URL the current section? */
function nundia_is_current($url)
{
    $path = trim((string) wp_parse_url($url, PHP_URL_PATH), '/');
    $req = trim((string) wp_parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
    $home = trim((string) wp_parse_url(home_url('/'), PHP_URL_PATH), '/');
    if ($path === $home) return is_front_page();
    if ($req === $path || strpos($req . '/', $path . '/') === 0) return true;
    // Section ownership for singles
    if ($path === 'shop' && (is_singular('product') || is_tax('product_cat'))) return true;
    if ($path === 'journal' && (is_singular('post') || is_category())) return true;
    if ($path === 'ricette' && is_singular('recipe')) return true;
    if ($path === 'diventa-partner' && is_page('diventa-partner')) return true;
    return false;
}
