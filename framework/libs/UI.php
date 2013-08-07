<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

class UI {

	public static $auto_global_menu = [];
	public static $auto_sub_menu = [];
	public static $auto_full_menu = [];

 	public static function Breadcrumbs($query_module, $query_action) {

 		include(FRAMEWORK_ROOT . '/global/common.globalvar.php');

	    $list = [];

	    $list[] = array('module' => DEFAULT_MODULE, 'action' => DEFAULT_ACTION, 'title' => 'home');

	    if($query_module != DEFAULT_MODULE && !empty($query_module)) {
	        $list[] = array('module' => $query_module, 'action' => DEFAULT_ACTION, 'title' => 'section ' . $query_module);
	    }

	    if($query_action != DEFAULT_ACTION && !empty($query_action)) {
	    	if(empty($query_module)) {
	    		$query_module = DEFAULT_MODULE;
	    	}

	        $list[] = array('module' => $query_module, 'action' => $query_action, 'title' => $query_module . ' ' . $query_action);
	    }   

	    echo '<ul class="breadcrumb">';

	    $i = 0;
	    $max = count($list);
	    for($i; $i<$max;$i++) {

	        $module = $list[$i]['module'];
	        $action = $list[$i]['action'];
	        $title  = $list[$i]['title'];

	        if($i == $max-1) {
	            echo '<li class="active">';
	        } else {
	            echo '<li>';
	        }

	        echo '<a href="'.GFCommon::formatLink($module, $action).'">'.$i18n->getText('menu', $title).'</a></li>';
	        
	    }

	    echo '</ul>';
 	}

	public static function generateAutoMenu() {
		include(FRAMEWORK_ROOT . '/global/common.globalvar.php');

		foreach($globalMapping as $module_name => $module) {
			include FRAMEWORK_ROOT . '/modules/' . $module . '/mapping.php';

			self::$auto_full_menu[$module_name] = [];
			self::$auto_global_menu[] = $module_name;

			foreach ($moduleMapping[$module] as $action_name => $action) {

				$aclOk = GFCommonAuth::checkAcl($action->acl, $action->perms, true);

				if(!is_int($action->inMenu)) {
					if($action->acl == GFCommonAuth::Registered) {
						$aclOk = true;
					}
				}

				if($action->inMenu != false && $aclOk) {
					self::$auto_sub_menu[$action_name] = $action->inMenu;
					self::$auto_full_menu[$module_name][$action_name] = $action->inMenu;
				}
			}
		}	
	}

	public static function printFullMenu(Array $menu) {

		global $i18n;

		echo '<div id="menu" class="navbar navbar-static">
		<a class="navbar-brand" href="#">Menu</a>
		
		<ul class="nav navbar-nav" role="navigation">';

		foreach($menu as $section => $submenu) {
			if(count($submenu) > 0) {
				echo "<li class='dropdown'><a href='#' class='dropdown-toggle'  id='menu-".$section."' role='button' data-toggle='dropdown' >".$i18n->getText('menu','section '.$section)."</a>
				<ul class='dropdown-menu' role='menu' aria-labelledby='menu-".$section."'>" . PHP_EOL;
				foreach ($submenu as $menu_action => $menu_name) {
					echo "<li><a href='".GFCommon::formatLink($section, $menu_action)."'>".$i18n->getText('menu',$section . ' ' . $menu_action)."</a></li>" . PHP_EOL;
				}
				echo '</ul></li>';
			}
		}
		echo '</ul></div>';
	} 	

	public static function AdminBar() {

		if(GFCommonAuth::checkAcl(GFCommonAuth::PermsCode, 'ADMIN', true)) {

			include(FRAMEWORK_ROOT . '/global/common.globalvar.php');

			$timeend=microtime(true);
			$time=$timeend-$timestart;	

			if(count(Modeles::$nb_query) > 0) {
				$queries = '<a href="#" data-toggle="dropdown" class="dropdown-toggle" id="admin-query">Number of query: '.count(Modeles::$nb_query).'</a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="admin-query">';
				foreach (Modeles::$nb_query as $query) {
					$queries .= "<li><a href=''>".$query."</a></li>" . PHP_EOL;
				}
				$queries .= '</ul>';
			} else {
				$queries = '<a href="">Number of query: 0</a>';
			}
			

			echo '<div class="navbar navbar-fixed-bottom navbar-inverse">
				<a href="" class="navbar-brand">Admin Bar</a>
				<ul class="nav navbar-nav">
					<li><a href="">Execution time: '.$time.'</a></li>
					<li>'.$queries.'</li>
				</ul>
			</div>';		
		}

	}

	public static function DebugPrint($data) {
		
		
		echo '<div class="alert alert-info debug">';
		if(is_array($data)) {
			print_r($data);
		} else {
			var_dump($data);
		}
		
		echo '</div>';
	}
 
}