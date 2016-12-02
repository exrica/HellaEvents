<div>

    <?php 
    $profileid = (int)escape_data($_GET['mid']);
    if ($loggedin) {
        //check if the user is viewing their own profile
        $edit = $currentuser == $profileid && $rank > 1 ? true : false;
    } else {
        $edit = false;
    }
    
    //get profile from database
    $result = getProfileData($profileid);
    $profile = mysqli_fetch_array($result, MYSQLI_ASSOC);

    //get profile data
    $displayname = $profile['displayname'];
    $fullname = $profile['fname'] . " " . $profile['lname'];
    $fullname = strlen($fullname) > 1 ? $fullname : "Not Set";
    $birthday = isset($profile['birthday']) ? verbose_date($profile['birthday'], false) : "Not Set";
    $gender = isset($profile['gender']) ? verbose_gender(($profile['gender'])) : "Not Set";
    $bio = isset($profile['bio']) ? ($profile['bio']) : "Not Set";

    echo "<p>";
    //edit member role button (admins+)
    if ($rank >= ADMIN_RANK && !$edit) {
        echo '<a class="button" href="?page=editmemberaccess&amp;mid='
        .$profileid
        . '">Edit Member Role</a>';
    }
    //edit profile button on users's own profile
    echo $edit ? " <a class='button' href='?page=editprofile&amp;mid=$currentuser'>Edit Profile</a>" : "";
    echo $edit ? " <a class='button' href='?page=changepass'>Change Password</a>" : "";
    echo "</p>";
    echo "<h3>About: $displayname</h3>";

    //coordinators+ can see real name
    if ($rank >= COORDINATOR_RANK) {
        echo "<p class='lpad'>Real Name: $fullname</p>";
    }
    //members can see profile data
    if ($rank >= MEMBER_RANK) {
        echo "<p class='lpad'>Birthday: $birthday</p>";
        echo "<p class='lpad'>Gender: $gender</p>";
        echo "<h3>Bio</h3>";
        echo "<p class='lpad'>$bio</p>";
    } else {
        echo "<p class='lpad'>You must be signed in you view this profile.</p>";
    }
  
    ?>
  
</div>