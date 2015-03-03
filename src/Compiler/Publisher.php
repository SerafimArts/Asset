<?php
/**
 * This file is part of Assets package.
 *
 * Serafim <nesk@xakep.ru> (05.11.2014 12:39)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Compiler;


use Serafim\Asset\Compiler;
use Serafim\Asset\Compiler\GZip;
use Serafim\Asset\Compiler\SourceMaps\SourceMap;

/**
 * Class Publisher
 * @package Serafim\Asset\Compiler
 */
class Publisher
{
    /**
     * @var File
     */
    protected $file;

    /**
     * @var
     */
    protected $configs;

    /**
     * @var
     */
    protected $app;

    /**
     * @var
     */
    protected $sources;

    /**
     * @param File $file
     * @param $configs
     * @param $app
     */
    public function __construct(File $file, $configs, $app)
    {
        $this->app     = $app;
        $this->file    = $file;
        $this->configs = $configs;
        $this->sources = $this->file->compile($this->app);
        $this->sources = $this->file->minify($this->sources);

        /**
         * Create file.map
         * Not worked yet
         *
         * SourceMaps\Builder::make($file, $this->sources);
         */

    }

    /**
     * @param $file
     */
    protected function mapGenerator($file)
    {
        $path = $this->file->getPublicPath() . '.map';
        $url = $this->file->getPublicUrl() . '.map';

        $this->sources .= "\n//# sourceMappingURL=${url}\n";

        $map = new SourceMap($this->sources);
        $map->setFile(basename($path));

        // Parse file

        $this->publishFile($path, $map->toJson());
    }

    /**
     * @return Publisher
     * @throws \Exception
     */
    public function publish()
    {
        $file = $this->publishFile(
            $this->file->getPublicPath(),
            $this->sources
        );

        if (
            isset($this->configs['gzip']) &&
            $this->configs['gzip'] >= 0
        ) {
            $gzip = $this->file->getPublicPath() . '.gz';
            GZip\Builder::make($this->file->getPublicPath(), $gzip, (int)$this->configs['gzip']);
        }

        $compiler = $this->app['asset'];
        $compiler::addCompiledFile($this->file);

        return $file;
    }

    /**
     * @param $path
     * @param $sources
     * @return $this
     */
    protected function publishFile($path, $sources)
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        file_put_contents($path, $sources);

        return $this;
    }
}
