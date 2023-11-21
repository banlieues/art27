<?php

namespace App\Controllers;

use App\Models\ContactsModel;
use App\Models\ActivitiesModel;
use App\Models\RegistrationsModel;

use App\Modules\DataView\Libraries\DataViewConstructor;
use App\Modules\DataView\Models\DataViewConstructorModel;

use Components\Library\ComponentOrderBy;

class Contacts extends BaseController
{
    protected $contactModel;
    protected $inscriptionModel;
    protected $activiteModel;

    protected $context;

    protected $request;

    public function __construct()
    {
        if(session('loggedUserRoleId')!=1)
       {
            header("Location:".base_url("identification/logout"));
       }
        
       $this->autorisationManager = \Config\Services::autorisationModel();

       if(!$this->autorisationManager->is_gestion_entity_autorise("contacts"))
       {
            header("Location:".base_url("autorisation/no_autorisation"));
       }



        $this->contactModel = new ContactsModel();
        $this->inscriptionModel = new RegistrationsModel();
        $this->activiteModel = new ActivitiesModel();
        $this->context = "contacts";
        $request=$this->request;

        $this->componentOrderBy=new ComponentOrderBy();

        $dataView=new DataViewConstructor();
        //$dataView->verifExistFields($this->contactModel->getFields(),$this->contactModel->getTable());

       // $this->contactModel->test();
    }

