<?php
require_once '../../Config.php';
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

if (!isset($_SESSION['loggedin_admin'])) {
    header('Location: Admin-Login.php');
    exit;
} else {
    $admin_name = $_SESSION['admin_name'];
    $sql = "SELECT * FROM admin WHERE admin_name='" . $admin_name . "'";
    $records = mysqli_query($link, $sql);
    $data = mysqli_fetch_assoc($records);
    $a_id = $data['admin_id'];
    $_SESSION['admin_id'] = $a_id;
}

function pendingPay()
{
    $db = new mysqli('gasmeter.mysql.database.azure.com', 'gasmeter', 'AdminLogin123', 'ocawbms');
    $sql_month = "SELECT * FROM bill_month";
    $records_month = mysqli_query($db, $sql_month);
    $data_month = mysqli_fetch_assoc($records_month);
    $bill_month = $data_month['month'];
    $pending_pay = mysqli_query($db, "SELECT * FROM current_bill WHERE (status = 'Not Paid' OR status = 'Partly Paid') AND  month = '$bill_month'");
    $result_pending_pay = mysqli_num_rows($pending_pay);
    return $result_pending_pay;
}
function totalPay()
{
    $db = new mysqli('gasmeter.mysql.database.azure.com', 'gasmeter', 'AdminLogin123', 'ocawbms');
    $sql_month = "SELECT * FROM bill_month";
    $records_month = mysqli_query($db, $sql_month);
    $data_month = mysqli_fetch_assoc($records_month);
    $bill_month = $data_month['month'];
    $total_pay = mysqli_query($db, "SELECT * FROM current_bill WHERE (status = 'Paid' OR status = 'Over Paid') AND month = '$bill_month'");
    $result_total_pay = mysqli_num_rows($total_pay);
    return $result_total_pay;
}


function allUsers(){
    $db = new mysqli('gasmeter.mysql.database.azure.com', 'gasmeter', 'AdminLogin123', 'ocawbms');
    $all = mysqli_query($db, "SELECT * FROM users");
    $all_users = mysqli_num_rows($all);
    return $all_users;
}

$sql_month = "SELECT * FROM bill_month";
$records_month = mysqli_query($link, $sql_month);
$data_month = mysqli_fetch_assoc($records_month);
$bill_month = $data_month['month'];

