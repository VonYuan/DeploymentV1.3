<?php
require_once '../../Config.php';
require_once 'User-Header.php';
//$users_data =check_login($con);

$username_err=$user_nic=$email_err=$contact_err="";
$username_err = $password_err = $email_err = $confirm_password_err = $nic_err = $contact_err = $new_password_err  = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
        $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM users WHERE user_id='" . $user_id . "'";
    $records = mysqli_query($link, $sql);
    $data = mysqli_fetch_assoc($records);

    $user_name = $data['user_name'];
    $user_nic = $data['user_nic'];
    $user_contact = $data['user_contact'];
    $user_email = $data['user_email'];
    $gender = $data['gender'];

    
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

         $user_name = trim($_POST["user_name"]);
        } else {
            echo "Oops! Something went wrong when inserting username. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }

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
                        $emailsql = "SELECT * FROM users WHERE user_id='" . $user_id . "'";
                        $emailsqlResult=mysqli_query($link, $emailsql);
                        $getemail=mysqli_fetch_array($emailsqlResult);

                        $uicR=$getemail['user_email'];
                        $Eemail=$_POST["user_email"];
                        if($uicR!=$Eemail)
                        {
                            $email_err = "This Email Address is already taken.";
                        }
                        
                        
                        
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
        
        $uicValue=$_POST['user_nic'];
        
    
        $checkicquery="SELECT * FROM users WHERE user_nic='" . $uicValue . "'";
        $checkicReault=mysqli_query($link,$checkicquery);
        $countic=mysqli_num_rows($checkicReault);
        if($countic>0)
        {
            $uicsql = "SELECT * FROM users WHERE user_id='" . $user_id . "'";
            $uicResult=mysqli_query($link, $uicsql);
            $getUIC=mysqli_fetch_array($uicResult);
            $uicR=$getUIC['user_nic'];
            if($uicValue!=$uicR)
            {
               $nic_err = "This NIC had been used";
            }
        }
        

        if (!preg_match("/^[0-9]*$/",($_POST["user_nic"]))) {
            $nic_err = "Only Number are allowed";
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

        if(isset($_POST['gender']))
        {
            $gender = $_POST['gender'];
        }
    

    if (empty($username_err) && empty($user_nic) && empty($email_err) && empty($contact_err))
    {
        $update = "UPDATE users SET user_name = '$user_name', user_nic = '$user_nic', 
        user_contact = '$user_contact', user_email = '$user_email', gender = '$gender' WHERE user_id = '$user_id'";

        $message = "Profile updated";
        $activity = "INSERT INTO activity_log (user_id, message) VALUES ('$user_id', '$message')";
        if(mysqli_query($link,$update)){
            mysqli_query($link,$activity);
            header("Location:Profile.php");
        }

        else{
            mysqli_error($link);
        }
        
    }
    
}



?>
<!DOCTYPE html>
<html lang="en">
<body>
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

<div class="row justify-content-center wrapper">
    
    <div class="col-lg-6 bg-white p-4 pt-12" style="background-color: #E5E4E2;">
        <div class="row gutters-sm">
            <div class=" col-md-12 mb-3">
                <div class="card border shadow-lg p-2">
                    <h2 class="align-items-center text-center">Profile</h2>
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                                       
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark" style="color: white;">
                                                <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                                            </div>
                                            <div class="modal-body" style="text-align: left;">
                                                <form action="updateProfile.php" method="POST" class="px-3 needs-validation">

                                                    <div class="form-group">
                                                        <label>Username</label>
                                                        <input type="text" class="form-control" name="user_name" value="<?php echo $data['user_name']; ?>">
                                                        <span class="help-block"><?php echo $username_err; ?></span>
                                                    </div><br>
                                                    <div class="form-group">
                                                        <label>Gender</label>
                                                        <select id="gender" name="gender" class="form-control">
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div><br>

                                                    <div class="form-group">
                                                        <label>NIC Number</label>
                                                        <input type="text" class="form-control" name="user_nic" value="<?php echo $data['user_nic'] ?>">
                                                        <span class="help-block"><?php echo $nic_err; ?></span>

                                                    </div><br>
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="text" class="form-control" name="user_email" placeholder="Enter Email" value="<?php echo $data['user_email']; ?>">
                                                        <span class="help-block"><?php echo $email_err; ?></span>
                                                    </div><br>

                                                    <div class="form-group">
                                                        <label>Contact No</label>
                                                        <input type="text" class="form-control" name="user_contact" placeholder="Enter a Contact Number" value="<?php echo $data['user_contact']; ?>">
                                                        <span class="help-block"><?php echo $contact_err; ?></span>
                                                    </div><br>

                                           
                                            <div class="modal-footer">

                                                <button type="submit" class="btn btn-success">Save changes</button>
                                                <a href="Profile.php" class="btn btn-secondary" role="button">Back</a>
                                            </div>
                                            </form>
                                                </div>
                                        </div>
                                    
                                





                            
                        </div><br>
                         
=
                    
                    </div>
                </div>
            </div>


        </div>

    </div>
</div>

    
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="Appland/assets/vendor/aos/aos.js"></script>
    <script src="Appland/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="Appland/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="Appland/assets/vendor/php-email-form/validate.js"></script>
    <script src="Appland/assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Template Main JS File -->
    <script src="Appland/assets/js/main.js"></script>
    <style>
        .help-block {
            color: red;
        }
    </style>
    </body>
</html>




<?php
require_once 'User-Footer.php'
?>