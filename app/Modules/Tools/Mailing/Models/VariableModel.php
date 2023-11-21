<?php

namespace Mailing\Models;

use Base\Models\BaseModel;
use DataView\Libraries\DataViewConstructor;

class VariableModel extends BaseModel 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $this->table = $this->t_variable;
        $this->primaryKey = get_primary_key($this->table);
    }

    public function VariablesGet($order=null, $request=null, $no_pager=null)
    {
        $modeldata = $this->VariableModelData($order, $request);
        if(!empty($no_pager) || (!empty($request->getGet('per_page')) && $request->getGet('per_page')=='all')) :
            $variables = $modeldata->find();
        else :
            $per_page = !empty($request->getGet('per_page')) ? $request->getGet('per_page') : 20;
            $variables = $modeldata->paginate($per_page);
        endif;
        $variables = database_decode($variables);

        return $variables;
    }

    public function VariablesPagerGet()
    {
        return $this->pager;
    }

    private function VariableModelData($order=null, $request=null)
	{
        $DataView = new DataViewConstructor();

        $this->resetQuery();
		$this->select("
            $this->t_variable.*,
            $this->t_user.nom as created_by_nom,
            $this->t_user.prenom as created_by_prenom,
        ");
        $this->join($this->t_user, "$this->t_user.id = $this->t_variable.created_by", 'left');


        if(!empty($request) && !empty($request->getGet('itemSearch')) && !empty(trim($request->getGet('itemSearch')))) :
            $fieldsSearch = array(
                "$this->t_variable.ref", 
                "$this->t_variable.label",
            );
            $DataView->setQuerySearch($this, $fieldsSearch);
        endif;

        $order = $DataView->GetOrderFromRequest($order, $request);
        if(!empty($order[0])) $this->orderBy($order[0], $order[1]);
        else $this->orderBy('label', 'asc');

        return $this;
    }
    
    public function VariableDelete($id_variable)
    {
        $this->delete($id_variable);

        return true;
    }

    public function VariableGet($id_variable)
    {
        $modeldata = $this->VariableModelData();
        $variable = $modeldata->find($id_variable);

        $variable = database_decode($variable);

        return $variable;      
    }

    public function VariableSave($post, $id_variable=null)
    {
        $post->updated_by = session('loggedUserId');
        if(!empty(database_encode($this->t_variable, $post))) :
            if(!empty($id_variable)) :
                $post->updated_at = date("Y-m-d H:i:s");
                $this->db->table($this->t_variable)
                    ->set(database_encode($this->t_variable, $post))
                    ->where('id_variable', $id_variable)
                    ->update();
            else :
                $post->created_by = session('loggedUserId');        
                $this->db->table($this->t_variable)
                    ->set(database_encode($this->t_variable, $post))
                    ->insert();
                $id_variable = $this->db->insertId();
            endif;
        endif;

        return $id_variable;
    }
}
