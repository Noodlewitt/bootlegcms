<?php
// To allow Laravel to work from sub-directories, the MIK custom server configuration sends an
// environment variable called LARAVEL_DIR. This string needs to be removed from the REQUEST_URI
// environment variable so routing works correctly under a sub-directory and a domain. This will
// only work when using server.php to simulate the rewrite module.
if (isset($_SERVER['LARAVEL_DIR']))
{
    $laravel_dir            = str_replace('/', '\/', $_SERVER['LARAVEL_DIR']);
    $_SERVER['REQUEST_URI'] = preg_replace('/^{$laravel_dir}/', '', $_SERVER['REQUEST_URI']);
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = urldecode($uri);

$paths = require __DIR__.'/bootstrap/paths.php';

$requested = $paths['public'].$uri;

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' and file_exists($requested))
{
	return false;
}

require_once $paths['public'].'/index.php';
