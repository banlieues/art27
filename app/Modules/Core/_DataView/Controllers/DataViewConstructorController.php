<?php

namespace DataView\Controllers;

use Base\Controllers\BaseController;

use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

use App\Models\ActivitiesModel;


class DataViewConstructorController extends BaseController 
{
    protected $dataGeneratorModel;
    protected $dataView;
    protected $context;
    protected $path;
    protected $descriptor;
   
    public function __construct()
    {
        
        if(session('loggedUserRoleId')!=1)
        {
             header("Location:".base_url("identification/logout"));
        }

        parent::__construct(__NAMESPACE__);

        $this->dataGeneratorModel = new DataViewConstructorModel();
        $this->dataView = new DataViewConstructor();  
        $this->context = "modelisation";
        $this->datas->context = $this->context;
        $this->path="DataView\Views\/";
    }

	public function index()
    {
        $entities=$this->dataGeneratorModel->getEntities();
      //  $injectedForms=$this->dataGeneratorModel->getListInjectedForm();

        //$conditions=$this->dataGeneratorModel->getInjectedForm_conditions();

        //$has_filtre_spip=$this->dataGeneratorModel->getInjectedFormParams_spip();
       // $prefixe_spip=$this->dataGeneratorModel->getInjectedFormPrefixe_spip();

        //debug($injectedForms,true);

        $this->datas->title = "Modélisation";
        $this->datas->subtitle = NULL;
        $this->datas->titleView = "Modélisation";
        $this->datas->entities = $entities;
       // $this->datas->injectedForms = $injectedForms;
       // $this->datas->has_filtre_spip = $has_filtre_spip;
       // $this->datas->prefixe_spip = $prefixe_spip;
        //$this->datas->conditions = explode(",",$conditions);
        
        return view($this->path."modelisation-index", (array) $this->datas);

        // return view($this->path."modelisation-index", 
        // [   
        //     'title' => "Modélisation",
        //     'subtitle' => NULL,
		// 	"titleView" => "Modélisation",
        //     'context' => $this->context,
        //     'entities'=>$entities,
        //     'injectedForms'=>$injectedForms,
        //     "has_filtre_spip"=>$has_filtre_spip,
        //     "prefixe_spip"=>$prefixe_spip,
        //     "conditions"=>explode(",",$conditions),
           
        // ]);   
       
    }

   

    public function list_fields($entity)
    {
        $entities=$this->dataGeneratorModel->getOneEntities($entity);
        $fields=$this->dataGeneratorModel->getFieldsArray($entity,TRUE);

        $this->datas->title = "Modélisation";
        $this->datas->subtitle = NULL;
        $this->datas->titleView = "Liste des champs de l'entité ". $entities->label;
        $this->datas->entities = $entities;
        $this->datas->label_entity = $entities->label;
        $this->datas->fields = $fields;
        
        return view($this->path."modelisation-list-fields", (array) $this->datas);

        // return view($this->path."modelisation-list-fields", 
        // [   
        //     'title' => "Modélisation",
        //     'subtitle' => NULL,
		// 	"titleView" => "Liste des champs de l'entité ". $entities->label,
        //     'context' => $this->context,
        //     'entities'=>$entities,
        //     'label_entity'=>$entities->label,
        //     'fields'=>$fields
        // ]);
    }

