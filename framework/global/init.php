<?php

session_start();


date_default_timezone_set('Europe/Zurich');


define("SERVER_ROOT", substr(__DIR__, 0, -7));

// declare empty array / variable for avoiding php warning
$moduleMapping = array();
$globalMapping = array();
$headers = array();

$display_headers = true;

$include_module = 'global';
$include_action = 'default';

$action_def = (object) array('page' => 'default',
	'acl' => 'anyone',
	'context' => 'page');