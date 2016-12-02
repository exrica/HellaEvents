<div id="registerform">
    
<?php
//submitted
if (isset($_POST['submit'])) { // Handle the form.
    
	$validemail = filter_var(escape_data($_POST['email']), FILTER_VALIDATE_EMAIL);
        $emailmsg = $validemail ? '' : 'Please enter your email address.';
        
        $validuser = strlen($_POST['username']) > 3;
        $usernamemsg = $validuser ? '' : 'Username must be at least 4 characters.';
      
        $validpass = strlen($_POST['password1']) > 7;
        $passmsg = $validpass ? '': 'Password must be at least 8 characters.';

        $validconfpass = escape_data($_POST['password1']) == escape_data($_POST['password2']);
        $confmsg = $validconfpass? '' : 'Does not match password.';
              
	if ($validemail && $validuser && $validpass && $validconfpass) {
                              
            $username = escape_data($_POST['username']);
            //encrypt password
            $password = hash('sha256', escape_data($_POST['password1']));
            $email = escape_data($_POST['email']);
     
            if (userExists($username)) {
                $validuser = false;
                $usernamemsg = 'The username is already in use, please choose another.';
                displayRegForm($emailmsg, $usernamemsg, $passmsg, $confmsg);    
                
            } else {
                $result = addMember($username, $password, $email);

                if ($result) {
                    
                    echo '<p>Thank you for registering!</p>';
                    echo '<p>Please <a href="?page=editprofile">complete your profile</a></p>';
                
                }
                else {
                    echo '<p>' . "Execute failed: (" . $stmt->errno . ") " . $stmt->error . '</p>';
                }
            }
            
	} else {
            displayRegForm($emailmsg, $usernamemsg, $passmsg, $confmsg);
        }
       
} else { // Display the form.
    displayRegForm('', '', '', '');
} ?>
</div>

<?php 
//registration form
function displayRegForm($emailmsg, $usernamemsg, $passmsg, $confirmmsg) { ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=register" method="post">
        
        <fieldset>
            <legend>Create your account:</legend>
            <p>
                Email Address: 
                <input type="text" name="email" size="40" maxlength="60" value="<?php if 
                    (isset($_POST['email'])) echo escape_data($_POST['email']); ?>"  /> 
                <span class="formwarning"><?php echo $emailmsg; ?></span>
            </p>

            <p>
                Username: 
                <input type="text" name="username"
                    size="20" maxlength="32" value="<?php if (isset($_POST['username'])) 
                    echo escape_data ($_POST['username']); ?>"  />
                <span class="formwarning"><?php echo $usernamemsg; ?></span>

            </p>

            <p>
                Password: 
                <input type="password" name="password1" size="20" maxlength="64" />
                <span class="formwarning"><?php echo $passmsg; ?></span>
            </p>

            <p>
                Confirm Password: 
                <input type="password" name="password2" size="20" maxlength="64" />
                <span class="formwarning"><?php echo $confirmmsg; ?></span>
            </p>

        <div class="submitbutton">
            <input type="submit" name="submit" value="Submit" />
        </div>
        </fieldset>

    </form>
<?php } ?>