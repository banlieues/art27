<?php

namespace Ticket\Models;
use CodeIgniter\Model;

class TicketModel extends Model
{
	protected $table="ticket";
	protected $primaryKey = 'id_ticket';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';



	protected $fields;



	public function __construct()
	{
	   	parent::__construct();

		  
    

	}

	public function getFields(){ return $this->fields;}

	public function getTable(){return $this->table;}

	public function getListTicket($id_partenaire_culturel=0,$annee_select=0,$mois_select="0",$id_ticket=0,$request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select(
			"
				ticket.id_ticket,
				convention_barcode.id_convention_barcode,
				crm_list_barcode_statut.label as barcode_statut,
				convention_barcode.statut,
				crm_list_ticket_statut.label as ticket_statut,
				ticket.statut as statut_ticket,
				
				convention_barcode.id_partenaire_social,
				partenaire_social.nom_partenaire_social,
				partenaire_social.numero_partenaire_social,

				partenaire_culturel.numero_partenaire_culturel,
				partenaire_culturel.nom_partenaire_culturel,
				partenaire_culturel.id_partenaire_culturel,

				convention_barcode.mois as mois_convention,
				convention_barcode.annee as annee_convention,
				ticket.mois as mois_ticket,
				ticket.annee as annee_ticket,
				convention_barcode.created_at,
				convention_barcode.created_by,
				convention_barcode.barcode,
				convention_barcode.num_code,
				ticket.num_code as num_code_ticket,
				convention_barcode.num_code,
				convention_barcode.commentaire,
				ticket.commentaire as commentaire_ticket,
				concat(crm_list_barcode_mois.label,' ',convention_barcode.annee) as label_mois,
				concat(crm_list_barcode_mois_ticket.label,' ',ticket.annee) as label_mois_ticket,
				concat(creator.prenom,' ',creator.nom) as user_create,
				ticket.url_file,
				ticket.date_created as date_scanning,
				concat(scannor.prenom,' ',scannor.nom) as scannor,

				



			"
			
			);

		$this->join("convention_barcode","convention_barcode.id_ticket=ticket.id_ticket","left");
		$this->join("crm_list_barcode_statut","crm_list_barcode_statut.id=convention_barcode.statut","left");
		$this->join("crm_list_ticket_statut","crm_list_ticket_statut.id=ticket.statut","left");
		$this->join("user_accounts as creator","creator.id=convention_barcode.created_by","left");
		$this->join("user_accounts as scannor","scannor.id=ticket.id_user","left");
		$this->join("partenaire_social","partenaire_social.id_partenaire_social=convention_barcode.id_partenaire_social","left");
		$this->join("partenaire_culturel","partenaire_culturel.id_partenaire_culturel=ticket.id_partenaire_culturel","left");
		$this->join("crm_list_barcode_mois ","crm_list_barcode_mois.ref=convention_barcode.mois","left");
		$this->join("crm_list_barcode_mois as crm_list_barcode_mois_ticket","crm_list_barcode_mois_ticket.ref=ticket.mois","left");



		if($id_partenaire_culturel!="0")
			$this->where("ticket.id_partenaire_culturel",$id_partenaire_culturel);

		if($annee_select>0)
			$this->where("ticket.annee",$annee_select);

		if($mois_select!="0")
			$this->where("ticket.mois",$mois_select);

		if($id_ticket>0)
			$this->where("ticket.id_ticket",$id_ticket);


		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				"crm_list_barcode_statut.label as barcode_statut",
				"convention_barcode.statut",
				"partenaire_social.nom_partenaire_social",
				"convention_barcode.mois",
				"convention_barcode.annee",
				"convention_barcode.created_at",
				"concat(crm_list_barcode_mois.label,' ',convention_barcode.annee)",

				
				
			
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
		 
		if($id_ticket>0)
		{
			return $this->get()->getRow();
		}
		else
		{
			return $this->paginate(50);

		}

	}


	public function get_partenaire_culturels()
	{
		$builder=$this->db->table("partenaire_culturel");
		$builder->select("id_partenaire_culturel,numero_partenaire_culturel,nom_partenaire_culturel");
		$builder->orderBy("nom_partenaire_culturel");
		return $builder->get()->getResult();
	}

