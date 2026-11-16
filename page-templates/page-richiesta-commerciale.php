<?php
/**
 * Template Name: Richiesta commerciale
 */
get_header();
$pid = get_queried_object_id();
$hero = nd_hero('richiesta-commerciale', $pid);
if (empty($hero['title'])) {
    $hero['title'] = 'Richiesta commerciale';
}
if (empty($hero['eyebrow'])) {
    $hero['eyebrow'] = 'B2B';
}
if (empty($hero['image'])) {
    $b2b_hero = nd_hero('b2b');
    $hero['image'] = !empty($b2b_hero['image']) ? $b2b_hero['image'] : '';
}
nundia_page_hero($hero);

$input = 'mt-1.5 w-full border border-input bg-white px-3 py-2.5 text-[14px] outline-none transition-colors focus:border-foreground';
$label = 'text-[10.5px] uppercase tracking-[0.16em] text-muted-foreground';

$eyebrow = nd('rc_eyebrow') ?: (nd('b2b_form_eyebrow') ?: 'Richiesta commerciale');
$heading = nd('rc_heading') ?: (nd('b2b_form_heading') ?: 'Parliamo del tuo assortimento');
$intro = nd('rc_intro') ?: (nd('b2b_form_intro') ?: 'Indica la tua attività e i prodotti di interesse: ti risponderemo con condizioni, formati disponibili e tempi di consegna.');
$bullets = nd_lines(nd('rc_bullets')) ?: nd_lines(nd('b2b_bullets'));
$email_orders = nd_opt('email_orders');
$email_suppliers = nd_opt('email_suppliers');
$form_title = nd('rc_form_title') ?: (nd('b2b_form_title') ?: 'Richiesta commerciale');
$form_sub = nd('rc_form_sub') ?: (nd('b2b_form_sub') ?: 'Rispondiamo alle richieste commerciali entro pochi giorni lavorativi.');
?>
<section class="container-ed py-10 lg:py-16" id="richiesta">
    <div class="grid gap-8 lg:grid-cols-[0.85fr_1.15fr] lg:gap-14 items-start">
        <div class="reveal">
            <?php if ($eyebrow): ?>
                <p class="eyebrow rule-accent"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($heading): ?>
                <h2 class="mt-4 text-[clamp(1.6rem,2.8vw,2.3rem)]"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>
            <?php if ($intro): ?>
                <p class="mt-3 max-w-md text-[14px] leading-relaxed text-muted-foreground"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>
            <?php if (!empty($bullets)): ?>
                <ul class="mt-6 space-y-2.5 border-t border-border pt-5 text-[13px] text-foreground/75">
                    <?php foreach ($bullets as $b): ?>
                        <li class="flex items-center gap-3"><span class="h-1 w-4 bg-primary" aria-hidden="true"></span><?php echo esc_html($b); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if ($email_orders || $email_suppliers): ?>
                <div class="mt-6 space-y-2 border-t border-border pt-5 text-[13px] text-foreground/75">
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
            <form class="js-ajax-form" data-action="nundia_ajax_b2b">
                <?php if ($form_title): ?>
                    <h3 class="text-xl font-bold"><?php echo esc_html($form_title); ?></h3>
                <?php endif; ?>
                <?php if ($form_sub): ?>
                    <p class="mt-1.5 text-[12.5px] text-muted-foreground"><?php echo esc_html($form_sub); ?></p>
                <?php endif; ?>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <label class="block"><span class="<?php echo $label; ?>">Azienda *</span><input name="company" class="<?php echo $input; ?>" required autocomplete="organization"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Partita IVA (VAT) *</span><input name="vat" class="<?php echo $input; ?>" required></label>
                    <label class="block"><span class="<?php echo $label; ?>">Referente *</span><input name="name" class="<?php echo $input; ?>" required autocomplete="name"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Email *</span><input name="email" type="email" class="<?php echo $input; ?>" required autocomplete="email"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Telefono *</span><input name="phone" type="tel" class="<?php echo $input; ?>" required autocomplete="tel"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Paese</span><input name="country" class="<?php echo $input; ?>" autocomplete="country-name"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Tipo di attività *</span>
                        <select name="business_type" class="<?php echo $input; ?>">
                            <option value="GDO">GDO</option>
                            <option value="HO.RE.CA.">HO.RE.CA.</option>
                            <option value="Importatore / distributore">Importatore / distributore</option>
                            <option value="Altro">Altro</option>
                        </select>
                    </label>
                    <label class="block"><span class="<?php echo $label; ?>">Volume stimato (opzionale)</span><input name="volume" class="<?php echo $input; ?>"></label>
                    <label class="block sm:col-span-2"><span class="<?php echo $label; ?>">Prodotti di interesse</span><input name="products" class="<?php echo $input; ?>"></label>
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
