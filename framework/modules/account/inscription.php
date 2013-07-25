<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

$form_inscription = new Form('form_inscription', 'POST');
 
$form_inscription->action(GFCommon::formatLink('account', 'inscription'));
 
$form_inscription->add('Text', 'username')
                 ->label($i18n->getText('account','username'))
                 ->placeholder($i18n->getText('account','username placeholder'));
 
$form_inscription->add('Password', 'password')
                 ->label($i18n->getText('account','password'))
                 ->jscrypt('sha1', GFCommonAuth::BrowserSalt); 
 
$form_inscription->add('Password', 'password_verif')
                 ->label($i18n->getText('account','password again'))
                 ->jscrypt('sha1', GFCommonAuth::BrowserSalt); 
 
$form_inscription->add('Email', 'email')
                 ->label($i18n->getText('account','email'))
                 ->placeholder($i18n->getText('account','email placeholder')); 

$form_inscription->add('Email', 'email_verif')
                 ->label($i18n->getText('account','email again'))
                 ->placeholder($i18n->getText('account','email placeholder'));                  
 
 
$form_inscription->add('Submit', 'submit')
                 ->value($i18n->getText('account','submit inscription'));
 
// Pré-remplissage avec les valeurs précédemment entrées (s'il y en a)
$form_inscription->bound($_POST);

$inscription_errors = array();

if ($form_inscription->is_valid($_POST)) {
 
    // On vérifie si les 2 mots de passe correspondent
    if ($form_inscription->get_cleaned_data('password') != $form_inscription->get_cleaned_data('password_verif')) {
 
        $inscription_errors[] = "passwords diff";
    }

    // On vérifie si les 2 adresses emails correspondent
    if ($form_inscription->get_cleaned_data('email') != $form_inscription->get_cleaned_data('email_verif')) {
 
        $inscription_errors[] = "emails diff";
    }   

    // Si d'autres erreurs ne sont pas survenues
    if (empty($inscription_errors)) {
 
        $model = Modeles::getModel('account', 'account');

		list($username, $password, $email) = $form_inscription->get_cleaned_data('username', 'password', 'email');        

    	$exists = $model->checkRegister($username, $email);

    	if($exists != false) {
    		foreach ($exists as $value) {

                //var_dump($exists);

    			if($value[$model::FIELD_USERNAME] == $username) {
    				$inscription_errors['username'] = array('id' => 'existant username', 'params' => array('username' => $username));
    			}

    			if($value[$model::FIELD_EMAIL] == $email) {
    				$inscription_errors['email'] = array('id' => 'existant email', 'params' => array('email' => $email));
    			} 
                $inscription_errors['username'] = array('id' => 'existant username', 'params' => array('username' => $username));		
    		}
    	} else {
    		$hash_validation = md5(uniqid(rand(), true));
        	$salt = md5(uniqid(rand(), true));

        	$model->registerUser($username, GFCommonAuth::getSha512($password, $salt), $salt, $email, $hash_validation);

        	header("Location: ".GFCommon::formatLink('account', 'accountmanage'));
    	}

    }
 
}

include SERVER_ROOT . '/view/account/inscription.php';