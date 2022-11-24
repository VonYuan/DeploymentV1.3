
<?php

    if(isset($_GET['user_account']))
    {
        $db = mysqli_connect("localhost","root","","ocawbms");
        mysqli_query($db,"DELETE FROM current_details WHERE user_account = '".$_GET['user_account']."'");
        mysqli_query($db,"DELETE FROM current_bill WHERE user_id = '".$_GET['user_account']."'");
        //mysqli_query($db,"DELETE FROM current_pay WHERE user_id = '".$_GET['user_id']."'");
        header("location:View-Users.php");
        exit();
    }

    //$dlt_uid = $data_user['user_id'];
    //echo $dlt_uid;
    //$dlt_currentbill = mysqli_query($db,"SELECT * FROM current_bill WHERE user_id = '$dlt_uid'");
    //$dlt_currentdetails = mysqli_query($db,"SELECT * FROM current_details WHERE user_id = '$dlt_uid'");
    //$dlt_currentpay = mysqli_query($db,"SELECT * FROM current_pay WHERE user_id = '$dlt_uid'");
    //$dlt_redbill = mysqli_query($db,"SELECT * FROM red_bill");
    //$dlt_users = mysqli_query($db,"DELETE FROM users WHERE user_id = '$dlt_uid'");
?>