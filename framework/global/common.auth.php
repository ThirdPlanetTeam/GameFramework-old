<?php

define("MODULE_ACCOUNT", "account");
define("ACTION_LOGIN", "login");
define("ACTION_LOGOUT", "logout");

class GFCommonAuth {

	const Unregistered = -1; // Need to be unregistered
	const Anyone = 0;
	const Registered = 1;
	const Admin = 2;



	public static function checkAcl($acl, $safe = false) {
		if($acl == GFCommonAuth::Anyone) {
			return true;
			// No Auth needed
		}

		if($acl >= GFCommonAuth::Registered) {
			if(!isset($_SESSION['token'])) {
				if($safe) {
					return false;
				} else {
					$e = new GFExceptionMinor("Login needed", 1);
					$e->redirectModule = MODULE_ACCOUNT;
					$e->redirectAction = ACTION_LOGIN;
					throw $e;
				}
			}
		}

		if($acl == GFCommonAuth::Unregistered) {
			if(isset($_SESSION['token'])) {
				if($safe) {
					return false;
				} else {				
					$e = new GFExceptionMinor("Proceed to logout", 1);
					$e->redirectModule = MODULE_ACCOUNT;
					$e->redirectAction = ACTION_LOGOUT;
					throw $e;
				}
			}
		}		

		if($acl >= GFCommonAuth::Admin && $_SESSION['token']['admin'] == false) {
			if($safe) {
				return false;
			} else {			
				throw new GFExceptionMajor("Unauthorised query", 1);	
			}	
		}

		return true;
	}

	public static function registerUser($username, int $acl = null) {
		$_SESSION['token'] = [];

		$_SESSION['token']['username'] = $username;

		if(!is_null($acl) && $acl >= GFCommonAuth::Admin) {
			$_SESSION['token']['admin'] = true;
		}
	}

	public static function unregisterUser() {
		if(isset($_SESSION['token'])) {
			unset($_SESSION['token']);
		}
	}
}