<div>
<?php

$action = $_SERVER['PHP_SELF'] . "?page=$page";
$formname = "Add Comment";

$commentformelements = array(
    array(
        "label" => "Comment:",
        "type" => "textarea",
        "name" => "eventcomment",
        "id" => "eventcomment",
        "class" => "textarea",
        "value" => "",
        "rows" => 10,
        "cols" => 40,
        "warning" => "Please add a comment before submitting"
    )
);

if (isset($_POST['submit'])) { // Handle the form.
    $valid = true;
    $errors = array(false);  

    if (strlen($_POST['eventcomment']) < 1) {
        $valid = false;
        $errors[0] = true;
    }
    if ($valid) {
        echo '<p>Comment has been added.</p>';
    } else {
            createForm($formname, $action, $commentformelements, $errors);
        }
}

else {
    $errors = array(false);
    createForm($formname, $action, $commentformelements, $errors);
}

?>    
</div>