	public function listContact()
    {
      
        if(!$this->autorisationManager->is_gestion_entity_autorise("contacts"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }


        $orderBy=$this->componentOrderBy->getOrderBy("nom,prenom,nom_court_institution",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("ASC",$this->request);

        $fieldsOrder=
        [
            
            "value1"=>[null,false,"contact_d"],
            
            "typepart"=>["Type",true],
            "nom,prenom,nom_court_institution"=>["Contact",true],
            "nom_court_institution"=>["Institution",true],
            "username"=>["Géré par l'utilisateur",true,"utilisateur_a"],
            "nb_inscription"=>["Nombre d'inscriptions",true,"registrations_r"],
            "codepostal"=>["Code postal",true],
            "localite"=>["Localité",true],
            "age"=>["Âge",true],
           
            "inscrire"=>[null,false,"registrations_d"]
           

        ];

       

        $contacts=$this->contactModel->getListContact($this->request,$orderBy,$orderDirection);
        $pager=$this->contactModel->pager;
        
        return view('contacts/list-contacts', [
            "contacts" => $contacts,
            "pager"=>$pager,
            "nbContacts"=> $pager->getTotal(),
            "fields"=> $this->contactModel->getFields(),
            "context"=> $this->context,
            "itemSearch"=>$this->request->getVar("itemSearch"),
			"titleView"=>"Liste des contacts",
            "getTh"=>$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request)

        ]);
    }

    public function viewContact($id_contact=NULL)
    {


        if(!$this->autorisationManager->is_gestion_entity_autorise("contacts"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }

        $dataView=new DataViewConstructor();

        //ETAPE I : On récupére les élements qui constituent la fiche
        $components=$dataView->getComponents("contacts");
        if(empty($components))
        {
            return "Aucune page définie pour les contacts";
        }

        //ETAPE 2 : on récupérer les données du contact en cours
        $contact=$this->contactModel->find($id_contact);
        
        $user=$this->inscriptionModel->get_user_by_id_contact($id_contact);

        if(!empty($contact)){
            if(empty($contact->nom)&&empty($contact->prenom))
            {
                $titleView=$contact->nom_court_institution;
            }
            else
            {
                $titleView=$contact->nom. " ".$contact->prenom;
            }
           
        }else{
            $titleView="Fiche non trouvée";
            $user=NULL;
        }

        //ETAPE 3 : On réupére la liste des inscriptions du contact
        $inscriptions=$this->inscriptionModel->getListInscription($this->request,0,$id_contact);
        $pagerInscription=$this->inscriptionModel->pager;

        return view('contacts/view-contact-fiche', [
            "contact" => $contact,
            "inscriptions"=>$inscriptions,
            "pagerInscription"=>$pagerInscription,
            "nbInscriptions"=>$pagerInscription->getTotal(),
            "fields"=> $this->contactModel->getFields(),
            "context"=> $this->context,
            "statutInscriptions"=>$this->inscriptionModel->getListStatutInscription(),
			"titleView"=>$titleView,
            "dataView"=>$dataView,
            "typeDataView"=>"read",
            "validation"=>NULL,
            "id_contact"=>$id_contact,
            "components"=>$components,
            "user"=>$user
        ]);
    }

    public function ModelisationContact($id_contact=NULL,$validation=NULL)
    {
        if(!$this->autorisationManager->is_autorise("modelisation_a"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }

        $dataView=new DataViewConstructor();


        $components=$dataView->getComponents("contacts");
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

        return view('contacts/view-contact-fiche', [
            "contact" => $contact,
            "fields"=> $this->contactModel->getFields(),
            "context"=> "modelisation",
			"titleView"=>"Modélisation de la fiche de contact",
            "dataView"=>$dataView,
            "typeDataView"=>"modelisation",
            "validation"=>$validation,
            "id_contact"=>$id_contact,
            "contact"=>$contact,
            "id_contact"=>$id_contact,
            "components"=>$components,

        ]);
    }

    public function viewContactInscription($id_contact=NULL)
    {

        if(!$this->autorisationManager->is_gestion_entity_autorise("contacts"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }

        if(!$this->autorisationManager->is_autorise("registrations_r"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }

        $orderBy=$this->componentOrderBy->getOrderBy("date_suivi",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);

        $fieldsOrder=
        [
            "delete"=>[null,false,"registrations_d"],
            "date_suivi"=>["Date de suivi",true],
            "id_inscription"=>[NULL,false],
            "residentiel"=>["<i class='fas fa-bed'></i>",true],
            "idact"=>["Ref",true],
            "titre"=>["Action",true],
            "date_debut"=>["Début",true],
            "nb_inscription"=>["Limite",true],
            "nom,prenom,nom_court_institution"=>["Contact",true],
            "username"=>["Géré par l'utilisateur",true,"utilisateur_a"],
            "age"=>["Âge",true],
            "alimentation"=>['<i class="'.icon("food").'"></i>',true],
            "statutsuivi"=>["Statut",true],
            "statut_payement"=>["Payement",true],
         
            "solde"=>["Solde",FALSE],
            "count_confirmation"=>[NULL,FALSE],
           
           
        ];

        $contact=$this->contactModel->find($id_contact);
        $user=$this->inscriptionModel->get_user_by_id_contact($id_contact);

        if(!empty($contact)):
            if(empty($contact->nom)&&empty($contact->prenom))
            {
                $titleView=$contact->nom_court_institution;
            }
            else
            {
                $titleView=$contact->nom. " ".$contact->prenom;
            }
        else:
            $titleView="Fiche non trouvée";
            
        endif;  
        $inscriptions=$this->inscriptionModel->getListInscription($this->request,0,$id_contact,$orderBy,$orderDirection);
        $pagerInscription=$this->inscriptionModel->pager;

        return view('contacts/view-contact-inscription', [
            "contact" => $contact,
            "inscriptions"=>$inscriptions,
            "pagerInscription"=>$pagerInscription,
            "nbInscriptions"=>$pagerInscription->getTotal(),
            "fields"=> $this->contactModel->getFields(),
            "context"=> $this->context,
            "statutInscriptions"=>$this->inscriptionModel->getListStatutInscription(),
            "statutPayements"=>$this->inscriptionModel->getListStatutPayement(),
            "statutSuivi"=>$this->request->getVar("statutSuivi"),
			"titleView"=>$titleView,
            "id_contact"=>$id_contact,
            "user"=>$user,
            "getTh"=>$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request),
            "statutSuivi"=>$this->request->getVar("statutSuivi"),
            "itemSearch"=>$this->request->getVar("itemSearch"),
        ]);
    }


    public function formContact($id_contact=NULL,$validation=NULL)
    {
     
        $dataView=new DataViewConstructor();

        $components=$dataView->getComponents("contacts");
        if(empty($components))
        {
            return "Aucune page définie pour les registrations";
        }

        if(!is_null($id_contact)){
            $typeDataView="update";
            $contact=$this->contactModel->find($id_contact);
            if(!empty($contact)):
                if(empty($contact->nom)&&empty($contact->prenom))
                {
                    $titleView=$contact->nom_court_institution;
                }
                else
                {
                    $titleView=$contact->nom. " ".$contact->prenom;
                }
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
        
        return view('contacts/view-contact-fiche', [
            "contact" => $contact,
            "fields"=> $this->contactModel->getFields(),
            "context"=> $this->context,
            "statutInscriptions"=>$this->inscriptionModel->getListStatutInscription(),
			"titleView"=>$titleView,
            "dataView"=>$dataView,
            "typeDataView"=>$typeDataView,
            "validation"=>$validation,
            "id_contact"=>$id_contact,
            "components"=>$components
        ]);
    }

    public function save()
    {
       //list of indexes of form
        $session = \Config\Services::session();
        $indexes=$this->request->getVar("indexesForm");

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
                $id_contact_save=$dataView->saveData(
                $indexes,
                $this->request->getVar(),
                $this->contactModel->getFields(),
                $this->contactModel->getTable(),
                $this->request->getVar("id_contact")
            );  
            
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
            
            return redirect()->to(base_url()."/contacts/viewContact/$id_contact_save")->with("success",$message);
        }
    }

    public function save_modelisation()
    {
        $posts=$this->request->getVar();
        
        $dataView=new DataViewConstructor();
        $dataGeneratorModel = new DataViewConstructorModel();
        $entityParams=$dataGeneratorModel->getOneEntities("contacts");


        $dataView->setComponents("contacts",$posts);
        
        $message= 'Le modèle de la fiche '.$entityParams->label.' a été enregistré';

        return redirect()->to(base_url()."/modelisation")->with("success",$message);
       

    }
}