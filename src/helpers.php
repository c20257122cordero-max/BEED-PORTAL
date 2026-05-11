<?php

/**
 * Application helper functions.
 *
 * This file is included by index.php (the front controller) and provides
 * shared utilities available to all controllers and views.
 */

if (!function_exists('url')) {
    /**
     * Build an app-relative URL, prepending the subfolder base path.
     *
     * Works whether the app is at the document root or in a subdirectory.
     * Spaces in the base path are percent-encoded so the URL is valid in
     * HTML attributes and HTTP Location headers.
     *
     * @param string $path Path starting with '/' (e.g. '/login').
     * @return string Full URL path including the base prefix.
     */
    function url(string $path): string
    {
        $base = defined('APP_BASE') ? APP_BASE : '';
        // Encode each segment of the base path (spaces → %20, etc.)
        $encodedBase = implode('/', array_map('rawurlencode', explode('/', $base)));
        return $encodedBase . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    /**
     * Send a Location redirect header and exit.
     *
     * @param string $path App-relative path (e.g. '/login').
     */
    function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }
}
