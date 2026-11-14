<?php
/**
 * Template Name: FAQ
 * Questions are managed in the admin under "FAQ" (order = "Ordine" attribute, category = "Categorie FAQ").
 */
get_header();
$pid = get_queried_object_id();
nundia_page_hero(nd_hero('faq', $pid));
$cats = get_terms(array('taxonomy' => 'faq_category', 'hide_empty' => true, 'orderby' => 'term_id', 'order' => 'ASC'));
$faqs = get_posts(array('post_type' => 'faq', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC'));
?>
<section class="container-ed py-14 lg:py-20">
    <?php if (!empty($faqs)): ?>
    <div class="grid gap-10 lg:grid-cols-[240px_1fr] lg:gap-16">
        <?php if (!empty($cats) && !is_wp_error($cats)): ?>
        <aside class="lg:sticky lg:top-32 lg:self-start">
            <p class="eyebrow">Categorie</p>
            <div class="js-filter mt-4 flex flex-wrap gap-2 lg:flex-col lg:items-start" data-target="#faq-list" data-active="text-primary" data-inactive="text-foreground/65 hover:text-foreground">
                <button type="button" data-filter="" class="text-left text-[12.5px] transition-colors text-primary">Tutti</button>
                <?php foreach ($cats as $c): ?>
                    <button type="button" data-filter="<?php echo esc_attr($c->slug); ?>" class="text-left text-[12.5px] transition-colors text-foreground/65 hover:text-foreground"><?php echo esc_html($c->name); ?></button>
                <?php endforeach; ?>
            </div>
            <?php if (function_exists('nd_draft_badge')) echo str_replace('inline-flex', 'mt-8 hidden lg:inline-flex', nd_draft_badge('')); ?>
        </aside>
        <?php endif; ?>
        <div class="<?php echo (empty($cats) || is_wp_error($cats)) ? 'max-w-3xl mx-auto w-full' : ''; ?>">
            <div id="faq-list" class="divide-y divide-border border-y border-border">
                <?php foreach ($faqs as $f):
                    $terms = wp_get_post_terms($f->ID, 'faq_category', array('fields' => 'slugs'));
                    $content = trim(wp_strip_all_tags($f->post_content));
                ?>
                    <div class="nd-faq" data-cat="<?php echo esc_attr(implode(' ', $terms)); ?>">
                        <button type="button" aria-expanded="false" class="flex w-full items-start justify-between gap-6 py-5 text-left">
                            <span class="text-[15.5px] leading-snug"><?php echo esc_html(get_the_title($f)); ?></span>
                            <?php echo nundia_icon('plus', 'nd-faq-icon mt-1 h-4 w-4 shrink-0 text-muted-foreground', 1.6); ?>
                        </button>
                        <?php if ($content): ?>
                            <div class="nd-faq-body grid transition-all duration-500 ease-out grid-rows-[0fr] opacity-0">
                                <div class="overflow-hidden">
                                    <p class="max-w-2xl whitespace-pre-line pb-6 text-[14px] leading-[1.85] text-muted-foreground"><?php echo esc_html($content); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>
<?php get_footer();
