<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app['app.controller'] = $app->share(function () use ($app) {
    $viewsDir = __DIR__ . "/../src/views/";
    return new \App\AppController($app, $viewsDir);
});

$app->get('/', function (Request $request) use ($app) {
    return $app['app.controller']->hello($request);
})->bind("hello");

function getApp()
{
    global $app;
    return $app;
}

function getService($service)
{
    global $app;
    return $app[$service];
}

return $app;
