<div id="editmemberaccess">

<?php
//get ranks
$ranksByName = getRanksArray();
$ranksByID = array_flip($ranksByName);

//get profile to change
$profileid = (int)escape_data($_GET['mid']);
$result = getProfileData($profileid);
$numrows = mysqli_num_rows($result);

if ($numrows > 0) {
    $profile = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $currentrank = getRank($profileid);
    $isbanned = $currentrank == 0;
    $currentRankName = $ranksByID[$currentrank];
    $displayname = $profile['displayname'];

    if ($rank >= ADMIN_RANK) {

        if ($currentuser == $profileid) {
            echo "<p>You cannot edit your own role.<p>";
        } else if ($currentrank >= $rank) {
            echo "<p>You do not have permission to edit this users's role.<p>";
        } else { 

            //admins can promote and demote event coords and below
            $rankchoice = $rank > ADMIN_RANK ? array("Member", "Event Coordinator", "Admin") : array("Member", "Event Coordinator");

            //show user info to confirm that they are editing the right user
            $fullname = $profile['fname'] . " " . $profile['lname'];
            $fullname = strlen($fullname) > 1 ? "($fullname)" : "";
            echo "<h4>Editing access for: <a href='?page=profile&amp;mid=$profileid'>$displayname $fullname</a></h4>";

            //create form
            $action = $_SERVER['PHP_SELF'] . "?page=$page&amp;mid=$profileid";
            $formname = "Edit Member Roles";

            $bantext = "Check to ban this member from the site.";
            $formelements = array(
                    array(
                    "label" => "Select Role:",
                    "type" => "select",
                    "name" => "memrole",
                    "id" => "memrole",
                    "class" => "",
                    "value" => $currentRankName,
                    "options" => $rankchoice,
                    "warning" => ""
                ),
                array(
                    "label" => "Ban Member:",
                    "type" => "checkbox",
                    "name" => "ban",
                    "id" => "ban",
                    "class" => "",
                    "value" => $isbanned ? $bantext : "",
                    "options" => array(
                        $bantext
                    ),
                    "warning" => ""
                )
            );

            //submitted
            if (isset($_POST['submit'])) { // Handle the form.
                $valid = true;  //not much to check
                $errors = createErrorArray($formelements);         
                    if ($valid) {
                        $newrank = escape_data($_POST['memrole']);
                        $newrankid = $ranksByName[$newrank];
                        if (isset($_POST['ban'])) {
                            $newrankid = BANNED_RANK;
                        }
                        $result = updateMemberRank($profileid, $newrankid);
                        if ($result)
                            echo '<p>Member role updated.</p>';
                    } else {
                        createForm($formname, $action, $formelements, $errors);
                    }
            } else {
                $errors = $errors = createErrorArray($formelements);
                createForm($formname, $action, $formelements, $errors);
            }
        }
    } else {
        echo "<p>You are not authorized to perform this function.</p>";
    }

} else {
    echo "<p>There was an error retrieving the member from the database.</p>";
}

?>

</div>
