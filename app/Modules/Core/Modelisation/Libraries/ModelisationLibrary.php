<?php namespace Modelisation\Libraries;

use Base\Libraries\BaseLibrary;
use Modelisation\Models\ModelisationModel;

class ModelisationLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        $this->ModelisationModel = new ModelisationModel();
    }

    public function getListAddField($type=NULL,$entity=NULL)
    {
       // echo "<h1>$type</h1>";
        if(!is_null($type))
        {
            $fieldsAll=$this->ModelisationModel->getFieldsGestion($type);
            $fieldsSelected=$this->ModelisationModel->getfieldsSelected($type,$entity);
    
            return view('Modelisation\fields-gestion',[
                "fieldsAll" => $fieldsAll,
                "fieldsSelected" => $fieldsSelected
            ]);
        }
        else
        {
            return false;
        }
    }

    public function getListAddFieldInjectedOther($id_injected_form=0,$type=NULL)
    {
        //$fieldsAll=$this->dataViewConstructorModel->getFieldsInjedtedFormDefault();
        $fieldsAll = $this->ModelisationModel->getFieldsGestion($type);

        $fieldsSelected=$this->dataViewConstructorModel->getFieldsInjedtedFormSelected($id_injected_form);
        return view('Modelisation\fields-gestion',[
            "fieldsAll" => $fieldsAll,
            "fieldsSelected" => $fieldsSelected,
            "is_need_clone" => FALSE,
            "type_clone" => "injected_form"
        ]);
    }

} 