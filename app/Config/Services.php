<?php

namespace Config;

use Autorisation\Models\AutorisationModel;
use CodeIgniter\Config\BaseService;
use Contact\Controllers\Contact;
use Dashboard\Libraries\Ldashboard;
use DataView\Models\DataViewConstructorModel;
use DataView\Libraries\DataViewConstructor;
use Historique\Controllers\Historique;
use Outlook\Models\OutlookModel;
use Outlook\Controllers\Outlook;
use Messagerie\Controllers\Messagerie;
/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    public static function dataViewConstructorModel($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('dataViewConstructorModel');
        }

        return new DataViewConstructorModel();
    }



    public static function dataViewConstructor($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('dataViewConstructor');
        }

        return new DataViewConstructor();
    }

    public static function contact($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('contact');
        }

        return new Contact();
    }


    public static function historique($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('historique');
        }

        return new Historique();
    }

    public static function autorisationModel($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('autorisationModel');
        }

        return new AutorisationModel();
    }

    public static function outlookModel($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('outlookModel');
        }

        return new OutlookModel();
    }

    public static function outlook($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('outlook');
        }

        return new Outlook();
    }

    public static function ldashboard($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('ldashboard');
        }

        return new Ldashboard();
    }


    public static function messagerie($getShared = true)
    {
        if ($getShared) {
            return self::getSharedInstance('messagerie');
        }

        return new Messagerie();
    }
 
}
