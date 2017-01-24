<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$varDirectory = __DIR__ . '/../var';
$app['paths.logs'] = "{$varDirectory}/logs/";

$app->register(new Igorw\Silex\ConfigServiceProvider(
    __DIR__ . '/config/config.php'
));
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app['http.timeout'] = 60;

$app['services.http'] = $app->share(function () use ($app) {
    return new \Utils\LoggedClient($app['services.logger'], $app['http.timeout']);
});

$app['services.logger'] = $app->share(function () use ($app) {
    return new \Utils\Logger($app['paths.logs'], time());
});

$app['services.groupKT'] = $app->share(function () use ($app) {
    return new PetProject\GroupKTService(
        $app['services.http']
    );
});

$app['app.controller'] = $app->share(function () use ($app) {
    $viewsDir = __DIR__ . "/../src/views/";
    return new \App\AppController($app, $viewsDir, $app['services.groupKT'], $app['credentials']);
});

$app->get('/', function (Request $request) use ($app) {
    return $app['app.controller']->hello($request);
})->bind("hello");

$app->post('/helloSubmit', function (Request $request) use ($app) {
    return $app['app.controller']->helloSubmit($request);
});

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
