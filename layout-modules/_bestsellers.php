<?php
/**
 * Layout Module: Bestsellers (WooCommerce featured products carousel)
 */
if (!function_exists('wc_get_products')) {
    return;
}

$featured = wc_get_products(array('featured' => true, 'status' => 'publish', 'limit' => 12, 'orderby' => 'date', 'order' => 'DESC'));
if (!$featured) {
    $featured = wc_get_products(array('status' => 'publish', 'limit' => 5, 'orderby' => 'date', 'order' => 'DESC'));
}

if (empty($featured)) {
    return;
}

$arrow = nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.8);
$eyebrow = nd('home_best_eyebrow');
$title = nd('home_best_title');
$link = nd('home_best_link');
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
?>
<section class="bg-primary py-16 text-white lg:py-24">
    <div class="container-ed">
        <div class="nd-carousel">
            <?php if ($eyebrow || $title || $link): ?>
            <div class="flex flex-wrap items-end justify-between gap-6 pb-2">
                <div>
                    <?php if ($eyebrow): ?>
                        <div class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.25em] text-white/80">
                            <span class="h-[2px] w-9 bg-white" aria-hidden="true"></span>
                            <span><?php echo esc_html($eyebrow); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($title): ?>
                        <h2 class="mt-4 text-[clamp(2.1rem,4.2vw,3.2rem)] font-bold tracking-tight leading-[1.08] text-white"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                </div>
                <?php if ($link): ?>
                <div>
                    <a class="group inline-flex items-center gap-2 rounded-full border border-white/35 bg-white/10 px-5 py-2.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-white backdrop-blur-sm transition-all duration-300 hover:bg-white hover:text-primary hover:border-white shadow-sm" href="<?php echo esc_url($shop_url); ?>">
                        <span><?php echo esc_html($link); ?></span><?php echo $arrow; ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="relative mt-8">
                <button type="button" data-dir="-1" aria-label="Prodotti precedenti" class="nd-carousel-btn nd-carousel-side-btn nd-carousel-side-prev">
                    <?php echo nundia_icon('chevron-left', 'h-5 w-5', 2.4); ?>
                </button>

                <div class="nd-carousel-track no-scrollbar flex snap-x snap-mandatory overflow-x-auto py-5 px-1 scroll-smooth">
                    <?php foreach ($featured as $p): ?>
                        <div class="nd-carousel-item"><?php nundia_product_card($p); ?></div>
                    <?php endforeach; ?>
                </div>

                <button type="button" data-dir="1" aria-label="Prodotti successivi" class="nd-carousel-btn nd-carousel-side-btn nd-carousel-side-next">
                    <?php echo nundia_icon('chevron-right', 'h-5 w-5', 2.4); ?>
                </button>
            </div>
        </div>
    </div>
</section>
