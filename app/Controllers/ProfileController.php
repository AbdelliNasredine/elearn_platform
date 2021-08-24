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
use Slim\Http\UploadedFile;

class ProfileController extends BaseController
{

	private $userService;

	public function __construct(ContainerInterface $container)
	{
		parent::__construct($container);
		$this->userService = $container->get(UserService::class);
	}

	// GET /user[/] index page
	public function index($request, $response)
	{
		// @todo make a redirection to user connected user profile page
		return $this->redirect($response, "user.profile");
	}

	// GET /user/{user_id} user profile page
	public function profile($request, $response)
	{
		// @todo implement user profile page
	}

	// GET /user/settings user settings page
	public function settings($request, $response)
	{
		// ge the user profile
		$user_profile = $this->auth->user()->profile;
		return $this->view($response, "user/settings/index.twig", ["profile" => $user_profile]);
	}

	// POST /user/edit-profile edit user profile
	public function editProfile($request, $response)
	{
		// validating required fields
		$validator = $this->validator->validate($request, [
			"first_name" => V::notEmpty(),
			"last_name" => V::notEmpty(),
			"email" => V::email()->notEmpty(),
			"birth_date" => V::optional(V::date()),
			"phone_number" => [
				"rules" => V::optional(V::digit()->length(10, 10, true)),
				"message" => "Not a valid phone number"
			]
		]);

		var_dump($request->getParam("birth_date"));

		if ($validator->isValid()) {

			// update the user profile if not found , or create new one
			$user_id = $this->auth->user()->id;
			Profile::updateProfileOrCreate($user_id, $request);

			$this->flash("success", "Your profile has successfully been updated");
			return $this->redirect($response, "user.settings");
		}

		// @todo set validation error into flash
		return $this->redirect($response, "user.settings");
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