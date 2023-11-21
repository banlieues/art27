<?php

namespace Demande\Models;

use Base\Models\BaseModel;
use Bien\Models\BienModel;
use CodeIgniter\Model;
use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;
use Layout\Libraries\LayoutLibrary;

use Historique\Models\HistoriqueModel;
use Mailing\Libraries\MailingLibrary;
use Outlook\Libraries\Myoutlook_lib;

class DemandeModel extends BaseModel
{
	protected $table="demande";
	protected $primaryKey = 'id_demande';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';


	protected $fields;

	public function __construct()
	{
	   	parent::__construct(__NAMESPACE__);

        $dataViewConstructor=new DataViewConstructor();
        $this->fields=$dataViewConstructor->getFields();

        $this->DataViewModel=new DataViewConstructorModel();

		$this->historiqueModel=new HistoriqueModel;
	}

	public function getFields(){ return $this->fields;}

	public function getTable(){return $this->table;}

    public function DemandeGeocodeGet($id_demande=null)
    {
        $LayoutLibrary = new LayoutLibrary();

        if(!empty($id_demande)) :
            $this->join($this->t_bien_demande, "$this->t_bien_demande.id_demande = $this->t_demande.id_demande", 'left');
            // join t_bien_geocode before t_bien to keep id_bien not null
            $this->join($this->t_bien_geocode, "$this->t_bien_geocode.id_bien = $this->t_bien_demande.id_bien", 'left');
            $this->join($this->t_bien, "$this->t_bien.id_bien = $this->t_bien_demande.id_bien", 'left');

            $demande = $this->find($id_demande);

            if(
                empty($demande->id_bien_geocode) && 
                (
                    (
                        !empty(preg_replace('/[^A-Za-z]+/', '', $demande->adresse_fr)) &&
                        !empty(preg_replace('/[^0-9]+/', '', $demande->adresse_fr))
                    ) || 
                    (
                        !empty(preg_replace('/[^A-Za-z]+/', '', $demande->adresse_nl)) &&
                        !empty(preg_replace('/[^0-9]+/', '', $demande->adresse_nl))
                    )
                )
            ) :
                $address = !empty($demande->adresse_fr) ? $demande->adresse_fr : $demande->adresse_nl;
                $BienModel = new BienModel();
                $BienModel->BienGeocodeSet($demande->id_bien, $address);
            endif;

            $geocode = $this->db->table($this->t_bien_geocode)->where('id_bien', $demande->id_bien)->get()->getRow();
            if(!empty($geocode)) :
                $location = $geocode;
                $location->color = $LayoutLibrary->getThemeByRef('bien')->color;
                $location->marker_html = '
                    ' . $LayoutLibrary->getThemeByRef('bien')->icon . '<br>
                    ' . $demande->adresse_fr . '<br>
                    ' . $demande->adresse_nl . '<br>
                ';
            endif;
        else :
            // multiple biens
        endif;

        $data = (object) [];
        $data->default_layer = 'hybrid';
        $data->locations = !empty($location) ? [$location] : [];
        // $data->street_view_location = true;
        $data->zoom = 19;

        return $data;
    }	

