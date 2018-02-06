<?php
    class Database {
        private static $dbh = null;
        
        static function connect() {
            if(self::$dbh == null){
                try {
                    self::$dbh = new PDO(MYSQL_DSN, MYSQL_USER, MYSQL_PASSWORD);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    return 'Cannot connect to database: ' . $e->getMessage();
                }
            }
            return self::$dbh;
        } 
    }