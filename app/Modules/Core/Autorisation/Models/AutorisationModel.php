<?php

namespace Autorisation\Models;

use Base\Libraries\InitLibrary;
use Base\Models\BaseModel;

class AutorisationModel extends BaseModel
{
    protected $returnType = 'object';
	protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->table = $this->t_autorisation;
        $this->primaryKey = get_primary_key($this->t_autorisation);
    }

    public function get_autorisation($id_user)
    {
        $this->where("id_user",$id_user);

        return $this->get()->getRow();
    }
  
    private function is_exist_autorisation($id_user)
    {
        $builder=$this->db->table($this->table);
        $builder->where("id_user", $id_user);

        $autorisation=$builder->get()->getRow();
        
        if(empty($autorisation)) return FALSE;

        return TRUE;
    }
  
    public function save_autorisation()
    {
        $config = new \Custom\Config\Autorisation();

        $q = $this->db->table($this->t_autorisation);
        $cruds = ["r", "u", "c", "d"];
        $id_user = $this->request->getGet('id_user') ?? session('loggedUserId');

        foreach($config->entities as $key=>$entity) :
            foreach($cruds as $crud) :
                $key_crud = $key.'_'.$crud;
                if($this->request->getPost($key_crud)) $data[$key_crud] = $this->request->getPost($key_crud);
                else $data[$key_crud] = 0;
            endforeach;
        endforeach;

        foreach($config->outils as $key=>$outil_config) :
            $key_crud=$key;
            if($this->request->getPost($key_crud)) $data[$key_crud] = $this->request->getPost($key_crud);
            else $data[$key_crud] = 0;           
        endforeach;

        if($this->is_exist_autorisation($id_user)) :
            $data = database_encode($this->table, $data);
            $q->where("id_user", $id_user);
            $q->update($data);
        else :
            $q->insert($data);
        endif;
    }

    public function is_autorise($element, $created_by=null)
    {
        $q = $this->db->table($this->table);
        $q->where("id_user", session('loggedUserId'));
        $result = $q->get()->getRow();

        if(isset($result->$element)) :
            if($result->$element==2) :
                return true;
            elseif($result->$element==1) :
                if(!empty($created_by) && $created_by==session('loggedUserId')) :
                    return true;
                elseif(empty($created_by)) :
                    return true;
                endif;
            endif;
        endif;

        return false;



        // if(!preg_match('/^(r|u|c|d)/', $element)) return false;

        // $alternative = substr($element, 1) . '_' . substr($element, 0, 1);
        // $result = $this->db->table($this->table)->where("id_user", session('loggedUserId'))->where($alternative, 1)->get()->getResult();

        // if(!empty($result)) return TRUE;

        // return FALSE;
    }

    // public function is_outils_autorise()
    // {
    //     $this->config_autorisation=config("\Custom\Config\Autorisation");

    //     //$this->entities_config=$this->config_autorisation->config["entities_config"];
    //     $outils_config=$this->config_autorisation->config["outils_config"];

    //     foreach($outils_config as $key=>$outil)
    //     {
    //         $key_outil=$key;
    //         if($this->is_autorise($key_outil)) return TRUE;
    //     }
    //     return FALSE;
    // }

    // public function is_gestion_autorise()
    // {
    //     $this->config_autorisation=config("\Custom\Config\Autorisation");

    //     $entities_config=$this->config_autorisation->config["entities_config"];

    //    // $cruds=["r","u","c","d"];
    //     $cruds=["r"];
    //     foreach($entities_config as $key=>$entity)
    //     {
    //         foreach($cruds as $crud):
    //             $key_entity=$key."_".$crud;
    //             if($this->is_autorise($key_entity)) return TRUE;
    //         endforeach;
    //     }
    //     return FALSE;
    // }

    public function is_gestion_entity_autorise($entity)
    {
      
        $cruds=["r","u","c","d"];
       
        foreach($cruds as $crud):
            $key_entity=$entity."_".$crud;
            if($this->is_autorise($key_entity)) return TRUE;
        endforeach;
    
        return FALSE;
    }
   
    // public function user_has_autorisation($id_user)
    // {
    //     $builder=$this->db->table("user_autorisation");
    //     $builder->where("id_user",$id_user);

    //     $result=$builder->get()->getRow();

    //     if(!empty($result)) return TRUE;

    //     return FALSE;

    // }

    // public function is_membre_interne($id_user)
    // {
    //     $builder=$this->db->table("user_accounts");
    //     $builder->where("id",$id_user);
    //     $builder->where("role_id",1);

    //     $result=$builder->get()->getRow();

    //     if(!empty($result)) return TRUE;

    //     return FALSE;

    // }

	// public function init_profil_default($id_user)
    //  {
    //     if(!$this->user_has_autorisation)
    //     {
    //         if($this->is_membre_interne($id_user))
    //         {
    //             $this->config_autorisation=config("\Custom\Config\Autorisation");

    //             //$this->entities_config=$this->config_autorisation->config["entities_config"];
    //             $default_autorisation=$this->config_autorisation->config["default_autorisation"];

    //             $data=NULL;

    //             foreach($default_autorisation as $default)
    //             {
    //                 $data[$default]=1;
    //             }

    //             if(!empty($data))
    //             {
    //                 $data["id_user"]=$id_user;
    //             }

    //             $builder=$this->db->table("user_autorisation");
    //             $builder->insert($data);
    //         }
    //     }
          
    //  }
}