<?php

//new members
function addMember($username, $password, $email) {
    global $dbc;
    $query = "INSERT INTO `users`(`username`, `password`, `email`, `registerdate`, `userrank`) "
                            . "VALUES (?, ?, ?, NOW(), 1000)";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'sss', $username, $password, $email);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }

}

//new members
function updatePass($userid, $password) {
    global $dbc;
    $query = "UPDATE `users` "
            . "SET `password` = ? "
            . "WHERE `userID` = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'si', $password, $userid);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }

}

function updateMemberRank($userid, $userrank) {
    global $dbc;
    $query = "UPDATE `users` 
        SET `userrank` = ?
        WHERE `userID` = ?";
    
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $userrank, $userid);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }
}

function updateProfile($userid, $fname, $lname, $displayname, $birthday, $gender, $bio) {
    global $dbc;
    $query = "UPDATE `profile_data` "
            . "SET `fname` = ?,`lname` = ?,`displayname` = ?,`birthday`= ?,`bio`= ?,`gender`= ? "
            . "WHERE `profileID` = ?";
    
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssi', $fname, $lname, $displayname, $birthday, $bio, $gender, $userid);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }
}
//new location
function addLocation($locationname, $addr, $addrunit, $city, $state, $country, $zip, $phone, $url, $comment, $allow) {
    $query = "INSERT INTO `locations` (`locationname`, `streetaddr1`, `aptunit`, `city`, `state`, `country`, `zip`, `phone`, `phoneext`, `website`, `comment`, `allownewevents`) "
            . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, '', ?, ?, ?)";
    global $dbc;
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssssssi', $locationname, $addr, $addrunit, $city, $state, $country, $zip, $phone, $url, $comment, $allow);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }
}

function updateLocation($locid, $locationname, $addr, $addrunit, $city, $state, $country, $zip, $phone, $url, $comment, $allow) {
    $query = "UPDATE `locations` "
            . "SET `locationname` = ?,`streetaddr1` = ?,`aptunit` = ?,`city` = ?,`state` = ?,`country` = ?,`zip` = ?,`phone` = ?, `website` = ?,`comment` = ?,`allownewevents` = ? "
            . "WHERE `locationid` = ?";
    global $dbc;
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssssssii', $locationname, $addr, $addrunit, $city, $state, $country, $zip, $phone, $url, $comment, $allow, $locid);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }
}

//set location to allow/disallow new events
function setLocationAllow($locid, $allow) {
    global $dbc;
    $query = "UPDATE `locations` "
            . "SET `allownewevents` = ? "
            . "WHERE `locationid`= ?";
    
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $allow, $locid);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }
}

//new event
function addEvent($title, $tag, $start, $end, $location, $description, $publish) {
    global $dbc;
    $query = "INSERT INTO `events`(`title`, `tagline`, `start`, `end`, `location`, `description`, `published`) "
                 . "VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssi',$title, $tag, $start, $end, $location, $description, $publish);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }

}

//update event
function updateEvent($eventid, $title, $tag, $start, $end, $location, $description, $publish) {
    global $dbc;
    $query = "UPDATE `events` "
            . "SET `title` = ?,`tagline` = ?,`start` = ?,`end` = ?,`location` = ?,`description`= ? ,`published` = ? "
            . "WHERE `eventID` = ?";
    
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssii',$title, $tag, $start, $end, $location, $description, $publish, $eventid);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }

}


//new rsvp
function addRSVP($eventid, $userid, $response, $guests) {
    global $dbc;
    $query = "INSERT INTO `eventrsvp`(`eventid`, `userid`, `response`, `guests`) "
            . "VALUES (?,?,?,?)";
    
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'iisi',$eventid, $userid, $response, $guests);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }
}

//delete rsvp
function deleteRSVP($eventid, $userid) {
        global $dbc;
    $query = "DELETE FROM `eventrsvp` WHERE `eventid` = ? AND `userid` = ?";
    
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $eventid, $userid);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }
}

