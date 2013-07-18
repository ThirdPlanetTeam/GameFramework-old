<?php 

$moduleMapping['global'] = (object) array(
	'default' => new GFCommonMapping(),
	'login' => new GFCommonMapping(['page' => 'login', 'menu' => 'Login', 'acl' => GFCommonAuth::Unregistered]),
	'logout' => new GFCommonMapping(['page' => 'logout', 'menu' => 'Logout', 'acl' => GFCommonAuth::Registered]),
	'accountmanage' => new GFCommonMapping(['page' => 'account', 'menu' => 'Account', 'acl' => GFCommonAuth::Registered])
);