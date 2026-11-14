<?php
/**
 * Home page – uses layout-modules structure like blessingChild.
 * Design, styles, and markup are 100% identical.
 */
get_header();

get_template_part('layout-modules/_hero-slider');
get_template_part('layout-modules/_categories');
get_template_part('layout-modules/_about-section');
get_template_part('layout-modules/_bestsellers');
get_template_part('layout-modules/_b2b-banner');
get_template_part('layout-modules/_news-recipes');
get_template_part('layout-modules/_newsletter');

get_footer();
