<?php
 
/**
 * Classe implémentant le singleton pour PDO
 * @author Savageman
 */
 
class PDOConnector extends PDO {
 
    private static $_instance;
 
    /* Constructeur : héritage public obligatoire par héritage de PDO */
    public function __construct( ) {
     
    }
    // End of PDOConnector::__construct() */
 
    /* Singleton */
    public static function getInstance() {
     
        if (!isset(self::$_instance)) {
             
            try {
             
                self::$_instance = new PDO(SQL_DSN, SQL_USERNAME, SQL_PASSWORD);
             
            } catch (PDOException $e) {
             
                //echo $e;
                echo "Database error";
                die();
            }
        }
        return self::$_instance;
    }
    // End of PDOConnector::getInstance() */
}