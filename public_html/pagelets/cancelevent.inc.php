<div>
<?php
if (isset($_POST['submit'])) {
    //user confimed, delete it!
    $eventfordeletion = escape_data($_POST['eventid']);
    $result = deleteEvent($eventfordeletion);
    if ($result) {
        echo "<p>Event deleted.</p>";
    }

} else {
    //get event for deletion
    $eventid = escape_data($_GET['id']);
    $result = getEvent($eventid);
    $numrows = mysqli_num_rows($result);
    if ($numrows != 1) {
        //something went wrong
        echo '<p>An error occured attempting to retrieve this event.<p>';
    } else { 
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
        $eventname = $row['title'];
        echo "<h3>$eventname</h3>";
        //warn user about permanent doom
        ?>
        <p>Deleting this event is permanent and cannot be undone.  All associated data including discussion comments, and RSVPs will be removed.</p>
        <h3 style="text-align:center;">Are you sure you wish to delete this event?</h3>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=cancelevent" method="post">
            <input type="hidden" name="eventid" value="<?php echo $eventid; ?>">
            <div style="text-align:center;">
                <input type="submit" name="submit" value="Delete Event" />
            </div>
        </form>
<?php 

    }
    
} ?>
</div>

