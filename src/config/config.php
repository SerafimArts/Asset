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
    'cache'     => (bool)App::environment('production'),

    'publish'   => 'advanced',

    'paths'     => [
        app_path('assets'),
        app_path('assets/javascripts'),
        app_path('assets/stylesheets'),
        app_path('assets/images'),
        base_path('lib/assets'),
        base_path('lib/assets/javascripts'),
        base_path('lib/assets/stylesheets'),
        base_path('lib/assets/images'),
    ],

    'public' => public_path('assets'),

    'url' => URL::to('/assets'),

    'drivers' => [
        'Serafim\\Asset\\Driver\\CoffeePhpDriver'   => ['coffee'],
        'Serafim\\Asset\\Driver\\JsDriver'          => ['js'],

        'Serafim\\Asset\\Driver\\ScssPhpDriver'     => ['scss'],
        // With Compass framework ("leafo/scssphp-compass": "dev-master@dev")
        // 'Serafim\\Asset\\Driver\\ScssCompassPhpDriver' => ['scss'],
        'Serafim\\Asset\\Driver\\SassPhpDriver'     => ['sass'],
        'Serafim\\Asset\\Driver\\LessPhpDriver'     => ['less'],
        'Serafim\\Asset\\Driver\\CssDriver'         => ['css'],

        'Serafim\\Asset\\Driver\\ImageDriver'      => ['jpg', 'png', 'gif'],
    ],

    'output' => [
        'js'    => 'Serafim\\Asset\\Serialize\\JsSerialize',
        'css'   => 'Serafim\\Asset\\Serialize\\CssSerialize',

        'jpg'   => 'Serafim\\Asset\\Serialize\\ImageSerialize',
        'png'   => 'Serafim\\Asset\\Serialize\\ImageSerialize',
        'gif'   => 'Serafim\\Asset\\Serialize\\ImageSerialize',
    ],

    'minify' => [
        'enable'      => (bool)App::environment('production'),
        'javascripts' => 'Serafim\\Asset\\Minify\\JsNativeMinify',
        'stylesheets' => 'Serafim\\Asset\\Minify\\CssNativeMinify',
    ],
];
