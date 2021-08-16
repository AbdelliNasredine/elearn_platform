<?php


namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

class RegisterController extends BaseController
{
	public function get(Request $request, Response $response)
	{
		$this->view($response, "security/register.html.twig");
	}

	public function post(Request $request, Response $response)
	{
		// data filtering
		$username = filter_var($request->getParam("username"), FILTER_SANITIZE_STRING);
		$password = filter_var($request->getParam("password"), FILTER_SANITIZE_STRING);

		// @todo implement validation

		// check if the username is not taken by another user
		$user = User::findByUsername($username);

		if ($user) {
			// @todo implement i18n for flash messages

			$this->flash("error", "Username must be unique");
			return $this->redirect($response, "auth.register");
		}

		// hashing the password
		$password_hash = $this->hash->password($password);

		// saving the user intro db
		User::createNewUser($username, $password_hash);

		// redirection to login page with success message
		$this->flash("success", "Your account has been created");
		return $this->redirect($response, "auth.login");


	}
}