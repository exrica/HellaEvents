<?php

//checks to see if the user session is logged in and sets page variables
if (isset($_SESSION['loggedin'])) {
    //check for browser change
    if ($_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT'])
    {
        exit;
    }
    $loggedin = true;
    $currentuser = (int)escape_data($_SESSION['userid']);
    //update the user's access role from the database
    $rank = (int)getRank(escape_data($_SESSION['userid']));
} else {
    $loggedin = false;
    //set to guest rank
    $rank = 1;
}



