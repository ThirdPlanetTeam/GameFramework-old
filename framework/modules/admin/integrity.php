<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

//echo 'INTEGRITY';

 function checkIniFile($dir) {

 	$grid = array();
 	$lang = array();

	$table = '';
	$head = '<tr><th>Var</th>'; 	
	$content = '';

	$current = opendir($dir);
	
	while($val = @readdir($current)) {
		if(!is_dir($dir.'/'.$val)&& $val != '.' && $val != '..') {
			// afficher
			$array = parse_ini_file($dir.'/'.$val, true);

			foreach ($array as $section => $array2) {
				foreach ($array2 as $key => $value) {
					$index = $section . ' : ' . $key;
					$grid[$index][$val] = $value;
					$lang[$val] = $val;
				}
			}
		}
	}
	closedir($current); 	

	foreach ($lang as $lang_key) {
		$head .= '<th>' . $lang_key . '</th>';
	}



	foreach ($grid as $variable => $array) {
		$table .= '<tr><td>' . $variable . '</td>';

		foreach ($lang as $lang_key) {

			$table .= '<td>';
			$table .= (isset($array[$lang_key])) ? $array[$lang_key] : '' ;
			$table .= '</td>';
		}

		$first = false;

		$table .= '</tr>';
	}

	$head .= '</tr>';

	return '<table>' . $head . $table . '</table';

 }

$content = '';

$page_title = 'Integrity check';

if($query_action == 'checklang') {
	$content = checkIniFile(LANG_DIR);
	$page_title = 'Lang check';	
} else {
	$action_list = array();

	$action_list['checklang'] = 'Check lang';

	$content = '<div class="modal fade" id="admin_modal"></div>';


}



include FRAMEWORK_ROOT . '/view/admin/admin.php';
