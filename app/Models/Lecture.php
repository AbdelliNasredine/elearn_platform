<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
	protected $table = "lectures";
	protected $fillable = ["name", "content" ,"chapter_id", "lecture_format_id"];

	public function chapter()
	{
		return $this->belongsTo(Chapter::class, "chapter_id");
	}

	public function format()
	{
		return $this->belongsTo(LectureFormat::class, "lecture_format_id");
	}

}