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

$title = get_field('title');
$content = get_field('content');
$button = get_field('button');

$bg = get_field('background_image');
$bg_url = '';
if (is_array($bg) && !empty($bg['url'])) {
    $bg_url = $bg['url'];
} elseif (is_numeric($bg)) {
    $bg_url = wp_get_attachment_image_url($bg, 'full');
} elseif (is_string($bg)) {
    $bg_url = $bg;
}

if (!$title && !$content && !$button && !$bg_url) {
    if (!empty($is_preview)) {
        echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Promo Block: Nessun contenuto configurato.</div>';
    }
    return;
}

$has_btn = ($button && !empty($button['url']) && !empty($button['title']));
?>

<section <?php echo $anchor; ?> 
    <?php if ($bg_url): ?>
        style="background-image: url('<?php echo esc_url($bg_url); ?>');" 
        class="md:py-20 py-12 relative flex flex-col justify-center items-center bg-cover bg-center bg-fixed min-h-[400px] <?php echo esc_attr($class_name); ?>"
    <?php else: ?>
        class="md:py-20 py-12 relative flex flex-col justify-center items-center bg-primary text-white <?php echo esc_attr($class_name); ?>"
    <?php endif; ?>>
    
    <?php if ($bg_url): ?>
        <div class="overlay"></div>
    <?php endif; ?>

    <div class="holder relative z-10 w-full">
        <div class="grid <?php echo $has_btn ? 'md:grid-cols-4' : 'grid-cols-1 max-w-4xl mx-auto'; ?> items-center text-white gap-6">
            <!-- Text -->
            <div class="<?php echo $has_btn ? 'md:col-span-3' : 'col-span-1'; ?> space-y-4">
                <?php if ($title) : ?>
                    <h2 class="text-4xl font-semibold leading-[117%]">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($content) : ?>
                    <div class="<?php ksa("text-white/90 text-base font-normal [&>ul]:list-disc [&>ul]:mt-4 [&>ul]:pl-5 [&>ul>li+li]:mt-1 [&>ol]:list-decimal [&>ol]:mt-4 [&>ol]:pl-5 [&>ol>li+li]:mt-1 space-y-3") ?>">
                        <?php echo $content; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($has_btn) :
                $link_url = $button['url'];
                $link_title = $button['title'];
                $link_target = !empty($button['target']) ? $button['target'] : '_self';
            ?>
                <!-- Button -->
                <div class="md:col-span-1 flex md:justify-center">
                    <a class="btn-primary" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                        <?php echo esc_html($link_title); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