    public function edit_field($mode,$entity,$field=NULL,$validation=NULL)
    {
        
        $lists=[];
        $typeFields=NULL;

        $entities=$this->dataGeneratorModel->getOneEntities($entity);
        $field=$this->dataGeneratorModel->getOneField($field);
        $typeFields=$this->dataGeneratorModel->getListTypeFields();
        

        
        if(isset($field->type_field)&&(in_array($field->type_field,["check","radio","select"])))
        {
            $lists=$this->dataGeneratorModel->getListField($field);
        }
        //debug($lists);

        if($mode=="update")
        {
            $titleView="Modélisation/".ucfirst($entities->label)."/".$field->label;
        }
        else
        {
            $titleView="Modélisation/".ucfirst($entities->label)."/Créer un nouveau champ";
        }

        $this->datas->title = "Modélisation";
        $this->datas->subtitle = NULL;
        $this->datas->titleView = $titleView;
        $this->datas->entities = $entities;
        $this->datas->field = $field;
        $this->datas->mode = $mode;
        $this->datas->lists = $lists;
        $this->datas->typeFields = $typeFields;
        $this->datas->dataGeneratorModel = $this->dataGeneratorModel;
        $this->datas->validation = $validation;
        $this->datas->entity = $entity;

        return view($this->path."modelisation-form-fields", (array) $this->datas);

        // return view($this->path."modelisation-form-fields", 
        // [   
        //     'title' => "Modélisation",
        //     'subtitle' => NULL,
		// 	"titleView" => $titleView,
        //     'context' => $this->context,
        //     'entities'=>$entities,
        //     'field'=>$field,
        //     'mode'=>$mode,
        //     "lists"=>$lists,
        //     "typeFields"=>$typeFields,
        //     "dataGeneratorModel"=>$this->dataGeneratorModel,
        //     "validation"=>$validation,
        //     "entity"=>$entity,
        // ]);  
    }


    public function saveField()
    {
        $validation =  \Config\Services::validation();
    
        $mode=$this->request->getVar("mode");
        $entity=$this->request->getVar("entity");
        $field=$this->request->getVar("field_index");

        if(empty($field)){$field="Sans Nom";}

        $getVar=$this->request->getVar();
        $total_item=$this->request->getVar("num_item_list");//nombre total de ligne pour la liste liée

        $rules=[];

        //rules for field_index
        if($mode=="insert")
        {
            $rules["field_index"]["label"]="label";
            $rules["field_index"]["rules"]="trim|required|alpha_dash|max_length[255]|is_unique[ban_fields.field_index]";
            $rules["field_index"]["errors"]=
                array(
                    'required' => "L'index du champ ne peut pas être vide!",
                    "max_length"=> "L'index du champ ne peut pas dépasser 255 caractères!",
                    'alpha_dash'=>"L'index du champ ne doit pas contenir d'espace ou de caractères spéciaux!",
                    "is_unique"=>"L'index du champ est déjà utilisé par un autre champ. Les index doivent être unique!"
                );
        }

        //rules for label
        $rules["label"]["label"]="label";
        $rules["label"]["rules"]="trim|required|max_length[255]";
        $rules["label"]["errors"]=array(
            'required' => "Le label du champ qui a pour index $field ne peut pas être vide!",
            "max_length"=> "Le label du champ qui a pour index $field ne peut pas dépasser 255 caractères!",
        );

        //Rules for list 
        if(in_array($getVar["type_field"],["select","check","radio"]))
        {
            if($total_item==0){
                //cas où l'user à afficher toutes les lignes de la liste liées
                //Il est nécessaire d'introduire une régle
                $num_item_list=0;
                $rules["label_item_##$num_item_list"]["label"]="label_item_##$num_item_list";
                    $rules["label_item_##$num_item_list"]["rules"]="trim|required|max_length[255]|not_in_list[".$this->getListPossible($getVar,'label_item_##',$num_item_list,$total_item)."]";
                    $rules["label_item_##$num_item_list"]['errors']= 
                    array(
                        'required' => "La liste liée ne peut pas être vide!",
                        "max_length"=> "Le label de l'item #$num_item_list ne peut pas dépasser 255 caractères!",
                        "not_in_list"=>"Le label de l'item #$num_item_list ne peut pas être identique à un autre label de la liste!"
                    );
            
            }

            for($num_item_list=1;$num_item_list<=$total_item;$num_item_list++)
            {
                if(isset($getVar["label_item_##$num_item_list"]))
                {
                    $rules["label_item_##$num_item_list"]["label"]="label_item_##$num_item_list";
                    $rules["label_item_##$num_item_list"]["rules"]="trim|required|max_length[255]|not_in_list[".$this->getListPossible($getVar,'label_item_##',$num_item_list,$total_item)."]";
                    $rules["label_item_##$num_item_list"]['errors']= 
                    array(
                        'required' => "Le label de l'item #$num_item_list de la liste liée ne peut pas être vide!",
                        "max_length"=> "Le label de l'item #$num_item_list ne peut pas dépasser 255 caractères!",
                        "not_in_list"=>"Le label de l'item #$num_item_list ne peut pas être identique à un autre label de la liste!"
                    );
                    
                }

                if(isset($getVar["ref_item_##$num_item_list"]))
                {
                    $rules["ref_item_##$num_item_list"]["label"]="ref_item_##$num_item_list";
                    $rules["ref_item_##$num_item_list"]["rules"]="trim|required|alpha_dash|max_length[255]|not_in_list[".$this->getListPossible($getVar,'ref_item_##',$num_item_list,$total_item)."]";
                    $rules["ref_item_##$num_item_list"]['errors']= 
                        array(
                            'required' => "La référence de l'item #$num_item_list de la liste liée ne peut pas être vide!",
                            'alpha_dash'=>"La référence de l'item #$num_item_list ne doit pas contenir d'espace ou de caractères spéciaux!",
                            "max_length"=> "La référence de l'item #$num_item_list ne peut pas dépasser 255 caractères!",
                            "not_in_list"=>"La référence de l'item #$num_item_list ne peut pas être identique à une autre référence de la liste!"
                        );
                    
                }
            }
        }
        //debug($rules);

        $validation->setRules($rules);

        if($validation->withRequest($this->request)->run())
        {
            $this->dataGeneratorModel->saveMetaData($getVar,$total_item);
            if($getVar["mode"]=="update")
            {
                $message= 'Les méta-données du champ '.$getVar['label'].' ont été enregistrées';
            }
            else
            {
                $message= 'Le champ '.$getVar['label'].' a été créé et ses méta-données ont été enregistrées';
            }
            return redirect()->to(base_url()."/modelisation/list_fields/$entity")->with('success', $message);


        }else{
            //debug($validation->getErrors());
            echo $this->edit_field($mode,$entity,$field,$this->validator);
        }
        
        //debug($this->request->getVar());
          
    }
 
