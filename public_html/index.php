<?php
//start session
ob_start();
session_name('hella');
session_start();
//set content type
header('Content-Type: text/html; charset=utf-8');

//turn on errors and warnings for development
error_reporting(E_ALL);

//get paths
$WEB_ROOT = getenv("DOCUMENT_ROOT");
//$APP_PATH = 'HellaPHPEvents'; //local
$APP_PATH = ''; //ssc server

//language and functions
//require_once("mysqli_connect.php"); //local
require_once("../upload/mysqli_connect.php"); //ssc server
require_once("$WEB_ROOT/$APP_PATH/includes/language.inc.php");
require_once("$WEB_ROOT/$APP_PATH/includes/functions.inc.php");
require_once("$WEB_ROOT/$APP_PATH/includes/sqlstatements.inc.php");

//get page
$page = getPage();
if (!file_exists("$WEB_ROOT/$APP_PATH/pagelets/$page.inc.php")) {
    $page = "index";
}

//get current user session info
require_once("$WEB_ROOT/$APP_PATH/includes/checklogin.inc.php");

//header file
include ("$WEB_ROOT/$APP_PATH/includes/header.inc.php");

//content goes here
echo "<h1 id='mainhead'>" . constant(strtoupper($page) . '_TITLE') . "</h1>";

include ("$WEB_ROOT/$APP_PATH/pagelets/$page.inc.php");

//footer file
include ("$WEB_ROOT/$APP_PATH/includes/footer.inc.php");

//close the database connection
mysqli_close($dbc);

// Show session info (for class use or debugging only)
if (isset($_GET['debug']))  {
   showDebugInfo();
   }
?>

