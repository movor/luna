<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload dir
    |--------------------------------------------------------------------------
    |
    | The dir where to store the images (relative from public)
    |
    */
    'dir' => [],

    /*
    |--------------------------------------------------------------------------
    | Filesystem disks (Flysytem)
    |--------------------------------------------------------------------------
    |
    | Define an array of Filesystem disks, which use Flysystem.
    | You can set extra options, example:
    |
    | 'my-disk' => [
    |        'URL' => url('to/disk'),
    |        'alias' => 'Local storage',
    |    ]
    */
    'disks' => [
        // 'uploads',
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the elFinder routes.
    |
    */

    'route' => [
        'prefix' => config('backpack.base.route_prefix', 'admin') . '/elfinder',
        'middleware' => ['web', 'admin'], // Set to null to disable middleware
    ],

    /*
    |--------------------------------------------------------------------------
    | Access filter
    |--------------------------------------------------------------------------
    |
    | Filter callback to check the files
    |
    */

    'access' => 'Barryvdh\Elfinder\Elfinder::checkAccess',

    /*
    |--------------------------------------------------------------------------
    | Roots
    |--------------------------------------------------------------------------
    |
    | By default, the roots file is LocalFileSystem, with the above public dir.
    | If you want custom options, you can set your own roots below.
    |
    */

    'roots' => [
        [
            'driver' => 'LocalFileSystem',
            'path' => storage_path('app/uploads'),
            'URL' => env('APP_URL') . '/uploads',
            'attributes' => [
                // Ignore .gitignore, .tmb and .quarantine
                // ['pattern' => '/.(.gitignore|.tmb|.quarantine)/', 'hidden' => true],
                // Prevent deletion of project folders
                // ['pattern' => '!^/blog_post$!', 'locked' => true, 'write' => false],
            ],
            'accessControl' => function ($attr, $path, $data, $volume, $isDir) {
                $storagePath = storage_path('app/uploads') . '/';

                //
                // Detect directories
                //

                if ($isDir) {

                    //
                    // Read Only (lock and prevent write)
                    //

                    $lockDirs = [
                        $storagePath . 'blog_post',
                    ];

                    if (in_array($path, $lockDirs)) {
                        if ($attr == 'locked') return true;
                        if ($attr == 'write') return false;
                    }

                    //
                    // Hide
                    //

                    $hideDirs = [
                        $storagePath . '.tmb',
                        $storagePath . '.quarantine',
                    ];

                    if ($attr == 'hidden' && in_array($path, $hideDirs)) {
                        return true;
                    }
                }

                //
                // Detect files
                //

                else {

                    //
                    // Hide recursive
                    //

                    $hideFilesRecursive = [
                        '.gitignore'
                    ];

                    if ($attr == 'hidden') {
                        $fileInfo = pathinfo($path);

                        if (in_array($fileInfo['basename'], $hideFilesRecursive)) {
                            return true;
                        }
                    }
                }
            }
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | These options are merged, together with 'roots' and passed to the Connector.
    | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1
    |
    */

    'options' => [
        'bind' => [
            'upload.presave' => function (&$path, &$name, $tmpname, $context, $volume) {
                // Sanitize uploaded file name
                $fileInfo = pathinfo($name);
                $name = str_slug($fileInfo['filename'], '_') . '.' . $fileInfo['extension'];
            },
        ],
    ],
];
