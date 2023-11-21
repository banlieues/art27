<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php
/** 
 * mysqli.php v0.1 by djphil (CC-BY-NC-SA 4.0) 
**/
$db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$db) {die("Connection failed: " . mysqli_connect_error());}
?>