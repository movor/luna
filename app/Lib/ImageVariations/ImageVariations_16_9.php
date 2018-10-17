<?php

namespace App\Lib\ImageVariations;

class ImageVariations_16_9 extends ImageVariationsBase
{
    public static function getSizes()
    {
        return [
            'xl' => '1280x720',
            'lg' => '640x360',
            'md' => '370x208',
            'sm' => '200x200',
            'xs' => '100x100'
        ];
    }
}