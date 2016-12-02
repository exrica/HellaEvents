<?php
ob_start();
session_name('hella');
session_start();
$WEB_ROOT = getenv("DOCUMENT_ROOT");
//$APP_PATH = 'HellaPHPEvents'; //local
$APP_PATH = ''; //ssc server

//language and functions
//require_once("$WEB_ROOT/$APP_PATH/mysqli_connect.php"); //local
require_once("../../upload/mysqli_connect.php"); //local
require_once ("$WEB_ROOT/$APP_PATH/includes/functions.inc.php");
require_once("$WEB_ROOT/$APP_PATH/includes/sqlstatements.inc.php");
require_once ("$WEB_ROOT/$APP_PATH/includes/language.inc.php");
require_once("$WEB_ROOT/$APP_PATH/includes/checklogin.inc.php");

echo $rank;
//check that user has access
if ($rank >= ADMIN_RANK) {
    
    //get location id and new allow choice
    $locid = (int)escape_data($_GET['lid']);
    $allow = (int)escape_data($_GET['allow']);

    //update database
    $result = setLocationAllow($locid, $allow);

    //redirect back to location list on success
    if ($result) {
        header("Location: ../index.php?page=managelocations");
    }
} else {
    header("Location: ../index.php");
}

