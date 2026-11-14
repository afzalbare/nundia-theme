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

$address = get_field('address');
$contact_number = get_field('contact_number');
$email_address = get_field('email_address');
$website = get_field('website');

$has_info = ($address || $contact_number || $email_address || $website);

$map = get_field('map');
$form_title = get_field('form_title');
$form_shortcode = get_field('form_shortcode');
$has_form = ($form_title || $form_shortcode);
$has_map_or_form = ($map || $has_form);

if (!$has_info && !$has_map_or_form) {
  if (!empty($is_preview)) {
    echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Contact Block: Nessun dato configurato.</div>';
  }
  return;
}
?>

<!-- Contact form -->
<section <?php echo $anchor; ?> class="md:py-16 py-8 <?php echo esc_attr($class_name); ?>">
  <div class="holder space-y-16">

    <?php if ($has_info) : ?>
    <!-- Contact Info -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">

      <?php if ($address) : ?>
        <div class="space-y-4 bg-light p-6 text-center">
          <h3 class="text-2xl text-black/80 font-semibold">Address</h3>
          <p class="text-black/60 text-base font-normal break-words"><?php echo esc_html($address); ?></p>
        </div>
      <?php endif; ?>

      <?php if ($contact_number) : ?>
        <div class="space-y-4 bg-light p-6 text-center">
          <h3 class="text-2xl text-black/80 font-semibold">Contact Number</h3>
          <a class="text-black/60 text-base font-normal break-words block hover:text-primary transition-colors" href="tel:<?php echo esc_html($contact_number); ?>">
            <?php echo esc_html($contact_number); ?>
          </a>
        </div>
      <?php endif; ?>

      <?php if ($email_address) : ?>
        <div class="space-y-4 bg-light p-6 text-center">
          <h3 class="text-2xl text-black/80 font-semibold">Email Address</h3>
          <a class="text-black/60 text-base font-normal break-words block hover:text-primary transition-colors" href="mailto:<?php echo esc_html($email_address); ?>">
            <?php echo esc_html($email_address); ?>
          </a>
        </div>
      <?php endif; ?>

      <?php if ($website) : ?>
        <div class="space-y-4 bg-light p-6 text-center">
          <h3 class="text-2xl text-black/80 font-semibold">Website</h3>
          <a class="text-black/60 text-base font-normal break-words block hover:text-primary transition-colors" href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener noreferrer">
            <?php echo esc_html($website); ?>
          </a>
        </div>
      <?php endif; ?>

    </div>
    <?php endif; ?>

    <?php if ($has_map_or_form) : ?>
    <!-- Contact form & Map -->
    <div class="<?php echo ($map && $has_form) ? 'grid md:grid-cols-2' : 'max-w-3xl mx-auto'; ?>">

      <?php if ($map) : ?>
        <div class="<?php ksa('h-[350px] md:h-full text-center [&>iframe]:w-full [&>iframe]:h-full'); ?>">
          <?php echo $map; ?>
        </div>
      <?php endif; ?>

      <?php if ($has_form) : ?>
      <div class="space-y-4 bg-light p-10 flex flex-col justify-center self-stretch">
        <?php if ($form_title) : ?>
          <h2 class="text-4xl font-bold text-primary"><?php echo esc_html($form_title); ?></h2>
        <?php endif; ?>

        <?php if ($form_shortcode) : ?>
          <div class="text-base text-black/70 contact-form">
            <?php echo do_shortcode($form_shortcode); ?>
          </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </div>
</section>
