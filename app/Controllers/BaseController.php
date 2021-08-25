<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;

class BaseController
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __get($prop)
	{
		if ($this->container->{$prop}) {
			return $this->container->{$prop};
		}
	}

	public function view($response, $viewPath, $options = [])
	{
		return $this->view->render($response, $viewPath, $options);
	}

	public function redirect($response, $path, $options = [])
	{
		return $response->withRedirect($this->router->pathFor($path, $options));
	}

	public function flash($type, $message)
	{
		return $this->flash->addMessage($type, $message);
	}

	public function flashNow($type, $message)
	{
		return $this->flash->addMessageNow($type, $message);
	}

	public function validate($request, $options)
	{
		return $this->container->get("validator")->validate($request, $options);
	}

	public function getErrors($validator)
	{
		$totalErrors = [];
		foreach ($validator->getErrors() as $errorKey => $errorValue) {
			if (is_array($errorValue)) {
				$totalErrors[$errorKey] = array_values($errorValue)[0];
			}
		}
		return $totalErrors;
	}
}
