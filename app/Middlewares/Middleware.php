<?php


namespace App\Middlewares;

use Psr\Container\ContainerInterface;
use Slim\Exception\MethodNotAllowedException;
use Slim\Exception\NotFoundException;

class Middleware
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function auth()
	{
		return $this->container->auth;
	}

	public function user()
	{
		return $this->auth()->user();
	}

	public function flash($type, $message)
	{
		$this->container->flash->addMessage($type, $message);
	}

	public function config($key)
	{
		return $this->container->config->get($key);
	}

	public function lang($key)
	{
		return $this->config("lang." . $key);
	}

	public function redirect($response, $path)
	{
		return $response->withRedirect($this->router()->pathFor($path));
	}

	public function notFound($request, $response)
	{
		throw new NotFoundException($request, $response);
	}

	public function notAllowed($request, $response)
	{
		throw new MethodNotAllowedException($request, $response, []);
	}

	protected function router()
	{
		return $this->container['router'];
	}

	protected function isSecure()
	{
		if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
			return true;
		}

		return false;
	}

}