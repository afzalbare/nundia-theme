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
$posts = get_field('team_box');
$link = get_field('page_link');
$has_link = ($link && !empty($link['url']) && !empty($link['title']));

if (!$title && !$content && empty($posts) && !$has_link) {
    if (!empty($is_preview)) {
        echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Team Block: Nessun membro o contenuto configurato.</div>';
    }
    return;
}
?>

<section <?php echo $anchor; ?> class="md:py-16 py-8 <?php echo esc_attr(trim($class_name)); ?>">
    <div class="holder">
        <div class="text-center">
            <?php if ($title || $content): ?>
                <div class="space-y-4">
                    <?php if ($title) : ?>
                        <h2 class="text-4xl font-bold text-primary"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                    <?php if ($content) : ?>
                        <div class="<?php ksa("text-black/80 text-base font-normal [&>ul]:list-disc [&>ul]:mt-4 [&>ul]:pl-5 [&>ul>li+li]:mt-1 [&>ol]:list-decimal [&>ol]:mt-4 [&>ol]:pl-5 [&>ol>li+li]:mt-1 space-y-3 max-w-[750px] mx-auto") ?>">
                            <?php echo $content; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($posts)) : ?>
                <!-- Team Grid -->
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4 mt-10">
                    <?php
                    global $post;
                    foreach ($posts as $post) :
                        setup_postdata($post);
                        $has_thumb = has_post_thumbnail($post);
                        $post_content = get_the_content(null, false, $post);
                        $occupation = get_field('occupation', $post->ID);
                    ?>
                        <div class="bg-white overflow-hidden rounded-md shadow-sm">
                            <?php if ($has_thumb) : ?>
                                <img class="w-full h-80 object-cover"
                                    src="<?php echo esc_url(get_the_post_thumbnail_url($post, 'large')); ?>"
                                    alt="<?php echo esc_attr(get_the_title($post)); ?>"
                                    loading="lazy" />
                            <?php endif; ?>

                            <div class="p-5 space-y-3 text-center">
                                <h3 class="text-2xl/[1.25rem] font-semibold text-gray-800">
                                    <?php echo esc_html(get_the_title($post)); ?>
                                </h3>
                                <?php if ($occupation) : ?>
                                    <h4 class="text-gray-400 uppercase text-lg tracking-wide font-semibold">
                                        <?php echo esc_html($occupation); ?>
                                    </h4>
                                <?php endif; ?>
                                <?php if ($post_content) : ?>
                                    <div class="text-gray-600 text-base font-normal">
                                        <?php echo apply_filters('the_content', $post_content); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($has_link) :
                $link_url = $link['url'];
                $link_title = $link['title'];
                $link_target = !empty($link['target']) ? $link['target'] : '_self';
            ?>
                <div class="mt-8 flex justify-center">
                    <a class="btn-primary" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                        <?php echo esc_html($link_title); ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
