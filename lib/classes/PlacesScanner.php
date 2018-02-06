<?php
    class PlacesScanner {
        protected $country;
        protected $state;
        protected $city;
        protected $zipcode;
        protected $filter;
        protected $types;
        protected $manager;
        protected $id;
        protected $emailToReport;
        protected $CSVWithPanofilename;
        protected $CSVWithoutPanofilename;
        protected $auto;

        protected $placeMapper;

        protected $error;

        public static $availableTypes = array(
            'accounting' => 'Accounting',
            'airport' => 'Airport',
            'amusement_park' => 'Amusement Park',
            'aquarium' => 'Aquarium',
            'art_gallery' => 'Art Gallery',
            'atm' => 'Atm',
            'bakery' => 'Bakery',
            'bank' => 'Bank',
            'bar' => 'Bar',
            'beauty_salon' => 'Beauty Salon',
            'bicycle_store' => 'Bicycle Store',
            'book_store' => 'Book Store',
            'bowling_alley' => 'Bowling Alley',
            'bus_station' => 'Bus Station',
            'cafe' => 'Cafe',
            'campground' => 'Campground',
            'car_dealer' => 'Car Dealer',
            'car_rental' => 'Car Rental',
            'car_repair' => 'Car Repair',
            'car_wash' => 'Car Wash',
            'casino' => 'Casino',
            'cemetery' => 'Cemetery',
            'church' => 'Church',
            'city_hall' => 'City Hall',
            'clothing_store' => 'Clothing Store',
            'convenience_store' => 'Convenience Store',
            'courthouse' => 'Courthouse',
            'dentist' => 'Dentist',
            'department_store' => 'Department Store',
            'doctor' => 'Doctor',
            'electrician' => 'Electrician',
            'electronics_store' => 'Electronics Store',
            'embassy' => 'Embassy',
            'fire_station' => 'Fire Station',
            'florist' => 'Florist',
            'funeral_home' => 'Funeral Home',
            'furniture_store' => 'Furniture Store',
            'gas_station' => 'Gas Station',
            'gym' => 'Gym',
            'hair_care' => 'Hair Care',
            'hardware_store' => 'Hardware Store',
            'hindu_temple' => 'Hindu Temple',
            'home_goods_store' => 'Home Goods Store',
            'hospital' => 'Hospital',
            'insurance_agency' => 'Insurance Agency',
            'jewelry_store' => 'Jewelry Store',
            'laundry' => 'Laundry',
            'lawyer' => 'Lawyer',
            'library' => 'Library',
            'liquor_store' => 'Liquor Store',
            'local_government_office' => 'Local Government Office',
            'locksmith' => 'Locksmith',
            'lodging' => 'Lodging',
            'meal_delivery' => 'Meal Delivery',
            'meal_takeaway' => 'Meal Takeaway',
            'mosque' => 'Mosque',
            'movie_rental' => 'Movie Rental',
            'movie_theater' => 'Movie Theater',
            'moving_company' => 'Moving Company',
            'museum' => 'Museum',
            'night_club' => 'Night Club',
            'painter' => 'Painter',
            'park' => 'Park',
            'parking' => 'Parking',
            'pet_store' => 'Pet Store',
            'pharmacy' => 'Pharmacy',
            'physiotherapist' => 'Physiotherapist',
            'plumber' => 'Plumber',
            'police' => 'Police',
            'post_office' => 'Post Office',
            'real_estate_agency' => 'Real Estate Agency',
            'restaurant' => 'Restaurant',
            'roofing_contractor' => 'Roofing Contractor',
            'rv_park' => 'Rv Park',
            'school' => 'School',
            'shoe_store' => 'Shoe Store',
            'shopping_mall' => 'Shopping Mall',
            'spa' => 'Spa',
            'stadium' => 'Stadium',
            'storage' => 'Storage',
            'store' => 'Store',
            'subway_station' => 'Subway Station',
            'synagogue' => 'Synagogue',
            'taxi_stand' => 'Taxi Stand',
            'train_station' => 'Train Station',
            'travel_agency' => 'Travel Agency',
            'university' => 'University',
            'veterinary_care' => 'Veterinary Care',
            'zoo' => 'Zoo'
        );
        public static $availableTypesID = array(
            'accounting' => '2',
            'airport' => '3',
            'amusement_park' => '4',
            'aquarium' => '5',
            'art_gallery' => '6',
            'atm' => '7',
            'bakery' => '8',
            'bank' => '9',
            'bar' => '10',
            'beauty_salon' => '11',
            'bicycle_store' => '12',
            'book_store' => '13',
            'bowling_alley' => '14',
            'bus_station' => '15',
            'cafe' => '16',
            'campground' => '17',
            'car_dealer' => '18',
            'car_rental' => '19',
            'car_repair' => '20',
            'car_wash' => '21',
            'casino' => '22',
            'cemetery' => '23',
            'church' => '24',
            'city_hall' => '25',
            'clothing_store' => '26',
            'convenience_store' => '27',
            'courthouse' => '28',
            'dentist' => '29',
            'department_store' => '30',
            'doctor' => '31',
            'electrician' => '32',
            'electronics_store' => '33',
            'embassy' => '34',
            'fire_station' => '37',
            'florist' => '38',
            'funeral_home' => '40',
            'furniture_store' => '41',
            'gas_station' => '42',
            'general_contractor' => '43',
            'gym' => '45',
            'hair_care' => '46',
            'hardware_store' => '47',
            'hindu_temple' => '49',
            'home_goods_store' => '50',
            'hospital' => '51',
            'insurance_agency' => '52',
            'jewelry_store' => '53',
            'laundry' => '54',
            'lawyer' => '55',
            'library' => '56',
            'liquor_store' => '57',
            'local_government_office' => '58',
            'locksmith' => '59',
            'lodging' => '60',
            'meal_delivery' => '61',
            'meal_takeaway' => '62',
            'mosque' => '63',
            'movie_rental' => '64',
            'movie_theater' => '65',
            'moving_company' => '66',
            'museum' => '67',
            'night_club' => '68',
            'painter' => '69',
            'park' => '70',
            'parking' => '71',
            'pet_store' => '72',
            'pharmacy' => '73',
            'physiotherapist' => '74',
            'plumber' => '76',
            'police' => '77',
            'post_office' => '78',
            'real_estate_agency' => '79',
            'restaurant' => '80',
            'roofing_contractor' => '81',
            'rv_park' => '82',
            'school' => '83',
            'shoe_store' => '84',
            'shopping_mall' => '85',
            'spa' => '86',
            'stadium' => '87',
            'storage' => '88',
            'store' => '89',
            'subway_station' => '90',
            'synagogue' => '91',
            'taxi_stand' => '92',
            'train_station' => '93',
            'travel_agency' => '94',
            'university' => '95',
            'veterinary_care' => '96',
            'zoo' => '97'
        );
        public static $all = 'all';

        public function __construct() {
            $this->placeMapper = new PlaceMapper();
        }

        public function setCountry($country) {
            $this->country = $country;
        }

        public function getCountry() {
            return $this->country;
        }

        public function setState($state) {
            $this->state = $state;
        }

        public function getState() {
            return $this->state;
        }

        public function setCity($city) {
            $this->city = $city;
        }

        public function getCity() {
            return $this->city;
        }

        public function setZipcode($zipcode) {
            $this->zipcode = $zipcode;
        }

        public function getZipcode() {
            return $this->zipcode;
        }

        public function setFilter($filter) {
            $this->filter = $filter;
        }

        public function getFilter() {
            return $this->filter;
        }

        public function setPlaceTypes($types) {
            $this->types = $types;
        }

        public function setManager(TaskManager $manager) {
            $this->manager = $manager;
        }

        public function setEmailToReport($email) {
            $this->emailToReport = $email;
        }

        public function getEmailToReport() {
            return $this->emailToReport;
        }

        public function getAddress() {
            return trim($this->country.' '.$this->state.' '.$this->city.' '.($this->zipcode != 'NOZIPCODE' ? ($this->zipcode.' ') : '').$this->filter);
        }

        public function getName() {
            return preg_replace("/[^a-zA-Z0-9\-\.]+/", "", $this->getAddress().date('YmdHis').implode('', $this->types));
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getId() {
            return $this->id;
        }

        public function setAuto($auto) {
            $this->auto = $auto;
        }

        public function getCSVWithPanoFilename() {
            if (!$this->CSVWithPanofilename) {
                $this->CSVWithPanofilename = sprintf($this->getCSVFilenameFormat(), 'withpano');
            }
            return $this->CSVWithPanofilename;
        }

        public function getCSVWithoutPanoFilename() {
            if (!$this->CSVWithoutPanofilename) {
                $this->CSVWithoutPanofilename = sprintf($this->getCSVFilenameFormat(), 'withoutpano');
            }
            return $this->CSVWithoutPanofilename;
        }

        protected function getCSVFilenameFormat() {
            $typesString = (in_array('All', $this->types) || (count($this->types) == count(self::$availableTypes)))
                ? 'allCategories'
                : mb_substr(implode('-', $this->types), 0, 42);
            return ($this->auto ? 'auto' : 'manual').'-%s-'.preg_replace("/[^a-zA-Z0-9\-\.]+/", "", $this->getAddress().'-'.date('d-m-Y-H-i-s').'-'.$typesString).'.csv';
        }

        public function getTypes() {
            return $this->types;
        }

        public function isReady() {
            if (!trim($this->getAddress())) {
                return false;
            }
            if (!$this->manager instanceof TaskManager) {
                return false;
            }
            return true;
        }

        public function run() {
            $allPossibleAddresses = $this->getAllPossibleAddresses();

            $googleGeoCode = new GoogleGeoCode();
            $placeRefs = array();

            $keys = Settings::getAllGoogleAPIKeys();
            foreach ($allPossibleAddresses as $address) {
                if ($this->isStopped()) $this->stop();

                $googleGeoCode->setKeys($keys);
                $geoCode = $googleGeoCode->getGeoCodeFor($address);
                if ($error = $googleGeoCode->getError()) {
                    $this->logApiEvent($error);
                    $this->manager->incrementFailedRequests($this);
                }
                if ($geoCode === false) {
                    $this->logApiEvent($googleGeoCode->getError());
                    $this->manager->incrementFailedRequests($this);
                    continue;
                }
                $res = $this->getPlaceRefs($geoCode, $address);
                $placeRefs = array_merge($placeRefs, $res);
                $this->updateTime();
            }
            $placeRefsWithoutDuplicates = array();
            foreach ($placeRefs as $placeRef) {
                $placeRefsWithoutDuplicates[$placeRef->place_id] = $placeRef;
            }
          

            $res = $this->fetchPlaces($placeRefsWithoutDuplicates);

            $this->manager->incrementProcessedRequests($this);

            if ($this->manager->isDone($this)) {
                $numberOfPlacesWithPano = $this->placeMapper->getPlacesWithPanoCount($this);
                $numberOfPlacesWithoutPano = $this->placeMapper->getPlacesWithoutPanoCount($this);
                $total = $numberOfPlacesWithPano + $numberOfPlacesWithoutPano;

                $this->logEvent($total.' places filtered.');
                $this->logEvent($numberOfPlacesWithPano.' places with panorama');
                $this->logEvent($numberOfPlacesWithoutPano.' places without panorama');

                $this->exportPlaces();

                $this->saveFilenames();
                $this->logEvent('Done');
                $this->finish();
            }
        }

        public function getAllPossibleRequests() {
            $baseDatum = array(
                'types' => $this->getTypes(),
                'country' => $this->getCountry(),
                'state' => $this->getState(),
                'city' => $this->getCity(),
                'filter' => $this->getFilter(),
                'pid' => $this->getId()
            );
            $zipcodes = $this->getAllPossibleZipcodes();
            if (count($zipcodes) == 0) {
                return array($baseDatum + array('zipcode' => 'NOZIPCODE'));
            }
            $data = array();
            foreach ($zipcodes as $zipcode) {
                $data[] = $baseDatum + array('zipcode' => $zipcode);
            }
            return $data;
        }

        protected function getPlaceRefs($geoCode, $address) {
            $googlePlaceRadarSearch = new GooglePlaceRadarSearch();

            $lat1 = $geoCode->geometry->viewport->northeast->lat;
            $lat2 = $geoCode->geometry->viewport->southwest->lat;
            $lng1 = $geoCode->geometry->viewport->northeast->lng;
            $lng2 = $geoCode->geometry->viewport->southwest->lng;

            $distance = 2 * asin(sqrt( pow(sin(deg2rad( ($lat1-$lat2) / 2)), 2) +
                    cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                    pow(sin(deg2rad(($lng1- $lng2) / 2)), 2))) * 6378245;

//             echo $geoCode->geometry->viewport->northeast->lat - $geoCode->geometry->location->lat, ' ';
//             echo - $geoCode->geometry->viewport->southwest->lat + $geoCode->geometry->location->lat, ' ';
//             echo $address, ': ', $distance, "\n";

            $googlePlaceRadarSearch->setRadius($distance / 2);
            $googlePlaceRadarSearch->setLocation($geoCode->geometry->location->lat.','.$geoCode->geometry->location->lng);
            $googlePlaceRadarSearch->setAddress($address);

            $places = array();
            $types = array_intersect(array_keys(self::$availableTypes), $this->types);

            if (count($types) == 0) {
                if (in_array(self::$all, $this->types)) {
                    $types = array_keys(self::$availableTypes);
                } else {
                    return $places;
                }
            }
            $keys = Settings::getAllGoogleAPIKeys();
            foreach ($types as $type) {
                if ($this->isStopped()) $this->stop();

                $googlePlaceRadarSearch->setType($type);
                $googlePlaceRadarSearch->setKeys($keys);
                $res = $googlePlaceRadarSearch->getPlaces();
                if ($res !== false) {
                    $places = array_merge($places, $res);
                    if ($error = $googlePlaceRadarSearch->getError()) {
//                         $this->logApiEvent($error);
                        $this->manager->incrementFailedRequests($this);
                    }
                } else {
                    $this->logApiEvent($googlePlaceRadarSearch->getError());
                    $this->manager->incrementFailedRequests($this);
                }
            }

            return $places;
        }

        protected function fetchPlaces($placeRefs) {
            $numberOfPlaces = count($placeRefs);

            $googlePlaceDetails = new GooglePlaceDetails();

            $googlePanoramaScraper = new GooglePanoramaScraper();

            $i = 0;
            $keys = Settings::getAllGoogleAPIKeys();

            foreach ($placeRefs as $placeRef) {
                if ($this->isStopped()) $this->stop();

                $googlePlaceDetails->setReference($placeRef->reference);
                $googlePlaceDetails->setKeys($keys);
                $place = $googlePlaceDetails->getDetails();
                if ($error = $googlePlaceDetails->getError()) {
//                     $this->logApiEvent($error);
                        $this->manager->incrementFailedRequests($this);
                }
                if (!$this->isAddressCorrect($place)) {
                    continue;
                }
                if ($place !== false) {
                    $googlePanoramaScraper->fetchPanoramaData($place);
                    $this->placeMapper->addPlace($this, $place);
                    $this->setProgress((int)($i++ / $numberOfPlaces * 100));
                } else {
                    $this->logApiEvent($googlePlaceDetails->getError());
                    $this->manager->incrementFailedRequests($this);
                }
            }
            $this->setProgress(100);
        }

        protected function isAddressCorrect($place) {
            if ($this->zipcode && ($this->zipcode != 'NOZIPCODE')) {
                if (mb_strpos(mb_strtolower($place->formatted_address), mb_strtolower($this->zipcode)) === false) {
//                     $this->logApiEvent($this->zipcode . ' ' . $place->formatted_address);
                    return false;
                }
                return true;
            }
            if ($this->city) {
                if (mb_strpos(mb_strtolower($place->formatted_address), mb_strtolower($this->city)) === false) {
//                     $this->logApiEvent($this->city . ' ' . $place->formatted_address);
                    return false;
                }
                return true;
            }
            return true;
        }

        protected function logEvent($message) {
            $this->manager->sendEvent($this, $message);
        }
        protected function logApiEvent($message) {
            $this->manager->sendApiEvent($this, $message);
        }

        protected function setProgress($percentage) {
            $this->manager->sendProgress($this, $percentage);
        }

        protected function updateTime() {
            $this->manager->updateTime($this);
        }

        protected function saveFilenames() {
            $this->manager->saveFilenames($this);
        }

        protected function finish() {
            $this->placeMapper->clear($this);
        }

        protected function getAllPossibleAddresses() {
            $zipcodes = $this->getAllPossibleZipcodes();
            if (count($zipcodes) < 2) {
                return array($this->getAddress());
            }
            $addresses = array();
            foreach ($zipcodes as $zipcode) {
                $addresses[] = $this->getAddress().' '.$zipcode;
            }
            return $addresses;
        }

        protected function getAllPossibleZipcodes() {
            if ($this->zipcode) {
                return array($this->zipcode);
            }
            $zipCodesSearcher = new ZipCodesSearcher();
            if ($this->city) {
                if ($this->state) {
                    if ($this->country) {
                        return $zipCodesSearcher->getZipcodesByCityAndStateAndCountry($this->city, $this->state, $this->country);
                    }
                    return $zipCodesSearcher->getZipcodesByCityAndState($this->city, $this->state);
                }
                if ($this->country) {
                    return $zipCodesSearcher->getZipcodesByCityAndCountry($this->city, $this->country);
                }
                return $zipCodesSearcher->getZipcodesByCity($this->city);
            }
            if ($this->state) {
                if ($this->country) {
                    return $zipCodesSearcher->getZipcodesByStateAndCountry($this->state, $this->country);
                }
                return $zipCodesSearcher->getZipcodesByState($this->state);
            }
            return $zipCodesSearcher->getZipcodesByCountry($this->country);
        }

        protected function exportPlaces() {
            $placesWithPanoStmt = $this->placeMapper->getPlacesWithPanoStatement($this);
            $placesWithoutPanoStmt = $this->placeMapper->getPlacesWithoutPanoStatement($this);

            $CSVExporter = new CSVExporter();
            $filenameWithPano = $this->getCSVWithPanoFilename();
            $CSVExporter->export($placesWithPanoStmt, $filenameWithPano);

            $filenameWithoutPano = $this->getCSVWithoutPanoFilename();
            $CSVExporter->export($placesWithoutPanoStmt, $filenameWithoutPano);

        }
        protected function deletePlaces(){
            $stmt =Database::connect()->prepare("DELETE FROM `places` WHERE `task_id`= ?");
            $stmt->execute(array($this->getId()));
        }

        protected function isStopped() {
            if (!$this->getId()) {
                return false;
            }
            return Database::connect()->query("SELECT stop FROM tasks WHERE id = ".(int)$this->getId())->fetchColumn();
        }

        protected function stop() {
            exit;
        }
    }
