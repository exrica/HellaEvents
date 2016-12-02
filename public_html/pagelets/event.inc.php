<?php 
//get event
$eventid = escape_data($_GET['id']);
$result = getEvent($eventid);
$numrows = mysqli_num_rows($result);
if ($numrows != 1) {
    echo '<p>An error occured attempting to retrieve this event.<p>';
} else {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    //check if event is upcoming or past
    if (strtotime(date('Y-m-d H:i:s')) < strtotime($row['start'])) {
        $past = false;
    } else {
        $past = true;
    }
    
//display event
?>

<div id="eventdetailbox">
    <h2><?php echo $row['title']; ?></h2>
    <h3><?php echo $row['tagline']; ?></h3>
    <p class="lpad">
        Start: <?php echo verbose_date($row['start']); ?>
    </p>
    
    <p class="lpad">
        End: <?php echo verbose_date($row['end']); ?>
    </p>
    
    <h3>
        Location:
    </h3>
    <?php if ($rank > 1) { //members+ can see the location ?>
    
    <p class="lpad"><span class="bold"><?php echo $row['locationname']; ?></span> <br />
            <?php echo $row['streetaddr1']; ?>, <?php echo $row['aptunit']; ?>
            <br />
            <?php echo $row['city']; ?>, <?php echo $row['state']; ?>, <?php echo $row['zip']; ?>
            <br />
            <?php echo $row['phone']; ?>
            <?php if (strlen($row['website']) > 1) { ?>
            <br />
            <a href = "<?php echo $row['website']; ?>">Website</a>
            <?php } 
            if (strlen($row['comment']) > 0) { ?>
            <br />
            Comment: <?php echo $row['comment'];  } ?>
        </p>
        
    <?php } else { 
        echo "<p>You must be logged in to see the location.</p>";
    }
?>
    
    <h3>Description: </h3>
    <p class="lpad">
        <?php echo $row['description']; ?>
    </p>
</div>


<div id="rsvpbox">
<?php
if ($rank > 1) { //members+ can rsvp
//see if the user already rsvped
$userrsvp = checkRSVP($eventid, $currentuser);
$rsvprows = mysqli_num_rows($userrsvp);
$going = array(
    "YES" => "Going", 
    "NO" => "Not Going", 
    "MAYBE" => "Maybe"
    );

//show existing rsvp if it exists
if ($rsvprows == 1) {
    $row = mysqli_fetch_array($userrsvp, MYSQLI_ASSOC);
    $response = $row['response'];
    echo "<h3>Your RSVP: ";
    echo $going[$response];
    echo "</h3>";
    if ($response == "YES" || $response == "MAYBE") {
        echo "<h4>You are bringing "
            . $row['guests']
            . " guest(s).</h4>";
    }
    //if the event is upcoming, let the user undo their rsvp
    if (!$past) {
        echo "<p><a class='button' href='responders/removersvp.php?id=$eventid'>Undo RSVP</a></p>";
    }
} else { //otherwise, show rsvp form

if (!$past) {  //don't rsvp for past events
    //create form
    $action = $_SERVER['PHP_SELF'] . "?page=$page&id=$eventid&amp;action=rsvp";
    $formname = "RSVP";
    $formelements = array(
        array(
            "label" => "I will attend: <br/>",
            "type" => "radio",
            "name" => "rsvp",
            "id" => "rsvp",
            "class" => "",
            "value" => "",
            "options" => array(
                "Yes",
                "Maybe",
                "No"
            ),
            "warning" => "Please select an option."
        ),
        array(
            "label" => "I will be bringing guest(s):<br/>(Select number of guests) ",
            "type" => "select",
            "name" => "numberguests",
            "id" => "numberguests",
            "class" => "",
            "value" => "",
            "options" => array(
                "0",
                "1",
                "2",
                "3",
                "4" //allow 4 guests - make this dynamic in the future?
            ),
            "warning" => ""
        ),
        array(
            "type" => "hidden",
            "name" => "eventid",
            "value" => $eventid
        )
    );

    //submitted
    if (isset($_POST['submit']) && escape_data($_GET['action']) == "rsvp") { // Handle the form.
        $valid = true;
        $errors = createErrorArray($formelements); 

        if (!isset($_POST['rsvp'])) {
            $valid = false;
            $errors[0] = true;
        }
        if ($valid) {
            //clean data
            $eventidform = escape_data($_POST['eventid']);
            $response = escape_data($_POST['rsvp']);
            $guests = escape_data($_POST['numberguests']);
            
            //add rsvp
            $result = addRSVP($eventidform, $currentuser, $response, $guests);
            if ($result) {
                echo '<p>RSVP has been received..</p>';
                echo "<p><a class='button' href='responders/removersvp.php?id=$eventid'>Undo RSVP</a></p>";
            }
        } else {
                createForm($formname, $action, $formelements, $errors);
            }
    }

    else {
        $errors = $errors = createErrorArray($formelements);
        createForm($formname, $action, $formelements, $errors);
    }
}}

?>
</div>
<div id="guestbox">
    <h3>Going:</h3>
    <?php 
    //show guests who are attending
    $rsvplist = getGuestList($eventid, "YES");
    $numresponses = mysqli_num_rows($rsvplist);
    if ($numresponses > 0) { 
        //get guest count
        $count = countGuests($eventid, "YES");
        echo "<p class='bold'>$count guests attending!</p>";
        //display all yes rsvps
          while ($rsvprow = mysqli_fetch_array($rsvplist, MYSQLI_ASSOC)) {
            $respprid = $rsvprow['profileid'];
            $fullname = $rsvprow['fname'] . " " . $rsvprow['lname'];
            $fullname = strlen($fullname) > 1 ? " ($fullname)" : "";
            $fullname = $rank >= COORDINATOR_RANK ? $fullname : "";  //coordinators can see the full name
            $removersvp = $rank >= COORDINATOR_RANK ? "<a class='button' href='responders/removememberrsvp.php?id=$eventid&amp;mid=$respprid'>Remove RSVP</a><br />" : "";
            echo "<div class='guestlist'><p>"
                . "<span class='bold'>"
                . "<a href='?page=profile&amp;mid=$respprid'>"
                . $rsvprow['displayname'] . $fullname
                . "</a></span><br />Additional guests: "
                . $rsvprow['guests'] 
                . "</p><p>"
                . $removersvp
                . "<br /></p></div>";
        }
    } else {
        $text = $past ? "No Yes RSVPs." : "No one going yet!";
        echo "<p>$text</p>";
    }

    //show maybe guests
    $rsvplist = getGuestList($eventid, "MAYBE");
    $numresponses = mysqli_num_rows($rsvplist);
    if ($numresponses > 0) { 
        echo "<h3>Maybe:</h3>";
        //show maybe guest count
        $count = countGuests($eventid, "MAYBE");
        echo "<p class='bold'>$count guests might attend!</p>";
        //show all maybe rsvps
          while ($rsvprow = mysqli_fetch_array($rsvplist, MYSQLI_ASSOC)) {
            $respprid = $rsvprow['profileid'];
            $fullname = $rsvprow['fname'] . " " . $rsvprow['lname'];
            $fullname = strlen($fullname) > 1 ? " ($fullname)" : "";
            $fullname = $rank >= COORDINATOR_RANK ? $fullname : "";
            $removersvp = $rank >= COORDINATOR_RANK ? "<a class='button' href='responders/removememberrsvp.php?id=$eventid&amp;mid=$respprid'>Remove RSVP</a><br />" : "";
            echo "<div class='guestlist'><p>"
                . "<span class='bold'>"
                . "<a href='?page=profile&amp;mid=$respprid'>"
                . $rsvprow['displayname'] . $fullname
                . "</a></span>"
                . "<br />Additional guests: "
                . $rsvprow['guests'] 
                . "</p><p>"
                . $removersvp
                . "<br /></p></div>";
        }
    }

    //show rsvps that said no
    $rsvplist = getGuestList($eventid, "NO");
    $numresponses = mysqli_num_rows($rsvplist);
    //show all no rsvps
    if ($numresponses > 0) { 
        echo "<h3>Not Going:</h3>";
          while ($rsvprow = mysqli_fetch_array($rsvplist, MYSQLI_ASSOC)) {
            $respprid = $rsvprow['profileid'];
            $fullname = $rsvprow['fname'] . " " . $rsvprow['lname'];
            $fullname = strlen($fullname) > 1 ? " ($fullname)" : "";
            $fullname = $rank >= COORDINATOR_RANK ? $fullname : "";
            echo "<div class='guestlist'><p><span class='bold'>"
                . ' <a href="?page=profile&amp;mid='
                . $respprid
                .'">'
                . $rsvprow['displayname'] . $fullname
                . "</a></span><br /><br /><br /></p></div>";
        }
    }

} else {
    echo "<h4>Log in to see RSVPs.</h4>";
      
}


?>
</div>


<div id="comments">
    <h3>Discussion</h3>

    <?php 
    
    //logged in members can post comments
    if ($loggedin) {
        if ($rank < 1) {
            //banned members cannot comment
            echo "<p class='darkred'>You have been banned from commenting on events.</p>";
        } else {
            //make the form
            $pastaction = $past ? "&amp;past=true" : "";
            $action = $_SERVER['PHP_SELF'] . "?page=$page&amp;id=$eventid" . "$pastaction&amp;action=comment";
            $formname = "Add Comment";

            $commentformelements = array(
                array(
                    "label" => "Add Comment:",
                    "type" => "textarea",
                    "name" => "eventcomment",
                    "id" => "eventcomment",
                    "class" => "eventcomment",
                    "value" => "",
                    "rows" => 5,
                    "cols" => 50,
                    "warning" => "Please add a comment before submitting"
                )
            );
            $cerrors = array(false);
            if (isset($_POST['submit']) && escape_data($_GET['action'] == "comment")) { // Handle the form.
                $valid = true;
                if (strlen($_POST['eventcomment']) < 1) {
                    $valid = false;
                    $cerrors[0] = true;
                }
                if ($valid) {
                    //clean the comment
                    $comment = escape_data($_POST['eventcomment']);
                    //add the comment
                    $result = addComment($eventid, $currentuser, $comment);
                    if ($result) {
                        //empty the value from the form (form creation method saves values from $_POST
                        $_POST['eventcomment'] = "";
                    }
                } else {
                $cerrors = array(false);
                }
            }
                createForm($formname, $action, $commentformelements, $cerrors, false);
        }
    }
    
    
    //get discussion comments from database
    $commentresult = getEventDiscussion($eventid);
    $numcomments = mysqli_num_rows($commentresult);
    if ($numcomments > 0) { 
        //display each comment
        while ($commentrow = mysqli_fetch_array($commentresult, MYSQLI_ASSOC)) {
            $commenterid = $commentrow['userid'];
            echo "<div class='roundgreybox'><h5>On "
                . verbose_date($commentrow['timestamp'])
                . ' <a href="?page=profile&amp;mid='
                . $commenterid
                .'">'
                . $commentrow['displayname']
                . "</a> wrote:</h5><p>"
                . $commentrow['comment'] 
                . "<br /><br /></p></div>";
        }
    } else {
        echo "<p>No discussion yet!</p>";

}
?>
    
</div>
<?php } ?>
