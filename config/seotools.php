<?php

return [
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults' => [
            'separator' => ' | ',
            'title' => env('APP_NAME'), // set false to total remove
            'description' => null, // set false to total remove
            'keywords' => [],
            'canonical' => null, // Set null for using Url::current(), set false to total remove
        ],

        /*
         * Webmaster tags are always added.
         */
        // TODO
        // Check if we need to add something here
        'webmaster_tags' => [
            'google' => null,
            'bing' => null,
            'alexa' => null,
            'pinterest' => null,
            'yandex' => null,
        ],
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            //'title' => env('APP_NAME'), // set false to total remove
            //'description' => 'We are Movor', // set false to total remove
            'url' => null, // Set null for using Url::current(), set false to total remove
            'type' => false,
            'site_name' => false,
            'images' => [],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'title' => false, // set false to total remove
            //'card'        => 'summary',
            'site' => '@_movor',
        ],
    ],
];
