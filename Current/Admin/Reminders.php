<?php

require_once 'Admin-Header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    
require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
    
// Load Composer's autoloader
require '../../vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

function allUsers(){
    $db = new mysqli('localhost', 'root', '', 'ocawbms');
    $all = mysqli_query($db, "SELECT * FROM users");
    $all_users = mysqli_num_rows($all);
    return $all_users;
  }

$user_name = $user_gender = $user_nic = $user_email = $user_contact = $user_password = $confirm_password = $send_password = "";
$username_err = $password_err = $email_err = $confirm_password_err = $nic_err = $contact_err = $stat_err = "";
$name_err = $name = $user_account = $user_address = $user_area = $address_err = $area_err = $acc_err = $user_premises = $premises_err = "";
$stat = $_SESSION['var'] = 1;

    // Processing form data when form is submitted
    if (isset($_POST['submit_add'])) {
    
        // Validate user Username
        if (empty(trim($_POST["user_name"]))) {
            $username_err = "Please enter a username.";
        } else {
            // Prepare a select statement
            $sql = "SELECT user_id FROM users WHERE user_name = ?";
    
            if ($stmt = $link->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_username);
    
                // Set parameters
                $param_username = trim($_POST["user_name"]);
    
                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    /* store result */
                    $stmt->store_result();
    
                    if ($stmt->num_rows() >= 1) {
                        $username_err = "This Username is already taken.";
                    } else {
                        $user_name = trim($_POST["user_name"]);
                    }
                } else {
                    echo "Oops! Something went wrong when inserting username. Please try again later.";
                }
    
                // Close statement
                $stmt->close();
            }
        }
    
        //validate email
        if (empty(trim($_POST["user_email"]))) {
            $email_err = "Please enter an email address!";
        } else {
            $user_email = trim($_POST["user_email"]);
            $user_email = stripslashes($user_email);
            $user_email = htmlspecialchars($user_email);
            if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
                $email_err = "Invalid email format";
            } else {
                // Prepare a select statement
                $sql = "SELECT user_id FROM users WHERE user_email = ?";
    
                if ($stmt = $link->prepare($sql)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("s", $param_email);
    
                    // Set parameters
                    $param_email = $user_email;
    
                    // Attempt to execute the prepared statement
                    if ($stmt->execute()) {
                        /* store result */
                        $stmt->store_result();
    
                        if ($stmt->num_rows() >= 1) {
                            $email_err = "This Email Address is already taken.";
                        } else {
                            $user_email = trim($_POST["user_email"]);
                        }
                    } else {
                        echo "Oops! Something went wrong when inserting email. Please try again later.";
                    }
    
                    // Close statement
                    $stmt->close();
                }
            }
        }
    
        if (!preg_match("/^[0-9'V'v]*$/",strlen($_POST["user_nic"]))) {
            $nic_err = "Only Numbers and V or v allowed for old version and Only Numbers are allowed for new version";
        }else if (strlen($_POST["user_nic"])!=10 && strlen($_POST["user_nic"])!=12) {
            $nic_err = "NIC number is Invalid";
        }else{
            $user_nic = $_POST['user_nic'];
        }
    
        if (empty(trim($_POST["user_contact"]))) {
            $contact_err = "Please enter a Contact Number.";
        } elseif (strlen(trim($_POST["user_contact"])) != 10){
            $contact_err = "Invalid Contact Number.";
        } else {
            $user_contact = trim($_POST["user_contact"]);
            $send_contact = $user_contact;
        }
    
        // Validate password
        if (empty(trim($_POST["user_password"]))) {
            $password_err = "Please enter a password.";
        } elseif (strlen(trim($_POST["user_password"])) < 6) {
            $password_err = "Password must have atleast 6 characters.";
        } else {
            $user_password = trim($_POST["user_password"]);
            $send_password = $user_password;
        }
    
        // Validate confirm password
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm password.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($user_password != $confirm_password)) {
                $confirm_password_err = "Password did not match.";
            }
        }
    
        $user_gender = $_POST['gender'];
    
        // Check input errors before inserting in database
        if (empty($username_err) && empty($email_err) && empty($nic_err) && empty($contact_err) && empty($password_err) && empty($confirm_password_err)) {
    
    
            // Prepare an insert statement
            $sql = "INSERT INTO users (user_name, gender, user_nic, user_email, user_contact, user_password) VALUES (?, ?, ?, ?, ?, ?)";
    
            if ($stmt = $link->prepare($sql)) {
    
    
                // Bind variables to the prepared statement as parameters
                if ($stmt->bind_param("ssssss", $param_username,$param_gender, $param_nic, $param_email, $param_contact, $param_password))
    
                    // Set parameters
                $param_username = $user_name;
                $param_gender = $user_gender;
                $param_nic = $user_nic;
                $param_email = $user_email;
                $param_contact = $user_contact;
                $param_password = password_hash($user_password, PASSWORD_DEFAULT); // Creates a password hash
    
    
                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
    
                    try {
                        //Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = "ocawbms2021@gmail.com";
                        $mail->Password = "OCAWBMS2021";
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                        $mail->Port = 587;
    
                        //Recipients
                        $mail->setFrom("ocawbms2021@gmail.com", "OCAWBMS");
                        $mail->addAddress($user_email);     // Add a recipient
    
                        // Content
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Online Gas Billing  System';
    
                        $mail->Body    = "<h3>Welcome to the Online Gas Billing System</h3><br><br>Your user account has been created succesfully.<br><br> Here's your account information:<br>
                         Username: $user_name <br>
                         Gender: $user_gender<br>
                         NIC: $user_nic<br>
                         Email: $user_email<br>
                         Contact No: $user_contact<br>
                         <br> Best Regards, <br> OCEWBMS Team";
    
                        //$mail->send();
                        //  echo $user->showwMessage('success','We have send you  reset link,please check your email');
    
                    } catch (Exception $e) {
                         echo 'Something went wrong,try again later';
    
                    }
                    $user_name = $user_nic = $user_contact = $user_email = "";
                    echo "<script> location.href='User-Added.php'";
                    #header("Location:User-Added.php");
                    exit();
                } else {
    
                    echo "Something went wrong when executing. Please try again later.";
                }
    
                // Close statement
                $stmt->close();
            }
        }
    
        // Close connection
        $link->close();
    }


    if (isset($_POST['submit_regi'])) {

        // Validate user Username
        if (empty(trim($_POST["name"]))) {
            $name_err = "Please enter the Name.";
        } else {
                $name = trim($_POST["name"]);
        }
    
        //validate address
        if (empty(trim($_POST["user_address"]))) {
            $address_err = "Please enter the Address!";
        } else {
            $user_address = trim($_POST["user_address"]);           
        }
    
        if (empty(trim($_POST["user_area"]))) {
            $area_err = "Please enter the Area Office as mentioned in the Bill!";
        } else {
            $user_area = trim($_POST["user_area"]);           
        }
    
        
        if (empty(trim($_POST["user_premises"]))) {
            $premises_err = "Please enter the Premises ID.";
        } else {
            $user_premises = trim($_POST["user_premises"]);
        }
    
        if (empty(trim($_POST["user_account"]))) {
            $acc_err = "Please enter the account number.";
        } elseif (strlen(trim($_POST["user_account"])) != 10){
            $acc_err = "Invalid Account Number. SHould consist with only 10 numbers";
        } else {
            $user_account = trim($_POST["user_account"]);
        }

        $email = $_POST['stat'];
        $find_user_id = mysqli_query($link, "SELECT user_id FROM users WHERE user_email = '$email'");
        $found_uid = mysqli_fetch_assoc($find_user_id);
    
        // Check input errors before inserting in database
        if (empty($name_err) && empty($address_err) && empty($area_err) && empty($premises_err) && empty($acc_err)) {
    
            $user_id = $found_uid['user_id'];
    
            // Prepare an insert statement
            $sql = "INSERT INTO current_details (user_id, name, user_address, user_area, user_premises, user_account) VALUES (?, ?, ?, ?, ?, ?)";
    
            if ($stmt = $link->prepare($sql)) {
    
    
                // Bind variables to the prepared statement as parameters
                if ($stmt->bind_param("isssss",$param_userid, $param_name, $param_address, $param_area, $param_premises, $param_acc))
    
                    // Set parameters
                $param_userid = $user_id;
                $param_name = $name;
                $param_address = $user_address;
                $param_area = $user_area;
                $param_premises = $user_premises;
                $param_acc = $user_account; // Creates a password hash
    
                $message = "Uploaded the registration form.";
                $activity = "INSERT INTO activity_log (user_id, message) VALUES ('$user_id', '$message')";
    
                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    $name = $stat = $user_account = $user_address = $user_area = $user_premises = "";
                    mysqli_query($link,$activity);
                    header("Location: User-Registered.php");
                    
                    exit();
                } else {
    
                    echo "Something went wrong when executing. Please try again later.";
                }
    
                // Close statement
                $stmt->close();
            }
        }
    
        // Close connection
        $link->close();
    }
  
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />

