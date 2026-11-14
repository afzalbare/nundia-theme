<?php
// =================================================================
// ====== Custom image sizes (Mirrors blessingChild/_/inc/_image-sizes.php)
// =================================================================

function custom_image_sizes($image_sizes)
{
    global $_wp_additional_image_sizes;
    if (empty($_wp_additional_image_sizes))
        return $image_sizes;

    foreach ($_wp_additional_image_sizes as $id => $data) {
        if (!isset($image_sizes[$id]))
            $image_sizes[$id] = ucfirst(str_replace('-', ' ', $id));
    }

    return $image_sizes;
}
