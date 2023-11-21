<?php 

namespace Mapping\Libraries;

use Base\Libraries\BaseLibrary;
use Api\Libraries\BaseApiLibrary;

class OSMLibrary extends BaseApiLibrary
{
    public function __construct()
    {
        parent::__construct();
    }

    public function GeocodeByLocationGet($address)
    {
        $full_address = implode(' ', (array) $address);
        if(empty(preg_replace('/[^A-Za-z]+/', '', $address))) return (object) ['lat' => null, 'lon' => null];

        // if(empty($address->country_address)) $full_address .= ' Belgique';

        $this->param = (object) [];
        $this->param->userAgent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2';
        $this->param->url = "https://nominatim.openstreetmap.org/search?q={" . urlencode($full_address) . "}&format=json&countrycodes=be&limit=1";

        $result = object_convert($this->curl((array) $this->param));

        if(empty($result)) return (object) ['lat' => null, 'lon' => null];

        $geocodes = (object) ['lat' => $result[0]->lat, 'lon' => $result[0]->lon, ];

        return $geocodes;
    }

    public function DistanceBetweenLocationsGet($location_start, $location_end)
    {
        if(empty($location_start->lat) || empty($location_start->lon) || empty($location_end->lat) || empty($location_end->lon)) return false;

        $this->param = (object) [];
        $this->param->userAgent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2';
        $this->param->url = "http://router.project-osrm.org/table/v1/driving/" . $location_start->lon . "," . $location_start->lat . ";" . $location_end->lon . "," . $location_end->lat . "?annotations=distance";
// debug($this->param);
        $result = object_convert($this->curl((array) $this->param));

        if(empty($result->distances)) return false;

        $distance_object = $result->distances;
        $distance = max($distance_object[0][1], $distance_object[1][0]);
        $distance = number_format($distance/1000, 2);

        return $distance;
    }

}