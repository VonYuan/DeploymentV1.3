<?php
session_start(); //start the session
$host = 'gasmeter.mysql.database.azure.com';
$username = 'gasmeter';
$password = 'AdminLogin123';
$db_name = 'ocawbms';

//Initializes MySQLi
$link = mysqli_init();

mysqli_ssl_set($link,NULL,NULL, "/var/www/html/DigiCertGlobalRootCA.pem", NULL, NULL);

// Establish the connection
mysqli_real_connect($link, 'gasmeter.mysql.database.azure.com', 'gasmeter', 'AdminLogin123', 'ocawbms', 3306, NULL, MYSQLI_CLIENT_SSL);

//If connection failed, show the error
if (mysqli_connect_errno())
{
    die('Failed to connect to MySQL: '.mysqli_connect_error());
}

?>

