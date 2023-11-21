<?php

namespace Calculator\Models;

use Base\Models\BaseModel;
use Calculator\Libraries\AdminLibrary;
use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;
use Tesorus\Libraries\TesorusLibrary;

class AdminModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $this->table = $this->t_road;
        // $globals = new \Calculator\Config\Globals();
        // foreach($globals as $global=>$value) $this->$global = $value;

        $this->TesorusLibrary = new TesorusLibrary();
    }

    // public function RoadGetEstimationWithGroup($road, $group)
    // {
    //     $data = (object) [];
    //     $data = object_merge($data, $this->RoadGetEstimation($road));
    //     // $data = object_merge($data, $this->EstimationFlatGetByGroup($group));

    //     return $data;
    // }

    // public function RoadGetEstimation($road)
    // {
    //     $data = (object) [];               

    //     $data->id_road = $road->id_road;
    //     $data->isActive = $road->isActive;
    //     $data->poste_label = $road->label_fr;
    //     // $data->measure = $road->measure;
    //     $data->min_price = $road->min_price;
    //     $data->avg_price = $road->avg_price;
    //     $data->max_price = $road->max_price;

    //     $labels = $this->TesorusLibrary->get_labels_by_id_road('calculator', $road->id_road);
    //     $data->level_0 = $labels[0];
    //     $data->level_1 = $labels[1];
    //     $data->level_2 = !empty($labels[2]) ? $labels[2] : null;
    //     $data->level_3 = !empty($labels[3]) ? $labels[3] : null;
    //     $data->level_4 = !empty($labels[4]) ? $labels[4] : null;

    //     $ranks = $this->TesorusLibrary->get_ranks_by_id_road('calculator', $road->id_road);
    //     $data->rank_0 = $ranks[0];
    //     $data->rank_1 = $ranks[1];
    //     $data->rank_2 = !empty($ranks[2]) ? $ranks[2] : null;
    //     $data->rank_3 = !empty($ranks[3]) ? $ranks[3] : null;
    //     $data->rank_4 = !empty($ranks[4]) ? $ranks[4] : null;

    //     $ids_road = $this->TesorusLibrary->get_ids_road_by_id_road('calculator', $road->id_road);
    //     $data->id_road_0 = $ids_road[0];
    //     $data->id_road_1 = $ids_road[1];
    //     $data->id_road_2 = !empty($ids_road[2]) ? $ids_road[2] : null;
    //     $data->id_road_3 = !empty($ids_road[3]) ? $ids_road[3] : null;
    //     $data->id_road_4 = !empty($ids_road[4]) ? $ids_road[4] : null;

    //     return $data;
    // }

    // public function EstimationFlatGetByGroup($group)
    // {
    //     $data = (object) [];           
    //     $data->id_group = $group->id_group;
    //     $data->groupe_de_travaux = $group->label_fr;
    //     $data->group_rank = $group->rank;
    //     $data->measure = $group->measure;

    //     return $data;
    // }

    public function PriceDelete($id_price)
    {
        $price = $this->db->table($this->t_price)->where('id_price', $id_price)->get()->getRow();

        $this->db->table($this->t_price)->where('id_price', $id_price)->delete();

        // $this->EstimationsUpdateByRoad($price->id_road);
    }

    public function PriceGet($id_price)
    {
        $price = $this->db->table($this->t_price)->where('id_price', $id_price)->get()->getRow();

        return $price;
    }

    public function PriceOriginsGet()
    {
        $datas = $this->db->table($this->t_l_price_origin)->where('is_actif', 1)->orderBy('rank', 'asc')->get()->getResult();

        return $datas;
    }

    public function PriceSave($post, $id_price=null)
    {
        $post->updated_at = date('Y-m-d H:i:s');
        $post->updated_by = session('loggedUserId');
        if(!empty($post->date_devis)) $post->date_devis = convert_date_fr_to_en($post->date_devis);
        if(empty($id_price)) :
            $post->created_at = date('Y-m-d H:i:s');
            $post->created_by = session('loggedUserId');
            $this->db->table($this->t_price)->set(database_encode($this->t_price, $post))->insert();
            $id_price = $this->db->insertID();
        else :
            $this->db->table($this->t_price)->set(database_encode($this->t_price, $post))->where('id_price', $id_price)->update();
        endif;

        $price = $this->db->table($this->t_price)->where('id_price', $id_price)->get()->getRow();
        // $this->EstimationsUpdateByRoad($price->id_road);

        return $id_price;
    }

    public function RoadDelete($id_road)
    {
        $this->db->table($this->t_road)->where('id_road', $id_road)->delete();

        $groups = database_decode($this->db->table($this->t_group)->where('JSON_CONTAINS(ids_road_children, ' . $id_road . ')', true)->get()->getResult());
        if(empty($groups)) return false;

        foreach($groups as $group) :
            if(!empty($group->ids_road_children) && in_array($id_road, $group->ids_road_children)) :
                $data = (object) [];
                $data->ids_road_children = array_values(array_filter(array_diff($group->ids_road_children, [$id_road])));
                $this->GroupSave($data, $group->id_group);
            endif;
        endforeach;
    }

    public function RoadGet($id_road)
    {
        $sq1 = $this->db->table($this->t_price);
        // $sq->select("JSON_ARRAYAGG(JSON_OBJECT(
        //     'id_price', $this->t_price.id_price,
        //     'id_road', $this->t_price.id_road,
        //     'unit_price', $this->t_price.unit_price,
        //     'date_devis', $this->t_price.date_devis,
        //     'price_origin', $this->t_price.price_origin,
        //     'is_ignored', $this->t_price.is_ignored,
        //     'comment', $this->t_price.comment,
        //     'validity_month', $this->t_price.validity_month,
        //     'updated_at', $this->t_price.updated_at,
        //     'updated_by', $this->t_price.updated_by,
        //     'updated_nom', updated_user.nom,
        //     'updated_prenom', updated_user.prenom,
        //     'created_at', $this->t_price.created_at,
        //     'created_by', $this->t_price.created_by,
        //     'created_nom', created_user.nom,
        //     'created_prenom', created_user.prenom
        // )) as prices");
        $sq1->select("CONCAT_WS('', '[',
            GROUP_CONCAT(
                CONCAT_WS('', '{',
                    '\"id_price\":\"', COALESCE($this->t_price.id_price, 'null'), '\",',
                    '\"id_road\":\"', COALESCE($this->t_price.id_road, 'null'), '\",',
                    '\"unit_price\":\"', COALESCE($this->t_price.unit_price, 'null'), '\",',
                    '\"date_devis\":\"', COALESCE($this->t_price.date_devis, 'null'), '\",',
                    '\"price_origin\":\"', COALESCE($this->t_price.price_origin, 'null'), '\",',
                    '\"price_origin_label\":\"', COALESCE($this->t_l_price_origin.label, 'null'), '\",',
                    '\"is_ignored\":\"', COALESCE($this->t_price.is_ignored, 'null'), '\",',
                    '\"comment\":\"', COALESCE(REPLACE(REPLACE(REPLACE($this->t_price.comment, char(13), '\\\\n'), char(10), '\\\\n'), '\\\\n\\\\n', '\\\\n'), 'null'), '\",',
                    '\"validity_month\":\"', COALESCE($this->t_price.validity_month, 'null'), '\",',
                    '\"updated_at\":\"', COALESCE($this->t_price.updated_at, 'null'), '\",',
                    '\"updated_by\":\"', COALESCE($this->t_price.updated_by, 'null'), '\",',
                    '\"updated_nom\":\"', COALESCE(updated_user.nom, 'null'), '\",',
                    '\"updated_prenom\":\"', COALESCE(updated_user.prenom, 'null'), '\",',
                    '\"created_at\":\"', COALESCE($this->t_price.created_at, 'null'), '\",',
                    '\"created_by\":\"', COALESCE($this->t_price.created_by, 'null'), '\",',
                    '\"created_nom\":\"', COALESCE(created_user.nom, 'null'), '\",',
                    '\"created_prenom\":\"', COALESCE(created_user.prenom, 'null'), '\"',
                '}')
            ),
        ']')");
        $sq1->where("$this->t_price.id_road", new RawSql("$this->t_road.id_road"));
        $sq1->join("$this->t_user as updated_user", "updated_user.id = $this->t_price.updated_by", 'left');
        $sq1->join("$this->t_user as created_user", "created_user.id = $this->t_price.created_by", 'left');
        $sq1->join($this->t_l_price_origin, "$this->t_l_price_origin.id = $this->t_price.price_origin", 'left');
        $sq1->orderBy('date_devis', 'desc');
        $sq1_string = $sq1->getCompiledSelect();

        $q = $this->RoadGetModelData();
        $q->select("($sq1_string) as prices");
        $q->where("$this->t_road.id_road", $id_road);
        $road = database_decode($q->get()->getRow());

        $q2 = $this->db->table($this->t_group);
        $q2->where("json_contains(ids_road_children, $id_road)", 1);
        $road->groups = database_decode($q2->get()->getResult());

        $labels = $this->TesorusLibrary->get_labels_by_id_road('calculator', $id_road);
        if(count($labels)>=2) $road->labels_main = [$labels[0], $labels[1]];
        
        return $road;
    }

    // public function GroupsGetByRoad($id_road)
    // {
    //     $q = $this->db->table($this->t_group_road);
    //     $q->select("
    //         $this->t_group_road.id_group,
    //         $this->t_group.label_fr,
    //     ");
    //     $q->join($this->t_group, "$this->t_group.id_group = $this->t_group_road.id_group", 'left');
    //     $q->where('id_road_calculator', $id_road);

    //     $groups = database_decode($q->get()->getResult());

    //     return $groups;
    // }

    // public function GroupsGetByParent($id_road_parent=0)
    // {
    //     $q = $this->db->table($this->t_group);
    //     $q->where('id_road_parent', $id_road_parent);
    //     $q->where('isActive', 1);
    //     $q->orderBy('rank', 'asc');
    //     $roads = $q->get()->getResult();

    //     return $roads;
    // }

    
    public function GroupGet($id_group)
    {
        $sq = $this->db->table($this->t_road);

        // $sq->select("JSON_ARRAYAGG($this->t_road.id_road_parent)");
        $sq->select("CONCAT_WS('', '[',
            GROUP_CONCAT(
                CONCAT('\"', $this->t_road.id_road_parent, '\"')
            ),
        ']')");
        $sq->where("JSON_CONTAINS($this->t_group.ids_road_children, $this->t_road.id_road)");
        $sq_string = $sq->getCompiledSelect();

        $q = $this->db->table($this->t_group);
        $q->select("$this->t_group.*");
        $q->select("($sq_string) as ids_road_children_parent");
        $q->where("$this->t_group.id_group", $id_group);
        $group = database_decode($q->get()->getRow());


        if(!empty($group->ids_road_children_genealogy)) $group->ids_road_children_genealogy = array_unique($group->ids_road_children_genealogy);

        return $group;
    }

    public function GroupsGetByRoadParent($id_road_parent, $is_active=null)
    {
        $sq = $this->db->table($this->t_road);
        // $sq->select("JSON_ARRAYAGG($this->t_road.id_road_parent)");
        $sq->select("CONCAT_WS('', '[',
            GROUP_CONCAT(
                $this->t_road.id_road_parent
            ),
        ']')");
        $sq->where("JSON_CONTAINS($this->t_group.ids_road_children, $this->t_road.id_road)");
        $sq_string = $sq->getCompiledSelect();

        if(empty($is_active) || $is_active=='inactive') :
            $q2 = $this->db->table($this->t_group);
            $q2->select("$this->t_group.*");
            $q2->select("($sq_string) as ids_road_children_parent");
            $q2->where("$this->t_group.id_road_parent", $id_road_parent);
            $q2->where('isActive', 0);
            $q2->orWhere('isActive is null');
            $q2->orderBy('rank', 'asc');
        endif;

        if(empty($is_active) || $is_active=='active') :
            $q1 = $this->db->table($this->t_group);
            $q1->select("$this->t_group.*");
            $q1->select("($sq_string) as ids_road_children_parent");
            $q1->where("$this->t_group.id_road_parent", $id_road_parent);
            $q1->where("$this->t_group.isActive", 1);
            // $q1->whereNotIn("$this->t_group.ids_road_children", ['', '[]']);
            // $q1->where("$this->t_group.ids_road_children IS NOT", null);
        endif;
        $q1->orderBy('rank', 'asc');

        if(empty($is_active)) $q1->union($q2);
        elseif($is_active=='inactive') $q1 = $q2;

        $groups = $this->db->newQuery()->fromSubquery($q1, 'q')->get()->getResult();

        return $groups;
    }

    public function PricesGetByRoad($id_road)
    {
        $q = $this->db->table($this->t_price);
        $q->select("
            $this->t_price.*,
            updated_user.nom as updated_nom, updated_user.prenom as updated_prenom,
            created_user.nom as created_nom, created_user.prenom as created_prenom,
            $this->t_l_price_origin.label as price_origin_label,
        ");
        $q->where('id_road', $id_road);
        $q->join("$this->t_user as updated_user", "updated_user.id = $this->t_price.updated_by", 'left');
        $q->join("$this->t_user as created_user", "created_user.id = $this->t_price.created_by", 'left');
        $q->join($this->t_l_price_origin, "$this->t_l_price_origin.id = $this->t_price.price_origin", 'left');
        $q->orderBy('date_devis', 'desc');
        $prices = database_decode($q->get()->getResult());

        return $prices;
    }

    // public function IdParentCalculatorGetByRoad($id_road)
    // {
    //     $ids_road = $this->TesorusLibrary->get_ids_road_by_id_road('calculator', $id_road);

    //     return $ids_road[1];
    // }

    public function GroupSave($data, $id_group=null)
    {
        $crud = !empty($id_group) ? 'update' : 'create';
        $data->updated_at = date('Y-m-d H:i:s');
        $data->updated_by = session('loggedUserId');
        if(empty($id_group)) :
            $data->created_by = session('loggedUserId');
            $rank = $this->db->table($this->t_group)->select('max(rank) as rank')->where('id_road_parent', $data->id_road_parent)->get()->getRow();
            $data->rank = $rank->rank + 1;
            $this->db->table($this->t_group)->set(database_encode($this->t_group, $data))->insert();
            $id_group = $this->db->insertID();
        else :
            $this->db->table($this->t_group)->set(database_encode($this->t_group, $data))->where('id_group', $id_group)->update();
        endif;

        // $this->EstimationUpdateByGroup($id_group);

        return $id_group;
    }

    // private function EstimationUpdateByGroup($id_group)
    // {
    //     $group = $this->GroupGet($id_group);
    //     if(!empty($group->isActive)):
    //         $this->EstimationsInsertByGroup($id_group);
    //     else :
    //         $this->EstimationsDeleteByGroup($id_group);
    //     endif;

    //     return $id_group;

    // }

    public function GroupDelete($id_group)
    {
        $this->db->table($this->t_group)->where('id_group', $id_group)->delete();
        // $this->EstimationsDeleteByGroup($id_group);
    }

    // public function EstimationDeleteByRoad($id_road)
    // {
    //     $q = $this->db->table($this->t_estimation);
    //     $q->where('id_road', $id_road);
    //     $q->delete();
    // }

    // public function EstimationsDeleteByGroup($id_group)
    // {
    //     $q = $this->db->table($this->t_estimation);
    //     $q->where('id_group', $id_group);
    //     $q->delete();
    // }

    // public function EstimationInsertByRoad($road)
    // {
    //     $this->db->table($this->t_estimation)->set(database_encode($this->t_estimation, $road))->insert();
    // }

    // public function EstimationsInsertByGroup($id_group)
    // {
    //     // $this->EstimationsDeleteByGroup($id_group);

    //     $group = $this->GroupGet($id_group);
    //     if(!empty($group->ids_road_children)) :
    //         foreach($group->ids_road_children as $id_road) :
    //             $road = $this->RoadGet($id_road);
    //             $data = $this->RoadGetEstimationWithGroup($road, $group);
    //             debug($data);
    //             // $this->EstimationInsertByRoad($data);
    //         endforeach;
    //     endif;
    // }

    // private function EstimationModelData()
    // {
    //     $poste_0 = "coalesce(pppp_cell.label_fr, ppp_cell.label_fr, pp_cell.label_fr, p_cell.label_fr, $this->t_cell.label_fr)";
    //     $rank_0 = "coalesce(pppp_road.rank, ppp_road.rank, pp_road.rank, p_road.rank, $this->t_road.rank)";
    //     $poste_1 = "coalesce(
    //         if(ppp_cell.label_fr not in($poste_0), ppp_cell.label_fr, null), 
    //         if(pp_cell.label_fr not in($poste_0), pp_cell.label_fr, null), 
    //         if(p_cell.label_fr not in($poste_0), p_cell.label_fr, null),
    //         if($this->t_cell.label_fr not in($poste_0), $this->t_cell.label_fr, null)
    //     )";
    //     $rank_1 = "coalesce(
    //         if(ppp_cell.label_fr not in($poste_0), ppp_road.rank, null), 
    //         if(pp_cell.label_fr not in($poste_0), pp_road.rank, null), 
    //         if(p_cell.label_fr not in($poste_0), p_road.rank, null),
    //         if($this->t_cell.label_fr not in($poste_0), $this->t_road.rank, null)
    //     )";
    //     $rank_groupe = "coalesce(pppp_group.rank, ppp_group.rank, pp_group.rank, p_group.rank, $this->t_group.rank)";
    //     $poste_2 = "coalesce(
    //         if(pp_cell.label_fr not in($poste_0, $poste_1), pp_cell.label_fr, null), 
    //         if(p_cell.label_fr not in($poste_0, $poste_1), p_cell.label_fr, null),
    //         if($this->t_cell.label_fr not in($poste_0, $poste_1), $this->t_cell.label_fr, null)
    //     )";
    //     $rank_2 = "coalesce(
    //         if(pp_cell.label_fr not in($poste_0, $poste_1), pp_road.rank, null), 
    //         if(p_cell.label_fr not in($poste_0, $poste_1), p_road.rank, null),
    //         if($this->t_cell.label_fr not in($poste_0, $poste_1), $this->t_road.rank, null)
    //     )";
    //     $poste_3 = "coalesce(
    //         if(p_cell.label_fr not in($poste_0, $poste_1, $poste_2), p_cell.label_fr, null),
    //         if($this->t_cell.label_fr not in($poste_0, $poste_1, $poste_2), $this->t_cell.label_fr, null)
    //     )";
    //     $rank_3 = "coalesce(
    //         if(p_cell.label_fr not in($poste_0, $poste_1, $poste_2), p_road.rank, null),
    //         if($this->t_cell.label_fr not in($poste_0, $poste_1, $poste_2), $this->t_road.rank, null)
    //     )";
    //     $poste_4 = "coalesce(
    //         if($this->t_cell.label_fr not in($poste_0, $poste_1, $poste_2, $poste_3), $this->t_cell.label_fr, null)
    //     )";
    //     $rank_4 = "coalesce(
    //         if($this->t_cell.label_fr not in($poste_0, $poste_1, $poste_2, $poste_3), $this->t_road.rank, null)
    //     )";
    //     $q = $this->db->table($this->t_road);
    //     $q->distinct();
    //     $q->select("
    //         $this->t_road.id_road,
    //         $poste_0 as poste_0,
    //         $poste_1 as poste_1,
    //         coalesce(pppp_group.label_fr, ppp_group.label_fr, pp_group.label_fr, p_group.label_fr, $this->t_group.label_fr) as groupe_de_travail,
    //         $poste_2 as poste_2,
    //         $poste_3 as poste_3,
    //         $poste_4 as poste_4,
    //         coalesce(pppp_road.measure, ppp_road.measure, pp_road.measure, p_road.measure, $this->t_road.measure) as measure,
    //         (
    //             select round(min(unit_price),2) from $this->t_price where $this->t_road.id_road = id_road
    //         ) as `min_price`,
    //         (
    //             select round(avg(unit_price),2) from $this->t_price where $this->t_road.id_road = id_road
    //         ) as avg_price,
    //         (
    //             select round(max(unit_price),2) from $this->t_price where $this->t_road.id_road = id_road
    //         ) as max_price,
    //     ");
    //     $q->join("$this->t_cell", "$this->t_cell.id_cell = $this->t_road.id_cell", 'left');
    //     $q->join("$this->t_group", "$this->t_group.id_road_parent = $this->t_road.id_road", 'left');
    //     $q->join("$this->t_road as p_road", "p_road.id_road = $this->t_road.id_road_parent", 'left');
    //     $q->join("$this->t_cell as p_cell", "p_cell.id_cell = p_road.id_cell", 'left');
    //     $q->join("$this->t_group as p_group", "p_group.id_road_parent = p_road.id_road", 'left');
    //     $q->join("$this->t_road as pp_road", "pp_road.id_road = p_road.id_road_parent", 'left');
    //     $q->join("$this->t_cell as pp_cell", "pp_cell.id_cell = pp_road.id_cell", 'left');
    //     $q->join("$this->t_group as pp_group", "pp_group.id_road_parent = pp_road.id_road", 'left');
    //     $q->join("$this->t_road as ppp_road", "ppp_road.id_road = pp_road.id_road_parent", 'left');
    //     $q->join("$this->t_cell as ppp_cell", "ppp_cell.id_cell = ppp_road.id_cell", 'left');
    //     $q->join("$this->t_group as ppp_group", "ppp_group.id_road_parent = ppp_road.id_road", 'left');
    //     $q->join("$this->t_road as pppp_road", "pppp_road.id_road = ppp_road.id_road_parent", 'left');
    //     $q->join("$this->t_cell as pppp_cell", "pppp_cell.id_cell = pppp_road.id_cell", 'left');
    //     $q->join("$this->t_group as pppp_group", "pppp_group.id_road_parent = pppp_road.id_road", 'left');

    //     $q->orderBy($rank_0);
    //     $q->orderBy($rank_1);
    //     $q->orderBy($rank_groupe);
    //     $q->orderBy($rank_2);
    //     $q->orderBy($rank_3);
    //     $q->orderBy($rank_4);

    //     return $q;
    // }

    // public function EstimationsGetByRoadParent($id_road_parent)
    // {
    //     // $q = $this->EstimationModelData();
    //     $q->where("$this->t_road.id_road_parent", $id_road_parent);
    //     $q->orWhere("p_road.id_road_parent", $id_road_parent);
    //     $q->orWhere("pp_road.id_road_parent", $id_road_parent);
    //     $q->orWhere("ppp_road.id_road_parent", $id_road_parent);
    //     $q->orWhere("pppp_road.id_road_parent", $id_road_parent);
    //     $roads = database_decode($q->get()->getResult());

    //     return $roads;
    // }

    // public function EstimationGetByRoad($id_road)
    // {
    //     // $q = $this->EstimationModelData();
    //     $q->where("$this->t_road.id_road", $id_road);
    //     $road = database_decode($q->get()->getRow());

    //     return $road;
    // }

    // public function EstimationsGet()
    // {
    //     $q = $this->EstimationModelData();
    //     $q->having("min_price is not", null);
    //     $roads = database_decode($q->get()->getResult());

    //     return $roads;
    // }

    // public function EstimationsGetFlat()
    // {
    //     $q = $this->db->table($this->t_estimation);
    //     $q->orderBy('rank_0', 'asc');
    //     $q->orderBy('rank_1', 'asc');
    //     $q->orderBy('group_rank', 'asc');
    //     $q->orderBy('rank_2', 'asc');
    //     $q->orderBy('rank_3', 'asc');
    //     $q->orderBy('rank_4', 'asc');
    //     $roads = $q->get()->getResult();

    //     return $roads;
    // }

    // public function EstimationsUpdateByRoad($id_road)
    // {
    //     $road = $this->RoadGet($id_road);

    //     if(isset($road) && !empty($road->isActive)) :
    //         if(!empty($road->is_terminus)) :
    //             $groups = database_decode($this->db->table($this->t_group)->where('JSON_CONTAINS(ids_road_children, ' . $id_road . ')', true)->get()->getResult());
    //             if(empty($groups)) return false;
    //             // foreach($groups as $group) :
    //             //     $this->EstimationUpdateByGroup($group->id_group);
    //             // endforeach;
    //         else :
    //             for($i=0; $i<=4; $i++) :
    //                 $data = (object) [];
    //                 $data->{"level_$i"} = $road->label_fr;
    //                 $data->{"rank_$i"} = $road->rank;
    //                 $this->db->table($this->t_estimation)->set(database_encode($this->t_estimation, $data))->where("id_road_$i", $road->id_road)->update();
    //             endfor;
    //         endif;
    //     else :
    //         $this->db->table($this->t_estimation)
    //             ->where('id_road_0', $id_road)
    //             ->orWhere('id_road_1', $id_road)
    //             ->orWhere('id_road_2', $id_road)
    //             ->orWhere('id_road_3', $id_road)
    //             ->orWhere('id_road_4', $id_road)
    //             ->delete();
    //     endif;
    // }

    private function RoadGetModelDataPrice($stat)
    {
        return "(
            select round($stat(price_$stat.unit_price),2) 
            from $this->t_price price_$stat
            left join $this->t_road as price_road_$stat on price_road_$stat.id_road = price_$stat.id_road
            where 
                (price_$stat.is_ignored is null or price_$stat.is_ignored=0) and
                price_road_$stat.id_road = $this->t_road.id_road and
                price_$stat.created_at > DATE_SUB(
                    (
                        select max(created_at) 
                        from $this->t_price 
                        where id_road = $this->t_road.id_road
                    ), 
                    INTERVAL coalesce(price_road_$stat.period_month_calcul, 6) MONTH
                )
        ) as " . $stat . "_price";
    }

    private function RoadGetModelData()
    {
        $sq = $this->db->table("$this->t_road subtable_road");
        // $sq->select("JSON_ARRAYAGG(subtable_road.id_road)");
        $sq->select("CONCAT_WS('', '[',
            GROUP_CONCAT(
                subtable_road.id_road
            ),
        ']')");
        $sq->where("subtable_road.id_road_parent = $this->t_road.id_road");
        $sq_string = $sq->getCompiledSelect();

        $q = $this->db->table($this->t_road);
        $q->select("
            $this->t_road.*,
            $this->t_cell.label_fr,
        ");
        $q->select("($sq_string) as ids_road_children");
        $q->select($this->RoadGetModelDataPrice('min'));
        $q->select($this->RoadGetModelDataPrice('avg'));
        $q->select($this->RoadGetModelDataPrice('max'));
        $q->join($this->t_cell, "$this->t_cell.id_cell = $this->t_road.id_cell", 'left');

        return $q;
    }

    public function RoadsGetByParent($id_road_parent=0, $is_active=null)
    {
        $roads_inactive = [];
        if(empty($is_active) || $is_active=='inactive') :
            $q2 = $this->RoadGetModelData();
            if(empty($id_road_parent)) :
                $q2->groupStart();
                    $q2->where("$this->t_road.id_road_parent", null);
                    $q2->orWhere("$this->t_road.id_road_parent", 0);
                $q2->groupEnd();
            else :
                $q2->where("$this->t_road.id_road_parent", $id_road_parent);
            endif;
            $q2->groupStart();
                $q2->where("$this->t_road.isActive", 0);
                $q2->orWhere("$this->t_road.isActive is null");
            $q2->groupEnd();
            $q2->orderBy("$this->t_road.rank", 'asc');
            $roads_inactive = $q2->get()->getResult();
        endif;

        $roads_active = [];
        if(empty($is_active) || $is_active=='active') :
            $q1 = $this->RoadGetModelData();
            if(empty($id_road_parent)) :
                $q1->groupStart();
                    $q1->where("$this->t_road.id_road_parent", null);
                    $q1->orWhere("$this->t_road.id_road_parent", 0);
                $q1->groupEnd();
            else :
                $q1->where("$this->t_road.id_road_parent", $id_road_parent);
            endif;
            $q1->where("$this->t_road.isActive", 1);
            $q1->orderBy("$this->t_road.rank", 'asc');
            $roads_active = $q1->get()->getResult();
        endif;

        $roads = array_merge($roads_active, $roads_inactive);
        
        return $roads;
    }
}