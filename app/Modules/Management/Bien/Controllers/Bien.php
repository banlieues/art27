<?php

namespace Bien\Controllers;

use Base\Controllers\BaseController;

use Layout\Libraries\LayoutLibrary;

use Bien\Models\BienModel;

use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;

use Components\Libraries\ComponentOrderBy;

use Demande\Models\DemandeModel;

use Components\Models\BanCrudModel;

class Bien extends BaseController
{
    public function __construct()
    {
       


        $this->bienModel = new BienModel();
        $this->context = "bien";

        $request=$this->request;

        $this->dataView=new DataViewConstructor();
        $this->componentOrderBy=new ComponentOrderBy();
      

        parent::__construct(__NAMESPACE__);

        $layout_l = new LayoutLibrary();


        $this->datas->theme = $layout_l->getThemeByRef($this->context);
        $this->datas->context = $this->context;
        $this->viewpath = $this->module . "\Views\/";  

        $this->demandeModel = new DemandeModel();

        $this->ban_crud_model=new BanCrudModel();
       // $path="Bien\Views\/";


    }



    public function listBien()
    {
      
      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("biens"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

      /*  $segment="bien";

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

        $orderBy=$this->componentOrderBy->getOrderBy("adresse_fr",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("ASC",$this->request);

        $fieldsOrder=
        [
            
            "delete"=>[null,false,"biens_d"],
            
            "adresse_fr"=>["Adresse FR",true],
            "adresse_nl"=>["Adresse NL",true],
            "bt"=>["Boîte",true],
            "etage_logement"=>["Etage",true],
            "type"=>["Type de bien",true],
            "nb_demande"=>["Nb demande",true],
            "contact_associee"=>["Demandeurs associés",true],
           
         
           
      
           

        ];


      /*  $fieldsOrder=
        [
            
            "value1"=>[null,false,"biens_d"],
            
            "typepart"=>["Type",true],
            "nom,prenom,nom_court_institution"=>["Bien",true],
            "nom_court_institution"=>["Institution",true],
            "username"=>["Géré par l'utilisateur",true,"utilisateur_a"],
            "nb_inscription"=>["Nombre d'inscriptions",true,"registrations_r"],
            "codepostal"=>["Code postal",true],
            "localite"=>["Localité",true],
            "age"=>["Âge",true],
           
            "inscrire"=>[null,false,"registrations_d"]
           

        ];*/

        
        $this->datas->biens=$this->bienModel->getListBien($this->request,$orderBy,$orderDirection);
      
        $this->datas->pager=$this->bienModel->pager;
        $this->datas->nbBiens= $this->datas->pager->getTotal();
        $this->datas->fields= $this->bienModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->itemSearch=$this->request->getVar("itemSearch");
        $this->datas->titleView="Liste des biens";
        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);

        return view($this->viewpath.'list-bien', (array) $this->datas );
    }

