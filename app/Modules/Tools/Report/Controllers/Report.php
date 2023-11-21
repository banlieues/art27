<?php

namespace Report\Controllers;

use Report\Libraries\MysqlLibrary;
use Report\Libraries\ReportLibrary;
use Report\Models\ReportModel;
use Base\Controllers\BaseController;
use Components\Libraries\FormLibrary;
use Components\Libraries\ListLibrary;
use Components\Libraries\PhpdocxLibrary;
use Tesorus\Libraries\TesorusLibrary;

class Report extends BaseController 
{
    protected $dir = APPPATH . 'modules/rp';
    
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $mysql = new MysqlLibrary();
        // $mysql->check_database();

        $this->FormLibrary = new FormLibrary(__NAMESPACE__);
        $this->ListLibrary = new ListLibrary(__NAMESPACE__);
        $this->phpdocx = new PhpdocxLibrary();

        $this->report_l = new ReportLibrary();
        $this->report_m = new ReportModel();

        $this->datas->context = 'report';

        // $this->layout_library->add_js('js_report');
    }

    public function index()
    {
        return redirect()->to(base_url('report/templates'));
    }

    public function import_production()
    {
        $this->rp_db_library->import_production();
    }

    public function block_info_collapse($id_report, $id_block)
    {
        $block = $this->report_m->get_block_by_id($id_block);

        // case new block uploaded
        $id_file = $block->id_file;

        // case block already recorded
        $report_block = $this->report_m->get_report_block_by_id_report_id_block($id_report, $id_block);
        if(!empty($report_block)) $id_file = $report_block->id_file;

        $html = $this->report_l->get_file_preview_by_id($id_file);

        echo $html;
    }

    public function block_choice()
    {
        $post = (object) $this->request->getPost();

        if(!empty($post->id_request_block)) return 'ERROR : id_report_block is null.';
        
        $data = (object) [];
        if(!empty($post->is_old)) :
            $data->is_old = $post->is_old;
        else :
            $report_block = $this->report_m->get_report_block_by_id($post->id_report_block);
            $data->id_file = $report_block->id_file;
        endif;
        $this->db->transStart();
        $this->report_m->report_block_update_by_id($post->id_report_block, $data);
        if ($this->db->transComplete() == FALSE) :
            $this->session->setFlashdata('warning', "Le bloc n'a pas pu être mis à jour.");
        else :
            $this->session->setFlashdata('success', "Le bloc a bien été mis à jour.");
        endif;

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function block_modal_choice($id_report_block)
    {
        $report_block = $this->report_m->get_report_block_by_id($id_report_block);
        $form_id = 'blockChoiceForm';

        $data = (object) [];
        $data->form_id = $form_id;
        $data->id_report_block = $id_report_block;
        $file = $this->report_m->get_file_by_id($report_block->id_file);
        $data->file_html = $this->report_l->get_file_preview_by_id($file->id);
        $file_current = $this->report_m->get_file_by_id($report_block->id_file_current);
        $data->file_current_html = $this->report_l->get_file_preview_by_id($file_current->id);

        $result = (object) [];
        $result->dialog_size = 'xl';
        $result->header = "Recherche de bloc";
        $result->body = view('Report\report/block_choice', (array) $data);
        $result->footer = '
            <button class="btn btn-sm btn-success d-none" 
                form="' . $form_id . '"
                >
                ' . t("Sélectionner", __NAMESPACE__) . '
            </button>
        ';
        
        echo json_encode($result);
    }

    public function report_person_linked_get()
    {
        $post = (object) $this->request->getPost();
        $datas = [];
        if(
            !empty($post->id_request) || 
            (empty($post->id_request) && (is_numeric($post->search) || strlen($post->search)>=3))
            ) :
            $persons = $this->report_m->person_search($post);
            foreach($persons as $person) :
                $data = (object) [];
                $data->id = $person->id_personne;
                $data->value = $person->id_personne . ' - ' . $person->prenom . ' ' . $person->nom;
                $datas[] = $data;
            endforeach;
        endif;

        echo json_encode($datas);
    }

    public function report_request_linked_get()
    {
        $post = (object) $this->request->getPost();
        $datas = [];
        if(
            !empty($post->id_person) || 
            (empty($post->id_person) && (is_numeric($post->search) || strlen($post->search)>=3))
            ) :
            $requests = $this->report_m->request_search($post);

            foreach($requests as $request) :
                $data = (object) [];
                $data->id = $request->id_demande;
                $data->value = $request->id_demande . ' - ' . $request->nom;
                $datas[] = $data;
            endforeach;
        endif;

        echo json_encode($datas);
    }

    public function report_download($id_report, $preview_type='word')
    {
        $report = $this->report_m->report_get($id_report);
        $blocks = $this->report_m->get_blocks_by_id_report($id_report);
        $files = [];
        foreach($blocks as $block) :
            $file = $this->report_m->get_file_by_id($block->id_file);
            $files[] = PATH_DOCU_DEMANDE . $file->url_file;
        endforeach;
        
        $userid = session('loggedUserId');
        $temp_url = PATH_DOCU_DEMANDE . 'temp_user' . $userid . '.docx';

        $level = $this->report_m->get_level_var_by_id($report->id_level);
        $level_label = $this->report_m->get_level_label_by_id($report->id_level);

        $this->phpdocx->MergeDocx($files, $temp_url);
        $variables = $this->get_tags_by_person_request($report->id_person, $report->id_request);
        if($level=='publication') :
            $this->phpdocx->CreateDocxFromTemplate($template, $destination, $variables);
            if($preview_type=='pdf') :
                $temp_url = $this->phpdocx->DocxToPdf($temp_url);
            endif;
        endif;
        $this->phpdocx->download_file($temp_url, ucfirst($level_label) . '_' . convert_utf8_to_url($report->label));
    }

    private function get_tags_by_person_request($id_person, $id_request)
    {
        $variables = (object) [];

        $person = $this->report_m->get_person_by_id($id_person);
        $variables->{'Demandeur/Prénom'} = $person->prenom;
        $variables->{'Demandeur/NOM'} = $person->nom;
        $variables->{'Demandeur/Téléphone'} = $person->telephone;
        $variables->{'Demandeur/Téléphones secondaires'} = $person->telephone2;
        $variables->{'Demandeur/Email'} = $person->email;
        $variables->{'Demandeur/Emails secondaires'} = $person->email2;

        $request = $this->report_m->get_request_by_id($id_request);
        $variables->{'Demande/Descriptif demande'} = $request->nom;

        return $variables;
    }

    public function report_duplicate_modal($id_report)
    {
        $report = $this->report_m->report_get($id_report);
        $level_label = $this->report_m->get_level_label_by_id($report->id_level);

        $html = '
            Vous allez dupliquer le ' . $level_label . ' :
            <div class="my-2 w-100 text-center font-weight-bold">' . $report->label . '</div>
            Veuillez confirmer votre action.
        ';

        $result = (object) [];
        $result->body = $html;
        $result->header = "Dupliquer un " . $level_label;
        $result->footer = $this->button_duplicate($id_report);

        echo json_encode($result);

    }

    private function button_duplicate($id_report)
    {
        return '
            <a role="button" class="btn btn-sm btn-warning" href="' . base_url('report/duplicate/' . $id_report). '">
                Confirmer
            </a>
        ';
    }

    public function report_duplicate($id_report)
    {
        $this->db->transStart();
        $report = $this->report_m->report_get($id_report);
        $report->label = $report->label . ' - Copie';
        unset($report->id_report);
        unset($report->updated_at);
        unset($report->updated_by);
        unset($report->created_at);
        unset($report->created_by);
        $id_report_duplicate = $this->report_m->report_insert($report);

        $blocks = $this->report_m->get_blocks_by_id_report($id_report);
        foreach($blocks as $block) :
            $block->id_report = $id_report_duplicate;
        endforeach;
        $this->report_m->report_block_replace($id_report_duplicate, $blocks);        
                
        $level_label = $this->report_m->get_level_label_by_id($report->id_level);
        if ($this->db->transComplete() == FALSE) :

            return redirect()->to(base_url('report/view/' . $id_report))->with('warning', "Le $level_label n'a pas pu être dupliqué.");

        else :

            return redirect()->to(base_url('report/view/' . $id_report_duplicate))->with('success', "Le $level_label a bien été dupliqué.");

        endif;
    }

    public function report_parent_blocks_get($id_parent)
    {
        $fields = json_decode(file_get_contents($this->path . 'Config/Json/report/form.json'));
        $field = $fields->blocks;
        $post = (object) [];
        $post->id_report = $id_parent;
        $html = $this->report_l->get_form_control_blocks($field, $post);
        
        echo $html;
    }

    public function report_parent_thems_get($id_parent)
    {
        $parent = $this->report_m->report_get($id_parent);
        
        echo json_encode($parent->ids_road_them);
    }

    public function block_search_modal($id_report)
    {
        $fields = json_decode(file_get_contents($this->path . '/Config/Json/block/form.json'));
        $field_ids_road_them = $this->FormLibrary->get_form_control_field($fields->ids_road_them, 'ids_road_them', $this->module);
        $field_ids_tag = $this->FormLibrary->get_form_control_field($fields->ids_tag, 'ids_tag', $this->module);

        $controls = (object) [];
        $controls->ids_tag = $this->FormLibrary->get_form_control($field_ids_tag, null);

        $data = (object) [];
        $data->controls = $controls;
        $body = view('Report\report/block_search', (array) $data);

        $result = (object) [];
        $result->dialog_is_full_height = 1;
        // $result->dialog_size = 'xl';
        $result->header = "Recherche de bloc";
        $result->body = $body;
        $result->footer = '
            <button type="button" class="btn btn-sm btn-success d-none" onclick="report_block_search_result(this, ' . $id_report . ');" form="blockResultsForm">
                ' . t("Sélectionner", __NAMESPACE__) . '
            </button>
            <button type="button" class="btn btn-sm btn-dark" onclick="report_block_search(this, ' . $id_report . ');" form="blockSearchForm">
                ' . t("Rechercher", __NAMESPACE__) . '
            </button>
        ';
        
        echo json_encode($result);
    }

    public function block_search()
    {
        $post = (object) $this->request->getPost();
        $blocks = $this->report_m->get_blocks_by_thems_and_tags($post);

        $data = (object) [];
        $data->id_report = $post->id_report;
        $data->blocks = $blocks;
        $view = view('Report\report/block_results', (array) $data);

        echo $view;
    }

    public function block_search_result()
    {
        $post = (object) $this->request->getPost();
        $i = $post->nb_blocks;
        $view = '';
        foreach($post->ids_block as $id_block) :
            $data = (object) [];
            $data->id_report = $post->id_report;
            $data->block = $this->report_m->get_block_by_id($id_block);
            $data->i = $i;
            // _print($data->block); die;
            $view .= view('Report\report/form_blocks_row', (array) $data);
            $i++;
        endforeach;
        
        echo $view;
    }

    public function report_view($id_report)
    {
        $report = $this->report_m->report_get($id_report);
        $level = $this->report_m->get_level_var_by_id($report->id_level);
        $level_label = $this->report_m->get_level_label_by_id($report->id_level);

        if(!empty($this->request->getPost())) :
            $validation = \Config\Services::validation();
            if ($validation->run($this->request->getPost(), $this->module . ucfirst($level)) != FALSE) :
                $post = (object) $this->request->getPost();
                
                $this->db->transStart();
                $this->report_m->report_update($id_report, $post);
                if ($this->db->transComplete() == FALSE) :

                    return redirect()->to($_SERVER["HTTP_REFERER"])->with('warning', "Le $level_label a bien été mis à jour.");

                else :

                    return redirect()->to($_SERVER["HTTP_REFERER"])->with('success', "Le $level_label a bien été mis à jour.");

                endif;
            endif;
        endif;

        $history = (object) [];
        $history->update_iduser = $report->updated_by;
        $history->updated_at = $report->updated_at;
        $history->create_iduser = $report->created_by;
        $history->created_at = $report->created_at;

        $this->datas->report = $report;
        $this->datas->controls = $this->report_l->get_form_controls('report', 'details', $report);
        $this->datas->level = $level;
        $this->datas->level_label = $level_label;
        $this->datas->navigation = $this->button_return_list($report->level);
        $this->datas->history = 
            $this->FormLibrary->get_details_history_button('reportHistory') . 
            $this->FormLibrary->get_details_history_text('reportHistory', $history);

        return view('Report\report/details', (array) $this->datas);
    }

    public function report_delete($id_report)
    {
        $report = $this->report_m->report_get($id_report);

        $this->db->transStart();
        $this->report_m->report_delete($id_report);
        if ($this->db->transComplete() == FALSE) :

            return redirect()->to(base_url('report/' . $report->level . 's'))->with('warning', "Le $report->level_label n'a pas pu être supprimé.");

        else :

            return redirect()->to(base_url('report/' . $report->level . 's'))->with('success', "Le $report->level_label a bien été supprimé.");

        endif;
    }

    public function report_delete_modal($id_report)
    {
        $report = $this->report_m->report_get($id_report);
        $level_label = $this->report_m->get_level_label_by_id($report->id_level);

        $html = '
            Vous allez supprimer le ' . $level_label . ' :
            <div class="my-2 w-100 text-center font-weight-bold">' . $report->label . '</div>
            Veuillez confirmer votre action.
        ';

        $result = (object) [];
        $result->body = $html;
        $result->header = "Suppression d'un " . $level_label;
        $result->footer = '<a role="button" class="btn btn-sm btn-danger" href="' . base_url('report/delete/' . $id_report). '"> Confirmer </a>';

        echo json_encode($result);
    }

    public function reports_get($level)
    {
        $reports = $this->report_m->reports_get($level);

        $datas = [];
        foreach($reports as $report) :
            $data = $report;
            $data->td_delete = view('Report\report/td_delete', (array) $report);
            $data->td_label = view('Report\report/td_label', (array) $report);
            $data->td_thems = !empty($report->ids_road_them) ? implode(', ', $this->get_report_thems_label($report->ids_road_them)) : '';
            if($level == 'publication') :
                $person = $this->report_m->get_person_by_id($report->id_person);
                $data->fullname = $person->prenom . ' ' . $person->nom;
            endif;
            $data->td_details = view('Report\report/td_details', (array) $report);
            $datas[] = $data;
        endforeach;
        
        $result = (object) [];
        $result->data = $datas;

        echo json_encode($result);
    }  

    private function get_report_thems_label($ids_road_them)
    {
        $RoadModel = new \Tesorus\Models\RoadModel('them');
        $roads = $RoadModel->RoadsGetByIds($ids_road_them);
        $labels = array_column($roads, 'label_fr');

        return $labels;
    }

    public function report_list($level='template')
    {
        $this->datas->level = $level;
        $this->datas->level_label = $this->report_m->get_level_label_by_var($level);
        $this->datas->navigation = $this->button_report_new($level);

        // debugd($this->datas);

        // $title = "Liste des " . $this->report_m->get_level_label_by_var($level) . "s";
        // $this->layout_library->set_title($title);
        // $this->layout_library->set_subtitle($title, $this->button_report_new($level));
        // $this->layout_library->view('Report\report/table', $data);

        return view('Report\report/table', (array) $this->datas);
    }

    private function button_report_new($level)
    {
        $newtext = '';
        if(in_array($level, ['schema', 'template'])) $newtext = 'Nouveau';
        elseif(in_array($level, ['publication'])) $newtext = 'Nouvelle';
        return '
            <a class="btn btn-sm btn-info" href="' . base_url('report/' . $level . '/new/') . '"> 
                ' . $newtext . ' ' . $this->report_m->get_level_label_by_var($level) . '
            </a>
        ';
    }

    public function report_validation($level) 
    {
        $level_label = $this->report_m->get_level_label_by_var($level);
        $validation = \Config\Services::validation();
        $validation->set_rules('label', 'Nom du ' . $level_label, 'required');
    }

    private function button_return_list($level)
    {
        $html = '
            <a role="button" class="btn btn-sm btn-dark mb-1 w-100" href="' . base_url('report/' . $level . 's') . '"> 
                Revenir à la liste 
            </a>
        ';

        return $html;
    }

    public function report_new($level) 
    {
        $level_label = $this->report_m->get_level_label_by_var($level);

        $post = (object) [];
        if(!empty($this->request->getPost())) :
            $validation = \Config\Services::validation();
            if ($validation->run($this->request->getPost(), $this->module . ucfirst($level)) == FALSE) :

                $this->datas->alert = validation_errors();
            else :
                $post = (object) $this->request->getPost();
                $this->db->transStart();
                $post->id_level = $this->report_m->get_level_id_by_var($level);
                $id_report = $this->report_m->report_insert($post);
                
                if ($this->db->transComplete() == FALSE) :

                    return redirect()->to(base_url($level . 's'))->with('warning', "Le $level_label n'a pas pu être créé.");

                else :

                    return redirect()->to(base_url('report/view/' . $id_report))->with('success', "Le $level_label a bien été créé.");

                endif;
            endif;
        endif;

        $post->id_level = $this->report_m->get_level_id_by_var($level);
        $this->datas->controls = $this->report_l->get_form_controls('report', 'new', $post);
        $this->datas->roads_them = $this->ListLibrary->get_list_by_ref('ids_road_them');
        $this->datas->level = $level;
        $this->datas->level_label = $level_label;
        $this->datas->navigation = $this->button_return_list($level);

        return view('Report\report/new', (array) $this->datas);
    }
}