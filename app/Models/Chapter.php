<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
	protected $table = "chapters";
	public $timestamps = false;
	protected $fillable = ["name", "course_id"];

	public function course()
	{
		return $this->belongsTo(Course::class, "course_id");
	}

	public function lectures()
	{
		return $this->hasMany(Lecture::class);
	}

}