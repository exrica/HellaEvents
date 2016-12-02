<?php

//database login info
DEFINE ('DB_USER', 'user'); //replace "user" with db user
DEFINE ('DB_PASSWORD', 'pass'); //replace "pass" with db pass
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'dbname'); //replace "dbname" with database name

//open connection
$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die('Could not connect to MySQL: ' . mysqli_connect_error());
mysqli_set_charset($dbc, 'utf8');

?>
