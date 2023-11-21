<?php

namespace Report\Models;

use Base\Models\BaseModel;
use Components\Libraries\ListLibrary;

class ReportModel extends BaseModel 
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->ListLibrary = new ListLibrary(__NAMESPACE__);

        $this->pk_file = get_primary_key($this->t_file);
        $this->pk_block = get_primary_key($this->t_block);
        $this->pk_tag = get_primary_key($this->t_tag);
        $this->pk_report = get_primary_key($this->t_report);
    }

    public function block_delete($id_block)
    {
        $this->db->table($this->t_block)->where('id_block', $id_block)->delete();
    }

    public function get_reports_by_id_block($id_block)
    {
        $reports = $this->db->table($this->t_report_block)->where('id_block', $id_block)->get()->getResult();

        return $reports;
    }

    public function get_person_by_id($id_person)
    {
        $persons = $this->db->table($this->t_person)->where('id_personne', $id_person)->get()->getResult();
        if(empty($persons)) return false;

        return database_decode($persons[0]);
    }

    public function get_request_by_id($id_request)
    {
        $requests = $this->db->table($this->t_request)->where('id_demande', $id_request)->get()->getResult();
        if(empty($requests)) return false;

        return database_decode($requests[0]);
    }

    public function person_search($post)
    {
        $q = $this->db->table($this->t_person);
        $q->select($this->t_person . '.*');
        if(!empty($post->id_request)) :
            $q->join($this->t_person_building_request, $this->t_person_building_request . '.id_personne = ' . $this->t_person . '.id_personne', 'left');
            $q->where($this->t_person_building_request . '.id_demande', $post->id_request);
        elseif(!empty($post->search)) :
            $q->where('1 = 0');
            $q->orWhere($this->t_person . '.id_personne', $post->search);
            $q->orLike($this->t_person . '.nom', $post->search);
            $q->orLike($this->t_person . '.prenom', $post->search);
        endif;
        $persons = $q->get()->getResult();

        return database_decode($persons);
    }

    public function request_search($post)
    {
        $q = $this->db->table($this->t_request);
        $q->select($this->t_request . '.*');
        if(!empty($post->id_person)) :
            $q->join($this->t_person_building_request, $this->t_person_building_request . '.id_demande = ' . $this->t_request . '.id_demande', 'left');
            $q->where($this->t_person_building_request . '.id_personne', $post->id_person);
        elseif(!empty($post->search)) :
            $q->where('1 = 0');
            $q->orWhere($this->t_request . '.id_demande', $post->search);
            $q->orLike($this->t_request . '.nom', $post->search);
        endif;
        $demandes = $q->get()->getResult();

        return database_decode($demandes);
    }

    public function block_update_by_id($id_block, $post)
    {
        $post->id_file = $post->id_file[0];
        $post->updated_at = date('Y-m-d H:i:s');
        $post->updated_by = session('loggedUserId');
        if(!empty($post->ids_road_them)) $post->ids_road_them = array_unique($post->ids_road_them);

        $data = database_encode($this->t_block, $post);
        if(!empty((array) $data)) $this->db->table($this->t_block)->where($this->pk_block, $id_block)->update($data);
    }

    public function tag_delete($id_tag)
    {
        $this->db->table($this->t_tag)->where($this->pk_tag, $id_tag)->delete();
    }

    public function tag_insert($post)
    {
        $post->created_by = session('loggedUserId');
        $data = database_encode($this->t_tag, $post);

        $this->db->table($this->t_tag)->insert($data);
        $id_tag = $this->db->insertID();

        return $id_tag;
    }

    public function report_delete($id_report)
    {
        $this->db->table($this->t_report)->where('id_report', $id_report)->delete();
        $this->db->table($this->t_report_block)->where('id_report', $id_report)->delete();
    }

    public function get_report_block_by_id_report_id_block($id_report, $id_block)
    {
        $q = $this->db->table($this->t_report_block);
        $q->where('id_report', $id_report);
        $q->where('id_block', $id_block);
        $report_blocks = $q->get()->getResult();

        if(empty($report_blocks)) return false;

        return $report_blocks[0];
    }

    public function block_insert($post)
    {
        $post->id_file = $post->id_file[0];
        $post->updated_by = session('loggedUserId');
        $post->created_by = session('loggedUserId');
        if(!empty($post->ids_road_them)) $post->ids_road_them = array_unique($post->ids_road_them);

        $data = database_encode($this->t_block, $post);
        if(empty((array) $data)) return false;
        
        $this->db->table($this->t_block)->insert($data);
        $id_block = $this->db->insertID();

        return $id_block;
    }

    public function report_insert($post)
    {
        $post->updated_by = session('loggedUserId');
        $post->created_by = session('loggedUserId');
        if(!empty($post->ids_road_them)) $post->ids_road_them = array_unique($post->ids_road_them);
        $data = database_encode($this->t_report, $post);

        if(empty($data)) return false;
        
        $this->db->table($this->t_report)->insert($data);
        $id_report = $this->db->insertID();

        if(!empty($post->blocks)) $this->report_block_replace($id_report, $post->blocks);

        return $id_report;
    }

    public function report_update($id_report, $data)
    {
        $data->updated_at = date('Y-m-d H:i:s');
        $data->updated_by = session('loggedUserId');
        if(!empty($data->ids_road_them)) $data->ids_road_them = array_unique($data->ids_road_them);

        $post = database_encode($this->t_report, $data);
        if(!empty((array) $post)) $this->db->table($this->t_report)->where($this->pk_report, $id_report)->update((array) $post);

        $blocks = !empty($data->blocks) ? $data->blocks : null;
        $this->report_block_replace($id_report, $blocks);
    }

    public function report_block_replace($id_report, $posts=null)
    {
        $this->db->table($this->t_report_block)->where('id_report', $id_report)->delete();
        if(!empty($posts)) :
            foreach($posts as $post) :
                $post = (object) $post;
                $post->id_report = $id_report;
                $data = database_encode($this->t_report_block, $post);
                $this->db->table($this->t_report_block)->insert($data);
            endforeach;
        endif;
    }

    public function report_block_update_by_id($id_report_block, $post)
    {
        if(!empty((array) $post)) $this->db->table($this->t_report_block)->where('id', $id_report_block)->update($post);
    }

    public function get_file_by_id($id_file)
    {
        $files = $this->db->table($this->t_file)->where($this->pk_file, $id_file)->get()->getResult();
        if(empty($files)) return false;

        return database_decode($files[0]);
    }

    public function get_tag_by_id($id_tag)
    {
        $tags = $this->db->table($this->t_tag)->where($this->pk_tag, $id_tag)->get()->getResult();
        if(empty($tags)) return false;

        return database_decode($tags[0]);
    }

    public function get_blocks_by_id_report($id_report)
    {
        $q = $this->db->table($this->t_report_block);
        $q->select($this->t_block . '.*');
        $q->select('
            ' . $this->t_report_block . '.id as id_report_block, 
            ' . $this->t_report_block . '.id_file as id_file_current, 
            ' . $this->t_report_block . '.is_old,
            ' . $this->t_report_block . '.rank
        ');
        $q->join($this->t_block,  $this->t_block . '.id_block = ' . $this->t_report_block . '.id_block', 'left');
        $q->where($this->t_report_block . '.id_report', $id_report);
        $q->orderBy($this->t_report_block . '.rank');
        $blocks = $q->get()->getResult();

        $i = 0;
        foreach($blocks as $block) :
            if($block->id_file != $block->id_file_current) :
                $block->is_updated = 1;
            endif;
            $blocks[$i] = $block;
            $i++;
        endforeach;

        return database_decode($blocks);
    }

    public function get_report_block_by_id($id_report_block)
    {
        $q = $this->db->table($this->t_report_block);
        $q->select($this->t_block . '.*');
        $q->select('
            ' . $this->t_report_block . '.id as id_report_block, 
            ' . $this->t_report_block . '.id_file as id_file_current, 
            ' . $this->t_report_block . '.is_old,
            ' . $this->t_report_block . '.rank
        ');
        $q->join($this->t_block,  $this->t_block . '.id_block = ' . $this->t_report_block . '.id_block', 'left');
        $q->where($this->t_report_block . '.id', $id_report_block);
        $blocks = $q->get()->getResult();

        if(empty($blocks)) return false;

        $block = $blocks[0];
        if($block->id_file != $block->id_file_current) $block->is_updated = 1;

        return database_decode($block);
    }

    public function get_blocks()
    {
        $blocks = $this->db->table($this->t_block)->get()->getResult();

        return database_decode($blocks);
    }

    public function get_tags()
    {
        $tags = database_decode($this->db->table($this->t_tag)->get()->getResult());
        
        $i=0;
        foreach($tags as $tag) :
            $blocks = $this->get_blocks_by_id_tag($tag->id_tag);
            $tag->blocks = !empty($blocks) ? $blocks : [];
            $tags[$i] = $tag;
            $i++;
        endforeach;

        return database_decode($tags);
    }

    public function get_blocks_by_id_tag($id_tag)
    {
        $blocks = $this->db->table($this->t_block)->where('JSON_CONTAINS(ids_tag, ' . $id_tag . ')')->get()->getResult();
        if(empty($blocks)) return false;

        return database_decode($blocks);
    }

    public function get_block_by_id($id_block)
    {
        $blocks = $this->db->table($this->t_block)->where($this->pk_block, $id_block)->get()->getResult();
        if(empty($blocks)) return false;

        return database_decode($blocks[0]);
    }

    public function get_blocks_by_thems_and_tags($post)
    {
        $report = $this->report_get($post->id_report);

        $q = $this->db->table($this->t_block);

        $where_array = [];

        if(!empty($post->ids_block)) :
            $where_array[] = '(' . $this->t_block . '.id_block not in (' . implode(',', $post->ids_block) . '))';
        endif;

        $where_them_array = [];
        $where_them_array[] = $this->t_block . '.ids_road_them is null';
        if(!empty($report->ids_road_them)) :
            foreach($report->ids_road_them as $id_road) :
                $where_them_array[] = 'JSON_CONTAINS(' . $this->t_block . '.ids_road_them, ' . $id_road . ')';
            endforeach;
            $where_array[] = '(1=0 OR ' . implode(' OR ', $where_them_array) . ')';
        endif;

        $where_tag_array = [];
        if(!empty($post->ids_tag)) :
            foreach($post->ids_tag as $id_tag) :
                $where_tag_array[] = 'JSON_CONTAINS(' . $this->t_block . '.ids_tag, ' . $id_tag . ')';
            endforeach;
            $where_array[] = '(1=0 OR ' . implode(' OR ', $where_tag_array) . ')';
        endif;

        if(!empty($where_array)) :
            $where = implode(' AND ', $where_array);
            $q->where($where);
        endif;
        
        $blocks = $q->get()->getResult();

        return database_decode($blocks);
    }

    
    public function get_level_label_by_var($var)
    {
        $list = $this->ListLibrary->get_list_by_ref('id_level');

        if(empty($list)) return false;

        $lang = $this->request->getLocale();
        foreach($list as $elem) :
            if($elem->var == $var) :
                if(!empty($elem->{'label_' . $lang})) return $elem->{'label_' . $lang};
            endif;
        endforeach;

        return false;
    }

    public function reports_get($level)
    {
        $id_level = $this->get_level_id_by_var($level);

        $reports = $this->db->table($this->t_report)->where('id_level', $id_level)->get()->getResult();

        $i = 0;
        foreach($reports as $report) :
            $report->level = $level;
            $report->level_label = $this->get_level_label_by_var($level);
            $report->updated_at = date('d/m/y - H:i', strtotime($report->updated_at));
            $reports[$i] = $report;
            $i++;
        endforeach;

        return database_decode($reports);
    }

    public function report_get($id_report)
    {
        $reports = $this->db->table($this->t_report)->where($this->pk_report, $id_report)->get()->getResult();
        if(empty($reports)) return false;
        
        $report = $reports[0];
        $report->level = $this->get_level_var_by_id($report->id_level);
        $report->level_label = $this->get_level_label_by_id($report->id_level);
        $report->blocks = $this->get_blocks_by_id_report($id_report);

        return database_decode($report);
    }

    public function get_level_var_by_id($id)
    {
        $list = $this->ListLibrary->get_list_by_ref('id_level');
        if(empty($list)) return false;

        foreach($list as $elem) :
            if($elem->id == $id) return $elem->var;
        endforeach;       
    }

    public function get_level_label_by_id($id)
    {
        $list = $this->ListLibrary->get_list_by_ref('id_level');
        if(empty($list)) return false;

        foreach($list as $elem) :
            if($elem->id == $id) return $elem->label_fr;
        endforeach;      
    }
    
    public function get_level_id_by_var($var)
    {
        $list = $this->ListLibrary->get_list_by_ref('id_level');
        if(empty($list)) return false;

        foreach($list as $elem) :
            if($elem->var == $var) return $elem->id;
        endforeach;      
    }

}