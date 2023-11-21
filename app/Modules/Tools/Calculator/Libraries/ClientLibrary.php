<?php

namespace Calculator\Libraries;

use Base\Libraries\BaseLibrary;
use Calculator\Libraries\GroupLibrary;
use Calculator\Models\AdminModel;
use Calculator\Models\ClientModel;
use Components\Libraries\PhpdocxLibrary;
use Layout\Libraries\LayoutLibrary;
use Tesorus\Libraries\TesorusLibrary;
use Tesorus\Models\RoadModel;

class ClientLibrary extends BaseLibrary
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->ClientModel = new ClientModel();
        $this->AdminModel = new AdminModel();
        $this->AdminLibrary = new AdminLibrary();
        $this->GroupLibrary = new GroupLibrary();

        $LayoutLibrary = new LayoutLibrary();
        $this->themes = $LayoutLibrary->getThemes();
    }

    // -------------------------------- ROAD THEMATIQUE TAG --------------------------------

    public function GetThemsTesorus($type)
    {
        $roads = $this->AdminLibrary->RoadsGetRecursive(null, 'active');

        $i = 0;
        foreach($roads as $road_0) :
            if(
                ($type=='active' && empty($road_0->isActive)) ||
                ($type=='inactive' && !empty($road_0->isActive))
            ) :
                unset($road_0);
            else :
                if(!empty($road_0->children)) :
                    $j = 0;
                    foreach($road_0->children as $road_1) :
                        if(
                            ($type=='active' && empty($road_1->isActive)) ||
                            ($type=='inactive' && !empty($road_1->isActive))
                        ) :
                            unset($road_1);
                        else :
                            $road_1->path = $road_0->label_fr . ' > ' . $road_1->label_fr;
                            if(!empty($road_1->children)) unset($road_1->children);
                            if(!empty($road_1->group)) unset($road_1->group);
                            $roads[$i]->children[$j] = $road_1;
                        endif;
                        $j++;
                    endforeach;
                endif;
            endif;
            $i++;
        endforeach;

        return $roads;
    }

    public function GetThemTagHtml($name, $form_id=null, $id_checked=null)
    {
        $roads = $this->GetThemsTesorus('active');

        $html_inactive = !empty($id_checked) ? $this->GetThemTagInactive($name, $id_checked) : '';
        $html_select = $this->GetThemTagSelect($name, $form_id, $roads, $id_checked);
        $html_checkbox = $this->GetThemTagCheckbox($name, $form_id, $roads, $id_checked);
        $html_js = view('Tesorus\js/js_tesorus_tag');

        $html = '
            <div id="DevisThemTagContainer" class="form-control-group bg-white">
                ' . $html_inactive . '
                <div class="row bg-white">
                    <div class="col">' . $html_select . '</div>
                    <div class="col-auto">
                        <a role="button" class="btn btn-sm btn-link link-dark" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#DevisThemCollapse"
                            title="' . t("Voir l'arborescence", __NAMESPACE__) . '"
                            >
                            ' . fontawesome('eye') . '
                        </a>
                    </div>
                </div>
                <div id="DevisThemCollapse" class="collapse px-4">
                    <div class="sticky_button d-flex justify-content-end form-nav">
                        <button type="button" class="btn btn-sm d-block bg-white" 
                            data-bs-toggle="collapse"  
                            data-bs-target="#DevisThemCollapse"
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

    private function GetThemTagCheckbox($name, $form_id, $roads=null, $id_checked=null)
    {
        $html = '<div>';
        foreach($roads as $road_0):
            $html .= '
                <label class="form-check-label text-left text-nowrap">
                    ' . t($road_0->label_fr, __NAMESPACE__) . '
                </label>
            ';
            if(!empty($road_0->children)):
                $html .= '<div id="road' . $road_0->id_road . 'Collapse" class="ms-5">';
                foreach($road_0->children as $road_1) :
                    $checked = (isset($id_checked) && $road_1->id_road == $id_checked) ? 'checked' : '';
                    $html .= '
                    <div class="form-check">
                        <input class="form-check-input" type="radio"
                            onclick="road_checkbox_to_tag(this);"
                            name="' . $name . '"
                            form="' . $form_id . '"
                            value="' . $road_1->id_road . '"
                            id="road' . $road_1->id_road . 'Radio"
                            ' . $checked . '
                            />
                        <label class="form-check-label text-left text-nowrap" for="road' . $road_1->id_road . 'Radio">
                            ' . t($road_1->label_fr, __NAMESPACE__) . '
                        </label>
                    </div>
                    ';
                endforeach;
                $html .= '</div>';
            endif;
        endforeach; 
        $html .= '</div>';

        return $html;
    }

    private function GetThemTagSelect($name, $form_id, $roads, $id_checked=null)
    {
        $options = [];
        foreach($roads as $road_0) :
            if(empty($road_0->children)) continue;
            foreach($road_0->children as $road_1) :
                $option = (object) [];
                $option->id_road = $road_1->id_road;
                $option->label_fr = $road_1->label_fr;
                $option->path = $road_1->path;
                $options[] = $option;
            endforeach;
        endforeach;

        $html = '
            <select 
                class="bs-multi-select form-control"
                onchange="road_tag_to_checkbox(this);"
                name-disabled="' . $name . '"
                form="' . $form_id . '"
                id="ThemTesorusSelect"
                >
        ';
        $html .= '<option selected hidden></option>';
        foreach($options as $option) :
            $selected = (!empty($id_checked) && $option->id_road==$id_checked) ? 'selected' : '';
            $html .= '<option value="' . $option->id_road . '" ' . $selected . '>' . $option->path . '</option>';
        endforeach;
        $html .= '</select>';

        return $html;
    }

    private function GetThemTagInactive($name, $id_checked=null)
    {
        $inactives = $this->GetThemsTesorus('inactive');
        if(empty($inactives)) return '';

        $html_array = [];
        foreach($inactives as $inactive) :
            if($inactive->id_road == $id_checked) :
                $html_array[] = '
                    <div class="form-check">
                        <input class="form-check-input" type="radio" checked
                            name="' . $name . '"
                            value="' . $inactive->id_road . '"
                            id="road' . $inactive->id_road . 'Radio"
                        />
                        <label class="form-check-label text-left text-nowrap w-auto ml-0 mr-1" 
                            for="road' . $inactive->id_road . 'Radio"
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
    
    public function FileCreateFromTableMesurage($id_demande, $filename)
    {
        $this->datas->roads = $this->GetThemsTesorus('active');
        $this->datas->devis = $this->DevisGet($id_demande, 'pdf');
        $this->datas->themes = $this->themes;
        $html = view('Calculator\devis-pdf-mesurage', (array) $this->datas);
// debugd($html);
        $Phpdocx = new PhpdocxLibrary();
        $file = $Phpdocx->AttachHtmlToDocx($filename . '_Table', $html);

        return $file;
    }
    
    public function FileCreateFromTablePrint($id_demande, $filename)
    {
        $this->datas->roads = $this->GetThemsTesorus('active');
        $this->datas->devis = $this->DevisGet($id_demande);
        $this->datas->themes = $this->themes;
        $html = '
            <phpdocx_break data-type="page" data-number="1" />
            ' . view('Calculator\devis-pdf-print', (array) $this->datas) . '
            <phpdocx_break data-type="page" data-number="1" />
        ';
    // debugd($this->datas->devis);
    // debugd($html);
        $Phpdocx = new PhpdocxLibrary();
        $file = $Phpdocx->AttachHtmlToDocx($filename . '_Table', $html);

        return $file;
    }

    public function FileCreateFromTemplate($name, $destinationName, $variables)
    {
        $Phpdocx = new PhpdocxLibrary();
        $template = APPPATH . 'Modules/Tools/Calculator/Documents/' . $name . '.docx';
        $file = $Phpdocx->CreateDocxFromTemplate($template, $destinationName . '_' . $name, $variables);

        return $file;
    }

    public function VariablesGet($id_demande)
    {
        $devis = $this->DevisGet($id_demande);

        $telephone1 = isset($devis->telephones[0]) ? $devis->telephones[0] : '';
        unset($devis->telephones[0]);
        $telephones = '';
        if(!empty($devis->telephones) && !empty(array_unique(array_filter($devis->telephones)))) :
            $telephones = implode(', ', array_unique(array_filter($devis->telephones)));
        endif;

        $email1 = isset($devis->emails[0]) ? $devis->emails[0] : '';
        unset($devis->emails[0]);
        $emails = '';
        if(!empty($devis->emails) && !empty(array_unique(array_filter($devis->emails)))) :
            $emails = implode(', ', array_unique(array_filter($devis->emails)));
        endif;

        $variables = [];
        $variables['Bien/AdresseFR'] = $devis->adresse_fr;
        $variables['Bien/Type'] = $devis->id_type_label;
        $variables['Bien/Etage'] = $devis->etage_logement;
        $variables['Demande/EnCharge'] = fullname($devis->user_prenom, $devis->user_nom);
        $variables['Demande/ID'] = $devis->id_demande;
        $variables['Demandeur/Prenom'] = ucfirst($devis->prenom_contact);
        $variables['Demandeur/Nom'] = mb_strtoupper($devis->nom_contact);
        $variables['Demandeur/Telephone'] = $telephone1;
        $variables['Demandeur/TelephonesSecondaires'] = $telephones;
        $variables['Demandeur/Email'] = $email1;
        $variables['Demandeur/EmailsSecondaires'] = $emails;
        $variables['Calculator/DateVisite'] = convert_date_en_to_fr_with_h($devis->date_visite, false);
        $variables['LienDemandeurBien/ProfilDemandeur'] = $devis->bien_contact_type;

        return $variables;
    }
    // public function GetGroupTesorusTableHtmlByRoom($id_room)
    // {
    //     $ids_group = $this->ClientModel->IdsRoadParentGroupGetByRoom($id_room);

    //     $html = '
    //         <div id="roadTable" class="table-responsive">
    //             <table class="table table-striped table-hover">
    //                 <tbody>
    //                     ' . $this->GetGroupTesorusTableRecursive($ids_group) . '
    //                 </tbody>
    //             </table>
    //         </div>
    //     ';

    //     return $html;        
    // }

    // private function GetGroupTesorusTableRecursive($ids_group, $roads=null)
    // {
    //     if(empty($roads)) $roads = $this->GetGroupsTesorus('active');

    //     $html = '';
    //     if(!empty($roads)) :
    //         // $i=0;
    //         foreach($roads as $road):
    //             $colspan_left = '';
    //             for($i=0; $i<$road->level; $i++) $colspan_left .= '<td class="px-4"></td>';
    //             $col_right = 3 - (int) $road->level;
    //             $colspan_right = $col_right > 0 ? '<td colspan="' . $col_right . '">' . $road->label_fr . '</td>' : '';
    //             $action = '';
    //             if(!empty($road->id_group)) :
    //                 $action = '
    //                     <a role="button" class="btn btn-sm btn-link link-dark"
    //                         title="Aller à la fiche du groupe de travaux"
    //                         href="' . base_url('calculator/group/' . $road->id_group) . '"
    //                     >
    //                     ' . fontawesome('info-circle') . '
    //                     </a>
    //                 ';
    //             elseif($road->level==1) :
    //                 $action = '
    //                     <button type="button" class="btn btn-sm btn-link link-dark text-decoration-none" 
    //                         title="Ajouter un groupe de travaux sous ce niveau"
    //                         onclick="group_new_modal(this, ' . $road->id_road . ');"
    //                         >
    //                         <small><small>' . fontawesome('plus') . '</small></small>
    //                         ' . fontawesome('toolbox') . '
    //                     </button>
    //                 ';
    //             endif;
    //             $html .= '
    //                 <tr>
    //                     ' . $colspan_left . '
    //                     ' . $colspan_right . '
    //                     <td class="text-center"> ' . $action . ' </td>
    //                 </tr>
    //             ';
    //             if(!empty($road->children)):
    //                 $html .= $this->GetGroupTesorusTableRecursive($road->children);
    //             endif;
    //         endforeach;
    //         $html .= '</tr>';
    //     endif;

    //     return $html;
    // }

    // private function IdsGroupByRoad($road, $ids_group=[])
    // {
    //     if(!empty($road->id_group)) $ids_group[] = $road->id_group;

    //     if(!empty($road->children)) :
    //         foreach($road->children as $child) :
    //             $ids_group = $this->IdsGroupByRoad($child, $ids_group);
    //         endforeach;
    //     endif;

    //     return $ids_group;
    // }

    public function DevisGet($id_demande)
    {
        $devis = $this->ClientModel->DevisGet($id_demande);

        if(empty($devis->works)) return $devis;

        $i = 0;
        $is_complete_devis = 1;
        foreach($devis->works as $work) :
            if(!empty($work->groups)) :
                $total_work = (object) ['pu' => 0, 'ht' => 0, 'tva' => 0, 'tvac' => 0, ];
                $is_complete_work = 1;
                $j = 0;
                foreach($work->groups as $group) :
                    $group = $this->GroupGetCalculatedFields($work->id_work, $group);
                    $total_work->pu += $group->total->pu;
                    $total_work->ht += $group->total->ht;
                    $total_work->tva += $group->total->tva;
                    $total_work->tvac += $group->total->tvac;
                    if(empty($group->quantity) || empty($group->ids_road)) :
                        $is_complete_work = 0;
                        $is_complete_devis = 0;
                    endif;
                    $work->groups[$j] = $group;
                    $j++;
                endforeach;
                $work->total = $total_work;
                $work->is_complete = $is_complete_work;
            endif;
            $devis->works[$i] = $work;
            $i++;
        endforeach;
        $devis->is_complete = $is_complete_devis;

        return $devis;
    }

    public function GroupGetCalculatedFields($id_work, $group)
    {
        $data_read = $this->GetGroupRoadsForm($group, 'read');
        $group->roads_html = $data_read->html;
        $group->roads_pdf = $data_read->pdf;
        $group->total = $data_read->total;

        $data_update = $this->GetGroupRoadsForm($group, 'update', 'DevisForm', 'works[' . $id_work . '][groups][' . $group->id_group . '][ids_road][]');
        $group->roads_form_html = $data_update->html;

        return $group;
    }

    // public function GetGroupRoadsMesurage($typeDataView, $form_id, $group, $work=null)
    // {
    //     $roads = $this->AdminLibrary->RoadsGetRecursive($group->id_road_parent, 'is_active');
    //     $html = $this->GetGroupRoadsMesurageRecursive($typeDataView, $form_id, $roads, $group, $work);

    //     return $html;
    // }

    // public function GetGroupRoadsMesurageRecursive($typeDataView, $form_id, $roads, $group, $work, $level=0)
    // {
    //     $html = '';
    //     if(empty($roads)) return $html;

    //     $total = 0;
    //     $total_tva = 0;
    //     $total_tvac = 0;

    //     foreach($roads as $road):
    //         if(empty($group->ids_road_children)) continue;

    //         $margin = '';
    //         for($i=0;$i<=$level;$i++) $margin .= '<div class="mx-4"></div>';

    //         if(in_array($road->id_road, $group->ids_road_children) && !empty($road->is_terminus)) :
    //             $name = isset($work) ? 'groups[' . $group->id_group . '][works][' . $work->id_work . ']' : 'groups[' . $group->id_group . '][works][##i##]';
    //             $checked = !empty($work->ids_road) && in_array($road->id_road, $work->ids_road) ? 'checked' : '';
    //             $display = in_array($typeDataView, ['create', 'update']) ? '' : 'style="display: none;"';
    //             $html .= '
    //                 <div class="row py-2 form_update px-2" ' . $display . '>
    //                     <div class="col d-flex">
    //                         ' . $margin . '
    //                         <div class="form-check">
    //                             <input type="checkbox"
    //                                 class="form-check-input"
    //                                 form="' . $form_id . '"
    //                                 name="' . $name . '[ids_road][]"
    //                                 value="' . $road->id_road . '"
    //                                 ' . $checked . '
    //                             />
    //                             <label class="form-check-label"> ' . $road->label_fr . ' </label>
    //                         </div>
    //                     </div>
    //                 </div>
    //             ';

    //             if(!empty($work->ids_road) && in_array($road->id_road, $work->ids_road)) :
    //                 $display = in_array($typeDataView, ['read']) ? '' : 'style="display: none;"';
    //                 $html .= '
    //                     <div class="row py-2 form_read" ' . $display . '>
    //                         <div class="col d-flex">
    //                             ' . $margin . '
    //                             <div>' . $road->label_fr . '</div>
    //                         </div>
    //                     </div>
    //                 ';
    //             endif;

    //         elseif(
    //             !empty($road->children) && 
    //             (
    //                 in_array($road->id_road, $group->ids_road_children_parent) ||
    //                 array_intersect(array_column($road->children, 'id_road'), $group->ids_road_children_parent)
    //             )) :
    //             if(isset($work->ids_road) &&
    //                 (
    //                     array_intersect(array_column($road->children, 'id_road'), $work->ids_road) ||
    //                     array_intersect(array_column($road->children, 'id_road'), $work->ids_road_parent)
    //                 )) :
    //                 $form_update = '';
    //                 $display = '';
    //             else :
    //                 $form_update = 'form_update';
    //                 $display = 'style="display: none;"';
    //             endif;
    //             $html .= '
    //                 <div class="row py-2 ' . $form_update . '" ' . $display . '>
    //                     <div class="col d-flex">
    //                         ' . $margin . '
    //                         <label class="form-check-label"> ' . $road->label_fr . ' </label>
    //                         <button type="button" class="btn btn-sm btn-link link-dark" disabled>
    //                             ' . fontawesome('caret-down') . '
    //                         </button>
    //                     </div>
    //                 </div>
    //             ';
    //             if(!empty($road->children)) :
    //                 $html .= $this->GetGroupRoadsMesurageRecursive($typeDataView, $form_id, $road->children, $group, $work, $level+1);
    //             endif;
    //         endif;
    //     endforeach;

    //     return $html;
    // }

    public function GetGroupRoadsForm($group, $typeDataView, $form_id=null, $name=null)
    {
        $roads = $this->AdminLibrary->RoadsGetRecursive($group->id_road_parent, 'active');
        $data = $this->GetGroupRoadsFormRecursive($group, $typeDataView, $roads, $form_id, $name);

        return $data;
    }

    public function GetGroupRoadsFormRecursive($group, $typeDataView, $roads, $form_id, $name, $level=0)
    {
        $data = (object) [
            'html' => '',
            'pdf' => '',
            'total' => (object) ['pu' => 0, 'ht' => 0, 'tva' => 0, 'tvac' => 0, ],
        ];

        if(empty($roads)) return $data;

        $html = '';
        $pdf = '';
        $total = (object) ['pu' => 0, 'ht' => 0, 'tva' => 0, 'tvac' => 0, ];
        foreach($roads as $road):
            if(empty($group->ids_road_children)) continue;

            $margin_html = '';
            $margin_pdf = 0;
            for($i=0;$i<$level;$i++) :
                $margin_html .= '<span class="mx-4"></span>';
                $margin_pdf += 20;
            endfor;

            if(in_array($road->id_road, $group->ids_road_children) && !empty($road->is_terminus)) :
                $checked = !empty($group->ids_road) && in_array($road->id_road, $group->ids_road) ? 'checked' : '';
                if($typeDataView=='read' && !empty($group->ids_road) && in_array($road->id_road, $group->ids_road)) :
                    $calcul_html = '';
                    $calcul_pdf = '';
                    if(!empty($group->quantity)) :
                        $pu = $road->avg_price;
                        $ht = calcul_ht($road->avg_price, $group->quantity);
                        $tva = calcul_tva($road->avg_price, $group->quantity);
                        $tvac = calcul_tvac($road->avg_price, $group->quantity);

                        $calcul_html .= '
                            <div class="row">
                                <div class="col">' . number_format($pu, 2, ',', '') . '</div>
                                <div class="col">' . number_format($ht, 2, ',', '') . '</div>
                                <div class="col">' . number_format($tva, 2, ',', '') . '</div>
                                <div class="col">' . number_format($tvac, 2, ',', '') . '</div>
                            </div>
                        ';

                        $calcul_pdf .= '
                            <td colspan="1" class="text-end">' . number_format($pu, 2, ',', '') . '</td>
                            <td colspan="1" class="text-end">' . number_format($ht, 2, ',', '') . '</td>
                            <td colspan="1" class="text-end">' . number_format($tva, 2, ',', '') . '</td>
                            <td colspan="1" class="text-end">' . number_format($tvac, 2, ',', '') . '</td>
                        ';

                        $total->pu += $pu;
                        $total->ht += $ht;
                        $total->tva += $tva;
                        $total->tvac += $tvac;                        
                    endif;
                    $html .= '
                        <div class="row">
                            <div class="col-6 offset-1">
                                ' . $margin_html . '
                                <label class="form-check-label"> ' . $road->label_fr . ' </label>
                            </div>
                            <div class="col-5 text-end">' . $calcul_html . '</div>
                        </div>
                    ';
                    $pdf .= '
                        <tr>
                            <td colspan="1"></td>
                            <td colspan="7"> <li style="margin-left: ' . $margin_pdf . 'px;"> ' . $road->label_fr . ' </li> </td>
                            ' . $calcul_pdf . '
                        </tr>
                    ';
                elseif(in_array($typeDataView, ['create', 'update'])) :
                    $html .= '
                        <div class="row">
                            <div class="col offset-1 d-flex">
                                ' . $margin_html . '
                                <div class="form-check">
                                    <input type="checkbox"
                                        class="form-check-input"
                                        form="' . $form_id . '"
                                        name="' . $name . '"
                                        value="' . $road->id_road . '"
                                        ' . $checked . '
                                    />
                                    <label class="form-check-label"> ' . $road->label_fr . ' </label>
                                </div>
                            </div>
                        </div>
                    ';
                endif;
            elseif(
                !empty($road->children) && 
                (
                    (
                        in_array($typeDataView, ['create', 'update']) && (
                            in_array($road->id_road, $group->ids_road_children_parent) ||
                            array_intersect(array_column($road->children, 'id_road'), $group->ids_road_children_parent)
                        )
                    ) ||
                    (
                        in_array($typeDataView, ['read']) && !empty($group->ids_road_parent) && (
                            in_array($road->id_road, $group->ids_road_parent) ||
                            array_intersect(array_column($road->children, 'id_road'), $group->ids_road_parent)
                        )
                    )
                )) :
                $html .= '
                    <div class="row">
                        <div class="col-6 offset-1">
                            ' . $margin_html . '
                            <label class="form-check-label"> ' . $road->label_fr . ' </label>
                            <button type="button" class="btn btn-sm btn-link link-dark" disabled>
                                ' . fontawesome('caret-down') . '
                            </button>
                        </div>
                    </div>
                ';
                $pdf .= '
                    <tr>
                        <td colspan="8"> <li style="margin-left: ' . $margin_pdf . 'px;"> ' . $road->label_fr . ' </li> </td>
                        <td colspan="4"></td>
                    </tr>
                ';
                if(!empty($road->children)) :
                    $subdata = $this->GetGroupRoadsFormRecursive($group, $typeDataView, $road->children, $form_id, $name, $level+1);
                    $html .= $subdata->html;
                    $pdf .= $subdata->pdf;
                    $total->pu += $subdata->total->pu;
                    $total->ht += $subdata->total->ht;
                    $total->tva += $subdata->total->tva;
                    $total->tvac += $subdata->total->tvac;
                endif;
            endif;
        endforeach;

        $data->html = $html;
        $data->pdf = $pdf;
        $data->total = $total;

        return $data;
    }

    // public function GetGroupRoadsEstimate($group, $work=null)
    // {
    //     $roads = $this->AdminLibrary->RoadsGetRecursive($group->id_road_parent, 'is_active');
    //     $result = $this->GetGroupRoadsEstimateRecursive($roads, $group, $work);
    //     $html = $result->html;
    //     if(!empty($result->total) && !empty($work->ids_road)) :
    //         $total = $result->total;
    //         $html .= '
    //             <div class="row">
    //                 <div class="col-1 offset-5 py-2 text-end bg-warning" style="--bs-bg-opacity: .2;"> ' . $this->themes->calculator->icon . ' </div>
    //                 <div class="col py-2 text-end bg-warning" style="--bs-bg-opacity: .2;">
    //                     <div class="row fw-bold">
    //                         <div class="col text-end" name="works[' . $work->id_work. '][pu]"> ' . round($total->pu, 2) . ' </div>
    //                         <div class="col text-end"> ' . $work->quantity . ' </div>
    //                         <div class="col text-end" name="works[' . $work->id_work. '][ht]"> ' . round($total->ht, 2) . ' </div>
    //                         <div class="col text-end" name="works[' . $work->id_work. '][tva]"> ' . round($total->tva, 2) . ' </div>
    //                         <div class="col text-end" name="works[' . $work->id_work. '][tvac]"> ' . round($total->tvac, 2) . ' </div>
    //                     </div>
    //                 </div>
    //             </div>
    //         ';
    //     endif;

    //     return $html;
    // }

    // public function GetGroupRoadsEstimateRecursive($roads, $group, $work, $level=0, $total=null)
    // {
    //     $html = '';
    //     if(empty($roads)) return (object) ['html' => $html, 'total' => $total];

    //     foreach($roads as $road):
    //         if(empty($group->ids_road_children)) continue;

    //         $margin = '';
    //         for($i=0;$i<=$level;$i++) $margin .= '<div class="mx-4"></div>';

    //         if(in_array($road->id_road, $group->ids_road_children) && !empty($road->is_terminus)) :

    //             if(isset($work) && !empty($work->quantity) && in_array($road->id_road, $work->ids_road)) :
    //                 $avg_price_total = !empty($work->quantity) ? $road->avg_price * $work->quantity : '';
    //                 $avg_price_tva = !empty($avg_price_total) ? 0.21 * $avg_price_total : '';
    //                 $avg_price_tvac = !empty($avg_price_total) ? $avg_price_total + $avg_price_tva : '';
    
    //                 if(is_null($total)) $total = (object) ['pu' => 0, 'ht' => 0, 'tva' => 0, 'tvac' => 0];
    //                 $total->pu += !empty($avg_price_total) ? $road->avg_price : 0;
    //                 $total->ht += !empty($avg_price_total) ? $avg_price_total : 0;
    //                 $total->tva += !empty($avg_price_tva) ? $avg_price_tva : 0;
    //                 $total->tvac += !empty($avg_price_total) ? $avg_price_tvac : 0;
    
    //                 $html .= '
    //                     <div class="row py-2">
    //                         <div class="col-6 d-flex">
    //                             ' . $margin . '
    //                             <div>' . $road->label_fr . '</div>
    //                         </div>
    //                         <div class="col">
    //                             <div class="row">
    //                                 <div class="col text-end" name="works[' . $work->id_work. '][roads][' . $road->id_road . '][avg_price]"> 
    //                                     ' . round($road->avg_price, 2) . '
    //                                 </div>
    //                                 <div class="col text-end" name="works[' . $work->id_work. '][quantity]">
    //                                     ' . round($work->quantity, 2) . '
    //                                 </div>
    //                                 <div class="col text-end" name="works[' . $work->id_work. '][roads][' . $road->id_road . '][avg_price_total]">
    //                                     ' . round($avg_price_total, 2) . '
    //                                 </div>
    //                                 <div class="col text-end" name="works[' . $work->id_work. '][roads][' . $road->id_road . '][avg_price_tva]">
    //                                     ' . round($avg_price_tva, 2) . '
    //                                 </div>
    //                                 <div class="col text-end" name="works[' . $work->id_work. '][roads][' . $road->id_road . '][avg_price_tvac]">
    //                                     ' . round($avg_price_tvac, 2) . '
    //                                 </div>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 ';
    //             endif;

    //         elseif(
    //             !empty($road->children) && 
    //             (
    //                 in_array($road->id_road, $group->ids_road_children_parent) ||
    //                 array_intersect(array_column($road->children, 'id_road'), $group->ids_road_children_parent)
    //             ) &&
    //             (
    //                 array_intersect(array_column($road->children, 'id_road'), $work->ids_road) ||
    //                 array_intersect(array_column($road->children, 'id_road'), $work->ids_road_parent)
    //             )
    //         ) :
    //             $html .= '
    //                 <div class="row py-2">
    //                     <div class="col d-flex">
    //                         ' . $margin . '
    //                         <label class="form-check-label"> ' . $road->label_fr . ' </label>
    //                         <button type="button" class="btn btn-sm btn-link link-dark" disabled>
    //                             ' . fontawesome('caret-down') . '
    //                         </button>
    //                     </div>
    //                 </div>
    //             ';
    //             if(!empty($road->children)) :
    //                 $result = $this->GetGroupRoadsEstimateRecursive($road->children, $group, $work, $level+1, $total);
    //                 $html .= $result->html;
    //                 $total = $result->total;
    //             endif;
    //         endif;
    //     endforeach;

    //     $result = (object) [];
    //     $result->html = $html;
    //     $result->total = $total;

    //     return $result;
    // }

    // public function GetGroupRoadsPrint($group)
    // {
    //     $roads = $this->AdminLibrary->RoadsGetRecursive($group->id_road_parent, 'is_active');
    //     $result = $this->GetGroupRoadsPrintRecursive($roads, $group);
    //     $html = $result->html;
    //     if(!empty($result->total) && !empty($group->ids_road)) :
    //         $total = $result->total;
    //         $html .= '
    //             <tr>
    //                 <td colspan="6"></td>
    //                 <td class="sous-total text-end"> Total </td>
    //                 <td class="sous-total text-end">' . round($total->pu, 2) . '</td>
    //                 <td class="sous-total text-end">' . $group->quantity . '</td>
    //                 <td class="sous-total text-end">' . round($total->ht, 2) . '</td>
    //                 <td class="sous-total text-end">' . round($total->tva, 2) . '</td>
    //                 <td class="sous-total text-end">' . round($total->tvac, 2) . '</td>
    //             </tr>
    //         ';
    //     endif;

    //     return $html;
    // }

    // public function GetGroupRoadsPrintRecursive($roads, $group, $level=0, $total=null)
    // {
    //     $html = '';
    //     if(empty($roads)) return (object) ['html' => $html, 'total' => $total];

    //     foreach($roads as $road):
    //         if(empty($group->ids_road_children)) continue;

    //         $padding = 60;
    //         for($i=0;$i<=$level;$i++) $padding += 10;

    //         if(in_array($road->id_road, $group->ids_road_children) && !empty($road->is_terminus)) :

    //             if(isset($work) && !empty($work->quantity) && !empty($work->ids_road) && in_array($road->id_road, $work->ids_road)) :
    //                 $avg_price_total = !empty($work->quantity) ? $road->avg_price * $work->quantity : '';
    //                 $avg_price_tva = !empty($avg_price_total) ? 0.21 * $avg_price_total : '';
    //                 $avg_price_tvac = !empty($avg_price_total) ? $avg_price_total + $avg_price_tva : '';
    
    //                 if(is_null($total)) $total = (object) ['pu' => 0, 'ht' => 0, 'tva' => 0, 'tvac' => 0];
    //                 $total->pu += !empty($avg_price_total) ? $road->avg_price : 0;
    //                 $total->ht += !empty($avg_price_total) ? $avg_price_total : 0;
    //                 $total->tva += !empty($avg_price_tva) ? $avg_price_tva : 0;
    //                 $total->tvac += !empty($avg_price_total) ? $avg_price_tvac : 0;
    
    //                 $html .= '
    //                     <tr>
    //                         <td class="label_road" colspan="7" style="padding-left: ' . $padding . 'px">' . $road->label_fr . '</td>
    //                         <td class="text-end">' . round($road->avg_price, 2) . '</td>
    //                         <td class="text-end">' . $work->quantity . '</td>
    //                         <td class="text-end">' . round($avg_price_total, 2) . '</td>
    //                         <td class="text-end">' . round($avg_price_tva, 2) . '</td>
    //                         <td class="text-end">' . round($avg_price_tvac, 2) . '</td>
    //                     </tr>
    //                 ';
    //             endif;

    //         elseif(
    //             !empty($road->children) && 
    //             (
    //                 in_array($road->id_road, $group->ids_road_children_parent) ||
    //                 array_intersect(array_column($road->children, 'id_road'), $group->ids_road_children_parent)
    //             ) &&
    //             (
    //                 array_intersect(array_column($road->children, 'id_road'), $group->ids_road) ||
    //                 array_intersect(array_column($road->children, 'id_road'), $group->ids_road_parent)
    //             )
    //         ) :
    //             $html .= '
    //                 <tr>
    //                     <td class="label_road" colspan="12" style="padding-left: ' . $padding . 'px">' . $road->label_fr . fontawesome('caret-down') . '</td>
    //                 </tr>
    //             ';
    //             if(!empty($road->children)) :
    //                 $result = $this->GetGroupRoadsPrintRecursive($road->children, $group, $level+1, $total);
    //                 $html .= $result->html;
    //                 $total = $result->total;
    //             endif;
    //         endif;
    //     endforeach;

    //     $result = (object) [];
    //     $result->html = $html;
    //     $result->total = $total;

    //     return $result;
    // }

    // public function GetGroupRoadsSelectedForm($i, $form_id, $group)
    // {
    //     $roads = $this->AdminLibrary->RoadsGetRecursive($group->id_road_parent);
    //     $name = 'rooms[' . $i . '][groups][' . $group->id_group . ']';

    //     $html = $this->GetGroupRoadsSelectedFormRecursive($form_id, $name, $roads, $group->ids_road_children, $group->ids_road);

    //     return $html;


    //     return $html;
    // }

    // private function GetGroupRoadsSelectedFormRecursive($form_id, $name, $roads, $ids_road_children, $ids_road_selected)
    // {
    //     $html = '';
    //     if(!empty($roads)) :
    //         foreach($roads as $road):
    //             if(empty($ids_road_children)) continue;
    //             if(
    //                 in_array($road->id_road, $ids_road_children) || 
    //                 (!empty($road->children) && array_intersect(array_column($road->children, 'id_road'), $ids_road_children))
    //             ) :
    //                 $html .= '<div class="ms-4">';
    //                 if(!empty($road->is_terminus)) :
    //                     $checked = !empty($ids_road_selected) && in_array($road->id_road, $ids_road_selected) ? 'checked' : '';
    //                     $html .= '
    //                         <div class="d-flex align-items-center">
    //                             <label class="form-check-label"> ' . $road->label_fr . ' </label>
    //                             <div class="flex-grow-1 px-2"> <hr> </div>
    //                             <input type="checkbox"
    //                                 class="form-check-input"
    //                                 form="' . $form_id . '"
    //                                 name="' . $name . '[ids_road][]"
    //                                 value="' . $road->id_road . '"
    //                                 ' . $checked . '
    //                             />
    //                         </div>
    //                     ';
    //                 else :
    //                     $html .= '
    //                         <div class="d-flex align-items-center">
    //                             <label class="form-check-label"> ' . $road->label_fr . ' </label>
    //                             <button type="button" class="btn btn-sm btn-link link-dark" disabled>
    //                                 ' . fontawesome('caret-down'). '
    //                             </button>
    //                         </div>  
    //                     ';
    //                     if(!empty($road->children)) :
    //                         $html .= $this->GetGroupRoadsSelectedFormRecursive($form_id, $name, $road->children, $ids_road_children, $ids_road_selected);
    //                     endif;
    //                 endif;
    //                 $html .= '</div>';
    //             endif;
    //         endforeach;
    //     endif;

    //     return $html;
    // }
}