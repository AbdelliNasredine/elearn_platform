<?php

namespace App\Controllers\Management;

use App\Controllers\BaseController;
use App\Models\Faculty;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class FacultyManagementController extends BaseController
{
	// GET
	public function index(Request $request, Response $response)
	{
		return $this->view($response, "admin/faculties/add.twig");
	}

	// POST
	public function store(Request $request, Response $response)
	{
		$validator = $this->validator->validate($request, [
			"name" => V::notEmpty(),
		]);

		if ($validator->isValid()) {
			$facultyName = $request->getParam("name");
			// check for uniqueness
			$faculty = Faculty::where("name", $facultyName)->get();
			if (!$faculty) {
				// save and redirect
				Faculty::create(["name" => $facultyName]);
				$this->flash("success", "Faculty {$facultyName} has been created");
				return $this->redirect($response, "admin.faculties");
			}

			$this->flash("error", "'Faculty name' must be unique");
			return $this->redirect($response, "admin.faculties");
		}

		$this->flash("error", "'Name' is required");
		return $this->redirect($response, "admin.faculties");
	}
}