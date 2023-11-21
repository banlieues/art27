<?php

namespace Partenaire_culturel\Controllers;

use Base\Controllers\BaseController;

use Layout\Libraries\LayoutLibrary;

use Partenaire_culturel\Models\Partenaire_culturelModel;

use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

use Components\Libraries\ComponentOrderBy;

use Ticket\Models\TicketModel;

use Barcode\Models\List_BarCodeModel;


class Partenaire_culturel extends BaseController
{

    public  $mois_ligne=[
        "1janvier"=>"Janvier",
        "2fevrier"=>"Février",
        "3mars"=>"Mars",
        "4avril"=>"Avril",
        "5mai"=>"Mai",
        "6juin"=>"Juin",
        "7juillet"=>"Juillet",
        "8aout"=>"Août",
        "9septembre"=>"Septembre",
        "10octobre"=>"Octobre",
        "11novembre"=>"Novembre",
        "12decembre"=>"Décembre"

    ];

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


        $this->partenaire_culturelModel = new Partenaire_culturelModel();
        $this->context = "partenaire_culturel";

        $request=$this->request;

        $this->dataView=new DataViewConstructor();
        $this->componentOrderBy=new ComponentOrderBy();
      

        parent::__construct(__NAMESPACE__);

        $layout_l = new LayoutLibrary();


        $this->datas->theme = $layout_l->getThemeByRef($this->context);
        $this->datas->context = $this->context;
        $this->viewpath = $this->module . "\Views\/";  

        $this->ticketModel = new TicketModel();
        $this->datas->mois_ligne=$this->mois_ligne;

