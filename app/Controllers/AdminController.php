<?php


namespace App\Controllers;


class AdminController extends BaseController
{
	public function index($request, $response) {
		return $this->view($response, "admin/index.twig");
	}
}