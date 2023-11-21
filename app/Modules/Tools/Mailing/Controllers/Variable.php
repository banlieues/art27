<?php

namespace Mailing\Controllers;

use Base\Controllers\BaseController;
use DataView\Libraries\DataViewConstructor;
use Mailing\Models\VariableModel;

class Variable extends BaseController 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->VariableModel = new VariableModel();

        $this->datas->context = 'mailing';
    }

    public function variable_delete($id_var)
    {
        $this->db->transStart();
        $this->MailingModel->variable_delete($id_var);
        $this->db->transComplete();
        if ($this->db->transStatus() == FALSE) :

            return redirect()->to(base_url('mailing/variables'))->with('warning', "La variable n'a pas pu être supprimée.");
        else :

            return redirect()->to(base_url('mailing/variables'))->with('success', "La variable a bien été supprimée.");
        endif;
    }

    public function variable_delete_modal($id_var)
    {
        $variable = $this->MailingModel->variable_get($id_var);

        $result = (object) [];
        $result->body = '
            <p> Vous êtes sur le point de supprimer la variable </p>
            <p class="text-center"><strong>' . $variable->label . '</strong></p>
            <p>Confirmez cette action irréversible.</p>
        ';
        $result->header = "Supprimer une variable";
        $result->footer = '
            <a role="button" class="btn btn-sm btn-danger"
                href="' . base_url('mailing/variable/delete/' . $id_var) . '"
                > 
                Confirmer 
            </a>
        ';

        echo json_encode($result);
    }

    public function variable_import()
    {
        $path = $this->path . 'Import/RapportVisite-Variables_Tags.csv';
        $rows = convert_file_csv_to_array($path);
        unset($rows[0]);

        foreach($rows as $row) :
            $variables = $this->db->table($this->t_mt_variable)->where('ref', $row[3])->get()->getResult();
            if(!empty($variables)) continue;

            $post = (object) [];
            $post->ref = $row[3];
            $post->label = $row[2];
            $comments = [];
            if(!empty($row[0]) && in_array($row[0], ['fr', 'nl'])) $comments[] = 'Langue : ' . $row[0];
            if(!empty($row[1])) $comments[] = 'Catégorie : ' . $row[1];
            if(!empty($row[4])) $comments[] = 'Notes : ' . $row[4];
            $post->comment = implode('<br>', $comments);
            $id_var = $this->MailingModel->variable_insert($post);
        endforeach;
    }

    private function get_button_variable_new()
    {
        return '
            <button type="button" class="btn btn-sm btn-info ms-2" title="Ajouter une nouvelle variable"
                onclick="variable_new_modal();"
                > 
                Nouvelle variable
            </button>
        ';
    }

    public function variable_new()
    {
        $post = (object) $this->request->getPost();

        $this->db->transStart();
        $id_var = $this->MailingModel->variable_insert($post);
        $this->db->transComplete();
        if ($this->db->transStatus() == FALSE) :
            $this->session->setFlashdata('warning', "La variable n'a pas pu être ajoutée.");
        else :
            $this->session->setFlashdata('success', "La variable a bien été ajoutée.");
        endif;

        echo $id_var;
    }

    public function variable_new_modal()
    {
        $form_id = 'variableNewForm';

        $data = (object) [];
        $data->form_id = $form_id;
        $data->controls = $this->MailingLibrary->get_form_controls('variable', 'new');
        $html = view('variable/new', (array) $data);

        $result = (object) [];
        $result->body = $html;
        $result->header = "Ajouter une variable";
        $result->footer = '
            <button type="button" class="btn btn-success" form="' . $form_id . '"
                onclick="variable_new(this);"
                > 
                Confirmer 
            </button>
        ';

        echo json_encode($result);
    }

    public function VariableColumns()
    {
        $columns = [
            "created_at" => [t("Créé à", __NAMESPACE__), true, 'desc'],
            "ref" => [t("Expéditeur", __NAMESPACE__), true],
            "label" => [t("Label", __NAMESPACE__), true],
            "comment" => ["Notes", false],
        ];

        return $columns;
    }

    public function VariableList()
    {
        $DataView = new DataViewConstructor();

        $columns = $this->VariableColumns();

        $order = $DataView->GetOrderDefault($columns);
        $variables = $this->VariableModel->VariablesGet($order, $this->request);
        $pager = $this->VariableModel->VariablesPagerGet();

        $this->datas->columns = $columns;
        $this->datas->context_sub = 'variable';
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);
        $this->datas->nb_variables = !empty($pager) ? $pager->getTotal() : count($variables);
        $this->datas->pager = $pager;
        $this->datas->titleView = t("Liste des variable", __NAMESPACE__);
        $this->datas->variables = $variables;

        return view('Mailing\variable-list', (array) $this->datas);
    }

    public function variables_get()
    {
        $variables = $this->MailingModel->variables_get();
        $i = 0;
        foreach($variables as $variable) :
            $variable->td_delete = view('Mailing\variable/td_delete', (array) $variable);
            $variable->create_datehtml = date('d/m/y - H:i', strtotime($variable->created_at));
            $variables[$i] = $variable;
            $i++;
        endforeach;
        
        $json = (object) [];
        $json->data = $variables;

        echo json_encode($json);
    }
}
