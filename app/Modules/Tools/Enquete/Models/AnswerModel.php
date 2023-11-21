<?php

namespace Enquete\Models;

use Autorisation\Libraries\AutorisationLibrary;
use Base\Models\BaseModel;
use DataView\Libraries\DataViewConstructor;
use Enquete\Models\EnqueteModel;
use Enquete\Libraries\EnqueteLibrary;
use Enquete\Models\FilterModel;

class AnswerModel extends BaseModel
{
    protected $allowedFields;
	protected $fields;
	protected $returnType = 'object';
	protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $this->table = $this->t_answer;
        $this->primaryKey = get_primary_key($this->table);

        $this->EnqueteModel = new EnqueteModel();
        $this->EnqueteLibrary = new EnqueteLibrary();
        $this->FilterModel = new FilterModel();
    }

    public function TotalAnswersGet($type)
    {
        $q = $this->AnswerModelData(null, $this->request);
        switch($type):
            case 'sended' : break;
            case 'waiting' : $q->where("$this->t_answer.id_statut_answer", 1); break;
            case 'consulted' : $q->where("$this->t_answer.id_statut_answer", 2); break;
            case 'answer' : $q->where("$this->t_answer.id_statut_answer", 3); break;
        endswitch;

        $nb = $q->countAllResults();

        return $nb;
    }

    public function AnswerSubqueryGet($select, $where=null)
    {
        $q = $this->db->table($this->t_answer);
        $q->resetQuery();
        $q->distinct();
        $q->select($select);
        $q = $this->AnswerModelDataJoin($q);
        $q = $this->PermissionQuery($q);
        if(!empty(session('filter'))) :
            $session_where = $this->FilterModel->session_where_get();
            if(!empty($session_where)) foreach($session_where as $w) $q->where($w);
        endif;

        if(!empty($where)) foreach($where as $w) $q->where($w);

        $query = $q->getCompiledSelect();

        return $query;
    }

    public function AnswerModelDataJoin($q)
    {
        $q->join($this->t_list_answer_statut, "$this->t_list_answer_statut.id = $this->t_answer.id_statut_answer", 'left');
        $q->join($this->t_profil, "$this->t_profil.id_contact_profil = $this->t_answer.id_contact_profil", 'left');
        $q->join($this->t_contact, "$this->t_contact.id_contact = $this->t_profil.id_contact", 'left');
        $q->join($this->t_list_lang, "$this->t_list_lang.id = $this->t_contact.id_langue", 'left');
        $q->join($this->t_demande, "$this->t_demande.id_demande = $this->t_answer.id_demande", 'left');
        $q->join($this->t_demande_carac, "$this->t_demande_carac.id_demande = $this->t_answer.id_demande", 'left');
        $q->join($this->t_user, "$this->t_user.id = $this->t_demande.id_utilisateur", 'left');
        $q->join($this->t_list_origin, "$this->t_list_origin.id_origine = $this->t_answer.source_personne", 'left');
        $q->join($this->t_road_demande, "$this->t_road_demande.id_road = $this->t_answer.id_request_type", 'left');
        $q->join($this->t_cell, "$this->t_cell.id_cell = $this->t_road_demande.id_cell", 'left');

        return $q;
    }

    public function AnswerModelData($order=null, $request=null)
    {
        $this->select("
            $this->t_answer.*,
            $this->t_list_answer_statut.label as statut_answer,
            $this->t_profil.id_contact, 
            $this->t_contact.prenom_contact as demandeur_name, 
            $this->t_contact.nom_contact as demandeur_lastname,
            $this->t_list_lang.label as langue,
            $this->t_user.prenom, 
            $this->t_user.nom,
            $this->t_list_origin.origine_fr as source_personne_label,
            $this->t_list_origin.origine_nl,
            $this->t_cell.label_fr as path_fr,
        ");

        $this->AnswerModelDataJoin($this);

        if(!empty(session('filter'))) :
            $session_where = $this->FilterModel->session_where_get();
            if(!empty($session_where)) foreach($session_where as $w) $this->where($w);
        endif;

        $DataView = new DataViewConstructor();
        // if(!empty($request) && !empty($request->getGet('itemSearch')) && !empty(trim($request->getGet('itemSearch')))) :
        //     $fieldsSearch = array(
        //         "$this->t_answer.date_envoi",
        //         "$this->t_answer.date_consultation",
        //         "$this->t_answer.date_reponse",
        //         "$this->t_list_answer_statut.label",
        //         "$this->t_contact.nom_contact",
        //         "$this->t_contact.prenom_contact",
        //         "$this->t_list_lang.label",
        //         "$this->t_user.nom",
        //         "$this->t_user.prenom",
        //         "$this->t_list_origin.origine_fr",
        //         "$this->t_list_origin.origine_nl",
        //         "$this->t_cell.label_fr",
        //     );
        //     $DataView->setQuerySearch($this, $fieldsSearch);
        // endif;

        $order = $DataView->GetOrderFromRequest($order, $request);
        if(!empty($order[0])) $this->orderBy($order[0], $order[1]);
        else $this->orderBy("$this->t_answer.id_answer", 'desc');

        return $this;
    }

    private function PermissionQuery($q)
    {
        $Autorisation = new AutorisationLibrary();
        if(!$Autorisation->is_autorise('enquete_all_r')) :
            if(session('loggedUserId')==117) :
                // case Tamo => access to Elena demandes
                $q->where("$this->t_demande.id_utilisateur", 23);
            else :
                $q->where("$this->t_demande.id_utilisateur", session('loggedUserId'));
            endif;
        endif;

        return $q;
    }

    public function ProfilsAutocompleteGet()
    {
        $q = $this->db->table($this->t_answer);
        $q->resetQuery();
        $q->distinct();
        $q->select("
            $this->t_answer.id_contact_profil as id, 
            CONCAT_WS(' ', $this->t_contact.prenom_contact, $this->t_contact.nom_contact) as label
        ");
        
        $q->join($this->t_profil, "$this->t_profil.id_contact_profil = $this->t_answer.id_contact_profil", 'left');
        $q->join($this->t_contact, "$this->t_contact.id_contact = $this->t_profil.id_contact", 'left');
        $q->join($this->t_demande, "$this->t_demande.id_demande = $this->t_answer.id_demande", 'left');
        $q->join($this->t_user, "$this->t_user.id = $this->t_demande.id_utilisateur", 'left');

        $q = $this->PermissionQuery($q);

        $q->orderBy("$this->t_contact.nom_contact", 'asc');
        return $q->get()->getResult();
    }   

    public function AnswersPagerGet()
    {
        return $this->pager;
    }

    public function AnswersGet($order=null, $request=null, $no_pager=null)
    {
        $modeldata = $this->AnswerModelData($order, $request);
        $modeldata->PermissionQuery($modeldata);

        if(!empty($no_pager) || (!empty($request->getGet('per_page')) && $request->getGet('per_page')=='all')) :
            $answers = $modeldata->find();
        else :
            $per_page = !empty($request->getGet('per_page')) ? $request->getGet('per_page') : 20;
            $answers = $modeldata->paginate($per_page);
        endif;

        $i = 0;
        foreach($answers as $answer) :
            $answers[$i] = $this->AnswerGetCalculatedFields($answer);
            $i++;
        endforeach;

        return $answers;        
    }

    public function AnswersFilledGet($order=null, $request=null, $no_pager=null)
    {
        $modeldata = $this->AnswerModelData($order, $request);
        if(!empty($no_pager) || (!empty($request->getGet('per_page')) && $request->getGet('per_page')=='all')) :
            $answers = $modeldata->where("$this->t_answer.id_statut_answer", 3)->find();
        else :
            $per_page = !empty($request->getGet('per_page')) ? $request->getGet('per_page') : 20;
            $answers = $modeldata->where("$this->t_answer.id_statut_answer", 3)->paginate($per_page);
        endif;

        $questions = $this->EnqueteModel->QuestionsGet();
        $i = 0;
        foreach($answers as $answer) :
            foreach($answer as $key=>$value) :
                foreach($questions as $question) :
                    if($key == $question->name_question) :
                        $answers[$i]->{$key . '_label'} = $this->EnqueteLibrary->get_answer_by_question_type($question, $value);
                    endif;
                endforeach;
            endforeach;
            $i++;
        endforeach;

        return $answers;        
    }

    public function AnswerGet($id_answer)
    {
        $answer = $this->AnswerModelData()->where('id_answer', $id_answer)->get()->getRow();

        $answer = $this->AnswerGetCalculatedFields($answer);

        return $answer;        
    }

    public function AnswerGetCalculatedFields($answer)
    {
        $answer->delay = $this->AnswerGetCalculatedFieldsDelay($answer);

        return $answer;
    }

    private function AnswerGetCalculatedFieldsDelay($answer)
    {
        $origin = new \DateTime($answer->date_envoi);
        $target = (!empty($answer->date_reponse) && $answer->date_reponse != '0000-00-00 00:00:00') ? new \DateTime($answer->date_reponse) : new \DateTime(date('Y-m-d H:i:s'));
        $interval = $origin->diff($target);
        $day = $interval->format('%a');

        return $day > 30 ? "+ 30" : $day;
    }

    public function AnswerSave($post, $id_answer=null)
    {
        $q = $this->db->table($this->t_answer)->set(database_encode($this->t_answer, $post));
        if(!empty($id_answer)) :
            $q->where('id_answer', $id_answer)->update();
        else :
            $q->insert();
            $id_answer = $this->db->InsertID();
        endif;
        
        return $id_answer;
    }
}
