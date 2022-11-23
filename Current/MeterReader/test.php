<?php
require_once 'Admin-Headers.php';
$uid = $_POST['user_id'];
$accountNum=$_POST['accountNum'];
$sql_record = "SELECT * FROM current_details WHERE user_id='" . $uid . "'";


$sql_month = "SELECT * FROM bill_month";
$records_month = mysqli_query($link, $sql_month);
$data_month = mysqli_fetch_assoc($records_month);
$bill_month = $data_month['month'];

$recordsDetails = mysqli_query($link, $sql_record);
$dataDetails = mysqli_fetch_assoc($recordsDetails);

$sql = "SELECT * FROM users WHERE user_id='" . $uid . "'";
$records = mysqli_query($link, $sql);
$data = mysqli_fetch_assoc($records);

$sql_img = "SELECT * FROM image_upload WHERE user_id='" . $uid . "' AND month = '$bill_month' ORDER BY id DESC LIMIT 1";
$records_img = mysqli_query($link, $sql_img);
$data_img = mysqli_fetch_assoc($records_img);

$all_imgs = "SELECT * FROM image_upload WHERE user_id='" . $uid . "'";
$records_all_imgs = mysqli_query($link, $all_imgs);
$data_all_imgs = mysqli_fetch_assoc($records_all_imgs);

$sql_month_bill = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND month = '$bill_month'";
$records_month_bill = mysqli_query($link, $sql_month_bill);
$data_month_bill = mysqli_fetch_assoc($records_month_bill);

$sql_bill = "SELECT * FROM current_bill WHERE user_id='" . $uid . "'";
$records_bill = mysqli_query($link, $sql_bill);
$data_bill = mysqli_fetch_assoc($records_bill);

$meter_err = $units_err = $charge_err = $total_err = "";

#calculate previous month
$currentmonth=date_create($bill_month);
date_sub($currentmonth,date_interval_create_from_date_string("1 months"));
$previous_month=date_format($currentmonth,"Y-m");

$sql_previous = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND month = '$previous_month' AND user_account = '$accountNum'";
$records_previousbill = mysqli_query($link, $sql_previous);
$previousmonthbill = mysqli_fetch_assoc($records_previousbill);

#$previousmeter=$previousmonthbill['meter'];

if(!empty($previousmonthbill['meter']))
{
    $previousmeter=$previousmonthbill['meter'];
    echo $previousmeter;
}else
{
    $previousmeter=0;
    echo $previousmeter;
}


#echo $previous_month;
#echo $accountNum;
#echo $previousmeter;

#echo $bill_month;
#echo $previousmonthbill['month'];
#echo "";
#echo $accountNum;
#echo $uid;


?>
<link rel="stylesheet" type=text/css
    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />

