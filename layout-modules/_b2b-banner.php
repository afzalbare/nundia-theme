<?php
/**
 * Layout Module: B2B Banner
 */
$arrow = nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.8);

$image = nd('home_b2b_image');
$eyebrow = nd('home_b2b_eyebrow');
$title = nd('home_b2b_title');
$body = nd('home_b2b_body');
$tags = nd_lines(nd('home_b2b_tags'));
$cta = nd('home_b2b_cta');
$cta_raw = nd('home_b2b_cta_url');
if (!$cta_raw || $cta_raw === '/b2b/') {
    $cta_raw = '/richiesta-commerciale/';
}
$cta_url = nd_url($cta_raw, '/richiesta-commerciale/');

if (!$image && !$eyebrow && !$title && !$body && empty($tags) && !$cta) {
    return;
}
?>
<section class="relative min-h-[520px] lg:min-h-[640px] flex items-center overflow-hidden bg-foreground">
    <?php if ($image): ?>
        <?php echo nundia_image($image, $title ?: 'B2B', 'absolute inset-0 h-full w-full', 'full'); ?>
        <div class="absolute inset-0 bg-black/45" aria-hidden="true"></div>
    <?php endif; ?>
    <div class="container-ed relative py-28 sm:py-36 lg:py-44">
        <div class="reveal max-w-xl text-white">
            <?php if ($eyebrow): ?>
                <p class="text-[10px] uppercase tracking-[0.3em] text-white/90 font-semibold drop-shadow-md"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($title): ?>
                <h2 class="mt-4 text-[clamp(2.1rem,4vw,3.2rem)] font-light leading-[1.1] text-white drop-shadow-md"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
            <?php if ($body): ?>
                <p class="mt-5 text-[15px] leading-[1.8] text-white/95 drop-shadow-md max-w-lg"><?php echo esc_html($body); ?></p>
            <?php endif; ?>
            <?php if (!empty($tags)): ?>
                <ul class="mt-7 flex flex-wrap gap-2">
                    <?php foreach ($tags as $tag): ?>
                        <li class="border border-white/60 bg-black/25 backdrop-blur-sm px-4 py-1.5 text-[10px] uppercase tracking-[0.18em] text-white shadow-sm"><?php echo esc_html($tag); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if ($cta): ?>
                <a class="group mt-9 inline-flex min-h-12 items-center gap-2 bg-white px-7 text-[10.5px] uppercase tracking-[0.2em] text-foreground transition-all duration-300 hover:bg-primary hover:text-white shadow-lg" href="<?php echo esc_url($cta_url); ?>">
                    <?php echo esc_html($cta); ?><?php echo $arrow; ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
