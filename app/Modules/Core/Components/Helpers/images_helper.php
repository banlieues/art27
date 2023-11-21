<?php
// Images_Helper

if (!function_exists('get_random_image'))
{
    function get_random_image($path = null)
    {
        $images = glob($path . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $image = $images[array_rand($images)];

        return $image; 
    }
}
