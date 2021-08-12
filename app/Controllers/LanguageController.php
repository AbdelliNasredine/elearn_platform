<?php


namespace App\Controllers;


use App\Lib\Session;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class LanguageController extends BaseController
{
	public function __invoke(Request $request, Response $response, $args)
	{

		// check if there is a redirect url
		$url = $request->getQueryParam("r") ?? "/";

		// validate args
		if (isset($args["lang"]) && in_array($args["lang"], $this->container["lang"]["available"])) {
			// store the language in the session
			Session::set("lang", $args["lang"]);
		}

		return $response->withRedirect($url);
	}
}