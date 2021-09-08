<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
	protected $table = "departments";
	public $timestamps = false;
	protected $fillable = ["name", "cover_image", "about", "faculty_id"];

	public function faculty()
	{
		return $this->belongsTo(Faculty::class, "faculty_id");
	}

	public function courses()
	{
		return $this->hasMany(Course::class);
	}

}