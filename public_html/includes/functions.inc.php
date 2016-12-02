<?php
//get the pagelet
function getPage() {
    if (!isset($_GET['page'])) {
        return "index";
    }
   return htmlentities($_GET['page']);
}

/*create form
 *   elements - must be an associative array in the format:
 *   "label" => "",
     "type" => "",
     "name" => "",
     "id" => "",
     "class" => "",
     "options" => array(), (options for radio group/checkbox/select box)
     "warning" => "" (warning to user if invalid input)
 * 
*/
function createForm($formname, $action, $elements, $warnings, $showborder = true) {
    //count elements
    $elementcount = count($elements);
    
    //begin form
    echo "<form action=\"$action\" method=\"post\">";
    echo $showborder ? "<fieldset>" : "<fieldset style='border:0px;'>";
    echo $showborder ? "<legend>$formname</legend>" : "";
    
    for ($i = 0; $i < $elementcount; $i++) {
        $element = $elements[$i];
        $warn = $warnings[$i];
        $iname = $element['name'];
        $type = $element['type'];
        
        //hidden input
        if ($type == "hidden") {
            createHiddenInput($iname, $element['value']);
            
        } else {
            //diplay form element label
            echo "<div><p>\n<label for=\"";
            echo $iname;
            echo "\">\n";
            echo $element['label'];
            echo "</label>\n";

            //form elements
            if ($type == "text") { //text box
                createTextbox($iname, $element['id'], $element['class'], $element['value']);
            } else if ($type == "password") {  //password box
                createPassbox($iname, $element['id'], $element['class']);
            } else if ($type == "textarea") {  //text area
                echo "<br/>";
                createTextarea($iname, $element['id'], $element['class'], $element['value'], $element['rows'], $element['cols']);
                echo "<br/>\n";
            } else if ($type == "select" || $type == "eventlocation") { //select box
                createSelectbox($iname, $element['id'], $element['class'], $element['options'], isset($element['value'])? $element['value'] : "");
            } else if ($type == "radio" || $type == "checkbox") {  //radio or checkbox in a group
                createRadioOrCheckbox($iname, $element['id'], $element['class'], $element['options'], $type, $element['value']);
            } else if ($type == "timeselect") { //special - select boxes arranged for time selection
                createTimeInput($iname, $element['id'], $element['class'], isset($element['value'])? $element['value'] : "");
            }
            if ($warn) {  //display invalid input warning
                echo "<span class=\"formwarning\">";
                echo $element['warning'];
                echo "</span>";
            }
            echo "</p></div>";
        }
    }
    
    //submit button
    echo "<div class=\"submitbutton\">\n";
    echo "<input type=\"submit\" name=\"submit\" value=\"Submit\" />\n</div>\n";
    echo "</fieldset>\n";
    echo "</form>";
}

//form elements
//--------------------------------------

function createHiddenInput($name, $value) {
    echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
}

function createTextbox($name, $id, $class, $value) {
    if (isset($_POST["$name"])) {
        $value = escape_data($_POST["$name"]);
    }
    echo '<input type="text" name="' . $name . '" id="'. $id;
    if (strlen($class) > 0) {
        echo '" class="' . $class;
    }
    if (strlen($value) > 0) {
        echo '" value="' . $value;
    }
    echo '" />' . "\n";
}

function createPassbox($name, $id, $class) {
    echo '<input type="password" name="' . $name . '" id="'. $id;
    if (strlen($class) > 0) {
        echo '" class="' . $class;
    }
    echo '" />';
    echo "\n";
}

function createTextarea($name, $id, $class, $value, $rows, $cols) {
    if (isset($_POST["$name"])) {
        $value = escape_data($_POST["$name"]);
    }
    echo '<textarea name="' . $name . '" id="' . $id .'" rows="' .
            $rows . '" cols="' . $cols;
    if (strlen($class) > 0) {
        echo '" class="' . $class;
    }
    echo '">' . $value . '</textarea>';
    echo "\n";
}

function createSelectbox($name, $id, $class, $options, $value="") {
    echo '<select name="' . $name . '" id="' . $id;
    if (strlen($class) > 0) {
        echo '" class="' . $class;
    }
    echo '">' . "\n";
    foreach ($options as $option) {
        echo '<option value="' . $option . '"';
         if (isset($_POST["$name"]) && escape_data($_POST["$name"]) == escape_data($option)) {
            echo ' selected="selected"';
        } else if ($value == $option) {
            echo ' selected="selected"';
        }
        echo '>';
        echo $option;
        echo '</option>';

    }
    echo '</select>' . "\n";
}

