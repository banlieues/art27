<?php

namespace Contact\Models;

use CodeIgniter\Model;
use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;
use Outlook\Libraries\Myoutlook_lib;

class ContactModel extends Model
{
	protected $table="contact";
	protected $primaryKey = 'id_contact';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';


	protected $fields;

	public function __construct()
	{
	   	parent::__construct();
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

	public function getListContact($request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select("
				contact.id_contact, 

				contact.nom_contact,
				contact.prenom_contact,
		
				contact.id_type_personne,
				liste_type_personne.label as type_personne,
				contact.id_langue,
				liste_langue.label as langue,
				contact.id_civilite,
				liste_civilite.label as civilite,

				(SELECT CONCAT(COALESCE(telephone,''),' ',COALESCE(telephone2,'')) FROM contact_profil WHERE contact_profil.id_contact=contact.id_contact) as telephone,

				(SELECT CONCAT(COALESCE(email,''),' ',COALESCE(email2,'')) FROM contact_profil WHERE contact_profil.id_contact=contact.id_contact) as email,

				(SELECT count(DISTINCT personne_bien.id_demande) FROM personne_bien WHERE personne_bien.id_contact=contact.id_contact) as nb_demande,

				(SELECT

					GROUP_CONCAT( DISTINCT CONCAT(COALESCE(bien.adresse_fr,''),'@@rel@@',COALESCE(liste_rel_personne_bien.label,''),'@@rel@@',bien.id_bien) SEPARATOR '@SEPARATOR@') 
					
					
					FROM personne_bien 

					LEFT JOIN bien ON bien.id_bien=personne_bien.id_bien 
					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

					WHERE personne_bien.id_contact=contact.id_contact 

				
				)
				as bien_associe ,

				contact.id_user_create

			
				"
			);

			

		$this->join("liste_type_personne","liste_type_personne.id=contact.id_type_personne","left");
		$this->join("liste_langue","liste_langue.id=contact.id_langue","left");
		$this->join("liste_civilite","liste_civilite.id=contact.id_civilite","left");

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				 	"contact.id_contact",
				 	"contact.nom_contact",
					 "contact.prenom_contact",
					"liste_type_personne.label",
					"
					(SELECT CONCAT(COALESCE(REPLACE(REPLACE(REPLACE(REPLACE(telephone,' ',''),'-',''),'.',''),'/',''),''),' ',COALESCE(REPLACE(REPLACE(REPLACE(REPLACE(telephone2,' ',''),'-',''),'.',''),'/',''),'')) FROM contact_profil WHERE contact_profil.id_contact=contact.id_contact)
					",
					"
					(SELECT CONCAT(COALESCE(email,''),' ',COALESCE(email2,'')) FROM contact_profil WHERE contact_profil.id_contact=contact.id_contact)
					",
					"
					(SELECT

					GROUP_CONCAT( DISTINCT CONCAT(bien.adresse_fr,'@@rel@@',liste_rel_personne_bien.label) SEPARATOR '@SEPARATOR@') 
					
					FROM personne_bien 

					LEFT JOIN bien ON bien.id_bien=personne_bien.id_bien 
					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien


					WHERE personne_bien.id_contact=contact.id_contact 

				
					)
					",
				 	
			
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
		 
		 if(!is_null($orderBy))
		 	$this->orderBy(sql_orderByDirection($orderBy,$orderDirection));
		 
		return $this->paginate(20);
	}



	public function getListDemandeur($request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select("
				contact.id_contact, 

				contact.nom_contact,
				contact.prenom_contact,
		
				contact.id_type_personne,
				liste_type_personne.label as type_personne,
				contact.id_langue,
				liste_langue.label as langue,
				contact.id_civilite,
				liste_civilite.label as civilite,

				(SELECT count(DISTINCT personne_bien.id_demande) FROM personne_bien WHERE personne_bien.id_contact=contact.id_contact) as nb_demande,

				(SELECT

					GROUP_CONCAT( DISTINCT CONCAT(COALESCE(bien.adresse_fr,''),'@@rel@@',COALESCE(liste_rel_personne_bien.label,''),'@@rel@@',bien.id_bien) SEPARATOR '@SEPARATOR@') 
					
					
					FROM personne_bien 

					LEFT JOIN bien ON bien.id_bien=personne_bien.id_bien 
					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

					WHERE personne_bien.id_contact=contact.id_contact 

				
				)
				as bien_associe 

			
				"
			);
			$this->where("contact.id_contact IN (SELECT personne_bien.id_contact FROM personne_bien)");
			

		$this->join("liste_type_personne","liste_type_personne.id=contact.id_type_personne","left");
		$this->join("liste_langue","liste_langue.id=contact.id_langue","left");
		$this->join("liste_civilite","liste_civilite.id=contact.id_civilite","left");

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				 	"contact.id_contact",
				 	"contact.nom_contact",
					 "contact.prenom_contact",
					"liste_type_personne.label",
					"
					(SELECT

					GROUP_CONCAT( DISTINCT CONCAT(bien.adresse_fr,'@@rel@@',liste_rel_personne_bien.label) SEPARATOR '@SEPARATOR@') 
					
					FROM personne_bien 

					LEFT JOIN bien ON bien.id_bien=personne_bien.id_bien 
					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien


					WHERE personne_bien.id_contact=contact.id_contact 

				
				)
					",
				 	
			
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
		 
		 if(!is_null($orderBy))
		 	$this->orderBy(sql_orderByDirection($orderBy,$orderDirection));
		 
		return $this->paginate(20);
	}

	public function contact($id_contact)
	{
		$builder=$this->db->table("contact");

		$builder->select("contact.*, 
		concat(user_create.prenom,' ',user_create.nom) as user_create,
		concat(user_modification.prenom,' ',user_modification.nom) as user_modification,
		contact.date_insert as date_insert_log,
		contact.date_modification as date_modification_log");

		$builder->join("user_accounts as user_create","user_create.id=contact.id_user_create","left");
		$builder->join("user_accounts as user_modification","user_modification.id=contact.id_user","left");

		$builder->where("contact.id_contact",$id_contact);

		return $builder->get()->getRow();
	}

	public function contact_profil($id_contact)
	{
		
		$builder=$this->db->table("contact");

		$builder->join("contact_profil","contact_profil.id_contact=contact.id_contact","left");


		$builder->where("contact.id_contact",$id_contact);

		return $builder->get()->getResult();
	}


	public function contact_profil_by_id_contact_profil($id_contact_profil)
	{
		$builder=$this->db->table("contact_profil");
		$builder->where("id_contact_profil",$id_contact_profil);

		return $builder->get()->getRow();
	}

	public function insertData($data)
	{
		$builder=$this->db->table($this->table);
		$builder->insert($data);
		return $this->db->insertID();
	}

	public function updateData($data,$id_contact)
	{
		$builder=$this->db->table($this->table);
		$where[$this->primaryKey]=$id_contact;

		$builder->update($data,$where);
		return $id_contact;
	}	



	public function get_demande_by_contact($id_contact)
	{
		$builder=$this->db->table("personne_bien");
		$builder->select("personne_bien.*, personne_bien.id_demande");
		$builder->select("demande.*");
		$builder->select("demande_caracteristique.*");
		$builder->select("bien.*");
		$builder->where("personne_bien.id_contact", $id_contact);

		$builder->join("bien","bien.id_bien=personne_bien.id_bien","left");
		$builder->join("demande","demande.id_demande=personne_bien.id_demande","left");
		$builder->join("demande_caracteristique","demande_caracteristique.id_demande=demande.id_demande","left");
		$builder->join("contact_profil","contact_profil.id_contact_profil=personne_bien.id_contact_profil","left");
		$builder->join("contact","contact.id_contact=personne_bien.id_contact","left");

		return $builder->get()->getResult();
	}

	


	public function get_result_search_link($itemSearch)
	{

		//Récupérer les fields
		$dataConstructor= \Config\Services::dataViewConstructorModel();;

		$contact_indexes=$dataConstructor->getFieldsWithIndex("contact",$is_exclude_field_sql=TRUE);

		$contact_profil_indexes=$dataConstructor->getFieldsWithIndex("contact_profil",$is_exclude_field_sql=TRUE);

		$array_fields_index=[];

		array_push($array_fields_index,"contact.id_contact");
		array_push($array_fields_index,"contact_profil.id_contact_profil");

		foreach($contact_indexes as $index_contact => $contact_field)
		{
			array_push($array_fields_index,"contact.$contact_field as $index_contact");
		}

		foreach($contact_profil_indexes as $index_contact_profil => $contact_profil_field)
		{
			array_push($array_fields_index,"contact_profil.$contact_profil_field as $index_contact_profil");
		}
		



		//Récupérer toutes les données possibles
		$this->select(

			implode(",",$array_fields_index)
		);

		$this->join("contact_profil","contact_profil.id_contact=contact.id_contact","left");

		$items=explode(" ",$itemSearch);
				
		$fieldSearchs=array
		(	
				"contact.nom_contact",
				"contact.prenom_contact", 
				
				
		);
	   
		$this->groupStart();
			foreach($items as $item):
				$this->groupStart();
				foreach($fieldSearchs as $fieldSearch):
					$this->orLike($fieldSearch,$item);
				endforeach;
				$this->groupEnd();
			endforeach;
		$this->groupEnd();

		
		return $this->find();
	}
	
	public function delete_relation_contact_profil($id_contact_profil_gasap)
    {
        $builder=$this->db->table("contact_profil_gasap");
        $builder->where("id_contact_profil_gasap",$id_contact_profil_gasap);

        return $builder->delete();
    }

	public function contact_profil_add($data)
	{
		$builder=$this->db->table("contact_profil");

		$builder->insert($data);


	}

	public function saveDataDelivreContact($indexesForm,$posts_brut,$id_contact=NULL)
    {
        $dataView = new DataViewConstructor(); 
       //descriptor
        $fields=$this->DataViewModel->getFields();

        //--------------------------1.traitement de contact --------------------


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
			$data_changes=$this->DataViewModel->set_log_fiche("contact",$data_insert_contact_with_index,"id_contact",$id_contact,"contact","contact");

			$data_insert_contact["date_modification"]=date("Y-m-d H:i:s");
            $data_insert_contact["id_user"]=session()->get("loggedUserId");
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
			$data_changes=$this->DataViewModel->set_log_fiche_insert($data_insert_contact_with_index);
			$this->DataViewModel->set_logs_fiche_insert_bd("contact",$data_changes,date("Y-m-d H:i:s"),$id_contact,$id_contact);

        }

		return $id_contact;
	}


	


	public function saveDataDelivreContactProfil($indexesForm,$contacts_profil,$id_contact=NULL)
    {
		
        //-----------------------2. Traitement de ou des profils de contact existant----------------------------
		$dataView = new DataViewConstructor(); 
		//descriptor
		 $fields=$this->DataViewModel->getFields();
 
		 $is_insert=FALSE;
		 $contact_exclude=["pk_value","id_contact"];
            
            $contact_profil_exclude=["is_multiple","id_contact","id_contact_profil","contact_profil_organisme"];    

           
                $id_contact_profil=$contacts_profil["id_contact_profil"];
                //$contact_profil_organisme=$contacts_profil["contact_profil_organisme"];

                foreach($contacts_profil as $index=>$value)
                {
                    if(!in_array($index,$contact_profil_exclude))
                    {
                        $data_insert_contact_profil[$index]=$value;
                    }
                }

				$data_insert_contact_profil_with_index=$dataView->prepareData($indexesForm,$data_insert_contact_profil,$fields,"contact_profil",true,false);

                $data_insert_contact_profil=$dataView->prepareData($indexesForm,$data_insert_contact_profil,$fields,"contact_profil",true);
                $data_insert_contact_profil["id_contact"]=$id_contact;

            

                $builder=$this->db->table("contact_profil");

                if($id_contact_profil>0)
                {
					$data_changes=$this->DataViewModel->set_log_fiche("contact",$data_insert_contact_profil_with_index,"id_contact_profil",$id_contact_profil,"contact_profil","contact_profil");

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





		return $id_contact;
	}


	public function saveDataDelivreNewContact($indexesForm,$posts_brut,$id_contact)
	{
		$id_contact=$this->saveDataDelivreContact($indexesForm,$posts_brut,$id_contact);
		$this->saveDataDelivreContactProfil($indexesForm,$posts_brut,$id_contact);

		return $id_contact;
	}

	public function saveDataDelivreDemande($indexesForm,$posts_brut,$id_contact=NULL)
    {
		$id_demande=$posts_brut["id_demande"];
		$id_bien=$posts_brut["id_bien"];


		$id_personne_bien=$posts_brut["id_personne_bien"];


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

        if($id_demande>0)
        {
			$data_changes=$this->DataViewModel->set_log_fiche("contact",$data_insert_demande_with_index,"id_demande",$id_demande,"demande","demande");

			$data_insert_demande["date_modification"]=date("Y-m-d H:i:s");
            $data_insert_demande["id_user"]=session()->get("loggedUserId");
            $builder->where("id_demande",$id_demande);
			unset($data_insert_demande["id_demande"]);
            $builder->update($data_insert_demande);
			$this->DataViewModel->set_logs_fiche_insert_bd("demande",$data_changes,date("Y-m-d H:i:s"),$id_demande,$id_demande);



        }
        else
        {
			
			$data_insert_demande["date_insert"]=date("Y-m-d H:i:s");
			$data_insert_demande["id_user_create"]=session()->get("loggedUserId");
	
            //$data_insert_demande["id_type_personne"]=1;
            
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
					$data_changes=$this->DataViewModel->set_log_fiche("contact",$data_insert_demande_caracteristique_with_index,"id_demande_caracteristique",$id_demande_caracteristique,"demande_caracteristique","demande");

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
				
			}



			/*-------------------Bien traitement ---------------------------------*/


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
			$data_changes=$this->DataViewModel->set_log_fiche("contact",$data_insert_bien_with_index,"id_bien",$id_bien,"bien","demande");

			$data_insert_bien["date_modification"]=date("Y-m-d H:i:s");
            $data_insert_bien["id_user"]=session()->get("loggedUserId");
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




			/*-----------------------relation demande, bien -----------------------*/

			
			if($posts_brut["id_personne_bien"]>0)
			{
				$id_personne_bien=$posts_brut["id_personne_bien"];
				$data_changes=$this->DataViewModel->set_logs_relation_bien($id_personne_bien,$posts_brut["rel_personne_bien"]);

				$builder=$this->db->table("personne_bien");
				$builder->where("id_personne_bien",$posts_brut["id_personne_bien"]);
				$data_rel_personne["rel_personne_bien"]=$posts_brut["rel_personne_bien"];
				$data_rel_personne["id_bien"]=$id_bien;
				unset($data_rel_personne["id_personne_bien"]);
				$builder->update($data_rel_personne);

				$this->DataViewModel->set_logs_fiche_insert_bd("demande",$data_changes,date("Y-m-d H:i:s"),$id_demande,$id_personne_bien);

			}


		return $id_contact;
	}

	public function associe_demande($post)
	{
		$id_bien=NULL;
		$rel_personne_bien=NULL;
		$builder=$this->db->table("personne_bien");
		$biens=$builder->where("id_demande",$post["id_demande"])->get()->getResult();
		if(!empty($biens))
		{
			foreach($biens as $bien)
			{
				if($bien->id_bien>0)
				{
					$id_bien=$bien->id_bien;
				}

			
			}
		}
		
		$builder=$this->db->table("personne_bien");
		$builder->where("id_demande",$post["id_demande"]);
		$builder->groupStart();
			$builder->where("id_contact",NULL);
			$builder->orWhere("id_contact",0);
		$builder->groupEnd();

		$demandes=$builder->get()->getResult();

		if(empty($demandes))
		{
			$builder=$this->db->table("personne_bien");

			$data_insert["id_contact"]=$post["id_contact"];
			$data_insert["id_contact_profil"]=$post["id_contact_profil"];
			$data_insert["id_demande"]=$post["id_demande"];
			$data_insert["id_bien"]=$id_bien;
			
			$builder->insert($data_insert);
		}
		else
		{
			foreach($demandes as $demande)
			{
				$builder=$this->db->table("personne_bien");
				$builder->where("id_personne_bien",$demande->id_personne_bien);
				$data_insert["id_demande"]=$post["id_demande"];
				$data_insert["id_contact"]=$post["id_contact"];
				$data_insert["id_contact_profil"]=$post["id_contact_profil"];
				$data_insert["id_bien"]=$id_bien;
				
				$builder->update($data_insert);
			}
		}
		
	

		//Mise à jour des id_bien, s'assurer qu'id-bien existe dans toutes les lignes et est identique
		

	

		if($id_bien>0&&$post["id_demande"]>0)
		{
			$builder=$this->db->table("personne_bien");
			$builder->where("id_demande",$post["id_demande"]);

			$data_id_bien["id_bien"]=$id_bien;
			$builder->update($data_id_bien);
		}

		return;
			

		
	}
}
