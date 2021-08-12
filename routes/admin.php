<?php

/**
 * ADMIN ROUTES
 */

use App\Controllers\AdminController;
use Slim\App;

$app->group("/admin", function(App $app) {

	$app->get("[/]", AdminController::class . ":index")->setName("admin.index");

})->add(new \App\Middlewares\AdminMiddleware($app->getContainer()));