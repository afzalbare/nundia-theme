<?php
/**
 * Cart page (WooCommerce cart, reference layout).
 */
get_header();
nundia_light_hero(nd_hero('carrello', get_queried_object_id()));
$cart = WC()->cart;
?>
<section class="container-ed py-12 lg:py-16">
    <?php if (!$cart || $cart->is_empty()): ?>
        <div class="py-16 text-center">
            <p class="text-[15px] text-muted-foreground">Il tuo carrello è vuoto.</p>
            <a class="mt-7 inline-flex min-h-12 items-center bg-primary px-7 text-[11px] uppercase tracking-[0.18em] text-white transition-colors hover:bg-primary/90" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Continua gli acquisti</a>
        </div>
    <?php else: ?>
        <div class="grid gap-12 lg:grid-cols-[1.6fr_1fr] lg:gap-16">
            <div>
                <ul class="space-y-5">
                    <?php foreach ($cart->get_cart() as $key => $item):
                        $p = $item['data'];
                        if (!$p || !$p->exists()) continue;
                        $link = get_permalink($item['product_id']);
                        $qty = (int) $item['quantity']; ?>
                        <li class="js-cart-line flex gap-4 py-3 sm:gap-6" data-key="<?php echo esc_attr($key); ?>">
                            <a class="h-24 w-20 shrink-0 overflow-hidden rounded-md bg-white" href="<?php echo esc_url($link); ?>">
                                <?php echo nundia_post_image($item['product_id'], $p->get_name(), 'relative h-full w-full', 'woocommerce_thumbnail'); ?>
                            </a>
                            <div class="flex flex-1 flex-col justify-between">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <a class="text-[15px] leading-snug hover:text-primary" href="<?php echo esc_url($link); ?>"><?php echo esc_html($p->get_name()); ?></a>
                                        <p class="mt-1 text-[12.5px] text-muted-foreground"><?php echo esc_html(nundia_price($p->get_price())); ?></p>
                                    </div>
                                    <button type="button" class="js-cart-set p-1 text-muted-foreground transition-colors hover:text-primary" data-qty="0" aria-label="Rimuovi"><?php echo nundia_icon('x', 'h-4 w-4', 1.5); ?></button>
                                </div>
                                <div class="mt-3 flex items-end justify-between">
                                    <div class="flex items-center rounded-sm bg-muted/60">
                                        <button type="button" class="js-cart-set flex h-9 w-9 items-center justify-center transition-colors hover:bg-white" data-qty="<?php echo $qty - 1; ?>" aria-label="-"><?php echo nundia_icon('minus', 'h-3 w-3', 2); ?></button>
                                        <span class="w-8 text-center text-[13px] font-medium tabular-nums"><?php echo $qty; ?></span>
                                        <button type="button" class="js-cart-set flex h-9 w-9 items-center justify-center transition-colors hover:bg-white" data-qty="<?php echo $qty + 1; ?>" aria-label="+"><?php echo nundia_icon('plus', 'h-3 w-3', 2); ?></button>
                                    </div>
                                    <p class="text-[14px] font-medium tabular-nums"><?php echo esc_html(nundia_price($item['line_subtotal'])); ?></p>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a class="mt-8 inline-flex items-center gap-2 text-[11px] uppercase tracking-[0.18em] text-muted-foreground transition-colors hover:text-foreground" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Continua gli acquisti</a>
            </div>
            <aside class="lg:sticky lg:top-32 lg:self-start">
                <div class="rounded-2xl bg-muted/40 p-6 sm:p-8">
                    <h2 class="text-[11px] font-semibold uppercase tracking-[0.22em] text-muted-foreground">Totale</h2>
                    <div class="mt-5 flex items-baseline justify-between">
                        <span class="text-[13px] text-muted-foreground">Subtotale</span>
                        <div class="text-right">
                            <span class="text-lg font-semibold tabular-nums text-foreground"><?php echo esc_html(nundia_price($cart->get_subtotal())); ?></span>
                            <span class="mt-0.5 block text-[11px] font-normal text-muted-foreground/85">(IVA inclusa) - escluse spese di trasporto</span>
                        </div>
                    </div>
                    <p class="mt-5 text-[12px] leading-relaxed text-muted-foreground"><?php echo esc_html(nd_opt('cart_note')); ?></p>
                    <a class="group mt-6 flex min-h-12 items-center justify-center gap-2 bg-primary px-6 text-[11px] uppercase tracking-[0.18em] text-white transition-colors hover:bg-primary/90" href="<?php echo esc_url(wc_get_checkout_url()); ?>">
                        Vai al pagamento<?php echo nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.6); ?>
                    </a>
                </div>
            </aside>
        </div>
    <?php endif; ?>
</section>
<?php get_footer();
