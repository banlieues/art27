<?php

namespace Enquete\Libraries;

use Base\Libraries\BaseLibrary;
use Enquete\Models\AnswerModel;
use Enquete\Models\EnqueteModel;
use Mailing\Libraries\DemandeLibrary;
use Mailing\Libraries\MailingLibrary;

class EnqueteLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->EnqueteModel = new EnqueteModel();
    }

    public function DemandeClose($id_demande, $isTest='noTest')
    {
        $MailingLibrary = new MailingLibrary();
        $AnswerModel = new AnswerModel();

        $post = (object) [];
        $post->id_demande = $id_demande;
        $results = $MailingLibrary->EmailSendByTemplate('demande_close', $post, $isTest);

        $debug = [];
        foreach($results as $result) :
            $param = database_decode($result->param);
            if(!empty($result->isSended)) :
                $answer = $this->db->table($this->t_answer)
                    ->where('id_demande', $param->id_demande)
                    ->where('id_contact_profil', $param->id_contact_profil)
                    ->get()->getRow();
                $param->date_envoi = date('Y-m-d H:i:s');
                $param->id_statut_answer = 1;
                if(!empty((array) $answer)) :
                    $id_answer = $AnswerModel->AnswerSave($param, $answer->id_answer);
                    $debug_text = 'Mise à jour';
                else :
                    $id_answer = $AnswerModel->AnswerSave($param);
                    $debug_text = 'Nouveau';
                endif;
                $debug[] = $debug_text . " : id_answer($id_answer) relié à id_demande($param->id_demande) et à id_contact_profil($param->id_contact_profil)";
            endif;
            unset($result->param);
        endforeach;

        return $debug;
    }

    // public function get_id_request_type_by_id_demande($id_demande)
    // {
    //     $DemandeLibrary = new DemandeLibrary();
    //     $request_type = $DemandeLibrary->get_request_type_by_id_demande($id_demande);
    //     return $request_type->id_road;
    // }

    // public function get_request_type_by_id_demande($id_demande)
    // {
    //     $accomp = $this->db
    //         ->table($this->t_demande_carac)
    //         ->where($this->t_demande_carac . '.id_demande', $id_demande)
    //         ->get()->getRow();
    //     $demande = $this->db
    //         ->table($this->t_demande)
    //         ->where('id_demande', $id_demande)
    //         ->get()->getRow();
                    
    //     if(!empty($accomp->id_type_accompagnement)) :
    //         $request_old = $this->db
    //             ->table($this->t_list_accomp_type)
    //             ->where('id', $accomp->id_type_accompagnement)
    //             ->get()->getRow();
    //     elseif(!empty($demande->id_type_demande)) :
    //         $request_old = $this->db
    //             ->table($this->t_list_demande_type)
    //             ->where('id', $demande->id_type_demande)
    //             ->get()->getRow();
    //     endif;

    //     $RoadModel = new \Tesorus\Models\RoadModel('demande');
    //     $roads = $RoadModel->get_roads_active_flat();
    //     foreach($roads as $road) :
    //         if(remove_accents(mb_strtolower($road->label_fr))==remove_accents(mb_strtolower($request_old->label))) return $road;
    //     endforeach;
    // }

    public function get_answer_by_question_type($question, $answer_code)
    {
        $answer = '-';
        switch($question->type_question):
            // radio
            case 6 :
                if(isset($answer_code)):
                    switch($answer_code):
                        case 0 : $answer = 'Non'; break;
                        case 1 : $answer = 'Oui'; break;
                    endswitch;                    
                endif;
                break;
            // slider
            case 1 : 
                if(isset($answer_code)) $answer = $answer_code . '/10'; break;
            // textarea
            case 2 : 
                if(isset($answer_code)) $answer = $answer_code; break;            
            // select
            case 4 : 
                switch($question->name_question) :
                    case "source_personne":
                        $origin_contact = $this->db
                            ->table($this->t_list_origin)
                            ->select('origine_fr as label_fr')
                            ->where('id_origine = ' . $answer_code)
                            ->get()->getRow();
                        if(!empty((array) $origin_contact)) $answer = $origin_contact->label_fr;
                        break;
                endswitch;
        endswitch;

        return $answer;
    }
    
    public function QuestionValidationGet($question)
    {
        switch($question->name_question):
            
            case "is_first_time":
            case "conseil_connaissance": 
            case "plan_travaux":
                return 'required|greater_than_equal_to[0]|less_than_equal_to[1]';
            break;
                
            case "answer_time": 
            case "qualite_expose":
            case "qualite_orateurs":
            case "has_solution_concrete": 
            case "rapport_ecrit_appreciation": 
            case "rapport_ecrit_accessibilite": 
            case "delai_visite_rapport": 
            case "delai_visite":
            case "delai_rdv": 
            case "accessible_message": 
            case "appreciation_rdv" : 
                $ruleRequired = empty($question->is_not_required) ? 'required|' : '';
                return $ruleRequired . 'integer|greater_than_equal_to[0]|less_than_equal_to[10]';
            break;

            case "source_personne": 
                $answers = $this->db->table($this->t_list_origin)->select('id_origine as id')->get()->getResult();
                $ids = array_column($answers, 'id');
                return 'required|in_list[' . implode(',', $ids) . ']';
            break;
            case 'suggestions': 
                return 'string';
            break;
        endswitch;
    }

    
    // public function send_mail_with_mailjet($mail)
    // {
    //     // comment when production
    //     // $mail->recipient = sessionUserMail();
        
    //     // $this->load->library('mailjet');
    //     $post['sendFrom'] = 'Homegrade';

    //     // uncomment when production
    //     $post['sendFromEmail'] = 'do_not_reply@homegrade.brussels';
    //     // comment when production
    //     //$post['sendFromEmail'] = 'igazet@homegrade.brussels';
    //     // $post['sendFromEmail'] = 'tamhau.nguyenba@banlieues.be';
    //    $post['sendFromEmail'] = CRMAIL;

    //     $post['subject'] = $mail->subject;
    //     $post['message'] = $mail->content;
    //     $post['sendTo'] = $mail->recipient;

    //     $response=$this->send_email($post);
       
    //     if($response) return $response; else return FALSE;

    //    /* $response = $this->mailjet->send($post);
        
    //     if($response->success()) return $response; else return FALSE;*/
    // }

    public function EnquetePreviewGet($enquete, $lang)
    {
        $EnqueteModel = new EnqueteModel();
        return $EnqueteModel->EnquetePreviewGet($enquete, $lang);
    } 

}