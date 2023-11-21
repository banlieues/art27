<?php

namespace Administrator\Database\Migrations;

use Base\Database\BaseMigration;

class TablesBanPopulate extends BaseMigration
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function up()
    {
        return false;

        debug('---------- START ' . basename(__FILE__) . ' ----------');
        $seeder = \Config\Database::seeder();
        $seeder->call('\\' . $this->module . '\Database\Seeds\BanSeeder');
        debug('---------- END ' . basename(__FILE__) . ' ----------');
    }

    public function down()
    {
    }
}
