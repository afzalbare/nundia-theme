<?php
/**
 * Template Name: B2B
 */
get_header();
$pid = get_queried_object_id();
nundia_page_hero(nd_hero('b2b', $pid));
$input = 'mt-2 w-full border border-input bg-white px-3 py-2.5 text-[14px] outline-none transition-colors focus:border-foreground';
$label = 'text-[10.5px] uppercase tracking-[0.16em] text-muted-foreground';
$collection = function_exists('wc_get_products') ? wc_get_products(array('featured' => true, 'status' => 'publish', 'limit' => 4, 'orderby' => 'date', 'order' => 'ASC')) : array();

$band_image = nd('b2b_band_image');
if ($band_image): ?>
<section class="relative overflow-hidden">
    <?php echo nundia_image($band_image, 'B2B', 'relative h-[36vh] min-h-[240px] w-full object-cover', 'full'); ?>
    <div class="absolute inset-0 bg-black/25" aria-hidden="true"></div>
</section>
<?php endif; ?>

<?php
$b2b_cards = nd_rows(nd('b2b_cards'));
if (!empty($b2b_cards)): ?>
<section class="container-ed py-16 lg:py-24">
    <div class="grid gap-12 lg:grid-cols-3">
        <?php foreach ($b2b_cards as $i => $c): ?>
            <div class="reveal"<?php echo $i ? ' style="transition-delay: ' . (90 * $i) . 'ms;"' : ''; ?>>
                <div class="border-t border-border pt-6">
                    <?php if (!empty($c[0])): ?>
                        <p class="font-mono text-[11px] tracking-[0.2em] text-primary"><?php echo esc_html($c[0]); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($c[1])): ?>
                        <h2 class="mt-3 text-xl"><?php echo esc_html($c[1]); ?></h2>
                    <?php endif; ?>
                    <?php if (!empty($c[2])): ?>
                        <p class="mt-3 text-[14px] leading-relaxed text-muted-foreground"><?php echo esc_html($c[2]); ?></p>
                    <?php endif; ?>
                    <?php if (nd('b2b_cards_draft') && nd_opt('show_draft_badges')): ?>
                        <p class="mt-4 text-[11px] uppercase tracking-[0.16em] text-muted-foreground/70"><?php echo esc_html(nd_opt('draft_label')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($collection)): ?>
<section class="border-y border-border bg-white py-16 lg:py-20">
    <div class="container-ed">
        <?php if ($coll_title = nd('b2b_collections')): ?>
            <div class="flex items-end justify-between gap-4">
                <h2 class="text-[clamp(1.5rem,2.6vw,2.1rem)]"><?php echo esc_html($coll_title); ?></h2>
            </div>
        <?php endif; ?>
        <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-3 lg:grid-cols-4 lg:gap-x-8">
            <?php foreach ($collection as $p) nundia_product_card($p); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
$eyebrow = nd('b2b_form_eyebrow');
$heading = nd('b2b_form_heading');
$intro = nd('b2b_form_intro');
$bullets = nd_lines(nd('b2b_bullets'));
$email_orders = nd_opt('email_orders');
$email_suppliers = nd_opt('email_suppliers');
$form_title = nd('b2b_form_title');
$form_sub = nd('b2b_form_sub');
?>
<section class="container-ed py-16 lg:py-24" id="richiesta">
    <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
        <div class="reveal">
            <?php if ($eyebrow): ?>
                <p class="eyebrow rule-accent"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($heading): ?>
                <h2 class="mt-5 text-[clamp(1.6rem,2.8vw,2.3rem)]"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>
            <?php if ($intro): ?>
                <p class="mt-4 max-w-md text-[14px] leading-relaxed text-muted-foreground"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>
            <?php if (!empty($bullets)): ?>
                <ul class="mt-8 space-y-3 border-t border-border pt-6 text-[13px] text-foreground/75">
                    <?php foreach ($bullets as $b): ?>
                        <li class="flex items-center gap-3"><span class="h-1 w-4 bg-primary" aria-hidden="true"></span><?php echo esc_html($b); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if ($email_orders || $email_suppliers): ?>
                <div class="mt-8 space-y-2 border-t border-border pt-6 text-[13px] text-foreground/75">
                    <?php if ($email_orders): ?>
                        <p><span class="text-muted-foreground">Ordini e clienti commerciali: </span><a href="mailto:<?php echo esc_attr($email_orders); ?>" class="hover:text-primary"><?php echo esc_html($email_orders); ?></a></p>
                    <?php endif; ?>
                    <?php if ($email_suppliers): ?>
                        <p><span class="text-muted-foreground">Fornitori: </span><a href="mailto:<?php echo esc_attr($email_suppliers); ?>" class="hover:text-primary"><?php echo esc_html($email_suppliers); ?></a></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="reveal" style="transition-delay: 120ms;">
            <form class="js-ajax-form border border-border bg-white p-6 sm:p-8" data-action="nundia_ajax_b2b">
                <?php if ($form_title): ?>
                    <h3 class="text-xl"><?php echo esc_html($form_title); ?></h3>
                <?php endif; ?>
                <?php if ($form_sub): ?>
                    <p class="mt-2 text-[12.5px] text-muted-foreground"><?php echo esc_html($form_sub); ?></p>
                <?php endif; ?>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <label class="block"><span class="<?php echo $label; ?>">Azienda *</span><input name="company" class="<?php echo $input; ?>" required></label>
                    <label class="block"><span class="<?php echo $label; ?>">Nome e cognome *</span><input name="name" class="<?php echo $input; ?>" required autocomplete="name"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Email *</span><input name="email" type="email" class="<?php echo $input; ?>" required autocomplete="email"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Telefono</span><input name="phone" class="<?php echo $input; ?>" autocomplete="tel"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Paese</span><input name="country" class="<?php echo $input; ?>" autocomplete="country-name"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Tipo di attività *</span>
                        <select name="business_type" class="<?php echo $input; ?>">
                            <option value="GDO">GDO</option>
                            <option value="HO.RE.CA.">HO.RE.CA.</option>
                            <option value="Importatore / distributore">Importatore / distributore</option>
                            <option value="Altro">Altro</option>
                        </select>
                    </label>
                    <label class="block sm:col-span-2"><span class="<?php echo $label; ?>">Prodotti di interesse</span><input name="products" class="<?php echo $input; ?>"></label>
                    <label class="block sm:col-span-2"><span class="<?php echo $label; ?>">Volume stimato (opzionale)</span><input name="volume" class="<?php echo $input; ?>"></label>
                    <label class="block sm:col-span-2"><span class="<?php echo $label; ?>">Messaggio</span><textarea name="message" rows="4" class="<?php echo $input; ?>"></textarea></label>
                </div>
                <button type="submit" class="group mt-6 inline-flex min-h-12 w-full items-center justify-center gap-2 bg-primary px-6 text-[11px] uppercase tracking-[0.18em] text-white transition-colors hover:bg-primary/90 disabled:opacity-60 sm:w-auto">
                    Invia<?php echo nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.8); ?>
                </button>
                <p class="js-form-msg mt-4 text-[13px]" role="status" hidden></p>
            </form>
        </div>
    </div>
</section>
<?php get_footer();
