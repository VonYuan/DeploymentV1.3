<?php

require_once 'Admin-Header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    
require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
    
// Load Composer's autoloader
require '../../vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

$uid =$_POST['user_id'];
#$uid = $_SESSION['user_id'];
//$uname = $_SESSION['user_uname'];

$sql_month = "SELECT * FROM bill_month";
$records_month = mysqli_query($link, $sql_month);
$data_month = mysqli_fetch_assoc($records_month);
$due_month = $data_month['month'];

$sql_record = "SELECT * FROM image_upload WHERE user_id='" . $uid . "' AND month = '$due_month' AND status != 'Rejected'";
$recordsDetails = mysqli_query($link, $sql_record);

$accountNum=$_POST['accountNum'];

$records_img = mysqli_query($link, "SELECT * FROM image_upload WHERE user_id = '$uid'");
$data_img = mysqli_fetch_assoc($records_img);

$sql_details = "SELECT * FROM current_details WHERE user_id='" . $uid . "' AND user_account = '$accountNum' ";
$records_details = mysqli_query($link, $sql_details);
$dataDetails = mysqli_fetch_array($records_details);
?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />

<div class="row justify-content-center wrapper">
    <div class="col-lg-14 p-4 pt-12" style="background-color: #E5E4E2;">
        <div class="row gutters-sm">
            <div class="col-md-12" ><br>
                <div class="card border shadow-lg mb-3 p-2">
                    <h2 class="align-items-center text-center">User Bills</h2>
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-hover" style="font-size: 14px;" id="newTable">
                                <thead style="font-weight: bold;font-size: 16px;">
                                    <tr>
                                        <th>Month</th>
                                        <th style="text-align: center;">Download Bill</th>
                                        <th style="text-align: center;">Status</th>
                                    </tr>
                                </thead>
                                <?php
                                $sql_bill = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND user_account = '$accountNum'";
                                $records_bill = mysqli_query($link, $sql_bill);
                                while ($data_bill = mysqli_fetch_assoc($records_bill)) {
                                ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $img_month = $data_bill['month'];
                                            $sql_img = "SELECT * FROM image_upload WHERE month='" . $img_month . "' AND status != 'Rejected'";
                                            $records_img_month = mysqli_query($link, $sql_img);
                                            $data_img_month = mysqli_fetch_assoc($records_img_month);

                                            $id = strval($data_bill['id']);
                                            echo $data_bill['month']; ?>
                                            
                                        </td>
                                        
                                        

                                        <td style="text-align: center;" disable >
                                            <?php 
                                                if($data_bill['status']=="Over Paid" OR $data_bill['status']=="Paid" OR $data_bill['status']=="Not Paid")
                                                {
                                            ?>
                                            <a href="Pdf.php?user_id=<?php echo $uid; ?>&month=<?php echo $data_bill['month']; ?>&user_account=<?php echo $accountNum; ?>" target="_blank" disable>
                                            <i class="fa fa-download" aria-hidden="true"></i></a>
                                            
                                            <?php 
                                                }
                                            ?>
                                        </td>

                                        <td style="text-align: center;">
                                            <?php
                                            if ($data_bill['status'] == 'Not Paid') {
                                            ?>
                                                <div class="btn btn-warning" style="color: white;">Not Paid</div>
                                            <?php
                                            } else if ($data_bill['status'] == 'Paid') {
                                            ?>
                                                <div class="btn btn-success" style="color: white;">Paid</div>
                                            <?php
                                            } else if ($data_bill['status'] == 'Over Paid') {
                                            ?>
                                                <div class="btn btn-primary" style="color: white;">Over Paid</div>
                                            <?php
                                            } else if ($data_bill['status'] == 'Partly Paid') {
                                            ?>
                                                <div class="btn btn-secondary" style="color: white;">Partly Paid</div>
                                            <?php
                                            } 
                                            ?>
                                        </td>
                                                
                                        
                                                
                                        
                                             
                                        <!--<td style="text-align: center;">
                                            
                                            
                                            
                                                <button name="submit" type="submit"
                                                class="btn btn-danger">Update</button>
                                                    
                                            
                                        </td>-->
                                                
                                                
                                    </tr>

                                <?php
                                }

                                ?>
                            </table>
                        </div>
                        <!-- <h2 class="align-items-center text-center">Guide</h2>
                <div class="card mb-3 p-2">
                    <div class="card-body">
                        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                                    class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                                    aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                                    aria-label="Slide 3"></button>
                                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"
                                    aria-label="Slide 4"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="align-items-center text-center">
                                        <p style="font-size: 18px;">FIrst time using? Then better to see this guidance!
                                        </p style="font-size: 18px;">
                                    </div>
                                    <img src="../../images/new.png" class="d-block w-100" alt="...">

                                </div>
                                <div class="carousel-item">
                                    <div class="align-items-center text-center">
                                        <h4>First Step</h4>
                                        <p>Click a clear image of the current meter. (Please check whether the values
                                            are readable after taking it)</p>
                                    </div>
                                    <img src="../../images/meter.png" class="d-block w-100" alt="...">

                                </div>
                                <div class="carousel-item">
                                    <div class="align-items-center text-center">
                                        <h4>Second Step</h4>
                                        <p>Upload the taken image using Upload button.</p>
                                    </div>
                                    <img src="../../images/upload.png" class="d-block w-100" alt="...">

                                </div>
                                <div class="carousel-item">
                                    <div class="align-items-center text-center">
                                        <h4>Third Step</h4>
                                        <p>Adminstrators will inform about the durable payment after the confirmation.
                                            (If there are issues
                                            with the image uploaded, then you will be asked to upload it again.
                                        </p>
                                    </div>
                                    <img src="../../images/check.png" class="d-block w-100" alt="...">

                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                    </div>
                </div> -->
                    </div>
                </div>
            </div>


    </div>
</div>
</div>


<!-- jQuery -->
<script src="../../jquery/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<script>
    var input = document.getElementById('select');
    var infoArea = document.getElementById('chosenfile');

    input.addEventListener('change', showFileName);

    function showFileName(event) {

        // the change event gives us the input it occurred in 
        var input = event.srcElement;

        // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
        var fileName = input.files[0].name;

        // use fileName however fits your app best, i.e. add it into a div
        infoArea.textContent = 'Selected File: ' + fileName;
    }
</script>

<script src="../../vendor.bundle.base.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/r-2.2.7/sc-2.0.3/sp-1.2.2/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#newTable').DataTable();
    });
</script>

<script>
    $(document).on("click", ".myBill", function() {
        var bill = $(this).data('bill');

        document.getElementById("bill").innerHTML = bill;
    });
</script>

<?php
require_once 'Admin-Footer.php';

?>