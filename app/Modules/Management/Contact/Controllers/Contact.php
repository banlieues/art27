<?php

namespace Contact\Controllers;

use Base\Controllers\BaseController;

use Layout\Libraries\LayoutLibrary;

use Contact\Models\ContactModel;

use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

use Demande\Models\DemandeModel;

use Components\Libraries\ComponentOrderBy;

class Contact extends BaseController
{
    public function __construct()
    {
        if(session('loggedUserRoleId')==2)
        {
            header("Location:" . base_url("user"));
        }

        if(session()->get("loggedUserRoleId")!=1)
        {
            header("Location:" . base_url("identification/logout"));
        }

        parent::__construct(__NAMESPACE__);

        $this->contactModel = new ContactModel();
        $this->context = "contact";

        $request=$this->request;

        $this->dataView=new DataViewConstructor();
        $this->componentOrderBy=new ComponentOrderBy();

        $layout_l = new LayoutLibrary();


        $this->datas->theme = $layout_l->getThemeByRef($this->context);
        $this->datas->context = $this->context;  

       // $path="Contact\Views\/";

       $this->demandeModel = new DemandeModel();
    }

    

    public function listContact()
    {
       /* $segment="contact";
        $uri = current_url(true);
        $url_en_cours=(string) $uri;
        $url_init=base_url()."/$segment";
        $url_previous=session()->get("url_previous_".$segment);
        if($url_init==$url_en_cours&&!empty($url_previous))
        {
            //return redirect()->to($url_previous);
        }
        else
        {
            $url_previous=session()->set("url_previous_".$segment,$url_en_cours);
        }*/
   
        $orderBy=$this->componentOrderBy->getOrderBy("nom_contact,prenom_contact",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("ASC",$this->request);

        $fieldsOrder=
        [
            
         
            
            "delete"=>[null,false,"contact_d"],
            "type_personne"=>["Type",true],
            "nom_contact,prenom_contact"=>["Contact",true],
            "langue"=>["Langue",true],
            "civilite"=>["Civilité",true],
            "telephone"=>["Téléphone(s)",true],
            "email"=>["Email(s)",true],
            "nb_demande"=>["Nb Demande",true],
            "bien_associe"=>["Bien(s) Associé(s)",true],
            
           
           

        ];



        
        $this->datas->contacts=$this->contactModel->getListContact($this->request,$orderBy,$orderDirection);
        $this->datas->pager=$this->contactModel->pager;
        $this->datas->nbContacts= $this->datas->pager->getTotal();
        $this->datas->fields= $this->contactModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des contacts";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);

        return view($this->module . '\list-contact', (array) $this->datas );
    }

    public function listDemandeur()
    {
      
   
        $orderBy=$this->componentOrderBy->getOrderBy("nom_contact,prenom_contact",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("ASC",$this->request);

        $fieldsOrder=
        [
            "delete"=>[null,false,"contact_d"],
            "nom_contact,prenom_contact"=>["Contact",true],
            "categorie_contact_list"=>["Catégorie",true],
           
           

        ];



        
        $this->datas->contacts=$this->contactModel->getListDemandeur($this->request,$orderBy,$orderDirection);
        $this->datas->pager=$this->contactModel->pager;
        $this->datas->nbContacts= $this->datas->pager->getTotal();
        $this->datas->fields= $this->contactModel->getFields();
        $this->datas->context= "demandeur";
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des contacts";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);

        return view($this->module . '\list-contact-demandeur', (array) $this->datas );
    }

    public function fiche($id_contact=NULL)
    {


      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("contacts"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();

        //ETAPE I : On récupére les élements qui constituent la fiche
        $components=$dataView->getComponents("contact");

        if(empty($components))
        {
            return "Aucune page définie pour les contacts";
        }

        //ETAPE 2 : on récupérer les données du contact en cours
        $contact=$this->contactModel->contact($id_contact);
       //debug($contact,true);
       // $user=$this->inscriptionModel->get_user_by_id_contact($id_contact);

        if(!empty($contact)){
          
           
                $titleView=$contact->nom_contact. " ".$contact->prenom_contact;
            
           
        }else{
                $titleView="Fiche non trouvée";
  
        }

        $this->datas->contact = $contact;
        $this->datas->fields = $this->contactModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="read";
        $this->datas->validation=NULL;
        $this->datas->id_contact=$id_contact;
        $this->datas->components=$components;
        $this->datas->contact_profil=$this->contactModel->contact_profil($id_contact);
        $this->datas->demande=$this->contactModel->get_demande_by_contact($id_contact);
        $this->datas->module=$this->module;

        return view($this->module . '\view-contact-fiche', (array) $this->datas);
    }

    public function associe_demande($id_contact=NULL,$id_contact_profil)
    {
        $demandes=$this->demandeModel->getListDemandes($this->request);
        $pager=$this->demandeModel->pager;

      

        $this->datas->demandes = $demandes;
        $this->datas->pager=$pager;
        $this->datas->nbDemandes= $pager->getTotal();
        $this->datas->itemSearch =$this->request->getVar("itemSearch");
        $this->datas->titleView = "Liste des demandes";
        $this->datas->demandeModel = $this->demandeModel;

        $this->datas->statut_demandes=$this->demandeModel->statut_demande();
        $this->datas->id_statut_demande=$this->request->getVar("statut_demande");
        $this->datas->mes_demandes=$this->request->getVar("mes_demandes");
        $this->datas->homegrade=$this->request->getVar("homegrade");

  

      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("biens"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

      

      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("contacts"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();

        //ETAPE I : On récupére les élements qui constituent la fiche
        
        //ETAPE 2 : on récupérer les données du contact en cours
       // $contact=$this->contactModel->find($id_contact);
        $contact_profil=$this->contactModel->contact_profil_by_id_contact_profil($id_contact_profil);
       // debug($contact,true);
       // $user=$this->inscriptionModel->get_user_by_id_contact($id_contact);

       $titleView=NULL;
          
                if(!empty($contact))
                {
                    $titleView.=$contact->nom_contact. " ".$contact->prenom_contact;

                }  
                if(!empty($contact_profil))
                {
                    if(!empty($contact_profil->email))
                        $titleView.= '-'.$contact_profil->email. '';

                    if(!empty($contact_profil->adresse))
                        $titleView.= '-'.$contact_profil->adresse. '';

                    if(!empty($contact_profil->localite))
                        $titleView.= '-'.$contact_profil->localite. '';

                }  
           
      

        $this->datas->contact = $this->contactModel->contact($id_contact);
        $this->datas->fields = $this->contactModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="associe";
        $this->datas->validation=NULL;
        $this->datas->id_contact=$id_contact;
        $this->datas->id_contact_profil=$id_contact_profil;
        $this->datas->contact_profil=$this->contactModel->contact_profil($id_contact);
        $this->datas->demande=$this->contactModel->get_demande_by_contact($id_contact);
        $this->datas->module=$this->module;

        /*$this->datas->gasap_referent=$this->contactModel->gasap_contact($id_contact,"gasap_contact_profil");
        $this->datas->gasap_referent_2=$this->contactModel->gasap_contact($id_contact,"gasap_contact_profil");
        $this->datas->gasap_tresorier=$this->contactModel->gasap_contact($id_contact,"gasap_contact_profil");
        $this->datas->gasap_inscription=$this->contactModel->gasap_contact($id_contact,"gasap_contact_profil");*/

       // debug($this->datas->gasap_membre);
      //  $this->datas->demande=$this->contactModel->get_demande_by_contact($id_contact);

      //  $this->datas->demandes=$this->contactModel->get_demande_by_contact($id_contact);

 
        return view($this->module . '\view-contact-associe-demande', (array) $this->datas);
    }

    public function new()
    {


      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("contacts"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();


        $contact_component=$dataView->getOneComponent("contact","contact");
        $contact_profil_component=$dataView->getOneComponent("contact","contact_profil");


        $titleView="Nouveau contact";

        $this->datas->contact = NULL;
        $this->datas->contact_component=$contact_component;
        $this->datas->contact_profil_component=$contact_profil_component;
        $this->datas->fields = $this->contactModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="new_form";
        $this->datas->validation=NULL;
        $this->datas->contact_profil=NULL;
        $this->datas->module=$this->module;
        $this->datas->id_contact=0;
        
     

 
       return view($this->module . '\view-contact-form-insert', (array) $this->datas);
    }


    

    public function ModelisationContact($id_contact=NULL,$validation=NULL)
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
        $this->datas->demande=$this->contactModel->get_demande_by_contact($id_contact);

       /* $this->datas->gasap_membre=$this->contactModel->gasap_contact($id_contact,"gasap_membre");
        $this->datas->gasap_referent=$this->contactModel->gasap_contact($id_contact,"gasap_referent");
        $this->datas->gasap_referent_2=$this->contactModel->gasap_contact($id_contact,"gasap_referent_2");
        $this->datas->gasap_tresorier=$this->contactModel->gasap_contact($id_contact,"gasap_tresorier");
        $this->datas->gasap_inscription=$this->contactModel->gasap_contact($id_contact,"gasap_inscription");*/

      //  $this->datas->demande=$this->contactModel->get_demande_by_contact($id_contact);


        


        return view($this->module . '\view-contact-fiche', (array) $this->datas);
    }

  


    public function formContact($id_contact=NULL,$validation=NULL)
    {
     
        $dataView=new DataViewConstructor();

        $components=$dataView->getComponents("contact");
        if(empty($components))
        {
            return "Aucune page définie pour les registrations";
        }

        if(!is_null($id_contact)){
            $typeDataView="update";
            $contact=$this->contactModel->find($id_contact);
            if(!empty($contact)):
                
                    $titleView=$contact->nom_contact. " ".$contact->prenom_contact;
                
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
     
     
        $this->datas->contact = $contact;
        $this->datas->fields = $this->contactModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="read";
        $this->datas->validation=NULL;
        $this->datas->id_contact=$id_contact;
        $this->datas->components=$components;
        $this->datas->viewpath=$this->viewpath;
        $this->datas->contact_profil=$this->contactModel->contact_profil($id_contact);
        $this->datas->demande=$this->contactModel->get_demande_by_contact($id_contact);

        
    }

    public function save($component="contact")
    {


       // die();
       //list of indexes of form
        $session = \Config\Services::session();
        $indexes=$this->request->getVar("indexesForm");
     // debug($this->request->getVar(),true);
        $dataView=new DataViewConstructor();
        $rules=$dataView->getRules($indexes,$this->contactModel->getFields());
    
        if (!$this->validate($rules)&&!empty($rules)) 
        {
            if($this->request->getVar('typeDataView')=="create")
            {
                echo $this->formContact(NULL,$this->validator);
            } 
            else 
            {
                echo $this->formContact($this->request->getVar('id_contact'),$this->validator);
            }          
        } 
        else 
        {
            //treatment of data  
            //debugd($this->request->getVar());
            switch ($component)
            {
                case "contact":
                    $id_contact_save= $this->contactModel->saveDataDelivreContact($this->request->getVar("indexesForm"),$this->request->getVar(),$this->request->getVar("id_entity"));
                    break;

                case "contact_profil":
                    //debug($this->request->getVar());
                        $id_contact_save= $this->contactModel->saveDataDelivreContactProfil($this->request->getVar("indexesForm"),$this->request->getVar(),$this->request->getVar("id_entity"));
                        break;


                case "demande":
                    //debug($this->request->getVar());
                        $id_contact_save= $this->contactModel->saveDataDelivreDemande($this->request->getVar("indexesForm"),$this->request->getVar(),$this->request->getVar("id_entity"));
                        break;
            }
          
            

           

             /*   $id_contact_save=$dataView->saveData(
                $indexes,
                $this->request->getVar(),
                $this->contactModel->getFields(),
                $this->contactModel->getTable(),
                $this->request->getVar("id_entity"),
                "id_contact"
            );  */
            
            if($this->request->getVar('typeDataView')=="create")
            {
                //$id_contact=$this->contactModel->insertData($data);
                $message= 'La fiche de contact a été créée';
            } 
            else 
            {
                //$id_contact=$this->contactModel->updateData($data,$this->request->getVar("id_contact"));
                $message= 'La fiche a été modifiée';
            }
            
           return redirect()->to(base_url()."/contact/fiche/$id_contact_save")->with("success",$message);
        }
    }

    public function save_insert()
    {
        $session = \Config\Services::session();

        $dataView=new DataViewConstructor();
        $dataForms=$this->request->getVar();

        if(!empty($dataForms))
        {
            foreach($dataForms as $dataForm)
            {
                $indexesForm=[];
                $datasForSave=[];

                foreach($dataForm as $df)
                {
                    if($df["name"]=="indexesForm[]")
                    {
                        $indexesForm[]=$df["value"];

                    }
                    else
                    {
                        $datasForSave[$df["name"]]=$df["value"];
                    }
                }

                $datasForSave["indexesForm"]=$indexesForm;

                if(isset($id_contact_new))
                {
                    $datasForSave["id_contact"]=$id_contact_new;
                }
                else
                {
                    $id_contact_new=$this->request->getVar("id_contact");
                }

               $id_contact_new=$dataView->saveData(
                    $indexesForm,
                    $datasForSave,
                    $this->contactModel->getFields(),
                    $this->contactModel->getTable(),
                    $id_contact_new,
                    "id_contact"
                );  

               // debug($datasForSave);
               //debug($id_contact_new);
            }

            

            //debug($indexesForm);
            
        }
       echo base_url()."/contact/return_fiche/".$id_contact_new;
    }



    public function save_new()
    {


        // die();
        //list of indexes of form
         $session = \Config\Services::session();
         $indexes=$this->request->getVar("indexesForm");
      // debug($this->request->getVar(),true);
         $dataView=new DataViewConstructor();
         $rules=$dataView->getRules($indexes,$this->contactModel->getFields());
     
         if (!$this->validate($rules)&&!empty($rules)) 
         {
                  
         } 
         else 
         {

            $id_contact_save= $this->contactModel->saveDataDelivreNewContact($this->request->getVar("indexesForm"),$this->request->getVar(),0);

             
            
                 //$id_contact=$this->contactModel->insertData($data);
                 $message= 'La fiche de contact a été créée';
            
          
             
            return redirect()->to(base_url()."/contact/fiche/$id_contact_save")->with("success",$message);
         }
     }


    public function save_associe_demande()
    {
        $post=$this->request->getVar();

    
        if(isset($post["id_demande"])&&$post["id_demande"]>0)
        {
            $this->contactModel->associe_demande($post);

            return redirect()->to(base_url()."/contact/fiche/".$post["id_contact"])->with("success","Le contact est associé à la demande");

        }
        else
        {
            return redirect()->back()->with("danger","Vous n'avez pas choisi de demande à associer");

        }
    }


    public function return_fiche($id_contact_save)
    {
        $message= 'La fiche Gasap a été créée';
        return redirect()->to(base_url()."/contact/fiche/$id_contact_save")->with("success",$message);

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

    public function form_search_link()
    {
       return view($this->module .'\form_search_link');
    }


    public function result_search_link()
    {
        $itemSearch=$this->request->getVar("itemSearch");
        $itemSearch=trim($itemSearch);

        if(empty($itemSearch))
            return view($this->module . '\form_search_link_no_result');

        $contacts=$this->contactModel->get_result_search_link($itemSearch);

       // debug($contacts,true);


        if(empty($contacts))
            return view($this->module . '\form_search_link_no_result');


            return view($this->module . '\form_search_link_result',["contacts"=>$contacts]);

    }

    public function delete_relation_contact_profil()
    {
       //debug($this->request->getVar(),true);
        if($this->request->getVar("idDelete")&&$this->request->getVar("idDelete")>0)
        {
           $is_delete=$this->contactModel->delete_relation_contact_profil($this->request->getVar("idDelete"));

           if($is_delete)
            {
                return redirect()->to($this->request->getVar("uriReturn"))->with("success","Le relation a été effacée");

            }
            else
            {
                return redirect()->to($this->request->getVar("uriReturn"))->with("danger","Impossible d'effacer cette relation");
            }
        }
        else
        {
            return redirect()->to($this->request->getVar("uriReturn"))->with("danger","Impossible d'effacer cette relation");
        }

    }

    public function contact_profil_add($id_contact)
    {
        $post=$this->request->getVar();
       // debug($post,true);

       
     
        $data["localite"]=$post["contact_profil_localite"];
        $data["adresse"]=$post["contact_profil_adresse"];
        $data["pays"]=$post["contact_profil_pays"];
        $data["email"]=$post["contact_profil_email"];
        $data["email2"]=$post["contact_profil_email2"];
        $data["telephone"]=$post["contat_profil_telephone"];
        $data["telephone2"]=$post["contact_profil_telephone2"];
        $data["id_contact"]=$id_contact;

        $this->contactModel-> contact_profil_add($data);

        return redirect()->to(base_url()."/contact/fiche/".$id_contact)->with("success","Le profil a bien ajouté au contact");
    
    }


    public function contact_general($id_contact,$mode="read")
    {

    }
    
    public function contact_profil($id_contact,$mode="read")
    {

    }

    public function demande($id_contact,$mode="read")
    {
        
    }

}