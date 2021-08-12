<?php


namespace App\Middlewares;


use Psr\Container\ContainerInterface;

class LanguageMiddleware extends Middleware
{

	public function __construct(ContainerInterface $container)
	{
		parent::__construct($container);

		$this->container['language'] = $container["lang"]["default"];
	}

	public function __invoke($request, $response, $next)
	{
		$uri = $request->getUri();
		$path = $uri->getPath();

		//Split path into chunks
		$pathChunks = explode("/", $path);

		//Check for language references
		if (count($pathChunks) > 1 && in_array($pathChunks[1], $this->container["lang"]["available"])) {

			//Set current language
			$this->container['language'] = $pathChunks[1];

			// Produce new URI without language reference
			unset($pathChunks[1]);
			$newPath = implode('/', $pathChunks);
			$newUri = $uri->withPath($newPath);

			return $next($request->withUri($newUri), $response);
		}
		return $next($request, $response);
	}
}