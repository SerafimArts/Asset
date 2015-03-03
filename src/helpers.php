<?php

if (!function_exists('asset_link')) {
    /**
     * @param $file
     * @param array $options
     * @return mixed
     */
    function asset_link($file, array $options = [])
    {
        return App::make('asset')->make($file)->toLink($options);
    }
}


if (!function_exists('asset_source')) {
    /**
     * @param $file
     * @param array $options
     * @return mixed
     */
    function asset_source($file, array $options = [])
    {
        return App::make('asset')->make($file)->toInline($options);
    }
}


if (!function_exists('asset_manifest')) {
    /**
     * @param $file
     * @param array $options
     * @return mixed
     */
    function asset_manifest()
    {
        return App::make('asset')->manifest();
    }
}
