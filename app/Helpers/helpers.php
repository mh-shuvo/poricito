<?php

use App\Helpers\ImageHelpers;

if (!function_exists('logo')) {
    /**
     * Get the primary logo path or HTML image tag
     *
     * @param string|null $alt
     * @param array $attributes
     * @return string|\Illuminate\Support\HtmlString
     */
    function logo(?string $alt = null, array $attributes = [])
    {
        if ($alt !== null) {
            return ImageHelpers::logoImage($alt, $attributes);
        }

        return ImageHelpers::getLogo();
    }
}

if (!function_exists('logoTransparent')) {
    /**
     * Get the transparent logo path or HTML image tag
     *
     * @param string|null $alt
     * @param array $attributes
     * @return string|\Illuminate\Support\HtmlString
     */
    function logoTransparent(?string $alt = null, array $attributes = [])
    {
        if ($alt !== null) {
            return ImageHelpers::logoTransparentImage($alt, $attributes);
        }

        return ImageHelpers::getLogoTransparent();
    }
}

if (!function_exists('favicon')) {
    /**
     * Get the favicon URL
     *
     * @return string
     */
    function favicon(): string
    {
        return ImageHelpers::getFavicon();
    }
}

if (!function_exists('faviconLinks')) {
    /**
     * Get HTML for all favicon links
     *
     * @return \Illuminate\Support\HtmlString
     */
    function faviconLinks(): \Illuminate\Support\HtmlString
    {
        return ImageHelpers::getFaviconLinks();
    }
}
