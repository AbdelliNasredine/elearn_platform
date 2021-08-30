<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\StorageService;
use Psr\Container\ContainerInterface;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Stream;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

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
		$type = $request->getQueryParam("type");
		$allowedExtensions = array("doc", "docx", "word", "pdf", "png", "jpg", "jpeg", "mp4");
		$maxFileSize = 1024 * 1024 * 100; // 20 MB

		if ($file->getError() !== UPLOAD_ERR_OK) {
			return $response->withStatus(500)
				->withHeader("Content-Type", "application/json")
				->withJson(json_encode(array("msg" => "Some error happened")));
		}

		// check file extension
		$extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
		if (!in_array($extension, $allowedExtensions)) {
			return $response->withStatus(400)
				->withHeader("Content-Type", "application/json")
				->withJson(json_encode(array("msg" => "Unsupported file extension")));
		}

		// check file size
		if ($file->getSize() > $maxFileSize) {
			return $response->withStatus(400)
				->withHeader("Content-Type", "application/json")
				->withJson(json_encode(array("msg" => "File size exceed maximum size allowed (20 MB)")));
		}

		$fileName = $this->storageService->moveFile($file);

		if ($type == "picture") {
			// crop the image to be square
			Image::load($this->storageService->getPath($fileName))
				->fit(Manipulations::FIT_CROP, 128, 128)
				->save();

		}

		return $response->withHeader("Content-Type", "application/json")
			->withJson(json_encode(array("msg" => "File uploaded successfully", "filename" => $fileName)));
	}

	public function getFile($request, $response, $args)
	{
		$file_name = $args["name"];

		$path = $this->storageService->getPath($file_name);

		if (!$this->storageService->exists($path)) throw new NotFoundException($request, $response);

		$fs = new Stream(fopen($path, "rb"));

		return $response->withBody($fs)
			->withHeader('Content-Type', mime_content_type($path))
			->withHeader('Content-Length', filesize($path));
	}
}