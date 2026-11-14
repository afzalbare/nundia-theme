<?php
/**
 * Fallback template (search results, archives without a dedicated template).
 */
get_header();
nundia_light_hero(array(
    'eyebrow' => is_search() ? 'Ricerca' : 'Archivio',
    'title' => is_search() ? sprintf('Risultati per “%s”', get_search_query()) : wp_strip_all_tags(get_the_archive_title()),
    'subtitle' => '',
));
?>
<section class="container-ed py-14 lg:py-20">
    <?php if (have_posts()): ?>
        <div class="grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
            <?php while (have_posts()): the_post(); ?>
                <a class="group block" href="<?php the_permalink(); ?>">
                    <div class="overflow-hidden rounded-md"><?php echo nundia_post_image(get_the_ID(), null, 'relative aspect-[4/3] w-full transition-transform duration-[1400ms] group-hover:scale-[1.04]'); ?></div>
                    <h3 class="mt-4 text-lg leading-snug group-hover:text-primary"><?php the_title(); ?></h3>
                    <p class="mt-2 line-clamp-2 text-[13px] leading-relaxed text-muted-foreground"><?php echo esc_html(get_the_excerpt()); ?></p>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="py-16 text-center text-[15px] text-muted-foreground">Nessun contenuto trovato.</p>
    <?php endif; ?>
</section>
<?php get_footer();
