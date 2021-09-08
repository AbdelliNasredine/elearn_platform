<?php

namespace App\Controllers;

use App\Models\AcademicLevel;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\LectureFormat;
use Respect\Validation\Validator as V;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class TeacherController extends BaseController
{

	public function getCreatePage(Request $request, Response $response)
	{
		$faculties = Faculty::all();
		$academicLevels = AcademicLevel::all();

		return $this->view($response, "teacher/add-course.twig", [
			"faculties" => $faculties,
			"academicLevels" => $academicLevels,
		]);

	}

	public function getMyCoursesPage(Request $request, Response $response)
	{
		$user = $this->auth->user();
		$courses = $user->courses;
		return $this->view($response, "teacher/my-courses.twig", ["courses" => $courses]);
	}

	public function getEditCoursePage(Request $request, Response $response, $args)
	{
		$id = $args["id"];
		$course = Course::find($id);
		$lectureFormats = LectureFormat::all();
		if (!$course) throw new NotFoundException($request, $response);
		return $this->view($response, "teacher/edit-course.twig", ["course" => $course, "lectureFormats" => $lectureFormats]);
	}


	public function storeCourse(Request $request, Response $response)
	{
		$validator = $this->validator->validate($request, [
			"title" => V::notEmpty(),
			"description" => V::optional(V::notEmpty()),
			"level" => V::intVal(),
			"department" => V::intVal(),
		]);

		if ($validator->isValid()) {
			$userId = $this->auth->user()->id;
			Course::create([
				"name" => $request->getParam("title"),
				"description" => $request->getParam("description"),
				"is_active" => false,
				"academic_level_id" => $request->getParam("level"),
				"departement_id" => $request->getParam("department"),
				"user_id" => $userId,
			]);
			$this->flash("success", "Your course has been created");
			return $this->redirect($response, "create-course");
		}

		return $this->redirect($response, "create-course");
	}

}