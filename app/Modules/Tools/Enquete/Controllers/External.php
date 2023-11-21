<?php

namespace Enquete\Controllers;

use Base\Controllers\BaseController;
use Enquete\Libraries\EnqueteLibrary;
use Enquete\Models\AnswerModel;
use Enquete\Models\EnqueteModel;

class External extends BaseController 
{
    protected $t_answer = 'en_answer';

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->EnqueteModel = new EnqueteModel();
        $this->AnswerModel = new AnswerModel();
        $this->EnqueteLibrary = new EnqueteLibrary();
    }
    
    private function AnswerCheckUrl($token, $id_demande, $id_contact_profil)
    {
        $answer = $this->db->table($this->t_answer)
            ->where('id_contact_profil', $id_contact_profil)
            ->where('id_demande', $id_demande)
            ->where('token', $token)
            ->get()->getRow();

        if(!empty((array) $answer)) return $answer;
    }

    public function AnswerForm($token, $id_demande, $id_contact_profil, $lang)
    {
        // $this->init_language($lang);
        $answer = $this->AnswerCheckUrl($token, $id_demande, $id_contact_profil);
        if(!isset($answer)) :
            die(t("
                Un problème lié aux identifiants du questionnaire est survenu (enquête non trouvée). 
                Veuillez réessayer ou prendre contact avec nos collaborateurs.
            ", __NAMESPACE__, ['lang' => $lang])  . signature());
        elseif($answer->id_statut_answer==3) :
            die(t("
                Cette enquête de satisfaction a déjà été répondue et nous vous en remercions.
            ", __NAMESPACE__, ['lang' => $lang])  . signature());
        endif;

        $post = (object) [];
        switch($answer->id_statut_answer):
            case 1 :
                $post->id_statut_answer = 2;
                $post->date_consultation = date('Y-m-d H:i:s');
                $this->db->table($this->t_answer)
                    ->set(database_encode($this->t_answer, $post))
                    ->where('id_answer', $answer->id_answer)
                    ->update();
                break;
            case 2 :
                $post->date_consultation = date('Y-m-d H:i:s');
                $this->db->table($this->t_answer)
                    ->set(database_encode($this->t_answer, $post))
                    ->where('id_answer', $answer->id_answer)
                    ->update();
                break;
            // case 3 :
            //     $post->lang = $lang;
            //     return view($this->module . '\external-thanks', (array) $post);
        endswitch;

        $data = (object) [];
        $data->lang = $lang;
        $data->answer = $answer;
        $data->enquete = $this->EnqueteModel->EnqueteGetByDemande($id_demande);

        return view('Enquete\enquete-iframe', (array) $data);
    }
        
    public function AnswerSave($token, $id_demande, $id_contact_profil, $lang)
    {
        // $this->init_language($lang);
        $validation = \Config\Services::validation();

        $answer = $this->AnswerCheckUrl($token, $id_demande, $id_contact_profil);

        if(!isset($answer)) :
            die(t("
                Un problème lié aux identifiants du questionnaire est survenu (enquête non trouvée ou non unique). Veuillez réessayer ou prendre contact avec nos collaborateurs.
            ", __NAMESPACE__, ['lang' => $lang])  . signature());
        endif;

        $enquete = $this->EnqueteModel->EnqueteGetByAnswer($answer->id_answer);
        foreach($enquete->ids_question as $id_question):
            $question = $this->EnqueteModel->QuestionGet($id_question);
            $rule = $this->EnqueteLibrary->QuestionValidationGet($question);
            $title = 'question_' . $lang;
            $validation->setRule($question->name_question, 'question "' . $question->$title . '"', $rule);
        endforeach;

        if ($validation->run($this->request->getPost())==FALSE) : 
            return redirect()
                ->to(base_url("enquete/external/form/$token/$id_demande/$id_contact_profil/$lang"))
                ->with('warning', $validation->listErrors())
                ;
        endif;

        $post = database_decode($this->request->getPost());
        $post->id_statut_answer = 3;
        $post->date_reponse = date('Y-m-d H:i:s');
        $this->AnswerModel->AnswerSave($post, $answer->id_answer);

        $data = (object) [];
        $data->lang = $lang;
        
        return view($this->module . '\external-thanks', (array) $data);
    }

}
