<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

class GFException extends Exception {
	public $redirectModule = DEFAULT_MODULE;
	public $redirectAction = DEFAULT_ACTION;

}

class GFExceptionMinor extends GFException {
	
}

class GFExceptionMajor extends GFException {
	
}

