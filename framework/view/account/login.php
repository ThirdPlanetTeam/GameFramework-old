<?php

	GFCommonJavascript::addScript('form');

	if (!empty($login_errors)) {
	 
	    echo '<ul>'."\n";
	     
	    foreach($login_errors as $e) {
	     
	     	if(is_array($e)) {
	     		echo '  <li>'.$i18n->getText('account error',$e['id'], $e['params']).'</li>'."\n";
	     	} else {
	        	echo '  <li>'.$i18n->getText('account error',$e).'</li>'."\n";
	        }
	    }
	     
	    echo '</ul>';
	}

	echo $login_form;
?>
<a href="<?php echo GFCommon::formatLink("account", "inscription"); ?>"><?php echo $i18n->getText('menu','account inscription'); ?></a><br>
<a href="<?php echo GFCommon::formatLink("account", "forgetpwd"); ?>"><?php echo $i18n->getText('menu','account forgetpwd'); ?></a>