	public function getListDemandes($request,$orderBy=NULL,$orderDirection=NULL,$id_bien=NULL)
	{
		$this->select("
				$this->table.id_demande, 
				$this->table.date, 
				$this->table.id_type_demande,
				liste_demande_type.label as type,
				$this->table.id_demande_statut,
				liste_demande_statut.label as statut,
				liste_pole.label as pole,
				$this->table.nom as sujet,
				createur.id as id_createur,
				createur.nom as nom_createur,
				createur.prenom as prenom_createur,
				encharge.id as id_encharge,
				encharge.nom as nom_encharge,
				encharge.prenom as prenom_encharge,
				id_utilisateur,
				id_personne_bien,
				personne_bien.id_contact,
				personne_bien.id_bien,
				(SELECT

					GROUP_CONCAT( DISTINCT CONCAT(concat(UPPER(COALESCE(contact.nom_contact,'')),' ',COALESCE(contact.prenom_contact,'')),'@@rel@@',COALESCE(liste_rel_personne_bien.label,''),'@@rel@@',contact.id_contact) SEPARATOR '@SEPARATOR@') 
					
					
					FROM personne_bien

					LEFT JOIN contact ON contact.id_contact=personne_bien.id_contact
					LEFT JOIN contact_profil ON contact_profil.id_contact_profil=personne_bien.id_contact_profil

					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

					WHERE personne_bien.id_demande=demande.id_demande

				
				)
				as contact_associee,

				(SELECT

					GROUP_CONCAT( DISTINCT CONCAT(COALESCE(bien.adresse_fr,''),'@@rel@@',COALESCE(liste_rel_personne_bien.label,''),'@@rel@@',bien.id_bien) SEPARATOR '@SEPARATOR@') 
					
					
					FROM personne_bien 

					LEFT JOIN bien ON bien.id_bien=personne_bien.id_bien 
					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

					WHERE personne_bien.id_demande=demande.id_demande  

				
				)
				as bien_associe 
				
              
			");

		$this->join("liste_demande_type","liste_demande_type.id=$this->table.id_type_demande","left");
		$this->join("liste_demande_statut","liste_demande_statut.id=$this->table.id_demande_statut","left");

		$this->join("liste_pole","liste_pole.id=$this->table.id_pole","left");
		
		$this->join("user_accounts as createur","createur.id=$this->table.id_user_create","left");
		$this->join("user_accounts as encharge","encharge.id=$this->table.id_utilisateur","left");

		$this->join("personne_bien","personne_bien.id_demande=$this->table.id_demande","left");


		/*if($request->getVar("statut_demande")&&!empty(trim($request->getVar("statut_demande"))))
		{
			$this->groupStart();
				$this->where("id_demande_statut",$request->getVar("statut_demande"));
			$this->groupEnd();
		}

			

		
		if($request->getVar("mes_demandes")&&!empty(trim($request->getVar("mes_demandes"))))
		{
			$this->groupStart();
				$this->where("demande.id_utilisateur",session()->get("loggedUserId"));
			$this->groupEnd();
		}


		if($request->getVar("homegrade")&&!empty(trim($request->getVar("homegrade"))))
		{
			$this->groupStart();
				$this->where("demande.id_utilisateur",25);
			$this->groupEnd();
		}*/
			
	

		if($request->getVar("statut_demande")&&!empty(trim($request->getVar("statut_demande"))))
		{
			$this->groupStart();
				$this->where("id_demande_statut",$request->getVar("statut_demande"));
			$this->groupEnd();
		}

		if($request->getVar("id_pole")&&!empty(trim($request->getVar("id_pole"))))
		{
			$this->groupStart();
				$this->where("id_pole",$request->getVar("id_pole"));
			$this->groupEnd();
		}

		if(
				$request->getVar("mes_demandes")&&!empty(trim($request->getVar("mes_demandes")))
				&&
				$request->getVar("homegrade")&&!empty(trim($request->getVar("homegrade")))
			)
			{
				$this->groupStart();
					$this->where("demande.id_utilisateur",session()->get("loggedUserId"));
					$this->orWhere("demande.id_utilisateur",25);
				$this->groupEnd();
			}

		
		elseif($request->getVar("mes_demandes")&&!empty(trim($request->getVar("mes_demandes"))))
		{
			$this->groupStart();
				$this->where("demande.id_utilisateur",session()->get("loggedUserId"));
			$this->groupEnd();

		}


		elseif($request->getVar("homegrade")&&!empty(trim($request->getVar("homegrade"))))
		{
			$this->groupStart();
				$this->where("demande.id_utilisateur",25);
			$this->groupEnd();

		}
			

		

		//itemSearch exists

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			$this->join("contact","contact.id_contact=personne_bien.id_contact","left");

			 $items=explode(" ",$request->getVar("itemSearch"));
			 $fieldSearchs=[
				 "$this->table.id_demande",
				 "contact.nom_contact",
				 "contact.prenom_contact",
				 "createur.nom",
				"createur.prenom",
				"liste_pole.label",
				
				"encharge.nom",
				"encharge.prenom",
				"(SELECT

					GROUP_CONCAT( DISTINCT CONCAT(COALESCE(bien.adresse_fr,''),'@@rel@@',COALESCE(liste_rel_personne_bien.label,''),'@@rel@@',bien.id_bien) SEPARATOR '@SEPARATOR@') 
					
					
					FROM personne_bien 

					LEFT JOIN bien ON bien.id_bien=personne_bien.id_bien 
					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

					WHERE personne_bien.id_demande=demande.id_demande  

				
			 )",
			"
			 (SELECT

					GROUP_CONCAT( DISTINCT CONCAT(concat(UPPER(COALESCE(contact.nom_contact,'')),' ',COALESCE(contact.prenom_contact,'')),'@@rel@@',COALESCE(liste_rel_personne_bien.label,''),'@@rel@@',contact.id_contact) SEPARATOR '@SEPARATOR@') 
					
					
					FROM personne_bien

					LEFT JOIN contact ON contact.id_contact=personne_bien.id_contact
					LEFT JOIN contact_profil ON contact_profil.id_contact_profil=personne_bien.id_contact_profil

					LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

					WHERE personne_bien.id_demande=demande.id_demande

				
				)"
				

				];
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
		 $this->groupBy("personne_bien.id_demande");

		 if(!is_null($orderBy))
		 $this->orderBy(sql_orderByDirection($orderBy,$orderDirection));
	 
		//$this->like("nom","Wil");
		return $this->paginate(50);
	}

