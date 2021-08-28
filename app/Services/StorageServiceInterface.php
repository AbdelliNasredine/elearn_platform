<?php

namespace App\Services;

use Psr\Http\Message\UploadedFileInterface;

interface StorageServiceInterface
{
	/**
	 * Move the uploaded file into the storage directory based
	 * on file extension
	 * @param UploadedFileInterface $file
	 * @return string fileName
	 */
	public function moveFile(UploadedFileInterface $file);

	/**
	 * Checks if the giving file name exists in the storage
	 * directory based on file extension
	 * @param $filename
	 * @return bool result
	 */
	public function exists($filename);
}