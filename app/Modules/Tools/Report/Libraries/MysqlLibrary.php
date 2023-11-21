<?php

namespace Report\Libraries;

use Report\Config\Globals;
use Base\Libraries\BaseLibrary;
use Components\Libraries\DatabaseLibrary;
use Translator\Libraries\TranslatorLibrary;

class MysqlLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        $globals = new Globals();
        foreach($globals as $global=>$value) $this->$global = $value;

        $this->db_l = new DatabaseLibrary(__NAMESPACE__);
        $this->transl_l = new TranslatorLibrary();
    }

    public function import_production()
    {
        $this->db->transStart();
        $this->import_production_tag();
        $sections = $this->db->table('rp_section_old')->get()->getResult();
        foreach($sections as $section) :
            $id_file = $this->import_production_file($section->id_file);
            $id_block = $this->import_production_block($section, $id_file);
        endforeach;
        $this->db->transComplete();
    }

    private function import_production_tag()
    {
        $tags = $this->db->table('rp_tag_old')->get()->getResult();
        foreach($tags as $tag) :
            $tags_new = $this->db->table($this->t_tag)->where('label', $tag->name)->get()->getResult();
            if(!empty($tags_new)) continue;

            $data = (object) [];
            $data->label = $tag->name;
            $data->comment = $tag->comment;
            $data->created_by = !empty($tag->created_by) ? $tag->created_by : 24;
            $data->created_at = $tag->created_at;
            $data = database_encode($this->t_tag, $data);
            $this->db->table($this->t_tag)->insert($data);
            _print($this->db->getLastQuery());
        endforeach;
    }

    private function import_production_block($section, $id_file)
    {
        $blocks = $this->db->table($this->t_block)->where('label', $section->section_name)->get()->getResult();
        if(!empty($blocks)) return;

        $data = (object) [];
        $data->id_file = $id_file;
        $data->ids_tag = $this->import_production_block_ids_tag($section->ids_tag);
        $data->label = $section->section_name;
        $data->comment = $section->comment;
        $data->updated_by = !empty($section->updated_by) ? $section->updated_by : 24;
        $data->updated_at = $section->updated_at;
        $data->created_by = !empty($section->created_by) ? $section->created_by : 24;
        $data->created_at = $section->created_at;
        $data = database_encode($this->t_block, $data);

        $this->db->table($this->t_block)->insert($data);
        _print($this->db->getLastQuery());
        $id_block = $this->db->insertID();

        return $id_block;
    }

    private function import_production_block_ids_tag($ids_tag)
    {
        $ids_tag_new = [];
        $ids_tag = explode(',', $ids_tag);
        foreach($ids_tag as $id_tag) :
            $tags = $this->db->table('rp_tag_old')->where('id_tag', $id_tag)->get()->getResult();
            if(empty($tags)) continue;

            $tag = $tags[0];
            $tags_new = $this->db->table($this->t_tag)->where('label', $tag->name)->get()->getResult();
            if(empty($tags_new)) continue;

            $tag_new = $tags_new[0];
            $ids_tag_new[] = $tag_new->id_tag;
        endforeach;

        return $ids_tag_new;
    }

    private function import_production_file($id_file)
    {
        if(empty($id_file)) return;

        $files = $this->db->table('rp_file_old')->where('id_file', $id_file)->get()->getResult();
        if(empty($files)) return;

        $file = $files[0];
        $name = $file->section_name_orig . $file->file_ext;

        $this->load->library('file_library', ['module'=>$this->module]);
        $pathinfo = (object) pathinfo($file->full_path);
        $path = base_url('assets/rp/docx/section/' . $pathinfo->basename);
        $datetime = date('ymdHis', strtotime($file->created_at));
        $upload = $this->file_library->file_copy_url_to_folder($path, $name, $datetime);
        if(empty($upload)) return;

        _print($upload);
        $data = (object) [];
        $data->name = $name;
        
        $data->commentaire = $file->commentaire;
        $data->id_user = !empty($file->id_user) ? $file->id_user : 24;
        $data->date_created = $file->created_at;

        $data->contentByte = $upload->contentByte;
        $data->contentByte_Type = $upload->contentByte_Type;
        $data->url_file = $upload->url_file;

        $data = database_encode($this->t_file, $data);
        $files_new = $this->db->table($this->t_file)->where('name', $name)->get()->getResult();
        if(!empty($files_new)) return $files_new[0]->id;

        $this->db->table($this->t_file)->insert($data);
        _print($this->db->getLastQuery());
        $id_file_new = $this->db->insertID();
        return $id_file_new;
    }

    public function check_database()
    {
        $this->transl_l->check_database__translator();
        $this->check_database__rp_tag();
        $this->check_database__rp_file();
        $this->check_database__rp_block();
        $this->check_database__rp_report();
        $this->check_database__rp_report_block();
        $this->check_database__fe_roads();
    }

    public function check_database__rp_tag()
    {
        $file = $this->path . 'json/tag/table.json';

        if(file_exists($file)) :
            if(!$this->db->tableExists($this->t_tag)) :
                $this->db_l->sql_create_table_from_file($this->t_tag, $file);
            else :
                $this->db_l->sql_alter_table_from_file($this->t_tag, $file);
            endif;
        endif;
    }

    public function check_database__rp_file()
    {
        $file = $this->path . 'json/file/table.json';

        if(file_exists($file)) :
            if(!$this->db->tableExists($this->t_file)) :
                $this->db_l->sql_create_table_from_file($this->t_file, $file);
            else :
                $this->db_l->sql_alter_table_from_file($this->t_file, $file);
            endif;
        endif;
    }

    public function check_database__rp_block()
    {
        $file = $this->path . 'json/block/table.json';

        if(file_exists($file)) :
            if(!$this->db->tableExists($this->t_block)) :
                $this->db_l->sql_create_table_from_file($this->t_block, $file);
            else :
                $this->db_l->sql_alter_table_from_file($this->t_block, $file);
            endif;
        endif;
    }

    public function check_database__rp_report()
    {
        $file = $this->path . 'json/report/table.json';

        if(file_exists($file)) :
            if(!$this->db->tableExists($this->t_report)) :
                $this->db_l->sql_create_table_from_file($this->t_report, $file);
            else :
                $this->db_l->sql_alter_table_from_file($this->t_report, $file);
            endif;
        endif;
    }

    public function check_database__rp_report_block()
    {
        $file = $this->path . 'json/report_block/table.json';

        if(file_exists($file)) :
            if(!$this->db->tableExists($this->t_report_block)) :
                $this->db_l->sql_create_table_from_file($this->t_report_block, $file);
            else :
                $this->db_l->sql_alter_table_from_file($this->t_report_block, $file);
            endif;
        endif;
    }

    public function check_database__fe_roads()
    {
        $roads = ['them'];
        $this->db_l->check_database__fe_roads($roads);
    }
}