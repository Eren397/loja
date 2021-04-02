<?php
abstract  class Connection {
    private static $conn;

    public static function getConn() {
        try {
            self::$conn = new PDO('mysql:dbname=MOOBITOY;host=localhost', 'root', 'root123');
        }catch(PDOException $e) {
            echo 'Erro no bd '.$e->getMessage();
        }

        return self::$conn;
        
    }

}