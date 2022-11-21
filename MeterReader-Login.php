<?php
//Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
// if(isset($_SESSION["loggedin_admin"]) && $_SESSION["loggedin_admin"] === true){
//   header("location: Admin-Dashboard.php");
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
$reader_username = $reader_password = "";
$username_err = $password_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["reader_username"]))) {
        $username_err = "Please enter the username.";
    } else {
        $reader_username = trim($_POST["reader_username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["reader_password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $reader_password = trim($_POST["reader_password"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement

        $sql = "SELECT reader_id, reader_name, reader_password FROM meter_reader WHERE reader_name = ?";

        if ($stmt = $link->prepare($sql)) {


            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = $reader_username;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if ($stmt->num_rows() == 1) {
                    // Bind result variables
                    $stmt->bind_result($reader_id, $reader_username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($reader_password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin_reader"] = true;
                            $_SESSION["reader_id"] = $reader_id;
                            $_SESSION["reader_name"] = $reader_username;

                            // Redirect user to welcome page
                            header("location: Current/MeterReader/View-Address.php");
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
    <link href="Appland/assets/img/favicon.png" rel="icon">
    <link href="Appland/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

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
    <div class="container" data-aos="fade-up">
    <main id="main" style="align-items: center;"><br>
        <section class="inner-page">
            <div class="row gutters-sm justify-content-center">
                <div class="col-md-6">
                    <div class="card border shadow-lg p-4">
                        <div class="container">
                            <div class="card-body">
                                <div class="row">
                                <div class=" col-md-10">
                                    <h2>Meter Reader Login </h2>
                                        <p>Please fill in your meter reader credentials to login.</p>
                                    </div>
                                    <!--<div class=" col-md-2"><img src="images/petros.jpg"></div>-->

                                </div>

                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <div class="form-group" style="opacity: 0.8;">
                                        <label>Username</label>
                                        <input type="text" name="reader_username" class="form-control" value="<?php echo $reader_username; ?>">
                                        <span class="help-block"><?php echo $username_err; ?></span>
                                    </div><br>
                                    <div class="form-group" style="opacity: 0.8;">
                                        <label>Password</label>
                                        <input type="password" name="reader_password" class="form-control">
                                        <span class="help-block"><?php echo $password_err; ?></span>

                                    </div><br>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-md btn-block myBtn" value="Login" style="float: right;background-color: #5777ba;color: white;width: 25%;">
                                    </div>
                                    <div class="forgot float-right">
                                        <a href="Current/meterReader/Forgot-Password.php">Forgot Password?</a>
                                    </div>
                                    <br>
                                    <div class="forgot float-right">
                                        <a href="MeterReader-Register.php">Already have account?</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>
    </div>
   <!-- End #main -->

  

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
      });
  });
  </script>
    
<style>
  /* OVERRIDE GOOGLE TRANSLATE WIDGET CSS BEGIN */
  div#google_translate_element div.goog-te-gadget-simple {
      border: none;
      background-color: #5777ba;
    
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