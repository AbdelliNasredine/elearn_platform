<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{

	const BLOCKED = "BLOCKED";
	const ACTIVE = "ACTIVE";

	protected $table = "user";
	protected $hidden = ["password", "role_id"];
	protected $fillable = ["username", "password", "status", "role_id"];


	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function profile()
	{
		return $this->hasOne(Profile::class);
	}

	/**
	 * Creating new user
	 * @param $username
	 * @param $password_hash
	 */
	public static function createNewUser($username, $password_hash, $role_id = null)
	{
		$role_id = $role_id == null ? Role::student() : $role_id;
		$user = self::create([
			"username" => $username,
			"password" => $password_hash,
			"status" => self::ACTIVE,
			"role_id" => $role_id
		]);

		// init an empty profile
		Profile::create(["user_id" => $user->id]);
	}

	/**
	 * Fetching user by their username
	 * @param $username
	 * @return mixed
	 */
	public static function findByUsername($username)
	{
		return self::where("username", $username)->first();
	}

	public function updateInformation($request)
	{
		$this->total_points = $request->getParam("total_points");
		$this->save();
		$this->profile->update([
			"first_name" => $request->getParam("first_name"),
			"last_name" => $request->getParam("last_name"),
			"email" => $request->getParam("email"),
			"birth_date" => $request->getParam("birth_date"),
			"phone_number" => $request->getParam("phone_number"),
		]);
	}

	private function isRole($role)
	{
		return $this->role->name == $role;
	}

	public function isAdmin()
	{
		return $this->isRole(Role::ADMIN);
	}

	public function isStudent()
	{
		return $this->isRole(Role::STUDENT);
	}

	public function isTeacher()
	{
		return $this->isRole(Role::TEACHER);
	}

	public function updateLastLogin()
	{
		$this->last_login = Carbon::now();
		$this->save();
	}

	public function updatePassword($hash_password)
	{
		$this->password = $hash_password;
		$this->save();
	}

}
