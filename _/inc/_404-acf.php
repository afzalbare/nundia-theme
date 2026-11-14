<?php
/**
 * 404 ACF field definitions
 * Mirrors blessingChild/_/inc/_404-acf.php
 */
if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group(array(
    'key' => 'group_5ba8bb5f98213',
    'title' => '404 Page Settings',
    'fields' => array(
        array(
            'key' => 'field_5ba8bb6c15a6b',
            'label' => 'Title',
            'name' => '404__title',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'default_value' => 'Pagina non trovata',
        ),
        array(
            'key' => 'field_5ba8bb7715a6c',
            'label' => 'Text',
            'name' => '404__text',
            'type' => 'wysiwyg',
            'instructions' => '',
            'required' => 0,
            'default_value' => '<p>La pagina che cerchi non esiste o è stata spostata.</p>',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 0,
            'delay' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'site-settings',
            ),
        ),
    ),
    'menu_order' => 5,
    'position' => 'normal',
    'style' => 'default',
    'active' => 1,
));

endif;
