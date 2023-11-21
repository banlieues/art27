<?php  

namespace Components\Libraries;

use Tesorus\Libraries\TesorusLibrary;
use Base\Libraries\BaseLibrary;
use Components\Libraries\FileLibrary;
use Components\Libraries\ListLibrary;
// use Translator\Libraries\TranslatorLibrary;

class FormLibrary extends BaseLibrary
{
    public function __construct($namespace)
    {
        parent::__construct($namespace);
        $this->namespace = $namespace;
    }

    
//    public function get_string_update($id_user, $datetime)
//     {
//         $user = $this->UserModel->get_user_by_id($id_user);
//         $user_html = !empty((array) $user) ? 'par <span class="mx-2 font-italic"> ' . $user->username . ' </span>' : '';
//         $date_html = date('d/m/y à H:i', strtotime($datetime));

//         $html = '
//             <small> 
//                 Dernière modification 
//                 ' . $user_html . '
//                 le <span class="mx-2 font-italic">' . $date_html . ' </span>
//             </small>
//         ';
            
//         return $html; 
//     } 

    // public function get_string_create($id_user, $datetime)
    // {
    //     $user = $this->UserModel->get_user_by_id($id_user);
    //     $user_html = !empty((array) $user) ? 'par <span class="mx-2 font-italic"> ' . $user->username . ' </span>' : '';
    //     $date_html = date('d/m/y à H:i', strtotime($datetime));

    //     $html = '
    //         <small> 
    //             Création 
    //             ' . $user_html . '
    //             le <span class="mx-2 font-italic">' . $date_html . ' </span>
    //         </small>
    //     ';
            
    //     return $html; 
    // }

    private function get_fields($entity)
    {
        $file = APPPATH . 'modules/' . $this->module . '/json/' . $entity . '/form.json';
        if(!file_exists($file)) return false;

        $fields = json_decode(file_get_contents($file));
        
        if(empty((array) $fields)) return false;

        return $fields;
    }

    private function get_field_by_ref($entity, $ref)
    {
        $fields = $this->get_fields($entity);
        if(empty((array) $fields->$ref)) return false;

        return $fields->$ref;
    }

    public function get_form_control_by_ref($entity, $ref, $post=null)
    {
        $ListLibrary = new ListLibrary($this->module);
        $list = $this->ListLibrary->get_list_by_ref($ref);
        $field = $this->get_field_by_ref($entity, $ref);
        $field->ref = $ref;
        $html = $this->get_form_control($field, $post, $list);

        return $html;
    }

    public function get_details_history_text($collapse_target, $param)
    {
        $text = '';
        if(!empty($param->update_iduser) || !empty($param->updated_at)) :
            $update_user = sessionUser($param->update_iduser);
            $update_user_html = !empty((array) $update_user) ? 'par <span class="mx-2 font-italic"> ' . $update_user->username . ' </span>' : '';
            $update_date_html = date('d/m/y à H:i', strtotime($param->updated_at));

            $text .= '
                <small> 
                    Dernière modification 
                    ' . $update_user_html . '
                    le <span class="mx-2 font-italic">' . $update_date_html . ' </span>
                </small>
            ';
        endif;
        if(!empty($param->create_iduser) || !empty($param->created_at)) :
            if(!empty($param->update_iduser) || !empty($param->updated_at)) $text .= ' <br> ';
            $create_user = sessionUser($param->create_iduser);
            $create_user_html = !empty((array) $create_user) ? 'par <span class="mx-2 font-italic"> ' . $create_user->username . ' </span>' : '';
            $create_date_html = date('d/m/y à H:i', strtotime($param->created_at));

            $text .= '
                <small> 
                    Création 
                    ' . $create_user_html . '
                    le <span class="mx-2 font-italic">' . $create_date_html . ' </span>
                </small>
            ';
        endif;
        $html = '
            <div id="' . $collapse_target . '" class="collapse h6">
                ' . $text . '
            </div>
        ';

        return $html;
    }

    public function get_details_history_button($collapse_target)
    {
        $html = '
            <button type="button" class="btn btn-sm" data-bs-toggle="collapse" data-bs-target="#' . $collapse_target . '">
                ' . fontawesome('business-time') . '
            </button>
        ';

        return $html;
    }

