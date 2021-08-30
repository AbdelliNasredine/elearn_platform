<?php


namespace App\Controllers;


use App\Models\Faculty;
use Slim\Http\Request;
use Slim\Http\Response;

class AdminController extends BaseController
{
	public function index($request, $response) {
		return $this->view($response, "admin/index.twig");
	}

	public function facultiesPage(Request $request, Response $response)
	{
		$faculties = Faculty::all();
		return $this->view($response, "admin/faculties/index.twig", ["faculties" => $faculties]);
	}
}