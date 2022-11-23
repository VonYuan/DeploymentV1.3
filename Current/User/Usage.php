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
    <div class="container" data-aos="fade-up">
        <div class="row justify-content-center wrapper ">
            <div class=" col-lg-6 bg-white p-4 pt-12">
                
                   
                    <?php
                    $sql_units = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' LIMIT 6";
                    $records_units = mysqli_query($link, $sql_units);
                    $tot_months =  array();

                    while ($data_units = mysqli_fetch_array($records_units)) {
                        array_push($tot_months, $data_units['month']);
                        $units[] = $data_units['units'];
                    }
                    ?>


                </div>
            
        </div>

                        <div class="col-md-14">
                            <div class="card border shadow-lg mb-2 p-2">
                                <div class="card-body align-items-center text-center">
                                    <?php
                                    if(!empty($data_method['category']))
                                    {
                                       if ($data_method['category'] == 'Domestic') {
                                    ?>
                                     <h2 class="align-items-center text-center">Gas Usage</h2>
                                        <div class="card border shadow-lg p-2">
                                            <div class="card-body align-items-center text-center">
                                                <canvas id="chartjs_bar"></canvas>
                                            </div>
                                            <h3>Chart above was your overall gas consumption.</h3>
                                        </div>
                                        
                                        
                                    <?php
                                    }
                                       
                                    ?>
                                        
                                 <?php
                                    } else{ ?>
                                    <h3>Please Register your account in order yo see your consumption</h3> 
                                    <a href="RegisterBill.php">Register your bill here.</a>
                                    <?php }
                                           
                                ?>
                                        
                                </div>
                            </div>
                        </div>
    </div>
   

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



<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script type="text/javascript">
    var ctx = document.getElementById("chartjs_bar").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($tot_months); ?>,
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
                data: <?php echo json_encode($units); ?>,
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
require_once 'User-Footer.php'
?>
</html>