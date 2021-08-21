<?php

namespace App\Controllers\Management;

use App\Controllers\BaseController;
use App\Models\Role;
use Slim\Http\Request;
use Slim\Http\Response;

class RoleController extends BaseController
{
	public function show(Request $request, Response $response)
	{
		$roles = Role::all();
		return $this->view($response, "admin/roles/index.twig", ["roles" => $roles]);
	}
}