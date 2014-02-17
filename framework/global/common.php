<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

class GFCommon {

	public static function formatLink($module, $action, array $querystring = null, $absolute = false) {

		if(is_null($querystring)) {
			$querystring = array();
		}

		if(isset($_SESSION['token']['id'])) {
			$querystring[] = 'token=' . $_SESSION['token']['id'];
		}


		if(URL_REWRITE) {
			if (!is_file(SERVER_ROOT.'/www/.htaccess')) 
			{
				self::generateHtaccess();
			}

			$base = '';
			$mod = '';
			$act = '';
			$q = '';

			if($absolute) {
				$base = SERVER_URL;
			}

			if($module != DEFAULT_MODULE) {
				$mod = $module.'/';
			}

			if($action != DEFAULT_ACTION) {
				$act = $action.'.html';
			}		

			if(count($querystring) > 0) {
				$q = '?';
			}

			return $base.$mod.$act.$q.implode('&',$querystring);
		} else {
			if(count($querystring) > 0) {
				$action .= '&';
			}
			return '?module='.$module.'&action='.$action.implode('&',$querystring);
		}
	}

	public static function generateHtaccess()
	{

		$url = '';

$content = '<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /'.$url.'/
RewriteRule ^([^/]*)/([^/]*)\.html$ /'.$url.'/?module=$1&action=$2 [L]
</IfModule>';
	}

}