<?php
    $con = new mysqli('petrosbilling.mysql.database.azure.com', 'petrosadmin', 'AdminLogin123', 'petrosbilling');

if (isset($_GET['user_id']) && isset($_GET['month'])) {
    
    $user_id = $_GET['user_id'];
    $month = $_GET['month'];
	$query = "SELECT filename, image, filetype, size FROM image_upload WHERE user_id = $user_id AND month = '$month'";
	$result = mysqli_query($con, $query) or die('Error, query failed');


    list($name, $content, $type, $size) = mysqli_fetch_array($result);
    header("Content-Disposition: attachment; filename=$name");
    header("Content-length: $size");
    header("Content-type: $type");
    echo $content;


	exit;
}
else{
    mysqli_error($con);
}

?>