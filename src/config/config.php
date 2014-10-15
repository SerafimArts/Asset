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
    'unique' => true,

    'cache'  => (bool)App::environment('production'),

    'path' => [
        'public'  => public_path('assets'),

        'sources' => app_path('assets'),

        'url'     => '/assets',
    ],

    'minify' => [
        'enable'      => (bool)App::environment('production'),

        'javascripts' => 'Serafim\\Asset\\Minify\\JsNativeMinify',

        'stylesheets' => 'Serafim\\Asset\\Minify\\CssNativeMinify',
    ],

    'drivers' => [
        'Serafim\\Asset\\Driver\\CoffeeNativeDriver' => ['coffee'],

        'Serafim\\Asset\\Driver\\LessNativeDriver' => ['less'],

        'Serafim\\Asset\\Driver\\ScssNativeDriver' => ['scss'],

        'Serafim\\Asset\\Driver\\SassNativeDriver' => ['sass'],

        'Serafim\\Asset\\Driver\\CssDriver' => ['css'],

        'Serafim\\Asset\\Driver\\JsDriver' => ['js'],
    ]
];