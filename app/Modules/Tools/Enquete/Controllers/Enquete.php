<?php

namespace Enquete\Controllers;

use Base\Controllers\BaseController;
use Components\Libraries\DatabaseLibrary;
use DataView\Libraries\DataViewConstructor;
use Enquete\Libraries\EnqueteLibrary;
use Enquete\Libraries\MysqlLibrary;
use Enquete\Models\EnqueteModel;

class Enquete extends BaseController 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->EnqueteModel = new EnqueteModel();
        $this->EnqueteLibrary = new EnqueteLibrary();

        $this->datas->context = "enquete";
    }

    // public function mysql()
    // {
    //     $db_l = new DatabaseLibrary(__NAMESPACE__);
    //     $file = $this->path . 'Config/Json/question/table.json';
    //     if(!file_exists($file)) $db_l->print_columns_from_table($this->t_question, $file);
    // }
    
    public function EnqueteList()
    {
        $DataView = new DataViewConstructor();
        
        $this->session->remove('filter');

        $context_sub = 'enquete';
        $columns = [
            "label_fr" => ["Label", true],
            "nb_questions" => ["Nb de questions", false],
            "action" => ["", false],
        ];
        $order = $DataView->GetOrderDefault($columns);
        $enquetes = $this->EnqueteModel->EnquetesGet($order, $this->request);
        $pager = $this->EnqueteModel->EnquetesPagerGet();

        $this->datas->columns = $columns;
        $this->datas->context_sub = 'enquete';
        $this->datas->enquetes = $enquetes;
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);
        $this->datas->nb_enquetes = !empty($pager) ? $pager->getTotal() : count($enquetes);
        $this->datas->pager = $pager;
        $this->datas->titleView = "Module Enquête - Liste des formulaires d'enquête";

        return view('Enquete\enquete-list', (array) $this->datas);        
    }

    public function EnqueteIframe($id_enquete, $lang)
    {       
        $enquete = $this->EnqueteModel->EnqueteGet($id_enquete);

        $questions = [];
        if(!empty($enquete->ids_question)) :
            foreach($enquete->ids_question as $id_question) :
                $questions[] = $this->EnqueteModel->QuestionGet($id_question);
            endforeach;
        endif;

        $this->datas->enquete = $enquete;
        $this->datas->lang = $lang;
        $this->datas->questions = $questions;

        return view('Enquete\enquete-iframe', (array) $this->datas);
    }

    public function EnqueteViewModal($id_enquete)
    {
        $enquete = $this->EnqueteModel->EnqueteGet($id_enquete);
        $this->datas->enquete = $enquete;
        $this->datas->question_list = $this->EnqueteModel->QuestionsGet();

        // $data = (object) [];
        // $data->lang = LanguageSessionGetuage();
        // $data->enquete = $enquete;
        // $data->form = $this->EnqueteLibrary->get_form('details', $lang, $company);
        
        $result = (object) [];
        $result->body = view('Enquete\enquete-view', (array) $this->datas);
        $result->title = "Détails du questionnaire \"" . $enquete->path_fr . "\"";
        $result->submit = '<button class="btn btn-sm btn-success" form="enqueteUpdateForm" onclick="waiting_start(this);"> Modifier </button>';

        echo json_encode($result);
    }

    public function form_update($id_enquete)
    {
        $post = database_decode($this->request->getPost());
        
        $this->db->transStart();
        $this->EnqueteModel->EnqueteSave($post, $id_enquete);
        if ($this->db->transComplete() == FALSE) :
            $messageType = 'warning';
            $message = "Le questionnaire n'a pas pu être mis à jour.";
        else :
            $messageType = 'success';
            $message = "Le questionnaire a bien été mis à jour.";
        endif;
        
        return redirect()->to(base_url('enquete/enquetes'))->with($messageType, $message);        
    }
    
    public function QuestionList()
    {
        $DataView = new DataViewConstructor();

        $this->session->remove('filter');

        $context_sub = 'question';
        $columns = [
            "num_question" => ["N°", true, 'asc'],
            "name_question" => ["Référence", true],
            "type_question_label" => ["Type", true],
            "question_fr" => ["Texte FR", true],
            "aide_question_fr" => ["Aide FR", true],
            "nb_enquetes" => ["Nbre de <br> formulaires reliés", true],
            "action" => ["", false],
        ];
        $order = $DataView->GetOrderDefault($columns);
        $questions = $this->EnqueteModel->QuestionsGet($order, $this->request);
        $pager = $this->EnqueteModel->EnquetesPagerGet();

        $this->datas->columns = $columns;
        $this->datas->context_sub = $context_sub;
        $this->datas->questions = $questions;
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);
        $this->datas->nb_questions = !empty($pager) ? $pager->getTotal() : count($questions);
        $this->datas->pager = $pager;
        $this->datas->titleView = "Module Enquête - Liste des questions d'enquête";

        return view('Enquete\question-list', (array) $this->datas);

        // $this->session->remove('filter');

        // $questions = $this->EnqueteModel->QuestionsGet();
        // $enquetes = $this->EnqueteModel->EnquetesGet();
        
        // foreach($enquetes as $enquete) :
        //     foreach($questions as $question):
        //         if(!empty($enquete->ids_question) && in_array($question->id_question, $enquete->ids_question)) :
        //             $question->enquetes[] = $enquete->path_fr;
        //         endif;
        //     endforeach;
        // endforeach;

        // $this->datas->questions = $questions;

        // return view('Enquete\question-list', (array) $this->datas);
    }
    
    public function QuestionModal($id_question=null)
    {
        $data = (object) [];
        $data->form = !empty($id_question) ? 'details' : 'new';
        if(!empty($id_question)) :
            $data->question = $this->EnqueteModel->QuestionGet($id_question);
        else :
            $question = $this->db->table($this->t_question)->selectMax('num_question')->get()->getRow();
            $data->next_num_question = $question->num_question + 1;
        endif;
        $data->type_question_list = $this->db->table($this->t_list_question_type)
            ->select('id_type as id, type as label_fr')
            ->whereIn('id_type', [1, 2, 6])
            ->get()->getResult();
        
        $result = (object) [];
        $result->body = view('Enquete\question-view', (array) $data);
        $result->title = !empty($id_question) ? 'Question : ' . $data->question->name_question : 'Nouvelle question';
        $button_text = !empty($id_question) ? 'Modifier' : 'Ajouter';
        $result->submit = '
            <button class="btn btn-sm btn-success" form="questionForm" onclick="waiting_start(elem);"> 
                ' . $button_text . ' 
            </button>
        ';

        echo json_encode($result);
    }
    
    // public function question_new_modal()
    // {
    //     $type_available = ['slider', 'radio', 'textarea'];
    //     $type_question_list = $this->db->table($this->t_list_question_type)->select('id_type as id, type as label_fr')->get()->getResult();

    //     $data = (object) [];
    //     $data->form = 'new';
        
    //     $type_question_list = [];
    //     foreach($type_question_list as $question_type) :
    //         if(in_array($question_type->label_fr, $type_available)) :
    //             $type_question_list[] = $question_type;
    //         endif;
    //     endforeach;
    //     $data->type_question_list = $type_question_list;

        

    //     $result = (object) [];
    //     $result->body = view('Enquete\question-view', (array) $data);
    //     $result->title = 'Nouvelle question';
    //     $result->submit = '<button class="btn btn-sm btn-success" form="questionNewForm"> Ajouter </button>';
        
    //     echo json_encode($result);
    // }
    
    public function QuestionUpdate($id_question)
    {
        $validation = \Config\Services::validation();
        if ($validation->run($this->request->getPost(), 'EnqueteQuestion') == TRUE) :
            $post = database_decode($this->request->getPost());
            $this->db->transStart();
            $this->EnqueteModel->QuestionSave($post, $id_question);
            if($this->db->transComplete() == FALSE) :
                $messageType = 'warning';
                $message = "La question n'a pas pu être mise à jour.";
            else :
                $messageType = 'success';
                $message = "La question a bien été mise à jour.";
                return redirect()->to(base_url('enquete/questions'))->with($messageType, $message);
            endif;
        else :   
            $messageType = 'warning';
            $message = "La question n'a pas pu être mise à jour. <br>";
            $message .= !empty($validation->listErrors()) ?  $validation->listErrors() : '';
        endif;
    }
    
    public function QuestionNew()
    {
        if(!$this->Autorisation->is_autorise('enquete_form_c')) return redirect()->to(base_url('enquete/questions'))->with('danger', "Vous n'êtes pas autorisé à créer des questions de formulaire.");
        
        $validation = \Config\Services::validation();
        if ($validation->run($this->request->getPost(), $this->module . 'Question') == TRUE) :
            $post = database_decode($this->request->getPost());
            $this->db->transStart();
            $this->EnqueteModel->QuestionSave($post);
            if ($this->db->transComplete() == FALSE) :
                $messageType = 'warning';
                $message = "La question n'a pas pu être créée.";
            else :
                $messageType = 'success';
                $message = "La question a bien été créée.";
                return redirect()->to(base_url('enquete/questions'))->with($messageType, $message);
            endif;
        else :   
            $messageType = 'warning';
            $message = "La question n'a pas pu être créée. <br>";
            $message .= !empty($validation->listErrors()) ?  $validation->listErrors() : '';
        endif;
    }
    
    public function QuestionDelete($id_question)
    {
        $this->db->transStart();
            $question = $this->EnqueteModel->QuestionGet($id_question);
            $this->db->table($this->t_question)->where('id_question', $id_question)->delete();
            $columns = $this->db->getFieldNames($this->t_answer);
            if(in_array($question->name_question, $columns)) :
                $this->forge->dropColumn($this->t_answer, $question->name_question);
            endif;
        if ($this->db->transComplete() == FALSE) :
            $this->session->setFlashdata('warning', "La question n'a pas pu être supprimée.");
        else :
            $this->session->setFlashdata('success', "Le question a bien été supprimée.");
        endif;
    }
}
