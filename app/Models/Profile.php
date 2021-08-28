<?php

namespace App\Models;

use App\Auth\Auth;
use App\Lib\Session;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
	protected $table = "profiles";
	protected $fillable = [
		"first_name",
		"last_name",
		"birth_date",
		"phone_number",
		"email",
		"picture",
		"user_id",
	];
	public $timestamps = false;

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public static function updateProfile($user_id, $request)
	{

		self::updateOrCreate(
			["user_id" => $user_id],
			[
				"first_name" => $request->getParam("first_name"),
				"last_name" => $request->getParam("last_name"),
				"phone_number"=> $request->getParam("phone_number"),
			]
		);
	}

}