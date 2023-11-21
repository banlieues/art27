<?php

namespace Calculator\Controllers;

use Base\Controllers\BaseController;
use Calculator\Libraries\AdminLibrary;
use Calculator\Libraries\GroupLibrary;
use Calculator\Models\AdminModel;
use CodeIgniter\Files\File;
use Tesorus\Libraries\TesorusLibrary;
use Tesorus\Models\RoadModel;

class Admin extends BaseController
{
    public function __construct() 
    {
        parent::__construct(__NAMESPACE__);

        $this->datas->context = 'calculator';
        $this->TesorusLibrary = new TesorusLibrary();
        $this->AdminLibrary = new AdminLibrary();
        $this->AdminModel = new AdminModel();
        $this->GroupLibrary = new GroupLibrary();
    }

    private function RoadsInactiveDeleteSql()
    {
        $q = '
            SELECT
                r1.isActive, c1.label_fr,
                r2.isActive, c2.label_fr,
                r3.isActive, c3.label_fr,
                r4.isActive, c4.label_fr,
                r5.isActive, c5.label_fr
            FROM `tesorus_road_calculator` r1 
            LEFT JOIN tesorus_cell c1 ON c1.id_cell = r1.id_cell 
            LEFT JOIN tesorus_road_calculator r2 ON r2.id_road = r1.id_road_parent 
            LEFT JOIN tesorus_cell c2 ON c2.id_cell = r2.id_cell 
            LEFT JOIN tesorus_road_calculator r3 ON r3.id_road = r2.id_road_parent 
            LEFT JOIN tesorus_cell c3 ON c3.id_cell = r3.id_cell 
            LEFT JOIN tesorus_road_calculator r4 ON r4.id_road = r3.id_road_parent 
            LEFT JOIN tesorus_cell c4 ON c4.id_cell = r4.id_cell 
            LEFT JOIN tesorus_road_calculator r5 ON r5.id_road = r4.id_road_parent 
            LEFT JOIN tesorus_cell c5 ON c5.id_cell = r5.id_cell 
            WHERE
                r1.isActive = 0 OR 
                r2.isActive = 0 OR
                r3.isActive = 0 OR
                r4.isActive = 0 OR
                r5.isActive = 0
        ';

        $q = '
            DELETE
            FROM `tesorus_road_calculator`
            WHERE id_road IN (
                SELECT r1.id_road
                FROM `tesorus_road_calculator` r1 
                LEFT JOIN tesorus_road_calculator r2 ON r2.id_road = r1.id_road_parent 
                LEFT JOIN tesorus_road_calculator r3 ON r3.id_road = r2.id_road_parent 
                LEFT JOIN tesorus_road_calculator r4 ON r4.id_road = r3.id_road_parent 
                LEFT JOIN tesorus_road_calculator r5 ON r5.id_road = r4.id_road_parent 
                WHERE
                    r1.isActive = 0 OR 
                    r2.isActive = 0 OR
                    r3.isActive = 0 OR
                    r4.isActive = 0 OR
                    r5.isActive = 0
            )
        ';
    }

    public function ExportSql()
    {
        $filename = date('ymdHis_') . '_ModuleCalculator.sql';
        $filepath = APPPATH . 'Modules/Tools/Calculator/Documents/' . $filename;

        $init = getenv('database.default.mysqldump') . ' --host=' . getenv('database.default.hostname') . ' --user=' . getenv('database.default.username') . ' --password=' . getenv('database.default.password') . ' ' . getenv('database.default.database') . ' tesorus_road_calculator tesorus_road_calculator_group tesorus_road_calculator_price';
        exec($init . ' > ' . $filepath);

        $file = new File($filepath);

        // $content = file_get_contents($file->getRealPath());
        return $this->response->download($file->getRealPath(), null);
    }

    public function RoadsEdit()
    {
        $this->datas->table = $this->TesorusLibrary->get_road_edit_html('calculator');

        return view('Calculator\road-list-edit', (array) $this->datas);
    }

    public function index() 
    {
        $road = (object) [];
        $road->ref = 'calculator';
        $road->title = $this->TesorusLibrary->road_label_get('calculator');

        $this->datas->road = $road;

        return view('Calculator\index', (array) $this->datas);
    }
    
    // public function EstimationUpdateRank($id_road_parent)
    // {
    //     $roads = $this->AdminModel->RoadsGetByParent($id_road_parent, 'active');
    //     foreach($roads as $road) :
    //         $this->db->table($this->t_estimation)->set('rank_0', $road->rank)->where('id_road_0', $road->id_road)->update();
    //         $this->db->table($this->t_estimation)->set('rank_1', $road->rank)->where('id_road_1', $road->id_road)->update();
    //         $this->db->table($this->t_estimation)->set('rank_2', $road->rank)->where('id_road_2', $road->id_road)->update();
    //         $this->db->table($this->t_estimation)->set('rank_3', $road->rank)->where('id_road_3', $road->id_road)->update();
    //         $this->db->table($this->t_estimation)->set('rank_4', $road->rank)->where('id_road_4', $road->id_road)->update();
    //     endforeach;
    // }
    
