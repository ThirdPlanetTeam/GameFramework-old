<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

$error_code = $e->message;

$error = $i18n->getText('site error', $error_code);

include FRAMEWORK_ROOT . '/view/index.php';
