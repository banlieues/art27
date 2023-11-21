<?php

namespace Calculator\Models;

use Base\Models\BaseModel;
use CodeIgniter\Database\RawSql;
use Tesorus\Libraries\TesorusLibrary;

class ClientModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        
        $this->table = $this->t_road;
        $this->primaryKey = get_primary_key($this->table);
        // $globals = new \Calculator\Config\Globals();
        // foreach($globals as $global=>$value) $this->$global = $value;
    }
    
    public function GroupWorkGet($id_work, $id_group)
    {
        $sq = $this->db->table($this->t_road);
        $sq->select("CONCAT_WS('', '[',
            GROUP_CONCAT(
                CONCAT('\"', $this->t_road.id_road_parent, '\"')
            ),
        ']')");
        $sq->where("JSON_CONTAINS($this->t_group.ids_road_children, $this->t_road.id_road)");
        $sq_string = $sq->getCompiledSelect();

        if(preg_match('/^##(\d+|i)##$/', $id_work)) :
            $group = $this->db->table($this->t_group)
                ->select("$this->t_group.*")
                ->select("($sq_string) as ids_road_children_parent")
                ->where('id_group', $id_group)
                ->get()->getRow();
            $group->id_work = $id_work;
        else :
            $sq2_sq = $this->db->table($this->t_road);
            // $sq2_sq->select("JSON_ARRAYAGG($this->t_road.id_road_parent)");
            $sq2_sq->select("CONCAT_WS('', '[',
                GROUP_CONCAT(
                    $this->t_road.id_road_parent
                ),
            ']')");
            $sq2_sq->where("JSON_CONTAINS($this->t_group_work.ids_road, $this->t_road.id_road)");
            $sq2_string = $sq2_sq->getCompiledSelect();
    
            $group = $this->db->table($this->t_group_work)
                ->select("$this->t_group.*")
                ->select("$this->t_group_work.*")
                ->select("($sq_string) as ids_road_children_parent")
                ->select("($sq2_string) as ids_road_parent")
                ->join($this->t_group, "$this->t_group.id_group = $this->t_group_work.id_group", 'left')
                ->where("$this->t_group_work.id_work", $id_work)
                ->where("$this->t_group_work.id_group", $id_group)
                ->get()->getRow();
        endif;

        return database_decode($group);
    }

    public function GroupsGetByIds($ids_group)
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
        $q->whereIn("$this->t_group.id_group", $ids_group);
        $groups = database_decode($q->get()->getResult());

        return $groups;
    }

    
    public function GroupsGetByThem($id_them)
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
        $q->where("$this->t_group.id_road_parent", $id_them);
        $q->where("$this->t_group.ids_road_children IS NOT NULL");
        $groups = database_decode($q->get()->getResult());

        return $groups;
    }

    public function GroupsGetByRoomByRoadParent($id_room, $id_road_parent)
    {
        $q = $this->db->table($this->t_group);
        $q->join($this->t_group_room, "$this->t_group_room.id_group = $this->t_group.id_group", 'left');
        $q->where("$this->t_group.id_road_parent", $id_road_parent);
        $q->where("$this->t_group_room.id_room", $id_room);
        $q->orderBy('rank', 'asc');

        $groups = database_decode($q->get()->getResult());

        return $groups;
    }

    // public function RoomsGetByDevis($id_devis)
    // {
    //     $sq1 = $this->db->table($this->t_group_room);
    //     // $sq1->select("JSON_ARRAYAGG($this->t_group.id_group)");
    //     $sq1->select("CONCAT_WS('', '[',
    //         GROUP_CONCAT(
    //             $this->t_group.id_group
    //         ),
    //     ']')");
    //     $sq1->join($this->t_group, "$this->t_group.id_group = $this->t_group_room.id_group", 'left');
    //     $sq1->where("$this->t_group_room.id_room", new RawSql("$this->t_devis_room.id_room"));
    //     $sq1_string = $sq1->getCompiledSelect();

    //     $sq2_sq = $this->db->table($this->t_road);
    //     // $sq2_sq->select("JSON_ARRAYAGG($this->t_road.id_road_parent)");
    //     $sq2_sq->select("CONCAT_WS('', '[',
    //         GROUP_CONCAT(
    //             $this->t_road.id_road_parent
    //         ),
    //     ']')");
    //     $sq2_sq->where("JSON_CONTAINS($this->t_group_room.ids_road, $this->t_road.id_road)");
    //     $sq2_sq_string = $sq2_sq->getCompiledSelect();

    //     $sq2 = $this->db->table($this->t_group_room);
    //     // $sq2->select("JSON_ARRAYAGG(JSON_OBJECT(
    //     //     'id_group', $this->t_group_room.id_group,
    //     //     'quantity', $this->t_group_room.quantity,
    //     //     'is_prior', $this->t_group_room.is_prior,
    //     //     'ids_road', $this->t_group_room.ids_road,
    //     //     'ids_road_parent', ($sq2_sq_string)
    //     // ))");
    //     $sq2->select("CONCAT_WS('', '[',
    //         GROUP_CONCAT(
    //             CONCAT_WS('', '{',
    //                 '\"id_group\":\"', COALESCE($this->t_group_room.id_group, 'null'), '\",',
    //                 '\"quantity\":\"', COALESCE($this->t_group_room.quantity, 'null'), '\",',
    //                 '\"is_prior\":\"', COALESCE($this->t_group_room.is_prior, 'null'), '\",',
    //                 '\"ids_road\":\"', COALESCE($this->t_group_room.ids_road, 'null'), '\",',
    //                 '\"ids_road_parent\":\"', ($sq2_sq_string), '\"',
    //             '}')
    //         ),
    //     ']')");
    //     $sq2->join($this->t_group, "$this->t_group.id_group = $this->t_group_room.id_group", 'left');
    //     $sq2->where("$this->t_group_room.id_room", new RawSql("$this->t_devis_room.id_room"));
    //     $sq2_string = $sq2->getCompiledSelect();

    //     $q = $this->db->table($this->t_devis_room);
    //     $q->select("$this->t_devis_room.*");
    //     $q->select("($sq1_string) as ids_group");
    //     $q->select("($sq2_string) as groups");
    //     $q->where('id_devis', $id_devis);

    //     $rooms = database_decode($q->get()->getResult());

    //     return $rooms;
    // }

    public function WorksGetByDevis($id_devis)
    {
        $sq2_sq = $this->db->table($this->t_road);
        // $sq2_sq->select("JSON_ARRAYAGG($this->t_road.id_road_parent)");
        $sq2_sq->select("CONCAT_WS('', '[',
            GROUP_CONCAT(
                $this->t_road.id_road_parent
            ),
        ']')");
        $sq2_sq->where("JSON_CONTAINS($this->t_work.ids_road, $this->t_road.id_road)");
        $sq2_string = $sq2_sq->getCompiledSelect();

        $q = $this->db->table($this->t_work);
        $q->select("$this->t_work.*");
        $q->select("($sq2_string) as ids_road_parent");
        $q->where('id_devis', $id_devis);

        $works = database_decode($q->get()->getResult());

        return $works;
    }

    public function DevisGet($id_demande, $id_devis=null)
    {
        $sq4 = $this->db->table($this->t_road);
        // $sq3->select("JSON_ARRAYAGG($this->t_road.id_road_parent)");
        $sq4->select("CONCAT_WS('', '[', GROUP_CONCAT($this->t_road.id_road_parent), ']')");
        $sq4->where("JSON_CONTAINS($this->t_group.ids_road_children, $this->t_road.id_road)");
        $sq4_string = $sq4->getCompiledSelect();

        $sq3 = $this->db->table($this->t_road);
        // $sq3->select("JSON_ARRAYAGG($this->t_road.id_road_parent)");
        $sq3->select("CONCAT_WS('', '[', GROUP_CONCAT($this->t_road.id_road_parent), ']')");
        $sq3->where("JSON_CONTAINS($this->t_group_work.ids_road, $this->t_road.id_road)");
        $sq3_string = $sq3->getCompiledSelect();

        $sq2 = $this->db->table($this->t_group_work);
        // $sq2->select("JSON_ARRAYAGG(JSON_OBJECT(
        //     'id_work', $this->t_group_work.id_work,
        //     'id_group', $this->t_group_work.id_group,
        //     'is_prior', $this->t_group_work.is_prior,
        //     'quantity', $this->t_group_work.quantity,
        //     'ids_road', $this->t_group_work.ids_road,
        //     'ids_road_parent', ($sq3_string)
        // ))");
        $sq2->select("CONCAT_WS('', '[',
            GROUP_CONCAT(
                CONCAT_WS('', '{',
                    '\"id_demande\":\"', $id_demande, '\",',
                    '\"id_work\":\"', COALESCE($this->t_group_work.id_work, 'null'), '\",',
                    '\"id_group\":\"', COALESCE($this->t_group_work.id_group, 'null'), '\",',
                    '\"label_fr\":\"', COALESCE($this->t_group.label_fr, 'null'), '\",',
                    '\"id_road_parent\":\"', COALESCE($this->t_group.id_road_parent, 'null'), '\",',
                    '\"ids_road_children\":\"', COALESCE($this->t_group.ids_road_children, 'null'), '\",',
                    '\"annotation_fr\":\"', COALESCE($this->t_group.annotation_fr, 'null'), '\",',
                    '\"annotation_nl\":\"', COALESCE($this->t_group.annotation_nl, 'null'), '\",',
                    '\"ids_road_children_parent\":', ($sq4_string), ',',
                    '\"measure\":\"', COALESCE($this->t_group.measure, 'null'), '\",',
                    '\"is_recommended\":\"', COALESCE($this->t_group_work.is_recommended, 'null'), '\",',
                    '\"is_prior\":\"', COALESCE($this->t_group_work.is_prior, 'null'), '\",',
                    '\"quantity\":\"', COALESCE($this->t_group_work.quantity, 'null'), '\",',
                    '\"ids_road\":\"', COALESCE($this->t_group_work.ids_road, 'null'), '\",',
                    '\"ids_road_parent\":', ($sq3_string),
                '}')
            ),
        ']')");
        $sq2->join($this->t_group, "$this->t_group.id_group = $this->t_group_work.id_group", 'left');
        $sq2->where("$this->t_group_work.id_work", new RawSql("$this->t_work.id_work"));
        $sq2_string = $sq2->getCompiledSelect();

        $sq1 = $this->db->table($this->t_work);
        // $sq1->select("JSON_ARRAYAGG(JSON_OBJECT(
        //     'id_work', $this->t_work.id_work,
        //     'label', $this->t_work.label,
        //     'groups', ($sq2_string)
        // ))");
        // $sq1->select("CONCAT_WS('', '[',
        //     GROUP_CONCAT(
        //         CONCAT_WS('', '{',
        //             '\"id_demande\":\"', $id_demande, '\",',
        //             '\"id_work\":\"', COALESCE($this->t_work.id_work, 'null'), '\",',
        //             '\"label\":\"', COALESCE($this->t_work.label, 'null'), '\",',
        //             '\"id_them\":\"', COALESCE($this->t_work.id_them, 'null'), '\",',
        //             '\"id_them_parent\":\"', COALESCE($this->t_road.id_road_parent, 'null'), '\",',
        //             '\"annotation\":\"', COALESCE($this->t_work.annotation, 'null'), '\",',
        //             '\"groups\":', ($sq2_string),
        //         '}')
        //     ),
        // ']')");
        $sq1->select("$id_demande as id_demande, $this->t_work.id_work, $this->t_work.label, $this->t_work.id_them, $this->t_work.id_them, $this->t_road.id_road_parent as id_them_parent, $this->t_work.annotation, ($sq2_string) as groups");
        $sq1->join($this->t_road, "$this->t_road.id_road = $this->t_work.id_them", 'left');
        $sq1->join("$this->t_road them_parent", "them_parent.id_road = $this->t_road.id_road_parent", 'left');
        $sq1->where("$this->t_work.id_demande", $id_demande);
        $sq1->orderBy("them_parent.rank", 'asc');
        $sq1->orderBy("$this->t_road.rank", 'asc');
        // $sq1->where("$this->t_work.id_demande", new RawSql("$this->t_bien_contact.id_demande"));
        // $sq1_string = $sq1->getCompiledSelect();

        $works = database_decode($sq1->get()->getResult());

        $q = $this->db->table($this->t_bien_contact);
        
        $q->select("$this->t_bien_contact.id_demande");
        $q->select("$this->t_bien.adresse_fr, $this->t_bien.etage_logement");
        $q->select("$this->t_l_bien_type.label as id_type_label");
        $q->select("$this->t_contact.nom_contact, $this->t_contact.prenom_contact");
        $q->select("$this->t_profil.telephone, $this->t_profil.email");
        $q->select("$this->t_calculator_demande.date_visite");
        $q->select("$this->t_calculator_demande.comment_difficulty");
        $q->select("$this->t_l_bien_contact_type.label as bien_contact_type");
        $q->select("$this->t_user.prenom as user_prenom, $this->t_user.nom as user_nom");
        // $q->select("($sq1_string) as works");

        $q->join($this->t_bien, "$this->t_bien.id_bien = $this->t_bien_contact.id_bien", 'left');
        $q->join($this->t_l_bien_contact_type, "$this->t_l_bien_contact_type.id = $this->t_bien_contact.rel_personne_bien", 'left');
        $q->join($this->t_l_bien_type, "$this->t_bien.id_type = $this->t_l_bien_type.id", 'left');
        $q->join($this->t_contact, "$this->t_contact.id_contact = $this->t_bien_contact.id_contact", 'left');
        $q->join($this->t_profil, "$this->t_profil.id_contact = $this->t_contact.id_contact", 'left');
        $q->join($this->t_demande, "$this->t_demande.id_demande = $this->t_bien_contact.id_demande", 'left');
        $q->join($this->t_user, "$this->t_user.id = $this->t_demande.id_utilisateur", 'left');
        $q->join($this->t_calculator_demande, "$this->t_calculator_demande.id_demande = $this->t_bien_contact.id_demande", 'left');
        $q->where("$this->t_bien_contact.id_demande", $id_demande);

        $devis = database_decode($q->get()->getRow());

        $devis->works = $works;

        return $devis;
    }

    // public function DemandeGet($id_demande)
    // {        
    //     $q = $this->db->table($this->t_bien_contact);
    //     $q->select("$this->t_bien_contact.id_demande");
    //     $q->select("$this->t_bien_contact.id_bien");
    //     $q->select("$this->t_bien_contact.id_contact");
    //     $q->select("$this->t_bien.adresse_fr, $this->t_bien.etage_logement");
    //     $q->select("$this->t_contact.nom_contact, $this->t_contact.prenom_contact");
    //     $q->select("$this->t_l_bien_contact_type.label as bien_contact_type");
    //     $q->select("$this->t_l_bien_type.label as id_type_label");
    //     $q->select("$this->t_rdv.date_rdv_debut");
    //     $q->select("$this->t_user.prenom as user_prenom, $this->t_user.nom as user_nom");

    //     $q->join($this->t_bien, "$this->t_bien.id_bien = $this->t_bien_contact.id_bien", 'left');
    //     $q->join($this->t_contact, "$this->t_contact.id_contact = $this->t_bien_contact.id_contact", 'left');
    //     $q->where("$this->t_bien_contact.id_demande", $id_demande);
    //     $demande = database_decode($q->get()->getRow());

    //     $demande->deviss = $this->DevissByDemande($id_demande);

    //     return $demande;
    // }

    // public function DevissByDemande($id_demande)
    // {
    //     $q = $this->db->table($this->t_devis);
    //     $q->select("$this->t_devis.*");
    //     $q->select("updated_user.prenom as updated_user_name, updated_user.nom as updated_user_lastname");
    //     $q->select("created_user.prenom as created_user_name, created_user.nom as created_user_lastname");
    //     $q->join("$this->t_user updated_user", "updated_user.id = $this->t_devis.updated_by", 'left');
    //     $q->join("$this->t_user created_user", "created_user.id = $this->t_devis.created_by", 'left');
    //     $q->where("$this->t_devis.id_demande", $id_demande);

    //     $deviss = database_decode($q->get()->getResult());        

    //     return $deviss;
    // }

    public function DevisSave($post, $id_demande)
    {
        $calculator = $this->db->table($this->t_calculator_demande)->where('id_demande', $id_demande)->get()->getResult();

        $data_demande = $post;
        $data_demande->updated_by = session('loggedUserId');
        if(!empty($calculator)) :
            $data_demande->updated_at = date('Y-m-d H:i:s');
            $this->db->table($this->t_calculator_demande)->set($data_demande)->where('id_demande', $id_demande)->update();
        else :
            $data_demande->id_demande = $id_demande;
            $data_demande->created_by = session('loggedUserId');
            $this->db->table($this->t_calculator_demande)->set($data_demande)->insert();
        endif;

        $ids_work_new = [];
        foreach($post->works as $id_work=>$work) :
            $work->id_demande = $id_demande;
            $work->updated_by = session('loggedUserId');
            if(preg_match('/^##\d+##$/', $id_work)) :
                unset($work->id_work);
                $work->created_by = session('loggedUserId');
                $this->db->table($this->t_work)
                    ->set(database_encode($this->t_work, $work))
                    ->insert();
                $id_work = $this->db->insertID();
            else :
                $work->updated_at = date('Y-m-d H:s:i');
                $this->db->table($this->t_work)
                    ->set(database_encode($this->t_work, $work))
                    ->where('id_demande', $id_demande)
                    ->where('id_work', $id_work)
                    ->update();
            endif;
            if(!empty($work->groups)) :
                $ids_group_new = [];
                foreach($work->groups as $id_group=>$group) :
                    $is_exists = $this->db->table($this->t_group_work)->where('id_work', $id_work)->where('id_group', $id_group)->get()->getResult();
                    $group->id_work = $id_work;
                    $group->id_group = $id_group;
                    $group->updated_by = session('loggedUserId');
                    if(empty($is_exists)) :
                        $group->created_by = session('loggedUserId');
                        $this->db->table($this->t_group_work)
                            ->set(database_encode($this->t_group_work, $group))
                            ->insert();
                    else :
                        $group->updated_at = date('Y-m-d H:s:i');
                        $this->db->table($this->t_group_work)
                            ->set(database_encode($this->t_group_work, $group))
                            ->where('id_group', $id_group)
                            ->where('id_work', $id_work)
                            ->update();
                    endif;
                    $ids_group_new[] = $id_group;
                endforeach;
                $this->db->table($this->t_group_work)->where('id_work', $id_work)->whereNotIn('id_group', $ids_group_new)->delete();
            endif;
            $ids_work_new[] = $id_work;
        endforeach;
        $this->db->table($this->t_work)->where('id_demande', $id_demande)->whereNotIn('id_work', $ids_work_new)->delete();

        return $ids_work_new;
    }

    public function GroupsSaveByRoom($id_room, $post)
    {
        $groups = $this->GroupsGetByRoom($id_room);
        $ids_group = !empty($groups) ? array_values(array_unique(array_column($groups, 'id_group'))) : [];
        $ids_group_new = [];

        if(!empty($post->ids_group)) :
            foreach($post->ids_group as $id_group) :
                $post->updated_by = session('loggedUserId');
                if(in_array($id_group, $ids_group)) :
                    $post->updated_at = date('Y-m-d H:i:s');
                    $this->db->table($this->t_group_room)
                        ->set(database_encode($this->t_group_room, $post))
                        ->where('id_room', $id_room)
                        ->where('id_group', $id_group)
                        ->update();
                    // dbdebug();
                else :
                    $post->created_by = session('loggedUserId');
                    $post->id_group = $id_group;
                    $this->db->table($this->t_group_room)
                        ->set(database_encode($this->t_group_room, $post))
                        ->insert();
                    // dbdebug();
                    $id_group = $this->db->insertID();
                endif;
                $ids_group_new[] = $id_group;
            endforeach;
        endif;

        $ids_group_delete = array_diff($ids_group, $ids_group_new);
        if(!empty($ids_group_delete)) :
            foreach($ids_group_delete as $id_group_delete) :
                $this->db->table($this->t_group_room)
                    ->where('id_room', $id_room)
                    ->where('id_group', $id_group_delete)
                    ->delete();
                // dbdebug();
            endforeach;
        endif;

        if(!empty($post->groups)) :
            foreach($post->groups as $group) :
                $this->db->table($this->t_group_room)
                    ->set(database_encode($this->t_group_room, $group))
                    ->where('id_room', $id_room)
                    ->where('id_group', $group->id_group)
                    ->update();
            endforeach;
        endif;

        return array_values(array_unique($ids_group_new));
    }

    // public function RoomsSaveByDevisNew($post)
    // {
    //     $max = $this->db->table($this->t_devis_room)->selectMax('id_devis')->selectMax('id_room')->get()->getRow();
    //     $id_devis = !empty($max->id_devis) ? $max->id_devis + 1 : 1;
    //     $id_room = !empty($max->id_room) ? $max->id_room + 1 : 1;
    //     $i = 0;

    //     foreach($post->rooms as $room) :
    //         $id_room = $id_room + $i;
    //         $data = $room;
    //         $data->id_room = $id_room;
    //         $data->updated_by = session('loggedUserId');
    //         $data->created_by = session('loggedUserId');
    //         $this->db->table($this->t_devis_room)
    //             ->set(database_encode($this->t_devis_room, $data))
    //             ->insert();
    //         // dbdebug();
    //         $i++;
    //     endforeach;

    //     return $id_devis;
    // }

    // public function RoomsSaveByDevis($id_devis, $post)
    // {
    //     $rooms = $this->RoomsGetByDevis($id_devis);
    //     $ids_room = !empty($rooms) ? array_values(array_unique(array_column($rooms, 'id_room'))) : [];
    //     $ids_room_new = [];

    //     foreach($post->rooms as $room) :
    //         if(empty($room->label)) continue;
    //         $data = $room;
    //         $data->updated_by = session('loggedUserId');
    //         if(!empty($room->id_room) && in_array($room->id_room, $ids_room)) :
    //             $data->updated_at = date('Y-m-d H:i:s');
    //             $this->db->table($this->t_devis_room)
    //                 ->set(database_encode($this->t_devis_room, $data))
    //                 ->where('id_room', $room->id_room)
    //                 ->where('id_devis', $id_devis)
    //                 ->update();
    //             // dbdebug();
    //             $id_room = $room->id_room;
    //         else :
    //             $data->created_by = session('loggedUserId');
    //             $data->id_devis = $id_devis;
    //             $max = $this->db->table($this->t_devis_room)->selectMax('id_room')->get()->getRow();
    //             $data->id_room = $max->id_room + 1;
    //             $this->db->table($this->t_devis_room)
    //                 ->set(database_encode($this->t_devis_room, $data))
    //                 ->insert();
    //             // dbdebug();
    //             $id_room = $this->db->insertID();
    //         endif;

    //         $this->GroupsSaveByRoom($id_room, $room);
    //         $ids_room_new[] = $id_room;
    //     endforeach;
    //     $ids_room_delete = array_diff($ids_room, $ids_room_new);

    //     if(!empty($ids_room_delete)) :
    //         foreach(array_diff($ids_room, $ids_room_new) as $id_room_delete) :
    //             $this->db->table($this->t_devis_room)
    //                 ->where('id_room', $id_room_delete)
    //                 ->where('id_devis', $id_devis)
    //                 ->delete();
    //             // dbdebug();
    //         endforeach;
    //     endif;
    // }

    public function WorkSave($post, $id_work=null)
    {
        $post->updated_by = session('loggedUserId');
        if(!empty($id_work)) :
            $post->updated_at = date('Y-m-d H:i:s');
            $this->db->table($this->t_work)->set(database_encode($this->t_work, $post))->where('id_work', $id_work)->update();
        else :
            $post->created_by = session('loggedUserId');
            $this->db->table($this->t_work)->set(database_encode($this->t_work, $post))->insert();
            $id_work = $this->db->insertID();
        endif;

        return $id_work;
    }

    private function DevisSaveWorks($id_devis, $post)
    {
        $works = $this->WorksGetByDevis($id_devis);
        $ids_work = !empty($works) ? array_values(array_unique(array_column($works, 'id_work'))) : [];
        $ids_work_new = [];
        foreach($post->groups as $id_group=>$value) :
            // $is_new_group = 1;
            if(!empty($value->works)) :
                foreach($value->works as $id_work=>$work) :
                    if(empty($work->work_name) && empty($work->quantity) && empty($work->ids_road)) continue;

                    $work->id_devis = $id_devis;
                    $work->id_group = $id_group;
                    if(preg_match('/^##\d+##$/', $id_work)) :
                        // create work
                        $ids_work_new[] = $this->WorkSave($work);
                    else :
                        // update work
                        $ids_work_new[] = $this->WorkSave($work, $id_work);
                    endif;

                endforeach;
            endif;
            // if(!empty($is_new_group)) :
            //     $data = (object) [];
            //     $data->id_devis = $id_devis;
            //     $data->id_group = $id_group;
            //     $ids_work_new[] = $this->WorkSave($data);
            // endif;
        endforeach;

        $ids_work_delete = array_diff($ids_work, $ids_work_new);
// debugd($ids_work_delete);

        if(!empty($ids_work_delete)) :
            foreach($ids_work_delete as $id_work_delete) :
                $this->db->table($this->t_work)->where('id_work', $id_work_delete)->delete();
                // dbdebug();
            endforeach;
        endif;
    }

    public function GroupsGetByRoom($id_room)
    {
        $groups = database_decode($this->db->table($this->t_group_room)->where('id_room', $id_room)->get()->getResult());

        return $groups;
    }
}