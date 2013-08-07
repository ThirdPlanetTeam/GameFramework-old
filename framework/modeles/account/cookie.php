<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

class CookieModel extends Modeles {

	const TABLE_NAME = 'gf_cookies';
	const FIELD_USEID = 'cookie_userid';
	const FIELD_HASH = 'cookie_hash';
	const FIELD_IP = 'cookie_ip';
	const FIELD_LOCATION = 'cookie_location';
	const FIELD_BROWSERINFO = 'cookie_browser';
	const FIELD_DATECREATION = 'cookie_datecreation';
	const FIELD_DATELASTLOGIN = 'cookie_lastlogin';
	const FIELD_DATEENDING = 'cookie_dateending';

	public function rewriteCookie($old_hash, $new_hash, $end) {
		$query = 'UPDATE ' . CookieModel::TABLE_NAME . ' SET 
		'.CookieModel::FIELD_HASH.' = :newhash,
		'.CookieModel::FIELD_DATELASTLOGIN.' = NOW(),
		'.CookieModel::FIELD_DATEENDING.' = :end
		WHERE '.CookieModel::FIELD_HASH.' = :oldhash LIMIT 1';

		$stat = $this->pdo->prepare($query);

		$stat->bindParam(':newhash', $new_hash, PDO::PARAM_STR);
		$stat->bindParam(':oldhash', $old_hash, PDO::PARAM_STR);
		$stat->bindParam(':end', $end, PDO::PARAM_STR);

		Modeles::$nb_query[] = $query;

		$stat->execute();
	}

	public function writeCookie($user_id, $hash, $browser, $ip, $geo, $end) {

		$query = 'INSERT INTO ' . CookieModel::TABLE_NAME . ' (
			'.CookieModel::FIELD_USEID.', 
			'.CookieModel::FIELD_HASH.', 
			'.CookieModel::FIELD_IP.', 
			'.CookieModel::FIELD_LOCATION.', 
			'.CookieModel::FIELD_BROWSERINFO.', 
			'.CookieModel::FIELD_DATECREATION.', 
			'.CookieModel::FIELD_DATELASTLOGIN.', 
			'.CookieModel::FIELD_DATEENDING.') 
			VALUES (:uid, :hash, :ip, :geo, :browser, NOW(), NULL, :end)';

		$stat = $this->pdo->prepare($query);

		$stat->bindParam(':uid', $user_id, PDO::PARAM_STR);
		$stat->bindParam(':hash', $hash, PDO::PARAM_STR);
		$stat->bindParam(':ip', $ip, PDO::PARAM_STR);
		$stat->bindParam(':geo', $geo, PDO::PARAM_STR);
		$stat->bindParam(':browser', $browser, PDO::PARAM_STR);
		$stat->bindParam(':end', $end, PDO::PARAM_STR);


		Modeles::$nb_query[] = $query;

		$stat->execute();
	}

	public function deleteCookie($hash) {
		$hash = $this->pdo->quote($hash, PDO::PARAM_STR);

		$query = "DELETE FROM " . CookieModel::TABLE_NAME . " WHERE ".CookieModel::FIELD_HASH." =  $hash LIMIT 1";
		$this->pdo->exec($query);

		Modeles::$nb_query[] = $query;
	}

	public function getCookie($hash) {
		$hash = $this->pdo->quote($hash, PDO::PARAM_STR);

		$result = $this->select('SELECT SQL_CACHE * FROM ' . CookieModel::TABLE_NAME . ' WHERE ' . CookieModel::FIELD_HASH . " LIKE $hash", true);

		return $result;
	}
}