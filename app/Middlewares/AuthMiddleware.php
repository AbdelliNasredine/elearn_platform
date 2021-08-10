<?php


namespace App\Middlewares;


class AuthMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		if(!$this->auth()->check()) {
			return $this->redirect($response, "auth.login");
		}

		$response = $next($request, $response);
		return $response;
	}
}