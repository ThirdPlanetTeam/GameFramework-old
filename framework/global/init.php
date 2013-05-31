<?php

session_start();


date_default_timezone_set('Europe/Zurich');

require_once('common.auth.php');
require_once('common.error.php');
require_once('common.security.php');
require_once('common.mapping.php');


define("SERVER_ROOT", substr(__DIR__, 0, -7));
define("DEFAULT_MODULE", 'global');
define("DEFAULT_ACTION", 'default');

// declare empty array / variable for avoiding php warning
$moduleMapping = array();
$globalMapping = array();
$headers = array();

$display_headers = true;

$include_module = DEFAULT_MODULE;
$include_action = DEFAULT_ACTION;

//$action_def = (object) array('page' => 'default','acl' => 'anyone','context' => 'page');
$action_def = new GFCommonMapping();
