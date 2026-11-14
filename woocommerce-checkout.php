<?php
/**
 * Checkout (reference layout). Submitting creates a WooCommerce order paid by bank transfer
 * (see nundia_handle_checkout() in functions.php). Orders appear in WooCommerce → Ordini.
 */
get_header();
$input = 'mt-2 w-full border border-input bg-background px-3 py-2.5 text-[14px] outline-none transition-colors focus:border-foreground';
$label = 'text-[10.5px] uppercase tracking-[0.16em] text-muted-foreground';
$cart = WC()->cart;

/* Order received */
if (is_wc_endpoint_url('order-received')) {
    $order_id = absint(get_query_var('order-received'));
    $order = $order_id ? wc_get_order($order_id) : null;
    $key = isset($_GET['key']) ? wc_clean(wp_unslash($_GET['key'])) : '';
    $valid = $order && hash_equals($order->get_order_key(), $key);
    nundia_light_hero(array('eyebrow' => 'Ordine ricevuto', 'title' => 'Grazie per il tuo ordine', 'subtitle' => $valid ? 'Ordine n. ' . $order->get_order_number() . ' · ' . nundia_price($order->get_total()) : ''));
    ?>
    <section class="container-ed py-12 lg:py-16">
        <div class="max-w-2xl">
            <p class="text-[15px] leading-[1.85] text-foreground/85"><?php echo esc_html(nd_opt('payment_note')); ?></p>
            <?php if ($valid): ?>
                <ul class="mt-8 divide-y divide-border border-y border-border">
                    <?php foreach ($order->get_items() as $item): ?>
                        <li class="flex items-center justify-between py-3 text-[14px]"><span><?php echo esc_html($item->get_name()); ?> × <?php echo (int) $item->get_quantity(); ?></span><span class="tabular-nums"><?php echo esc_html(nundia_price($item->get_total())); ?></span></li>
                    <?php endforeach; ?>
                </ul>
                <?php $bacs = get_option('woocommerce_bacs_accounts');
                if (!empty($bacs[0]['iban'])): ?>
                    <div class="mt-8 border border-primary bg-primary/5 p-5 text-[13px]">
                        <p class="text-[10px] uppercase tracking-[0.16em] text-muted-foreground">Coordinate bancarie</p>
                        <p class="mt-2"><?php echo esc_html($bacs[0]['account_name']); ?> · IBAN <?php echo esc_html($bacs[0]['iban']); ?><?php echo !empty($bacs[0]['bic']) ? ' · BIC ' . esc_html($bacs[0]['bic']) : ''; ?></p>
                        <p class="mt-1 text-muted-foreground">Causale: ordine n. <?php echo esc_html($order->get_order_number()); ?></p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <a class="mt-10 inline-flex min-h-12 items-center bg-primary px-7 text-[11px] uppercase tracking-[0.18em] text-white transition-colors hover:bg-primary/90" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Continua gli acquisti</a>
        </div>
    </section>
    <?php
    get_footer();
    return;
}

nundia_light_hero(nd_hero('checkout', get_queried_object_id()));

if (!$cart || $cart->is_empty()): ?>
    <section class="container-ed py-12 lg:py-16">
        <div class="py-16 text-center">
            <p class="text-[15px] text-muted-foreground">Il tuo carrello è vuoto.</p>
            <a class="mt-7 inline-flex min-h-12 items-center bg-primary px-7 text-[11px] uppercase tracking-[0.18em] text-white transition-colors hover:bg-primary/90" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Continua gli acquisti</a>
        </div>
    </section>
<?php get_footer(); return; endif;

