<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

class GFCommonLog { 

	const logPath = 'log';
	const logFile = 'dev.log';

	private $writeable = false;

	public function __construct() {

        if (!is_dir($this->getFilepath())) 
        { 
        	mkdir($this->getFilepath(),0705); 
        	chmod($this->getFilepath(),0705); 
        }		

		$writeable1 = is_writable($this->getFilname());
		$writeable2 = is_writable($this->getFilepath()) && !file_exists($this->getFilname());

		$this->writeable = ($writeable1 || $writeable2);
	}

	/* Magic method, called when instance in invoked as an function
		ex:
		$log = new self();
		$log("test"); <- __invoke will be called with "test" as param
	*/
    public function __invoke($param)
    {
        $this->addLog($param);
    }

	public function addLog($message) {
		$writeable1 = is_writable($this->getFilname());
		$writeable2 = is_writable($this->getFilepath()) && !file_exists($this->getFilname());

		if ($writeable1 || $writeable2) {
			file_put_contents($this->getFilname(), $message . PHP_EOL, FILE_APPEND);
		} else {
			trigger_error($message, E_USER_NOTICE);
		}
	}

	public function getLog($num = 20) {
		if(file_exists($this->getFilname())) {
			return $this->read_file($this->getFilname(), $num);
		}
		return '';
	}

	// One time per session, when writing log, delete old log file
	public function cleanOldLog() {
		// not yet implemented
	}

	// https://gist.github.com/ain/1894692
	private function read_file($file, $lines) {
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

	private function getFilepath()
	{
		return SERVER_ROOT . '/' . self::logPath;
	}

    private function getFilname()
    {
    	return $this->getFilepath() . '/' . self::logFile;
    }	
}