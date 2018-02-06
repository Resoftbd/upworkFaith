<?php
    class TaskManager {
        protected $interactiveMode = false;
        protected $backgroundMode = false;

        public function run(PlacesScanner $scanner) {
            $scanner->setManager($this);
            if ($this->wasRunByShell() || $this->backgroundMode) {
                $this->registerTheTask($scanner);
            }
            if ($this->backgroundMode) {
                $this->setInteractiveMode(false);
                return $this->runInTheBackgroundMode($scanner);
            }
            if (!$scanner->isReady()) {
                return 'The scanner is not ready';
            }
            return $scanner->run();
        }
        
        public function setInteractiveMode($on = true) {
            $this->interactiveMode = $on;
        }
        
        public function sendEvent(PlacesScanner $scanner, $event) {
            $this->showMessage($event."\n");
            if ($scanner->getId()) {
                Database::connect()->prepare("UPDATE tasks SET status = ?, last_pending_time = NOW() WHERE id = ?")->execute(array(
                    $event,
                    $scanner->getId()
                ));
                Database::connect()->prepare("INSERT INTO tasks_log (event, task_id) VALUES (?, ?)")->execute(array(
                    $event,
                    $scanner->getId()
                ));
            }
        }
        public function sendApiEvent(PlacesScanner $scanner, $event) {
            $this->showMessage($event."\n");
            if ($scanner->getId()) {
                Database::connect()->prepare("INSERT INTO tasks_api_log (event, task_id) VALUES (?, ?)")->execute(array(
                    $event,
                    $scanner->getId()
                ));
            }
        }
        
        public function sendProgress(PlacesScanner $scanner, $percentage) {
            $this->showMessage("\033[5D".str_pad($percentage, 3, ' ', STR_PAD_LEFT) . " %".($percentage == 100 ? "\n" : ''));
            if ($scanner->getId()) {
                Database::connect()->prepare("UPDATE tasks SET progress = ?, last_pending_time = NOW() WHERE id = ?")->execute(array($percentage, $scanner->getId()));
                if ($percentage == 100) {
//                     $this->sendEmail($scanner);
                }
            }
        }
        
        public function updateTime(PlacesScanner $scanner) {
            if ($scanner->getId()) {
                Database::connect()->prepare("UPDATE tasks SET last_pending_time = NOW() WHERE id = ?")->execute(array($scanner->getId()));
            }
        }
        
        public function saveFilenames(PlacesScanner $scanner) {
            if ($scanner->getId()) {
                Database::connect()->prepare("UPDATE tasks SET csv_with_pano = ?, csv_without_pano = ?, last_pending_time = NOW() WHERE id = ?")->execute(array(
                    $scanner->getCSVWithPanoFilename(),
                    $scanner->getCSVWithoutPanoFilename(),
                    $scanner->getId()
                ));
            }
        }
        
        public function saveTotalRequests(PlacesScanner $scanner, $total) {
            if ($scanner->getId()) {
                Database::connect()->prepare("UPDATE tasks SET total = ?, last_pending_time = NOW() WHERE id = ?")->execute(array(
                    $total,
                    $scanner->getId()
                ));
            }
        }
        
        public function incrementProcessedRequests(PlacesScanner $scanner) {
            if ($scanner->getId()) {
                Database::connect()->prepare("UPDATE tasks SET processed = processed + 1, last_pending_time = NOW() WHERE id = ?")->execute(array(
                    $scanner->getId()
                ));
            }
        }
        public function incrementFailedRequests(PlacesScanner $scanner) {
            if ($scanner->getId()) {
                Database::connect()->prepare("UPDATE tasks SET failed = failed + 1, last_pending_time = NOW() WHERE id = ?")->execute(array(
                    $scanner->getId()
                ));
            }
        }
        
        public function setBackgroundMode($on = true) {
            $this->backgroundMode = $on;
        }
        
        protected function sendEmail(PlacesScanner $scanner) {
            $stmt = Database::connect()->prepare("SELECT * FROM tasks WHERE id = ?");
            $stmt->execute(array($scanner->getId()));
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data['report_to_email']) {
                return ;
            }
            $subject = 'Scanning process started at '.$data['start_time'].' is completed.';
            $message = "Scanning process '{$data['name']}' started at {$data['start_time']} is completed.\n\n";
            $message .= "Generated files are available by the following URLs:\n.";
            $message .= Url::getCurrent()."/downloadCSV.php?filename=".$scanner->getCSVWithPanoFilename()."\n";
            $message .= Url::getCurrent()."/downloadCSV.php?filename=".$scanner->getCSVWithoutPanoFilename()."\n";

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'To: user <'.$data['report_to_email'].'>' . "\r\n";
            $headers .= 'From: server <ayzaat@gmail.com>' . "\r\n";
            
            mail($data['report_to_email'], $subject, $message, $headers);
        }
        
        protected function showMessage($message) {
            if ($this->interactiveMode) {
                echo $message;
            }
        }
        
        protected function runInTheBackgroundMode(PlacesScanner $scanner) {
            $data = $scanner->getAllPossibleRequests();
            $this->saveTotalRequests($scanner, count($data));
            $this->sendEvent($scanner, 'Starting a scan process over '.count($data).' possible zip codes.');
            foreach ($data as $datum) {
                $opts = array('http' =>
                    array(
                        'method'  => 'POST',
                        'timeout' => 2,
                        'header'  => 'Content-type: application/x-www-form-urlencoded',
                        'content' => http_build_query($datum),
                    )
                );
                $context = stream_context_create($opts);
                file_get_contents(Url::getCurrent().'/index.php', false, $context);
            }
            if (!count($data)) {
                return 'There are no zipcodes for the given location';
            }
        }
        
        protected function registerTheTask(PlacesScanner $scanner) {
            Database::connect()->prepare("INSERT INTO tasks (name, status, report_to_email, processed, start_time) VALUES (?, 'Initialized', ?, 0, NOW())")->execute(array(
            	$scanner->getName(),
            	$scanner->getEmailToReport()
            ));
            $id = Database::connect()->lastInsertId();
            $scanner->setId($id);
            return $id;
        }
        
        public function wasRunByShell() {
            return !isset($_SERVER['SERVER_NAME']);
        }
        
        public function isDone(PlacesScanner $scanner) {
            if ($this->wasRunByShell()) {
                return true;
            }
            if (!$scanner->getId()) {
                return true;
            }
            $stmt = Database::connect()->prepare("SELECT processed, failed, total FROM tasks WHERE id = ?");
            $stmt->execute(array($scanner->getId()));
            $state = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($state['processed'] + $state['failed']) == $state['total'];
        }
    }