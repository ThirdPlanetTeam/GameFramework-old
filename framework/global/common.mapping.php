<?php

class GFCommonMapping {
	public $page;
	public $acl;
	public $context;
	public $headerView;
	public $footerView;

	const DefaultPage = 'default';
	const DefaultHeader = 'view/header.php';
	const DefaultFooter = 'view/footer.php';	

	const ContextPage = 'page';
	const ContextAjax = 'ajax';



	public function __construct($page = GFCommonMapping::DefaultPage, 
								$acl = GFCommonAuth::Anyone, 
								$context = GFCommonMapping::ContextPage, 
								$headerView = GFCommonMapping::DefaultHeader, 
								$footerView = GFCommonMapping::DefaultFooter) {
		$this->page = $page;
		$this->acl = $acl;
		$this->context = $context;
		$this->headerView = $headerView;
		$this->footerViewView = $footerView;
	}
}