<?php
if (!function_exists('asset_link')) {
    function asset_link($file, array $options = []) {
        return App::make('asset')->make($file)->toLink($options);
    }
}

if (!function_exists('asset_source')) {
    function asset_source($file, array $options = []) {
        return App::make('asset')->make($file)->getInline($options);
    }
}