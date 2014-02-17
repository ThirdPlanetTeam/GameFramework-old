<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

class GFException extends Exception {
	public $redirectModule = DEFAULT_MODULE;
	public $redirectAction = DEFAULT_ACTION;
	public $message = '';

}

class GFExceptionMinor extends GFException {
	
}

class GFExceptionMajor extends GFException {
	
}

class GFExceptionBanned extends GFExceptionMajor {

}

/*class GFErrorDevLog { 

	const logPath = 'log';
	const logFile = 'dev.log';
*/
	/* Magic method, called when instance in invoked as an function
		ex:
		$log = new GFErrorDevLog();
		$log("test"); <- __invoke will be called with "test" as param
	*/
    /*public function __invoke($param)
    {
        GFErrorDevLog::addLog($param);
    }

	public static function addLog($message) {
		$writeable1 = is_writable(GFErrorDevLog::getFilname());
		$writeable2 = is_writable(GFErrorDevLog::getFilepath()) && !file_exists(GFErrorDevLog::getFilname());

		if ($writeable1 || $writeable2) {
			file_put_contents(GFErrorDevLog::getFilname(), $message . PHP_EOL, FILE_APPEND);
		} else {
			trigger_error($message, E_USER_NOTICE);
		}
	}

	public static function getLog($num = 20) {
		if(file_exists(GFErrorDevLog::getFilname())) {
			return GFErrorDevLog::read_file(GFErrorDevLog::getFilname(), $num);
		}
		return '';
	}

	// One time per session, when writing log, delete old log file
	public static function cleanOldLog() {
		// not yet implemented
	}

	// https://gist.github.com/ain/1894692
	private static function read_file($file, $lines) {
    	//global $fsize;
    	$handle = fopen($file, "r");
    	$linecounter = $lines;
    	$pos = -2;
    	$beginning = false;
    	$text = array();
	    while ($linecounter > 0) {
	        $t = " ";
	        while ($t != "\n") {
	            if(fseek($handle, $pos, SEEK_END) == -1) {
	                $beginning = true; 
	                break; 
	            }
	            $t = fgetc($handle);
	            $pos --;
	        }
	        $linecounter --;
	        if ($beginning) {
	            rewind($handle);
	        }
	        $text[$lines-$linecounter-1] = fgets($handle);
	        if ($beginning) break;
	    }
	    fclose ($handle);
	    return array_reverse($text);
	}

	private static function getFilepath()
	{
		return SERVER_ROOT . '/' . GFErrorDevLog::logPath;
	}

    private static function getFilname()
    {
    	return GFErrorDevLog::getFilepath() . '/' . GFErrorDevLog::logFile;
    }	
}*/

/*function exception_handler($e)
{
    echo 'Unknown error';
}
 
set_exception_handler('exception_handler');*/