    protected function getListPossible($getVar,$type_item,$num_item_list_select,$total_item)
    {
        $in_list=[];
        for($num_item_list=1;$num_item_list<=$total_item;$num_item_list++)
        {
            $index_item=$type_item.$num_item_list;
            if($num_item_list!=$num_item_list_select&&isset($getVar[$index_item])&&!empty($getVar[$index_item]))
            {
                $in_list[]=$getVar[$index_item];
            }

        }
        if(empty($in_list))
            return NULL;

        return implode(",",$in_list);    
    }

   //Methods for injected form
    public function injectedForm($id_injected_form=0)
    {
        $dataView=new DataViewConstructor();
        $fields=$this->dataGeneratorModel->getFields();


        if($id_injected_form>0)
        {
            $injectedForm=$this->dataGeneratorModel->getInjectedForm($id_injected_form);
            $valueHeaderText=$injectedForm->header_text;
        }
        else
        {
            $injectedForm=$this->dataGeneratorModel->getInjectedFormDefault();
            $valueHeaderText=$this->dataGeneratorModel->getLastHeaderText();
            //debug($valueHeaderText,TRUE);
        }

        $injectedFormConditions=$this->dataGeneratorModel->getInjectedFormConditions();
        
        //connaitre les paramètres de l'injection form
        $has_injectedFormParams_spip=$this->dataGeneratorModel->getInjectedFormParams_spip();

        //debug($injectedForm, true);

        if(isset($injectedForm->filtre_spip)&&!empty($injectedForm->filtre_spip))
        {
            $filtre_spip_value=explode(",",$injectedForm->filtre_spip);
        }
        else{
            $filtre_spip_value=[];
        }

        $this->datas->title = "Modélisation";
        $this->datas->subtitle = NULL;
        $this->datas->titleView = "Modélisation";
        $this->datas->injectedForm = $injectedForm;
        $this->datas->fields = $fields;
        $this->datas->dataView = $dataView;
        $this->datas->id_injected_form = $id_injected_form;
        $this->datas->injectedFormConditions = $injectedFormConditions;
        $this->datas->valueHeaderText = $valueHeaderText;
        $this->datas->filtres_spip = NULL;
        $this->datas->filtre_spip_value = $filtre_spip_value;
        $this->datas->has_spip_filtre = $has_injectedFormParams_spip;

        return view($this->path."injected-form", (array) $this->datas);

        // return view($this->path."injected-form", 
        // [   
        //     'title' => "Modélisation",
        //     'subtitle' => NULL,
		// 	"titleView" => "Modélisation",
        //     'context' => $this->context,
        //     'injectedForm'=>$injectedForm,
        //     'fields'=>$fields,
        //     'dataView'=>$dataView,
        //     'id_injected_form'=>$id_injected_form,
        //     'injectedFormConditions'=>$injectedFormConditions,
        //     'valueHeaderText'=>$valueHeaderText,
        //     "filtres_spip"=>NULL,
        //     "filtre_spip_value"=>$filtre_spip_value,
        //     "has_spip_filtre"=>$has_injectedFormParams_spip
       
           
        // ]);   
    }

