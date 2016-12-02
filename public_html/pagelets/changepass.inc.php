<?php


//user must be logged in
if ($loggedin) {
    $action = $_SERVER['PHP_SELF'] . "?page=$page";

    $formname = "Change Password";
    $formelements = array(
        array(
            "label" => "Current password:",
            "type" => "password",
            "name" => "currentpass",
            "id" => "currentpass",
            "class" => "",
            "value" => "",
            "warning" => "The password entered does not match our records."
        ),
        array(
            "label" => "New password:",
            "type" => "password",
            "name" => "password1",
            "id" => "password1",
            "class" => "",
            "value" => "",
            "warning" => "Password must be at least 8 characters."
        ),
        array(
            "label" => "Confirm password:",
            "type" => "password",
            "name" => "password2",
            "id" => "password2",
            "class" => "",
            "value" => "",
            "warning" => "Does not match password."
        )
    );
    
    //submitted
    if (isset($_POST['submit'])) {
        // Handle the form.
        $valid = true;
        $errors = createErrorArray($formelements);
        
        //validate
        if (strlen($_POST['currentpass']) < 1) {
            $valid = false;
            $errors[0] = true;
        }
        $currentpass = hash('sha256', escape_data($_POST['currentpass']));
        if (!checkCurrentPass($currentuser, $currentpass)) {
            $valid = false;
            $errors[0] = true;
        }
        if (strlen($_POST['password1']) < 8) {
            $valid = false;
            $errors[1] = true;
        }
        if (escape_data($_POST['password1']) != escape_data($_POST['password2'])) {
            $valid = false;
            $errors[2] = true;
        }
        
        if ($valid) {
            $newpass = hash('sha256', escape_data($_POST['password1']));
            $result = updatePass($currentuser, $newpass);
            
            if ($result) {
                echo "<p>Your password has been updated.</p>";
            }
            
        } else {
            createForm($formname, $action, $formelements, $errors);
        }
    } else {
        $errors = createErrorArray($formelements);
        createForm($formname, $action, $formelements, $errors);
    }
    
} else {
    echo '<p>You must be logged in to use this function.</p>';
}

?>

