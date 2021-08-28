<?php

/**
 * ADMIN ROUTES
 */

use App\Controllers\AdminController;
use App\Controllers\Management\DocumentController;
use App\Controllers\Management\RoleController;
use App\Controllers\Management\UserController;
use Slim\App;

$app->group("/admin", function (App $app) {

	$app->get("[/]", AdminController::class . ":index")->setName("admin.index");

	// user management
	$app->get("/users", UserController::class . ":all")->setName("admin.users");
	$app->map(["GET", "POST"], "/users/add", UserController::class . ":add")
		->setName("admin.addUser");
	$app->map(["GET", "POST"], "/users/{id:[0-9]+}/edit", UserController::class . ":edit")
		->setName("admin.editUser");
	$app->get("/users/{id:[0-9]+}/delete", UserController::class . ":delete")
		->setName("admin.deleteUser");

	// role management
	$app->get("/roles", RoleController::class . ":show")->setName("admin.roles");
	$app->map(["GET", "POST"], "/roles/add", RoleController::class . ":add")->setName("admin.addRole");

	// documents management
	$app->get("/documents", DocumentController::class . ":index")
		->setName("admin.documents");


})->add(new \App\Middlewares\AdminMiddleware($app->getContainer()));