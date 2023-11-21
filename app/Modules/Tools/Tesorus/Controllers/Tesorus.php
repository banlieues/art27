<?php

namespace Tesorus\Controllers;

use Base\Controllers\BaseController;
use DataView\Libraries\DataViewConstructor;
use Tesorus\Models\CellModel;
use Tesorus\Models\RoadModel;
use Tesorus\Libraries\MysqlLibrary;
use Tesorus\Libraries\TesorusLibrary;
use Translator\Libraries\TranslatorLibrary;

class Tesorus extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);   

        $this->CellModel = new CellModel();
        $this->TesorusLibrary = new TesorusLibrary();
        $this->mysql_l = new MysqlLibrary();
        $this->mysql_l->check_database($this->roads);

        $this->datas->context = 'tesorus';
    }

    public function index()
    {
        return redirect()->to(base_url('tesorus/roads'));
    }

    public function road_get_ids_road($road_name, $id_road)
    {
        $ids_road = $this->TesorusLibrary->get_ids_road_by_id_road($road_name, $id_road);

        echo json_encode($ids_road);
    }
 
    public function RoadDelete($road_name, $id_road)
    {
        $RoadModel = new RoadModel($road_name);
        $road = $RoadModel->RoadGet($id_road);
        $id_road_parent = $road->id_road_parent;

        $this->db->transStart();
        $RoadModel->RoadDelete($id_road);
        if ($this->db->transComplete() == FALSE) :

            return redirect()->to($_SERVER['HTTP_REFERER'])->with('warning', "Le chemin n'a pas pu être supprimé.");
            // flashdata('roadDelete', 'warning', "Le chemin n'a pas pu être supprimé.");
        else :
            return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', "Le chemin a bien été supprimé.");
            // flashdata('roadDelete', 'success', "Le chemin a bien été supprimé.");
        endif;

        echo $id_road_parent;
    }

    public function road_delete_modal($road_name, $id_road)
    {

        $RoadModel = new RoadModel($road_name);
        $road = $RoadModel->RoadGet($id_road);
        $path = !empty($road->label_fr) ? $road->label_fr : $this->TesorusLibrary->get_path_by_id_road($road_name, $id_road);
        $html = '
            Vous allez supprimer le chemin :
            <div class="my-2 w-100 text-center font-weight-bold">' . $path . '</div>
            Veuillez confirmer votre action.
        ';

        $result = (object) [];
        $result->body = $html;
        $result->header = "Suppression d'un chemin";
        $result->footer = '<button type="button" class="btn btn-sm btn-danger" onclick="road_delete(\'' . $road_name . '\', ' . $id_road . ');"> Confirmer </button>';

        echo json_encode($result);
    }

    public function get_path_by_id_road($road_name, $id_road)
    {
        $path = $this->TesorusLibrary->get_path_by_id_road($road_name, $id_road);

        echo $path;
    }

    // public function modal_translate()
    // {
    //     $this->load->library('tamo_translate');
    //     $post = (object) $this->request->getPost();
    //     $result = $this->tamo_translate->modal_update($post);
        
    //     echo json_encode($result);
    // }


    public function cell_new_modal()
    {
        $formId = 'cellNewForm';
        $result['header'] = 'Ajouter un nouvel élément de liste';
        $result['body'] = '
            <form id="' . $formId . '" method="post" action="' . base_url('tesorus/cell/new') . '">
                ' . view('Tesorus\cell-form') . '
            </form>
        ';
        $result['submit'] = '
            <button class="btn btn-sm btn-success" form="' . $formId . '" onclick="waiting_start(this);">
                Ajouter
            </button>
        ';

        echo json_encode($result);
    }

    public function cell_new()
    {
        $messagetype = 'warning';
        $validation = \Config\Services::validation();
        if($validation->run($this->request->getPost(), $this->module . 'Cell') == TRUE) :
            $post = (object) $this->request->getPost();
            $this->db->transStart();
            $id_cell = $this->CellModel->CellSave($post);
            if ($this->db->transComplete() == FALSE) :
                $message = "L'élément de liste n'a pas pu être créé.";
            else :
                $messagetype = 'success';
                $message = "L'élément de liste a bien été créé.";
            endif;
        else :
            $message = "L'élément de liste n'a pas pu être créé. <br>" . $validation->listErrors();
        endif;

        return redirect()->to(base_url('tesorus/cells'))->with($messagetype, $message);
    }

    public function cell_update_modal($id_cell)
    {
        $data = (object) [];
        $data->cell = $this->CellModel->CellGet($id_cell);

        $formId = 'cellUpdateForm';
        $result['header'] = 'Editer un élément de liste';
        $result['body'] = '
            <form id="' . $formId . '" method="post" action="' . base_url('tesorus/cell/update/' . $id_cell) . '">
                ' . view('Tesorus\cell-form', (array) $data) . '
            </form>
        ';
        $result['submit'] = '
            <button class="btn btn-sm btn-success" form="' . $formId . '" onclick="waiting_start(this);">
                Enregistrer
            </button>
        ';

        echo json_encode($result);
    }

    public function road_update_modal($road_name, $id_road)
    {
        $RoadModel = new RoadModel($road_name);
        $formId = 'roadUpdateForm';
        $data = (object) [];
        $data->road = $RoadModel->RoadGet($id_road);

        $result['header'] = 'Editer un chemin';
        $result['body'] = '
            <form id="' . $formId . '" method="post" action="' . base_url('tesorus/road/update/' . $road_name . '/' . $id_road) . '">
                ' . view('Tesorus\road-form', (array) $data) . '
            </form>
            ';
        $result['submit'] = '<button class="btn btn-sm btn-success" form="' . $formId . '" onclick="waiting_start(this);"> Enregistrer </button>';

        echo json_encode($result);
    }

    public function road_update_rank($road_name)
    {
        $RoadModel = new RoadModel($road_name);

        $post = $this->request->getPost('ranks');
        $this->db->transStart();
        foreach($post as $row) :
            $row = (object) $row;
            $data = (object) [];
            $data->rank = $row->rank;
            $RoadModel->RoadSave($data, $row->id_road);
        endforeach;

        if ($this->db->transComplete() == FALSE) :
            $result = 'error';
        else :
            $result = 'success';
        endif;
        
        echo $result;
    }

    public function RoadSaveIsActive($road_name, $id_road, $isActive)
    {
        $RoadModel = new RoadModel($road_name);
        $this->db->transStart();
        $RoadModel->RoadSaveIsActive($id_road, $isActive);

        if ($this->db->transComplete() == FALSE) :
            $messagetype = 'warning';
            $message = ($isActive==1) ? "Le chemin vers la thématique n'a pas pu être affiché." : "Le chemin vers la thématique n'a pas pu être masqué.";
        else :
            $messagetype = 'success';
            $message = ($isActive==1) ? "Le chemin vers la thématique a bien été affiché." : "Le chemin vers la thématique a bien été masqué.";
        endif;

        return redirect()->to($_SERVER['HTTP_REFERER'])->with($messagetype, $message);
    }

    public function RoadSaveHasText($road_name, $id_road)
    {
        $RoadModel = new RoadModel($road_name);

        $this->db->transStart();
        $has_text = $RoadModel->RoadSaveHasText($id_road);
        if ($this->db->transComplete() == FALSE) :
            $messageText = 'warning';
            if($has_text==1) $message = "Le champ de texte n'a pas pu être supprimé.";
            else $message = "Le champ de texte n'a pas pu être créé.";
        else :
            $messagetype = 'success';
            if($has_text==1) $message = "Le champ de texte a bien été supprimé.";
            else $message = "Le champ de texte a bien été créé.";
        endif;

        return redirect()->to($_SERVER['HTTP_REFERER'])->with($messagetype, $message);
    }

    public function road_new_modal($road_name)
    {
        $post = database_decode($this->request->getPost());
        
        $result['header'] = 'Nouveau chemin vers un tag';
        $result['body'] = '
            <form id="roadNewForm" method="post" action="' . base_url('tesorus/road/new/' . $road_name) . '">
                ' . view('Tesorus\road-form', (array) $post) . '
            </form>
            ';
        $result['submit'] = '<button class="btn btn-sm btn-success" form="roadNewForm"> Ajouter </button>';

        echo json_encode($result);
    }

    // public function CellsGet()
    // {
    //     $where = [];
    //     if($this->input->get('term')) :
    //         $term = $this->input->get('term');
    //         $where[] = 'label_fr like "%' . $term . '%"';
    //     endif;
    //     $cells = $this->CellModel->CellsGet(['label_fr', 'asc'], $this->request);
    //     $data = [];
    //     foreach($cells as $cell) $data[] = trim($cell->label_fr);

    //     echo json_encode($data);
    // }
    
    public function road_save($road_name, $id_road=null)
    {
        $post = database_decode($this->request->getPost());
        
        $validation = \Config\Services::validation();
        if($validation->run((array) $post, 'TesorusRoad') == FALSE) :
            $alert = 'warning';
            $message = !empty($id_road) ? "Le chemin n'a pas pu être créé." : "Le chemin n'a pas pu être mis à jour.";
            $this->datas->validation = $validation;
        else :
            $RoadModel = new RoadModel($road_name);
            $id_road = $RoadModel->RoadSave($post, $id_road);
            $alert = 'success';
            $message = !empty($id_road) ? "Le chemin a bien été créé." : "Le chemin a bien été mis à jour.";
        endif;

        return redirect()->to($_SERVER['HTTP_REFERER'])->with($alert, $message);
    }

    public function RoadList()
    {
        foreach($this->roads as $road) :
            $data = (object) [];
            $data->ref = $road;
            $data->title = $this->TesorusLibrary->road_label_get($road);
            foreach(['list', 'radio', 'checkbox', 'tag'] as $tesorus_type) :
                $data->{'button_' . $tesorus_type} = $this->TesorusLibrary->{'get_road_' . $tesorus_type . '_button'}($road);
            endforeach;
            $this->datas->roads[] = $data;
        endforeach;

        $this->datas->context_sub = 'road';
        $this->datas->titleView = "Liste des thesaurus";

        return view('Tesorus\road-list', (array) $this->datas);
    }

    public function road_view_modal($road_name, $type)
    {
        $function = 'get_road_' . $type . '_html';
        $view = $this->TesorusLibrary->$function($road_name, 'no_name');

        echo $view;
    }

    public function RoadView($road_name)
    {
        $buttons = '';
        foreach(['list', 'radio', 'checkbox', 'tag'] as $tesorus_type) :
            $buttons .= $this->TesorusLibrary->{'get_road_' . $tesorus_type . '_button'}($road_name);
        endforeach;
        $road_label = $this->TesorusLibrary->road_label_get($road_name);

        $this->datas->buttons = $buttons;
        $this->datas->road_label = $road_label;
        $this->datas->table = $this->TesorusLibrary->get_road_edit_html($road_name);
        $this->datas->navigation = $this->button_road_list();
        $this->datas->titleView = t("Edition du thesaurus", __NAMESPACE__) . ' : ' . $road_label;

        return view('Tesorus\road-edit', (array) $this->datas);
    }

    private function button_road_list()
    {
        return '
            <a role="button" class="btn btn-sm btn-dark" href="' . base_url('tesorus/roads'). '"> 
                ' . t("Retour à la liste des chemins", __NAMESPACE__) . '
            </a>
        ';
    } 

    public function get_ids_road_by_id_road($road_name, $id_road)
    {
        $ids_road_parent = $this->TesorusLibrary->get_ids_road_by_id_road($road_name, $id_road);

        echo json_encode($ids_road_parent);
    }

    public function CellModalComment($id_cell)
    {
        $cell = $this->CellModel->CellGet($id_cell);
        $view = '';
        $view .= '<h6 class="fw-bold"> Notes </h6>';
        $view .= !empty($cell->comment) ? nl2br($cell->comment) : '';

        echo $view;
    }
    
    public function CellList()
    {
        $DataView = new DataViewConstructor();

        $columns = [
            "label_fr" => [t("Label", __NAMESPACE__), true, 'asc'],
            "paths" => [t("Emplacements", __NAMESPACE__), false],
            "comment" => [t("Notes", __NAMESPACE__), false],
            "action" => ["", false],
        ];
        
        $order = $DataView->GetOrderDefault($columns);
        $cells = $this->CellModel->CellsGet($order, $this->request);
        $pager = $this->CellModel->CellsPagerGet();

        $this->datas->columns = $columns;
        $this->datas->context_sub = 'cell';
        $this->datas->cells = $cells;
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);
        $this->datas->nb_cells = !empty($pager) ? $pager->getTotal() : count($cells);
        $this->datas->pager = $pager;
        $this->datas->titleView = "Liste des cellules";

        return view('Tesorus\cell-list', (array) $this->datas);
    }

    // private function button_new() 
    // {
    //     $html = '';
    //     if($this->Autorisation->is_autorise('tesorus_c')):
    //         $html .= '
    //             <button type="button" class="btn btn-sm btn-success mx-1" onclick="cell_new_modal();"> 
    //                 Ajouter un nouvel élément de liste 
    //             </button>
    //         ';
    //     endif;

    //     return $html;
    // }

    private function button_dublon()
    {
        $dublon_button = '';
        
        $cells = $this->db->table($this->t_cell)->get()->getResult();
        $i = 0;
        $ids_to_skip = [];
        foreach($cells as $cell) :
            $dublons = $this->CellModel->CellsGetByLabel($cell->label_fr);
            if(count($dublons)>1 && !in_array($cell->id_cell, $ids_to_skip)) :
                $i++;
                foreach($dublons as $dublon) $ids_to_skip[] = $dublon->id_cell;
            endif;
        endforeach;

        if($i>0) $dublon_button = '
            <a role="button" class="btn btn-sm btn-warning mx-1" href="' . base_url('tesorus/cell/dublon') . '"> 
                Fusionner les doublons (' . $i . ')
            </a> 
        ';

        return $dublon_button;
    }

    public function cell_dublon()
    {
        $cells = $this->db->table($this->t_cell)->get()->getResult();
        foreach($cells as $cell) :
            $dublons = $this->CellModel->CellsGetByLabel($cell->label_fr);
            if(count($dublons)>1) $this->TesorusLibrary->merge_cell_dublons($dublons);
        endforeach;

        return redirect()->to($_SERVER["HTTP_REFERER"]);
    }
}