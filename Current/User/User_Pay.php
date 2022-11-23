<?php
require_once '../User/User-Header.php';
$text=$_GET['billmonth'];
$accountNum=$_GET['accountNum'];
#$uid = $_SESSION['user_id'];
$uid=$_GET['user_id'];
$sql_month = "SELECT * FROM bill_month";
$records_month = mysqli_query($link, $sql_month);
$data_month = mysqli_fetch_assoc($records_month);
$due_month = $data_month['month'];

$sql_user = "SELECT * FROM users WHERE user_id='" . $uid . "'";
$records_user = mysqli_query($link, $sql_user);
$data_user = mysqli_fetch_assoc($records_user);

$sql_method = "SELECT * FROM current_details WHERE user_id='" . $uid . "' AND user_account= '$accountNum'";
$records_method = mysqli_query($link, $sql_method);
$data_method = mysqli_fetch_assoc($records_method);

$sql_bill = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND month = '$due_month'";
$records_bill = mysqli_query($link, $sql_bill);
$one_bill = mysqli_fetch_assoc($records_bill);
echo $uid;

$totalpay=$_GET['totalpay'];





require_once '../../vendor/autoload.php';
require_once '../../vendor/stripe/stripe-php/init.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

\Stripe\Stripe::setApiKey('sk_test_51I2wZ8EG7KGMl4QwyFek7A5Tdi5HmY1zhvfDZXF3tOg5nmEthyYa0TiQqhU36ElpmdQYHdrvRC4ywfzOJZQEWi1p00U56ikRwn');

$user_id = $uid;

/*$sql_month = "SELECT * FROM bill_month";
$records_month = mysqli_query($link, $sql_month);
$data_month = mysqli_fetch_assoc($records_month);
$due_month = $data_month['month'];*/




$testmonth=$text;

#$sql_month = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND month = '$testmonth' AND user_account = '$accountNum'";
$sql_month = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND month = '$testmonth'";
$records_month = mysqli_query($link, $sql_month);
$data_month = mysqli_fetch_assoc($records_month);
$bill = mysqli_fetch_assoc($records_month);
$due_month =  $data_month['month'];

#$sql_detail = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND month = '$testmonth' AND month = '$testmonth'";

#$test=$data_month['meter'];
//$one_bill = mysqli_fetch_assoc($records_month);
//$due_months =  "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND month = '2023-03' ";

