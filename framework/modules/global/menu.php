<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

$auto_global_menu = [];
$auto_sub_menu = [];
$auto_full_menu = [];

/*if(isset($_SESSION['token'])) {
	echo "Bienvenue " . $_SESSION['token']['username'];
}*/

function generateAutoMenu() {
	include(FRAMEWORK_ROOT . '/global/common.globalvar.php');

	foreach($globalMapping as $module_name => $module) {
		include FRAMEWORK_ROOT . '/modules/' . $module . '/mapping.php';

		$auto_full_menu[$module_name] = [];
		$auto_global_menu[] = $module_name;

		foreach ($moduleMapping[$module] as $action_name => $action) {

			$aclOk = GFCommonAuth::checkAcl($action->acl, true);

			if(!is_int($action->inMenu)) {
				if($action->acl == GFCommonAuth::Registered) {
					$aclOk = true;
				}
			}

			if($action->inMenu != false && $aclOk) {
				$auto_sub_menu[$action_name] = $action->inMenu;
				$auto_full_menu[$module_name][$action_name] = $action->inMenu;
			}
		}
	}	
}

function printFullMenu(Array $menu) {

	global $i18n;

	//GFCommonJavascript::addCallback('$( "#menu" ).dropdown();');

	echo '<div id="menu" class="navbar navbar-static">
	<a class="navbar-brand" href="#">Menu</a>
	
	<ul class="nav navbar-nav" role="navigation">';


	//echo '<div id="menu" class="dropdown">' . PHP_EOL;

	foreach($menu as $section => $submenu) {
		if(count($submenu) > 0) {
			echo "<li class='dropdown'><a href='#' class='dropdown-toggle'  id='menu-".$section."' role='button' data-toggle='dropdown' >".$i18n->getText('menu','section '.$section)."</a>
			<ul class='dropdown-menu' role='menu' aria-labelledby='menu-".$section."'>" . PHP_EOL;
			foreach ($submenu as $menu_action => $menu_name) {
				echo "<li><a href='?module=$section&action=$menu_action'>".$i18n->getText('menu',$section . ' ' . $menu_action)."</a></li>" . PHP_EOL;
			}
			echo '</ul></li>';
		}
	}
	echo '</ul></div>';
}