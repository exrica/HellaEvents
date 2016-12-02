<script type="text/javascript">
    $(function() {
        $('.date-pick').datePicker( {
            startDate: '01/01/1920',
            endDate: (new Date()).asString()
        });
    });
</script>

<?php


$action = $_SERVER['PHP_SELF'] . "?page=$page" . "&debug=1";
$method = "post";
$formname = "Test Form";
$formelements = array(
    array(
        "label" => "Enter name:",
        "type" => "text",
        "name" => "name",
        "id" => "name",
        "class" => "",
        "value" => "",
        "warning" => "Please enter you name."
    ),
    array(
        "label" => "Birth date:",
        "type" => "text",
        "name" => "bday",
        "id" => "bday",
        "class" => "date-pick",
        "value" => "",
        "warning" => "Please enter you birthday."
    ),
    array(
        "label" => "Password:",
        "type" => "password",
        "name" => "pass",
        "id" => "pass",
        "class" => "pass",
        "value" => "",
        "warning" => "Password must be good"
    ),
    array(
        "label" => "Bio:",
        "type" => "textarea",
        "name" => "biotext",
        "id" => "biotex",
        "class" => "textarea",
        "value" => "",
        "rows" => 20,
        "cols" => 40,
        "warning" => "something about entering a bio"
    ),
    array(
        "label" => "Pick One",
        "type" => "select",
        "name" => "things",
        "id" => "things",
        "class" => "",
        "options" => array(
            "",
            "Apple",
            "Orange Banana",
            "Happy"
        ),
        "warning" => "select one please"
    ),
    array(
        "label" => "Select One:",
        "type" => "radio",
        "name" => "sex",
        "id" => "sex",
        "class" => "",
        "options" => array(
            "Male",
            "Female",
            "Not Specified"
        ),
        "warning" => "Please select a sex."
    ),
    array(
        "label" => "Select Some:",
        "type" => "checkbox",
        "name" => "times",
        "id" => "times",
        "class" => "",
        "options" => array(
            "Morning",
            "Lunch Time",
            "Afternoon",
            "Dinner Time"
        ),
        "warning" => "Please select a time."
    )
    
);

$errors = array(false, false, false, false, false, false, false);

createForm($formname, $action, $formelements, $errors);


?>
