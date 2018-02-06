<?php
    class CreateCSV {
        protected $db;
        
        public function setDb($db) {
            $this->db = $db;
        }
        public function getPlacesWithPanoStatement($id) {
            $stmt = $this->db->prepare("SELECT * FROM places WHERE task_id = ? AND has_pano = ?");
            $stmt->execute(array($id, 1));
            return $stmt;
        }
        
        public function getPlacesWithoutPanoStatement($id) {
            $stmt = $this->db->prepare("SELECT * FROM places WHERE task_id = ? AND has_pano = ?");
            $stmt->execute(array($id, 0));
            return $stmt;
        }
        public function getPlacesWithPanoCount($id) {
            $stmt = $this->db->prepare("SELECT COUNT(1) FROM places WHERE task_id = ? AND has_pano = ?");
            $stmt->execute(array($id, 1));
            return $stmt->fetchColumn();
        }
        
        public function getPlacesWithoutPanoCount($id) {
            $stmt = $this->db->prepare("SELECT COUNT(1) FROM places WHERE task_id = ? AND has_pano = ?");
            $stmt->execute(array($id, 0));
            return $stmt->fetchColumn();
        }
        public function exportPlaces($id, $filenameWithPano, $filenameWithoutPano) {
            $placesWithPanoStmt = $this->getPlacesWithPanoStatement($id);
            $placesWithoutPanoStmt = $this->getPlacesWithoutPanoStatement($id);
            
            $CSVExporter = new CSVExporter();
            $CSVExporter->export($placesWithPanoStmt, $filenameWithPano);
            $CSVExporter->export($placesWithoutPanoStmt, $filenameWithoutPano);
            $this->updateTask($id,$filenameWithPano,$filenameWithoutPano);
            $this->finish($id);

        }
        public function updateTask($id, $filenameWithPano, $filenameWithoutPano){
            $stmt = $this->db->prepare("UPDATE tasks SET `csv_with_pano` = ?, `csv_without_pano` = ? WHERE id = ?");
            $stmt->execute(array($filenameWithPano, $filenameWithoutPano,$id));
        }
        public function finish($id){
            $numberOfPlacesWithPano = $this->getPlacesWithPanoCount($id);
            $numberOfPlacesWithoutPano = $this->getPlacesWithoutPanoCount($id);
            $total = $numberOfPlacesWithPano + $numberOfPlacesWithoutPano;
            
            $this->sendEvent($id,$total.' places filtered.');
            $this->sendEvent($id,$numberOfPlacesWithPano.' places with panorama');
            $this->sendEvent($id,$numberOfPlacesWithoutPano.' places without panorama');
            $this->sendEvent($id,'Done CSV Generated' );
            $this->deletePlaces($id);
            $this->sendEvent($id,'Places deleted' );
        }
        public function sendEvent($id, $event) {
            if ($id) {
                $this->db->prepare("UPDATE tasks SET status = ?, last_pending_time = NOW() WHERE id = ?")->execute(array(
                    $event,
                    $id
                ));
                $this->db->prepare("INSERT INTO tasks_log (event, task_id) VALUES (?, ?)")->execute(array(
                    $event,
                    $id
                ));
            }
        }
        public function deletePlaces($id){
            $stmt = $this->db->prepare("DELETE FROM `places` WHERE `task_id`= ?");
            $stmt->execute(array($id));
        }
    }