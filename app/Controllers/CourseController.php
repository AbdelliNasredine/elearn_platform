<?php

namespace App\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Department;
use App\Models\Lecture;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class CourseController extends BaseController
{
	public function index(Request $request, Response $response, $args)
	{
		$courseId = $args["id"];
		$course = Course::find($courseId);
		if (!$course) throw new NotFoundException($request, $response);
		return $this->view($response, "course/index.twig", ["course" => $course]);
	}

	public function courseByDepartment(Request $request, Response $response, $args)
	{
		$departmentId = $args["department_id"];
		$department = Department::find($departmentId);
		if (!$department) throw new NotFoundException($request, $response);
		return $this->view($response, "course/course_by_department.twig", ["department" => $department]);
	}

	public function start(Request $request, Response $response, $args)
	{
		$courseId = $args["c_id"];
		$firstChapter = Chapter::where("course_id", $courseId)
			->orderBy("created_at")
			->first();
		$lecture = Lecture::where("chapter_id", $firstChapter->id)
			->orderBy("created_at")
			->first();
		return $this->redirect($response, "lectures", ["c_id" => $courseId, "l_id" => $lecture->id]);
	}

	public function lecture(Request $request, Response $response, $args)
	{
		$courseId = $args["c_id"];
		$lecture_id = $args["l_id"];

		$course = Course::find($courseId);
		$lecture = Lecture::find($lecture_id);

		return $this->view($response, "course/one.twig", ["course" => $course, "lecture" => $lecture]);
	}
}