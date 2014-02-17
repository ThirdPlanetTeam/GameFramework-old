<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

define('WEB_DOMAIN', $_SERVER["HTTP_HOST"]);
define("SERVER_ROOT", substr(__DIR__, 0, -17));
define("FRAMEWORK_ROOT", SERVER_ROOT . '/framework');
define("DEFAULT_MODULE", 'global');
define("DEFAULT_ACTION", 'default');

?>