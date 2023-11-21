<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Map_geo extends CI_Controller {

        var $api_map = 'AIzaSyAfIlffztCfgwK1KK511E90X58Bp8db9kk';

	public function __construct()
	{
		parent::__construct();

		//Add package 
		$this->load->add_package_path(APPPATH.'third_party/mapGeo');
                $this->load->library('l_map_geo'); 

	}

	public function test(){
        
                $objet = array(
                	array(
                		"name"=>"Maison",
                		"address"=>"RUE DE DANEMARK 37 1060 BRUXELLES", 
                                "content"=>"<srong>Maison</strong><br>rUE DANEMARK 37 1060 BRUXELLES"
                	),
                        array(
                                "name"=>"travail",
                                "address"=>"Chaussée de waterloo 412, 1050 Ixelles", 
                                "content"=>"<srong>Bureau</strong><br>Chaussée de waterloo 412, 1060 Ixelles, Belgique"
                        )
                        
                );

                $config = array(
        			'address_objet' => $objet,
                                'library'=>'openstreetmap'

        		);

                $data['view'] = $this->l_map_geo->get_map($config);
                $this->load->view('header', $data);
                $this->load->view('map_geo_view');

	}

        public function map_itineraire($pointB=NULL){
                //print_r($pointB); die();
                if(!is_null($pointB)){

                        $data['destination'] = str_replace("-", " ", $pointB);
                        $data['destination_latlng'] = $this->l_map_geo->get_lat_lng_from_address($data['destination']);
                        //print_r($data['destination_latlng']);
                        $this->load->view('map_itineraire_view', $data);
                }

        }

        public function add_address_latlng(){

                if($this->input->post()):
                        $address = url_title($this->input->post('address')); 
                        $lat = $this->input->post('lat');
                        $lng = $this->input->post('lng');
                        $data = array(
                            "address"=>$address,
                            "latitude"=>$lat,
                            "longitude"=>$lng
                        );
                        $this->load->database(); 
                        $this->db->insert("geocoding", $data);

                        echo 'success';
                        exit();
                endif;
        }

	
}
