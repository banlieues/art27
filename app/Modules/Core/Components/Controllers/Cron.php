<?php

namespace Components\Controllers;

use Base\Controllers\BaseController;
use Company\Libraries\ExternLibrary as CompanyExternLibrary;
use DemandeWeb\Libraries\ExternLibrary as DemandeWebExternLibrary;

class Cron extends BaseController
{
    public function __construct()
    {
        parent::__construct(__NAMESPACE__);
    }

    public function Minutes15()
    {
        $Company = new CompanyExternLibrary();
        $Company->CronMinutes15();
        $DemandeWeb = new DemandeWebExternLibrary();
        $DemandeWeb->CronMinutes15();
    }
}