<?php

namespace App\Controllers;

class UserController extends BaseController
{
	// GET /settings
	public function settings($request, $response)
	{
		return $this->view($response, "user/settings.twig");
	}

	// POST /settings/update-profile
	public function updateProfile($request, $response){

		// @todo validate the input
		// @todo update

		die(var_dump($request->getParsedBody()));
	}
}