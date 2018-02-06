<?php
    class Settings {
        public static function get($key) {
            $stmt = Database::connect()->prepare("SELECT `value` FROM settings WHERE `key` = ?");
            $stmt->execute(array($key));
            return $stmt->fetchColumn();
        }
        
        public static function getAllGoogleAPIKeys() {
            return Database::connect()->query("SELECT `value` FROM settings WHERE `key` = 'google_api_key'")->fetchAll(PDO::FETCH_COLUMN);
        }
        
        public static function saveAllGoogleAPIKeys($keys) {
            Database::connect()->query("DELETE FROM settings WHERE `key` = 'google_api_key'");
            foreach ($keys as $key) {
                Database::connect()->prepare("INSERT INTO settings (`key`, `value`) VALUES ('google_api_key', ?)")->execute(array($key));
            }
        }
        
        public static function set($key, $value) {
            Database::connect()->prepare('UPDATE settings SET `value` = ? WHERE `key` = ?')->execute(array($value, $key));
        }
    }