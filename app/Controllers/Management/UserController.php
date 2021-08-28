<?php

namespace App\Controllers\Management;

use App\Controllers\BaseController;
use App\Models\Role;
use App\Models\User;
use Respect\Validation\Validator as V;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class UserController extends BaseController
{
	// index
	public function all(Request $request, Response $response)
	{
		$auth_user_id = $this->auth->user()->id;
		$users = User::where("id", "<>", $auth_user_id)->get();
		return $this->view($response, "admin/users/index.twig", ["users" => $users]);
	}

	// [POST, GET] => /admin/users/add
	public function add(Request $request, Response $response)
	{
		if ($request->isGet()) {
			$roles = Role::all();
			return $this->view($response, "admin/users/add.twig", ["roles" => $roles]);
		}
		$validator = $this->validator->validate($request, [
			"username" => V::notEmpty()->length(5),
			"password" => V::notEmpty()->length(8),
			"role" => V::notEmpty()
		]);
		if ($validator->isValid()) {
			$username = $request->getParam("username");
			$password = $request->getParam("password");
			$role_id = $request->getParam("role");
			// check if the username is unique
			if (User::findByUsername($username) == null) {
				$password = $this->hash->password($password);
				$user = User::createNewUser($username, $password, $role_id);
				$this->flash("success", "User has been created successfully");
				return $this->redirect($response, "admin.users");
			}
			// error : username is used
			$this->flash("error", "Username is not unique");
			return $this->redirect($response, "admin.addUser");
		}
		return $this->view($response, "admin/users/add.twig");
	}

	// [POST, GET] => /admin/users/{id}
	public function user(Request $request, Response $response, array $args)
	{
		$user_id = $args["id"];
		$idValidator = $this->validator->value($user_id, V::notEmpty()->numeric(), "user_id");
		if ($idValidator->isValid()) {
			$user = User::find($user_id);
			if (!$user) throw new NotFoundException($request, $response);
			if ($request->isGet()) {
				return $this->view($response, "admin/users/profile.twig", ["user" => $user]);
			}
			if ($request->isPost()) {
				$validator = $this->validator->validate($request, [
					"total_points" => V::numeric(),
					"first_name" => V::optional(V::alpha()->notEmpty()),
					"last_name" => V::optional(V::alpha()->notEmpty()),
					"email" => V::optional(V::email()),
					"birth_date" => V::optional(V::date()),
					"phone_number" => V::optional(V::digit()->length(10, 10, true)),
				]);
				if ($validator->isValid()) {
					$user->updateInformation($request);
					$this->flash("success", "User with id $user_id has updated");
					return $this->redirect($response, "admin.user", ["id" => $user_id]);
				}
				// error : validation
			}
		}
		// error : no user with this id
		throw new NotFoundException($request, $response);
	}

	// [POST, GET] => /admin/users/{id}/edit
	public function edit(Request $request, Response $response, $args)
	{
		if($request->isGet()) {
			return $this->view($response, "admin/users/edit.twig");
		}
	}

	// POST /admin/users/{id}/delete
	public function delete(Request $request, Response $response, $args)
	{
		$user_id = $args["id"];
		// check if user found
		if (User::find($user_id)) {
			// @todo delete if there is some images in the storage
			User::destroy($user_id);
			$this->flash("success", "User with id $user_id has been deleted");
			return $this->redirect($response, "admin.users");
		}
		throw new NotFoundException($request, $response);
	}
}