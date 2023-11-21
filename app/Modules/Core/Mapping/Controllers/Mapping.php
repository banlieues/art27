<?php

namespace Mapping\Controllers;

use Base\Controllers\BaseController;
use Custom\Config\Osm;
use DataView\Libraries\DataViewConstructor;
use Gasap\Models\GasapModel;
use Farmer\Models\FarmerModel;
use Mapping\Libraries\OSMLibrary;

class Mapping extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->osm = new OSMLibrary();

        $this->datas->context = 'mapping';
    }

    public function MappingDataGet($entity_ref, $id=null)
    {
        $path = '\\' . ucfirst($entity_ref) . '\\Models\\' . ucfirst($entity_ref) . 'Model';
        $model = new $path();
        $data = $model->{ucfirst($entity_ref) . 'GeocodeGet'}($id);

        echo json_encode($data);
    }

    public function Search()
    {
        return view('Mapping\search', (array) $this->datas);
    }

    private function FarmersByArea($farmers, $circle)
    {
        $DataView = new DataViewConstructor();

        $columns = [
            "distance_from_search_point" => ["Distance", true, 'asc'],
            "farmer_label" => ["Producteur.trice", true],
            "production" => ["Production", true],
            "adresse" => ["Adresse", false],
            "panier" => ["Paniers", false],
            "gasaps" => ["Groupes", false],
        ];
        
        $farmer_list = [];
        foreach($farmers as $farmer) :
            $farmer_distance = vincentyGreatCircleDistance($circle->lat, $circle->lon, $farmer->lat, $farmer->lon)/1000;
            if($farmer_distance<$this->request->getPost('radius')) :
                $farmer->distance_from_search_point = round($farmer_distance, 2);
                $farmer_list[] = $farmer;
            endif;
        endforeach;
        usort($farmer_list, function($a, $b) {return strcmp($a->distance_from_search_point, $b->distance_from_search_point);});

        $data = (object) [];
        $data->columns = $columns;
        $data->farmers = $farmer_list;
        $data->get = '';
        $data->getTh = $DataView->SetOrderTh($columns, $this->request);
        $data->id_user_get = '';
        $data->nb_farmers = !empty($farmer_list) ? count($farmer_list) : 0;
        $data->themes = $this->themes;

        return $data;
    }

    private function GasapsByArea($gasaps, $circle)
    {
        $DataView = new DataViewConstructor();

        $columns = [
            "distance_from_search_point" => ["Distance", true, 'asc'],
            "gasap_label" => ["Groupe", true],
            "gasap_type_label" => ["Type", true],
            "adresse" => ["Adresse", false],
            "panier" => ["Paniers", false],
            "farmers" => ["Producteurs", false],
        ];

        $gasap_list = [];
        foreach($gasaps as $gasap) :
            $gasap_distance = vincentyGreatCircleDistance($circle->lat, $circle->lon, $gasap->lat, $gasap->lon)/1000;
            if($gasap_distance<$this->request->getPost('radius')) :
                $gasap->distance_from_search_point = round($gasap_distance, 2);
                $gasap_list[] = $gasap;
            endif;
        endforeach;
        usort($gasap_list, function($a, $b) {return strcmp($a->distance_from_search_point, $b->distance_from_search_point);});

        $data = (object) [];
        $data->columns = $columns;
        $data->gasaps = $gasap_list;
        $data->get = '';
        $data->getTh = $DataView->SetOrderTh($columns, $this->request);
        $data->id_user_get = '';
        // $data->nb_gasaps = count($gasaps);
        $data->nb_gasaps = !empty($gasap_list) ? count($gasap_list) : 0;
        $data->themes = $this->themes;

        return $data;
    }

    public function TerritoryValidation()
    {
        $data = (object) [];
        $validation = \Config\Services::validation();
        if($validation->run($this->request->getPost(), $this->module . 'Territory') == false) :

            $error = (object) [];
            $error->key = 'warning';
            $error->value = 'La distance doit être encodée.';

            $data->error = view('Custom\flash_one', (array) $error);

        // else :
        //     $post = database_decode($this->request->getPost());
        //     $address = [];
        //     $address[] = !empty($post->address) ? $post->address : 'Bruxelles';
        //     $data->circle = $this->osm->GeocodeByLocationGet($address);
        //     $data->circle->radius = $post->radius * 1000;         
        endif;

        echo json_encode($data);  
    }

    public function geocodeSet()
    {
        $gasaps = $this->GasapModel->GasapsGet('active', null, $this->request);
        $pager = $this->GasapModel->GasapsPagerGet();

        foreach($gasaps as $gasap) $this->GasapModel->GasapSetGeocodes($gasap->id_gasap);
        $farmers = $this->FarmerModel->FarmersGet('active', null, $this->request, 'no_pager');
        foreach($farmers as $farmer) $this->FarmerModel->FarmerSetGeocodes($farmer->id_farmer);
        $gasapfarmers = $this->db->table($this->t_farmer_gasap)->get()->getResult();
        foreach($gasapfarmers as $gf) :
            $gasap = $this->GasapModel->FindOneGasap($gf->id_gasap);
            $farmer = $this->FarmerModel->FindOneFarmer($gf->id_farmer);
            $data = (object) [];
            $data->calcul_fg_distance = $this->osm->DistanceBetweenLocationsGet($gasap, $farmer);
            $data->updated_at = date('Y-m-d H:i:s');
            $data->updated_by = session('loggedUserId');
            $this->db->table($this->t_farmer_gasap)->set(database_encode($this->t_farmer_gasap, $data))->where('id_farmer_gasap', $gf->id_farmer_gasap)->update();
            debug($this->db->getLastQuery());
        endforeach;
    }
}