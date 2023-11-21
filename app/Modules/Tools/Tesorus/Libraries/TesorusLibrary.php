<?php

namespace Tesorus\Libraries;

use Autorisation\Libraries\AutorisationLibrary;
use Base\Libraries\BaseLibrary;
use Tesorus\Models\CellModel;
use Tesorus\Models\RoadModel;
use Components\Libraries\DatabaseLibrary;
use Translator\Libraries\TranslatorLibrary;

class TesorusLibrary extends BaseLibrary
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->Autorisation = new AutorisationLibrary();
        $this->CellModel = new CellModel();
    }

    // public function check_translation()
    // {
    //     $this->load->library('tamo_translate');
    //     $this->tamo_translate->refresh_session($this->t_translation);
    //     $_SESSION['translation']['default'] = $this->t_translation;
    // }

    public function TagsValueGet($road_name, $ids_road=null, $roads=null)
    {
        foreach($ids_road as $id_road) :
            $ids_road = array_merge($ids_road, $this->get_ids_road_by_id_road($road_name, $id_road));
        endforeach;
        $ids_road = array_values(array_unique(array_filter($ids_road)));

        $html = '';
        if(empty($ids_road)) return $html;

        $roads = !empty($roads) ? $roads : $this->get_roads_recursive($road_name);
        $html = '<small><ul>' . $this->TagsValueGetRecursive($road_name, $ids_road, $roads) . '</ul></small>';

        return $html;
    }

    public function TagsValueGetRecursive($road_name, $ids_road=null, $roads)
    {
        $html = '';
        if(empty($ids_road)) return $html;

        foreach($roads as $road) :
            if(in_array($road->id_road, $ids_road)):
                $html .= '<li>' . $road->label_fr . '</li>';
                if(!empty($road->children)) :
                    $html .= '<ul>' . $this->TagsValueGetRecursive($road_name, $ids_road, $road->children) . '</ul>';
                endif;
            endif;
        endforeach;

        return $html;
    }

    public function clean_road_text_for_database($datas)
    {
        $result = (object) [];
        foreach($datas as $id_road=>$value) :
            if(isset($value) && trim($value)!='') $result->$id_road = $value;
        endforeach;

        return $result;
    }

    public function get_roads_recursive($road_name, $id_road_parent=0, $level=0)
    {
        $RoadModel = new RoadModel($road_name);
        $roads = $RoadModel->RoadsGetByParent($id_road_parent);

        if(count($roads)>0):
            foreach($roads as $road):
                $road->level = $level;
                $road->children = $this->get_roads_recursive($road_name, $road->id_road, $level+1);
            endforeach;
        endif;

        return $roads;
    }

    // -------------------------------- EDIT -------------------------------- 

    public function get_road_sort_html($road_name)
    {
        $html = '<div id="roadEditWait" class="w-100 p-5 text-center">' . fontawesome('spinner') . '</div>';
        $html .= '<div id="roadEdit" road_name="' . $road_name . '" style="display: none;">';
        $html .= '<div class="collapse show road-active" id="road0Collapse" id_road="0">';
        $html .= $this->get_road_sort_recursive($road_name);
        $html .= '</div>';
        $html .= '</div>';

        return $html;        
    }

    private function get_road_sort_recursive($road_name, $roads=null)
    {
        if(!isset($roads)) $roads = $this->get_roads_recursive($road_name);

        $html = '';
        if(empty($roads)) return $html;

        $i=0;
        foreach($roads as $road):
            if(!empty($road->isActive)) :
                $html .= '
                    <div class="road-edit-group"
                        id_road="' . $road->id_road . '"
                        id_cell="' . $road->id_cell . '"
                        rank="' . $road->rank . '"
                        >
                    ';
                $html .= $this->get_road_sort_row($road_name, $road, $i+1);
                if(!empty($road->children)):
                    $html .= '
                        <div class="collapse" 
                            aria-expanded="true"
                            id_road="' . $road->id_road . '" 
                            id="road' . $road->id_road . 'Collapse"
                            data-parent="#road' . $road->id_road_parent . 'Collapse"
                            >
                            ' . $this->get_road_sort_recursive($road_name, $road->children) . '
                        </div>
                    ';
                endif;
                $html .= '</div>';
                if(isset($road->isActive) && $road->isActive==1) $i++;
            endif;
        endforeach;

        return $html;
    }

    private function get_road_sort_row($road_name, $road, $rank)
    {
        $button_sort = $this->button_sort($road_name, $road);

        $tab = '';
        for($k=0; $k<=$road->level; $k++) $tab .= '<div class="mx-4"></div>';

        $label = '';
        if(!empty($road->label_fr)) :
            $label = empty($road->isActive) ? '
                <small class="fst-italic">
                    <span class="mx-2">' . fontawesome('eye-slash') . '</span>
                    ' . t($road->label_fr, __NAMESPACE__) . '
                </small>
            ' : $road->label_fr;
        endif;

        $rank = empty($road->isActive) ? '' : $rank;
        $button_sublevel_toggle = $this->button_sublevel_toggle($road);
        
        $html = '
            <div class="row align-items-center m-0 p-2 justify-content-between" id_road="' . $road->id_road . '">
                <div class="col">
                    <div class="d-flex align-items-center">
                        ' . $tab . '
                        <div class="mx-2"> ' . $rank . ' </div>
                        <div class="mx-2"> ' . $label . ' </div>
                        ' . $button_sublevel_toggle . '
                    </div>
                </div> 
                <div class="col road-edit-buttons">
                    <div class="row align-items-center">
                        <div class="col-2"> ' . $button_sort . ' </div>
                    </div>   
                </div>   
            </div>
        ';
        return $html;
    }

    // -------------------------------- EDIT -------------------------------- 

    public function get_road_edit_html($road_name)
    {
        $html = '<div id="roadEditWait" class="w-100 p-5 text-center">' . fontawesome('spinner') . '</div>';
        $html .= '<div id="roadEdit" road_name="' . $road_name . '" style="display: none;">';
        $html .= '<div class="collapse show road-active" id="road0Collapse" id_road="0">';
        $html .= $this->get_road_edit_recursive($road_name);
        $html .= '</div>';
        $html .= '</div>';

        return $html;        
    }

    private function get_road_edit_recursive($road_name, $roads=null)
    {
        if(!isset($roads)) $roads = $this->get_roads_recursive($road_name);
        $html = '';
        if(!empty($roads)) :
            $i=0;
            foreach($roads as $road):
                if(!empty($road->isActive)) :
                    $html .= '
                        <div class="road-edit-group"
                            id_road="' . $road->id_road . '"
                            id_cell="' . $road->id_cell . '"
                            rank="' . $road->rank . '"
                            >
                        ';
                    $html .= $this->get_road_edit_row($road_name, $road, $i+1);
                    if(!empty($road->children)):
                        $html .= '
                            <div class="collapse" 
                                aria-expanded="true"
                                id_road="' . $road->id_road . '" 
                                id="road' . $road->id_road . 'Collapse"
                                data-parent="#road' . $road->id_road_parent . 'Collapse"
                                >
                                ' . $this->get_road_edit_recursive($road_name, $road->children) . '
                            </div>
                        ';
                    endif;
                    $html .= '</div>';
                    if(isset($road->isActive) && $road->isActive==1) $i++;
                endif;
            endforeach;
            $html .= $this->get_road_edit_new($road_name, $roads[0]);
            foreach($roads as $road):
                if(empty($road->isActive)) :
                    $html .= '
                        <div class="road-edit-group bg-secondary"
                            style="--bs-bg-opacity: .5;"
                            id_road="' . $road->id_road . '"
                            id_cell="' . $road->id_cell . '"
                            rank="' . $road->rank . '"
                        >
                        ';
                    $html .= $this->get_road_edit_row($road_name, $road, $i+1);
                    $html .= '</div>';
                endif;
            endforeach;
        else :
            $html .= $this->get_road_edit_new($road_name);
        endif;
        return $html;
    }

    private function get_road_edit_new($road_name, $road=null)
    {
        if(!isset($road)) :
            $road = (object) [];
            $road->id_road_parent = 0;
            $road->level = 0;
        endif;
        if(!isset($road->id_road_parent)) $road->id_road_parent = 0;
        $RoadModel = new RoadModel($road_name);
        $rank = count($RoadModel->RoadsActiveGetByParent($road->id_road_parent)) + 1;
        $tab = '';
        for($k=0; $k<=$road->level; $k++) $tab .= '<div class="mx-4"></div>';
        $html = '
                <div class="road-new-group row align-items-center m-0 p-2">
                    <div class="col-auto">
                        <div class="d-flex align-items-center">
                            ' . $tab . '
                            <div class="mx-2">
                                <button type="button" class="road-new btn btn-sm btn-outline-dark text-decoration-none p-1 mx-1" 
                                    title="Ajouter un élément à ce niveau"
                                    onclick="road_new_modal(this, \'' . $road_name . '\', ' . $road->id_road_parent . ');"
                                    >
                                    ' . fontawesome('plus') . ' ' . fontawesome('code-branch') . '
                                </button>
                            </div>
                        </div>
                    </div>
                </div>     
            ';
        return $html;
    }

    private function button_sort($road_name, $road)
    {
        if(!empty($road->isActive)) :
            $sort = '
                <a role="button" class="road-move btn btn-sm p-1 mx-1"> 
                    ' . fontawesome('sort') . '
                </a>
            ';
        else :
            if($this->Autorisation->is_autorise('tesorus_d')):
                $sort = '
                    <button type="button" class="btn btn-sm mx-2" onclick="road_delete_modal(this, \'' . $road_name . '\', ' . $road->id_road . ')"> 
                        ' . fontawesome('trash-alt') . '
                    </button>
                ';
            endif;
        endif;

        return $sort;
    }

    
    public function button_sublevel_create($road_name, $road)
    {
        // $invisible = (empty($road->children) && !empty($road->isActive)) ? '' : 'invisible';
        
        $html = '
            <button type="button" class="road-sub road-new btn btn-sm btn-link link-dark text-decoration-none p-1 mx-1 text-nowrap" 
                title="Ajouter un chemin sous ce niveau"
                onclick="road_new_modal(this, \'' . $road_name . '\', ' . $road->id_road . ');"
                >
                <small><small>' . fontawesome('plus') . '</small></small>
                ' . fontawesome('code-branch') . '
            </button>
        ';

        return $html;
    }

    private function button_sublevel_toggle($road)
    {
        $sublevel_toggle = '';
        if(!empty($road->children) && !empty($road->isActive)) $visible_class = '';
        else $visible_class = 'invisible';

        $sublevel_toggle = '
            <button type="button" class="btn btn-sm btn-caret p-1 mx-1 ' . $visible_class . '" 
                id="road' . $road->id_road . 'Button"
                data-bs-toggle="collapse" 
                data-bs-target="#road' . $road->id_road . 'Collapse" 
                >
                ' . fontawesome('caret-right') . '
            </button>
            ';

        return $sublevel_toggle;
    }

    public function button_active_toggle($road_name, $road)
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
                    onclick="road_update_active_modal(this, \'' . $road_name . '\', ' . $road->id_road . ', ' . $road->isActive . ');"
                    > 
                    ' . $button_text . ' 
                </button>
        ';

        return $html;
    }

    private function button_has_text($road_name, $road)
    {
        if(!empty($road->has_text)) :
            $button_text = fontawesome('keyboard-slash');
            $button_title = "Supprimer le champ texte";
        else :
            $button_text = fontawesome('keyboard');
            $button_title = "Ajouter un champ texte";
        endif;

        $html = '                
                <button type="button" class="road-text btn btn-sm p-1 mx-1" 
                    title="' . $button_title . '" 
                    onclick="road_update_hasText_modal(\'' . $road_name . '\', ' . $road->id_road . ', ' . $road->has_text . ');"> 
                    ' . $button_text . ' 
                </button>
        ';

        return $html;
    }

    private function button_edit_road($road_name, $road)
    {
        $html = '                
            <button type="button" class="road-edit btn btn-sm p-1 mx-1" onclick="road_update_modal(this, \'' . $road_name . '\', ' . $road->id_road . ');">
                ' . fontawesome('edit') . '
            </button>
        ';

        return $html;
    }

    private function get_road_label_unlinked($id_road)
    {
        return 'Chemin chemin brisé (id_road = ' . $id_road . ')';

        // return '
        //     <span class="btn btn-sm" title="Lien entre le chemin et l\'élément de liste est brisé (id_road = ' . $id_road . ')">
        //         ' . fontawesome('unlink'). '
        //     </span>
        // ';
    }

    private function get_road_edit_row($road_name, $road, $rank)
    {
        $tab = '';
        for($k=0; $k<=$road->level; $k++) $tab .= '<div class="mx-4"></div>';
        $label = '';
        if(!empty($road->label_fr)) :
            $label = empty($road->isActive) ? '
                <small class="fst-italic">
                    <span class="mx-2">' . fontawesome('eye-slash') . '</span>
                    ' . t($road->label_fr, __NAMESPACE__) . '
                </small>
            ' : $road->label_fr;
        else :
            $label = $this->get_road_label_unlinked($road->id_road);
        endif;
        $icon_has_text = empty($road->has_text) || !$this->db->tableExists('tesorus_road_' . $road_name . '_text') ? '' : '<span class="mx-2">' . fontawesome('keyboard') . '</span>';
        $rank = empty($road->isActive) ? '' : $rank;
        $button_sublevel_toggle = $this->button_sublevel_toggle($road);

        $button_sort = $this->button_sort($road_name, $road);
        $button_edit_road = empty($road->is_terminus) ? $this->button_edit_road($road_name, $road) : '';
        $button_active_toggle = empty($road->is_terminus) ? $this->button_active_toggle($road_name, $road) : '';
        $button_sublevel_create = ($this->Autorisation->is_autorise('tesorus_c') && empty($road->is_terminus)) ? $this->button_sublevel_create($road_name, $road) : '';
        $button_has_text = $this->db->tableExists('tesorus_road_' . $road_name . '_text') ? $this->button_has_text($road_name, $road) : '';
        
        $html = '
            <div class="row align-items-center m-0 p-2 justify-content-between" id_road="' . $road->id_road . '">
                <div class="col">
                    <div class="d-flex align-items-center">
                        ' . $tab . '
                        <div class="mx-2"> ' . $label . $icon_has_text . ' </div>
                        ' . $button_sublevel_toggle . '
                    </div>
                </div> 
                <div class="col road-edit-buttons">
                    <div class="row align-items-center">
                        <div class="col"> ' . $button_sort . ' </div>
                        <div class="col"> ' . $button_active_toggle . ' </div>
                        <div class="col"> ' . $button_edit_road . ' </div>
                        <div class="col"> ' . $button_sublevel_create . ' </div>
                        <div class="col"> ' . $button_has_text . ' </div>
                    </div>   
                </div>   
            </div>
        ';

        return $html;
    }
    
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

    public function get_road_tag_html($road_name, $name, $ids_checked=null, $texts=null)
    {
        $html_inactive = !empty($ids_checked) ? $this->get_road_inactive_html($road_name, $name,$ids_checked) : '';
        $html_select = $this->get_road_tag_select($road_name, $name, $ids_checked);
        $html_checkbox = $this->get_road_checkbox_recursive($road_name, $name, null, $ids_checked);
        $html_js = view('Tesorus\js/js_tesorus_tag');

        $control_id = !empty($ref) ? $ref : $road_name;

        // $html = '
        //     <div id="' . $control_id . '" class="form-control-group">
        //         ' . $html_inactive . '
        //         ' . $html_select . '
        //     </div>
        // ';

        $html = '
            <div id="' . $control_id . '" class="form-control-group">
                ' . $html_inactive . '
                <div class="row">
                    <div class="col-auto">
                        <div class="sticky_button">
                            <button type="button" class="btn btn-sm btn-link link-dark tag-button-show-list" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#' . $control_id . 'Collapse"
                                title="' . t("Voir l'arborescence", __NAMESPACE__) . '"
                                onclick="tag_button_alternate(this);">
                                ' . fontawesome('eye') . '
                            </button>
                            <button type="button" class="btn btn-sm btn-link link-dark tag-button-hide-list" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#' . $control_id . 'Collapse"
                                title="' . t("Masquer l'arborescence", __NAMESPACE__) . '"
                                onclick="tag_button_alternate(this);"
                                style="display: none;"
                                >
                                ' . fontawesome('eye-slash') . '
                            </button>
                        </div>
                    </div>
                    <div class="col"> 
                        ' . $html_select . '
                        <div id="' . $control_id . 'Collapse" class="collapse mt-2 border rounded p-2">' . $html_checkbox . '</div>
                    </div>              
                </div>
            </div>
            ' . $html_js . '
        ';

        return $html;
    }

    public function get_paths($road_name)
    {
        $param = (object) [];
        $param->align = 'inline';

        $RoadModel = new RoadModel($road_name);
        $roads = $RoadModel->RoadsActiveGet();
        $paths = [];
        foreach($roads as $road) :
            $data = (object) [];
            $data->id_road = $road->id_road;
            $data->path = $this->get_path_by_id_road($road_name, $road->id_road, $param);
            $paths[] = $data;
        endforeach;

        return $paths;        
    }

    private function get_road_tag_select($road_name, $name, $ids_checked=null)
    {
        $paths = $this->get_paths($road_name);
        // $control_id = !empty($ref) ? $ref : $road_name;

        // $html = '
        //     <select 
        //         class="bs-multi-select tags-block" multiple
        //         name="' . $ref . '[]"
        //         road_name = "' . $road_name . '"
        //         id="' . $ref . 'Select"
        //         >
        // ';
        $html = '
            <select
                class="bs-multi-select tags-block form-select"
                multiple
                onchange="road_tag_to_checkbox(this);"
                name-disabled="' . $name . '[]"
                road_name = "' . $road_name . '"
                >
        ';
        foreach($paths as $path) :
            $selected = (!empty($ids_checked) && in_array($path->id_road, $ids_checked)) ? 'selected' : '';
            $html .= '<option value="' . $path->id_road . '" ' . $selected . '>' . $path->path . '</option>';
        endforeach;
        $html .= '</select>';

        return $html;
    }

    private function get_road_inactive_html($road_name, $name, $ids_checked)
    {
        $RoadModel = new RoadModel($road_name);
        $inactives = $RoadModel->RoadsInactiveGet();
        if(empty($inactives)) return '';

        $html_array = [];
        foreach($inactives as $inactive) :
            if(in_array($inactive->id_road, $ids_checked)) :
                $label = $this->get_path_by_id_road($road_name, $inactive->id_road);
                $html_array[] = '
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked
                            name="' . $name . '[]"
                            value="' . $inactive->id_road . '"
                            id="road' . $inactive->id_road . 'Checkbox"
                        />
                        <label class="form-check-label text-left text-nowrap w-auto ml-0 mr-1" 
                            for="road' . $inactive->id_road . 'Checkbox"
                            >
                            ' . $label . '
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
    
    // -------------------------------- ROAD CHECKBOX COLLAPSE--------------------------------

    public function get_road_checkboxCollapse_html($road_name, $name, $ids_checked=null, $texts=null)
    {
        $html = $this->get_road_checkboxCollapse_recursive($road_name, $name, null, $ids_checked, $texts);
        
        return $html;
    }

    private function get_road_checkboxCollapse_recursive($road_name, $name, $roads=null, $ids_checked=null, $texts=null)
    {
        $RoadModel = new RoadModel($road_name);

        $html = '';

        if(!isset($roads)) $roads = $this->get_roads_active_recursive($road_name);

        $html .= '<div>';
        foreach($roads as $road):
            $html .= $this->get_road_checkboxCollapse_block($road_name, $name, $road, $ids_checked, $texts);
        endforeach; 
        $html .= '</div>';    

        foreach($roads as $road):
            if(!empty($road->children)):
                $ids_road_children = $RoadModel->IdsRoadGetByParent($road->id_road);
                $collapse_html = '';
                if(isset($ids_checked) && array_intersect($ids_road_children, $ids_checked)) $collapse_html = 'show';
                $html .= '<div id="road' . $road->id_road . 'Collapse" class="collapse ' . $collapse_html . ' ms-5">';
                $html .= '
                    <hr>
                    <div class="d-flex justify-content-between">
                        <label class="font-italic mb-0"> 
                            <small>' . $this->get_path_by_id_road($road_name, $road->id_road) . ' </small> 
                        </label>
                        <button class="btn btn-sm" onclick="$(this).closest(\'.collapse\').collapse(\'hide\');"> ' . fontawesome('times') . ' </button>
                    </div>
                    ';
                $html .= $this->get_road_checkboxCollapse_recursive($road_name, $name, $road->children, $ids_checked, $texts);
                $html .= '</div>';
            endif;
        endforeach;

        return $html;
    }

    private function get_road_checkboxCollapse_block($road_name, $name, $road, $ids_checked=null, $texts=null)
    {
        $checked_html = (isset($ids_checked) && in_array($road->id_road, $ids_checked)) ? 'checked' : '';
        $collapse_button = !empty($road->children) ? $this->get_road_checkboxCollapse_block_collapse_button($road_name, $road, $ids_checked) : '<span class="px-1"></span>';
        $form_check_class = !empty($road->has_text) ? 'd-block d-flex' : '';
        $input_text = !empty($road->has_text) ? $this->get_road_checkboxCollapse_block_has_text($road_name, $name, $road, $texts) : '';

        $label = t($road->label_fr, __NAMESPACE__);
        $html = '
            <div class="form-check form-check-inline mr-5 mb-2' . $form_check_class . '">
                <input class="form-check-input" type="checkbox"
                    onclick="road_checkbox_input_behaviour(this);"
                    name="' . $name . '[]"
                    value="' . $road->id_road . '"
                    id="road' . $road->id_road . 'Checkbox"
                    ' . $checked_html . '
                    />
                ' . $collapse_button . '
                <label class="form-check-label text-left text-nowrap w-auto ml-0 mr-1" for="road' . $road->id_road . 'Checkbox">
                    ' . $label . '
                </label>
                ' . $input_text . '
            </div>
        ';

        return $html;
    }

    private function get_road_checkboxCollapse_block_collapse_button($road_name, $road, $ids_checked=null, $texts=null)
    {
        $RoadModel = new RoadModel($road_name);

        $button_disabled = '';
        $ids_road_children = $RoadModel->IdsRoadGetByParent($road->id_road);
        if(
            (isset($ids_checked) && array_intersect($ids_road_children, $ids_checked)) ||
            (isset($texts) && array_intersect($ids_road_children, object_keys($texts)))
            ) :
            // $button_icon = fontawesome('caret-down');
            $button_disabled = 'disabled';
        endif;
        $html = '
            <button type="button" class="btn btn-sm btn-caret mr-1 px-1' . $button_disabled . '" ' . $button_disabled . '
                onclick="road_checkbox_dropdown_behaviour(this);"
                data-bs-toggle="collapse" 
                data-bs-target="#road' . $road->id_road . 'Collapse"
                >
                ' . fontawesome('caret-right') . '
            </button>
            ';

        return $html;
    }

    private function get_road_checkboxCollapse_block_has_text($road_name, $name, $road, $texts)
    {
        $id_road = $road->id_road;
        $value = isset($texts->$id_road) ? $texts->$id_road : '';
        $html = '
            <input type="text" class="form-control w-auto mx-2" 
                name="' . $name . '_text[' . $road->id_road . ']"
                value="' . $value . '"
                />
            <script>
                $(\'input[name="' . $name . '_text[' . $road->id_road . ']"]\').on(\'focus\', function() { 
                    $(this).parent().find(\'input[type="checkbox"]\').prop(\'checked\', true); 
                });
            </script>

        ';

        return $html;
    }    
    
    // -------------------------------- ROAD CHECKBOX --------------------------------

    public function get_road_checkbox_button($road_name)
    {
        return '
            <button type="button" class="btn btn-sm mx-2" 
                title="Liste à choix multiple"
                onclick="road_view_modal(this, \'' . $road_name . '\', \'checkbox\', \'xl\');"
                > 
                ' . fontawesome('check-square') . '
            </button>
        ';
    }

    public function get_road_checkbox_html($road_name, $name, $ids_checked=null, $texts=null)
    {
        $html = '
            <div road_name="' . $road_name . '" class="form-control-group">
                ' . $this->get_road_checkbox_recursive($road_name, $name, null, $ids_checked, $texts) . '
            </div>
        ';
        
        return $html;
    }

    private function get_road_checkbox_recursive($road_name, $name, $roads=null, $ids_checked=null, $texts=null)
    {
        $RoadModel = new RoadModel($road_name);

        $html = '';

        if(!isset($roads)) $roads = $this->get_roads_active_recursive($road_name);

        $html .= '<div>';
        foreach($roads as $road):
            $html .= $this->get_road_checkbox_row($road_name, $name, $road, $ids_checked, $texts);
            if(!empty($road->children)):
                $ids_road_children = $RoadModel->IdsRoadGetByParent($road->id_road);
                $html .= '
                    <div id="road' . $road->id_road . 'Collapse" class="ms-5">
                        ' . $this->get_road_checkbox_recursive($road_name, $name, $road->children, $ids_checked, $texts) . '
                    </div>
                ';
            endif;
        endforeach; 
        $html .= '</div>';

        return $html;
    }

    private function get_road_checkbox_row($road_name, $name, $road, $ids_checked=null, $texts=null)
    {
        $checked_html = (isset($ids_checked) && in_array($road->id_road, $ids_checked)) ? 'checked' : '';
        // $form_check_class = !empty($road->has_text) ? 'd-block d-flex' : '';
        $input_text = !empty($road->has_text) ? $this->get_road_checkbox_row_has_text($road_name, $road, $texts) : '';

        $html = '
            <div class="form-check">
                <input class="form-check-input" type="checkbox"
                    onclick="road_checkbox_to_tag(this);"
                    name="' . $name . '[]"
                    value="' . $road->id_road . '"
                    id="road' . $road->id_road . 'Checkbox"
                    ' . $checked_html . '
                    />
                <label class="form-check-label text-left text-nowrap" for="road' . $road->id_road . 'Checkbox">
                    ' . t($road->label_fr, __NAMESPACE__) . '
                </label>
                ' . $input_text . '
            </div>
            ';

        return $html;
    }

    private function get_road_checkbox_row_has_text($road_name, $road, $texts)
    {
        
        $id_road = $road->id_road;
        $value = isset($texts->$id_road) ? $texts->$id_road : '';
        $html = '
            <input type="text" class="form-control w-auto" 
                name="ids_road_' . $road_name . '_text[' . $road->id_road . ']"
                value="' . $value . '"
            />
            <script>
                $(\'input[name="ids_road_' . $road_name . '_text[' . $road->id_road . ']"]\').on(\'focus\', function() { 
                    $(this).parent().find(\'input[type="checkbox"]\').prop(\'checked\', true); 
                });
            </script>
        ';

        return $html;
    }

    // -------------------------------- MAIN --------------------------------

    public function get_road_main_html($road_name)
    {
        $RoadModel = new RoadModel($road_name);
        $roads = $RoadModel->RoadsActiveGetByParent(0);
        $html = '<form id="roadMainForm">';
        foreach($roads as $road):
            $html .= '
                <div class="row form-check">
                    <input class="form-check-input" type="radio" value="' . $road->id_road . '"
                        id_road="' . $road->id_road . '" 
                        id_cell="' . $road->id_cell . '" 
                        id="road' . $road->id_road . 'Radio" 
                        />
                    <label class="form-check-label" for="road' . $road->id_road . 'Radio"> ' . $road->label_fr . ' </label>
                </div>
            ';
        endforeach;
        $html .= '</form>';

        return $html;
    }

    // -------------------------------- COLLAPSE --------------------------------

    public function get_road_collapse_html($road_name)
    {
        $html = '<div id="roadListCollapse">';
        $html .= '<div id="road0Collapse">';
        $html .= $this->get_road_collapse_recursive($road_name);
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public function get_road_collapse_recursive($road_name, $roads=null)
    {
        if(!isset($roads)) $roads = $this->get_roads_active_recursive($road_name);
        $html = '';
        foreach($roads as $road):
            // $html .= '<div class="d-flex">';
            $html .= $this->get_road_collapse_block($road);
            if(!empty($road->children)):
                $html .= '<div id="road' . $road->id_road . 'Collapse" class="collapse ms-5" data-bs-parent="#road' . $road->id_road_parent . 'Collapse">';
                $html .= $this->get_road_collapse_recursive($road_name, $road->children);
                $html .= '</div>';
            endif;
            // $html .= '</div>';
        endforeach;
        
        return $html;
    }

    private function get_road_collapse_block($road)
    {
        if(!empty($road->children)) $visibility = '';
        else $visibility = 'style="visibility: hidden"';

        $rank = $road->rank + 1;
        $html = '
            <div class="d-flex text-nowrap">
                <div>
                    <button type="button" class="btn btn-sm btn-caret" 
                        id="road' . $road->id_road . 'Button"
                        data-bs-toggle="collapse" 
                        data-bs-target="#road' . $road->id_road . 'Collapse" 
                        ' . $visibility . '
                    > 
                    ' . fontawesome('caret-right') . '
                    </button>
                </div>
                <label class="form-check-label mx-2 text-nowrap" for="road' . $road->id_road . 'Button">
                    ' . $rank . '. ' . $road->label_fr . ' 
                </label>
            </div>
            ';
        return $html;
    }   
    
    // -------------------------------- RADIO --------------------------------

    public function get_road_radio_button($road_name)
    {
        return '
            <button type="button" class="btn btn-sm mx-2" 
                title="Liste à cocher unique"
                onclick="road_view_modal(this, \'' . $road_name . '\', \'checkbox\', \'xl\');"
                > 
                ' . fontawesome('check-circle') . '
            </button>
        ';
    }

    public function get_road_radio_html($road_name, $name, $id_checked=null, $text=null)
    {
        $html = '<div road_name="' . $road_name . '">';
        $html .= '<div id="road0Collapse">';
        $html .= $this->get_road_radio_recursive($road_name, $name, null, $id_checked, $text);
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public function get_road_radio_recursive($road_name, $name, $roads=null, $id_checked, $text)
    {
        if(!isset($roads)) $roads = $this->get_roads_active_recursive($road_name);
        $html = '';
        foreach($roads as $road):
            // $html .= '<div class="d-flex">';
            $html .= $this->get_road_radio_block($name, $road, $id_checked, $text);
            if(!empty($road->children)):
                $html .= '<div id="road' . $road->id_road . 'Collapse" class="collapse ms-5" data-bs-parent="#road' . $road->id_road_parent . 'Collapse">';
                $html .= $this->get_road_radio_recursive($road_name, $name, $road->children, $id_checked, $text);
                $html .= '</div>';
            endif;
            // $html .= '</div>';
        endforeach;
        
        return $html;
    }

    private function get_road_radio_block($name, $road, $id_checked, $text)
    {
        $checked_html = (isset($id_checked) && $road->id_road==$id_checked) ? 'checked' : '';
        $visibility = !empty($road->children) ? '' : 'style="visibility: hidden"';

        $rank = $road->rank + 1;
        $html = '
            <div class="form-check d-flex">
                <input class="form-check-input" type="radio"
                    name="' . $name . '"
                    value="' . $road->id_road . '"
                    id="road' . $road->id_road . 'Checkbox"
                    ' . $checked_html . '
                    />
                <label class="form-check-label mx-2 text-nowrap" for="road' . $road->id_road . 'Button">
                    ' . $rank . '. ' . $road->label_fr . ' 
                </label>
                <button type="button" class="btn btn-sm btn-caret" 
                    id="road' . $road->id_road . 'Button"
                    data-bs-toggle="collapse" 
                    data-bs-target="#road' . $road->id_road . 'Collapse" 
                    ' . $visibility . '
                    > 
                    ' . fontawesome('caret-right') . '
                </button>
            </div>
        ';
        return $html;
    }

    // -------------------------------- LIST --------------------------------

    public function get_road_list_button($road_name)
    {
        return '
            <button type="button" class="btn btn-sm mx-2" 
                title="Liste"
                onclick="road_view_modal(this, \'' . $road_name . '\', \'list\', \'xl\');"
                > 
                ' . fontawesome('list-ol') . '
            </button>
        ';
    }

    public function get_road_list_html($road_name)
    {
        $html = '<div id="roadList">';
        $html .= $this->get_road_list_recursive($road_name);
        $html .= '</div>';

        return $html;
    }

    public function get_road_list_recursive($road_name, $roads=null, $level=0, $parent_identation='')
    {
        if(!isset($roads)) $roads = $this->get_roads_active_recursive($road_name);
        $html = '';
        $i = 0;
        foreach($roads as $road):
            $identation = $parent_identation . ($road->rank + 1) . '.';
            $tab = '';
            for($t=0; $t<$level; $t++) $tab .= '<div class="d-inline px-4"></div>';
            $html .= '
                <div class="d-flex">
                    ' . $tab . '
                    <label class="form-check-label mx-2 text-nowrap" for="road' . $road->id_road . 'Button">
                        ' . $identation . ' ' . t($road->label_fr, __NAMESPACE__) . ' 
                    </label>
                </div>
                ';
            if(!empty($road->children)):
                // $html .= '<div id="road' . $road->id_road . 'Collapse" class="col ms-5">';
                $html .= $this->get_road_list_recursive($road_name, $road->children, $level+1, $identation);
                if($i<count($roads)-1) $html .= '<br>';
                // $html .= '</div>';
            endif;
            $i++;
        endforeach;
        
        return $html;
    }

    // public function get_path_by_id_road($road_name, $id_road, $param=null)
    // {
    //     return $this->path_by_road_get_recursive($road_name, $id_road, $param);
    // }
    
    // private function path_by_road_get_recursive($road_name, $id_road, $param=null)
    // {
    //     $RoadModel = new RoadModel($road_name);
    //     $road = $RoadModel->RoadGet($id_road);

    //     if(empty($road) || empty($road->label_fr)) :
    //         $path = $this->get_road_label_unlinked($id_road);
    //         return $path;
    //     endif;

    //     $param = isset($param) ? database_decode($param) : (object) [];
    //     $param->lang = empty($param->lang) ? LanguageSessionGet() : $param->lang;
    //     $param->withButton = false;
    //     $id_road_parent = $road->id_road_parent;
    //     if($id_road_parent == 0) :
    //         $title = t($road->label_fr, __NAMESPACE__, $param);
    //     else :
    //         $title = $this->path_by_road_get_recursive($road_name, $id_road_parent, $param) . ' > '. t($road->label_fr, __NAMESPACE__, $param);
    //     endif;

    //     return $title;
    // }

    public function get_ranks_by_id_road($road_name, $id_road)
    {
        $roads = $this->get_roads_by_id_road($road_name, $id_road);
        $ranks = array_column($roads, 'rank');

        return $ranks;
    }

    public function get_labels_by_id_road($road_name, $id_road)
    {
        $roads = $this->get_roads_by_id_road($road_name, $id_road);
        $labels = array_column($roads, 'label_fr');

        return $labels;
    }

    public function get_path_by_id_road($road_name, $id_road)
    {
        $labels = $this->get_labels_by_id_road($road_name, $id_road);

        $param = (object) [];
        $param->lang = LanguageSessionGet();
        $param->withButton = false;

        $labels_lang = [];
        foreach($labels as $label) :
            $labels_lang[] = t($label, __NAMESPACE__, $param);
        endforeach;

        return implode(' > ', $labels_lang);
    }

    public function get_ids_road_by_id_road($road_name, $id_road)
    {
        $roads = $this->get_roads_by_id_road($road_name, $id_road);
        return array_column($roads, 'id_road');
    }

    public function get_roads_by_id_road($road_name, $id_road)
    {
        return array_reverse($this->get_roads_by_id_road_recursive($road_name, $id_road));
    }

    private function get_roads_by_id_road_recursive($road_name, $id_road, $roads=null)
    {
        $RoadModel = new RoadModel($road_name);
        $road = $RoadModel->RoadGet($id_road);

        if(empty($road) || empty($road->label_fr)) :
            $road = (object) [];
            $road->label_fr = $this->get_road_label_unlinked($id_road);
            $road->path = $this->get_road_label_unlinked($id_road);
            $roads[] = $road;
            return $roads;
        endif;

        $roads[] = $road;
        if($road->id_road_parent != 0) $roads = $this->get_roads_by_id_road_recursive($road_name, $road->id_road_parent, $roads);

        return $roads;
    }

    public function get_roads_active_recursive($road_name, $id_road_parent=0, $level=0)
    {
        $RoadModel = new RoadModel($road_name);
        $roads = $RoadModel->RoadsActiveGetByParent($id_road_parent);

        if(count($roads)>0):
            foreach($roads as $road):
                $road->level = $level;
                // $road->ids_cell = $this->get_ids_cell_by_id_road($road_name, $road->id_road);
                // $road->labels = $this->get_labels_by_id_road($road_name, $road->id_road);
                // $road->ids_road = $this->get_ids_road_by_id_road($road_name, $road->id_road);
                // $road->path = implode(' > ', $road->labels);
                $road->children = $this->get_roads_active_recursive($road_name, $road->id_road, $level+1);
            endforeach;
        endif;

        return $roads;
    }

    public function get_roads_with_cell_recursive($roads, $ids_leaf)
    {
        $data = [];
        $i = 0;
        foreach($roads as $road) :
            if(empty($road->children)) :
                if(in_array($road->id_road, $ids_leaf)) :
                    $data[$i] = $road;
                endif;
            else :
                $road->children = $this->get_roads_with_cell_recursive($road->children, $ids_leaf);
                if(count($road->children)>0) :
                    $data[$i] = $road;
                endif;
            endif;
            $i++;
        endforeach;

        return $data;
    }

    public function get_roads_active_flat($road_name)
    {
        $RoadModel = new RoadModel($road_name);
        $roads = $RoadModel->get_roads_active_flat();

        $i = 0;
        foreach($roads as $road) :
            $road->ids_cell = $this->get_ids_cell_by_id_road($road_name, $road->id_road);
            $road->labels = $this->get_labels_by_id_road($road_name, $road->id_road);
            $road->ids_road = $this->get_ids_road_by_id_road($road_name, $road->id_road);
            $road->path = implode(' > ', $road->labels);
            $roads[$i] = $road;
        endforeach;

        return $roads;
    }

    public function get_roads_branch($road_name)
    {
        $roads = $this->get_roads_active_recursive($road_name);
        return $roads;
    }

    public function get_ids_cell_by_id_road($road_name, $id_road)
    {
        return array_reverse($this->get_ids_cell_by_id_road_recursive($road_name, $id_road));
    }

    private function get_ids_cell_by_id_road_recursive($road_name, $id_road, $ids=null)
    {
        $RoadModel = new RoadModel($road_name);
        $road = $RoadModel->RoadGet($id_road);
        $ids[] = $road->id_cell;
        if($road->id_road_parent != 0) $ids = $this->get_ids_cell_by_id_road_recursive($road_name, $road->id_road_parent, $ids);

        return $ids;
    }

    public function convert_to_id_road_demande($id_type_demande, $id_type_accompagnement)
    {
        if(!empty($id_type_accompagnement)) :
            $demande_old = $this->db->table('liste_type_accompagnement')->where('id', $id_type_accompagnement)->get()->getResult()[0];
        elseif(!empty($id_type_demande)) :
            $demande_old = $this->db->table('liste_demande_type')->where('id', $id_type_demande)->get()->getResult()[0];
        endif;

        if(empty($demande_old)) return false;

        $RoadModel = new RoadModel('demande');
        $demandes_new = $RoadModel->get_roads_active_flat();

        $id_demande_type = '';

        foreach($demandes_new as $demande_new) :
            if(mb_strtolower($demande_old->label)=='accompagnement') $demande_old->label = 'général';
            if(mb_strtolower($demande_new->label_fr)==mb_strtolower($demande_old->label)) :
                $id_demande_type = $demande_new->id_road;
                break;
            endif;
        endforeach;

        return $id_demande_type;
    }

    public function get_paths_by_id_cell($id_cell)
    {
        $roads = $this->get_roads_by_id_cell($id_cell);
        $paths = [];
        foreach($roads as $road) :
            $paths[] = $road->path;
        endforeach;

        return $paths;
    }

    public function get_roads_by_id_cell($id_cell)
    {
        $data = [];
        foreach($this->roads as $road_name) :
            $RoadModel = new RoadModel($road_name);
            $roads = $RoadModel->RoadsGetByCell($id_cell);
            // $title = $this->road_label_get($road_name, ['withButton', false]);
            foreach($roads as $road) :
                $road->path = $this->get_path_by_id_road($road_name, $road->id_road);
                $data[] = $road;
            endforeach;
        endforeach;

        return $data;
    }

    public function road_label_get($road_name, $param=null)
    {
        $roads = json_decode(file_get_contents($this->path . 'Config/Json/road/list.json'));
        if(empty($roads)) return false;
        if(empty($roads->$road_name)) return false;

        return t($roads->$road_name->label, __NAMESPACE__);
    }

    public function ParentsGetByRoads($road_name, $ids_road)
    {
        $RoadModel = new RoadModel($road_name);
        return $RoadModel->ParentsGetByRoads($ids_road);
    }

    public function TableRoadGet($road_name)
    {
        $RoadModel = new RoadModel($road_name);
        return $RoadModel->TableRoadGet($road_name);
    }

    public function get_dems_join_cell()
    {
        $dems = $this->db->table('demande_feature')->get()->getResult();
        foreach($dems as $dem) :
            $dem->cell_them_first = $this->db->table($this->t_cell)->where('id_cell = ' . $dem->id_cell_them_first)->get()->getResult()[0]->label_fr;

            if(isset($dem->ids_cell_them_second)):
                $ids_cell_them_second = json_decode($dem->ids_cell_them_second);
                $cells_them_second = [];
                foreach($ids_cell_them_second as $id):
                    $cells_them_second[] = $this->db->table($this->t_cell)->where('id_cell = ' . $id)->get()->getResult()[0]->label_fr;
                endforeach;
                if(!empty($cells_them_second)) :
                    sort($cells_them_second);
                    $dem->cells_them_second = $cells_them_second;
                endif;
            endif;

            if(isset($dem->ids_cell_accomp)):
                $ids_cell_accomp = json_decode($dem->ids_cell_accomp);
                $cells_accomp = [];
                foreach($ids_cell_accomp as $id):
                    $cells_accomp[] = $this->db->table($this->t_cell)->where('id_cell = ' . $id)->get()->getResult()[0]->label_fr;
                endforeach;
                if(!empty($cells_accomp)) :
                    sort($cells_accomp);
                    $dem->cells_accomp = $cells_accomp;
                endif;
            endif;
        endforeach;

        return $dems;        
    }

    public function merge_cell_dublons($dublons)
    {      
        $columns = $this->db->getFieldNames($this->t_cell);
        $post = (object) [];
        $id_to_keep = null;
        $ids_to_replace = null;
        for($i=count($dublons)-1; $i>=0; $i--) :
            if($i==count($dublons)-1) $id_to_keep = $dublons[$i]->id_cell;
            else $ids_to_replace[] = $dublons[$i]->id_cell;
            foreach($columns as $column) :
                if(empty($post->$column) && isset($dublons[$i]->$column)) $post->$column = $dublons[$i]->$column;
            endforeach;
        endfor; 
        $this->db->table($this->t_cell)->where('id_cell', $id_to_keep)->update((array) $post);

        foreach($ids_to_replace as $id_to_replace) :
            foreach($this->roads as $road_name) :
                $road_table = $this->TableRoadGet($road_name);
                $post = (object) [];
                $post->id_cell = $id_to_keep;
                $this->db->table($road_table)->where('id_cell', $id_to_replace)->update((array) $post);
            endforeach;
            $this->db->table($this->t_cell)->where('id_cell', $id_to_replace)->delete();

        endforeach;
    }

}