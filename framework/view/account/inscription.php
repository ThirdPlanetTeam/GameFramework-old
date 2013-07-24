<h2>Inscription au site</h2>
 
<?php

	GFCommonJavascript::AddScript('lib/jquery', GFCommonJavascript::ScopeCore);
	GFCommonJavascript::AddScript('lib/sha', GFCommonJavascript::ScopeScript);

	GFCommonJavascript::AddScript('form', GFCommonJavascript::ScopeScript);
 
if (!empty($erreurs_inscription)) {
 
    echo '<ul>'."\n";
     
    foreach($erreurs_inscription as $e) {
     
        echo '  <li>'.$e.'</li>'."\n";
    }
     
    echo '</ul>';
}
 
echo $form_inscription;