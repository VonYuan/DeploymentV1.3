<?php
require_once 'Config.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);


$reader_name = $reader_gender = $reader_nic = $reader_email = $reader_contact = $reader_password = $confirm_password = $send_password = "";
$username_err = $password_err = $email_err = $confirm_password_err = $nic_err = $contact_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate user Username
    if (empty(trim($_POST["reader_name"]))) {
        $reader_name = "Please enter a username.";
    } else {
        // Prepare a select statement
        $sql = "SELECT reader_id FROM meter_reader WHERE reader_name = ?";

        if ($stmt = $link->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["reader_name"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                /* store result */
                $stmt->store_result();

                if ($stmt->num_rows() >= 1) {
                    $username_err = "This Username is already taken.";
                } else {
                    $reader_name = trim($_POST["reader_name"]);
                }
            } else {
                echo "Oops! Something went wrong when inserting username. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    //validate email
    if (empty(trim($_POST["reader_email"]))) {
        $email_err = "Please enter an email address!";
    } else {
        $reader_email = trim($_POST["reader_email"]);
        $reader_email = stripslashes($reader_email);
        $reader_email = htmlspecialchars($reader_email);
        if (!filter_var($reader_email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format";
        } else {
            // Prepare a select statement
            $sql = "SELECT reader_id FROM meter_reader WHERE reader_email = ?";

            if ($stmt = $link->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_email);

                // Set parameters
                $param_email = $reader_email;

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    /* store result */
                    $stmt->store_result();

                    if ($stmt->num_rows() >= 1) {
                        $email_err = "This Email Address is already taken.";
                    } else {
                        $reader_email = trim($_POST["reader_email"]);
                    }
                } else {
                    echo "Oops! Something went wrong when inserting email. Please try again later.";
                }

                // Close statement
                $stmt->close();
            }
        }
    }

    if (!preg_match("/^[0-9'V'v]*$/", strlen($_POST["reader_nic"]))) {
        $nic_err = "Only Numbers and V or v allowed for old version and Only Numbers are allowed for new version";
    } else if (strlen($_POST["reader_nic"]) != 10 && strlen($_POST["reader_nic"]) != 12) {
        $nic_err = "NIC number is Invalid";
    } else {
        $reader_nic = $_POST['reader_nic'];
    }

    if (empty(trim($_POST["reader_contact"]))) {
        $contact_err = "Please enter a Contact Number.";
    } elseif (!preg_match("/^[0-9]*$/", strlen($_POST["reader_contact"]))) {
        $contact_err = "Invalid Contact Number.";
    }elseif (strlen(trim($_POST["reader_contact"])) != 10) {
        $contact_err = "Invalid Contact Number.";
    } else {
        $reader_contact = trim($_POST["reader_contact"]);
        $send_contact = $reader_contact;
    }

    // Validate password
    if (empty(trim($_POST["reader_password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["reader_password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $reader_password = trim($_POST["reader_password"]);
        $send_password = $reader_password;
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (($reader_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    $reader_gender = $_POST['gender'];

    // Check input errors before inserting in database
    if (empty($username_err) && empty($email_err) && empty($nic_err) && empty($contact_err) && empty($password_err) && empty($confirm_password_err)) {


        // Prepare an insert statement
        $sql = "INSERT INTO meter_reader (reader_name, gender, reader_nic, reader_email, reader_contact, reader_password) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $link->prepare($sql)) {


            // Bind variables to the prepared statement as parameters
            if ($stmt->bind_param("ssssss", $param_username, $param_gender, $param_nic, $param_email, $param_contact, $param_password))

                // Set parameters
            $param_username = $reader_name;
            $param_gender = $reader_gender;
            $param_nic = $reader_nic;
            $param_email = $reader_email;
            $param_contact = $reader_contact;
            $param_password = password_hash($reader_password, PASSWORD_DEFAULT); // Creates a password hash


            // Attempt to execute the prepared statement
            if ($stmt->execute()) {

                try {
                    //Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.mailtrap.io';
                    $mail->SMTPAuth = true;
                    $mail->Username = "13a4d424c492e1";
                    $mail->Password = "b0610fb36617fb";
                    $mail->Port = 2525;

                    //Recipients
                    $mail->setFrom("13a4d424c492e1@gmail.com", "Petros Billing System");
                    $mail->addAddress($reader_email);     // Add a recipient

                    // Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Online Gas Billing Management System';

                    $mail->Body    = "<h3>Welcome to the Online Gas Billing Management System</h3><br><br>Your user account has been created succesfully.<br><br> Here's your account information:<br>
                     Username: $reader_name <br>
                     Gender: $reader_gender<br>
                     NIC: $reader_nic<br>
                     Email: $reader_email<br>
                     Contact No: $reader_contact<br>
                     <br> Best Regards, <br> OEAWBMS Team";

                    $mail->send();
                    //  echo $user->showwMessage('success','We have send you  reset link,please check your email');

                } catch (Exception $e) {
                     echo 'Something went wrong,try again later';

                }
                header("Location: MeterReader-Register.php");
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

<?php


// Check if the user is already logged in, if yes then redirect him to welcome page
// if(isset($_SESSION["loggedin_user"]) && $_SESSION["loggedin_user"] === true){
//   header("location: User-Dashboard.php");
//   exit;
// }
// if ($link === false) {
//     die("ERROR: Could not connect. " . mysqli_connect_error());
// }

// // Define variables and initialize with empty values
// $user_name = $user_password = "";
// $username_err = $password_err = "";


// // Processing form data when form is submitted
// if ($_SERVER["REQUEST_METHOD"] == "POST") {

//     // Check if username is empty
//     if (empty(trim($_POST["user_name"]))) {
//         $username_err = "Please enter the username.";
//     } else {
//         $user_name = trim($_POST["user_name"]);
//     }

//     // Check if password is empty
//     if (empty(trim($_POST["user_password"]))) {
//         $password_err = "Please enter your password.";
//     } else {
//         $user_password = trim($_POST["user_password"]);
//     }

//     // Validate credentials
//     if (empty($username_err) && empty($password_err)) {
//         // Prepare a select statement

//         $sql = "SELECT user_id, user_name, user_password FROM users WHERE user_name = ?";

//         if ($stmt = $link->prepare($sql)) {


//             // Bind variables to the prepared statement as parameters
//             $stmt->bind_param("s", $param_username);

//             // Set parameters
//             $param_username = $user_name;

//             // Attempt to execute the prepared statement
//             if ($stmt->execute()) {
//                 // Store result
//                 $stmt->store_result();

//                 // Check if username exists, if yes then verify password
//                 if ($stmt->num_rows() == 1) {
//                     // Bind result variables
//                     $stmt->bind_result($user_id, $user_name, $hashed_password);
//                     if ($stmt->fetch()) {
//                         if (password_verify($user_password, $hashed_password)) {
//                             // Password is correct, so start a new session
//                             session_start();

//                             // Store data in session variables
//                             $_SESSION["loggedin_user"] = true;
//                             $_SESSION["user_id"] = $user_id;
//                             $_SESSION["user_uname"] = $user_name;

//                             // Redirect user to welcome page
//                             header("location: Current/User/User-Dashboard.php");
//                         } else {
//                             // Display an error message if password is not valid
//                             $password_err = "The password you entered was not valid.";
//                         }
//                     }
//                 } else {
//                     // Display an error message if username doesn't exist
//                     $username_err = "No account found with that username.";
//                 }
//             } else {
//                 echo "Oops! Something went wrong. Please try again later.";
//             }

//             // Close statement
//             $stmt->close();
//         }
//     }

//     // Close connection
//     $link->close();
// }
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
                includedLanguages: 'en,si,ta',
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
                    <li><a class="nav-link scrollto active">Sign in</a></li>
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
            <div class="container h-100 p-4">
                <div class="row h-100 align-items-center justify-content-center">
                    <div class="col-lg-6">
                        <div class="card border shadow-lg p-4">
                            <h3 class="text-center font-weight-bold p-2">Meter Reader Registration for New Account</h3>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="px-3 needs-validation" id="reader_add">

                                <p style="font-size: 14px;">*Please fill this form to create an user account.</p>

                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" name="reader_name" placeholder="Enter a Username" value="<?php echo $reader_name; ?>" required>
                                    <span class="help-block"><?php echo $username_err; ?></span>
                                </div><br>

                                <div class="row">
                                    <div class=" col-md-6">
                                        <label>Gender</label>
                                        <select id="gender" name="gender" class="form-control">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <div class=" col-md-6">
                                        <label>NIC Number</label>
                                        <input type="text" class="form-control" name="reader_nic" placeholder="Enter the NIC Number" value="<?php echo $reader_nic; ?>" required>
                                        <span class="help-block"><?php echo $nic_err; ?></span>
                                    </div>
                                </div><br>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="reader_email" placeholder="Enter Email" value="<?php echo $reader_email; ?>" required>
                                    <span class="help-block"><?php echo $email_err; ?></span>
                                </div><br>

                                <div class="form-group">
                                    <label>Contact No</label>
                                    <input type="text" class="form-control" name="reader_contact" placeholder="Enter a Contact Number" value="<?php echo $reader_contact; ?>" required>
                                    <span class="help-block"><?php echo $contact_err; ?></span>
                                </div><br>

                                <div class="row">
                                    <div class=" col-md-6">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="reader_password" placeholder="Enter Password" required>
                                        <span class="help-block"><?php echo $password_err; ?></span>
                                    </div>

                                    <div class=" col-md-6">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control" name="confirm_password" placeholder="Re-Enter Password" required>
                                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                                    </div>
                                </div>
                                <hr class="my-3" />

                                <div>
                                    <button class="btn btn-primary btn-md btn-block myBtn" type="submit " name="submit" style="background-color: #5777ba;color: white;width: 100%;">Create
                                        Account</button>
                                </div><br>


                            </form>
                        </div>
                        <!-- Registration Form End -->
                    </div>
                </div>
            </div>

        </section>

    </main><!-- End #main -->

    <style>
        .help-block {
            color: red;
        }
    </style>

    

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