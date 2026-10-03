<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Locate the application root. Works both when `public/` is a subfolder of
// the app (normal Laravel layout, e.g. local dev) and when it's split out
// as a sibling folder next to a `backend/` folder (cPanel-style hosting
// where the document root is `public/` and the rest of the app lives in
// a separate, non-web-accessible `backend/` folder).
$appRoot = is_dir(__DIR__.'/../vendor') ? __DIR__.'/..' : __DIR__.'/../backend';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appRoot.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appRoot.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $appRoot.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
