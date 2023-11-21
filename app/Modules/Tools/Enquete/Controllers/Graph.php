<?php

namespace Enquete\Controllers;

use Base\Controllers\BaseController;
use Enquete\Libraries\ChartLibrary;
use Enquete\Libraries\FilterLibrary;
use Enquete\Libraries\TrendLibrary;
use Enquete\Models\AnswerModel;
use Enquete\Models\EnqueteModel;
use Enquete\Models\FilterModel;

class Graph extends BaseController
{
    protected $module = 'en';
    protected $t_answer = 'en_answer';
    protected $t_demande = 'demande';

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->AnswerModel = new AnswerModel();
        $this->ChartLibrary = new ChartLibrary();
        $this->EnqueteModel = new EnqueteModel();
        $this->FilterLibrary = new FilterLibrary();
        $this->FilterModel = new FilterModel();
        $this->TrendLibrary = new TrendLibrary();

        $this->datas->context = "enquete";
    }

    public function tableau($story)
    {
        $data = (object) [];
        switch($story) :
            case 'charts' :
                $title = "Charts";
                $data->section = 'Ch';
                $data->worksheet = $title;
                $data->workcode = '16574899976420';
                $data->sheet = 'SB1';
                $data->id = 'viz1657620414898';
            break;
            case 'trends' :
                $title = 'Trends';
                $data->section = 'Tr';
                $data->worksheet = $title;
                $data->workcode = '16574900685470';
                $data->sheet = 'TB1';
                $data->id = 'viz1657619158765';
            break;
        endswitch;

        $this->layout_library->set_title($title);
        $this->layout_library->set_subtitle($title);

        return view($this->module . '\tableau', $data);
    }

    public function ChartList($reset=null)
    {
        $this->FilterLibrary->set_session($reset);

        $context_sub = 'chart';

        $this->datas->context_sub = $context_sub; 
        $this->datas->totals = $this->FilterLibrary->get_totals();
        $this->datas->filter_active = $this->FilterLibrary->get_session_html(); 
        $this->datas->questions = $this->EnqueteModel->QuestionsActiveGet();
        $this->datas->target = $this->FilterLibrary->get_target_object($context_sub);

        return view('Enquete\chart-list', (array) $this->datas);
    }
    
    public function ChartParamGet($id_question)
    {
        $question = $this->EnqueteModel->QuestionGet($id_question);

        $param = $this->ChartLibrary->set_chart_by_question_type($question);
        $param->colors = $this->ChartLibrary->get_chart_colors();

        echo json_encode($param);
    }

    public function ChartModal($canvas_type, $id_question)
    {
        $this->datas->filter_active = $this->FilterLibrary->get_session_html();
        $this->datas->id_question = $id_question;
        if($canvas_type == 'chart') :
            $this->datas->type_question = $this->EnqueteModel->QuestionGet($id_question)->type_question;
        endif;
        
        return view('Enquete\chart-modal', (array) $this->datas);
    }

    // public function TrendList($reset=null)
    // {       
    //     $this->FilterLibrary->set_session($reset);

    //     $context_sub = 'trend';

    //     $this->datas->context_sub = $context_sub;        
    //     $this->datas->filter_active = $this->FilterLibrary->get_session_html();
    //     $this->datas->target = $this->FilterLibrary->get_target_object($context_sub);        
    //     $this->datas->timerange_list = $this->TrendLibrary->get_timerange_list();
    //     $this->datas->totals = $this->FilterLibrary->get_totals();
    //     $this->datas->trends = $this->TrendLibrary->TrendsGet();

    //     return view('Enquete\trend-list', (array) $this->datas);
    // }

    public function TrendView($reference=null, $reset=null)
    {       
        $this->FilterLibrary->set_session($reset);

        $context_sub = 'trend';

        $this->datas->context_sub = $context_sub;        
        $this->datas->filter_active = $this->FilterLibrary->get_session_html();
        $this->datas->reference = $reference;
        $this->datas->target = $reference ? $this->FilterLibrary->get_target_object($context_sub, $reference) : null;    
        $this->datas->timerange_list = $this->TrendLibrary->get_timerange_list();
        $this->datas->totals = $this->FilterLibrary->get_totals();
        $this->datas->trends = $this->TrendLibrary->TrendsGet();

        return view('Enquete\trend-view', (array) $this->datas);
    }

    public function trend_param_get($reference, $timerange=null)
    {
        $period = $this->TrendLibrary->set_trend_period($timerange);
        if(!isset($timerange)) $timerange = $this->TrendLibrary->set_trend_timerange($period->date_start, $period->date_end);

        $trend = $this->TrendLibrary->TrendGetByKey($reference);
        $trend->reference = $reference;

        $data = $this->TrendDatasGet($reference, $period, $timerange);

        $result = (object) [];
        $result->title = $trend->title;
        $result->timerange = $timerange;
        if(in_array($reference, ['origin', 'number'])) :
            $result->data = $this->TrendLibrary->{'set_data_stacked_' . $reference}($data);
        else :
            $result->data = $this->TrendLibrary->set_data_score($data, $trend);
        endif;      
     
        echo json_encode($result);
    }

    private function TrendDatasGet($reference, $period, $timerange)
    {
        $this->FilterModel = new FilterModel(); 
        
        $q = $this->db->table($this->t_date);
        $q->resetQuery();
        
        $get_select = 'Subquery' . ucfirst($reference);
        $subquery = $this->TrendLibrary->$get_select($timerange);
        $select = [];
        foreach($subquery as $sq) :
            if(!empty($sq->where)) :
                $select[] = $this->AnswerModel->AnswerSubqueryGet($sq->select, $sq->where);
            else :
                $select[] = $this->AnswerModel->AnswerSubqueryGet($sq->select);
            endif;
        endforeach;
        $date_start = $period->date_start->format('Y-m-d');
        $date_end = $period->date_end->format('Y-m-d');
        $select_timerange = $this->TrendLibrary->QuerySelectTimerange($timerange);

        if(!empty(session('filter'))) :
            // $session_join = $this->FilterModel->session_join_get();
            $session_where = $this->FilterModel->session_where_get();
        endif;

        $q->distinct();
        $i=0;
        foreach($select as $s) :
            $q->select('(' . $s . ') as data_' . $i);  
            $i++;
        endforeach;
        $q->select($select_timerange);
        $q->join($this->t_answer, 'date(' . $this->t_answer . '.date_reponse) = date(' . $this->t_date . '.fulldate)', 'left');
        $q = $this->AnswerModel->AnswerModelDataJoin($q);
        // if(!empty($session_join)) foreach($session_join as $j) $q->join($j[0], $j[1], 'left');
        $q->where('DATE(' . $this->t_date . '.fulldate) >= DATE("' . $date_start . '")');
        $q->where('DATE(' . $this->t_date . '.fulldate) <= DATE("' . $date_end . '")');            
        if(!empty($session_where)) foreach($session_where as $w) $q->where($w);
        $q->groupBy('label');
        $q->orderBy($this->TrendLibrary->set_orderby_timerange($timerange));

        $data = $q->get()->getResult();
        // _print($this->db->last_query()); die;

        return $data;
    }
}
