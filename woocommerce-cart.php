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
                                        <?php 
                                        $item_format = !empty($item['format']) ? $item['format'] : nundia_product_weight($item['product_id']);
                                        if ($item_format): ?>
                                            <p class="mt-0.5 text-[11.5px] font-medium text-muted-foreground"><?php echo esc_html($item_format); ?></p>
                                        <?php endif; ?>
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
            <?php 
            $subtotal = floatval($cart->get_subtotal());
            $shipping_rates = nundia_shipping_rates();
            $free_threshold = nundia_free_shipping_threshold();
            $default_country = 'IT';
            $default_shipping = ($free_threshold > 0 && $subtotal >= $free_threshold) ? 0.0 : $shipping_rates[$default_country]['cost'];
            $grand_total = $subtotal + $default_shipping;
            ?>
            <aside class="lg:sticky lg:top-32 lg:self-start">
                <div class="rounded-2xl bg-muted/40 p-6 sm:p-8">
                    <h2 class="text-[11px] font-semibold uppercase tracking-[0.22em] text-muted-foreground">Riepilogo Ordine</h2>
                    
                    <!-- Subtotale prodotti -->
                    <div class="mt-5 flex items-baseline justify-between pb-3.5 border-b border-border/80">
                        <span class="text-[13px] text-muted-foreground">Subtotale prodotti</span>
                        <span class="text-[15px] font-semibold tabular-nums text-foreground"><?php echo esc_html(nundia_price($subtotal)); ?></span>
                    </div>

                    <!-- Destinazione spedizione -->
                    <div class="mt-4">
                        <label for="js-shipping-country" class="block text-[11px] font-semibold uppercase tracking-[0.16em] text-muted-foreground mb-2">Destinazione spedizione</label>
                        <select id="js-shipping-country" 
                                class="w-full h-11 border border-input bg-white px-3 text-[13px] outline-none transition-colors focus:border-foreground"
                                data-subtotal="<?php echo esc_attr($subtotal); ?>"
                                data-threshold="<?php echo esc_attr($free_threshold); ?>">
                            <?php foreach ($shipping_rates as $code => $rate): ?>
                                <option value="<?php echo esc_attr($code); ?>" data-cost="<?php echo esc_attr($rate['cost']); ?>" <?php selected($code, $default_country); ?>>
                                    <?php echo esc_html($rate['label']); ?> (<?php echo esc_html(nundia_price($rate['cost'])); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Spese di trasporto esplicite -->
                    <div class="mt-3.5 flex items-center justify-between text-[13px] pb-4 border-b border-border">
                        <span class="text-muted-foreground">Spese di trasporto</span>
                        <span class="js-shipping-cost font-semibold tabular-nums text-foreground">
                            <?php echo $default_shipping == 0 ? '<span class="text-green-600">GRATUITA (0,00 €)</span>' : esc_html(nundia_price($default_shipping)); ?>
                        </span>
                    </div>

                    <!-- Costo totale, IVA e trasporto inclusi (richiesto esplicitamente per legge) -->
                    <div class="mt-5 pt-1">
                        <div class="text-[11px] font-bold uppercase tracking-[0.12em] text-foreground leading-tight">
                            COSTO TOTALE, IVA E TRASPORTO INCLUSI
                        </div>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-[12px] text-muted-foreground">Totale finale</span>
                            <span class="js-grand-total text-2xl font-bold tabular-nums text-primary">
                                <?php echo esc_html(nundia_price($grand_total)); ?>
                            </span>
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
