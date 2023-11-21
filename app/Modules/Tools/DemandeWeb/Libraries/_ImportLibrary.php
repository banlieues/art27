<?php

namespace DemandeWeb\Libraries;

use Components\Libraries\MigrationLibrary;
use Base\Libraries\BaseLibrary;

class ImportLibrary extends BaseLibrary 
{   
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->forge = \Config\Database::forge();
    }

    public function HomegradeToH4()
    {
        $this->HomegradeToH4TableDeposit();
        $this->HomegradeToH4TableListMoyenContact();
    }

    private function HomegradeToH4TableDeposit()
    {
        if(!$this->db->tableExists($this->t_deposit)) :
            debug("La table $this->t_deposit n'existe pas.");
            return false;
        endif;

        if(!$this->db->fieldExists('id_user_on_work', $this->t_deposit)) :
            $fields = [
                'id_user_on_work' => [
                    'type' => 'TINYINT',
                    'null' => true,
                ],
            ];
            $this->forge->addColumn($this->t_deposit, $fields);
            dbdebug();
        endif;
    }

    private function HomegradeToH4TableListMoyenContact()
    {
        $row = $this->db->table($this->t_list_moyen_contact)->where('id', 14)->get()->getRow();
        if(empty((array) $row)) :
            $this->db->table($this->t_list_moyen_contact)->set(['label' => 'Rénolution', 'rank' => 120])->insert();
            dbdebug();
            $id = $this->db->InsertID();
            if($id != 14) :
                $this->db->table($this->t_list_moyen_contact)->set(['id' => 14])->where('id', $id)->update();
                dbdebug();
            endif;
        endif;
    }
}
