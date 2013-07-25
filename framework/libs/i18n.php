<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

define('LANG_DIR', SERVER_ROOT . '/i18n/');
define('LANG_FALLBACK', 'en'); // Lang when requested lang doesn't exists

class i18n {

	private $lang;
	private $lang_array;

	public static function getLangCode() {
		//fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4
		$array = preg_split('/,/i', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$lang = array();
		
		foreach($array as $item) {
			$q = preg_split('/;/i', $item);
			$lang[] = $q[0];
		}
		
		return $lang;
	}
	
	public function __construct($requested_lang = 'en') {
		
		$this->lang = LANG_FALLBACK;		
		
		if(is_array($requested_lang)) {
			foreach($requested_lang as $lang) {
				$result = $this->selectLang($lang);

				if($result) {
					break;
				}
			}
		} else {
			$lang = strtolower($requested_lang);
			$this->selectLang($lang);
		}

		$this->lang_array = parse_ini_file(LANG_DIR . $this->lang . '.ini', true);	

	}
	
	public function selectLang($lang) {
	
		$lang = strtolower($lang);
	
		if(preg_match('/^[a-z_-]{2,8}$/i', $lang) == 1) {
			if(file_exists(LANG_DIR . $lang . '.ini')) {
				$this->lang = $lang;
				return true;
			} else {
				preg_match('/^([a-z]{2,8})[_-]?/i', $lang, $result);
				
				if(isset($result[1])) {
					if(file_exists(LANG_DIR . $result[1] . '.ini')) {
						$this->lang = $result[1];
						return true;
					}
				}
			}
		
		}
		
		return false;
		

	}
	
	public function getText($cat,$id,$param = array()) {
		if(isset($this->lang_array[$cat][$id])) {
			
			$var = array_map(array('i18n','wrapVar'), array_keys($param));
			
			$text = preg_replace($var,$param,$this->lang_array[$cat][$id]);
			
			return $text;
		} else {
			return $cat.'-'.$id;
		}
	}

	public static function wrapVar($var) {
		return '/{' . $var . '}/';
	}
	
}



?>