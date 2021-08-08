<?php


namespace App\Controllers;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function getSignIn(Request $request, Response $response){
        $this->view($response, "auth/sign-in.twig");
    }

    public function postSignIn(Request $request, Response $response) {
        die(var_dump($request->getParsedBody()));
    }
}