$user_name = $user_gender = $user_nic = $user_email = $user_contact = $user_password = $confirm_password = $send_password = "";
$username_err = $password_err = $email_err = $confirm_password_err = $nic_err = $contact_err = $stat_err = "";
$name_err = $name = $user_account = $user_address = $user_area = $address_err = $area_err = $acc_err = $user_premises = $premises_err = "";
$stat = $_SESSION['var'] = 1;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $month = $_POST['month'];
    $due_date = $_POST['Due_Date'];
    $a_id = $data['admin_id'];
    date_default_timezone_set('Asia/kuala_lumpur');
    $updated_at = date("Y-m-d H:i:s");

    $sql = "UPDATE bill_month SET admin_id = '$a_id', month = '$month', updated_at = '$updated_at' WHERE id = '1'";

    $update_due_date = "UPDATE bill_month SET admin_id = '$a_id', Due_Date = '$due_date', updated_at = '$updated_at' WHERE id = '1'";
    mysqli_query($link, $update_due_date);


    $sql_red_bill = mysqli_query($link, "SELECT user_id FROM current_bill WHERE status = 'Not Paid' AND month = '$bill_month'");
    


    if (mysqli_query($link, $sql)) {
        while ($data_red_bill = mysqli_fetch_assoc($sql_red_bill)) {
            $red_id = $data_red_bill['user_id'];
            $insert = mysqli_query($link, "INSERT INTO red_bill (user_id, month) VALUES ('$red_id','$bill_month')");
        }
        //header("Location:Month-Updated.php");
        echo '<br/> <div class="alert alert-success alert-dismissible fade show" role="alert" ><strong>';
        echo 'Billing month updated succesfully! Refresh the page once it is updated';
        echo ' </strong> <button type="button" class="btn btn-success-close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div>';
    } else {
        echo mysqli_error($link);
    }
}

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
                #header("Location:User-Added.php");
                echo "<script> location.href='User-Added.php';</script>";
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
    <div class="col-lg-12 p-4 pt-12">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="card border mb-3 p-2">
                        <h2 class="align-items-center text-center  p-2">Billing Month</h2>
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                                <?php
                                $sql_month = "SELECT * FROM bill_month";
                                $records_month = mysqli_query($link, $sql_month);
                                $data_month = mysqli_fetch_assoc($records_month);
                                $admin_id = $data_month['admin_id'];
                                $sql_admin = "SELECT * FROM admin WHERE admin_id = '$admin_id'";
                                $records_admin = mysqli_query($link, $sql_admin);
                                $data_admin = mysqli_fetch_assoc($records_admin);
                                $month = $data_month['month'];
                                ?>
                                <div class="row gutters-sm">
                                    <div class="form-group col-md-4">
                                        <label>Billing Month</label>
                                        <input type="month" class="form-control" name="month" required value="<?php echo $data_month['month'] ?>">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Due Date</label>
                                        <input type="date" class="form-control" name="Due_Date" required placeholder="Click the Calender Icon" value="<?php echo $data_month['Due_Date']?>">

                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Updated By</label>
                                        <input disabled type="text" class="form-control" value="<?php echo $data_admin['admin_id'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Updated At</label>
                                        <input disabled type="text" class="form-control" value="<?php echo $data_month['updated_at'] ?>">
                                    </div>
                                </div><br>
                                <div class="form-group">
                                        <button class="btn btn-outline-danger btn-md btn-block myBtn" type="submit " name="submit">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
            
                <div class="col mb-3 p-2">
                <div class="card border mb-3 p-2">
                    <h2 class="align-items-center text-center p-2">Live Dashboard</h2>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4 alert-dark fade show">
                                    <div class="panel-heading">
                                        <div class="stat-panel text-center">
                                            <div class="stat-panel-number h1 ">
                                                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#paid">
                                                    <div class="row">
                                                        <div class="col-md-3" style="float:left;
                                                        border-radius: 100%;color:#734F96"><i class="fa fa-check-square-o fa-2x" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <h4 class="stat-panel-title">
                                                                <?php echo totalPay() ?>
                                                            </h4>
                                                            <h5 style="color: grey;">Total Paid</h5>
                                                        </div>
                                                    </div>
                                                </button>

                                                <div class="modal fade" id="paid" tabindex="-1" aria-labelledby="paidLabel" aria-hidden="true">
                                                    <div class="modal-dialog ">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background-color:#734F96">
                                                                <h4 class="modal-title" id="paidLabel" style="color: white;">
                                                                    Paid Users</h4>
                                                                <button type="button" class="btn" style="background-color:#734F96;color: white;" data-bs-dismiss="modal" aria-label="Close">
                                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body paid">
                                                                <table class="table table-striped table-hover table-paid" style="font-size: 14px;" id="paidTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Username</th>
                                                                        </tr>
                                                                    </thead>

                                                                    <?php
                                                                    $records = mysqli_query($link, "SELECT * FROM current_bill WHERE (status = 'Paid' OR status = 'Over Paid') AND month = '$month'");

                                                                    while ($data = mysqli_fetch_array($records)) {
                                                                        $uid = $data['user_id'];
                                                                        $recordsOne = mysqli_query($link, "SELECT * FROM users WHERE user_id = '$uid'");
                                                                        $dataOne = mysqli_fetch_array($recordsOne);
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                                <h6 style="float: left;">
                                                                                    <?php echo $dataOne['user_name']; ?>&nbsp;&nbsp;
                                                                                </h6>
                                                                                <a style="text-decoration: none; float: right;color:#734F96;" href="View-Registration.php?user_id=<?php echo $data['user_id'] ?>">View
                                                                                    <i class="fa fa-cc-stripe" aria-hidden="true"></i>
                                                                                </a>

                                                                            </td>
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
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-4 alert-warning fade show">
                                    <div class="panel-heading">
                                        <div class="stat-panel text-center">
                                            <div class="stat-panel-number h1 ">
                                                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#pendingPay">
                                                    <div class="row">
                                                        <div class="col-md-3" style="float:left;
                                                        border-radius: 100%;color:#FF9966">
                                                            <i class="fa fa-question-circle-o fa-2x" aria-hidden="true"></i>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <h4 class="stat-panel-title">
                                                                <?php echo pendingPay() ?>
                                                            </h4>
                                                            <h5 style="color: grey;">Pending to Pay</h5>
                                                        </div>
                                                    </div>
                                                </button>

                                                <div class="modal fade" id="pendingPay" tabindex="-1" aria-labelledby="pendingPayLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="background-color:#FF9966">
                                                                <h4 class="modal-title" id="pendingPayLabel" style="color: white;">
                                                                    Users Pending to Pay</h4>
                                                                <button type="button" class="btn" style="background-color:#FF9966;color: white;" data-bs-dismiss="modal" aria-label="Close">
                                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-striped table-hover" style="font-size: 14px;" id="pendingTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Username</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <?php
                                                                    $records = mysqli_query($link, "SELECT * FROM current_bill WHERE (status = 'Not Paid' OR status = 'Partly Paid') AND month = '$month'");

                                                                    while ($data = mysqli_fetch_array($records)) {
                                                                        $uid = $data['user_id'];
                                                                        $recordsOne = mysqli_query($link, "SELECT * FROM users WHERE user_id = '$uid'");
                                                                        $dataOne = mysqli_fetch_array($recordsOne);
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                                <h6 style="float: left;">
                                                                                    <?php echo $dataOne['user_name']; ?>&nbsp;&nbsp;
                                                                                </h6>
                                                                                <a style="text-decoration: none; float: right;color:#FF9966;" href="View-Registration.php?user_id=<?php echo $data['user_id'] ?>">View
                                                                                    Bill
                                                                                    <i class="fa fa-cc-stripe" aria-hidden="true"></i>
                                                                                </a>

                                                                            </td>
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
                            </div>
                            
                            <?php
                            $due_month = $data_month['month'];
                            ?>
                            
                            <?php
                            $que = "SELECT user_id FROM current_details WHERE status = 'Approved' AND user_id NOT IN (SELECT user_id FROM image_upload WHERE month = '$due_month')";
                            $records_not = mysqli_query($link, $que);
                            while ($data_not = mysqli_fetch_array($records_not)) {
                            $uid = $data_not['user_id'];
                            $recordsOne = mysqli_query($link, "SELECT * FROM users WHERE user_id = '$uid'");
                            $dataOne = mysqli_fetch_array($recordsOne);
                            ?>
                                                
                            <?php
                            }
                            ?>

                        
                    </div>
                </div>
            </div>

        <div class=" col p-2">
            <div class="border shadow-lg card p-2">
                    <h3 class="align-items-center text-center p-2">Add User</h3>
                    <div class="panel-heading">
                        <div class="stat-panel text-center">
                            <div class="stat-panel-number h1 ">
                                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#add">
                                    <div class="row">
                                        <div class="col-md-3" style="float:left;
                                                        border-radius: 100%;color:#734F96"><i
                                                class="fa fa-user-plus fa-2x" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-md-8">
                                            <p style="color: grey;">Click here to create a user account</p>
                                        </div>
                                    </div>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="add" tabindex="-1" aria-labelledby="addLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header " style="color: white;background-color: #734F96;">
                                                <h5 class="modal-title" id="addLabel">Create User Account</h5>
                                                <button type="button" class="btn" data-bs-dismiss="modal"
                                                    aria-label="Close" style="color: white;">
                                                    <i class="fa fa-times" aria-hidden="true"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <form
                                                    action="<?php $_SESSION['var'] = 1; echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                                    method="POST" class="px-3 needs-validation" id="user_add">

                                                    <p style="font-size: 14px;">*Please fill this form to create an user
                                                        account.</p>

                                                    <div class="form-group" style="text-align: left;">
                                                        <label
                                                            style="font-weight: normal;font-size: 18px;">Username</label>
                                                        <input type="text" class="form-control" name="user_name"
                                                            placeholder="Enter a Username"
                                                            value="<?php echo $user_name; ?>" required>
                                                        <span class="help-block"><?php echo $username_err; ?></span>
                                                    </div>

                                                    <div class="row gutters-sm">
                                                        <div class="form-group col-md-6" style="text-align: left;">
                                                            <label
                                                                style="font-weight: normal;font-size: 18px;">Gender</label>
                                                            <select id="gender" name="gender" class="form-control">
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-md-6" style="text-align: left;">
                                                            <label style="font-weight: normal;font-size: 18px;">NIC
                                                                Number</label>
                                                            <input type="text" class="form-control" name="user_nic"
                                                                placeholder="Enter the NIC Number" required>
                                                            <span class="help-block"><?php echo $nic_err; ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="row gutters-sm">
                                                        <div class="form-group col-md-6" style="text-align: left;">
                                                            <label
                                                                style="font-weight: normal;font-size: 18px;">Email</label>
                                                            <input type="email" class="form-control" name="user_email"
                                                                placeholder="Enter Email"
                                                                value="<?php echo $user_email; ?>" required>
                                                            <span class="help-block"><?php echo $email_err; ?></span>
                                                        </div>

                                                        <div class="form-group col-md-6" style="text-align: left;">
                                                            <label style="font-weight: normal;font-size: 18px;">Contact
                                                                No</label>
                                                            <input type="text" class="form-control" name="user_contact"
                                                                placeholder="Enter a Contact Number"
                                                                value="<?php echo $user_contact; ?>" required>
                                                            <span class="help-block"><?php echo $contact_err; ?></span>
                                                        </div>
                                                    </div>

                                                    <div class="row gutters-sm">
                                                        <div class="form-group col-md-6" style="text-align: left;">
                                                            <label
                                                                style="font-weight: normal;font-size: 18px;">Password</label>
                                                            <input type="password" class="form-control"
                                                                name="user_password" placeholder="Enter Password"
                                                                required>
                                                        </div>

                                                        <div class="form-group col-md-6" style="text-align: left;">
                                                            <label style="font-weight: normal;font-size: 18px;">Confirm
                                                                Password</label>
                                                            <input type="password" class="form-control"
                                                                name="confirm_password" placeholder="Re-Enter Password"
                                                                required>
                                                        </div>
                                                    </div><br>
                                                    <hr class="my-3" />

                                                    <div class="row gutters-sm" style="float: right;">
                                                        <div class="form-group col-md-8">
                                                            <button class="btn btn-block myBtn"
                                                                style="background-color: #734F96;color: white;"
                                                                type="submit " name="submit_add">Create
                                                                Account</button>
                                                        </div>
                                                </form>
                                                <div class="form-group col-md-4">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><br>

            <div class=" col-md-14 mb-2">
                <div class="border shadow-lg card p-2">
                    <h3 class="align-items-center text-center p-2">Register User</h3>
                    <div class="panel-heading">
                        <div class="stat-panel text-center">
                            <div class="stat-panel-number h1 ">
                                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#register">
                                    <div class="row">
                                        <div class="col-md-3" style="padding-right:10px ;color:#CD7F32"><i
                                                class="fa fa-user-plus fa-2x" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-md-8">
                                            <p style="color: grey; float: left;">Click here to register a user account
                                                for
                                                Gas
                                                billing  system</p>
                                        </div>
                                    </div>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="register" tabindex="-1" aria-labelledby="registerLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header " style="color: white;background-color: #CD7F32;">
                                                <h5 class="modal-title" id="registerLabel">Create User Account</h5>
                                                <button type="button" class="btn" data-bs-dismiss="modal"
                                                    aria-label="Close" style="color: white;">
                                                    <i class="fa fa-times" aria-hidden="true"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <form
                                                    action="<?php $_SESSION['var'] = 2; echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                                    method="POST" class="px-3 needs-validation" id="user_add">
                                                    <p style="font-size: 14px;">*Please fill this form to register for
                                                        the
                                                        Gas billing  system. All
                                                        the information is related to manual bill</p>

                                                    <div class="form-group">
                                                        <label>User Email</label>
                                                        <input type="text" class="form-control" name="stat"
                                                            placeholder="Enter the User Email" required>
                                                        <span class="help-block"><?php echo $stat_err; ?></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Name of the Person</label>
                                                        <input type="text" class="form-control" name="name"
                                                            placeholder="Enter the Name" value="<?php echo $name; ?>"
                                                            required>
                                                        <span class="help-block"><?php echo $name_err; ?></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Address</label>
                                                        <input type="text" class="form-control" name="user_address"
                                                            placeholder="Enter the Address"
                                                            value="<?php echo $user_address; ?>" required>
                                                        <span class="help-block"><?php echo $address_err; ?></span>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Area Office</label>
                                                        <input type="text" class="form-control" name="user_area"
                                                            placeholder="Enter the Area Office"
                                                            value="<?php echo $user_area; ?>" required>
                                                        <span class="help-block"><?php echo $area_err; ?></span>
                                                    </div>

                                                    <div class="row gutters-sm">
                                                        <div class="form-group col-md-6">
                                                            <label>Premises ID</label>
                                                            <input type="text" class="form-control" name="user_premises"
                                                                placeholder="Enter the Premises ID"
                                                                value="<?php echo $user_premises; ?>" required>
                                                            <span class="help-block"><?php echo $premises_err; ?></span>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label>Gas Account Number</label>
                                                            <input type="text" class="form-control" name="user_account"
                                                                placeholder="Enter the Account Number"
                                                                value="<?php echo $user_account; ?>" required>
                                                            <span class="help-block"><?php echo $acc_err; ?></span>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <hr class="my-3" />

                                                    <div class="row gutters-sm" style="float: right;">
                                                        <div class="form-group col-md-6">
                                                            <button class="btn btn-block myBtn" type="submit "
                                                                style="background-color: #CD7F32;color: white;"
                                                                name="submit_regi">Register</button>
                                                        </div>
                                                </form>
                                                <div class="form-group col-md-6">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><br>
            <div class=" col-md-14 mb-2">
                <div class="border shadow-lg card p-2">
                    <h3 class="align-items-center text-center p-2">Red Bill Users</h3>
                    <div class="panel-heading">
                        <div class="stat-panel text-center">
                            <div class="stat-panel-number h1 ">
                                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#redBill">
                                    <div class="row">
                                        <div class="col-md-3" style="padding-right:10px ;color:red"><i
                                                class="fa fa-user-times fa-2x" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-md-8">
                                            <p style="color: grey; float: left;">Users who
                                                didn't pay the bill within the deadline.</p>
                                        </div>
                                    </div>
                                </button>

                                

                                <!-- Modal -->
                                <div class="modal fade" id="redBill" tabindex="-1" aria-labelledby="redBillLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger" style="color: white;">
                                                <h5 class="modal-title" id="redBillLabel">Red Bill Users</h5>
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                                    aria-label="Close" style="color: white;">
                                                    <i class="fa fa-times" aria-hidden="true"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive-md">
                                                    <table class="table table-striped table-hover"
                                                        style="font-size: 14px;" id="redTable">
                                                        <thead>
                                                            <tr>
                                                                <th style="text-align: center;">UserName</th>
                                                                <th style="text-align: center;">Month</th>
                                                                <th style="text-align: center;">Contact</th>
                                                                <th style="text-align: center;">View</th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                $red_bill = mysqli_query($link,"SELECT DISTINCT user_id,month FROM red_bill");
                                                while($results_red_bill = mysqli_fetch_assoc($red_bill)){
                                                    $red_id = $results_red_bill['user_id'];
                                                    $users = mysqli_query($link,"SELECT * FROM users WHERE user_id = '$red_id'");
                                                    $results_users = mysqli_fetch_assoc($users);
                                                    ?>
                                                        <tr style="text-align: center; font-weight: normal;">
                                                            <td><?php echo $results_users['user_name'] ?></td>
                                                            <td><?php echo $results_red_bill['month'] ?></td>
                                                            <td><?php echo $results_users['user_contact'] ?></td>
                                                            <td><a class="btn btn-danger" role="button" href="View-Registration.php?user_id=<?php echo $red_id ?>">View</a></td>
                                                        </tr>
                                                        <?php
                                                }
                                                ?>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--<div class="col-md-14">
                <div class="card border mb-3 p-2">
                    <div class="row gutters-sm">
                        <h2 class="align-items-center text-center">Domestic&nbsp;<i class="fa fa-home" aria-hidden="true"></i></h2>
                        <div class="col-md-6">
                            <div class="card border mb-3 p-2">

                                <div class="table-responsive-sm">
                                    <table class="table table-striped table-hover" style="text-align: center;">
                                        <thead style="font-weight: bold;">
                                            <tr>
                                                <th> Consumption</th>
                                                <th>Charge<br>(Rm/mmbtu)</th>
                                                <th>Fixed Charge<br>(Rm/mmbtu)</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td>0-30</td>
                                            <td>3.00</td>
                                            <td>30.00</td>
                                        </tr>
                                        <tr>
                                            <td>31-60</td>
                                            <td>4.70</td>
                                            <td>60.00</td>
                                        </tr>
                                        <tr>
                                            <td>61-90</td>
                                            <td>7.50</td>
                                            <td>90.00</td>
                                        </tr>
                                        <tr>
                                            <td>91-120</td>
                                            <td>21.00</td>
                                            <td>315.00</td>
                                        </tr>
                                        <tr>
                                            <td>121-180</td>
                                            <td>24.00</td>
                                            <td>315.00</td>
                                        </tr>
                                        <tr>
                                            <td>180 ></td>
                                            <td>36.00</td>
                                            <td>315.00</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border mb-3 p-2">
                                <h2 class="align-items-center text-center">
                                    Usage
                                    <?php
                                    $dom_m1 = $dom_m2 = $dom_m3 = $dom_m4 = $dom_m5 = $dom_m6 = 0;
                                    $m1 = date("Y-m", strtotime('-1 month', strtotime($due_month)));
                                    $m2 = date("Y-m", strtotime('-1 month', strtotime($m1)));
                                    $m3 = date("Y-m", strtotime('-1 month', strtotime($m2)));
                                    $m4 = date("Y-m", strtotime('-1 month', strtotime($m3)));
                                    $m5 = date("Y-m", strtotime('-1 month', strtotime($m4)));
                                    $m6 = date("Y-m", strtotime('-1 month', strtotime($m5)));

                                    $tot_months =  array();
                                    array_push($tot_months, $m1, $m2, $m3, $m4, $m5, $m6);
                                    $tot_dom =  array();

                                    $usage = mysqli_query($link, "SELECT * FROM current_bill");
                                    $use_dom = 0;
                                    while ($res = mysqli_fetch_array($usage)) {
                                        $usage_id = $res['user_id'];
                                        $use = mysqli_query($link, "SELECT category FROM current_details WHERE user_id = '$usage_id'");
                                        $result = mysqli_fetch_array($use);
                                        if ($result['category'] == 'Domestic') {
                                            if ($res['month'] == $m1) {
                                                $dom_m1 += $res['units'];
                                            } else if ($res['month'] == $m2) {
                                                $dom_m2 += $res['units'];
                                            } else if ($res['month'] == $m3) {
                                                $dom_m3 += $res['units'];
                                            } else if ($res['month'] == $m4) {
                                                $dom_m4 += $res['units'];
                                            } else if ($res['month'] == $m5) {
                                                $dom_m5 += $res['units'];
                                            } else if ($res['month'] == $m6) {
                                                $dom_m6 += $res['units'];
                                            }
                                        }
                                    }
                                    array_push($tot_dom, $dom_m1, $dom_m2, $dom_m3, $dom_m4, $dom_m5, $dom_m6);
                                    ?>
                                    <canvas id="dom_bar"></canvas>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <div class="col-md-14">
            <div class="card border mb-3 p-2">
                <div class="row gutters-sm">
                    <h2 class="align-items-center text-center">
                        Industry&nbsp;<i class="fa fa-industry" aria-hidden="true"></i>
                    </h2>
                    <div class="col-md-6">
                        <div class="card border mb-3 p-2">

                            <div class="table-responsive-sm">
                                <table class="table table-striped table-hover" style="text-align: center;">
                                    <thead style="font-weight: bold;">
                                        <tr>
                                            <th>Consume</th>
                                            <th>Charge<br>(Rm/mmbtu)</th>
                                            <th>Fixed<br>Charge<br>(mmbtu/month)</th>
                                            <th>Max<br>Demand<br>Charge<br>(Rm/mmbtu)</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>1-1</td>
                                        <td>10.50</td>
                                        <td>240.00</td>
                                        <td>-</td>
                                    </tr>
                                    <td colspan="4" style="padding-right: 350px;">1-2</td>

                                    <tr>
                                        <td>Day</td>
                                        <td>10.45</td>
                                        <td rowspan="3" style="padding-top: 50px;">3000.00</td>
                                        <td rowspan="3" style="padding-top: 50px;">850.00</td>
                                    </tr>
                                    <tr>
                                        <td>Peak</td>
                                        <td>13.60</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>7.35</td>
                                    </tr>
                                    <td colspan="4" style="padding-right: 350px;">1-3</td>
                                    <tr>
                                        <td>Day</td>
                                        <td>10.25</td>
                                        <td rowspan="3" style="padding-top: 50px;">3000.00</td>
                                        <td rowspan="3" style="padding-top: 50px;">750.00</td>
                                    </tr>
                                    <tr>
                                        <td>Peak</td>
                                        <td>13.40</td>
                                    </tr>
                                    <tr>
                                        <td>Off Peak</td>
                                        <td>7.15</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border mb-3 p-2">
                            <h2 class="align-items-center text-center">
                                Usage
                                <?php
                                $ind_m1 = $ind_m2 = $ind_m3 = $ind_m4 = $ind_m5 = $ind_m6 = 0;
                                $tot_ind =  array();
                                $usage = mysqli_query($link, "SELECT * FROM current_bill");
                                while ($res = mysqli_fetch_array($usage)) {
                                    $usage_id = $res['user_id'];
                                    $use = mysqli_query($link, "SELECT category FROM current_details WHERE user_id = '$usage_id'");
                                    $result = mysqli_fetch_array($use);
                                    if ($result['category'] == 'Industrial 1') {
                                        if ($res['month'] == $m1) {
                                            $ind_m1 += $res['units'];
                                        } else if ($res['month'] == $m2) {
                                            $ind_m2 += $res['units'];
                                        } else if ($res['month'] == $m3) {
                                            $ind_m3 += $res['units'];
                                        } else if ($res['month'] == $m4) {
                                            $ind_m4 += $res['units'];
                                        } else if ($res['month'] == $m5) {
                                            $ind_m5 += $res['units'];
                                        } else if ($res['month'] == $m6) {
                                            $ind_m6 += $res['units'];
                                        }
                                    }
                                }
                                array_push($tot_ind, $ind_m1, $ind_m2, $ind_m3, $ind_m4, $ind_m5, $ind_m6);
                                ?>
                                <canvas id="ind_bar"></canvas>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    

<style>
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

    label {
    font-weight: normal;
    font-size: 18px;
}
</style>

<script src="../../vendor.bundle.base.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/r-2.2.7/sc-2.0.3/sp-1.2.2/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#paidTable').DataTable();
    });

    $(document).ready(function() {
        $('#imgTable').DataTable();
    });

    $(document).ready(function() {
        $('#pendingImgTable').DataTable();
    });

    $(document).ready(function() {
        $('#pendingTable').DataTable();
    });

    $(document).ready(function() {
        $('#pendingRegiTable').DataTable();
    });

    $(document).ready(function() {
        $('#approvedTable').DataTable();
    });

    $(document).ready(function() {
        $('#rejectedTable').DataTable();
    });
