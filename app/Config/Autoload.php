<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTOLOADER CONFIGURATION
 * -------------------------------------------------------------------
 *
 * This file defines the namespaces and class maps so the Autoloader
 * can find the files as needed.
 *
 * NOTE: If you use an identical key in $psr4 or $classmap, then
 * the values in this file will overwrite the framework's values.
 */
class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     * This maps the locations of any namespaces in your application to
     * their location on the file system. These are used by the autoloader
     * to locate files the first time they have been instantiated.
     *
     * The '/app' and '/system' directories are already mapped for you.
     * you may change the name of the 'App' namespace if you wish,
     * but this should be done prior to creating any namespaced classes,
     * else you will need to modify all of those classes for this to work.
     *
     * Prototype:
     *   $psr4 = [
     *       'CodeIgniter' => SYSTEMPATH,
     *       'App'         => APPPATH
     *   ];
     *
     * @var array<string, array<int, string>|string>
     * @phpstan-var array<string, string|list<string>>
     */

    public $psr4 = [
        APP_NAMESPACE           => APPPATH, // For custom app namespace
        'Config'                => APPPATH . 'Config',

        // Core Modules
        'Administrator'         => APPPATH . 'Modules/Core/Administrator',
        'Api'                   => APPPATH . 'Modules/Core/Api', 
        'Autorisation'          => APPPATH . 'Modules/Core/Autorisation',
        'Base'                  => APPPATH . 'Modules/Core/Base',
        'Components'            => APPPATH . 'Modules/Core/Components',
        'DataView'              => APPPATH . 'Modules/Core/DataView' ,
        'Historique'            => APPPATH . 'Modules/Core/Historique' ,
        'Layout'                => APPPATH . 'Modules/Core/Layout',
        'Mail'                  => APPPATH . 'Modules/Core/Mail',
        'Mapping'               => APPPATH . 'Modules/Core/Mapping', 
        'Modelisation'          => APPPATH . 'Modules/Core/Modelisation', 
        'Translator'            => APPPATH . 'Modules/Core/Translator',

        // Custom Module
        'Custom'                => APPPATH . 'Modules/Custom',

        // Management Module
        'Bien'                  => APPPATH . 'Modules/Management/Bien',
        'Contact'               => APPPATH . 'Modules/Management/Contact',
        'Contact_profil'        => APPPATH . 'Modules/Management/Contact_profil',
        'Dashboard'             => APPPATH . 'Modules/Management/Dashboard',
        'Dashboard_default'     => APPPATH . 'Modules/Management/Dashboard_default',
        'Demande'               => APPPATH . 'Modules/Management/Demande',
        'DocumentUpload'        => APPPATH . 'Modules/Management/DocumentUpload',
        'Outlook'               => APPPATH . 'Modules/Management/Outlook',
        'Rdv'                   => APPPATH . 'Modules/Management/Rdv',
        'Tache'                 => APPPATH . 'Modules/Management/Tache',
        'Delete'                => APPPATH . 'Modules/Management/Delete',
        'Messagerie'            => APPPATH . 'Modules/Management/Messagerie',

        'Partenaire_culturel'   => APPPATH . 'Modules/Management/Partenaire_culturel',
        'Partenaire_social'     => APPPATH . 'Modules/Management/Partenaire_social',
        'Barcode'               => APPPATH . 'Modules/Management/Barcode',
        'Ticket'               => APPPATH . 'Modules/Management/Ticket',

        // Tools Modules
        'Calendar'              => APPPATH . 'Modules/Tools/Calendar',
        'Calculator'            => APPPATH . 'Modules/Tools/Calculator',
        'Company'               => APPPATH . 'Modules/Tools/Company',         
        'DataQuery'             => APPPATH . 'Modules/Tools/DataQuery', 
        'DemandeWeb'            => APPPATH . 'Modules/Tools/DemandeWeb', 
        'DepositBox'            => APPPATH . 'Modules/Tools/DepositBox', 
        'DocumentsGenerator'    => APPPATH . 'Modules/Tools/DocumentsGenerator',
        'DocumentsTemplates'    => APPPATH . 'Modules/Tools/DocumentsTemplates',
        'Doublon'               => APPPATH . 'Modules/Tools/Doublon', 
        'Enquete'               => APPPATH . 'Modules/Tools/Enquete', 
        'Tesorus'               => APPPATH . 'Modules/Tools/Tesorus',
        'Import'                => APPPATH . 'Modules/Tools/Import',
        'Liste'                 => APPPATH . 'Modules/Tools/Liste',
        'Mailing'               => APPPATH . 'Modules/Tools/Mailing',
        'MailTemplate'          => APPPATH . 'Modules/Tools/MailTemplate',
        'Report'                => APPPATH . 'Modules/Tools/Report', 
        'Tutorials'             => APPPATH . 'Modules/Tools/Tutorials',

        // DocumentsTemplates Modules
        'Dompdf'                => FCPATH . 'vendor/dompdf/dompdf/src',
        // 'Cpdf'                  => APPPATH . 'ThirdParty/dompdf/lib',
        'Phpdocx'               => APPPATH . 'ThirdParty/Phpdocx/Classes/Phpdocx',
    ];
    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
     * The class map provides a map of class names and their exact
     * location on the drive. Classes loaded in this manner will have
     * slightly faster performance because they will not have to be
     * searched for within one or more directories as they would if they
     * were being autoloaded through a namespace.
     *
     * Prototype:
     *   $classmap = [
     *       'MyClass'   => '/path/to/class/file.php'
     *   ];
     *
     * @var array<string, string>
     */
    public $classmap = [];

    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
     * The files array provides a list of paths to __non-class__ files
     * that will be autoloaded. This can be useful for bootstrap operations
     * or for loading functions.
     *
     * Prototype:
     *   $files = [
     *       '/path/to/my/file.php',
     *   ];
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public $files = [];

    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
     * Prototype:
     *   $helpers = [
     *       'form',
     *   ];
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public $helpers = [];
}
