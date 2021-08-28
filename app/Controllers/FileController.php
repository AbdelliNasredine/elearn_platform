<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\StorageService;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Stream;

class FileController extends BaseController
{

	private $storageService;

	public function __construct(ContainerInterface $container)
	{
		parent::__construct($container);
		$this->storageService = $container->get(StorageService::class);
	}

	public function upload(Request $request, Response $response)
	{
		$file = $request->getUploadedFiles()["file"];
		$allowedExtensions = array("doc","docx","word","pdf","png","jpg","jpeg","mp4");
		$maxFileSize = 1024 * 1024 * 100; // 20 MB

		if($file->getError() !== UPLOAD_ERR_OK) {
			return $response->withStatus(500)
						->withHeader("Content-Type", "application/json")
						->withJson(json_encode(array("msg" => "Some error happened")));
		}

		// check file extension
		$extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
		if(!in_array($extension, $allowedExtensions)) {
			return $response->withStatus(400)
				->withHeader("Content-Type", "application/json")
				->withJson(json_encode(array("msg" => "Unsupported file extension")));
		}

		// check file size
		if($file->getSize() > $maxFileSize) {
			return $response->withStatus(400)
				->withHeader("Content-Type", "application/json")
				->withJson(json_encode(array("msg" => "File size exceed maximum size allowed (20 MB)")));
		}

		$fileName = $this->storageService->moveFile($file);
			return $response->withHeader("Content-Type", "application/json")
						->withJson(json_encode(array("msg" => "File uploaded successfully" , "filename" => $fileName)));
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