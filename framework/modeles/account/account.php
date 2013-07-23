<?php

class AccountModel extends Modeles {

	const TABLE_NAME = 'gf_users';
	const FIELD_USERNAME = 'user_username';
	const FIELD_HASH = 'user_hash';
	const FIELD_DATECREATION = 'user_datecreation';

	public function getUserInfo($username) {

		$usr = $this->pdo->quote($username, PDO::PARAM_STR);

		$result = $this->select('SELECT * FROM ' . AccountModel::TABLE_NAME . ' WHERE ' . AccountModel::FIELD_USERNAME . " LIKE $usr", true);

		return $result;
	}

	public function checkRegister($username, $email) {

	}

	public function registerUser($username, $hash, $email) {
		
	}


}