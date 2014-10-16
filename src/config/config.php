<?php
/**
 * This file is part of Assets package.
 *
 * Serafim <nesk@xakep.ru> (14.10.2014 19:57)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    // requie only unique files
    'unique' => true,

    // do not check file updates if file already exists
    'cache'  => (bool)App::environment('production'),

    // generate /path/file (original) or /path/{HASH}/{HASH}/file (advanced) paths
    'publish' => 'advanced',

    'path' => [
        // sources directory path
        'sources' => app_path('assets'),

        // public directory path
        'public'  => public_path('assets'),

        // assets url
        'url'     => '/assets',
    ],

    'minify' => [
        // enable minification
        'enable'      => (bool)App::environment('production'),

        // javascript minify driver
        'javascripts' => 'Serafim\\Asset\\Minify\\JsNativeMinify',

        // stylesheet minify driver
        'stylesheets' => 'Serafim\\Asset\\Minify\\CssNativeMinify',
    ],

    // drivers for file extensions (DriverName => [extension-1, extension-2])
    'drivers' => [
        'Serafim\\Asset\\Driver\\CoffeeNativeDriver' => ['coffee'],

        'Serafim\\Asset\\Driver\\LessNativeDriver' => ['less'],

        'Serafim\\Asset\\Driver\\ScssNativeDriver' => ['scss'],

        'Serafim\\Asset\\Driver\\SassNativeDriver' => ['sass'],

        'Serafim\\Asset\\Driver\\CssDriver' => ['css'],

        'Serafim\\Asset\\Driver\\JsDriver' => ['js'],
    ]
];
