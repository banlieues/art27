<?php

namespace Tesorus\Config;

use CodeIgniter\Config\BaseConfig;

class Globals extends BaseConfig
{
    public $module  = 'Tesorus';
    public $path  = APPPATH . 'Modules/Tools/Tesorus/';

    public $t_cell = 'tesorus_cell';
    public $t_road_accomp = 'tesorus_road_accomp';
    public $t_road_eco_impact = 'tesorus_road_eco_impact';
    public $t_road_demande = 'tesorus_road_demande';
    public $t_road_them = 'tesorus_road_them';
    public $t_road_work = 'tesorus_road_work';
    public $t_thematique = 'tesorus_road_thematique';

    public $roads = ['thematique', 'batiment', 'accomp', 'work', 'demande', 'eco_impact', 'calculator'];
}