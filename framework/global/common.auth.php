<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

define("MODULE_ACCOUNT", "account");
define("ACTION_LOGIN", "login");
define("ACTION_LOGOUT", "logout");

define("MODULE_ERROR", "global");
define("ACTION_ERROR", "error");

class GFCommonAuth {

	const Unregistered = -1; // Need to be unregistered
	const Anyone = 0;
	const Registered = 1;
	const Admin = 2;
	const PermsCode = 3;

	const BrowserSalt = 'b9704fe8c16809f48ccedb818654e41e';


	public static function checkAcl($acl, $perms, $safe = false) {

		global $security;

		if(isset($_SESSION['token'])) {
			GFCommonSecurity::refreshPermissions($_SESSION['token']['uid']);
		}

		if(!$security->canLogin() && !$safe) {
			$e = new GFExceptionMajor("IP banned", 1);
			//$e->redirectModule = MODULE_ERROR;
			//$e->redirectAction = ACTION_ERROR;
			throw $e;			
		}

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


		if($acl == GFCommonAuth::Admin && !isset($_SESSION['token']['admin'])) {
			if($safe) {
				return false;
			} else {	
				$security->unauthorizedPlace();
				throw new GFExceptionMajor("Unauthorised query", 1);	
			}	
		}

		if($acl == GFCommonAuth::PermsCode && (!isset($_SESSION['token']['perms'][$perms]) && !isset($_SESSION['token']['admin']))) {
			if($safe) {
				return false;
			} else {	
				$security->unauthorizedPlace();
				throw new GFExceptionMajor("Unauthorised query", 1);	
			}	
		}		

		/*if($acl >= GFCommonAuth::Admin && $_SESSION['token']['admin'] == false) {
			if($safe) {
				return false;
			} else {	
				$security->unauthorizedPlace();
				throw new GFExceptionMajor("Unauthorised query", 1);	
			}	
		}*/

		return true;
	}

	public static function registerToken($user_id, int $acl = null) {
		$_SESSION['token'] = [];

		$_SESSION['token']['id'] = md5(uniqid(rand(), true));
		$_SESSION['token']['uid'] = $user_id;

		/*if(!is_null($acl) && $acl >= GFCommonAuth::Admin) {
			$_SESSION['token']['admin'] = true;
		}*/
	}

	public static function unregisterToken() {
		if(isset($_SESSION['token'])) {
			unset($_SESSION['token']);
		}

		self::removeCookie();
	}

	public static function getSha512($pwd, $salt) {
		return explode('$', crypt($pwd, '$6$rounds=5000$'.$salt.'$'))[4];
	}

	public static function autologin() {

		global $security;

		//unset($_SESSION['token']);

		if(!isset($_SESSION['token'])) {

			$cookie = self::getCookie();

			if($cookie['hash'] == null || $cookie['uid'] == null) {
				// There is no cookie
				return;
			}

			$hash = $cookie['hash'];
			$user_id = $cookie['uid'];

			$error = '';

			$model = Modeles::getModel('account', 'cookie');

			$bdd_cookie = $model->getCookie($hash);

			if($bdd_cookie != false) {

				if($bdd_cookie[CookieModel::FIELD_USEID] != $user_id) {
					$error .= "Bad UID, Received UID: $user_id, Expected: ".$bdd_cookie[CookieModel::FIELD_USEID] . ' ' . PHP_EOL;
				}

				$browser = json_encode(GFCommonSecurity::getBrowserInfo());

				if(md5($browser) != md5($bdd_cookie[CookieModel::FIELD_BROWSERINFO])) {
					$error .= "Browsers do not match, Received : $browser, Expected: " . $bdd_cookie[CookieModel::FIELD_BROWSERINFO] . ' ' . PHP_EOL;
				}

				if(empty($error)) {
			    	GFCommonAuth::registerToken($user_id);
			    	GFCommonAuth::generateCookie($user_id, $hash);
				} else {
					GFCommonAuth::unregisterToken();
					$security->Log("Bad cookie : $error");
				}
			} else {
				GFCommonAuth::unregisterToken();
				$security->Log("Bad cookie : Innexistant cookie in bdd");
			}
		}
	}

	public static function getCookie() {
		$uid = isset($_COOKIE["gf_autologin_uid"]) ? $_COOKIE["gf_autologin_uid"] : null;
		$hash = isset($_COOKIE["gf_autologin_hash"]) ? $_COOKIE["gf_autologin_hash"] : null;

		return array('hash' => $hash, 'uid' => $uid);
	}

	public static function generateCookie($uid, $oldhash = false) {

		$model = Modeles::getModel('account', 'cookie');

		$hash = md5(uniqid(rand(), true) . $uid);

		$ip = $_SERVER['REMOTE_ADDR'];

		$browser = GFCommonSecurity::getBrowserInfo();

		//$chain = json_encode(array(['uid' => $uid, 'hash' => $hash]));

		$end = time() + 3600 * 24 * 10;

		if($oldhash) {
			$model->rewriteCookie($oldhash, $hash, date("Y-m-d H:i:s",$end));
		} else {
			$model->writeCookie($uid, $hash, json_encode($browser), $ip, gethostbyaddr($ip), date("Y-m-d H:i:s",$end));	
		}

		self::setCookie($uid, $hash, $end);

		

	}

	public static function removeCookie() {

		
		

		$hash = self::getCookie();

		if($hash['hash']) {
			$model = Modeles::getModel('account', 'cookie');
			$model->deleteCookie($hash['hash']);
		}


		$end = time() - (3600 * 24 * 365);
		setcookie('gf_autologin_uid', "deleted", $end, WEB_SUBFOLDER, "", SSL, true);
		setcookie('gf_autologin_hash', "deleted", $end, WEB_SUBFOLDER, "", SSL, true);	
	}

	private static function setCookie($uid, $hash, $end) {
		setcookie('gf_autologin_uid', $uid, $end, WEB_SUBFOLDER, "", SSL, true);
		setcookie('gf_autologin_hash', $hash, $end, WEB_SUBFOLDER, "", SSL, true);		
	}

}