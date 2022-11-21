<?php
require_once 'User-Header.php';

$user_id = $_SESSION['uid'];
$uname = $_SESSION['user_uname'];
$sql_record = "SELECT * FROM current_details WHERE user_id='" . $user_id . "'";
$recordsDetails = mysqli_query($link, $sql_record);
if($dataDetails = mysqli_fetch_assoc($recordsDetails)){
    
 ?>

<?php
require_once '../../Config.php';
require_once 'User-Header.php';
//$users_data =check_login($con);


if (!isset($_SESSION['loggedin_user'])) {
    header('Location: User-Login.php');
    exit;
} else {

    $user_name = $_SESSION['user_uname'];
    $sql = "SELECT * FROM users WHERE user_name='" . $user_name . "'";
    $records = mysqli_query($link, $sql);
    $data = mysqli_fetch_assoc($records);
    $_SESSION['uid'] = $data['user_id'];

    $uid = $data['user_id'];

    $sql_method = "SELECT * FROM current_details WHERE user_id='" . $uid . "'";
    $records_method = mysqli_query($link, $sql_method);
    $data_method = mysqli_fetch_assoc($records_method);

    $sql_month = "SELECT * FROM bill_month";
    $records_month = mysqli_query($link, $sql_month);
    $data_month = mysqli_fetch_assoc($records_month);
    $due_month = $data_month['month'];

    $sql_img = "SELECT * FROM image_upload WHERE user_id='" . $uid . "' AND month = '$due_month'ORDER BY id DESC LIMIT 1";
    $records_img = mysqli_query($link, $sql_img);

    $sql_bill = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND month = '$due_month'";
    $records_bill = mysqli_query($link, $sql_bill);
}



$username_err = $password_err = $email_err = $confirm_password_err = $nic_err = $contact_err = $new_password_err  = $confirm_password_err = "";
?>
<!DOCTYPE html>                  
<html lang="en"> 
    <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Vendor CSS Files -->
    <link href="Appland/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="Appland/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="Appland/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="Appland/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="Appland/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="Appland/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    </head>
    
            <body>
               
                      <div class="row justify-content-center wrapper">
                            <div class="col-lg-4 bg-white p-4 pt-12">
                                <h2 class="align-items-center text-center">Progress</h2>
                                <?php

                                $sql = "SELECT * FROM current_details WHERE user_id='" . $uid . "'";
                                $exist = "SELECT COUNT(user_id) FROM current_details WHERE user_id='" . $uid . "'";
                                if (mysqli_query($link, $sql)) {
                                    $recordsDetails = mysqli_query($link, $sql);
                                    $dataDetails = mysqli_fetch_assoc($recordsDetails);
                                    $status = $dataDetails['status'];
                                    if ($status == 'Pending') {
                                ?>
                                        <div class="card-body align-items-center text-center btn btn-light">
                                            <div class="row">
                                                <h5 class="align-items-center text-center">Your Registration Form is still
                                                    <?php echo $status; ?>...</h5>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped  bg-warning progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                    </div>
                                                </div><br>
                                                <a href="User-Register.php" style="text-decoration: none;">
                                                    <h7 class="align-items-center text-center">Click here to view the filled
                                                        form
                                                    </h7>
                                                </a>
                                            </div>
                                        </div>

                                        <?php
                                    } else if ($status == 'Approved') {
                                        if ($data_img = mysqli_fetch_assoc($records_img)) {
                                            if ($data_img['status'] == 'Prepared') {
                                                if ($data_bill = mysqli_fetch_assoc($records_bill)) {
                                                    if ($data_bill['status'] == 'Paid') {
                                        ?>
                                                        <div class="card-body align-items-center text-center btn bg-white">
                                                            <div class="row">
                                                                <div class="alert alert-success" role="alert">
                                                                    <h5 class="align-items-center text-center">Bill Payment is Done&nbsp;<i class="fa fa-check-circle-o" aria-hidden="true"></i></h5>
                                                                    You have paid the bill for <?php echo $due_month; ?>.
                                                                    You will recieve an email about your payment information and you can
                                                                    see it from here as well.
                                                                    <a class="alert-link" href="User-Pay.php" style="text-decoration: none;"><br>
                                                                        <h7 class="align-items-center text-center">View Payment&nbsp;<i class="fa fa-credit-card" aria-hidden="true"></i></h7>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } 
                                                    
                                                    
                                                    else {
                                                    ?>
                                                        <div class="card-body align-items-center text-center btn btn-light">
                                                            <div class="row">
                                                                <h5 class="align-items-center text-center">Your Payable Bill is
                                                                    Prepared&nbsp;<i class="fa fa-check-square-o" aria-hidden="true"></i>

                                                                </h5><br>
                                                                <div class="align-items-center text-center">
                                                                    <div class="progress">
                                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>

                                                                </div><br>
                                                                <p>Your payable bill is prepared.
                                                                    Please settle the bill charge before the due date given in the bill.
                                                                    Extra charges may apply if the charge
                                                                    is not settled within the due date. If you have any issues regarding
                                                                    the
                                                                    bill please contact us.
                                                                    <a href="User-Pay.php" style="text-decoration: none;"><Br>
                                                                        <h7 class="align-items-center text-center">View Bill&nbsp;<i class="fa fa-file-text" aria-hidden="true"></i></h7>
                                                                    </a>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <div class="card-body align-items-center text-center btn btn-light">
                                                        <div class="row">
                                                            <h5 class="align-items-center text-center">Your Payable Bill is
                                                                Prepared&nbsp;<i class="fa fa-check-square-o" aria-hidden="true"></i>

                                                            </h5><br>
                                                            <div class="align-items-center text-center">
                                                                <div class="progress">
                                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>

                                                            </div><br>
                                                            <p>Your payable bill is prepared.
                                                                Please settle the bill charge before the due date given in the bill.
                                                                Extra charges may apply if the charge
                                                                is not settled within the due date. If you have any issues regarding
                                                                the
                                                                bill please contact us.
                                                                <a href="Bill-Info.php" style="text-decoration: none;"><Br>
                                                                    <h7 class="align-items-center text-center">View Bill&nbsp;<i class="fa fa-file-text" aria-hidden="true"></i></h7>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } else if ($data_img['status'] == 'Pending') {
                                                ?>
                                                <div class="card-body align-items-center text-center btn btn-light">
                                                    <div class="row">
                                                        <h5 class="align-items-center text-center">Your Payable Bill is
                                                            Pending&nbsp;<i class="fa fa-file-text-o" aria-hidden="true"></i>

                                                        </h5><br>
                                                        <div class="align-items-center text-center">
                                                            <div class="spinner-grow text-warning" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <div class="spinner-grow text-warning" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <div class="spinner-grow text-warning" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <div class="spinner-grow text-warning" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <div class="spinner-grow text-warning" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </div>
                                                        <p>You have uploaded the image of the meter&nbsp;<i class="fa fa-check-square-o" aria-hidden="true"></i>.
                                                            Please wait untill it get confirmed by administrators.
                                                            <a href="Image-Upload.php" style="text-decoration: none;">
                                                                <h7 class="align-items-center text-center">Click here
                                                                </h7>
                                                            </a>to view the uploaded image
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php
                                            } else if ($data_img['status'] == 'Rejected') {
                                            ?>
                                                <div class="card-body align-items-center text-center btn bg-white">
                                                    <div class="row">
                                                        <div class="alert alert-danger" role="alert">
                                                            <h5 class="align-items-center text-center">Your uploaded image was rejected&nbsp;<i class="fa fa-times" aria-hidden="true"></i></h5>
                                                            Reason is <?php echo $data_img['feedback']; ?>
                                                            <a class="alert-link" href="Image-Upload.php" style="text-decoration: none;"><br>
                                                                <h7 class="align-items-center text-center">Click here to upload image again&nbsp;</h7>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="card-body align-items-center text-center btn btn-light">
                                                <div class="row">
                                                    <h5 class="align-items-center text-center">Your
                                                        Registration
                                                        Form is
                                                        Approved&nbsp;<i class="fa fa-check-square-o" aria-hidden="true"></i></h5>
                                                    <br>
                                                    <div class="align-items-center text-center">
                                                        <div class="progress">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>

                                                    </div><br>
                                                    <a href="Image-Upload.php" style="text-decoration: none;">
                                                        <h7 class="align-items-center text-center">Click
                                                            here to
                                                            upload
                                                            the
                                                            image of the meter</h7>
                                                    </a>
                                                </div>
                                            </div>

                                        <?php
                                        }
                                    } else if ($status == 'Rejected') {
                                        ?>
                                        <div class="card-body align-items-center text-center btn btn-light">
                                            <div class="row">
                                                <h5 class="align-items-center text-center">Your
                                                    Registration
                                                    Form is
                                                    Rejected&nbsp;<i class="fa fa-times" aria-hidden="true"></i></h5><br>
                                                <div class="progress">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div><br>
                                                <a href="User-Register.php" style="text-decoration: none;">
                                                    <h7 class="align-items-center text-center">
                                                        Click
                                                        here to
                                                        see what went wrong</h7>
                                                </a>
                                            </div>
                                        </div>

                                    <?php
                                    } else {
                                    ?>
                                        <div class="card-body align-items-center text-center btn bg-white">
                                            <div class="row">
                                                <div class="alert alert-danger" role="alert">
                                                    <h5 class="align-items-center text-center">You have not registered still! &nbsp;<i class="fa fa-exclamation" aria-hidden="true"></i></h5>
                                                    <a class="alert-link" href="User-Register.php" style="text-decoration: none;"><br>
                                                        <h7 class="align-items-center text-center">Click here to register for the bill&nbsp;<i class="fa fa-credit-card" aria-hidden="true"></i></h7>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }

                                ?>
                            </div>
                        </div>
               
             
<div class="row justify-content-center wrapper">
    <div class="col-lg-7 bg-white p-4 pt-12">
        <?php
        if($dataDetails['status'] == 'Pending'){
            ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Dear <?php echo $uname?>!</strong> You have succesfully uploaded the form. Your form is still
            <?php echo $dataDetails['status']; ?> and it is displayed in below. If you have any issues contact us.
        </div>
        <?php
        }

        else if($dataDetails['status'] == 'Approved'){
            ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Dear <?php echo $uname?>!</strong> You have succesfully uploaded the form. Now you can upload an
            image of you Gas meter
            to get updated about your bill. Your form is
            <?php echo $dataDetails['status']; ?> and it is displayed in below. If you have any issues contact us.
        </div>
        <div class="form-group">
            <div>
                <h5 class="text-center" style="color: green;">Approved&nbsp;<i class="fa fa-check-square-o" aria-hidden="true"></i></h5>
            </div>
        </div>
        <?php
        }

        else{
            ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Dear <?php echo $uname?>!</strong> Your form is
            <?php echo $dataDetails['status']; ?> and see what went wrong in below 'Reasons to be Rejected' field. If
            you have any issues contact us.

        </div>
        <?php
        }
        ?>

        <h4 class="text-center font-weight-bold">Registered Gas Bill Management Form</h4>
        <hr class="my-3" />
        <form class="px-3 needs-validation">
            <div class="form-group">
                <label>Name of the Person</label>
                <input type="text" class="form-control" name="name" disabled
                    value="<?php echo $dataDetails['name']; ?>">
            </div><br>

            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="user_address" disabled
                    value="<?php echo $dataDetails['user_address']; ?>">
            </div><br>

            <div class="form-group">
                <label>Area Office</label>
                <input type="text" class="form-control" name="user_area" disabled
                    value="<?php echo $dataDetails['user_area']; ?>">
            </div><br>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Premises ID</label>
                    <input type="text" class="form-control" name="user_premises" disabled
                        value="<?php echo $dataDetails['user_premises']; ?>">
                </div>

                <div class="form-group col-md-6">
                    <label>Gas Account Number</label>
                    <input type="text" class="form-control" name="user_account" disabled
                        value="<?php echo $dataDetails['user_account']; ?>">
                </div>
            </div><br>

            <?php
            if($dataDetails['status'] == 'Rejected'){
                ?>
            <div class="form-group">
                <label>Reasons to be Rejected</label>
                <input type="textarea" class="form-control" name="user_area" disabled
                    value="<?php echo $dataDetails['feedback']; ?>">
            </div><br>
            <div class="form-group">
                <a href="Register-Again.php" class=" btn btn-danger">
                    Register Again</a>
            </div><br>
            <?php
            }
            ?>

            <hr class="my-3" />
        </form>
    </div>
    <!-- Registration Form End -->
</div>
<?php

}

else{
    $name = $user_address = $user_account = $user_area = $user_premises = "";
    $name_err = $address_err = $acc_err = $area_err = $premises_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

    // Check input errors before inserting in database
    if (empty($name_err) && empty($address_err) && empty($area_err) && empty($premises_err) && empty($acc_err)) {


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
                header("Location: User-Registered.php");
                mysqli_query($link,$activity);
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
          
<div class="row justify-content-center wrapper">
    <div class="col-lg-7 bg-white p-4 pt-12">
        <h4 class="text-center font-weight-bold">Registration for Gas Billing System</h4>
        <hr class="my-3" />
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="px-3 needs-validation"
            id="user_add">

            <p style="font-size: 14px;">*Please fill this form to register for the Gas bill management system. All
                the information is related to manual bill</p>

            <div class="form-group">
                <label>Name of the Person</label>
                <input type="text" class="form-control" name="name" placeholder="Enter the Name"
                    value="<?php echo $name; ?>" required>
                <span class="help-block"><?php echo $name_err; ?></span>
            </div><br>

            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="user_address" placeholder="Enter the Address"
                    value="<?php echo $user_address; ?>" required>
                <span class="help-block"><?php echo $address_err; ?></span>
            </div><br>

            <div class="form-group">
                <label>Area Office</label>
                <input type="text" class="form-control" name="user_area" placeholder="Enter the Area Office"
                    value="<?php echo $user_area; ?>" required>
                <span class="help-block"><?php echo $area_err; ?></span>
            </div><br>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Premises ID</label>
                    <input type="text" class="form-control" name="user_premises" placeholder="Enter the Premises ID"
                        value="<?php echo $user_premises; ?>" required>
                    <span class="help-block"><?php echo $premises_err; ?></span>
                </div>

                <div class="form-group col-md-6">
                    <label>Gas Account Number</label>
                    <input type="text" class="form-control" name="user_account" placeholder="Enter the Account Number"
                        value="<?php echo $user_account; ?>" required>
                    <span class="help-block"><?php echo $acc_err; ?></span>
                </div>
            </div><br>

            <div class="form-group">
                <button class="btn btn-danger btn-lg btn-block myBtn" type="submit " name="submit">Submit</button>
            </div><br>

            <hr class="my-3" />
        </form>
    </div>
    <!-- Registration Form End -->
</div>
                </div>
                              



<style>
.help-block {
    color: red;
}
</style>
        
    </body>  

<?php
}
?>


<?php
require_once 'User-Footer.php'
?>
</html>


