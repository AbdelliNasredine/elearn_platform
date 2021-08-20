<?php

namespace App\Services;

use Psr\Http\Message\UploadedFileInterface;

interface StorageServiceInterface
{
	public function moveFile($directory, UploadedFileInterface $file);
	public function exists($filename);
	public function mkdir();
}