<?php

namespace DocumentUpload\Controllers;

use Base\Controllers\BaseController;


use DocumentUpload\Models\DocumentModel;


use Layout\Libraries\LayoutLibrary;


use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

use Demande\Models\DemandeModel;

use Components\Libraries\ComponentOrderBy;


class DocumentUpload extends BaseController
{
  public function __construct()
    {
       
        if(session()->get("loggedUserRoleId")==2)
        {
            header("Location:" . base_url("user"));
        }
            if(session()->get("loggedUserRoleId")!=1)
        {
            header("Location:" . base_url("identification/logout"));
        }

        parent::__construct(__NAMESPACE__);

        $layout_l = new LayoutLibrary();

        $request=$this->request;

        $this->documentModel = new DocumentModel();
   

        $this->context = 'document';
        $this->datas->theme = $layout_l->getThemeByRef($this->context);
        $this->datas->context = $this->context;
        $this->viewpath = $this->module . "\Views";  
        $this->componentOrderBy=new ComponentOrderBy();

        $this->autorisationManager = \Config\Services::autorisationModel();
    }

   
    public function listDocument()
    {
       /* $segment="document";

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
   
        $orderBy=$this->componentOrderBy->getOrderBy("document_upload.date_created
        ",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);

        $fieldsOrder=
        [
            "delete"=>[null,false,"documents_d"],
            "document_upload.date_created"=>["Date d'upload",true],
            "document_upload.name"=>["Nom du fichier",true],
            "document_upload.commentaire"=>["Commentaire",true],
            "document_upload.id_type"=>["Type",true],
            "demande.nom"=>["Demande liée",true]
           
           

        ];



        
        $this->datas->documents=$this->documentModel->getListDocument($this->request,$orderBy,$orderDirection);
        $this->datas->pager=$this->documentModel->pager;
        $this->datas->nbDocuments= $this->datas->pager->getTotal();
        $this->datas->fields= $this->documentModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des documents";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);
        $this->datas->type_document=$this->documentModel->get_liste_type_document();
        $this->datas->module=$this->module;


        return view($this->module . '\list-document', (array) $this->datas );
    }


    public function liste_demande($id_document)
    {

        $this->demandeModel=new DemandeModel();
        $orderBy=$this->componentOrderBy->getOrderBy("date",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);

        $this->datas->document=$this->documentModel->getDocument($id_document);

        $fieldsOrder=
        [
            "check"=>[null,false],
            "date"=>["Date",true],
            "id_demande"=>["N°",true],
            "type"=>["Type",true],
            "statut"=>["Statut",true],
            "nom_createur"=>["Créateur",true],
            "nom_encharge"=>["En charge",true],
            "sujet"=>["Sujet",true],
            "contact_associee"=>["Demandeur",true],
            "bien_associe"=>["Bien",true],

        ];

        $demandes=$this->demandeModel->getListDemandesLinkDocument($this->request,$orderBy,$orderDirection,$id_document);
        $pager=$this->demandeModel->pager;
   
        $this->datas->title = lang('Dashboard.title');
        $this->datas->subtitle = lang('Dashboard.subtitle');
        $this->datas->titleView = lang('Dashboard.title');

        $this->datas->demandes = $demandes;
        $this->datas->pager=$pager;
        $this->datas->nbDemandes= $pager->getTotal();
        $this->datas->itemSearch =$this->request->getVar("itemSearch");
        $this->datas->titleView = "Associer le document n°$id_document à un demande";
        $this->datas->demandeModel = $this->demandeModel;

        $this->datas->statut_demandes=$this->demandeModel->statut_demande();
        $this->datas->id_statut_demande=$this->request->getVar("statut_demande");
        $this->datas->mes_demandes=$this->request->getVar("mes_demandes");
        $this->datas->homegrade=$this->request->getVar("homegrade");

        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);


        
        return view('Demande\listDemandeLinkDocument', (array) $this->datas);
    }

    public function associe_demande($id_document)
    {
        if($this->request->getVar("id_demande"))
        {
            $this->documentModel->associe_demande($id_document,$this->request->getVar("id_demande"));
            $this->datas->document=$this->documentModel->getDocument($id_document);
            $this->datas->id_demande=$this->request->getVar("id_demande");
            $this->datas->titleView = "Confirmation de l'ajout du document n°$id_document à la demande n°".$this->request->getVar("id_demande");
            return view($this->module .'\confirmation_associe_demande', (array) $this->datas);
        }
        else
        {
            return redirect()->to(base_url()."/document/liste_demande/$id_document")->with("danger","Vous devez sélectionner une demande");
        }
    }


    public function upload_file()
    {
       $write_path=PATH_DOCU_DEMANDE;

       //$write_path="./assets/test_upload/";


        $id_demande=$this->request->getVar("id_demande");
       
        $id_message=$this->request->getVar("id_message");
        
        if ($this->request->getFile("file")) {
             
            //if ($validateImage) {
                $imageFile = $this->request->getFile('file');
                
                $nameOriginal=$imageFile->getName();
                $newName = date("ymdHis").'_'.slugify_name_with_extension($imageFile->getName());
    
                $imageFile->move($write_path,$newName);
                $data = [
    
                    'name' => slugify_name_with_extension($nameOriginal),
                    'url_file' => $newName,
                    'contentByte_Type'  => $imageFile->getClientMimeType(),
                    'id_user' =>session()->get('loggedUserId'),
                    'date_created'=>date("Y-m-d H:i:s"),
                    'display'   => 1,
                    'id_message' => $id_message,
                    'id_demande' => $id_demande
                 
                ];
                $id_upload_file = $this->documentModel->upload_file($data);
                
            //}
             
        }
        else
        {
            echo "Erreur! Pas de fichier";
        }
       

        //Je récupère l'image
    }


    public function setTypeDocument($id_document) 
    {
        if(!$this->autorisationManager->is_autorise("document_u"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }
        $id_type=$this->request->getVar("id_type");


  
        $this->documentModel->setIdtype($id_document,$id_type);

        $document=$this->documentModel->getDocument($id_document);


       echo view($this->module.'\form_document_type', [
            "document" => $document,
            "type_document"=>$this->documentModel->get_liste_type_document()
            
        ]);

   


        //echo json_encode($data);

       
    }


    public function setCommentaire()
    {
        if($this->request->getVar("id_document"))
        {
            $this->documentModel->setCommentaire($this->request->getVar("id_document"),$this->request->getVar("commentaire"));

            echo view($this->module.'\form_document_commentaire', [
                "document" => $this->documentModel->getDocument($this->request->getVar("id_document"))
              
                
            ]);
        }
    }
}