<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
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

/*function exception_handler($e)
{
    echo 'Unknown error';
}
 
set_exception_handler('exception_handler');*/
