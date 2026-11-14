<?php
/**
 * Template Name: Chi siamo
 */
get_header();
$pid = get_queried_object_id();
nundia_page_hero(nd_hero('chi-siamo', $pid));

$story_title = nd('about_story_title');
$story_paras = nd_lines(nd('about_story_body'));
$story_image = nd('about_story_image');
$has_story = ($story_title || !empty($story_paras) || $story_image);

if ($has_story): ?>
<section class="container-ed py-16 lg:py-24">
    <div class="grid gap-12 <?php echo $story_image ? 'lg:grid-cols-[1.1fr_1fr] lg:gap-20' : 'max-w-3xl mx-auto'; ?>">
        <div class="reveal">
            <?php if ($story_title): ?>
                <h2 class="text-[clamp(1.6rem,2.8vw,2.3rem)]"><?php echo esc_html($story_title); ?></h2>
            <?php endif; ?>
            <?php foreach ($story_paras as $para): ?>
                <p class="mt-5 max-w-lg text-[15px] leading-[1.85] text-muted-foreground"><?php echo esc_html($para); ?></p>
            <?php endforeach; ?>
            <?php if (nd('about_story_draft') && function_exists('nd_draft_badge')) echo nd_draft_badge('mt-6'); ?>
        </div>
        <?php if ($story_image): ?>
        <div class="reveal" style="transition-delay: 120ms;">
            <div class="overflow-hidden rounded-md">
                <?php echo nundia_image($story_image, $story_title ?: 'Chi siamo', 'relative aspect-[1/1] w-full'); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php
$heritage_eyebrow = nd('about_heritage_eyebrow');
$heritage_title = nd('about_heritage_title');
$heritage_body = nd('about_heritage_body');
$has_heritage = ($heritage_title || $heritage_body);

$philosophy_title = nd('about_philosophy_title');
$philosophy_body = nd('about_philosophy_body');
$has_philosophy = ($philosophy_title || $philosophy_body);

if ($heritage_eyebrow || $has_heritage || $has_philosophy): ?>
<section class="border-y border-border bg-white">
    <div class="container-ed py-16 lg:py-24">
        <?php if ($heritage_eyebrow): ?>
            <p class="eyebrow rule-accent"><?php echo esc_html($heritage_eyebrow); ?></p>
        <?php endif; ?>

        <?php if ($has_heritage || $has_philosophy): ?>
        <div class="flex item-center justfy-spacebetwin pt-2.5">
            <?php if ($has_heritage): ?>
            <div class="reveal">
                <?php if ($heritage_title): ?>
                    <h2 class="mt-5 text-[clamp(1.6rem,2.8vw,2.3rem)]"><?php echo esc_html($heritage_title); ?></h2>
                <?php endif; ?>
                <?php if ($heritage_body): ?>
                    <p class="mt-5 max-w-lg text-[15px] leading-[1.85] text-muted-foreground"><?php echo esc_html($heritage_body); ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($has_philosophy): ?>
            <div class="reveal <?php echo $has_heritage ? 'lg:pt-0' : ''; ?>" style="transition-delay: 120ms;">
                <?php if ($philosophy_title): ?>
                    <h2 class="text-[clamp(1.6rem,2.8vw,2.3rem)]"><?php echo esc_html($philosophy_title); ?></h2>
                <?php endif; ?>
                <?php if ($philosophy_body): ?>
                    <p class="mt-5 max-w-lg text-[15px] leading-[1.85] text-muted-foreground"><?php echo esc_html($philosophy_body); ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php
$gallery_imgs = array();
foreach (array(1, 2, 3) as $i) {
    $img = nd('about_gallery_' . $i);
    if ($img) {
        $gallery_imgs[] = $img;
    }
}
if (!empty($gallery_imgs)): ?>
<section class="container-ed py-16 lg:py-24">
    <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($gallery_imgs as $idx => $img_src): ?>
            <div class="reveal"<?php echo $idx > 0 ? ' style="transition-delay: ' . (90 * $idx) . 'ms;"' : ''; ?>>
                <div class="overflow-hidden rounded-md">
                    <?php echo nundia_image($img_src, 'Tradizione dolciaria siciliana', 'relative aspect-[4/5] w-full transition-transform duration-[1400ms] hover:scale-[1.04]'); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php
$quality_eyebrow = nd('about_quality_eyebrow');
$quality_body = nd('about_quality_body');
$quality_items = nd_rows(nd('about_quality_items'));
if ($quality_eyebrow || $quality_body || !empty($quality_items)): ?>
<section class="border-t border-border bg-foreground py-16 text-white lg:py-24">
    <div class="container-ed">
        <div class="reveal">
            <?php if ($quality_eyebrow): ?>
                <p class="eyebrow text-white/55"><?php echo esc_html($quality_eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($quality_body): ?>
                <p class="mt-5 max-w-2xl text-[15px] leading-[1.85] text-white/70"><?php echo esc_html($quality_body); ?></p>
            <?php endif; ?>
            <?php if (!empty($quality_items)): ?>
                <div class="mt-10 grid gap-8 border-t border-white/15 pt-10 sm:grid-cols-3">
                    <?php foreach ($quality_items as $i => $row): ?>
                        <div>
                            <p class="font-mono text-[11px] tracking-[0.2em] text-primary"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></p>
                            <?php if (!empty($row[0])): ?>
                                <p class="mt-2 text-[13px] uppercase tracking-[0.16em] text-white/80"><?php echo esc_html($row[0]); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($row[1])): ?>
                                <p class="mt-2 text-[12.5px] leading-relaxed text-white/45"><?php echo esc_html($row[1]); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
$cta_title = nd('about_cta_title');
$cta_body = nd('about_cta_body');
$cta_button = nd('about_cta_button');
if ($cta_title || $cta_body || $cta_button): 
    $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
?>
<section class="container-ed py-16 text-center lg:py-24">
    <div class="reveal">
        <?php if ($cta_title): ?>
            <h2 class="text-[clamp(1.7rem,3vw,2.5rem)]"><?php echo esc_html($cta_title); ?></h2>
        <?php endif; ?>
        <?php if ($cta_body): ?>
            <p class="mt-4 text-[14px] text-muted-foreground"><?php echo esc_html($cta_body); ?></p>
        <?php endif; ?>
        <?php if ($cta_button): ?>
            <a class="group mt-8 inline-flex min-h-12 items-center gap-2 bg-primary px-8 text-[11px] uppercase tracking-[0.2em] text-white transition-colors hover:bg-primary/90" href="<?php echo esc_url($shop_url); ?>">
                <?php echo esc_html($cta_button); ?><?php echo nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.6); ?>
            </a>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php get_footer();
