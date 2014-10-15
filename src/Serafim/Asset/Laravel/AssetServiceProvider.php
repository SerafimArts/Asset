<?php
/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (03.06.2014 13:21)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Serafim\Asset\Laravel;

use Illuminate\Support\ServiceProvider;
use Serafim\Asset\Commands\AssetGenerateCommand;
use Serafim\Asset\Compiler;

/**
 * Class AssetServiceProvider
 * @package Asset\Provider
 */
class AssetServiceProvider extends ServiceProvider
{
    const CONFIG_PATH   = '/../../../config/config.php';
    const HELPERS_PATH  = '/../helpers.php';

    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * Register package
     */
    public function boot()
    {
        $this->package('serafim/asset');
    }

    /**
     * Register application
     */
    public function register()
    {
        require_once __DIR__ . self::HELPERS_PATH;

        $this->app['asset'] = $this->app->share(function ($app) {

            $compiler = new Compiler($app, $this->getConfigs($app));
            $app['events']->fire(Compiler::EVENT_BOOT, $compiler);

            return $compiler;
        });
    }

    /**
     * @param $app
     * @return array
     */
    public function getConfigs($app)
    {
        $config = $app->config->get('asset::config');
        return array_merge($this->getDefaultConfigs(), $config);
    }

    /**
     * @return array
     */
    public function getDefaultConfigs()
    {
        return require __DIR__ . self::CONFIG_PATH;
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
