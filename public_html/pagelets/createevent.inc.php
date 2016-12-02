<script type="text/javascript">
    //fancy little date picked from http://2008.kelvinluck.com/assets/jquery/datePicker/v2/demo/index.html
    //slighly modified
    $(function()
    {
        $('.date-view1')
                .datePicker()
                .bind(
                        'dateSelected',
                        function(e, selectedDate, $td)
                        {
                                $('.date1').val(selectedDate.asString());
                                $('.date-view2').dpSetSelected(selectedDate.addDays(0).asString());
                        }
                );
        $('.date-view2')
                .datePicker()
                .bind(
                        'dateSelected',
                        function(e, selectedDate, $td)
                        {
                                $('.date2').val(selectedDate.asString());
                        }
                );
    });
</script>

<div id="createevent">
<?php 
//get locations from db
$locarray = getLocationsArray();
$locarrayByID = array_flip($locarray);

//is it an update or new event?
$update = false;

//default values
$vtitle = "";
$vtag = "";
$vstartdate = "";
$vstarttime = "";
$venddate = "";
$vendttime = "";
$vlocation = "";
$vdesc = "";
$action = $_SERVER['PHP_SELF'] . "?page=$page";
$formname = "Create Event";

//if the id is set, it's an update
if (isset($_GET['id'])) {
    $eventid = escape_data($_GET['id']);
    //get the event data from the database
    $result = getEvent($eventid);
    $numrows = mysqli_num_rows($result);
    if ($numrows == 1) {
        //only update if there is 1 results (to prevent accidentally erasing event data)
        $update = true;
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        //populate values
        $vtitle = $row['title'];
        $vtag = $row['tagline'];
        $vstartdate = date("m/d/Y", strtotime($row['start']));
        $vstarttime = date("h:i A", strtotime($row['start']));
        $venddate = date("m/d/Y", strtotime($row['end']));
        $vendttime = date("h:i A", strtotime($row['end']));
        $vlocation = $row['locationname'];
        $vdesc = $row['description'];
        
        $action = $_SERVER['PHP_SELF'] . "?page=$page&amp;id=$eventid";
        $formname = "Edit Event";
    }
} 

//form elements
$formelements = array(
    array(
        "label" => "Event Title:",
        "type" => "text",
        "name" => "eventname",
        "id" => "eventname",
        "class" => "",
        "value" => $vtitle,
        "warning" => "Please enter an event title."
    ),
    array(
        "label" => "Event Tag Line:",
        "type" => "text",
        "name" => "tag",
        "id" => "tag",
        "class" => "",
        "value" => $vtag,
        "warning" => "Please enter a tagline."
    ),
    array(
        "label" => "Start date (mm/dd/yyyy):",
        "type" => "text",
        "name" => "startdate",
        "id" => "startdate",
        "class" => "date-view1",
        "value" => $vstartdate,
        "warning" => "Please enter or select a valid date in the format mm/dd/yyyy."
    ),
    array(
        "label" => "Start Time:",
        "type" => "timeselect",
        "name" => "starttime",
        "id" => "starttime",
        "class" => "",
        "value" => $vstarttime,
        "warning" => ""
    ),
    array(
        "label" => "End date (mm/dd/yyyy):",
        "type" => "text",
        "name" => "enddate",
        "id" => "enddate",
        "class" => "date-view2",
        "value" => $venddate,
        "warning" => "Please enter or select a valid date in the format mm/dd/yyyy."
    ),
    array(
        "label" => "End Time:",
        "type" => "timeselect",
        "name" => "endtime",
        "id" => "endtime",
        "class" => "",
        "value" => $vendttime,
        "warning" => "Event end cannot occur before the event starts."
    ),
    array(
        "label" => "Event Location:",
        "type" => "eventlocation",
        "name" => "location",
        "id" => "location",
        "class" => "",
        "value" => $vlocation,
        "options" => array_keys($locarray),
        "warning" => "Please choose a location."
    ),
    array(
        "label" => "Description:",
        "type" => "textarea",
        "name" => "desc",
        "id" => "desc",
        "class" => "eventdesctextarea",
        "value" => $vdesc,
        "rows" => 10,
        "cols" => 60,
        "warning" => "Please enter an event description"
    )
);


//make sure user has access before displaying form
if ($rank >= COORDINATOR_RANK) {
    
    //submitted
    if (isset($_POST['submit'])) { // Handle the form.
        $valid = true;
        $errors = $errors = createErrorArray($formelements);

        if (strlen($_POST['eventname']) < 1) {
            $valid = false;
            $errors[0] = true;
        }
        if (strlen($_POST['tag']) < 1) {
            $valid = false;
            $errors[1] = true;
        }
        if (!(checkDateFormat($_POST['startdate']))) {
            $valid = false;
            $errors[2] = true;
        }
        if (!(checkDateFormat($_POST['enddate']))) {
            $valid = false;
            $errors[4] = true;
        }
        if (strlen($_POST['location']) < 1 || $_POST['location'] == "Select Location") {
            $valid = false;
            $errors[6] = true;
        }
        if (strlen($_POST['desc']) < 1) {
            $valid = false;
            $errors[7] = true;
        }

        $start = toDateTime($_POST['startdate'], $_POST['starttime'], $_POST['starttimemins'], $_POST['starttimeampm']);
        $end = toDateTime($_POST['enddate'], $_POST['endtime'], $_POST['endtimemins'], $_POST['endtimeampm']);
        if ($start >= $end) {
            $valid = false;
            $errors[5] = true;
        }

        if ($valid) {
            //clean data
            $title = escape_data($_POST['eventname']);
            $tag = escape_data($_POST['tag']);
            $start = toDateTime(escape_data($_POST['startdate']), escape_data($_POST['starttime']), escape_data($_POST['starttimemins']), escape_data($_POST['starttimeampm']));
            $end = toDateTime(escape_data($_POST['enddate']), escape_data($_POST['endtime']), escape_data($_POST['endtimemins']), escape_data($_POST['endtimeampm']));
            $location = $locarray[escape_data($_POST['location'])];
            $description = escape_data($_POST['desc']);
            $publish = 1;
            
            $strstart = $start->format('Y-m-d H:i:s');
            $strend = $end->format('Y-m-d H:i:s');
            
            //either update or insert new
            if ($update) {
                $result = updateEvent($eventid, $title, $tag, $strstart, $strend, $location, $description, $publish);
            } else {
                $result = addEvent($title, $tag, $strstart, $strend, $location, $description, $publish);
            }
            
            //success
            if ($result) {
                $text = $update ? "Event updated" : "Thank you for creating an event!";
                echo "<p>$text</p>";
            }
        } else {
            createForm($formname, $action, $formelements, $errors);
        }
    } else {
        $errors = $errors = createErrorArray($formelements);
        createForm($formname, $action, $formelements, $errors);
    }

} else {
    //no form for you.
    echo '<p>You must be logged in as an event coordinator to add or edit events.</p>';
}
 ?>
    
</div>