function addComment($eventid, $userid, $comment) {
    global $dbc;
    $query = "INSERT INTO `event_discussion`(`eventid`, `userid`, `timestamp`, `comment`) "
            . "VALUES (?,?, NOW(),?)";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'iis',$eventid, $userid, $comment);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }
}

function deleteEvent($eventid) {
    global $dbc;
    $query = "DELETE FROM `events` WHERE `eventID` = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'i',$eventid);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
        return false;
    }
}


//query statements

//get login
function getLogin($user, $pass) {
    global $dbc;
//    $query = "SELECT `userID`, `userrank` FROM `users` WHERE `username` = ? AND `password` = ?";
//    $stmt = mysqli_stmt_init($dbc);
//    if(!mysqli_stmt_prepare($stmt, $query)) {
//        echo "<p>Failed to prepare statement</p>";
//        mysqli_stmt_close($stmt);
//        return false;
//    }
//    mysqli_stmt_bind_param($stmt, 'ss', $user, $pass);
//    if (mysqli_stmt_execute($stmt)) {
//        $result = mysqli_stmt_get_result($stmt);
//    } else {
//        echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
//        mysqli_stmt_close($stmt);
//        return false;
//    }
//    mysqli_stmt_close($stmt);
//    return $result;
    $q = "SELECT `userID`, `userrank` FROM `users` WHERE `username` = '$user' AND `password` = '$pass'";
    $r = @mysqli_query($dbc, $q);
    return $r;
    
}

//get members
function getMembers($orderby = "displayname") {
    global $dbc;
//    $query = "SELECT `profileID`, `displayname`, `rolename` "
//        . "FROM `profile_data`, `users`, `roles` "
//        . "WHERE `profile_data`.`profileID` = `users`.`userID` "
//        . "&& `users`.`userrank` = `roles`.`roleid` "
//        . "ORDER BY ?";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 's', $orderby);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
//    return $result;
    $q = "SELECT `profileID`, `displayname`, `rolename` "
        . "FROM `profile_data`, `users`, `roles` "
        . "WHERE `profile_data`.`profileID` = `users`.`userID` "
        . "&& `users`.`userrank` = `roles`.`roleid` "
        . "ORDER BY $orderby";
    $r = @mysqli_query($dbc, $q);
    return $r;
}

//get profile
function getProfileData($userid) {
    global $dbc;
//    $query = "SELECT `profileID`, `fname`, `lname`, `displayname`, `birthday`, `bio`, `gender` "
//            . "FROM `profile_data` "
//            . "WHERE `profileID` = ?";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 'i',$userid);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
//    return $result;
    $q = "SELECT `profileID`, `fname`, `lname`, `displayname`, `birthday`, `bio`, `gender` "
            . "FROM `profile_data` "
            . "WHERE `profileID` = '$userid'";
    $r = @mysqli_query($dbc, $q);
    return $r;
}

//get event list
function getEventList($past, $orderby = "start ASC") {
    global $dbc;
    $ltgt = $past ? "<=" : ">";
//    $query = "SELECT `eventID`, `title`, `tagline`, `start`, `end`, `locationname`, `description`, `published` "
//        . "FROM `events`, `locations` "
//        . "WHERE `events`.`location` = `locations`.`locationid`"
//        . "AND `start` $ltgt NOW()"
//        . "ORDER BY ?";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 's',$orderby);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
//    return $result;
      $q = "SELECT `eventID`, `title`, `tagline`, `start`, `end`, `locationname`, `description`, `published` "
        . "FROM `events`, `locations` "
        . "WHERE `events`.`location` = `locations`.`locationid`"
        . "AND `start` $ltgt NOW()"
        . "ORDER BY $orderby";
    $r = @mysqli_query($dbc, $q);
    return $r;
}