    public function fiche($id_bien=NULL)
    {


      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("biens"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();

        //ETAPE I : On récupére les élements qui constituent la fiche
        $components=$dataView->getComponents("bien");

        if(empty($components))
        {
            return "Aucune page définie pour les biens";
        }

        //ETAPE 2 : on récupérer les données du bien en cours
        $bien=$this->bienModel->bien($id_bien);
        
       // $user=$this->inscriptionModel->get_user_by_id_bien($id_bien);

        if(!empty($bien)){
          
           if(!empty(trim($bien->adresse_fr)))
           {
                $titleView=$bien->adresse_fr; 
           }
           else
           {
                $titleView=  "<i>Adresse inconnue</i>"; 
           }
                 
                
              
            
           
        }else{
            $titleView="Fiche non trouvée";
  
        }

        $this->datas->bien = $bien;
        $this->datas->fields = $this->bienModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="read";
        $this->datas->validation=NULL;
        $this->datas->id_bien=$id_bien;
        $this->datas->components=$components;
        $this->datas->viewpath=$this->viewpath;
       
        $this->datas->demande=$this->bienModel->get_demande_by_bien($id_bien);
        //debug( $this->datas->demande);

        $this->datas->bien_caracteristique=$this->bienModel->bien_caracteristique($id_bien);

       
       
        return view($this->viewpath.'view-bien-fiche', (array) $this->datas);
    }
    
    public function get_bien_data($id_bien)
    {
        echo json_encode(
			$this->bienModel->find($id_bien)
		 );
	       flush();
	       exit();  
    }

    public function associe_demande($id_bien=NULL)
    {
        $orderBy=$this->componentOrderBy->getOrderBy("date",$this->request);
        $orderDirection=$this->componentOrderBy->getOrderDirection("DESC",$this->request);



        $fieldsOrder=
        [
            "coche"=>[NULL,false],
            "date"=>["Date",true],
            "id_demande"=>["N°",true],
            "type"=>["Type",true],
            "statut"=>["Statut",true],
            "nom_createur"=>["Créateur",true],
            "nom_encharge"=>["En charge",true],
            "sujet"=>["Sujet",true],
            "contact_associee"=>["Demandeur",true],
       

        ];

        $demandes=$this->demandeModel->getListDemandes($this->request,NULL,NULL,$id_bien);
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

        $this->datas->getTh=$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request);


      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("biens"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

      

        //ETAPE 2 : on récupérer les données du bien en cours
        $bien=$this->bienModel->find($id_bien);
        
       // $user=$this->inscriptionModel->get_user_by_id_bien($id_bien);

      
          
       if(!empty(trim($bien->adresse_fr)))
       {
            $titleView=$bien->adresse_fr; 
       }
       else
       {
            $titleView=  "<i>Adresse inconnue</i>"; 
       }

        $this->datas->bien = $bien;
        $this->datas->fields = $this->bienModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->typeDataView="associe";
        $this->datas->validation=NULL;
        $this->datas->id_bien=$id_bien;
        $this->datas->viewpath=$this->viewpath;
   

       
       
        return view($this->viewpath.'view-bien-associe-demande', (array) $this->datas);
    }

    public function new()
    {


      /*  if(!$this->autorisationManager->is_gestion_entity_autorise("biens"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();

        //ETAPE I : On récupére les élements qui constituent la fiche
        $bien_component=$dataView->getOneComponent("bien","bien");
        $bien_caracteristique_component=$dataView->getOneComponent("bien","bien_caracteristique");

      
       
       // $user=$this->inscriptionModel->get_user_by_id_bien($id_bien);

      
            $titleView="Nouveau bien";
  
        

        $this->datas->bien = null;
        $this->datas->fields = $this->bienModel->getFields();
        $this->datas->context= $this->context;
        $this->datas->titleView=$titleView;
        $this->datas->dataView=$dataView;
        $this->datas->typeDataView="new_form";
        $this->datas->validation=NULL;
        $this->datas->id_bien=0;
        $this->datas->viewpath=$this->viewpath;
       


     
        $this->datas->bien_component=$bien_component;
        $this->datas->bien_caracteristique_component=$bien_caracteristique_component;

       
       
        return view($this->viewpath.'view-bien-form-insert', (array) $this->datas);
    }

    public function ModelisationBien($id_bien=NULL,$validation=NULL)
    {
    /* if(!$this->autorisationManager->is_autorise("modelisation_a"))
        {
            header("Location:".base_url("autorisation/no_autorisation"));
        }*/

        $dataView=new DataViewConstructor();


        $components=$dataView->getComponents("bien");
        if(empty($components))
        {
            return "Aucune page définie pour les biens";
        }

        if(!is_null($id_bien)){
            $typeDataView="update";
            $bien=$this->bienModel->getBien($id_bien);
            if(!empty($bien)):
                $titleView="Bien de $bien->nom $bien->prenom pour $bien->idact ".$bien->titre;
            else:
                $titleView="Fiche non trouvée";
            endif;    
        }

        if(is_null($id_bien)||$id_bien==0)
        {
            $bien=NULL;
            $typeDataView="create";
            $titleView="Créer une nouvelle fiche de bien";
        }

        if(!empty($bien))
        {
            $id_bien=$bien->id_bien;
            $bien=$this->bienModel->find($id_bien);

        }
        else
        {
            $bien=NULL;
            $id_bien=NULL;
        }

        $this->datas->bien = $bien;
        $this->datas->fields= $this->bienModel->getFields();
        $this->datas->context=  "modelisation";
        $this->datas->titleView= "Modélisation de la fiche de bien";
        $this->datas->dataView= $dataView;
        $this->datas->typeDataView= "modelisation";
        $this->datas->validation= $validation;
        $this->datas->id_bien= $id_bien;
        $this->datas->bien= $bien;
        $this->datas->id_bien= $id_bien;
        $this->datas->components= $components;
        $this->datas->viewpath=$this->viewpath;
   
        
       
        


        return view($this->viewpath.'view-bien-fiche', (array) $this->datas);
    }

    public function viewBienInscription($id_bien=NULL)
    {

        if(!$this->autorisationManager->is_gestion_entity_autorise("biens"))
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
            "nom,prenom,nom_court_institution"=>["Bien",true],
            "username"=>["Géré par l'utilisateur",true,"utilisateur_a"],
            "age"=>["Âge",true],
            "alimentation"=>['<i class="'.icon("food").'"></i>',true],
            "statutsuivi"=>["Statut",true],
            "statut_payement"=>["Payement",true],
         
            "solde"=>["Solde",FALSE],
            "count_confirmation"=>[NULL,FALSE],
           
           
        ];

        $bien=$this->bienModel->find($id_bien);
        $user=$this->inscriptionModel->get_user_by_id_bien($id_bien);

        if(!empty($bien)):
            if(empty($bien->nom)&&empty($bien->prenom))
            {
                $titleView=$bien->nom_court_institution;
            }
            else
            {
                $titleView=$bien->nom. " ".$bien->prenom;
            }
        else:
            $titleView="Fiche non trouvée";
            
        endif;  
        $inscriptions=$this->inscriptionModel->getListInscription($this->request,0,$id_bien,$orderBy,$orderDirection);
        $pagerInscription=$this->inscriptionModel->pager;

        return view('biens/view-bien-inscription', [
            "bien" => $bien,
            "inscriptions"=>$inscriptions,
            "pagerInscription"=>$pagerInscription,
            "nbInscriptions"=>$pagerInscription->getTotal(),
            "fields"=> $this->bienModel->getFields(),
            "context"=> $this->context,
            "statutInscriptions"=>$this->inscriptionModel->getListStatutInscription(),
            "statutPayements"=>$this->inscriptionModel->getListStatutPayement(),
            "statutSuivi"=>$this->request->getVar("statutSuivi"),
			"titleView"=>$titleView,
            "id_bien"=>$id_bien,
            "user"=>$user,
            "getTh"=>$this->componentOrderBy->orderTh($fieldsOrder,$orderBy,$orderDirection,$this->request),
            "statutSuivi"=>$this->request->getVar("statutSuivi"),
            "itemSearch"=>$this->request->getVar("itemSearch"),
        ]);
    }