    public function get_info_titles($fields)
    {
        $titles = (object) [];
        foreach($fields as $ref=>$field) :
            $titles->$ref = t($field->label_fr, $this->namespace, ['withButton'=>false]);
        endforeach;

        return $titles;
    }

    public function get_inputs_hidden_from_post($post, $form_id=null)
    {
        $form_attr = !empty($form_id) ? 'form="' . $form_id . '"' : '';
        $inputs_hidden = '';
        if(!empty($post)) :
            foreach($post as $name=>$value) :
                $value = isset($value) ? $value : '';
                $value = is_string($value) ? $value : json_encode($value, JSON_NUMERIC_CHECK);
                $inputs_hidden .= '<input type="hidden" ' . $form_attr . ' name="' . $name . '" value="' . $value . '"/>';
            endforeach;
        endif;

        return $inputs_hidden;
    }

    public function get_form_control_field($field, $ref, $module, $form_type=null)
    {
        $field->ref = $ref;
        $field->module = $module;

        if(!empty($field->form)) $field->control_id = $field->form . '__' . $ref;
        
        if(!empty($form_type) && !empty($field->form_type)) :
            if(!empty($field->form_type->$form_type)) :
                $field = object_merge($field, $field->form_type->$form_type);
            endif;
            unset($field->form_type);
        endif;

        return $field;
    }

    public function get_form_control($field, $post, $list=null)
    {
        $ref = $field->ref;
        $value = isset($post->$ref) ? $post->$ref : null;

        $text = null;
        // if($field->type == 'file' && !empty($post->{$field->pk})) :
        //     $field->pk_value = $post->{$field->pk};
        // elseif($field->type == 'tesorus') :
        if($field->type == 'tesorus') :
            $text = (!empty($post->{$ref . '_text'})) ? $post->{$ref . '_text'} : null;
        endif;
        $control = $this->get_form_control_html($field, $value, $list, $text);

        return $control;
    }

    public function get_form_control_html($field, $value=null, $list=null, $text=null)
    {
        $html = '';
        if(in_array($field->type, ['hidden', 'info'])) :
            $html .= $this->{'get_form_control_' . $field->type}($field, $value);
        else :
            $answer_html = $this->{'get_form_control_' . $field->type}($field, $value, $list, $text);
            $html .= $this->get_form_control_group($answer_html, $field);
        endif;

        return $html;
    }

    // public function get_form_html($param)
    // {
    //     $html = '';
    //     if(in_array($param->type, ['hidden', 'info'])) :
    //         $html .= $this->{'get_form_html_' . $param->type}($param);
    //     else :
    //         $answer_html = $this->{'get_form_html_' . $param->type}($param);
    //         $html .= $this->get_form_html_group($answer_html, $param);
    //     endif;

    //     return $html;
    // }

    public function get_form_control_group($answer_html, $field)
    {
        $group_class = $this->get_form_class_group($field->type, $field->grid);
        $label_class = $this->get_form_class_label($field->type, $field->grid);
        $answer_class = $this->get_form_class_answer($field->type, $field->grid);
        $label = t($field->label_fr, $this->namespace);
        $label_div = !empty($field->isRequired) ? '<label class="' . $label_class . ' fw-bold"> ' . $label . '* </label>' : '<label class="' . $label_class . '"> ' . $label . ' </label>';
        $html = '
            <div class="mb-2 ' . $group_class . '">
                ' . $label_div . '
                <div class="' . $answer_class . '">' . $answer_html . '</div>
            </div>
        ';

        return $html;
    }

    // public function get_form_html_group($answer_html, $param)
    // {
    //     $group_class = $this->get_form_class_group($param->type, $param->grid);
    //     $label_class = $this->get_form_class_label($param->type, $param->grid);
    //     $answer_class = $this->get_form_class_answer($param->type, $param->grid);
    //     $label = t($param->label_fr);
    //     $html = '
    //         <div class="mb-2 ' . $group_class . '">
    //             <label class="' . $label_class . '"> ' . $label . ' </label>
    //             <div class="' . $answer_class . '">' . $answer_html . '</div>
    //         </div>
    //     ';
    //     return $html;
    // }

