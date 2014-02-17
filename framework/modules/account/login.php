<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

$account_form = new Form('account_form', 'POST');

$account_form->add_bootstrap(INPUT_SIZE, LABEL_SIZE);

$account_form->action(GFCommon::formatLink('account', 'login'));

$account_form->add('Text', 'username')
         ->label($i18n->getText('account','username'))
         ->placeholder($i18n->getText('account','username placeholder'));

$account_form->add('Password', 'password')
         ->label($i18n->getText('account','password'))
         ->jscrypt('sha1', GFCommonAuth::BrowserSalt); 

$account_form->add('Checkbox', 'autologin')
               ->label($i18n->getText('account','autologin'))
               ->required(false);       

$account_form->add('Hidden', 'source_module')
		 ->value($query_module);  

$account_form->add('Hidden', 'source_action')
		 ->value($query_action);    		   

$account_form->add('Submit', $i18n->getText('account','submit login')); 

$account_form->bound($_POST);

$account_errors = array();

if ($account_form->is_valid($_POST)) {
	    $list = $account_form->get_cleaned_data('username', 'password', 'source_module', 'source_action', 'autologin');

	    $model = Modeles::getModel('account', 'account');


	    list($username, $password, $source_module, $source_action, $autologin) = $list;

	    $user = $model->getUserInfoByUsername($username);

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

		    	$security->loginOk();

		    	GFCommonAuth::registerToken($user[$model::FIELD_ID]);

		    	if($autologin) {
		    		GFCommonAuth::generateCookie($user[$model::FIELD_ID]);
		    	}

   	

			    if($source_module != 'account' || ($source_module == 'account' && $source_action != 'login' && $source_action != 'logout')) {
			    	//header("Location: ".GFCommon::formatLink($source_module, $source_action, null, true));
			    } else {
			    	//header("Location: ".SERVER_URL);
			    }

		    } else {
		    	$account_errors[] = 'bad password';
		    }

	    } else {
	    	$account_errors[] = 'bad username';
	    }

	    $security->loginFailed();
}

$page_title = 'login title';

include FRAMEWORK_ROOT . '/view/account/form.php';
