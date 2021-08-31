<?php

namespace App\Middlewares;

class TeacherMiddleware extends Middleware
{
	public function __invoke($request, $response, $next)
	{
		if($this->user()->isTeacher()) {
			$response = $next($request, $response);
			return $response;
		}
		return $this->notAllowed($request, $response);
	}
}