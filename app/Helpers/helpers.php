<?php

use Illuminate\Support\Facades\Storage;


if (!function_exists('storage_url')) {
    function storage_asset($file = ''): string
    {
        if (!file_exists(storage_path('app/public/' . $file))) {
            return asset('/assets/img/noimage.jpg');
        }
        return asset(AssetsFolderEnum::STORAGE_ASSETS->value . '/' . $file);
    }
}