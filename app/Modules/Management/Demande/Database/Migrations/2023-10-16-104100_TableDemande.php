<?php

namespace Demande\Database\Migrations;

use Base\Database\BaseMigration;

class TableDemande_231016 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->t_field = 'ban_fields';
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableDemande();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    public function TableDemande()
    {
        if(!$this->db->fieldExists('demande_thematique', $this->t_demande)) :
            $fields = [
                'demande_thematique' => [ 'type' => 'text', 'null' => true, ],
            ];
            $this->forge->addColumn($this->t_demande, $fields);
            dbdebug();
        endif;
    }

}