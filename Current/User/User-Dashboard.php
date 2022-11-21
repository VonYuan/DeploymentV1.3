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

    <title>Petros</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="Appland/assets/img/petros-favicon.png" rel="icon">
    <link href="Appland/assets/img/petros-apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="Appland/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="Appland/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="Appland/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="Appland/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="Appland/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="Appland/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="Appland/assets/css/style.css" rel="stylesheet">
    
    <!--External library for icon-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.iconify.design/iconify-icon/1.0.1/iconify-icon.min.js"></script>
 
    

    <!-- =======================================================
  * Template Name: Appland - v4.3.0
  * Template URL: https://bootstrapmade.com/free-bootstrap-app-landing-page-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Quick Access</h2>
                
            </div>

            <div class="row">

<div class="col-lg-12">
    <div class="row">
        
        <div class="col-lg-2 info">
            
            
            <i class='bx bx-registered'></i>
            <h4>Register Gas Account</h4>
            <div class="col-lg p-3 text-center">
             <a href="User-Register.php" class="btn btn-outline-primary btn-md" role="button">Register</a>
            </div>
        </div>
        <div class="col-lg-2 info">
            <i class='bx bxs-receipt'></i>
            <h4>Payment For Bill</h4>
            <div class="col-lg p-3 text-center">
             <a href="ViewPay.php" class="btn btn-outline-primary btn-md" role="button">Pay</a>
            </div>
        </div>
        <div class="col-lg-2 info">
            <i class='bx bxs-notification' ></i>
            <h4>Notification</h4>
            <br>
            <div class="col-lg p-2 text-center">
             <a href="Notifications.php" class="btn btn-outline-primary btn-md" role="button">Check</a>
            </div>
        </div>
        <div class="col-lg-2 info">
            <i class="bx bx-user"></i>
            <h4>Profile</h4>
            <br>
            <div class="col-lg p-2 text-center">
             <a href="Profile.php" class="btn btn-outline-primary btn-md" role="button">View profile</a>
            </div>
        </div>
        <div class="col-lg-2 info">
            <i class="bx bx-tachometer"></i>
            <h4>Usage</h4>
            <br>
            <div class="col-lg p-2 text-center">
             <a href="Usage.php" class="btn btn-outline-primary btn-md" role="button">View Usage</a>
            </div>
        </div>
        <div class="col-lg-2 info">
            <i class="bx bx-log-out"></i>
            <h4>Log-Out</h4>
            <br>
            <div class="col-lg p-2 text-center">
             <a href="User-Logout.php" class="btn btn-outline-primary btn-md" role="button">Log-Out</a>
            </div>
        </div>
    </div>
</div>



</div>

        </div>
    </section>




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