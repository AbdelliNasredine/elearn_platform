<?php

namespace App\Controllers;

use App\Auth\Auth;
use App\Lib\Session;
use App\Models\Profile;
use App\Models\User;
use Respect\Validation\Validator as V;

class UserController extends BaseController
{

	// GET /user[/] index page
	public function index($request, $response) {
		// @todo make a redirection to user connected user profile page
		return $this->redirect($response, "user.profile");
	}

	// GET /user/{user_id} user profile page
	public function profile($request, $response) {
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
			"first_name" => V::stringType()->notEmpty(),
			"last_name" => V::stringType()->notEmpty(),
			"email" => V::email()->notEmpty(),
			"birth_date" => V::optional(V::date()),
			"phone_number" => [
				"rules" => V::optional(V::digit()->length(10, 10, true)),
				"message" => "Not a valid phone number"
			]
		]);

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
	public function changePassword($request, $response){
		// @todo implement handling change password POST request
	}

	// POST /user/change-picture
	public function changeProfilePicture($request, $response){
		// @todo implement changing and saving user profile picture
	}
}