    public function formBien($id_bien=NULL,$validation=NULL)
    {
     
        $dataView=new DataViewConstructor();

        $components=$dataView->getComponents("bien");
        if(empty($components))
        {
            return "Aucune page définie pour les registrations";
        }

        if(!is_null($id_bien)){
            $typeDataView="update";
            $bien=$this->bienModel->find($id_bien);
            if(!empty($bien)):
                
                    $titleView=$bien->nom_bien. " ".$bien->prenom_bien;
                
            else:
                $titleView="Fiche non trouvée";
            endif;    
        }
        if(is_null($id_bien)||$id_bien==0)
        {
            $bien=NULL;
            $typeDataView="create";
            $titleView="Créer une nouvelle fiche de bien";
        }
     
     
            $this->datas->bien = $bien;
            $this->datas->fields= $this->bienModel->getFields();
            $this->datas->context= $this->context;
			$this->datas->titleView=$titleView;
            $this->datas->dataView=$dataView;
            $this->datas->typeDataView=$typeDataView;
            $this->datas->validation=$validation;
            $this->datas->id_bien=$id_bien;
            $this->datas->components=$components;
            $this->datas->bien_profil=$this->bienModel->bien_profil($id_bien);
            $this->datas->demande=$this->bienModel->get_demande_by_bien($id_bien);

        return view($this->viewpath.'view-bien-fiche', (array) $this->datas);
        return view('biens/view-bien-fiche', [
            
        ]);
    }

