<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
	protected $table = "profile";
	protected $fillable = [
		"first_name",
		"last_name",
		"birth_date",
		"phone_number",
		"email",
		"img_url",
		"user_id",
	];
	public $timestamps = false;

	public function user() {
		return $this->belongsTo(User::class);
	}

}