<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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


	/**
	 * Creating new user
	 * @param $username
	 * @param $password_hash
	 */
	public static function createNewUser($username, $password_hash)
	{
		self::create([
			"username" => $username,
			"password" => $password_hash,
			"status" => self::ACTIVE,
			"role_id" => Role::student()
		]);
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

	private function isRole($role){
		return $this->role->name == $role;
	}

	public function isAdmin(){
		return $this->isRole(Role::ADMIN);
	}

	public function isStudent(){
		return $this->isRole(Role::STUDENT);
	}

	public function isTeacher(){
		return $this->isRole(Role::TEACHER);
	}

	public function updateLastLogin() {
		$this->last_login = Carbon::now();
		$this->save();
	}

}
