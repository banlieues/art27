<?php

namespace Api\Libraries;

use Api\Libraries\BaseApiLibrary;
use Custom\Config\Key;

class GravityFormApiLibrary extends BaseApiLibrary
{
    public function __construct()
    {
        parent::__construct();
    }

    public function api($id_form, $fields_crm)
    {
        $fields_gf = $this->get_form_fields($id_form);
        $field_list_gf = $this->get_form_field_list($fields_gf);
        $fields_missing = $this->get_form_fields_missing($field_list_gf, $fields_crm);
        $datas = [];
        if(empty($fields_missing)) :
            $fields_linked = $this->get_form_fields_linked($fields_gf, $fields_crm);
            $datas = $this->get_form_entries($id_form, $fields_linked);
        else :
            $message = "
                Échec lors de l'import des nouvelles données dans le CRM. <br>
                Les champs { " . implode(', ', $fields_missing) . " } sont manquants pour importer correctement les données dans le CRM. <br>
                Veuillez les introduire dans le formulaire Gravity Forms correspondant pour permettre l'importation des données.
            ";
            debug($message);
            $session = session();
            $session->setFlashdata('warning', $message);
        endif;
        return $datas;
    }

    private function get_form_entries($id_form, $fields_linked)
    {
        $route    = 'forms/' . $id_form . '/entries?paging[page_size]=9999999999&sorting[key]=id&sorting[direction]=ASC&sorting[is_numeric]=true';
        $data = $this->import_datas($route);
        $entries = $data->entries;

        $ids_gf = [];
        foreach($fields_linked as $id_gf=>$field) $ids_gf[] = $id_gf;

        $datas = [];
        foreach($entries as $entry) :
            if($entry->status == 'active') :
                $data = (object) [];
                foreach($entry as $key=>$value) :
                    if(in_array($key, ['id', 'date_created'])) :
                        $data->{'gf_' . $key} = $value; 
                        continue;
                    endif;

                    $id_gf_array = explode('.', $key);
                    $id_gf = $id_gf_array[0];
                    if(in_array($key, $ids_gf) && !empty($value)) :
                        $label = $fields_linked->$key;
                        $data->$label = $value;
                    elseif(in_array($id_gf, $ids_gf) && !empty($value)) :
                        $label = $fields_linked->$id_gf;
                        if(count($id_gf_array)>1) :
                            $data->$label[] = $value;
                        elseif(count($id_gf_array)==1) :
                            $data->$label = $value;
                        endif;
                    endif;
                endforeach;
                $datas[] = $data;
            endif;
        endforeach;

        return $datas;
    }

    public function get_form_fields($id_form)
    {
        $route    = 'forms/' . $id_form;
        $data = $this->import_datas($route);

        $fields = $data->fields;

        return $fields;
    }

    private function get_form_fields_missing($fields_gf, $fields_crm)
    {
        $fields_missing = array_diff($fields_crm, $fields_gf);

        return $fields_missing;
    }

    public function get_form_fields_linked($fields, $fields_crm)
    {
        $fields_linked = (object) [];
        $ids_gf = [];
        foreach($fields as $field) :
            if(isset($field->adminLabel) && in_array($field->adminLabel, $fields_crm)) :
                $id_gf = $field->id;
                $fields_linked->$id_gf = $field->adminLabel;
            elseif(!empty($field->inputs)) :
                foreach($field->inputs as $input) :
                    if(!empty($input->name) && in_array($input->name, $fields_crm)) :
                        $id_gf = $input->id;
                        $fields_linked->$id_gf = $input->name;
                        continue;
                    endif;
                endforeach;
            endif;

            // remove when id_lang field is added in gravityforms
            if(isset($field->adminLabel) && $field->adminLabel=='id_lang') :
                $id_gf = $field->id;
                $fields_linked->$id_gf = 'id_lang';
            elseif(!empty($field->inputs)) :
                foreach($field->inputs as $input) :
                    if(!empty($input->name) && $input->name=='id_lang') :
                        $id_gf = $input->id;
                        $fields_linked->$id_gf = 'id_lang';
                        continue;
                    endif;
                endforeach;
            endif;
            // end remove

        endforeach;

        return $fields_linked;
    }

    public function get_form_field_list($fields_gf)
    {
        $field_list_gf = [];
        foreach($fields_gf as $field) :
            if(!empty($field->adminLabel)) :
                $field_list_gf[] = $field->adminLabel;
            elseif(!empty($field->inputs)) :
                foreach($field->inputs as $input) :
                    if(!empty($input->name)) :
                        $field_list_gf[] = $input->name;
                        continue;
                    endif;
                endforeach;
            endif;
        endforeach;

        return $field_list_gf;
    }

