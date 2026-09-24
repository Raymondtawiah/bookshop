<?php

if (!function_exists('vite_asset')) {
    function vite_asset(string $file): string {
        $manifestPath = public_path('build/manifest.json');

        if (!file_exists($manifestPath)) {
            return asset($file);
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (isset($manifest[$file])) {
            return asset('build/' . $manifest[$file]['file']);
        }

        return asset($file);
    }
}
