<?php

namespace Report\Controllers;

use Base\Controllers\BaseController;
use Components\Libraries\FormLibrary;
use Components\Libraries\ListLibrary;
use Components\Libraries\PhpdocxLibrary;
use Report\Libraries\MysqlLibrary;
use Report\Libraries\ReportLibrary;
use Report\Models\ReportModel;

class Block extends BaseController 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->FormLibrary = new FormLibrary(__NAMESPACE__);
        $this->ListLibrary = new ListLibrary(__NAMESPACE__);
        $this->report_m = new ReportModel();
        $this->report_l = new ReportLibrary();
        $this->phpdocx = new PhpdocxLibrary();

        $this->datas->context = 'report';
    }

    public function index()
    {
        return redirect()->to(base_url('report/blocks'));
    }

    public function block_download($id_block, $preview_type='word')
    {
        $block = $this->report_m->get_block_by_id($id_block);
        $file = $this->report_m->get_file_by_id($block->id_file);
        $path = PATH_DOCU_DEMANDE . $file->url_file;
        if($preview_type=='pdf') :
            $path = $this->phpdocx->DocxToPdf($path);
        endif;

        $this->phpdocx->download_file($path, 'bloc_' . convert_utf8_to_url($block->label));
    }

    public function block_delete($id_block)
    {
        $this->db->transStart();
        $this->report_m->block_delete($id_block);
        if ($this->db->transComplete() == FALSE) :

            redirect()->to(base_url('report/blocks'))->with('warning', "Le bloc n'a pas pu être supprimé.");

        else :

            redirect()->to(base_url('report/blocks'))->with('success', "Le bloc a bien été supprimé.");

        endif;
    }

    public function file_preview_get($id_file)
    {
        $file = $this->report_m->get_file_by_id($id_file);
        $url = PATH_DOCU_DEMANDE . $file->url_file;

        $html = $this->phpdocx->docx_to_html($url);

        echo $html;
    }

    public function details($id_block)
    {
        if(!empty($this->request->getPost())) :
            $validation = \Config\Services::validation();
            if ($validation->run($this->request->getPost(), $this->module . 'Block') != FALSE) :
                $post = (object) $this->request->getPost();
                $this->db->transStart();
                $this->report_m->block_update_by_id($id_block, $post);
                if ($this->db->transComplete() == FALSE) :

                    redirect()->to($_SERVER["HTTP_REFERER"])->with('warning', "Le bloc n'a pas pu être mis à jour.");

                else :

                    redirect()->to($_SERVER["HTTP_REFERER"])->with('success', "Le bloc a bien été mis à jour.");

                endif;

                @unlink($_FILES);
            endif;
        endif;

        $block = $this->report_m->get_block_by_id($id_block);
        $form_id = 'blockDetailsForm';

        $history = (object) [];
        $history->update_iduser = $block->updated_by;
        $history->updated_at = $block->updated_at;
        $history->create_iduser = $block->created_by;
        $history->created_at = $block->created_at;

        $this->datas->form_id = $form_id;
        $this->datas->id_block = $id_block;
        $this->datas->id_file = $block->id_file;
        $this->datas->controls = $this->report_l->get_form_controls('block', 'details', $block);
        $this->datas->roads_them = $this->ListLibrary->get_list_by_ref('ids_road_them');
        $this->datas->navigation = $this->button_return_list($form_id);
        $this->datas->history =
            $this->FormLibrary->get_details_history_button('blockHistory') . 
            $this->FormLibrary->get_details_history_text('blockHistory', $history);

        return view('Report\block/details', (array) $this->datas);
    }

    private function button_block_update($form_id)
    {
        return '
            <button type="submit" form="' . $form_id . '" class="btn btn-sm btn-info"> 
                ' . t("Enregistrer les modifications du bloc", __NAMESPACE__) . '
            </button>
        ';
    }

    public function block_validation() 
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'label' => ['label' => 'Nom du bloc', 'rules' => 'required'],
            'id_file[]' => ['label' => 'Fichier associé', 'rules' => 'required'],
        ]);
    }

    private function button_return_list()
    {
        $html = '
            <a role="button" class="btn btn-sm btn-dark" href="' . base_url('report/blocks') . '"> 
                Revenir à la liste 
            </a>
        ';

        return $html;
    }

    public function new() 
    {
        $post = (object) [];
        if(!empty($this->request->getPost())) :
            $post = (object) $this->request->getPost();
            $validation = \Config\Services::validation();
            if ($validation->run($this->request->getPost(), $this->module . 'Block') == FALSE) :
                $this->datas->alert = validation_errors();
            else :
                $this->db->transStart();
                $id_block = $this->report_m->block_insert($post);
                if ($this->db->transComplete() == FALSE) :

                    redirect()->to($_SERVER['HTTP_REFERER'])->with('warning', "Le bloc n'a pas pu être créé.");

                else :

                    redirect()->to(base_url('report/block/view/' . $id_block))->with('success', "Le bloc a bien été créé.");

                endif;
            endif;
        endif;

        $this->datas->controls = $this->report_l->get_form_controls('block', 'new', $post);
        $this->datas->roads_them = $this->ListLibrary->get_list_by_ref('ids_road_them');
        $this->datas->navigation = $this->button_return_list();
        
        return view('Report\block/new', (array) $this->datas);
    }

    // public function block_modal_info($id_block)
    // {
    //     $html = '
    //         <div class="alert alert-warning mb-4 text-left"> 
    //             Le style de l\'aperçu peut changer par rapport à celui du fichier Word. Cette différence ne sera pas prise en compte lors de la génération de rapport.
    //         </div>
    //     ';
    //     $block = $this->report_m->get_block_by_id($id_block);
    //     $file = $this->report_m->get_file_by_id($block->id_file);
    //     $url = FCPATH . $this->file_folder . '/' . $file->url_file;

    //     if(file_exists($url)) :
    //         $this->load->library('phpdocx');
    //         $html .= '<div class="border rounded p-5">' . $this->phpdocx->docx_to_html($url) . '</div>';
    //     endif;

    //     $result = (object) [];
    //     $result->dialog_size = 'xl';
    //     $result->body = $html;
    //     $result->header = "Aperçu du bloc";
    //     $result->close_text = "Fermer";
    //     echo json_encode($result);
    // }

    // public function block_info_collapse($id_block)
    // {
    //     $block = $this->report_m->get_block_by_id($id_block);
    //     $html = $this->report_l->get_file_preview_by_id($block->id_file);

    //     echo $html;
    // }

    public function file_modal_info($id_file)
    {
        $html = $this->report_l->get_block_warning() . $this->report_l->get_file_preview_by_id($id_file);

        $result = (object) [];
        $result->dialog_size = 'xl';
        $result->body = $html;
        $result->header = "Aperçu du bloc";
        $result->close_text = "Fermer";

        echo json_encode($result);
    }

    public function block_modal_delete($id_block)
    {
        $block = $this->report_m->get_block_by_id($id_block);
        $html = '
            Vous allez supprimer le block :
            <div class="my-2 w-100 text-center font-weight-bold">' . $block->label . '</div>
            Veuillez confirmer votre action.
        ';

        $result = (object) [];
        $result->body = $html;
        $result->header = "Suppression d'un block";
        $result->footer = '<a role="button" class="btn btn-sm btn-danger" href="' . base_url('report/block_delete/' . $id_block). '"> Confirmer </a>';

        echo json_encode($result);
    }

    public function convert_ids_tag()
    {
        $blocks = $this->db->table($this->t_block)->get()->getResult();
        $pk_block = get_primary_key($this->t_block);
        foreach($blocks as $block) :
            if(!empty(trim($block->ids_tag))) :
                // $ids_tag = str_replace(']]', ']', str_replace('[[', '[', $block->ids_tag));
                $ids_tag = str_replace(',]', ']', str_replace('[,', '[', '[' . $block->ids_tag . ']'));
                $this->db->set('ids_tag_array', $ids_tag)->where($pk_block, $block->id_block)->update('rp_block');
                // _print($this->db->last_query());
            endif;
        endforeach;
    }

    public function get_blocks()
    {
        $blocks = $this->report_m->get_blocks();

        $i = 0;
        foreach($blocks as $block) :
            $block->tags = $this->get_block_tags($block);
            $block->tagnames = $this->get_block_tagnames($block);
            $block->them_paths = $this->get_block_them_paths($block);
            $block->create_username = sessionUser($block->created_by) ? sessionUser($block->created_by)->username : '';
            $block->update_username = sessionUser($block->updated_by) ? sessionUser($block->updated_by)->username : '';
            $block->updated_at = date('d/m/y à H:i', strtotime($block->updated_at));
            $block->td_delete = $this->get_block_td_delete($block);
            // $block->td_label = view('Report\block/td_label', (array) $block);
            $block->td_details = view('Report\block/td_details', (array) $block);
            $blocks[$i] = $block;
            $i++;
        endforeach;

        $result = (object) [];
        $result->data = $blocks;
        echo json_encode($result);
    }

    private function get_block_them_paths($block)
    {
        $them_paths = [];
        $this->load->add_package_path(APPPATH . 'modules/fe');
        $this->load->library('TesorusLibrary');
        if(!empty($block->ids_road_them)) :
            foreach($block->ids_road_them as $id_them) :
                $them_paths[] = $this->TesorusLibrary->get_path_by_id_road('them', $id_them);
            endforeach;
        endif;
        $this->load->add_package_path(APPPATH . 'modules/fe');

        return implode('<br>', $them_paths);
    }

    private function get_block_tags($block)
    {
        $tags = [];
        if(!empty($block->ids_tag)) :
            foreach($block->ids_tag as $id_tag) :
                $tag = $this->report_m->get_tag_by_id($id_tag);
                $tags[] = $tag;
            endforeach;
        endif;

        return $tags;
    }

    private function get_block_tagnames($block)
    {
        $tagnames = [];
        if(!empty($block->ids_tag)) :
            foreach($block->ids_tag as $id_tag) :
                $tag = $this->report_m->get_tag_by_id($id_tag);
                $tagnames[] = $tag->label;
            endforeach;
        endif;

        return implode(', ', $tagnames);
    }

    private function get_block_td_delete($block)
    {
        $reports = $this->report_m->get_reports_by_id_block($block->id_block);
        if(!empty($reports)) return '';
        
        $html = view('Report\block/td_delete', (array) $block);

        return $html;
    }

    public function list()
    {
        $title = "Liste des blocs";
        $this->layout_library->set_title($title);
        $this->layout_library->set_subtitle(t($title, __NAMESPACE__), $this->button_block_new());
         
        return view('Report\block/list');
    }

    private function button_block_new()
    {
        $html = '
            <a role="button" class="btn btn-sm btn-info"
                href="' . base_url('report/block_new') . '"
                >
                ' . t("Nouveau bloc", __NAMESPACE__) . '
            </a>
        ';

        return $html;
    }

    private function button_block_update_cancel()
    {
        $html = '
            <button type="button" class="btn btn-sm btn-outline-secondary"
                onclick="window.location.reload();"
                >
                ' . t("Annuler les modificatons", __NAMESPACE__) . '
            </button>
        ';

        return $html;
    }

    public function tag_list()
    {
        $title = "Liste des tags de bloc";
        $this->layout_library->set_title($title);
        $this->layout_library->set_subtitle($title, $this->button_tag_new());	    
        $this->layout_library->view('tag/table');        
    }
    
    private function button_tag_new()
    {
        return '
            <button type="button" class="btn btn-sm btn-info" title="Ajouter un nouveau tag"
                onclick="tag_modal_new();"
                > 
                Nouveau tag
            </button>
        ';
    }

    public function tag_modal_new()
    {
        $form_id = 'tagNewForm';

        $data = (object) [];
        $data->form_id = $form_id;
        $data->controls = $this->report_l->get_form_controls('tag', 'new');
        $html = view('Report\tag/new', (array) $data);

        $result = (object) [];
        $result->body = $html;
        $result->header = "Ajouter un tag";
        $result->footer = '
            <button type="button" class="btn btn-sm btn-success" form="' . $form_id . '"
                onclick="tag_new(this);"
                > 
                Confirmer 
            </button>
        ';

        echo json_encode($result);
    }

    public function block_tag_modal_new($id_block=null)
    {
        if(empty($id_block)) $id_block = 'null';
        $form_id = 'tagNewForm';

        $data = (object) [];
        $data->form_id = $form_id;
        $data->controls = $this->report_l->get_form_controls('tag', 'new');
        $html = view('Report\tag/new', (array) $data);

        $result = (object) [];
        $result->body = $html;
        $result->header = "Ajouter un tag";
        $result->footer = '
            <button type="button" class="btn btn-sm btn-success" form="' . $form_id . '"
                onclick="block_tag_new(this, ' . $id_block . ');"
                > 
                Confirmer 
            </button>
        ';

        echo json_encode($result);
    }

    public function tag_new()
    {
        $post = (object) $this->request->getPost();

        $this->db->transStart();
        $id_tag = $this->report_m->tag_insert($post);
        if ($this->db->transComplete() == FALSE) :
            $this->session->setFlashdata('warning', "Le tag n'a pas pu être ajouté.");
        else :
            $this->session->setFlashdata('success', "Le tag a bien été ajouté.");
        endif;

        echo $id_tag;
    }

    public function block_tag_new($id_block=null)
    {
        $post = (object) $this->request->getPost();
        $id_tag = $this->report_m->tag_insert($post);

        $tag = $this->report_m->get_tag_by_id($id_tag);

        echo json_encode($tag);
    }

    public function get_tags()
    {
        $tags = $this->report_m->get_tags();

        $i=0;
        foreach($tags as $tag):
            $tag->nb_blocks = count($tag->blocks);
            $tag->td_blocks = $this->get_tag_td_blocks($tag->blocks);
            $tag->td_delete = view('Report\tag/td_delete', (array) $tag);
            $tags[$i] = $tag;
            $i++;
        endforeach;
        
        $result = (object) [];
        $result->data = $tags;
        echo json_encode($result);
    }

    private function get_tag_td_blocks($blocks)
    {
        $links = [];
        foreach($blocks as $block) :
            $links[] = '
                <a href="' . base_url('report/block/view/' . $block->id_block) . '" target="_blank">
                    ' . $block->label . '
                </a>
            ';
        endforeach;

        return implode(', ', $links);
    }

    public function tag_modal_delete($id_tag)
    {
        $tag = $this->report_m->get_tag_by_id($id_tag);
        $html = '
            Vous allez supprimer le tag : <div class="d-inline font-weight-bold">' . $tag->label . '</div>. <br>
            Veuillez confirmer votre action.
        ';

        $result = (object) [];
        $result->body = $html;
        $result->header = "Suppression d'un tag";
        $result->footer = '<a role="button" class="btn btn-sm btn-danger" href="' . base_url('report/tag_delete/' . $id_tag). '"> Confirmer </a>';

        echo json_encode($result);
    }

    public function tag_delete($id_tag)
    {
        $this->db->transStart();
        $this->report_m->tag_delete($id_tag);
        if ($this->db->transComplete() == FALSE) :
            $this->session->setFlashdata('warning', "Le tag n'a pas pu être supprimé.");
        else :
            $this->session->setFlashdata('success', "Le tag a bien été supprimé.");
        endif;

        return redirect()->to(base_url('report/tags')); 
    }

}