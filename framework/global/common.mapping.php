<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

class GFCommonMapping {
	public $page;
	public $acl;
	public $context;
	public $headerView;
	public $footerView;
	public $inMenu;

	const DefaultPage = 'default';
	const DefaultHeader = 'view/header.php';
	const DefaultFooter = 'view/footer.php';	

	const ContextPage = 'page';
	const ContextAjax = 'ajax';



	public function __construct($arg1 = null, $acl = null, $context = null, $headerView = null, $footerView = null, $menu = false) {
		if(is_array($arg1)) {
			// named arguments
			$this->page = (array_key_exists("page", $arg1)) 				? $arg1["page"] : GFCommonMapping::DefaultPage;
			$this->acl = (array_key_exists("acl", $arg1)) 					? $arg1["acl"] : GFCommonAuth::Anyone;
			$this->context = (array_key_exists("context", $arg1)) 			? $arg1["context"] : GFCommonMapping::ContextPage;
			$this->headerView = (array_key_exists("headerView", $arg1)) 	? $arg1["headerView"] : GFCommonMapping::DefaultHeader;
			$this->footerViewView = (array_key_exists("footerView", $arg1)) ? $arg1["footerView"] : GFCommonMapping::DefaultFooter;
			$this->inMenu = (array_key_exists("menu", $arg1)) 				? $arg1["menu"] : false;
		} else {
			// Old method
			$this->page = (!is_null($arg1)) ? $arg1 : GFCommonMapping::DefaultPage;
			$this->acl = (!is_null($acl)) ? $acl : GFCommonAuth::Anyone;
			$this->context = (!is_null($context)) ? $context : GFCommonMapping::ContextPage;
			$this->headerView = (!is_null($headerView)) ? $headerView : GFCommonMapping::DefaultHeader;
			$this->footerViewView = (!is_null($footerView)) ? $footerView : GFCommonMapping::DefaultFooter;
			$this->inMenu = (!is_null($menu)) ? $menu : false;
		}
	}
}