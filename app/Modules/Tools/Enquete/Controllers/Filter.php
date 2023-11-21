<?php

namespace Enquete\Controllers;

use Base\Controllers\BaseController;
use Enquete\Libraries\FilterLibrary;
use Enquete\Models\EnqueteModel;

class Filter extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->EnqueteModel = new EnqueteModel();
        $this->FilterLibrary = new FilterLibrary();

        // if(!$this->fh_dao->get_autorisation("enquete_r")) die("Vous n'avez pas l'autorisation de voir les enquêtes");
    }
   
    // public function mysql()
    // {
    //     $this->mysql->print_columns_from_table();
    // }

    public function set_where_chart()
    {
        if(!$this->request->getPost()) return false;

        $post = database_decode($this->request->getPost());
        $filter_question = $this->FilterLibrary->set_filter_array_question($post->id_question, [$post->id_answer]);
        $this->session->set('id_question', $filter_question);

        echo true; 
    }

    public function filter_get()
    {
        $result = (object) [];

        if(!empty(session('filter'))) :
            foreach(session('filter') as $elem) :
                $elem = database_decode($elem);
                if(isset($elem->label) && isset($elem->text)) :
                    $key = $elem->label;
                    $result->$key = $elem->text;
                endif;
            endforeach;
        endif;

        echo json_encode($result);
    }

    public function filter_set()
    {
        $post = database_decode($this->request->getPost());
        if(!empty($this->request->getPost('filter'))) :
            $this->session->set('filter_' . $post->type, $post->filter);
        else :
            $this->session->remove('filter_' . $post->type);
        endif;
    }

    public function FilterModal($target, $reference=null)
    {
        $param = $this->FilterLibrary->get_filter_list();
        $param->target = $this->FilterLibrary->get_target_object($target, $reference);

        $data = (object) [];
        $data->title = 'Filtrer les enquêtes';
        $data->body = view($this->module . '\filter-modal', (array) $param);
        $data->footer = '<button form="filterForm" class="btn btn-sm btn-warning" onclick="waiting_start(this);"> Filtrer </button>';

        echo json_encode($data);
    }

        
    public function AnswersByQuestion($id_question, $onevent)
    {
        
        $list = $this->EnqueteModel->OptionsGetByQuestion($id_question);

        $options = [];
        foreach($list as $row):
            $option = (object) [];
            $option->value = $row->id;
            $option->label = $row->label_fr;
            $option->selected = false;
            if($onevent=='onload') :
                if(
                    session('filter') &&
                    isset(session('filter')->id_question) && 
                    !empty(session('filter')->id_question->ids_answer) && 
                    in_array($row->id, session('filter')->id_question->ids_answer->value)
                ) :
                    $option->selected = true;
                endif;
            endif;
            $options[] = $option;
        endforeach;

        echo json_encode($options);
    }

}
