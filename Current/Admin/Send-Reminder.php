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

$user_id = $_GET['user_id'];
$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $month = date("F Y");
    $message = $_POST['message'];
    $user_email = $_POST['user_email'];
    $user_name = $_POST['user_name'];
    $sql = "INSERT INTO notifications (user_id, month, message) VALUES ('$user_id', '$month', '$message')";
    
    if (mysqli_query($link, $sql)){
        header("Location: SendReminders.php?user_id=$user_id");
        
    }
    else{
        echo ("Something went wrong. Please try again later!".mysqli_error($link));
    }
}


?>