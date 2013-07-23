<?php


$login_form = new Form('login_form', 'POST');

$login_form->action('index.php?module=account&action=login');

$login_form->add('Text', 'username')
         ->label($i18n->getText('login','username'))
         ->placeholder('username');

$login_form->add('Password', 'password')
         ->label($i18n->getText('login','password'))
         ->jscrypt('sha1', 'salt1'); 

$login_form->add('Hidden', 'source_module')
		 ->value($query_module);  

$login_form->add('Hidden', 'source_action')
		 ->value($query_action);    		   

$login_form->add('Submit', 'submit'); 

$login_form->bound($_POST);

if ($login_form->is_valid($_POST)) {
	    $list = $login_form->get_cleaned_data('username', 'password', 'source_module', 'source_action');

	    $model = Modeles::getModel('account', 'account');

	    //print_r($list);

	    list($username, $password, $source_module, $source_action) = $list;

	    $user = $model->getUserInfo($username);

	    if($user != false) {
		    $salt = md5($user[$model::FIELD_DATECREATION]);

		    //$hash = explode('$', crypt($user[$model::FIELD_HASH], '$6$rounds=5000$'.$salt.'$'))[4];
		    $hash = explode('$', crypt($password, '$6$rounds=5000$'.$salt.'$'))[4];

		    if($hash == $user[$model::FIELD_HASH]) {
		    	GFCommonAuth::registerUser($username);
		    }

	    } else {
	    	echo 'mauvais username';
	    }

	    if($source_module != 'global' || ($source_module == 'global' && $source_action != 'login' && $source_action != 'logout')) {
	    	header("Location: ?module=$source_module&action=$source_action");
	    } else {
	    	header("Location: .");
	    }

} else {

	GFCommonJavascript::AddScript('lib/jquery', GFCommonJavascript::ScopeCore);
	GFCommonJavascript::AddScript('lib/sha', GFCommonJavascript::ScopeScript);

	GFCommonJavascript::AddScript('form', GFCommonJavascript::ScopeScript);

	echo $login_form;

}