<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class ImagePlaceholder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image-placeholder:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Image placeholders from application specific variations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $imageSizesArray = config('custom_castable.image_sizes');

        $client = new Client(['base_uri' => 'http://via.placeholder.com']);

        // Initiate each request but do not block
        $promises = [];
        foreach ($imageSizesArray as $value) {
            $promises[$value] = $client->getAsync('/' . $value);
        }

        try {
            $results = Promise\unwrap($promises);
        } catch (\Throwable $e) {
            $this->error($e);
        }

        foreach ($results as $key => $value) {
            file_put_contents(storage_path('app/uploads/placeholders/placeholder') . '-' . $key. '.jpg', $value->getBody()->getContents());
        }
    }
}