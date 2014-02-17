<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

include_once 'common.php';

$moduleMapping['account'] = (object) array(
	'default' => new GFCommonMapping(['page' => 'account', 'menu' => true, 'acl' => GFCommonAuth::Registered]),
	'login' => new GFCommonMapping(['page' => 'login', 'menu' => true, 'acl' => GFCommonAuth::Unregistered]),
	'inscription' => new GFCommonMapping(['page' => 'inscription', 'menu' => true, 'acl' => GFCommonAuth::Unregistered]),
	'forgetpwd' => new GFCommonMapping(['page' => 'forget_pwd', 'acl' => GFCommonAuth::Unregistered]),
	'active' => new GFCommonMapping(['page' => 'active_account', 'acl' => GFCommonAuth::Unregistered]),
	'logout' => new GFCommonMapping(['page' => 'logout', 'menu' => GFCommonAuth::Registered, 'acl' => GFCommonAuth::Registered])
);