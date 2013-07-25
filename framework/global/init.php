<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

session_start();

date_default_timezone_set('Europe/Zurich');

header('X-UA-Compatible: IE=edge,chrome=1');

require_once('common.php');
require_once('common.auth.php');
require_once('common.error.php');
require_once('common.security.php');
require_once('common.mapping.php');
require_once('common.javascript.php');

define("SERVER_ROOT", substr(__DIR__, 0, -7));
define("DEFAULT_MODULE", 'global');
define("DEFAULT_ACTION", 'default');

include(SERVER_ROOT . '/global/common.globalvar.php');

spl_autoload_register(function ($class) {
    include SERVER_ROOT . '/libs/' . $class . '.php';
});


$lang = i18n::getLangCode();


$i18n = new i18n($lang);

// declare empty array / variable for avoiding php warning
$moduleMapping = array();
$globalMapping = array();
$headers = array();



$display_headers = true;

$include_module = DEFAULT_MODULE;
$include_action = DEFAULT_ACTION;

//$action_def = (object) array('page' => 'default','acl' => 'anyone','context' => 'page');
$action_def = new GFCommonMapping();


