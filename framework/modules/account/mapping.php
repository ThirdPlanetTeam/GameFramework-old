<?php 

$moduleMapping['account'] = (object) array(
	'login' => new GFCommonMapping(['page' => 'login', 'menu' => 'Login', 'acl' => GFCommonAuth::Unregistered]),
	'logout' => new GFCommonMapping(['page' => 'logout', 'menu' => 'Logout', 'acl' => GFCommonAuth::Registered]),
	'accountmanage' => new GFCommonMapping(['page' => 'account', 'menu' => 'Account', 'acl' => GFCommonAuth::Registered])
);