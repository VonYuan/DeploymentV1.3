<?php
 //start the session

define('DB_SERVER', 'petrosbilling.mysql.database.azure.com');
define('DB_USERNAME', 'petrosadmin');
define('DB_PASSWORD', 'AdminLogin123');
define('DB_NAME', 'petrosbilling');
 
$link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME ); //connect to the database
 
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>