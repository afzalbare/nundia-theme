<?php
/**
 * Registers navigation menus for use with wp_nav_menu function
 * Mirrors blessingChild/_/inc/_register-navs.php
 */
function wd_register_menus()
{
    register_nav_menus(
        array(
            'primary'     => __('Primary Menu', 'nundia'),
            'footer_menu' => __('Footer Menu', 'nundia'),
        )
    );
}
add_action('init', 'wd_register_menus');
