<?php
session_start(); //start the session
$link = mysqli_init();
mysqli_ssl_set($link,NULL,NULL, "/var/www/html/DigiCertGlobalRootCA.crt.pem", NULL, NULL);
mysqli_real_connect($link, 'gasmeter.mysql.database.azure.com', 'gasmeter', 'AdminLogin123', 'ocawbms', 3306, MYSQLI_CLIENT_SSL);
if (mysqli_connect_errno($link)) {
die('Failed to connect to MySQL: '.mysqli_connect_error());
}

?>

