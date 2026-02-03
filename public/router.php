<?php
// Router script for PHP built-in server
// This handles URL rewriting for our application

// Get the request URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove query string
$uri = strtok($uri, '?');

// If the requested file exists, serve it directly
if (file_exists(__DIR__ . $uri) && $uri !== '/' && pathinfo(__DIR__ . $uri, PATHINFO_EXTENSION) !== '') {
    return false; // Serve the file directly
}

// For all other requests, route through index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once __DIR__ . '/index.php';