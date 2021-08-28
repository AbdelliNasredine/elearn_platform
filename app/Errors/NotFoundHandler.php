<?php


namespace App\Errors;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class NotFoundHandler extends ErrorHandler
{
    public function __invoke(Request $request, Response $response): Response
    {
        return $this->render($response, "errors/404.twig", 404);
    }
}