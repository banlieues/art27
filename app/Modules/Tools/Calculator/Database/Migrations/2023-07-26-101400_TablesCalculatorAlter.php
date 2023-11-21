<?php

namespace Calculator\Database\Migrations;

use Base\Database\BaseMigration;

class TablesCalculatorAlter_230726 extends BaseMigration
{
    public function __construct() 
    {
        parent::__construct(__NAMESPACE__);
    }
    
    public function up() 
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TableGroupAlter();
        $this->TableRoadAlter();
        // $this->TableEstimationAlter();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TableGroupAlter()
    {
        if(!$this->db->fieldExists('measure', $this->t_group)) :
            $fields = [
                'measure' => [ 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            ];
            $this->forge->addColumn($this->t_group, $fields);
            dbdebug();
        endif;
        if(!$this->db->fieldExists('comment', $this->t_group)) :
            $fields = [
                'comment' => [ 'type' => 'text', 'null' => true, ],
            ];
            $this->forge->addColumn($this->t_group, $fields);
            dbdebug();
        endif;
    }

    private function TableRoadAlter()
    {
        if(!$this->db->fieldExists('comment', $this->t_road)) :
            $fields = [
                'comment' => [ 'type' => 'text', 'null' => true, ],
            ];
            $this->forge->addColumn($this->t_road, $fields);
            dbdebug();
        endif;
        if($this->db->fieldExists('measure', $this->t_road)) :
            $fields = [
                'measure' => [ 'name' => '_measure', 'type' => 'varchar', 'constraint' => 255, 'null' => true, ],
            ];
            $this->forge->modifyColumn($this->t_road, $fields);
            dbdebug();
        endif;
    }

    // private function TableEstimationAlter()
    // {
    //     if($this->db->fieldExists('groupe_de_travaux_rank', $this->t_estimation)) :
    //         $this->forge->dropColumn($this->t_estimation, 'groupe_de_travaux_rank');
    //         dbdebug();
    //     endif;
    // }
}



