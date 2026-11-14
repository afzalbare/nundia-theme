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
$has_counters = have_rows('counters');

$block_image = get_field('block_image');
$bg_url = '';
if (is_array($block_image) && !empty($block_image['url'])) {
  $bg_url = $block_image['url'];
} elseif (is_numeric($block_image)) {
  $bg_url = wp_get_attachment_image_url($block_image, 'full');
} elseif (is_string($block_image)) {
  $bg_url = $block_image;
}

if (!$block_title && !$block_content && !$has_counters && !$bg_url) {
  if (!empty($is_preview)) {
    echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Counter Block: Nessun contenuto configurato.</div>';
  }
  return;
}

$counter_ids = ['teachers-counter', 'kids-counter', 'parents-counter', 'awards-counter'];
$counter_index = 0;
?>

<section <?php echo $anchor; ?> 
  <?php if ($bg_url): ?>
    style="background-image: url('<?php echo esc_url($bg_url); ?>');" 
    class="md:py-16 py-8 bg-cover bg-center bg-fixed md:min-h-[576px] text-black flex items-center justify-center <?php echo esc_attr($class_name); ?>"
  <?php else: ?>
    class="md:py-16 py-8 md:min-h-[300px] text-black flex items-center justify-center bg-gray-50 <?php echo esc_attr($class_name); ?>"
  <?php endif; ?>>

  <div class="holder z-10 relative w-full h-full flex flex-col items-center justify-center text-center py-16">

    <?php if ($block_title || $block_content): ?>
      <div class="space-y-4">
        <?php if ($block_title) : ?>
          <h2 class="text-4xl font-bold text-primary"><?php echo esc_html($block_title); ?></h2>
        <?php endif; ?>
        <?php if ($block_content) : ?>
          <div class="<?php ksa("text-black/80 text-base font-normal [&>ul]:list-disc [&>ul]:mt-4 [&>ul]:pl-5 [&>ul>li+li]:mt-1 [&>ol]:list-decimal [&>ol]:mt-4 [&>ol]:pl-5 [&>ol>li+li]:mt-1 space-y-3 max-w-[750px] mx-auto") ?>">
            <?php echo $block_content; ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- Counter Cards -->
    <?php if ($has_counters): ?>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full max-w-4xl mt-10">
        <?php while (have_rows('counters')): the_row();
          $number = get_sub_field('number');
          $label_name = get_sub_field('label_name');
          if (!$number && !$label_name) continue;
          $c_id = isset($counter_ids[$counter_index]) ? $counter_ids[$counter_index] : 'counter-' . $counter_index;
        ?>
          <div class="counter-card p-6 text-center">
            <?php if ($number) : ?>
              <div class="text-4xl font-semibold text-primary mb-2" id="<?php echo esc_attr($c_id); ?>">
                <?php echo esc_html($number); ?>
              </div>
            <?php endif; ?>
            <?php if ($label_name) : ?>
              <div class="text-lg/[29px] font-medium"><?php echo esc_html($label_name); ?></div>
            <?php endif; ?>
          </div>
          <?php $counter_index++; ?>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>

</section>
