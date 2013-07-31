<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

require_once(FRAMEWORK_ROOT . '/modeles/config.php');

class Modeles {
    public $pdo;

    public function __construct() {
        $this->pdo = PDOConnector::getInstance();
    }

    public function __destruct() {
        $this->pdo = null;
    }

    public static function getModel($package, $name) {
    	$classname = ucfirst($name).'Model';
    	include FRAMEWORK_ROOT.'/modeles/'.$package.'/'.$name.'.php';
    	return new $classname();
    }

    public function select($query, $firstline = false) {
        $result = $this->pdo->query($query);

        if($firstline) {
            $lines = $result->fetch(PDO::FETCH_ASSOC);
        } else {
            $lines = $result->fetchAll(PDO::FETCH_ASSOC);
        }

        $result->closeCursor();

        return $lines;
    }
}