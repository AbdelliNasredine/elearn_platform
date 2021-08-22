<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	const STUDENT = "STUDENT";
	const ADMIN = "ADMIN";
	const TEACHER = "TEACHER";

	protected $table = "role";
	public $timestamps = false;
	protected $fillable = ["name"];

	public function users()
	{
		return $this->hasMany(User::class);
	}

	private static function getRole($role = self::STUDENT)
	{
		return static::where("name", $role)->first();
	}

	public static function student()
	{
		return self::getRole(self::STUDENT)->id;
	}

	public static function admin()
	{
		return self::getRole(self::ADMIN)->id;
	}

}