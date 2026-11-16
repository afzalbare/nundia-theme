<?php

/**
 * Footer – mirrors the reference footer with the client's changes:
 * lockup order NUNDÌA / Sapori di Sicilia / Typhoeus Group, no flags,
 * no "© … Tutti i diritti riservati" line, corrected company line.
 */
if (!defined('ABSPATH')) exit;

$arrow = nundia_icon('arrow-right', 'h-3 w-3 -translate-x-1 opacity-0 transition-all duration-300 group-hover:translate-x-0 group-hover:opacity-100', 1.6);
$link_class = 'group inline-flex items-center gap-1.5 text-sm text-white/85 transition-colors hover:text-white';
$cols_defaults = array(
    'footer_col_1' => array('Esplora', array(
        array('Shop', '/shop/'),
        array('Ricette', '/ricette/'),
        array('FAQ', '/faq/'),
        array('Diventa Partner', '/diventa-partner/'),
    )),
    'footer_col_2' => array('La maison', array(
        array('Chi siamo', '/chi-siamo/'),
        array('B2B', '/b2b/'),
        array('Contatti', '/contatti/'),
    )),
    'footer_col_3' => array('Informazioni legali', array(
        array('Privacy policy', '/privacy/'),
        array('Cookie policy', '/cookie-policy/'),
        array('Termini e condizioni', '/termini/'),
        array('Spedizioni e resi', '/spedizioni-e-resi/'),
    )),
);

$footer_cols = array();
$nav_locs = get_nav_menu_locations();
foreach ($cols_defaults as $loc => $def) {
    list($def_title, $def_items) = $def;
    if (!empty($nav_locs[$loc])) {
        $menu_obj = wp_get_nav_menu_object($nav_locs[$loc]);
        $menu_items = wp_get_nav_menu_items($nav_locs[$loc]);
        if (!empty($menu_items)) {
            $links = array();
            foreach ($menu_items as $mi) {
                if ((int) $mi->menu_item_parent === 0) {
                    if (stripos($mi->title, 'Notizie') !== false || stripos($mi->url, '/journal') !== false) {
                        continue;
                    }
                    $links[] = array('label' => $mi->title, 'url' => $mi->url);
                }
            }
            $footer_cols[] = array(
                'title'    => $menu_obj ? $menu_obj->name : $def_title,
                'links'    => $links,
                'is_legal' => ($loc === 'footer_col_3'),
            );
            continue;
        }
    }
    $links = array();
    foreach ($def_items as $it) {
        $links[] = array('label' => $it[0], 'url' => home_url($it[1]));
    }
    $footer_cols[] = array(
        'title'    => $def_title,
        'links'    => $links,
        'is_legal' => ($loc === 'footer_col_3'),
    );
}

$socials = array('instagram' => 'Instagram', 'facebook' => 'Facebook', 'linkedin' => 'LinkedIn');
?>
</main>

