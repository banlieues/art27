<?php

namespace Autorisation\Libraries;

use Autorisation\Models\AutorisationModel;
use Base\Libraries\BaseLibrary;

class AutorisationLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $this->AutorisationModel = new AutorisationModel();
    }

    public function save_autorisation()
    {
        return $this->AutorisationModel->save_autorisation();
    }

    public function is_autorise($element, $created_by=null)
    {
        return $this->AutorisationModel->is_autorise($element, $created_by);
    }

    public function get_autorisation($id_user)
    {
        return $this->AutorisationModel->get_autorisation($id_user);
    }

    public function is_gestion_entity_autorise($entity)
    {
        return $this->AutorisationModel->is_gestion_entity_autorise($entity);
    }

    public function HasCrud($autorisation_keys, $entity_ref)
    {
        foreach($autorisation_keys as $key) :
            if(preg_match('/^' . $entity_ref . '_(r|u|c|d)$/', $key)) return true;
        endforeach;

        return false;
    }

    public function HasMoreThanCrud($autorisation_keys, $entity_ref)
    {   
        foreach($autorisation_keys as $key) :
            if(preg_match('/^' . $entity_ref . '/', $key) && !preg_match('/^' . $entity_ref . '_(r|u|c|d)$/', $key)) return true;
        endforeach;

        return false;
    }
}