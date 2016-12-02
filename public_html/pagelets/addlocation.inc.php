<div>
<?php

//make check permissions
if ($rank >= ADMIN_RANK) {

    //populate state array
    $starray = getStatesArray();
    $statebyid = array_flip($starray);

    //is it an update or new event?
    $update = false;
    
    //default values
    $locationname = "";
    $addr = "";
    $addrunit = "";
    $city = "";
    $state = "";
    $country = "";
    $zip = "";
    $phone = "";
    $url = "";
    $comment = "";
    $allow = "";
    $action = $_SERVER['PHP_SELF'] . "?page=$page";
    $formname = "Add Location";
    
    //if the id is set, it's an update
    if (isset($_GET['lid'])) { 
        $locid = escape_data($_GET['lid']);
        $result = getLocationByID($locid);
        $numrows = mysqli_num_rows($result);
        if ($numrows == 1) {
            //only update if there is 1 results (to prevent accidentally erasing event data)
            $update = true;
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
            $locationname = $row['locationname'];
            $addr = $row['streetaddr1'];
            $addrunit = $row['aptunit'];
            $city = $row['city'];
            $state = $row['state'];
            $state = $statebyid[$state];
            $country = $row['country'];
            $zip = $row['zip'];
            $phone = $row['phone'];
            $url = $row['website'];
            $comment = $row['comment'];
            $allow = $row['allownewevents'] ? "Yes" : "No";
            
            $action = $_SERVER['PHP_SELF'] . "?page=$page&amp;lid=$locid";
            $formname = "Edit Location";
        }
        
    }
    
    //set form elements
    $formelements = array(
        array(
            "label" => "Location Name:",
            "type" => "text",
            "name" => "locationname",
            "id" => "locationname",
            "class" => "",
            "value" => $locationname,
            "warning" => "Please enter the location name."
        ),
        array(
            "label" => "Street Address:",
            "type" => "text",
            "name" => "addr",
            "id" => "addr",
            "class" => "",
            "value" => $addr,
            "warning" => "Please enter the street address."
        ),
        array(
            "label" => "Unit (optional):",
            "type" => "text",
            "name" => "addrunit",
            "id" => "addrunit",
            "class" => "",
            "value" => $addrunit,
            "warning" => ""
        ),
        array(
            "label" => "City:",
            "type" => "text",
            "name" => "city",
            "id" => "city",
            "class" => "",
            "value" => $city,
            "warning" => "Please enter the city."
        ),
        array(
            "label" => "State:",
            "type" => "select",
            "name" => "state",
            "id" => "state",
            "class" => "",
            "value" => $state,
            "options" => array_keys($starray),
            "warning" => "Please select a state."
        ),
        array(
            "label" => "Country:",
            "type" => "select",
            "name" => "country",
            "id" => "country",
            "class" => "",
            "value" => $country,
            "options" => array(
              "USA",
              "Other"
            ),
            "warning" => "Please select a country."
        ),
        array(
            "label" => "ZIP:",
            "type" => "text",
            "name" => "zip",
            "id" => "zip",
            "class" => "",
            "value" => $zip,
            "warning" => "Please enter the ZIP code."
        ),
        array(
            "label" => "Phone:",
            "type" => "text",
            "name" => "phone",
            "id" => "phone",
            "class" => "",
            "value" => $phone,
            "warning" => "Please enter the phone number."
        ),
        array(
            "label" => "Website: (optional)",
            "type" => "text",
            "name" => "url",
            "id" => "url",
            "class" => "",
            "value" => $url,
            "warning" => ""
        ),
        array(
            "label" => "Comments (optional):",
            "type" => "text",
            "name" => "comment",
            "id" => "comment",
            "class" => "",
            "value" => $comment,
            "warning" => ""
        ),
        array(
            "label" => "Make this location available for events:",
            "type" => "radio",
            "name" => "allow",
            "id" => "allow",
            "class" => "",
            "value" => $allow,
            "options" => array(
              "Yes",
              "No"
            ),
            "warning" => "Please select one."
        ),

    );

    //form submitted
    if (isset($_POST['submit'])) { // Handle the form.
        $valid = true;
        $errors = createErrorArray($formelements);

        if (strlen($_POST['locationname']) < 1) {
            $valid = false;
            $errors[0] = true;
        }
        if (strlen($_POST['addr']) < 1) {
            $valid = false;
            $errors[1] = true;
        }
        if (strlen($_POST['city']) < 1) {
            $valid = false;
            $errors[3] = true;
        }
        if (strlen($_POST['state']) < 1 || $_POST['state'] == "Select State") {
            $valid = false;
            $errors[4] = true;
        }
        if (strlen($_POST['country']) < 1) {
            $valid = false;
            $errors[5] = true;
        }
        if (strlen($_POST['zip']) < 1) {
            $valid = false;
            $errors[6] = true;
        }
        if (strlen($_POST['phone']) < 1) {
            $valid = false;
            $errors[7] = true;
        }
        if (!isset($_POST['allow'])) {
            $valid = false;
            $errors[10] = true;
        }

        //all fields valid
        if ($valid) {

            //clean data
            $locationname = escape_data($_POST['locationname']);
            $addr = escape_data($_POST['addr']);
            $addrunit = escape_data($_POST['addrunit']);
            $city = escape_data($_POST['city']);
            $state = $starray[escape_data($_POST['state'])];
            $country = escape_data($_POST['country']);
            $zip = escape_data($_POST['zip']);
            $phone = escape_data($_POST['phone']);
            $url = escape_data($_POST['url']);
            $comment = escape_data($_POST['comment']);
            $allow = escape_data($_POST['allow']) == 'Yes' ? true : false;

            if ($update) {
                $result = updateLocation($locid, $locationname, $addr, $addrunit, $city, $state, $country, $zip, $phone, $url, $comment, $allow);
            } else {
                $result = addLocation($locationname, $addr, $addrunit, $city, $state, $country, $zip, $phone, $url, $comment, $allow);
            }

            
            if ($result) {
                $text = $update ? "Location updated" : "New location added.";
                echo "<p>$text</p>";
            }
        } else {
            createForm($formname, $action, $formelements, $errors);
        }
    } else {
        $errors = $errors = createErrorArray($formelements);
        createForm($formname, $action, $formelements, $errors);
    }

}
else {
    echo "<p>You must be signed in as an administrator to use this function.</p>";
}
?>
    
</div>
