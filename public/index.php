<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

header_remove('X-Powered-By');

// Application root. Standard layout: one level up. Shared-hosting layout (public files copied
// into public_html, app uploaded beside it as "aparkitsolutions"): see docs/DEPLOYMENT.md.
$base = is_file(__DIR__.'/../vendor/autoload.php') ? __DIR__.'/..' : __DIR__.'/../aparkitsolutions';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $base.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $base.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $base.'/bootstrap/app.php';
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
