<?php

/**
 * Header – mirrors the reference header, with the client's requested changes:
 * gold logo lockup (NUNDÌA / Sapori di Sicilia / Typhoeus Group), language dropdown,
 * no slogan in the top bar, no thin divider line, no red accent bar, cart icon.
 */
if (!defined('ABSPATH')) exit;

$is_home        = is_front_page();
$is_transparent = function_exists('nundia_has_transparent_header') ? nundia_has_transparent_header() : true;
$cart_count     = nundia_cart_count();
$nav_items      = nundia_nav_items();
$brand          = nd_opt('brand_name');
$slogan         = nd_opt('brand_slogan');
$group          = nd_opt('brand_group');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#FFFFFF">
    <link rel="icon" type="image/png" href="<?php echo esc_url(nundia_img('nundia-emblem.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo esc_url(nundia_img('nundia-emblem.png')); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div class="flex min-h-screen flex-col bg-background">

        <header class="nd-header fixed inset-x-0 top-0 z-50 transition-all duration-500 <?php echo $is_transparent ? 'text-white' : 'border-b border-border bg-background/92 text-foreground backdrop-blur-xl'; ?>" data-transparent="<?php echo $is_transparent ? 'true' : 'false'; ?>">
            <div class="nd-header-row container-ed flex items-center justify-between gap-3 lg:gap-4 xl:gap-6">
                <a class="nd-logo shrink-0 items-center" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr($brand . ' – ' . $slogan); ?> – home">
                    <?php
                    $logo_light = nd_opt('header_logo_light');
                    $logo_dark = nd_opt('header_logo_dark');
                    if (!$logo_light) $logo_light = nundia_img('nundia-logo-light.png') . '?v=' . filemtime(get_template_directory() . '/assets/images/nundia-logo-light.png');
                    if (!$logo_dark) $logo_dark = nundia_img('nundia-logo-full.png') . '?v=' . filemtime(get_template_directory() . '/assets/images/nundia-logo-full.png');
                    ?>
                    <img src="<?php echo esc_url($logo_light); ?>" alt="<?php echo esc_attr($brand); ?>" class="nd-logo-light" width="120" height="120">
                    <img src="<?php echo esc_url($logo_dark); ?>" alt="<?php echo esc_attr($brand); ?>" class="nd-logo-dark" width="120" height="120">
                </a>

                <!-- Desktop Navigation: in the middle, visible on desktop (>= 1024px) -->
                <nav class="nd-nav flex items-center gap-2.5 lg:gap-3 xl:gap-4.5 2xl:gap-5.5 flex-nowrap whitespace-nowrap overflow-x-auto no-scrollbar" aria-label="Menu principale">
                    <?php foreach ($nav_items as $item):
                        $active = nundia_is_current($item['url']); ?>
                        <a class="group relative py-1.5 text-[11px] xl:text-[11.5px] 2xl:text-[12px] font-medium uppercase tracking-[0.10em] xl:tracking-[0.13em] transition-opacity whitespace-nowrap <?php echo $active ? 'opacity-100 font-semibold' : 'opacity-80 hover:opacity-100'; ?>" href="<?php echo esc_url($item['url']); ?>" <?php echo $active ? ' aria-current="page"' : ''; ?>>
                            <?php echo esc_html($item['title']); ?>
                            <span class="absolute inset-x-0 bottom-0 h-[2px] origin-left bg-primary transition-transform duration-300 <?php echo $active ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100'; ?>"></span>
                        </a>
                    <?php endforeach; ?>
                    <?php
                    $cta_text = nd_opt('header_cta_text');
                    $cta_link = nd_opt('header_cta_link');
                    if (!$cta_link || $cta_link === '/b2b/' || $cta_link === home_url('/b2b/')) {
                        $cta_link = home_url('/richiesta-commerciale/');
                    }
                    $b2b_active = $cta_link ? nundia_is_current($cta_link) : false;
                    if ($cta_text) :
                    ?>
                        <a class="group relative py-1.5 text-[11px] xl:text-[11.5px] 2xl:text-[12px] font-medium uppercase tracking-[0.10em] xl:tracking-[0.13em] transition-opacity whitespace-nowrap <?php echo $b2b_active ? 'opacity-100 font-semibold' : 'opacity-80 hover:opacity-100'; ?>" href="<?php echo esc_url($cta_link); ?>">
                            <?php echo esc_html($cta_text); ?>
                            <span class="absolute inset-x-0 bottom-0 h-[2px] origin-left bg-primary transition-transform duration-300 <?php echo $b2b_active ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100'; ?>"></span>
                        </a>
                    <?php endif; ?>
                </nav>

                <!-- Right Side Actions: Cart + Language (desktop) / Cart + Hamburger (mobile) -->
                <div class="nd-actions flex items-center gap-2.5 sm:gap-3 lg:gap-3.5 xl:gap-4.5 shrink-0">
                    <a aria-label="Carrello" class="nd-cart-link relative flex h-9 w-9 items-center justify-center transition-opacity hover:opacity-75" href="<?php echo esc_url(nundia_cart_url()); ?>">
                        <?php echo nundia_icon('shopping-cart', 'h-4 w-4 sm:h-[17px] sm:w-[17px]', 1.6); ?>
                        <span class="js-cart-count absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-primary px-1 text-[9px] font-bold text-white<?php echo $cart_count ? '' : ' hidden'; ?>"><?php echo (int) $cart_count; ?></span>
                    </a>
                    <div class="nd-lang-header items-center pl-2.5 sm:pl-3 xl:pl-4 border-l border-current/30">
                        <?php nundia_language_switcher('lang-nav'); ?>
                    </div>

                    <!-- Mobile Hamburger Button (< 1024px) -->
                    <button type="button" class="js-menu-toggle items-center justify-center transition-opacity hover:opacity-75 focus-visible:outline-none" aria-label="Menu" aria-expanded="false" aria-controls="nd-mobile-menu">
                        <span class="sr-only">Menu</span>
                        <svg class="nd-icon-open h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="nd-icon-close hidden h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Drawer (shown after clicking menu button on mobile) -->
            <div id="nd-mobile-menu" class="nd-mobile-menu lg:hidden fixed inset-x-0 bottom-0 top-[var(--nd-row)] z-40 bg-white text-[#1a1a1a] overflow-y-auto" aria-hidden="true">
                <div class="container-ed flex flex-col py-6 px-5 sm:px-8">
                    <nav class="flex flex-col space-y-1 divide-y divide-border/50" aria-label="Menu mobile">
                        <?php foreach ($nav_items as $item):
                            $active = nundia_is_current($item['url']); ?>
                            <a class="flex items-center justify-between py-3.5 text-[14px] font-medium tracking-wide uppercase transition-colors <?php echo $active ? 'text-primary font-semibold' : 'text-foreground/90 hover:text-primary'; ?>" href="<?php echo esc_url($item['url']); ?>" <?php echo $active ? ' aria-current="page"' : ''; ?>>
                                <span><?php echo esc_html($item['title']); ?></span>
                                <svg class="h-4 w-4 opacity-40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        <?php endforeach; ?>
                    </nav>

                    <div class="mt-6 pt-6 border-t border-border flex flex-col gap-4">
                        <?php if ($cta_text): ?>
                            <a class="inline-flex w-full items-center justify-center bg-primary px-5 py-3.5 text-[12px] font-medium uppercase tracking-[0.16em] text-white transition-opacity hover:opacity-90 rounded-sm" href="<?php echo esc_url($cta_link); ?>">
                                <?php echo esc_html($cta_text); ?>
                            </a>
                        <?php endif; ?>
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-xs uppercase tracking-wider text-muted-foreground font-medium">Lingua</span>
                            <?php nundia_language_switcher('lang-mobile'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <?php if (!$is_transparent): ?>
            <div aria-hidden="true" class="nd-header-spacer"></div>
        <?php endif; ?>

        <main class="flex-1">