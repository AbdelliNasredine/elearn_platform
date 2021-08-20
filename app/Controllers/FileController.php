<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\StorageService;
use Psr\Container\ContainerInterface;
use Slim\Http\Stream;

class FileController extends BaseController
{

	private $storageService;

	public function __construct(ContainerInterface $container)
	{
		parent::__construct($container);
		$this->storageService = $container->get(StorageService::class);
	}

	public function getImage($request, $response, $args)
	{
		// get the image filename
		$image_name = $args["name"];
		$username = $args["username"];

		// check if the name is valid

		// get user directory
		$user_dir = $this->storageService->getUserDirectory($username);
		$path = $user_dir . DIRECTORY_SEPARATOR . $image_name;
		$file_content = file_get_contents($path);
		$fs = new Stream(fopen($path, "rb"));

		return $response->withBody($fs)
			->withHeader('Content-Type', mime_content_type($path))
			->withHeader('Content-Length', filesize($path));
	}
}