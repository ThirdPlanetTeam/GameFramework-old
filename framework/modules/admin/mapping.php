<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

//include_once 'common.php';


$moduleMapping['admin'] = (object) array(
	'default' => new GFCommonMapping(['menu' => true, 'acl' => GFCommonAuth::Admin]),
	'account' => new GFCommonMapping(['page' => 'account', 'menu' => true, 'acl' => GFCommonAuth::Admin]),
	'integrity' => new GFCommonMapping(['page' => 'integrity', 'menu' => true, 'acl' => GFCommonAuth::Admin]),
	'checklang' => new GFCommonMapping(['context' => GFCommonMapping::ContextAjax, 'page' => 'integrity', 'acl' => GFCommonAuth::Admin])
);

