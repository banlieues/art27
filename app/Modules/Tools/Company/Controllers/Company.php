<?php

namespace Company\Controllers;

use Base\Controllers\BaseController;
use Company\Libraries\CompanyLibrary;
use Company\Models\CompanyModel;
use Components\Libraries\FileLibrary;
use Components\Libraries\ListLibrary;
use DataView\Libraries\DataViewConstructor;
// use Layout\Libraries\LayoutLibrary;

class Company extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $this->CompanyLibrary = new CompanyLibrary();
        $this->CompanyModel = new CompanyModel();

        $this->datas->context = 'company';
        $this->datas->context_sub = 'company';
        // // IMPORTANT : MUST CHANGE enquete_all_r -> alltesorus when tesorus permission will be created.
        // $this->tesorus_permission = [];
        // if($this->fh_dao->get_autorisation("enquete_all_r")) $this->tesorus_permission[] = 'all';
        $this->datas->tesorus_permission[] = 'all';

    }
    
    public function index()
    {
        return redirect()->to(base_url('company/companies'));
    }

    public function migrate()
    {
        $companies = $this->db->table($this->t_company)->where('ids_file is not null')->get()->getResult();
        foreach($companies as $company) :
            $ids_file = database_decode($company->ids_file);
            $ids_file_new = [];
            if(!empty($ids_file)) :
                foreach($ids_file as $id_file) :
                    $files = $this->db->table($this->email_file)->where('id', $id_file)->get()->getResult();
                    if(empty($files)) continue;
                    $file = filter_post_by_table_fields($this->t_file, $files[0]);
                    $q = $this->db->table($this->t_file);
                    $q->insert($file);
                    // _print($this->db->last_query());
                    $ids_file_new[] = $q->insertID();
                endforeach;
            endif;

            $data = (object) [];
            $data->ids_file = $ids_file_new;
            $data = database_encode($this->t_company, $data);
            $this->db->table($this->t_company)->where('id_company', $company->id_company)->update($data);
            // _print($this->db->last_query());
        endforeach;
    }

    public function file_delete($id_file)
    {
        $file_l = new FileLibrary(__NAMESPACE__);

        $param = (object) $this->request->getPost();
        $file_l->file_delete($param->ref, $id_file);
    }

    // public function modal_translate()
    // {
    //     $post = (object) $this->request->getPost();
    //     $result = $this->tamo_translate->modal_update($post);
    //     echo json_encode($result);
    // }

    // public function translate_field()
    // {
    //     $post = (object) $this->request->getPost();
    //     $t_translation = $post->t_translation;
    //     $post = database_encode($t_translation, $post);
    //     $this->db->where('ref', $post->ref)->update($t_translation, $post);
        
    //     $this->tamo_translate->refresh_session($t_translation);

    //     header('Location: ' . $_SERVER["HTTP_REFERER"] );
    // }

    // public function translation_export()
    // {
    //     $transl = $this->db->table($this->t_translation)->get()->getResult();
    //     $file = $this->dir_uploads . '/co/translation/export.csv';
    //     $this->tamo_translate->export($transl, $file, $this->t_translation);
    // }

    // public function modal_translation_upload()
    // {
    //     $form = "translationUploadFile";
    //     $result['header'] = 'Importation des traductions';
    //     $result['body'] = '
    //         <div class="alert alert-warning mb-4">
    //             Le fichier doit impérativement être de format .csv avec une virgule comme séparateur et des doubles guillemets pour encadrer les champs. La première colonne contient les champs en français, la seconde ceux en néerlandais.
    //         </div>
    //         <form id="' . $form . '" action="' . base_url('co/company/translation_import') . '" method="post" enctype="multipart/form-data">
    //             <input class="form-control-file" type="file" name="translation_file"/>
    //         </form>
    //     ';
    //     $result['footer'] = '<button class="btn btn-success" form="' . $form . '"> Importer </button>';

    //     echo json_encode($result);
    // }

    // public function translation_import()
    // {
    //     $config['upload_path'] = $this->dir_uploads . '/co/translation';
    //     $config['file_name'] = 'import';
    //     $config['allowed_types'] = 'csv';
    //     $config['overwrite'] = true;
    //     $this->load->library('upload', $config);

    //     if(!$this->upload->do_upload('translation_file')) :
    //         $error = array('error' => $this->upload->display_errors());
    //         flashdata('translationUpload', 'warning', "Échec lors d'import des données de traduction. <br>" . implode('<br>', $error));
    //     else :
    //         $file = $config['upload_path'] . '/' . $config['file_name'] . '.' . $config['allowed_types'];
    //         if($this->translation_process($file)) :
    //             $status = 'success';
    //             $message = "Les données de traduction ont bien été chargées.";
    //         else :
    //             $status = 'warning';
    //             $message = "Échec lors d'import des données de traduction. <br> Chaque ligne doit être séparée par un saut à la ligne et les colonnes par une virgule.";
    //         endif;
    //         flashdata('translationUpload', $status, $message);
    //     endif;

    //     header('Location: ' . $_SERVER["HTTP_REFERER"] );
    // }

    // private function translation_process($file)
    // {
    //     $transl = $this->tamo_translate->convert_file_csv_to_array($file);

    //     if(empty($transl) && count($transl)<=1) return false;

    //     $i = 0;
    //     foreach($transl as $row) :
    //         if(!empty($row) && !empty($row[0]) && !empty($row[1])) :
    //             $data = (object) [];
    //             $data->label_nl = $row[1];
    //             $ref = convert_utf8_to_url($row[0]);
    //             $this->db->where('ref', $ref)->update($this->t_translation, $data);
    //         endif;
    //     endforeach;

    //     return true;
    // }

    // public function translate_list()
    // {
    //     $this->load->library('co_translation_lib');
    //     if ($this->request->getPost()) :
    //         $post = (object) $this->request->getPost();
    //         $post_company = (object) $post->{$this->t_translation};
    //         $post_cell = (object) $post->{$this->t_fe_translation};
    //         $this->tamo_translate->update_lines($this->t_translation, $post_company);
    //         $this->tamo_translate->update_lines($this->t_translation, $post_cell);
    //     endif;

    //     $data = (object) [];

    //     $rows_company = $this->db->table($this->t_translation)->get()->getResult();
    //     foreach($rows_company as $row) $row->t_translation = $this->t_translation;
    //     $rows_feature = $this->db->table($this->t_fe_translation)->get()->getResult();
    //     foreach($rows_feature as $row) $row->t_translation = $this->t_fe_translation;
    //     $data->rows = array_merge($rows_company, $rows_feature);

    //     $data->form_id = 'companyTranslate';

    //     $title = 'Traduction des champs';
    //     $title_button = '
    //         <button type="button" class="btn btn-sm btn-secondary mx-1"
    //             onclick="location.reload();"
    //             > 
    //             Annuler les modifications 
    //         </button>
    //         <button form="' . $data->form_id . '" class="btn btn-sm btn-success mx-1"> 
    //             Enregistrer les modifications 
    //         </button>
    //     ';
    //     $this->layout_library->set_title($title);
    //     $this->layout_library->set_subtitle($title, $title_button);      
    //     $this->layout_library->view('translate', $data);
    // }

    // public function export_translation()
    // {
    //     $this->load->library('co_translation_lib');
    //     $this->co_translation_lib->export();
    // }

    // public function import_translation()
    // {
    //     $this->load->library('co_translation_lib');
    //     $this->co_translation_lib->import();
    // }

    public function rule()
    {
        $ListLibrary = new ListLibrary(__NAMESPACE__);

        $columns = json_decode(file_get_contents($this->path . 'Config/Json/deposit/table.json'));
        $column_list = array_keys((array) $columns); 
        
        $fields = json_decode(file_get_contents($this->path . 'Config/Json/deposit/form.json'));

        $post_fields = (object) [];
        $post_lists = (object) [];
        foreach($column_list as $col) :
                if(!empty($fields->$col)) $post_fields->$col = $fields->$col;
                $list = $ListLibrary->get_list_by_ref($col);
                if(!empty($list)) $post_lists->$col = $list;
                if($col=='ids_contact_schedule') :
                    $post_lists->{$col . '_day'} = $ListLibrary->get_list_by_ref($col . '_day');
                    $post_lists->{$col . '_clock'} = $ListLibrary->get_list_by_ref($col . '_clock');
                endif;
        endforeach;

        $this->datas->id_form = 9;
        $this->datas->fields = $post_fields;
        $this->datas->lists = $post_lists;

        return view('Company\rule', (array) $this->datas);
    }

    private function button_delete_confirm($id_company)
    {
        $html = '
            <a role="button" class="btn btn-sm btn-danger mx-1" href="' . base_url('company/company/delete/' . $id_company) . '"> 
                Confirmer
            </a>
        ';
        return $html;
    }

    public function CompanyDeleteModal($id_company)
    {
        $company = $this->CompanyModel->CompanyGet($id_company);
        $result['header'] = "Supprimer une demande";
        $result['body'] = "
            Vous êtes sur le point de supprimer la demande de <br><br>
            <div class='text-center font-weight-bold'> $company->label </div> <br>
            Veuillez confirmer votre action.
        ";
        $result['footer'] = $this->button_delete_confirm($id_company);
        
        echo json_encode($result);
    }

    public function CompanyDelete($id_company)
    {
        $company = $this->CompanyModel->CompanyGet($id_company);
        if(!$this->Autorisation->is_autorise('company_d', $company->created_by)) return redirect()->to($_SERVER['HTTP_REFERER'])->with('danger', "Vous n'êtes pas autorisé à supprimer une fiche entreprise.");

        $this->db->transStart();
        $this->CompanyModel->CompanyDelete($id_company);
        if($this->db->transComplete() == false) :
            $messagetype = 'warning';
            $message = "Échec lors de la suppression de la fiche entreprise.";
        else :
            $messagetype = 'success';
            $message = "La fiche entreprise a bien été supprimée.";
        endif;

        return redirect()->to(base_url('company/companies'))->with($messagetype, $message);
    }

    public function CompaniesExportCsv()
    {
        $companies = $this->CompanyModel->CompaniesExport();
        // debugd($companies);
        $fields = json_decode(file_get_contents($this->path . 'Config/Json/company/form.json'));

        $labels = [];
        foreach($companies[0] as $key=>$value) :
            $is_found = 0;
            foreach($fields as $ref=>$info) :
                if($key==$ref) :
                    $labels[] = $info->label_fr;
                    $is_found = 1;
                    break;
                endif;
            endforeach;
            if(empty($is_found)) :
                if($key=='updated_at') $labels[] = "Date de dernière mise à jour";
                elseif($key=='updated_by') $labels[] = "Modifié par";
                elseif($key=='import_datetime') $labels[] = "Date d'import";
                elseif($key=='created_at') $labels[] = "Date de création";
            endif;
        endforeach;

        $filename = date('Ymd') . '_liste_des_entreprises';

        export_csv($filename, $companies, $labels);
    }

    private function button_export_csv()
    {
        $html = '
            <a role="button" 
                class="btn btn-sm btn-dark mx-1" 
                href="' . base_url('company/companies/export/csv'). '"
                >
                ' . t("Exporter la liste", __NAMESPACE__) . '
            </a>';

        return $html;
    }

    public function CompanyModalComment($id_company)
    {
        $company = $this->CompanyModel->CompanyGet($id_company);
        $view = '';
        $view .= '<h6 class="fw-bold"> Notes </h6>';
        $view .= !empty($company->comment) ? nl2br($company->comment) : '';

        echo $view;
    }

    public function CompanyColumns()
    {
        return [
            "updated_at" => [t("Màj le", __NAMESPACE__), true, 'desc'],
            "user_lastname" => [t("Màj par", __NAMESPACE__), true],
            "created_at" => [t("Création le", __NAMESPACE__), true],
            "id_status" => [t("Statut", __NAMESPACE__), true],
            "label" => [t("Dénomination", __NAMESPACE__), true],
            "id_lang" => [t("Langue", __NAMESPACE__), true],
            "comment" => [t("Notes", __NAMESPACE__), false],
            "action" => ["", false],
        ];
    }

    public function CompanyList()
    {
        $DataView = new DataViewConstructor();

        $columns = $this->CompanyColumns();

        $order = $DataView->GetOrderDefault($columns);
        $companies = $this->CompanyModel->CompaniesGet($order, $this->request);
        $pager = $this->CompanyModel->CompaniesPagerGet();

        $this->datas->columns = $columns;
        $this->datas->companies = $companies;
        $this->datas->nb_companies = !empty($pager) ? $pager->getTotal() : count($companies);
        $this->datas->pager = $pager;
        $this->datas->titleView = t("Ready to renov", __NAMESPACE__) . ' - ' . t("Liste des entreprises", __NAMESPACE__);
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);

        return view('Company\company-list', (array) $this->datas);
    }

    public function CompaniesGet()
    {
        $json['data'] = $this->CompanyModel->CompaniesGet();

        echo json_encode($json);
    }

    public function CompanyView($id_company=null)
    {
        if(!empty($this->request->getPost())) :
            $validation = \Config\Services::validation();
            if(!$this->Autorisation->is_autorise('company_u') && $id_company):
                return redirect()->to($_SERVER['HTTP_REFERER'])->with('danger', "Vous n'êtes pas autorisé à modifier une fiche entreprise.");
            elseif(!$this->Autorisation->is_autorise('company_c') && !$id_company):
                return redirect()->to(base_url('company/companies'))->with('danger', "Vous n'êtes pas autorisé à créer une fiche entreprise.");
            elseif($validation->run($this->request->getPost(), 'CompanyCompany') == TRUE) :
                $post = database_decode($this->request->getPost());
                $this->db->transStart();
                $id_company = $this->CompanyModel->CompanySave($post, $id_company);
                if ($this->db->transComplete() == FALSE) :
                    $messagetype = 'warning';
                    $message = $id_company ? "Échec lors de la mise à jour de l'entreprise." : "Échec lors de la création de la fiche entreprise.";
                else :
                    $messagetype = 'success';
                    $message = $id_company ? "Les modifications de l'entreprise ont bien été enregistrées." : "La fiche entreprise a bien été créée.";
                endif;

                return redirect()->to(base_url("company/company/view/$id_company"))->with($messagetype, $message);
            else :
                $this->datas->validation = $validation;
                $this->session->setFlashdata('danger', implode('<br>', $validation->getErrors()));
            endif;
        endif;

        $company = $id_company ? $this->CompanyModel->CompanyGet($id_company) : $this->request->getPost();

        $init = (object) [];
        $init->module = $this->module;
        $init->form_type = $id_company ? 'details' : 'new';
        $init->form = 'CompanyViewForm';
        $controls = $this->CompanyLibrary->get_form_controls($init, $company);

        $this->datas->formId = 'CompanyViewForm';
        $this->datas->company = $company;
        $this->datas->controls = $controls;
        $this->datas->titleView = $id_company ? t("Ready to renov", __NAMESPACE__) . ' - ' . t("Entreprise", __NAMESPACE__) . ' : ' . $company->label : 'Nouvelle fiche entreprise';

        return view('Company\company-view', (array) $this->datas);          
    }
}