function createRadioOrCheckbox($name, $id, $class, $options, $type, $value = ""){
    //TODO: decide where to keep this loop
    $ctr = "";
    foreach ($options as $option) {
        echo '<input type="' . $type . '" name="' . $name . '" id="' . $id . $ctr;
        if (strlen($class) > 0) {
            echo '" class="' . $class;
        }
        echo '" value="' . $option . '" ';

        //TODO: fix this
        if (isset($_POST["$name"]) && escape_data($_POST["$name"]) == escape_data($option )) {
            echo " checked ";
        } else if ($value == $option) {
            echo " checked ";
        }
        echo "/> ";
        echo $option;
        echo "&nbsp;&nbsp;&nbsp;";
        $ctr++;
    }
}

function createTimeInput($name, $id, $class, $value = "") {
    $hrs = array();
    for ($i = 0; $i < 12; $i++) {
        $hrs[$i] = str_pad($i+1, 2,'0', STR_PAD_LEFT);
    }
    $mins = array ("00", "15", "30", "45");
    $ampm = array ("AM", "PM");

    $ampmvalue = "";
    $hrvalue = "";
    $minvalue = "";
    //check for pre-set value string in format hh:mm AM
    if (strlen($value) > 0) {
        $vtime = explode(" ", $value);
        $ampmvalue = $vtime[1];
        $vtime = explode(":", $vtime[0]);
        $hrvalue = $vtime[0];
        $minvalue = $vtime[1];
    }
    //create each select box
    createSelectbox($name, $id, $class, $hrs, $hrvalue);
    echo ": ";
    createSelectbox($name . "mins", $id . "mins", $class, $mins, $minvalue);
    createSelectbox($name . "ampm", $id . "ampm", $class, $ampm, $ampmvalue);
    
}

function createLocationSelect($name, $id, $class, $addressbook, $value = "") {
    //TODO: get locations from database
    createSelectbox($name, $id, $class, $addressbook, $value);
    echo '<a href="?page=addlocation"> Add New</a>';
}

function checkDateFormat($date) {
    $dateregex = '/(0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])[- \/.](19|20)\d\d/';
    return preg_match($dateregex, $date);
}

//convert time to date
function toDateTime($datestring, $hrs, $mins, $ampm) {
    $format = "m/d/Y g:i A";
    $datetimestr = $datestring . " " . $hrs. ":" . $mins. " " . $ampm;
    $datetime = DateTime::createFromFormat($format, $datetimestr);
    return $datetime; 
}

//check that the start date/time is before the end date/time
function validateStartEnd($start, $end) {
    return $start < $end;
}

//pre-populate errors array with blank errors
function createErrorArray($elements) {
    $elementcount = count($elements);
    $errarray = array();
    for ($i = 0; $i < $elementcount; $i++) {
        $errarray[$i] = false;
    }
    return $errarray;
}

//made a date into a formatted string with or without the time
function verbose_date($date, $showtime = true) {
    if ($showtime)
        return date("F j, Y \\a\\t g:ia", strtotime($date));
    else 
        return date("F j, Y", strtotime($date));
}

//convert M F O gender to full word for display
function verbose_gender($gender) {
    if ($gender == 'M') {
        return "Male";
    } else if ($gender == 'F') {
        return "Female";
    } else {
        return "Other";
    }
}

//scrub data to prevent xss/injection/etc
function escape_data($data) {
    global $dbc;
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlentities($data, ENT_QUOTES);
    if (ini_get('magic_quotes_gpc')) {
        $data = stripslashes($data);
    }
    return mysqli_real_escape_string($dbc, $data);
}

//debugging
function showDebugInfo()  
{
    echo '<div style="padding:20px;">';
    echo '<h5>SESSION Debugging Info: </h5><pre>';
    echo print_r($_SESSION);
    echo '</pre>';                    
    echo '<h5>GET Info: </h5><pre>';
    echo print_r($_GET);
    echo '</pre>';
    echo '<h5>POST Info: </h5><pre>';
    echo print_r($_POST);
    echo '</pre>';
    echo '</div>';
}
?>