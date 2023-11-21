<?php

namespace Tesorus\Models;

use Base\Models\BaseModel;
// use Calculator\Models\AdminModel;
use Tesorus\Models\CellModel;

class RoadModel extends BaseModel 
{   
	protected $allowedFields;
	protected $fields;
	protected $returnType = 'object';
	protected $useAutoIncrement = true;

    public function __construct($road_name)
    {
        parent::__construct(__NAMESPACE__);

        $this->road_name = $road_name;
        $this->table = $this->TableRoadGet($road_name);
        $this->primaryKey = get_primary_key($this->table);

        // $this->AdminModel = new AdminModel();
        $this->CellModel = new CellModel();
    }

    public function TableRoadGet($road_name)
    {
        $roads = json_decode(file_get_contents($this->path . 'Config/Json/road/list.json'));
        if(empty($roads)) return false;
        if(empty($roads->$road_name)) return false;

        return $roads->$road_name->table;
    }

    public function RoadModelData()
    {
        $this->join($this->t_cell, "$this->t_cell.id_cell = $this->table.id_cell", 'left');

        return $this;
    }

    public function RoadsPagerGet()
    {
        return $this->pager;
    }


    public function RoadDelete($id_road)
    {
        $this->db->table($this->table)->where("$this->table.id_road", $id_road)->delete();
    }

    public function RoadSave($data, $id_road=null)
    {
        if(!empty($data->label_fr)) :
            $id_cell = $this->CellModel->CellSave($data);
            $data->id_cell = $id_cell;
        endif;

        $data->updated_by = session('loggedUserId');
        if(!empty($id_road)) :
            $data->updated_at = date('Y-m-d H:i:s');
            $this->db->table($this->table)->set(database_encode($this->table, $data))->where('id_road', $id_road)->update();
        else :
            $id_road_parent = !empty($data->id_road_parent) ? $data->id_road_parent : 0;
            $data->rank = count($this->RoadsActiveGetByParent($id_road_parent));
            $data->created_by = session('loggedUserId');
            $this->db->table($this->table)->set(database_encode($this->table, $data))->insert();
            $id_road = $this->db->InsertID();
        endif;

        // if($this->road_name=='calculator') :
        //     $this->AdminModel->EstimationsUpdateByRoad($id_road);
        // endif;

        return $id_road;
    }

    public function RoadSaveHasText($id_road)
    {
        $road = $this->RoadGet($id_road);

        $post = (object) [];
        if(empty($road->has_text)) $post->has_text = 1;
        else $post->has_text = 0;

        $this->RoadSave($post, $id_road);

        return $road->has_text;
    }

    public function RoadsGetByIds($ids_road)
    {
        $q = $this->RoadModelData();
        $q->whereIn("$this->table.id_road", $ids_road);
        $roads = database_decode($q->get()->getResult());

        return $roads;
    }

    public function get_roads_active_flat()
    {
        $q = $this->RoadModelData();
        $q->where("$this->table.isActive", 1);
        $roads = database_decode($q->get()->getResult());

        return $roads;
    }

    // public function get_roads_active_recursive_flat($id_road_parent)
    // {
    //     $q = $this->RoadModelData();
    //     $q->distinct();
    //     $q->join("$this->table as p_road", "p_road.id_road = $this->table.id_road_parent", 'left');
    //     $q->join("$this->t_cell as p_cell", "p_cell.id_cell = p_road.id_cell", 'left');
    //     $q->join("$this->table as pp_road", "pp_road.id_road = p_road.id_road_parent", 'left');
    //     $q->join("$this->t_cell as pp_cell", "pp_cell.id_cell = pp_road.id_cell", 'left');
    //     $q->join("$this->table as ppp_road", "ppp_road.id_road = pp_road.id_road_parent", 'left');
    //     $q->join("$this->t_cell as ppp_cell", "ppp_cell.id_cell = ppp_road.id_cell", 'left');
    //     $q->where("$this->table.id_road !=", null);
    //     $q->groupStart();
    //         $q->where("$this->table.id_road_parent", $id_road_parent);
    //         $q->orWhere("p_road.id_road_parent", $id_road_parent);
    //         $q->orWhere("pp_road.id_road_parent", $id_road_parent);
    //         $q->orWhere("ppp_road.id_road_parent", $id_road_parent);
    //     $q->groupEnd();
    //     $roads = database_decode($q->get()->getResult());

    //     return array_values(array_unique(array_filter($roads), SORT_REGULAR));
    // }

