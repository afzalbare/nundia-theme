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

$image = get_field('image');
$title = get_field('title');
$content = get_field('content');
$link = get_field('button');
$image_align = get_field('image_align');

$img_url = '';
$img_alt = '';
if (is_array($image) && !empty($image['url'])) {
  $img_url = $image['url'];
  $img_alt = !empty($image['alt']) ? $image['alt'] : ($title ?: '');
} elseif (is_numeric($image)) {
  $img_url = wp_get_attachment_image_url($image, 'large');
  $img_alt = get_post_meta($image, '_wp_attachment_image_alt', true) ?: ($title ?: '');
} elseif (is_string($image)) {
  $img_url = $image;
  $img_alt = $title ?: '';
}

$has_btn = ($link && !empty($link['url']) && !empty($link['title']));
$has_text = ($title || $content || $has_btn);

if (!$img_url && !$has_text) {
  if (!empty($is_preview)) {
    echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Text & Media Block: Nessun contenuto configurato.</div>';
  }
  return;
}
?>

<!-- Text Media Block -->
<section <?php echo $anchor; ?> class="md:py-16 py-8 <?php echo esc_attr(trim($class_name)); ?>">
  <div class="holder">
    <?php if ($img_url && $has_text): ?>
      <div class="grid grid-cols-1 lg:grid-cols-6 items-center lg:gap-16 gap-8">
        <div class="lg:col-span-3 text-center <?php echo $image_align ? 'lg:order-2' : ''; ?>">
          <img class="block mx-auto rounded-md object-cover max-h-[500px]" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" loading="lazy" />
        </div>

        <div class="lg:col-span-3 space-y-4 lg:space-y-6 <?php echo $image_align ? 'lg:order-1' : ''; ?>">
          <?php if ($title) : ?>
            <h2 class="text-primary text-3xl font-bold"><?php echo esc_html($title); ?></h2>
          <?php endif; ?>

          <?php if ($content) : ?>
            <div class="<?php ksa("text-black/80 text-base font-normal [&>ul]:list-disc [&>ul]:mt-4 [&>ul]:pl-5 [&>ul>li+li]:mt-1 [&>ol]:list-decimal [&>ol]:mt-4 [&>ol]:pl-5 [&>ol>li+li]:mt-1 space-y-3") ?>">
              <?php echo $content; ?>
            </div>
          <?php endif; ?>

          <?php if ($has_btn) :
            $link_url = $link['url'];
            $link_title = $link['title'];
            $link_target = !empty($link['target']) ? $link['target'] : '_self';
          ?>
            <div class="pt-2">
              <a class="btn-primary inline-block" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                <?php echo esc_html($link_title); ?>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>

    <?php elseif ($img_url && !$has_text): ?>
      <div class="max-w-2xl mx-auto text-center">
        <img class="block mx-auto rounded-md object-cover" src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($img_alt); ?>" loading="lazy" />
      </div>

    <?php else: ?>
      <div class="max-w-3xl mx-auto space-y-4 lg:space-y-6 text-center">
        <?php if ($title) : ?>
          <h2 class="text-primary text-3xl font-bold"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($content) : ?>
          <div class="<?php ksa("text-black/80 text-base font-normal [&>ul]:list-disc [&>ul]:mt-4 [&>ul]:pl-5 [&>ul>li+li]:mt-1 [&>ol]:list-decimal [&>ol]:mt-4 [&>ol]:pl-5 [&>ol>li+li]:mt-1 space-y-3") ?>">
            <?php echo $content; ?>
          </div>
        <?php endif; ?>

        <?php if ($has_btn) :
          $link_url = $link['url'];
          $link_title = $link['title'];
          $link_target = !empty($link['target']) ? $link['target'] : '_self';
        ?>
          <div class="pt-2 flex justify-center">
            <a class="btn-primary inline-block" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
              <?php echo esc_html($link_title); ?>
            </a>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
