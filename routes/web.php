<?php

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\HomeController;
use App\Middlewares\AuthMiddleware;
use Slim\App;

/**
 * PUBLIC ROUTES (NOT AUTHENTICATED)
 */
$app->group("/auth", function (App  $app) {

	$app->get("/login", LoginController::class . ":get")->setName("auth.login");
    $app->post("/login", LoginController::class . ":post");
    $app->get("/register" , RegisterController::class . ":get" )->setName("auth.register");
	$app->post("/register" , RegisterController::class . ":post" );

});

/**
 * PROTECTED ROUTES (REQUIRES AUTHENTICATION)
 */
$app->group("", function(App $app) {
    $app->get("/", HomeController::class . ":index")->setName("home");
})->add(new AuthMiddleware($app->getContainer()));