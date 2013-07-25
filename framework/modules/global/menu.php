<?php

$global_menu = [];
$sub_menu = [];
$full_menu = [];

if(isset($_SESSION['token'])) {
	echo "Bienvenue " . $_SESSION['token']['username'];
}

foreach($globalMapping as $module_name => $module) {
	include SERVER_ROOT . '/modules/' . $module . '/mapping.php';

	$full_menu[$module_name] = [];
	$global_menu[] = $module_name;

	foreach ($moduleMapping[$module] as $action_name => $action) {

		$aclOk = GFCommonAuth::checkAcl($action->acl, true);

		if(!is_int($action->inMenu)) {
			if($action->acl == GFCommonAuth::Registered) {
				$aclOk = true;
			}
		}

		if($action->inMenu != false && $aclOk) {
			$sub_menu[$action_name] = $action->inMenu;
			$full_menu[$module_name][$action_name] = $action->inMenu;
		}
	}
}

function printFullMenu(Array $menu) {

	global $i18n;

	GFCommonJavascript::addScript('jqueryui');

	GFCommonJavascript::addCallback('$( "#menu" ).accordion();');

	echo '<div id="menu">';

	foreach($menu as $section => $submenu) {
		if(count($submenu) > 0) {
			echo "<h3>".$i18n->getText('menu','section '.$section)."</h3><div><ul>";
			foreach ($submenu as $menu_action => $menu_name) {
				echo "<li><a href='?module=$section&action=$menu_action'>".$i18n->getText('menu',$section . ' ' . $menu_action)."</a></li>";
			}
			echo '</ul></div>';
		}
	}
	echo '</div>';
}