</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script type="text/javascript">
    var ctx = document.getElementById("chartjs_bar").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($tot_methods); ?>,
            datasets: [{
                backgroundColor: [
                    "#5969ff",
                    "#ff407b",
                    "#25d5f2",
                    "#ffc750",
                    "#2ec551",
                    "#7040fa",
                    "#ff004e"
                ],
                data: <?php echo json_encode($tot_units); ?>,
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

<script type="text/javascript">
    var ctx = document.getElementById("dom_bar").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($tot_months); ?>,
            datasets: [{
                backgroundColor: [
                    "#ffc750",
                    "#2ec551",
                    "#ff407b",
                    "#3B444B",
                    "#25d5f2",
                    "#ff004e"
                ],
                data: <?php echo json_encode($tot_dom); ?>,
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

<script type="text/javascript">
    var ctx = document.getElementById("rel_bar").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($tot_months); ?>,
            datasets: [{
                backgroundColor: [
                    "#ffc750",
                    "#2ec551",
                    "#ff407b",
                    "#3B444B",
                    "#25d5f2",
                    "#ff004e"
                ],
                data: <?php echo json_encode($tot_rel); ?>,
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

<script type="text/javascript">
    var ctx = document.getElementById("ind_bar").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($tot_months); ?>,
            datasets: [{
                backgroundColor: [
                    "#ffc750",
                    "#2ec551",
                    "#ff407b",
                    "#3B444B",
                    "#25d5f2",
                    "#ff004e"
                ],
                data: <?php echo json_encode($tot_ind); ?>,
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

<script type="text/javascript">
    var ctx = document.getElementById("hotel_bar").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($tot_months); ?>,
            datasets: [{
                backgroundColor: [
                    "#ffc750",
                    "#2ec551",
                    "#ff407b",
                    "#3B444B",
                    "#25d5f2",
                    "#ff004e"
                ],
                data: <?php echo json_encode($tot_hotel); ?>,
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

<script type="text/javascript">
    var ctx = document.getElementById("gen_bar").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($tot_months); ?>,
            datasets: [{
                backgroundColor: [
                    "#ffc750",
                    "#2ec551",
                    "#ff407b",
                    "#3B444B",
                    "#25d5f2",
                    "#ff004e"
                ],
                data: <?php echo json_encode($tot_gen); ?>,
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