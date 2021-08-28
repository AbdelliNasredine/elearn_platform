<?php

namespace App\Controllers;

use App\Auth\Auth;
use App\Lib\Session;
use App\Models\Profile;
use App\Models\User;
use App\Services\StorageService;
use App\Services\UserService;
use Psr\Container\ContainerInterface;
use Respect\Validation\Validator as V;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

class ProfileController extends BaseController
{

	private $userService;

	public function __construct(ContainerInterface $container)
	{
		parent::__construct($container);
		$this->userService = $container->get(UserService::class);
	}

	// GET /user/{user_id} user profile page
	public function index(Request $request, Response $response, $args)
	{
		$user_id = $args["id"];
		$idValidator = $this->validator->value($user_id, V::notEmpty()->numeric(), "user_id");
		if ($idValidator->isValid()) {
			$user = User::find($user_id);
			if (!$user) throw new NotFoundException($request, $response);
			return $this->view($response, "user/profile.twig", ["user" => $user]);
		}
		// error : no user with this id
		throw new NotFoundException($request, $response);
	}


	// GET /user/settings user settings page
	public function settings(Request $request, Response $response)
	{
		// ge the user profile
		$user_profile = $this->auth->user()->profile;
		return $this->view($response, "user/settings.twig", ["profile" => $user_profile]);
	}

	// [GET, POST] /user/edit-profile edit user profile
	public function editProfile(Request $request, Response $response, $args)
	{

		// check permissions , if the auth user id is equal
		// to $args["id"] meaning

		// @todo refactor into one middleware
		$user_id = $args["id"];
		if ($user_id != $this->auth->user()->id) {
			throw new MethodNotAllowedException($request, $response, []);
		}

		if ($request->isGet()) {
			return $this->view($response, "user/edit_profile.twig");
		}
		if ($request->isPost()) {
			// validating required fields
			$validator = $this->validator->validate($request, [
				"first_name" => V::notEmpty(),
				"last_name" => V::notEmpty(),
				"phone_number" => [
					"rules" => V::optional(V::digit()->length(10, 10, true)),
					"message" => "Not a valid phone number"
				]
			]);
		}
		if ($validator->isValid()) {

			// update the user profile if not found , or create new one
			Profile::updateProfile($user_id, $request);

			$this->flash("success", "Your profile has been updated");
			return $this->redirect($response, "user.profile", ["id" => $user_id]);
		}

		// @todo set validation error into flash
		return $this->redirect($response, "user.profile" , ["id" => $user_id]);
	}


	// POST /user/change-password
	public function changePassword($request, $response)
	{
		$validator = $this->validator->validate($request, [
			"old_password" => V::notEmpty(),
			"new_password" => V::notEmpty(),
			"new_password_conf" => V::notEmpty()->equals($request->getParam("new_password"))
		]);
		if ($validator->isValid()) {
			// check if the current password is correct
			$user = $this->auth->user();
			$new_password_hash = $this->hash->password($request->getParam("new_password"));
			if ($new_password_hash === $user->password) {
				$user->updatePassword($new_password_hash);
				$this->flash("success", "You password has been changed");
				return $this->redirect($response, "user.settings");
			}
			$this->flash("error", "Incorrect Password");
			return $this->redirect($response, "user.settings");
		}

		// @todo set validation error into flash
		return $this->redirect($response, "user.settings");
	}

	// POST /user/change-picture
	public function changeProfilePicture($request, $response)
	{
		$uploaded_img = $request->getUploadedFiles()["image"];
		// @todo - implement image size image size validation
		$imageValidation = $this->validator->object($uploaded_img, [
			"file" => [
				"rules" => V::image(),
				"messages" => [
					"image" => "Uploaded file must be an image",
					"size" => "Image size must be greater then 5mb"
				]
			],
		]);

		if ($imageValidation->isValid()) {
			if ($uploaded_img->getError() === UPLOAD_ERR_OK) {
				$this->userService->changeUserProfilePicture($uploaded_img, $this->auth->user());
				return $this->redirect($response, "user.settings");
			}
		}
		return $this->redirect($response, "user.settings");
	}

}