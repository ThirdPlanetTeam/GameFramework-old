<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

?>
<h2><?php echo $i18n->getText('account',$page_title); ?></h2>
 
<?php
	GFCommonJavascript::addScript('form');

	if (!empty($account_errors)) {
	 
	    echo '<ul>'."\n";
	     
	    foreach($account_errors as $e) {
	     
	     	if(is_array($e)) {
	     		echo '  <li>'.$i18n->getText('account error',$e['id'], $e['params']).'</li>'."\n";
	     	} else {
	        	echo '  <li>'.$i18n->getText('account error',$e).'</li>'."\n";
	        }
	    }
	     
	    echo '</ul>';
	}

	echo $account_form;
?>
<a href="<?php echo GFCommon::formatLink("account", "login"); ?>"><?php echo $i18n->getText('menu','account login'); ?></a><br>
<a href="<?php echo GFCommon::formatLink("account", "inscription"); ?>"><?php echo $i18n->getText('menu','account inscription'); ?></a><br>
<a href="<?php echo GFCommon::formatLink("account", "forgetpwd"); ?>"><?php echo $i18n->getText('menu','account forgetpwd'); ?></a>