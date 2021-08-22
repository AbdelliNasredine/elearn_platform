<?php

namespace App\Controllers\Management;

use App\Controllers\BaseController;
use App\Models\Role;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class RoleController extends BaseController
{
	public function show(Request $request, Response $response)
	{
		$roles = Role::all();
		return $this->view($response, "admin/roles/index.twig", ["roles" => $roles]);
	}

	public function add(Request $request, Response $response){
		if($request->isGet()) {
			return $this->view($response, "admin/roles/add.twig");
		}

		$validator = $this->validator->validate($request, [
			'name' => [
				"rules" => V::notEmpty()->length(3, null),
				"messages" => [
					"notEmpty" => "'Role Name' is required",
					"length" => "'Role Name' should be at least 3 characters long"
				]
			],
		]);

		if($validator->isValid()) {
			$role_name = $request->getParam("name");
			Role::create(["name" => $role_name]);
			$this->flash("success", "New Role has been added successfully!");
			return $this->redirect($response, "admin.roles");
		}
		return $this->view($response,"admin/roles/add.twig");
	}
}