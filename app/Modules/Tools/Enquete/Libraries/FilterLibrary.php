<?php

namespace Enquete\Libraries;

use Base\Libraries\BaseLibrary;
use Enquete\Libraries\EnqueteLibrary;
use Enquete\Models\AnswerModel;
use Enquete\Models\EnqueteModel;
use Enquete\Models\FilterModel;

class FilterLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->AnswerModel = new AnswerModel();
        $this->EnqueteLibrary = new EnqueteLibrary();
        $this->EnqueteModel = new EnqueteModel();
        $this->FilterModel = new FilterModel();
    }

    public function navigation_get($target)
    {
        $param = $this->get_totals();
        $param->target = $this->get_target_object($target);
        
        return view('Enquete\navigation', (array) $param);
    }

    public function get_target_object($target_page, $reference=null)
    {
        $data = (object) [];
        $data->name = $target_page;
        switch($target_page) :
            case 'send' :
                $data->title = 'Tableaux' ;
                $data->url = base_url('enquete/sends');
                break;
            case 'answer' : 
                $data->title = 'Récapitulatif' ;
                $data->url = base_url('enquete/answers');
                break;
            case 'chart' : 
                $data->title = 'Statistiques' ;
                $data->url = base_url('enquete/charts');
                break;
            case 'trend' : 
                $data->title = 'Courbes de tendance' ;
                if(!empty($reference)) :
                    $data->url = base_url('enquete/trend/' . $reference);
                else :
                    $data->url = base_url('enquete/trends');
                endif;
                break;
            case 'enquete' : 
                $data->title = 'Formulaires' ;
                $data->url = base_url('enquete/enquetes');
                break;
            case 'question' :
                $data->title = 'Questions' ;
                $data->url = base_url('enquete/questions');
                break;
        endswitch;

        return $data;
    }

    public function get_totals()
    {
        $data = (object) [];
        $data->sended = $this->AnswerModel->TotalAnswersGet('sended');
        $data->waiting = $this->AnswerModel->TotalAnswersGet('waiting');
        $data->consulted = $this->AnswerModel->TotalAnswersGet('consulted');
        $data->answer = $this->AnswerModel->TotalAnswersGet('answer');

        return $data;
    }

    public function set_session($isReset=0)
    {
        if(!empty($isReset)) :
            if($this->session->has('filter')) $this->session->remove('filter');
            $redirect = preg_replace('/\/1$/', '', trim($_SERVER['REQUEST_URI']));
            // $redirect = str_replace('answers/1', 'answers', $_SERVER['REQUEST_URI']);

            return redirect()->to($redirect);
        else :
            if(!empty($this->request->getPost())) :
                $post = database_decode($this->request->getPost());
                // _print($post); die;
                $this->session->set('filter', $this->set_filter_array($post));
                
                return redirect()->to($_SERVER["HTTP_REFERER"]);
            endif;
        endif;

        // _print(session('filter')); die;
    }

    private function set_filter_array_date($period)
    {
        $data = (object) [];
        foreach($period as $key=>$value):
            if(isset($value) && $value != '') :
                $elem = (object) [];
                $elem->value = $value;
                switch($key):
                    case 'type' :
                        $elem->label = 'Type';
                        switch($value) :
                            case 'send' : $elem->text = 'date d\'envoi'; break;
                            case 'answer' : $elem->text = 'date de réponse'; break;
                        endswitch;
                        break;
                    case 'month' :
                        setlocale(LC_TIME, 'fra_fra');
                        $elem->label = 'Mois';
                        $elem->text = month_label_from_number($value);
                        break;
                    case 'year' :
                        $elem->label = 'Année';
                        $elem->text = $value;
                        break;
                    case 'from' :
                        $elem->label = 'A partir du';
                        $elem->text = $value;
                        break;
                    case 'to' :
                        $elem->label = 'Jusqu\'au';
                        $elem->text = $value;
                        break;
                endswitch;
                $data->$key = $elem;
            endif;
        endforeach;

        $data->label = 'Période (basée sur la ' . $data->type->text . ')';
        $data->text = '';
        if(!empty($data->year->text)) :
            if(!empty($data->month->text)) :
                $data->text = $data->month->text . ' ' . $data->year->text;
            else :
                $data->text = $data->year->text;
            endif;
        elseif(!empty($data->from->value) || !empty($data->to->value)) :
            if(!empty($data->from->value)) :
                $data->text .= 'A partir du ' . $data->from->value . ' ';
            endif;
            if(!empty($data->to->value)) :
                $data->text .= 'Jusqu\'au ' . $data->to->value;
            endif;
        endif;

        // $this->set_trend_period($data);

        return $data;
    }

    public function get_session_html()
    {
        $html = '<small>';
        if(session('filter')): 
            if(count((array) session('filter'))>1) $html .= 'FILTRES ACTIFS :';
            else $html .= 'FILTRE ACTIF :';
            $html .= '<ul class="mb-0">';
            foreach(session('filter') as $elem) :
                if(isset($elem->label) && isset($elem->text)) $html .= '<li>' . $elem->label . ' : ' . $elem->text . '</li>';
            endforeach;
            $html .= '</ul>';
        else: 
            $html .= 'AUCUN FILTRE';
        endif;
        $html .= '</small>';

        return $html;
    }

    public function set_filter_array($post)
    {
        $data = (object) [];
        foreach($post as $key=>$value) :
            $elem = (object) [];
            $elem->value = $value;
            if(isset($value) && $value != '') :
                switch($key) :
                    case 'period' :
                        $period = $post->period;
                        if(!empty($period->year) || !empty($period->from) || !empty($period->to)) $elem = $this->set_filter_array_date($period);
                        else unset($elem);
                    break;                
                    case 'id_contact_profil' :
                        $elem->label = 'Demandeur';
                        $result = $this->db->table($this->t_person)->select('prenom, nom')->where('id_personne', $value)->get()->getRow();
                        $elem->text = !empty((array) $result) ? fullname($result->prenom, $result->nom) : null;
                    break;                
                    case 'id_lang' :
                        $elem->label = 'Langue du demandeur';
                        $result = $this->db->table($this->t_list_lang)->select('label')->where('id', $value)->get()->getRow();
                        $elem->text = !empty((array) $result) ? $result->label : null;
                        break;                
                    case 'id_user' :
                        $elem->label = 'Conseiller';
                        $result = $this->db->table($this->t_user)->select('prenom, nom')->where('id_user = ' . $value)->get()->getRow();
                        $elem->text = !empty((array) $result) ? fullname($result->prenom, $result->nom) : null;
                    break;
                    case 'id_enquete' :
                        $elem->label = 'Type de demande/accompagnement';
                        $result = $this->EnqueteModel->EnqueteGet($value);
                        $elem->text = !empty($result->path_fr) ? $result->path_fr : null;
                    break;
                    case 'id_answer_status' :
                        $elem->label = 'Statut de l\'enquête';
                        $list = $this->EnqueteModel->get_answer_status();
                        foreach($list as $l) if($l->id==$value) $result = $l;
                        $elem->text = !empty($result->label_fr) ? $result->label_fr : null;
                        $elem->text .= !empty($result->annotation_fr) ? ' <small>(' . $result->annotation_fr . ')</small>' : '';
                    break;
                    case 'id_question' :
                        $ids_answer = $post->ids_answer;
                        $elem = $this->set_filter_array_question($value, $ids_answer, $elem);
                    break;
                    case 'ids_answer' :
                        unset($elem);
                    break;
                    case 'suggestions' :
                        $elem->label = 'Suggestions';
                        $elem->text = '"' . $value . '"';
                    break;
                    endswitch;
                if(isset($elem)) $data->$key = $elem;
            endif;
        endforeach;

        return $data;
    }

    public function set_filter_array_question($id_question, $ids_answer_string, $elem=null)
    {
        $elem = !empty($elem) ? $elem : (object) [];
        $elem->label = 'Question';
        $elem->value = $id_question;
        $result = $this->db->table($this->t_question)->where('id_question', $id_question)->get()->getRow();
        $elem->text = !empty($result->question_fr) ? $result->question_fr : null;
        if(!empty($ids_answer_string)) :
            $ids_answer = (object) [];
            $ids_answer->value = $ids_answer_string;
            $ids_answer->label = count($ids_answer->value)>1 ? 'Réponses' : 'Réponse';

            $options = $this->EnqueteModel->OptionsGetByQuestion($id_question);
            $texts = [];
            foreach($options as $option) :
                if(in_array($option->id, $ids_answer->value)) :
                    $texts[] = $option->id;
                endif;
            endforeach;
            $ids_answer->text = implode(', ', $texts);

            $elem->ids_answer = $ids_answer;
            $elem->text .= ' Réponse : ' . implode(', ', $texts);
        endif;

        return $elem;
    }

    public function get_filter_list()
    {
        $data = (object) [];
        $data->month_list = month_list_object('num', 'name');
        $data->year_list = $this->FilterModel->get_year_list();
        $data->user_list = $this->FilterModel->users_in_t_answer_get();
        $data->person_list = $this->AnswerModel->ProfilsAutocompleteGet();
        $data->language_list = $this->FilterModel->get_language_list();
        $data->question_list = $this->EnqueteModel->QuestionsActiveGet();
        $data->type_demande_list = $this->FilterModel->get_types_demande();
        $data->enquete_list = $this->EnqueteModel->EnquetesGet();
        $data->answer_status_list = $this->EnqueteModel->get_answer_status();

        return $data;
    }


}