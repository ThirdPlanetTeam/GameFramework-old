<?php

	GFCommonJavascript::AddScript('lib/jquery', GFCommonJavascript::ScopeCore);
	GFCommonJavascript::AddScript('lib/sha', GFCommonJavascript::ScopeScript);

	GFCommonJavascript::AddScript('form', GFCommonJavascript::ScopeScript);

	echo $login_form;
?>
<a href="<?php echo GFCommon::formatLink("account", "inscription"); ?>">Inscription</a><br>
<a href="<?php echo GFCommon::formatLink("account", "forgetpwd"); ?>">Mot de passe oublié ?</a>