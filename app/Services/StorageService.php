<?php

namespace App\Services;

use App\Models\User;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Exception\NotFoundException;
use Slim\Exception\SlimException;

class StorageService implements StorageServiceInterface
{

	private $storage_dir;

	public function __construct(ContainerInterface $container)
	{
		$this->storage_dir = $container->get("storage_dir");
	}

	public function getExtension($filename)
	{
		return pathinfo($filename, PATHINFO_EXTENSION);
	}

	public function moveFile(UploadedFileInterface $file)
	{
		$extension = $this->getExtension($file->getClientFilename());
		$basename = bin2hex(random_bytes(8));
		$filename = sprintf('%s.%0.8s', $basename, $extension);
		$directory = $this->storage_dir . DIRECTORY_SEPARATOR . $extension;

		// check if extension directory exists, create it otherwise
		if (!is_dir($directory)) {
			$result = mkdir($directory);
			if (!$result) throw new \Exception("Cannot create dir");
		};

		// move the file into storage
		$file->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

		return $filename;
	}

	public function exists($filePath)
	{
		return file_exists($filePath);
	}

	public function getFileContent($file_name)
	{
		$extension = $this->getExtension($file_name);
		$path = $this->storage_dir . DIRECTORY_SEPARATOR . $extension;
		if (!$this->exists($path)) return null;
		return file_get_contents($path);
	}

	public function getPath($file_name)
	{
		return $this->storage_dir . DIRECTORY_SEPARATOR . $this->getExtension($file_name) . DIRECTORY_SEPARATOR . $file_name;
	}


}