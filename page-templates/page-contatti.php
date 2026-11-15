<?php
/**
 * Template Name: Contatti
 */
get_header();
$pid = get_queried_object_id();
nundia_page_hero(nd_hero('contatti', $pid));
$input = 'mt-2 w-full border border-input bg-white px-3 py-2.5 text-[14px] outline-none transition-colors focus:border-foreground';
$label = 'text-[10.5px] uppercase tracking-[0.16em] text-muted-foreground';
$tel = function ($n) { return preg_replace('/[^0-9+]/', '', $n); };
$items = array(
    array('mail', 'Informazioni generali', nd_opt('email_general'), 'mailto:' . nd_opt('email_general')),
    array('mail', 'Ordini e clienti commerciali', nd_opt('email_orders'), 'mailto:' . nd_opt('email_orders')),
    array('mail', 'Fornitori', nd_opt('email_suppliers'), 'mailto:' . nd_opt('email_suppliers')),
    array('phone', 'Telefono', nd_opt('phone'), 'tel:' . $tel(nd_opt('phone'))),
    array('message-circle', 'WhatsApp (dall’estero)', nd_opt('mobile'), 'https://wa.me/' . ltrim($tel(nd_opt('mobile')), '+')),
);

$valid_items = array_filter($items, function($it) {
    return !empty($it[2]);
});

$social_label = nd('contact_social_label');
$social_text = nd('contact_social_text');
$social_links = array();
foreach (array('instagram' => 'Instagram', 'facebook' => 'Facebook', 'linkedin' => 'LinkedIn') as $k => $l) {
    if ($url = nd_opt($k)) {
        $social_links[$k] = array('url' => $url, 'label' => $l);
    }
}
$has_social = ($social_label || $social_text || !empty($social_links));
?>
<section class="container-ed py-10 lg:py-14">
    <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16 items-start">
        <div class="reveal">
            <?php if (!empty($valid_items)): ?>
                <div class="space-y-4 sm:space-y-5">
                    <?php foreach ($valid_items as $it): ?>
                        <div>
                            <p class="flex items-center gap-2 text-[10px] uppercase tracking-[0.2em] text-muted-foreground"><?php echo nundia_icon($it[0], 'h-3.5 w-3.5', 1.5); ?><?php echo esc_html($it[1]); ?></p>
                            <?php if ($it[3]): ?>
                                <a href="<?php echo esc_url($it[3]); ?>" class="mt-1 block text-[15px] font-medium text-foreground transition-colors hover:text-primary"><?php echo esc_html($it[2]); ?></a>
                            <?php else: ?>
                                <p class="mt-1 text-[15px] font-medium text-foreground"><?php echo esc_html($it[2]); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($has_social && $social_text !== 'Da definire'): ?>
                <div class="mt-8 pt-2">
                    <?php if ($social_label && $social_label !== 'Contatti'): ?>
                        <p class="eyebrow"><?php echo esc_html($social_label); ?></p>
                    <?php endif; ?>
                    <div class="mt-3 flex flex-wrap gap-4 text-[12.5px] text-foreground/75">
                        <?php if ($social_text): ?>
                            <span class="italic text-muted-foreground"><?php echo esc_html($social_text); ?></span>
                        <?php else: foreach ($social_links as $k => $data): ?>
                            <a href="<?php echo esc_url($data['url']); ?>" target="_blank" rel="noreferrer noopener" class="inline-flex items-center gap-2 hover:text-primary"><?php echo nundia_icon($k, 'h-4 w-4', 1.5); ?><?php echo esc_html($data['label']); ?></a>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="reveal" style="transition-delay: 120ms;">
            <form class="js-ajax-form" data-action="nundia_ajax_contact">
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block"><span class="<?php echo $label; ?>">Nome e cognome *</span><input name="name" class="<?php echo $input; ?>" required autocomplete="name"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Email *</span><input name="email" type="email" class="<?php echo $input; ?>" required autocomplete="email"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Telefono</span><input name="phone" class="<?php echo $input; ?>" autocomplete="tel"></label>
                    <label class="block"><span class="<?php echo $label; ?>">Oggetto</span><input name="subject" class="<?php echo $input; ?>"></label>
                    <label class="block sm:col-span-2"><span class="<?php echo $label; ?>">Messaggio *</span><textarea name="message" rows="5" class="<?php echo $input; ?>" required></textarea></label>
                </div>
                <button type="submit" class="group mt-6 inline-flex min-h-12 w-full items-center justify-center gap-2 bg-primary px-6 text-[11px] uppercase tracking-[0.18em] text-white transition-colors hover:bg-primary/90 disabled:opacity-60 sm:w-auto">
                    Invia<?php echo nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.8); ?>
                </button>
                <p class="js-form-msg mt-4 text-[13px]" role="status" hidden></p>
            </form>
            <?php if ($privacy_note = nd('contact_privacy_note')): ?>
                <p class="mt-4 text-[11.5px] leading-relaxed text-muted-foreground"><?php echo esc_html($privacy_note); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php get_footer();
