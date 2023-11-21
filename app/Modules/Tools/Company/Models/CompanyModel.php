<?php

namespace Company\Models;

use Base\Models\BaseModel;
use Components\Libraries\FileLibrary;
use Components\Libraries\FormLibrary;
use Components\Libraries\ListLibrary;
use DataView\Libraries\DataViewConstructor;
use Tesorus\Libraries\TesorusLibrary;

class CompanyModel extends BaseModel 
{   
    protected $allowedFields;
	protected $fields;
	protected $returnType = 'object';
	protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->table = $this->t_company;
        $this->primaryKey = get_primary_key($this->table);

        $this->FileLibrary = new FileLibrary();
        $this->FormLibrary = new FormLibrary(__NAMESPACE__);
        $this->ListLibrary = new ListLibrary(__NAMESPACE__);
    }

    // public function translation_update($post)
    // {
    //     $post = (object) $post;
    //     foreach([$this->t_translation, $this->t_fe_translation] as $table) :
    //         foreach($post->$table as $ref=>$label) :
    //             if(!empty($ref) && is_array($label)) :
    //                 $label = (object) $label;
    //                 $data = (object) [];
    //                 $data->ref = $ref;
    //                 if(isset($label->label_fr)) $data->label_fr = $label->label_fr;
    //                 if(isset($label->label_nl)) $data->label_nl = $label->label_nl;
    //                 $this->db->replace($this->t_translation, $data);
    //             endif;
    //         endforeach;
    //         $this->tamo_translate->refresh_session($table);
    //     endforeach;
    // }


    public function CompaniesExport()
    {
        $TesorusLibrary = new TesorusLibrary();
        // $this->load->helper('file');
        // $this->load->library('ListLibrary');
        // $this->load->add_package_path(APPPATH . "modules/fe");
        // $this->load->library('feature_library');       

        $q = $this->db->table($this->t_company);
        $q->orderBy('label');

        $companies = database_decode($q->get()->getResult());

        $datas = [];
        foreach($companies as $company) :
            $data = (object) [];
            foreach($company as $key=>$value) :
                switch($key) :
                    case 'created_at' :
                    case 'import_datetime' :
                    case 'updated_at' :
                        $data->$key = date('d/m/y - H:i', strtotime($value));
                    break;

                    case 'id_contact_source' :
                    case 'id_juridic_form' :
                    case 'id_lang' :
                    case 'id_status' :
                        $data->$key = $this->ListLibrary->get_selected_label($value, $key);
                    break;

                    case 'ids_access_prof' :
                    case 'ids_build_circular' :
                    case 'ids_building_type' :
                    case 'ids_contact_type' :
                    case 'ids_durability_label' :
                    case 'ids_ecological_dim' :
                        $array = $this->ListLibrary->get_selected_labels($value, $key);
                        $data->$key = !empty($array) ? implode(PHP_EOL, $array) : null;
                    break;

                    case 'ids_contact_schedule' :
                    break;

                    case 'ids_road_eco_impact' :
                    case 'ids_road_work' :
                        if(empty((array) $value)) :
                            $data->$key = null;
                        else :
                            preg_match('/^ids_road_(\w+)/', $key, $matches);
                            $road_name = $matches[1];
                            $array = [];
                            // debug($value);
                            foreach($value as $val) :
                                $array[] = $TesorusLibrary->get_path_by_id_road($road_name, $val);
                            endforeach;
                            $data->$key = !empty($array) ? implode(PHP_EOL, $array) : null;    
                        endif;
                    break;

                    case 'id_company' :
                    case 'id_file_lang' :
                    case 'id_langage' :
                    case 'ids_file' :
                    case 'ids_road_eco_impact_text' :
                    case 'ids_use_bio_insulation' :
                    case 'ids_use_bio_insulation_text' :
                    break;

                    case 'is_ccce' :
                    case 'is_code_practice' :
                    case 'is_general_enterprise' :
                    case 'is_group_buying' :
                    case 'is_interest_workshop_renovation' :
                    case 'is_nbn_s_01_400_1' :
                        if(isset($value)) :
                            if($value==1) :
                                $data->$key = 'Oui';
                            elseif($value==0) :
                                $data->$key = 'Non';
                            endif;
                        endif;
                    break;

                    case 'id_user' :
                    case 'updated_by' :
                        $data->$key = sessionUser($value) ? sessionUser($value)->username : '';
                    break;
                    default :
                        $data->$key = $company->$key;
                    break;
                endswitch;
            endforeach;
            $datas[] = $data;
        endforeach;
        // debugd($datas);
        return $datas;
    }

    public function CompanyDelete($id_company)
    {
        $this->db->table($this->t_company)->where('id_company', $id_company)->delete();
    }

    public function CompanyModelData($order=null, $request=null)
    {
        $DataView = new DataViewConstructor();

        $this->select("
            $this->t_company.*,
            $this->t_list_lang.label as langage,
            $this->t_user.prenom as user_name,
            $this->t_user.nom as user_lastname,
        ");
        $this->join($this->t_list_lang, "$this->t_list_lang.id = $this->t_company.id_langage", 'left');
        $this->join($this->t_user, "$this->t_user.id = $this->t_company.updated_by", 'left');

        if(!empty($request) && !empty($request->getGet('itemSearch')) && !empty(trim($request->getGet('itemSearch')))) :
            $fieldsSearch = array(
                "$this->t_company.label",
                "$this->t_user.nom",
                "$this->t_user.prenom",
            );
            $DataView->setQuerySearch($this, $fieldsSearch);
        endif;

        $order = $DataView->GetOrderFromRequest($order, $request);
        if(!empty($order[0])) $this->orderBy($order[0], $order[1]);
        else $this->orderBy('updated_at', 'desc');

        return $this;
    }

    public function CompaniesPagerGet()
    {
        return $this->pager;
    }

    public function CompaniesGet($order=null, $request=null)
    {
        $modeldata = $this->CompanyModelData($order, $request);
        if(!empty($no_pager) || (!empty($request->getGet('per_page')) && $request->getGet('per_page')=='all')) :
            $companies = database_decode($modeldata->find());
        else :
            $per_page = !empty($request->getGet('per_page')) ? $request->getGet('per_page') : 20;
            $companies = database_decode($modeldata->paginate($per_page));
        endif;

        if(empty($companies)) return null;
        $lists = $this->ListLibrary->get_lists(false);

        $i = 0;
        foreach($companies as $company) :
            $companies[$i] = $this->CompanyGetCalculatedFields($company);
            // foreach($company as $ref=>$value) :
            //     if(!empty($lists->$ref) && $ref!='ids_contact_schedule') :
            //         $companies[$i]->$ref = $this->ListLibrary->get_selected_object($value, $ref);
            //     endif;
            // endforeach;
            $i++;
        endforeach;

        return database_decode($companies);
    }

    public function CompanyGetCalculatedFields($company)
    {
        $lists = $this->ListLibrary->get_lists(false);
        foreach($company as $ref=>$value) :
            if(!empty($lists->$ref) && $ref!='ids_contact_schedule') :
                $company->$ref = $this->ListLibrary->get_selected_object($value, $ref);
            endif;
        endforeach;

        return $company;
    }

    public function CompanyGet($id_company)
    {
        $company = $this->CompanyModelData()->where('id_company', $id_company)->get()->getRow();

        if(!empty($company->ids_file)) :
            $company->files = $this->FileLibrary->FilesGet($company->ids_file);
            $company->docs = $this->FileLibrary->DocsGet($company->ids_file);
            $company->imgs = $this->FileLibrary->ImgsGet($company->ids_file);
        endif;

        return database_decode($company);
    }

    public function CompanySave($data, $id_company=null)
    {
        $data = $this->FileLibrary->FilesUpload($data);
        $data->updated_by = session('loggedUserId');

        if(!empty($id_company)) :
            $this->db->table($this->t_company)->set(database_encode($this->t_company, $data))->where('id_company', $id_company)->update();
        else :
            $data->created_by = session('loggedUserId');
            $this->db->table($this->t_company)->set(database_encode($this->t_company, $data))->insert();
            $id_company = $this->db->insertID();
        endif;

        return $id_company;
    }

    // public function CompanyUpdate($id_company, $data)
    // {
    //     $data = $this->FileLibrary->FilesUpload($data);
    //     $data->updated_by = session('loggedUserId');
    //     $data->updated_at = date('Y-m-d H:i:s');

    //     $this->db->table($this->t_company)->set(database_encode($this->t_company, $data))->where('id_company', $id_company)->update();

    //     return $id_company;
    // }

    // public function CompanyInsert($data)
    // {
    //     $data = $this->FileLibrary->FilesUpload($data);
    //     $data->created_by = session('loggedUserId');
    //     $data->updated_by = session('loggedUserId');

    //     $this->db->table($this->t_company)->set(database_encode($this->t_company, $data))->insert($data);
    //     $id_company = $this->db->insertID();

    //     return $id_company;
    // }

    public function company_clean_for_database($post)
    {
        $is_array = 0;
        if(is_array($post)) :
            $is_array = 1;
            $post = (object) $post;
        endif;
        foreach($post as $key=>$value) if(is_string($value)) $post->$key = trim($value);
        if(isset($post->bce)) $post->bce = preg_replace('/[^0-9]/', '', $post->bce);
        if(!empty($post->ids_road_eco_impact_text)) :
            $TesorusLibrary = new TesorusLibrary();
            $post->ids_road_eco_impact_text = $TesorusLibrary->clean_road_text_for_database($post->ids_road_eco_impact_text);
        endif;
        if($is_array == 1) $post = (array) $post;
        
        return $post;
    }
}
