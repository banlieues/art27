<?php

namespace Demande\Controllers;

use Base\Controllers\BaseController;

use Demande\Models\DemandeModel;
use Layout\Libraries\LayoutLibrary;

use DocumentUpload\Models\DocumentModel;

use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

use Outlook\Libraries\Myoutlook_lib;
use Outlook\Models\OutlookModel;

use MailTemplate\Libraries\TemplateLibrary;

use Historique\Controllers\Historique;

use Uri_ban\Controllers\Uri_ban;

use Components\Libraries\ComponentOrderBy;

use Components\Models\BanCrudModel;

use Bien\Controllers\Bien;
use CodeIgniter\Database\RawSql;
use DemandeWeb\Libraries\DemandeLibrary;

use Rdv\Controllers\Rdv;
use Tache\Controllers\Tache;

use Messagerie\Controllers\Messagerie;




class Demande extends BaseController
{
    public function __construct()
    {
       
  

        parent::__construct(__NAMESPACE__);

        $layout_l = new LayoutLibrary();

        $request=$this->request;

        $this->DWLibrary = new DemandeLibrary();

        $this->demandeModel = new DemandeModel();

        $this->documentModel = new DocumentModel();

        $this->outlookModel = new OutlookModel();

       // $this->bienModel = new BienModel();

        //$this->contactModel = new ContactModel();

        $this->context = 'demande';
        $this->datas->theme = $layout_l->getThemeByRef($this->context);
        $this->datas->context = $this->context;
        $this->viewpath = $this->module . "\Views";  

        $this->myoutlook_lib=new Myoutlook_lib();

        $this->historique=new Historique();

        $this->componentOrderBy=new ComponentOrderBy();
        $this->ban_crud_model=new BanCrudModel();

        $this->bien=new Bien();
       // debugd();
    }

    public function index()
    {
      

        $this->datas->title = lang('Dashboard.title');
        $this->datas->subtitle = lang('Dashboard.subtitle');
        $this->datas->titleView = lang('Dashboard.title');

        return view($this->viewpath . '\index', (array) $this->datas);
    }

    public function list_demande()
    {

        if(empty($this->request->getVar()))
        {
            header("Location:".base_url()."demande?mes_demandes=1");
            exit();
        }

        $orderBy=$this->componentOrderBy->getOrderBy("date",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);



        $fieldsOrder=
        [
            "date"=>["Date",true],
            "id_demande"=>["N°",true],
            "type"=>["Type",true],
            "statut"=>["Statut",true],
            "nom_createur"=>["Créateur",true],
            "nom_encharge"=>["En charge",true],
            "pole"=>["Pôle",true],
            "sujet"=>["Sujet",true],
            "contact_associee"=>["Demandeur",true],
            "bien_associe"=>["Bien",true],

        ];

        $demandes=$this->demandeModel->getListDemandes($this->request,$orderBy,$orderDirection);
        $pager=$this->demandeModel->pager;
   
        $this->datas->title = lang('Dashboard.title');
        $this->datas->subtitle = lang('Dashboard.subtitle');
        $this->datas->titleView = lang('Dashboard.title');

        $this->datas->demandes = $demandes;
        $this->datas->pager=$pager;
        $this->datas->nbDemandes= $pager->getTotal();
        $this->datas->itemSearch =$this->request->getVar("itemSearch");
        $this->datas->titleView = "Liste des demandes";
        $this->datas->demandeModel = $this->demandeModel;

        $this->datas->statut_demandes=$this->demandeModel->statut_demande();
        $this->datas->id_statut_demande=$this->request->getVar("statut_demande");

        $this->datas->poles=$this->demandeModel->poles();
        $this->datas->id_pole=$this->request->getVar("id_pole");

        $this->datas->mes_demandes=$this->request->getVar("mes_demandes");
        
        $this->datas->homegrade=$this->request->getVar("homegrade");

        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);

       
        
