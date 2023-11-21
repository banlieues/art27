<?php

namespace Demande\Database\Migrations;

use Base\Database\BaseMigration;

class TableFieldUser_231116 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableField();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    public function TableField()
    {
        foreach([
            'demande_utilisateur', 
            'demande_utilisateur_2', 
            'utilisateur_demande',
            'utilisateur_demande_2',
            ] as $index) :
            $this->db->table('ban_fields')->set('label_list', 'prenom,nom')->set('order_list', 'prenom')->where('field_index', $index)->update();
            dbdebug();
        endforeach;
        $this->db->table('ban_fields')->set('label_list', 'prenom,nom')->set('order_list', 'prenom')->where('label_list', 'nom,prenom')->update();
        dbdebug();
    }

}