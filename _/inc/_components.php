<?php
/**
 * Shared markup components – class strings mirror the reference design 1:1.
 */
if (!defined('ABSPATH')) exit;

/** Theme image URL. */
function nundia_img($filename) {
    return get_template_directory_uri() . '/assets/images/' . ltrim($filename, '/');
}

/**
 * Image wrapper identical to the reference (<span wrap><span><img></span></span>).
 * $src may be a URL or an attachment ID.
 */
function nundia_image($src, $alt = '', $wrap = 'relative w-full', $size = 'large', $eager = false, $fit = 'object-cover') {
    $srcset = '';
    if (is_numeric($src)) {
        $id = (int) $src;
        $src = wp_get_attachment_image_url($id, $size);
        $srcset = wp_get_attachment_image_srcset($id, $size);
    }
    if (!$src) return '';
    $wrap = trim($wrap);
    if (strpos(' ' . $wrap . ' ', ' inline-block ') === false) $wrap = 'inline-block ' . $wrap;
    if (strpos(' ' . $wrap . ' ', ' relative ') === false && strpos(' ' . $wrap . ' ', ' absolute ') === false) $wrap .= ' relative';
    return '<span class="' . esc_attr($wrap) . '"><span class="block relative w-full h-full overflow-hidden"><img src="' . esc_url($src) . '"'
        . ($srcset ? ' srcset="' . esc_attr($srcset) . '" sizes="(min-width: 1024px) 50vw, 100vw"' : '')
        . ' alt="' . esc_attr($alt) . '"' . ($eager ? ' fetchpriority="high"' : ' loading="lazy"') . ' decoding="async" class="w-full h-full inset-0 absolute ' . esc_attr($fit) . '"></span></span>';
}

/** Featured image of a post (ID) or a theme fallback. */
function nundia_post_image($post_id, $alt = null, $wrap = 'relative w-full', $size = 'large', $fallback = '', $fit = 'object-cover') {
    $thumb = get_post_thumbnail_id($post_id);
    if (!$thumb && empty($fallback)) return '';
    if ($alt === null) $alt = get_the_title($post_id);
    $src = $thumb ? $thumb : (strpos($fallback, 'http') === 0 ? $fallback : nundia_img($fallback));
    return nundia_image($src, $alt, $wrap, $size, false, $fit);
}

/** "3,80 €" */
function nundia_price($amount) {
    return number_format((float) $amount, 2, ',', '.') . ' €';
}

/** Eyebrow line with red/white rule, as in the reference. */
function nundia_eyebrow($text, $class = 'text-muted-foreground', $rule = 'bg-primary', $center = false) {
    if (empty($text) && $text !== '0') return '';
    $rule_html = '<span class="h-[2px] w-9 ' . esc_attr($rule) . '" aria-hidden="true"></span>';
    return '<p class="flex items-center ' . ($center ? 'justify-center ' : '') . 'gap-3 text-[10px] uppercase tracking-[0.3em] ' . esc_attr($class) . '">'
        . $rule_html . esc_html($text) . ($center ? $rule_html : '') . '</p>';
}

/** Dark photo hero used by interior pages. */
function nundia_page_hero($hero) {
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
        <div class="container-ed relative flex min-h-[40vh] lg:min-h-[50vh] flex-col justify-end pb-14 pt-32 lg:pb-16">
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
        <div class="absolute inset-x-0 bottom-0 h-[6px] bg-primary" aria-hidden="true"></div>
    </section>
    <?php
}

/** Hero used by legal / cart / checkout pages. */
function nundia_light_hero($hero) {
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
        <div class="absolute inset-x-0 bottom-0 h-[6px] bg-primary" aria-hidden="true"></div>
    </section>
    <?php
}

/**
 * Does this view use a transparent hero header like the home page?
 * True for the home page and interior pages/archives with a hero section.
 * False for single product, post, and recipe views without a top hero banner.
 */
function nundia_has_transparent_header() {
    if (is_singular(array('product', 'post', 'recipe'))) {
        return false;
    }
    return true;
}