<div class="row justify-content-center wrapper">
    <div class="col-lg-12 p-4 pt-12" style="background-color: #E5E4E2;">
        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                <div class="card border shadow-lg p-2">
                    <h2 class="align-items-center text-center">Profile</h2>
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <?php
                    if($data['gender'] == "Male"){?>
                            <img src="https://img.icons8.com/color/120/000000/person-male.png" />
                            <?php
                    }

                    else if($data['gender'] == "Female"){?>
                            <img src="https://img.icons8.com/color/120/000000/person-female.png" />
                            <?php
                    }

                    else{?>
                            <img src="https://img.icons8.com/material-rounded/120/000000/user.png" />
                            <?php
                    }

                    ?>

                            <div class="mt-3">
                                <h4><?php echo $data['user_name']?></h4>
                            </div>
                        </div><br>
                        <div class="col-md-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Gender</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <?php echo $data['gender'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">NIC Number</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <?php echo $data['user_nic'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Email Address</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <?php echo $data['user_email'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Contact Number</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <?php echo $data['user_contact'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Joined</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <?php echo $data['created_at'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <!-- Profile end here -->
                                <!--button-->
                                <div class="form-group col-md-6">
                                    <button class="btn btn-success btn-lg btn-block myBtn" data-bs-toggle="modal"
                                        data-bs-target="#updateBill">Update Bill</button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="updateBill" tabindex="-1"
                                        aria-labelledby="updateBillLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger" style="color: white;">
                                                    <h5 class="modal-title" id="updateBillLabel">Update Bill</h5>
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <i class="fa fa-times" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="px-3 needs-validation" method="POST"
                                                        action="Bill-Update.php?user_id=<?php echo $data['user_id']?>">
                                                        <input type="text" name="user_email" class="form-control"
                                                            value="<?php echo $data['user_email'] ?>" hidden>
                                                        
                                                        <input type="text" name="accountNum" class="form-control"
                                                            value="<?php echo $accountNum ?>" hidden>
                                                        
                                                        <div class="form-row">  
                                                            <!--<diV class="form-group col-md-2"><img
                                                                    src="../../images/ceb_bill.png"></diV>-->
                                                            <div class="form-group col-md-10 p-2">
                                                                <h5 style="text-align: center;">Petros<br>Statement of Gas Account<h5>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" class="form-control"
                                                                value="<?php echo $dataDetails['name'] ?>" disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address</label>
                                                            <input type="text" class="form-control"
                                                                value="<?php echo $dataDetails['user_address'] ?>"
                                                                disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Gas Account Number</label>
                                                            <input type="text" class="form-control" name="accountNum"
                                                                value="<?php echo $accountNum; ?>">
                                                            
                                                            
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Category</label>
                                                            <input type="text" class="form-control"
                                                                value="<?php echo $dataDetails['category'] ?>" disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Month</label>
                                                            <input type="month" class="form-control" name="month"
                                                                value="<?php echo $bill_month ?>" disabled>
                                                            <!--<span class="help-block"><?php #echo $meter_err; ?></span>-->
                                                        </div>
                                                                
                                                        <div class="form-group">
                                                            <label>Last Month Meter Reading(m3)</label>
                                                            <input type="text" class="form-control" name="prevmeter"
                                                                value="<?php echo $previousmeter ?>"
                                                                enable>
                                                        </div>
                                                                
                                                        <div class="form-group">
                                                            <label>Meter Reading(m3) </label>
                                                            <input type="text" class="form-control" name="meter"
                                                                required placeholder="Ex:23654">
                                                            <span class="help-block"><?php echo $meter_err; ?></span>
                                                        </div>
                                                        
                                                        
                                                                
                                                        <!--<div class="form-group">
                                                            <label>Units Consumed for Month
                                                                <?php # echo $bill_month?></label>
                                                            <input type="text" class="form-control" name="units"
                                                                required placeholder="Ex:100">
                                                            <span class="help-block"><?php #echo $units_err; ?></span>
                                                        </div>-->
                                                                
                                                        <!--<div class="form-group">
                                                            <label>Charge for Gas Consumed (RM.)</label>
                                                            <input type="text" class="form-control" name="charge"
                                                                required placeholder="Ex:1000.00">
                                                            <span class="help-block"><?php# echo $charge_err; ?></span>
                                                        </div>-->

                                                        <!--<div class="form-group">
                                                            <label>Total Amount Due (RM)</label>
                                                            <input type="text" class="form-control" name="total"
                                                                placeholder="Ex:1500.00" required>
                                                            <span class="help-block"><?php #echo $total_err; ?></span>
                                                        </div>-->
                                                                
                                                        <div class="form-group">
                                                            <label>Due Date</label>
                                                            <input type="date" class="form-control" name="due" required
                                                                placeholder="Click the Calender Icon">
                                                        </div>


                                                </div>
                                                <div class="modal-footer">
                                                    <button name="submit" type="submit"
                                                        class="btn btn-danger">Update</button>
                                                    
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                        
                                        
                                                        
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                        <!--accept-->
                               
           


    </div>
</div>
<script src="../../vendor.bundle.base.js"></script>
<script type="text/javascript"
    src="https://cdn.datatables.net/v/bs4/dt-1.10.24/r-2.2.7/sc-2.0.3/sp-1.2.2/datatables.min.js"></script>
<script>
$(document).ready(function() {
    $('#payTable').DataTable();
});
</script>

<?php
require_once 'Admin-Footer.php';
?>