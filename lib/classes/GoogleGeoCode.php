<?php
    class GoogleGeoCode {
        const URL = 'https://maps.googleapis.com/maps/api/geocode/json';
        protected $keys, $error;
        
        public function getGeoCodeFor($address) {
            foreach ($this->keys as $key) {
                $params = array(
                    'address' => $address,
                    'key' => $key
                );
                $queryString = http_build_query($params);
                $jsonData = file_get_contents(self::URL.'?'.$queryString);
                $data = json_decode($jsonData);
                if ($data->status == 'OK') {
                    return count($data->results) ? $data->results[0] : null;
                }
    	        if (in_array($data->status, array('ZERO_RESULTS', 'INVALID_REQUEST', 'UNKNOWN_ERROR', 'NOT_FOUND'))) {
                    return array();
    	        }
    	        if ($data->error_message) {
                   $this->error = 'Google GeoCode API: '.$data->error_message.' '.mb_substr($key, -4);
    	        } else {
                   $this->error = 'Google GeoCode API: Cannot process the request: '.' '.mb_substr($key, -4);
    	        }
            }
            return false;
        }
        
        public function setKeys($keys) {
            $this->keys = $keys;
        }
        
        public function getError() {
            return $this->error;
        }
    }