	public function getListTicketAjouterDemande($request,$orderBy=NULL,$orderDirection=NULL,$id_demande=NULL)
	{
		$this->select("
				

				ticket_upload.id as id_ticket,
				ticket_upload.name,
				ticket_upload.url_file,
				ticket_upload.date_created,
				ticket_upload.commentaire,
				ticket_upload.id_type,
				list_types_depot.name as name_type,

				CONCAT(user_accounts.prenom,' ',user_accounts.nom) as user,


				(SELECT GROUP_CONCAT(ticket_upload_lien.id_demande) FROM ticket_upload_lien WHERE ticket_upload_lien.id_ticket=ticket_upload.id) as id_demandes
				
			
				"
			);

		/*if(!is_null($id_demande))
		{
			$this->where("$id_demande NOT IN (SELECT ticket_upload_lien.id_demande FROM ticket_upload_lien WHERE ticket_upload_lien.id_ticket=ticket_upload.id)");
		}*/

		//$this->join("demande","demande.id_demande=ticket.id_demande","left");
		$this->join("list_types_depot","list_types_depot.id=ticket_upload.id_type","left");
		$this->join("user_accounts","user_accounts.id=ticket_upload.id_user","left");
		
		$this->where("display","1");

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				 "ticket_upload.url_file",
				 "ticket_upload.name",
				 "ticket_upload.commentaire",
				 "list_types_depot.name",
				 "CONCAT(user_accounts.prenom,' ',user_accounts.nom)"
					
				 	
			
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

	public function getListTicketGererDemande($request,$orderBy=NULL,$orderDirection=NULL,$id_demande=NULL)
	{
		$this->select("
				

				ticket_upload.id as id_ticket,
				ticket_upload.name,
				ticket_upload.url_file,
				ticket_upload.date_created,
				ticket_upload.commentaire,
				ticket_upload.id_type,
				list_types_depot.name as name_type,

				CONCAT(user_accounts.prenom,' ',user_accounts.nom) as user,


				(SELECT GROUP_CONCAT(ticket_upload_lien.id_demande) FROM ticket_upload_lien WHERE ticket_upload_lien.id_ticket=ticket_upload.id) as id_demandes
				
			
				"
			);

		if(!is_null($id_demande))
		{
			$this->where("$id_demande IN (SELECT ticket_upload_lien.id_demande FROM ticket_upload_lien WHERE ticket_upload_lien.id_ticket=ticket_upload.id)");
		}

		//$this->join("demande","demande.id_demande=ticket.id_demande","left");
		$this->join("list_types_depot","list_types_depot.id=ticket_upload.id_type","left");
		$this->join("user_accounts","user_accounts.id=ticket_upload.id_user","left");
		
		$this->where("display","1");

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				 "ticket_upload.url_file",
				 "ticket_upload.name",
				 "ticket_upload.commentaire",
				 "list_types_depot.name",
				 "CONCAT(user_accounts.prenom,' ',user_accounts.nom)"
					
				 	
			
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
		 
		return $this->paginate(200);
	}


	public function getTicket($id_ticket)
	{
		$this->select("
				
				ticket_upload.id as id_ticket,
				ticket_upload.name,
				ticket_upload.url_file,
				ticket_upload.date_created,
				ticket_upload.commentaire,
				ticket_upload.id_type,
				list_types_depot.name as name_type,

				CONCAT(user_accounts.prenom,' ',user_accounts.nom) as user,


				(SELECT GROUP_CONCAT(ticket_upload_lien.id_demande) FROM ticket_upload_lien WHERE ticket_upload_lien.id_ticket=ticket_upload.id) as id_demandes

			
				"
			);

			
		//$this->join("ticket_upload_lien","ticket_upload_lien.id_ticket=ticket_upload.id","left");
		//$this->join("demande","demande.id_demande=ticket_upload_lien.id_demande","left");
		$this->join("list_types_depot","list_types_depot.id=ticket_upload.id_type","left");
		$this->join("user_accounts","user_accounts.id=ticket_upload.id_user","left");
		
		$this->where("display","1");

		
		 
		return $this->find($id_ticket);
	}

	public function getListTickets($id_demande=NULL, $id_message=NULL)
	{
	/*	$builder=$this->db->table("email_outlook_lien");
		$builder->join("ticket","ticket.id_message=email_outlook_lien.id_email");
		if(!is_null($id_demande))
		{
			$builder->where("email_outlook_lien.id_demande",$id_demande);
		}

		if(!is_null($id_message))
		{
			$builder->where("email_outlook_lien.id_email",$id_message);
		}*/

		$builder=$this->db->table("ticket_upload_lien");

		$builder->join("ticket_upload","ticket_upload.id=ticket_upload_lien.id_ticket");
		$builder->groupBy("ticket_upload_lien.id_ticket");
		if($id_demande>0)
			$builder->where("ticket_upload_lien.id_demande",$id_demande);

		if($id_message>0)
			$builder->where("ticket_upload_lien.id_message",$id_message);

		return $builder->get()->getResult();

	}

	public function upload_file($data)
	{
		$data_insert_ticket=$data;
		

		$builder=$this->db->table("ticket");
		$builder->insert($data_insert_ticket);
		$id_ticket=$this->db->insertId();



		return $id_ticket;
	}


	public function associe_demande($id_ticket,$id_demande)
	{
			$data_insert["id_ticket"]=$id_ticket;
			$data_insert["id_demande"]=$id_demande;
			$data_insert["id_user"]=session()->get("loggedUserId");
			$data_insert["date_created"]=date("Y-m-d H:i:s");

			$builder=$this->db->table("ticket_upload_lien");
			$builder->insert($data_insert);
	}

	public function get_liste_type_ticket()
	{
		$builder=$this->db->table("list_types_depot");
		$builder->where("is_actif",1);
		$builder->orderBy("rank,name");

		return $builder->get()->getResult();
	}

	
	public function setIdtype($id_ticket,$id_type)
	{
		$data_insert["id_type"]=$id_type;
		$builder=$this->db->table("ticket_upload");
		$builder->where("id",$id_ticket);
	
		$builder->update($data_insert);
	}


	public function setCommentaire($id_ticket,$commentaire)
	{
		$builder=$this->db->table("ticket");
		$builder->where("id_ticket",$id_ticket);
		$data["commentaire"]=$commentaire;

		$builder->update($data);
	}

	public function setNumCode($id_ticket,$num_code)
	{

		//1. avant de changer je récupère s'il y a une relation
		

		//1. Je recherche si le codebarre est présent ou pas
		$num_code=trim($num_code);

		$builder=$this->db->table("convention_barcode");
		$builder->select("num_code,id_convention_barcode");
		$builder->where("num_code",$num_code);
		$result=$builder->get()->getRow();
		
		$builder=$this->db->table("ticket");
		$builder->where("id_ticket",$id_ticket);
		$data["num_code"]=$num_code;

		if(!empty($result))
		{
			$data["statut"]=2;

		}
		else
		{
			$data["statut"]=1;
		}

		$builder->update($data);

		//j'efface la relation existante 
		$builder=$this->db->table("convention_barcode");
		$builder->where("id_ticket",$id_ticket);
		$data_ticket["id_ticket"]=0;
		$builder->update($data_ticket);
		
		//j'implantr la nouvelle relation
		if(!empty($result))
		{
			$builder=$this->db->table("convention_barcode");
			$builder->where("id_convention_barcode",$result->id_convention_barcode);
			$data_ticket["id_ticket"]=$id_ticket;
			$builder->update($data_ticket);
		}
	}


	public function set_ajouter_ticket_demande($id_ticket,$id_demande)
	{
			$data_insert["id_ticket"]=$id_ticket;
			$data_insert["id_demande"]=$id_demande;
			$data_insert["id_user"]=session()->get("loggedUserId");
			$data_insert["date_created"]=date("Y-m-d H:i:s");

			$builder=$this->db->table("ticket_upload_lien");
			$builder->insert($data_insert);

	}
	

	public function get_statut()
	{
		$builder=$this->db->table("crm_list_ticket_statut");
		$builder->where("is_actif",1);
		$builder->orderBy("rank");

		return $builder->get()->getResult();

	}

	public function setStatut($id_ticket,$statut)
	{
		$builder=$this->db->table("ticket");
		$builder->where("id_ticket",$id_ticket);
		$data["statut"]=$statut;
		$data["updated_at"]=date("Y-m-d H:i:s");
		$data["updated_by"]=session()->get("loggedUserId");

		return $builder->update($data);
	}

	

	public function get_ticket($id_ticket)
	{
		$builder=$this->db->table("ticket");
		$builder->select("statut as statut_ticket,id_ticket,commentaire as commentaire_ticket,num_code as num_code_ticket");
		$builder->where("id_ticket",$id_ticket);
		return $builder->get()->getRow();
	}
}
