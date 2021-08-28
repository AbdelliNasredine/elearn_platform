<?php

use App\Core\TwigExtensions\CsrfExtension;
use App\Core\TwigExtensions\DateExtension;
use App\Core\TwigExtensions\LanguageExtension;
use App\Lib\Hash;
use App\Services\StorageService;
use App\Services\UserService;
use Awurth\SlimValidation\Validator;
use Awurth\SlimValidation\ValidatorExtension;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Knlv\Slim\Views\TwigMessages;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use App\Auth\Auth;

$container = $app->getContainer();

/**
 * STORAGE DIR
 */

$container["storage_dir"] = $container["settings"]["upload_dir"];
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
 * CSRF
 */
$container["csrf"] = function($container) {
	return new Guard();
};

$container["validator"] = function($container) {
	return new Validator;
};

/**
 * TRANSLATOR
 */
$container["translator"] = function ($container) {

	$path = $container["translations"]["path"];
	$fallBack = $container["lang"]["default"];

	$loader = new FileLoader(
		new Filesystem(),
		$path
	);

	$translator = new Translator($loader, $container->language);
	$translator->setFallback($path);
	return $translator;
};

/**
 * VIEW ENGINE INITIALIZATION
 */
$container["view"] = function ($container) {
    $view = new Twig($container["twig"]["path"], $container["twig"]["options"]);
    $router = $container->get('router');
    $uri = Uri::createFromEnvironment(new Environment($_SERVER));

    // globals
	$view->getEnvironment()->addGlobal('auth', [
		'check' => $container->auth->check(),
		'user' => $container->auth->user(),
	]);

    // extensions
    $view->addExtension(new TwigExtension($router, $uri));
	$view->addExtension(new TwigMessages($container["flash"]));
	$view->addExtension(new CsrfExtension($container["csrf"]));
	$view->addExtension(new LanguageExtension($container));
	$view->addExtension(new ValidatorExtension($container['validator']));
	$view->addExtension(new DateExtension());
    return $view;
};

/**
 * AUTHENTICATION CLASS
 */
$container["auth"] = function ($container) {
	return new Auth;
};

/**
 * HASH CLASS
 */
$container["hash"] = function($container) {
	return new Hash;
};

/**
 * SERVICE CLASS
 */
$container[StorageService::class] = function ($container) {
	return new StorageService($container);
};
$container[UserService::class] = function ($container) {
	return new UserService($container);
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

$container["notAllowedHandler"] = function ($container) {
	return new \App\Errors\NotAllowedHandler($container);
};

