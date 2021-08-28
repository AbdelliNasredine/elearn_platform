<?php

namespace App\Controllers\Management;

use App\Models\Document;
use Slim\Http\Request;
use Slim\Http\Response;

class DocumentController extends \App\Controllers\BaseController
{

	public function index(Request $request, Response $response) {
		$documents = Document::all();
		return $this->view($response, "admin/documents/index.twig");
	}

}