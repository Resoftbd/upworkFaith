<?php
    class GooglePanoramaScraper {
        const PANO_ID_URL = 'http://maps.google.com/cbk';
        public $panoid;
        public $foundSeeInside;
        public function fetchPanoramaData($place) {
            $this->foundSeeInside=false;
            $this->fetchPanoramaUrlAndImage($place);
            if (!$this->foundSeeInside) {
                return;
            }
            $this->fetchPanoramaIdAndDate($place);
        }

        public function fetchPanoramaUrlAndImage($place) {
            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->strictErrorChecking = false;
            $doc->loadHTMLFile($place->url);
            $mystring = $doc->textContent;

            unset($doc);
            $pos2 = strpos($mystring, 'panoid=');
            $posf = strpos($mystring,'","',$pos2);
            if($pos2 > 0){
                $pos1 = strpos($mystring, '//geo');
                $startIndex = min($pos1, $posf);
                $length = abs($pos1 - $posf);
                $between = substr($mystring, $startIndex, $length);
                unset($mystring);
                $pos1 = strpos($between, 'panoid=')+7;
                $pos2 = strpos($between, '\u0026');
                $startIndex = min($pos1, $pos2);
                $length = abs($pos1 - $pos2);

                $this->panoid= substr($between, $startIndex, $length);
                $place->panoramaUrl = str_replace('\u0026','&',$between);
                $place->panoramaId=$this->panoid;
                $this->foundSeeInside=true;
            }else{
               $this->foundSeeInside=false;
            }


        }

        public function fetchPanoramaIdAndDate($place) {
            global $urlFromJs;
            if(isset($place->panoramaId) && $place->panoramaId != ""){
                $params = array(
                	'output' => 'json',
                    'panoid'=>$place->panoramaId
                );
                $context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
                $jsonData = file_get_contents(self::PANO_ID_URL.'?output=json&panoid='.$place->panoramaId,false,$context);
                $data = json_decode($jsonData);

                if(isset($data) && ($data!= "{}") && $data->Data->imagery_type == 2 )
                       {
                    $place->panoramaDate = $data->Data->image_date;
                    $place->imagery_type = $data->Data->imagery_type;

                }
                $place->panoramaPreview="https://maps.google.com/maps/@".$place->geometry->location->lat.",".$place->geometry->location->lng.",0a,73.7y,270h,90t/data=!3m4!1e1!3m2!1s".$place->panoramaId."!2e0?source=apiv3";
            }
        }

        public function file_get_contents_curl($url) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

            $data = curl_exec($ch);
            curl_close($ch);

            return $data;
        }
}
?>
