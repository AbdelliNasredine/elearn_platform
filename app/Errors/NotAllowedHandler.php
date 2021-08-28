<?php

namespace App\Errors;


use Slim\Http\Request;
use Slim\Http\Response;

class NotAllowedHandler extends ErrorHandler
{
	public function __invoke(Request $request, Response $response, $methods)
	{
		return $this->render($response, "errors/405.twig", 405);
	}
}