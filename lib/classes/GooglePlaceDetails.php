<?php
    class GooglePlaceDetails {
        const URL = 'https://maps.googleapis.com/maps/api/place/details/json';
        protected $reference, $keys, $error;
        
        public function getDetails() {
            foreach ($this->keys as $key) {
                $params = array(
                    'language' => 'en',
                    'reference' => $this->reference,
                    'key' => $key
                );
                $queryString = http_build_query($params);
                $url = self::URL.'?'.$queryString;
                $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
                $jsonData = file_get_contents($url,false,$context);
                $data = json_decode($jsonData);
                if ($data->status == 'OK') {
                    return $data->result;
                }
    	        if (in_array($data->status, array('ZERO_RESULTS', 'INVALID_REQUEST', 'UNKNOWN_ERROR', 'NOT_FOUND'))) {
                    return null;
    	        }
    	        if ($data->error_message) {
                   $this->error = 'Google Place Details API: '.$data->error_message.' '.mb_substr($key, -4);
    	        } else {
                   $this->error = 'Google Place Details API: Cannot process the request. Response status: '.print_r($data, true).' '.mb_substr($key, -4);
    	        }
            }
            return false;
        }
        
        public function setReference($reference) {
            $this->reference = $reference;
        }
        
        public function getError() {
            return $this->error;
        }
        
        public function setKeys($keys) {
            $this->keys = $keys;
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
    }