        return view($this->viewpath .'\listDemande', (array) $this->datas);
    }


    public function fiche($id_demande=NULL,$id_email_primary=0,$id_message_attach=0,$id_rdv=0,$id_tache=0)
    {
       // debugd(session()->get());

      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("demandes"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();
      

        //ETAPE I : On récupére les élements qui constituent la fiche
        $components=$dataView->getComponents("demande");

        if(empty($components))
        {
            return "Aucune page définie pour les demandes";
        }

       /* $messagerie=new Messagerie();
        $notes_non_lues=$messagerie->is_non_lu_entity("demande",$id_demande,true);*/
   
        //ETAPE 2 : on récupérer les données du demande en cours
        $demande=$this->demandeModel->find($id_demande);
  
       // $user=$this->inscriptionModel->get_user_by_id_demande($id_demande);

        if(!empty($demande)){
          
           
                $titleView="Demande n°$id_demande";
            
           
        }else{
            $titleView="Fiche non trouvée";
  
        }

        $oneRdv=NULL;
        $oneTache=NULL;

        if($id_rdv>0)
        {
            $rdvc=new Rdv();
            
            $oneRdv=$rdvc->form_rdv($id_demande,$id_rdv,true);
        }

        if($id_tache>0)
        {
            $tachec=new Tache();

            $oneTache=$tachec->form_tache($id_demande,$id_tache,true);

        }
        
        //$this->datas->demande = $demande;
        $this->datas->email_demandeur=$this->demandeModel->get_email_demandeur($id_demande);
        $this->datas->fields = $this->demandeModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="read";
        $this->datas->validation=NULL;
        $this->datas->id_demande=$id_demande;
        $this->datas->components=$components;
       $this->datas->demande=$this->demandeModel->getDemande($id_demande);
       //debugd($this->datas->demande);
        $this->datas->contact=$this->demandeModel->getDemandeurs($id_demande);
        $this->datas->bien=$this->demandeModel->getBiens($id_demande);
     
        $this->datas->demandeModel=$this->demandeModel;
        $this->datas->id_email_primary=$id_email_primary;
        $this->datas->id_message_attach=$id_message_attach;

        $this->datas->statut_demande=$this->demandeModel->statut_demande();

        $this->datas->path=$this->viewpath;
        $this->datas->id_personne_bien=$this->demandeModel->get_id_personne_bien($id_demande);


        $this->datas->documents=$this->documentModel->getListDocuments($id_demande);
      
        $this->datas->oneRdv=$oneRdv;
        $this->datas->oneTache=$oneTache;

     

        /*$this->datas->notes_non_lues=$notes_non_lues;*/


       
        return view($this->module . '\view-demande-fiche', (array) $this->datas);
    }


    public function liste_fil($id_demande)
    {
        $this->datas->emails=$this->demandeModel->getFil($id_demande);;
        $this->datas->id_demande=$id_demande;

        return view($this->module . '\liste_fil', (array) $this->datas);
    }

    public function liste_document($id_demande)
    {
        $this->datas->documents=$this->documentModel->getListDocuments($id_demande);
        $this->datas->id_demande=$id_demande;

        return view($this->module . '\liste_document', (array) $this->datas);

    }

    public function liste_document_join($id_demande,$id_message=0,$context="new")
    {
      
        $this->datas->documents=$this->documentModel->getListDocuments($id_demande);
        $this->datas->id_demande=$id_demande;
        $this->datas->id_message=$id_message;
        $documents_join=[];

        if($context=="transfert"||$context=="brouillon")
        {
            $fichiers=$this->outlookModel->get_fichier_joins($id_message);
            if(!empty($fichiers))
            {
            
                foreach($fichiers as $fichier)
                {
                    array_push($documents_join,$fichier->id_document);
                }
            }
            
        }

        $this->datas->id_document_deja_join=$documents_join;
    
        return view($this->module . '\liste_document_join', (array) $this->datas);

    }

    public function liste_rdv($id_demande)
    {
        $this->datas->rdvs=$this->demandeModel->getRdvs($id_demande);
        $this->datas->id_demande=$id_demande;

        return view($this->module . '\liste_rdv', (array) $this->datas);

    }

    public function liste_tache($id_demande)
    {
        $this->datas->taches=$this->demandeModel->getTaches($id_demande);
        $this->datas->id_demande=$id_demande;

        return view($this->module . '\liste_tache', (array) $this->datas);

    }


    public function fiche_old($id_demande=NULL)
    {
        $demande=$this->demandeModel->getDemande($id_demande);
        $demandeurs=$this->demandeModel->getDemandeurs($id_demande);
        $biens=$this->demandeModel->getBiens($id_demande);
        $documents=$this->demandeModel->getDocuments($id_demande);
        $rdvs=$this->demandeModel->getRdvs($id_demande);

        $this->datas->demande = $demande;
        $this->datas->demandeurs=$demandeurs;
        $this->datas->biens=$biens;
     
		$this->datas->titleView="Demande n°$id_demande";
        $this->datas->demandeModel=$this->demandeModel;
        $this->datas->id_demande=$id_demande;
        $this->datas->documents=$documents;
        $this->datas->rdvs=$rdvs;
        $this->datas->path=$this->viewpath;

      
      
        return view($this->viewpath .'\view-demande-fiche',  (array) $this->datas);
    
    }


    public function new_web()
    {
        $this->datas->nom_demande=NULL;
        $this->datas->descriptif_demande=NULL;
        $interface=NULL;
        $id_message=0;
        $id_contact=0;
        $id_contact_profil=0;
        $id_bien=0;
        
        $bien=NULL;
        $contact=NULL;
        $contact_profil=NULL;
        $rel_personne_bien=0;

       


        if(session()->get("importDemande"))
        {
            $import["importDemande"]=session()->get("importDemande");

			//debug($import["importDemande"]);
			if(!isset($import["importDemande"]->subject))
			{
                $this->datas->nom_demande=NULL;
			}
			else
			{
                $this->datas->nom_demande=$import["importDemande"]->subject;
			}

			if(!isset($import["importDemande"]->id_sexe))
			{
                $this->datas->id_sexe=NULL;
			}
			else
			{
                $this->datas->id_sexe=$import["importDemande"]->id_sexe;
			}

			if(!isset($import["importDemande"]->id_demande_origine))
			{
				$this->datas->id_demande_origine=NULL;
			}
			else
			{
                $this->datas->id_demande_origine=$import["importDemande"]->id_demande_origine;
			}
			
            $this->datas->descriptif_demande=$import["importDemande"]->comment;

			if(isset($import["importDemande"]->id_contact_profil)&&$import["importDemande"]->id_contact_profil>0)
			{

                
                $id_contact_profil=$import["importDemande"]->id_contact_profil;
                $contact_profil=$this->demandeModel->getFormOneContactProfil($id_contact_profil);
        
                $id_contact=$contact_profil->id_contact;
                $contact=$this->demandeModel->getFormOneContact($id_contact);
      


			}

			if(isset($import["importDemande"]->id_bien)&&$import["importDemande"]->id_bien>0)
			{
                $bien=$this->demandeModel->getFormOneBien($import["importDemande"]->id_bien);
                $id_bien=$import["importDemande"]->id_bien;
		
			}
            

			 if(isset($import["importDemande"]->urls_file)&&!empty($import["importDemande"]->urls_file))
			 {

				$this->datas->urls_file=$import["importDemande"]->urls_file;
			 }
			 
			 if(isset($import["importDemande"]->id_bien)&&!empty($import["importDemande"]->id_bien))
			 {

                $this->datas->id_bien_tamo=$import["importDemande"]->id_bien;
			 }
			 else
			 {
                $this->datas->id_bien_tamo=0;
			 }
			 
			 if(isset($import["importDemande"]->id_deposit)&&!empty($import["importDemande"]->id_deposit))
			 {

                $this->datas->id_deposit=$import["importDemande"]->id_deposit;
			 }
			 else
			 {
                $this->datas->id_deposit=0;
			 }

			 if(isset($import["importDemande"]->infos)&&!empty(isset($import["importDemande"]->infos)))
			 {
                $this->datas->info_origine_form=$import["importDemande"]->infos;
			 }
        }

        if(isset($import["importDemande"]->id_moyen_contact))
        {
       
           if(empty($import["importDemande"]->id_moyen_contact))
           {
               $interface="web";
           }
       
           if($import["importDemande"]->id_moyen_contact==14)
           {
               $interface="renolution";
           }

           else
           {
               $interface="web";
           }
        }
        else
        {
           $interface="web";
        }
        


        $type=$interface;
       

            
        $this->datas->titleView="Formulaire demande";
        $this->datas->path=$this->viewpath;
        $this->datas->type=$type;
        $this->datas->dataview=new DataViewConstructor();


        session()->set("interface",$type);
        $this->datas->interface=$type;


        //voilà l'ensemble des id a psser
        $this->datas->interface=$type;
        $this->datas->rel_personne_bien=$rel_personne_bien;
        $this->datas->form_insert_personne=NULL;

        $this->datas->id_contact=$id_contact;
        $this->datas->id_contact_profil=$id_contact_profil;
        $this->datas->id_bien=$id_bien;
        $this->datas->id_bien_tamo=$id_bien;

        $this->datas->bien=$bien;
        $this->datas->contact=$contact;
        $this->datas->contact_profil=$contact_profil;


            $this->datas->importDemande=$import["importDemande"];
            $this->datas->info_origine_form = $this->DWLibrary->get_info($import["importDemande"]->id_deposit);

            //debug($import);
        return view($this->viewpath .'\formDemande',  (array) $this->datas);
    
    }

    public function new($type=NULL,$id_message=0,$id_contact=0,$id_contact_profil=0,$id_bien=0)
    {
   
        $bien=NULL;
        $contact=NULL;
        $contact_profil=NULL;
        $rel_personne_bien=0;
            
        $this->datas->titleView="Formulaire demande";
        $this->datas->path=$this->viewpath;
        $this->datas->type=$type;
        $this->datas->dataview=new DataViewConstructor();


        //1. On a un id_bien mais pas id_contact, généralement c'est quand on attache un bien existant à une demande

        if($id_bien>0&&$id_contact==0)
        {
            $bien=$this->demandeModel->getFormOneBien($id_bien);
            //On recherche un id_contact
            $personne_bien=$this->demandeModel->get_personne_bien_by_id_bien($id_bien);

            if(count($personne_bien)==1)
            {
                $id_contact=$personne_bien[0]->id_contact;
                $id_contact_profil=$personne_bien[0]->id_contact_profil;
                $rel_personne_bien=$personne_bien[0]->rel_personne_bien;

                $contact=$this->demandeModel->getFormOneContact($id_contact);
                $contact_profil=$this->demandeModel->getFormOneContactProfil($id_contact_profil);

                

               
            }
            else
            {
                
                //$contacts=$this->demandeModel->get_personne_by_id_bien($id_bien);

                $this->datas->demandeurs_bien= $this->form_get_demandeurs_possibles($id_bien);
            }

           //debugd($this->datas->id_contact);
        }

        else if($id_contact>0)
        {

            $contact=$this->demandeModel->getFormOneContact($id_contact);
            $contact_profil=$this->demandeModel->getFormOneContactProfil($id_contact_profil);

            //je regarde si j'ai plusieurs bien
            $bien=$this->bien->get_bien_by_contact($id_contact,$id_contact_profil);

            if(count($bien)>1)//plus de deux biens
            {
                $this->datas->bien_possibles=$this->bien->search_bien_by_id_contact_no_mis_en_forme($id_contact,$id_contact_profil);

            }
            else 
            {
                if(isset($bien[0]->id_bien))//un seul bien
                {
                    $bien=$this->demandeModel->getFormOneBien($bien[0]->id_bien);

                }
            }
           // debug($contact);
            //savoir s'il a un ou plusieurs biens

        }
  
        if($type=="outlook")
        {

            $message=$this->ban_crud_model->read_data("email_outlook","*","id_primary=".$id_message);
            if(isset($message[0]->id_primary)&&$type=="outlook"):
                $this->datas->id_message=$id_message;
                $this->datas->email_demande=$message[0]->sender_mail;
                $this->datas->nom_demande=$message[0]->subject;
                $this->datas->descriptif_demande=$message[0]->body_preview;
                $this->datas->demande_by_email=$this->get_demande_by_email($message[0]->sender_mail,$id_message);
                $this->datas->demandeurs=$this->form_get_demandeurs_possibles_message($message[0]->sender_mail);
            
            else:
                $this->datas->email_demande=NULL;
                $this->datas->nom_demande=NULL;
                $this->datas->descriptif_demande=NULL;
                $this->datas->demande_by_email=NULL;



            endif;
        }


        session()->set("interface",$type);
        $this->datas->interface=$type;


 

        //voilà l'ensemble des id a psser
        $this->datas->interface=$type;
        $this->datas->rel_personne_bien=$rel_personne_bien;
        $this->datas->form_insert_personne=NULL;

        $this->datas->id_contact=$id_contact;
        $this->datas->id_contact_profil=$id_contact_profil;
        $this->datas->id_bien=$id_bien;
        $this->datas->id_bien_tamo=$id_bien;

        $this->datas->bien=$bien;
        $this->datas->contact=$contact;
        $this->datas->contact_profil=$contact_profil;

        return view($this->viewpath .'\formDemande',  (array) $this->datas);
    
    }

    public function form_get_demandeurs_possibles($id_bien)
    {
        $contacts=$this->demandeModel->get_personne_by_id_bien($id_bien);

        return $this->datas->demandeurs_bien= view('Demande\liste_demandeur_possible',["contacts"=>$contacts]);
    }


    public function form_get_demandeurs_possibles_message($mail)
    {
        $contacts=$this->demandeModel->get_personne_by_mail($mail);

        return $this->datas->demandeurs_bien= view('Demande\liste_demandeur_possible',["contacts"=>$contacts]);
    }
    

    public function new_form_web($type=NULL,$id_message=0,$id_contact=0,$id_contact_profil=0,$id_bien=0)
    {
        //debugd(session(""));
        $bien=NULL;
        $contact=NULL;
        $contact_profil=NULL;
            
        $this->datas->titleView="Formulaire demande";
        $this->datas->path=$this->viewpath;
        $this->datas->type=$type;
        $this->datas->dataview=new DataViewConstructor();

        //Ici on s'occupe du cas d'outlook
        if($type=="outlook")
        {
            $message=$this->ban_crud_model->read_data("email_outlook","*","id_primary=".$id_message);
            if(isset($message[0]->id_primary)&&$type=="outlook"):
                $this->datas->id_message=$id_message;
                $this->datas->email_demande=$message[0]->sender_mail;
                $this->datas->nom_demande=$message[0]->subject;
                $this->datas->descriptif_demande=$message[0]->body_preview;
                $this->datas->demande_by_email=$this->get_demande_by_email($message[0]->sender_mail,$id_message);
                $this->datas->demandeurs=$this->get_demandeur_by_email($this->datas->email_demande);
            
            else:
                $this->datas->email_demande=NULL;
                $this->datas->nom_demande=NULL;
                $this->datas->descriptif_demande=NULL;
                $this->datas->demande_by_email=NULL;



            endif;

    

        }


        session()->set("interface",$type);
        $this->datas->interface=$type;


        if(!empty((array) session()->get('importDemande'))) :
            $deposit = session()->get('importDemande');
            $this->datas->nom_demande = $deposit->subject;
            $this->datas->descriptif_demande = $deposit->comment;
            if(!empty($deposit->id_contact)) $id_contact = $deposit->id_contact ;
            if(!empty($deposit->id_contact_profil)) $id_contact_profil = $deposit->id_contact_profil ;
            if(!empty($deposit->id_bien)) $id_bien = $deposit->id_bien ;
        endif;

        if(!empty((array) session()->get('importDemande'))):
			

			$import["importDemande"]=session()->get('importDemande');

			//debug($import["importDemande"]);
			if(!isset($import["importDemande"]->subject))
			{
                $this->datas->nom_demande=NULL;
			}
			else
			{
				$this->datas->nom_demande=$import["importDemande"]->subject;
			}

			if(!isset($import["importDemande"]->id_sexe))
			{
				$this->datas->id_sexe=NULL;
			}
			else
			{
				$this->datas->id_sexe=$import["importDemande"]->id_sexe;
			}

			if(!isset($import["importDemande"]->id_demande_origine))
			{
				$this->datas->id_demande_origine=NULL;
			}
			else
			{
				$this->datas->id_demande_origine=$import["importDemande"]->id_demande_origine;
			}
			
			$this->datas->descriptif_demande=$import["importDemande"]->comment;


            if(isset($import["importDemande"]->id_contact_profil)&&$import["importDemande"]->id_contact_profil>0)
			{
                $db=db_connect();
                $builder=$db->table("contact_profil");
                $builder->where("id_contact_profil",$import["importDemande"]->id_contact_profil);
                $builder->join("contact","contact.id_contact=contact_profil.id_contact","left");
                $import["personne_systeme"]=$builder->get()->getResult();

				/*$this->db->where("id_personne",$import["importDemande"]->id_personne);
				$import["personne_systeme"]=$this->db->get("personne")->row();*/
				//print_r($import["personne_systeme"]);
			}

			elseif(isset($import["importDemande"]->id_contact)&&$import["importDemande"]->id_contact>0)
			{
                $db=db_connect();
                $builder=$db->table("contact");
                $builder->where("id_contact",$import["importDemande"]->id_contact);
                $import["personne_systeme"]=$builder->get()->getResult();

				/*$this->db->where("id_personne",$import["importDemande"]->id_personne);
				$import["personne_systeme"]=$this->db->get("personne")->row();*/
				//print_r($import["personne_systeme"]);
			}

			if(isset($import["importDemande"]->id_bien)&&$import["importDemande"]->id_bien>0)
			{
               /* $db=db_connect();
                $builder=$db->table("personne");
                $builder->where("id_personne",$import["importDemande"]->id_personne);
                $import["personne_systeme"]=$builder->get()->getResult();*/

                $db=db_connect();
                $builder=$db->table("bien");
                $builder->where("id_bien",$import["importDemande"]->id_bien);
                $import["bien_systeme"]=$builder->get()->getResult();

				//print_r($import["bien_systeme"]);
			}

			/*echo '<pre>test';
			 print_r($import["importDemande"]);
			 echo '</pre>';*/

			 if(isset($import["importDemande"]->urls_file)&&!empty($import["importDemande"]->urls_file))
			 {

				$this->datas->urls_file=$import["importDemande"]->urls_file;
			 }
			 
			 if(isset($import["importDemande"]->id_bien)&&!empty($import["importDemande"]->id_bien))
			 {

				$this->datas->id_bien_tamo=$import["importDemande"]->id_bien;
			 }
			 else
			 {
				$this->datas->id_bien_tamo=0;
			 }
			 
			 if(isset($import["importDemande"]->id_deposit)&&!empty($import["importDemande"]->id_deposit))
			 {

				$this->datas->id_deposit=$import["importDemande"]->id_deposit;
			 }
			 else
			 {
				$this->datas->id_deposit=0;
			 }

			 if(isset($import["importDemande"]->infos)&&!empty(isset($import["importDemande"]->infos)))
			 {
				$this->datas->info_origine_form=$import["importDemande"]->infos;
			 }

             if(isset($import["importDemande"]->id_moyen_contact))
             {
            
                if(empty($import["importDemande"]->id_moyen_contact))
                {
                    $interface="web";
                }
            
                if($import["importDemande"]->id_moyen_contact==14)
                {
                    $interface="renolution";
                }
    
                else
                {
                    $interface="web";
                }
             }
             else
             {
                $interface="web";
             }

             $this->datas->interface=$interface;
		endif;		
		


        $this->datas->form_insert_personne=NULL;

        $this->datas->id_contact=$id_contact;
        $this->datas->id_contact_profil=$id_contact_profil;
        $this->datas->id_bien=$id_bien;

        $this->datas->id_bien_tamo=$id_bien;

      
        if($id_contact>0)
        {
            $contact=$this->demandeModel->getFormOneContact($id_contact);

            if($id_bien>0)
            {


            }
            else
            {


            }
        }

        if($id_contact_profil>0)
        {
            $contact_profil=$this->demandeModel->getFormOneContactProfil($id_contact_profil);
        }

        if($id_bien>0)
        {
            $bien=$this->demandeModel->getFormOneBien($id_bien);

        }

        $this->datas->bien=$bien;
        $this->datas->contact=$contact;
        $this->datas->contact_profil=$contact_profil;

       

        return view($this->viewpath .'\formDemande',  (array) $this->datas);
    
    }

    public function get_demandeur_by_email($mail)
	{
	     $table="personne_bien";
	    $select="contact.id_contact, contact.nom_contact as nom, contact.prenom_contact as prenom ";
	
	    $left=array();
	    $left_condition["demande"]="demande.id_demande=personne_bien.id_demande";
	    $left_condition["contact"]="contact.id_contact=personne_bien.id_contact";
        $left_condition["contact_profil"]="contact_profil.id_contact_profil=personne_bien.id_contact_profil";
	    $left_condition["liste_demande_type"]="liste_demande_type.id=demande.id_type_demande";
	    $left_condition["email_outlook_lien"]="email_outlook_lien.id_demande=demande.id_demande";
	    $left_condition["email_outlook"]="email_outlook.id_primary=email_outlook_lien.id_email";
	    $mail=extraire_mail($mail);
	    array_push($left,$left_condition);
	   // print_r($mail); die();
	    $where="contact_profil.email LIKE '%$mail%' ";
	    $where.= " OR email_outlook.sender_mail LIKE '%$mail%' OR email_outlook.to_mail LIKE '%$mail%'  ";
	    //$where_condition["personne.email"]=$mail;
	   // array_push($where,$where_condition);
	    
	    $demandeurs=$this->ban_crud_model->read_data($table,$select,$where,$left,"personne_bien.date_insertion DESC","contact.id_contact",100);
	   // print_r($demandes);
	    $de=NULL;
	    
		foreach($demandeurs as $demandeur):
		    if(isset($demandeur->id_contact)):
			$de.='<a '
			    . 'style="margin-bottom:2px; margin-right:2px" '
			    . ' href="'.base_url().'fh/fhc_dao/page_view/'.$demandeur->id_contact.'/fhd_liste_demande" '
			    . ' fh-descriptor="fhd_liste_demandeur" href-ajax='.base_url().'fh/fhc_dao/get_fiche/'.$demandeur->id_contact
			    . ' href-title="'.base_url().'fh/fhc_dao/get_fiche_title/'.$demandeur->id_contact.'"'
			    . ' class="fh_dao_fiche btn btn-info btn-xs">'
			    . ' <i class="fa fa-user"></i> '
			    . ' '.$demandeur->prenom." ".$demandeur->nom.'</a>';
			
		    endif;
		endforeach;
	  
	    
	    return $de;
	}

    public function get_demandeur_by_bien($id_bien)
	{
	     $table="personne_bien";
	    $select="contact.id_contact, contact.nom_contact as nom, contact.prenom_contact as prenom ";
	
	    $left=array();
	    $left_condition["demande"]="demande.id_demande=personne_bien.id_demande";
	    $left_condition["contact"]="contact.id_contact=personne_bien.id_contact";
        $left_condition["contact_profil"]="contact_profil.id_contact_profil=personne_bien.id_contact_profil";
	    $left_condition["liste_demande_type"]="liste_demande_type.id=demande.id_type_demande";
	    $left_condition["email_outlook_lien"]="email_outlook_lien.id_demande=demande.id_demande";
	    $left_condition["email_outlook"]="email_outlook.id_primary=email_outlook_lien.id_email";
	   
	    array_push($left,$left_condition);
	   // print_r($mail); die();
	    $where="personne_bien.id_bien = $id_bien ";
	   
	    //$where_condition["personne.email"]=$mail;
	   // array_push($where,$where_condition);
	    
	    $demandeurs=$this->ban_crud_model->read_data($table,$select,$where,$left,"personne_bien.date_insertion DESC","contact.id_contact",100);
	   // print_r($demandes);
	    $de=NULL;
	    
		foreach($demandeurs as $demandeur):
		    if(isset($demandeur->id_contact)):
			$de.='<a '
			    . 'style="margin-bottom:2px; margin-right:2px" '
			    . ' href="'.base_url().'fh/fhc_dao/page_view/'.$demandeur->id_contact.'/fhd_liste_demande" '
			    . ' fh-descriptor="fhd_liste_demandeur" href-ajax='.base_url().'fh/fhc_dao/get_fiche/'.$demandeur->id_contact
			    . ' href-title="'.base_url().'fh/fhc_dao/get_fiche_title/'.$demandeur->id_contact.'"'
			    . ' class="fh_dao_fiche btn btn-info btn-xs">'
			    . ' <i class="fa fa-user"></i> '
			    . ' '.$demandeur->prenom." ".$demandeur->nom.'</a>';
			
		    endif;
		endforeach;
	  
	    
	    return $de;
	}
	
	public function get_demande_by_email($mail,$id_message=0){
	    $table="personne_bien";
	    $select="demande.id_demande, DATE_FORMAT(personne_bien.date_insertion,'%d/%m/%Y') as date,liste_demande_type.label as type ";
	
	    $left=array();
	    $left_condition["demande"]="demande.id_demande=personne_bien.id_demande";
	    $left_condition["contact"]="contact.id_contact=personne_bien.id_contact";
        $left_condition["contact_profil"]="contact_profil.id_contact_profil=personne_bien.id_contact_profil";

	    $left_condition["liste_demande_type"]="liste_demande_type.id=demande.id_type_demande";
	    $left_condition["email_outlook_lien"]="email_outlook_lien.id_demande=demande.id_demande";
	    $left_condition["email_outlook"]="email_outlook.id_primary=email_outlook_lien.id_email";
	    $mail=extraire_mail($mail);
	    array_push($left,$left_condition);
	    
	    $where="contact_profil.email LIKE '%$mail%' ";
	    $where.= " OR email_outlook.sender_mail LIKE '%$mail%' OR email_outlook.to_mail LIKE '%$mail%'  ";
	    //$where_condition["personne.email"]=$mail;
	   // array_push($where,$where_condition);
	    $demandes=$this->ban_crud_model->read_data($table,$select,$where,$left,"personne_bien.date_insertion DESC","demande.id_demande", 100);
	  
	    $de=NULL;

        if(!empty($demandes))
        {
            $de=view("Demande\demande_by_email",["demandes"=>$demandes,"id_message"=>$id_message]);
        }
	    
	
	  
	   // echo $mail;

        //debug($demandes); die();
	    return $de;
	}



    public function new_outlook($id_message=0)
	{
        die();
	   // $this->session->set_userdata("interface",'outlook');
	   

	
	    $this->session->set_userdata("interface",'outlook');
		$interface="outlook";
		$params["interface"]=$interface;
	    $params["last_comment"]=0; 
	    $params["value_id"]=0;
	    $params["sous_panel_indexes"]=NULL;
	 
	    
	    
		$data["interface"]=$interface;

        if($interface=="outlook")
        {
            $message=$this->ban_crud_model->read_data("email_outlook","*","id_primary=".$id_message);
            if(isset($message[0]->id_primary)&&$interface=="outlook"):
                $data["id_message"]=$id_message;
                $data["email_demande"]=$message[0]->sender_mail;
                $data["nom_demande"]=$message[0]->subject;
                $data["descriptif_demande"]=$message[0]->body_preview;
                $data["demande_by_email"]=$this->get_demande_by_email($message[0]->sender_mail);
                $data["demandeurs"]=$this->get_demandeur_by_email($data["email_demande"]);
            
            else:
                $data["email_demande"]=NULL;
                $data["nom_demande"]=NULL;
                $data["descriptif_demande"]=NULL;
                $data["demande_by_email"]=NULL;
            endif;

        }
       
	    
	

	    $data["form_insert"]=$this->fh_dao->get_fiche_insert($params);
	    
	    
	    $this->config->load('fhd_liste_demande_permanence_2'); 
	    $params=$this->config->item("params");
	    $params["last_comment"]=0; 
	    $params["value_id"]=0;
	    $params["sous_panel_indexes"]=NULL;
		$params["interface"]=$interface;
	    $data["form_insert_2"]=$this->fh_dao->get_fiche_insert($params);
	    
	    $this->config->load('fhd_liste_demandeur_permanence'); 
	    $params=$this->config->item("params");
	    $params["last_comment"]=0; 
	    $params["value_id"]=0;
	    $params["sous_panel_indexes"]=NULL;
		$params["interface"]=$interface;
	    $data["form_insert_personne"]=$this->fh_dao->get_fiche_insert($params);
	    
	    $this->config->load('fhd_liste_bien_reduit'); 
	    $params=$this->config->item("params");
	    $params["last_comment"]=0; 
	    $params["value_id"]=0;
	    $params["sous_panel_indexes"]=NULL;
		$params["interface"]=$interface;
	    $data["form_insert_bien"]=$this->fh_dao->get_fiche_insert($params);
	    
	   $params_count["id_user"]=$this->session->userdata("id");
	    $data['span_count_no_lus'] = $this->l_messagerie->span_count_no_lus($params_count);
	    $data["token"]= $this->l_messagerie->get_token();
	    $this->load->view("template/header");
	    
	   
	    $this->fh_dao->get_js();
	     $this->load->view("template/rae_js",$data);
	     $data["token"]= $this->l_messagerie->get_token();
	   $this->load->view("js_messagerie/reload_js",$data);
	     $this->load->view("template/nav",$data);
	    $this->load->view("interface/outlook_ajout",$data);
	    $this->load->view("template/footer");
	}

    public function ModelisationDemande($id_demande=NULL,$validation=NULL)
    {
       /* if(!$this->autorisationManager->is_autorise("modelisation_a"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();


        $components=$dataView->getComponents("demande");
        if(empty($components))
        {
            return "Aucune page définie pour les demandes";
        }

        if(!is_null($id_demande)){
            $typeDataView="update";
            $demande=$this->demandeModel->getDemande($id_demande);
            if(!empty($demande)):
                $titleView="Demande de $demande->nom $demande->prenom pour $demande->idact ".$demande->titre;
            else:
                $titleView="Fiche non trouvée";
            endif;    
        }

        if(is_null($id_demande)||$id_demande==0)
        {
            $demande=NULL;
            $typeDataView="create";
            $titleView="Créer une nouvelle fiche de demande";
        }

        if(!empty($demande))
        {
            $id_demande=$demande->id_demande;
            $demande=$this->demandeModel->find($id_demande);

        }

        else
        {
            $demande=NULL;
            $id_demande=NULL;
        }


      

        $this->datas->documents=NULL;
        $this->datas->rdvs=NULL;
        $this->datas->path=$this->viewpath;
        $this->datas->demande = NULL;
        $this->datas->fields= $this->demandeModel->getFields();
        $this->datas->context=  "modelisation";
        $this->datas->titleView= "Modélisation de la fiche de demande";
        $this->datas->dataView= $dataView;
        $this->datas->typeDataView= "modelisation";
        $this->datas->validation= $validation;
        $this->datas->id_demande= $id_demande;
        $this->datas->demande= $demande;
        $this->datas->id_demande= $id_demande;
        $this->datas->components= $components;
        $this->datas->id_personne_bien=0;

        $this->datas->demandeModel=$this->demandeModel;
       

       /* $this->datas->gasap_membre=$this->demandeModel->gasap_demande($id_demande,"gasap_membre");
        $this->datas->gasap_referent=$this->demandeModel->gasap_demande($id_demande,"gasap_referent");
        $this->datas->gasap_referent_2=$this->demandeModel->gasap_demande($id_demande,"gasap_referent_2");
        $this->datas->gasap_tresorier=$this->demandeModel->gasap_demande($id_demande,"gasap_tresorier");
        $this->datas->gasap_inscription=$this->demandeModel->gasap_demande($id_demande,"gasap_inscription");*/

      //  $this->datas->demande=$this->demandeModel->get_demande_by_demande($id_demande);


        


        return view($this->module . '\view-demande-fiche', (array) $this->datas);
    }

    public function save_modelisation()
    {
        $posts=$this->request->getVar();
        
        $dataView=new DataViewConstructor();
        $dataGeneratorModel = new DataViewConstructorModel();
        $entityParams=$dataGeneratorModel->getOneEntities("demande");


        $dataView->setComponents("demande",$posts);
        
        $message= 'Le modèle de la fiche '.$entityParams->label.' a été enregistré';

        return redirect()->to(base_url()."/modelisation")->with("success",$message);
       

    }




    public function save($component="contact")
    {
       //list of indexes of form
        $session = \Config\Services::session();
        $indexes=$this->request->getVar("indexesForm");

        $dataView=new DataViewConstructor();
        $rules=$dataView->getRules($indexes,$this->demandeModel->getFields());
    
        if (!$this->validate($rules)&&!empty($rules)) 
        {
            if($this->request->getVar('typeDataView')=="create")
            {
                echo $this->formDemande(NULL,$this->validator);
            } 
            else 
            {
                echo $this->formDemande($this->request->getVar('id_demande'),$this->validator);
            }          
        } 
        else 
        {
                    //debugd($this->request->getVar());

            switch ($component)
            {
                case "contact":
                     //debug($this->request->getVar());
                    $id_contact_save= $this->demandeModel->saveDataDelivreContact($this->request->getVar("indexesForm"),$this->request->getVar(),$this->request->getVar("id_entity"));
                    break;

                case "bien":
                    //debugd($this->request->getVar());
                        $id_contact_save= $this->demandeModel->saveDataDelivreBien($this->request->getVar("indexesForm"),$this->request->getVar(),$this->request->getVar("id_entity"));
                        break;


                case "demande":
                    //debugd($this->request->getVar());
                        $id_contact_save= $this->demandeModel->saveDataDelivreDemande($this->request->getVar("indexesForm"),$this->request->getVar(),$this->request->getVar("id_entity"));
                        break;
            }
            
            /*    $id_bien_save=$dataView->saveData(
                $indexes,
                $this->request->getVar(),
                $this->demandeModel->getFields(),
                $this->demandeModel->getTable(),
                $this->request->getVar("id_demande"),
                'id_demande'
            );  */
            
            if($this->request->getVar("id_demande")>0)
            {
                $id_bien_save=$this->request->getVar("id_demande");
            }

            if($this->request->getVar('typeDataView')=="create")
            {
                //$id_bien=$this->bienModel->insertData($data);
                $message= 'La fiche de la demande a été créée';
            } 
            else 
            {
                //$id_bien=$this->bienModel->updateData($data,$this->request->getVar("id_bien"));
                $message= 'La fiche de la demande été modifiée';
            }
            
            return redirect()->to(base_url()."/demande/fiche/$id_bien_save")->with("success",$message);
        }
    }

    public function delete_bien($id_personne_bien,$id_demande)
    {
       //debug($this->request->getVar(),true);
        if($id_personne_bien>0&&$id_demande>0)
        {
           $is_delete=$this->demandeModel->delete_bien($id_personne_bien);

           if($is_delete)
            {
                return redirect()->to(base_url()."/demande/fiche/$id_demande")->with("success","Le relation a été effacée");

            }
            else
            {
                return redirect()->to(base_url()."/demande/fiche/$id_demande")->with("danger","Impossible d'effacer cette relation");
            }
        }
        else
        {
            return redirect()->to(base_url()."/demande/fiche/$id_demande")->with("danger","Impossible d'effacer cette relation");
        }

    }

    public function delete_contact($id_personne_bien,$id_demande)
    {
       //debug($this->request->getVar(),true);
       if($id_personne_bien>0&&$id_demande>0)
       {
          $is_delete=$this->demandeModel->delete_contact($id_personne_bien);

          if($is_delete)
           {
               return redirect()->to(base_url()."/demande/fiche/$id_demande")->with("success","Le relation a été effacée");

           }
           else
           {
               return redirect()->to(base_url()."/demande/fiche/$id_demande")->with("danger","Impossible d'effacer cette relation");
           }
       }
       else
       {
           return redirect()->to(base_url()."/demande/fiche/$id_demande")->with("danger","Impossible d'effacer cette relation");
       }


    }

    public function set_permanence()
    {
        
        //debug($this->request->getVar()); 
        $id_type_demande=array();
        $indexes_traduction=array();
        //ensemble des valeurs à enregistrer
        $exclude=array("honeypot","id_bien_tamo","urls_file","id_bien","id_demandeur","is_occupe","accompagnement_is_occupe","visite_is_occupe","value_id","id_entity_bien","id_entity_personne","id_message");
    
        $dataviewModel=new DataViewConstructorModel();
        $descriptor=$dataviewModel->getDescriptorBrut();
      //  $this->config->load('fh_descriptor'); 
       // require(APPPATH.'config/fh_descriptor.php');
        //debug($descriptor,true);


        //initialiser les variables

         $interface=$this->request->getVar("interface");

        if($this->request->getVar("id_entity_bien")): $id_entity_bien=$this->request->getVar("id_entity_bien"); else: $id_entity_bien=NULL; endif;
        //if($this->request->getVar("id_entity_personne")): $id_entity_personne=$this->request->getVar("id_entity_personne"); else: $id_entity_personne=NULL; endif;
        if($this->request->getVar("id_message")): $id_message=$this->request->getVar("id_message"); else: $id_message=NULL; endif;
        if($this->request->getVar("urls_file")): $urls_file=$this->request->getVar("urls_file"); else: $urls_file=NULL; endif;
        if($this->request->getVar("id_bien_tamo")): $id_bien_tamo=$this->request->getVar("id_bien_tamo"); else: $id_bien_tamo=NULL; endif;
        if($this->request->getVar("id_deposit")): $id_deposit=$this->request->getVar("id_deposit"); else: $id_deposit=NULL; endif;

        if($this->request->getVar("id_contact")): $id_entity_personne=$this->request->getVar("id_contact"); else: $id_entity_personne=NULL; endif;
        if($this->request->getVar("id_contact_profil")): $id_contact_profil=$this->request->getVar("id_contact_profil"); else: $id_contact_profil=NULL; endif;




         /*  foreach($this->request->getVar() as $k=>$v):
                 
                 echo "<li>".$k.'->'.$v;
                 
             
             endforeach;

             die();*/


             /*print_r($id_bien_tamo);

             die();*/

             
             
     
         $is_information=FALSE;
         $is_accompagnement=FALSE;
         $is_visite=FALSE;
         $array_id_type_demande=$this->request->getVar("id_type_demande");
        // debug($array_id_type_demande,true);
         $nombre_coche=0;
         $type_demande_encodage=array();
         if(in_array(1,$array_id_type_demande)): $nombre_coche=$nombre_coche+1; array_push($type_demande_encodage,1); $is_information=TRUE; $data_demande["id_type_demande"]=1; endif;
         if(in_array(2,$array_id_type_demande)): $nombre_coche=$nombre_coche+1; array_push($type_demande_encodage,2);$is_visite=TRUE; $data_demande_visite["id_type_demande"]=2; endif;
         if(in_array(3,$array_id_type_demande)): $nombre_coche=$nombre_coche+1; array_push($type_demande_encodage,3);$is_accompagnement=TRUE; $data_demande_accompagnement["id_type_demande"]=3; endif;
         
         
         $data_encodage["nombre_coche"]=$nombre_coche;
         $data_encodage["id_demande_type"]=implode(",",$type_demande_encodage);
         $data_encodage["is_premier_contact"]=$this->request->getVar("demande_contact_premier");
         $data_encodage["lang_contact"]=$this->request->getVar("langue_personne");
         $data_encodage["demande_origine"]=$this->request->getVar("demande_origine");
         $data_encodage["duree_contact"]=$this->request->getVar("demande_contact_duree");
         $data_encodage["localite"]=$this->request->getVar("adresse_fr_cp");
         $data_encodage["date_insert"]=date("Y-m-d H:i:s");
         $data_encodage["user_create"]=session()->get('loggedUserId');
         
        foreach($this->request->getVar() as $k=>$v):
        if(!in_array($k,$exclude)&&isset($descriptor[$k]["field_sql"])):
            $d_field=$descriptor[$k]["field_sql"];
            $d_table=$descriptor[$k]["table"];
            if(!is_array($v))
            {
                $v=trim($v);
            }
            else
            {
                $v=implode(",",$v);
            }
            
            
            $indexes_traduction[$d_field]=$k;
            switch ($d_table) {
                
            case "demande":
                
                if($is_information&&!strstr($k,"visite_")&&!strstr($k,"accompagnement_")):
                 $data_demande[$d_field]=$v;
                 $data_demande["id_type_demande"]=1;
                endif;
                
                 if($is_visite&&strstr($k,"visite_")):
                 $data_demande_visite[$d_field]=$v;
                 $data_demande_visite["id_type_demande"]=2;
             endif;
                
              if($is_accompagnement&&strstr($k,"accompagnement_")):
                 $data_demande_accompagnement[$d_field]=$v;
                 $data_demande_accompagnement["id_type_demande"]=3;
              endif;
                
                break;
            
            case "demande_caracteristique":
                 if($is_information&&!strstr($k,"visite_")&&!strstr($k,"accompagnement_")):

                  if(is_array($v))
                  {
                    $data_demande_caracteristique[$d_field]=implode(",",$v); 
                  }
                  else
                  {
                    $data_demande_caracteristique[$d_field]=$v;
                  }  
                
                endif;
                
                 if($is_visite&&strstr($k,"visite_")):

                    if(is_array($v))
                    {
                      $data_demande_visite_caracteristique[$d_field]=implode(",",$v); 
                    }
                    else
                    {
                        $data_demande_visite_caracteristique[$d_field]=$v;
                    }  


                endif;
                
              if($is_accompagnement&&strstr($k,"accompagnement_")):
                  if($d_field=="date_pvb"):
             
                   $data_demande_accompagnement_caracteristique[$d_field]=convert_date_fr_to_en($v);
                  //&&!empty($v)&&$v!=="00/00/0000"
                  else:

                    if(is_array($v))
                    {
                        $data_demande_accompagnement_caracteristique[$d_field]=implode(",",$v); 
                    }
                    else
                    {
                        $data_demande_accompagnement_caracteristique[$d_field]=$v;
                    }  

                   



                  endif;
                 
                // print_r($d_field);
              endif;
                
               
               
                break;
            
            case "contact":
            case "personne":
                if(is_array($v))
                {
                    $data_personne[$d_field]=implode(",",$v); 
                }
                else
                {
                    $data_personne[$d_field]=$v;
                }  
               
                break;

            case "contact_profil":
                if(is_array($v))
                {
                    $data_contact_profil[$d_field]=implode(",",$v); 
                }
                else
                {
                    $data_contact_profil[$d_field]=$v;
                }  
                
                break;
            
            case "bien":
                if(is_array($v))
                {
                    $data_bien[$d_field]=implode(",",$v); 
                }
                else
                {
                    $data_bien[$d_field]=$v;
                }  
                
                break;
            
            default:
                if(is_array($v))
                {
                    $data_demande_other[$d_field]=implode(",",$v); 
                }
                else
                {
                    $data_demande_other[$d_field]=$v;
                }  
                
                break;
            }
          
        endif;
        
        endforeach;
        
        
        
        
        //debug value
        
              $debug_value=false;

              if($debug_value)
              {

             
        	       echo "id_bien=$id_entity_bien | id_personne=$id_entity_personne | id_message=$id_message | id_contact_profil=$id_contact_profil";
        	       echo "<hr>Contact";
                        debug($data_personne);
                        echo "<hr>";
                    echo "<hr>Contact profil";
                        debug($data_contact_profil);
                        echo "<hr>";
                    if(isset($data_bien)):
                        debug($data_bien);
                        else:
                    echo "nobien";
                        endif;
                        echo "<hr>";
                        debug($data_demande_other);
                            echo "<hr>";
                        print_r($data_demande);
                        echo "<hr>";
                        debug($data_demande_caracteristique);
                        die();
                }
               
                 
                 //je crée la demande dans la table demande
                 
        $data_demande["date"]=date("Y-m-d H:i:s");
        $data_demande_accompagnement["date"]=date("Y-m-d H:i:s");
        $data_demande_visite["date"]=date("Y-m-d H:i:s");
        
        $data_demande["date_insert"]=date("Y-m-d H:i:s");
        $data_demande_accompagnement["date_insert"]=date("Y-m-d H:i:s");
        $data_demande_visite["date_insert"]=date("Y-m-d H:i:s");
       
        
        
        $data_demande["id_user_create"]=session()->get('loggedUserId');
        $data_demande_accompagnement["id_user_create"]=session()->get('loggedUserId');
        $data_demande_visite["id_user_create"]=session()->get('loggedUserId');
        
        if($this->request->getVar("demande_pole")):
         $data_demande["id_pole"]=$this->request->getVar("demande_pole");
        endif;
        
        if($this->request->getVar("accompagnement_demande_pole")):
         $data_demande_accompagnement["id_pole"]=$this->request->getVar("accompagnement_demande_pole");
        endif;

        if($this->request->getVar("visite_demande_pole")):
         $data_demande_visite["id_pole"]=$this->request->getVar("visite_demande_pole");
        endif;
       
        
        if($this->request->getVar("accompagnement_is_occupe")):
             $data_demande_accompagnement["id_utilisateur"]=session()->get('loggedUserId');
        endif;
        
        if($this->request->getVar("visite_is_occupe")):
             $data_demande_visite["id_utilisateur"]=session()->get('loggedUserId');
        endif;
        //traduction
        switch($interface):
                 case 'bureau':
                     $data_demande["moyen_contact"]=12;
                     $data_demande_accompagnement["moyen_contact"]=12;
                     $data_demande_visite["moyen_contact"]=12;
                 break;
                 
                 case 'event':
                     $data_demande["moyen_contact"]=3;
                     $data_demande_accompagnement["moyen_contact"]=3;
                     $data_demande_visite["moyen_contact"]=3;
                 break;
                 
                 
                 case 'guichet':
                     $data_demande["moyen_contact"]=2;
                     $data_demande_accompagnement["moyen_contact"]=2;
                     $data_demande_visite["moyen_contact"]=2;
                 break;
                 
                 case 'telephone':
                     $data_demande["moyen_contact"]=1;
                     $data_demande_accompagnement["moyen_contact"]=1;
                     $data_demande_visite["moyen_contact"]=1;
                 break;
                     
                 case 'outlook':
                     $data_demande["moyen_contact"]=4;
                     $data_demande_accompagnement["moyen_contact"]=4;
                     $data_demande_visite["moyen_contact"]=4;
                 break;
                     
                 case 'stand':
                     $data_demande["moyen_contact"]=11;
                     $data_demande_accompagnement["moyen_contact"]=11;
                     $data_demande_visite["moyen_contact"]=11;     	
                 break;

                 case 'web':
                     $data_demande["moyen_contact"]=5;
                     $data_demande_accompagnement["moyen_contact"]=5;
                     $data_demande_visite["moyen_contact"]=5;     	
                 break;

                 case 'renolution':
                     $data_demande["moyen_contact"]=14;
                     $data_demande_accompagnement["moyen_contact"]=14;
                     $data_demande_visite["moyen_contact"]=14;     	
                 break;
                 
                 default:
                     $data_demande["moyen_contact"]=13;
                     $data_demande_accompagnement["moyen_contact"]=13;
                     $data_demande_visite["moyen_contact"]=13;
        
        endswitch;
        
        if(isset($data_demande["moyen_contact"])):
                $data_encodage["moyen_contact"]=$data_demande["moyen_contact"];
        endif;
        
       /* if($this->request->getVar("id_demandeur")>0):
        $data_demande["id_demande_statut"]=1;
        else:
        $data_demande["id_demande_statut"]=6;
        endif;*/
        
        if(isset($data_demande["id_type_demande"])&&$data_demande["id_type_demande"]==1):
            $data_demande["id_demande_statut"]=6;
        else:
            $data_demande["id_demande_statut"]=1;
        endif;
        
        if(isset($data_demande_accompagnement["id_type_demande"])):
            /*$data_demande_accompagnement["id_demande_statut"]=6;
        else:*/
            $data_demande_accompagnement["id_demande_statut"]=1;
        endif;
        
        if(isset($data_demande_visite["id_type_demande"])):
        /*	$data_demande_visite["id_demande_statut"]=6;
        else:*/
            $data_demande_visite["id_demande_statut"]=1;
        endif;
        
        
        if($this->request->getVar("is_occupe")):
         
                 $data_demande["id_utilisateur"]=session()->get('loggedUserId');
             
                     $data_demande["id_demande_statut"]=1;
        else:
             if($interface==="web" ||$interface==="outlook" ||$interface==="bureau" || $interface==="renolution"  ):
                 
                 $data_demande["id_demande_statut"]=1;
                 $data_demande["id_utilisateur"]=25;

             elseif(empty($interface)):	
                 $data_demande["id_demande_statut"]=1;
                 $data_demande["id_utilisateur"]=25;
             else:
                     $is_cloturer=TRUE;
                     //$data_demande_visite["id_demande_statut"]=6;
                     $data_demande["id_utilisateur"]=25;
                 
                 if(!empty($data_personne["email"])):
                     $data["is_envoyer_cloture"]=TRUE;
                 endif;
         
             endif;
            
         endif;
        
        
        
        if($is_information):

            $new_id=$this->ban_crud_model->insert_data($data_demande,"demande");
            $data_demande_caracteristique["id_demande"]=$new_id;
            $this->ban_crud_model->insert_data($data_demande_caracteristique,"demande_caracteristique");
        endif;
        
        if($is_accompagnement):
             $new_id_accompagnement=$this->ban_crud_model->insert_data($data_demande_accompagnement,"demande");
             $data_demande_accompagnement_caracteristique["id_demande"]=$new_id_accompagnement;
             $this->ban_crud_model->insert_data($data_demande_accompagnement_caracteristique,"demande_caracteristique");
        endif;
        
        if($is_visite):
             $new_id_visite=$this->ban_crud_model->insert_data($data_demande_visite,"demande");
             $data_demande_visite_caracteristique["id_demande"]=$new_id_visite;
             $this->ban_crud_model->insert_data($data_demande_visite_caracteristique,"demande_caracteristique");
        endif;
        
        //je crée la demande dans la table demande_caracteristique
        
        
       
        //Je m'occupe du bien
        //si id_bien_entity>0 alors je vérifie que l'adresse est identique alors je compare les champs et update à la rigueur les champs
        //et j'historicise l'update
        //Sinon, j'enregistre un nouveau bien
        
        //je vérifie s'il y a des valeurs
        $is_value_bien=FALSE;
       // print_r($data_bien); die();
        foreach($data_bien as $kb=>$vb):
                if(!empty($vb)): $is_value_bien=TRUE; endif;
        endforeach;
        
        if(isset($data_bien)&&$is_value_bien):

         //avant tout chose, je dois vérifier si le bien existe
            
       
        if($id_entity_bien>0):
             //si on est dans le cas de l'interface web et que id_tamo est égale à l'id_entity_bien alors on ne fait pas
             //la vérification, on update direct si pas id_tamo existe et est différnt id_entity_bien alors on revient au système par défaut
             if(($interface==="web"||$interface==="renolution")&&isset($id_bien_tamo)&&!is_null($id_bien_tamo)&&$id_bien_tamo==$id_entity_bien)
             {
                 $where_bien="id_bien=".$id_entity_bien;
                 $data_bien["id_user_create"]=session()->get('loggedUserId');
                 $data_bien["date_insert"]=date("Y-m-d H:i:s");
                 $this->ban_crud_model->update_data("bien",$data_bien,$where_bien);
                 array_push($id_type_demande,3);
             }
             else
             {

                 //si je suis dans l'interface web et que id_bien tamo ne correspond pas alors je dois effacer id_bien tamo
                 if(($interface==="web"||$interface==="renolution")&&isset($id_bien_tamo)&&!is_null($id_bien_tamo)&&$id_bien_tamo!=$id_entity_bien)
                 {
                     $where_efface_tamo="id_bien=".$id_bien_tamo;
                     $this->ban_crud_model->delete_data("bien",$where_efface_tamo);

                 }

                 $where_bien="id_bien=".$id_entity_bien;
                 $data_bien_sql=$this->ban_crud_model->read_data("bien","*",$where_bien);
                // debugd($data_bien_sql); 
                 $field_bien=array("adresse_fr","adresse_nl","id_type","etage_logement","id_nombre_logement","id_chauffage");
                         if(isset($data_bien_sql[0]->id_bien)):
                                 if(
                                     ( 
                                         ( 
                                             $data_bien_sql[0]->adresse_nl===
                                             $data_bien["adresse_nl"]
                                             ||$data_bien_sql[0]->adresse_fr===$data_bien["adresse_fr"])
                                             && $data_bien_sql[0]->id_type<>2
                                         )
                                     ||
                                         (
                                             ( $data_bien_sql[0]->adresse_nl===$data_bien["adresse_nl"]
                                         ||$data_bien_sql[0]->adresse_fr===$data_bien["adresse_fr"])
                                         && $data_bien_sql[0]->id_type==2 && $data_bien_sql[0]->etage_logement===$data_bien["etage_logement"]
                                         )
                                     
                                     
                                     ):
                                         $this->ban_crud_model->update_data("bien",$data_bien,$where_bien);
                                         array_push($id_type_demande,3);
                                         foreach($field_bien as $field):
                                             if(isset($data_bien[$field])&&isset($data_bien_sql[0]->$field)&&$data_bien_sql[0]->$field!==$data_bien[$field]):
                                                 $value_update[$field]=$data_bien[$field];
                                                 $value_update["id_user"]=session()->get('loggedUserId');
                                                 $value_update["date_modification"]=date("Y-m-d H:i:s");
                                                 $this->ban_crud_model->update_data("bien",$value_update,$where_bien);
                                                 unset($value_update);
                                             endif;
                                         endforeach;
                         
                         
                                 else:
                         
                                         $data_bien["id_user_create"]=session()->get('loggedUserId');
                                         $data_bien["date_insert"]=date("Y-m-d H:i:s");
                                         $id_entity_bien=$this->ban_crud_model->insert_data($data_bien,"bien"); 
                                         array_push($id_type_demande,2);
                                         array_push($id_type_demande,3);
                                         
                                 endif;
                         
                         else:
                                 $data_bien["id_user_create"]=session()->get('loggedUserId');
                                 $data_bien["date_insert"]=date("Y-m-d H:i:s");
                                 $id_entity_bien=$this->ban_crud_model->insert_data($data_bien,"bien");
                                 array_push($id_type_demande,2);
                                 array_push($id_type_demande,3);
                         endif;
             }
            
            
         else:
                 $data_bien["id_user_create"]=session()->get('loggedUserId');
                 $data_bien["date_insert"]=date("Y-m-d H:i:s");
                 $id_entity_bien=$this->ban_crud_model->insert_data($data_bien,"bien");
                 array_push($id_type_demande,2);
                 array_push($id_type_demande,3);
        endif;
        
         else:
         $id_entity_bien=0;
         
         endif;
        
        
        
        //Je m'occupe du demandeur
        // debug($data_personne);
        
        //cas d'un anonyme
        if(
                (
                        !isset($data_personne["nom_contact"])
                        ||
                        (isset($data_personne["nom_contact"])&&empty($data_personne["nom_contact"]))
                )
                
                &&  
                (
                        !isset($data_personne["prenom_contact"])
                        ||
                        (isset($data_personne["prenom_contact"])&&empty($data_personne["prenom_contact"]))
                )

                &&
                (
                        $id_entity_personne==0
                        ||
                        is_null($id_entity_personne)
                )
            ):

             $data_personne["nom_contact"]="Anonyme";
             $id_entity_personne=$this->ban_crud_model->insert_data($data_personne,"contact");
             array_push($id_type_demande,4);
             array_push($id_type_demande,5);

       //debugd($data_personne);

       elseif($id_entity_personne>0):
         //Savoir si je reprend bien la personne en question
         $where=array();

         if($interface==="web"||$interface==="renolution")
         {
             

             $where_condition["id_contact"]=$id_entity_personne;
             

         }
         else
         {
             //$where_condition["nom"]=$data_personne["nom"];
             //$where_condition["prenom"]=$data_personne["prenom"];

             $where_condition["id_contact"]=$id_entity_personne;
         }
         
            array_push($where,$where_condition);

             $count=$this->ban_crud_model->read_data("contact","*",$where,NULL,NULL,NULL,NULL,TRUE);
             if($count>0): 
                 array_push($id_type_demande,5);
                 $where_update="id_contact=$id_entity_personne";
                 $personne=$this->ban_crud_model->read_data("contact","*",$where_update);
                 //$field_update=array("id_langue","id_civilite","email","telephone","adresse","localite","pays");//acnien
                 $field_update=array("id_langue","id_civilite");
                 foreach($field_update as $field):
                     if(isset($personne[0]->$field)&&isset($data_personne[$field])&&$personne[0]->$field!==$data_personne[$field]):
                         $value_update[$field]=$data_personne[$field];
                         $value_update["id_user"]=session()->get('loggedUserId');
                         $value_update["date_modification"]=date("Y-m-d H:i:s");
                         $this->ban_crud_model->update_data("contact",$value_update,$where_update);
                         unset($value_update);
                     endif;
                 endforeach;
                 if($this->request->getVar("langue_personne")):
                     $langue_personne=$this->request->getVar("langue_personne");
     
                     if($langue_personne>0):
                         $value_update["id_langue"]=$langue_personne;
                         $value_update["id_user"]=session()->get('loggedUserId');
                         $value_update["date_modification"]=date("Y-m-d H:i:s");
                         $this->ban_crud_model->update_data("contact",$value_update,$where_update);
                         unset($value_update);
                     endif;	
                endif;
             else:
                     $data_personne["id_user_create"]=session()->get('loggedUserId');
                     $data_personne["date_insert"]=date("Y-m-d H:i:s");
                     if($this->request->getVar("langue_personne")):
                         $langue_personne=$this->request->getVar("langue_personne");
         
                         if($langue_personne>0):
                             $data_personne["id_langue"]=$langue_personne;
                         
                         endif;	
                        endif;
                     $id_entity_personne=$this->ban_crud_model->insert_data($data_personne,"contact");
                     array_push($id_type_demande,4);
                     array_push($id_type_demande,5);
             endif;
         else:
             $data_personne["id_user_create"]=session()->get('loggedUserId');
             $data_personne["date_insert"]=date("Y-m-d H:i:s");
             if($this->request->getVar("langue_personne")):
                 $langue_personne=$this->request->getVar("langue_personne");
                 if($langue_personne>0):
                     $data_personne["id_langue"]=$langue_personne;
                 
                 endif;	
                endif;
              $id_entity_personne=$this->ban_crud_model->insert_data($data_personne,"contact");
             array_push($id_type_demande,4);
             array_push($id_type_demande,5);
         endif;

        /* if(isset($data_contact_profil["localite"])&&!empty($data_contact_profil["localite"]))
         {
            $data_contact_profil["localite"]=$this->demandeModel->get_localite_complete($data_contact_profil["localite"]);
         }*/

         if($id_contact_profil>0)
         {
             $where_contact_profil="id_contact_profil=$id_contact_profil";
             $this->ban_crud_model->update_data("contact_profil",$data_contact_profil,$where_contact_profil);

         }
         else
         {
            $data_contact_profil["id_contact"]=$id_entity_personne;
            $id_contact_profil=$this->ban_crud_model->insert_data($data_contact_profil,"contact_profil");
         }
     
     
     $data_relation["id_contact"]=$id_entity_personne;
     $data_relation["id_contact_profil"]=$id_contact_profil;
     $data_relation["id_bien"]=$id_entity_bien;
     $data_encodage["id_contact"]=$data_relation["id_contact"];
     $data_relation["id_contact_profil"]=$id_contact_profil;
     $data_encodage["id_bien"]=$data_relation["id_bien"];
     if($this->request->getVar("rel_personne_bien")>0):
         $data_relation["rel_personne_bien"]=$this->request->getVar("rel_personne_bien");
         $data_encodage["rel_personne_bien"]=$this->request->getVar("rel_personne_bien");
      endif;
      
      if($is_information):
         $data_relation["id_demande"]=$new_id;
         $this->ban_crud_model->insert_data($data_relation,"personne_bien");
     endif;
     
     if($is_accompagnement):
         $data_relation["id_demande"]=$new_id_accompagnement;
         $this->ban_crud_model->insert_data($data_relation,"personne_bien");
     endif;
     
     if($is_visite):
         $data_relation["id_demande"]=$new_id_visite;
         $this->ban_crud_model->insert_data($data_relation,"personne_bien");
     endif;
     
     
     if($id_message!=="0"&&!is_null($id_message)):
         $ms=$this->ban_crud_model->read_data("email_outlook","id","id_primary=$id_message");
         if(!empty($ms[0]->id)):
         $insert_message["id_message"]=$ms[0]->id;
         endif;
         $insert_message["id_email"]=$id_message;
         
         if($is_information):
             $insert_message["id_demande"]=$new_id;
             $this->ban_crud_model->insert_data($insert_message,"email_outlook_lien");
         endif;
         if($is_accompagnement):
             $insert_message["id_demande"]=$new_id_accompagnement;
             $this->ban_crud_model->insert_data($insert_message,"email_outlook_lien");
         endif;
         if($is_visite):
             $insert_message["id_demande"]=$new_id_visite;
             $this->ban_crud_model->insert_data($insert_message,"email_outlook_lien");
         endif;
         
         
         array_push($id_type_demande,6);
       $data["is_new_demande_message"]=TRUE;
     
     endif;

     //J'enregister la relation id_demande, id_personne, id_bien
       /* if($this->request->getVar("id_bien")>0
            ||$this->request->getVar("id_demandeur")>0
            ||$this->request->getVar("rel_personne_bien")>0):
        $data_relation["id_demande"]=$new_id;
        if($this->request->getVar("id_bien")>0):
         $data_relation["id_bien"]=$this->request->getVar("id_bien");
        endif;
       
         $data_relation["id_personne"]=$id_entity_personne;
      
        
        
        endif;*/
        
     $params_outlook["id_message"]=$id_message;
      $compte=0;
 $db=db_connect();

     if($is_information):
         $params_outlook["id_demande"]=$new_id;
         if(isset($is_cloturer)&&$is_cloturer): else:
         
       $this->myoutlook_lib->mails_notification_new($params_outlook);
     
         endif;
         $data["id"]=$new_id;
         $compte=$compte+1;
          $this->historique->set_historique_general($id_type_demande,$new_id,$id_entity_personne,$id_entity_bien,$id_message);

          //On met à jour id_message avec id_demande
        

          if(isset($id_message)&&$id_message>0)
          {
            $builder=$db->table("document_upload_lien");
            $builder->where("id_message",$id_message);
            $data_document_lien["id_demande"]=$new_id;
            $builder->update($data_document_lien);
          } 
         
         

     endif;
      
      
     if($is_accompagnement):
         $params_outlook["id_demande"]=$new_id_accompagnement;
         
          $this->myoutlook_lib->mails_notification_new($params_outlook);
         
         
         $data["id_accompagnement"]=$new_id_accompagnement;
         $compte=$compte+1;
          $this->historique->set_historique_general($id_type_demande,$new_id_accompagnement,$id_entity_personne,$id_entity_bien,$id_message);
         
         
          if(isset($id_message)&&$id_message>0)
          {
          $builder=$db->table("document_upload_lien");
          $builder->where("id_message",$id_message);
          $data_document_lien["id_demande"]=$new_id_accompagnement;
          $builder->update($data_document_lien);
          }
     endif;
      
     if($is_visite):
         $params_outlook["id_demande"]=$new_id_visite;
         
         $this->myoutlook_lib->mails_notification_new($params_outlook);
         
         $data["id_visite"]=$new_id_visite;
         $compte=$compte+1;

        $this->historique->set_historique_general($id_type_demande,$new_id_visite,$id_entity_personne,$id_entity_bien,$id_message);
       if(isset($id_message)&&$id_message>0)
          {
            $builder=$db->table("document_upload_lien");
            $builder->where("id_message",$id_message);
            $data_document_lien["id_demande"]=$new_id_visite;
            $builder->update($data_document_lien);
          }
      endif;
        
     $id_encodage=$this->ban_crud_model->insert_data($data_encodage,'encodage');
     $data_en["id_encodage"]=$id_encodage;

     
         if($is_information):
             $where_en["id_demande"]=$new_id;
             $this->ban_crud_model->update_data("demande",$data_en,$where_en);
         endif;
         if($is_accompagnement):
              $where_en["id_demande"]=$new_id_accompagnement;
             $this->ban_crud_model->update_data("demande",$data_en,$where_en);
         endif;
         if($is_visite):
             $where_en["id_demande"]=$new_id_visite;
             $this->ban_crud_model->update_data("demande",$data_en,$where_en);
         endif;


     $data["compte"]=$compte;
      
        //historique
        array_push($id_type_demande,1);
        //interface web importation message
        
        if($interface==="web"||$interface==="renolution")
        {
             $data_message_transform["body_preview"]=nl2br($this->request->getVar("descriptif_demande"));
             $data_message_transform["body_content"]=nl2br($this->request->getVar("descriptif_demande"));

             if(empty($this->request->getVar("nom_demande")))
             {
                 $subject="Demande provenant de notre formulaire de contact";
             }
             else
             {
                 $subject=$this->request->getVar("nom_demande");
             }

             $data_message_transform["sender_mail"]=$this->request->getVar("email_personne");
             $data_message_transform["to_mail"]="info@homegrade.brussels";
             $data_message_transform["is_homegrade"]=1;
             $data_message_transform["orig"]="zebd";
             $data_message_transform["draft"]=0;
             //On recuper les donnes id_deposit
             $deposits=$this->ban_crud_model->read_data("re_deposit","gf_date_created","id_deposit=$id_deposit");

             if(isset($deposits[0]->gf_date_created)):
                 $date_created_datetime=$deposits[0]->gf_date_created;
                 $date_send_datetime=$deposits[0]->gf_date_created;
                 $date_received_datetime=$deposits[0]->gf_date_created;
             else:
                 $date_created_datetime=date("Y-m-d H:i:s");
                 $date_send_datetime=date("Y-m-d H:i:s");
                 $date_received_datetime=date("Y-m-d H:i:s");
             endif;	


             $data_message_transform["created_datetime"]=$date_created_datetime;
             //$data_message_transform["send_datetime"]=$date_send_datetime;
             $data_message_transform["received_datetime"]=$date_received_datetime;

             $data_message_transform["send_name"]=$this->request->getVar("prenom_personne_no_obligatoire").' '.$this->request->getVar("nom_personne_no_obligatoire");

             if($is_information):

                 $data_message_transform["body_preview"]=nl2br($this->request->getVar("descriptif_demande"));
                 $data_message_transform["body_content"]=nl2br($this->request->getVar("descriptif_demande"));

                 $data_message_transform["subject"]="#Ref:$new_id# $subject ";
                 $new_id_message=$this->ban_crud_model->insert_data($data_message_transform,"email_outlook");

                 $data_insert_liaison["id_email"]=$new_id_message;
                 $data_insert_liaison["id_demande"]=$new_id;
                 $this->ban_crud_model->insert_data($data_insert_liaison,"email_outlook_lien");

             endif;


             
             if($is_accompagnement):

                 $mess=$this->request->getVar("accompagnement_descriptif_demande");
                 if(!empty($mess))
                 {
                 
                     $data_message_transform["body_preview"]=nl2br($this->request->getVar("accompagnement_descriptif_demande"));
                     $data_message_transform["body_content"]=nl2br($this->request->getVar("accompagnement_descriptif_demande"));
                 }
                 else
                 {
                     $data_message_transform["body_preview"]=nl2br($this->request->getVar("descriptif_demande"));
                     $data_message_transform["body_content"]=nl2br($this->request->getVar("descriptif_demande"));
                 }


                 if(empty($this->request->getVar("accompagnement_nom_demande")))
                 {
                     $subject="Demande provenant de notre formulaire de contact";
                 }
                 else
                 {
                     $subject=$this->request->getVar("accompagnement_nom_demande");
                 }

                 $data_message_transform["subject"]="#Ref:$new_id_accompagnement# $subject ";
                 $new_id_message_accompagnement=$this->ban_crud_model->insert_data($data_message_transform,"email_outlook");

                 $data_insert_liaison["id_email"]=$new_id_message_accompagnement;
                 $data_insert_liaison["id_demande"]=$new_id_accompagnement;
                 $this->ban_crud_model->insert_data($data_insert_liaison,"email_outlook_lien");
                 
             endif;
             if($is_visite):

                 $mess=$this->request->getVar("visite_descriptif_demande");
                 if(!empty($mess))
                 {
                 
                     $data_message_transform["body_preview"]=nl2br($this->request->getVar("visite_descriptif_demande"));
                     $data_message_transform["body_content"]=nl2br($this->request->getVar("visite_descriptif_demande"));
                 }
                 else
                 {
                     $data_message_transform["body_preview"]=nl2br($this->request->getVar("descriptif_demande"));
                     $data_message_transform["body_content"]=nl2br($this->request->getVar("descriptif_demande"));
                 }

                 if(empty($this->request->getVar("visite_nom_demande")))
                 {
                     $subject="Demande provenant de notre formulaire de contact";
                 }
                 else
                 {
                     $subject=$this->request->getVar("visite_nom_demande");
                 }

                 $data_message_transform["subject"]="#Ref:$new_id_visite# $subject ";
                 $new_id_message_visite=$this->ban_crud_model->insert_data($data_message_transform,"email_outlook");

                 $data_insert_liaison["id_email"]=$new_id_message_visite;
                 $data_insert_liaison["id_demande"]=$new_id_visite;
                 $this->ban_crud_model->insert_data($data_insert_liaison,"email_outlook_lien");

             endif;
             
        }
        //interface web importation des fichiers
        if(!is_null($urls_file))
        {
          foreach($urls_file as $url_file)
          {
              $content_file=file_get_contents($url_file);

              //obtenir le nom du fichier
              $explode_name=explode("/",$url_file);
              $count=count($explode_name);
              $name_file=date("ymsHis").'_'.$explode_name[$count-1];

              //Put content
              $path=PATH_DOCU_DEMANDE;

              file_put_contents($path.$name_file,$content_file);

              $insert_file["name"]=$name_file;
              $insert_file["url_file"]=$name_file;
              $insert_file["id_user"]=session()->get('loggedUserId');


              if($is_information):


                    $document_lien["id_document"]=$this->ban_crud_model->insert_data($insert_file,"document_upload");

                    $document_lien["id_demande"]=$new_id;

                    if(isset($new_id_message))
                    {
                        $document_lien["id_message"]=$new_id_message;
                    }

                    $this->ban_crud_model->insert_data($document_lien,"document_upload_lien");

                 

              endif;
              if($is_accompagnement):

                $document_lien["id_document"]=$this->ban_crud_model->insert_data($insert_file,"document_upload");

                $document_lien["id_demande"]=$new_id_accompagnement;
                
                if(isset($new_id_message_accompagnement))
                {
                    $document_lien["id_message"]=$new_id_message_accompagnement;
                }

                $this->ban_crud_model->insert_data($document_lien,"document_upload_lien");



              endif;
              if($is_visite):

                $document_lien["id_document"]=$this->ban_crud_model->insert_data($insert_file,"document_upload");

                $document_lien["id_demande"]=$new_id_visite;
                
                if(isset($new_id_message_visite))
                {
                    $document_lien["id_message"]=$new_id_message_visite;
                }

                $this->ban_crud_model->insert_data($document_lien,"document_upload_lien");


              endif;

          }
        }

      

        if(($interface==="web"||$interface==="renolution")&&!is_null($id_deposit))
        {
           
           $where_deposit["id_deposit"]=$id_deposit;
           $data_deposit["is_deleted"]=1;
           $this->ban_crud_model->update_data("re_deposit",$data_deposit,$where_deposit);
        }

        return view($this->module . '\interface/permanence_confirm', $data);

        
       // $this->load->view("interface/permanence_confirm",$data);

     /*   if($interface==="web"||$interface==="renolution")
        {

             $session_destroy=array(
                     "id_deposit",
                     "id_personne",
                     "prenom",
                     "nom",
                     "email",
                     'email2',
                     'telephone',
                     'telephone2',
                     'id_bien',
                     'id_type_demande',
                     'subject',
                     'comment',
                     'urls_file'


             );*/
         /*    foreach($session_destroy as $index_destroy)
             {
                 if($this->session->userdata($index_destroy))
                 {
                      $this->session->unset_userdata($index_destroy);
  
                 }
             }*/
         //}

      

      
        if($interface)
        {
         //$this->session->unset_userdata("interface");
        }
        
        if(isset($id_deposit)&&$id_deposit>0)
        {
            $DWModel = new \DemandeWeb\Models\DemandeModel();
            $DWModel->DepositDelete($id_deposit);
        }
       


        flush();
        
    }

    public function liste_document_ajouter_demande($id_demande)
    {
        $orderBy=$this->componentOrderBy->getOrderBy("document_upload.date_created",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);

        $fieldsOrder=
        [
      
            "document_upload.date_created"=>["Date d'upload",true],
            "document_upload.name"=>["Nom du fichier",true],
            "document_upload.commentaire"=>["Commentaire",true],
            "document_upload.id_type"=>["Type",true],
            "demande.nom"=>["Demande liée",true]
           
           

        ];
        $this->datas->id_demande=$id_demande;
        $this->datas->documents=$this->documentModel->getListDocumentAjouterDemande($this->request,$orderBy,$orderDirection,$id_demande);
        
        $this->datas->pager=$this->documentModel->pager;
        $this->datas->nbDocuments= $this->datas->pager->getTotal();
        $this->datas->fields= $this->documentModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des documents";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);
        $this->datas->type_document=$this->documentModel->get_liste_type_document();
        $this->datas->module=$this->module;


        return view('DocumentUpload\liste_document_ajouter_demande', (array) $this->datas );
    }

    public function liste_document_gerer_demande($id_demande)
    {
        $orderBy=$this->componentOrderBy->getOrderBy("document_upload.date_created
        ",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);

        $fieldsOrder=
        [
      
            "document_upload.date_created"=>["Date d'upload",true],
            "document_upload.name"=>["Nom du fichier",true],
            "document_upload.commentaire"=>["Commentaire",true],
            "document_upload.id_type"=>["Type",true],
            "demande.nom"=>["Demande liée",true]
           
           

        ];
        $this->datas->id_demande=$id_demande;
        $this->datas->documents=$this->documentModel->getListDocumentGererDemande($this->request,$orderBy,$orderDirection,$id_demande);
        $this->datas->pager=$this->documentModel->pager;
        $this->datas->nbDocuments= $this->datas->pager->getTotal();
        $this->datas->fields= $this->documentModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des documents";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);
        $this->datas->type_document=$this->documentModel->get_liste_type_document();
        $this->datas->module=$this->module;


        return view('DocumentUpload\liste_document_gerer_demande', (array) $this->datas );
    }

    public function set_ajouter_document_demande($id_document,$id_demande=0)
    {
        if($id_document>0&&$id_demande>0)
        {

            $this->documentModel->set_ajouter_document_demande($id_document,$id_demande);
            return "<span class='text-success'><b>Le document a été ajouté</b></span>";
        }
        else
        {
           return  "<span class='text-danger'><b>Erreur! Impossible d'ajouter le document</b></span>";
        }
    }

    public function attach_message($id_demande,$id_message)
    {
        $db=db_connect();

        //On vérifie si le message n'est pas déjà attaché
        $builder=$db->table("email_outlook_lien");
        $builder->where("id_email",$id_message);
        $builder->where("id_demande",$id_demande);

        $attachement=$builder->get()->getRow();

        if(!empty($attachement))
        {
            $message="Le message n°$id_message est déjà dans le fil de la discussion de la demande n°$id_demande";
            return redirect()->to(base_url()."demande/fiche/$id_demande")->with("danger",$message);
        }
        else
        {
            //debugd();
            //on insert le message
            $builder=$db->table("email_outlook_lien");
            $data_insert['id_email']=$id_message;
            $data_insert["id_demande"]=$id_demande;
            //debugd($data_insert);
            $builder->insert($data_insert);

             //ON vérifie si le message a des documents joints, si c'est le cas on attache
            $builder=$db->table("document_upload_lien");
            $builder->where("id_message",$id_message);
            $documents=$builder->get()->getResult();
            if(!empty($documents))
            {
                $data_update_demande["id_demande"]=$id_demande;
                $builder=$db->table("document_upload_lien");
                $builder->where("id_message",$id_message);
                $builder->update($data_update_demande);
            }
            
            

            //notification ----  ICI IL FAUT ACHEVER POUR ENVOYER LES MAILS AUTOMATIQUES
             //Il faut voir maintenant si le consiller qui gère la demande est en situation de messagerie automatique
            
            
            $ms=$this->ban_crud_model->read_data("email_outlook","id,sender_mail,subject","id_primary=$id_message");
            $this->myoutlook_lib->send_messagerie_automatique($id_demande,$ms[0]->sender_mail,$ms[0]->subject);
                                            
                   //notification de mise à jour                      
             $params_outlook["id_demande"]=$id_demande;
             $params_outlook["id_message"]=$id_message;
            

             $this->myoutlook_lib->mails_notification($params_outlook);

            //On récupere $id_type_demande
            $builder=$db->table("demande");
            $builder->where("id_demande",$id_demande);
            $demande=$builder->get()->getRow();

            $id_type_demande=$demande->id_type_demande;

            $this->historique->set_historique_general($id_type_demande,$id_demande,$id_contact=0,$id_bien=0,$id_message);

            //on retourne sur la demande avec le message comme quoi le message est attaché
            $message="Le message n°$id_message a été ajouté au fil de la discussion de la demande n°$id_demande";
            return redirect()->to(base_url()."demande/fiche/$id_demande")->with("success",$message);
        }
       

    }

    public function associe_message_demande($id_message=NULL)
    {
        $orderBy=$this->componentOrderBy->getOrderBy("date",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);



        $fieldsOrder=
        [
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

        $demandes=$this->demandeModel->getListDemandes($this->request,$orderBy,$orderDirection);
        $pager=$this->demandeModel->pager;
 


        $this->datas->demandes = $demandes;
        $this->datas->pager=$pager;
        $this->datas->nbDemandes= $pager->getTotal();
        $this->datas->itemSearch =$this->request->getVar("itemSearch");
        $this->datas->titleView = "Ajouter le message #$id_message à une demande";
        $this->datas->demandeModel = $this->demandeModel;
        $this->datas->id_message=$id_message;

        $this->datas->statut_demandes=$this->demandeModel->statut_demande();
        $this->datas->id_statut_demande=$this->request->getVar("statut_demande");
        $this->datas->mes_demandes=$this->request->getVar("mes_demandes");
        $this->datas->homegrade=$this->request->getVar("homegrade");

        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);


       
        return view('Demande\view-message-associe-demande', (array) $this->datas);
    }

    public function change_statut_demande($id_demande)
    {
       // debugd($this->request->getVar());
        if($id_demande>0&&$this->request->getVar("id_demande_statut"))
        {
            $demande=$this->demandeModel->getDemande($id_demande);
            $demande_statut_old=$demande->demande_statut_label;
            $this->demandeModel->set_statut_demande($id_demande,$this->request->getVar("id_demande_statut"),$demande_statut_old);
            if($this->request->getVar("id_demande_statut")==6)
            {
                
                $EnqueteLibrary = new \Enquete\Libraries\EnqueteLibrary();
                $EnqueteLibrary->DemandeClose($id_demande);
            }
        }


        return $this->get_statut_demande_select($id_demande,true);
    }

    public function get_statut_demande_select($id_demande,$is_notif=false)
    {
        $data["statut_demande"]=$this->demandeModel->statut_demande();
        $data["demande"]=$this->demandeModel->getDemande($id_demande);
        $data["is_notif"]=$is_notif;

        return view("Demande\get_statut_demande_select",$data);
    }

}