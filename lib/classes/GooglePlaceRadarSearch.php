<?php
    class GooglePlaceRadarSearch {
        const URL = 'https://maps.googleapis.com/maps/api/place/radarsearch/json';
        
        protected $keys, $location, $type, $radius, $address, $error;
        
        public function getPlaces() {
            foreach ($this->keys as $key) {
    		    $params = array(
    		    	'location' => $this->location,
    		        'radius' => $this->radius,
    		        'types' => $this->type,
    		        'keyword' => $this->type.' '.$this->address,
    		        'key' => $key
    		    );
    		    $queryString = http_build_query($params);
                $url = self::URL.'?'.$queryString;
                $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
    		    $jsonData = file_get_contents($url,false,$context);
    		    $data = json_decode($jsonData);
    		    if ($data->status == 'OK') {
    		        return $data->results;
    		    }
		        if (in_array($data->status, array('ZERO_RESULTS', 'INVALID_REQUEST', 'NOT_FOUND'))) {
                    return array();
		        }
		        if ($data->error_message) {
	               $this->error = 'Google Place RadarSearch API: '.$data->error_message.' '.mb_substr($key, -4);
		        } else {
	               $this->error = 'Google Place RadarSearch API: Cannot process the request: '.print_r($data, true).' '.mb_substr($key, -4);
		        }
            }
            return false;
        }
        /*public function file_get_contents_curl($url) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

            $data = curl_exec($ch);
            curl_close($ch);

            return $data;
        }*/
        
        public function setLocation($location) {
            $this->location = $location;
        }
        
        public function setType($type) {
            $this->type = $type;
        }
        
        public function setRadius($radius) {
            $this->radius = $radius;
        }
        
        public function setAddress($address) {
            $this->address = $address;
        }
        
        public function getError() {
            return $this->error;
        }
        
        public function setKeys($keys) {
            $this->keys = $keys;
        }
    }