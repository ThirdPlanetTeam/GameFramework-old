<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

$account_form = new Form('form_inscription', 'POST');

$account_form->add_bootstrap(INPUT_SIZE, LABEL_SIZE);

$account_form->action(GFCommon::formatLink('account', 'inscription'));
 
$account_form->add('Text', 'username')
                 ->label($i18n->getText('account','username'))
                 ->placeholder($i18n->getText('account','username placeholder'));
 
$account_form->add('Password', 'password')
                 ->label($i18n->getText('account','password'))
                 ->jscrypt('sha1', GFCommonAuth::BrowserSalt); 
 
$account_form->add('Password', 'password_verif')
                 ->label($i18n->getText('account','password again'))
                 ->jscrypt('sha1', GFCommonAuth::BrowserSalt); 
 
$account_form->add('Email', 'email')
                 ->label($i18n->getText('account','email'))
                 ->placeholder($i18n->getText('account','email placeholder')); 

$account_form->add('Email', 'email_verif')
                 ->label($i18n->getText('account','email again'))
                 ->placeholder($i18n->getText('account','email placeholder'));                  
 
 
$account_form->add('Submit', 'submit')
                 ->value($i18n->getText('account','submit inscription'));
 
// Pré-remplissage avec les valeurs précédemment entrées (s'il y en a)
$account_form->bound($_POST);

$account_errors = array();

if ($account_form->is_valid($_POST)) {
 
    // On vérifie si les 2 mots de passe correspondent
    if ($account_form->get_cleaned_data('password') != $account_form->get_cleaned_data('password_verif')) {
 
        $account_errors[] = "passwords diff";
    }

    // On vérifie si les 2 adresses emails correspondent
    if ($account_form->get_cleaned_data('email') != $account_form->get_cleaned_data('email_verif')) {
 
        $account_errors[] = "emails diff";
    }   

    // Si d'autres erreurs ne sont pas survenues
    if (empty($account_errors)) {
 
        $model = Modeles::getModel('account', 'account');

		list($username, $password, $email) = $account_form->get_cleaned_data('username', 'password', 'email');        

    	$exists = $model->checkRegister($username, $email);

    	if($exists != false) {
    		foreach ($exists as $value) {

                //var_dump($exists);

    			if($value[$model::FIELD_USERNAME] == $username) {
    				$account_errors['username'] = array('id' => 'existant username', 'params' => array('username' => $username));
    			}

    			if($value[$model::FIELD_EMAIL] == $email) {
    				$account_errors['email'] = array('id' => 'existant email', 'params' => array('email' => $email));
    			} 
                $account_errors['username'] = array('id' => 'existant username', 'params' => array('username' => $username));		
    		}
    	} else {
    		$hash_validation = md5(uniqid(rand(), true));
        	$salt = md5(uniqid(rand(), true));

            $mail = new \api\tools\Mail();
            
            $mail->Subject .= 'Code de validation de votre compte';

            
            $mail->AddTo($email, $username);
                        
            $mail->ParseTitle = "Game Framework";          
            $mail->ParseCorps = "<table width='100%' height='200px'>
                                <tr>
                                    <td align='center'>
                                    Votre compte $username est bien enregistré<br />
                                    il ne vous reste plus qu'à le valider<br />
                                    code de validation: $hash_validation<br>
                                    </td>
                                </tr>
                    </table>";
                    
            $mail->Parse();

            $mail->Send();  


        	$model->registerUser($username, GFCommonAuth::getSha512($password, $salt), $salt, $email, $hash_validation);

        	header("Location: ".GFCommon::formatLink('account', 'default'));
    	}

    }
 
}

$page_title = 'inscription title';

include FRAMEWORK_ROOT . '/view/account/form.php';