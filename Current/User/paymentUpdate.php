<?php 
require_once '../../Config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';



require_once '../../vendor/autoload.php';
require_once '../../vendor/stripe/stripe-php/init.php';
$mail = new PHPMailer(true);
\Stripe\Stripe::setApiKey('sk_test_51M7Jt5J9tfJYUGMWi4JJDLwBnJ9piycv4vsGFaIPoSwVAy2Xk5d8HNT3FOX6aQpgeUvum9wc8FTriZBpHvo4iqV2009TwqWe0Q');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $due_month=$_POST['month'];
    $currentmonth=date_create($due_month);
    date_sub($currentmonth,date_interval_create_from_date_string("1 months"));
    $previous_month=date_format($currentmonth,"Y-m");
    #echo $previous_month;
    
    $user_id=$_POST['userid'];
    $accountNum=$_POST['accountNum'];
    $token = $_POST['stripeToken'];
    $name = $_POST['pay_name'];
    $nic = $_POST['pay_nic'];
    $amount = $_POST['pay_amount'];
    
    $sql_user = "SELECT * FROM users WHERE user_id='" . $user_id . "'";
    $records_user = mysqli_query($link, $sql_user);
    $data_user = mysqli_fetch_assoc($records_user);
    
   
    $sql = "INSERT INTO current_pay (user_id, pay_name, pay_nic, pay_amount, token, month) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $link->prepare($sql)){
        $stmt->bind_param("isssss",$user_id, $name, $nic, $amount ,$token, $due_month);

        $param_userid = $user_id;
            $param_name = $name;
            $param_nic = $nic;
            $param_amount = $amount;
            $param_token = $token;
            $param_month = $due_month;
             

            #can work
            $update = "UPDATE current_bill SET status ='Not Paid' WHERE user_id = '$user_id' AND month = '$due_month'";
            #cant work
             #$update = "UPDATE current_bill SET status ='Paid' WHERE user_id = '$uid' AND month = '$due_month' AND user_account= '$accountNum'";
            

            $message = "Paid the bill for $accountNum through online.";
            $activity = "INSERT INTO activity_log (user_id, message) VALUES ('$user_id', '$message')";
            $currentotal="SELECT total FROM current_bill WHERE user_account= '$accountNum' AND month= '$due_month' ";
            $curren_charge="SELECT charge FROM current_bill WHERE user_account= '$accountNum' AND month= '$due_month' ";
            
        
            $totalquery=mysqli_query($link,$currentotal);
            $totalminus=mysqli_fetch_array($totalquery);
            $totalafterpay=$totalminus[0] - $amount;
            $updatetotal = "UPDATE current_bill SET total = '$totalafterpay' WHERE user_id = '$user_id' AND month = '$due_month' AND user_account= '$accountNum'";
        
            $chargequery=mysqli_query($link,$curren_charge);
            $chargeminus=mysqli_fetch_array($chargequery);
            $chargeAmount=$chargeminus[0] - $amount;
            $updatcharge = "UPDATE current_bill SET charge = '$chargeAmount' WHERE user_id = '$user_id' AND month = '$due_month' AND user_account= '$accountNum'";
           
            $updatestatusPaid="UPDATE current_bill SET status = 'Paid' WHERE user_id = '$user_id' AND month = '$due_month' AND user_account= '$accountNum'";
        
            $updatestatusOverPaid="UPDATE current_bill SET status = 'Over Paid' WHERE user_id = '$user_id' AND month = '$due_month' AND user_account= '$accountNum'";
        
            $updatestatusPartlyPaid="UPDATE current_bill SET status = 'Partly Paid' WHERE user_id = '$user_id' AND month = '$due_month' AND user_account= '$accountNum'";
        
            $updateamountPay="UPDATE current_bill SET amount_pay = '$amount' WHERE user_id = '$user_id' AND month = '$due_month' AND user_account= '$accountNum'";
        
            $checkprevious="SELECT * FROM current_bill WHERE user_id = '$user_id' AND month = '$previous_month' AND user_account= '$accountNum'";
            $checkpreviousResult=mysqli_query($link,$checkprevious);
            $PreviousDetail=mysqli_fetch_array($checkpreviousResult);
        
            $updatePreviousTotal="UPDATE current_bill SET total = 0 WHERE user_id = '$user_id' AND month = '$previous_month' AND user_account= '$accountNum'";
            
           
            
        
            $updatestatusPreviousStatus="UPDATE current_bill SET status = 'Paid' WHERE user_id = '$user_id' AND month = '$previous_month' AND user_account= '$accountNum'";

        if ($stmt->execute() && mysqli_query($link, $update)) {
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = "ocawbms2021@gmail.com";
                $mail->Password = "OEAWBMS2021";
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port = 587;

                //Recipients
                $mail->setFrom("ocawbms2021@gmail.com", "OCAWBMS");
                $mail->addAddress($data_user['user_email']);     // Add a recipient

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Online Gas Bill Management System';

                $mail->Body    = "<h3>Dear ".$data_user['user_name'].",</h3><br>You have successfully paid the amount. Here's your payment information,<br>
                Name: $name <br>
                NIC Number: $nic <br>
                Amount: $amount <br>
                Bill Month: $due_month <br>";
               

                $mail->send();

            } catch (Exception $e) {
                
                 echo 'Something went wrong,try again later ';
                
                header("Location:User-Dashboard.php");
            }
             
           
            
            mysqli_query($link,$activity);
            mysqli_query($link,$updatetotal);
            mysqli_query($link,$updatcharge);
            mysqli_query($link,$updateamountPay);
          
            if($totalafterpay==0)
            {
                mysqli_query($link,$updatestatusPaid);
                if(!empty($PreviousDetail))
                {
                    if ($PreviousDetail['status']!="Over Paid")
                    {
                        mysqli_query($link,$updatestatusPreviousStatus);

                    }else
                    {
                        mysqli_query($link,$updatePreviousTotal);
                    }
                        
                }
                    
                
            }elseif($totalafterpay<=0)
            {
                mysqli_query($link,$updatestatusOverPaid);
                mysqli_query($link,$updatestatusPreviousStatus);

            }
            else
            {
                
                mysqli_query($link,$updatestatusPartlyPaid);
                mysqli_query($link,$updatestatusPreviousStatus);

 
            }
            mysqli_close($link);
            echo '<br/> <div class="alert alert-success alert-dismissible fade show" role="alert" style="top:60px;left:0;right:0;position:fixed;"><strong>';
            echo 'You have successfully did the payment for <strong>'.$due_month.'</strong>';
            echo 'You have successfully did the payment for <strong>'.$accountNum.'</strong>';
            echo ' </strong> <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"> </button> </div>';            
            exit();
            
        } else {
            echo "Something went wrong when executing. Please try again later.";
        }
    }

    else {
        echo "Something went wrong when preparing.";
    }

        exit();

}

?>




?>