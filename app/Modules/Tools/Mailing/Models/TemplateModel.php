<?php

namespace Mailing\Models;

use Base\Models\BaseModel;
use DataView\Libraries\DataViewConstructor;

class TemplateModel extends BaseModel 
{   
	protected $allowedFields;
	protected $fields;
	protected $returnType = 'object';
	protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->table = $this->t_template;
        $this->primaryKey = get_primary_key($this->table);
    }

    public function TemplateDelete($id_template)
    {
        debugd('todo !!!');
    }

    public function TemplatesGet($order=null, $request=null, $no_pager=null)
    {
        $modeldata = $this->TemplateModelData($order, $request);
        if(!empty($no_pager) || (!empty($request->getGet('per_page')) && $request->getGet('per_page')=='all')) :
            $templates = $modeldata->find();
        else :
            $per_page = !empty($request->getGet('per_page')) ? $request->getGet('per_page') : 20;
            $templates = $modeldata->paginate($per_page);
        endif;
        $templates = database_decode($templates);

        return $templates;
    }

    public function TemplatesPagerGet()
    {
        return $this->pager;
    }

    private function TemplateModelData($order=null, $request=null)
	{
        $DataView = new DataViewConstructor();

        $this->resetQuery();
		$this->select("
            $this->t_mail_template.*,
            $this->t_user.nom as author_lastname,
            $this->t_user.prenom as author_name,
        ");
        $this->join($this->t_user, "$this->t_user.id = $this->t_mail_template.updated_by", 'left');


        if(!empty($request) && !empty($request->getGet('itemSearch')) && !empty(trim($request->getGet('itemSearch')))) :
            $fieldsSearch = array(
                "$this->t_mail_template.ref", 
                "$this->t_mail_template.label", 
                "$this->t_mail_template.subject_fr", 
                "$this->t_mail_template.hello_fr", 
                "$this->t_mail_template.content_fr", 
                "$this->t_mail_template.greetings_fr", 
            );
            $DataView->setQuerySearch($this, $fieldsSearch);
        endif;

        $order = $DataView->GetOrderFromRequest($order, $request);
        if(!empty($order[0])) $this->orderBy($order[0], $order[1]);
        else $this->orderBy('updated_at', 'desc');

        return $this;
    }

    public function languages_get()
    {
        $q = $this->db->table($this->t_list_lang);
        $q->select('*, LOWER(label) as label');
        $q->where('is_actif IS NOT NULL AND is_actif = 1');
        $q->orderBy('rank');
        return $q->get()->getResult();
    }

    public function template_get_by_ref($ref)
    {
        $results = $this->db->table($this->t_mail_template)->where('trim(lower(' . $this->t_mail_template . '.ref))', $ref)->get()->getResult();

        if(empty($results)) return false;

        $result = database_decode($results[0]);

        return $result;
    }
    
    public function template_delete($id_template)
    {
        $this->db->table($this->t_mail_template)->where('id_template', $id_template)->delete();

        return true;
    }

    public function TemplateGetByRef($ref)
    {
        $modeldata = $this->TemplateModelData();
        $modeldata->where('ref', $ref);
        $template = $modeldata->get()->getRow();

        $template = database_decode($template);

        return $template;      
    }

    public function TemplateGet($id_template)
    {
        $modeldata = $this->TemplateModelData();
        $template = $modeldata->find($id_template);

        $template = database_decode($template);

        return $template;      
    }

    public function templates_get()
    {;
        $templates = $this->db->table($this->t_mail_template)->get()->getResult();
        $templates = database_decode($templates);
        $datas = [];
        foreach($templates as $template):
            $template->update_datehtml = date('d/m/y à H:i', strtotime($template->updated_at));
            $template->create_datehtml = date('d/m/y à H:i', strtotime($template->created_at));
            $datas[] = $template;
        endforeach;

        return $datas;
    }

    public function TemplateSave($post, $id_template=null)
    {
        $post->updated_by = session('loggedUserId');
        if(!empty(database_encode($this->t_mail_template, $post))) :
            if(!empty($id_template)) :
                $post->updated_at = date("Y-m-d H:i:s");
                $this->db->table($this->t_mail_template)
                    ->set(database_encode($this->t_mail_template, $post))
                    ->where('id_template', $id_template)
                    ->update();
            else :
                $post->created_by = session('loggedUserId');        
                $this->db->table($this->t_mail_template)
                    ->set(database_encode($this->t_mail_template, $post))
                    ->insert();
                $id_template = $this->db->insertId();
            endif;
        endif;

        return $id_template;
    }

    public function template_insert($post)
    {
        $post->updated_by = session('loggedUserId');
        $post->created_by = session('loggedUserId');

        $data = database_encode($this->t_mail_template, $post);
        if(!empty($data)) $this->db->insert($this->t_mail_template, $data);
        $id_template = $this->db->getInsertID();

        return $id_template;
    }
}