	public function getListDemandesLinkDocument($request,$orderBy=NULL,$orderDirection=NULL,$id_document=0)
	{
					$this->select("
					$this->table.id_demande, 
					$this->table.date, 
					$this->table.id_type_demande,
					liste_demande_type.label as type,
					$this->table.id_demande_statut,
					liste_demande_statut.label as statut,
					$this->table.nom as sujet,
					createur.id as id_createur,
					createur.nom as nom_createur,
					createur.prenom as prenom_createur,
					encharge.id as id_encharge,
					encharge.nom as nom_encharge,
					encharge.prenom as prenom_encharge,
					id_utilisateur,
					id_personne_bien,
					(SELECT

						GROUP_CONCAT( DISTINCT CONCAT(concat(UPPER(COALESCE(contact.nom_contact,'')),' ',COALESCE(contact.prenom_contact,'')),'@@rel@@',COALESCE(liste_rel_personne_bien.label,''),'@@rel@@',contact.id_contact) SEPARATOR '@SEPARATOR@') 
						
						
						FROM personne_bien

						LEFT JOIN contact ON contact.id_contact=personne_bien.id_contact
						LEFT JOIN contact_profil ON contact_profil.id_contact_profil=personne_bien.id_contact_profil

						LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

						WHERE personne_bien.id_demande=demande.id_demande

					
					)
					as contact_associee,

					(SELECT

						GROUP_CONCAT( DISTINCT CONCAT(COALESCE(bien.adresse_fr,''),'@@rel@@',COALESCE(liste_rel_personne_bien.label,''),'@@rel@@',bien.id_bien) SEPARATOR '@SEPARATOR@') 
						
						
						FROM personne_bien 

						LEFT JOIN bien ON bien.id_bien=personne_bien.id_bien 
						LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

						WHERE personne_bien.id_demande=demande.id_demande  

					
					)
					as bien_associe 
					
				
				");

			$this->join("liste_demande_type","liste_demande_type.id=$this->table.id_type_demande","left");
			$this->join("liste_demande_statut","liste_demande_statut.id=$this->table.id_demande_statut","left");

			$this->join("user_accounts as createur","createur.id=$this->table.id_user_create","left");
			$this->join("user_accounts as encharge","encharge.id=$this->table.id_utilisateur","left");

			$this->join("personne_bien","personne_bien.id_demande=$this->table.id_demande","left");

			if($id_document>0)
			{
				$this->where("$id_document NOT IN (SELECT document_upload_lien.id_document FROM document_upload_lien WHERE document_upload_lien.id_demande=demande.id_demande)");

			}

			if($request->getVar("statut_demande")&&!empty(trim($request->getVar("statut_demande"))))
				$this->where("id_demande_statut",$request->getVar("statut_demande"));

				if(
					$request->getVar("mes_demandes")&&!empty(trim($request->getVar("mes_demandes")))
					&&
					$request->getVar("homegrade")&&!empty(trim($request->getVar("homegrade")))
				)
				{
					$this->groupStart();
						$this->where("demande.id_utilisateur",session()->get("loggedUserId"));
						$this->orWhere("demande.id_utilisateur",25);

					$this->groupEnd();
				}


			elseif($request->getVar("mes_demandes")&&!empty(trim($request->getVar("mes_demandes"))))
			{
				$this->where("demande.id_utilisateur",session()->get("loggedUserId"));

			}


			elseif($request->getVar("homegrade")&&!empty(trim($request->getVar("homegrade"))))
			{
				$this->where("demande.id_utilisateur",25);
			}
				

			//itemSearch exists

			if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
			{
				$this->join("contact","contact.id_contact=personne_bien.id_contact","left");

				$items=explode(" ",$request->getVar("itemSearch"));
				$fieldSearchs=[
					"$this->table.id_demande",
					"contact.nom_contact",
					"contact.prenom_contact",
					"createur.nom",
					"createur.prenom",
					
					"encharge.nom",
					"encharge.prenom",
					"(SELECT

						GROUP_CONCAT( DISTINCT CONCAT(COALESCE(bien.adresse_fr,''),'@@rel@@',COALESCE(liste_rel_personne_bien.label,''),'@@rel@@',bien.id_bien) SEPARATOR '@SEPARATOR@') 
						
						
						FROM personne_bien 

						LEFT JOIN bien ON bien.id_bien=personne_bien.id_bien 
						LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

						WHERE personne_bien.id_demande=demande.id_demande  

					
				)",
				"
				(SELECT

						GROUP_CONCAT( DISTINCT CONCAT(concat(UPPER(COALESCE(contact.nom_contact,'')),' ',COALESCE(contact.prenom_contact,'')),'@@rel@@',COALESCE(liste_rel_personne_bien.label,''),'@@rel@@',contact.id_contact) SEPARATOR '@SEPARATOR@') 
						
						
						FROM personne_bien

						LEFT JOIN contact ON contact.id_contact=personne_bien.id_contact
						LEFT JOIN contact_profil ON contact_profil.id_contact_profil=personne_bien.id_contact_profil

						LEFT JOIN liste_rel_personne_bien ON liste_rel_personne_bien.id=personne_bien.rel_personne_bien

						WHERE personne_bien.id_demande=demande.id_demande

					
					)"
					

					];
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
			$this->groupBy("personne_bien.id_demande");
			if(!is_null($orderBy))
			$this->orderBy(sql_orderByDirection($orderBy,$orderDirection));

			//$this->like("nom","Wil");
			return $this->paginate(50);
	}

	public function getDemande($id_demande)
	{
		$this->select("
				$this->table.*,
                demande_caracteristique.*, 
				personne_bien.id_personne_bien,
				personne_bien.id_contact,
				personne_bien.id_contact_profil,
				personne_bien.id_bien,
				concat(user_create.prenom,' ',user_create.nom) as user_create,
				concat(user_modification.prenom,' ',user_modification.nom) as user_modification,
				demande.date_insert as date_insert_log,
				demande.date_modification as date_modification_log,
				demande.id_demande as id_demande,
				liste_demande_statut.label as demande_statut_label,
			");


			/*$this->join("liste_demande_type","liste_demande_type.id=$this->table.id_type_demande","left");
			$this->join("liste_demande_statut","liste_demande_statut.id=$this->table.id_demande_statut","left");
			
			$this->join("user_accounts as createur","createur.id=$this->table.id_user_create","left");
			$this->join("user_accounts as encharge","encharge.id=$this->table.id_utilisateur","left");
			$this->join("user_accounts as backup","backup.id=$this->table.id_utilisateur_2","left");*/

			$this->join("demande_caracteristique","demande_caracteristique.id_demande=$this->table.id_demande","left");
	
			$this->join("personne_bien","personne_bien.id_demande=$this->table.id_demande","left");

			$this->join("user_accounts as user_create","user_create.id=demande.id_user_create","left");
			$this->join("user_accounts as user_modification","user_modification.id=demande.id_user","left");
			$this->join("liste_demande_statut","liste_demande_statut.id=$this->table.id_demande_statut","left");

			$this->where("$this->table.id_demande",$id_demande);
			//$this->groupBy("personne_bien.id_demande");

			return $this->get()->getRow();

	}

	public function getContacts($id_demande)
	{
		$builder=$this->db->table("personne_bien");
		$builder->select("
				contact.nom_contact,
				contact.prenom_contact,
				contact.id_contact,
				liste_rel_personne_bien.label as relation_bien
				");

		$builder->join("contact","contact.id_contact=personne_bien.id_contact","left");
		$builder->join("liste_rel_personne_bien","liste_rel_personne_bien.id=personne_bien.rel_personne_bien","left");

		$builder->where("personne_bien.id_demande",$id_demande);

		$builder->groupBy("personne_bien.id_contact");
		return $builder->get()->getResult();	
	}

	public function getDemandeurs($id_demande)
	{
		$builder=$this->db->table("personne_bien");

		$builder->select("
            personne_bien.*,
            contact.*,
            contact_profil.email, contact_profil.email2,
            contact_profil.telephone, contact_profil.telephone2,
            contact_profil.adresse, contact_profil.localite, contact_profil.pays,
            MAX(rel_personne_bien) as rel_personne_bien,
			liste_rel_personne_bien.label as statut_relation_bien
        ");

		$builder->join("contact","contact.id_contact=personne_bien.id_contact","left");
		$builder->join("contact_profil","contact_profil.id_contact_profil=personne_bien.id_contact_profil","left");
		$builder->join("liste_rel_personne_bien","liste_rel_personne_bien.id=personne_bien.rel_personne_bien","left");


		$builder->where("personne_bien.id_demande",$id_demande);
        // $builder->groupStart();
        //     $builder->where("contact.id_contact>",0);
        //     $builder->orWhere("contact.id_contact is null");
        // $builder->groupEnd();		

		$builder->groupBy("personne_bien.id_contact");
		$builder->orderBy("personne_bien.rel_personne_bien ASC");

        $demandeurs = $builder->get()->getResult();

		return $demandeurs;
	}

	
	

	public function getAdresses($id_personne)
	{
		$builder=$this->db->table("personne_adresse");
		
		$builder->where("personne_adresse.id_personne",$id_personne);
		
		return $builder->get()->getResult();
	}



	public function getEmailsById_demande($id_demande)
	{
		$emailsPossibles=[];

		$personnes=$this->db->getDemandeurs($id_demande);

		if(!empty($personnes))
		{
			foreach($personnes as $personne)
			{
				$adresses=$this->getAdresses($personne->id_personne);
			
			}
		}

		
		return $emailsPossible;
	}

	public function getEmailsById_personne($id_personne)
	{
		$builder=$this->db->table("personne_adresse");
		$builder->where("personne_adresse.id_personne",$id_personne);
		
		return $builder->get()->getResult();
	}


	public function getBiens($id_demande)
	{
		$builder=$this->db->table("personne_bien");
		$builder->select("
                    *,
					bien_caracteristique.id as id_bien_caracteristique,
					personne_bien.id_bien,

				");

		$builder->join("bien","bien.id_bien=personne_bien.id_bien","left");
		$builder->join("bien_caracteristique","bien.id_bien=bien_caracteristique.id_bien","left");
		/*$builder->join("liste_bien_type","liste_bien_type.id=bien.id_type","left");
		$builder->join("liste_bien_nombre_logement","liste_bien_nombre_logement.id=bien.id_nombre_logement","left");
		$builder->join("liste_bien_chauffage","liste_bien_chauffage.id=bien.id_chauffage","left");
		$builder->join("liste_type_chauffage","liste_type_chauffage.id=bien.id_type_chauffage","left");
		$builder->join("liste_type_cuisiniere","liste_type_cuisiniere.id=bien.id_type_cuisiniere","left");
		$builder->join("liste_type_four","liste_type_four.id=bien.id_type_four","left");
		$builder->join("liste_type_eau","liste_type_eau.id=bien.id_type_eau","left");
		$builder->join("liste_type_peb","liste_type_peb.id=bien.id_certificat_peb","left");*/

		$builder->where("personne_bien.id_demande",$id_demande);
		$builder->where("bien.id_bien>",0);


		$builder->groupBy("personne_bien.id_bien");
		return $builder->get()->getResult();	
	}


	public function getFormOneBien($id_bien)
	{
		$builder=$this->db->table("bien");
		$builder->select("
					*

				");

		$builder->where("bien.id_bien",$id_bien);


		
		return $builder->get()->getRow();	
	}

	public function getFormOneContact($id_contact)
	{
		$builder=$this->db->table("contact");
		$builder->select("
					*

				");

		$builder->where("contact.id_contact",$id_contact);


		
		return $builder->get()->getRow();	
	}


	public function getFormOneContactProfil($id_contact_profil)
	{
		$builder=$this->db->table("contact_profil");
		$builder->select("
					*

				");

		$builder->where("contact_profil.id_contact_profil",$id_contact_profil);


		
		return $builder->get()->getRow();	
	}


	public function getFil($id_demande)
	{
		$builder = $this->db->table("email_outlook_lien");
        $builder->select("email_outlook_lien.*");
        $builder->select("email_outlook.*");
        $replace = 'email_outlook.body_content';
        foreach(signatures_all() as $signature) :
           $replace = "REPLACE(" . $replace . ", '" . $signature . "', '')";
        endforeach;
        $replace = "REGEXP_REPLACE($replace, '(?s)<!--.*-->', '')"; // remove html comment inside email because css bug
        $replace = $replace.' as body_content';
        $builder->select($replace);

    	// $builder->select("
        //     REGEXP_REPLACE(
        //         REGEXP_REPLACE(
        //             REGEXP_REPLACE(
        //                 REGEXP_REPLACE(
        //                     email_outlook.body_content, 
        //                     '(min-|max-)?height\s?:\s?[a-zA-Z0-9_]*.?[a-zA-Z0-9_]*%?(\s|;|\")', 
        //                     '\\\1height:auto\\\2'),    
        //                 '(min-|max-)?width\s?:\s?[a-zA-Z0-9_]*.?[a-zA-Z0-9_]*%?(\s|;|\")', 
        //                 '\\\1width:auto\\\2'),
        //             'height\s?=\s?\".[^\"]*\"', 
        //             ''),
        //         'width\s?=\s?\".[^\"]*\"',
        //         ''
        //     ) as body_content
        // ");
		
        // $builder->select("
        //     REGEXP_REPLACE(
        //         REGEXP_REPLACE(
        //             REGEXP_REPLACE(
        //                 REGEXP_REPLACE(
        //                     email_outlook.body_preview, 
        //                     '(min-|max-)?height\s?:\s?[a-zA-Z0-9_]*.?[a-zA-Z0-9_]*%?(\s|;|\")', 
        //                     '\\1height:auto\\2'),    
        //                 '(min-|max-)?width\s?:\s?[a-zA-Z0-9_]*.?[a-zA-Z0-9_]*%?(\s|;|\")', 
        //                 '\\1width:auto\\2'),
        //             'height\s?=\s?\".[^\"]*\"', 
        //             ''),
        //         'width\s?=\s?\".[^\"]*\"',
        //         ''
        //     ) as body_preview
        // ");

		// $builder->select("body_preview");

		$builder->join("email_outlook","email_outlook.id_primary=email_outlook_lien.id_email","left");
		$builder->where("email_outlook_lien.id_demande", $id_demande);
		$builder->orderBy("created_datetime", 'DESC');
       return $messages = $builder->get()->getResult();

        // $i = 0;
        // foreach($messages as $message) :
        //     if(!empty($match)) debugd($match);
        //     if($i==0 || empty($message->body_content)) : 
        //         $i++; 
        //         continue; 
        //     endif;
        //     for($j=$i-1; $j>=0; $j--) :
        //         if(!empty($messages[$j]->body_content) && strstr($messages[$i]->body_content, $messages[$j]->body_content)) :
        //             $messages[$i]->body_content = str_replace($messages[$j]->body_content, '', $messages[$i]->body_content);
        //             break;
        //         endif;
        //     endfor;
        //     $i++;
        // endforeach;

		// return $messages;
	}


	public function getRdvs($id_demande)
	{
		$builder=$this->db->table("demande_rdv");
		$builder->select(
			"
                demande_rdv.*,
				titre,
				date_rdv_debut,
				date_rdv_fin,
				TIMEDIFF(date_rdv_fin, date_rdv_debut) as calcul_duree,
				duree,
				liste_rdv_type.label as type,
				liste_rdv_statut.label as statut

			
			"

		);
		$builder->where("demande_rdv.id_demande",$id_demande);

		$builder->join("rdv","demande_rdv.id_rdv=rdv.id_rdv","left");
		$builder->join("liste_rdv_type","rdv.id_type_rdv=liste_rdv_type.id","left");
		$builder->join("liste_rdv_statut","rdv.id_statut_rdv=liste_rdv_statut.id","left");

		$builder->orderBy("rdv.date_rdv_debut DESC");
		return $builder->get()->getResult();
	}

	public function getTaches($id_demande)
	{
		$builder=$this->db->table("demande_tache");
		$builder->select("
            demande_tache.*,
            tache.titre,
            tache.sujet,
            tache.date_tache,
            liste_tache_type.label as type,
            liste_tache_statut.label as statut
        ");
		$builder->join("tache","demande_tache.id_tache=tache.id_tache","left");
		$builder->join("liste_tache_type","tache.id_type_tache=liste_tache_type.id","left");
		$builder->join("liste_tache_statut","tache.id_statut_tache=liste_tache_statut.id","left");
		$builder->where("demande_tache.id_demande",$id_demande);
		$builder->orderBy("tache.date_tache DESC");
		return $builder->get()->getResult();
	}

	
	public function get_personne_bien_by_id_bien($id_bien)
	{
		$builder=$this->db->table("personne_bien");
		$builder->where("id_bien",$id_bien);

		$builder->groupBy("personne_bien.id_contact");

		$result=$builder->get()->getResult();

		

		if(!empty($result))
		{
			return $result;
		}
		return NULL;
	}

	public function get_rel_personne_by_id_bien_id_contact($id_bien,$id_contact)
	{
		

		$builder=$this->db->table("personne_bien");
		$builder->where("id_bien",$id_bien);

		$results=$builder->get()->getResult();

		if(!empty($results))
		{
			foreach($results as $result)
			{
				if($result->rel_personne_bien>0)
					return $result->rel_personne_bien;
			}
		}

		return null;

	}

	public function get_personne_by_id_bien($id_bien)
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
		

		$array_fields_index[]="rel_personne_bien";

		//Récupérer toutes les données possibles
		$builder=$this->db->table("personne_bien");
		$builder->where("personne_bien.id_bien",$id_bien);

		$builder->select(
				
			implode(",",$array_fields_index)
		);

		$builder->join("contact","contact.id_contact=personne_bien.id_contact","left");
		$builder->join("contact_profil","contact_profil.id_contact_profil=personne_bien.id_contact_profil","left");

		
		$builder->groupBy("personne_bien.id_contact");
		
		return $builder->get()->getResult();
	}


	public function get_personne_by_mail($mail)
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
		

		$array_fields_index[]="rel_personne_bien";

		//Récupérer toutes les données possibles
		$builder=$this->db->table("contact_profil");
		//$builder->where("personne_bien.id_bien",$id_bien);

		$builder->where("contact_profil.email",$mail);



		$builder->select(
				
			implode(",",$array_fields_index)
		);

		$builder->join("contact","contact.id_contact=contact_profil.id_contact","left");
		$builder->join("personne_bien","personne_bien.id_contact_profil=contact_profil.id_contact_profil","left");
		

		
		$builder->groupBy("personne_bien.id_contact");
		
		return $builder->get()->getResult();
	}



	public function get_personne_bien_by_id_contact($id_contact)
	{
		$builder=$this->db->table("personne_bien");
		$builder->where("id_contact",$id_contact);

		$result=$builder->get()->getResult();

		if(!empty($result))
		{
			return $result;
		}
		return NULL;
	}

	public function get_personne_bien_by_id_demande($id_demande)
	{
		$builder=$this->db->table("personne_bien");
		$builder->where("id_demande",$id_demande);

		$result=$builder->get()->getResult();

		if(!empty($result))
		{
			return $result;
		}
		return NULL;
	}

	
	
	public function get_id_personne_bien($id_demande)
	{
		$builder=$this->db->table("personne_bien");
		$builder->where("id_demande",$id_demande);

		$result=$builder->get()->getRow();

		if(!empty($result))
		{
			return $result->id_personne_bien;
		}
		return NULL;

	}

	public function delete_bien($id_personne_bien)
	{
		$builder=$this->db->table("personne_bien");

		$builder->where("id_personne_bien",$id_personne_bien);

		$data["id_bien"]=0;

		return $builder->update($data);
	}

	public function delete_contact($id_personne_bien)
	{
		$builder=$this->db->table("personne_bien");
		$builder->where("id_personne_bien",$id_personne_bien);

		$data["id_contact"]=0;
		$data["id_contact_profil"]=0;

		unset($data["id_personne_bien"]);
		return $builder->update($data);
	}


	public function statut_demande()
	{
		$builder=$this->db->table("liste_demande_statut");
		$builder->orderBy("rank,label");

		return $builder->get()->getResult();
	}

	public function poles()
	{
		$builder=$this->db->table("liste_pole");
		$builder->orderBy("rank,label");
		$builder->where("is_actif",1);
		return $builder->get()->getResult();
	}

	public function insert_data($data,$table)
	{
		$builder=$this->db->table($table);
		$builder->insert($data);

		return $this->db->insertID();

	}

	public function update_data($table,$data,$where)
	{
		$builder=$this->db->table($table);
		$builder->where($where);
		$builder->update($data);
	}

	public function get_user_encharge($id_demande)
	{
		$builder=$this->db->table("demande");

		$builder->where("id_demande",$id_demande);

		$demande=$builder->get()->getRow();

		if(isset($demande->id_utilisateur)&&$demande->id_utilisateur>0)
		{
			return $demande->id_utilisateur;
		}
		else
		{
			return session()->get('loggedUserId');
		}
	}


	public function get_email_demandeur($id_demande)
	{
		$builder=$this->db->table("personne_bien");

		

		$builder->where("personne_bien.id_demande",$id_demande);

		$builder->join("contact_profil","personne_bien.id_contact_profil=contact_profil.id_contact_profil");

		$emails=$builder->get()->getResult();

		if(!empty($emails))
		{
			$emails_possible=[];
			foreach($emails as $email)
			{
				if(!empty($email->email))
				{
					array_push($emails_possible,$email->email);

				}

				return implode(",",$emails_possible);
			}
		}
		else{
			return NULL;
		}
	}


	public function saveDataDelivreContact($indexesForm,$posts_brut,$id_demande=NULL)
    {
		//debugd($id_contact);
		//debugd($posts_brut);
        $dataView = new DataViewConstructor(); 
       //descriptor
        $fields=$this->DataViewModel->getFields();

        //--------------------------1.traitement de contact --------------------

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
       
		//on récuper les champs en index
		$data_insert_contact_with_index=$dataView->prepareData($indexesForm,$data_insert_contact,$fields,"contact",true,false);

		//On convertit les champs en fieldsql
        $data_insert_contact=$dataView->prepareData($indexesForm,$data_insert_contact,$fields,"contact",true);

        $builder=$this->db->table("contact");

        if($id_contact>0)
        {
			//on vérifie s'il a des valeurs différents avant l'insertion
			$data_changes=$this->DataViewModel->set_log_fiche("demande",$data_insert_contact_with_index,"id_contact",$id_contact,"contact","contact");

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
			$data_changes=$this->DataViewModel->set_log_fiche_insert($data_insert_contact_with_index);
			$this->DataViewModel->set_logs_fiche_insert_bd("contact",$data_changes,date("Y-m-d H:i:s"),$id_contact,$id_contact);

        }

		 //-----------------------2. Traitement de ou des profils de contact existant----------------------------
		 $dataView = new DataViewConstructor(); 
		 //descriptor
		  $fields=$this->DataViewModel->getFields();
  
		  $is_insert=FALSE;
		  $contact_exclude=["pk_value","id_contact"];
			 
			 $contact_profil_exclude=["is_multiple","id_contact","id_contact_profil","contact_profil_organisme"];   
			 
			 

			$contacts_profil=$posts_brut;
			
			//debugd($contacts_profil);

				 $id_contact_profil=$contacts_profil["id_contact_profil"];
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
 
				
				 $builder=$this->db->table("contact_profil");

				
				 if($id_contact_profil>0)
				 {
					
					$data_changes=$this->DataViewModel->set_log_fiche("demande",$data_insert_contact_profil_with_index,"id_contact_profil",$id_contact_profil,"contact_profil","contact");

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
 
			   
// die();
				 $builder=$this->db->table("contact");
				 $data_insert_contact["date_modification"]=date("Y-m-d H:i:s");
			 $data_insert_contact["id_user"]=session()->get("loggedUserId");
			 $builder->where("id_contact",$id_contact);
			 unset($data_insert_contact["id_contact"]);
			 $builder->update($data_insert_contact);
 
		
		/*---------- On met à jour si nécessaire, la relation avec la demande -------------*/

				 /*debug($posts_brut["id_personne_bien"]);
				 debug($id_contact);
				 debugd($id_contact_profil);*/

		//On récupére id_bien
		$id_bien=NULL;
		$rel_personne_bien=NULL;
		$builder=$this->db->table("personne_bien");
		$biens=$builder->where("id_demande",$id_demande)->get()->getResult();

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
		
		$id_personne_bien=$posts_brut["id_personne_bien"];

		if($posts_brut["id_personne_bien"]>0)
		{
			
			$data_changes=$this->DataViewModel->set_logs_relation_bien($id_personne_bien,$posts_brut["rel_personne_bien"]);

			$builder=$this->db->table("personne_bien");
			$builder->where("id_personne_bien",$posts_brut["id_personne_bien"]);

			$data_rel_personne["id_contact"]=$id_contact;
			$data_rel_personne["id_contact_profil"]=$id_contact_profil;
			$data_rel_personne["rel_personne_bien"]=$posts_brut["rel_personne_bien"];
			$data_rel_personne["id_bien"]=$id_bien;
			unset($data_rel_personne["id_personne_bien"]);
			$builder->update($data_rel_personne);

			$this->DataViewModel->set_logs_fiche_insert_bd("demande",$data_changes,date("Y-m-d H:i:s"),$id_demande,$id_personne_bien);




		}
		
		//On doit vérifier si la relation existe dans le système


 
 
		 return $id_demande;
	}

	

	public function saveDataDelivreDemande($indexesForm,$posts_brut,$id_demande=NULL)
    {
		//debugd($posts_brut);
		$id_demande=$posts_brut["id_demande"];
		$id_personne_bien=$posts_brut["id_personne_bien"];

        $dataView = new DataViewConstructor(); 
        //descriptor
        $fields=$this->DataViewModel->getFields();

		//On classe les datas en deux parties

		//debugd($posts_brut);

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

		//on récuper les champs en index
		$data_insert_demande_with_index=$dataView->prepareData($indexesForm,$data_insert_demande,$fields,"demande",true,false);
        //debug($indexesForm);

		//on récuper les champs en champs mysql
        $data_insert_demande=$dataView->prepareData($indexesForm,$data_insert_demande,$fields,"demande",true);

        $builder=$this->db->table("demande");

        if($id_demande>0)
        {
			$data_changes=$this->DataViewModel->set_log_fiche("demande",$data_insert_demande_with_index,"id_demande",$id_demande,"demande","demande");
			$data_insert_demande["date_modification"]=date("Y-m-d H:i:s");
            $data_insert_demande["id_user"]=session()->get("loggedUserId");
            $builder->where("id_demande",$id_demande);
			unset($data_insert_demande["id_demande"]);
            $builder->update($data_insert_demande);
			$this->DataViewModel->set_logs_fiche_insert_bd("demande",$data_changes,date("Y-m-d H:i:s"),$id_demande,$id_demande);

            // email automatique update demande
            $OutlookLibrary = new Myoutlook_lib();
            if(!empty($data_changes['demande_utilisateur'])) :
                $OutlookLibrary->demande_assign_notification($id_demande);
            else :
                $OutlookLibrary->demande_update_notification($id_demande);
            endif;
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
		
        // echo "ppo";
        //debugd($data_insert_demande_caracteristique);

        if(!empty($data_insert_demande_caracteristique))
        {
			$data_insert_demande_caracteristique["id_demande"]=$id_demande;
            $builder=$this->db->table("demande_caracteristique");

			if($id_demande_caracteristique>0)
            {
                $data_changes=$this->DataViewModel->set_log_fiche("demande",$data_insert_demande_caracteristique_with_index,"id_demande_caracteristique",$id_demande_caracteristique,"demande_caracteristique","demande");

                $builder->where("id_demande_caracteristique",$id_demande_caracteristique);
                unset($data_insert_demande_caracteristique["id_demande_caracteristique"]);
                $builder->update($data_insert_demande_caracteristique);
            // debugd($data_insert_demande_caracteristique);
                $this->DataViewModel->set_logs_fiche_insert_bd("demande",$data_changes,date("Y-m-d H:i:s"),$id_demande,$id_demande_caracteristique);

            }
            else
            {
                $data_insert_demande_caracteristique["id_demande"]=$id_demande;
                $builder->insert($data_insert_demande_caracteristique);
                //debugd($data_insert_demande_caracteristique);
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

		return $id_demande;
	}

	public function saveDataDelivreBien($indexesForm,$posts_brut,$id_demande=NULL)
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

		/*debugd($posts_brut);
		debugd($id_bien);


		debugd($data_insert_bien);*/

        $builder=$this->db->table("bien");

        if($id_bien>0)
        {
			$data_changes=$this->DataViewModel->set_log_fiche("demande",$data_insert_bien_with_index,"id_bien",$id_bien,"bien","bien");

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
					$data_changes=$this->DataViewModel->set_log_fiche("demande",$data_insert_bien_caracteristique_with_index,"bien_caracteristique.id",$id_bien_caracteristique,"bien_caracteristique","bien");

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
 
 
		/*---------- On met à jour si nécessaire, la relation avec la demande -------------*/

				 /*debug($posts_brut["id_personne_bien"]);
				 debug($id_bien);
				 debugd($id_bien_caracteristique);*/

		/*if($posts_brut["id_personne_bien"]>0)
		{
			$builder=$this->db->table("personne_bien");
			$builder->where("id_personne_bien",$posts_brut["id_personne_bien"]);
			$data_rel_personne["id_bien"]=$id_bien;
			unset($data_rel_personne["id_personne_bien"]);
			$builder->update($data_rel_personne);
		}*/

		//on met à jour si nécessaire
		//1. On regarde si id_bien existe déjà dans personne_rel_bien

		$builder=$this->db->table("personne_bien");
		$builder->where("id_demande",$id_demande);
		$data_id_bien["id_bien"]=$id_bien;
		$builder->update($data_id_bien);
		
		
		
		


 
 
		 return $id_demande;
	}


	public function get_localite_complete($cp)
	{
		$builder=$this->db->table("liste_localite");
		$builder->where("cp",$cp);

		$label=$builer->get()->getRow();

		if(!empty($label))
		{
			return $label->label;
		}
		else
		{
			return null;		
		}
	}

	public function set_statut_demande($id_demande,$id_demande_statut,$id_demande_statut_old)
	{
		
		$builder=$this->db->table("demande");
		$builder->where("id_demande",$id_demande);
		$data["id_demande_statut"]=$id_demande_statut;
		$data["date_modification"]=date("Y-m-d H:i:s");
		$data["id_user"]=session()->loggedUserId;

		$builder->update($data);

		$demande=$this->getDemande($id_demande);

		$id_demande_statut=$demande->demande_statut_label;
		$data_changes["demande_statut"]=["value_old"=>$id_demande_statut_old,"value_new"=>$id_demande_statut];

		$this->DataViewModel->set_logs_fiche_insert_bd("demande",$data_changes,date("Y-m-d H:i:s"),$id_demande,$id_demande);





	}
	
}
