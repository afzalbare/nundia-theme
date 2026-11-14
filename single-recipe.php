<?php
/**
 * Single recipe.
 */
get_header();
while (have_posts()): the_post();
    $id = get_the_ID();
    $prep = (int) get_post_meta($id, 'prep_minutes', true);
    $cook = (int) get_post_meta($id, 'cook_minutes', true);
    $servings = (int) get_post_meta($id, 'servings', true);
    $ingredients = nd_lines(get_post_meta($id, 'ingredients', true));
    $steps = nd_lines(get_post_meta($id, 'instructions', true));
    $terms = wp_get_post_terms($id, 'recipe_category');
    $related_ids = function_exists('get_field') ? (array) get_field('related_products', $id) : array();
    $related = function_exists('wc_get_product') ? array_filter(array_map('wc_get_product', array_filter($related_ids))) : array();
    $body = trim(get_the_content());
    $recipe_img = nundia_post_image($id, null, 'relative aspect-[16/9] w-full', 'full');
    $has_time_bar = ($prep || $cook || $servings);
    $has_right = (!empty($steps) || $body);
    ?>
    <article class="pb-20">
        <div class="container-ed pt-8">
            <a class="inline-flex items-center gap-2 text-[10.5px] uppercase tracking-[0.2em] text-muted-foreground hover:text-foreground" href="<?php echo esc_url(get_post_type_archive_link('recipe')); ?>">
                <?php echo nundia_icon('arrow-left', 'h-3.5 w-3.5', 1.6); ?>Ricette
            </a>
        </div>
        <header class="container-ed mt-8 max-w-3xl">
            <p class="eyebrow">Ricette</p>
            <h1 class="mt-4 text-[clamp(2rem,4.2vw,3.2rem)] leading-[1.05]"><?php the_title(); ?></h1>
            <?php if (has_excerpt()): ?><p class="mt-5 text-[16px] leading-relaxed text-muted-foreground"><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
            <?php if ($has_time_bar): ?>
                <div class="mt-7 flex flex-wrap gap-6 border-y border-border py-4 text-[12px] text-muted-foreground">
                    <?php if ($prep): ?><span class="inline-flex items-center gap-2"><?php echo nundia_icon('clock', 'h-4 w-4', 1.5); ?>Preparazione: <?php echo $prep; ?> min</span><?php endif; ?>
                    <?php if ($cook): ?><span class="inline-flex items-center gap-2"><?php echo nundia_icon('clock', 'h-4 w-4', 1.5); ?>Cottura: <?php echo $cook; ?> min</span><?php endif; ?>
                    <?php if ($servings): ?><span class="inline-flex items-center gap-2"><?php echo nundia_icon('users', 'h-4 w-4', 1.5); ?><?php echo $servings; ?> persone</span><?php endif; ?>
                </div>
            <?php endif; ?>
        </header>

        <?php if ($recipe_img): ?>
            <div class="container-ed mt-10">
                <?php echo $recipe_img; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($ingredients) || $has_right): ?>
            <div class="container-ed mt-12 <?php echo (!empty($ingredients) && $has_right) ? 'grid gap-12 lg:grid-cols-[0.8fr_1.2fr] lg:gap-16' : 'max-w-3xl mx-auto'; ?>">
                <?php if (!empty($ingredients)): ?>
                    <div>
                        <h2 class="text-[11px] uppercase tracking-[0.22em] text-muted-foreground">Ingredienti</h2>
                        <ul class="mt-5 divide-y divide-border border-y border-border">
                            <?php foreach ($ingredients as $ing): ?><li class="py-3 text-[14px] leading-relaxed text-foreground/80"><?php echo esc_html($ing); ?></li><?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($has_right): ?>
                    <div>
                        <?php if (!empty($steps)): ?>
                            <h2 class="text-[11px] uppercase tracking-[0.22em] text-muted-foreground">Procedimento</h2>
                            <ol class="mt-5 space-y-6">
                                <?php foreach ($steps as $i => $step): ?>
                                    <li class="flex gap-5">
                                        <span class="mt-0.5 font-mono text-[11px] tracking-[0.18em] text-primary"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                                        <p class="text-[15px] leading-[1.85] text-foreground/85"><?php echo esc_html($step); ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        <?php endif; ?>
                        <?php if ($body): ?><div class="nd-prose mt-10"><?php the_content(); ?></div><?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($related)): ?>
            <section class="container-ed mt-16 border-t border-border pt-12">
                <h2 class="text-[clamp(1.4rem,2.4vw,2rem)]">Potrebbe interessarti</h2>
                <div class="mt-8 grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-3 lg:grid-cols-4 lg:gap-x-8">
                    <?php foreach ($related as $p) nundia_product_card($p); ?>
                </div>
            </section>
        <?php endif; ?>
    </article>
<?php endwhile;
get_footer();
