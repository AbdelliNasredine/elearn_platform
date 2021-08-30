<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
	protected $table = "departements";
	public $timestamps = false;
	protected $fillable = ["name", "cover_image", "about"];

	public function faculty(){
		return $this->belongsTo(Faculty::class, "faculty_id");
	}

}