//get events between dates
function getEventsBetween($start, $end) {
    global $dbc;
//    $query = "SELECT `eventID`, `title`, `start` "
//            . "FROM `events` "
//            . "WHERE (`start` BETWEEN ? AND ?)"
//            . "ORDER BY `start`";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 'ss', $start, $end);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
//    return $result;
    $q = "SELECT `eventID`, `title`, `start` "
            . "FROM `events` "
            . "WHERE (`start` BETWEEN '$start' AND '$end')"
            . "ORDER BY `start`";
    $r = @mysqli_query($dbc, $q);
    return $r;
}

//get event details
function getEvent($eventid) {
    global $dbc;
//    $query = "SELECT `title`, `tagline`, `start`, `end`, `description`, `published`, "
//        . "`locationname`, `streetaddr1`, `aptunit`, `city`, `state`, `country`, "
//        . "`zip`, `phone`, `phoneext`, `website`, `comment` "
//        . "FROM `events`, `locations` "
//        . "WHERE `eventID` = ? "
//        . "AND `events`.`location` = `locations`.`locationid` ";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 'i',$eventid);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
//    return $result;
    $q = "SELECT `title`, `tagline`, `start`, `end`, `description`, `published`, "
        . "`locationname`, `streetaddr1`, `aptunit`, `city`, `state`, `country`, "
        . "`zip`, `phone`, `phoneext`, `website`, `comment` "
        . "FROM `events`, `locations` "
        . "WHERE `eventID` = '$eventid'"
        . "AND `events`.`location` = `locations`.`locationid` ";
    $r = @mysqli_query($dbc, $q);
    return $r;
}

//check if user has rsvp for event
function checkRSVP($eventid, $userid) {
    global $dbc;
//    $query = "SELECT `eventid`, `userid`, `response`, `guests` FROM `eventrsvp` WHERE `eventid` = ? AND `userid` = ?";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 'ii',$eventid, $userid);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
//    return $result;
    $q = "SELECT `eventid`, `userid`, `response`, `guests` FROM `eventrsvp` WHERE `eventid` = '$eventid' AND `userid` = '$userid'";
    $r = @mysqli_query($dbc, $q);
    return $r;
}

//get the guest list
function getGuestList($eventid, $rsvp) {
    global $dbc;
//    $query = "SELECT `profileid`, `displayname`, `fname`, `lname`, `guests` "
//            . "FROM `eventrsvp`, `profile_data` "
//            . "WHERE `eventrsvp`.`userid` = `profile_data`.`profileid` "
//            . "AND `eventid` = ? "
//            . "AND `response` = ?";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 'is',$eventid, $rsvp);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
//    return $result;
    $q = "SELECT `profileid`, `displayname`, `fname`, `lname`, `guests` "
            . "FROM `eventrsvp`, `profile_data` "
            . "WHERE `eventrsvp`.`userid` = `profile_data`.`profileid` "
            . "AND `eventid` = '$eventid' "
            . "AND `response` = '$rsvp'";
    $r = @mysqli_query($dbc, $q);
    return $r;
}

//get comments
function getEventDiscussion($eventid) {
    global $dbc;
//    $query = "SELECT `postid`, `userid`, `displayname`, `timestamp`, `comment` "
//            . "FROM `event_discussion`, `profile_data` "
//            . "WHERE `event_discussion`.`userid` = `profile_data`.`profileID` "
//            . "AND `eventid` = ? "
//            . "ORDER BY `timestamp` DESC";
//
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 'i',$eventid);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
//    return $result;
    $q = "SELECT `postid`, `userid`, `displayname`, `timestamp`, `comment` "
            . "FROM `event_discussion`, `profile_data` "
            . "WHERE `event_discussion`.`userid` = `profile_data`.`profileID` "
            . "AND `eventid` = '$eventid' "
            . "ORDER BY `timestamp` DESC";
    $r = @mysqli_query($dbc, $q);
    return $r;
}

