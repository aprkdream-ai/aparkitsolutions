<?php

if (! function_exists('asset_v')) {
    /** Asset URL with a file-modification version for long-term browser caching. */
    function asset_v(string $path): string
    {
        $file = public_path($path);

        return asset($path).(is_file($file) ? '?v='.filemtime($file) : '');
    }
}

if (! function_exists('inline_css')) {
    /** Minified site CSS for inlining in <head> (removes the render-blocking stylesheet request). */
    function inline_css(): Illuminate\Support\HtmlString
    {
        static $css;
        $css ??= str_replace('url("../fonts/', 'url("'.asset('assets/fonts').'/', (string) @file_get_contents(public_path('assets/css/app.min.css')));

        return new Illuminate\Support\HtmlString($css);
    }
}

if (! function_exists('email_obfuscate')) {
    /**
     * Encodes an email address as HTML character references: browsers render it and follow mailto:
     * links normally, but naive scrapers searching the HTML for "name@domain" don't find it.
     */
    function email_obfuscate(?string $email): Illuminate\Support\HtmlString
    {
        $out = '';
        foreach (mb_str_split((string) $email) as $ch) {
            $out .= '&#'.mb_ord($ch).';';
        }

        return new Illuminate\Support\HtmlString($out);
    }
}

if (! function_exists('whatsapp_url')) {
    function whatsapp_url(?string $text = null): ?string
    {
        $number = preg_replace('/\D+/', '', (string) config('site.contact.whatsapp'));

        return $number ? 'https://wa.me/'.$number.($text ? '?text='.rawurlencode($text) : '') : null;
    }
}
