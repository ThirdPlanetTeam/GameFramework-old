<?php
 
/**
 * Classe implémentant le singleton pour PDO
 * @author Savageman
 */
 
class PDOConnector extends PDO {
 
    private static $_instance;

    public static $log = null;
 
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
             
                self::sendLog($e);
                //echo $e;
                echo "Database error";
                die();
            }
        }
        return self::$_instance;
    }
    // End of PDOConnector::getInstance() */

    private static function sendLog($message) {
        if(self::$log != null) {
            $log = self::$log;
            $log('PDOConnector: ' . $message);
        }        
    }
}