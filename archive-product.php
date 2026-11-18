<?php
/**
 * Shop (WooCommerce shop page + product categories).
 */
get_header();
$shop_id = wc_get_page_id('shop');
nundia_page_hero(nd_hero('shop', $shop_id));

$cats = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => true, 'exclude' => array(get_option('default_product_cat')), 'orderby' => 'meta_value_num', 'meta_key' => 'order', 'order' => 'ASC'));
$current = isset($_GET['cat']) ? sanitize_title(wp_unslash($_GET['cat'])) : '';
if (is_tax('product_cat')) $current = get_queried_object()->slug;
$products = wc_get_products(array('status' => 'publish', 'limit' => -1, 'orderby' => 'date', 'order' => 'DESC', 'visibility' => 'catalog'));
$pill = 'whitespace-nowrap border px-4 py-2 text-[10.5px] uppercase tracking-[0.16em] transition-colors';
$on = 'border-foreground bg-foreground text-white';
$off = 'border-border text-foreground/70 hover:border-foreground';
?>
<section class="container-ed py-10 lg:py-14">
    <div class="flex flex-col gap-5 border-b border-border pb-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="js-filter no-scrollbar -mx-1 flex gap-1 overflow-x-auto" data-target="#product-grid" data-base="<?php echo esc_attr($pill); ?>" data-active="<?php echo esc_attr($on); ?>" data-inactive="<?php echo esc_attr($off); ?>" data-count="#product-count">
            <button type="button" data-filter="" class="<?php echo $pill . ' ' . ($current ? $off : $on); ?>">Tutti</button>
            <?php if (!is_wp_error($cats)) foreach ($cats as $c): ?>
                <button type="button" data-filter="<?php echo esc_attr($c->slug); ?>" class="<?php echo $pill . ' ' . ($current === $c->slug ? $on : $off); ?>"><?php echo esc_html($c->name); ?></button>
            <?php endforeach; ?>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <label class="relative flex items-center">
                <?php echo nundia_icon('search', 'pointer-events-none absolute left-3 h-4 w-4 text-muted-foreground', 1.5); ?>
                <span class="sr-only">Cerca</span>
                <input type="search" data-search="#product-grid" placeholder="Cerca un prodotto…" class="h-10 w-full border border-input bg-background pl-9 pr-3 text-[13px] outline-none transition-colors focus:border-foreground sm:w-56">
            </label>
            <select aria-label="Ordina" data-sort="#product-grid" class="h-10 border border-input bg-background px-3 text-[12px] uppercase tracking-[0.12em] outline-none focus:border-foreground">
                <option value="-date">Novità</option>
                <option value="price">Prezzo crescente</option>
                <option value="-price">Prezzo decrescente</option>
            </select>
        </div>
    </div>
    <div class="mt-7 flex items-center justify-between">
        <p class="text-[11px] uppercase tracking-[0.2em] text-muted-foreground"><span id="product-count"><?php echo count($products); ?></span> prodotti</p>
        <?php echo nd_draft_badge(''); ?>
    </div>
    <div id="product-grid" class="grid grid-cols-2 gap-x-4 gap-y-10 py-10 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 lg:gap-x-8" data-initial-filter="<?php echo esc_attr($current); ?>">
        <?php foreach ($products as $i => $p):
            $slugs = wp_get_post_terms($p->get_id(), 'product_cat', array('fields' => 'slugs'));
            $attrs = sprintf('data-item data-cat="%s" data-price="%s" data-date="%d" data-text="%s"',
                esc_attr(implode(' ', $slugs)), esc_attr($p->get_price()), count($products) - $i,
                esc_attr(mb_strtolower($p->get_name() . ' ' . nundia_product_weight($p) . ' ' . wp_strip_all_tags($p->get_short_description()))));
            nundia_product_card($p, $attrs);
        endforeach; ?>
    </div>
    <p class="js-empty hidden py-16 text-center text-[15px] text-muted-foreground">Nessun prodotto trovato.</p>
</section>
<?php get_footer();
