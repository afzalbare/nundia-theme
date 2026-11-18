<?php
/**
 * Layout Module: About Section
 */
$arrow = nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.8);

$image = nd('home_about_image');
$eyebrow = nd('home_about_eyebrow');
$title = nd('home_about_title');
$sub = nd('home_about_sub');
$body = nd('home_about_body');
$cta = nd('home_about_cta');
$cta_url = nd_url(nd('home_about_cta_url'), '/chi-siamo/');

if (!$image && !$eyebrow && !$title && !$sub && !$body && !$cta) {
    return;
}
?>
<section class="bg-background pb-16 lg:pb-24">
    <div class="container-ed">
        <div class="grid items-center gap-10 <?php echo $image ? 'lg:grid-cols-2 lg:gap-16' : 'max-w-3xl mx-auto'; ?>">
            <?php if ($image): ?>
            <div class="reveal">
                <div class="overflow-hidden rounded-md">
                    <?php echo nundia_image($image, $title ?: 'About image', 'relative aspect-[4/3] w-full transition-transform duration-[1400ms] ease-out hover:scale-[1.03]'); ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="reveal" style="transition-delay: 120ms;">
                <?php if ($eyebrow): ?>
                    <?php echo nundia_eyebrow($eyebrow, 'text-primary'); ?>
                <?php endif; ?>
                <?php if ($title): ?>
                    <h2 class="mt-5 max-w-lg text-[clamp(1.9rem,3.6vw,2.7rem)] leading-[1.15] text-foreground"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                <?php if ($sub): ?>
                    <p class="mt-4 text-[1.15rem] font-semibold text-primary"><?php echo esc_html($sub); ?></p>
                <?php endif; ?>
                <?php if ($body): ?>
                    <p class="mt-4 max-w-lg text-[15px] leading-[1.8] text-muted-foreground"><?php echo esc_html($body); ?></p>
                <?php endif; ?>
                <?php if ($cta): ?>
                    <a class="group mt-7 inline-flex min-h-12 items-center gap-2 bg-primary px-7 text-[11px] uppercase tracking-[0.16em] text-white transition-colors duration-300 hover:bg-primary/90" href="<?php echo esc_url($cta_url); ?>">
                        <?php echo esc_html($cta); ?><?php echo $arrow; ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
