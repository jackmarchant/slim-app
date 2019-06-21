<?php

namespace App\Traits;

trait UrlGenerator {
    /**
     * Generate an absolute url with the hostname
     */
    public function generateUrl(string $path): string
    {
        return getenv('SITE_HOSTNAME') . '/' . $path;
    }
}