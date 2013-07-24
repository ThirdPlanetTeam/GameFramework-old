<?php



class GFCommon {

	const URL_REWRITE = false;

	public static function formatLink($module, $action, $querystring = '') {
		if(GFCommon::URL_REWRITE) {
			return $module.'/'.$action.'/?'.$querystring;
		} else {
			if($querystring != '') {
				$querystring = '&'.$querystring;
			}
			return '?module='.$module.'&action='.$action.$querystring;
		}
	}
}