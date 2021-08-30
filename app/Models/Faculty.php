<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
	protected $table = "faculties";
	public $timestamps = false;
	protected $fillable = ['name'];

	public function departments(){
		return $this->hasMany(Department::class);
	}

}