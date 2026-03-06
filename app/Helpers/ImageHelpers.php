<?php

namespace App\Helpers;

class ImageHelpers
{
    /**
     * Get the primary logo path
     *
     * @return string
     */
    public static function getLogo(): string
    {
        return asset('logo/poricito.png');
    }

    /**
     * Get the transparent logo path
     *
     * @return string
     */
    public static function getLogoTransparent(): string
    {
        return asset('logo/poricito_transparent.png');
    }

    /**
     * Get the favicon URL
     *
     * @return string
     */
    public static function getFavicon(): string
    {
        return asset('favicon/favicon.ico');
    }

    /**
     * Get HTML for all favicon links
     *
     * @return \Illuminate\Support\HtmlString
     */
    public static function getFaviconLinks(): \Illuminate\Support\HtmlString
    {
        $html = '<link rel="icon" type="image/x-icon" href="' . self::getFavicon() . '">' . PHP_EOL;
        $html .= '        <link rel="apple-touch-icon" href="' . asset('favicon/apple-touch-icon.png') . '">' . PHP_EOL;
        $html .= '        <link rel="icon" type="image/png" sizes="32x32" href="' . asset('favicon/favicon-32x32.png') . '">' . PHP_EOL;
        $html .= '        <link rel="icon" type="image/png" sizes="16x16" href="' . asset('favicon/favicon-16x16.png') . '">' . PHP_EOL;
        $html .= '        <link rel="manifest" href="' . asset('favicon/site.webmanifest') . '">';

        return new \Illuminate\Support\HtmlString($html);
    }

    /**
     * Get the logo HTML image tag
     *
     * @param string $alt
     * @param array $attributes
     * @return \Illuminate\Support\HtmlString
     */
    public static function logoImage(string $alt = 'Logo', array $attributes = []): \Illuminate\Support\HtmlString
    {
        $attrs = '';
        foreach ($attributes as $key => $value) {
            $attrs .= ' ' . $key . '="' . e($value) . '"';
        }

        $html = '<img src="' . self::getLogo() . '" alt="' . e($alt) . '"' . $attrs . '>';

        return new \Illuminate\Support\HtmlString($html);
    }

    /**
     * Get the transparent logo HTML image tag
     *
     * @param string $alt
     * @param array $attributes
     * @return \Illuminate\Support\HtmlString
     */
    public static function logoTransparentImage(string $alt = 'Logo', array $attributes = []): \Illuminate\Support\HtmlString
    {
        $attrs = '';
        foreach ($attributes as $key => $value) {
            $attrs .= ' ' . $key . '="' . e($value) . '"';
        }

        $html = '<img src="' . self::getLogoTransparent() . '" alt="' . e($alt) . '"' . $attrs . '>';

        return new \Illuminate\Support\HtmlString($html);
    }
}
