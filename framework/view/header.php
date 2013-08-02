<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <base href="<?php echo SERVER_URL; ?>">
        <title><?php echo $i18n->getText('site','sitename');  if(isset($pagename)) { echo $i18n->getText('site',$pagename);  } ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <?php GFCommonJavascript::renderCss(); ?>
        <link rel="stylesheet" href="css/style.css">
        <script type='text/javascript'>
            curl = {
                baseUrl: 'js/',
                paths: {    
                    "jquery": "lib/jquery.js",
                    "css": "lib/curl/css.js",
                    "css!": "../css/",
                    "bootstrap": "lib/bootstrap.js"
                }
            }
        </script>
        <script type="text/javascript" src='js/lib/curl.js'></script>

    </head>
    <body>

    <div class="container">

<?php

echo Widgets::Breadcrumbs($query_module, $query_action);