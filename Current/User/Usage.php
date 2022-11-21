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
                <div class="card border shadow-lg p-2">
                    <h2 class="align-items-center text-center">Gas Usage</h2>
                    <?php
                    $sql_units = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' LIMIT 6";
                    $records_units = mysqli_query($link, $sql_units);
                    $tot_months =  array();

                    while ($data_units = mysqli_fetch_array($records_units)) {
                        array_push($tot_months, $data_units['month']);
                        $units[] = $data_units['units'];
                    }
                    ?>

                    <div class="card-body align-items-center text-center">
                        <canvas id="chartjs_bar"></canvas>
                    </div>
                </div>
            </div>
        </div>

                        <div class="col-md-14">
                            <div class="card border shadow-lg mb-2 p-2">
                                <div class="card-body align-items-center text-center">
                                    <?php

                                    if ($data_method['category'] == 'Domestic') {
                                    ?>
                                        <button type="button" class="btn btn-lg btn-dark" style="width: 100%;" data-bs-toggle="modal" data-bs-target="#domesticeModal">
                                            How your Bill is Calculated?
                                        </button>Click the above button to see how
                                        your
                                        monthly bill is calculated accroding to the
                                        units you used.

                                        <!-- Modal -->
                                        <div class="modal fade" id="domesticeModal" tabindex="-1" aria-labelledby="domesticeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark" style="color: white;">
                                                        <h5 class="modal-title" id="domesticeModalLabel">
                                                            Category -
                                                            Domestic&nbsp;<i class="fa fa-home" aria-hidden="true"></i>
                                                        </h5>
                                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive-sm">
                                                            <table class="table table-striped table-hover" style="text-align: center;">
                                                                <thead style="font-weight: bold;">
                                                                    <tr>
                                                                        <th> Consumption
                                                                        </th>
                                                                        <th>Charge<br>(Rm/mmbtu)
                                                                        </th>
                                                                        <th>Fixed
                                                                            Charge<br>(Rm/mmbtu)
                                                                        </th>
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
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    } else if ($data_method['category'] == 'Industrial 1') {
                                    ?>
                                        <button type="button" class="btn btn-dark" style="color: white;" style="width: 100%;" data-bs-toggle="modal" data-bs-target="#industryModal">
                                            How your Bill is Calculated?
                                        </button><br>Click the above button to see how
                                        your
                                        monthly bill is calculated accroding to the
                                        units you used.

                                        <!-- Modal -->
                                        <div class="modal fade" id="industryModal" tabindex="-1" aria-labelledby="industryModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark" style="color: white;">
                                                        <h5 class="modal-title" id="industryModalLabel">
                                                            Category -
                                                            Industry&nbsp;<i class="fa fa-industry" aria-hidden="true"></i>
                                                        </h5>
                                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive-sm">
                                                            <table class="table table-striped table-hover" style="text-align: center;">
                                                                <thead style="font-weight: bold;">
                                                                    <tr>
                                                                        <th>Consume
                                                                        </th>
                                                                        <th>Charge<br>(Rs/kWh)
                                                                        </th>
                                                                        <th>Fixed<br>Charge<br>(Rs/month)
                                                                        </th>
                                                                        <th>Max<br>Demand<br>Charge<br>(Rs/kVA)
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tr>
                                                                    <td>1-1</td>
                                                                    <td>10.50</td>
                                                                    <td>240.00</td>
                                                                    <td>-</td>
                                                                </tr>
                                                                <td colspan="4" style="padding-right: 350px;">
                                                                    1-2</td>

                                                                <tr>
                                                                    <td>Day</td>
                                                                    <td>10.45</td>
                                                                    <td rowspan="3" style="padding-top: 50px;">
                                                                        3000.00</td>
                                                                    <td rowspan="3" style="padding-top: 50px;">
                                                                        850.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Peak</td>
                                                                    <td>13.60</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Off Peak
                                                                    </td>
                                                                    <td>7.35</td>
                                                                </tr>
                                                                <td colspan="4" style="padding-right: 350px;">
                                                                    1-3</td>
                                                                <tr>
                                                                    <td>Day</td>
                                                                    <td>10.25</td>
                                                                    <td rowspan="3" style="padding-top: 50px;">
                                                                        3000.00</td>
                                                                    <td rowspan="3" style="padding-top: 50px;">
                                                                        750.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Peak</td>
                                                                    <td>13.40</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Off Peak
                                                                    </td>
                                                                    <td>7.15</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    } else if ($data_method['category'] == 'General Purpose 1') {
                                    ?>
                                        <button type="button" class="btn btn-dark" style="width: 100%;" data-bs-toggle="modal" data-bs-target="#generalModal">
                                            How your Bill is Calculated?
                                        </button>Click the above button to see how
                                        your
                                        monthly bill is calculated accroding to the
                                        units you used.

                                        <!-- Modal -->
                                        <div class="modal fade" id="generalModal" tabindex="-1" aria-labelledby="generalModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark" style="color: white;">
                                                        <h5 class="modal-title" id="generalModalLabel">
                                                            Category - General
                                                            Purpose&nbsp;<i class="fa fa-building" aria-hidden="true"></i>
                                                        </h5>
                                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive-sm">
                                                            <table class="table table-striped table-hover" style="text-align: center;">
                                                                <thead style="font-weight: bold;">
                                                                    <tr>
                                                                        <th>Consum
                                                                        </th>
                                                                        <th>Charge<br>(Rs/kWh)
                                                                        </th>
                                                                        <th>Fixed<br>Charge<br>(Rs/month)
                                                                        </th>
                                                                        <th>Max<br>Demand<br>Charge<br>(Rs/kVA)
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tr>
                                                                    <td>GP-1</td>
                                                                    <td>19.50</td>
                                                                    <td>240.00</td>
                                                                    <td>-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>GP-2</td>
                                                                    <td>19.40</td>
                                                                    <td>3000.00</td>
                                                                    <td>850.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>GP-3</td>
                                                                    <td>19.10</td>
                                                                    <td>3000.00</td>
                                                                    <td>750.00</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    } else if ($data_method['category'] == 'Hotel 1') {
                                    ?>
                                        <button type="button" class="btn btn-dark" style="width: 100%;" data-bs-toggle="modal" data-bs-target="#hotelModal">
                                            How your Bill is Calculated?
                                        </button>Click the above button to see how
                                        your
                                        monthly bill is calculated accroding to the
                                        units you used.

                                        <!-- Modal -->
                                        <div class="modal fade" id="hotelModal" tabindex="-1" aria-labelledby="hotelModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark" style="color: white;">
                                                        <h5 class="modal-title" id="hotelModalLabel">
                                                            Category - Hotel</h5>
                                                        &nbsp;<i class="fa fa-cutlery" aria-hidden="true"></i>
                                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive-sm">
                                                            <table class="table table-striped table-hover" style="text-align: center;">
                                                                <thead style="font-weight: bold;">
                                                                    <tr>
                                                                        <th>Consumption
                                                                        </th>
                                                                        <th>Charge<br>(Rs/kWh)
                                                                        </th>
                                                                        <th>Fixed
                                                                            Charge<br>(Rs/month)
                                                                        </th>
                                                                        <th>Max
                                                                            Demand
                                                                            Charge<br>(Rs/kVA)
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tr>
                                                                    <td>H-1</td>
                                                                    <td>19.50</td>
                                                                    <td>240.00</td>
                                                                    <td>-</td>
                                                                </tr>
                                                                <td colspan="4" style="padding-right: 350px;">
                                                                    H-2</td>

                                                                <tr>
                                                                    <td>Day</td>
                                                                    <td>13.00</td>
                                                                    <td rowspan="3" style="padding-top: 50px;">
                                                                        3000.00</td>
                                                                    <td rowspan="3" style="padding-top: 50px;">
                                                                        850.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Peak</td>
                                                                    <td>16.90</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Off Peak
                                                                    </td>
                                                                    <td>9.15</td>
                                                                </tr>
                                                                <td colspan="4" style="padding-right: 350px;">
                                                                    H-3</td>
                                                                <tr>
                                                                    <td>Day</td>
                                                                    <td>12.60</td>
                                                                    <td rowspan="3" style="padding-top: 50px;">
                                                                        3000.00</td>
                                                                    <td rowspan="3" style="padding-top: 50px;">
                                                                        750.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Peak</td>
                                                                    <td>16.40</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Off Peak
                                                                    </td>
                                                                    <td>8.85</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    } else if ($data_method['category'] == 'Religious & Charity') {
                                    ?>
                                        <button type="button" class="btn btn-dark" data-bs-toggle="modal" style="width: 100%;" data-bs-target="#domesticeModal">
                                            How your Bill is Calculated?
                                        </button>Click the above button to see how
                                        your
                                        monthly bill is calculated accroding to the
                                        units you used.

                                        <!-- Modal -->
                                        <div class="modal fade" id="domesticeModal" tabindex="-1" aria-labelledby="domesticeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark" style="color: white;">
                                                        <h5 class="modal-title" id="domesticeModalLabel">
                                                            Category - Religious &
                                                            Charity</h5>&nbsp;<i class="fa fa-university" aria-hidden="true"></i>
                                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="accordion-body">
                                                            <table class="table table-striped table-hover" style="text-align: center;">
                                                                <thead style="font-weight: bold;">
                                                                    <tr>
                                                                        <th>Consumption
                                                                        </th>
                                                                        <th>Charge<br>(Rs/kWh)
                                                                        </th>
                                                                        <th>Fixed
                                                                            Charge<br>(Rs/month)
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tr>
                                                                    <td>0-30</td>
                                                                    <td>1.90</td>
                                                                    <td>30.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>31-90</td>
                                                                    <td>2.80</td>
                                                                    <td>60.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>91-120</td>
                                                                    <td>6.75</td>
                                                                    <td>180.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>121-180</td>
                                                                    <td>7.50</td>
                                                                    <td>180.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>180 ></td>
                                                                    <td>9.40</td>
                                                                    <td>240.00</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
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