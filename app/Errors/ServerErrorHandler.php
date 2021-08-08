<?php


namespace App\Errors;


class ServerErrorHandler extends ErrorHandler
{
    public function __invoke($request, $response, $exception) {
        return $this->render($response, "Server Error", 500);
    }
}