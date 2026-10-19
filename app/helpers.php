<?php

if (! function_exists('asset_v')) {
    /** Asset URL with a file-modification version for long-term browser caching. */
    function asset_v(string $path): string
    {
        $file = public_path($path);

        return asset($path).(is_file($file) ? '?v='.filemtime($file) : '');
    }
}

if (! function_exists('whatsapp_url')) {
    function whatsapp_url(?string $text = null): ?string
    {
        $number = preg_replace('/\D+/', '', (string) config('site.contact.whatsapp'));

        return $number ? 'https://wa.me/'.$number.($text ? '?text='.rawurlencode($text) : '') : null;
    }
}