        $this->list_BarCodeModel=new List_BarCodeModel();

    }



    public function listPartenaire_culturel()
    {
      
   

        $orderBy=$this->componentOrderBy->getOrderBy("nom_partenaire_culturel",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("ASC",$this->request);

        $fieldsOrder=
        [
            
            "delete"=>[null,false,"partenaire_culturels_culturel_d"],
            
            "nom_partenaire_culturel"=>["Nom",true],
            "commentaire"=>[null,false],
            "adresse_partenaire_culturel"=>["Adresse postal",true],
            "code_postal"=>["Localité",true],
            "telephone_partenaire_culturel"=>["Téléphone",true],
            "gsm_partenaire_culturel"=>["Gsm",true],
            "mail_partenaire_culturel"=>["Mail",true],
            "web_partenaire_culturel"=>["Web",true],
    

        ];

        
        
        $this->datas->partenaire_culturels_culturel=$this->partenaire_culturelModel->getListPartenaire_culturel($this->request,$orderBy,$orderDirection);
        $this->datas->pager=$this->partenaire_culturelModel->pager;
        $this->datas->nbPartenaire_culturels_culturel= $this->datas->pager->getTotal();
        $this->datas->fields= $this->partenaire_culturelModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des partenaires culturels";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);

        return view($this->viewpath.'list-partenaire_culturel', (array) $this->datas );
    }

    public function fiche($id_partenaire_culturel=NULL)
    {


      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("partenaire_culturels_culturel"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();

        //ETAPE I : On récupére les élements qui constituent la fiche
        $components=$dataView->getComponents("partenaire_culturel");

        if(empty($components))
        {
            return "Aucune page définie pour les partenaire_culturels_culturel";
        }

        //ETAPE 2 : on récupérer les données du partenaire_culturel en cours
        $partenaire_culturel=$this->partenaire_culturelModel->partenaire_culturel($id_partenaire_culturel);
        $this->datas->info_log=date_log($partenaire_culturel->created_at,$partenaire_culturel->updated_at,$partenaire_culturel->createur,$partenaire_culturel->updateur);

        
       // $user=$this->inscriptionModel->get_user_by_id_partenaire_culturel($id_partenaire_culturel);

        if(!empty($partenaire_culturel)){
          
         
                $titleView=  "<i>Fiche du partenaire_culturel n°$id_partenaire_culturel</i>"; 
           
                 
                
              
            
           
        }else{
            $titleView="Fiche non trouvée";
  
        }

        $this->datas->partenaire_culturel = $partenaire_culturel;
        $this->datas->fields = $this->partenaire_culturelModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="read";
        $this->datas->validation=NULL;
        $this->datas->id_partenaire_culturel=$id_partenaire_culturel;
        $this->datas->components=$components;
        $this->datas->viewpath=$this->viewpath;
        $this->datas->tab="fiche";
       
        //debug( $this->datas->demande);


       
       
        return view($this->viewpath.'view-partenaire_culturel-fiche', (array) $this->datas);
    }
    
    

   

    public function new()
    {


      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("partenaire_culturels_culturel"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();

        //ETAPE I : On récupére les élements qui constituent la fiche
        $partenaire_culturel_component=$dataView->getOneComponent("partenaire_culturel","partenaire_culturel");
       // $partenaire_culturel_caracteristique_component=$dataView->getOneComponent("partenaire_culturel","partenaire_culturel");
      
       
       // $user=$this->inscriptionModel->get_user_by_id_partenaire_culturel($id_partenaire_culturel);

      
        $titleView="Nouveau partenaire culturel";
  
        $components=

        $this->datas->partenaire_culturel = null;
        $this->datas->fields = $this->partenaire_culturelModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="new_form";
        $this->datas->validation=NULL;
        $this->datas->id_partenaire_culturel=0;
        $this->datas->viewpath=$this->viewpath;
       $this->datas->components=$dataView->getComponents("partenaire_culturel");


     
        $this->datas->partenaire_culturel_component=$partenaire_culturel_component;
       // $this->datas->partenaire_culturel_caracteristique_component=$partenaire_culturel_caracteristique_component;

       
       
        return view($this->viewpath.'view-partenaire_culturel-form-insert', (array) $this->datas);
    }

    public function ModelisationPartenaire_culturel($id_partenaire_culturel=NULL,$validation=NULL)
    {
    /* if(!$this->autorisationManager->is_autorise("modelisation_a"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();


        $components=$dataView->getComponents("partenaire_culturel");
        if(empty($components))
        {
            return "Aucune page définie pour les partenaire_culturels_culturel";
        }

        if(!is_null($id_partenaire_culturel)){
            $typeDataView="update";
            $partenaire_culturel=$this->partenaire_culturelModel->getPartenaire_culturel($id_partenaire_culturel);
            if(!empty($partenaire_culturel)):
                $titleView="Partenaire_culturel de $partenaire_culturel->nom $partenaire_culturel->prenom pour $partenaire_culturel->idact ".$partenaire_culturel->titre;
            else:
                $titleView="Fiche non trouvée";
            endif;    
        }

        if(is_null($id_partenaire_culturel)||$id_partenaire_culturel==0)
        {
            $partenaire_culturel=NULL;
            $typeDataView="create";
            $titleView="Créer une nouvelle fiche de partenaire_culturel";
        }

        if(!empty($partenaire_culturel))
        {
            $id_partenaire_culturel=$partenaire_culturel->id_partenaire_culturel;
            $partenaire_culturel=$this->partenaire_culturelModel->find($id_partenaire_culturel);

        }
        else
        {
            $partenaire_culturel=NULL;
            $id_partenaire_culturel=NULL;
        }

        $this->datas->partenaire_culturel = $partenaire_culturel;
        $this->datas->fields= $this->partenaire_culturelModel->getFields();
        $this->datas->context=  "modelisation";
        $this->datas->titleView= "Modélisation de la fiche de partenaire_culturel";
        $this->datas->dataView= $dataView;
        $this->datas->typeDataView= "modelisation";
        $this->datas->validation= $validation;
        $this->datas->id_partenaire_culturel= $id_partenaire_culturel;
        $this->datas->partenaire_culturel= $partenaire_culturel;
        $this->datas->id_partenaire_culturel= $id_partenaire_culturel;
        $this->datas->components= $components;
        $this->datas->viewpath=$this->viewpath;
   


        return view($this->viewpath.'view-partenaire_culturel-fiche', (array) $this->datas);
    }



    public function save($component="partenaire_culturel")
    {
       //list of indexes of form
        $session = \Config\Services::session();
        $indexes=$this->request->getVar("indexesForm");

      

        $dataView=new DataViewConstructor();
        $rules=$dataView->getRules($indexes,$this->partenaire_culturelModel->getFields());
    
        if (!$this->validate($rules)&&!empty($rules)) 
        {
            if($this->request->getVar('typeDataView')=="create")
            {
                echo $this->formPartenaire_culturel(NULL,$this->validator);
            } 
            else 
            {
                echo $this->formPartenaire_culturel($this->request->getVar('id_partenaire_culturel'),$this->validator);
            }          
        } 
        else 
        {

            switch ($component)
            {
                case "partenaire_culturel":
                    $id_partenaire_culturel_save= $this->partenaire_culturelModel->saveDataDelivrePartenaire_culturel($this->request->getVar("indexesForm"),$this->request->getVar(),$this->request->getVar("id_entity"));
                    break;

           
            }


            //treatment of data  
         /*       $id_partenaire_culturel_save=$dataView->saveData(
                $indexes,
                $this->request->getVar(),
                $this->partenaire_culturelModel->getFields(),
                $this->partenaire_culturelModel->getTable(),
                $this->request->getVar("id_partenaire_culturel")
            );  */
            
            if($this->request->getVar('typeDataView')=="create")
            {
                //$id_partenaire_culturel=$this->partenaire_culturelModel->insertData($data);
                $message= 'La fiche de partenaire_culturel a été créée';
            } 
            else 
            {
                //$id_partenaire_culturel=$this->partenaire_culturelModel->updateData($data,$this->request->getVar("id_partenaire_culturel"));
                $message= 'La fiche a été modifiée';
            }
            
            return redirect()->to(base_url()."/partenaire_culturel/fiche/$id_partenaire_culturel_save")->with("success",$message);
        }
    }


  

    public function save_modelisation()
    {
        $posts=$this->request->getVar();
        
        $dataView=new DataViewConstructor();
        $dataGeneratorModel = new DataViewConstructorModel();
        $entityParams=$dataGeneratorModel->getOneEntities("partenaire_culturel");


        $dataView->setComponents("partenaire_culturel",$posts);
        
        $message= 'Le modèle de la fiche '.$entityParams->label.' a été enregistré';

        return redirect()->to(base_url()."/modelisation")->with("success",$message);
       

    }

    public function get_remarque_by_partenaire_culturel($id_partenaire_culturel)
    {

        $this->datas->remarques=$this->partenaire_culturelModel->get_remarque_by_partenaire_culturel($id_partenaire_culturel);
        $this->datas->titleView="Remarques";
        return view($this->viewpath.'get_remarque_by_partenaire_culturel', (array) $this->datas);
    }

    
    public function get_commentaire_by_art27_partenaire_culturel($id_partenaire_culturel)
    {
        $this->datas->commentaires=$this->partenaire_culturelModel->get_commentaire_by_art27_partenaire_culturel($id_partenaire_culturel);
        return view($this->viewpath.'get_commentaire_by_art27_partenaire_culturel', (array) $this->datas);
    }

    public function _list_ticket($id_partenaire_culturel,$annee_select)
    {
       

        $orderBy=$this->componentOrderBy->getOrderBy("ticket.date_created",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);

        if($this->request->getVar("annee_select"))
        {
            $annee_select=$this->request->getVar("annee_select");
        }

        if(empty($annee_select))
        {
            $annee_select=date("Y");
        }

        $fieldsOrder=
        [
            
           // "delete"=>[null,false,"partenaire_socials_culturel_d"],
           "num_code_ticket"=>["NumCode",true],
           "-"=>["Ticket",false],
            "Partenaire culturel"=>["Partenaire culturel",false],
            "label_mois_ticket"=>["Mois concerné",true],
           
            "statut"=>["Statut ticket",true],
            "commentaire"=>["Commentaire",true],
            "date_scanning"=>["Scanné le",true],
            "scannor"=>["par",true],
            "Partenaire social"=>["Partenaire social",false],
            "num_code"=>["Référence",TRUE],
            "barcode"=>["Code Barre produit",false],
           
           

        ];


     

        
        $this->datas->tickets=$this->ticketModel->getListTicket($id_partenaire_culturel,$annee_select,$mois_select=0,$this->request,$orderBy,$orderDirection);
        
        $partenaire_culturel=$this->partenaire_culturelModel->partenaire_culturel($id_partenaire_culturel);
        $this->datas->info_log=date_log($partenaire_culturel->created_at,$partenaire_culturel->updated_at,$partenaire_culturel->createur,$partenaire_culturel->updateur);

        
       // $user=$this->inscriptionModel->get_user_by_id_partenaire_culturel($id_partenaire_culturel);

        if(!empty($partenaire_culturel)){
          
         
                $titleView=  "<i>Liste des tickets n°$id_partenaire_culturel</i>"; 
           
                 
                
              
            
           
        }else{
            $titleView="Fiche non trouvée";
  
        }
        $this->datas->partenaire_culturel=$partenaire_culturel;
        $this->datas->pager=$this->ticketModel->pager;
        $this->datas->nbTickets= $this->datas->pager->getTotal();
        $this->datas->fields= $this->ticketModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des tickets";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);
        $this->datas->type_ticket=$this->ticketModel->get_liste_type_ticket();
        $this->datas->module=$this->module;
        $this->datas->partenaire_culturels=$this->ticketModel->get_partenaire_culturels();
        $this->datas->mois=$this->mois_ligne;
        $this->datas->annee_select=$annee_select;
        $this->datas->statuts=$this->list_BarCodeModel->get_statut();
        $this->datas->statuts_ticket=$this->ticketModel->get_statut();

        $this->datas->typeDataView="read";
        $this->datas->tab="";

       


        return view('Partenaire_culturel\list_ticket', (array) $this->datas );
    }

}