    // public function EstimationUpdateIsActive($id_road, $isActive)
    // {
    //     if(empty($isActive)) :
    //         $this->db->table($this->t_estimation)
    //             ->where('id_road', $id_road)
    //             ->orWhere('id_road_0', $id_road)
    //             ->orWhere('id_road_1', $id_road)
    //             ->orWhere('id_road_2', $id_road)
    //             ->orWhere('id_road_3', $id_road)
    //             ->orWhere('id_road_4', $id_road)
    //             ->delete();
    //             dbdebug();
    //     else :
    //         $road = $this->AdminModel->RoadGet($id_road);
    //         $groups = $this->AdminLibrary->EstimationGetGroupsByRoad($id_road);
    //         if(!empty($groups)) :
    //             $road->children = $groups;
    //         else :
    //             $road->children = $this->AdminLibrary->RoadsGetRecursiveWithGroup($id_road);
    //         endif;
    //         $datas = $this->AdminLibrary->RoadsGetFlatWithGroup([$road]);
    //         foreach($datas as $data) :
    //             $this->AdminModel->EstimationInsertByRoad($data);
    //         endforeach;
    //     endif;

    //     echo false;
    // }
    
    // public function EstimationImport()
    // {
    //     $datas = $this->AdminLibrary->RoadsGetFlat();
    // }
    
    // public function RoadsExport()
    // {
    //     $roads = $this->AdminLibrary->RoadsGetFlat();

    //     $datas = [];
    //     foreach($roads as $road) :
    //         $data = (object) [];
    //         $data->level_0 = $road->level_0;
    //         $data->level_1 = $road->level_1;
    //         $data->level_2 = $road->level_2;
    //         $data->level_3 = $road->level_3;
    //         $data->level_4 = $road->level_4;
    //         $data->poste_label = $road->poste_label;
    //         $data->isActive = $road->isActive;
    //         $data->id_road = $road->id_road;
    //         $data->measure = $road->measure;
    //         $data->min_price = $road->min_price;
    //         $data->avg_price = $road->avg_price;
    //         $data->max_price = $road->max_price;
    //         $datas[] = $data;
    //     endforeach;
        
    //     $labels = ['level_0', 'level_1', 'level_2', 'level_3', 'level_4', 'poste_label', 'isActive', 'id_road', 'measure', 'min_price', 'avg_price', 'max_price'];

    //     export_csv(date('ymdHis') . '_ModuleCalculator_ListeDesPostes.csv', $datas, $labels);
    // }
    
    // public function PricesExport()
    // {
    //     $roads = $this->AdminLibrary->RoadsGetFlat();

    //     $datas = [];
    //     foreach($roads as $road) :
    //         $datas_by_road = $this->AdminModel->PricesGetByRoad($road->id_road);

    //         foreach($datas_by_road as $price_by_road) :
    //             $data = (object) [];
    //             $data->level_0 = $road->level_0;
    //             $data->level_1 = $road->level_1;
    //             $data->level_2 = $road->level_2;
    //             $data->level_3 = $road->level_3;
    //             $data->level_4 = $road->level_4;
    //             $data->poste_label = $road->poste_label;
    //             $data->id_road = $road->id_road;
    //             $data->measure = $road->measure;
    //             $data->price_origin_label = $price_by_road->price_origin_label;
    //             $data->date_devis = $price_by_road->date_devis;
    //             $data->unit_price = $price_by_road->unit_price;
    //             $data->is_ignored = $price_by_road->is_ignored;
    //             $data->updated_at = $price_by_road->updated_at;
    //             $data->updated_by = fullname($price_by_road->updated_prenom, $price_by_road->updated_nom);
    //             $data->created_at = $price_by_road->created_at;
    //             $data->created_by = fullname($price_by_road->created_prenom, $price_by_road->created_nom);
    //             $datas[] = $data;
    //         endforeach;
    //     endforeach;

    //     $labels = ['level_0', 'level_1', 'level_2', 'level_3', 'level_4', 'poste_label', 'id_road', 'measure', 'price_origin_label', 'date_devis', 'unit_price', 'is_ignored', 'updated_at', 'updated_by', 'created_at', 'created_by'];

    //     export_csv(date('ymdHis') . '_ModuleCalculator_ListeDesPrixUnitaires.csv', $datas, $labels);
    // }

    // public function EstimationList()
    // {
    //     $this->datas->context_sub = 'estimation';
    //     $this->datas->roads = $this->AdminModel->EstimationsGetFlat();
    //     $this->datas->tab = 'estimation';
    //     $this->datas->titleView = 'Calculette - Liste des estimations';

    //     return view('Calculator\estimation-list', (array) $this->datas);
    // }

    public function RoadsTesorus()
    {
        if(!$this->Autorisation->is_autorise('calculator_u')) return redirect()->to(base_url('forbidden?url=' . current_url()));

        $this->datas->context_sub = 'tesorus';
        $this->datas->tab = 'tesorus';
        $this->datas->table = $this->TesorusLibrary->get_road_edit_html('calculator');
        $this->datas->titleView = 'Calculette - Liste des thématiques';

        return view('Calculator\road-tesorus', (array) $this->datas);
    }
    
