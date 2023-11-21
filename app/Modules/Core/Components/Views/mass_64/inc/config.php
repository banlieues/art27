<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php
/** 
 * config.php v0.1 by djphil (CC-BY-NC-SA 4.0) 
**/

header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'utf-8');
ini_set('magic_quotes_gpc', 0);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

ini_set('max_execution_time', '0');
ini_set('memory_limit', '4096M');
ini_set('max_input_vars', 10000);

/**
 * Database
**/
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "root";
$dbname = "h4_model";
$tbname = "document_upload";

/**
 * General
**/
$title = "Batch Process";
$version = "0.2";
$color = "deep-purple"; // dark-gray
$debug = false;
$strict = true;

$chunk = 100;


$path = "/Users/absuur/Sites/h4p8/public/demandes/documents/";
$path_final = "/Users/absuur/Sites/h4p8/public/demandes/documents/";

$tags_class = "w3-tag w3-round-xxlarge w3-white w3-border";