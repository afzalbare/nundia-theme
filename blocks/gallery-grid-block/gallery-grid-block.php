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

$gallery_grid = get_field('gallery_grid');
if (empty($gallery_grid)) {
  if (!empty($is_preview)) {
    echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Gallery Grid Block: Nessuna immagine selezionata.</div>';
  }
  return;
}
?>

<section <?php echo $anchor; ?> class="md:py-16 py-8 <?php echo esc_attr(trim($class_name)); ?>">
  <div class="holder">
    <div class="gallery-grid grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <?php foreach ($gallery_grid as $image) :
        $url = '';
        $alt = '';
        $caption = '';
        if (is_array($image) && !empty($image['url'])) {
          $url = $image['url'];
          $alt = !empty($image['alt']) ? $image['alt'] : '';
          $caption = !empty($image['caption']) ? $image['caption'] : '';
        } elseif (is_numeric($image)) {
          $url = wp_get_attachment_image_url($image, 'full');
          $alt = get_post_meta($image, '_wp_attachment_image_alt', true) ?: '';
          $caption = wp_get_attachment_caption($image) ?: '';
        }
        if (!$url) continue;
      ?>
        <a href="<?php echo esc_url($url); ?>" data-lightbox="models" data-title="<?php echo esc_attr($caption); ?>">
          <img class="w-full h-full object-cover aspect-square rounded-sm" src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" />
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
