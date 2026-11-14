<?php
// Support custom "anchor" values.
$anchor = '';
if (!empty($block['anchor'])) {
    $anchor = ' id="' . esc_attr($block['anchor']) . '"';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = '';
if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}

if (!have_rows('feature_item')) {
    if (!empty($is_preview)) {
        echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Features Block: Nessun elemento configurato.</div>';
    }
    return;
}
?>

<section <?php echo $anchor; ?> class="<?php echo esc_attr(trim($class_name)); ?>">
    <!-- Feature Items Grid -->
    <div class="features-block grid sm:grid-cols-2 lg:grid-cols-4">
        <?php while (have_rows('feature_item')) : the_row();
            $upload_image = get_sub_field('upload_image');
            $title = get_sub_field('title');
            $content = get_sub_field('content');

            $img_url = '';
            $img_alt = '';
            if (is_array($upload_image) && !empty($upload_image['url'])) {
                $img_url = $upload_image['url'];
                $img_alt = !empty($upload_image['alt']) ? $upload_image['alt'] : ($title ?: '');
            } elseif (is_numeric($upload_image)) {
                $img_url = wp_get_attachment_image_url($upload_image, 'medium');
                $img_alt = get_post_meta($upload_image, '_wp_attachment_image_alt', true) ?: ($title ?: '');
            } elseif (is_string($upload_image)) {
                $img_url = $upload_image;
                $img_alt = $title ?: '';
            }

            if (!$img_url && !$title && !$content) {
                continue;
            }
        ?>
            <div class="features-box text-white p-8 text-center relative">
                <?php if ($img_url) : ?>
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6">
                        <img class="w-10 h-10 object-contain" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" loading="lazy" />
                    </div>
                <?php endif; ?>

                <?php if ($title) : ?>
                    <h3 class="text-2xl font-medium mb-4"><?php echo esc_html($title); ?></h3>
                <?php endif; ?>

                <?php if ($content) : ?>
                    <div class="text-base font-normal">
                        <?php echo $content; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</section>
