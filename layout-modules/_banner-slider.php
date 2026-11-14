<?php
/**
 * Layout Module: Banner Slider
 */
if (!have_rows('slider_items')) {
    return;
}
?>
<!-- Hero Slider -->
<section>
    <!-- Swiper -->
    <div class="swiper bannerSlider h-[600px]">
        <div class="swiper-wrapper">
            <!-- Slides -->
            <?php while (have_rows('slider_items')) : the_row();
                $bg_image = get_sub_field('bg_image');
                $title = get_sub_field('title');
                $link = get_sub_field('cta_button');

                $bg_url = '';
                if (is_array($bg_image) && !empty($bg_image['url'])) {
                    $bg_url = $bg_image['url'];
                } elseif (is_numeric($bg_image)) {
                    $bg_url = wp_get_attachment_image_url($bg_image, 'full');
                } elseif (is_string($bg_image)) {
                    $bg_url = $bg_image;
                }

                if (!$bg_url && !$title && !$link) {
                    continue;
                }
            ?>
                <div
                    class="swiper-slide !flex flex-col items-center justify-center bg-no-repeat bg-cover bg-center relative z-0"
                    <?php if ($bg_url) : ?>
                    style="background-image: url('<?php echo esc_url($bg_url); ?>');"
                    <?php endif; ?>>
                    <?php if ($bg_url) : ?>
                        <div class="overlay"></div>
                    <?php endif; ?>
                    <div class="holder text-center relative z-10 px-4">
                        <?php if ($title) : ?>
                            <h2 class="text-4xl lg:text-6xl font-extrabold text-white mb-8 max-w-[700px] mx-auto">
                                <?php echo esc_html($title); ?>
                            </h2>
                        <?php endif; ?>

                        <?php if ($link && !empty($link['url']) && !empty($link['title'])) :
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = !empty($link['target']) ? $link['target'] : '_self';
                        ?>
                            <a href="<?php echo esc_url($link_url); ?>"
                                target="<?php echo esc_attr($link_target); ?>"
                                class="btn-primary">
                                <?php echo esc_html($link_title); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Optional pagination -->
        <div class="swiper-pagination"></div>
    </div>
</section>
