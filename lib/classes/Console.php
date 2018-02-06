<?php
class Console {
    static function showMessageAndDie($message) {
        echo "$message\n";
        die();
    }
    
    static function showErrorAndDie($message) {
        self::showMessageAndDie("ERROR: ".$message);
    }
}