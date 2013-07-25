<?php 

$moduleMapping['account'] = (object) array(
	'login' => new GFCommonMapping(['page' => 'login', 'menu' => true, 'acl' => GFCommonAuth::Unregistered]),
	'inscription' => new GFCommonMapping(['page' => 'inscription', 'menu' => true, 'acl' => GFCommonAuth::Unregistered]),
	'forgetpwd' => new GFCommonMapping(['page' => 'forget_pwd', 'acl' => GFCommonAuth::Unregistered]),
	'logout' => new GFCommonMapping(['page' => 'logout', 'menu' => GFCommonAuth::Registered, 'acl' => GFCommonAuth::Registered]),
	'accountmanage' => new GFCommonMapping(['page' => 'account', 'menu' => true, 'acl' => GFCommonAuth::Registered])
);