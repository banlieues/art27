<?php

namespace Bien\Models;

use Base\Models\BaseModel;
use CodeIgniter\Model;
use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;
use Layout\Libraries\LayoutLibrary;
use Mapping\Libraries\OSMLibrary;
use Outlook\Libraries\Myoutlook_lib;

class BienModel extends BaseModel
{
	protected $table="bien";
	protected $primaryKey = 'id_bien';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';


	protected $fields;

	public function __construct()
	{
	   	parent::__construct(__NAMESPACE__);
        // $activiteModel = new ActivitiesModel();
        // $inscriptionModel = new RegistrationsModel();

        // $this->tableInscription=$activiteModel->getTable();
        // $this->tableInscription=$inscriptionModel->getTable();

	


		$dataViewConstructor=new DataViewConstructor();
		$this->fields=$dataViewConstructor->getFields();

		$this->DataViewModel=new DataViewConstructorModel();

	}

	public function getFields(){ return $this->fields;}

	public function getTable(){return $this->table;}

	public function getListBien($request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select("
				bien.id_bien, 

				bien.adresse_fr,
				bien.adresse_nl,
		
				bien.id_type,
				liste_bien_type.label as type,

				liste_localite.label as localite,

				bien.bt,
				bien.etage_logement,
				

				(SELECT count(DISTINCT personne_bien.id_demande) FROM personne_bien WHERE personne_bien.id_bien=bien.id_bien) as nb_demande,

				(SELECT

					GROUP_CONCAT( DISTINCT CONCAT(concat(UPPER(COALESCE(contact.nom_contact,'')),' ',COALESCE(contact.prenom_contact,'')),'@@rel@@',COALESCE(liste_rel_personne_bien.label,''),'@@rel@@',contact.id_contact) SEPARATOR '@SEPARATOR@') 
					
					
					FROM personne_bien 

					LEFT JOIN contact ON contact.id_contact=personne_bien.id_contact
					LEFT JOIN contact_profil ON contact_profil.id_contact_profil=personne_bien.id_contact_profil

					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

					WHERE personne_bien.id_bien=bien.id_bien 

				
				)
				as contact_associee 

			
				"
			);

			
			$this->join("liste_localite","liste_localite.cp=bien.adresse_fr_cp","left");

		$this->join("liste_bien_type","liste_bien_type.id=bien.id_type","left");

		$this->where("adresse_fr<>",' ');
		

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				"bien.id_bien", 

				"bien.adresse_fr",
				"bien.adresse_nl",
		
				"bien.id_type",
				"liste_bien_type.label",
				

				 
					"(SELECT

					GROUP_CONCAT( DISTINCT CONCAT(contact.nom_contact,'@@rel@@',contact.prenom_contact) SEPARATOR '@SEPARATOR@') 
					
					
					FROM personne_bien 

					LEFT JOIN contact ON contact.id_contact=personne_bien.id_contact
					LEFT JOIN contact_profil ON contact_profil.id_contact_profil=personne_bien.id_contact_profil

					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

					WHERE personne_bien.id_bien=bien.id_bien 

				
				)"
			
				 )
				;
			


			 $this->groupStart();
				 foreach($items as $item):
					 $this->groupStart();
					 foreach($fieldSearchs as $fieldSearch):
						 $this->orLike($fieldSearch,$item);
					 endforeach;
					 $this->groupEnd();
				 endforeach;
			 $this->groupEnd();

		 }

		 $this->groupBy("bien.id_bien");
		 
		 if(!is_null($orderBy))
		 	$this->orderBy(sql_orderByDirection($orderBy,$orderDirection));
		 
		return $this->paginate(20);
	}

	public function bien($id_bien)
	{
		$builder=$this->db->table("bien");
		$builder->select("
				bien.*, 
				concat(user_create.prenom,' ',user_create.nom) as user_create,
				concat(user_modification.prenom,' ',user_modification.nom) as user_modification,
				bien.date_insert as date_insert_log,
				bien.date_modification as date_modification_log,

			");

		$builder->join("user_accounts as user_create","user_create.id=bien.id_user_create","left");
		$builder->join("user_accounts as user_modification","user_modification.id=bien.id_user","left");

		$builder->where("id_bien",$id_bien);



		return $builder->get()->getRow();
	}

	public function bien_profil($id_bien)
	{
		$builder=$this->db->table("bien_profil");

		$builder->where("id_bien",$id_bien);

		return $builder->get()->getResult();
	}


	public function bien_caracteristique($id_bien)
	{

		$builder=$this->db->table("bien_caracteristique");

		$builder->select("bien_caracteristique.*, bien_caracteristique.id as id_bien_caracteristique");

		$builder->where("bien_caracteristique.id_bien",$id_bien);


		return $builder->get()->getRow();
	}
	
    public function BienGeocodeGet($id_bien=null)
    {
        $LayoutLibrary = new LayoutLibrary();

        if(!empty($id_bien)) :
            $this->join($this->t_bien_geocode, "$this->t_bien_geocode.id_bien = $this->t_bien.id_bien", 'left');
            $bien = $this->find($id_bien);

            if(empty($bien->id_bien_geocode) && (!empty(preg_replace('/[^A-Za-z]+/', '', $bien->adresse_fr)) || !empty(preg_replace('/[^A-Za-z0-9 ]+/', '', $bien->adresse_nl)))) :
                $address = !empty($bien->adresse_fr) ? $bien->adresse_fr : $bien->adresse_nl;
                $this->BienGeocodeSet($id_bien, $address);
            endif;

            $geocode = $this->db->table($this->t_bien_geocode)->where('id_bien', $id_bien)->get()->getRow();
            if(!empty($geocode)) :
                $location = $geocode;
                $location->color = $LayoutLibrary->getThemeByRef('bien')->color;
                $location->marker_html = '
                    ' . $LayoutLibrary->getThemeByRef('bien')->icon . '<br>
                    ' . $bien->adresse_fr . '<br>
                    ' . $bien->adresse_nl . '<br>
                ';
            endif;
        else :
            // multiple biens
        endif;

        $data = (object) [];
        $data->locations = !empty($location) ? [$location] : [];
        $data->default_layer = 'hybrid';
        // $data->street_view_location = true;
        $data->itinerary = true;
        $data->zoom = 20;

        return $data;
    }

    public function BienGeocodeSet($id_bien, $address)
    {
        $OsmLibrary = new OSMLibrary();
        $geocode = $OsmLibrary->GeocodeByLocationGet($address);
        $geocode->id_bien = $id_bien;
        $this->db->table($this->t_bien_geocode)->insert(database_encode($this->t_bien_geocode, $geocode));
    }

	public function insertData($data)
	{
		$builder=$this->db->table($this->table);
		$builder->insert($data);
		return $this->db->insertID();
	}

	public function updateData($data,$id_bien)
	{
		$builder=$this->db->table($this->table);
		$where[$this->primaryKey]=$id_bien;

		$builder->update($data,$where);
		return $id_bien;
	}	



	public function get_demande_by_bien($id_bien)
	{
		$builder=$this->db->table("personne_bien");

		$builder->where("personne_bien.id_bien",$id_bien);

		$builder->join("contact","contact.id_contact=personne_bien.id_contact","left");
		$builder->join("contact_profil","contact_profil.id_contact_profil=personne_bien.id_contact_profil","left");
		$builder->join("demande","demande.id_demande=personne_bien.id_demande","left");
		$builder->join("demande_caracteristique","demande_caracteristique.id_demande=demande.id_demande","left");
		$builder->join("bien_caracteristique","bien_caracteristique.id_bien=personne_bien.id_bien","left");
		$builder->join("bien","bien.id_bien=personne_bien.id_bien","left");


		return $builder->get()->getResult();

	}

	public function contact_nom($id_contact)
	{
		$builder=$this->db->table("contact");
		$builder->select("CONCAT(prenom_contact,' ',nom_contact) as contact");
		$builder->where("id_contact",$id_contact);
		$contact=$builder->get()->getRow();

		if(!empty($contact))
		{
			return $contact->contact;
		}
		else
		{
			return NULL;
		}
	}


	public function search_bien_by_id_contact($id_contact)
	{
		$builder=$this->db->table("personne_bien");
		
		 
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

		$builder->where("personne_bien.id_contact",$id_contact);
		$builder->where("personne_bien.id_bien>0");
		$builder->join("bien","bien.id_bien=personne_bien.id_bien","left");

		$builder->join("contact","contact.id_contact=personne_bien.id_contact","left");
		$builder->join("contact_profil","contact_profil.id_contact_profil=personne_bien.id_contact_profil","left");

		$builder->join("liste_bien_type","liste_bien_type.id=bien.id_type","left");

		$builder->groupBy("bien.id_bien");
		return $builder->get()->getResult();


	}


	public function saveDataDelivreDemande($indexesForm,$posts_brut,$id_bien=NULL)
    {
		//debugd($posts_brut);
		$id_demande=$posts_brut["id_demande"];
		$id_personne_bien=$posts_brut["id_personne_bien"];

		$id_contact=$posts_brut["id_contact"];
		$id_contact_profil=$posts_brut["id_contact_profil"];


        $dataView = new DataViewConstructor(); 
       //descriptor
        $fields=$this->DataViewModel->getFields();

		//On classe les datas en deux parties



        //--------------------------1.traitement de la demande--------------------


        $posts_demande=$posts_brut;
        $is_insert=FALSE;
        $demande_exclude=["pk_value","id_demande"];

        foreach($posts_demande as $index=>$value)
        {
            if(!in_array($index,$demande_exclude))
            {
                $data_insert_demande[$index]=$value;
                
            }
        }
       
        
		$data_insert_demande_with_index=$dataView->prepareData($indexesForm,$data_insert_demande,$fields,"demande",true,false);

        $data_insert_demande=$dataView->prepareData($indexesForm,$data_insert_demande,$fields,"demande",true);


        $builder=$this->db->table("demande");

		//debugd($id_demande);
        if($id_demande>0)
        {
			$data_changes=$this->DataViewModel->set_log_fiche("bien",$data_insert_demande_with_index,"id_demande",$id_demande,"demande","demande");

			$data_insert_demande["date_modification"]=date("Y-m-d H:i:s");
            $data_insert_demande["id_user"]=session()->get("loggedUserId");
            $builder->where("id_demande",$id_demande);

			
			unset($data_insert_demande["id_demande"]);

			//debugd($data_insert_demande);
            $builder->update($data_insert_demande);
			$this->DataViewModel->set_logs_fiche_insert_bd("demande",$data_changes,date("Y-m-d H:i:s"),$id_demande,$id_demande);

			

        }
        else
        {

			$data_insert_demande["date_insert"]=date("Y-m-d H:i:s");
			$data_insert_demande["id_user_create"]=session()->get("loggedUserId");
	
            //$data_insert_demande["id_type_personne"]=1;
            //debugd($data_insert_demande);
            $builder->insert($data_insert_demande);
            $id_demande=$this->db->insertId();
			$data_changes=$this->DataViewModel->set_log_fiche_insert($data_insert_demande_with_index);
			$this->DataViewModel->set_logs_fiche_insert_bd("demande",$data_changes,date("Y-m-d H:i:s"),$id_demande,$id_demande);


        }

        // email automatique update demande
        $OutlookLibrary = new Myoutlook_lib();
        if(isset($data_changes['demande_utilisateur'])) :
            $OutlookLibrary->demande_assign_notification($id_demande);
        else :
            $OutlookLibrary->demande_update_notification($id_demande);
        endif;
        
//die();
//debug($id_demande);
		 //-----------------------2. Traitement de ou des caracteristiques de demande existant----------------------------
		 $dataView = new DataViewConstructor(); 
		 //descriptor
		  $fields=$this->DataViewModel->getFields();
  
		  $is_insert=FALSE;
		  $demande_exclude=["pk_value","id_demande"];
			 
			 $demande_caracteristique_exclude=["is_multiple","id_demande","id_demande_caracteristique","demande_caracteristique_organisme"];    
 
			
				 $id_demande_caracteristique=$posts_demande["id_demande_caracteristique"];
				 //$demande_caracteristique_organisme=$demande_caracteristique["demande_caracteristique_organisme"];
 
				 foreach($posts_demande as $index=>$value)
				 {
					 if(!in_array($index,$demande_caracteristique_exclude))
					 {
						 $data_insert_demande_caracteristique[$index]=$value;
					 }
				 }
 
			   
				 $data_insert_demande_caracteristique_with_index=$dataView->prepareData($indexesForm,$data_insert_demande_caracteristique,$fields,"demande_caracteristique",true,false);

				 $data_insert_demande_caracteristique=$dataView->prepareData($indexesForm,$data_insert_demande_caracteristique,$fields,"demande_caracteristique",true);
		
				// debug($data_insert_demande_caracteristique);

				 if(!empty($data_insert_demande_caracteristique))
				 {
					
					
					$data_insert_demande_caracteristique["id_demande"]=$id_demande;
 
			 
					
				 $builder=$this->db->table("demande_caracteristique");

				 
 
				 if($id_demande_caracteristique>0)
				 {
					$data_changes=$this->DataViewModel->set_log_fiche("bien",$data_insert_demande_caracteristique_with_index,"id_demande_caracteristique",$id_demande_caracteristique,"demande_caracteristique","demande");

					 $builder->where("id_demande_caracteristique",$id_demande_caracteristique);
					 unset($data_insert_demande_caracteristique["id_demande_caracteristique"]);
					 $builder->update($data_insert_demande_caracteristique);
					
					 $this->DataViewModel->set_logs_fiche_insert_bd("demande",$data_changes,date("Y-m-d H:i:s"),$id_demande,$id_demande_caracteristique);

 
				 }
				 else
				 {
					$data_insert_demande_caracteristique["id_demande"]=$id_demande;
					 $builder->insert($data_insert_demande_caracteristique);
					 $id_demande_caracteristique=$this->db->insertId();

					 $data_changes=$this->DataViewModel->set_log_fiche_insert($data_insert_demande_caracteristique_with_index);
					 $this->DataViewModel->set_logs_fiche_insert_bd("demande",$data_changes,date("Y-m-d H:i:s"),$id_demande,$id_demande_caracteristique);
 


				 }
 
			   
 
				 $builder=$this->db->table("demande");
				 $data_insert_demande_date["date_modification"]=date("Y-m-d H:i:s");
			 	$data_insert_demande_date["id_user"]=session()->get("loggedUserId");
			 	$builder->where("id_demande",$id_demande);

				 unset($data_insert_demande_date["id_demande"]);

			 	$builder->update($data_insert_demande_date);


				 				  //-----------------------3. Traitement  contact existant----------------------------


				 $id_contact=$posts_brut["id_contact"];
				 $posts_contact=$posts_brut;
				 $is_insert=FALSE;
				 $contact_exclude=["pk_value","id_contact"];
		 
				 foreach($posts_contact as $index=>$value)
				 {
					 if(!in_array($index,$contact_exclude))
					 {
						 $data_insert_contact[$index]=$value;
						 
					 }
				 }
				

				 $data_insert_contact_with_index=$dataView->prepareData($indexesForm,$data_insert_contact,$fields,"contact",true,false);

				 $data_insert_contact=$dataView->prepareData($indexesForm,$data_insert_contact,$fields,"contact",true);
		 
		 
		 
				 $builder=$this->db->table("contact");
		 
				 if($id_contact>0)
				 {
					$data_changes=$this->DataViewModel->set_log_fiche("bien",$data_insert_contact_with_index,"id_contact",$id_contact,"contact","demande");

					 $data_insert_contact["date_modification"]=date("Y-m-d H:i:s");
					 $data_insert_contact["id_user"]=session()->get("loggedUserId");
					 //debug($id_contact);
					 //debugd($data_insert_contact);
					 $builder->where("id_contact",$id_contact);
					unset($data_insert_contact["id_contact"]);
					 $builder->update($data_insert_contact);

					 $this->DataViewModel->set_logs_fiche_insert_bd("contact",$data_changes,date("Y-m-d H:i:s"),$id_contact,$id_contact);

		 
		 
				 }
				 else
				 {
		 
					 $data_insert_contact["date_insert"]=date("Y-m-d H:i:s");
					 $data_insert_contact["id_user_create"]=session()->get("loggedUserId");
			 
					 //$data_insert_contact["id_type_personne"]=1;
					 
					 $builder->insert($data_insert_contact);
					 $id_contact=$this->db->insertId();
				 }
		 
				  //-----------------------4. Traitement de ou des profils de contact existant----------------------------
				  $dataView = new DataViewConstructor(); 
				  //descriptor
				   $fields=$this->DataViewModel->getFields();
		   
				   $is_insert=FALSE;
				   $contact_exclude=["pk_value","id_contact"];
					  
					  $contact_profil_exclude=["is_multiple","id_contact","id_contact_profil","contact_profil_organisme"];   
					  
					  
		 
					 $contacts_profil=$posts_brut;
					 
					 //debugd($contacts_profil);
					 $id_contact=$posts_brut["id_contact_profil"];
						  //$id_contact_profil=$contacts_profil["id_contact_profil"];
						  //$contact_profil_organisme=$contacts_profil["contact_profil_organisme"];
		  
						  foreach($contacts_profil as $index=>$value)
						  {
							  if(!in_array($index,$contact_profil_exclude))
							  {
								  $data_insert_contact_profil[$index]=$value;
							  }
						  }
						  
						 // debugd($data_insert_contact_profil);
						 $data_insert_contact_profil_with_index=$dataView->prepareData($indexesForm,$data_insert_contact_profil,$fields,"contact_profil",true,false);

						  $data_insert_contact_profil=$dataView->prepareData($indexesForm,$data_insert_contact_profil,$fields,"contact_profil",true);
						  $data_insert_contact_profil["id_contact"]=$id_contact;
		  
					 // debugd($data_insert_contact_profil);
		  
						  $builder=$this->db->table("contact_profil");
		  
						  if($id_contact_profil>0)
						  {
							$data_changes=$this->DataViewModel->set_log_fiche("bien",$data_insert_contact_profil_with_index,"id_contact_profil",$id_contact_profil,"contact_profil","demande");

							  $builder->where("id_contact_profil",$id_contact_profil);
							  unset($data_insert_contact_profil["id_contact_profil"]);
							  $builder->update($data_insert_contact_profil);

							  $this->DataViewModel->set_logs_fiche_insert_bd("contact",$data_changes,date("Y-m-d H:i:s"),$id_contact,$id_contact_profil);

		  
		  
						  }
						  else
						  {
							
							  $builder->insert($data_insert_contact_profil);
							  $id_contact_profil=$this->db->insertId();

							  $data_changes=$this->DataViewModel->set_log_fiche_insert($data_insert_contact_profil_with_index);
							  $this->DataViewModel->set_logs_fiche_insert_bd("contact",$data_changes,date("Y-m-d H:i:s"),$id_contact,$id_contact_profil);
		  
						  }
		  
						
		  
						  $builder=$this->db->table("contact");
						  $data_insert_contact["date_modification"]=date("Y-m-d H:i:s");
					  $data_insert_contact["id_user"]=session()->get("loggedUserId");
					  $builder->where("id_contact",$id_contact);
					  unset($data_insert_contact["id_contact"]);

					  $builder->update($data_insert_contact);



				 if($posts_brut["id_personne_bien"]>0)
				 {
					$id_personne_bien=$posts_brut["id_personne_bien"];

					$data_changes=$this->DataViewModel->set_logs_relation_bien($id_personne_bien,$posts_brut["rel_personne_bien"]);

					 $builder=$this->db->table("personne_bien");
					 $builder->where("id_personne_bien",$posts_brut["id_personne_bien"]);
					 $data_rel_personne["id_contact"]=$id_contact;
					 $data_rel_personne["id_contact_profil"]=$id_contact_profil;
					 $data_rel_personne["rel_personne_bien"]=$posts_brut["rel_personne_bien"];
					 unset($data_rel_personne["id_personne_bien"]);

					 $builder->update($data_rel_personne);

					 $this->DataViewModel->set_logs_fiche_insert_bd("demande",$data_changes,date("Y-m-d H:i:s"),$id_demande,$id_personne_bien);

				 }
				 


			}


		return $id_bien;
	}

	public function saveDataDelivreBien($indexesForm,$posts_brut,$id_bien=NULL)
    {
		//debugd($id_bien);
		//debugd($posts_brut);
        $dataView = new DataViewConstructor(); 
       //descriptor
        $fields=$this->DataViewModel->getFields();

        //--------------------------1.traitement de bien --------------------

		$id_bien=$posts_brut["id_bien"];
        $posts_bien=$posts_brut;
        $is_insert=FALSE;
        $bien_exclude=["pk_value","id_bien"];

        foreach($posts_bien as $index=>$value)
        {
            if(!in_array($index,$bien_exclude))
            {
                $data_insert_bien[$index]=$value;
                
            }
        }
       
        
		$data_insert_bien_with_index=$dataView->prepareData($indexesForm,$data_insert_bien,$fields,"bien",true,false);

        $data_insert_bien=$dataView->prepareData($indexesForm,$data_insert_bien,$fields,"bien",true);



        $builder=$this->db->table("bien");

        if($id_bien>0)
        {
			$data_changes=$this->DataViewModel->set_log_fiche("bien",$data_insert_bien_with_index,"id_bien",$id_bien,"bien","bien");

			$data_insert_bien["date_modification"]=date("Y-m-d H:i:s");
            $data_insert_bien["id_user"]=session()->get("loggedUserId");
			//debug($id_bien);
			//debugd($data_insert_bien);
            $builder->where("id_bien",$id_bien);
			unset($data_insert_bien["id_bien"]);
            $builder->update($data_insert_bien);

			$this->DataViewModel->set_logs_fiche_insert_bd("bien",$data_changes,date("Y-m-d H:i:s"),$id_bien,$id_bien);



        }
        else
        {

			$data_insert_bien["date_insert"]=date("Y-m-d H:i:s");
			$data_insert_bien["id_user_create"]=session()->get("loggedUserId");
	
            //$data_insert_bien["id_type_personne"]=1;
            
            $builder->insert($data_insert_bien);
            $id_bien=$this->db->insertId();

			$data_changes=$this->DataViewModel->set_log_fiche_insert($data_insert_bien_with_index);

			$this->DataViewModel->set_logs_fiche_insert_bd("bien",$data_changes,date("Y-m-d H:i:s"),$id_bien,$id_bien);

        }
 
 
		 return $id_bien;
	}

	public function saveDataDelivreBienCaracteristique($indexesForm,$posts_brut,$id_bien=NULL)
    {
		//debugd($id_bien);
		//debugd($posts_brut);
        $dataView = new DataViewConstructor(); 
       //descriptor
        $fields=$this->DataViewModel->getFields();

        //--------------------------1.traitement de bien --------------------

		$id_bien=$posts_brut["id_bien"];
        

		 //-----------------------2. Traitement de ou des carac de bien existant----------------------------
		 $dataView = new DataViewConstructor(); 
		 //descriptor
		  $fields=$this->DataViewModel->getFields();
  
		  $is_insert=FALSE;
		  $bien_exclude=["pk_value","id_bien"];
			 
			 $bien_caracteristique_exclude=["is_multiple","id_bien","id_bien_caracteristique","bien_caracteristique_organisme"];   
			 
			 

			$biens_profil=$posts_brut;
			
			//debugd($biens_profil);

			
				 $id_bien_caracteristique=$biens_profil["id_bien_caracteristique"];
				 //$bien_caracteristique_organisme=$biens_profil["bien_caracteristique_organisme"];
 
				 foreach($biens_profil as $index=>$value)
				 {
					 if(!in_array($index,$bien_caracteristique_exclude))
					 {
						 $data_insert_bien_caracteristique[$index]=$value;
					 }
				 }
				 
				// debugd($data_insert_bien_caracteristique);
				$data_insert_bien_caracteristique_with_index=$dataView->prepareData($indexesForm,$data_insert_bien_caracteristique,$fields,"bien_caracteristique",true,false);

				 $data_insert_bien_caracteristique=$dataView->prepareData($indexesForm,$data_insert_bien_caracteristique,$fields,"bien_caracteristique",true);
				 $data_insert_bien_caracteristique["id_bien"]=$id_bien;
 
			// debugd($data_insert_bien_caracteristique);
 
				 $builder=$this->db->table("bien_caracteristique");
 
				 if($id_bien_caracteristique>0)
				 {
					$data_changes=$this->DataViewModel->set_log_fiche("bien",$data_insert_bien_caracteristique_with_index,"bien_caracteristique.id",$id_bien_caracteristique,"bien_caracteristique","bien_caracteristique");

					 $builder->where("id",$id_bien_caracteristique);
					 unset($data_insert_bien_caracteristique["id"]);
					 $builder->update($data_insert_bien_caracteristique);

					 $this->DataViewModel->set_logs_fiche_insert_bd("bien",$data_changes,date("Y-m-d H:i:s"),$id_bien,$id_bien_caracteristique);

 
 
				 }
				 else
				 {
					
					 $builder->insert($data_insert_bien_caracteristique);
					 $id_bien_caracteristique=$this->db->insertId();

					 $data_changes=$this->DataViewModel->set_log_fiche_insert($data_insert_bien_caracteristique_with_index);
					 $this->DataViewModel->set_logs_fiche_insert_bd("bien",$data_changes,date("Y-m-d H:i:s"),$id_bien,$id_bien_caracteristique);

				 }
 
			   
 
				 $builder=$this->db->table("bien");
				 $data_insert_bien["date_modification"]=date("Y-m-d H:i:s");
			 $data_insert_bien["id_user"]=session()->get("loggedUserId");
			 $builder->where("id_bien",$id_bien);
			 unset($data_insert_bien["id_bien"]);

			 $builder->update($data_insert_bien);
 
 
	


 
 
		 return $id_bien;
	}

	public function saveDataDelivreNewBien($indexesForm,$posts_brut,$id_bien)
	{
		$id_bien=$this->saveDataDelivreBien($indexesForm,$posts_brut,$id_bien);
		$posts_brut["id_bien"]=$id_bien;
		$this->saveDataDelivreBienCaracteristique($indexesForm,$posts_brut,$id_bien);

		return $id_bien;
	}


	public function associe_demande($post)
	{
		//debugd($post);
		//On regarde si personne_rel_bien existe
		$builder=$this->db->table("personne_bien");
		$demandes=$builder->where("id_demande",$post["id_demande"])->get()->getResult();


		if(!empty($demandes))
		{
			$builder=$this->db->table("personne_bien");
			$builder->where("id_demande",$post["id_demande"]);
			$data["id_bien"]=$post["id_bien"];

			return $builder->update($data);
		}
		else
		{
			$builder=$this->db->table("personne_bien");
			$data["id_bien"]=$post["id_bien"];
			$data["id_demande"]=$post["id_demande"];
			//debugd($data);
			return $builder->insert($data);
		}

		
		
	}
}