<div class="row justify-content-center wrapper">
    <div class="col-lg-12 bg-white p-4">
        <div class="row gutters-sm">
            <div class=" col-md-12 mb-2">
                <div class="border shadow-lg card p-2">
                    <h4 class="text-center font-weight-bold">Reminders for All Users(<?php echo allUsers()?>)</h4>
                    <hr class="my-3" />
                    <div class="table-responsive-sm">
                        <table class="table table-striped table-hover" style="font-size: 14px;" id="myTable">
                            <thead style="font-weight: bold;font-size: 16px;">
                            <tr>
                                    <td style="text-align: center;">UserName</td>
                                    <td style="text-align: center;">Send Reminders</td>
                                </tr>
                            </thead>
                            <?php
            $db = mysqli_connect("localhost","root","","ocawbms");
            $records = mysqli_query($db,"SELECT user_id, gender, user_name FROM users");

            while($data=mysqli_fetch_array($records)){
                // $_SESSION['learners_name'] = $data['learners_name'];
                ?>
                            <tr>
                                <td style="text-align: center;">
                                    <?php
                    if($data['gender'] == "Male"){?>
                                    <img src="https://img.icons8.com/color/60/000000/person-male.png" />
                                    <?php
                    }

                    else if($data['gender'] == "Female"){?>
                                    <img src="https://img.icons8.com/color/60/000000/person-female.png" />
                                    <?php
                    }

                    else{?>
                                    <img src="https://img.icons8.com/material-rounded/24/000000/user.png" />
                                    <?php
                    }

                    ?>
                                    &nbsp;<br><?php echo $data['user_name'];?>
                                </td>

                                    <?php
                                        $uid = $data['user_id'];
                                        $data_username = $data['user_name'];
                                        $records_details = mysqli_query($db,"SELECT * FROM current_details WHERE user_id = '$uid'");
                                        $records_user = mysqli_query($db,"SELECT * FROM users WHERE user_id = '$uid'");
                                        $data_user=mysqli_fetch_array($records_user);
                                        $data_details=mysqli_fetch_array($records_details)
                                    ?>

                                <td style="text-align: center;">      
                                    <input type = "button" onclick = "location = 'SendReminders.php?user_id=<?php echo $data_user['user_id']?>'" value ="View" >
                                </td>
                            </tr>

                            <?php
            }

        ?>
                        </table>
                    </div>
                </div>
            </div>

