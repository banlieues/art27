<?php

namespace Lieu\Controllers;

use App\Controllers\BaseController;

use Lieu\Models\LieuModel;
use App\Models\ContactsModel;
use App\Models\ActivitiesModel;
use App\Models\RegistrationsModel;

use App\Modules\DataView\Libraries\DataViewConstructor;
use App\Modules\DataView\Models\DataViewConstructorModel;

use Components\Library\ComponentOrderBy;

class Lieu extends BaseController
{
    protected $lieuModel;
    protected $contactModel;
    protected $inscriptionModel;
    protected $activiteModel;

    protected $context;

    protected $request;


    protected $path="Lieu\Views\/";

    public function __construct()
    {
        $this->autorisationManager = \Config\Services::autorisationModel();

        if(session()->get("loggedUserRoleId")!=1)
       {
            header("Location:".base_url("identification/logout"));
       }

       if(!$this->autorisationManager->is_gestion_entity_autorise("lieu"))
       {
            header("Location:".base_url("autorisation/no_autorisation"));
       }
        
        $this->contactModel = new ContactsModel();
        $this->inscriptionModel = new RegistrationsModel();
        $this->activiteModel = new ActivitiesModel();
        $this->lieuModel = new LieuModel();
        $this->context = "lieu";
        $request=$this->request;

        $this->componentOrderBy=new ComponentOrderBy();

        $dataView=new DataViewConstructor();
        //$dataView->verifExistFields($this->contactModel->getFields(),$this->contactModel->getTable());

       // $this->contactModel->test();
    }





	public function listLieu()
    {
      

        $orderBy=$this->componentOrderBy->getOrderBy("titre_lieu",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("ASC",$this->request);

        $fieldsOrder=
        [
            
            "value1"=>[null,false],
            "titre_lieu"=>["Titre",true],
            "is_actif"=>["Visible",true],
            "descriptif_lieu"=>["Descriptif",true],
            "adresse_lieu"=>["Adresse",true],
            
            
           

        ];

       

        $lieux=$this->lieuModel->getListLieu($this->request,$orderBy,$orderDirection);
        $pager=$this->lieuModel->pager;
        
        return view($this->path.'list-lieux', [
            "lieux" => $lieux,
            "pager"=>$pager,
            "nbLieux"=> $pager->getTotal(),
            "fields"=> $this->lieuModel->getFields(),
            "context"=> $this->context,
            "itemSearch"=>$this->request->getVar("itemSearch"),
			"titleView"=>"Liste des lieux",
            "getTh"=>$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request)

        ]);
    }

    public function viewLieu($id_lieu=NULL)
    {
        $dataView=new DataViewConstructor();

        //ETAPE I : On récupére les élements qui constituent la fiche
        $components=$dataView->getComponents("lieu");
        if(empty($components))
        {
            return "Aucune page définie pour les lieux";
        }

        //ETAPE 2 : on récupérer les données du lieu en cours
        $lieu=$this->lieuModel->find($id_lieu);
        
     

        if(!empty($lieu))
        {
            $titleView=$lieu->titre_lieu;
        
        }
        else
        {
            $titleView="Fiche non trouvée";
           
        }

        //ETAPE 3 : On réupére la liste des inscriptions du contact
        $inscriptions=$this->inscriptionModel->getListInscription($this->request,0,$id_lieu);
        $pagerInscription=$this->inscriptionModel->pager;

       // debug($this->lieuModel->getFields(),true);

        return view($this->path.'view-lieu-fiche', [
            "lieu" => $lieu,
            "inscriptions"=>$inscriptions,
            "pagerInscription"=>$pagerInscription,
            "nbInscriptions"=>$pagerInscription->getTotal(),
            "fields"=> $this->lieuModel->getFields(),
            "context"=> $this->context,
            "statutInscriptions"=>$this->inscriptionModel->getListStatutInscription(),
			"titleView"=>$titleView,
            "dataView"=>$dataView,
            "typeDataView"=>"read",
            "validation"=>NULL,
            "id_lieu"=>$id_lieu,
            "components"=>$components,
            //"user"=>$user
        ]);
    }

