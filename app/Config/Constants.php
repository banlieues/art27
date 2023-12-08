<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/**
 * --------------------------------------------------------------------
 * Homegrade constants
 * --------------------------------------------------------------------
 */



defined('MODULE_DIR') || define('MODULE_DIR', APPPATH . 'Modules/');
defined('URL_DOCUMENT')        || define('URL_DOCUMENT', "demandes/documents/");
defined('PATH_DOCU_UPLOAD')        || define('PATH_DOCU_UPLOAD', "./tickets/");
defined('PATH_DOCU')        || define('PATH_DOCU', "./uploads/");
defined('PATH_DOCU_URL')        || define('PATH_DOCU_URL', "uploads/");



defined('CRM_NAME') || define("CRM_NAME","CRM");
defined('PUBLIC_MAIL') || define("PUBLIC_MAIL","crm@banlieues.be");

//version non production

defined('CRMAIL') || define("CRMAIL","crm@banlieues.be");
defined('CRMAILMDP') || define("CRMAILMDP","fb343d0fc8acca7fa57df5d6c2102f35");
defined('IDCRMFOLDER') || define("IDCRMFOLDER",'sssssssss');



defined('HAS_OUTLOOK') || define("HAS_OUTLOOK",false);
defined('HAS_NOTE_NOTIFICATION') || define("HAS_NOTE_NOTIFICATION",false);


//Version production
/*define("CRMAIL","info@homegrade.brussels");
define("CRMAILMDP","Bum45258@");
define("IDCRMFOLDER","AQMkADhmYjA0M2YxLWEzZjYtNDZiNi05MmY3LTYzZDIzYWViMDQzMQAuAAADHySc0b-FHkiVbIGwhnzaVwEAygL9Vv-UBUq_4U2d2CaMZAAChMjexAAAAA==");
*/


//defined('EMAIL_FROM') || define('EMAIL_FROM',"info@homegrade.brussels"); // no errors


defined('IS_AJAX_DATATABLE') || define('IS_AJAX_DATATABLE',FALSE);
defined('IS_PROD') || define('IS_PROD',FALSE);
 
/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * Additional constants define by user
**/
defined('ALLOW_REGISTRATIONS')  || define('ALLOW_REGISTRATIONS', FALSE);
defined('COOKIES_CONSENT')      || define('COOKIES_CONSENT', FALSE);
defined('BACKEND_ACCESS_LEVEL') || define('BACKEND_ACCESS_LEVEL', 1);
defined('ACCORDION_SIDEBAR')    || define('ACCORDION_SIDEBAR', FALSE);
defined('SIDEBAR_WEBSTORAGE')   || define('SIDEBAR_WEBSTORAGE', TRUE);

// Specific to user_accounts database
defined('VALIDED_BY_DEFAULT')   || define('VALIDED_BY_DEFAULT', 0);
defined('ACTIVED_BY_DEFAULT')   || define('ACTIVED_BY_DEFAULT', 0);

// Specific to user_profiles database

defined('AVATAR_DEFAULT')       || define('AVATAR_DEFAULT', "default.png");
defined('AVATAR_PATH')          || define('AVATAR_PATH', "images/avatars/");

defined('DEFAULT_ROLE_ID')      || define('DEFAULT_ROLE_ID', 5);
defined('DEFAULT_GENDER_ID')    || define('DEFAULT_GENDER_ID', 1);
defined('DEFAULT_COUNTRY_ID')   || define('DEFAULT_COUNTRY_ID', 22);
defined('DEFAULT_WEBSITE')      || define('DEFAULT_WEBSITE', 'www.exemple.com');
defined('DEFAULT_PHONE')        || define('DEFAULT_PHONE', '+00 0 000 00 00');
defined('DEFAULT_GSM')          || define('DEFAULT_GSM', '+00 000 00 00 00');
defined('DEFAULT_BIRTHDAY')     || define('DEFAULT_BIRTHDAY', '0000-00-00 00:00');
defined('DEFAULT_SERVICE_ID')   || define('DEFAULT_SERVICE_ID', 0);

// Specific to deposit_box database
defined('DEPOSITBOX_QUOTA')     || define('DEPOSITBOX_QUOTA', 1073741824); // 1Gb
defined('DEPOSITBOX_FILESIZE')  || define('DEPOSITBOX_FILESIZE', 5242880); // 5Mb
defined('DEPOSITBOX_PATH')      || define('DEPOSITBOX_PATH', "public/depositbox/");

// Specific to mails_box database
defined('MAILSBOX_QUOTA')       || define('MAILSBOX_QUOTA', 1073741824); // 1Gb
defined('MAILSBOX_FILESIZE')    || define('MAILSBOX_FILESIZE', 5242880); // 5Mb
defined('MAILSBOX_PATH')        || define('MAILSBOX_PATH', "public/mailsbox/");
/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);
