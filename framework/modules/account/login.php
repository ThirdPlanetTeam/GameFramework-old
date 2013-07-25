<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

$login_form = new Form('login_form', 'POST');

$login_errors = array();

$login_form->action(GFCommon::formatLink('account', 'login'));

$login_form->add('Text', 'username')
         ->label($i18n->getText('account','username'))
         ->placeholder($i18n->getText('account','username placeholder'));

$login_form->add('Password', 'password')
         ->label($i18n->getText('account','password'))
         ->jscrypt('sha1', GFCommonAuth::BrowserSalt); 

$login_form->add('Hidden', 'source_module')
		 ->value($query_module);  

$login_form->add('Hidden', 'source_action')
		 ->value($query_action);    		   

$login_form->add('Submit', $i18n->getText('account','submit login')); 

$login_form->bound($_POST);

if ($login_form->is_valid($_POST)) {
	    $list = $login_form->get_cleaned_data('username', 'password', 'source_module', 'source_action');

	    $model = Modeles::getModel('account', 'account');


	    list($username, $password, $source_module, $source_action) = $list;

	    $user = $model->getUserInfo($username);

	    if($user != false) {
		    $salt = $user[$model::FIELD_SALT];

		    $hash = GFCommonAuth::getSha512($password, $salt);

		    var_dump($hash);

		    if($hash == $user[$model::FIELD_HASH]) {
		    	GFCommonAuth::registerUser($username);

			    if($source_module != 'account' || ($source_module == 'account' && $source_action != 'login' && $source_action != 'logout')) {
			    	header("Location: ".GFCommon::formatLink($source_module, $source_action));
			    } else {
			    	header("Location: .");
			    }

		    } else {
		    	$login_errors[] = 'bad password';
		    }

	    } else {
	    	$login_errors[] = 'bad username';
	    }
}

include SERVER_ROOT . '/view/account/login.php';
