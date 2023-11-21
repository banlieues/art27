<?php

namespace Enquete\Libraries;

use Base\Libraries\BaseLibrary;
use Enquete\Models\AnswerModel;

class ChartLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->AnswerModel = new AnswerModel();
    }
    
    private function get_chart_pie_boolean($question)
    {        
        // $where_yes = $this->t_answer . '.' . $question->name_question . ' = 1';
        $nb_yes = $this->AnswerModel
            ->AnswerModelData()
            ->where("$this->t_answer.$question->name_question", 1)
            ->countAllResults();
        
        // $where_no = $this->t_answer . '.' . $question->name_question . ' = 0';
        // $nb_no = $this->AnswerModel->answers_count($where_no);
        $nb_no = $this->AnswerModel
            ->AnswerModelData()
            ->where("$this->t_answer.$question->name_question", 0)
            ->countAllResults();

        $result = (object) [];
        $result->labels = ['Oui', 'Non'];
        $result->ids_label = [1, 0];
        $result->datas = [$nb_yes, $nb_no];
        
        return $result;
    }
    
    private function get_chart_bar_score($question)
    {
        $labels = [];
        $datas = [];
        $null = 0;
        for($i=0; $i<=10; $i++) :
            $labels[] = $i;
            // $where = $this->t_answer . '.' . $question->name_question . ' = ' . $i;
            $count = $this->AnswerModel
                ->AnswerModelData()
                ->where("$this->t_answer.$question->name_question", $i)
                ->countAllResults();
            // $count = $this->AnswerModel->answers_count($where);
            $datas[] = $count;
            if($count == 0) $null++;
        endfor;

        $result = (object) [];
        $result->labels = $labels;
        $result->ids_label = $labels;
        if($null < $i) $result->datas = $datas;

        return $result;
    }
    
    private function get_chart_bar_list($question)
    {
        $label_list = $result = array();
        $label_name = 'label_fr';
        $label_id = 'id';
        if($question->name_question == 'source_personne') $label_list = $this->db->table($this->t_list_origin)->select('id_origine as id, origine_fr as label_fr')->get()->getResult();
        $nb_label = count($label_list);
        $null = 0;
        $labels = $ids_label = $datas = array();
        foreach($label_list as $label):
            $labels[] = $label->$label_name;
            $ids_label[] = $label->$label_id;
            // $where = $this->t_answer . '.' . $question->name_question . ' = ' . $label->$label_id;
            $count = $this->AnswerModel
                ->AnswerModelData()
                ->where("$this->t_answer.$question->name_question", $label->$label_id)
                ->countAllResults();
            // $count = $this->AnswerModel->answers_count($where);
            $datas[] = $count;
            if($count == 0) $null++;
        endforeach;
        
        $result = (object) [];
        $result->labels = $labels;
        $result->ids_label = $ids_label;
        if($null < $nb_label) $result->datas = $datas;

        return $result; 
    }
    
    // private function get_answer_rate($name_question)
    // {
    //     $where[] = ['($this->t_answer . '.' . $name_question . ' IS NULL OR $this->t_answer . ' . $name_question . ' = "")'];
    //     $nb_empty = $this->AnswerModel->answers_count($where);
    //     $total = $this->AnswerModel->answers_count();
    //     $rate = ($total - $nb_empty)/$total*100;
        
    //     return $rate;
    // }
    
    public function set_chart_by_question_type($question)
    {
        $data = (object) [];
        switch($question->type_question):
            case 6 :
                // radio
                $data = $this->get_chart_pie_boolean($question);
                $data->chartType = 'pie';
                break;
            case 1 :
                // slider
                $data = $this->get_chart_bar_score($question);
                $data->chartType = 'bar';
                break;
            case 4 :
                // select
                $data = $this->get_chart_bar_list($question);
                $data->chartType = 'bar';
                break;
        endswitch;
        $data->title = $question->num_question . '. ' . $question->question_fr;
        $data->name_question = $question->name_question;    

        return $data;
    }

    private function random_color_part() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    public function random_color() {
        return '#' . $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }
    
    public function get_chart_colors()
    {
        return ['#1ABC9C', '#F1C40F' , '#E67E22', '#3498DB', '#E74C3C', '#9B59B6', '#95A5A6', '#1ABC9C', '#2B2B2B', '#27AE60', '#D35400', '#2980B9', '#C0392B', '#ECF0F1', '#7F8C8D', '#F1C40F', '#2C3E50'];
    }

}