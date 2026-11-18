<?php
/**
 * Layout Module: News & Recipes
 */
$news = get_posts(array('post_type' => 'post', 'numberposts' => 2));
$recipes = get_posts(array('post_type' => 'recipe', 'numberposts' => 2));

if (empty($news) && empty($recipes)) {
    return;
}

$see_all = nd('home_see_all');
$arrow = nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.8);

$row = function ($p, $meta) {
    $img_html = nundia_post_image($p->ID, null, 'relative aspect-[4/3] w-full transition-transform duration-[1200ms] ease-out group-hover:scale-[1.06]', 'medium');
    ?>
    <a class="group flex items-start gap-4" href="<?php echo esc_url(get_permalink($p)); ?>">
        <?php if ($img_html): ?>
            <div class="w-28 shrink-0 overflow-hidden sm:w-32">
                <?php echo $img_html; ?>
            </div>
        <?php endif; ?>
        <div class="min-w-0 flex-1">
            <?php if ($meta): ?>
                <p class="text-[9.5px] uppercase tracking-[0.2em] text-muted-foreground"><?php echo esc_html($meta); ?></p>
            <?php endif; ?>
            <h3 class="mt-2 text-[1.1rem] leading-snug text-foreground transition-colors group-hover:text-primary"><?php echo esc_html(get_the_title($p)); ?></h3>
        </div>
    </a>
    <?php
};

$has_both = !empty($news) && !empty($recipes);
?>
<section class="bg-background py-16 lg:py-24">
    <div class="container-ed">
        <div class="<?php echo $has_both ? 'grid gap-12 lg:grid-cols-2 lg:gap-16' : 'max-w-2xl mx-auto space-y-12'; ?>">
            <?php if (!empty($news)): 
                $news_title = nd('home_journal_title');
            ?>
            <div class="reveal">
                <?php if ($news_title): ?>
                <div class="flex flex-wrap items-end justify-between gap-4 border-b border-border pb-4">
                    <?php if ($news_title): ?>
                        <h2 class="text-[clamp(1.7rem,2.8vw,2.3rem)] leading-none text-foreground"><?php echo esc_html($news_title); ?></h2>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <div class="mt-7 space-y-7">
                    <?php foreach ($news as $p) {
                        $cat = get_the_category($p->ID);
                        $meta_text = ($cat ? $cat[0]->name . ' · ' : '') . get_the_date('Y-m-d', $p);
                        $row($p, $meta_text);
                    } ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($recipes)): 
                $recipes_title = nd('home_recipes_title');
                $recipe_link = get_post_type_archive_link('recipe') ?: home_url('/ricette/');
            ?>
            <div class="reveal"<?php echo $has_both ? ' style="transition-delay: 110ms;"' : ''; ?>>
                <?php if ($recipes_title || $see_all): ?>
                <div class="flex flex-wrap items-end justify-between gap-4 border-b border-border pb-4">
                    <?php if ($recipes_title): ?>
                        <h2 class="text-[clamp(1.7rem,2.8vw,2.3rem)] leading-none text-foreground"><?php echo esc_html($recipes_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($see_all): ?>
                        <a class="group inline-flex items-center gap-2 text-[10px] uppercase tracking-[0.18em] text-primary" href="<?php echo esc_url($recipe_link); ?>"><?php echo esc_html($see_all); ?><?php echo $arrow; ?></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <div class="mt-7 space-y-7">
                    <?php foreach ($recipes as $p) {
                        $prep = (int) get_post_meta($p->ID, 'prep_minutes', true);
                        $cook = (int) get_post_meta($p->ID, 'cook_minutes', true);
                        $servings = (int) get_post_meta($p->ID, 'servings', true);
                        $parts = array();
                        if ($prep || $cook) {
                            $parts[] = ($prep + $cook) . ' min';
                        }
                        if ($servings) {
                            $parts[] = $servings . ' persone';
                        }
                        $row($p, implode(' · ', $parts));
                    } ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
