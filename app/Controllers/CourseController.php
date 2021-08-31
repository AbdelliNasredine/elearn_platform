<?php

namespace App\Controllers;

use App\Models\AcademicLevel;
use App\Models\Course;
use App\Models\Faculty;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class CourseController extends BaseController
{

	public function getCreatePage(Request $request, Response $response)
	{
		$faculties = Faculty::all();
		$academicLevels = AcademicLevel::all();

		return $this->view($response, "user/add-course.twig", [
			"faculties" => $faculties,
			"academicLevels" => $academicLevels,
		]);

	}

	public function storeCourse(Request $request, Response $response)
	{
		$validator = $this->validator->validate($request, [
			"title" => V::notEmpty(),
			"description" => V::optional(V::notEmpty()),
			"level" => V::intVal(),
			"department" => V::intVal(),
		]);

		if($validator->isValid()) {
			$userId = $this->auth->user()->id;
			Course::create([
				"name" => $request->getParam("title"),
				"description" => $request->getParam("description"),
				"is_active" => false,
				"academic_level_id" => $request->getParam("level"),
				"department_id" => $request->getParam("department"),
				"user_id" => $userId,
			]);
			$this->flash("success", "Your course has been created");
			return $this->redirect($response, "create-course");
		}

		return $this->redirect($response, "create-course");
	}

}