    public function RoadGet($id_road)
    {
        $q = $this->RoadModelData();
        $q->where("$this->table.id_road", $id_road);
        $road = database_decode($q->get()->getRow());

        return $road;
    }

    public function RoadsActiveGet()
    {
        $q = $this->RoadModelData();
        $q->where("$this->table.isActive", 1);
        $roads = database_decode($q->get()->getResult());

        return $roads;
    }
        
    public function IdsRoadGetByParent($id_road_parent=0)
    {
        $roads = $this->RoadsGetByParent($id_road_parent);
        $ids_road = array_column($roads, 'id_road');

        return $ids_road;
    }

    public function RoadsInactiveGet()
    {
        $q = $this->RoadModelData();
        $q->where("$this->table.isActive", 0);
        $q->orWhere("$this->table.isActive", null);
        $roads = database_decode($q->get()->getResult());

        return $roads;
    }

    public function RoadsGetByParent($id_road_parent=0)
    {
        $roads_active = $this->RoadsActiveGetByParent($id_road_parent);
        $roads_inactive = $this->RoadsInactiveGetByParent($id_road_parent);
        $roads = array_merge($roads_active, $roads_inactive);

        return $roads;
    }

    public function RoadsActiveGetByParent($id_road_parent)
    {
        $q = $this->RoadModelData();
        $q->groupStart();
            $q->where("$this->table.id_road_parent", $id_road_parent);
            if($id_road_parent==0) $q->orWhere("$this->table.id_road_parent", null);
        $q->groupEnd();
        $q->where("$this->table.isActive", 1);
        $q->orderBy("$this->table.rank", 'asc');
        $roads = database_decode($q->get()->getResult());

        return $roads;
    }

    public function ParentsGetByRoads($ids_road)
    {
        $q = $this->db->table($this->table);
        $q->select("CONCAT_WS('', '[', GROUP_CONCAT($this->table.id_road_parent), ']') as ids_road_parent");
        $q->join("$this->table as t_parent", "t_parent.id_road = $this->table.id_road_parent", 'left');
        $q->groupStart();
            $q->whereIn("$this->table.id_road", $ids_road);
            $q->where("t_parent.isActive", 1);
        $q->groupEnd();
        $roads = $q->get()->getRow();
        
        if(empty($roads)) return [];

        return database_decode($roads->ids_road_parent);
    }

    public function RoadsInactiveGetByParent($id_road_parent)
    {
        $q = $this->RoadModelData();
        $q->groupStart();
            $q->where("$this->table.id_road_parent", $id_road_parent);
            if($id_road_parent==0) $q->orWhere("$this->table.id_road_parent", null);
        $q->groupEnd();
        $q->groupStart();
            $q->where("$this->table.isActive", 0);
            $q->orWhere("$this->table.isActive", null);
        $q->groupEnd();
        $q->orderBy("$this->t_cell.label_fr", 'asc');
        $roads = database_decode($q->get()->getResult());

        return $roads;
    }

    public function RoadsGetByCell($id_cell)
    {
        $q = $this->RoadModelData();
        $q->where("$this->table.id_cell", $id_cell);
        $q->where("$this->table.isActive", 1);
        $roads = database_decode($q->get()->getResult());

        return $roads;
    }

    public function RoadSaveIsActive($id_road, $isActive=null)
    {
        $r = $this->RoadGet($id_road);

        $roads = $this->RoadsGetByParent($r->id_road_parent);
        if(empty($roads)) return null;

        foreach($roads as $road) :
            $post = (object) [];
            if($road->id_road==$id_road) :
                $post->isActive = $isActive;
                $this->RoadSave($post, $road->id_road);
                break;
            endif;
        endforeach;

        // if($this->road_name=='calculator') :
        //     $this->AdminModel->EstimationsUpdateByRoad($id_road);
        // endif;
        // $i=0;
        // foreach($roads as $road) :
        //     $post = (object) [];
        //     if($road->id_road==$id_road) :
        //         $post->isActive = $isActive;
        //         $post->rank = !empty($isActive) ? count($this->RoadsActiveGetByParent($r->id_road_parent)) : null;
        //         $this->RoadSave($post, $road->id_road);
        //     elseif(!empty($road->isActive)) :
        //         $post->rank = $i;
        //         $this->RoadSave($post, $road->id_road);
        //         $i++;
        //     endif;
        // endforeach;

        // $children_road = $this->RoadsGetByParent($r->id_road);
        // if(!empty($children_road)) :
        //     foreach($children_road as $cr) :
        //         $this->RoadSaveIsActive($cr->id_road);
        //     endforeach;
        // endif; 
    }

}
