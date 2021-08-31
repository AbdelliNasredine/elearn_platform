<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LectureFormat extends Model
{
	protected $table = "lecture_formats";
	public $timestamps = false;
	protected $fillable = ["format"];

}