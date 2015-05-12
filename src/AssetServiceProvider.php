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
use Serafim\Asset\Compiler\CacheManifest;

/**
 * Class AssetServiceProvider
 * @package Asset\Provider
 */
class AssetServiceProvider extends ServiceProvider
{
    /**
     * Helpers path
     */
    const PATH_HELPERS = '/helpers.php';

    /**
     * Configs path
     */
    const PATH_CONFIGS = '/config/config.php';

    /**
     * Destination config file name
     */
    const PATH_CONFIGS_DEST = 'assets';

    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * Register package
     */
    public function boot()
    {
        $this->publishes([
            $this->getDefaultConfigs() => config_path(self::PATH_CONFIGS_DEST . '.php')
        ], 'config');
    }

    /**
     * Register an application
     */
    public function register()
    {
        require_once __DIR__ . self::PATH_HELPERS;

        $this->mergeConfigFrom($this->getDefaultConfigs(), self::PATH_CONFIGS_DEST);

        $this->app['asset'] = $this->app->share(function ($app)  {
            $configs  = $this->app->config->get(self::PATH_CONFIGS_DEST);
            $compiler = new Compiler($this->app, $configs);

            $app['events']->fire(Events::BOOT, $compiler);

            return $compiler;
        });
    }

    /**
     * @return string
     */
    protected function getDefaultConfigs()
    {
        return __DIR__ . self::PATH_CONFIGS;
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
