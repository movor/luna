<?php

namespace App\Console\Commands;

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
        // Placeholder images base url and storage path
        $url = 'http://via.placeholder.com/';
        $storagePath = storage_path('app/uploads/placeholders/');

        // Add one more size which will represent raw image (without size)
        $imageResolutions = array_merge(config('custom_castable.image_sizes'), ['2560x1440']);

        foreach ($imageResolutions as $imageResolution) {
            $filename = $imageResolution == '2560x1440'
                ? 'placeholder.png'
                : 'placeholder-' . $imageResolution . '.png';

            file_put_contents($storagePath . $filename, file_get_contents($url . $imageResolution));
        }

        return 1;
    }
}