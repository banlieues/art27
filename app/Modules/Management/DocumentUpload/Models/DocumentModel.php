<?php

namespace DocumentUpload\Models;
use CodeIgniter\Model;

class DocumentModel extends Model
{
	protected $table="document_upload";
	protected $primaryKey = 'id';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';



	protected $fields;

	public function __construct()
	{
	   	parent::__construct();

		  
    

	}

	public function getFields(){ return $this->fields;}

	public function getTable(){return $this->table;}

	public function getListDocument($request,$orderBy=NULL,$orderDirection=NULL,$entity=NULL,$id_entity=NULL)
	{
		$this->select("
				

				document_upload.id as id_document,
				document_upload.name,
				document_upload.url_file,
				document_upload.date_created,
				document_upload.commentaire,
				document_upload.id_type,
				list_types_depot.name as name_type,

				CONCAT(user_accounts.prenom,' ',user_accounts.nom) as user,


				
				
			
				"
			);

			

		//$this->join("demande","demande.id_demande=document.id_demande","left");
		$this->join("list_types_depot","list_types_depot.id=document_upload.id_type","left");
		$this->join("user_accounts","user_accounts.id=document_upload.id_user","left");

		$this->join("document_upload_lien","document_upload_lien.id_document=document_upload.id","left");
		
		$this->where("display","1");

		if(!is_null($entity))
		{
			$this->where("document_upload_lien.entity",$entity);
		}

		if(!is_null($id_entity))
		{
			$this->where("document_upload_lien.id_entity",$id_entity);
		}

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				 "document_upload.url_file","document_upload.name",
					
				 	
			
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

	public function getListDocumentAjouterDemande($request,$orderBy=NULL,$orderDirection=NULL,$id_demande=NULL)
	{
		$this->select("
				

				document_upload.id as id_document,
				document_upload.name,
				document_upload.url_file,
				document_upload.date_created,
				document_upload.commentaire,
				document_upload.id_type,
				list_types_depot.name as name_type,

				CONCAT(user_accounts.prenom,' ',user_accounts.nom) as user,


				(SELECT GROUP_CONCAT(document_upload_lien.id_demande) FROM document_upload_lien WHERE document_upload_lien.id_document=document_upload.id) as id_demandes
				
			
				"
			);

		/*if(!is_null($id_demande))
		{
			$this->where("$id_demande NOT IN (SELECT document_upload_lien.id_demande FROM document_upload_lien WHERE document_upload_lien.id_document=document_upload.id)");
		}*/

		//$this->join("demande","demande.id_demande=document.id_demande","left");
		$this->join("list_types_depot","list_types_depot.id=document_upload.id_type","left");
		$this->join("user_accounts","user_accounts.id=document_upload.id_user","left");
		
		$this->where("display","1");

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				 "document_upload.url_file",
				 "document_upload.name",
				 "document_upload.commentaire",
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

	public function getListDocumentGererDemande($request,$orderBy=NULL,$orderDirection=NULL,$id_demande=NULL)
	{
		$this->select("
				

				document_upload.id as id_document,
				document_upload.name,
				document_upload.url_file,
				document_upload.date_created,
				document_upload.commentaire,
				document_upload.id_type,
				list_types_depot.name as name_type,

				CONCAT(user_accounts.prenom,' ',user_accounts.nom) as user,


				(SELECT GROUP_CONCAT(document_upload_lien.id_demande) FROM document_upload_lien WHERE document_upload_lien.id_document=document_upload.id) as id_demandes
				
			
				"
			);

		if(!is_null($id_demande))
		{
			$this->where("$id_demande IN (SELECT document_upload_lien.id_demande FROM document_upload_lien WHERE document_upload_lien.id_document=document_upload.id)");
		}

		//$this->join("demande","demande.id_demande=document.id_demande","left");
		$this->join("list_types_depot","list_types_depot.id=document_upload.id_type","left");
		$this->join("user_accounts","user_accounts.id=document_upload.id_user","left");
		
		$this->where("display","1");

		 if($request->getVar("itemSearch")&&!empty(trim($request->getVar("itemSearch"))))
		 {
			 $items=explode(" ",$request->getVar("itemSearch"));
				
			 $fieldSearchs=array(
				 "document_upload.url_file",
				 "document_upload.name",
				 "document_upload.commentaire",
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


	public function getDocument($id_document)
	{
		$this->select("
				
				document_upload.id as id_document,
				document_upload.name,
				document_upload.url_file,
				document_upload.date_created,
				document_upload.commentaire,
				document_upload.id_type,
				list_types_depot.name as name_type,

				CONCAT(user_accounts.prenom,' ',user_accounts.nom) as user,



			
				"
			);

			
		//$this->join("document_upload_lien","document_upload_lien.id_document=document_upload.id","left");
		//$this->join("demande","demande.id_demande=document_upload_lien.id_demande","left");
		$this->join("list_types_depot","list_types_depot.id=document_upload.id_type","left");
		$this->join("user_accounts","user_accounts.id=document_upload.id_user","left");
		
		$this->where("display","1");

		
		 
		return $this->find($id_document);
	}

	public function getListDocuments($entity=NULL,$id_entity=NULL)
	{
	/*	$builder=$this->db->table("email_outlook_lien");
		$builder->join("document","document.id_message=email_outlook_lien.id_email");
		if(!is_null($id_demande))
		{
			$builder->where("email_outlook_lien.id_demande",$id_demande);
		}

		if(!is_null($id_message))
		{
			$builder->where("email_outlook_lien.id_email",$id_message);
		}*/

		$builder=$this->db->table("document_upload_lien");

		$builder->join("document_upload","document_upload.id=document_upload_lien.id_document");
		$builder->groupBy("document_upload_lien.id_document");
		if(!is_null($entity))
			$builder->where("entity",$entity);

		if(!is_null($entity))
			$builder->where("id_entity",$id_entity);

		return $builder->get()->getResult();

	}

	public function upload_file($data)
	{
		$data_insert_document=$data;
		unset($data_insert_document["entity"]);
		unset($data_insert_document["id_entity"]);

		$builder=$this->db->table("document_upload");
		$builder->insert($data_insert_document);
		$id_document=$this->db->insertId();


		
		if(!isset($data["entity"])||(isset($data["entity"])&&is_null($data["entity"])))
		{
			$data["entity"]=NULL;
		}
			

		if(!isset($data["id_entity"])||(isset($data["id_entity"])&&is_null($data["id_entity"])))
		{
			$data["id_entity"]=0;
		}
			

		if(!is_null($data["entity"])||$data["id_entity"]>0)
		{
			$data_insert["id_document"]=$id_document;
			$data_insert["entity"]=$data["entity"];
			$data_insert["id_entity"]=$data["id_entity"];

			$builder=$this->db->table("document_upload_lien");
			$builder->insert($data_insert);
		}


		return $id_document;
	}


	public function associe_demande($id_document,$id_demande)
	{
			$data_insert["id_document"]=$id_document;
			$data_insert["id_demande"]=$id_demande;
			$data_insert["id_user"]=session()->get("loggedUserId");
			$data_insert["date_created"]=date("Y-m-d H:i:s");

			$builder=$this->db->table("document_upload_lien");
			$builder->insert($data_insert);
	}

	public function get_liste_type_document()
	{
		$builder=$this->db->table("list_types_depot");
		$builder->where("is_actif",1);
		$builder->orderBy("rank,name");

		return $builder->get()->getResult();
	}

	
	public function setIdtype($id_document,$id_type)
	{
		$data_insert["id_type"]=$id_type;
		$builder=$this->db->table("document_upload");
		$builder->where("id",$id_document);
	
		$builder->update($data_insert);
	}


	public function setCommentaire($id_document,$commentaire)
	{
		$builder=$this->db->table("document_upload");
		$builder->where("id",$id_document);
		$data["commentaire"]=$commentaire;

		$builder->update($data);
	}


	public function set_ajouter_document_demande($id_document,$id_demande)
	{
			$data_insert["id_document"]=$id_document;
			$data_insert["id_demande"]=$id_demande;
			$data_insert["id_user"]=session()->get("loggedUserId");
			$data_insert["date_created"]=date("Y-m-d H:i:s");

			$builder=$this->db->table("document_upload_lien");
			$builder->insert($data_insert);

	}
	
}
