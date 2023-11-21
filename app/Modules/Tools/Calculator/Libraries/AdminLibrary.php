<?php

namespace Calculator\Libraries;

use Autorisation\Libraries\AutorisationLibrary;
use Base\Libraries\BaseLibrary;
use Calculator\Models\AdminModel;
use Layout\Libraries\LayoutLibrary;
use Tesorus\Libraries\TesorusLibrary;

class AdminLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->AdminModel = new AdminModel();
        $this->TesorusLibrary = new TesorusLibrary();
        $this->Autorisation = new AutorisationLibrary();

        $LayoutLibrary = new LayoutLibrary();
        $this->themes = $LayoutLibrary->getThemes();
    }

    public function GetRoadCollapse()
    {
        $html = '
            <div id="roadListCollapse" class="my-2 mx-4">
                <div class="row border-bottom py-2 bg-light sticky_button">
                    <div class="col-6 fw-bold">
                        Thématiques et travaux
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-2 text-center fw-bold"> PU moyen </div>
                            <div class="col-4 text-center fw-bold"> Mise à jour le </div>
                            <div class="col-2 text-center fw-bold"> Période de calcul </div>
                            <div class="col text-center fw-bold border-start"> Actions </div>
                        </div>
                    </div>
                </div>
                ' . $this->GetRoadCollapseRecursive() . '
            </div>
        ';

        return $html;
    }

    public function GetRoadCollapseRecursive($roads=null, $level=0, $isMaskedInit=null)
    {
        $roads = !empty($roads) ? $roads : $this->RoadsGetRecursive();

        $level++;
        $html = '';
        foreach($roads as $road):
            $isMasked = $isMaskedInit;
            if(empty($isMasked) && empty($road->isActive)) $isMasked = 1;
            $html .= $this->GetRoadCollapseBlock($road, $level, $isMasked);
            if(!empty($road->children)):
                $html .= '
                    <div id="road' . $road->id_road . 'Collapse" class="collapse">
                        ' . $this->GetRoadCollapseRecursive($road->children, $level, $isMasked) . '
                    </div>
                ';
            endif;
            // $html .= '</div>';
        endforeach;
        
        return $html;
    }

    private function GetRoadCollapseBlock($road, $level, $isMasked=null)
    {
        $paddings = '';
        for($i=1; $i<$level; $i++) $paddings .= '<span class="mx-4"></span>';

        $label_fr = !empty($isMasked) ? '
            <div class="d-inline fst-italic small">' . $road->label_fr . '</div>
            <div class="d-inline btn btn-sm">' . fontawesome('eye-slash') . '</div>
            ' : '
            ' . $road->label_fr . '
        ';

        $button_active_toggle = $this->TesorusLibrary->button_active_toggle('calculator', $road);

        if(!empty($road->is_terminus)) :
            // $action .= $this->TesorusLibrary->button_active_toggle('calculator', $road);
            $html = '
                <div class="row border-bottom">
                    <div class="col-6">
                        ' . $paddings . '
                        <label class="form-check-label mx-2 text-nowrap" for="road' . $road->id_road . 'Button">
                            ' . $label_fr . ' 
                        </label>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-2 text-end"> ' . $road->avg_price . ' </div>
                            <div class="col-4 text-end"> ' . convert_date_en_to_fr_with_h($road->updated_at, true, false) . ' </div>
                            <div class="col-2 text-end"> ' . $road->period_month_calcul . ' </div>
                            <div class="col border-start">
                                <div class="row">
                                    <div class="col">
                                        <a role="button" class="btn btn-sm btn-link link-dark p-1 mx-1"
                                            title="Aller à la fiche du poste"
                                            onclick="waiting_start(this);"
                                            href="' . base_url('calculator/road/' . $road->id_road) . '"
                                            >
                                            ' . fontawesome('info-circle') . '
                                        </a>
                                    </div>
                                    <div class="col"> ' . $button_active_toggle . ' </div>
                                    <div class="col"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        else :
            $button_add_post = '';
            if($road->level>=1 && empty($road->is_terminus)) $button_add_post .= '
                <button type="button" class="road-sub road-new btn btn-sm btn-link link-dark text-decoration-none text-nowrap p-1 mx-1" 
                    title="Ajouter un poste sous ce niveau"
                    onclick="road_new_modal(this, \'calculator\', ' . $road->id_road . ', \'is_terminus\');"
                    >
                    <small><small>' . fontawesome('plus') . '</small></small>
                    ' . fontawesome('tag') . '
                </button>
            ';
            $button_collapse = '';
            if(!empty($road->children)) $button_collapse .= '
                <button type="button" class="btn btn-sm btn-caret" 
                    id="road' . $road->id_road . 'Button"
                    data-bs-toggle="collapse" 
                    data-bs-target="#road' . $road->id_road . 'Collapse"
                    > 
                    ' . fontawesome('caret-right') . '
                </button>
            ';
            // $action .= $this->TesorusLibrary->button_active_toggle('calculator', $road);
            $button_delete_road = '';
            if(!empty($road->is_terminus) && $this->Autorisation->is_autorise('calculator_d', $road->created_by)) :
                $button_delete_road .= '
                    <button class="ban_deleteForm btn btn-sm btn-link link-danger p-1 mx-1"
                        title="Supprimer le chemin"
                        id_delete="' . $road->id_road . '"
                        href="' . base_url('calculator/road/' . $road->id_road) . '/delete"
                        text_alert="le chemin sélectionné"
                        >
                        ' . fontawesome('trash-alt') . '
                    </button>
                ';
            endif;

            $html = '
                <div class="row border-bottom">
                    <div class="col-6">
                        ' . $paddings . '
                        <label class="form-check-label mx-2 text-nowrap" for="road' . $road->id_road . 'Button">
                            ' . $label_fr . ' 
                        </label>
                        ' . $button_collapse . '
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col offset-8 border-start">
                                <div class="row">
                                    <div class="col"></div>
                                    <div class="col"></div>
                                    <div class="col">' . $button_add_post . '</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';

        endif;

        return $html;
    }

    public function RoadsGetRecursive($id_road_parent=0, $is_active=null, $level=0)
    {
        $roads = $this->AdminModel->RoadsGetByParent($id_road_parent, $is_active);

        if(count($roads)>0):
            foreach($roads as $road):
                $road->level = $level;
                // $road->ids_cell = $this->TesorusLibrary->get_ids_cell_by_id_road('calculator', $road->id_road);
                // $road->labels = $this->TesorusLibrary->get_labels_by_id_road('calculator', $road->id_road);
                // $road->ids_road = $this->TesorusLibrary->get_ids_road_by_id_road('calculator', $road->id_road);
                // $road->path = implode(' > ', $road->labels);
                $road->children = $this->RoadsGetRecursive($road->id_road, $is_active, $level+1);
            endforeach;
        endif;

        return $roads;
    }

    public function GetRoadTesorusTableHtml()
    {
        $html = '
            <div id="roadTable" class="table-responsive table-fullview"> 
                <table class="table table-striped table-hover">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th colspan="5"> Postes et sous-postes </th>
                            <th> Unité de mesure </th>
                            <th> PU min </th>
                            <th> PU moyen </th>
                            <th> PU max </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="10">
                                <button type="button" 
                                    class="road-sub road-new btn btn-sm btn-link link-dark text-decoration-none text-nowrap" 
                                    title="Ajouter un chemin à la racine"
                                    onclick="road_new_modal(this, \'calculator\', 0);"
                                    >
                                    <small><small>' . fontawesome('plus') . '</small></small>
                                    ' . fontawesome('code-branch') . '
                                </button>
                            </td>
                        </tr>
                        ' . $this->GetRoadTesorusTableRecursive() . '
                    </tbody>
                </table>
            </div>
        ';

        return $html;        
    }

    private function GetRoadTesorusTableRecursive($roads=null)
    {
        if(!isset($roads)) $roads = $this->RoadsGetRecursive();

        // debugd($roads);

        $html = '';
        if(!empty($roads)) :
            // $i=0;
            foreach($roads as $road):
                $colspan_left = '';
                for($i=0; $i<$road->level; $i++) $colspan_left .= '<td class="px-4"></td>';
                $col_right = 5 - (int) $road->level;
                $colspan_right = $col_right > 0 ? '<td colspan="' . $col_right . '">' . $road->label_fr . '</td>' : '';
                if(!empty($road->is_terminus)) :
                    $action = '
                        <a role="button" class="btn btn-sm btn-link link-dark p-1 mx-1"
                            title="Aller à la fiche du poste"
                            onclick="waiting_start(this);"
                            href="' . base_url('calculator/road/' . $road->id_road) . '"
                        >
                        ' . fontawesome('info-circle') . '
                        </a>
                    '; 

                    // $action .= $this->TesorusLibrary->button_active_toggle('calculator', $road);

                else :
                    $action = $this->TesorusLibrary->button_sublevel_create('calculator', $road);

                    if($road->level>0) $action .= '
                        <button type="button" class="road-sub road-new btn btn-sm btn-link link-dark text-decoration-none text-nowrap p-1 mx-1" 
                            title="Ajouter un poste sous ce niveau"
                            onclick="road_new_modal(this, \'calculator\', ' . $road->id_road . ', \'is_terminus\');"
                            >
                            <small><small>' . fontawesome('plus') . '</small></small>
                            ' . fontawesome('tag') . '
                        </button>
                    ';

                    // $action .= $this->TesorusLibrary->button_active_toggle('calculator', $road);

                    if(empty($road->children) && $this->Autorisation->is_autorise('calculator_d', $road->created_by)) :
                        $action .= '
                            <button class="ban_deleteForm btn btn-sm btn-link link-danger p-1 mx-1"
                                title="Supprimer le chemin"
                                id_delete="' . $road->id_road . '"
                                href="' . base_url('calculator/road/' . $road->id_road) . '/delete"
                                text_alert="le chemin sélectionné"
                                >
                                ' . fontawesome('trash-alt') . '
                            </button>
                        ';
                    endif;
                endif;
                $html .= '
                    <tr>
                        ' . $colspan_left . '
                        ' . $colspan_right . '
                        <td> ' . $road->measure . ' </td>
                        <td> ' . $road->min_price . ' </td>
                        <td> ' . $road->avg_price . ' </td>
                        <td> ' . $road->max_price . ' </td>
                        <td class="text-end"> ' . $action . ' </td>
                    </tr>
                ';
                if(empty($road->is_terminus)):
                    $html .= $this->GetRoadTesorusTableRecursive($road->children);
                endif;
            endforeach;
            $html .= '</tr>';
        endif;
        // $road_0 = !empty($roads[0]) ? $roads[0] : null;
        // $html .= '<tr>' . $this->get_road_edit_new($road_0) . '<tr>';

        return $html;
    }

    // public function EstimationTableRefresh()
    // {
    //     $this->db->table($this->t_estimation)->truncate();
    //     $roads = $this->RoadsGetFlat();
    //     foreach($roads as $road) :
    //         $this->AdminModel->EstimationInsertByRoad($road);
    //     endforeach;
    // }

    // public function EstimationSaveByRoad($id_road)
    // {
    //     $post = $this->AdminModel->RoadGet($id_road);
    //     $this->AdminModel->EstimationSaveByRoad($post, $id_road)
    // }
    
    // public function RoadsGetFlat($roads=[], $datas=[])
    // {
    //     $roads = !empty($roads) ? $roads : $this->RoadsGetRecursive();

    //     foreach($roads as $road) :
    //         if(!empty($road->is_terminus)) :
    //             $datas[] = $this->AdminModel->RoadGetEstimation($road);
    //         elseif(!empty($road->children)) :
    //             $datas = $this->RoadsGetFlat($road->children, $datas);
    //         endif;
    //     endforeach;

    //     return $datas;
    // }

    // public function RoadsGetFlatWithGroup($roads=[], $datas=[], $group=null)
    // {
    //     // $roads = !empty($roads) ? $roads : $this->RoadsGetRecursiveWithGroup();

    //     foreach($roads as $road) :
    //         if(!empty($road->is_terminus) && isset($group)) :
    //             $datas[] = $this->AdminModel->RoadGetEstimationWithGroup($road, $group);
    //         elseif(!empty($road->children)) :
    //             if(!empty($road->id_group)) :
    //                 $group = $road;
    //             endif;
    //             $datas = $this->RoadsGetFlatWithGroup($road->children, $datas, $group);
    //         endif;
    //     endforeach;

    //     return $datas;
    // }

    // public function EstimationPopulateRoads()
    // {
    //     $roads = $this->AdminModel->EstimationsGet();
    //     foreach($roads as $road) :
    //         $this->AdminModel->EstimationInsertByRoad($road);
    //     endforeach;
    // }

    // public function EstimationGetGroupsByRoad($id_road)
    // {
    //     $groups = $this->AdminModel->GroupsGetByRoadParent($id_road);

    //     if(empty($groups)) return null;

    //     $j = 0;
    //     foreach($groups as $group) :
    //         if(!empty($group->ids_road_children)) :
    //             $roads_group = $this->RoadsGetRecursive($group->id_road_parent, null, 2);
    //             $roads_group_selected = $this->TesorusLibrary->get_roads_with_cell_recursive($roads_group, $group->ids_road_children);
    //             $groups[$j]->children = $roads_group_selected;
    //         endif;
    //         $j++;
    //     endforeach;
    //     return $groups;

    // }

    // public function RoadsGetRecursiveWithGroup($roads=null)
    // {
    //     $roads = !empty($roads) ? $roads : $this->RoadsGetRecursive();

    //     $i = 0;
    //     foreach($roads as $road) :
    //         $groups = $this->EstimationGetGroupsByRoad($road->id_road);
    //         if(!empty($groups)) :
    //             $roads[$i]->children = $groups;
    //         else :
    //             if(!empty($road->children)) :
    //                 // $roads[$i]->children = $this->RoadsGetRecursiveWithGroup($road->children);
    //             endif;
    //         endif;
    //         $i++;
    //     endforeach;

    //     return $roads;
    // }

    // public function EstimationHtmlTable()
    // {
    //     $html = '
    //         <div class="row border-bottom sticky_button bg-light py-2">
    //             <div class="col-6"> Postes et groupes de travaux </div>
    //             <div class="col-6">
    //                 <div class="row">
    //                     <div class="col text-end"> Unité de mesure </div>
    //                     <div class="col text-end"> PU min </div>
    //                     <div class="col text-end"> PU moyen </div>
    //                     <div class="col text-end"> PU max </div>
    //                 </div>
    //             </div>
    //         </div>
    //         ' . $this->EstimationHtmlTableRecursive() . '
    //     ';

    //     return $html;        
    // }

    // private function EstimationHtmlTableRecursive($roads=null)
    // {
    //     $roads = !empty($roads) ? $roads : $this->RoadsGetRecursiveWithGroup();

    //     $html = '';
    //     if(!empty($roads)) :
    //         foreach($roads as $road):
    //             $span = '';
    //             if(isset($road->level)) :
    //                 if($road->level<=1) $level = $road->level;
    //                 else $level = $road->level + 1;
    //             else :
    //                 $level = 2;
    //             endif;
    //             for($i=0; $i<$level;$i++) $span .= '<span class="mx-4"></span>';

    //             $collapse_id = !empty($road->id_road) ? 'Road' . $road->id_road . 'Collapse' : 'Group' . $road->id_group . 'Collapse';
    //             if(isset($road->level)) :
    //                 if($road->level==0) :
    //                     // case first level
    //                     $html .= '
    //                         <div class="row border border-2 border-dark py-2 mt-2">
    //                             <div class="col-6">
    //                                 ' . $span . ' <b> ' . $road->label_fr . ' </b>
    //                                 <button class="btn btn-sm btn-caret p-1 mx-1"
    //                                     data-bs-toggle="collapse"
    //                                     data-bs-target="#' . $collapse_id . '"
    //                                     >
    //                                     ' . fontawesome('caret-down'). '
    //                                 </button>
    //                             </div>
    //                         </div>
    //                     ';
    //                 elseif($road->level==1) :
    //                     // case second level
    //                     $html .= '
    //                         <div class="row border-bottom py-1">
    //                             <div class="col-6">
    //                                 ' . $span . ' ' . $road->label_fr . '
    //                                 <button class="btn btn-sm btn-caret p-1 mx-1"
    //                                     data-bs-toggle="collapse"
    //                                     data-bs-target="#' . $collapse_id . '"
    //                                     >
    //                                     ' . fontawesome('caret-down'). '
    //                                 </button>
    //                             </div>

    //                         </div>
    //                     ';
    //                 elseif(empty($road->is_terminus)) :
    //                     // case sub levels
    //                     $html .= '
    //                         <div class="row border-bottom py-1">
    //                             <div class="col-6">
    //                                 ' . $span . '
    //                                 ' . $road->label_fr . '
    //                             </div>
    //                         </div>
    //                     ';
    //                 else :
    //                     // case final post
    //                     $html .= '
    //                         <div class="row border-bottom py-1">
    //                             <div class="col-6">
    //                                 ' . $span . '
    //                                 ' . $this->themes->calculator_post->icon . '
    //                                 ' . $road->label_fr . '
    //                             </div>
    //                             <div class="col-6">
    //                                 <div class="row">
    //                                     <div class="col text-end"></div>
    //                                     <div class="col text-end"> ' . $road->min_price . ' </div>
    //                                     <div class="col text-end"> ' . $road->avg_price . ' </div>
    //                                     <div class="col text-end"> ' . $road->max_price . ' </div>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                     ';
    //                 endif;
    //             else :
    //                 // case group
    //                 $html .= '
    //                     <div class="row border-bottom py-1">
    //                         <div class="col-6">
    //                             ' . $span . '
    //                             ' . $this->themes->calculator_group->icon . '
    //                             ' . $road->label_fr . '
    //                             <button class="btn btn-sm btn-caret p-1 mx-1"
    //                                 data-bs-toggle="collapse"
    //                                 data-bs-target="#' . $collapse_id . '"
    //                                 >
    //                                 ' . fontawesome('caret-down'). '
    //                             </button>
    //                         </div>
    //                         <div class="col-6">
    //                             <div class="row">
    //                             <div class="col text-end"> ' . $road->measure . ' </div>
    //                             <div class="col text-end"></div>
    //                             <div class="col text-end"></div>
    //                             <div class="col text-end"></div>
    //                         </div>
    //                     </div>
    //             </div>
    //                 ';
    //             endif;

    //             if(!empty($road->children)):
    //                 $html .= '
    //                     <div id="' . $collapse_id . '" class="collapse">
    //                         ' . $this->EstimationHtmlTableRecursive($road->children) . '
    //                         <div class="my-2"></div>
    //                     </div>
    //                 ';
    //             endif;
    //         endforeach;
    //     endif;

    //     return $html;
    // }
}