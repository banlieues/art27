<?php

namespace Enquete\Libraries;

use Autorisation\Libraries\AutorisationLibrary;
use Base\Libraries\BaseLibrary;
use Enquete\Libraries\ChartLibrary;
use Enquete\Libraries\EnqueteLibrary;
use Enquete\Models\AnswerModel;
use Enquete\Models\EnqueteModel;
use Enquete\Models\FilterModel;

class TrendLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct();
        
	    $globals = new \Enquete\Config\Globals();
        foreach($globals as $global=>$value) $this->$global = $value;

        $this->AnswerModel = new AnswerModel();
        $this->Autorisation = new AutorisationLibrary();
        $this->ChartLibrary = new ChartLibrary();
        $this->EnqueteModel = new EnqueteModel();
        $this->EnqueteLibrary = new EnqueteLibrary();
        $this->FilterModel = new FilterModel();
    }

    public function set_orderby_timerange($timerange)
    {
        $orderby = '';
        switch($timerange) :
            case 'week' : $orderby = 'year, week'; break;
            case 'month' : $orderby = 'year, month'; break;
            case 'quarter' : $orderby = 'year, quarter'; break;
            case 'year' : $orderby = 'year'; break;
        endswitch;

        return $orderby;
    }

    private function QueryWhereTimerange($timerange, $date_type='answer')
    {
        // if($date_type=='send') $date_field = 'date_envoi';
        // elseif($date_type=='answer') $date_field = 'date_reponse';
        $date_field = 'date_envoi';

        $where = '';
        if($timerange=='week') :
            $where = "
                week($this->t_answer.$date_field) = $this->t_date.week AND 
                year($this->t_answer.$date_field) = $this->t_date.year
            ";
        elseif($timerange=='month') :
            $where = "
                month($this->t_answer.$date_field) = $this->t_date.month AND 
                year($this->t_answer.$date_field) = $this->t_date.year
            ";
        elseif($timerange=='quarter') :
            $where = "
                quarter($this->t_answer.$date_field) = $this->t_date.quarter AND 
                year($this->t_answer.$date_field) = $this->t_date.year
            ";
        elseif($timerange=='year') :
            $where = "year($this->t_answer.$date_field) = $this->t_date.year";
        endif;

        return $where;
    }

    public function QuerySelectTimerange($timerange='month')
    {
        if($timerange=='week') :
            return "
                $this->t_date.week,
                $this->t_date.year,
                CONCAT_WS('', 'Sem ', $this->t_date.week, ', ', $this->t_date.year) as label
            ";
        elseif($timerange=='month') :
            return "
                $this->t_date.month,
                $this->t_date.year,
                CONCAT_WS('', DATE_FORMAT($this->t_date.fulldate, '%M'), ' ', $this->t_date.year) as label
            "; 
        elseif($timerange=='quarter') :
            return "
                $this->t_date.quarter,
                $this->t_date.year,
                CONCAT_WS('', 'Trim ', $this->t_date.quarter, ', ', $this->t_date.year) as label
            ";
        elseif($timerange=='year') :
            return "
                $this->t_date.year,
                $this->t_date.year as label
            "; 
        endif;
    }

    public function SubqueryNumber($timerange)
    {
        // $where_init[] = $this->QueryWhereTimerange($timerange);
        $list = $this->set_data_list_number();
        $datas = [];
        $select = "count($this->t_answer.id_answer)";
        foreach($list as $l) :
            $data = (object) [];
            $data->select = $select;
            $data->where = [];
            $data->where[] = "$this->t_answer.id_statut_answer = $l->id";
            $data->where[] = $this->QueryWhereTimerange($timerange);
            $datas[] = $data;
        endforeach;

        return $datas;
        
        // $select = 'count(' . $this->t_answer . '.id_answer)';
        // // data 0
        // $data_0 = (object) [];
        // $data_0->select = $select;
        // $data_0->where = [];
        // $data_0->where[] = $this->QueryWhereTimerange($timerange, 'send');
        // //data 1
        // $data_1 = (object) [];
        // $data_1->select = $select;
        // $data_1->where = [];
        // $data_1->where[] = $this->QueryWhereTimerange($timerange);
        // $data_1->where[] = $this->t_answer . '.id_statut_answer = 3';
        // return [$data_0, $data_1];
    }

    private function QuerySelectCount($where, $timerange)
    {
        if(!empty((array) session('filter'))) :
            // $session_join = $this->FilterModel->session_join_get();
            $session_where = $this->FilterModel->session_where_get();
        endif;

        $q = $this->db->table($this->t_answer);
        $q->resetQuery();
        $q->select("count($this->t_answer.id_answer)");
        $q = $this->AnswerModel->AnswerModelDataJoin($q);

        if(!$this->Autorisation->is_autorise('enquete_all_r')) $q = $this->FilterModel->query_permission_get($q);
        // if(!empty($session_join)) foreach($session_join as $j) $q->join($j[0], $j[1], 'left');
        $q->where($this->QueryWhereTimerange($timerange));
        if(!empty($session_where)) foreach($session_where as $w) $q->where($w);
        if(isset($where)) $q->where($where);

        $query = $q->getCompiledSelect();

        return $query;
    }

    public function SubqueryRelation($timerange)
    {
        $focus_0 = $this->QuerySelectCount("$this->t_answer.is_first_time >= 1", $timerange);
        $total_0 = $this->QuerySelectCount("$this->t_answer.is_first_time >= 0", $timerange);
        $focus_1 = $this->QuerySelectCount("$this->t_answer.conseil_connaissance >= 1", $timerange);
        $total_1 = $this->QuerySelectCount("$this->t_answer.conseil_connaissance >= 0", $timerange);

        // $where[] = $this->QueryWhereTimerange($timerange);
        // data 0
        $data_0 = (object) [];
        $data_0->select = "100 * ($focus_0)/($total_0)";
        //data 1
        $data_1 = (object) [];
        $data_1->select = "100 * ($focus_1)/($total_1)";

        return [$data_0, $data_1];
    }   

    public function SubqueryAnswer($timerange)
    {
        $where[] = $this->QueryWhereTimerange($timerange);
        $where[] = 'answer_time IS NOT NULL';
        $where[] = 'rapport_ecrit_appreciation IS NOT NULL';
        // data 0
        $data_0 = (object) [];
        $data_0->select = "avg($this->t_answer.answer_time)";
        $data_0->where = $where;
        // data 1
        $data_1 = (object) [];
        $data_1->select = "avg($this->t_answer.rapport_ecrit_appreciation)";
        $data_1->where = $where;

        return [$data_0, $data_1];
    } 

    public function SubquerySupport($timerange)
    {
        $where[] = $this->QueryWhereTimerange($timerange);
        $where[] = 'qualite_orateurs IS NOT NULL';
        $where[] = 'rapport_ecrit_appreciation IS NOT NULL';
        // data 0
        $data_0 = (object) [];
        $data_0->select = "avg($this->t_answer.qualite_orateurs)";
        $data_0->where = $where;
        // data 1
        $data_1 = (object) [];
        $data_1->select = "avg($this->t_answer.rapport_ecrit_appreciation)";
        $data_1->where = $where;

        return [$data_0, $data_1];
    }

    public function SubqueryVisit($timerange)
    {
        $where[] = $this->QueryWhereTimerange($timerange);
        $where[] = 'qualite_orateurs IS NOT NULL';
        $where[] = 'qualite_expose IS NOT NULL';
        // data 0
        $data_0 = (object) [];
        $data_0->select = "avg($this->t_answer.qualite_orateurs)";
        $data_0->where = $where;
        // data 1
        $data_1 = (object) [];
        $data_1->select = "avg($this->t_answer.qualite_expose)";
        $data_1->where = $where;

        return [$data_0, $data_1];
    } 

    public function SubqueryUtility($timerange)
    {
        $where[] = $this->QueryWhereTimerange($timerange);
        $where[] = 'has_solution_concrete IS NOT NULL';
        $where[] = 'rapport_ecrit_accessibilite IS NOT NULL';
        // data 0
        $data_0 = (object) [];
        $data_0->select = "avg($this->t_answer.has_solution_concrete)";
        $data_0->where = $where;
        // data 1
        $data_1 = (object) [];
        $data_1->select = "avg($this->t_answer.rapport_ecrit_accessibilite)";
        $data_1->where = $where;

        return [$data_0, $data_1];
    }

    public function SubqueryDelay($timerange)
    {
        $where[] = 'delai_visite IS NOT NULL';
        $where[] = 'delai_visite_rapport IS NOT NULL';
        $where[] = $this->QueryWhereTimerange($timerange);
        // data 0
        $data_0 = (object) [];
        $data_0->select = "avg($this->t_answer.delai_visite)";
        $data_0->where = $where;
        // data 1
        $data_1 = (object) [];
        $data_1->select = "avg($this->t_answer.delai_visite_rapport)";
        $data_1->where = $where;

        return [$data_0, $data_1];
    }
    
    public function SubqueryOrigin($timerange)
    {
        // $where_init[] = $this->QueryWhereTimerange($timerange);
        $list = $this->db->table($this->t_list_origin)->select('id_origine as id, origine_fr as label')->get()->getResult();
        $datas = [];
        // foreach($list as $l) :
        //     $focus = $this->QuerySelectCount($this->t_answer . '.source_personne = ' . $l->id, $timerange);
        //     $total = $this->QuerySelectCount('1 = 1', $timerange); 
        //     $data = (object) [];
        //     $data->select = '100 * (' . $focus. ')/(' . $total . ')';
        //     $datas[] = $data;
        // endforeach;

        $select = "count($this->t_answer.id_answer)";
        foreach($list as $l) :
            $data = (object) [];
            $data->select = $select;
            $data->where = [];
            $data->where[] = "$this->t_answer.source_personne = $l->id";
            $data->where[] = $this->QueryWhereTimerange($timerange);
            $datas[] = $data;
        endforeach;


        return $datas;
    }
    
    public function get_first_date()
    {
        $result = $this->db->table($this->t_answer)->selectMin('date_envoi', 'first_date')->get()->getRow();
        if(!empty((array) $result)) return $result->first_date;
    }

    public function set_trend_timerange($date_start, $date_end)
    {
        $interval = date_diff($date_end, $date_start);
        $months = $interval->days/30;
        switch(true) :
            case $months <= 5 : $timerange = 'week'; break;
            case $months <= 50 : $timerange = 'month'; break;
            case $months <= 100 : $timerange = 'quarter'; break;
            default : $timerange = 'year';
        endswitch;

        return $timerange;
    }

    public function set_trend_period($timerange=null)
    {
        if(session('filter') && isset(session('filter')->period)) :
            $period = session('filter')->period;
            if(!empty($period->year->value)) :
                $year = $period->year->value;
                if(!empty($period->month->value)) :
                    $month = $period->month->value;
                    $date_start = new \DateTime("$year-$month-01");
                    $date_end = new \DateTime("$year-$month-01");
                    $date_end->modify('+1 month');
                else :
                    $date_start = new \DateTime("$year-01-01");
                    $date_end = new \DateTime("$year-01-01");
                    $date_end->modify('+1 year');
                endif;
            elseif(!empty($period->from->value) || !empty($period->to->value)) :
                if(!empty($period->from->value)) $date_start = new \DateTime($period->from->value);
                if(!empty($period->to->value)) $date_end = new \DateTime($period->to->value);
            endif;
        endif;

        $date_max = new \DateTime();
        $date_min = new \Datetime($this->get_first_date());
        $result = (object) [];
        if(!isset($date_start) || $date_start < $date_min) $result->date_start = $date_min;
        else $result->date_start = $date_start;
        if(!isset($date_end) || $date_end > $date_max) $result->date_end = $date_max;
        else $result->date_end = $date_end;

        return $result;
    }

    public function set_data_score($result, $param)
    {
        $colors = $this->ChartLibrary->get_chart_colors();

        $data = (object) [];
        $data->labels = $data->datasets = [];
        for($i=0; $i<=1; $i++) :
            $data->datasets[$i] = (object) [];
            $data->datasets[$i]->label = $param->label[$i];
            $data->datasets[$i]->backgroundColor = $colors[$i];
            $j=0;
            foreach($result as $row):
                $data->labels[$j] = $row->label;
                $value = 'data_' . $i;
                $data->datasets[$i]->data[$j] = $row->$value;
                $j++;
            endforeach;
        endfor;

        return $data;
    }

    private function set_data_stacked($list, $result)
    {
        $colors = $this->ChartLibrary->get_chart_colors();
        
        $data = (object) [];
        $data->labels = [];
        $data->datasets = [];

        $i=0;
        foreach($list as $l) :
            $dataset = (object) [];
            $dataset->label = $l->label;
            $dataset->backgroundColor = $colors[$i];

            $j=0;
            foreach($result as $row) :
                $data->labels[$j] = $row->label;
                $dataset->data[$j] = $row->{'data_' . $i};
                $j++;
            endforeach;
            $data->datasets[] = $dataset;
            $i++;
        endforeach;

        return $data;
    }

    public function set_data_stacked_origin($result)
    {
        $list = $this->db->table($this->t_list_origin)->select('id_origine as id, origine_fr as label')->get()->getResult();
        $data = $this->set_data_stacked($list, $result);

        return $data;
    }

    public function set_data_stacked_number($result)
    {
        $list = $this->set_data_list_number();
        $data = $this->set_data_stacked($list, $result);

        return $data;
    }

    public function set_data_list_number()
    {
        $status = $this->EnqueteModel->get_answer_status();
        $list = [];
        foreach($status as $s) :
            $s->label = $s->label_fr;
            unset($s->label_fr);
            $list[] = $s;
        endforeach;

        return $list;
    }

    public function set_data_number($result)
    {
        $list = $this->set_data_list_number();
        $data = $this->set_data_stacked($list, $result);

        return $data;
    }

    public function get_timerange_list()
    {
        $timerange = (object) [
            (object) ['ref' => 'week', 'label' => 'Semaine', 'shortlabel' => 'Sem',],
            (object) ['ref' => 'month', 'label' => 'Mois', 'shortlabel' => 'Mois',],
            (object) ['ref' => 'quarter', 'label' => 'Trimestre', 'shortlabel' => 'Trim',],
            (object) ['ref' => 'year', 'label' => 'Année', 'shortlabel' => '',],
        ];

        return $timerange;
    }

    public function TrendsGet()
    {
        $json = '{
            "number" : {
                "reference" : "number",
                "title" : "Nombre d\'enquêtes",
                "label" : ["Envoyées", "Répondues"]
            },
            "relation" : {
                "reference" : "relation",
                "title" : "Premier contact et recommendation ( en % )",
                "label" : ["Premier contact avec Homegrade (oui) ", "Recommandation à ses contacts (oui) "],
                "label_default" : [
                    "' . $this->EnqueteModel->QuestionGetByName('is_first_time')->question_fr . '", 
                    "' . $this->EnqueteModel->QuestionGetByName('conseil_connaissance')->question_fr . '"
                ]
            },
            "origin" : {
                "reference" : "origin",
                "title" : "Arrivée chez Homegrade"
            },
            "answer" : {
                "reference" : "answer",
                "title" : "Evaluation des temps et qualité de réponse ( /10 )",
                "label" : ["Note sur le temps de réponse", "Note sur la qualité de réponse"],
                "label_default" : [
                    "' . $this->EnqueteModel->QuestionGetByName('answer_time')->question_fr . '", 
                    "' . $this->EnqueteModel->QuestionGetByName('rapport_ecrit_appreciation')->question_fr . '"
                ]
            },
            "support" : {
                "reference" : "support",
                "title" : "Evaluation des accompagnements ( /10 )",
                "label_default" : ["Qualité relationnelle", "Compréhension des réponses"],
                "label" : [
                    "' . $this->EnqueteModel->QuestionGetByName('qualite_orateurs')->question_fr . '", 
                    "' . $this->EnqueteModel->QuestionGetByName('accessible_message')->question_fr . '"
                ]
            },
            "visit" : {
                "reference" : "visit",
                "title" : "Evaluation générale des visites ( /10 )",
                "label_default" : ["Qualité relationnelle", "Organisation de la visite"],
                "label" : [
                    "' . $this->EnqueteModel->QuestionGetByName('qualite_orateurs')->question_fr . '", 
                    "' . $this->EnqueteModel->QuestionGetByName('qualite_expose')->question_fr . '"
                ]
            },
            "utility" : {
                "reference" : "utility",
                "title" : "Evaluation de l\'utilité des visites ( /10 )",
                "label_default" : ["Utilité de la visite", "Utilité du rapport"],
                "label" : [
                    "' . $this->EnqueteModel->QuestionGetByName('has_solution_concrete')->question_fr . '", 
                    "' . $this->EnqueteModel->QuestionGetByName('rapport_ecrit_accessibilite')->question_fr . '"
                ]
            },
            "delay" : {
                "reference" : "delay",
                "title" : "Evaluation des délais de visite ( /10 )",
                "label_default" : ["Délai 1er contact - visite", "Délai visite - rapport"],
                "label" : [
                    "' . $this->EnqueteModel->QuestionGetByName('delai_visite')->question_fr . '", 
                    "' . $this->EnqueteModel->QuestionGetByName('delai_visite_rapport')->question_fr . '"
                ]
            }
        }';

        return json_decode($json);
    }

    public function TrendGetByKey($key)
    {
        $object = $this->TrendsGet();
        return $object->$key;
    }
}