<?php

namespace Calculator\Libraries;

use Base\Libraries\BaseLibrary;
use Calculator\Models\AdminModel;
use Tesorus\Libraries\TesorusLibrary;
use Tesorus\Models\RoadModel;

class GroupLibrary extends BaseLibrary
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->AdminLibrary = new AdminLibrary();
        $this->AdminModel = new AdminModel();
        $this->TesorusLibrary = new TesorusLibrary();
    }

    public function GetGroupCollapse()
    {
        $html = '
            <div id="groupListCollapse" class="my-2 mx-4">
                <div class="row border-bottom sticky_button py-2 bg-light">
                    <div class="col-8 fw-bold">
                        Thématiques et groupes de travaux
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-4 text-center fw-bold"> Unité </div>
                            <div class="col border-start text-center fw-bold"> Actions </div>
                        </div>
                    </div>
                </div>
                ' . $this->GetGroupCollapseRecursive() . '
            </div>
        ';

        return $html;
    }

    public function GetGroupCollapseRecursive($roads=null, $level=0, $isMaskedInit=null)
    {
        if(!isset($roads)) $roads = $this->GetGroupsTesorus();

        $level++;
        $html = '';
        foreach($roads as $road):
            $isMasked = $isMaskedInit;
            if(empty($isMasked) && empty($road->isActive)) $isMasked = 1;
            $html .= $this->GetGroupCollapseBlock($road, $level, $isMasked);
            if(!empty($road->children)):
                $html .= '
                    <div id="road' . $road->id_road . 'Collapse" class="collapse group-sortable">
                        ' . $this->GetGroupCollapseRecursive($road->children, $level, $isMasked) . '
                    </div>
                ';
            endif;
            // $html .= '</div>';
        endforeach;
        
        return $html;
    }

    private function GetGroupCollapseBlock($road, $level, $isMasked=null)
    {
        $paddings = '';
        for($i=1; $i<$level; $i++) $paddings .= '<span class="mx-4"></span>';

        $label_fr = !empty($isMasked) ? '
            <div class="d-inline fst-italic small">' . $road->label_fr . '</div>
            <div class="d-inline btn btn-sm">' . fontawesome('eye-slash') . '</div>
            ' : '
            ' . $road->label_fr . '
        ';

        if(!empty($road->id_road)) :
            $button_add_group = '';
            if($level==2) $button_add_group .= '
                <button type="button" class="btn btn-sm btn-link link-dark text-decoration-none text-nowrap" 
                    title="Ajouter un groupe de travaux sous ce niveau"
                    onclick="group_new_modal(this, ' . $road->id_road . ');"
                    >
                    <small><small>' . fontawesome('plus') . '</small></small>
                    ' . fontawesome('toolbox') . '
                </button>
            ';
            $html = '
                <div class="row border-bottom">
                    <div class="col-8">
                        ' . $paddings . '
                        <label class="form-check-label mx-2 text-nowrap" for="road' . $road->id_road . 'Button">
                            ' . $label_fr . ' 
                        </label>
                        <button type="button" class="btn btn-sm btn-caret" 
                            id="road' . $road->id_road . 'Button"
                            data-bs-toggle="collapse" 
                            data-bs-target="#road' . $road->id_road . 'Collapse"
                            > 
                            ' . fontawesome('caret-right') . '
                        </button>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-4"></div>
                            <div class="col border-start">
                                <div class="row">
                                    <div class="col"></div>
                                    <div class="col"></div>
                                    <div class="col"></div>
                                    <div class="col"> ' . $button_add_group . ' </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        elseif(!empty($road->id_group)) :
            $group = $this->AdminModel->GroupGet($road->id_group);
            $button_active = $this->GetGroupCollapseButtonActive($road);
            $html = '
                <div class="row border-bottom group-sortable-row" id_group="' . $road->id_group . '">
                    <div class="col-8">
                        ' . $paddings . '
                        <label class="form-check-label mx-2 text-nowrap">
                            ' . $label_fr . ' 
                        </label>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-4"> ' . $group->measure . ' </div>
                            <div class="col border-start">
                                <div class="row">
                                    <div class="col">
                                        <a role="button" class="btn btn-sm road-move"
                                            title="Modifier le rang du groupe de travaux"
                                            >
                                            ' . fontawesome('sort') . '
                                        </a>
                                    </div>
                                    <div class="col"> ' . $button_active . ' </div>
                                    <div class="col">
                                        <a role="button" class="btn btn-sm"
                                            title="Aller à la fiche du groupe de travaux"
                                            href="' . base_url('calculator/group/' . $road->id_group) . '"
                                            >
                                            ' . fontawesome('info-circle') . '
                                        </a>
                                    </div>
                                    <div class="col"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        endif;

        return $html;
    }   
    
    public function GetGroupCollapseButtonActive($road)
    {
        if(!empty($road->isActive)) :
            $button_text = fontawesome('eye-slash');
            $button_title = 'Masquer';
        else :
            $button_text = fontawesome('eye');
            $button_title = 'Afficher';
        endif;

        $html = '                
                <button type="button" class="btn btn-sm p-1 mx-1"
                    title="' . $button_title . '"
                    onclick="group_update_active_modal(this, ' . $road->id_group . ', ' . $road->isActive . ');"
                    > 
                    ' . $button_text . ' 
                </button>
        ';

        return $html;
    }

    public function GetGroupTesorusTableHtml()
    {
        $html = '
            <div id="roadTable" class="table-responsive">
                <table class="table table-striped table-hover">
                    <tbody>
                        ' . $this->GetGroupTesorusTableRecursive() . '
                    </tbody>
                </table>
            </div>
        ';

        return $html;        
    }

    private function GetGroupTesorusTableRecursive($roads=null)
    {
        if(empty($roads)) $roads = $this->GetGroupsTesorus();

        $html = '';
        if(!empty($roads)) :
            // $i=0;
            foreach($roads as $road):
                $colspan_left = '';
                for($i=0; $i<$road->level; $i++) $colspan_left .= '<td class="px-4"></td>';
                $col_right = 3 - (int) $road->level;
                $colspan_right = $col_right > 0 ? '<td colspan="' . $col_right . '">' . $road->label_fr . '</td>' : '';
                $action = '';
                if(!empty($road->id_group)) :
                    $action = '
                        <a role="button" class="btn btn-sm btn-link link-dark"
                            title="Aller à la fiche du groupe de travaux"
                            href="' . base_url('calculator/group/' . $road->id_group) . '"
                        >
                        ' . fontawesome('info-circle') . '
                        </a>
                    ';
                elseif($road->level==1) :
                    $action = '
                        <button type="button" class="btn btn-sm btn-link link-dark text-decoration-none" 
                            title="Ajouter un groupe de travaux sous ce niveau"
                            onclick="group_new_modal(this, ' . $road->id_road . ');"
                            >
                            <small><small>' . fontawesome('plus') . '</small></small>
                            ' . fontawesome('toolbox') . '
                        </button>
                    ';
                endif;
                $html .= '
                    <tr>
                        ' . $colspan_left . '
                        ' . $colspan_right . '
                        <td class="text-center"> ' . $action . ' </td>
                    </tr>
                ';
                if(!empty($road->children)):
                    $html .= $this->GetGroupTesorusTableRecursive($road->children);
                endif;
            endforeach;
            $html .= '</tr>';
        endif;

        return $html;
    }

    public function GroupGetPath($group)
    {
        $labels = $this->TesorusLibrary->get_labels_by_id_road('calculator', $group->id_road_parent);
        $labels[] = $group->label_fr;

        return implode(' > ', $labels);
    }

    // private function GetGroupRoadsAvailableTableRecursive($id_group, $roads, $ids)
    // {
    //     $html = '';
    //     if(!empty($roads)) :
    //         // $i=0;
    //         foreach($roads as $road):
    //             if(
    //                 !in_array($road->id_road, $ids) || 
    //                 (!empty($road->children) && !array_intersect(array_column($road->children, 'id_road'), $ids))
    //             ) continue;

    //             $colspan_left = '';
    //             for($i=0; $i<$road->level; $i++) $colspan_left .= '<td class="px-4"></td>';
    //             $col_right = 3 - (int) $road->level;
    //             $redirect_post = !empty($road->is_terminus) ? '
    //                 <a role="button" class="btn btn-sm btn-link link-dark mb-1"
    //                     target="_blank"
    //                     title="Aller à la fiche du poste"
    //                     href="' . base_url('calculator/road/' . $road->id_road) . '"
    //                 >
    //                 ' . fontawesome('up-right-from-square') . '
    //                 </a>
    //             ' : '';
    //             $colspan_right = $col_right > 0 ? '
    //                 <td colspan="' . $col_right . '">
    //                     ' . $redirect_post . '
    //                     ' . $road->label_fr . '
    //                 </td>
    //             ' : '';
    //             $action = !empty($road->is_terminus) ? '
    //                 <input type="checkbox" class="mt-1" 
    //                     name="ids_road_children[]" form="NewRoadsForm" value="' . $road->id_road . '"
    //                     onclick="js_activate_add_roads_to_group(this);"
    //                 />
    //                 ' : '';
    //             $html .= '
    //                 <tr>
    //                     ' . $colspan_left . '
    //                     ' . $colspan_right . '
    //                     <td class="text-center"> ' . $action . ' </td>
    //                 </tr>
    //             ';
    //             if(!empty($road->children)):
    //                 $html .= $this->GetGroupRoadsAvailableTableRecursive($id_group, $road->children, $ids);
    //             endif;
    //         endforeach;
    //         $html .= '</tr>';
    //     endif;

    //     return $html;
    // }
    
    // public function GetGroupRoadsList($id_group)
    // {
    //     $group = $this->AdminModel->GroupGet($id_group);
    //     $roads = $this->AdminLibrary->RoadsGetRecursive($group->id_road_parent);

    //     $html = $this->GetGroupRoadsListRecursive($roads, $group);

    //     return $html;
    // }

    // public function GetGroupRoadsListRecursive($roads, $group)
    // {
    //     $html = '';
    //     if(!empty($roads)) :
    //         foreach($roads as $road):
    //             if(empty($group->ids_road_children)) continue;
    //             if(
    //                 in_array($road->id_road, $group->ids_road_children) || 
    //                 (!empty($road->children) && (array_intersect(array_column($road->children, 'id_road'), $group->ids_road_children_parent) || array_intersect(array_column($road->children, 'id_road'), $group->ids_road_children)))
    //             ) :
    //                 $html .= '<div class="ms-4">';
    //                 if(!empty($road->is_terminus)) :
    //                     $html .= '
    //                         <div class="d-flex align-items-center">
    //                             <label class="form-check-label"> ' . $road->label_fr . ' </label>
    //                             <div class="flex-grow-1 px-2"> <hr> </div>
    //                             <a role="button" class="btn btn-sm btn-link link-dark"
    //                                 target="_blank"
    //                                 title="Aller à la fiche du poste"
    //                                 href="' . base_url('calculator/road/' . $road->id_road) . '"
    //                             >
    //                             ' . fontawesome('up-right-from-square') . '
    //                             </a>
    //                         </div>
    //                     ';
    //                 else :
    //                     $html .= '
    //                         <div class="d-flex align-items-center">
    //                             <label class="form-check-label"> ' . $road->label_fr . ' </label>
    //                             <button type="button" class="btn btn-sm btn-link link-dark" disabled>
    //                                 ' . fontawesome('caret-down') . '
    //                             </a>
    //                         </div>
    //                     ';
    //                     if(!empty($road->children)) :
    //                         $html .= $this->GetGroupRoadsListRecursive($road->children, $group);
    //                     endif;
    //                 endif;
    //                 $html .= '</div>';
    //             endif;
    //         endforeach;
    //     endif;

    //     return $html;
    // }

    public function GetThemRoadsForm($form_id, $id_group)
    {
        $group = $this->AdminModel->GroupGet($id_group);
        $roads = $this->AdminLibrary->RoadsGetRecursive($group->id_road_parent, 'active');

        $html = $this->GetThemRoadsFormRecursive($form_id, $roads, $group);

        return $html;
    }

    private function GetThemRoadsFormRecursive($form_id, $roads, $group)
    {
        $html = '';
        if(!empty($roads)) :
            foreach($roads as $road):

                $html .= '<div class="ms-4">';
                if(!empty($road->is_terminus)) :
                    $display_class = (!empty($group->ids_road_children) && in_array($road->id_road, $group->ids_road_children)) ? 'form_read' : 'form_update';
                    $display = (!empty($group->ids_road_children) && in_array($road->id_road, $group->ids_road_children)) ? '' : 'style="display: none !important;"';
                    $checked = (!empty($group->ids_road_children) && in_array($road->id_road, $group->ids_road_children)) ? 'checked' : '';
                    $html .= '
                        <div class="d-flex align-items-center ' . $display_class . '" ' . $display . '>
                            <label class="form-check-label"> ' . $road->label_fr . ' </label>
                            <div class="d-flex form_update" style="display: none !important;">
                                <div class="flex-grow-1 px-2"> <hr> </div>
                                <input type="checkbox"
                                    class="form-check-input"
                                    form="' . $form_id . '"
                                    name="ids_road_children[]"
                                    value="' . $road->id_road . '"
                                    ' . $checked . '
                                />
                            </div>
                        </div>
                    ';
                else :
                    $display_class = (!empty($group->ids_road_children_parent) && (
                        in_array($road->id_road, $group->ids_road_children_parent) ||
                        array_intersect(array_column($road->children, 'id_road'), $group->ids_road_children_parent)
                    )) ? 'form_read' : 'form_update';
                    $display = (!empty($group->ids_road_children_parent) && (
                        in_array($road->id_road, $group->ids_road_children_parent) ||
                        array_intersect(array_column($road->children, 'id_road'), $group->ids_road_children_parent)
                    )) ? '' : 'style="display: none !important;"';
      
                    $html .= '
                        <div class="d-flex align-items-center ' . $display_class . '" ' . $display . '>
                            <label class="form-check-label"> ' . $road->label_fr . ' </label>
                            <button type="button" class="btn btn-sm btn-link link-dark" disabled>
                                ' . fontawesome('caret-down'). '
                            </button>
                        </div>  
                    ';
                    if(!empty($road->children)) :
                        $html .= $this->GetThemRoadsFormRecursive($form_id, $road->children, $group);
                    endif;
                endif;
                $html .= '</div>';
            endforeach;
        endif;

        return $html;
    }

