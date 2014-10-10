<?php namespace Asset\Provider;

/**
 * This file is part of Asset package.
 *
 * serafim <nesk@xakep.ru> (03.06.2014 13:21)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Asset\Config;
use Asset\Manifest;
use Illuminate\Support\ServiceProvider;

/**
 * Class AsseterviceProvider
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
        $this->package('serafim/Asset');
    }

    /**
     *
     */
    public function register()
    {
        $this->app['Asset'] = $this->app->share(
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
                Config::C_Asset_PATH => app_path('Asset'),
                Config::C_PUBLIC_HTTP => '/Asset',
                Config::C_CACHE_PATH => storage_path('Asset'),
                Config::C_PUBLIC_PATH => public_path('Asset')
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