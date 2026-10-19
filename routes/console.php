<?php

use Illuminate\Support\Facades\Artisan;

/*
| Minify public/assets/css/app.css → app.min.css (no Node toolchain needed).
| Run after every CSS change; the layout loads the .min file.
*/
Artisan::command('assets:build', function () {
    $src = public_path('assets/css/app.css');
    $css = file_get_contents($src);

    // Protect strings and url() contents, then minify.
    $keep = [];
    $css = preg_replace_callback('/("([^"\\\\]|\\\\.)*"|\'([^\'\\\\]|\\\\.)*\'|url\([^)]*\))/', function ($m) use (&$keep) {
        $keep[] = $m[0];

        return '___K'.(count($keep) - 1).'___';
    }, $css);
    $css = preg_replace('!/\*.*?\*/!s', '', $css);
    $css = preg_replace('/\s+/', ' ', $css);
    $css = preg_replace('/\s*([{};,>])\s*/', '$1', $css);
    $css = preg_replace('/:\s+/', ':', $css);
    $css = str_replace(';}', '}', $css);
    // Restore spaces required inside selectors like ":not(x) .y" were kept; restore protected tokens.
    $css = preg_replace_callback('/___K(\d+)___/', fn ($m) => $keep[$m[1]], trim($css));

    file_put_contents(public_path('assets/css/app.min.css'), $css);
    $this->info(sprintf('app.min.css written (%s KB → %s KB)', round(filesize($src) / 1024, 1), round(strlen($css) / 1024, 1)));
})->purpose('Minify the site stylesheet');
