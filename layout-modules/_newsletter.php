<?php
/**
 * Layout Module: Newsletter
 */
$eyebrow = nd('home_nl_eyebrow');
$title = nd('home_nl_title');
$body = nd('home_nl_body');
$button = nd('home_nl_button');

if (!$eyebrow && !$title && !$body) {
    return;
}

$arrow = nundia_icon('arrow-right', 'h-3.5 w-3.5 transition-transform group-hover:translate-x-1', 1.8);
$btn_label = $button ?: 'Iscriviti';
?>
<section class="bg-background pb-12">
    <div class="container-ed">
        <div class="mx-auto max-w-3xl text-center">
            <?php if ($eyebrow): ?>
                <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($title): ?>
                <h2 class="mt-4 font-serif text-[clamp(2rem,3.6vw,2.8rem)] font-normal leading-[1.08]"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
            <?php if ($body): ?>
                <p class="mx-auto mt-4 max-w-3xl text-[14px] leading-relaxed text-muted-foreground whitespace-normal md:whitespace-nowrap"><?php echo esc_html($body); ?></p>
            <?php endif; ?>
            <form class="js-ajax-form mx-auto mt-8 flex max-w-md flex-col gap-3 sm:flex-row" data-action="nundia_ajax_newsletter" novalidate>
                <label class="sr-only" for="newsletter-email">Email</label>
                <input id="newsletter-email" name="email" type="email" required placeholder="nome@email.com" class="min-h-12 flex-1 border border-input bg-background px-4 text-sm outline-none transition-colors focus:border-foreground">
                <button type="submit" class="group inline-flex min-h-12 items-center justify-center gap-2 bg-primary px-6 text-[10.5px] uppercase tracking-[0.18em] text-white transition-colors hover:bg-[#6F1616] disabled:opacity-60">
                    <?php echo esc_html($btn_label); ?><?php echo $arrow; ?>
                </button>
            </form>
            <p class="js-form-msg mt-4 text-[13px] text-muted-foreground" role="status" hidden></p>
        </div>
    </div>
</section>
