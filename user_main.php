<?php

//Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
// if(isset($_SESSION["loggedin_user"]) && $_SESSION["loggedin_user"] === true){
//   header("location: User-Dashboard.php");
//   exit;
// }

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'ocawbms');

$link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Define variables and initialize with empty values
$user_name = $user_password = "";
$username_err = $password_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["user_name"]))) {
        $username_err = "Please enter the username.";
    } else {
        $user_name = trim($_POST["user_name"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["user_password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $user_password = trim($_POST["user_password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement

        $sql = "SELECT user_id, user_name, user_password FROM users WHERE user_name = ?";

        if ($stmt = $link->prepare($sql)) {


            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = $user_name;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if ($stmt->num_rows() == 1) {
                    // Bind result variables
                    $stmt->bind_result($user_id, $user_name, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($user_password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin_user"] = true;
                            $_SESSION["user_id"] = $user_id;
                            $_SESSION["user_uname"] = $user_name;

                            // Redirect user to welcome page
                            header("location: Current/User/User-Dashboard.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $link->close();
}
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
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                includedLanguages: 'en,ms,zh-CN',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>

    <!-- =======================================================
  * Template Name: Appland - v4.3.0
  * Template URL: https://bootstrapmade.com/free-bootstrap-app-landing-page-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top  header-transparent ">
        <div class="container d-flex align-items-center justify-content-between">

            <div class="logo">
                <h1><a href="Appland/index.html">Petros</a></h1>
                <!-- Uncomment below if you prefer to use an image logo -->
                <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
            </div>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto" href="Appland/index.php">Home</a></li>
                    <li><a class="nav-link scrollto active">Log in</a></li>
                    <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
                    <li><a class="getstarted scrollto" href="#">Language
                            <div id="google_translate_element"></div>
                        </a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    <main id="main" style="align-items: center;"><br>
        <section class="inner-page">
            <div class="row gutters-sm justify-content-center">
                <div class="col-md-6">
                    <div class="card border shadow-lg ">
                        <div class="container">
                            <div class="card-body">
                                <h2>User Login</h2>
                                <p>Please fill in your user credentials to login.</p>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <div class="form-group" style="opacity: 0.8;">
                                        <label>Username</label>
                                        <input type="text" name="user_name" class="form-control" value="<?php echo $user_name; ?>">
                                        <span class="help-block"><?php echo $username_err; ?></span>
                                    </div><br>
                                    <div class="form-group" style="opacity: 0.8;">
                                        <label>Password</label>
                                        <input type="password" name="user_password" class="form-control">
                                        <span class="help-block"><?php echo $password_err; ?></span>

                                    </div><br>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-md btn-block myBtn" value="Login" style="float: right;background-color: #5777ba;color: white;width: 25%;">
                                    </div><br><br>
                                    <p>You will be logged into Gas billong management system. </p>
                                    <div class="forgot float-right">
                                        <a href="User-Forgot-Password.php" style="text-decoration: none;">Forgot Password?</a>
                                        <br>
                                        <a href="User-Register.php" style="text-decoration: none;">Dont have account?</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main><!-- End #main -->
    
    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Quick Access</h2>
            </div>

            <div class="row">

<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-12 info">
           
            <a href="User-Login.php" class="btn btn-primary" role="button">Login</a>
            <a href="User-Register.php" class="btn btn-primary" role="button">Register Gas Meter</a>
            <a href="Current/User/User-Dashboard.php" class="btn btn-primary" role="button">Dashboard</a>
            <a href="Current/User/User-Pay.php" class="btn btn-primary" role="button">Payment</a>
            
            
            
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
    <script>
  $('document').ready(function() {

      $('#google_translate_element').on("click", function() {

          // Change font family and color
          $("iframe").contents().find(
                  ".goog-te-menu2-item div, .goog-te-menu2-item:link div, .goog-te-menu2-item:visited div, .goog-te-menu2-item:active div"
              ) 
          // Change Google's default blue border
          // $("iframe").contents().find('.goog-te-menu2').css('border', '1px solid red');

          // $("iframe").contents().find('.goog-te-menu2').css('background-color', 'red');

          // Change the iframe's box shadow
          // $(".goog-te-menu-frame").css({
          //     '-moz-box-shadow': '0 3px 8px 2px #666666',
          //     '-webkit-box-shadow': '0 3px 8px 2px #666',
          //     'box-shadow': '0 3px 8px 2px #666'
          // });
      });
  });
  </script>

  <style>
  /* OVERRIDE GOOGLE TRANSLATE WIDGET CSS BEGIN */
  div#google_translate_element div.goog-te-gadget-simple {
      border: none;
      background-color: #5777ba;
      /*#e3e3ff*/
  }

  div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value:hover {
      text-decoration: none;
  }

  div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span {
      color: white;
  }

  div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span:hover {
      color: white;
  }

  .goog-te-gadget-icon {
      display: none !important;
      /*background: url("url for the icon") 0 0 no-repeat !important;*/
  }

  /* Remove the down arrow */
  /* when dropdown open */
  div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span[style="color: rgb(213, 213, 213);"] {
      display: none;
  }

  /* after clicked/touched */
  div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span[style="color: rgb(118, 118, 118);"] {
      display: none;
  }

  /* on page load (not yet touched or clicked) */
  div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span[style="color: rgb(155, 155, 155);"] {
      display: none;
  }

  /* Remove span with left border line | (next to the arrow) in Chrome & Firefox */
  div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span[style="border-left: 1px solid rgb(187, 187, 187);"] {
      display: none;
  }

  /* Remove span with left border line | (next to the arrow) in Edge & IE11 */
  /* div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span[style="border-left-color: rgb(187, 187, 187); border-left-width: 1px; border-left-style: solid;"] {
      display: none;
  } */

  /* HIDE the google translate toolbar */
  .goog-te-banner-frame.skiptranslate {
      display: none !important;
  }

  body {
      top: 0px !important;
  }

  /* OVERRIDE GOOGLE TRANSLATE WIDGET CSS END */
  </style>

</body>

</html>