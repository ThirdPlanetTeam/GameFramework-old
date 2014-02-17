<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

class Modeles {
    public $pdo;

    public static $nb_query = array();

    public function __construct() {
        $this->pdo = PDOConnector::getInstance();
    }

    public function __destruct() {
        $this->pdo = null;
    }

    public static function getModel($package, $name) {
        $classname = self::loadModel($package, $name);
    	return new $classname();
    }

    public static function loadModel($package, $name) {
        $classname = ucfirst($name).'Model';
        include_once FRAMEWORK_ROOT.'/modeles/'.$package.'/'.$name.'.php';   
        return $classname;     
    }

    public function select($query, $firstline = false) {
        $result = $this->pdo->query($query);

        if($firstline) {
            $lines = $result->fetch(PDO::FETCH_ASSOC);
        } else {
            $lines = $result->fetchAll(PDO::FETCH_ASSOC);
        }

        $result->closeCursor();

        Modeles::$nb_query[] = $query;

        return $lines;
    }
}