    public function save($component="bien")
    {
       //list of indexes of form
        $session = \Config\Services::session();
        $indexes=$this->request->getVar("indexesForm");

        $dataView=new DataViewConstructor();
        $rules=$dataView->getRules($indexes,$this->bienModel->getFields());
    
        if (!$this->validate($rules)&&!empty($rules)) 
        {
            if($this->request->getVar('typeDataView')=="create")
            {
                echo $this->formBien(NULL,$this->validator);
            } 
            else 
            {
                echo $this->formBien($this->request->getVar('id_bien'),$this->validator);
            }          
        } 
        else 
        {

            switch ($component)
            {
                case "bien":
                    $id_bien_save= $this->bienModel->saveDataDelivreBien($this->request->getVar("indexesForm"),$this->request->getVar(),$this->request->getVar("id_entity"));
                    break;

                case "bien_caracteristique":
                    //debug($this->request->getVar());
                        $id_bien_save= $this->bienModel->saveDataDelivreBienCaracteristique($this->request->getVar("indexesForm"),$this->request->getVar(),$this->request->getVar("id_entity"));
                        break;


                case "demande":
                    //debug($this->request->getVar());
                        $id_bien_save= $this->bienModel->saveDataDelivreDemande($this->request->getVar("indexesForm"),$this->request->getVar(),$this->request->getVar("id_entity"));
                        break;
            }


            //treatment of data  
         /*       $id_bien_save=$dataView->saveData(
                $indexes,
                $this->request->getVar(),
                $this->bienModel->getFields(),
                $this->bienModel->getTable(),
                $this->request->getVar("id_bien")
            );  */
            
            if($this->request->getVar('typeDataView')=="create")
            {
                //$id_bien=$this->bienModel->insertData($data);
                $message= 'La fiche de bien a été créée';
            } 
            else 
            {
                //$id_bien=$this->bienModel->updateData($data,$this->request->getVar("id_bien"));
                $message= 'La fiche a été modifiée';
            }
            
            return redirect()->to(base_url()."/bien/fiche/$id_bien_save")->with("success",$message);
        }
    }

    public function save_new()
    {
       //list of indexes of form
        $session = \Config\Services::session();
        $indexes=$this->request->getVar("indexesForm");

        $dataView=new DataViewConstructor();
        $rules=$dataView->getRules($indexes,$this->bienModel->getFields());
    
        if (!$this->validate($rules)&&!empty($rules)) 
        {
                   
        } 
        else 
        {

           
                    $id_bien_save= $this->bienModel->saveDataDelivreNewBien($this->request->getVar("indexesForm"),$this->request->getVar(),0);
                

              


          
                //$id_bien=$this->bienModel->insertData($data);
                $message= 'La fiche de bien a été créée';
           
            
            return redirect()->to(base_url()."/bien/fiche/$id_bien_save")->with("success",$message);
        }
    }


    public function save_associe_demande()
    {
        $post=$this->request->getVar();

    
        if(isset($post["id_demande"])&&$post["id_demande"]>0)
        {
            $this->bienModel->associe_demande($post);

            return redirect()->to(base_url()."/bien/fiche/".$post["id_bien"])->with("success","Le bien est associé à la demande");

        }
        else
        {
            return redirect()->back()->with("danger","Vous n'avez pas choisi de demande à associer");

        }
    }

    public function save_modelisation()
    {
        $posts=$this->request->getVar();
        
        $dataView=new DataViewConstructor();
        $dataGeneratorModel = new DataViewConstructorModel();
        $entityParams=$dataGeneratorModel->getOneEntities("bien");


        $dataView->setComponents("bien",$posts);
        
        $message= 'Le modèle de la fiche '.$entityParams->label.' a été enregistré';

        return redirect()->to(base_url()."/modelisation")->with("success",$message);
       

    }

