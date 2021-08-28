<?php

namespace App\Controllers\Management;

use App\Controllers\BaseController;
use App\Models\Document;
use App\Models\DocumentType;
use Slim\Exception\MethodNotAllowedException;
use Slim\Http\Request;
use Slim\Http\Response;

class DocumentController extends BaseController
{

	public function index(Request $request, Response $response) {
		$documents = Document::all();
		return $this->view($response, "admin/documents/index.twig", ["documents" => $documents]);
	}

	public function new(Request $request, Response $response)
	{
		if($request->isGet()) {
			$documentTypes = DocumentType::all();
			return $this->view($response, "admin/documents/new.twig", ["documentTypes" => $documentTypes]);
		}
		if ($request->isPost()) {
			// validate
			// store
			// redirect
		}

		throw new MethodNotAllowedException($request, $response, []);
	}

}