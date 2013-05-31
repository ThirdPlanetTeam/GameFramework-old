<?php

class GFException extends Exception {
	public $redirectModule = DEFAULT_MODULE;
	public $redirectAction = DEFAULT_ACTION;

}

class GFExceptionMinor extends GFException {
	
}

class GFExceptionMajor extends GFException {
	
}