function getLocations($orderby = 'locationname', $all = true) {
    global $dbc;
//    $query = "SELECT `locationid`, `locationname`, `city`, `state`, `country`, `allownewevents` "
//            . "FROM `locations` "
//            . "ORDER BY ?";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 's', $orderby);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
//    return $result;
    $q = "SELECT `locationid`, `locationname`, `city`, `state`, `country`, `allownewevents` "
            . "FROM `locations` "
            . "ORDER BY $orderby";
    $r = @mysqli_query($dbc, $q);
    return $r;
}

function getLocationByID($locid) {
    global $dbc;
    $q = "SELECT `locationname`, `streetaddr1`, `aptunit`, `city`, `state`, `country`, `zip`, `phone`, `phoneext`, `website`, `comment`, `allownewevents` "
            . "FROM `locations` "
            . "WHERE `locationid` = '$locid'";
    $r = @mysqli_query($dbc, $q);
    return $r;
}

//queries that return values rather than results

//get rank
function getRank($userid) {
    global $dbc;
//    $query = "SELECT `userrank` FROM `users` WHERE `userid` = ?";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 'i', $userid);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
//    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
//    return $row['userrank'];
    $q = "SELECT `userrank` FROM `users` WHERE `userid` = '$userid'";
    $r = @mysqli_query($dbc, $q);
    $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
    return $row['userrank'];
}

//check pass by user id
function checkCurrentPass($userid, $pass) {
    global $dbc;
    $q = "SELECT `userID` FROM `users` WHERE `userID` = $userid AND `password` = '$pass'";
    $r = mysqli_query($dbc, $q);
    $numrows = mysqli_num_rows($r);
    if ($numrows == 1) {
        return true;
    } else {
        return false;
    }
}

//check if username exists (true/false)
function userExists($username) {
    global $dbc;
//    $query = "SELECT `username` from `users` WHERE `username` = ?";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 's',$username);
//    mysqli_stmt_execute($stmt);

    $q = "SELECT `username` from `users` WHERE `username` = $username";
    $r = @mysqli_query($dbc, $q);
    $numrows = @mysqli_num_rows($r);
    return $numrows > 0;
}

//count guests
function countGuests($eventid, $rsvp) {
    global $dbc;
//    $query = "SELECT COUNT(`userid`) + SUM(`guests`) AS 'total' "
//            . "FROM `eventrsvp` "
//            . "WHERE `eventid` = ? AND `response` = ?";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_bind_param($stmt, 'is', $eventid, $rsvp);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
    $q = "SELECT COUNT(`userid`) + SUM(`guests`) AS 'total' "
            . "FROM `eventrsvp` "
            . "WHERE `eventid` = '$eventid' AND `response` = '$rsvp'";
    $result = @mysqli_query($dbc, $q);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row['total'];
}

//get states as an array
function getStatesArray() {
    global $dbc;
//    $query = "SELECT `stateid`, `statename` FROM `states`";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
    $array = array("Select State" => "");
    $q = "SELECT `stateid`, `statename` FROM `states`";
    $result = @mysqli_query($dbc, $q);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $array[$row['statename']] = $row['stateid'];
    }
    return $array;
}

//get locations as an array
function getLocationsArray() {
    global $dbc;
//    $query = "SELECT `locationid`, `locationname` FROM `locations` WHERE `allownewevents` = 1";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
    $array = array("Select Location" => "");
    $q = "SELECT `locationid`, `locationname` FROM `locations` WHERE `allownewevents` = 1";
    $result = @mysqli_query($dbc, $q);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $array[$row['locationname']] = $row['locationid'];
    }
    return $array;
}


//get ranks as an array
function getRanksArray() {
    global $dbc;
//    $query = "SELECT `roleid`, `rolename` FROM `roles`";
//    $stmt = mysqli_prepare($dbc, $query);
//    mysqli_stmt_execute($stmt);
//    $result = $stmt->get_result();
    $array = array("" => "");
    $q = "SELECT `roleid`, `rolename` FROM `roles`";
    $result = @mysqli_query($dbc, $q);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $array[$row['rolename']] = $row['roleid'];
    }
    return $array;
}

?>

