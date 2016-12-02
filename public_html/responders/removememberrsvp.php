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

//make sure user is logged in
if ($rank >= COORDINATOR_RANK) {
    //delete rsvp for event and current user
    $eventid = (int)escape_data($_GET['id']);
    $memberid = (int)escape_data($_GET['mid']);
    $result = deleteRSVP($eventid, $memberid);

    //redirect back to even page on success
    if ($result) {
        header("Location: ../index.php?page=event&id=$eventid");
    }
} else {
    header("Location: ../index.php");
}