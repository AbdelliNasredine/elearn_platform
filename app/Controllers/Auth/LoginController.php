<?php


namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Lib\Session;
use App\Models\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as V;


class LoginController extends BaseController
{
	public function get(Request $request, Response $response)
	{
		$this->view($response, "security/login.html.twig");
	}

	public function post(Request $request, Response $response)
	{
		// @todo implement validation of user data

		// data validation
		// variables filtering
		// $username = filter_var($request->getParam("username"), FILTER_SANITIZE_STRING);
		// $password = filter_var($request->getParam("password"), FILTER_SANITIZE_STRING);

		$validator = $this->validator->validate($request, [
			"username" => V::notBlank()->noWhitespace(),
			"password" => V::notBlank()->min(8)
		]);

		// checking if user with this username is found
		$user = User::findByUsername($username);

		// error: user not found
		if (!$user || !$this->hash->verifyPassword($password, $user->password)) {
			// @todo implement i18n for flash messages
			$this->flash("error", "User not found");
			return $this->redirect($response, "auth.login");
		}

		// successes: user found with these credentials
		if ($this->hash->verifyPassword($password, $user->password)) {

			// update last login field
			$user->updateLastLogin();

			// @todo implement i18n for flash messages
			Session::set("auth_id", $user->id);
			return $this->redirect($response, 'home');
		}

		$this->flash("error", "Some error happened");
		return $this->redirect($response, "auth.login");
	}
}