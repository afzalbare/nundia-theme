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

$args = [
    'post_type'      => 'team-member',
    'posts_per_page' => -1,
    'post_status'    => 'publish'
];

$team_query = new WP_Query($args);

if (!$team_query->have_posts()) {
    if (!empty($is_preview)) {
        echo '<div class="p-6 border border-dashed border-gray-400 text-center text-sm text-gray-500">Team Grid Block: Nessun membro del team trovato.</div>';
    }
    wp_reset_postdata();
    return;
}
?>

<section <?php echo $anchor; ?> class="md:py-16 py-8 <?php echo esc_attr(trim($class_name)); ?>">
    <div class="holder">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <?php while ($team_query->have_posts()) : $team_query->the_post();
                $post_id = get_the_ID();
                $occupation = get_field('occupation', $post_id);
                $content = get_the_content();
            ?>
                <div class="bg-white overflow-hidden rounded-md shadow-sm">
                    <?php if (has_post_thumbnail($post_id)) : ?>
                        <img class="w-full h-80 object-cover"
                            src="<?php echo esc_url(get_the_post_thumbnail_url($post_id, 'large')); ?>"
                            alt="<?php echo esc_attr(get_the_title($post_id)); ?>"
                            loading="lazy" />
                    <?php endif; ?>

                    <div class="p-5 space-y-3 text-center">
                        <h3 class="text-2xl/[1.25rem] font-semibold text-gray-800">
                            <?php the_title(); ?>
                        </h3>
                        <?php if ($occupation) : ?>
                            <h4 class="text-gray-400 uppercase text-lg tracking-wide font-semibold">
                                <?php echo esc_html($occupation); ?>
                            </h4>
                        <?php endif; ?>
                        <?php if ($content) : ?>
                            <div class="text-gray-600 text-base font-normal">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php
wp_reset_postdata();
?>
