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
    'cache'   => env('ASSETS_CACHE', false),

    'gzip'    => env('ASSETS_CACHE', -1), // gzip level (-1 == do not build zip files)

    'publish' => 'basic',

    'paths'   => [
        base_path('resources/assets'),
        base_path('resources/assets/javascripts'),
        base_path('resources/assets/stylesheets'),
        base_path('resources/assets/images'),
    ],

    'public'  => public_path('assets'),

    'url'     => url('/assets'),

    'drivers' => [
        'Serafim\\Asset\\Driver\\CoffeePhpDriver'       => ['coffee'],
        'Serafim\\Asset\\Driver\\JsDriver'              => ['js'],
        'Serafim\\Asset\\Driver\\ScssPhpDriver'         => ['scss'],
        'Serafim\\Asset\\Driver\\SassPhpDriver'         => ['sass'],
        'Serafim\\Asset\\Driver\\LessPhpDriver'         => ['less'],
        'Serafim\\Asset\\Driver\\CssDriver'             => ['css'],
        'Serafim\\Asset\\Driver\\ImageDriver'           => ['jpg', 'png', 'gif'],
    ],

    'output'  => [
        'js'  => 'Serafim\\Asset\\Serialize\\JsSerialize',
        'css' => 'Serafim\\Asset\\Serialize\\CssSerialize',
        'jpg' => 'Serafim\\Asset\\Serialize\\ImageSerialize',
        'png' => 'Serafim\\Asset\\Serialize\\ImageSerialize',
        'gif' => 'Serafim\\Asset\\Serialize\\ImageSerialize',
    ],

    'minify'  => [
        'enable' => env('ASSETS_MINIFY', false),
        'js'     => 'Serafim\\Asset\\Minify\\JsNativeMinify',
        'css'    => 'Serafim\\Asset\\Minify\\CssNativeMinify',
    ],
];
