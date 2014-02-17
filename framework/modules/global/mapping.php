<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

include_once 'common.php';

$moduleMapping['global'] = (object) array(
	'default' => new GFCommonMapping(),
	'error' => new GFCommonMapping()
);