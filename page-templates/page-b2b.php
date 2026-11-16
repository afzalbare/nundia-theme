<?php
/**
 * Template Name: B2B
 */
get_header();
$pid = get_queried_object_id();
nundia_page_hero(nd_hero('b2b', $pid));

$band_image = nd('b2b_band_image');
if ($band_image): ?>
<section class="relative overflow-hidden">
    <?php echo nundia_image($band_image, 'B2B', 'relative h-[32vh] min-h-[220px] w-full object-cover', 'full'); ?>
    <div class="absolute inset-0 bg-black/25" aria-hidden="true"></div>
</section>
<?php endif; ?>

<?php
$b2b_cards = nd_rows(nd('b2b_cards'));
if (!empty($b2b_cards)): ?>
<section class="container-ed py-12 lg:py-20">
    <div class="grid gap-8 lg:grid-cols-3">
        <?php foreach ($b2b_cards as $i => $c): ?>
            <div class="reveal"<?php echo $i ? ' style="transition-delay: ' . (90 * $i) . 'ms;"' : ''; ?>>
                <div class="border-t border-border pt-5">
                    <?php if (!empty($c[0])): ?>
                        <p class="font-mono text-[11px] tracking-[0.2em] text-primary"><?php echo esc_html($c[0]); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($c[1])): ?>
                        <h2 class="mt-2 text-xl font-bold"><?php echo esc_html($c[1]); ?></h2>
                    <?php endif; ?>
                    <?php if (!empty($c[2])): ?>
                        <p class="mt-2 text-[14px] leading-relaxed text-muted-foreground"><?php echo esc_html($c[2]); ?></p>
                    <?php endif; ?>
                    <?php if (nd('b2b_cards_draft') && nd_opt('show_draft_badges')): ?>
                        <p class="mt-3 text-[11px] uppercase tracking-[0.16em] text-muted-foreground/70"><?php echo esc_html(nd_opt('draft_label')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php
$banner_eyebrow = nd('b2b_banner_eyebrow') ?: 'B2B';
$banner_heading = nd('b2b_banner_heading') ?: 'Per GDO, HO.RE.CA. e distribuzione internazionale utilizza il modulo dedicato.';
$banner_cta     = nd('b2b_banner_cta_label') ?: 'Richiesta commerciale';
$banner_link    = nd('b2b_banner_cta_link') ?: home_url('/richiesta-commerciale/');
if ($banner_link && strpos($banner_link, 'http') !== 0) {
    $banner_link = home_url($banner_link);
}
?>
<!-- Full-Width Black Band (replaces the contact form, per client request) -->
<section class="bg-foreground text-white py-14 lg:py-20">
    <div class="container-ed">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">
            <div class="max-w-4xl reveal">
                <?php if ($banner_eyebrow): ?>
                <p class="eyebrow text-white/70 mb-3 flex items-center gap-3">
                    <span class="h-[2px] w-9 bg-primary" aria-hidden="true"></span>
                    <span><?php echo esc_html($banner_eyebrow); ?></span>
                </p>
                <?php endif; ?>
                <?php if ($banner_heading): ?>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold tracking-tight text-white leading-snug">
                    <?php echo esc_html($banner_heading); ?>
                </p>
                <?php endif; ?>
            </div>
            <?php if ($banner_cta): ?>
            <div class="shrink-0 reveal" style="transition-delay: 90ms;">
                <a class="group inline-flex min-h-12 items-center justify-center gap-3 bg-primary px-8 text-[11px] font-semibold uppercase tracking-[0.18em] text-white transition-all duration-300 hover:bg-primary/90 shadow-lg" href="<?php echo esc_url($banner_link); ?>">
                    <span><?php echo esc_html($banner_cta); ?></span>
                    <?php echo nundia_icon('arrow-right', 'h-4 w-4 transition-transform group-hover:translate-x-1', 1.8); ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer();
