<?php

namespace Calculator\Controllers;

use Base\Controllers\BaseController;
use Calculator\Libraries\AdminLibrary;
use Calculator\Libraries\GroupLibrary;
use Calculator\Models\AdminModel;
use CodeIgniter\Files\File;
use Tesorus\Libraries\TesorusLibrary;
use Tesorus\Models\RoadModel;

class Group extends BaseController
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

        
    // public function GroupsExportCsv()
    // {
    //     $roads = $this->AdminLibrary->RoadsGetFlatWithGroup();

    //     $datas = [];
    //     foreach($roads as $road) :
    //         $data = (object) [];
    //         $data->level_0 = $road->level_0;
    //         $data->level_1 = $road->level_1;
    //         $data->groupe_de_travaux = $road->groupe_de_travaux;
    //         $data->id_group = $road->id_group;
    //         $data->isActive = $road->isActive;
    //         $datas[] = $data;
    //     endforeach;

    //     $datas = array_values(array_of_objects_unique($datas));

    //     $labels = ['level_0', 'level_1', 'groupe_de_travaux', 'id_group', 'isActive'];

    //     export_csv(date('ymdHis') . '_ModuleCalculator_ListeDesGroupes.csv', $datas, $labels);
    // }

    public function GroupGetPath($id_group)
    {
        $group = $this->AdminModel->GroupGet($id_group);

        echo $this->GroupLibrary->GroupGetPath($group);
    }

    public function GroupUpdateActive($id_group, $isActive)
    {
        $data = (object) [];
        $data->isActive = $isActive;
        $this->db->transStart();
        $this->AdminModel->GroupSave($data, $id_group);
        if ($this->db->transComplete() == FALSE) :
            $alert = 'danger';
            $message = "La fiche groupe de travaux et les estimations associées n'ont pu être mis à jour.";
        else :
            $alert = 'success';
            $message = "La fiche groupe de travaux et les estimations associées ont pu être mis à jour.";
        endif;

        return redirect()->to($_SERVER['HTTP_REFERER'])->with($alert, $message);
    }

    public function GroupUpdateRank()
    {
        $post = $this->request->getPost('ranks');

        $this->db->transStart();
        foreach($post as $row) :
            $row = (object) $row;
            $data = (object) [];
            $data->rank = $row->rank;
            $this->AdminModel->GroupSave($data, $row->id_group);
        endforeach;

        if ($this->db->transComplete() == FALSE) :
            $result = 'error';
        else :
            $result = 'success';
        endif;
        
        echo $result;
    }

    public function GroupDelete($id_group)
    {
        $this->AdminModel->GroupDelete($id_group);
        
        return redirect()->to(base_url('calculator/groups'))->with('success', "Le groupe de travaux a bien été supprimé");
    }

    public function GroupTagModal()
    {
        $view = $this->GroupLibrary->GetGroupTagHtml('checkbox', 'ids_group');

        echo $view;
    }

    public function GroupNew()
    {
        $validation = \Config\Services::validation();
        if($validation->run($this->request->getPost(), 'CalculatorGroup') == FALSE) :
            $alert = 'warning';
            $message = "Le groupe de travaux n'a pas pu être créé." . $validation->listErrors();
            return redirect()->to(base_url("calculator/groups"))->with($alert, $message);
            
        else :
            $post = database_decode($this->request->getPost());
            $id_group = $this->AdminModel->GroupSave($post);
            $alert = 'success';
            $message = "Le groupe de travaux a bien été créé.";
            return redirect()->to(base_url("calculator/group/$id_group"))->with($alert, $message);
        endif;
    }

    public function GroupNewModal()
    {
        $form_id = "GroupNewForm";

        $post = database_decode($this->request->getPost());
        $post->form_id = $form_id;
        $post->typeDataView = 'create';
        
        $result['header'] = 'Nouveau groupe de travaux';
        $result['body'] = '
            <form id="' . $form_id . '"
                method="post"
                action="' . base_url('calculator/group/new') . '"
                >
                ' . view('Calculator\group-form', (array) $post) . '
            </form>
            ';
        $result['submit'] = '
            <button
                class="btn btn-sm btn-' . $this->themes->calculator->color . '"
                form="' . $form_id . '"
                onclick="waiting_start(this);"
                >
                Ajouter
            </button>';

        echo json_encode($result);
    }

    public function GroupList() 
    {
        $this->datas->context_sub = 'group';
        $this->datas->tab = 'group';
        $this->datas->view = $this->GroupLibrary->GetGroupCollapse();
        $this->datas->titleView = 'Calculette - Liste des groupes de travaux';
        return view('Calculator\group-list', (array) $this->datas);
    }
    
    public function GroupViewRoadsNew($id_group)
    {
        $post = database_decode($this->request->getPost());
        $ids_new = !empty($post->ids_road_children) ? $post->ids_road_children : [];
        
        $group = $this->AdminModel->GroupGet($id_group);
        $ids_road_children = !empty($group->ids_road_children) ? $group->ids_road_children : [];

        $data = (object) [];
        $data->ids_road_children = array_values(array_filter(array_merge($ids_road_children, $ids_new)));
        $this->AdminModel->GroupSave($data, $id_group);

        return redirect()->to(base_url("calculator/group/$id_group"))->with('success', "Les postes ont bien été rajouté au groupe de travaux.");
    }
    
    public function GroupViewRoadsUnlink($id_group)
    {
        $post = database_decode($this->request->getPost());
        $ids_unlink = !empty($post->ids_road_children) ? $post->ids_road_children : [];
        
        $group = $this->AdminModel->GroupGet($id_group);
        $ids_road_children = !empty($group->ids_road_children) ? $group->ids_road_children : [];

        $data = (object) [];
        $data->ids_road_children = array_values(array_filter(array_diff($ids_road_children, $ids_unlink)));
        $this->AdminModel->GroupSave($data, $id_group);

        return redirect()->to(base_url("calculator/group/$id_group"))->with('success', "Les postes ont bien été rajouté au groupe de travaux.");
    }

    public function GroupView($id_group)
    {
        if(!empty($this->request->getPost())) :
            if(!empty($this->request->getPost())) :
                $validation = \Config\Services::validation();
                if($validation->run($this->request->getPost(), 'CalculatorGroup') == FALSE) :
                    $this->datas->validation = $validation;
                else :
                    $message = !empty($id_group) ? "Le groupe de travaux a bien été mis à jour." : "Le groupe de travaux a bien été créé.";
                    $post = database_decode($this->request->getPost());
                    $id_group = $this->AdminModel->GroupSave($post, $id_group);
                    return redirect()
                        ->to(base_url('calculator/group/' . $id_group))
                        ->with('success', $message);
                endif;
            endif;
        endif;

        $form_id = 'GroupUpdateForm';

        $group = $this->AdminModel->GroupGet($id_group);

        $labels = $this->TesorusLibrary->get_labels_by_id_road('calculator', $group->id_road_parent);
        $labels[] = $group->label_fr;
        $group->labels = $labels;
        $group->path = $this->GroupLibrary->GroupGetPath($group);
        $group->labels_main = [$labels[0], $labels[1]];
        $group->group_roads_form = $this->GroupLibrary->GetThemRoadsForm($form_id, $id_group);

        $this->datas->context_sub = 'group';
        $this->datas->form_id = $form_id;
        $this->datas->group = $group;
        $this->datas->titleView = 'Calculette - Détails du groupe de travaux';
        $this->datas->typeDataView = 'read';

        return view('Calculator\group-view', (array) $this->datas);
    }

    public function GroupViewRoadDelete($id_group, $id_road_delete)
    {
        $group = $this->AdminModel->GroupGet($id_group);
        if(!empty($group->ids_road_children)) :
            $i = 0;
            foreach($group->ids_road_children as $id_road) :
                if($id_road==$id_road_delete) :
                    unset($group->ids_road_children[$i]);
                    $data = (object) [];
                    $data->ids_road_children = array_values(array_filter($group->ids_road_children));
                    $this->AdminModel->GroupSave($data, $id_group);
                    break;
                endif;
                $i++;
            endforeach;
        endif;
        // $this->AdminLibrary->EstimationTableRefresh();

        return redirect()->to(base_url("calculator/group/$id_group"))->with('success', 'Les postes ont bien été retirés du groupe de travaux.');
    }
}



