<?php
    class PlaceMapper {
        protected $db;

        public function __construct() {
            $this->db = Database::connect();
        }

        public function getPlacesStatement(PlacesScanner $scanner) {
            $stmt = $this->db->prepare("SELECT * FROM places WHERE task_id = ?");
            $stmt->execute(array($scanner->getId()));
            return $stmt;
        }

        public function getPlacesWithPanoStatement(PlacesScanner $scanner) {
            $stmt = $this->db->prepare("SELECT * FROM places WHERE task_id = ? AND has_pano = ?");
            $stmt->execute(array($scanner->getId(), 1));
            return $stmt;
        }

        public function getPlacesWithoutPanoStatement(PlacesScanner $scanner) {
            $stmt = $this->db->prepare("SELECT * FROM places WHERE task_id = ? AND has_pano = ?");
            $stmt->execute(array($scanner->getId(), 0));
            return $stmt;
        }

        public function addPlace(PlacesScanner $scanner, $place) {
            $this->db->prepare("INSERT INTO places (task_id, place_id, data, has_pano, searching_city, searching_zipcode) VALUES (?, ?, ?, ?, ?, ?)")
                ->execute(array(
                        $scanner->getId(),
                        $place->place_id,
                        serialize($place),
                        (isset($place->imagery_type) && ($place->imagery_type != "") && ($place->imagery_type == 2))? 1 : 0,
                        $scanner->getCity(),
                        ($scanner->getZipcode() == 'NOZIPCODE' ? '' : $scanner->getZipcode())
                ));
        }

        public function updatePlace(PlacesScanner $scanner, $place) {
            $this->db->prepare("UPDATE places SET data = ?, has_pano = ? WHERE place_id = ?")
                ->execute(array(serialize($place), (isset($place->imagery_type) && $place->imagery_type == 2) ? 1 : 0, $place->place_id));
        }

        public function getPlacesWithPanoCount(PlacesScanner $scanner) {
            $stmt = $this->db->prepare("SELECT COUNT(1) FROM places WHERE task_id = ? AND has_pano = ?");
            $stmt->execute(array($scanner->getId(), 1));
            return $stmt->fetchColumn();
        }

        public function getPlacesWithoutPanoCount(PlacesScanner $scanner) {
            $stmt = $this->db->prepare("SELECT COUNT(1) FROM places WHERE task_id = ? AND has_pano = ?");
            $stmt->execute(array($scanner->getId(), 0));
            return $stmt->fetchColumn();
        }

        public function deletePlace($placeId) {
            $this->db->prepare("DELETE FROM places WHERE place_id = ?")->execute(array($placeId));
        }

        public function clear(PlacesScanner $scanner) {
            $this->db->prepare("DELETE FROM places WHERE task_id = ?")->execute(array($scanner->getId()));
        }
    }
