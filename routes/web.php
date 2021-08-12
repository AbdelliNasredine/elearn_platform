<?php

use App\Auth\Auth;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\HomeController;
use App\Lib\Session;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use Slim\App;


/**
 * GUEST ROUTES (NO AUTHENTICATION)
 */
$app->group("/auth", function (App  $app) {

	$app->get("/login", LoginController::class . ":get")->setName("auth.login");
    $app->post("/login", LoginController::class . ":post");
    $app->get("/register" , RegisterController::class . ":get" )->setName("auth.register");
	$app->post("/register" , RegisterController::class . ":post" );

})->add(new GuestMiddleware($app->getContainer()));


/**
 * PROTECTED ROUTES (REQUIRES AUTHENTICATION)
 */
$app->group("", function(App $app) {
	$app->get("/", HomeController::class . ":index")->setName("home");

	// logout
	$app->get("/auth/logout", function ($request, $response) {
		Session::destroy(Auth::AUTH_ID_KEY);
		return $response->withRedirect($this->router->pathFor("home"));
	});
})->add(new AuthMiddleware($app->getContainer()));