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
                    <div class="mt-8 border-t border-border pt-6 text-[13.5px]">
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
            <fieldset class="mt-10">
                <legend class="eyebrow">Metodo di pagamento</legend>
                <div class="mt-4 space-y-4">
                    <div>
                        <p class="text-[14px] font-semibold text-foreground">Bonifico bancario</p>
                        <p class="mt-1.5 max-w-lg text-[13px] leading-relaxed text-muted-foreground"><?php echo esc_html(nd_opt('payment_note')); ?></p>
                    </div>

                    <div class="pt-2">
                        <p class="text-[10.5px] uppercase tracking-[0.18em] font-semibold text-muted-foreground mb-3">Pagamenti accettati</p>
                        
                        <!-- Official Payment Brand Badges -->
                        <div class="flex flex-wrap items-center gap-2.5">
                            <!-- VISA -->
                            <div class="flex h-8 w-13 items-center justify-center rounded bg-white px-2.5 py-1 border border-border shadow-xs" title="Visa">
                                <svg class="h-3.5 w-auto" viewBox="0 0 48 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.88 1.15L12.56 15.2H8.38L5.14 3.75C4.94 3 4.71 2.72 4.09 2.4C3.06 1.86 1.45 1.35 0 1.05L0.09 0.65H7.07C7.99 0.65 8.79 1.25 9 2.22L10.74 11.45L14.94 0.65H18.88V1.15ZM35.48 10.37C35.5 6.42 30 6.2 30.04 4.45C30.06 3.92 30.58 3.35 31.7 3.2C32.25 3.12 33.8 3.07 35.54 3.87L36.23 0.67C35.29 0.32 34.07 0 32.55 0C28.61 0 25.84 2.1 25.81 5.1C25.78 7.32 27.76 8.55 29.27 9.3C30.82 10.07 31.35 10.55 31.34 11.22C31.32 12.25 30.07 12.7 28.93 12.72C26.9 12.75 25.73 12.17 24.8 11.75L24.08 15.12C25.02 15.55 26.75 15.92 28.53 15.95C32.74 15.95 35.46 13.87 35.48 10.37ZM45.91 15.2H49.57L46.38 0.65H43.01C42.24 0.65 41.6 1.09 41.32 1.77L35.29 15.2H39.46L40.29 12.92H45.39L45.91 15.2ZM41.45 9.77L43.55 4.02L44.75 9.77H41.45ZM24.23 0.65L20.95 15.2H17.01L20.29 0.65H24.23Z" fill="#1A1F71"/>
                                </svg>
                            </div>
                            <!-- Mastercard -->
                            <div class="flex h-8 w-13 items-center justify-center rounded bg-white px-2 py-1 border border-border shadow-xs" title="Mastercard">
                                <svg class="h-4.5 w-auto" viewBox="0 0 32 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10" cy="10" r="10" fill="#EB001B"/>
                                    <circle cx="22" cy="10" r="10" fill="#F79E1B"/>
                                    <path d="M16 2.87C17.78 4.67 18.9 7.2 18.9 10C18.9 12.8 17.78 15.33 16 17.13C14.22 15.33 13.1 12.8 13.1 10C13.1 7.2 14.22 4.67 16 2.87Z" fill="#FF5F00"/>
                                </svg>
                            </div>
                            <!-- PayPal -->
                            <div class="flex h-8 w-16 items-center justify-center rounded bg-white px-2.5 py-1 border border-border shadow-xs" title="PayPal">
                                <svg class="h-3.5 w-auto" viewBox="0 0 60 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.4 1.2H2.2C1.9 1.2 1.6 1.4 1.5 1.7L0 14.8H3.3L4.1 8.8C4.2 8.4 4.5 8.2 4.9 8.2H6.6C9.8 8.2 11.6 6.6 12 3.6C12.3 2 11.4 1.2 7.4 1.2Z" fill="#003087"/>
                                    <path d="M8.2 4.5C8 6.5 6.6 7.6 4.6 7.6H3.5L2.6 14.8H0.1L0.9 8.8C1 8.4 1.3 8.2 1.7 8.2H3.4C6.6 8.2 8.4 6.6 8.8 3.6C9.1 2 8.2 1.2 4.2 1.2H0.8L0.9 0.6H4.2C8.2 0.6 9.4 1.7 8.2 4.5Z" fill="#0079C1"/>
                                    <text x="14" y="12" font-family="system-ui, -apple-system, sans-serif" font-size="11.5" font-weight="800" font-style="italic" fill="#003087">Pay<tspan fill="#0079C1">Pal</tspan></text>
                                </svg>
                            </div>
                            <!-- American Express -->
                            <div class="flex h-8 w-13 items-center justify-center rounded bg-white px-2 py-1 border border-border shadow-xs" title="American Express">
                                <svg class="h-4.5 w-auto" viewBox="0 0 32 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="32" height="20" rx="2" fill="#006FCF"/>
                                    <path d="M12.8 9.5L14.2 6.5H16.8L14.6 11.2L16.8 15.5H14.2L12.8 12.8L11.4 15.5H8.9L11.1 11.2L8.9 6.5H11.4L12.8 9.5ZM4.2 15.5L7.2 6.5H9.6L6.6 15.5H4.2ZM24.5 15.5V6.5H30V8.5H26.8V10H29.5V12H26.8V13.5H30V15.5H24.5ZM17.5 15.5V6.5H20.2L22 12.2L23.8 6.5H26.5V15.5H24.2V9.8L22.6 14.5H21.4L19.8 9.8V15.5H17.5Z" fill="white"/>
                                </svg>
                            </div>
                            <!-- Maestro -->
                            <div class="flex h-8 w-13 items-center justify-center rounded bg-white px-2 py-1 border border-border shadow-xs" title="Maestro">
                                <svg class="h-4.5 w-auto" viewBox="0 0 32 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10" cy="10" r="10" fill="#EB001B"/>
                                    <circle cx="22" cy="10" r="10" fill="#00A2E5"/>
                                    <path d="M16 2.87C17.78 4.67 18.9 7.2 18.9 10C18.9 12.8 17.78 15.33 16 17.13C14.22 15.33 13.1 12.8 13.1 10C13.1 7.2 14.22 4.67 16 2.87Z" fill="#7B338D"/>
                                </svg>
                            </div>
                            <!-- Postepay -->
                            <div class="flex h-8 w-15 items-center justify-center rounded bg-white px-2 py-1 border border-border shadow-xs" title="Postepay">
                                <svg class="h-4.5 w-auto" viewBox="0 0 44 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="44" height="20" rx="2.5" fill="#FEDB00"/>
                                    <text x="3" y="14" font-family="system-ui, -apple-system, sans-serif" font-size="9" font-weight="900" fill="#003B7A">poste<tspan fill="#E30613">pay</tspan></text>
                                </svg>
                            </div>
                        </div>

                        <p class="mt-2.5 text-[12px] text-muted-foreground/90">
                            <?php echo esc_html(nd_opt('payment_methods')); ?>
                        </p>
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
                                <?php 
                                $item_format = !empty($item['format']) ? $item['format'] : nundia_product_weight($item['product_id']);
                                if ($item_format): ?>
                                    <p class="text-[11px] text-muted-foreground/80"><?php echo esc_html($item_format); ?></p>
                                <?php endif; ?>
                                <p class="text-[11.5px] text-muted-foreground">× <?php echo (int) $item['quantity']; ?></p>
                            </div>
                            <p class="text-[12.5px] tabular-nums"><?php echo esc_html(nundia_price($item['line_subtotal'])); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php 
                $subtotal = floatval($cart->get_subtotal());
                $rates = nundia_shipping_rates();
                $th = nundia_free_shipping_threshold();
                $default_country = 'IT';
                $shipping_cost = ($th > 0 && $subtotal >= $th) ? 0.0 : $rates[$default_country]['cost'];
                $checkout_total = $subtotal + $shipping_cost;
                ?>
                <input type="hidden" name="nd_shipping_country" id="js-checkout-country-input" value="IT">
                <input type="hidden" name="nd_shipping_cost" id="js-checkout-shipping-input" value="<?php echo esc_attr($shipping_cost); ?>">

                <div class="mt-6 border-t border-border pt-4 space-y-2.5">
                    <div class="flex items-center justify-between text-[13px]">
                        <span class="text-muted-foreground">Subtotale</span>
                        <span class="tabular-nums font-medium"><?php echo esc_html(nundia_price($subtotal)); ?></span>
                    </div>
                    <div class="flex items-center justify-between text-[13px]">
                        <span class="text-muted-foreground">Spese di trasporto</span>
                        <span class="js-checkout-shipping tabular-nums font-semibold text-foreground">
                            <?php echo $shipping_cost == 0 ? '<span class="text-green-600">GRATUITA</span>' : esc_html(nundia_price($shipping_cost)); ?>
                        </span>
                    </div>
                    <div class="border-t border-border pt-3.5">
                        <span class="block text-[10.5px] uppercase tracking-[0.14em] font-bold text-foreground leading-tight">COSTO TOTALE, IVA E TRASPORTO INCLUSI</span>
                        <div class="mt-1.5 flex items-baseline justify-between">
                            <span class="text-[12px] text-muted-foreground">Totale</span>
                            <span class="js-checkout-total text-xl font-bold tabular-nums text-primary"><?php echo esc_html(nundia_price($checkout_total)); ?></span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="mt-6 flex min-h-12 w-full items-center justify-center gap-2 bg-primary px-6 text-[11px] uppercase tracking-[0.18em] text-white transition-colors hover:bg-primary/90 disabled:opacity-60">
                    Conferma ordine<?php echo nundia_icon('arrow-right', 'h-3.5 w-3.5', 1.6); ?>
                </button>
            </div>
        </aside>
    </form>
</section>
<?php get_footer();