    private function get_form_control_editor($field, $value=null)
    {
        $value = isset($value) ? $value : '';
        $html = '        
            <button type="button" class="btn btn-primary mb-2" onclick="js_edit_content_modal(this)">
                ' . t("Editer le contenu", $this->namespace) . '
            </button>
            <div class="border p-4" data-name="' . $field->ref . '">
                ' . $value . '
            </div>
            <input type="hidden" name="' . $field->ref . '" value="' . htmlspecialchars($value) . '"/>
        ';

        return $html;
    }

    // private function get_form_html_editor($param)
    // {
    //     $value = isset($param->value) ? $param->value : '';
    //     $html = '        
    //         <button type="button" class="btn btn-primary mb-2" onclick="js_edit_content_modal(this)">
    //             ' . t("Editer le contenu", $this->namespace) . '
    //         </button>
    //         <div class="border p-4" data-name="' . $param->ref . '">
    //             ' . $value . '
    //         </div>
    //         <input type="hidden" name="' . $param->ref . '" value="' . htmlspecialchars($value) . '"/>
    //     ';

    //     return $html;
    // }

    private function get_form_control_info($field, $value=null)
    {
        $html = '';
        if(isset($value)) $html .= '
            <div class="d-flex align-items-center">
                <div> ' . t($field->label_fr, $this->namespace) . '</div>
                <div> ' . $value . ' </div>
            </div>
        ';

        return $html;
    }

    // private function get_form_html_info($param)
    // {
    //     $html = '';
    //     if(isset($param->value)) $html .= '
    //         <div class="d-flex align-items-center">
    //             <div> ' . t($param->label_fr, $this->namespace) . '</div>
    //             <div> ' . $param->value . ' </div>
    //         </div>
    //     ';

    //     return $html;
    // }

    private function get_form_control_hidden($field, $value=null)
    {
        $value = isset($value) ? $value : '';
        $html = '<input type="hidden" name="' . $field->ref . '" value="' . $value . '"/>';

        return $html;
    }

    // private function get_form_html_hidden($param)
    // {
    //     $param->value = isset($param->value) ? $param->value : '';
    //     $html = '<input type="hidden" name="' . $param->ref . '" value="' . $param->value . '"/>';

    //     return $html;
    // }

    private function get_form_control_radio_dual($field, $value=null)
    {
        $input_align = $this->get_form_class_check_align($field->type, $field->grid);
        $param = (object) [];
        $param->align = ($input_align=='form-check-inline') ? 'inline' : 'block';
        $checked_no = (isset($value) && $value==0) ? $checked_no = 'checked' : '';
        $checked_yes = (isset($value) && $value==1) ? $checked_yes = 'checked' : '';
        
        $html = '
            <div class="form-check ' . $input_align . ' mb-2">
                <input class="form-check-input" type="radio" name="' . $field->ref . '" value="0" ' . $checked_no . '/>
                <label class="form-check-label text-left"> ' . t("Non", $this->namespace, $param) . ' </label>
            </div>
            <div class="form-check ' . $input_align . ' mb-2">
                <input class="form-check-input" type="radio" name="' . $field->ref . '" value="1" ' . $checked_yes . '>
                <label class="form-check-label text-left"> ' . t("Oui", $this->namespace, $param) . ' </label>
            </div>
        ';

        return $html;
    }

    private function get_form_control_radio($field, $value=null, $list=null)
    {
        $html = '';
        if(!empty($list)) :
            $answer_align = $this->get_form_class_check_align($field->type, $field->grid);
            $param = (object) [];
            $param->align = ($answer_align=='form-check-inline') ? 'inline' : 'block';
            foreach($list as $elem) :
                $checked = (isset($value) && $elem->id==$value) ? $checked = 'checked' : '';
                $label_answer = t($elem->label_fr, $this->namespace, $param);
                $html .= '
                    <div class="form-check ' . $answer_align . ' mb-2">
                        <input class="form-check-input" type="radio" 
                            name="' . $field->ref . '" 
                            value="' . $elem->id . '"
                            ' . $checked . '
                            />
                        <label class="form-check-label text-left"> ' . $label_answer . ' </label>
                    </div>
                ';
            endforeach;
        endif;

        return $html;
    }