<footer class="bg-primary text-white">
    <div class="container-ed py-8 lg:py-10">
        <div class="nd-footer-top grid gap-6 lg:grid-cols-[1.25fr_2.4fr] lg:gap-8 lg:items-center">
            <div>
                <?php
                $footer_logo = nd_opt('footer_logo');
                if (!$footer_logo) {
                    $footer_logo = nundia_img('nundia-logo-light.png') . '?v=' . filemtime(get_template_directory() . '/assets/images/nundia-logo-light.png');
                }
                $brand_name = nd_opt('brand_name') ?: get_bloginfo('name');
                $brand_parts = array_filter(array($brand_name, nd_opt('brand_slogan')));
                $brand_alt = implode(' – ', $brand_parts);
                ?>
                <a class="nd-footer-logo inline-block" aria-label="<?php echo esc_attr($brand_name); ?> – home" href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php echo esc_attr($brand_alt); ?>" width="789" height="779" loading="lazy">
                </a>
                <?php
                $active_socials = array();
                foreach ($socials as $key => $label) {
                    $url = nd_opt($key);
                    if ($url) {
                        $active_socials[$key] = array('url' => $url, 'label' => $label);
                    }
                }
                if (!empty($active_socials)): ?>
                <div class="mt-3 flex items-center gap-2">
                    <?php foreach ($active_socials as $key => $data): ?>
                        <a href="<?php echo esc_url($data['url']); ?>" target="_blank" rel="noreferrer noopener" aria-label="<?php echo esc_attr($data['label']); ?> NUNDÌA" class="flex h-8 w-8 items-center justify-center rounded-full border border-white/35 text-white transition-colors duration-300 hover:border-white hover:bg-white hover:text-primary">
                            <?php echo nundia_icon($key, 'h-3.5 w-3.5', 1.5); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="nd-footer-links grid gap-6 sm:grid-cols-3 lg:self-center">
                <?php foreach ($footer_cols as $col): ?>
                    <div>
                        <p class="text-sm uppercase tracking-[0.25em] text-white font-medium"><?php echo esc_html($col['title']); ?></p>
                        <ul class="mt-2 space-y-1">
                            <?php foreach ($col['links'] as $l): ?>
                                <li><a class="<?php echo $link_class; ?>" href="<?php echo esc_url($l['url']); ?>"><?php echo esc_html($l['label']); ?><?php echo $arrow; ?></a></li>
                            <?php endforeach; ?>
                            <?php if (!empty($col['is_legal'])): ?>
                                <li><button type="button" class="js-cookie-prefs text-sm text-white/60 transition-colors hover:text-white">Preferenze cookie</button></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php 
        $footer_legal = nd_opt('footer_legal');
        $footer_note = nd_opt('footer_note');
        if ($footer_legal || $footer_note): 
        ?>
        <div class="mt-3.5 space-y-0.5 border-t border-white/20 pt-2.5 text-sm leading-relaxed text-white/70">
            <?php if ($footer_legal): ?>
                <p><?php echo esc_html($footer_legal); ?></p>
            <?php endif; ?>
            <?php if ($footer_note): ?>
                <p><?php echo esc_html($footer_note); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</footer>

<div class="nd-cookie fixed inset-x-0 bottom-0 z-[9999] px-3 pb-3 sm:px-5 sm:pb-5" role="dialog" aria-live="polite" aria-label="La tua privacy" hidden>
    <div class="mx-auto max-w-3xl border border-border bg-white shadow-[0_-10px_50px_rgba(26,26,26,0.12)]">
        <div class="flex items-start gap-4 p-5 sm:p-7">
            <?php echo nundia_icon('cookie', 'mt-1 hidden h-5 w-5 shrink-0 text-primary sm:block', 1.4); ?>
            <div class="flex-1">
                <div class="flex items-start justify-between gap-4">
                    <h2 class="text-lg">La tua privacy</h2>
                    <button type="button" class="js-cookie-close" aria-label="Chiudi"><?php echo nundia_icon('x', 'h-4 w-4 text-muted-foreground', 2); ?></button>
                </div>
                <p class="mt-2 text-[13px] leading-relaxed text-muted-foreground">Usiamo cookie tecnici necessari al funzionamento del sito. Con il tuo consenso possiamo usare cookie analitici e di marketing. Puoi cambiare scelta in qualsiasi momento.</p>
                <div class="nd-cookie-prefs mt-4 space-y-2 border-t border-border pt-4 text-[13px]" hidden>
                    <label class="flex items-center gap-3"><input type="checkbox" checked disabled> Necessari</label>
                    <label class="flex items-center gap-3"><input type="checkbox" name="analytics"> Analitici</label>
                    <label class="flex items-center gap-3"><input type="checkbox" name="marketing"> Marketing</label>
                </div>
                <div class="mt-5 flex flex-wrap items-center gap-2.5">
                    <button type="button" data-consent="all" class="min-h-11 bg-primary px-5 text-[11px] uppercase tracking-[0.18em] text-white transition-colors hover:bg-primary/90">Accetta tutti</button>
                    <button type="button" data-consent="necessary" class="min-h-11 border border-foreground/25 px-5 text-[11px] uppercase tracking-[0.18em] text-foreground transition-colors hover:border-foreground">Rifiuta i non necessari</button>
                    <button type="button" data-consent="custom" class="min-h-11 px-3 text-[11px] uppercase tracking-[0.18em] text-muted-foreground underline underline-offset-4 hover:text-foreground">Personalizza</button>
                    <a class="min-h-11 px-1 text-[11px] uppercase tracking-[0.18em] text-muted-foreground underline underline-offset-4 hover:text-foreground" href="<?php echo esc_url(home_url('/cookie-policy/')); ?>">Cookie policy</a>
                </div>
            </div>
        </div>
    </div>
</div>

</div><!-- /.flex wrapper -->
<div id="google_translate_element" class="hidden" aria-hidden="true"></div>
<?php wp_footer(); ?>
</body>

</html>