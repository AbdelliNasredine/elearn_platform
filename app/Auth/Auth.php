<?php


namespace App\Auth;


use App\Lib\Session;
use App\Models\User;

class Auth
{
	const AUTH_ID_KEY = "auth_id";

	public function check(){
		return Session::exists(self::AUTH_ID_KEY);
	}

	public function user(){
		// should return the current authenticated user
		return $this->check() ? User::find(Session::get(self::AUTH_ID_KEY)) : null;
	}
}