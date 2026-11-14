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
$cta_url = nd('home_b2b_cta_url');
if (!$cta_url) {
    $cta_url = home_url('/b2b/');
}

if (!$image && !$eyebrow && !$title && !$body && empty($tags) && !$cta) {
    return;
}
?>
<section class="relative overflow-hidden bg-foreground">
    <?php if ($image): ?>
        <?php echo nundia_image($image, $title ?: 'B2B', 'absolute inset-0 h-full w-full', 'full'); ?>
        <div class="absolute inset-0 bg-black/25" aria-hidden="true"></div>
    <?php endif; ?>
    <div class="container-ed relative py-16 lg:py-24">
        <div class="reveal max-w-xl bg-primary/95 p-8 text-white sm:p-10 lg:p-12">
            <?php if ($eyebrow): ?>
                <p class="text-[10px] uppercase tracking-[0.3em] text-white/70"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($title): ?>
                <h2 class="mt-4 text-[clamp(1.9rem,3.4vw,2.7rem)] leading-[1.05] text-white"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
            <?php if ($body): ?>
                <p class="mt-5 text-[14.5px] leading-[1.8] text-white/80"><?php echo esc_html($body); ?></p>
            <?php endif; ?>
            <?php if (!empty($tags)): ?>
                <ul class="mt-7 flex flex-wrap gap-2">
                    <?php foreach ($tags as $tag): ?>
                        <li class="border border-white/30 px-3 py-1.5 text-[9.5px] uppercase tracking-[0.18em] text-white/80"><?php echo esc_html($tag); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if ($cta): ?>
                <a class="group mt-9 inline-flex min-h-12 items-center gap-2 bg-white px-7 text-[10.5px] uppercase tracking-[0.2em] text-foreground transition-colors duration-300 hover:bg-secondary" href="<?php echo esc_url($cta_url); ?>">
                    <?php echo esc_html($cta); ?><?php echo $arrow; ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
