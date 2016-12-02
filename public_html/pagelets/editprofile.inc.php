<script type="text/javascript">
    $(function() {
        $('.date-pick').datePicker( {
            startDate: '01/01/1920',
            endDate: (new Date()).asString()
        });
    });
</script>
<div id="createevent">
<?php 
//make sure the user is editing their own profile
if (($loggedin) && isset($_GET['mid'])) {
    $profileid = (int)(escape_data($_GET['mid']));
  
    if ($currentuser == $profileid) {
        
        //get profile data
        $result = getProfileData($currentuser);
        
        $profile = mysqli_fetch_array($result, MYSQLI_ASSOC);

        //populate values
        $fname = strlen($profile['fname']) > 0 ? $profile['fname'] : "";
        $lname = strlen($profile['lname']) > 0 ? $profile['lname'] : "";
        $displayname = $profile['displayname'];
        $birthday = strlen($profile['birthday']) > 0 ? date('m/d/Y', strtotime($profile['birthday'])) : "";
        $gender = strlen($profile['gender']) > 0 ? verbose_gender($profile['gender']) : "Other";
        $bio = strlen($profile['bio']) > 0 ? $profile['bio'] : "";
        
        //create form
        $action = $_SERVER['PHP_SELF'] . "?page=$page&amp;mid=$currentuser";
        $formname = "Edit Profile";

        $formelements = array(
            array(
                "label" => "First name:",
                "type" => "text",
                "name" => "fname",
                "id" => "fname",
                "class" => "",
                "value" => $fname,
                "warning" => "Please enter you first name."
            ),
            array(
                "label" => "Last name:",
                "type" => "text",
                "name" => "lname",
                "id" => "lname",
                "class" => "",
                "value" => $lname,
                "warning" => "Please enter you last name."
            ),
            array(
                "label" => "Display name:",
                "type" => "text",
                "name" => "displayname",
                "id" => "displayname",
                "class" => "",
                "value" => $displayname,
                "warning" => "Please enter your name/handle as you would like it to appear."
            ),
            array(
                "label" => "Birth date (mm/dd/yyyy):",
                "type" => "text",
                "name" => "bday",
                "id" => "bday",
                "class" => "date-pick",
                "value" => $birthday,
                "warning" => "Please enter or select a valid date in the format mm/dd/yyyy."
            ),
            array(
                "label" => "Select Gender:",
                "type" => "radio",
                "name" => "gender",
                "id" => "gender",
                "class" => "",
                "value" =>  $gender,
                "options" => array(
                    "Male",
                    "Female",
                    "Other" ),
                "warning" => "Please select your gender."
            ),
            array(
                "label" => "Bio:",
                "type" => "textarea",
                "name" => "bio",
                "id" => "bio",
                "class" => "textarea",
                "value" => $bio,
                "rows" => 10,
                "cols" => 40,
                "warning" => "" //can be left blank
            )
        );

        //submitted

        if (isset($_POST['submit'])) { // Handle the form.
            $valid = true;
            $errors = createErrorArray($formelements);

                if (strlen($_POST['fname']) < 1) {
                    $valid = false;
                    $errors[0] = true;
                }
                if (strlen($_POST['lname']) < 1) {
                    $valid = false;
                    $errors[1] = true;
                }
                if (strlen($_POST['displayname']) < 1) {
                    $valid = false;
                    $errors[2] = true;
                }
                if (!(checkDateFormat($_POST['bday']))) {
                    $valid = false;
                    $errors[3] = true;
                }
                if (!isset($_POST['gender'])) {
                    $valid = false;
                    $errors[4] = true;
                }

                if ($valid) {
                    //clean data
                   
                    $newfname = escape_data($_POST['fname']);
                    $newlname = escape_data($_POST['lname']);
                    $newdisplayname = escape_data($_POST['displayname']);
                    $newbday = date('Y-m-d', strtotime(escape_data($_POST['bday'])));
                    $newgender = escape_data($_POST['gender']);
                    $newgender = $newgender[0];
                    $newbio = escape_data($_POST['bio']);

                    //update database
                    updateProfile($currentuser, $newfname, $newlname, $newdisplayname, $newbday, $newgender, $newbio);
                    echo '<p>Thank you for completing your profile!</p>';
                } else {
                    createForm($formname, $action, $formelements, $errors);
                }
        } else {
            $errors = createErrorArray($formelements);
            createForm($formname, $action, $formelements, $errors);
        }

    } else {
        echo "<p>You are not logged in as this user.</p>";
    }
    
} else {
    echo "<p>You must be logged in to edit your profile.";
}


?>
</div>
