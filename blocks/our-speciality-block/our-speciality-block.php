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

// Get ACF fields
$section_title = get_field('section_title');
$section_description = get_field('section_description');
$speciality_items = get_field('speciality_items');

if (empty($section_title) && empty($section_description) && empty($speciality_items)) {
  if (!empty($is_preview)) {
    echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Our Speciality Block: Nessun elemento configurato.</div>';
  }
  return;
}
?>

<section<?php echo $anchor; ?> class="our-speciality-section md:py-16 py-8 <?php echo esc_attr(trim($class_name)); ?>">
  <?php if ($section_title || $section_description): ?>
    <div class="container mx-auto px-4 mb-12">
      <div class="text-center">
        <?php if ($section_title): ?>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4"><?php echo esc_html($section_title); ?></h2>
        <?php endif; ?>
        <?php if ($section_description): ?>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto"><?php echo esc_html($section_description); ?></p>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if (!empty($speciality_items) && is_array($speciality_items)): ?>
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($speciality_items as $item):
          $title = !empty($item['title']) ? $item['title'] : '';
          $description = !empty($item['description']) ? $item['description'] : '';
          $icon = !empty($item['icon']) ? $item['icon'] : '';
          $bg_color = !empty($item['background_color']) ? $item['background_color'] : 'bg-primary';
          $icon_color = !empty($item['icon_color']) ? $item['icon_color'] : 'text-primary';

          if (!$title && !$description && !$icon) continue;
        ?>
          <div class="<?php echo esc_attr($bg_color); ?> text-white p-8 text-center relative rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
            <?php if ($icon): ?>
              <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 <?php echo esc_attr($icon_color); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr($icon); ?>"></path>
                </svg>
              </div>
            <?php endif; ?>
            <?php if ($title): ?>
              <h3 class="text-2xl font-medium mb-4"><?php echo esc_html($title); ?></h3>
            <?php endif; ?>
            <?php if ($description): ?>
              <p class="text-base font-normal leading-relaxed"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</section>
