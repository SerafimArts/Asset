<?php
/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (03.06.2014 13:21)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Serafim\Asset;

use Illuminate\Support\ServiceProvider;
use Serafim\Asset\Compiler;

/**
 * Class AssetServiceProvider
 * @package Asset\Provider
 */
class AssetServiceProvider extends ServiceProvider
{
    const HELPERS_PATH  = '/helpers.php';

    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * Register package
     */
    public function boot()
    {
        $this->package('serafim/asset', 'asset', __DIR__);
    }

    /**
     * Register application
     */
    public function register()
    {
        require_once __DIR__ . self::HELPERS_PATH;

        $this->app['asset'] = $this->app->share(function ($app) {
            $configs = $app->config->get('asset::config');
            $compiler = new Compiler($app, $configs);
            $app['events']->fire(Events::BOOT, $compiler);

            return $compiler;
        });
    }
    /**
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
