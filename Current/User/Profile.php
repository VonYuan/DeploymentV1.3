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
                            <?php
                            if ($data['gender'] == "Male") { ?>
                                <img src="https://img.icons8.com/color/120/000000/person-male.png" />
                            <?php
                            } else if ($data['gender'] == "Female") { ?>
                                <img src="https://img.icons8.com/color/120/000000/person-female.png" />
                            <?php
                            } else { ?>
                                <img src="https://img.icons8.com/material-rounded/120/000000/user.png" />
                            <?php
                            }

                            ?>

                            <div class="mt-3">
                                <h4><?php echo $user_name ?></h4>
                                <a href="Edit-Profile.php" class="btn btn-outline-success" role="button">Edit Profile&nbsp;<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editModal">
                                    Edit Profile&nbsp;<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>


                                <!-- Modal -->
                                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark" style="color: white;">
                                                <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                                                
                                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal" style="color: white;" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                                            </div>
                                            <div class="modal-body" style="text-align: left;">
                                                <form action="Edit.php" method="POST" class="px-3 needs-validation">

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

                                            </div>
                                            <div class="modal-footer">

                                                <button type="submit" class="btn btn-success">Save changes</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                                    Change Password&nbsp;<i class="fa fa-unlock-alt" aria-hidden="true"></i>
                                </button>


                                <!-- Modal -->
                                <div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark" style="color: white;">
                                                <h5 class="modal-title" id="editPasswordModalLabel">Change Password</h5>
                                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal" style="color: white;" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                                            </div>
                                            <div class="modal-body" style="text-align: left;">
                                                <form action="Edit-Password.php" method="POST" class="px-3 needs-validation">

                                                    <div class="form-group">
                                                        <label>New Password</label>
                                                        <input type="password" name="new_password" class="form-control">
                                                        <span class="help-block"><?php echo $new_password_err; ?></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Confirm Password</label>
                                                        <input type="password" name="confirm_password" class="form-control">
                                                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                                                    </div>

                                            </div>
                                            <div class="modal-footer">

                                                <button type="submit" class="btn btn-success">Save changes</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><br>
                         
                        <div class="col-md-12 text-center">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="text-center">
                                            <h6 class="mb-0"><ins>Gender</ins></h6>
                                        </div>
                                        <div class="text-center text-secondary">
                                            <?php echo $data['gender'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="text-center">
                                            <h6 class="mb-0">NIC Number</h6>
                                        </div>
                                        <div class="text-center text-secondary">
                                            <?php echo $data['user_nic'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="text-center">
                                            <h6 class="mb-0">Email Address</h6>
                                        </div>
                                        <div class="text-center text-secondary">
                                            <?php echo $data['user_email'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="text-center">
                                            <h6 class="mb-0">Contact Number</h6>
                                        </div>
                                        <div class="text-center text-secondary">
                                            <?php echo $data['user_contact'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="text-center">
                                            <h6 class="mb-0">Joined</h6>
                                        </div>
                                        <div class="text-center text-secondary">
                                            <?php echo $data['created_at'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
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