<?php

use App\Auth\Auth;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\CourseController;
use App\Controllers\TeacherController;
use App\Controllers\FileController;
use App\Controllers\HomeController;
use App\Controllers\LanguageController;
use App\Controllers\ProfileController;
use App\Lib\Session;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\TeacherMiddleware;
use Slim\App;

/**
 * LANGUAGE SWITCH
 */
$app->get("/lang/{lang}", LanguageController::class)->setName("language.switch");

/**
 * GUEST ROUTES (NO AUTHENTICATION)
 */
$app->group("/auth", function (App $app) {

	$app->get("/login", LoginController::class . ":get")->setName("auth.login");
	$app->post("/login", LoginController::class . ":post");
	$app->get("/register", RegisterController::class . ":get")->setName("auth.register");
	$app->post("/register", RegisterController::class . ":post");

})->add(new GuestMiddleware($app->getContainer()));

/**
 * AUTH LOGOUT
 */
$app->get("/auth/logout", function ($request, $response) {
	Session::destroy(Auth::AUTH_ID_KEY);
	return $response->withRedirect($this->router->pathFor("home"));
})->setName("auth.logout")->add(new AuthMiddleware($app->getContainer()));


/**
 * PROTECTED ROUTES (REQUIRES AUTHENTICATION)
 */
$app->group("", function (App $app) {
	$app->get("/", HomeController::class . ":index")->setName("home");

	/**
	 * USER ROUTES (TEACHERS & STUDENTS)
	 */
	$app->get("/user/{id:[0-9]+}", ProfileController::class . ":index")
		->setName("user.profile");

	$app->map(["GET", "POST"], "/user/{id:[0-9]+}/edit", ProfileController::class . ":editProfile")
		->setName("user.editProfile");

	$app->get("/user/{id:[0-9]+}/profile-picture", ProfileController::class . ":changeProfilePicture")
		->setName("user.changeProfilePicture");

	$app->get("/user/settings", ProfileController::class . ":settings")
		->setName("user.settings");

	$app->post("/user/settings/change-password", ProfileController::class . ":changePassword")
		->setName("user.changePassword");

	/**
	 * COURSE ROUTES
	 */
	$app->get("/courses/{id:[0-9]}", CourseController::class . ":index")
		->setName("course");

	/**
	 * FILE ASSETS
	 */
	$app->get("/files/{name}", FileController::class . ":getFile")
		->setName("file");
	$app->post("/upload", FileController::class . ":upload")->setName("file.upload");

	/**
	 * ROUTES FOR ONLY TEACHER ROLE
	 */
	$app->get("/create-course", TeacherController::class . ":getCreatePage")
		->setName("create-course")->add(new TeacherMiddleware($app->getContainer()));

	$app->post("/create-course", TeacherController::class . ":storeCourse")
		->setName("create-course")->add(new TeacherMiddleware($app->getContainer()));

	$app->get("/my-courses", TeacherController::class . ":getMyCoursesPage")
		->setName("my-courses")->add(new TeacherMiddleware($app->getContainer()));

	$app->get("/my-courses/{id:[0-9]+}/edit", TeacherController::class . ":getEditCoursePage")
		->setName("teacher.course.edit")->add(new TeacherMiddleware($app->getContainer()));

})->add(new AuthMiddleware($app->getContainer()));