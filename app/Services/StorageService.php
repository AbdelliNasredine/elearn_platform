<?php

namespace App\Services;

use App\Models\User;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UploadedFileInterface;

class StorageService extends Service implements StorageServiceInterface
{

	private $storage_dir;

	public function __construct(ContainerInterface $container)
	{
		parent::__construct($container);
		$this->storage_dir = $container->get("storage_dir");
	}

	public function moveFile($directory, UploadedFileInterface $file)
	{
		$extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
		$basename = bin2hex(random_bytes(8));
		$filename = sprintf('%s.%0.8s', $basename, $extension);

		$file->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

		return $filename;
	}

	public function exists($filename)
	{
		// TODO: Implement exists() method.
	}

	public function mkdir()
	{
		// TODO: Implement mkdir() method.
	}

	public function getUserDirectory($username)
	{
		// check if user directory is found
		// if not , create one
		// else return dir path
		$user_dir = $this->storage_dir . DIRECTORY_SEPARATOR . $username;
		var_dump($user_dir);
		if (is_dir($user_dir)) {

			return $user_dir;
		}
		return !mkdir($user_dir) ? die("cannot create a directory") : $user_dir;
	}
}