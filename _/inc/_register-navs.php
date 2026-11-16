<?php
/**
 * Registers navigation menus for use with wp_nav_menu function
 * Mirrors blessingChild/_/inc/_register-navs.php
 */
function wd_register_menus()
{
    register_nav_menus(
        array(
            'primary'      => __('Menu Principale', 'nundia'),
            'footer_col_1' => __('Footer Colonna 1 (Esplora)', 'nundia'),
            'footer_col_2' => __('Footer Colonna 2 (La maison)', 'nundia'),
            'footer_col_3' => __('Footer Colonna 3 (Informazioni legali)', 'nundia'),
        )
    );
}
add_action('init', 'wd_register_menus');
