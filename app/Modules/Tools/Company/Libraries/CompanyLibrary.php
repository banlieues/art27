<?php

namespace Company\Libraries;

use Base\Libraries\BaseLibrary;
use Company\Config\Globals;
use Company\Models\DepositModel;
use Components\Libraries\FormLibrary;
use Components\Libraries\ListLibrary;

class CompanyLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->DepositModel = new DepositModel();
        $this->FormLibrary = new FormLibrary(__NAMESPACE__);
        $this->ListLibrary = new ListLibrary(__NAMESPACE__);
    }

    public function deposit_info_titles_get()
    {
        $fields = json_decode(file_get_contents($this->path . 'Config/Json/deposit/form.json'));
        $titles = $this->FormLibrary->get_info_titles($fields);

        return $titles;
    }

    public function DepositGetInfo($id_deposit)
    {
        $fields = json_decode(file_get_contents($this->path . 'Config/Json/deposit/form.json'));
        $deposit = $this->DepositModel->DepositGet($id_deposit);
        $companies = $this->DepositModel->DepositDublonsGet($id_deposit);
        array_unshift($companies, $deposit);

        foreach($fields as $ref=>$field) :
            $list = $this->ListLibrary->get_list_by_ref($ref);
            foreach($companies as $company) :
                $object = (object) ['value' => null, 'label' => null];
                if(isset($company->$ref)) :
                    if($ref == 'ids_contact_schedule') :
                        $object = $this->get_selected_object_schedule($company->$ref);
                    elseif(!empty($list)) :
                        $object = $this->ListLibrary->get_selected_object($company->$ref, $ref);
                    else :
                        $object->value = $company->$ref;
                        $object->label = $company->$ref;
                    endif;
                endif;
                $company->$ref = $object;           
            endforeach;
        endforeach;

        return $companies;
    }

    private function get_selected_object_schedule($ids)
    {
        $field = (object) [];
        $field->day = $this->ListLibrary->get_list_by_ref('ids_contact_schedule_day');
        $field->time = $this->ListLibrary->get_list_by_ref('ids_contact_schedule_time');
        $field->clock = $this->ListLibrary->get_list_by_ref('ids_contact_schedule_clock');
        $field->list = $this->ListLibrary->get_list_by_ref('ids_contact_schedule');
        $field->ids_contact_schedule = $ids;

        $data = (object) [];
        $data->field = $field;
        $data->ids_contact_schedule = $ids;

        $object = (object) [];
        $object->value = json_encode($ids);
        $object->label = view('Company\deposit-view-ids_contact_schedule', (array) $data);

        return $object;
    }

    private function get_info_ids_contact_schedule_data($field, $post)
    {
        $ref = 'ids_contact_schedule';
        $data['field'] = $field;
        $data['company'] = (object) [];
        $data['company']->$ref = !empty($post->$ref) ? $post->$ref : null;

        $info = (object) [];
        $info->answer = view('Company\deposit-view-ids_contact_schedule', $data);
        $info->value = !empty($post->$ref) ? json_encode($post->$ref, JSON_NUMERIC_CHECK) : 'null';

        return $info;
    }

    private function get_info_ids_contact_schedule($field, $new_post, $dublons_post)
    {
        $ref = 'ids_contact_schedule';
        $label = $field->label_fr;
        $new = $this->get_info_ids_contact_schedule_data($field, $new_post);

        $html = '';
        if(empty($dublons_post)) :
            $html = $this->FormLibrary->get_info_html_alone($label, $new);
        else :
            $dublons = [];
            foreach($dublons_post as $post) :
                $dublons[] = $this->get_info_ids_contact_schedule_data($field, $post);
            endforeach;
            $html = $this->FormLibrary->get_info_html_dublon($ref, $label, $new, $dublons);
        endif;

        return $html;
    }

    public function get_form_controls($init, $post=null)
    {
        $fields = json_decode(file_get_contents($this->path . 'Config/Json/company/form.json'));

        $controls = (object) [];
        $except = ['ids_contact_schedule'];
        foreach($fields as $ref=>$field) :
            if(!in_array($ref, $except)) :
                $field = $this->FormLibrary->get_form_control_field($field, $ref, $this->module, $init->form_type);
                $list = $this->ListLibrary->get_list_by_ref($ref);
                $controls->$ref = $this->FormLibrary->get_form_control($field, $post, $list);
            else :
                $field->ref = $ref;
                $value = isset($post->$ref) ? $post->$ref : null;
                if($ref == 'ids_contact_schedule') :
                    $list = $this->ListLibrary->get_list_by_ref($ref);
                    $list_day = $this->ListLibrary->get_list_by_ref($ref . '_day');
                    $list_time = $this->ListLibrary->get_list_by_ref($ref . '_time');
                    $list_clock = $this->ListLibrary->get_list_by_ref($ref . '_clock');
                    $controls->$ref = $this->get_form_control_ids_contact_schedule($field, $value, $list, $list_day, $list_time, $list_clock);
                endif;
            endif;
        endforeach;

        return $controls;
    }

    private function get_form_control_ids_contact_schedule($field, $value, $list, $list_day, $list_time, $list_clock)
    {
        foreach(['day', 'time', 'clock'] as $elem) :
            $i = 0;
            foreach(${'list_' . $elem} as $el) :
                $field->$elem[$i] = (object) [];
                $field->$elem[$i]->id = $el->id;
                $field->$elem[$i]->label = t($el->label_fr, __NAMESPACE__);
                $i++;
            endforeach;
        endforeach;
        $field->list = $list;

        $data = (object) [];
        $data->field = $field;
        $data->company = (object) [];
        $data->company->ids_contact_schedule = isset($value) ? $value : null;
        $answer_html = view('Company\company-view-ids_contact_schedule', (array) $data);

        $html = $this->FormLibrary->get_form_control_group($answer_html, $field);

        return $html;
    }
}