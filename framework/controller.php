<?php
 
// Initialisation
include 'global/init.php';
include 'mapping.php';

/*
 global controller:
 check if the requested module exist, and call the module's controller
 the module's controller indicate the action file and the view for displaying.
 they also indicate the headers parameter, and if the view need html header & footers
*/
 
// Début de la tamporisation de sortie
ob_start();


if(!empty($_GET['module'])) {
    $module = $_GET['module'];
    if(ctype_alpha($module)) {
        if(array_key_exists($module, $globalMapping)) {
            $include_module = $globalMapping[$module];
        }
    }
}

include SERVER_ROOT . '/modules/' . $include_module . '/mapping.php';

if(!empty($_GET['action'])) {
    $action = $_GET['action'];
    if(ctype_alpha($action)) {
        if(array_key_exists($action, $moduleMapping)) {
            $action_def = $moduleMapping[$action];
            $include_action = $action_def->page;          
        }
    }
}

include SERVER_ROOT . '/modules/' . $include_module . '/' . $include_action . '.php';  

$content = ob_get_clean();
 
foreach ($headers as $head) {
    header($head);
}

if($action_def->context == 'page') {
    include 'view/header.php';
}
 
echo $content;
 
if($action_def->context == 'page') {
    include 'view/footer.php';
}