<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

?>
<h2><?php echo $page_title; ?></h2>
 
<?php




	if (!empty($action_list)) {
	 
	    echo '<ul>'."\n";
	     
	    foreach($action_list as $action => $label) {
	     	echo ' <li><a id="'.$action.'" href="#" class="btn btn-primary btn-lg">'.$label.'</a></li>';
	     	/*GFCommonJavascript::addCallback("
				$('#".$action."').modal({
					remote: '".GFCommon::formatLink("admin", $action)."'
				})
			");*/

	    }
	     
	    echo '</ul>';
	}

	echo $content;
?>