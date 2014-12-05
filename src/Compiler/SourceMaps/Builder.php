<?php
namespace Serafim\Asset\Compiler\SourceMaps;

use Exception;

class Builder
{
    /**
     * @param $file
     * @param $sources
     */
    public static function make($file, &$sources)
    {
        if ($file->getSplFileInfo()->getExtension() !== 'js') {
            // Making map only for js files
            return;
        }

        $path   = $file->getPublicPath() . '.map';
        $url    = $file->getPublicUrl()  . '.map';

        $sources .= "\n//# sourceMappingURL=${url}\n";

        $map = new SourceMap($sources);
        $map->setFile(basename($path));

        // Parse file
        dd($map->toJson());

        self::publishFile($path, $map->toJson());
    }

    /**
     * @param $path
     * @param $sources
     */
    protected static function publishFile($path, $sources)
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        file_put_contents($path, $sources);
    }
}