    public function injectedFormIframeOnly($id_injected_form=0)
    {
       
        $dataView=new DataViewConstructor();
        $fields=$this->dataGeneratorModel->getFields();
        $injectedForm=$this->dataGeneratorModel->getInjectedForm($id_injected_form);
        
        if(isset($injectedForm->fields))
        {
            $indexes_form=explode(",",$injectedForm->fields);
        }

        $this->datas->title = "form";
        $this->datas->subtitle = NULL;
        $this->datas->titleView = "form";
        $this->datas->injectedForm = $injectedForm;
        $this->datas->fields = $fields;
        $this->datas->dataView = $dataView;
        $this->datas->is_frame = TRUE;
        $this->datas->id_injected_form = $id_injected_form;
        $this->datas->id_contact = 0;
        $this->datas->indexes_form = $indexes_form;
        $this->datas->id_activity = 0;
        $this->datas->activity = NULL;
        $this->datas->id_user = 0;
        $this->datas->no_menu = TRUE;

        return view($this->path."injected-form-iframe_distant", (array) $this->datas);
        
        // return view($this->path."injected-form-iframe_distant", 
        // [   
        //     'title' => "form",
        //     'subtitle' => NULL,
		// 	"titleView" => "form",
        //     'context' => $this->context,
        //     'injectedForm'=>$injectedForm,
        //     'fields'=>$fields,
        //     'dataView'=>$dataView,
        //     "is_frame"=>TRUE,
        //     "id_injected_form"=>$id_injected_form,
        //     "id_contact"=>0,
        //     "indexes_form"=>$indexes_form,
        //     "id_activity"=>0,
        //     "activity"=>NULL,
        //     "id_user"=>0,
        //     "no_menu"=>TRUE
           
        // ]);   
        
    }


    public function injectedFormIframe($id_injected_form=0)
    {

         echo "youpiie";   
    }

    
   

    public function saveInjectedForm()
    {
       if($this->request->getVar())
       {
            $posts=$this->request->getVar();
            $this->dataGeneratorModel->saveInjectedForm($posts);


            
            $message= 'Le formulaire '.$posts["title"].' a été enregistré';


                return redirect()->to(base_url()."/modelisation")->with("success",$message);
                //return redirect()->to(base_url()."/registration/listinscription");
        

       }
       else
       {
        return redirect()->to(base_url()."/modelisation"); 
       }
    }
    


    
}