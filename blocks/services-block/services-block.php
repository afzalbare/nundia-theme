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
$has_services = have_rows('services');
$has_left = ($block_title || $block_content || $has_services);

$welcome_title = get_field('welcome_title');
$welcome_image = get_field('welcome_image');
$welcome_content = get_field('welcome_content');
$button = get_field('button');
$has_btn = ($button && !empty($button['url']) && !empty($button['title']));
$has_right = ($welcome_title || $welcome_image || $welcome_content || $has_btn);

if (!$has_left && !$has_right) {
  if (!empty($is_preview)) {
    echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Services Block: Nessun dato configurato.</div>';
  }
  return;
}

$has_both = ($has_left && $has_right);
?>

<!-- Services Block -->
<section <?php echo $anchor; ?> class="<?php echo esc_attr(trim($class_name)); ?>">
  <div class="holder">
    <div class="<?php echo $has_both ? 'grid md:grid-cols-5 md:gap-6' : 'max-w-4xl mx-auto'; ?>">

      <?php if ($has_left) : ?>
      <!-- Left Content -->
      <div class="<?php echo $has_both ? 'md:col-span-3' : 'w-full'; ?> space-y-8 md:space-y-12 py-10">
        <?php if ($block_title || $block_content) : ?>
          <div class="space-y-3 md:space-y-6">
            <?php if ($block_title) : ?>
              <h2 class="text-3xl text-black opacity-90 font-medium"><?php echo esc_html($block_title); ?></h2>
            <?php endif; ?>

            <?php if ($block_content) : ?>
              <div class="<?php ksa("text-black/80 text-base font-normal [&>ul]:list-disc [&>ul]:mt-4 [&>ul]:pl-5 [&>ul>li+li]:mt-1 [&>ol]:list-decimal [&>ol]:mt-4 [&>ol]:pl-5 [&>ol>li+li]:mt-1 space-y-3") ?>">
                <?php echo $block_content; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php if ($has_services) : ?>
        <div class="grid md:grid-cols-2 gap-7">
          <?php while (have_rows('services')) : the_row();
            $icon_image = get_sub_field('icon_image');
            $link = get_sub_field('service_title');
            $service_content = get_sub_field('service_content');

            $icon_url = '';
            $icon_alt = '';
            if (is_array($icon_image) && !empty($icon_image['url'])) {
              $icon_url = $icon_image['url'];
              $icon_alt = !empty($icon_image['alt']) ? $icon_image['alt'] : '';
            } elseif (is_numeric($icon_image)) {
              $icon_url = wp_get_attachment_image_url($icon_image, 'thumbnail');
              $icon_alt = get_post_meta($icon_image, '_wp_attachment_image_alt', true) ?: '';
            } elseif (is_string($icon_image)) {
              $icon_url = $icon_image;
            }

            if (!$icon_url && !$link && !$service_content) continue;
          ?>
            <div class="flex gap-2 md:gap-4">
              <?php if ($icon_url) : ?>
                <div class="w-16 h-16 bg-blue-400 flex items-center justify-center shrink-0 aspect-[1] rounded-[40px]">
                  <img class="w-8 h-8 object-contain" src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($icon_alt); ?>" loading="lazy" />
                </div>
              <?php endif; ?>

              <div class="space-y-2">
                <?php if ($link && !empty($link['url']) && !empty($link['title'])) :
                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = !empty($link['target']) ? $link['target'] : '_self';
                ?>
                  <h3 class="text-lg/[1.2rem] text-black opacity-90 font-medium transition-all hover:text-primary">
                    <a class="button" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                  </h3>
                <?php endif; ?>

                <?php if ($service_content) : ?>
                  <div class="<?php ksa("text-black/80 text-base font-normal [&>ul]:list-disc [&>ul]:mt-4 [&>ul]:pl-5 [&>ul>li+li]:mt-1 [&>ol]:list-decimal [&>ol]:mt-4 [&>ol]:pl-5 [&>ol>li+li]:mt-1 space-y-3") ?>">
                    <?php echo $service_content; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <!-- Right Content -->
      <?php if ($has_right) :
        $wel_img_url = '';
        $wel_img_alt = '';
        if (is_array($welcome_image) && !empty($welcome_image['url'])) {
          $wel_img_url = $welcome_image['url'];
          $wel_img_alt = !empty($welcome_image['alt']) ? $welcome_image['alt'] : '';
        } elseif (is_numeric($welcome_image)) {
          $wel_img_url = wp_get_attachment_image_url($welcome_image, 'medium');
          $wel_img_alt = get_post_meta($welcome_image, '_wp_attachment_image_alt', true) ?: '';
        } elseif (is_string($welcome_image)) {
          $wel_img_url = $welcome_image;
        }
      ?>
        <div class="<?php echo $has_both ? 'md:col-span-2' : 'w-full'; ?>">
          <div class="bg-[#fafafa] px-6 py-10 space-y-4 md:space-y-8 h-full rounded-md shadow-sm">
            <?php if ($welcome_title || $wel_img_url) : ?>
              <div class="flex items-center gap-3 pb-3">
                <?php if ($welcome_title) : ?>
                  <h2 class="text-3xl text-black opacity-90 font-medium"><?php echo esc_html($welcome_title); ?></h2>
                <?php endif; ?>

                <?php if ($wel_img_url) : ?>
                  <div class="max-w-24 w-20 h-20 rounded-[50%] aspect-[1] overflow-hidden ml-auto shrink-0">
                    <img class="w-full h-full object-cover" src="<?php echo esc_url($wel_img_url); ?>" alt="<?php echo esc_attr($wel_img_alt); ?>" loading="lazy" />
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <?php if ($welcome_content) : ?>
              <div class="space-y-8">
                <div class="<?php ksa("text-black/80 text-base font-normal [&>ul]:list-disc [&>ul]:mt-4 [&>ul]:pl-5 [&>ul>li+li]:mt-1 [&>ol]:list-decimal [&>ol]:mt-4 [&>ol]:pl-5 [&>ol>li+li]:mt-1 space-y-3") ?>">
                  <?php echo $welcome_content; ?>
                </div>
              </div>
            <?php endif; ?>

            <?php if ($has_btn) :
              $link_url = $button['url'];
              $link_title = $button['title'];
              $link_target = !empty($button['target']) ? $button['target'] : '_self';
            ?>
              <div class="pt-4">
                <a class="btn-primary inline-block" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                  <?php echo esc_html($link_title); ?>
                </a>
              </div>
            <?php endif; ?>

          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
