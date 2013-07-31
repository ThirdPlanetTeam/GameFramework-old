<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

class GFCommonJavascript {

    private static $_jsfiles = array();
    private static $_jscallback = array();
    private static $_cssfiles = array();

    private static $_glueJS = "<script type='text/javascript' src='js/%s.js'></script>";
    private static $_glueCSS = "<link rel='stylesheet' href='css/%s.css'></link>";
 

    public static function addScript($filename, $amd = true)
    {
    	if (!isset(self::$_jsfiles[$filename])){
			self::$_jsfiles[$filename] = $filename;
		}
    }

    public static function addStyle($filename)
    {
    	if (!in_array($filename, self::$_cssfiles)){
			self::$_cssfiles[] = $filename;
		}
    }  

    public static function addCallback($callback)
    {

        $id = md5($callback);

        if (!isset(self::$_jscallback[$id])){
            self::$_jscallback[$id] = $callback;
        }
    }          

    public static function renderCss()
    {
   	
    	$css = self::$_cssfiles;


    	array_walk($css, function (&$item, $key) {
    		$item = sprintf(self::$_glueCSS, $item);
		});

		echo implode($css);

    }

    public static function renderJavascript()
    {

        $args = array();

        echo "<script type='text/javascript'>" . PHP_EOL . PHP_EOL
            . "curl([
                'js!lib/jquery.js!order',
                'js!lib/bootstrap.js!order',
                '";
        echo implode("','",self::$_jsfiles);  

        foreach (self::$_jsfiles as $file) {
            if($file == 'jquery') {
                $args[] = '$';
            } else {
                $args[] = preg_replace('/[^a-z]/i', '', $file);
            }
        }              
        echo "','domReady!']," .PHP_EOL;

        echo 'function (';
        echo  implode(', ', $args);

        echo ') { ' . PHP_EOL;

        echo implode(PHP_EOL, self::$_jscallback);  

        echo PHP_EOL . '});'. PHP_EOL .'</script>' . PHP_EOL;


    }    

    public static function renderAjax() {

    }


}