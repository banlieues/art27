<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class L_map_geo {

	var $api_map = 'AIzaSyAfIlffztCfgwK1KK511E90X58Bp8db9kk';

	public function __construct()
	{
		$this->CI =& get_instance();
	}

    public function get_map($params=array()){
        //params address_objet {name, address, type=array('','','','')}
        //params center_address : adress (centre de la carte dans le cas où la geolocalisation a échoué)
        //params zoom : zoom
        //params library : 'google', 'openstreetmap'

        if(isset($params['address_objet'])):
        	$address_objet = $params['address_objet'];
        else :
        	$address_objet = array();
        endif;

        if(isset($params['center_address']) && !empty($params['center_address'])):
        	$center_address = $params['center_address'];
        else : 
        	$center_address = "auto";
        endif;

        if(isset($params['zoom']) && !empty($params['zoom'])):
            $zoom = $params['zoom'];
        else : 
            $zoom = NULL;
        endif;


		$view = "";

        if(isset($params['library']) && $params['library'] == 'openstreetmap'){
            $this->CI->load->library('l_openstreet_map');
            $view .= $this->CI->l_openstreet_map->display_markers($address_objet, $center_address);
            $viewResponse = $view;
        }else{
            $view .= $this->markers_map($address_objet);
            $viewResponse = $this->view_map_markers($view, $center_address, $zoom); 
        }

        

		return $viewResponse;
    }

    private function anti_slash($string){
            $string = str_replace('\"', '"', $string);
            $string = str_replace('"', '\"', $string);
            return $string;
    }

    private function markers_map($address_objet=NULL){
        $view  = "";
        if(!is_null($address_objet)):
            $i=1;
            foreach ($address_objet as $address) {
                $address['name'] = $this->anti_slash($address['name']);
                $address['content'] = $this->anti_slash($address['content']);
                $address['address'] = $this->anti_slash($address['address']);
                $view .= '
                      marker = new google.maps.Marker({';

                if(isset($address['lat']) && isset($address['lng'])):
                    $view .= 'position: new google.maps.LatLng('.$address['lat'].','.$address['lng'].'),';
                else :
                    $lat_lng = $this->get_lat_lng_from_address($address['address']);
                    $view .= 'position: new google.maps.LatLng('.$lat_lng[0].','.$lat_lng[1].'),';
                endif;

                $view .= '       
                        title : "'.$address['name'].'",
                        content : "'.$address['content'].'",
                        map :  map
                      });

                       bounds.extend(new google.maps.LatLng('.$lat_lng[0].','.$lat_lng[1].'));

                      google.maps.event.addListener( marker, "click", (function(marker, infowindow) {
                        return function() {

                          infowindow.setContent("'.$address['content'].'");
                          infowindow.open(map, marker);
                        }
                      })(marker,infoWindow));
                ';
                $i++;
            }
        endif;
        return $view;
    }

    private function view_map_markers($viewAdd, $center_address, $zoom){
        $view  = "";
        //$lat_lng = $this->get_lat_lng_from_address($center_address);
        $view .= '
        <div id="floating-panel" style=" background-color: #fff;padding: 5px;border: 1px solid #999;text-align: center;font-family: \'Roboto\',\'sans-serif\';line-height: 30px;padding-left: 10px;">
            <input type="text" id="your-address" placeholder="Entrer une adresse" size="35"> <button id="submit_address" class="btn btn-sm btn-primary">OK</button> 
            OU <button id="localise_moi_mapgeo" class="btn btn-sm btn-primary">Localiser moi sur la carte</button>
        </div>
        <div id="map" style="width:100%; height:450px"></div>
        <script type="text/javascript">
         function initMap(){
        ';
	
	

        if(!is_null($zoom)){
             $view .= '
                        var map = new google.maps.Map(document.getElementById("map"), {
                          zoom: '.$zoom.'
                        });';

        }else{
            $view .= 'var map = new google.maps.Map(document.getElementById("map"));';
        }
        $view .= '

            var geocoder = new google.maps.Geocoder();
            var infoWindow = new google.maps.InfoWindow({map: map});
             var bounds = new google.maps.LatLngBounds();
        ';

        $view .='
           // map.setCenter(new google.maps.LatLng($lat_lng[0],$lat_lng[1]));

            document.getElementById(\'localise_moi_mapgeo\').addEventListener(\'click\', function() {
                var thiss = this;
                localise_moi(thiss);
            });

            document.getElementById("submit_address").addEventListener("click", function(){
                var thiss = this;
                localise_address(thiss);
            });
        ';
        $view .= $viewAdd;
        $view .='
            //markers
            map.fitBounds(bounds);

            function localise_address(thiss){
                var address = document.getElementById("your-address").value;
                geocoder.geocode( { "address": address}, function(results, status) {
                  if (status == "OK") {';

                    if(!is_null($zoom)){
                         $view .= '
                                    var map = new google.maps.Map(document.getElementById("map"), {
                                      zoom: '.$zoom.'
                                    });';

                    }else{
                        $view .= 'var map = new google.maps.Map(document.getElementById("map"));';
                    }

                    $view.='
                              var infoWindow = new google.maps.InfoWindow({map: map});
                              var bounds = new google.maps.LatLngBounds();';

                    $view.= $viewAdd;

         $view .='

                    //var addresscentre = {lat:$lat_lng[0], lng:$lat_lng[1]};
                    
                    //var km_distance = calculateAndDisplayRoute(addresscentre, results[0].geometry.location);
                    //map.setCenter(results[0].geometry.location);
                   
                     bounds.extend(results[0].geometry.location);
                    // bounds_bis.extend(addresscentre);
                     map.fitBounds(bounds);

                    infoWindow.setPosition(results[0].geometry.location);
                    infoWindow.setContent(\'Vous êtes ici\');

                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location,
                        title : \'Vous êtes ici\',
                        content : \'Vous êtes ici\',
                        icon : \'http://maps.google.com/mapfiles/ms/icons/blue-dot.png\',
                    });

                    google.maps.event.addListener( marker, "click", (function(marker, infowindow) {
                        return function() {
                          infowindow.setContent("Vous êtes ici");
                          infowindow.open(map, marker);
                        }
                      })(marker,infoWindow)); 



                  } else {
                    alert("Geocode was not successful for the following reason: " + status);
                  }
                });
            }

            function localise_moi(thiss) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {';

                    if(!is_null($zoom)){
                         $view .= '
                                    var map = new google.maps.Map(document.getElementById("map"), {
                                      zoom: '.$zoom.'
                                    });';

                    }else{
                        $view .= 'var map = new google.maps.Map(document.getElementById("map"));';
                    }

                    $view.='
                              var infoWindow = new google.maps.InfoWindow({map: map});
                              var bounds = new google.maps.LatLngBounds();';

                    $view.= $viewAdd;



                         
        $view .= '
                     bounds.extend(new google.maps.LatLng(position.coords.latitude,position.coords.longitude));
                     map.fitBounds(bounds);

                       infoWindow.setPosition(new google.maps.LatLng(position.coords.latitude,position.coords.longitude));
                        infoWindow.setContent(\'Vous êtes ici\');

                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(position.coords.latitude,position.coords.longitude),
                            title : \'Vous êtes ici\',
                            content : \'Vous êtes ici\',
                            icon : \'http://maps.google.com/mapfiles/ms/icons/blue-dot.png\',
                            map: map
                        });   

                        map.setCenter(new google.maps.LatLng(position.coords.latitude,position.coords.longitude));

                        google.maps.event.addListener( marker, "click", (function(marker, infowindow) {
                            return function() {

                              infowindow.setContent("Vous êtes ici");
                              infowindow.open(map, marker);
                            }
                          })(marker,infoWindow));       

                    },function() {
                        alert("Erreur autorisation: Veuillez verifier que vous autorisez la geolocalisation sur votre navigateur.")
                    });
                }else{
                    alert("votre navigateur ne prend pas en charge la geolocalisation");
                }
            }


            function calculateAndDisplayRoute(origin, destination) {
                var directionsService = new google.maps.DirectionsService;
                directionsService.route({
                  origin: origin,  // Haight.
                  destination: destination,  // Ocean Beach.
                  // Note that Javascript allows us to access the constant
                  // using square brackets and a string value as its
                  // "property."
                  travelMode: google.maps.TravelMode["DRIVING"]
                }, function(response, status) {
                  if (status == "OK") {
                      console.log(response);
                      return (response.routes[0].legs[0].distance.value/1000) ;
                  } else {
                    window.alert("Directions request failed due to " + status);
                  }
                }); 
                
            }
         }

        ';
        $view .= '
        </script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key='.$this->api_map.'&callback=initMap"></script>
        ';

        return $view;

    }

    public function classe_ordre_address($center_address, $address_objet=array()){

        $i = 0;
        foreach ($address_objet as $address) {

            $address_objet[$i]->km = $this->get_km_address($center_address, $address->adresse);
            $i++;
        }

        print_r($address_objet); die();
    }

    public function distance_points($pointA, $pointB){
        $data_location = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$pointA."&destinations=".$pointB."&key=".$this->api_map;

        $ch = curl_init($data_location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $contents = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($contents);

        if ($data->status=="OK") 
        {
            print_r($data); die();
            return $data;
        }
    }

    public function get_lat_lng_from_address($address, $attempts = 0)
    {
        
        $lat = 0;
        $lng = 0;
        
        $error = '';

        $address = url_title($address); 
        
        $CI =& get_instance();
        $CI->load->database(); 
        $CI->db->select("latitude,longitude");
        $CI->db->from("geocoding");
        $CI->db->where("address", $address);
        $query = $CI->db->get();
            
        if ($query->num_rows()>0) {
            $row = $query->row();
            return array($row->latitude, $row->longitude);
        }

        $data_location = "https://maps.google.com/maps/api/geocode/json?address=".urlencode(utf8_encode($address))."&sensor=false";
        
        //$data = file_get_contents($data_location);
        $ch = curl_init($data_location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $contents = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($contents);
        
        if ($data->status=="OK") 
        {
            $lat = $data->results[0]->geometry->location->lat;
            $lng = $data->results[0]->geometry->location->lng;
           
            if ($address != "" && $lat != 0 && $lng != 0)
            {
                $data = array(
                    "address"=>$address,
                    "latitude"=>$lat,
                    "longitude"=>$lng
                );
                $CI->db->insert("geocoding", $data);
            }
           
        }
        else
        {
            if ($data->status == "OVER_QUERY_LIMIT") 
            {
                $error = $data->status;
                if ($attempts < 2)
                {
                    sleep(1);
                    ++$attempts;
                    list($lat, $lng, $error) = $this->get_lat_lng_from_address($address, $attempts);
                }
            }
        }
        
        return array($lat, $lng, $error);
        
    }

    private function get_km_address($pointA, $pointB){

        $adresse1 = str_replace(" ", "+", $pointA); //adresse de départ
        $adresse2 = str_replace(" ", "+", $pointB); //adresse d'arrivée

        $url = 'http://maps.google.com/maps/api/directions/xml?language=fr&origin='.$adresse1.'&destination='.$adresse2.'&sensor=false'; //on créé l'url
        //on lance une requete aupres de google map avec l'url créée

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        
        $xml = curl_exec($ch);

        //on réccupère les infos
        $charger_googlemap = simplexml_load_string($xml);
        $distance = $charger_googlemap->route->leg->distance->value;

        //si l'info est récupérée, on calcule la distance
        if ($charger_googlemap->status == "OK") {
            $distance = $distance/1000;
            $distance = number_format($distance, 2, ',', ' ');
            return $distance;
        }else {
            //si l'info n'est pas récupérée, on lui attribu 0
            return "0";
        }

    }


}