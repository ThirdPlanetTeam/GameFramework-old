<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

class AccountModel extends Modeles {

	const TABLE_NAME = 'gf_users';
	const FIELD_USERNAME = 'user_username';
	const FIELD_HASH = 'user_hash';
	const FIELD_SALT = 'user_salt';
	const FIELD_EMAIL = 'user_email';
	const FIELD_CREATION = 'user_datecreation';
	const FIELD_VALIDATION = 'user_validationcode';

	public function getUserInfo($username) {

		$usr = $this->pdo->quote($username, PDO::PARAM_STR);

		$result = $this->select('SELECT * FROM ' . AccountModel::TABLE_NAME . ' WHERE ' . AccountModel::FIELD_USERNAME . " LIKE $usr", true);

		return $result;
	}

	public function checkRegister($username, $email) {
		$usr = $this->pdo->quote($username, PDO::PARAM_STR);
		$mail = $this->pdo->quote($email, PDO::PARAM_STR);

		$result = $this->select('SELECT * FROM ' . AccountModel::TABLE_NAME . ' WHERE ' . AccountModel::FIELD_USERNAME . " LIKE $usr OR " . AccountModel::FIELD_EMAIL . " LIKE $mail");

		return $result;		
	}

	public function registerUser($username, $hash, $salt, $email, $validation) {
		
		$query = 'INSERT INTO ' . AccountModel::TABLE_NAME . ' (
			'.AccountModel::FIELD_USERNAME.', 
			'.AccountModel::FIELD_HASH.', 
			'.AccountModel::FIELD_SALT.', 
			'.AccountModel::FIELD_EMAIL.', 
			'.AccountModel::FIELD_CREATION.', 
			'.AccountModel::FIELD_VALIDATION.') 
			VALUES (:user, :hash, :salt, :email, NOW(), :validation)';

		$stat = $this->pdo->prepare($query);

		$stat->bindParam(':user', $username, PDO::PARAM_STR);
		$stat->bindParam(':hash', $hash, PDO::PARAM_STR);
		$stat->bindParam(':salt', $salt, PDO::PARAM_STR);
		$stat->bindParam(':email', $email, PDO::PARAM_STR);
		$stat->bindParam(':validation', $validation, PDO::PARAM_STR);

		$stat->execute();
	}


}