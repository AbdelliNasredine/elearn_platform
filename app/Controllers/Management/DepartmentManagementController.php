<?php

namespace App\Controllers\Management;

use App\Controllers\BaseController;
use App\Models\Department;
use App\Models\Faculty;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class DepartmentManagementController extends BaseController
{

	// POST
	public function store(Request $request, Response $response, $args)
	{
		$facultyId = $args["id"];
		$departmentName = $request->getParam("name");

		if (!$departmentName) {
			$this->flash("error", "'Department name' is required");
			return $this->redirect($response, "admin.faculty", ["id" => $facultyId]);
		}

		$department = Department::where("name", $departmentName)->get();

		if($department->isNotEmpty()) {
			$this->flash("error", "'Department name' must be unique");
			return $this->redirect($response, "admin.faculty", ["id" => $facultyId]);
		}

		Department::create([
			"name" => $departmentName,
			"faculty_id" => $facultyId
		]);

		$this->flash("success", "Department {$departmentName} has been created");
		return $this->redirect($response, "admin.faculty", ["id" => $facultyId]);
	}
}