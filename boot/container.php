<?php

use Illuminate\Database\Capsule\Manager;
use Knlv\Slim\Views\TwigMessages;
use Slim\Flash\Messages;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

$container = $app->getContainer();

/**
 * DATABASE INITIALIZATION
 */
$capsule = new Manager;
$capsule->addConnection($container["db"]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container["db"] = function ($container) use ($capsule) {
    return $capsule;
};

/**
 * FLASH MESSAGES
 */
$container["flash"] = function ($container) {
    return new Messages();
};

/**
 * VIEW ENGINE INITIALIZATION
 */
$container["view"] = function ($container) {
    $view = new Twig($container["twig"]["path"], $container["twig"]["options"]);
    $router = $container->get('router');
    $uri = Uri::createFromEnvironment(new Environment($_SERVER));

    // extensions
    $view->addExtension(new TwigExtension($router, $uri));
    $view->addExtension(new TwigMessages($container["flash"]));

    return $view;
};


/**
 * CUSTOM ERROR HANDLERS
 */
//$container["errorHandler"] = function($container) {
//    return new App\Errors\ServerErrorHandler($container);
//};

$container["notFoundHandler"] = function ($container) {
    return new App\Errors\NotFoundHandler($container);
};

