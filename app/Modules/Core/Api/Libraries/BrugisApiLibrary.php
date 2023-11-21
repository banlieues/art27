<?php

namespace Api\Libraries;

use Api\Libraries\BaseApiLibrary;

class BrugisApiLibrary extends BaseApiLibrary
{
    public function __construct()
    {
        parent::__construct();
    }

    public function api($address, $lang)
    {
        $datas = $this->import_datas($address, $lang);
        $results = [];
        if(!empty($data)) :
            foreach($datas as $data) :
                if($data->qualificationText->policeNumber=='Found') :
                    
                    $address = (object) [];
                    $address->number = $data->address->number;
                    $address->street = $data->address->street->name;
                    $address->pc = $data->address->street->postCode;
                    $address->city = $data->address->street->municipality;

                    $result = (object) [];
                    $result->address = $address;
                    $result->label = $data->address->street->name . ', ' . $data->address->number . ' - ' . $data->address->street->postCode . ' ' . $data->address->street->municipality;
                    $result->lang = $data->language;

                    $results[] = $result;
                endif;
            endforeach;
        endif;

        return $results;
    }

    private function import_datas($address, $lang)
    {
        $param['url'] = "https://geoservices.irisnet.be/localization/Rest/Localize/getaddresses?address=$address&language=$lang&spatialReference=31370";
        $param['header'] = ['Content-Type: application/json;charset=utf-8'];
        $data = $this->curl($param);

        return database_decode($data);
    }
}