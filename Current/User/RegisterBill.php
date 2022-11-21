<?php
include_once 'User-Header.php';
require_once '../../Config.php';
$user_id = $_SESSION['uid'];
$uname = $_SESSION['user_uname'];
$sql_record = "SELECT * FROM current_details WHERE user_id='" . $user_id . "'";

$recordsDetails = mysqli_query($link, $sql_record);
if($dataDetails = mysqli_fetch_assoc($recordsDetails)){
  
 ?>

<?php


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
    
    <?php
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
                 echo "<script> location.href='User-Dashboard.php'; </script>";
                exit;
                #mysqli_query($link,$activity);
                #exit();
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
    
     <body>
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                        <h4 class="modal-title" id="photo Label" style="color: white;">
                                                            Register Gas Bill</h4>
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-times" aria-hidden="true"></i></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="px-3 needs-validation">
                                                            <div class="form-row">
                                                                <!--<div class="form-group col-md-2"><img src="../../images/ceb_bill.png"></div>-->
                                                                <div class="form-group col-md-12 p-2">
                                                                    <h5 style="text-align: center;">Registration for Gas Billing System</h5>
                                                                </div>
                                                            </div>
                                                            
                                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="px-3 needs-validation" id="user_add">
                                                                
                                                             <p style="font-size: 14px;">*Please fill this form to register for the Gas bill management system. All the information is related to manual bill</p>
                                                                
                                                                
                                                            <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" class="form-control" name="name" required
                                                                placeholder="Enter the Name">
                                                            </div>
                                                                
                                                            <div class="form-group">
                                                                    <label>Address</label>
                                                                    <input type="text" class="form-control" name="user_address" placeholder="Enter the Address" required>
                                                            </div>
                                                                
                                                                
                                                            <div class="form-group">
                                                                   <label>Area Office</label>
                                                                    <input type="text" class="form-control" name="user_area" placeholder="Enter the Area Office" required>
                                                            </div>
                                                                
                                                                
                                                            <div class="form-row">    
                                                                    <div class="form-group col-md-6">
                                                                       <label>Premises ID</label>
                                                                        <input type="text" class="form-control" name="user_premises" placeholder="Enter the Premises ID" required>
                                                                    </div>
                                                                
                                                                <div class="form-group col-md-6">
                                                                       <label>Gas Account Number</label>
                                                                        <input type="text" class="form-control" name="user_account" placeholder="Enter the Account Number" required>
                                                                </div>
                                                                
                                                                    
                                                            </div>
                                                                
                                                                
                                                            <div class="form-group">
                                                                <button class="btn btn-danger btn-lg btn-block myBtn" type="submit " name="submit">Submit</button>
                                                            </div><br>
                                                        </form>








  

                                                        </div>
                                                    </div>



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


