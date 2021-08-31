<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
	protected $table = "courses";
	protected $fillable = [
		"name",
		"description",
		"department_id",
		"academic_level_id",
		"user_id",
		"cover_image",
		"is_active"
	];


	public function department()
	{
		return $this->belongsTo(Department::class, "department_id");
	}

	public function user()
	{
		return $this->belongsTo(User::class, "user_id");
	}

}