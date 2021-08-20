<?php

namespace App\Services;

use App\Models\User;
use Psr\Container\ContainerInterface;
use Slim\Http\UploadedFile;

class UserService extends Service
{

	private $storageService;

	public function __construct(ContainerInterface $container)
	{
		parent::__construct($container);
		$this->storageService = $container->get(StorageService::class);
	}

	public function changeUserProfilePicture(UploadedFile $image, User $user)
	{
		// if user has not a profile picture
		$user_profile = $user->profile;
		if(!$user_profile->picture) {
			$user_directory = $this->storageService->getUserDirectory($user->username);
			$user_profile->picture = $this->storageService->moveFile($user_directory, $image);
			$user_profile->save();
		}
		// @todo if the user already has a profile picture
	}
}