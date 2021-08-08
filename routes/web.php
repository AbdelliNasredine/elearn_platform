<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use Slim\App;

/**
 * PUBLIC ROUTES (NOT AUTHENTICATED)
 */
$app->group("/auth", function (App  $app) {

    // GET LOGIN
    $app->get("/sign-in", AuthController::class . ":getSignIn")->setName("sign-in");

    // POST LOGIN
    $app->post("/sign-in", AuthController::class . ":postSignIn")->setName("sign-in");
});

/**
 * PROTECTED ROUTES (REQUIRES AUTHENTICATION)
 */
$app->group("", function(App $app) {
    $app->get("/", HomeController::class . ":index");
});