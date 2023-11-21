<?php

namespace Contact_profil\Controllers;

use Base\Controllers\BaseController;

use Layout\Libraries\LayoutLibrary;

use Contact\Models\ContactModel;

use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

use Components\Libraries\ComponentOrderBy;

class Contact_profil extends BaseController
{
    public function __construct()
    {
        if(session('loggedUserRoleId')==2)
        {
            header("Location:" . base_url("user"));
        }
            if(session('loggedUserRoleId')!=1)
        {
            header("Location:" . base_url("identification/logout"));
        }


        $this->contactModel = new ContactModel();
        $this->context = "contact";

        $request=$this->request;

        $this->dataView=new DataViewConstructor();
        $this->componentOrderBy=new ComponentOrderBy();
      

        parent::__construct(__NAMESPACE__);

        $layout_l = new LayoutLibrary();


        $this->datas->theme = $layout_l->getThemeByRef($this->context);
        $this->datas->context = $this->context;
    }

    public function ModelisationContact_profil($id_contact=NULL,$validation=NULL)
    {
       /* if(!$this->autorisationManager->is_autorise("modelisation_a"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();


        $components=$dataView->getComponents("contact");
        if(empty($components))
        {
            return "Aucune page définie pour les contacts";
        }

        if(!is_null($id_contact)){
            $typeDataView="update";
            $contact=$this->contactModel->getContact($id_contact);
            if(!empty($contact)):
                $titleView="Contact de $contact->nom $contact->prenom pour $contact->idact ".$contact->titre;
            else:
                $titleView="Fiche non trouvée";
            endif;    
        }

        if(is_null($id_contact)||$id_contact==0)
        {
            $contact=NULL;
            $typeDataView="create";
            $titleView="Créer une nouvelle fiche de contact";
        }

        if(!empty($contact))
        {
            $id_contact=$contact->id_contact;
            $contact=$this->contactModel->find($id_contact);

        }

        else
        {
            $contact=NULL;
            $id_contact=NULL;
        }

        $this->datas->contact = $contact;
        $this->datas->fields= $this->contactModel->getFields();
        $this->datas->context=  "modelisation";
        $this->datas->titleView= "Modélisation de la fiche de contact";
        $this->datas->dataView= $dataView;
        $this->datas->typeDataView= "modelisation";
        $this->datas->validation= $validation;
        $this->datas->id_contact= $id_contact;
        $this->datas->contact= $contact;
        $this->datas->id_contact= $id_contact;
        $this->datas->components= $components;
        $this->datas->contact_profil=$this->contactModel->contact_profil($id_contact);

        return view($this->module . '\view-contact-fiche', (array) $this->datas);
    }

   

    public function save_modelisation()
    {
        $posts=$this->request->getVar();
        
        $dataView=new DataViewConstructor();
        $dataGeneratorModel = new DataViewConstructorModel();
        $entityParams=$dataGeneratorModel->getOneEntities("contact");


        $dataView->setComponents("contact",$posts);
        
        $message= 'Le modèle de la fiche '.$entityParams->label.' a été enregistré';

        return redirect()->to(base_url()."/modelisation")->with("success",$message);
       

    }

    

}