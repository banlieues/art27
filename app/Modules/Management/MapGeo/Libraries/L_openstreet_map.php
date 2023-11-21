<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class L_openstreet_map {

	public function __construct()
	{
		$this->CI =& get_instance();
	}
	

	public function display_markers($address_objet, $center_address){

		if(empty($center_address) OR $center_address == 'auto'){
			$center_address = 'Belgique';
			//$center_address = $this->get_lat_lng_from_address($center_address);
		}	

		$view = '';
		$view .= 
		'
			<div id="floating-panel" style=" background-color: #fff;padding: 5px;border: 1px solid #999;text-align: center;font-family: \'Roboto\',\'sans-serif\';line-height: 30px;padding-left: 10px;">
	            <input type="text" id="your-address" placeholder="Entrer une adresse" size="35"> <button id="submit_address" class="btn btn-sm btn-primary">OK</button> 
	            OU <button id="localise_moi_mapgeo" class="btn btn-sm btn-primary">Localiser moi sur la carte</button>
	        </div>
	        <div id="container_map">
				<div style="height: 450px;" id="mapid"></div>
			</div>

			<script>
				
			 	var map;
			 	var bounds = [];
				var defaultZoom = 10;
				var redIcon = new L.Icon({
						iconUrl: "'.base_url().'assets/Leaflet/icons/marker-icon-2x-red.png",
						shadowUrl: "'.base_url().'assets/Leaflet/icons/marker-shadow.png",
						iconSize: [25, 41],
						iconAnchor: [12, 41],
						popupAnchor: [1, -34],
						shadowSize: [41, 41]
					});
				var blueIcon = new L.Icon({
						iconUrl: "'.base_url().'assets/Leaflet/icons/marker-icon-2x-blue.png",
						shadowUrl: "'.base_url().'assets/Leaflet/icons/marker-shadow.png",
						iconSize: [25, 41],
						iconAnchor: [12, 41],
						popupAnchor: [1, -34],
						shadowSize: [41, 41]
					});

				var openStreetMapGeocoder = GeocoderJS.createGeocoder("openstreetmap");

				openStreetMapGeocoder.geocode("'.$center_address.'", function(result) {
			        console.log(result[0]["latitude"]);
			        if(result[0]){
			        	var lat = result[0]["latitude"];
			        	var lng = result[0]["longitude"]
			        	center = [ lat, lng];

			        	loadMap();
			        }
			    });
				

			    function loadMap() {
			    	//initialisation de la carte
				 	buildMap();
		';

		//markers
		 $view_markers = "";
		 $i=0;
		 foreach ($address_objet as $address) {
                $address['name'] = $this->anti_slash($address['name']);
                $address['content'] = $this->anti_slash($address['content']);
                $address['address'] = $this->anti_slash($address['address']);

                //verifie ladresse ds bd
                $address_db = url_title($address['address']); 
        
		        $CI =& get_instance();
		        $CI->load->database(); 
		        $CI->db->select("latitude,longitude");
		        $CI->db->from("geocoding");
		        $CI->db->where("address", $address_db);
		        $query = $CI->db->get();
		            
		        if ($query->num_rows()>0) {
		            $row = $query->row();
		           //return array($row->latitude, $row->longitude);
		            $view_markers .= 
	                '	
	                	var latlng = [ '.$row->latitude.', '.$row->longitude.'];
			        	//create marker and add to map
			        	marker = new L.marker(latlng, {icon: redIcon})
							.bindPopup("'.$address['content'].'")
							.addTo(map);	
						bounds.push(marker);
	                ';
		        }else{
		        	$view_markers .= 
	                '
	                	openStreetMapGeocoder.geocode("'.$address['address'].'", function(result) {
					        console.log(result[0]);
					        if(result[0]){
					        	var lat = result[0]["latitude"]; 
					        	var lng = result[0]["longitude"]
					        	var latlng = [ lat, lng];

					        	//create marker and add to map
					        	marker = new L.marker(latlng, {icon: redIcon})
									.bindPopup("'.$address['content'].'")
									.addTo(map);

								bounds.push(marker);

					        	//insert new address in database
								var address_db = "'.$address['address'].'";

								$.ajax({
									type: "POST",
									url : "'.base_url().'map_geo/add_address_latlng",
									data : {address : address_db, lat:lat, lng:lng},
									success : function(data){
										console.log(data);
									}

								});

							}
						});
	                	
	                ';
		        }
         }

        $view .= $view_markers;
		$view.=
		'
				var group = new L.featureGroup(bounds);
 				map.fitBounds(group.getBounds());

 				//initialise map
 				function buildMap()  {
    				document.getElementById("container_map").innerHTML = "<div style=\"height: 450px;\" id=\"mapid\"></div>";
    				var carte = new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
							    attribution: "Map data &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors",
							    maxZoom: 18,
							    minZoom:2
							});

					map = new L.Map("mapid");
					// disable drag and zoom handlers
				    //map.dragging.disable();
				    //map.touchZoom.disable();
				    //map.doubleClickZoom.disable();
				    map.scrollWheelZoom.disable();

					map.setView(center, defaultZoom).addLayer(carte);
    			}

 				//localisation 
		        $(document).off("click", "#localise_moi_mapgeo").on("click", "#localise_moi_mapgeo", function(e){
		        	e.preventDefault();
		            if (navigator.geolocation) {
				      navigator.geolocation.getCurrentPosition(function(position){
					    var latitude = position.coords.latitude;
					    var longitude = position.coords.longitude;  
					    //vider le bounds
				        bounds = [];
				        //reinitialisation de map
				        buildMap();
				        //markers
				        '.$view_markers.'
				        //add address
				        var latlng = [latitude, longitude];
				        marker = new L.marker(latlng, {icon: blueIcon})
								.bindPopup("Votre position")
								.addTo(map);
						bounds.push(marker);
						//fit bouds
						var new_group = new L.featureGroup(bounds);
						map.fitBounds(new_group.getBounds());

					  },function() {
                        alert("Erreur autorisation: Veuillez verifier que vous autorisez la geolocalisation sur votre navigateur.")
                      });
					}else {
					    alert("votre navigateur ne prend pas en charge la geolocalisation");
					}
		        });

		        //adresse localisation
		        $(document).off("click", "#submit_address").on("click", "#submit_address", function(e){
		        	e.preventDefault();
		        	var address = document.getElementById("your-address").value;
		        	openStreetMapGeocoder.geocode(address, function(result) {
				        console.log(result[0]);
				        if(result[0]){
				        	//vider le bounds
				        	bounds = [];
				        	//reinitialisation de map
				        	buildMap();
				        	//markers
				        	'.$view_markers.'
				        	//add address
				        	var lat = result[0]["latitude"]; 
				        	var lng = result[0]["longitude"];
				        	var latlng = [lat, lng];
				        	marker = new L.marker(latlng, {icon: blueIcon})
								.bindPopup("Votre addresse")
								.addTo(map);
							bounds.push(marker);
							//fit bouds
							var new_group = new L.featureGroup(bounds);
							map.fitBounds(new_group.getBounds());
						}else{
							alert("Adresse no found!");
						}
					});
		        });
			}

			</script>
         
		';
		return $view;
	}

	private function anti_slash($string){
            $string = str_replace('\"', '"', $string);
            $string = str_replace('"', '\"', $string);
            return $string;
    }

}