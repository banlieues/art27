<?php

namespace Company\Models;

use Base\Models\BaseModel;
use Company\Models\CompanyModel;
use Components\Libraries\FileLibrary;
use Components\Libraries\ListLibrary;
use DataView\Libraries\DataViewConstructor;

class DepositModel extends BaseModel 
{  
    protected $allowedFields;
	protected $fields;
	protected $returnType = 'object';
	protected $useAutoIncrement = true;

    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->table = $this->t_deposit;
        $this->primaryKey = get_primary_key($this->table);

        $this->CompanyModel = new CompanyModel();
        $this->FileLibrary = new FileLibrary();
        $this->ListLibrary = new ListLibrary(__NAMESPACE__);
    }

    public function DepositSetWorker($id_deposit, $id_user=null)
    {
        $this->db->table($this->t_deposit)->set('id_user_on_work', $id_user)
            ->where('id_deposit', $id_deposit)
            ->update();
    }
    
    public function DepositModelData($order=null, $request=null)
    {
        $DataView = new DataViewConstructor();

        $this->select("
            $this->t_deposit.*,
            CONCAT_WS(' ', $this->t_user.prenom, $this->t_user.nom) as user_on_work,
        ");
        $this->join($this->t_user, "$this->t_user.id = $this->t_deposit.id_user_on_work", 'left');

        if(!empty($request) && !empty($request->getGet('itemSearch')) && !empty(trim($request->getGet('itemSearch')))) :
            $fieldsSearch = array(
                "$this->t_deposit.label",
            );
            $DataView->setQuerySearch($this, $fieldsSearch);
        endif;

        $order = $DataView->GetOrderFromRequest($order, $request);
        if(!empty($order[0])) $this->orderBy($order[0], $order[1]);
        else $this->orderBy('gf_date_created', 'desc');

        return $this;
    }

    public function DepositsPagerGet()
    {
        return $this->pager;
    }

    public function DepositsGet($order=null, $request=null)
    {
        $modeldata = $this->DepositModelData($order, $request)->where('is_deleted !=', 1);
        if(!empty($no_pager) || (!empty($request->getGet('per_page')) && $request->getGet('per_page')=='all')) :
            $deposits = $modeldata->find();
        else :
            $per_page = !empty($request->getGet('per_page')) ? $request->getGet('per_page') : 20;
            $deposits = $modeldata->paginate($per_page);
        endif;
        $deposits = database_decode($deposits);
        if(empty($deposits)) return null;

        $lists = $this->ListLibrary->get_lists(false);

        $i = 0;
        foreach($deposits as $deposit) :
            $deposits[$i]->comment = nl2br($deposit->comment);
            foreach($deposit as $ref=>$value) :
                if(!empty($lists->$ref) && $ref!='ids_contact_schedule') :
                    $deposits[$i]->$ref = $this->ListLibrary->get_selected_object($value, $ref);
                endif;
            endforeach;
            $i++;
        endforeach;

        return database_decode($deposits);
    }

    public function DepositInsert($data)
    {
        if(!empty(database_encode($this->t_deposit, $data))) :
            $this->db->table($this->t_deposit)->set(database_encode($this->t_deposit, $data))->insert();
            return database_encode($this->t_deposit, $data);
        endif;
    }

    public function DepositToCompany($id_deposit)
    {
        $deposit = $this->DepositGet($id_deposit);
        $deposit->id_contact_source = 1;

        $id_company = $this->CompanyModel->CompanySave($deposit);

        $this->DepositDelete($id_deposit);

        return $id_company;
    }

    public function DepositDelete($id_deposit)
    {
        // $this->db->where('id_deposit', $id_deposit)->delete($this->t_deposit);
        $data = (object) [];
        $data->is_deleted = 1;
        $this->db->table($this->t_deposit)->set(database_encode($this->t_deposit, $data))->where('id_deposit', $id_deposit)->update();
    }

    public function deposits_get()
    {
        // $deposits = $this->db->table($this->t_deposit)->get()->getResult();
        $deposits = $this->db->table($this->t_deposit)->where('is_deleted', 0)->get()->getResult();
        $datas = [];
        foreach($deposits as $deposit) :
            $deposit->date_create = date('d/m/y - H:i', strtotime($deposit->gf_date_created));
            $datas[] = $deposit;
        endforeach;

        return $datas;
    }

    public function DepositGet($id_deposit)
    {
        $deposit = $this->DepositModelData()->where('id_deposit', $id_deposit)->get()->getRow();

        if(empty((array) $deposit)) return null;

        // $deposit->bce = $this->deposit_bce_add_dot($deposit->bce);

        return database_decode($deposit);
    }

    public function deposit_bce_add_dot($bce)
    {
        $bce1 = substr($bce, 0, 4);
        $bce2 = substr($bce, 4, 3);
        $bce3 = substr($bce, 7, 10);
        $bce = $bce1 . '.' . $bce2 . '.' . $bce3;

        return $bce;
    }

    public function DepositDublonsGet($id_deposit)
    {
        $deposit = $this->DepositGet($id_deposit);

        $q = $this->db->table($this->t_company);
        $q->where("lower(trim($this->t_company.label)) = '" . mb_strtolower(trim($deposit->label)) . "'");
        $dublons = $q->get()->getResult();


        $q = $this->db->table($this->t_company);
        $q->where("trim($this->t_company.website) is not null");
        $q->where("trim($this->t_company.website) !=", "");
        $q->where("trim($this->t_company.website)", trim($deposit->website));
        $dublons = array_merge($dublons, $q->get()->getResult());

        $q = $this->db->table($this->t_company);
        $q->where("trim($this->t_company.bce) is not null");
        $q->where("trim($this->t_company.bce) !=", "");
        $q->where("REGEXP_REPLACE(trim($this->t_company.bce), '[^0-9]+', '')", preg_replace('/[^0-9]+/', '', trim($deposit->bce)));
        $dublons = array_merge($dublons, $q->get()->getResult());
        // dbdebugd();
        $dublons = database_decode(array_values(array_of_objects_unique($dublons)));

        return $dublons;
    }
}
