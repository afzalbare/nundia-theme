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

if (!have_rows('accordion_items')) {
  if (!empty($is_preview)) {
    echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Accordion Block: Nessun elemento configurato.</div>';
  }
  return;
}
?>

<!-- FAQ Section -->
<section <?php echo $anchor; ?> class="md:py-16 py-8 <?php echo esc_attr($class_name); ?>">
  <div class="holder space-y-4">
      <?php $first_item = true; ?>
      <?php while (have_rows('accordion_items')) : the_row();
        $title = get_sub_field('title');
        $content = get_sub_field('content');
        if (!$title && !$content) continue;
      ?>
        <details class="group [&_summary::-webkit-details-marker]:hidden" <?php echo $first_item ? ' open' : ''; ?>>
          <summary class="flex items-center justify-between gap-1.5 rounded-md border border-gray-100 bg-gray-50 p-4 text-black cursor-pointer">
            <?php if ($title) : ?>
              <h2 class="text-xl font-medium"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
            <?php require get_template_directory() . '/svgs/bootom-arrow.php'; ?>
          </summary>

          <?php if ($content) : ?>
            <div class="<?php ksa("px-4 pt-4 text-black/80 text-base font-normal [&>ul]:list-disc [&>ul]:mt-4 [&>ul]:pl-5 [&>ul>li+li]:mt-1 [&>ol]:list-decimal [&>ol]:mt-4 [&>ol]:pl-5 [&>ol>li+li]:mt-1 space-y-3") ?>">
              <?php echo $content; ?>
            </div>
          <?php endif; ?>
        </details>
        <?php $first_item = false; ?>
      <?php endwhile; ?>
  </div>
</section>
<!-- FAQ Section End-->