    private function get_form_control_file($field, $value=null)
    {   
        $FileLibrary = new FileLibrary($this->module);

        $data = (object) [];
        $data->pk_file = get_primary_key($this->t_file);
        $data->files = [];
        $data->ref = $field->ref;
        $data->module = $field->module;
        $data->multiple = !empty($field->is_multiple) ? 'multiple' : '';
        if(!empty($value)) :
            $files = [];
            if(is_array($value)) :
                $files = $FileLibrary->FilesGet($value);
                $docs = $FileLibrary->DocsGet($value);
                $imgs = $FileLibrary->ImgsGet($value);
                    // $files[] = $file_l->get_file_by_id_file($id_file, $field->ref);
            elseif(is_string($value) || is_int($value)) :
                $files[] = $FileLibrary->FileGet($value);
                // $files[] = $file_l->get_file_by_id_file($value, $field->ref);
            endif;
            $data->files = array_values(array_filter($files));
            $data->docs = !empty($docs) ? array_values(array_filter($docs)) : [];
            $data->imgs = !empty($imgs) ? array_values(array_filter($imgs)) : [];
        endif;

        return view('Components\file/form', (array) $data);
    }

    private function get_form_control_checkbox($field, $value=null, $list=null)
    {
        $input_align = $this->get_form_class_check_align($field->type, $field->grid);
        $param = (object) [];
        $param->align = ($input_align=='form-check-inline') ? 'inline' : 'block';
        $html = '';
        if(!empty($list)) :
            foreach($list as $elem) :
                $checked = (!empty($value) && in_array($elem->id, $value)) ? 'checked' : '';
                $label_answer = t($elem->label_fr, $this->namespace, $param);
                $html .= '
                    <div class="form-check ' . $input_align . ' mb-2">
                        <input class="form-check-input" type="checkbox" 
                            name="' . $field->ref . '[]" 
                            value="' . $elem->id . '"
                            ' . $checked . '
                            />
                        <label class="form-check-label text-left"> ' . $label_answer . ' </label>
                    </div>
                ';
            endforeach;
        endif;

        return $html;
    }

    private function get_form_control_select($field, $value=null, $list=null)
    {
        $html = '';
        if(!empty($list)) :
            $html .= '<select class="form-select" name="' . $field->ref . '">';
            $html .= '<option></option>';
            foreach($list as $elem) :
                $selected = (isset($value) && $value==$elem->id) ? 'selected' : '';
                $param = (object) [];
                $param->align = 'inline';
                $param->withButton = false;
                $label_answer = t($elem->label_fr, $this->namespace, $param);
                $html .= '<option value="' . $elem->id . '"' . $selected . '>' . $label_answer . ' </option>';
            endforeach;
            $html .= '</select>';
        endif;

        return $html;
    }

    private function get_form_mask($mask)
    {
        $html = 'data-inputmask="\'mask\': \'' . $mask . '\'"';

        return $html;
    }

    private function get_form_control_text($field, $value=null)
    {
        $annotation = !empty($field->annotation) ? '<div style="line-height:1"> <small> ' . t($field->annotation, $this->namespace) . ' </small> </div>' : '';
        $required_attr = !empty($field->isRequired) ? 'required ' : '';
        $readonly_class = !empty($field->isReadonly) ? 'readonly ' : '';
        $readonly_attr = !empty($field->isReadonly) ? 'readonly ' : '';
        $required_anno = !empty($field->isRequired) ? '<div class="invalid-feedback"> ' . t("Ce champ est obligatoire pour valider le formulaire.", $this->namespace) . '</div>' : '';
        $value = isset($value) ? $value : '';
        $mask = isset($field->mask) ? $this->get_form_mask($field->mask) : '';
        $brugis_class = !empty($field->isBrugis) ? 'brugis-search' : '';
        $no_autocomplete = !empty($field->isBrugis) ? ' autocomplete="false" ' : '';
        $brugis_js = !empty($field->isBrugis) ? view('Components\js/brugis-tamo') : '';
        
        $html = '
            <input type="text" class="form-control ' . $readonly_class . ' ' . $brugis_class . '" 
                name="' . $field->ref . '" 
                value="' . $value . '" 
                ' . $no_autocomplete . ' 
                ' . $mask . ' 
                ' . $readonly_attr . ' 
                ' . $required_attr . ' 
                />
            ' . $annotation . '
            ' . $required_anno . '
            ' . $brugis_js . '
        ';

        return $html;
    }

