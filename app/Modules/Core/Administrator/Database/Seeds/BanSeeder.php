<?php

namespace Administrator\Database\Seeds;

use Base\Database\BaseSeeder;

class BanSeeder extends BaseSeeder
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function run()
    {
        $tables = [
            $this->t_avatar_settings, 
            $this->t_component_contact, 
            $this->t_component_contact_field,
            $this->t_contact, 
            $this->t_entity, 
            $this->t_field,
            $this->t_user, 
            $this->t_contact_user, 
            $this->t_user_profile
        ];
        foreach($tables as $tablename) :

            $builder = $this->db->table($tablename);

            $nb = $builder->countAll();
            if($nb>0) continue;

            // $this->database_l->export_table_datas('settings_cropper', 'h4', $tablename); die;

            $file = APPPATH . 'Modules/' . $this->module . '/Database/Init/d_' . $tablename . '.json';
            if(!file_exists($file)) continue;

            $datas = json_decode(file_get_contents($file));
            foreach($datas as $data) $builder->insert((array) $data);
        endforeach;
    }
}