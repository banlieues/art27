<?php

namespace Enquete\Models;

use Base\Models\BaseModel;
use DataView\Libraries\DataViewConstructor;
use Enquete\Libraries\FilterLibrary;

class EnqueteModel extends BaseModel
{ 
    protected $allowedFields;
	protected $fields;
	protected $returnType = 'object';
	protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->table = $this->t_enquete;
        $this->primaryKey = get_primary_key($this->table);
    }

    public function QuestionSave($post, $id_question=null)
    {
        $post->updated_by = session('loggedUserId');
        if(!empty($id_question)) :
            $post->created_by = session('loggedUserId');
            $this->db->table($this->t_question)
                ->set(database_encode($this->t_question, $post))
                ->where('id_question', $id_question)
                ->update();
         else :
            $post->updated_at = date('Y-m-d H:i:s');
            $this->db->table($this->t_question)
                ->set(database_encode($this->t_question, $post))
                ->insert();
            $id_question = $this->db->InsertID();

            $field = [];
            switch($post->type_question):
                case 1 : $field[] = $post->name_question . " INT(3) NULL;"; break; // slider
                case 2 : $field[] = $post->name_question . " text NULL;"; break; // textarea
                case 3 : $field[] = $post->name_question . " VARCHAR(255) NULL;"; break; // input
                case 4 : $field[] = $post->name_question . " INT(3) NULL;"; break; // select
                case 5 : $field[] = $post->name_question . " TEXT NULL;"; break; // checkbox
                case 6 : $field[] = $post->name_question . " INT(3) NULL;"; break; // radio
            endswitch;
            $forge = \Config\Database::forge();
            $forge->addColumn($this->t_answer, $field);
        endif;

        return $id_question;
    }
    
    public function get_answer_status()
    {
        $fields = json_decode(file_get_contents($this->path . 'Config/Json/list.json'));
        $list = $fields->id_answer_status;

        return $list;
    }

    public function QuestionsActiveGet()
    {
        $questions = $this->QuestionModelData()->whereNotIn("$this->t_question.type_question", [2, 3])->get()->getResult();
        $answer_columns = $this->db->getFieldNames($this->t_answer);
        $datas = [];
        foreach($questions as $question) :
            if(in_array($question->name_question, $answer_columns)) $datas[] = $question;
        endforeach;

        return $datas;
    }

    public function QuestionModelData($order=null, $request=null)
    {
        $q = $this->db->table($this->t_question);
        $q->select("
            $this->t_question.*, 
            $this->t_list_question_type.type as type_question_label,
        ");
        $q->select("(
            SELECT concat('[', group_concat(id_enquete SEPARATOR ','), ']') 
            FROM $this->t_enquete 
            WHERE json_contains(ids_question, id_question)
        ) as ids_enquete,");
        $q->select("(
            SELECT count(id_enquete) 
            FROM $this->t_enquete 
            WHERE json_contains(ids_question, id_question)
        ) as nb_enquetes,");

        $q->join($this->t_list_question_type, "$this->t_question.type_question = $this->t_list_question_type.id_type", 'left');

        $DataView = new DataViewConstructor();
        if(!empty($request) && !empty($request->getGet('itemSearch')) && !empty(trim($request->getGet('itemSearch')))) :
            $fieldsSearch = array(
                "$q->t_question.question_fr",
                "$q->t_question.question_nl",
                "$q->t_question.aide_question_fr",
                "$q->t_question.aide_question_nl",
                "$q->t_list_question_type.type",
            );
            $q = $DataView->setQuerySearch($q, $fieldsSearch);
        endif;

        $order = $DataView->GetOrderFromRequest($order, $request);
        if(!empty($order[0])) $q->orderBy($order[0], $order[1]);
        else $q->orderBy("$this->t_question.num_question", 'asc');

        return $q;
    }
    
    public function QuestionGetCalculatedFields($question)
    {
        $question->html = (object) [];
        $question->preview = (object) [];
        foreach(['fr', 'nl'] as $lang):
            $question->html->$lang = $this->QuestionGetCalculatedFieldsHtml($question, $lang);

            $data = (object) [];
            $data->question = $question;
            $data->lang = $lang;
            $question->preview->$lang = view('Enquete\question-preview', (array) $data);            
        endforeach;

        return $question;
    }

    public function QuestionsGet($order=null, $request=null)
    {
        $questions = database_decode($this->QuestionModelData($order, $request)->get()->getResult());

        $i = 0;
        foreach($questions as $question) :
            $questions[$i] = $this->QuestionGetCalculatedFields($question);
            $i++;
        endforeach;

        return $questions;
    }
    
    public function QuestionsGetByEnquete($id_enquete)
    {
        $questions = $this->QuestionModelData()
            ->whereIn($id_enquete, new \CodeIgniter\Database\RawSql('ids_enquete'))
            ->get()->getResult();
        return $questions;

        $enquete = $this->db->table($this->t_enquete)->where('id_enquete', $id_enquete)->get()->getRrow();
        if(empty((array) $enquete)) return null;

        $enquete = database_decode($enquete);
        foreach($enquete->ids_question as $id_question):
            $questions[] = $this->QuestionGet($id_question);
        endforeach;

        return array_values(array_unique(array_filter($questions)));    

    }

    public function QuestionGet($id_question)
    {
        $question = $this->QuestionModelData()->where("$this->t_question.id_question", $id_question)->get()->getRow();
        if(empty((array) $question)) return null;

        $question = database_decode($question);
        $question = $this->QuestionGetCalculatedFields($question);

        return $question;
    }
    
    public function OptionsGetByQuestion($id_question)
    {
        $question = $this->db->table($this->t_question)->where("$this->t_question.id_question", $id_question)->get()->getRow();
        
        $answers = [];
        switch($question->name_question):
            case "is_first_time":
            case "conseil_connaissance": 
            case "plan_travaux":
                $answers[] = (object) [ 'id' => 0, 'label_fr' => 'Non', ];
                $answers[] = (object) [ 'id' => 1, 'label_fr' => 'Oui', ];
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
                for($i=0; $i<=10; $i++) :
                    $answers[] = (object) [ 'id' => $i, 'label_fr' => $i . '/10', ];
                endfor;
                break;
            
            case "source_personne":
                $answers = $this->db->table($this->t_list_origin)->select('id_origine as id, origine_fr as label_fr, origine_nl as label_nl')->get()->getResult();
                break;

        endswitch;

        return $answers;
    }

    public function QuestionGetCalculatedFieldsHtml($question, $lang)
    {
        $data = $question;
        $data->lang = $lang;
        $html = '';
        switch($question->type_question_label):
            case 'slider' : $html = view('Enquete\form/form_slider', (array) $data); break;
            case 'textarea' : $html = view('Enquete\form/form_textarea', (array) $data); break;
            case 'input' : break;
            case 'select' :
                $data->options = $this->OptionsGetByQuestion($question->id_question);
                $html = view('Enquete\form/form_select', (array) $data);
                break;
            case 'checkbox' : break;
            case 'radio' : $html = view('Enquete\form/form_radio', (array) $data); break;
        endswitch;
        
        return $html;
    }
    
    public function QuestionGetByName($name_question)
    {
        $question = $this->db->table($this->t_question)->where('name_question', $name_question)->get()->getRow();

        return $question;
    }
    
    public function EnqueteGetByAnswer($id_answer)
    {
        $answer =$this->db->table($this->t_answer)->where('id_answer', $id_answer)->get()->getRow();
        if(empty((array) $answer)) return null;

        $enquete = $this->EnqueteGetByDemande($answer->id_demande);
        return $enquete;
    }

    public function EnqueteGetByDemande($id_demande)
    {
        $demande = $this->db->table($this->t_demande)->where('id_demande', $id_demande)->get()->getRow();
        $accomp = $this->db->table($this->t_demande_carac)->where("$this->t_demande_carac.id_demande", $id_demande)->get()->getRow();
            
        $EnqueteModel = new EnqueteModel();
        $q = $EnqueteModel->EnqueteModelData();
        if(!empty($accomp->id_type_accompagnement)) $q->where('id_type_accompagnement', $accomp->id_type_accompagnement);
        elseif(!empty($demande->id_type_demande)) $q->where('id_type_demande', $demande->id_type_demande);
        $enquete = database_decode($q->get()->getRow());

        $enquete = $this->EnqueteGetCalculatedFields($enquete);

        return $enquete;
    }

    public function EnqueteModelData($order=null, $request=null)
    {
        $this->select("
            $this->t_enquete.*,
            json_length($this->t_enquete.ids_question) as nb_questions,
            $this->t_cell.label_fr,
        ");
    
        $this->join($this->t_road_demande, "$this->t_road_demande.id_road = $this->t_enquete.id_request_type", 'left');
        $this->join($this->t_cell, "$this->t_cell.id_cell = $this->t_road_demande.id_cell", 'left');

        $DataView = new DataViewConstructor();
        if(!empty($request) && !empty($request->getGet('itemSearch')) && !empty(trim($request->getGet('itemSearch')))) :
            $fieldsSearch = array(
                "$this->t_cell.label_fr",
            );
            $DataView->setQuerySearch($this, $fieldsSearch);
        endif;

        $order = $DataView->GetOrderFromRequest($order, $request);
        if(!empty($order[0])) $this->orderBy($order[0], $order[1]);
        else $this->orderBy("$this->t_enquete.rank", 'asc');

        return $this;
    }

    public function EnqueteGetCalculatedFields($enquete)
    {
        $TesorusLibrary = new \Tesorus\Libraries\TesorusLibrary();
        $enquete->path_fr = $TesorusLibrary->get_path_by_id_road('demande', $enquete->id_request_type, (object) ['lang'=>'fr']);
        $enquete->path_nl = $TesorusLibrary->get_path_by_id_road('demande', $enquete->id_request_type, (object) ['lang'=>'nl']);

        $iframe = (object) [];
        $preview = (object) [];
        foreach(['fr', 'nl'] as $lang) :
            $iframe->$lang = base_url("enquete/enquete/$enquete->id_enquete/iframe/$lang");
            $preview->$lang = $this->EnquetePreviewGet($enquete, $lang);
        endforeach;
        $enquete->iframe = $iframe;
        $enquete->preview = $preview;

        return $enquete;
    }

    public function EnquetePreviewGet($enquete, $lang)
    {
        // $this->init_language($lang);

        if(empty($enquete->ids_question)) return '<div class="p-4"> Il n\'y a pas de question pour ce questionnaire. </div>';

        $questions = [];
        foreach($enquete->ids_question as $id_question):
            $question = $this->QuestionGet($id_question);
            $questions[] = $question;
        endforeach;

        $data = (object) [];
        $data->lang = $lang;
        $data->questions = $questions;
        $data->namespace = __NAMESPACE__;
        
        $html = view('Enquete\enquete-preview', (array) $data);
        
        return $html;
    } 

    public function EnquetesGet($order=null, $request=null)
    {
        $enquetes = database_decode($this->EnqueteModelData($order, $request)->get()->getResult());

        $i = 0;
        foreach($enquetes as $enquete) :
            $enquetes[$i] = $this->EnqueteGetCalculatedFields($enquete);
            $i++;
        endforeach;
        return $enquetes;
    }

    public function EnquetesPagerGet()
    {
        return $this->pager;
    }

    public function EnqueteSave($post, $id_enquete=null)
    {
        $post->updated_by = session('loggedUserId');
        if(!empty($id_enquete)) :
            $post->updated_at = date('Y-m-d H:i:s');
            $this->db
                ->table($this->t_enquete)
                ->set(database_encode($this->t_enquete, $post))
                ->where('id_enquete', $id_enquete)
                ->update();
        else :
            $post->created_by = session('loggedUserId');
            $this->db
                ->table($this->t_enquete)
                ->set(database_encode($this->t_enquete, $post))
                ->insert();
            $id_enquete = $this->db->InsertID();
        endif;

        return $id_enquete;
    }

    public function EnqueteGet($id_enquete)
    {
        $enquete = $this->EnqueteModelData()->where("$this->t_enquete.id_enquete", $id_enquete)->get()->getRow();
        if(empty((array) $enquete)) return null;

        $enquete = database_decode($enquete);
        $enquete = $this->EnqueteGetCalculatedFields($enquete);

        return $enquete;
    }
}
