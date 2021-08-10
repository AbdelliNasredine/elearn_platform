<?php


namespace App\Auth;


use App\Lib\Session;
use App\Models\User;

class Auth
{
	public function check(){
		return Session::exists("auth_id");
	}

	public function user(){
		// should return the current authenticated user
		return $this->check() ? User::find(Session::get("auth_id")) : null;
	}
}