//     private function GetGroupRoadsAvailableTableHtml($id_group, $id_road_parent, $ids_road_children)
//     {
//         $roads_flat = $this->AdminModel->EstimationsGetByRoadParent($id_road_parent);
//         $ids = !empty($ids_road_children) ? array_diff(array_column($roads_flat, 'id_road'), $ids_road_children) : array_column($roads_flat, 'id_road');
// // debugd($roads_flat);
//         $roads_group = $this->AdminLibrary->RoadsGetRecursive($id_road_parent);
//         $roads = $this->TesorusLibrary->get_roads_with_cell_recursive($roads_group, $ids);

//         $html = '
//             <div id="groupRoadsTable" class="table-responsive" style="max-height: 280px; overflow: auto;">
//                 <table class="table table-striped table-hover my-0">
//                     <thead class="table-light sticky-top" style="z-index:99;">
//                         <tr>
//                             <th colspan="3"> Postes disponibles </th>
//                             <th class="text-center">
//                                 <form id="NewRoadsForm" method="post" action="' . base_url("calculator/group/$id_group/roads/new") . '">
//                                     <button type="submit" form="NewRoadsForm" 
//                                         class="btn btn-sm btn-success disabled"
//                                         title="Ajouter les postes sélectionnés au groupe de travaux"
//                                         disabled
//                                         >
//                                         ' . fontawesome('plus') . '
//                                     </button>
//                                 </form>
//                             </th>
//                         </tr>
//                     </thead>
//                     <tbody>
//                         ' . $this->GetGroupRoadsAvailableTableRecursive($id_group, $roads, $ids) . '
//                     </tbody>
//                 </table>
//             </div>
//         ';

