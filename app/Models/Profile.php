<?php

namespace App\Models;

use App\Auth\Auth;
use App\Lib\Session;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
	protected $table = "profile";
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

	public static function updateProfileOrCreate($user_id, $request)
	{
		$birthDate = $request->getParam("birth_date");
		$birthDate = empty($birthDate) ? null : $birthDate;

		self::updateOrCreate(
			["user_id" => $user_id],
			[
				"first_name" => $request->getParam("first_name"),
				"last_name" => $request->getParam("last_name"),
				"email" => $request->getParam("email"),
				"birth_date"=> $birthDate,
				"phone_number"=> $request->getParam("phone_number"),
			]
		);
	}

}