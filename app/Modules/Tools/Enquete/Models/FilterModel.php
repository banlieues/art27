<?php

namespace Enquete\Models;

use Autorisation\Libraries\AutorisationLibrary;
use Base\Models\BaseModel;
use Enquete\Models\EnqueteModel;
use Enquete\Libraries\EnqueteLibrary;

class FilterModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->Autorisation = new AutorisationLibrary();
        $this->EnqueteModel = new EnqueteModel();
        $this->EnqueteLibrary = new EnqueteLibrary();
    }

    public function session_join_get()
    {
        $join = [];
        $filter = session('filter');

        $join[] = [$this->t_list_lang, "$this->t_list_lang.id = $this->t_contact.id_langue", 'left'];
        if(isset($filter->id_user) && isset($filter->id_user->value)) :           
            if($this->Autorisation->is_autorise('enquete_all_r')) :
                $join[] = [$this->t_demande, "$this->t_answer.id_demande = $this->t_demande.id_demande", 'left'];
            endif;
        endif;
        if(isset($filter->id_enquete) && isset($filter->id_enquete->value)) :
            if($this->Autorisation->is_autorise('enquete_all_r')) :
                $join[] = [$this->t_demande, "$this->t_answer.id_demande = $this->t_demande.id_demande", 'left'];
            endif;
            $join[] = [$this->t_demande_carac, "$this->t_answer.id_demande = $this->t_demande_carac.id_demande", 'left'];
        endif;

        return array_filter($join);
    }

    public function session_where_get()
    {
        $where = [];

        $filter = database_decode(session('filter'));
        if(isset($filter->period)) :
            $where[] = $this->session_where_period_get();
        endif;
        if(isset($filter->id_user->value)) :
            $where[] = "$this->t_demande.id_utilisateur = " . $filter->id_user->value;
        endif;
        if(!empty($filter->id_contact_profil->value)) :
            $where[] = "$this->t_answer.id_contact_profil = " . $filter->id_contact_profil->value;
        endif;
        if(!empty($filter->id_lang->value)) :
            $where[] = "$this->t_list_lang.id = " . $filter->id_lang->value;
        endif;
        if(!empty($filter->id_answer_status->value)) :
            $where[] = "$this->t_answer.id_statut_answer = " . $filter->id_answer_status->value;
        endif;
        if(!empty($filter->id_enquete->value)) :
            $enquete = $this->db->table($this->t_enquete)->where('id_enquete', $filter->id_enquete->value)->get()->getRow();
            if(!empty($enquete->id_type_accompagnement)) $where[] = "$this->t_demande_carac.id_type_accompagnement = $enquete->id_type_accompagnement";
            elseif(!empty($enquete->id_type_demande)) $where[] = "$this->t_demande.id_type_demande = $enquete->id_type_demande";
        endif;
        if(!empty($filter->id_question->value) && !empty($filter->id_question->ids_answer->value)) :
            $where[] = "$this->t_answer.id_statut_answer = 3";
            $question = $this->db->table($this->t_question)->where('id_question', $filter->id_question->value)->get()->getRow();
            if(!empty((array) $question)) :
                $where[] = "$this->t_answer.$question->name_question in (" . implode(',', $filter->id_question->ids_answer->value) . ")";
            endif;
        endif;
        if(!empty($filter->suggestions->value)) :
            $where[] = $this->t_answer . '.suggestions like "%' . $filter->suggestions->value . '%"';
        endif;

        return array_filter($where);
    }

    private function session_where_period_get()
    {
        $filter = database_decode(session('filter'));
        $period = $filter->period;
        if($period->type->value == 'answer') $date_column = $this->t_answer . '.date_reponse';
        elseif($period->type->value == 'send') $date_column = $this->t_answer . '.date_envoi';

        $where_date = array();

        if(!empty($period->from) || !empty($period->to)) :
            if(!empty($period->from->value)) : $where_date[] = $date_column. ' >= "' . $period->from->value . '"'; endif;
            if(!empty($period->to->value)) : $where_date[] = $date_column. ' <= "' . $period->to->value . '"'; endif;
        elseif(!empty($period->year->value)) :
            $where_date[] = 'YEAR(' . $date_column . ') = ' . $period->year->value;
            if(!empty($period->month->value)) : $where_date[] = 'MONTH(' . $date_column . ') = ' . $period->month->value; endif;
        endif;
        
        return implode(' AND ', $where_date); 
    }

    public function query_permission_get($q)
    {
        // pour Tamo (117) : récupère les données de Eléna (23)
        if(session('loggedUserId')==117) $q->where("$this->t_demande.id_utilisateur", 23);
        else $q->where("$this->t_demande.id_utilisateur", session('loggedUserId'));

        $q->join($this->t_demande, "$this->t_demande.id_demande = $this->t_answer.id_demande", 'left');

        return $q;
    }

    public function get_language_list()
    {
        $langs = $this->db->table($this->t_list_lang)->select('id, label')->where('is_actif', 1)->get()->getResult();

        return $langs;        
    }

    public function get_year_list()
    {
        $q = $this->db->table($this->t_date);
        $q->resetQuery();
        $q->distinct();
        $q->select('year');
        $q->where('year <= year(now())');
        if($this->Autorisation->is_autorise('enquete_all_r')):
            $q->where('(
                year >= (
                    SELECT min(year(date_envoi)) 
                    FROM ' . $this->t_answer . ' 
                    WHERE date_envoi IS NOT NULL AND date_envoi <> "0000-00-00 00:00:00"
                ) or 
                year >= (
                    SELECT min(year(date_reponse)) 
                    FROM ' . $this->t_answer . ' 
                    WHERE date_reponse IS NOT NULL AND date_reponse <> "0000-00-00 00:00:00"
                )
            )');
        else:
            $q->where('(
                year >= (
                    SELECT min(year(date_envoi)) 
                    FROM ' . $this->t_answer . ' 
                    LEFT JOIN ' . $this->t_demande . ' ON ' . $this->t_demande . '.id_demande=' . $this->t_answer . '.id_demande 
                    WHERE 
                        ' . $this->t_answer . '.date_envoi IS NOT NULL AND 
                        ' . $this->t_answer . '.date_envoi <> "0000-00-00 00:00:00" AND
                        ' . $this->t_demande . '.id_utilisateur = ' . session('loggedUserId') . '
                ) or 
                year >= (
                    SELECT min(year(date_reponse)) 
                    FROM ' . $this->t_answer . ' 
                    LEFT JOIN ' . $this->t_demande . ' ON ' . $this->t_demande . '.id_demande=' . $this->t_answer . '.id_demande  
                    WHERE 
                        ' . $this->t_answer . '.date_reponse IS NOT NULL AND 
                        ' . $this->t_answer . '.date_reponse <> "0000-00-00 00:00:00" AND
                        ' . $this->t_demande . '.id_utilisateur = ' . session('loggedUserId') . '
                )
            )');
        endif;
        $years = $q->get()->getResult();

        $data = [];
        foreach($years as $year) $data[] = $year->year;

        return $data;
    }
    
    public function users_in_t_answer_get()
    {   
        $q = $this->db->table($this->t_answer);
        $q->resetQuery();
        $q->distinct();
        $q->join($this->t_demande, $this->t_demande . '.id_demande = ' . $this->t_answer . '.id_demande', 'left');
        $q->join($this->t_user, $this->t_user . '.id = ' . $this->t_demande . '.id_utilisateur', 'left');

        if(!$this->Autorisation->is_autorise('enquete_all_r')) :
            $q->where($this->t_demande . '.id_utilisateur', session('loggedUserId'));
        endif;

        $q->select($this->t_user . '.id, ' . $this->t_user . '.nom, ' . $this->t_user . '.prenom, ' . $this->t_user . '.email');
        $q->orderBy('nom', 'ASC');
        // $this->db->where('id_user = ' . session('loggedUserId'));
        return $q->get()->getResult();
    }
    
    public function get_types_demande()
    {
        return $this->db->table($this->t_list_demande_type)->get()->getResult();
    }
}