//         return $html;
//     }

    // private function GetGroupRoadsChildrenTableHtml($id_group, $id_road_parent, $ids_road_children)
    // {
    //     $roads_group = $this->AdminLibrary->RoadsGetRecursive($id_road_parent);
    //     $roads = $this->TesorusLibrary->get_roads_with_cell_recursive($roads_group, $ids_road_children);

    //     // $ids = [];
    //     // foreach($ids_road_children as $id_road) :
    //     //     $ids = array_values(array_filter(array_merge($ids, $this->TesorusLibrary->get_ids_road_by_id_road('calculator', $id_road))));
    //     // endforeach;

    //     // $roads = $this->TesorusLibrary->get_roads_active_recursive('calculator', $id_road_parent);
    //     // debugd($roads);

    //     $html = '
    //         <div id="groupRoadsTable" class="table-responsive">
    //             <table class="table table-striped table-hover">
    //                 <thead>
    //                     <tr>
    //                         <th colspan="3"> Postes et sous-postes </th>
    //                         <th class="text-center">
    //                             <form id="UnlinkRoadsForm" method="post" action="' . base_url("calculator/group/$id_group/roads/unlink") . '">
    //                                 <button type="submit" form="UnlinkRoadsForm" 
    //                                     class="btn btn-sm btn-danger disabled"
    //                                     title="Retirer les postes sélectionnés du groupe de travaux"
    //                                     disabled
    //                                     >
    //                                     ' . fontawesome('unlink') . '
    //                                 </button>
    //                             </form>
    //                         </th>
    //                     </tr>
    //                 </thead>
    //                 <tbody>
    //                     ' . $this->GetGroupRoadsChildrenTableRecursive($id_group, $roads) . '
    //                 </tbody>
    //             </table>
    //         </div>
    //     ';

    //     return $html;        
    // }

    // private function GetGroupRoadsChildrenTableRecursive($id_group, $roads)
    // {
    //     $html = '';
    //     if(!empty($roads)) :
    //         // $i=0;
    //         foreach($roads as $road):

    //             $colspan_left = '';
    //             for($i=0; $i<$road->level; $i++) $colspan_left .= '<td class="px-4"></td>';
    //             $col_right = 3 - (int) $road->level;
    //             $redirect_post = !empty($road->is_terminus) ? '
    //                 <a role="button" class="btn btn-sm btn-link link-dark mb-1"
    //                     target="_blank"
    //                     title="Aller à la fiche du poste"
    //                     href="' . base_url('calculator/road/' . $road->id_road) . '"
    //                 >
    //                 ' . fontawesome('up-right-from-square') . '
    //                 </a>
    //             ' : '';
    //             $colspan_right = $col_right > 0 ? '
    //                 <td colspan="' . $col_right . '">
    //                     ' . $redirect_post . '
    //                     ' . $road->label_fr . '
    //                 </td>
    //             ' : '';
    //             $action = !empty($road->is_terminus) ? '
    //                 <input type="checkbox" class="mt-1" 
    //                     name="ids_road_children[]" form="UnlinkRoadsForm" value="' . $road->id_road . '"
    //                     onclick="js_activate_unlink_roads_to_group(this);"
    //                 />
    //                 ' : '';
    //             $html .= '
    //                 <tr>
    //                     ' . $colspan_left . '
    //                     ' . $colspan_right . '
    //                     <td class="text-center"> ' . $action . ' </td>
    //                 </tr>
    //             ';
    //             if(!empty($road->children)):
    //                 $html .= $this->GetGroupRoadsChildrenTableRecursive($id_group, $road->children);
    //             endif;
    //         endforeach;
    //         $html .= '</tr>';
    //     endif;

    //     return $html;
    // }

    public function GetGroupsInactiveTesorus($roads=null)
    {
        if(empty($roads)) $roads = $this->GetGroupsTesorus();

        $datas = [];
        foreach($roads as $road) :
            if(empty($road->isActive)) :
                if(!empty($road->children)) $road->children = $this->GetGroupsInactiveTesorus($road->children);
                $datas[] = $road;
            endif;
        endforeach;

        return $datas;
    }

    public function GetGroupsActiveTesorus($roads=null)
    {
        if(empty($roads)) $roads = $this->GetGroupsTesorus();

        $datas = [];
        foreach($roads as $road) :
            if(!empty($road->isActive)) :
                if(!empty($road->children)) $road->children = $this->GetGroupsActiveTesorus($road->children);
                $datas[] = $road;
            endif;
        endforeach;

        return $datas;
    }

    public function GetGroupsTesorus($is_active=null)
    {
        $roads = $this->AdminLibrary->RoadsGetRecursive(null, $is_active);
        $i = 0;
        foreach($roads as $road) :
            if(!empty($road->children)) :
                $j = 0;
                foreach($road->children as $group_parent) :
                    $groups = $this->AdminModel->GroupsGetByRoadParent($group_parent->id_road, $is_active);
                    $k = 0;
                    foreach($groups as $group) :
                        if(!empty($group->ids_road_children_genealogy)) $groups[$k]->ids_road_children_genealogy = array_unique($group->ids_road_children_genealogy);
                        $groups[$k]->path = $roads[$i]->label_fr . ' > ' . $group_parent->label_fr . ' > ' . $group->label_fr;
                        $k++;
                    endforeach;
                    $group_parent->children = array_values($groups);
                    $roads[$i]->children[$j] = $group_parent;
                    $j++;
                endforeach;
            endif;
            $i++;
        endforeach;

        return $roads;
    }

    // public function GetGroupsRecursive($id_road_parent=0, $level=0)
    // {
    //     $roads = $this->AdminModel->GroupsGetByParent($id_road_parent);

    //     if(count($roads)>0):
    //         foreach($roads as $road):
    //             $road->level = $level;
    //             // $road->ids_cell = $this->TesorusLibrary->get_ids_cell_by_id_road('calculator_group', $road->id_road);
    //             // $road->labels = $this->TesorusLibrary->get_labels_by_id_road('calculator_group', $road->id_road);
    //             // $road->ids_road = $this->TesorusLibrary->get_ids_road_by_id_road('calculator_group', $road->id_road);
    //             // $road->path = implode(' > ', $road->labels);
    //             $road->children = $this->GetGroupsRecursive($road->id_road, $level+1);
    //         endforeach;
    //     endif;

    //     return $roads;
    // }

    // -------------------------------- ROAD TAG --------------------------------

    public function get_road_tag_button($road_name)
    {
        return '
            <button type="button" class="btn btn-sm mx-2" 
                title="Sélection par tags"
                onclick="road_view_modal(this, \'' . $road_name . '\', \'tag\', \'xl\');"
                > 
                ' . fontawesome('tags') . '
            </button>
        ';
    }

    public function GetGroupTagHtml($type, $name, $form_id=null, $ids_checked=null)
    {
        $roads = $this->GetGroupsActiveTesorus();

        $html_inactive = !empty($ids_checked) ? $this->GetGroupTagInactive($ids_checked) : '';
        $html_select = $this->GetGroupTagSelect($type, $name, $form_id, $roads, $ids_checked);
        $html_checkbox = $this->GetGroupTagCheckbox($type, $name, $form_id, $roads, $ids_checked);
        $html_js = view('Tesorus\js/js_tesorus_tag');

        $html = '
            <div id="GroupTagContainer" class="form-control-group bg-white">
                ' . $html_inactive . '
                <div class="row bg-white">
                    ' . $html_select . '
                    <a role="button" class="d-block text-dark mt-2" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#GroupCollapse"
                        >
                        <small class="font-weight-bold"> ' . t("Voir l'arborescence", __NAMESPACE__) . '</small>
                    </a>
                </div>
                <div id="GroupCollapse" class="collapse px-4">
                    <div class="d-flex justify-content-end form-nav">
                        <button type="button" class="btn btn-sm d-block bg-white" 
                            data-bs-toggle="collapse"  
                            data-bs-target="#GroupCollapse"
                            > 
                            <small class="font-weight-bold"> ' . t("Masquer l'arborescence", __NAMESPACE__) . '</small> 
                        </button>
                    </div>
                    ' . $html_checkbox . '
                </div>
            </div>
            ' . $html_js . '
        ';

        return $html;
    }

    private function GetGroupTagCheckbox($type, $name, $form_id, $roads=null, $ids_checked=null)
    {
        $html = '<div>';
        foreach($roads as $road):
            $html .= $this->GetGroupTagCheckboxRow($type, $name, $form_id, $road, $ids_checked);
            if(!empty($road->children)):
                $html .= '
                    <div id="road' . $road->id_road . 'Collapse" class="ms-5">
                        ' . $this->GetGroupTagCheckbox($type, $name, $form_id, $road->children, $ids_checked) . '
                    </div>
                ';
            endif;
        endforeach; 
        $html .= '</div>';

        return $html;
    }
    
    private function GetGroupTagCheckboxRow($type, $name, $form_id, $road, $ids_checked=null)
    {
        $checked_html = (isset($ids_checked) && isset($road->id_group) && in_array($road->id_group, $ids_checked)) ? 'checked' : '';
        $form_id_html = !empty($form_id) ? 'form="' . $form_id . '"' : '';
        $name .= $type=='checkbox' ? '[]' : '';
        $html = empty($road->id_group) ? '
            <label class="form-check-label text-left text-nowrap">
                ' . t($road->label_fr, __NAMESPACE__) . '
            </label>
        ' : '
            <div class="form-check">
                <input class="form-check-input" type="' . $type . '"
                    onclick="road_checkbox_to_tag(this);"
                    name="' . $name . '"
                    ' . $form_id_html . '
                    value="' . $road->id_group . '"
                    id="road' . $road->id_group . 'Checkbox"
                    ' . $checked_html . '
                    />
                <label class="form-check-label text-left text-nowrap" for="road' . $road->id_group . 'Checkbox">
                    ' . t($road->label_fr, __NAMESPACE__) . '
                </label>
            </div>
        ';
        return $html;
    }

    private function GetGroupTagSelect($type, $name, $form_id, $roads, $ids_checked=null)
    {
        if(!empty($ids_checked) && is_int($ids_checked)) $ids_checked = [$ids_checked];

        $options = [];
        foreach($roads as $road) :
            if(empty($road->children)) continue;
            foreach($road->children as $group_parent) :
                if(empty($group_parent->children)) continue;
                foreach($group_parent->children as $group) :
                    $option = (object) [];
                    $option->id_group = $group->id_group;
                    $option->label_fr = $group->label_fr;
                    $option->path = $group->path;
                    $options[] = $option;
                endforeach;
            endforeach;
        endforeach;

        $form_id_html = !empty($form_id) ? 'form="' . $form_id . '"' : '';
        $multiple = $type=='checkbox' ? 'multiple' : '';
        $name .= $type=='checkbox' ? '[]' : '';
        $html = '
            <select 
                class="bs-multi-select tags-block form-control"
                ' . $multiple . '
                onchange="road_tag_to_checkbox(this);"
                name-disabled="' . $name . '"
                ' . $form_id_html . '
                id="GroupTesorusSelect"
                >
        ';
        $html .= $type=='checkbox' ? '' : '<option disabled selected hidden></option>';
        foreach($options as $option) :
            $selected = (!empty($ids_checked) && in_array($option->id_group, $ids_checked)) ? 'selected' : '';
            $html .= '<option value="' . $option->id_group . '" ' . $selected . '>' . $option->path . '</option>';
        endforeach;
        $html .= '</select>';

        return $html;
    }

    private function GetGroupTagInactive($name, $ids_checked=null)
    {
        $inactives = $this->GetGroupsInactiveTesorus();
        if(empty($inactives)) return '';

        $html_array = [];
        foreach($inactives as $inactive) :
            if(in_array($inactive->id_group, $ids_checked)) :
                $html_array[] = '
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked
                            name="' . $name . '[]"
                            value="' . $inactive->id_group . '"
                            id="road' . $inactive->id_group . 'Checkbox"
                        />
                        <label class="form-check-label text-left text-nowrap w-auto ml-0 mr-1" 
                            for="road' . $inactive->id_group . 'Checkbox"
                            >
                            ' . $inactive->path . '
                        </label>
                    </div>
                ';
            endif;
        endforeach;
        if(empty($html_array)) return '';

        $html = '
            <div class="alert alert-warning mb-4"> 
                <small> 
                    <p>La liste a été modifiée et il reste des données enregistrées obsolètes. Veuillez les désélectionner et encoder les valeurs actualisées. </p>
                    <p>' . implode('', $html_array) . '</p>
                </small> 
            </div>
        ';

        return $html;
    }
    
}