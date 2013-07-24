<?php 

$moduleMapping['account'] = (object) array(
	'login' => new GFCommonMapping(['page' => 'login', 'menu' => 'Login', 'acl' => GFCommonAuth::Unregistered]),
	'inscription' => new GFCommonMapping(['page' => 'inscription', 'menu' => 'Inscription', 'acl' => GFCommonAuth::Unregistered]),
	'forgetpwd' => new GFCommonMapping(['page' => 'forget_pwd', 'acl' => GFCommonAuth::Unregistered]),
	'logout' => new GFCommonMapping(['page' => 'logout', 'menu' => 'Logout', 'acl' => GFCommonAuth::Registered]),
	'accountmanage' => new GFCommonMapping(['page' => 'account', 'menu' => 'Account', 'acl' => GFCommonAuth::Registered])
);