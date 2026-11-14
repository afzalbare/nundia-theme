<?php
/**
 * Recipes archive (/ricette/).
 */
get_header();
nundia_page_hero(nd_hero('ricette'));
$recipes = get_posts(array('post_type' => 'recipe', 'numberposts' => -1));
$cats = get_terms(array('taxonomy' => 'recipe_category', 'hide_empty' => true, 'orderby' => 'term_id', 'order' => 'ASC'));
$btn = 'border px-4 py-2 text-[10.5px] uppercase tracking-[0.16em]';
$on = 'border-foreground bg-foreground text-white';
$off = 'border-border hover:border-foreground';
?>
<section class="container-ed py-10 lg:py-14">
    <?php if (!empty($recipes)): ?>
        <div class="flex flex-col gap-4 border-b border-border pb-6 sm:flex-row sm:items-center sm:justify-between">
            <?php if (!empty($cats) && !is_wp_error($cats)): ?>
                <div class="js-filter flex flex-wrap gap-2" data-target="#recipe-list" data-base="<?php echo esc_attr($btn); ?>" data-active="<?php echo esc_attr($on); ?>" data-inactive="<?php echo esc_attr($off); ?>">
                    <button type="button" data-filter="" class="<?php echo "$btn $on"; ?>">Tutti</button>
                    <?php foreach ($cats as $c): ?>
                        <button type="button" data-filter="<?php echo esc_attr($c->slug); ?>" class="<?php echo "$btn $off"; ?>"><?php echo esc_html($c->name); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div></div>
            <?php endif; ?>
            <label class="relative flex items-center">
                <?php echo nundia_icon('search', 'pointer-events-none absolute left-3 h-4 w-4 text-muted-foreground', 1.5); ?>
                <span class="sr-only">Cerca</span>
                <input type="search" data-search="#recipe-list" placeholder="Cerca" class="h-10 w-full border border-input bg-background pl-9 pr-3 text-[13px] outline-none focus:border-foreground sm:w-56">
            </label>
        </div>
        <div id="recipe-list" class="mt-10 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($recipes as $i => $p):
                $terms = wp_get_post_terms($p->ID, 'recipe_category');
                $total = (int) get_post_meta($p->ID, 'prep_minutes', true) + (int) get_post_meta($p->ID, 'cook_minutes', true);
                $servings = (int) get_post_meta($p->ID, 'servings', true);
                $img_html = nundia_post_image($p->ID, null, 'relative aspect-[4/3] w-full transition-transform duration-[1400ms] group-hover:scale-[1.04]');
                $excerpt = get_the_excerpt($p);
            ?>
                <div class="reveal" data-item data-cat="<?php echo esc_attr(implode(' ', wp_list_pluck($terms, 'slug'))); ?>" data-text="<?php echo esc_attr(mb_strtolower(get_the_title($p) . ' ' . $excerpt)); ?>"<?php echo $i ? ' style="transition-delay: ' . (80 * ($i % 3)) . 'ms;"' : ''; ?>>
                    <a class="group block" href="<?php echo esc_url(get_permalink($p)); ?>">
                        <?php if ($img_html): ?>
                            <div class="overflow-hidden rounded-md"><?php echo $img_html; ?></div>
                        <?php endif; ?>
                        <?php if ($terms): ?><p class="mt-4 text-[10px] uppercase tracking-[0.2em] text-muted-foreground"><?php echo esc_html($terms[0]->name); ?></p><?php endif; ?>
                        <h3 class="mt-2 text-lg leading-snug group-hover:text-primary"><?php echo esc_html(get_the_title($p)); ?></h3>
                        <?php if ($excerpt): ?>
                            <p class="mt-2 line-clamp-2 text-[13px] leading-relaxed text-muted-foreground"><?php echo esc_html($excerpt); ?></p>
                        <?php endif; ?>
                        <?php if ($total || $servings): ?>
                            <div class="mt-3 flex items-center gap-4 text-[11.5px] text-muted-foreground">
                                <?php if ($total): ?><span class="inline-flex items-center gap-1.5"><?php echo nundia_icon('clock', 'h-3.5 w-3.5', 1.5); ?><?php echo (int) $total; ?> min</span><?php endif; ?>
                                <?php if ($servings): ?><span class="inline-flex items-center gap-1.5"><?php echo nundia_icon('users', 'h-3.5 w-3.5', 1.5); ?><?php echo (int) $servings; ?></span><?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="js-empty hidden py-16 text-center text-[15px] text-muted-foreground">Nessuna ricetta trovata.</p>
    <?php else: ?>
        <p class="py-16 text-center text-[15px] text-muted-foreground">Nessuna ricetta disponibile al momento.</p>
    <?php endif; ?>
</section>
<?php get_footer();