    public function verif_doublon_adresse()
	{
	    $adresse_fr=$this->request->getVar("adresse_fr");
	    $adresse_nl=$this->request->getVar("adresse_nl");

        //debug($this->request->getVar());
	    
	    $table="bien";

	    $convert=str_replace(array(",","'",'"'," "),"",$adresse_fr);
	    $convert_nl=str_replace(array(",","'",'"'," "),"",$adresse_nl);

        $db=db_connect();
        $builder=$this->db->table("bien");

        if(empty(trim($adresse_fr))&&empty(trim($adresse_nl)))
		{

			echo "<i>Je n'ai pas trouvé de biens correspondants. Car vous n'avez pas d'indiquer d'adresse dans adresse Fr ou adresse NL!</i>";
            return TRUE;
		}
	
		if(!empty(trim($adresse_fr))||!empty(trim($adresse_nl)))
		{
            

            $items=explode(" ",$adresse_fr);
            $fieldSearchs=["adresse_fr"];

            $builder->groupStart();
                $builder->groupStart();
                    foreach($items as $item):
                        $builder->groupStart();
                        foreach($fieldSearchs as $fieldSearch):
                            $builder->orLike($fieldSearch,$item);
                        endforeach;
                        $builder->groupEnd();
                    endforeach;
                $builder->groupEnd();
            $builder->groupEnd();

            $items=explode(" ",$adresse_nl);
            $fieldSearchs=["adresse_fr"];
            $builder->orGroupStart();
                $builder->groupStart();
                    foreach($items as $item):
                        $builder->groupStart();
                        foreach($fieldSearchs as $fieldSearch):
                            $builder->orLike($fieldSearch,$item);
                        endforeach;
                        $builder->groupEnd();
                    endforeach;
                $builder->groupEnd();
            $builder->groupEnd();


			/*$where="(replace(replace(replace(adresse_fr,',',''),' ',''),'\'','')='$convert')";
			$where.=" OR ";
			$where.="(replace(replace(replace(adresse_fr,',',''),' ',''),'\'','')='$convert_nl')";*/


		}

		if(!empty(trim($adresse_fr))||empty(trim($adresse_nl)))
		{

            $items=explode(" ",$adresse_fr);
            $fieldSearchs=["adresse_fr"];

            $builder->groupStart();
                $builder->groupStart();
                    foreach($items as $item):
                        $builder->groupStart();
                        foreach($fieldSearchs as $fieldSearch):
                            $builder->orLike($fieldSearch,$item);
                        endforeach;
                        $builder->groupEnd();
                    endforeach;
                $builder->groupEnd();
            $builder->groupEnd();

			//$where="(replace(replace(replace(adresse_fr,',',''),' ',''),'\'','')='$convert')";
			
		}

		if(empty(trim($adresse_fr))||!empty(trim($adresse_nl)))
		{
            $items=explode(" ",$adresse_nl);
            $fieldSearchs=["adresse_fr"];

            $builder->groupStart();
                $builder->groupStart();
                    foreach($items as $item):
                        $builder->groupStart();
                        foreach($fieldSearchs as $fieldSearch):
                            $builder->orLike($fieldSearch,$item);
                        endforeach;
                        $builder->groupEnd();
                    endforeach;
                $builder->groupEnd();
            $builder->groupEnd();

			//$where.="(replace(replace(replace(adresse_fr,',',''),' ',''),'\'','')='$convert_nl')";
			
		}

	
	    

	    
	    
	    $builder->select
        (   
            "
                bien.id_bien,
                bien.adresse_fr,
                bien.adresse_nl,
                bien.id_type,
                liste_bien_type.label as type,
                etage_logement,
                bt,id_chauffage,
                id_nombre_logement
            "
        );
	   // $left=array();

	    
	    //array_push($left,$left_condition);


       // $builder->where($where);

        $builder->join("liste_bien_type","liste_bien_type.id=bien.id_type","left");

        $bienssql=$builder->get()->getResult();


	    //$bienssql=$this->ban_crud_model->read_data($table,$select,$where,$left);
	    
	   
	    if(isset($bienssql[0]->id_bien)):
		 $biens="<b>J'ai trouvé:<b><br>";
        $data["biens"]=$bienssql;
         echo view("Bien\doublon_bien",$data);
		
	    else:
	    echo "<i>Je n'ai pas trouvé de biens qui pourraient correspondre à cette adresse!</i>";
	    endif;
	}


    public function search_bien_by_id_contact($id_contact,$id_contact_profil)
    {


        $biens=$this->bienModel->search_bien_by_id_contact($id_contact);
        $contact=$this->bienModel->contact_nom($id_contact);
       // debug($biens);

        if(!empty($biens)):
            $data["biens"]=$biens;
            echo '<div class="my-4">';
            echo "<b>J'ai trouvé ".count($biens)." bien(s) lié(s) à $contact </b>";
            echo view("Bien\doublon_bien",$data);
            echo '</div>';
           
           else:
            echo '<div class="my-4">';
           echo "<i>Je n'ai pas trouvé de biens qui pourraient correspondre à $contact!</i>";
           echo '</div>';
        endif;
    }


    public function get_bien_by_contact($id_contact,$id_contact_profil)
    {
        return $this->bienModel->search_bien_by_id_contact($id_contact);
    }
    
    public function search_bien_by_id_contact_no_mis_en_forme($id_contact,$id_contact_profil)
    {


        $biens=$this->bienModel->search_bien_by_id_contact($id_contact);
        $contact=$this->bienModel->contact_nom($id_contact);
       // debug($biens);
        $view=null;
        if(!empty($biens)):
            $data["biens"]=$biens;
            $view.= '<div class="my-4">';
            $view.=  "<b>J'ai trouvé ".count($biens)." bien(s) lié(s) à $contact </b>";
            $view.=  view("Bien\doublon_bien",$data);
            $view.=  '</div>';
           
           else:
            $view.=  '<div class="my-4">';
            $view.=  "<i>Je n'ai pas trouvé de biens qui pourraient correspondre à $contact!</i>";
            $view.=  '</div>';
        endif;

        return $view;
    }


}