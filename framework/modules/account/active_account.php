<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

$account_form = new Form('validation_form', 'POST');

$account_form->add_bootstrap(INPUT_SIZE, LABEL_SIZE);

$account_form->action(GFCommon::formatLink('account', 'active'));

$account_form->add('Text', 'username')
         ->label($i18n->getText('account','username'))
         ->placeholder($i18n->getText('account','username placeholder'));

$account_form->add('Text', 'validation_hash')
         ->label($i18n->getText('account','validation'))
         ->placeholder($i18n->getText('account','validation placeholder'));

$account_form->add('Hidden', 'source_module')
		 ->value($query_module);  

$account_form->add('Hidden', 'source_action')
		 ->value($query_action);    		   

$account_form->add('Submit', $i18n->getText('account','submit validation')); 

$account_form->bound($_POST);

$account_errors = array();

if ($account_form->is_valid($_POST)) {
	    $list = $account_form->get_cleaned_data('username', 'validation_hash', 'source_module', 'source_action');

	    $model = Modeles::getModel('account', 'account');


	    list($username, $validation, $source_module, $source_action) = $list;

	    $user = $model->getUserInfo($username);

	    if($user != false) {

	    	if($user[$model::FIELD_VALIDATION] == null) {
	    		$account_errors[] = 'already validated';
	    	} elseif($user[$model::FIELD_VALIDATION] != $validation) {
	    		$account_errors[] = 'bad validation code';
	    	} else {

	    		$model->validateUser($username);

				header("Location: ".GFCommon::formatLink('account', 'login', array('source_module='.$source_module, 'source_action='.$source_action)));
	    	}

	    } else {
	    	$account_errors[] = 'bad username';
	    }
}

$page_title = 'validation title';

include SERVER_ROOT . '/view/account/form.php';
