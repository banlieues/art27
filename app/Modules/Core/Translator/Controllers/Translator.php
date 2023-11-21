<?php

namespace Translator\Controllers;

use Base\Controllers\BaseController;
use DataView\Libraries\DataViewConstructor;
use Translator\Libraries\TranslatorLibrary;
use Translator\Models\TranslatorModel;

class Translator extends BaseController 
{
    protected $dir = APPPATH . 'modules/translator';
    protected $t_translator = 'translator';

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->TranslatorLibrary = new TranslatorLibrary();
        $this->TranslatorModel = new TranslatorModel();

        $this->datas->context = 'translator';
    }

    public function LanguageSet($locale)
    {
        $this->session->set('loggedUserLocale', $locale);

        echo true;
    }

    public function export()
    {
        $this->TranslatorLibrary->export();
    }
    
    public function import()
    {
        $this->TranslatorLibrary->import();
    }

    public function import_modal()
    {
        $form = "translatorImportFile";

        $result = (object) [];
        $result->header = 'Importation des traductions';
        $result->body = '
            <div class="alert alert-warning mb-4">
                Le fichier doit impérativement être de format .csv avec un point-virgule comme séparateur et des doubles guillemets pour encadrer les champs. La première colonne contient les champs en français, la seconde ceux en néerlandais.
            </div>
            <form id="' . $form . '" action="' . base_url('translator/import') . '" method="post" enctype="multipart/form-data">
                <input class="form-control-file" type="file" name="translator_file"/>
            </form>
        ';
        $result->footer = '<button class="btn btn-success" form="' . $form . '"> Importer </button>';

        echo json_encode($result);
    }

    public function RowDelete($id_transl)
    {
        $this->TranslatorModel->RowDelete($id_transl);
    }

    public function RowDeleteModal($id_transl)
    {
        $row = $this->TranslatorModel->RowGet($id_transl);

        $result = (object) [];
        $result->header = "Supprimer la ligne de traduction";
        $result->body = "
            Vous êtes sur le point de supprimer la ligne de traduction <br><br>
            <div class='text-center fw-bold'> $row->label_fr </div> <br>
            Veuillez confirmer votre action.
        ";
        $result->footer = '
            <button type="button" 
                class="btn btn-sm btn-danger mx-1" 
                onclick="row_delete(this, ' . $id_transl . ');"
                nb_columns="' . count($this->RowColumns()) . '"
                data-bs-dismiss="modal"
                > 
                Confirmer
            </button>
        ';
        
        echo json_encode($result);
    }

    public function RowView($id_transl=null)
    {
        if(!empty($this->request->getPost())) :
            $post = database_decode($this->request->getPost());
            $this->TranslatorModel->RowSave($post, $id_transl);
        endif;

        $this->datas->columns = $this->RowColumns();
        $this->datas->row = $this->TranslatorModel->RowGet($id_transl);

        $view = view('Translator\row-tr', (array) $this->datas);

        echo $view;
    }

    public function RowColumns()
    {
        $columns = [
            "updated_at" => [t("Màj le", __NAMESPACE__), true],
            "updated_by_nom" => [t("Màj par", __NAMESPACE__), true],
            "module" => [t("Module", __NAMESPACE__), true],
            "ref" => [t("Référence", __NAMESPACE__), true],
            "label_fr" => [t("Label FR", __NAMESPACE__), true, 'asc'],
            "label_nl" => [t("Label NL", __NAMESPACE__), true],
            "action" => ["", false],
        ];

        return $columns;
    }

    public function RowList()
    {
        $DataView = new DataViewConstructor();
        
        if(!empty($this->request->getPost())) :
            $data = (object) $this->request->getPost();
            $posts = $data->rows;

            $this->db->transStart();
            foreach($posts as $post) :
                $post = (object) $post;
                $this->TranslatorModel->update($post);
            endforeach;
            if ($this->db->transComplete() == FALSE) :
                $this->session->setFlashdata('warning', "Les champs de traduction n'ont pas pu être modifiées.");
            else :
                $this->session->setFlashdata('success', "Les champs de traduction ont bien été modifiées.");
            endif;

            header('Location: ' . $_SERVER["HTTP_REFERER"] );
        endif;

        $columns = $this->RowColumns();
        $order = $DataView->GetOrderDefault($columns);
        $rows = $this->TranslatorModel->RowsGet($order, $this->request);
        $pager = $this->TranslatorModel->RowsPagerGet();

        $this->datas->rows = $rows;
        $this->datas->columns = $columns;
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);
        $this->datas->isEmpty = !empty($this->request->getGet('isEmpty')) ? true : false;
        $this->datas->nb_rows = !empty($pager) ? $pager->getTotal() : count($rows);
        $this->datas->pager = $pager;
        $this->datas->titleView = t("Liste de traduction", __NAMESPACE__);

        return view('Translator\row-list', (array) $this->datas);

    }

    public function RowViewModal($id_transl)
    {
        $row = $this->TranslatorModel->RowGet($id_transl);

        $form_id = 'translationForm';

        $data = (object) [];
        $data->form_id = $form_id;
        $data->row = $row;

        $result = (object) [];
        $result->dialog_size = 'lg';
        $result->header = 'Traduction';
        $result->body = view('Translator\form', (array) $data);
        $result->footer = '
            <button type="button" 
                class="btn btn-sm btn-success"
                form="' . $form_id. '" 
                onclick="translator_save(this, ' . $row->id_transl . ');" 
                > 
                ' . t("Enregistrer la traduction", __NAMESPACE__) . '
            </button>
        ';
    
        echo json_encode($result);
    }

    public function RowSave($id_transl)
    {
        $validation = \Config\Services::validation();
        if ($validation->run($this->request->getPost(), 'TranslatorRow') == true) :
            $post = (object) $this->request->getPost();
            $id_transl = $this->TranslatorModel->RowSave($post, $id_transl);
            $row = $this->TranslatorModel->RowGet($id_transl);

            echo json_encode($row);
        else :
            $data = (object) [];
            $data->invalid_fields = $validation->getErrors();

            echo json_encode($data);
        endif;
    }

}
