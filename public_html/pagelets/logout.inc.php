<?php

//destroy session and reset cookie
$_SESSION = array(); 
session_destroy();
setcookie ('PHPSESSID', '', time()-3600, '/', '', 0, 0); 
//redirect to index
echo "<script> setTimeout (\"window.location='index.php'\", 1500); </script>";
echo "<p>You are now logged out... you will now be returned to the main index.</p>";

?>