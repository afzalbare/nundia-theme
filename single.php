<?php
/**
 * Single news article.
 */
get_header();
while (have_posts()): the_post();
    $cat = get_the_category();
    $url = rawurlencode(get_permalink());
    ?>
    <article class="pb-20">
        <div class="container-ed pt-8">
            <a class="inline-flex items-center gap-2 text-[10.5px] uppercase tracking-[0.2em] text-muted-foreground hover:text-foreground" href="<?php echo esc_url(home_url('/journal/')); ?>">
                <?php echo nundia_icon('arrow-left', 'h-3.5 w-3.5', 1.6); ?>Notizie
            </a>
        </div>
        <header class="container-ed mt-8 max-w-3xl">
            <p class="eyebrow"><?php echo esc_html(($cat ? $cat[0]->name . ' · ' : '') . get_the_date('Y-m-d')); ?></p>
            <h1 class="mt-4 text-[clamp(2rem,4.4vw,3.4rem)] leading-[1.04]"><?php the_title(); ?></h1>
            <?php if (has_excerpt()): ?><p class="mt-5 text-[16px] leading-relaxed text-muted-foreground"><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
            <p class="mt-6 text-[11px] uppercase tracking-[0.18em] text-muted-foreground"><?php echo esc_html(nd_opt('brand_name')); ?></p>
        </header>
        <?php if (has_post_thumbnail()): ?>
            <div class="container-ed mt-10"><?php echo nundia_post_image(get_the_ID(), null, 'relative aspect-[16/8] w-full', 'full'); ?></div>
        <?php endif; ?>
        <div class="container-ed mt-12 max-w-3xl">
            <div class="nd-article space-y-6"><?php the_content(); ?></div>
            <div class="mt-12 flex items-center gap-3 border-t border-border pt-6">
                <span class="text-[10.5px] uppercase tracking-[0.18em] text-muted-foreground">Condividi</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noreferrer noopener" aria-label="Facebook" class="flex h-9 w-9 items-center justify-center border border-border hover:border-foreground"><?php echo nundia_icon('facebook', 'h-4 w-4', 1.5); ?></a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $url; ?>" target="_blank" rel="noreferrer noopener" aria-label="LinkedIn" class="flex h-9 w-9 items-center justify-center border border-border hover:border-foreground"><?php echo nundia_icon('linkedin', 'h-4 w-4', 1.5); ?></a>
                <button type="button" class="js-copy-link flex h-9 w-9 items-center justify-center border border-border hover:border-foreground" data-url="<?php echo esc_url(get_permalink()); ?>" aria-label="Copia link"><?php echo nundia_icon('link2', 'h-4 w-4', 1.5); ?></button>
            </div>
        </div>
    </article>
<?php endwhile;
get_footer();
