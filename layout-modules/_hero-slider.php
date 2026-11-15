<?php
/**
 * Layout Module: Hero Slider
 * Client change: the hero slides advance automatically (no arrows / dashes).
 */
$page_id = get_queried_object_id();
$arrow = nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.8);

/* Hero slides: ACF repeater or defaults */
$slides = array();
$acf_slides = function_exists('get_field') ? get_field('home_slides', $page_id) : null;
if (is_array($acf_slides) && !empty($acf_slides)) {
    foreach ($acf_slides as $s) {
        $img = '';
        if (!empty($s['image'])) {
            if (is_array($s['image']) && !empty($s['image']['ID'])) {
                $img = $s['image']['ID'];
            } elseif (is_numeric($s['image'])) {
                $img = (int) $s['image'];
            } elseif (is_array($s['image']) && !empty($s['image']['url'])) {
                $img = $s['image']['url'];
            } elseif (is_string($s['image'])) {
                $img = $s['image'];
            }
        }
        $alt = !empty($s['image']['alt']) ? $s['image']['alt'] : (!empty($s['title']) ? $s['title'] : '');
        $slides[] = array(
            'image' => $img,
            'alt' => $alt,
            'eyebrow' => !empty($s['eyebrow']) ? $s['eyebrow'] : '',
            'title' => !empty($s['title']) ? $s['title'] : '',
            'body' => !empty($s['body']) ? $s['body'] : '',
            'cta_label' => !empty($s['cta_label']) ? $s['cta_label'] : '',
            'cta_link' => !empty($s['cta_link']) ? $s['cta_link'] : '',
        );
    }
} else {
    $defaults = function_exists('nundia_default_slides') ? nundia_default_slides() : array();
    foreach ($defaults as $s) {
        $s['image'] = nundia_img($s['image']);
        $slides[] = $s;
    }
}

if (empty($slides)) {
    return;
}

$link = function ($url) {
    if (empty($url)) return '#';
    return (strpos($url, 'http') === 0) ? $url : home_url($url);
};
?>
<section class="nd-hero swiper hero-swiper relative h-[100svh] max-h-[1000px] min-h-[560px] w-full overflow-hidden bg-foreground text-white" data-interval="9000">
    <div class="swiper-wrapper">
        <?php foreach ($slides as $i => $s): ?>
            <div class="swiper-slide relative h-full w-full overflow-hidden select-none">
                <?php if (!empty($s['image'])): ?>
                    <div class="nd-hero-media absolute inset-0 h-full w-full overflow-hidden">
                        <div class="h-full w-full animate-drift">
                            <?php echo nundia_image($s['image'], $i === 0 ? $s['alt'] : '', 'relative h-full w-full object-cover', 'full', $i === 0); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-black/55 pointer-events-none" aria-hidden="true"></div>
                <div class="container-ed relative flex h-full flex-col justify-end pb-12 pt-28 lg:pb-16 pointer-events-none z-10">
                    <div class="nd-hero-text relative w-full max-w-2xl pointer-events-auto">
                        <?php if (!empty($s['eyebrow'])): ?>
                            <p class="flex items-center gap-3 text-[10px] uppercase tracking-[0.3em] text-white/80">
                                <span class="h-[2px] w-9 bg-white" aria-hidden="true"></span><?php echo esc_html($s['eyebrow']); ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($s['title'])): ?>
                            <<?php echo $i === 0 ? 'h1' : 'h2'; ?> class="mt-5 whitespace-pre-line text-[clamp(2.3rem,4.8vw,4.1rem)] leading-[1.05] text-white"><?php echo esc_html($s['title']); ?></<?php echo $i === 0 ? 'h1' : 'h2'; ?>>
                        <?php endif; ?>
                        <?php if (!empty($s['body'])): ?>
                            <p class="mt-5 max-w-lg text-[15.5px] leading-relaxed text-white/85"><?php echo esc_html($s['body']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($s['cta_label'])): ?>
                            <div class="mt-8 flex flex-wrap items-center gap-3 pointer-events-auto">
                                <a class="group inline-flex min-h-12 items-center justify-center gap-2 bg-primary px-7 text-[11px] uppercase tracking-[0.16em] text-white transition-colors duration-300 hover:bg-primary/90" href="<?php echo esc_url($link($s['cta_link'])); ?>">
                                    <?php echo esc_html($s['cta_label']); ?><?php echo $arrow; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="mt-10 h-10" aria-hidden="true"></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
