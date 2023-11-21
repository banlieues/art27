<?php

namespace Liste\Controllers;

use Base\Controllers\BaseController;
use Liste\Libraries\ListeLibrary;
use Liste\Models\ListeModel;

class Liste extends BaseController
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->ListeModel = new ListeModel();
        $this->ListeLibrary = new ListeLibrary();

        $this->datas->context = 'liste';
    }

    public function TableList()
    {
        if(!$this->Autorisation->is_autorise('liste_r')) return redirect()->to('forbidden');

        $ListeLibrary = new ListeLibrary();
        $this->datas->entities = $ListeLibrary->TableEntities();
        $this->datas->titleView = "Liste des listes";
	
        return view('Liste\table-list', (array) $this->datas);
    }
    
    public function RowEditModal($table, $id)
    {
        if(!$this->Autorisation->is_autorise('liste_u')) return false;

        $form_id = 'RowEditForm';

        $data = (object) [];
        $data->pk = get_primary_key($table) ;
        $data->fields = $this->ListeModel->RowGet($table, $id);
        $data->form_action = base_url('liste/table/' . $table . '/row/' . $id);
        $data->form_id = $form_id;
        $data->table = $table;

        $result = (object) [];
        $result->body = view('Liste\row-form', (array) $data);
        $result->title = 'Modifier l\'élément <b>' . $data->fields->label_original . '</b> de la liste <b> ' . $this->ListeLibrary->TableLabel($table) . '</b>';
        $result->submit = '
            <button type="button"
                class="btn btn-sm btn-' . $this->themes->liste->color . '"
                form="' . $form_id . '"
                onclick="row_edit(this, \'' . $table . '\', ' . $id . ');"
                >
                Modifier
            </button>
        ';

        echo json_encode($result);
    }
    
        
    public function RowEdit($table, $id)
    {
        if(!$this->Autorisation->is_autorise('liste_u')) return false;

        $result = (object) [];

        $validation = $this->RowValidation($table, $id);
        if ($validation->run($this->request->getPost()) === FALSE) :
            $result->error = implode('<br>', $validation->getErrors());
        else :
            $post = database_decode($this->request->getPost());
            $this->ListeModel->RowSave($table, $post, $id);
        endif;

        echo json_encode($result);
    }
    
    public function TableRankUpdate($table)
    {
        if(!$this->Autorisation->is_autorise('liste_u')) return false;

        $pk = get_primary_key($table);

        $posts = database_decode($this->request->getPost('data'));
        foreach($posts as $post) :
            $this->db->table($table)->set('rank', $post->rank)->where($pk, $post->pk_value)->update();
        endforeach;
        
        echo true;
    }
    
    public function TableView($table)
    {
        if(!$this->Autorisation->is_autorise('liste_u')) return redirect()->to('forbidden');

        $this->datas->fields = $this->db->getFieldNames($table) ;
        $this->datas->pk = get_primary_key($table) ;
        $this->datas->has_rank = $this->db->fieldExists('rank', $table);
        $this->datas->result = $this->ListeModel->RowsGet($table);
        $this->datas->table = $table;
        $this->datas->titleView = 'Liste : ' . $this->ListeLibrary->TableLabel($table);

        return view('Liste\table-view', (array) $this->datas);            
    }
    
    public function TableModal($table)
    {
        if(!$this->Autorisation->is_autorise('liste_r')) return false;

        $data = (object) [];
        $data->typeDataView = 'read';
        $data->fields = $this->db->getFieldNames($table) ;
        $data->pk = get_primary_key($table) ;
        $data->has_rank = $this->db->fieldExists('rank', $table);
        $data->result = $this->ListeModel->RowsGet($table);

        $result = (object) [];
        $result->body = view('Liste\table-table', (array) $data);
        $result->title = 'Table : ' . $this->ListeLibrary->TableLabel($table);
        $result->submit = '<a class="btn btn-sm btn-' . $this->themes->liste->color . '" href="' . base_url('liste/table/' . $table) . '"> Modifier </a>';
        
        echo json_encode($result);
    }
    
    public function RowNewModal($table)
    {
        if(!$this->Autorisation->is_autorise('liste_c')) return false;

        $form_id = 'RowNewForm';

        $data = (object) [];
        $data->pk = get_primary_key($table);
        $data->fields = $this->ListeModel->RowGetEmptyData($table);
        $data->table = $table;
        $data->form_id = $form_id;
        $data->form_action = base_url('liste/table/' . $table . '/row/new');

        $result = (object) [];
        $result->body = view('Liste\row-form', (array) $data);
        $result->title = 'Ajouter un nouvel élément à la liste <b>' . $this->ListeLibrary->TableLabel($table) . '</b>';
        $result->submit = '
            <button type="button" onclick="row_new(this, \'' . $table . '\');" class="btn btn-sm btn-' . $this->themes->liste->color . '" form="' . $form_id . '"> Enregistrer </button>
        ';
            
        echo json_encode($result);
    }
    
    public function RowNew($table)
    {
        if(!$this->Autorisation->is_autorise('liste_c')) return false;

        $result = (object) [];

        $validation = $this->RowValidation($table);
        if ($validation->run($this->request->getPost()) === FALSE) :
            $result->error = implode('<br>', $validation->getErrors());
        else :
            $post = database_decode($this->request->getPost());
            $this->ListeModel->RowSave($table, $post);
        endif;

        echo json_encode($result);
    }

    
    private function RowValidation($table, $id=null)
    {
        $validation = \Config\Services::validation();
        $pk = get_primary_key($table);

        $rules = [];
        if(!empty($id)) :
            $rules[$pk] = [
                'label'  => $pk,
                'rules'  => "is_natural_no_zero",
            ];
        endif;

        $fields = ['label', 'label_fr', 'name', 'name_fr', 'status_name', 'localite_fr', 'origine_fr', 'type'];
        $db_fields = $this->db->getFieldNames($table);
        foreach($fields as $field) :
            if(in_array($field, $db_fields)) :
                $rules[$field] = [
                    'label'  => $field,
                    'rules'  => "required",
                    'errors' => [
                        'required' => 'Le champ [{field}] est requis.',
                    ],
                ];
                if(!empty($id)) :
                    $rules[$field]['rules'] = "required|is_unique[$table.$field, $pk, $id]";
                    $rules[$field]['errors']['is_unique'] = "La valeur du champ [{field}] existe déjà dans cette liste.";
                endif;
            endif;
        endforeach;

        $fields = ['cp', 'nationalite_m', 'nationalite_f'];
        foreach($fields as $field):
            if(in_array($field, $db_fields)) :
                $rules[$field] = [
                    'label'  => $field,
                    'rules'  => "required",
                    'errors' => [
                        'required' => 'Le champ [{field}] est requis.',
                    ],
                ];
            endif;
        endforeach;

        $validation->setRules($rules);

        return $validation;
    }
}