    private function get_form_control_textarea($field, $value=null)
    {
        $value = isset($value) ? $value : '';
        // $value = (!empty($param->mask) && empty($value)) ? $this->get_form_mask($param->mask) : '';
        $html = '
            <textarea class="form-control" rows="3"
                name="' . $field->ref . '"
                >' . $value . '</textarea>
        ';

        return $html;
    }

    // private function get_form_html_textarea($param)
    // {
    //     $value = isset($param->value) ? $param->value : '';
    //     $$value = (!empty($param->mask) && empty($value)) ? $this->get_form_mask($param->mask) : '';
    //     $html = '
    //         <textarea class="form-control" rows="3"
    //             name="' . $param->ref . '"
    //             >' . $value . '</textarea>
    //     ';

    //     return $html;
    // }

    private function get_form_control_email($field, $value=null)
    {
        $value = isset($value) ? $value : '';
        $html = '
            <input type="email" class="form-control" name="' . $field->ref . '" value="' . $value . '">
        ';

        return $html;
    }

    // private function get_form_html_email($param)
    // {
    //     $value = isset($param->value) ? $param->value : '';
    //     $html = '
    //         <input type="email" class="form-control" name="' . $param->ref . '" value="' . $value . '">
    //     ';

    //     return $html;
    // }

    private function get_form_control_date($field, $value=null)
    {
        $value = isset($value) ? $value : '';
        $html = '
            <input type="date" class="form-control" name="' . $field->ref . '" value="' . $value . '">
        ';

        return $html;
    }

    // private function get_form_html_date($param)
    // {
    //     $value = isset($param->value) ? $param->value : '';
    //     $html = '
    //         <input type="date" class="form-control" name="' . $param->ref . '" value="' . $value . '">
    //     ';

    //     return $html;
    // }

    private function get_form_control_tesorus($field, $value=null, $list=null, $text=null)
    {
        $TesorusLibrary = new TesorusLibrary();

        $tesorus = $field->tesorus;
        $html = $TesorusLibrary->{'get_road_' . $tesorus->type . '_html'}($tesorus->road, $field->ref, $value, $text, $field->ref);

        return $html;
    }

    private function get_form_control_tag($field, $value=null)
    {
        $t_tag = $this->module_short . '_tag';
        $tags = $this->db->table($t_tag)->get()->getResult();

        $html = '
            <select 
                class="bs-multi-select" multiple
                name="' . $field->ref . '[]"
                >
        ';
        foreach($tags as $tag) :
            $selected = (!empty($value) && in_array($tag->id_tag, $value)) ? 'selected' : '';
            $html .= '<option value="' . $tag->id_tag . '" ' . $selected . '>' . $tag->label . '</option>';
        endforeach;
        $html .= '</select>';

        return $html;
    }

    public function get_form_class_label($type, $grid)
    {
        $label_padding = isset($type) && in_array($type, ['radio', 'radio_dual', 'checkbox']) ? 'pt-0' : '';
        $label_class[] = 'col-form-label';
        // if(isset($field->type) && in_array($field->type, ['radio', 'radio_dual', 'checkbox'])) $label_class[] = 'text-left';
        $label_class[] = isset($grid->group_col) ? 'pb-0' : null;
        $label_class[] = isset($grid->label_col) ? $grid->label_col . ' ' . $label_padding : 'text-left';

        return implode(' ', $label_class);
    }

    public function get_form_class_group($type, $grid)
    {
        $group_class = isset($grid->group_col) ? $grid->group_col : 'row';

        return $group_class;
    }

    public function get_form_class_answer($type, $grid)
    {
        $answer_class = [];
        if(
            isset($type) && 
            in_array($type, ['radio', 'radio_dual', 'checkbox']) &&
            isset($grid->group_col)
        ) :
            $answer_class[] = 'py-2';
        endif;
        $answer_class[] = isset($grid->answer_col) ? $grid->answer_col : null;

        return implode(' ', $answer_class);
    }

    private function get_form_class_check_align($type, $grid)
    {
        if(isset($grid->input_align)) :
            switch($grid->input_align):
                case 'inline' : $input_align = 'form-check-inline'; break;
                case 'block' : $input_align = ''; break;
            endswitch;
        else :
            if(!empty($grid->list) && count($grid->list)<3) $input_align = 'form-check-inline';
            elseif($type=='radio_dual') $input_align = 'form-check-inline';
            else $input_align = '';
        endif;

        return $input_align;
    }
}