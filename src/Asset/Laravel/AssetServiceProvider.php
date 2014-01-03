<?php
/**
 * @copyright  Copyright (c) 2013
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */

namespace Asset\Laravel
{
    use \Asset,
        \Illuminate\Support\ServiceProvider;

    class AssetCompiler
    {
        private static $_instance = null;
        public static function getInstance()
        {
            if (self::$_instance === null) {
                self::$_instance = self::createInstance();
            }
            return self::$_instance;
        }

        public static function getBasePath()
        {
            return app_path() . '/assets';
        }

        public static function getCachePath()
        {
            return public_path() . '/assets/';
        }

        private static function createInstance()
        {
            $instance = new Asset\Compiler([
                Asset\Config::CACHE => self::getCachePath(),
                Asset\Config::URL   => app('url')->to('/') . '/assets/',
                Asset\Config::BASE_PATH => AssetCompiler::getBasePath()
            ]);

            Asset\Trigger::css(function($source){
                return Asset\Helper\CssMin::minify($source);
            });
            #Asset\Trigger::js(function($source){
            #    return \JSMin::minify($source);
            #});
            return $instance;
        }
    }

    class AssetServiceProvider extends ServiceProvider
    {

        /**
         * Indicates if loading of the provider is deferred.
         *
         * @var bool
         */
        protected $defer = false;

        /**
         * Bootstrap the application events.
         *
         * @return void
         */
        public function boot()
        {
            $this->package('serafim/asset');
        }

        /**
         * Register the service provider.
         *
         * @return void
         */
        public function register()
        {
            // Register 'oauth'
            $this->app['asset'] = $this->app->share(function($app)
            {
                $compiler = \Asset\Laravel\AssetCompiler::getInstance();
                return $compiler;
            });
        }

        /**
         * Get the services provided by the provider.
         *
         * @return array
         */
        public function provides()
        {
            return array();
        }
    }
}

namespace Asset\Adaptor
{

    /**
     * Class Scss
     * @package Asset\Adaptor
     */
    class Scss extends AbstractAdaptor
    {
        /**
         * @var
         */
        protected static $type = self::TYPE_STYLE;

        /**
         * @param $sources
         * @return mixed
         */
        public static function compile($sources)
        {
            $sources = \Asset\Trigger::call('scss', $sources);
            $scss = new \scssc;
            $scss->addImportPath(
                \Asset\Laravel\AssetCompiler::getBasePath() . '/scss'
            );
            return $scss->compile($sources);
        }
    }
}