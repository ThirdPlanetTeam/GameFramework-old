<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

session_start();

date_default_timezone_set('Europe/Zurich');

header('X-UA-Compatible: IE=edge,chrome=1');

require_once('common.php');
require_once('common.auth.php');
require_once('common.error.php');
require_once('common.security.php');
require_once('common.log.php');
require_once('common.mapping.php');
require_once('common.javascript.php');

require_once('constant.php');

define('WEB_DOMAIN', $_SERVER["HTTP_HOST"]);
define("SERVER_ROOT", substr(__DIR__, 0, -17));
define("FRAMEWORK_ROOT", SERVER_ROOT . '/framework');
define("DEFAULT_MODULE", 'global');
define("DEFAULT_ACTION", 'default');

include(FRAMEWORK_ROOT . '/global/common.globalvar.php');

require_once(SERVER_ROOT . '/etc/config.php');

$inhttps = ((key_exists('HTTPS', $_SERVER)) ?  $_SERVER["HTTPS"] == 'on' : false);

$protocol = (SSL == true || $inhttps) ? 'https' : 'http';

define('SERVER_URL' , $protocol . '://' . WEB_DOMAIN . WEB_SUBFOLDER . '/');


spl_autoload_register(function ($class) {

	$framework = FRAMEWORK_ROOT . '/libs/' . $class . '.php';
	if (file_exists($framework)) {
    	include $framework;
    	return;
	}

    $api = SERVER_ROOT . '/' . strtr($class, '\\', '//') . '.php';
    if (file_exists($api)) {
        include($api);
        return;
	}	
});


$lang = i18n::getLangCode();

$i18n = new i18n($lang);

$log = new GFCommonLog();

$security = new GFCommonSecurity();

// declare empty array / variable for avoiding php warning
$moduleMapping = array();
$globalMapping = array();
$headers = array();


// Injection des dépendances dans les modules externes
$i18n->logInstance = $log;
PDOConnector::$log = $log;

// Initialize js and css bases
GFCommonJavascript::addScript('main');
GFCommonJavascript::addStyle('lib/bootstrap');

$display_headers = true;

$include_module = DEFAULT_MODULE;
$include_action = DEFAULT_ACTION;

$action_def = new GFCommonMapping();


