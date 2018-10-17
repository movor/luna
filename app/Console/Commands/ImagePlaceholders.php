<?php

namespace App\Console\Commands;

use App\Lib\ImageVariations\ImageVariations_16_9;
use File;
use Illuminate\Console\Command;

class ImagePlaceholders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:placeholders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Image placeholders from application specific image variations';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $storagePath = storage_path('app/uploads/placeholders/');

        // Remove old placeholders if any
        File::delete(File::glob($storagePath . '*.png'));

        // Placeholder images base url and storage path
        $url = 'http://via.placeholder.com/';

        // Get unique resolutions
        $imageResolutions = array_unique(array_merge(
        // Add all image variations here
            array_values(ImageVariations_16_9::getSizes()),
            ['1920x1080']
        ));

        foreach ($imageResolutions as $imageResolution) {
            $filename = $imageResolution == '1920x1080'
                ? 'placeholder.png' // Original placeholder (without resize)
                : 'placeholder-' . $imageResolution . '.png';

            file_put_contents($storagePath . $filename, file_get_contents($url . $imageResolution));
        }

        return 1;
    }
}