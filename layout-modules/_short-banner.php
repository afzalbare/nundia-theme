<?php
/**
 * Layout Module: Short Banner
 */
$banner_image = get_field('banner_image');
$image_url = '';
if (is_array($banner_image) && !empty($banner_image['url'])) {
    $image_url = $banner_image['url'];
} elseif (is_numeric($banner_image)) {
    $image_url = wp_get_attachment_image_url($banner_image, 'full');
} elseif (is_string($banner_image)) {
    $image_url = $banner_image;
}

$banner_mobile = get_field('banner_image_mobile');
$banner_mobile_url = '';
if (is_array($banner_mobile) && !empty($banner_mobile['url'])) {
    $banner_mobile_url = $banner_mobile['url'];
} elseif (is_numeric($banner_mobile)) {
    $banner_mobile_url = wp_get_attachment_image_url($banner_mobile, 'full');
} elseif (is_string($banner_mobile)) {
    $banner_mobile_url = $banner_mobile;
}

$banner_sub_title = get_field('banner_sub_title');
$banner_title = get_field('banner_title');
$has_cta = have_rows('cta_items');

if (!$image_url && !$banner_mobile_url && !$banner_sub_title && !$banner_title && !$has_cta) {
    return;
}
?>

<section class="min-h-[300px] md:min-h-[475px] relative flex flex-col items-center justify-center text-center bg-foreground">
    <?php if (wp_is_mobile() && $banner_mobile_url) : ?>
        <img class="h-full w-full absolute right-0 top-0 object-cover"
            src="<?php echo esc_url($banner_mobile_url); ?>"
            alt="<?php echo esc_attr(get_the_title()); ?>">
    <?php elseif ($image_url) : ?>
        <img class="h-full w-full absolute right-0 top-0 object-cover"
            src="<?php echo esc_url($image_url); ?>"
            alt="<?php echo esc_attr(get_the_title()); ?>">
    <?php endif; ?>

    <?php if ($image_url || $banner_mobile_url) : ?>
        <div class="overlay"></div>
    <?php endif; ?>

    <div class="holder relative z-10 px-4">
        <?php if ($banner_sub_title) : ?>
            <h3 class="text-white text-4xl md:text-5xl font-black"><?php echo esc_html($banner_sub_title); ?></h3>
        <?php endif; ?>

        <?php if ($banner_title) : ?>
            <<?php echo is_front_page() ? 'h2' : 'h1'; ?> class="bannerTitle text-white text-[56px] md:text-[64px] font-proximaNovaExtraCondensed font-bold leading-none">
                <?php echo esc_html($banner_title); ?>
            </<?php echo is_front_page() ? 'h2' : 'h1'; ?>>
        <?php endif; ?>

        <?php if ($has_cta) :
            while (have_rows('cta_items')) : the_row();
                $link = get_sub_field('cta_link');
                if ($link && !empty($link['url']) && !empty($link['title'])) :
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = !empty($link['target']) ? $link['target'] : '_self'; ?>
                    <div class="mt-5">
                        <a class="btn-primary" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                            <?php echo esc_html($link_title); ?>
                        </a>
                    </div>
                <?php endif;
            endwhile;
        endif; ?>
    </div>
</section>