<style>
label {
    font-weight: normal;
    font-size: 18px;
}

.form-group {
    text-align: left;
}

.help-block {
    color: red;
}

.pagination,
    div.dataTables_wrapper div.dataTables_filter label,
    div.dataTables_wrapper div.dataTables_length label,
    div.dataTables_wrapper div.dataTables_filter,
    table.dataTable td.dataTables_empty,
    table.dataTable th.dataTables_empty,
    div.dataTables_wrapper div.dataTables_info {
        font-size: 14px;
    }

    .page-link,
    .page-link:hover {
        color: black;
        text-decoration: none;
    }
</style>

<!-- <style>
.page-link,
.page-link:hover,
.page-group-current-page:active {
    color: red;
    text-decoration: none;
}
</style> -->

<script src="../../vendor.bundle.base.js"></script>
<script type="text/javascript"
    src="https://cdn.datatables.net/v/bs4/dt-1.10.24/r-2.2.7/sc-2.0.3/sp-1.2.2/datatables.min.js"></script>
<script>
$(document).ready(function() {
    $('#myTable').DataTable();
});

$(document).ready(function() {
    $('#redTable').DataTable();
});
</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script type="text/javascript">
var ctx = document.getElementById("chartjs_bar").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($tot_user_methods); ?>,
        datasets: [{
            backgroundColor: [
                "#ffc750",
                "#2ec551",
                "#ff407b",
                "#3B444B",

            ],
            data: <?php echo json_encode($tot_users); ?>,
        }]
    },
    options: {
        legend: {
            display: true,
            position: 'bottom',

            labels: {
                fontColor: '#71748d',
                fontSize: 14,
            }
        },


    }
});
</script>

<?php
require_once 'Admin-Footer.php';
?>