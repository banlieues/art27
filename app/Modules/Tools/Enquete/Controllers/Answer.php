<?php

namespace Enquete\Controllers;

use Base\Controllers\BaseController;
use DataView\Libraries\DataViewConstructor;
use Enquete\Libraries\EnqueteLibrary;
use Enquete\Libraries\FilterLibrary;
use Enquete\Libraries\MysqlLibrary;
use Enquete\Models\AnswerModel;
use Enquete\Models\EnqueteModel;
use Mailing\Libraries\DemandeLibrary;
use Mailing\Libraries\TemplateLibrary;

class Answer extends BaseController 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $this->AnswerModel = new AnswerModel();
        $this->EnqueteModel = new EnqueteModel();
        $this->EnqueteLibrary = new EnqueteLibrary();
        $this->FilterLibrary = new FilterLibrary();

        $this->datas->context = 'enquete';
    }

    public function DemandeCloseTest()
    {
        $debug = $this->EnqueteLibrary->DemandeClose(170836);
        debugd($debug);
    }

    public function AnswerViewModalSuggestions($id_answer)
    {
        $answer = $this->AnswerModel->AnswerGet($id_answer);
        $view = '';
        $view .= '<h6 class="fw-bold"> Suggestions </h6>';
        $view .= !empty($answer->suggestions) ? nl2br($answer->suggestions) : '';

        echo $view;
    }

    public function correction()
    {
        $this->load->dbforge();
        $queries = [];
        $questions = $this->EnqueteModel->QuestionsGet();
        foreach($questions as $question) :
            if($question->type_question==1) :
                $queries[] = "UPDATE " . $this->t_answer . " SET $question->name_question = 10 WHERE $question->name_question > 10; ";
                $queries[] = "UPDATE " . $this->t_answer . " SET $question->name_question = 0 WHERE $question->name_question < 0; ";
            endif;
        endforeach;

        foreach($queries as $query) :
            // $this->db->query($query);
            if($this->db->query($query)) echo '<br> OK - ' . $query . '<br>';
            else echo '<br> ERROR - ' . $query . '<br>';
        endforeach;
    }
        
    public function SendList($isReset=0)
    {
        $DataView = new DataViewConstructor();

        $this->FilterLibrary->set_session($isReset);
        $context_sub = 'send';
        $columns = [
            "date_envoi" => ["Date d'envoi", true, 'desc'],
            "statut_answer" => ["Statut", true],
            "delay" => ["Délai (jours)", false],
            "langue" => ["Langue", true],
            "demandeur_lastname" => ["Demandeur", true],
            "path_fr" => ["Demande", true],
            "nom" => ["Conseiller en charge", true],
        ];
        $order = $DataView->GetOrderDefault($columns);
        $answers = $this->AnswerModel->AnswersGet($order, $this->request);
        $pager = $this->AnswerModel->AnswersPagerGet();

        $this->datas->answers = $answers;
        $this->datas->columns = $columns;
        $this->datas->context_sub = $context_sub;
        $this->datas->totals = $this->FilterLibrary->get_totals();
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);
        $this->datas->filter_active = $this->FilterLibrary->get_session_html();
        $this->datas->nb_answers = !empty($pager) ? $pager->getTotal() : count($answers);
        $this->datas->pager = $pager;
        $this->datas->target = $this->FilterLibrary->get_target_object($context_sub);

        return view($this->module . '\answer-list', (array) $this->datas);
    }

    public function AnswerList($isReset=0)
    {
        $DataView = new DataViewConstructor();

        $this->FilterLibrary->set_session($isReset);
        $questions = $this->EnqueteModel->QuestionsGet();

        $context_sub = 'answer';
        $columns = [
            "date_reponse" => ["Date de la réponse", true, 'desc'],
            "id_answer" => ["ID réponse", true],
            "id_demande" => ["ID demande", true],
            "suggestions" => ["Notes", true],
        ];
        foreach($questions as $question) : 
            if($question->name_question!='suggestions') :
                $columns[$question->name_question] = ["Q" . $question->num_question, true, null, $question->question_fr];
            endif;
        endforeach;

        $order = $DataView->GetOrderDefault($columns);
        $answers = $this->AnswerModel->AnswersFilledGet($order, $this->request);
        $pager = $this->AnswerModel->AnswersPagerGet();

        $this->datas->answers = $answers;
        $this->datas->columns = $columns;
        $this->datas->context_sub = $context_sub;
        $this->datas->totals = $this->FilterLibrary->get_totals();
        $this->datas->getTh = $DataView->SetOrderTh($columns, $this->request);
        $this->datas->filter_active = $this->FilterLibrary->get_session_html();
        $this->datas->nb_answers = !empty($pager) ? $pager->getTotal() : count($answers);
        $this->datas->pager = $pager;
        $this->datas->questions = $questions;
        $this->datas->target = $this->FilterLibrary->get_target_object($context_sub);

        return view($this->module . '\answer-summary', (array) $this->datas);
    }

    // public function summary_paginate_get()
    // {
    //     $dt_param = $this->get_datatables_param();
    //     $answers = $this->AnswerModel->answers_paginate_get($dt_param);
    //     $questions = $this->EnqueteModel->QuestionsGet();
    //     foreach($answers as $answer) :
    //         foreach($answer as $key=>$value) :
    //             foreach($questions as $question)
    //                 if($key == $question->name_question) $answer->$key = $this->EnqueteLibrary->get_answer_by_question_type($question, $value);
    //         endforeach;
    //     endforeach;
    //     $result = $this->get_datatables_paginate_result($this->t_answer, $answers, $dt_param);

    //     echo json_encode($result);
    // }

    // private function get_datatables_paginate_result($table, $datas, $dt_param)
    // {        
    //     $totalRecords = $this->db->table($table)->countAll();

    //     $results = [];
    //     $totalRecordwithFilter = 0;
    //     $row = $dt_param->rowStart;

    //     if(empty($dt_param->searchValue)) :
    //         $i=0;
    //         foreach($datas as $data) :
    //             if($i>=$dt_param->rowStart && $i<$dt_param->rowStart+$dt_param->rowsPerPage) $results[] = $data;
    //             $totalRecordwithFilter++;
    //             $i++;
    //         endforeach;
    //     else :
    //         $i=0;
    //         foreach($datas as $data) :
    //             if($this->get_datatables_search($dt_param->searchValue, $data)) :
    //                 if($i>=$dt_param->rowStart && $i<$dt_param->rowStart+$dt_param->rowsPerPage) $results[] = $data;
    //                 $totalRecordwithFilter++;
    //                 $i++;
    //             endif;
    //         endforeach;
    //     endif;

    //     ## Response
    //     $response = (object) [];
    //     $response->draw = intval($dt_param->draw);
    //     $response->recordsTotal = intval($totalRecords);
    //     $response->recordsFiltered = intval($totalRecordwithFilter);
    //     $response->data = $results;
        
    //     return $response;
    // }

