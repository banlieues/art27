<?php

namespace Barcode\Models;

use CodeIgniter\Model;


class BarcodeModel extends Model
{
	protected $table="barcode";
	protected $primaryKey = 'id_barcode';
	protected $useAutoIncrement = true;
	protected $returnType     = 'object';


	protected $fields;

	public function list_get_partenaire_sociaux()
	{
			$builder=$this->db->table("partenaire_social");
			$builder->select("id_partenaire_social,nom_partenaire_social");
			return $builder->get()->getResult();
		
	}

	public function get_partenaire_social($id_partenaire_social)
	{
		$builder=$this->db->table("partenaire_social");
		$builder->select("id_partenaire_social,nom_partenaire_social");
		$builder->where("id_partenaire_social",$id_partenaire_social);

		return $builder->get()->getRow();
	}


}