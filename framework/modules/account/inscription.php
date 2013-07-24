<?php

$form_inscription = new Form('form_inscription', 'POST');
 
$form_inscription->action(GFCommon::formatLink('account', 'inscription'));
 
$form_inscription->add('Text', 'username')
                 ->label("Votre nom d'utilisateur");
 
$form_inscription->add('Password', 'password')
                 ->label("Votre mot de passe")
                 ->jscrypt('sha1', GFCommonAuth::BrowserSalt); 
 
$form_inscription->add('Password', 'password_verif')
                 ->label("Votre mot de passe (vérification)")
                 ->jscrypt('sha1', GFCommonAuth::BrowserSalt); 
 
$form_inscription->add('Email', 'email')
                 ->label("Votre adresse email"); 

$form_inscription->add('Email', 'email_verif')
                 ->label("Votre adresse email (vérification)");                  
 
 
$form_inscription->add('Submit', 'submit')
                 ->value("Je veux m'inscrire !");
 
// Pré-remplissage avec les valeurs précédemment entrées (s'il y en a)
$form_inscription->bound($_POST);

$erreurs_inscription = array();

if ($form_inscription->is_valid($_POST)) {
 
    // On vérifie si les 2 mots de passe correspondent
    if ($form_inscription->get_cleaned_data('password') != $form_inscription->get_cleaned_data('password_verif')) {
 
        $erreurs_inscription[] = "Les deux mots de passes entrés sont différents !";
    }

    // On vérifie si les 2 adresses emails correspondent
    if ($form_inscription->get_cleaned_data('email') != $form_inscription->get_cleaned_data('email_verif')) {
 
        $erreurs_inscription[] = "Les deux adresses emails  entrés sont différentes !";
    }   

    // Si d'autres erreurs ne sont pas survenues
    if (empty($erreurs_inscription)) {
 
        $model = Modeles::getModel('account', 'account');

		list($username, $password, $email) = $form_inscription->get_cleaned_data('username', 'password', 'email');        

    	$exists = $model->checkRegister($username, $email);

    	if($exists != false) {
    		foreach ($exists as $value) {
    			if($exists[$model::FIELD_USERNAME] == $username) {
    				$erreurs_inscription['username'] = "Le nom d'utilisateur $username est déjà utilisé";
    			}

    			if($exists[$model::FIELD_EMAIL] == $email) {
    				$erreurs_inscription['email'] = "L'adresse email $email est déjà utilisé";
    			}    			
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