<?php
require_once '../../Config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

// Load Composer's autoloader
require '../../vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

$sql_month = "SELECT * FROM bill_month";
$records_month = mysqli_query($link, $sql_month);
$data_month = mysqli_fetch_assoc($records_month);
$bill_month = $data_month['month'];
$bill_due_date = $data_month['Due_Date'];


$user_id = $_GET['user_id'];
$user_email = $_POST['user_email'];
$accountNum = $_POST['accountNum'];
$meter = $units = $charge = $total = $month = "";
$meter_err = $units_err = $charge_err = $total_err = "";


if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (!preg_match("/^[0-9]*$/",$meter)){
        $meter_err = "Only numbers are allowed!";
    }
    else{
        $meter = $_POST["meter"];
    }

    #if (!preg_match("/^[0-9]*$/",$units)){
        #$units_err = "Only numbers are allowed!";
    #}
    #else{
        #$units = $_POST["units"];
    #}

    #if (!preg_match("/^[0-9]*$/",$charge)){
        #$charge_err = "Only numbers are allowed!";
    #}
    #else{
        #$charge = $_POST["charge"];
    #}

    #if (!preg_match("/^[0-9]*$/",$total)){
        #$total_err = "Only numbers are allowed!";
    #}
    #else{
        #$total = $_POST["total"];
    #}
    
    $prevmeter = $_POST["prevmeter"];
    
    $month = $bill_month;
    $due = $_POST['due'];
    $accountNum=$_POST['accountNum'];
    

     
    $units=$meter-$prevmeter;
    $charge=(((1.1756*36.24975830/1000)*$units*7.93));
    $charges=round($charge,0);
    
    $sql_totalamount="SELECT SUM(charge) FROM current_bill WHERE user_id= '$user_id' AND user_account='$accountNum'";
    $records_totalamount = mysqli_query($link, $sql_totalamount);
    $total = mysqli_fetch_array($records_totalamount);
    $total=$total[0]+$charges;
    
    
    
    

    $sql = "INSERT INTO current_bill (user_id, user_account, month, meter, units, charge,charge_current_Month,total,overall_payment, amount_pay,due) VALUES ('$user_id', '$accountNum', '$month', '$meter', '$units', '$charges','$charges', '$total','$total', '0','$bill_due_date')";
    

    mysqli_query($link,$sql);
    header("Location:View-Address.php");


}
?>