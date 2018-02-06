<?php
    class CSVExporter {
        public function export(PDOStatement $placesStmt, $filename) {
            /*if(strlen($filename)>75){
                $filename = substr($filename,0,75);
            }*/
            $fp = fopen(OUTPUT_DIR.'/'.$filename, 'w');
            $columnNames = array(
                'Name of business',
                'Phone number',
                'Address',
                'City',
                'State',
                'Zipcode',
                'Country',
                'Website',
                'Panoram Id',
                'Google Pano Page',
                'Google Embedded',
                'Google Place URL',
                'SEE INSIDE image',
                'Category',
                'Longitude',
                'Latitude',
                'Date of pano',
                'Publisher name',
                'Website of',
                'Image Type'
            );
            fputcsv($fp, $columnNames);
            
            while ($placeData = $placesStmt->fetch(PDO::FETCH_ASSOC)) {
                $place = unserialize($placeData['data']);
                $fields = array(
                    $place->name,
                    $place->international_phone_number,
                    $place->formatted_address,
                    $place->address_components[2]->long_name,
                    $place->address_components[3]->long_name,
                    $place->address_components[5]->long_name,
                    $place->address_components[4]->long_name,
                    $place->website,
                    isset($place->panoramaId)?$place->panoramaId:"",
                    $place->panoramaUrl,
                    $this->getEmbedFor($place),
                    $place->url,
                    $place->panoramaPreview,
                    implode('|', (array)$place->types),
                    $place->geometry->location->lng,
                    $place->geometry->location->lat,
                    $place->panoramaDate,
                    '',
                    $place->website,
                    $place->imagery_type
                );
                fputcsv($fp, $fields);
            }
            
            fclose($fp);
        }
        
        protected function getEmbedFor($place) {
            if (!$place->panoramaUrl) {
                return '';
            }
            return '<iframe width="800" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps/sv?cbp=0,0.1,0,0,0&panoid='.$place->panoramaId.'"></iframe>';
        }
    }