    private function import_datas($route)
    {
        $key = new Key();

        $param['url'] = $key->WordpressUrl . '/wp-json/gf/v2/' . $route;
        $param['header'] = ['Content-Type: application/json;charset=utf-8'];
        $param['username'] = $key->GravityformsUsername;
        $param['password'] = $key->GravityformsPassword;
        $data = $this->curl($param);

        return object_convert($data);
    }

    private function get_signature( $string, $private_key ) {
        $hash = hash_hmac( 'sha1', $string, $private_key, true );
        $sig = rawurlencode( base64_encode( $hash ) );
        return $sig;
    }

    public function ImportDatas($table, $gf_datas)
    {
        $crm_datas = $this->GetCrmDatas($table);
        $this->db->transStart();
        $imports = $this->InsertNewDatas($table, $gf_datas, $crm_datas);
        $i = count($imports);
        if ($this->db->transComplete() == FALSE) :
            if($i>0) :
                $messagetype = 'warning';
                $message = "Échec lors de l'import de nouvelles données dans le CRM.";
                $this->session->setFlashdata($messagetype, $message);
            endif;
        else :
            $messagetype = 'success';
            if($i>0) :
                if($i > 1) $message = "$i nouveaux imports ont été enregistrés dans le CRM.";
                else $message = "Un nouvel import a été enregistré dans le CRM.";
                $this->session->setFlashdata($messagetype, $message);
            endif;
        endif;

        return $imports;
    }

    private function GetCrmDatas($table)
    {
        $deposits_db = $this->db->table($table)->get()->getResult();
        $gf_db = [];
        foreach($deposits_db as $deposit_db) :
            if(
                !empty($deposit_db->gf_id) && 
                isset($deposit_db->gf_date_created) && 
                $deposit_db->gf_date_created!='0000-00-00 00:00:00'
                )
                $gf_db[] = (object) ['gf_id'=>$deposit_db->gf_id, 'gf_date_created'=>$deposit_db->gf_date_created];
        endforeach;

        return $gf_db;
    }
    
    private function InsertNewDatas($table, $deposits, $gf_db)
    {
        $imports = [];
        foreach($deposits as $deposit) :
            $gf = (object) ['gf_id'=>$deposit->gf_id, 'gf_date_created'=>$deposit->gf_date_created];
            if(!in_array($gf, $gf_db)) :
                $post = $this->FormatingData($deposit);
                $imports[] = $post;
                $this->db->table($table)->set(database_encode($table, $post))->insert();
            endif;
        endforeach;

        return $imports;
    }

    private function FormatingData($post)
    {
        $is_array = 0;
        if(is_array($post)) :
            $is_array = 1;
            $post = (object) $post;
        endif;
        foreach($post as $key=>$value) :
            // numeric field
            if(in_array($key, ['contact_phone', 'address_pc', 'bce'])) :
                $post->$key = preg_replace('/[^0-9]/', '', $value);
                // preg_match('/\d+/', $value, $matches);
                // if(!empty($matches)) $post->$key = implode('', $matches);
                // $post->$key = str_replace(['(', ')', ' ', '/', '-', ',' , ';', ':'], '', $value);
                // $post->$key = preg_replace('/[a-zA-Z]+/', '', $value);
            elseif($key == 'urls_file') :
                if(is_string($value) && !is_json($value)) $post->$key = '[' . $value . ']';
                else $post->$key = $value;
            elseif($key == 'ids_contact_schedule') :
                $post->$key = $this->ConvertScheduleData($value);
            else :
                if(is_string($post->$key)) $post->$key = trim($value);
                elseif(is_array($value)) $post->$key = array_filter($value);
            endif;
        endforeach;
        // $post->ids_contact_type = [1];
        // if(isset($post->ids_contact_schedule)) $post->ids_contact_schedule = $this->deposit_import_ids_contact_schedule($post->ids_contact_schedule);

        if($is_array == 1) $post = (array) $post;
        
        return $post;
    }

    private function ConvertScheduleData($value_gf)
    {
        $value_gf = database_decode($value_gf);

        $value_crm = [];
        foreach($value_gf as $v) :
            if(is_numeric($v)) :
                $value_crm[] = $v;
            elseif(is_array($v)) :
                $value_crm = array_merge($v, $value_crm);
            endif;
        endforeach;

        asort($value_crm);

        return array_values(array_unique(array_filter($value_crm)));
    }
}