/** Product card (shop grid, home carousel, related). */
function nundia_product_card($product, $extra_attrs = '') {
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
                <button type="button" class="js-add-to-cart nd-product-btn" data-product-id="<?php echo esc_attr($id); ?>" data-label="Aggiungi al carrello" data-done="Aggiunto!" data-check="1" <?php disabled(!$in_stock); ?>>
                    <?php if ($in_stock): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="opacity-95" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
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

/** Supported languages (Google Translate codes) – same set as the reference. */
function nundia_languages() {
    return array(
        'it' => array('Italiano', 'it'),
        'en' => array('English', 'gb'),
        'de' => array('Deutsch', 'de'),
        'fr' => array('Français', 'fr'),
        'ru' => array('Русский', 'ru'),
        'ar' => array('العربية', 'sa'),
        'ja' => array('日本語', 'jp'),
    );
}

/** Current language from the googtrans cookie ("/it/en"). */
function nundia_current_lang() {
    $langs = nundia_languages();
    if (!empty($_COOKIE['googtrans']) && preg_match('#^/it/([a-z-]+)$#i', $_COOKIE['googtrans'], $m) && isset($langs[strtolower($m[1])])) {
        return strtolower($m[1]);
    }
    return 'it';
}

/**
 * Language dropdown: shows only the language in use; the others open in a menu.
 * $variant: 'light' (on transparent/dark header) or 'dark' (on white).
 */
function nundia_language_switcher($id = 'lang') {
    $langs = nundia_languages();
    $cur = nundia_current_lang();
    ?>
    <div class="nd-lang relative notranslate" data-lang-switcher translate="no">
        <button type="button" class="nd-lang-btn flex items-center gap-1.5 text-[10px] uppercase tracking-[0.2em] opacity-80 transition-opacity hover:opacity-100" aria-haspopup="listbox" aria-expanded="false" aria-controls="<?php echo esc_attr($id); ?>-menu" aria-label="Lingua / Language">
            <span class="nd-flag h-[18px] w-[18px] overflow-hidden rounded-full"><img src="https://flagcdn.com/w40/<?php echo esc_attr($langs[$cur][1]); ?>.png" alt="" class="h-full w-full object-cover"></span>
            <span><?php echo esc_html(strtoupper($cur)); ?></span>
            <?php echo nundia_icon('chevron-down', 'nd-lang-chevron h-3 w-3', 1.8); ?>
        </button>
        <ul id="<?php echo esc_attr($id); ?>-menu" class="nd-lang-menu" role="listbox" hidden>
            <?php foreach ($langs as $code => $l): ?>
                <li>
                    <button type="button" role="option" data-lang="<?php echo esc_attr($code); ?>" aria-selected="<?php echo $code === $cur ? 'true' : 'false'; ?>" class="nd-lang-item">
                        <span class="h-[18px] w-[18px] shrink-0 overflow-hidden rounded-full"><img src="https://flagcdn.com/w40/<?php echo esc_attr($l[1]); ?>.png" alt="" loading="lazy" class="h-full w-full object-cover"></span>
                        <span><?php echo esc_html($l[0]); ?></span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

/** Primary navigation items (WP menu "Menu Principale" or fallback in the client's order). */
function nundia_nav_items() {
    $items = array();
    $locations = get_nav_menu_locations();
    if (!empty($locations['primary'])) {
        $menu_items = wp_get_nav_menu_items($locations['primary']);
        if ($menu_items) {
            foreach ($menu_items as $mi) {
                if ((int) $mi->menu_item_parent === 0) $items[] = array('title' => $mi->title, 'url' => $mi->url);
            }
        }
    }
    if (empty($items)) {
        foreach (array(
            array('Home', '/'), array('Chi siamo', '/chi-siamo/'), array('Notizie', '/journal/'), array('Ricette', '/ricette/'),
            array('B2B', '/b2b/'), array('Shop', '/shop/'), array('FAQ', '/faq/'), array('Contatti', '/contatti/'),
        ) as $i) $items[] = array('title' => $i[0], 'url' => home_url($i[1]));
    }
    return $items;
}

/** Is a nav URL the current section? */
function nundia_is_current($url) {
    $path = trim((string) wp_parse_url($url, PHP_URL_PATH), '/');
    $req = trim((string) wp_parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
    $home = trim((string) wp_parse_url(home_url('/'), PHP_URL_PATH), '/');
    if ($path === $home) return is_front_page();
    if ($req === $path || strpos($req . '/', $path . '/') === 0) return true;
    // Section ownership for singles
    if ($path === 'shop' && (is_singular('product') || is_tax('product_cat'))) return true;
    if ($path === 'journal' && (is_singular('post') || is_category())) return true;
    if ($path === 'ricette' && is_singular('recipe')) return true;
    return false;
}