//     private function get_datatables_param()
//     {
//         $dt_param = (object) $this->request->getPost();
// // _print($dt_param); die;
//         ## Read value
//         $data = (object) [];
//         $data->draw = $dt_param->draw;
//         $data->rowStart = $dt_param->start;
//         $data->rowsPerPage = $dt_param->length; // Rows display per page
//         $data->columnIndex = $dt_param->order[0]['column']; // Column index
//         $data->columnName = $dt_param->columns[$data->columnIndex]['data']; // Column name
//         $data->columnSortOrder = $dt_param->order[0]['dir']; // asc or desc
//         $data->searchValue = $dt_param->search['value']; // Search value

//         return $data;
//     }

//     public function get_datatables_search($string, $answer)
//     {
//         foreach($answer as $key=>$value) :
//             $string_search = mb_strtolower(remove_accents($string));
//             $value_search = mb_strtolower(remove_accents($value));
//             if(strpos($value_search, $string_search) !== false) return true;
//         endforeach;
//         return false;
//     }

    // public function get_answers_summary()
    // {
    //     $answers = $this->AnswerModel->AnswersGet();
    //     $questions = $this->EnqueteModel->QuestionsGet();
    //     $i=0;
    //     foreach($answers as $answer) :
    //         foreach($answer as $key=>$value) :
    //             foreach($questions as $question)
    //                 if($key == $question->name_question) $answers[$i]->$key = $this->EnqueteLibrary->get_answer_by_question_type($question, $value);
    //         endforeach;
    //         $i++;
    //     endforeach;

    //     $json = (object) [];
    //     $json->data = !empty($answers) ? $answers : [];
        
    //     echo json_encode($json);
    // }

    // public function summary($reset=null)
    // {
    //     $this->FilterLibrary->set_session($reset);

    //     $this->datas->data_filter = $this->FilterLibrary->get_filter_list(); 
    //     $this->datas->data_filter->totals = $this->FilterLibrary->get_totals(); 
    //     $this->datas->filter_active = $this->FilterLibrary->get_session_html();
    //     $this->datas->questions = $this->EnqueteModel->QuestionsGet();      
    //     $this->datas->navigation = $this->FilterLibrary->navigation_get('summary');
    //     $this->datas->target = $this->FilterLibrary->get_target_object('summary');

    //     return view($this->module . '\answer/answer_summary', (array) $this->datas);
    // }

    public function AnswerViewModal($id_answer)
    {
        $answer = $this->AnswerModel->AnswerGet($id_answer);
        $questions = $this->EnqueteModel->QuestionsGet();
        // $questions = $this->EnqueteModel->QuestionsGetByEnquete($answer->id_enquete);
        $i=0;
        foreach($questions as $question):
            $question->name_question = str_replace(['pvb_', 'coprop_', 'fu_'], ['', '', ''], $question->name_question);
            foreach($answer as $name=>$value):
                if($name == $question->name_question && $value!=''):
                    $questions[$i]->answer = $this->EnqueteLibrary->get_answer_by_question_type($question, $value);
                    unset($answer->$name);
                endif;
            endforeach;
            $i++;
        endforeach;
        $answer->questions = $questions;
        
        $result = (object) [];
        $result->body = view($this->module . '\answer-view', (array) $answer);
        $result->title = ''
            . '<h4> Réponse de l\'enquête ' . $answer->path_fr . '</h4>'
            . '<small> de ' . $answer->demandeur_name . ' ' . $answer->demandeur_lastname . ' le ' . $answer->date_reponse . '</small>';
            
        echo json_encode($result);
    }
    
    private function set_tags_value($demande, $person)
    {
        $tags['##titre##'] = $person->civilite_fr;
        $tags['##titre_nl##'] = $person->civilite_nl;
        $tags['##prenom##'] = $person->prenom;
        $tags['##nom##'] = $person->nom;
        $tags['##numero_demande##'] = $demande->id_demande;

        if(!empty($demande->path_fr)) $tags['##type_demande_fr##'] = strip_tags($demande->path_fr); else $tags['##type_demande_fr##'] = 'Cloture';
        if(!empty($demande->path_nl)) $tags['##type_demande_nl##'] = strip_tags($demande->path_nl); else $tags['##type_demande_nl##'] = 'Afsluiting';
        return $tags;
    }
    
    public function debug_demande_close()
    {
        $this->db->distinct();
        $this->db->select($this->t_bien_contact_demande_profil . '.id_demande, ' . $this->t_person . '.prenom, ' . $this->t_person . '.nom, ' . $this->t_person . '.email');
        $this->db->join($this->t_bien_contact_demande_profil, $this->t_bien_contact_demande_profil . '.id_demande = demande_h.key_primary', 'left');
        $this->db->join($this->t_person, $this->t_person . '.id_personne = ' . $this->t_bien_contact_demande_profil . '.id_personne', 'left');
        $this->db->where('demande_h.index', 'id_demande_statut');
        $this->db->where('demande_h.value_new', 'Clôturée');
        $this->db->where('demande_h.date_modification BETWEEN "2022-05-10 15:16:00" AND "2022-06-02 00:00:00"');
        $this->db->where($this->t_person . '.email is not null');
        $this->db->where('trim(' . $this->t_person . '.email)<>""');
        $demandes = $this->db->table('demande_h')->get()->getResult();

        $MailingDemandeLibrary = new DemandeLibrary();
        $i=0;
        foreach($demandes as $demande) :
            $answers = $this->db->table($this->t_answer)->where('id_demande', $demande->id_demande)->get()->getResult();
            if(empty($answers)) :
                $road = $MailingDemandeLibrary->get_request_type_by_id_demande($demande->id_demande);
                if(!empty($road)) :
                    $this->demande_close($demande->id_demande);
                    debug($i);
                    debug($demande->prenom . ' ' . $demande->nom . ' <' . $demande->email . '> - Demande : ' . $demande->id_demande);
                    $i++;
                endif;
            endif;
        endforeach;
    }
}
