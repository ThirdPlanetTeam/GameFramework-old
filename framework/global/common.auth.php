<?php

define("ACTION_LOGIN", "login");

class GFCommonAuth {

	const Anyone = 0;
	const Registered = 1;
	const Admin = 2;


	public static function checkAcl($acl) {
		if($acl == GFCommonAuth::Anyone) {
			return;
			// No Auth needed
		}

		if($acl >= GFCommonAuth::Registered) {
			if(!isset($_SESSION['token'])) {
				$e = new GFExceptionMinor("Login needed", 1);
				$e->redirectAction = ACTION_LOGIN;
				throw $e;
			}
		}

		if($acl == GFCommonAuth::Admin && $_SESSION['token']['admin'] == false) {
			throw new GFExceptionMajor("Unauthorised query", 1);		
		}
	}
}