<?php

/**
 * Layout Module: Categories
 */
if (!taxonomy_exists('product_cat')) {
    return;
}

$cats = get_terms(array(
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'exclude' => array(get_option('default_product_cat')),
    'orderby' => 'meta_value_num',
    'meta_key' => 'order',
    'order' => 'ASC',
));

if (empty($cats) || is_wp_error($cats)) {
    return;
}

$eyebrow = nd('home_cat_eyebrow');
$title = nd('home_cat_title');
?>
<section class="bg-background py-16 lg:py-24">
    <div class="container-ed">
        <?php if ($eyebrow || $title): ?>
            <div class="reveal text-center">
                <?php if ($eyebrow): ?>
                    <?php echo nundia_eyebrow($eyebrow, 'text-muted-foreground', 'bg-primary', true); ?>
                <?php endif; ?>
                <?php if ($title): ?>
                    <h2 class="mt-4 text-[clamp(1.9rem,4vw,2.9rem)] leading-[1.1] text-foreground"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="mt-12 grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-x-5 sm:gap-x-7 gap-y-8 sm:gap-y-10 nd-categories-grid">
            <?php foreach ($cats as $i => $cat):
                $thumb = get_term_meta($cat->term_id, 'thumbnail_id', true);
                $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
                $cat_link = add_query_arg('cat', $cat->slug, $shop_url);
            ?>
                <div class="reveal text-center" <?php echo $i ? ' style="transition-delay: ' . (70 * $i) . 'ms;"' : ''; ?>>
                    <a class="group block" href="<?php echo esc_url($cat_link); ?>">
                        <?php if ($thumb): ?>
                            <div class="overflow-hidden rounded-md">
                                <?php echo nundia_image((int) $thumb, $cat->name, 'relative aspect-[3/4] w-full transition-transform duration-[1300ms] ease-out group-hover:scale-[1.06]', 'woocommerce_single'); ?>
                            </div>
                        <?php endif; ?>
                        <h3 class="mx-auto mt-4 max-w-[13rem] text-[1.05rem] font-semibold leading-snug text-foreground transition-colors duration-300 group-hover:text-primary"><?php echo esc_html($cat->name); ?></h3>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>