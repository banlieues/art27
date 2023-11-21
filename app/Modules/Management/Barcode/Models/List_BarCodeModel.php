<?php

namespace Barcode\Models;

use CodeIgniter\Model;
use DataView\Libraries\DataViewConstructor;
use DataView\Models\DataViewConstructorModel;


class List_BarCodeModel extends Model
{
	protected $table="convention_barcode";
	protected $primaryKey = 'id_partenaire_social';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';


	public function getListPartenaire_social($id_partenaire_social=0,$annee_select=0,$mois_select=0,$request,$orderBy=NULL,$orderDirection=NULL)
	{
		$this->select(
			"
				convention_barcode.id_convention_barcode,
				crm_list_barcode_statut.label as barcode_statut,
				convention_barcode.statut,
				convention_barcode.id_partenaire_social,
				partenaire_social.nom_partenaire_social,
				partenaire_social.numero_partenaire_social,
				convention_barcode.mois,
				convention_barcode.annee,
				convention_barcode.created_at,
				convention_barcode.created_by,
				convention_barcode.barcode,
				convention_barcode.num_code,
				convention_barcode.commentaire,
				concat(crm_list_barcode_mois.label,' ',convention_barcode.annee) as label_mois,
				concat(creator.prenom,' ',creator.nom) as user_create,
				
				partenaire_culturel.numero_partenaire_culturel,
				partenaire_culturel.nom_partenaire_culturel,
				partenaire_culturel.id_partenaire_culturel,

				ticket.url_file,




			"
			
			);

			
		$this->join("crm_list_barcode_statut","crm_list_barcode_statut.id=convention_barcode.statut","left");
		$this->join("user_accounts as creator","creator.id=convention_barcode.created_by","left");
		$this->join("partenaire_social","partenaire_social.id_partenaire_social=convention_barcode.id_partenaire_social","left");
		$this->join("ticket","ticket.id_ticket=convention_barcode.id_ticket","left");
		$this->join("partenaire_culturel","partenaire_culturel.id_partenaire_culturel=ticket.id_partenaire_culturel","left");

		$this->join(" crm_list_barcode_mois "," crm_list_barcode_mois.ref=convention_barcode.mois","left");


		if($id_partenaire_social>0)
			$this->where("convention_barcode.id_partenaire_social",$id_partenaire_social);

		if($annee_select>0)
			$this->where("convention_barcode.annee",$annee_select);

		if($mois_select!="0")
			$this->where("convention_barcode.mois",$mois_select);


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
		 
		return $this->paginate(50);
	}
	
	public function get_statut()
	{
		$builder=$this->db->table("crm_list_barcode_statut");
		$builder->where("is_actif",1);
		$builder->orderBy("rank");

		return $builder->get()->getResult();

	}

	public function setCommentaire($id_convention_barcode,$commentaire)
	{
		$builder=$this->db->table("convention_barcode");
		$builder->where("id_convention_barcode",$id_convention_barcode);
		$data["commentaire"]=$commentaire;
		$data["updated_at"]=date("Y-m-d H:i:s");
		$data["updated_by"]=session()->get("loggedUserId");
		return $builder->update($data);
	}

	public function setStatut($id_convention_barcode,$statut)
	{
		$builder=$this->db->table("convention_barcode");
		$builder->where("id_convention_barcode",$id_convention_barcode);
		$data["statut"]=$statut;
		$data["updated_at"]=date("Y-m-d H:i:s");
		$data["updated_by"]=session()->get("loggedUserId");

		return $builder->update($data);
	}

	

	public function getBarCode($id_convention_barcode)
	{
		$builder=$this->db->table("convention_barcode");
		$builder->where("id_convention_barcode",$id_convention_barcode);
		return $builder->get()->getRow();
	}
	

	
}
