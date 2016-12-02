<div id="loginform">
<?php
if (isset($_POST['submit'])) { // Handle the form.
    
        $validuser = strlen($_POST['username']) > 0;
        $usernamemsg = $validuser ? '' : 'Please enter your username.';

        $validpass = strlen($_POST['password']) > 0;
        $passmsg = $validpass ? '': 'Please enter your password.';

              
	if ($validuser && $validpass) {
            //clean and encrypt
            $user = escape_data($_POST['username']);
            $password = hash('sha256', escape_data($_POST['password']));
            
            //get login
            $result = getLogin($user, $password);
            $numrows = mysqli_num_rows($result);
            
            if ($numrows == 1) {
                //regenerate the session id
                session_regenerate_id();
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                //set session variables
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION["loggedin"] = true;
                $_SESSION["userid"] = $row['userID'];
                //redirect
                echo "<script> setTimeout (\"window.location='index.php'\", 1500); </script>";
                echo "<p>You are now logged in... you will now be returned to the main index.</p>";
            } else {
                //failed login, destroy the session
                session_destroy();
                echo '<p class="formwarning">The username and/or password provided did not match our records.</p>';
                displayLoginForm('', '');
            }

	} else {
            displayLoginForm($usernamemsg, $passmsg);
        }

} else { // Display the form.
    displayLoginForm('', '');
} ?>
</div>

<?php 
//custom login form for this page only

function displayLoginForm($usernamemsg, $passmsg) { ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=login" method="post">
        
        <fieldset>
            <legend>Log in:</legend>

            <p>
                Username: 
                <input type="text" name="username"
                    size="20" maxlength="32" value="<?php if (isset($_POST['username'])) 
                    echo $_POST['username']; ?>"  />
                <span class="formwarning"><?php echo $usernamemsg; ?></span>
            </p>

            <p>
                Password: 
                <input type="password" name="password" size="20" maxlength="64" />
                <span class="formwarning"><?php echo $passmsg; ?></span>
            </p>

            <div class="submitbutton">
                <input type="submit" name="submit" value="Log In" />
            </div>
        </fieldset>

    </form>
<?php } ?>
