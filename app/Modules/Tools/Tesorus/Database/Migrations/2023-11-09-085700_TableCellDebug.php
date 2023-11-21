<?php

namespace Tesorus\Database\Migrations;

use Base\Database\BaseMigration;

class TableCellDebug_231109 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableCellDebug();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableCellDebug()
    {
        $cells_empty_ref = $this->db->table($this->t_cell)->where('reference is null')->orWhere('reference', '')->get()->getResult();
        // debugd($cells_empty_ref);
        if(!empty($cells_empty_ref)) :
            foreach($cells_empty_ref as $cell) :
                $this->db->table($this->t_cell)
                    ->set('reference', str_replace(' ', '_', mb_strtolower(remove_accents(trim($cell->label_fr)))))
                    ->where('id_cell', $cell->id_cell)
                    ->update();
                dbdebug();
            endforeach;
        endif;
    }
}