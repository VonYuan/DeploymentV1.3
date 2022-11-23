<?php
require_once '../../Config.php';
$oid=$_POST['user_id'];
$accountNum=$_POST['accountNum'];

$deleteQuery="Delete FROM tenant WHERE owner_id='$oid' AND owner_account='$accountNum'";
$deleteResult=mysqli_query($link, $deleteQuery);

header("Location:Tenant.php");

?>