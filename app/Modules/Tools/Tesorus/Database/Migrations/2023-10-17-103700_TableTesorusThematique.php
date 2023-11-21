<?php

namespace Tesorus\Database\Migrations;

use Base\Database\BaseMigration;
use Components\Libraries\MigrationLibrary;

class TableTesorusThematique_231017 extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);

        $this->MigrationLibrary = new MigrationLibrary();
    }

    public function up()
    {
        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $this->MigrationLibrary->TableTesorusCreate('tesorus_road_thematique');
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }
}