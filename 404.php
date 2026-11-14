<?php
get_header();

$title = function_exists('get_field') ? get_field('404__title', 'option') : '';
if (!$title) $title = 'Pagina non trovata';

$subtitle = function_exists('get_field') ? get_field('404__text', 'option') : '';
if (!$subtitle) $subtitle = 'La pagina che cerchi non esiste o è stata spostata.';
$subtitle = trim(wp_strip_all_tags($subtitle));

nundia_light_hero(array('eyebrow' => '404', 'title' => $title, 'subtitle' => $subtitle));
?>
<section class="container-ed py-14 lg:py-20">
    <a class="inline-flex min-h-12 items-center bg-primary px-7 text-[11px] uppercase tracking-[0.18em] text-white transition-colors hover:bg-primary/90" href="<?php echo esc_url(home_url('/')); ?>">Torna alla home</a>
</section>
<?php get_footer();
