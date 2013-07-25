<h2><?php echo $i18n->getText('account','inscription title'); ?></h2>
 
<?php

	GFCommonJavascript::addScript('form');
 
if (!empty($inscription_errors)) {
 
    echo '<ul>'."\n";
     
    foreach($inscription_errors as $e) {
     
     	if(is_array($e)) {
     		echo '  <li>'.$i18n->getText('account error',$e['id'], $e['params']).'</li>'."\n";
     	} else {
        	echo '  <li>'.$i18n->getText('account error',$e).'</li>'."\n";
        }
    }
     
    echo '</ul>';
}
 
echo $form_inscription;