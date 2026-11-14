<?php
/**
 * News ("Notizie" / journal) – posts page.
 */
get_header();
$page_id = (int) get_option('page_for_posts');
nundia_page_hero(nd_hero('journal', $page_id));
$posts = get_posts(array('post_type' => 'post', 'numberposts' => -1));
$cats = get_categories(array('hide_empty' => true, 'exclude' => array(get_option('default_category'))));
$btn = 'border px-4 py-2 text-[10.5px] uppercase tracking-[0.16em]';
$on = 'border-foreground bg-foreground text-white';
$off = 'border-border hover:border-foreground';
$meta = function ($p) {
    $c = get_the_category($p->ID);
    return ($c ? $c[0]->name . ' · ' : '') . get_the_date('Y-m-d', $p);
};
?>
<section class="container-ed py-10 lg:py-14">
    <?php if (!empty($posts)): ?>
        <div class="flex flex-col gap-4 border-b border-border pb-6 sm:flex-row sm:items-center sm:justify-between">
            <?php if (!empty($cats) && !is_wp_error($cats)): ?>
                <div class="js-filter flex flex-wrap gap-2" data-target="#news-list" data-base="<?php echo esc_attr($btn); ?>" data-active="<?php echo esc_attr($on); ?>" data-inactive="<?php echo esc_attr($off); ?>">
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
                <input type="search" data-search="#news-list" placeholder="Cerca" class="h-10 w-full border border-input bg-background pl-9 pr-3 text-[13px] outline-none focus:border-foreground sm:w-56">
            </label>
        </div>
        <div id="news-list">
            <?php foreach ($posts as $i => $p):
                $slugs = implode(' ', wp_list_pluck(get_the_category($p->ID), 'slug'));
                $excerpt = get_the_excerpt($p);
                $text = esc_attr(mb_strtolower(get_the_title($p) . ' ' . $excerpt));
                $img_first = nundia_post_image($p->ID, null, 'relative aspect-[16/11] w-full transition-transform duration-[1400ms] group-hover:scale-[1.04]');
                $img_rest = nundia_post_image($p->ID, null, 'relative aspect-[4/3] w-full transition-transform duration-[1400ms] group-hover:scale-[1.04]');
                if ($i === 0): ?>
                    <div class="reveal mt-10" data-item data-cat="<?php echo esc_attr($slugs); ?>" data-text="<?php echo $text; ?>">
                        <a class="group grid gap-6 md:grid-cols-2 md:items-center" href="<?php echo esc_url(get_permalink($p)); ?>">
                            <?php if ($img_first): ?>
                                <div class="overflow-hidden rounded-md"><?php echo $img_first; ?></div>
                            <?php endif; ?>
                            <div class="<?php echo !$img_first ? 'md:col-span-2 max-w-2xl' : ''; ?>">
                                <p class="eyebrow"><?php echo esc_html($meta($p)); ?></p>
                                <h2 class="mt-4 text-[clamp(1.6rem,3vw,2.4rem)] leading-[1.1]"><?php echo esc_html(get_the_title($p)); ?></h2>
                                <?php if ($excerpt): ?>
                                    <p class="mt-4 max-w-md text-[14.5px] leading-relaxed text-muted-foreground"><?php echo esc_html($excerpt); ?></p>
                                <?php endif; ?>
                                <span class="mt-6 inline-flex items-center gap-2 text-[11px] uppercase tracking-[0.18em] text-primary">Leggi di più<?php echo nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.6); ?></span>
                            </div>
                        </a>
                    </div>
                    <?php if (count($posts) > 1): ?>
                        <div class="mt-14 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                    <?php endif; ?>
                <?php else: ?>
                    <div class="reveal" data-item data-cat="<?php echo esc_attr($slugs); ?>" data-text="<?php echo $text; ?>"<?php echo $i > 1 ? ' style="transition-delay: ' . (80 * (($i - 1) % 3)) . 'ms;"' : ''; ?>>
                        <a class="group block" href="<?php echo esc_url(get_permalink($p)); ?>">
                            <?php if ($img_rest): ?>
                                <div class="overflow-hidden rounded-md"><?php echo $img_rest; ?></div>
                            <?php endif; ?>
                            <p class="mt-4 text-[10px] uppercase tracking-[0.2em] text-muted-foreground"><?php echo esc_html($meta($p)); ?></p>
                            <h3 class="mt-2 text-lg leading-snug group-hover:text-primary"><?php echo esc_html(get_the_title($p)); ?></h3>
                            <?php if ($excerpt): ?>
                                <p class="mt-2 line-clamp-2 text-[13px] leading-relaxed text-muted-foreground"><?php echo esc_html($excerpt); ?></p>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endif;
            endforeach;
            if (count($posts) > 1): ?></div><?php endif; ?>
        </div>
        <p class="js-empty hidden py-16 text-center text-[15px] text-muted-foreground">Nessun articolo trovato.</p>
    <?php else: ?>
        <p class="py-16 text-center text-[15px] text-muted-foreground">Nessun articolo pubblicato al momento.</p>
    <?php endif; ?>
</section>
<?php get_footer();
