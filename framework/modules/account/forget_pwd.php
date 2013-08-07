<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

$account_form = new Form('forget_pwd', 'POST');

$account_form->add_bootstrap(INPUT_SIZE, LABEL_SIZE);

$account_form->action(GFCommon::formatLink('account', 'forgetpwd'));

$account_form->add('Text', 'username')
         ->label($i18n->getText('account','username'))
         ->placeholder($i18n->getText('account','username placeholder'))
         ->required(false);

$account_form->add('Email', 'email')
         ->label($i18n->getText('account','email'))
         ->placeholder($i18n->getText('account','email placeholder'))
         ->required(false); 

$account_form->add('Hidden', 'source_module')
		 ->value($query_module);  

$account_form->add('Hidden', 'source_action')
		 ->value($query_action);    		   

$account_form->add('Submit', $i18n->getText('account','submit forgetpwd')); 

$account_form->bound($_POST);

$account_errors = array();

echo 'toto';

if ($account_form->is_valid($_POST)) {

		echo 'toto2';

	    $list = $account_form->get_cleaned_data('username', 'email', 'source_module', 'source_action');

	    $model = Modeles::getModel('account', 'account');


	    list($username, $email, $source_module, $source_action) = $list;

	    
	    if($username != null) {
	    	$user = $model->getUserInfoByUsername($username);
	    } else if($email != null) {
	    	$user = $model->getUserInfoByEmail($email);
	    } else {
			$account_errors[] = 'username or email missing';
	    }

	    if(empty($account_errors)) {

		    if($user != false) {
			    $salt = $user[$model::FIELD_SALT];

			    $hash = GFCommonAuth::getSha512($password, $salt);

			    $need_activation = $user[$model::FIELD_VALIDATION] != null;

			   	if($need_activation) {
			   		$location = "Location: ".GFCommon::formatLink('account', 'active', array('source_module='.$source_module, 'source_action='.$source_action));
			   		header($location);
			   		exit;
			   	}

			    if($hash == $user[$model::FIELD_HASH]) {

			    	GFCommonAuth::registerToken($user[$model::FIELD_ID]);

				    if($source_module != 'account' || ($source_module == 'account' && $source_action != 'login' && $source_action != 'logout')) {
				    	header("Location: ".GFCommon::formatLink($source_module, $source_action, null, true));
				    } else {
				    	header("Location: ".SERVER_URL);
				    }

			    } else {
			    	$account_errors[] = 'bad password';
			    }

		    } else {
		    	$account_errors[] = 'bad username';
		    }

		}
}

$page_title = 'forgetpwd title';

include FRAMEWORK_ROOT . '/view/account/form.php';
