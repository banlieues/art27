<?php

namespace Report\Libraries;

use Report\Config\Globals;
use Report\Models\ReportModel;
use Base\Libraries\BaseLibrary;
use Components\Libraries\FormLibrary;
use Components\Libraries\ListLibrary;
use Translator\Libraries\TranslatorLibrary;

class ReportLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
        $globals = new Globals();
        foreach($globals as $global=>$value) $this->$global = $value;

        $this->report_m = new ReportModel();
        $this->FormLibrary = new FormLibrary(__NAMESPACE__);
        $this->ListLibrary = new ListLibrary(__NAMESPACE__);
    }

    public function get_block_warning()
    {
        return '
            <div class="alert alert-warning mb-4"> 
                ' . t("
                    Le style de l'aperçu peut changer par rapport à celui du fichier Word. Cette différence ne sera pas prise en compte lors de la génération de rapport.
                ", __NAMESPACE__) . '
            </div>
        ';
    }

    public function get_form_controls($controller, $form_type, $post=null)
    {
        $controls = (object) [];
        $fields = json_decode(file_get_contents($this->path . 'Config/Json/' . $controller . '/form.json'));

        $exceptions = ['preview', 'blocks', 'id_parent', 'id_person', 'id_request'];
        foreach($fields as $ref=>$field) :
            $field = $this->FormLibrary->get_form_control_field($field, $ref, $this->module, $form_type);
            if(!in_array($ref, $exceptions)) :
                $list = $this->ListLibrary->get_list_by_ref($ref);
                $controls->$ref = $this->FormLibrary->get_form_control($field, $post, $list);
            else :
                $controls->$ref = $this->{'get_form_control_' . $ref}($field, $post);
            endif;
        endforeach;

        return $controls;
    }

    public function get_form_control_blocks($field, $post)
    {
        $data = (object) [];
        if(!empty($post->id_report)) :
            $data->blocks = $this->report_m->get_blocks_by_id_report($post->id_report);
            $data->id_report = $post->id_report;
        endif;
        $data->label = $field->label_fr;
        $data->block_warning = $this->get_block_warning();

        return view('Report\report/form_blocks', (array) $data);
    }

    private function get_form_control_preview($field, $post)
    {
        if(empty($post->id_block)) return false;
        
        $block = $this->report_m->get_block_by_id($post->id_block);
        $file = $this->report_m->get_file_by_id($block->id_file);

        $answer_html = $this->get_block_warning() . $this->get_file_preview_by_id($file->id);

        $html = $this->FormLibrary->get_form_control_group($answer_html, $field);

        return $html;
    }

    private function get_form_control_id_parent($field, $post)
    {
        $id_level_parent = $post->id_level - 1;
        $parents = $this->db->table($this->t_report)->where('id_level', $id_level_parent)->get()->getResult();
        $options = '<option selected disabled></option>';
        foreach($parents as $parent) :
            $selected = (!empty($post->id_parent) && $post->id_parent==$parent->id_report) ? 'selected' : '';
            $options .= '<option value="' . $parent->id_report . '" ' . $selected . '>' . $parent->label . '</option>';
        endforeach;

        $annotation = empty($post->id_parent) ? '<div style="line-height:1"> <small> ' . t($field->annotation, __NAMESPACE__) . ' </small> </div>' : '';
        $disabled = !empty($post->id_parent) ? 'disabled' : '';
        $answer_html = '
            <select class="form-select" name="id_parent" onchange="parent_blocks_get(this); parent_thems_get(this);" ' . $disabled . '>
                ' . $options . '
            </select>
            ' . $annotation . '
        ';

        $html = $this->FormLibrary->get_form_control_group($answer_html, $field);

        return $html;
    }

    private function get_form_control_id_person($field, $post)
    {
        $id_person = '';
        $person_search = '';
        if(!empty($post->id_person)) :
            $person = $this->report_m->get_person_by_id($post->id_person);
            $person_search = $person->id_personne . ' - ' . $person->prenom . ' ' . $person->nom;
            $id_person = $post->id_person;
        endif;

        $answer_html = '
            <input class="form-control" name="id_person_search" autocomplete="false" value="' . $person_search . '"/>
            <input type="hidden" name="id_person" value="' . $id_person . '"/>
            <script>
                $(\'[name="id_person_search"]\').on(\'keyup\', function() {
                    $(\'[name=id_person]\').val(\'\');
                    $(this).html(\'' . fontawesome('spinner') . '\');
                    const search = $(this).val();
                    $(this).autocomplete({
                        source: function(request, response) {
                            const url = "' . base_url('report/person/linked/get') . '";
                            let data = {};
                            data.search = search;
                            data.id_request = $(\'[name="id_request"]\').val();
                            $.post(url, data, function(result) {
                                result = JSON.parse(result);
                                $(this).html(\'\');
                                response(result);
                            });
                        },
                        minLength: 0,
                        select: function( event, ui ) {
                            $(\'[name=id_person]\').val(ui.item.id);
                        }
                    }).focus(function () {
                        $(this).autocomplete("search");
                    });
                });
            </script>
        ';

        $html = $this->FormLibrary->get_form_control_group($answer_html, $field);

        return $html;
    }

    private function get_form_control_id_request($field, $post)
    {
        $id_request = '';
        $request_search = '';
        if(!empty($post->id_request)) :
            $request = $this->report_m->get_request_by_id($post->id_request);
            $request_search = $request->id_demande . ' - ' . $request->nom;
            $id_request = $post->id_request;
        endif;

        $answer_html = '
            <input class="form-control" name="id_request_search" autocomplete="false" value="' . $request_search . '"/>
            <input type="hidden" name="id_request" value="' . $id_request . '"/>
            <script>
                $(\'[name="id_request_search"]\').on(\'keyup\', function() {
                    $(\'[name=id_request]\').val(\'\');
                    $(this).html(\'' . fontawesome('spinner') . '\');
                    const search = $(this).val();
                    $(this).autocomplete({
                        source: function(request, response) {
                            const url = "' . base_url('report/request/linked/get') . '";
                            let data = {};
                            data.search = search;
                            data.id_person = $(\'[name="id_person"]\').val();
                            $.post(url, data, function(result) {
                                result = JSON.parse(result);
                                $(this).html(\'\');
                                response(result);
                            });
                        },
                        minLength: 0,
                        select: function( event, ui ) {
                            $(\'[name=id_request]\').val(ui.item.id);
                        }
                    }).focus(function () {
                        $(this).autocomplete("search");
                    });
                });
            </script>
        ';

        $html = $this->FormLibrary->get_form_control_group($answer_html, $field);

        return $html;
    }

    public function get_file_preview_by_id($id_file)
    {
        $data = (object) [];
        $data->id_file = $id_file;
        $data->url = base_url('block/file/preview/get/' . $id_file);

        return view('Report\block/preview', (array) $data);
    }

}