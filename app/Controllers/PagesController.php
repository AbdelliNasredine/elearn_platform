<?php

namespace App\Controllers;

use App\Models\Faculty;
use App\Models\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class PagesController extends BaseController
{
	public function index(Request $request, Response $response)
	{
		if ($this->auth->user()->isAdmin())
			return $this->redirect($response, "admin.index");
		return $this->view($response, "home.twig");
	}

	public function explorePage(Request $request, Response $response)
	{
		$faculties = Faculty::all();
		return $this->view($response, "explore.twig", ["faculties" => $faculties]);
	}
}
