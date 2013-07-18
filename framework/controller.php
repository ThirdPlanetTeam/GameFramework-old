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
 
function loadAction($query_module, $query_action) {

    // Global variables
    include(SERVER_ROOT . '/global/common.globalvar.php');

    // Début de la tamporisation de sortie
    ob_start();

    try {

        if(!empty($query_module)) {
            $module = $query_module;
            if(ctype_alpha($module)) {
                if(array_key_exists($module, $globalMapping)) {
                    $include_module = $globalMapping[$module];
                }
            }
        }

        include SERVER_ROOT . '/modules/' . $include_module . '/mapping.php';

        if(!empty($query_action)) {
            $action = $query_action;
            if(ctype_alpha($action)) {
                if(array_key_exists($action, $moduleMapping[$include_module])) {
                    $action_def = $moduleMapping[$include_module]->$action;
                    $include_action = $action_def->page;          
                }
            }
        }

        GFCommonAuth::checkAcl($action_def->acl);


        include SERVER_ROOT . '/modules/' . $include_module . '/' . $include_action . '.php';  

    } catch(GFExceptionMinor $e) {
        // Minor error
        include SERVER_ROOT . '/modules/' . $e->redirectModule . '/mapping.php';    
        include SERVER_ROOT . '/modules/' . $e->redirectModule . '/' . $e->redirectAction . '.php'; 
    } catch(GFExceptionMajor $e) {
        // Major error
    }

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

}

$mod = "";
$act = "";

if(isset($_GET['module'])) 
{
    $mod = $_GET['module'];
}

if(isset($_GET['action'])) 
{
    $act = $_GET['action'];
}

loadAction($mod, $act);

