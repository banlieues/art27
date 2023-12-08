<?php

namespace DataView\Controllers;

use Base\Controllers\BaseController;
use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

class DataView extends BaseController 
{
    protected $fields;


    public function __construct()
    {
        $dataViewConstructor=new DataViewConstructor();
        $this->fields=$dataViewConstructor->getFields();

        $this->dataViewModel=new DataViewConstructorModel();
    }

    public function index()
    {
        if(session('loggedUserRoleId')==1) :

            return redirect()->to('dashboard');

        else :

            return redirect()->to('identification/logout');

        endif;
    }

    public function ajout_multiple()
    {
        if($this->request->getVar())
        {
            
            return view("DataView\card/card_ajout_multiple",
                [
                    "i"=>$this->request->getVar("new_count_data"),
                    "fields_index"=>$this->request->getVar("fields"),
                    "label_multiple_title"=>$this->request->getVar("label_multiple_title"),
                    "champ_multiple_title"=>$this->request->getVar("champ_multiple_title"),
                    "name_id_multiple"=>$this->request->getVar("name_id_multiple"),
                    "id_components"=>$this->request->getVar("id_components"),
                    "typeDataView"=>'create',
                    "fields"=>$this->fields,


                ]
            
            );
        }
        else
        {
            echo '<div class="text-center text-danger m-2">';
            echo "ERROR ! Impossible d'ajouter!";
            echo '</div>';
        }
    }

    public function delete_multiple()
    {
        
    }
}
