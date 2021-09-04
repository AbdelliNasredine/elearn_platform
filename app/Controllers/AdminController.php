<?php


namespace App\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

class AdminController extends BaseController
{
	public function index($request, $response)
	{
		$coursesCount = Course::count();
		$usersCount = User::count();
		$facultiesCount = Faculty::count();
		return $this->view($response, "admin/index.twig", [
			"courseCount" => $coursesCount,
			"usersCount" => $usersCount,
			"facultiesCount" => $facultiesCount
		]);
	}

	public function facultiesPage(Request $request, Response $response)
	{
		$faculties = Faculty::with("departments")->get();
		return $this->view($response, "admin/faculties/index.twig", ["faculties" => $faculties]);
	}
}