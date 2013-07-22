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
	    list($username, $password, $source_module, $source_action) = $login_form->get_cleaned_data('username', 'password', 'source_module', 'source_action');

	    GFCommonAuth::registerUser($username);

	    if($source_module != 'global' || ($source_module == 'global' && $source_action != 'login' && $source_action != 'logout')) {
	    	header("Location: ?module=$source_module&action=$source_action");
	    } else {
	    	header("Location: .");
	    }

} else {

	GFCommonJavascript::AddScript('lib/jquery', GFCommonJavascript::ScopeCore);
	GFCommonJavascript::AddScript('lib/sha', GFCommonJavascript::ScopeScript);

	GFCommonJavascript::AddScript('<script>
	  $(function() {
	    $( "#login_form" ).on("submit", function() {
	    	$(".jscrypt").each(function() {
	    		var hash;
	    		var input = $(this).attr("for");
	    		var pass= $("#"+input).val();
	    		var salt = $(this).data("salt");

	    		hash = Sha1.hash(salt + pass);

	    		$("#"+input).attr("value", "");
	    		$(this).val(hash);

	    	});
	    });
	  });
  </script>', GFCommonJavascript::ScopeInline);

	echo $login_form;

}