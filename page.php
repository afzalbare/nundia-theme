<?php
/**
 * Default page (legal pages: Privacy, Cookie Policy, Termini, Spedizioni e resi, …).
 * The first paragraph of the content is shown as the lead.
 */
get_header();
while (have_posts()): the_post();
    $hero = nd_hero('_legal', get_the_ID());
    nundia_light_hero($hero);
    ?>
    <section class="container-ed py-14 lg:py-20">
        <div class="max-w-2xl">
            <div class="nd-legal"><?php the_content(); ?></div>
            <?php echo nd_draft_badge('mt-10'); ?>
        </div>
    </section>
<?php endwhile;
get_footer();
