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
$has_courses = have_rows('our_course');

if (!$block_title && !$block_content && !$has_courses) {
  if (!empty($is_preview)) {
    echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Courses Block: Nessun corso configurato.</div>';
  }
  return;
}
?>

<section <?php echo $anchor; ?> class="md:py-16 py-8 <?php echo esc_attr($class_name); ?>">
  <div class="holder">
    <?php if ($block_title || $block_content): ?>
      <!-- Section Title -->
      <div class="space-y-4 text-center max-w-[750px] mx-auto">
        <?php if ($block_title) : ?>
          <h2 class="text-4xl font-bold text-primary"><?php echo esc_html($block_title); ?></h2>
        <?php endif; ?>

        <?php if ($block_content) : ?>
          <div class="<?php ksa("text-black/80 text-base font-normal [&>ul]:list-disc [&>ul]:mt-4 [&>ul]:pl-5 [&>ul>li+li]:mt-1 [&>ol]:list-decimal [&>ol]:mt-4 [&>ol]:pl-5 [&>ol>li+li]:mt-1 space-y-3") ?>">
            <?php echo $block_content; ?>
          </div>
        <?php endif; ?>
      </div>
      <!-- / Section Title -->
    <?php endif; ?>

    <?php if ($has_courses) : ?>
      <!-- Courses Loop -->
      <div class="grid md:grid-cols-2 gap-[30px] <?php echo ($block_title || $block_content) ? 'pt-14' : ''; ?>">
        <?php while (have_rows('our_course')) : the_row();
          $image = get_sub_field('image');
          $course_title = get_sub_field('course_title');
          $class_time = get_sub_field('class_time');
          $course_info = get_sub_field('course_info');

          $img_url = '';
          $img_alt = '';
          if (is_array($image) && !empty($image['url'])) {
            $img_url = $image['url'];
            $img_alt = !empty($image['alt']) ? $image['alt'] : ($course_title ?: '');
          } elseif (is_numeric($image)) {
            $img_url = wp_get_attachment_image_url($image, 'large');
            $img_alt = get_post_meta($image, '_wp_attachment_image_alt', true) ?: ($course_title ?: '');
          } elseif (is_string($image)) {
            $img_url = $image;
            $img_alt = $course_title ?: '';
          }

          if (!$img_url && !$course_title && !$class_time && !$course_info) {
            continue;
          }
        ?>
          <div class="grid grid-cols-1 md:grid-cols-12 overflow-hidden rounded-md shadow-sm">
            <?php if ($img_url) : ?>
              <!-- Image -->
              <div class="<?php echo ($course_title || $class_time || $course_info) ? 'md:col-span-5' : 'md:col-span-12'; ?>">
                <img class="w-full h-full object-cover" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" loading="lazy" />
              </div>
            <?php endif; ?>

            <?php if ($course_title || $class_time || $course_info) : ?>
              <!-- Content -->
              <div class="<?php echo $img_url ? 'md:col-span-7' : 'md:col-span-12'; ?> bg-light p-6 space-y-2 flex flex-col justify-center">
                <?php if ($course_title) : ?>
                  <h3 class="text-primary text-2xl font-semibold capitalize"><?php echo esc_html($course_title); ?></h3>
                <?php endif; ?>

                <?php if ($class_time) : ?>
                  <p class="text-base leading-normal tracking-wider capitalize font-medium">
                    <span class="text-orange">class time:</span>
                    <span><?php echo esc_html($class_time); ?></span>
                  </p>
                <?php endif; ?>

                <?php if ($course_info) : ?>
                  <div class="<?php ksa("[&>p]:text-base [&>p]:text-black [&>p]:opacity-50 [&>p]:leading-normal") ?>">
                    <?php echo $course_info; ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      </div>
      <!-- / Courses Loop -->
    <?php endif; ?>
  </div>
</section>
