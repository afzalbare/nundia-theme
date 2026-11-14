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

$block_title = get_field('block_title');
$block_content = get_field('block_content');
$has_testimonials = have_rows('testimonial');

if (!$block_title && !$block_content && !$has_testimonials) {
  if (!empty($is_preview)) {
    echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Testimonials Block: Nessuna testimonianza configurata.</div>';
  }
  return;
}
?>

<!-- Testimonial block -->
<section <?php echo $anchor; ?> class="md:py-16 py-8 <?php echo esc_attr(trim($class_name)); ?>">
  <div class="holder">
    <?php if ($block_title || $block_content) : ?>
      <div class="space-y-4 text-center max-w-[750px] mx-auto px-5">
        <?php if ($block_title) : ?>
          <h2 class="text-4xl font-bold text-primary"><?php echo esc_html($block_title); ?></h2>
        <?php endif; ?>

        <?php if ($block_content) : ?>
          <div class="<?php ksa("text-black/80 text-base font-normal [&>ul]:list-disc [&>ul]:mt-4 [&>ul]:pl-5 [&>ul>li+li]:mt-1 [&>ol]:list-decimal [&>ol]:mt-4 [&>ol]:pl-5 [&>ol>li+li]:mt-1 space-y-3") ?>">
            <?php echo $block_content; ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if ($has_testimonials) : ?>
      <div class="<?php echo ($block_title || $block_content) ? 'mt-14' : ''; ?>">
        <div class="swiper testimonial-swiper">
          <div class="swiper-wrapper">
            <?php while (have_rows('testimonial')) : the_row();
              $image = get_sub_field('image');
              $message = get_sub_field('message');
              $name = get_sub_field('name');
              $relation = get_sub_field('relation');

              $img_url = '';
              $img_alt = '';
              if (is_array($image) && !empty($image['url'])) {
                $img_url = $image['url'];
                $img_alt = !empty($image['alt']) ? $image['alt'] : ($name ?: '');
              } elseif (is_numeric($image)) {
                $img_url = wp_get_attachment_image_url($image, 'medium');
                $img_alt = get_post_meta($image, '_wp_attachment_image_alt', true) ?: ($name ?: '');
              } elseif (is_string($image)) {
                $img_url = $image;
                $img_alt = $name ?: '';
              }

              if (!$img_url && !$message && !$name && !$relation) continue;
            ?>
              <div class="swiper-slide">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 bg-white p-6 rounded-md shadow-sm">
                  <?php if ($img_url) : ?>
                    <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-full overflow-hidden shrink-0 border-2 border-primary/20">
                      <img class="w-full h-full object-cover" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" loading="lazy" />
                    </div>
                  <?php endif; ?>

                  <div class="text-base text-black opacity-80 font-normal space-y-4 flex-1">
                    <?php if ($message) : ?>
                      <div class="italic">
                        <?php echo $message; ?>
                      </div>
                    <?php endif; ?>

                    <?php if ($name || $relation) : ?>
                      <div class="<?php ksa("[&>p]:text-primary [&>p]:text-lg [&>p]:font-bold [&>span]:text-black [&>span]:text-base [&>span]:font-medium") ?>">
                        <?php if ($name) : ?>
                          <p><?php echo esc_html($name); ?></p>
                        <?php endif; ?>
                        <?php if ($relation) : ?>
                          <span><?php echo esc_html($relation); ?></span>
                        <?php endif; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
          <!-- Pagination -->
          <div class="swiper-pagination mt-6"></div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
<!-- Testimonial block -->