    public function ModelisationLieu($id_lieu=NULL,$validation=NULL)
    {
        $dataView=new DataViewConstructor();


        $components=$dataView->getComponents("lieu");
        if(empty($components))
        {
            return "Aucune page définie pour les lieu";
        }

        if(!is_null($id_lieu)){
            $typeDataView="update";
            $lieu=$this->lieuModel->getlieu($id_lieu);
            if(!empty($lieu)):
                $titleView="lieu de $lieu->nom $lieu->prenom pour $lieu->idact ".$lieu->titre;
            else:
                $titleView="Fiche non trouvée";
            endif;    
        }

        if(is_null($id_lieu)||$id_lieu==0)
        {
            $lieu=NULL;
            $typeDataView="create";
            $titleView="Créer une nouvelle fiche de lieu";
        }

        if(!empty($lieu))
        {
            $id_lieu=$lieu->id_lieu;
            $lieu=$this->lieuModel->find($id_lieu);

        }

        else
        {
            $lieu=NULL;
            $id_lieu=NULL;
        }

        return view($this->path.'view-lieu-fiche', [
            "lieu" => $lieu,
            "fields"=> $this->lieuModel->getFields(),
            "context"=> "modelisation",
			"titleView"=>"Modélisation de la fiche de lieu",
            "dataView"=>$dataView,
            "typeDataView"=>"modelisation",
            "validation"=>$validation,
            "id_lieu"=>$id_lieu,
            "lieu"=>$lieu,
            "id_lieu"=>$id_lieu,
            "components"=>$components,

        ]);
    }

    public function viewContactInscription($id_lieu=NULL)
    {
        $contact=$this->contactModel->find($id_lieu);
        $user=$this->inscriptionModel->get_user_by_id_lieu($id_lieu);

        if(!empty($contact)):
            $titleView=$contact->nom. " ".$contact->prenom;
        else:
            $titleView="Fiche non trouvée";
            
        endif;  
        $inscriptions=$this->inscriptionModel->getListInscription($this->request,0,$id_lieu);
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
            "id_lieu"=>$id_lieu,
            "user"=>$user
        ]);
    }


    public function formLieu($id_lieu=NULL,$validation=NULL)
    {
     
        $dataView=new DataViewConstructor();

        $components=$dataView->getComponents("lieu");
        if(empty($components))
        {
            return "Aucune page définie pour les registrations";
        }

        if(!is_null($id_lieu)){
            $typeDataView="update";
            $lieu=$this->lieuModel->find($id_lieu);
            if(!empty($lieu)):
                $titleView=$lieu->titre_lieu;
            else:
                $titleView="Fiche non trouvée";
            endif;    
        }
        if(is_null($id_lieu)||$id_lieu==0)
        {
            $lieu=NULL;
            $typeDataView="create";
            $titleView="Créer une nouvelle fiche de lieu";
        }
        
        return view($this->path.'view-lieu-fiche', [
            "lieu" => $lieu,
            "fields"=> $this->lieuModel->getFields(),
            "context"=> $this->context,
            "statutInscriptions"=>$this->inscriptionModel->getListStatutInscription(),
			"titleView"=>$titleView,
            "dataView"=>$dataView,
            "typeDataView"=>$typeDataView,
            "validation"=>$validation,
            "id_lieu"=>$id_lieu,
            "components"=>$components
        ]);
    }

    public function save()
    {
       //list of indexes of form
        $session = \Config\Services::session();
        $indexes=$this->request->getVar("indexesForm");

        $dataView=new DataViewConstructor();
        $rules=$dataView->getRules($indexes,$this->lieuModel->getFields());
    
        if (!$this->validate($rules)&&!empty($rules)) 
        {
            if($this->request->getVar('typeDataView')=="create")
            {
                echo $this->formLieu(NULL,$this->validator);
            } 
            else 
            {
                echo $this->formLieu($this->request->getVar('id_lieu'),$this->validator);
            }          
        } 
        else 
        {
            //treatment of data  
                $id_lieu_save=$dataView->saveData(
                $indexes,
                $this->request->getVar(),
                $this->lieuModel->getFields(),
                $this->lieuModel->getTable(),
                $this->request->getVar("id_lieu")
            );  
            
            if($this->request->getVar('typeDataView')=="create")
            {
                //$id_lieu=$this->contactModel->insertData($data);
                $message= 'La fiche du lieu a été créée';
            } 
            else 
            {
                //$id_lieu=$this->contactModel->updateData($data,$this->request->getVar("id_lieu"));
                $message= 'La fiche a été modifiée';
            }
            
            return redirect()->to(base_url()."/lieu/viewlieu/$id_lieu_save")->with("success",$message);
        }
    }

    public function save_modelisation()
    {
        $posts=$this->request->getVar();
        
        $dataView=new DataViewConstructor();
        $dataGeneratorModel = new DataViewConstructorModel();
        $entityParams=$dataGeneratorModel->getOneEntities("lieu");


        $dataView->setComponents("lieu",$posts);
        
        $message= 'Le modèle de la fiche '.$entityParams->label.' a été enregistré';

        return redirect()->to(base_url()."/modelisation")->with("success",$message);
       

    }
}