<?php

namespace Demande\Database\Migrations;

use Base\Database\BaseMigration;

class TableField_230922 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->t_field = 'ban_fields';
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableField();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    public function TableField()
    {
        $fields = $this->db->table($this->t_field)->whereIn('field_index', ['demande_contact_duree', 'demande_is_premier_contact', 'demande_origine'])->get()->getResult();
        foreach($fields as $field) :
            $this->db->table($this->t_field)->set('rule', 'trim')->where('field_index', $field->field_index)->update();
            dbdebug();
        endforeach;
    }

}