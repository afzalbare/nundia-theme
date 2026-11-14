<?php
/* Template Name: Custom Page template */
?>

<?php get_header();
if (have_posts()) : while (have_posts()) : the_post(); ?>
    <section class="!py-[56px] md:!pt-[70px] md:!pb-[170px] custom-template">
      <div class="holder">
        <div class="grid md:grid-cols-6">
          <div class="<?php ksa('[&>p>span]:font-proximaNovaExtraCondensed [&>*]:text-sm [&>*]:font-medium [&>p>span]:block [&>p>span]:text-center [&>*]:leading-[1.8] [&>p>span]:uppercase [&>p>span]:tracking-[0.7px] [&>p>span]:mb-4 md:[&>p>span]:mb-5 [&>p>span]:text-primary-blue/50 [&>hr]:text-primary-blue/50 [&>h3]:font-proximaNovaExtraCondensed [&>h3]:text-2xl [&>h3]:font-semibold [&>h3]:leading-[1.3] [&>h3]:text-slate-gray [&>*]:text-slate-gray space-y-4 md:space-y-7 [&>ul]:list-disc [&>ul]:pl-6 [&>ol]:list-decimal [&>ol]:pl-6 textContent fade-in col-span-4') ?>" data-scroll>
            <?php the_content(); ?>
          </div>
        </div>
      </div>
    </section>
<?php endwhile;
endif;
get_footer(); ?>