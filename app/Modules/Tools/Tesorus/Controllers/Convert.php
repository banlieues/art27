<?php

namespace Tesorus\Controllers;

use Tesorus\Libraries\MysqlLibrary;
use Base\Controllers\BaseController;

class Convert extends BaseController
{
    public function __construct($roads)
    {
        parent::__construct(__NAMESPACE__);
    }

    public function index()
    {
        $queries = [];
        $queries[] = 'DROP TABLE IF EXISTS demande_thematic';
        $queries[] = 'DROP TABLE IF EXISTS demande_feature';
        foreach($queries as $query) :
            if($this->db->query($query)) echo '<br> OK - ' . $query . '<br>';
            else echo '<br> ERROR - ' . $query . '<br>';
        endforeach;
        $queries = [];
        if(!$this->db->table_exists('demande_feature')):
            $queries[] = 'DROP TABLE IF EXISTS `demande_feature`';
            $queries[] = '
            CREATE TABLE `demande_feature` (
                `id` int(11) NOT NULL,
                `id_demande` int(11) NOT NULL,
                `id_cell_them_first` int(11) DEFAULT NULL,
                `ids_cell_them_second` text DEFAULT NULL,
                `ids_cell_accomp` text DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ';
            $queries[] = 'ALTER TABLE `demande_feature` ADD PRIMARY KEY (`id`)';
            $queries[] = 'ALTER TABLE `demande_feature` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;';
            foreach($queries as $query) :
                if($this->db->query($query)) echo '<br> OK - ' . $query . '<br>';
                else echo '<br> ERROR - ' . $query . '<br>';
            endforeach;
            $this->insert_demande_feature();
        endif;    
    }

    private function insert_demande_feature()
    {
        $demandes = $this->db->table('demande_caracteristique')->orderBy('id_demande', 'asc')->get()->getResult();
        // $cells = $this->db->table($this->t_cell)->get()->getResult();
        $datas = [];
        foreach($demandes as $dem):
            $ok = 0;
            foreach($dem as $key=>$value):
                if(preg_match('/^id_th/', $key) && !empty($value)) :
                    $ok = 1;
                endif;
            endforeach;
            if($ok == 1):
                $data = (object) [];
                $data->id_demande = $dem->id_demande;
                if(isset($dem->id_thematique_principal) && $dem->id_thematique_principal!=0) :
                    $id_cell_them_first = $this->convert_id_cell('them', 'liste_thematique', $dem->id_thematique_principal, 0);
                    $data->id_cell_them_first = $id_cell_them_first[0];
                endif;                
                if(!empty($this->get_ids_cell_them_second($dem))) $data->ids_cell_them_second = json_encode($this->get_ids_cell_them_second($dem), JSON_NUMERIC_CHECK);
                if(!empty($this->get_ids_cell_accomp($dem))) :
                    $data->ids_cell_accomp = json_encode($this->get_ids_cell_accomp($dem), JSON_NUMERIC_CHECK);
                endif;
                if(
                    !empty($data->id_cell_them_first) || 
                    !empty($data->ids_cell_them_second) ||
                    !empty($data->ids_cell_accomp) 
                    ) $datas[] = $data;
            endif;
        endforeach;

        foreach($datas as $data) :
            $this->db->insert('demande_feature', $data);
            _print($this->db->last_query());
        endforeach;
    }

    private function get_ids_cell_them_second($dem)
    {
        $ids_cell_array = [];
        if(isset($dem->id_thematique_secondaire) && $dem->id_thematique_secondaire!=0) :
            $ids_cell_array[] = $this->convert_id_cell('them', 'liste_thematique', $dem->id_thematique_secondaire, 0);
        endif;
        foreach($dem as $key=>$value):
            if(preg_match('/^id_th_/', $key) && !empty($value)) :
                
                $table = str_replace('id_th_', 'liste_th_', $key);
                switch($key):
                    case 'id_th_prime' : 
                        $ids_cell_array[] = $this->convert_id_cell('them', $table, $value, 1);
                        $ids_cell_array[] = $this->convert_id_cell('them', 'liste_ths_petit_patrimoine', $dem->id_ths_petit_patrimoine, 6); 
                        break;
                    // case 'id_th_accompagnement' : break;
                    case 'id_th_acoustique' :
                        $ids_cell_array[] = $this->convert_id_cell('them', $table, $value, 11); 
                        break;
                    case 'id_th_energie' : 
                        $ids_cell_array[] = $this->convert_id_cell('them', $table, $value, 19);
                        $ids_cell_array[] = $this->convert_id_cell('them', 'liste_ths_isolation', $dem->id_ths_isolation, 27); 
                        $ids_cell_array[] = $this->convert_id_cell('them', 'liste_ths_energie_renouvelable', $dem->id_ths_energie_renouvelable, 22); 
                        break;
                    case 'id_th_logement' : 
                        $ids_cell_array[] = $this->convert_id_cell('them', $table, $value, 39);
                        $ids_cell_array[] = $this->convert_id_cell('them', 'liste_ths_aide_achat', $dem->id_ths_aide_achat, 50); 
                        $ids_cell_array[] = $this->convert_id_cell('them', 'liste_ths_aide_locative', $dem->id_ths_aide_locative, 42); 
                        $ids_cell_array[] = $this->convert_id_cell('them', 'liste_ths_aide_juridique', $dem->id_ths_aide_juridique, 54); 
                        $ids_cell_array[] = $this->convert_id_cell('them', 'liste_ths_insalubrite', $dem->id_ths_insalubrite, 57); 
                        $ids_cell_array[] = $this->convert_id_cell('them', 'liste_ths_logement_inoccupe', $dem->id_ths_logement_inoccupe, 61); 
                        break;
                    case 'id_th_patrimoine' : 
                        $ids_cell_array[] = $this->convert_id_cell('them', $table, $value, 79);
                        break;
                    case 'id_th_renovation' : 
                        $ids_cell_array[] = $this->convert_id_cell('them', $table, $value, 66);
                        break;
                    case 'id_th_urbanisme' : 
                        $ids_cell_array[] = $this->convert_id_cell('them', $table, $value, 101);
                        break;
                    case 'id_th_regularisation' : 
                        $ids_cell_array[] = $this->convert_id_cell('them', $table, $value, 103);
                        break;
                    // case 'id_th_ag' : $id_parent = ; break;
                    // case 'id_th_visite' : $id_parent = ; break;
                    case 'id_th_bati_durable' : 
                        $ids_cell_array[] = $this->convert_id_cell('them', $table, $value, 107);
                        $ids_cell_array[] = $this->convert_id_cell('them', 'liste_ths_batiment_durable', $dem->id_ths_batiment_durable, 107);
                        break;
                    // case 'id_ths_petit_patrimoine_acc' : $id_parent = ; break;
                endswitch;
            endif;
        endforeach;
        $ids_cell = [];
        foreach($ids_cell_array as $ids) $ids_cell = array_merge($ids_cell, $ids);
        if(!empty($ids_cell)) $ids_cell = array_values(array_filter(array_unique($ids_cell)));

        return $ids_cell;
    }

    private function get_ids_cell_accomp($dem)
    {
        $ids_cell_array = [];
        if(isset($dem->id_type_accompagnement) && $dem->id_type_accompagnement!=0) :
            $ids_cell_array[] = $this->convert_id_cell('accomp', 'liste_type_accompagnement', $dem->id_type_accompagnement, 0);
        endif;
        foreach($dem as $key=>$value):
            if(!empty($value)) :
                $table = str_replace('id_', 'liste_', $key);
                switch($key):
                    case 'id_objet_pvb' : 
                        $ids_cell_array[] = [108]; // Prêt vert
                        $ids_cell_array[] = [109]; // Objet du PVB
                        $ids_cell_array[] = $this->convert_id_cell('accomp', $table, $value, 109);
                        break;
                    // case 'date_pvb' : break;
                    case 'id_credit_pvb' :
                        $ids_cell_array[] = [108]; // Prêt vert
                        $ids_cell_array[] = [114]; // Organisme de Crédit PVB
                        $ids_cell_array[] = $this->convert_id_cell('accomp', $table, $value, 114); 
                        break;
                    case 'id_revenu' : 
                        $ids_cell_array[] = [108]; // Prêt vert
                        $ids_cell_array[] = [116]; // Catégorie de revenus du demandeur
                        $ids_cell_array[] = $this->convert_id_cell('accomp', $table, $value, 116);
                        break;
                    case 'id_objet_permis' : 
                        $ids_cell_array[] = [119]; // Facilitateur urbanisme
                        $ids_cell_array[] = [120]; // Objet du permis
                        $ids_cell_array[] = $this->convert_id_cell('accomp', $table, $value, 120); 
                        break;
                    case 'id_raison_permis' : 
                        $ids_cell_array[] = [119]; // Facilitateur urbanisme
                        $ids_cell_array[] = [134]; // Raison du permis
                        $ids_cell_array[] = $this->convert_id_cell('accomp', $table, $value, 134); 
                        break;
                    case 'id_patrimoine' : 
                        $ids_cell_array[] = [146]; // Petit patrimoine
                        $ids_cell_array[] = $this->convert_id_cell('accomp', $table, $value, 146); 
                        break;                
                    // case 'id_geste' : break;              
                    // case 'id_intervention' : break;                       
                    // case 'nb_coproprio' : break;                        
                    // case 'id_visite' : break;              
                    case 'id_type_projet' : 
                        $ids_cell_array[] = [143]; // Be-exemplary
                        $ids_cell_array[] = [144]; // Type de projet
                        $ids_cell_array[] = $this->convert_id_cell('accomp', 'liste_th_projet_type', $value, 144);
                        break;
                    // case 'id_petite_intervention_non' : break;                   
                endswitch;
            endif;
        endforeach;
        
        $ids_cell = [];
        foreach($ids_cell_array as $ids) $ids_cell = array_merge($ids_cell, $ids);
        if(!empty($ids_cell)) $ids_cell = array_values(array_filter(array_unique($ids_cell)));

        return $ids_cell;
    }

    private function convert_id_cell($road_title, $table_old, $ids_old, $id_road_parent)
    {
        $RoadModel = new \Tesorus\Models\RoadModel($road_title);
        $roads = $RoadModel->RoadsGetByParent($id_road_parent);
        $ids_cell_new = [];
        if($this->db->table_exists($table_old) && !empty($ids_old)):
            $ids = explode(',', $ids_old);
            foreach($ids as $id):
                $cells_old = $this->db->where('id', $id)->get($table_old)->getResult();
                if(!empty($cells_old)) :
                    $cell_old = $cells_old[0];
                    foreach($roads as $road):
                        if(mb_strtolower(remove_accents($cell_old->label))==mb_strtolower(remove_accents($road->label_fr))) :
                            $ids_cell_new[] = $road->id_cell;
                        endif;
                    endforeach;
                endif;
            endforeach;
        endif;
        
        return $ids_cell_new;
    }
}