$notices = wc_get_notices('error');
wc_clear_notices();
$old = function ($k, $d = '') { return isset($_POST[$k]) ? esc_attr(sanitize_text_field(wp_unslash($_POST[$k]))) : esc_attr($d); };
?>
<section class="container-ed py-12 lg:py-16">
    <?php if ($notices): ?>
        <div class="mb-8 border border-primary bg-primary/5 p-4 text-[13px] text-primary" role="alert">
            <?php foreach ($notices as $n) echo '<p>' . wp_kses_post(is_array($n) ? $n['notice'] : $n) . '</p>'; ?>
        </div>
    <?php endif; ?>
    <form method="post" class="grid gap-12 lg:grid-cols-[1.4fr_1fr] lg:gap-16">
        <?php wp_nonce_field('nundia_checkout', '_nd_nonce'); ?>
        <input type="hidden" name="nundia_checkout" value="1">
        <div class="space-y-10">
            <fieldset>
                <legend class="eyebrow">Dati di contatto</legend>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <label class="block"><span class="<?php echo $label; ?>">Nome e cognome *</span><input type="text" name="nd_name" required autocomplete="name" class="<?php echo $input; ?>" value="<?php echo $old('nd_name'); ?>"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Email *</span><input type="email" name="nd_email" required autocomplete="email" class="<?php echo $input; ?>" value="<?php echo $old('nd_email'); ?>"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Telefono</span><input type="text" name="nd_phone" autocomplete="tel" class="<?php echo $input; ?>" value="<?php echo $old('nd_phone'); ?>"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Paese</span><input type="text" name="nd_country" autocomplete="country-name" class="<?php echo $input; ?>" value="<?php echo $old('nd_country', 'Italia'); ?>"></label>
                </div>
            </fieldset>
            <fieldset>
                <legend class="eyebrow">Indirizzo di spedizione</legend>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <label class="block sm:col-span-2"><span class="<?php echo $label; ?>">Indirizzo *</span><input type="text" name="nd_address" required autocomplete="street-address" class="<?php echo $input; ?>" value="<?php echo $old('nd_address'); ?>"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Città *</span><input type="text" name="nd_city" required autocomplete="address-level2" class="<?php echo $input; ?>" value="<?php echo $old('nd_city'); ?>"></label>
                    <label class="block"><span class="<?php echo $label; ?>">CAP</span><input type="text" name="nd_postcode" autocomplete="postal-code" class="<?php echo $input; ?>" value="<?php echo $old('nd_postcode'); ?>"></label>
                    <label class="block sm:col-span-2"><span class="<?php echo $label; ?>">Messaggio</span><textarea name="nd_message" rows="3" class="<?php echo $input; ?>"></textarea></label>
                </div>
                <p class="mt-4 max-w-lg text-[12px] leading-relaxed text-muted-foreground"><?php echo esc_html(nd_opt('shipping_note')); ?></p>
            </fieldset>
            <fieldset>
                <legend class="eyebrow">Metodo di pagamento</legend>
                <div class="mt-5 border border-primary bg-primary/5 p-5">
                    <p class="text-[13.5px] font-medium">Bonifico bancario</p>
                    <p class="mt-1.5 text-[12.5px] leading-relaxed text-muted-foreground"><?php echo esc_html(nd_opt('payment_note')); ?></p>
                    <div class="mt-4 border-t border-primary/20 pt-4">
                        <p class="text-[10px] uppercase tracking-[0.16em] text-muted-foreground">Pagamenti accettati</p>
                        <p class="mt-1.5 text-[12.5px] text-foreground/80"><?php echo esc_html(nd_opt('payment_methods')); ?></p>
                    </div>
                </div>
            </fieldset>
        </div>
        <aside class="lg:sticky lg:top-32 lg:self-start">
            <div class="border border-border bg-white p-6">
                <h2 class="eyebrow">Carrello</h2>
                <ul class="mt-5 space-y-4">
                    <?php foreach ($cart->get_cart() as $item): $p = $item['data']; ?>
                        <li class="flex items-center gap-3">
                            <div class="h-14 w-12 shrink-0 overflow-hidden bg-white"><?php echo nundia_post_image($item['product_id'], $p->get_name(), 'relative h-full w-full', 'woocommerce_thumbnail'); ?></div>
                            <div class="flex-1">
                                <p class="text-[13px] leading-tight"><?php echo esc_html($p->get_name()); ?></p>
                                <p class="text-[11.5px] text-muted-foreground">× <?php echo (int) $item['quantity']; ?></p>
                            </div>
                            <p class="text-[12.5px] tabular-nums"><?php echo esc_html(nundia_price($item['line_subtotal'])); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="mt-6 flex items-baseline justify-between border-t border-border pt-5">
                    <span class="text-[12px] uppercase tracking-[0.16em] text-muted-foreground">Totale</span>
                    <span class="text-lg tabular-nums"><?php echo esc_html(nundia_price($cart->get_subtotal())); ?></span>
                </div>
                <button type="submit" class="mt-6 flex min-h-12 w-full items-center justify-center gap-2 bg-primary px-6 text-[11px] uppercase tracking-[0.18em] text-white transition-colors hover:bg-primary/90 disabled:opacity-60">
                    Conferma ordine<?php echo nundia_icon('arrow-right', 'h-3.5 w-3.5', 1.6); ?>
                </button>
            </div>
        </aside>
    </form>
</section>
<?php get_footer();
