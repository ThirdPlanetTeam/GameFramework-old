<?php

class GFCommonJavascript {

    private static $_jsfiles = array();
    private static $_cssfiles = array();

    const ScopeCore = 1;
    const ScopeLib = 2;
    const ScopeScript = 3;
    const ScopeInline = 4;

    private static $_glueJS = "<script type='text/javascript' src='js/%s.js'></script>";
    private static $_glueCSS = "<link rel='stylesheet' href='css/%s.css'></link>";
 

    public static function addScript($filename, $scope)
    {
    	if (!isset(self::$_jsfiles[$filename])) {
			self::$_jsfiles[$filename] = ['scope' => $scope, 'file' => $filename ];
		}
    }

    public static function addStyle($filename)
    {
    	if (!in_array($filename, self::$_cssfiles)){
			self::$_cssfiles[] = $filename;
		}
    }    

    private static function prepare()
    {

		$call = array();

    	foreach (self::$_jsfiles as $file) {
    		$k = $file['scope'];
    		$call[$k][] = $file['file'];
    	}    	

    	return $call;
    }

    public static function renderHeader()
    {
    	$call = GFCommonJavascript::prepare();
    	
    	$css = self::$_cssfiles;


    	array_walk($css, function (&$item, $key) {
    		$item = sprintf(self::$_glueCSS, $item);
		});

		echo implode($css);

    	if(isset($call[GFCommonJavascript::ScopeCore])) {
    		$call = $call[GFCommonJavascript::ScopeCore];

	    	array_walk($call, function (&$item, $key) {
	    		$item = sprintf(self::$_glueJS, $item);
			});
			echo implode($call);
    	}


		


    }

    public static function renderFooter()
    {
    	$call = GFCommonJavascript::prepare();
	
    	unset($call[GFCommonJavascript::ScopeCore]);



    	foreach($call as $scope => $files) {

    		if($scope < GFCommonJavascript::ScopeInline) {

		    	array_walk($files, function (&$item, $key) {
		    		$item = sprintf(self::$_glueJS, $item);
				});
		    }

			echo implode($files);

    	}


    }    


}