<?php

namespace Tesorus\Models;

use Base\Models\BaseModel;
use DataView\Libraries\DataViewConstructor;
use Tesorus\Libraries\TesorusLibrary;

class CellModel extends BaseModel 
{   
	protected $allowedFields;
	protected $fields;
	protected $returnType = 'object';
	protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->table = $this->t_cell;
        $this->primaryKey = get_primary_key($this->table);
    }

    public function CellSave($post, $id_cell=null)
    {
        if(empty($id_cell)) :
            $cells = $this->CellModelData()->where('lower(label_fr)', mb_strtolower($post->label_fr))->get()->getResult();
            if(empty($cells)):
                $post->label_fr = ucfirst(trim($post->label_fr));
                $post->reference = str_replace(' ', '_', mb_strtolower(remove_accents(trim($post->label_fr))));
                $this->db->table($this->t_cell)->set(database_encode($this->t_cell, $post))->insert();
                $id_cell = $this->db->insertID();
            else:
                $id_cell = $cells[0]->id_cell;
            endif;
        else :
            $this->db->table($this->t_cell)->set(database_encode($this->t_cell, $post))->where("id_cell", $id_cell)->update();
        endif;

        return $id_cell;
    }

    private function CellModelData($order=null, $request=null)
    {
        $DataView = new DataViewConstructor();

        if(!empty($request) && !empty($request->getGet('itemSearch')) && !empty(trim($request->getGet('itemSearch')))) :
            $fieldsSearch = array(
                "$this->t_cell.label_fr", 
            );
            $DataView->setQuerySearch($this, $fieldsSearch);
        endif;

        $order = $DataView->GetOrderFromRequest($order, $request);
        if(!empty($order[0])) $this->orderBy($order[0], $order[1]);
        else $this->orderBy('label_fr', 'asc');

        return $this;
    }

    public function CellsPagerGet()
    {
        return $this->pager;
    }
    
    public function CellGet($id_cell)
    {
        $cell = $this->CellModelData()->where('id_cell', $id_cell)->get()->getRow();
        if(empty((array) $cell)) return null;

        $cell = $this->CellCalculatedFields($cell);

        return $cell;
    }

    public function CellsGetByLabel($label)
    {
        $cells = $this->CellModelData()->where('lower(trim(label_fr))', mb_strtolower(trim($label)))->get()->getResult();

        return $cells;
    }

    public function CellCalculatedFields($cell)
    {
        $TesorusLibrary = new TesorusLibrary();
        $cell->paths = $TesorusLibrary->get_paths_by_id_cell($cell->id_cell);

        return $cell;
    }

    public function CellsGet($order=null, $request=null, $no_pager=null)
    {
        $q = $this->CellModelData($order, $request);
        if(!empty($no_pager) || (!empty($request->getGet('per_page')) && $request->getGet('per_page')=='all')) :
            $cells = $q->find();
        else :
            $per_page = !empty($request->getGet('per_page')) ? $request->getGet('per_page') : 20;
            $cells = $q->paginate($per_page);
        endif;
        $cells = database_decode($cells);

        $i = 0;
        foreach($cells as $cell) :
            $cells[$i] = $this->CellCalculatedFields($cell);
            $i++;
        endforeach;

        return $cells;
    }
}
