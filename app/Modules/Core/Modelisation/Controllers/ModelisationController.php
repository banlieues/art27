<?php

namespace Modelisation\Controllers;

use Base\Controllers\BaseController;
use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;
use Modelisation\Libraries\ModelisationLibrary;
use Modelisation\Models\ModelisationModel;

class ModelisationController extends BaseController 
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

        $this->ModelisationLibrary = new ModelisationLibrary();
        $this->ModelisationModel = new ModelisationModel();
        $this->DataViewModel = new DataViewConstructorModel();
        $this->dataView = new DataViewConstructor();  
        $this->context = "modelisation";
        $this->datas->context = $this->context;
    }

	public function index()
    {
        $entities=$this->DataViewModel->getEntities();

        $this->datas->titleView = "Modélisation - Liste des entités";
        $this->datas->entities = $entities;
        
        return view("Modelisation\index", (array) $this->datas);
    }

    public function Fiche($entity_ref)
    {
        $validation = \Config\Services::validation();

        $entity = $this->DataViewModel->getOneEntities($entity_ref);

        

        if($this->request->getPost()) :
            $this->dataView->setComponents($entity_ref, $this->request->getPost());

            return redirect()->to(base_url("modelisation/$entity_ref/fiche"))->with("success", "Le modèle de la fiche $entity->label a été enregistré");
        endif;

        $components = $this->dataView->getComponents($entity_ref);
       

        if(empty($components)) return "Aucune page définie pour l'entité <b> $entity->label </b>";

        $this->datas->entity = $entity;
        $this->datas->fields= $this->DataViewModel->getFields();
        $this->datas->titleView= "Modélisation - Fiche de l'entité <b> $entity->label </b>";
        $this->datas->dataView = $this->dataView;
        $this->datas->components= $components;
        $this->datas->validation = $validation;
        $this->datas->ModelisationLibrary = $this->ModelisationLibrary;

       

        return view('Modelisation\fiche', (array) $this->datas);
    }

    public function Fields($entity_ref)
    {
        $entity = $this->DataViewModel->getOneEntities($entity_ref);
        $fields = $this->DataViewModel->getFieldsArray($entity_ref, TRUE);

        $this->datas->titleView = "Modélisation - Liste des champs de l'entité <b> $entity->label </b>";
        $this->datas->entity = $entity;
        $this->datas->fields = $fields;
        
        return view('Modelisation\fields', (array) $this->datas);
    }

    public function Field($entity_ref, $mode, $field=null)
    {
        if($this->request->getPost()) :

            $post = (object) $this->request->getPost();
            
            $field = empty($field) ? "Sans Nom" : $field;
            $rules = $this->getRules($mode, $field, $post);

            $validation =  \Config\Services::validation();
            $validation->setRules($rules);
    
            if($validation->withRequest($this->request)->run()) :
                $this->ModelisationModel->FieldSave($mode, $post, $post->num_item_list);
                $message = $mode=="update" ? "Les méta-données du champ $post->label ont été enregistrées" : "Le champ $post->label a été créé et ses méta-données ont été enregistrées";
                $field = !empty($field) ? $field : $post->field_index;
                return redirect()->to(current_url())->with('success', $message);
            else :
                $this->datas->validation = $validation;
                $this->session->setFlashdata('danger', implode('<br>', $validation->getErrors()));
            endif;
        endif;

        $entity = $this->DataViewModel->getOneEntities($entity_ref);
        $field = $this->DataViewModel->getOneField($field);
        $typeFields = $this->DataViewModel->getListTypeFields();
        $lists = (isset($field->type_field) && (in_array($field->type_field,["check","radio","select"]))) ? $this->DataViewModel->getListField($field) : [];

        $this->datas->titleView = $mode=='update' ? "Modélisation - Editer le champ " . ucfirst($entity->label) . "/" . $field->label : "Modélisation - Editer le champ " . ucfirst($entity->label);
        $this->datas->entity = $entity;
        $this->datas->field = $field;
        $this->datas->mode = $mode;
        $this->datas->lists = $lists;
        $this->datas->typeFields = $typeFields;
        $this->datas->request = $this->request;
        $this->datas->DataViewModel = $this->DataViewModel;

        return view('Modelisation\field-form', (array) $this->datas); 
    }

    public function edit_field($mode,$entity,$field=NULL,$validation=NULL)
    {
        
        $lists=[];
        $typeFields=NULL;

        $entities=$this->DataViewModel->getOneEntities($entity);
        $field=$this->DataViewModel->getOneField($field);
        $typeFields=$this->DataViewModel->getListTypeFields();
        

        
        if(isset($field->type_field)&&(in_array($field->type_field,["check","radio","select"])))
        {
            $lists=$this->DataViewModel->getListField($field);
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
        $this->datas->DataViewModel = $this->DataViewModel;
        $this->datas->validation = $validation;
        $this->datas->entity = $entity;

        return view($this->path."modelisation-form-fields", (array) $this->datas); 
    }

    public function getRules($mode, $field, $post)
    {
        $rules=[];
        $total_item=$post->num_item_list;

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
        if(in_array($post->type_field, ["select","check","radio"]))
        {
            if($total_item==0){
                //cas où l'user à afficher toutes les lignes de la liste liées
                //Il est nécessaire d'introduire une régle
                $num_item_list=0;
                $rules["label_item_##$num_item_list"]["label"] = "label_item_##$num_item_list";
                $rules["label_item_##$num_item_list"]["rules"] = "trim|required|max_length[255]|not_in_list[" . $this->getListPossible($post, 'label_item_##', $num_item_list,$total_item) . "]";
                $rules["label_item_##$num_item_list"]['errors'] = array(
                    'required' => "La liste liée ne peut pas être vide!",
                    "max_length"=> "Le label de l'item #$num_item_list ne peut pas dépasser 255 caractères!",
                    "not_in_list"=>"Le label de l'item #$num_item_list ne peut pas être identique à un autre label de la liste!"
                );
            }

            for($num_item_list=1; $num_item_list<=$total_item; $num_item_list++)
            {
                if(isset($post->{"label_item_##$num_item_list"}))
                {
                    $rules["label_item_##$num_item_list"]["label"] = "label_item_##$num_item_list";
                    $rules["label_item_##$num_item_list"]["rules"] = "trim|required|max_length[255]|not_in_list[" . $this->getListPossible($post,'label_item_##', $num_item_list,$total_item) . "]";
                    $rules["label_item_##$num_item_list"]['errors'] = array(
                        'required' => "Le label de l'item #$num_item_list de la liste liée ne peut pas être vide!",
                        "max_length"=> "Le label de l'item #$num_item_list ne peut pas dépasser 255 caractères!",
                        "not_in_list"=>"Le label de l'item #$num_item_list ne peut pas être identique à un autre label de la liste!"
                    );
                }

                if(isset($post->{"ref_item_##$num_item_list"}))
                {
                    $rules["ref_item_##$num_item_list"]["label"] = "ref_item_##$num_item_list";
                    $rules["ref_item_##$num_item_list"]["rules"] = "trim|required|alpha_dash|max_length[255]|not_in_list[" . $this->getListPossible($post, 'ref_item_##', $num_item_list,$total_item) . "]";
                    $rules["ref_item_##$num_item_list"]['errors'] = array(
                        'required' => "La référence de l'item #$num_item_list de la liste liée ne peut pas être vide!",
                        'alpha_dash'=>"La référence de l'item #$num_item_list ne doit pas contenir d'espace ou de caractères spéciaux!",
                        "max_length"=> "La référence de l'item #$num_item_list ne peut pas dépasser 255 caractères!",
                        "not_in_list"=>"La référence de l'item #$num_item_list ne peut pas être identique à une autre référence de la liste!"
                    );
                }
            }
        }

        return $rules;
    }

    private function getListPossible($post, $type_item, $num_item_list_select, $total_item)
    {
        $in_list=[];
        for($num_item_list=1; $num_item_list<=$total_item; $num_item_list++)
        {
            $index_item = $type_item . $num_item_list;
            if($num_item_list!=$num_item_list_select && isset($post->$index_item) && !empty($post->$index_item))
            {
                $in_list[] = $post->$index_item;
            }

        }
        if(empty($in_list))
            return NULL;

        return implode(",", $in_list);    
    }


   //Methods for injected form
    public function injectedForm($id_injected_form=0)
    {
        $dataView=new DataViewConstructor();
        $fields=$this->DataViewModel->getFields();


        if($id_injected_form>0)
        {
            $injectedForm=$this->DataViewModel->getInjectedForm($id_injected_form);
            $valueHeaderText=$injectedForm->header_text;
        }
        else
        {
            $injectedForm=$this->DataViewModel->getInjectedFormDefault();
            $valueHeaderText=$this->DataViewModel->getLastHeaderText();
            //debug($valueHeaderText,TRUE);
        }

        $injectedFormConditions=$this->DataViewModel->getInjectedFormConditions();
        
        //connaitre les paramètres de l'injection form
        $has_injectedFormParams_spip=$this->DataViewModel->getInjectedFormParams_spip();

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
        $fields=$this->DataViewModel->getFields();
        $injectedForm=$this->DataViewModel->getInjectedForm($id_injected_form);
        
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
            $this->DataViewModel->saveInjectedForm($posts);


            
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