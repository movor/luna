<?php

/**
 * Fetch random image based on biggest size from image variations
 *
 * @return string
 */
function fetchRandomBase64Image()
{
    $image = file_get_contents('https://picsum.photos/1920/1080?random');

    return 'data:image/jpg;base64,' . base64_encode($image);
}