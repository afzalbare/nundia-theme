<?php

/**
 * News ("Notizie" / journal) – posts page.
 */
get_header();
$page_id = (int) get_option('page_for_posts');
nundia_page_hero(nd_hero('journal', $page_id));
$posts = get_posts(array('post_type' => 'post', 'numberposts' => -1));
$cats = get_categories(array('hide_empty' => true, 'exclude' => array(get_option('default_category'))));
$btn = 'border px-4 py-2 text-[10.5px] uppercase tracking-[0.16em] transition-colors rounded-sm';
$on = 'border-foreground bg-foreground text-white';
$off = 'border-border text-foreground/70 hover:border-foreground';
?>
<section class="container-ed py-10 lg:py-14">
    <?php if (!empty($posts)): ?>
        <div class="flex flex-col gap-4 border-b border-border pb-6 sm:flex-row sm:items-center sm:justify-between">
            <?php if (!empty($cats) && !is_wp_error($cats)): ?>
                <div class="js-filter no-scrollbar -mx-1 flex gap-1.5 overflow-x-auto" data-target="#news-list" data-base="<?php echo esc_attr($btn); ?>" data-active="<?php echo esc_attr($on); ?>" data-inactive="<?php echo esc_attr($off); ?>">
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

        

        <!-- News Swiper Carousel with mouse wheel scroll & smaller uniform photos -->
        <div class="nd-carousel nd-news-carousel relative mt-7.5">
            <button type="button" aria-label="Notizie precedenti" class="nd-carousel-side-btn nd-carousel-side-prev nd-news-prev">
                <?php echo nundia_icon('chevron-left', 'h-5 w-5', 2.4); ?>
            </button>

            <div id="news-list" class="swiper news-swiper py-4 px-1 overflow-hidden">
                <div class="swiper-wrapper">
                    <?php foreach ($posts as $i => $p):
                        $c_list = get_the_category($p->ID);
                        $primary_cat = !empty($c_list) ? $c_list[0]->name : '';
                        $slugs = implode(' ', wp_list_pluck($c_list, 'slug'));
                        $excerpt = get_the_excerpt($p);
                        $text = esc_attr(mb_strtolower(get_the_title($p) . ' ' . $excerpt));
                        $img = nundia_post_image($p->ID, null, 'relative aspect-[16/10] w-full transition-transform duration-700 ease-out group-hover:scale-105', 'medium');
                    ?>
                        <div class="swiper-slide h-auto nd-news-card reveal" data-item data-cat="<?php echo esc_attr($slugs); ?>" data-text="<?php echo $text; ?>" style="transition-delay: <?php echo ($i * 70); ?>ms;">
                            <a class="group flex flex-col h-full bg-white border border-border/80 rounded-xl overflow-hidden shadow-sm transition-all duration-300 hover:shadow-xl hover:border-primary/40 hover:-translate-y-1" href="<?php echo esc_url(get_permalink($p)); ?>">
                                <div class="relative overflow-hidden aspect-[16/10] w-full bg-muted">
                                    <?php if ($img): ?>
                                        <?php echo $img; ?>
                                    <?php else: ?>
                                        <div class="h-full w-full bg-muted flex items-center justify-center text-muted-foreground text-xs uppercase tracking-wider font-medium">NUNDÌA</div>
                                    <?php endif; ?>
                                    <?php if ($primary_cat): ?>
                                        <span class="absolute top-3 left-3 rounded-full bg-white/95 backdrop-blur-sm px-3 py-1 text-[9.5px] font-semibold uppercase tracking-[0.16em] text-primary shadow-sm">
                                            <?php echo esc_html($primary_cat); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex flex-col flex-1 p-4 sm:p-5">
                                    <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground mb-2"><?php echo esc_html(get_the_date('d M Y', $p)); ?></p>
                                    <h3 class="text-[16px] sm:text-[17px] font-bold leading-snug text-foreground transition-colors group-hover:text-primary line-clamp-2">
                                        <?php echo esc_html(get_the_title($p)); ?>
                                    </h3>
                                    <?php if ($excerpt): ?>
                                        <p class="mt-2.5 line-clamp-2 text-[13px] leading-relaxed text-muted-foreground flex-1">
                                            <?php echo esc_html($excerpt); ?>
                                        </p>
                                    <?php endif; ?>
                                    <div class="mt-5 pt-3.5 border-t border-border/50 flex items-center justify-between">
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-[0.16em] text-primary group-hover:gap-2.5 transition-all duration-300">
                                            <span>Leggi di più</span>
                                            <?php echo nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.6); ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="button" aria-label="Notizie successive" class="nd-carousel-side-btn nd-carousel-side-next nd-news-next">
                <?php echo nundia_icon('chevron-right', 'h-5 w-5', 2.4); ?>
            </button>
        </div>
        <p class="js-empty hidden py-16 text-center text-[15px] text-muted-foreground">Nessun articolo trovato.</p>
    <?php else: ?>
        <p class="py-16 text-center text-[15px] text-muted-foreground">Nessun articolo pubblicato al momento.</p>
    <?php endif; ?>
</section>
<?php get_footer();
