<?php

/**
 * ADMIN ROUTES
 */

use App\Controllers\AdminController;
use App\Controllers\Management\RoleController;
use Slim\App;

$app->group("/admin", function(App $app) {

	$app->get("[/]", AdminController::class . ":index")->setName("admin.index");

	// user management

	// role management
	$app->get("/roles", RoleController::class . ":show")->setName("admin.roles");
	$app->map(["GET", "POST"], "/roles/add", RoleController::class . ":add")->setName("admin.addRole");

})->add(new \App\Middlewares\AdminMiddleware($app->getContainer()));