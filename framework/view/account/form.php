<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

?>
<h2><?php echo $i18n->getText('account',$page_title); ?></h2>
 
<?php
	GFCommonJavascript::addScript('form');

	if (!empty($account_errors)) {
	 
	    //echo '<ul>'."\n";
	     
	    foreach($account_errors as $e) {
	     	echo '  <div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>';
	     	if(is_array($e)) {
	     		echo $i18n->getText('account error',$e['id'], $e['params']).'</div>'."\n";
	     	} else {
	        	echo $i18n->getText('account error',$e).'</div>'."\n";
	        }
	    }
	     
	    //echo '</ul>';
	}

	echo $account_form;
?>
<a href="<?php echo GFCommon::formatLink("account", "login"); ?>"><?php echo $i18n->getText('menu','account login'); ?></a><br>
<a href="<?php echo GFCommon::formatLink("account", "inscription"); ?>"><?php echo $i18n->getText('menu','account inscription'); ?></a><br>
<a href="<?php echo GFCommon::formatLink("account", "forgetpwd"); ?>"><?php echo $i18n->getText('menu','account forgetpwd'); ?></a>