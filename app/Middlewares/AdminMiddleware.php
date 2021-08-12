<?php


namespace App\Middlewares;


class AdminMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		if($this->auth()->check()) {
			if($this->user()->isAdmin()) {
				$response = $next($request, $response);
				return $response;
			}
		}

		return $this->notFound($request, $response);
	}
}