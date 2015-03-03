<?php
namespace Serafim\Asset\Compiler\GZip;

use Exception;

/**
 * Class Builder
 * @package Serafim\Asset\Compiler\GZip
 */
class Builder
{
    /**
     * @param $src
     * @param bool $dst
     * @param int $level
     * @return bool
     * @throws Exception
     */
    public static function make($src, $dst = false, $level = 5)
    {
        if ($dst == false) {
            $dst = $src . '.gz';
        }

        if (!file_exists($src)) {
            throw new Exception("File ${src} doesn't exist");
        }

        $srcHandle = fopen($src, 'r');

        if (!file_exists($dst)) {
            $dstHandle = gzopen($dst, "w$level");
            while (!feof($srcHandle)) {
                $chunk = fread($srcHandle, 2048);
                gzwrite($dstHandle, $chunk);
            }
            fclose($srcHandle);
            gzclose($dstHandle);

            return true;
        }


        return false;
    }
}