    public function RoadList() 
    {
        if(!$this->Autorisation->is_autorise('calculator_u')) return redirect()->to(base_url('forbidden?url=' . current_url()));

        $this->datas->context_sub = 'road';
        $this->datas->tab = 'road';
        $this->datas->titleView = 'Calculette - Liste des postes';
        $this->datas->view = $this->AdminLibrary->GetRoadCollapse();

        return view('Calculator\road-list', (array) $this->datas);
    }

    public function RoadView($id_road)
    {
        if(!$this->Autorisation->is_autorise('calculator_u')) return redirect()->to(base_url('forbidden?url=' . current_url()));

        $form_id_price_update = 'PriceUpdateForm';
        $form_id_prices_new = 'PricesNewForm';
        $form_id_road_update = 'RoadUpdateForm';

        $validation = \Config\Services::validation();
        if(!empty($this->request->getPost())) :
            $post = database_decode($this->request->getPost());
            if(!empty($post->$form_id_price_update)) :
                $postdata = $post->$form_id_price_update;
                if($validation->run((array) $postdata, 'CalculatorPrice') == FALSE) :
                    $this->datas->validation = $validation;
                    $alert = 'warning';
                    $message = "Le prix unitaire du poste n'a pas pu être mis à jour. <br>" . $validation->listErrors();
                else :
                    $this->AdminModel->PriceSave($postdata, $postdata->id_price);
                    $alert = 'success';
                    $message = "Le prix unitaire du poste ont bien été mis à jour.";
                endif;
            elseif(!empty($post->$form_id_prices_new)) :
                $alert = '';
                $message = '';
                foreach($post->$form_id_prices_new as $pricenew) :
                    if(empty($pricenew->date_devis) && empty($pricenew->unit_price) && empty($pricenew->price_origin)) continue;
                    $validation->reset();
                    if($validation->run((array) $pricenew, 'CalculatorPrice') == FALSE) :
                        $this->datas->validation = $validation;
                        $alert = 'warning';
                        $message .= "Un prix unitaire n'a pas pu être rajouté au poste. " . $validation->listErrors();
                    else :
                        $pricenew->id_road = $id_road;
                        $this->AdminModel->PriceSave($pricenew);
                        $message .= "Un prix unitaire a bien été rajouté au poste.<br>";
                    endif;
                endforeach;
                if(empty($alert)) :
                    $alert = 'success';
                    $message = "Les prix unitaires ont bien été rajoutés au poste.";
                endif;
            elseif(!empty($post->$form_id_road_update)) :
                $postdata = $post->$form_id_road_update;
                if($validation->run((array) $postdata, 'CalculatorRoad') == FALSE) :
                    $this->datas->validation = $validation;
                    $alert = 'warning';
                    $message = "Le poste n'a pas pu être mis à jour. <br>" . $validation->listErrors();
                else :
                    $RoadModel = new RoadModel('calculator');
                    $RoadModel->RoadSave($postdata, $id_road);
                    $alert = 'success';
                    $message = "Le poste a bien été mis à jour.";
                endif;
            endif;

            return redirect()
                ->to(base_url('calculator/road/' . $id_road))
                ->with($alert, $message);

        endif;

        // $id_parent_calculator = $this->AdminModel->IdParentCalculatorGetByRoad($id_road);
        $this->datas->context_sub = 'road';
        $this->datas->form_id_price_update = $form_id_price_update;
        $this->datas->form_id_prices_new = $form_id_prices_new;
        $this->datas->form_id_road_update = $form_id_road_update;
        $this->datas->road = $this->AdminModel->RoadGet($id_road);
        $this->datas->road->path = $this->TesorusLibrary->get_path_by_id_road('calculator', $id_road);
        // $this->datas->prices = $this->AdminModel->PricesGetByRoad($id_road);
        // debugd($this->datas->road);
        $this->datas->price_origin_list = $this->AdminModel->PriceOriginsGet();
        $this->datas->titleView = 'Calculette - Détails du poste';
        $this->datas->typeDataView = 'read';

        return view('Calculator\road-view', (array) $this->datas);
    }

    public function PriceDelete($id_price)
    {
        $price = $this->AdminModel->PriceGet($id_price);
        $this->AdminModel->PriceDelete($id_price);

        return redirect()
            ->to(base_url('calculator/road/' . $price->id_road))
            ->with('success', "Le prix unitaire a bien été supprimé.");
    }

    public function RoadDelete($id_road)
    {
        $this->AdminModel->RoadDelete($id_road);
        $message = "Le chemin a bien été supprimé";

        $prices = $this->db->table($this->t_price)->where('id_road', $id_road)->get()->getResult();
        if(!empty($prices)) :
            $this->db->table($this->t_price)->where('id_road', $id_road)->delete();
            $message .= " et l'ensemble des prix unitaires associés";
        endif;
        $message .= ".";

        return redirect()
            ->to(base_url('calculator/roads'))
            ->with('success', $message);
    }
}



