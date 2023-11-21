<?php

namespace Company\Libraries;

use Api\Libraries\GravityFormApiLibrary;
use Base\Libraries\BaseLibrary;

class ExternLibrary extends BaseLibrary
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function CronMinutes15()
    {
        debug('----------- start module Company - cron 15 minutes ---------------');
        $this->GravityToCrm();
        debug('----------- end module Company - cron 15 minutes ---------------');
    }

    public function GravityToCrm()
    {
        $GravityLibrary = new GravityFormApiLibrary();

        $id_form = '9';
        $fields_crm = [
            'address_city', 
            'address_pc', 
            'address_street', 
            'bce', 
            'comment',
            'contact_email', 
            'contact_lastname', 
            'contact_name', 
            'contact_phone', 
            'id_juridic_form', 
            'id_lang', 
            'ids_contact_schedule', 
            'label', 
            'website', 
        ];
        $gf_datas = $GravityLibrary->api($id_form, $fields_crm);

        if(empty($gf_datas)) return false;

        $imports = $GravityLibrary->ImportDatas($this->t_deposit, $gf_datas);

        debug(count($imports) . ' nouvel(s) import(s)');
        debug($imports);
    }
}