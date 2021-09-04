<?php

namespace App\Controllers\Management;

use App\Controllers\BaseController;
use App\Models\Course;
use Slim\Http\Request;
use Slim\Http\Response;

class CourseManagementController extends BaseController
{
	public function index(Request $request, Response $response)
	{
		$courses = Course::all();
		return $this->view($response, "admin/courses/index.twig", ["courses" => $courses]);
	}
}