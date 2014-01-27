<?php
namespace Asset\Provider;

use Asset\Compiler;
use Asset\Config;
use Illuminate\Support\ServiceProvider;

class AssetServiceProvider extends ServiceProvider
{
    protected $defer = false;
    public function boot()
    {
        $this->package('serafim/asset');
    }

    public function register()
    {
        $this->app['asset'] = $this->app->share(function($app){
            $compiler = \Asset\Compiler::getInstance(
                Config::getInstance([
                    Config::PATH_PUBLIC => public_path() . '/assets',
                    Config::PATH_SOURCE => app_path(),
                    Config::PATH_TEMP   => storage_path() . '/assets',
                    Config::PATH_URL    => '/assets/'
                ])
            );
            return $compiler;
        });
    }

    public function provides()
    {
        return [];
    }
}