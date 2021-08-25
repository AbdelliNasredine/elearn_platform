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
		$validator = $this->validate($request, [
			"username" => [
				"rules" => V::notBlank()->noWhiteSpace(),
				"message" => "Username must not contain whitespaces"
			],
			"password" => [
				"rules" => V::notEmpty()->length(8),
				"message" => "Password must be at least 8 characters long"
			],
		]);

		if ($validator->isValid()) {

			$username = $request->getParam("username");
			$password = $request->getParam("password");

			// checking if user with this username is found
			$user = User::findByUsername($username);

			// error: user not found
			if (!$user || !$this->hash->verifyPassword($password, $user->password)) {
				// @todo implement i18n for flash messages
				$this->flash("error", "Wrong username or password");
				return $this->redirect($response, "auth.login");
			}

			// success: user found with these credentials
			if ($this->hash->verifyPassword($password, $user->password)) {

				// update last login field
				$user->updateLastLogin();

				// @todo implement i18n for flash messages
				Session::set("auth_id", $user->id);
				return $this->redirect($response, 'home');
			}
		}

		$this->flash("validation", $this->getErrors($validator));
		return $this->redirect($response, "auth.login");
	}
}