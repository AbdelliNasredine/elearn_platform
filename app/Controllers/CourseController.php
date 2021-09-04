<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\Department;
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
}