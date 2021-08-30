<?php

namespace App\Services;

use App\Models\Profile;
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

	public function changeUserProfilePicture($image_path,$user_id)
	{
		// @todo if the user already has a profile picture
		$user_profile = User::find($user_id)->profile;
		$user_profile->picture = $image_path;
		$user_profile->save();
	}
}