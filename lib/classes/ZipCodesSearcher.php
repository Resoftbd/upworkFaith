<?php
    class ZipCodesSearcher {
        public function getZipcodesByCity($city) {
            $stmt = Database::connect()->prepare("SELECT postal_code FROM postal_codes WHERE place_name LIKE CONCAT(?, '%')");
            $stmt->execute(array($city));
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        
        public function getZipcodesByCityAndState($city, $state) {
            $stmt = Database::connect()->prepare("SELECT postal_code FROM postal_codes WHERE place_name LIKE CONCAT(?, '%') AND admin_name1 LIKE CONCAT(?, '%')");
            $stmt->execute(array($city, $state));
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        
        public function getZipcodesByCityAndStateAndCountry($city, $state, $country) {
            $stmt = Database::connect()->prepare("SELECT postal_code FROM postal_codes WHERE place_name LIKE CONCAT(?, '%') AND admin_name1 LIKE CONCAT(?, '%') AND country_code = ?");
            $stmt->execute(array($city, $state, Country::getCountryCode($country)));
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        
        public function getZipcodesByCityAndCountry($city, $country) {
            $stmt = Database::connect()->prepare("SELECT postal_code FROM postal_codes WHERE place_name LIKE CONCAT(?, '%') AND country_code = ?");
            $stmt->execute(array($city, Country::getCountryCode($country)));
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        
        public function getZipcodesByState($state) {
            $stmt = Database::connect()->prepare("SELECT postal_code FROM postal_codes WHERE admin_name1 LIKE CONCAT(?, '%')");
            $stmt->execute(array($state));
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        
        public function getZipcodesByStateAndCountry($state, $country) {
            $stmt = Database::connect()->prepare("SELECT postal_code FROM postal_codes WHERE admin_name1 LIKE CONCAT(?, '%') AND country_code = ?");
            $stmt->execute(array($state, Country::getCountryCode($country)));
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        
        public function getZipcodesByCountry($country) {
            $stmt = Database::connect()->prepare("SELECT postal_code FROM postal_codes WHERE country_code = ?");
            $stmt->execute(array(Country::getCountryCode($country)));
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
    }