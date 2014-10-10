<?php namespace Asset\Provider;

/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (03.06.2014 13:21)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Asset\Config;
use Asset\Manifest;
use Illuminate\Support\ServiceProvider;

/**
 * Class AssetServiceProvider
 * @package Asset\Provider
 */
class AssetServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

    /**
     *
     */
    public function boot()
    {
        $this->package('serafim/asset');
    }

    /**
     *
     */
    public function register()
    {
        $this->app['asset'] = $this->app->share(
            function ($app) {
                return $this->getCompiler();
            }
        );
    }

    /**
     * @return Manifest
     */
    protected function getCompiler()
    {
        $config = new Config(
            [
                Config::C_ASSETS_PATH => app_path('assets'),
                Config::C_PUBLIC_HTTP => '/assets',
                Config::C_CACHE_PATH => storage_path('assets'),
                Config::C_PUBLIC_PATH => public_path('assets')
            ]
        );

        return (new Manifest($config));
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [];
    }
}