$name = $nic = $amount = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
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
            $update = "UPDATE current_bill SET status ='Not Paid' WHERE user_id = '$uid' AND month = '$due_month'";
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
    
            $updatecreditamount="UPDATE current_bill SET credit = '0' WHERE user_id = '$user_id' AND month = '$due_month' AND user_account= '$accountNum'";
           

            
           

        
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
                

            }
           
            
            mysqli_query($link,$activity);
            mysqli_query($link,$updatetotal);
            mysqli_query($link,$updatcharge);
            mysqli_query($link,$updateamountPay);
            mysqli_query($link, $updatecreditamount);  
          
            if($totalafterpay==0)
            {
                mysqli_query($link,$updatestatusPaid);
                mysqli_query($link,$updatestatusPreviousStatus);

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
<link rel="stylesheet" href="../Pay/css/style.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />

<div class="row justify-content-center wrapper">
    <div class="col-lg-12 p-4 pt-12" style="background-color: #E5E4E2;">
        <div class="row gutters-sm">

            <?php 
            
            
            
                    $sql_one_bill = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND month = '$due_month' AND user_account = '$accountNum'";
                    $results_bill = mysqli_query($link, $sql_one_bill);
                    if($bill = mysqli_fetch_assoc($results_bill)){
                        ?>
            <div class=" col-md-5 mb-3"><br>
                <div class="card border shadow-lg p-2">
                    <?php
                    if($bill['status'] == 'Not Paid' OR $bill['status'] == 'Partly Paid'){
                        ?>
                    <h2 class="align-items-center text-center">Online Payment</h2>
                    
                    <div class="card-body">
                        <p>*Fill required information of the person who is paying.</p>
                        <form action="paymentUpdate.php?billmonth="<?php echo $text?> method="POST"
                            id="payment-form">
                            <div class="form-group">
                                <label>Bill Owner Name</label>
                                <input type="text" class="form-control" value="<?php echo $data_method['name']; ?>"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label>User Account Name</label>
                                <input type="text" class="form-control" value="<?php echo $data_user['user_name']; ?>"
                                    disabled>
                            </div>
                            
                            <div class="form-group">
                                <label>User Account Number</label>
                                <input type="text" class="form-control" value="<?php echo $accountNum; ?>"
                                    disabled>
                                
                                <input type="text" class="form-control" name="accountNum" value="<?php echo $accountNum; ?>"
                                    hidden>
                            </div>
                            
                            

                            <div class="form-group">
                                <label>Bill Month</label>
                                <input type="text" class="form-control" value="<?php echo $due_month; ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="pay_name" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>NIC Number</label>
                                    <input type="text" class="form-control" name="pay_nic" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="user_email"
                                        value="<?php echo $data_user['user_email'];?>" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Amount(Rs.)</label>
                                <input type="text" class="form-control" name="pay_amount" required>
                            </div>
                            <div class="form-group">
                                <label>Card Details</label>
                                <div class="form-row">

                                    <div id="card-element" class="form-control">
                                        <!-- a Stripe Element will be inserted here. -->
                                    </div>
                                    <!-- Used to display form errors -->
                                    <div id="card-errors" role="alert"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <input type="text" class="form-control" name="userid" value="<?php echo $uid; ?>"hidden>
                                <input type="text" class="form-control" name="month" value="<?php echo $due_month; ?>"hidden>
                                <button>Confirm Payment</button>
                            </div>
                        </form>
                    </div>
                    <?php
                    }

                    else if($bill['status'] == 'Over Paid'){
                        $sql_pay = "SELECT * FROM current_pay WHERE user_id='" . $uid . "' AND month = '$due_month'";
                        $results_pay = mysqli_query($link, $sql_pay);
                        $bill_pay = mysqli_fetch_assoc($results_pay);
                        ?>
                    <h2 class="align-items-center text-center">Online Payment for <?php echo $bill_pay['month'] ?></h2>
                    <div class="card-body">
                        <div class="alert alert-success" role="alert">
                            
                            <?php echo $bill_pay['month'] ?>
                            <h5 class="align-items-center text-center">Bill Payment is Done&nbsp;<i
                                    class="fa fa-check-circle-o" aria-hidden="true"></i></h5>
                            You have paid the bill for <?php echo $bill_pay['month']  ?>.
                            You will recieve an email about your payment information and you can
                            see it from here as well.
                        </div>
                        <div>
                            <div class="form-group">
                                <label>Bill Owner Name</label>
                                <input type="text" class="form-control" value="<?php echo $data_method['name']; ?>"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label>User Account Name</label>
                                <input type="text" class="form-control" value="<?php echo $data_user['user_name']; ?>"
                                    disabled>
                            </div>
                            
                            <div class="form-group">
                                <label>User Account Number</label>
                                <input type="text" class="form-control" value="<?php echo $accountNum; ?>"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label>Bill Month</label>
                                <input type="text" class="form-control" value="<?php echo $due_month; ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="<?php echo $bill_pay['pay_name'] ?>"
                                    disabled>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>NIC Number</label>
                                    <input type="text" class="form-control" value="<?php echo $bill_pay['pay_nic'] ?>"
                                        disabled>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="user_email"
                                        value="<?php echo $data_user['user_email'];?>" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Amount(Rs.)</label>
                                <input type="text" class="form-control" value="<?php echo $bill_pay['pay_amount'] ?>"
                                    disabled>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                        else if($bill['status'] == 'Paid'){
                        $sql_pay = "SELECT * FROM current_pay WHERE user_id='" . $uid . "' AND month = '$due_month'";
                        $results_pay = mysqli_query($link, $sql_pay);
                        $bill_pay = mysqli_fetch_assoc($results_pay);
                        ?>
                    <h2 class="align-items-center text-center">Online Payment for <?php echo $bill_pay['month'] ?></h2>
                    <div class="card-body">
                        <div class="alert alert-success" role="alert">
                            
                            <?php echo $bill_pay['month'] ?>
                            <h5 class="align-items-center text-center">Bill Payment is Done&nbsp;<i
                                    class="fa fa-check-circle-o" aria-hidden="true"></i></h5>
                            You have paid the bill for <?php echo $bill_pay['month']  ?>.
                            You will recieve an email about your payment information and you can
                            see it from here as well.
                        </div>
                        <div>
                            <div class="form-group">
                                <label>Bill Owner Name</label>
                                <input type="text" class="form-control" value="<?php echo $data_method['name']; ?>"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label>User Account Name</label>
                                <input type="text" class="form-control" value="<?php echo $data_user['user_name']; ?>"
                                    disabled>
                            </div>
                            
                            <div class="form-group">
                                <label>User Account Number</label>
                                <input type="text" class="form-control" value="<?php echo $accountNum; ?>"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label>Bill Month</label>
                                <input type="text" class="form-control" value="<?php echo $due_month; ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="<?php echo $bill_pay['pay_name'] ?>"
                                    disabled>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>NIC Number</label>
                                    <input type="text" class="form-control" value="<?php echo $bill_pay['pay_nic'] ?>"
                                        disabled>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="user_email"
                                        value="<?php echo $data_user['user_email'];?>" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Amount(Rs.)</label>
                                <input type="text" class="form-control" value="<?php echo $bill_pay['pay_amount'] ?>"
                                    disabled>
                            </div>
                        </div>
                    </div>
                        
                    ?>
                    
                    <?php
                    }
                     ?>


                </div>
            </div>
            <div class="col-md-7"><br>
                

                <div class="card border shadow-lg mb-3 p-2">
                    
                    

                    <h2 class="align-items-center text-center">Gas Bill <?php echo $bill['month'] ?></h2>
                    
                    <div class="card-body">
                        <div class="px-3 needs-validation">
                            <div class="form-row">
                                <!--<diV class="form-group col-md-2"><img src="../../images/ceb_bill.png"></diV>-->
                                <div class="form-group col-md-10 p-2">
                                    <br>
                                    <h4 style="text-align: center;">Petros Statement of Gas
                                        Account <h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="<?php echo $data_method['name'] ?>"
                                    disabled>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control"
                                    value="<?php echo $data_method['user_address'] ?>" disabled>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Gas Account Number</label>
                                    <input type="text" class="form-control"
                                        value="<?php echo $data_method['user_account'] ?>" disabled>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Category</label>
                                    <input type="text" class="form-control"
                                        value="<?php echo $data_method['category'] ?>" disabled>
                                </div>
                            </div>
                            <div class="form-row">
                                      <?php
                                      $currentmonth=date_create($bill['month']);
                                      date_sub($currentmonth,date_interval_create_from_date_string("1 months"));
                                      $previous_month=date_format($currentmonth,"Y-m");
                                      
                                     $sql_previous = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND month='$previous_month' AND user_account = '$accountNum'";
                                      
                                    $records_previousbill = mysqli_query($link, $sql_previous);   
                                    $previousmonthbill = mysqli_fetch_assoc($records_previousbill);
                                    
                                    if(!empty($previousmonthbill['meter']))
                                    {
                                       $previousmeter=$previousmonthbill['meter'];
                                       
                                    }else
                                    {
                                        $previousmeter=0;
                                        
                                    }
                                    
                                    if(!empty($previousmonthbill['status']))
                                    {
                                        if($previousmonthbill['status']=="Paid" || $previousmonthbill['status']=="Over Paid")
                                        {
                                            $lastmonth_charge=0;
                                        }else
                                        {
                                            $lastmonth_charge=$previousmonthbill['charge'];
                                        } 

                                         if($previousmonthbill['status']=="Over Paid")
                                        {
                                            $overpaid_amount= abs($previousmonthbill['total']);
                                                       
                                           $updatecredit="UPDATE current_bill SET credit = '$overpaid_amount' WHERE user_id = '$user_id' AND month = '$due_month' AND user_account= '$accountNum'";
                                           mysqli_query($link, $updatecredit);  

                                        }else
                                        {
                                            $overpaid_amount=0;
                                        }  
                                    }else
                                    {
                                        $lastmonth_charge=0;
                                        $overpaid_amount=0;
                                    }
       
                        
                                
                                    ?>
                                    <div class="form-group col-md-4">
                                      <label>Last Month Meter Reading</label>
                                         <input type="text" class="form-control" value="<?php echo $previousmeter?>" disabled>
                                     </div>                                 
                                     <div class="form-group col-md-4">
                                         <label>Charge Consumed for 
                                             Last Month (RM)</label>
                                         <input type="text" class="form-control" value="<?php echo $lastmonth_charge?>" disabled>
                                     </div>
                                    <div class="form-group col-md-4">
                                         <label>Over Paid Amount(RM)</label>
                                         <input type="text" class="form-control" value="<?php echo $overpaid_amount?>" disabled>
                                     </div>
                            </div>
                                    
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Meter Reading</label>
                                    <input type="text" class="form-control" value="<?php echo $bill['meter']#php echo $one_bill['meter'] ?>"
                                        disabled>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Units Consumed for Month
                                        <?php echo date('F')?></label>
                                    <input type="text" class="form-control" value="<?php echo$bill['units'] ?>"
                                        disabled>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Charge for Gas Consumed (mmbtu) For the Month</label>
                                    <input type="text" class="form-control" value="<?php echo $bill['charge'] ?>"
                                        disabled>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Total Amount Due (RM.)</label>
                                    <input type="text" class="form-control" value="<?php echo $totalpay?>"
                                        disabled>
                                </div>
                            </div>

                                    
                            
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Updated</label>
                                    <input type="text" class="form-control" value="<?php echo $bill['updated_at']?>"
                                        disabled>

                                </div>

                                <div class="form-group col-md-6">
                                    <label>Due Date</label>
                                    <input type="date" class="form-control" value="<?php echo $bill['due'] ?>"
                                        disabled>
                                </div>
                            </div>
                            <div class="form-group">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    
                }
                    ?>
        </div>

        <div class="row gutters-sm">
            <div class=" col-md-14 mb-3"><br>
                <div class="card border shadow-lg p-2">
                    <h2 class="align-items-center text-center">Previous Payments</h2>
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-hover" style="font-size: 14px;" id="payTable">
                                <thead style="font-weight: bold;font-size: 16px;">
                                    <tr style="text-align: center;">
                                        <td style="text-align: center;">Month</td>
                                        <td style="text-align: center;">Name</td>
                                        <td style="text-align: center;">NIC</td>
                                        <td style="text-align: center;">Bill</td>
                                        <td style="text-align: center;">Paid Amount</td>
                                        <td style="text-align: center;">Paid</td>
                                    </tr>
                                </thead>
                                <?php
                    $records = mysqli_query($link,"SELECT * FROM current_pay WHERE user_id = '$uid'");

                    while($data=mysqli_fetch_array($records)){
                    ?>
                                <tr style="text-align: center;">
                                    <td><?php echo $data['month']; ?></td>
                                    <td><?php echo $data['pay_name']; ?></td>
                                    <td><?php echo $data['pay_nic']; ?></td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#<?php echo $data['token']; ?>">View&nbsp;<i
                                                class="fa fa-file-text" aria-hidden="true"></i></button>

                                    </td>
                                    <div class="modal fade" id="<?php echo $data['token']; ?>" tabindex="-1"
                                        aria-labelledby="<?php echo $data['token']; ?>Label" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h4 class="modal-title" id="<?php echo $data['token']; ?>Label"
                                                        style="color: white;">
                                                        Bill</h4>
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <i class="fa fa-times" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="px-3 needs-validation">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-2"><img
                                                                    src="../../images/petros.jpg"></div>
                                                            <div class="form-group col-md-12 p-2">
                                                                <h5 style="text-align: center;">Petros Statement of Gas Account<h5>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" class="form-control"
                                                                value="<?php echo $data_method['name'] ?>" disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address</label>
                                                            <input type="text" class="form-control"
                                                                value="<?php echo $data_method['user_address'] ?>"
                                                                disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Gas Account Number</label>
                                                            <input type="text" class="form-control"
                                                                value="<?php echo $data_method['user_account'] ?>"
                                                                disabled>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <input type="text" class="form-control"
                                                                value="<?php echo $data_method['category'] ?>" disabled>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Meter Reading</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $one_bill['meter'] ?>" disabled>
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Units Consumed for Month
                                                                    <?php echo date('F')?></label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $one_bill['units'] ?>" disabled>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Charge For the Month(Rs.)</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $one_bill['charge'] ?>" disabled>
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Total Amount Due (Rs.)</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $one_bill['total'] ?>" disabled>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Updated</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $one_bill['updated_at']?>"
                                                                    disabled>

                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Due Date</label>
                                                                <input type="date" class="form-control"
                                                                    value="<?php echo $one_bill['due'] ?>" disabled>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-dark"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <td>
                                        <div class="btn btn-success"><?php echo $data['pay_amount']; ?></div>
                                    </td>
                                    <td><?php echo $data['paid_at']; ?></td>
                                </tr>

                                <?php
                    }

                   ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="../../vendor.bundle.base.js"></script>
<script type="text/javascript"
    src="https://cdn.datatables.net/v/bs4/dt-1.10.24/r-2.2.7/sc-2.0.3/sp-1.2.2/datatables.min.js"></script>
<script>
$(document).ready(function() {
    $('#payTable').DataTable();
});
</script>
<!-- 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script src="https://js.stripe.com/v3"></script>
<script src="../Pay/js/charge.js"></script>

<?php
require_once 'User-Footer.php';
?>