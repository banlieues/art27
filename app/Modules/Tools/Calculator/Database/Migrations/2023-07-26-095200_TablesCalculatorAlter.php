<?php

namespace Calculator\Database\Migrations;

use Components\Libraries\MigrationLibrary;
use Base\Database\BaseMigration;

class TablesCalculatorAlter extends BaseMigration
{
    public function __construct() 
    {
        parent::__construct(__NAMESPACE__);
    }
    
    public function up() 
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->TablePriceAlter();
        // $this->TableEstimationAlter();
        $this->TablePriceOriginUpdate();
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    private function TablePriceOriginUpdate()
    {
        if(!$this->db->tableExists($this->t_l_price_origin)) return false;
        if($this->db->table($this->t_l_price_origin)->countAll()!=2) return false;

        $this->db->table($this->t_l_price_origin)->set('label', 'Devis Homegrade')->where('label', 'Homegrade')->update();
        dbdebug();

        $this->db->table($this->t_l_price_origin)->set('rank', 4)->where('label', 'Autre')->update();
        dbdebug();

        $datas = [
            [
                'label' => 'Devis Réseau Habitat',
                'rank' => 2,
                'updated_by' => 0,
                'created_by' => 0,
            ],
            [
                'label' => 'UPA',
                'rank' => 3,
                'updated_by' => 0,
                'created_by' => 0,
            ],
        ];
        foreach($datas as $data) :
            $this->db->table($this->t_l_price_origin)
                ->set(database_encode($this->t_l_price_origin, $data))
                ->insert();
            dbdebug();
        endforeach;
    }

    // private function TableEstimationAlter()
    // {
    //     if($this->db->tableExists($this->t_estimation)):
    //         if(!$this->db->fieldExists('isActive', $this->t_estimation)) :
    //             $fields = [
    //                 'isActive' => [ 'type' => 'tinyint', 'null' => true, ],
    //             ];
    //             $this->forge->addColumn($this->t_estimation, $fields);
    //             dbdebug();
    //         endif;
    //     endif;
    // }

    private function TablePriceAlter()
    {
        if($this->db->tableExists($this->t_price)):
            if(!$this->db->fieldExists('is_ignored', $this->t_price)) :
                $fields = [
                    'is_ignored' => [ 'type' => 'tinyint', 'null' => true, ],
                ];
                $this->forge->addColumn($this->t_price, $fields); dbdebug();
            endif;
            if(!$this->db->fieldExists('comment', $this->t_price)) :
                if($this->db->fieldExists('notes', $this->t_price)) :
                    $fields = [
                        'notes' => [ 'name' => 'comment', 'type' => 'text', 'null' => true, ],
                    ];
                    $this->forge->modifyColumn($this->t_price, $fields);
                    dbdebug();
                else :
                    $fields = [
                        'comment' => [ 'type' => 'text', 'null' => true, ],
                    ];
                    $this->forge->addColumn($this->t_price, $fields); dbdebug();    
                endif;
            endif;
            if(!$this->db->fieldExists('validity_month', $this->t_price)) :
                $fields = [
                    'validity_month' => [ 'type' => 'int', 'null' => true, ],
                ];
                $this->forge->addColumn($this->t_price, $fields); dbdebug();
            endif;
        endif;
    }
}



