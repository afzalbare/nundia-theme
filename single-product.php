<?php
/**
 * Single product.
 */
get_header();
while (have_posts()): the_post();
    $product = wc_get_product(get_the_ID());
    $id = $product->get_id();
    $in_stock = $product->is_in_stock() && $product->is_purchasable();
    $unit = get_post_meta($id, 'unit_label', true);
    $details = array(
        'Ingredienti' => get_post_meta($id, 'ingredients', true),
        'Allergeni' => get_post_meta($id, 'allergens', true),
        'Valori nutrizionali' => get_post_meta($id, 'nutrition', true),
        'Confezionamento' => get_post_meta($id, 'packaging', true),
    );
    $has_draft = in_array('', array_map('trim', $details), true);
    // "Potrebbe interessarti": same category first (newest first), topped up with other products.
    $cat_slugs = wp_get_post_terms($id, 'product_cat', array('fields' => 'slugs'));
    $related = $cat_slugs ? wc_get_products(array('status' => 'publish', 'limit' => 4, 'exclude' => array($id), 'category' => $cat_slugs, 'orderby' => 'date', 'order' => 'DESC')) : array();
    if (count($related) < 4) {
        $exclude = array_merge(array($id), array_map(function ($p) { return $p->get_id(); }, $related));
        $related = array_merge($related, wc_get_products(array('status' => 'publish', 'limit' => 4 - count($related), 'exclude' => $exclude, 'orderby' => 'date', 'order' => 'DESC')));
    }
    $gallery = $product->get_gallery_image_ids();
    ?>
    <article class="pb-20">
        <div class="container-ed pt-8">
            <a class="inline-flex items-center gap-2 text-[10.5px] uppercase tracking-[0.2em] text-muted-foreground transition-colors hover:text-foreground" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                <?php echo nundia_icon('arrow-left', 'h-3.5 w-3.5', 1.6); ?>Shop
            </a>
        </div>
        <div class="container-ed mt-7 grid gap-10 lg:grid-cols-2 lg:gap-16">
            <div>
                <div class="relative overflow-hidden rounded-md bg-white">
                    <?php echo nundia_post_image($id, $product->get_name(), 'relative aspect-[4/5] w-full', 'large', 'cannoli.png', 'object-contain'); ?>
                </div>
                <?php if ($gallery): ?>
                    <div class="mt-4 grid grid-cols-4 gap-3">
                        <?php foreach ($gallery as $gid): ?>
                            <div class="overflow-hidden rounded-md"><?php echo nundia_image($gid, $product->get_name(), 'relative aspect-square w-full', 'medium'); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="lg:pt-6">
                <?php if ($unit): ?><p class="eyebrow"><?php echo esc_html($unit); ?></p><?php endif; ?>
                <h1 class="mt-3 text-[clamp(1.9rem,3.6vw,2.9rem)] leading-[1.05]"><?php the_title(); ?></h1>
                <p class="mt-4 text-xl tabular-nums text-primary"><?php echo esc_html(nundia_price($product->get_price())); ?></p>
                <?php if ($product->get_short_description()): ?>
                    <p class="mt-5 max-w-lg text-[15px] leading-relaxed text-muted-foreground"><?php echo esc_html(wp_strip_all_tags($product->get_short_description())); ?></p>
                <?php endif; ?>
                <div class="mt-7 flex items-center gap-2 text-[11px] uppercase tracking-[0.16em]">
                    <span class="h-1.5 w-1.5 rounded-full <?php echo $in_stock ? 'bg-green-600' : 'bg-primary'; ?>"></span>
                    <?php echo $in_stock ? 'Disponibile' : 'Non disponibile'; ?>
                </div>
                <div class="js-qty-wrap mt-7 flex flex-wrap items-center gap-3">
                    <div class="flex items-center border border-border">
                        <button type="button" class="js-qty flex h-12 w-11 items-center justify-center transition-colors hover:bg-secondary" data-step="-1" aria-label="-"><?php echo nundia_icon('minus', 'h-3.5 w-3.5', 2); ?></button>
                        <span class="js-qty-value w-10 text-center text-sm tabular-nums">1</span>
                        <button type="button" class="js-qty flex h-12 w-11 items-center justify-center transition-colors hover:bg-secondary" data-step="1" aria-label="+"><?php echo nundia_icon('plus', 'h-3.5 w-3.5', 2); ?></button>
                    </div>
                    <button type="button" class="js-add-to-cart inline-flex h-12 flex-1 items-center justify-center gap-2 bg-primary px-8 text-[11px] uppercase tracking-[0.2em] text-white transition-colors hover:bg-primary/90 disabled:cursor-not-allowed disabled:bg-muted disabled:text-muted-foreground sm:flex-none" data-product-id="<?php echo esc_attr($id); ?>" data-label="Aggiungi al carrello" data-done="Aggiunto al carrello" data-check="1" <?php disabled(!$in_stock); ?>>
                        Aggiungi al carrello
                    </button>
                </div>
                <?php $desc = trim(wp_strip_all_tags(get_the_content())); if ($desc): ?>
                    <div class="mt-9 border-t border-border pt-6">
                        <p class="whitespace-pre-line text-[14.5px] leading-[1.85] text-foreground/80"><?php echo esc_html($desc); ?></p>
                    </div>
                <?php endif; ?>
                <?php 
                $active_details = array_filter($details, function($v) {
                    return trim((string)$v) !== '';
                });
                if (!empty($active_details)): ?>
                    <dl class="mt-8 divide-y divide-border border-t border-border">
                        <?php foreach ($active_details as $label => $value): ?>
                            <div class="grid grid-cols-[130px_1fr] gap-4 py-4">
                                <dt class="text-[10.5px] uppercase tracking-[0.16em] text-muted-foreground"><?php echo esc_html($label); ?></dt>
                                <dd class="text-[13.5px] leading-relaxed text-foreground/80">
                                    <span class="whitespace-pre-line"><?php echo esc_html($value); ?></span>
                                </dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                <?php endif; ?>
                <?php if ($has_draft && !empty($active_details) && nd_opt('show_draft_badges')) echo nd_draft_badge('mt-6'); ?>
            </div>
        </div>
        <?php if ($related): ?>
            <section class="container-ed mt-20 border-t border-border pt-12">
                <h2 class="text-[clamp(1.5rem,2.4vw,2rem)]">Potrebbe interessarti</h2>
                <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-3 lg:grid-cols-4 lg:gap-x-8">
                    <?php foreach ($related as $p) nundia_product_card($p); ?>
                </div>
            </section>
        <?php endif; ?>
    </article>
<?php endwhile;
get_footer();
