<?php
require_once 'Admin-Header.php';
$uid = $_GET['user_id'];
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

//$sql_img = "SELECT * FROM image_upload WHERE user_id='" . $uid . "' AND month = '$bill_month' ORDER BY id DESC LIMIT 1";
//$records_img = mysqli_query($link, $sql_img);
//$data_img = mysqli_fetch_assoc($records_img);

//$all_imgs = "SELECT * FROM image_upload WHERE user_id='" . $uid . "'";
//$records_all_imgs = mysqli_query($link, $all_imgs);
//$data_all_imgs = mysqli_fetch_assoc($records_all_imgs);

$sql_month_bill = "SELECT * FROM current_bill WHERE user_id='" . $uid . "' AND month = '$bill_month'";
$records_month_bill = mysqli_query($link, $sql_month_bill);
$data_month_bill = mysqli_fetch_assoc($records_month_bill);

$sql_bill = "SELECT * FROM current_bill WHERE user_id='" . $uid . "'";
$records_bill = mysqli_query($link, $sql_bill);
$data_bill = mysqli_fetch_assoc($records_bill);

$meter_err = $units_err = $charge_err = $total_err = "";


?>
<link rel="stylesheet" type=text/css
    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />

<div class="row justify-content-center wrapper">
    <div class="col-lg-12 p-4 pt-12" style="background-color: #E5E4E2;">
        <div class="row gutters-sm">
            <div class = "col md-6 p-2" >
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
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Gender</h6>
                                        </div>
                                        <div class="col-sm-8 text-secondary">
                                            <?php echo $data['gender'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">NIC Number</h6>
                                        </div>
                                        <div class="col-sm-6 text-secondary">
                                            <?php echo $data['user_nic'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Email Address</h6>
                                        </div>
                                        <div class="col-sm-8 text-secondary">
                                            <?php echo $data['user_email'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Contact Number</h6>
                                        </div>
                                        <div class="col-sm-6 text-secondary">
                                            <?php echo $data['user_contact'] ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h6 class="mb-0">Joined</h6>
                                        </div>
                                        <div class="col-sm-8 text-secondary">
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
            <?php
            if($dataDetails['status'] == 'Pending'){
            ?>
            <div class="col-md-6">
                <div class="card border shadow-lg mb-4 p-2">
                    <h2 class="text-center">Registered Gas Bill Management Form</h2>
                    <hr>
                    <div class="px-3 needs-validation">
                        <div class="form-group">
                            <label>Name of the Person</label>
                            <input type="text" class="form-control" name="name" disabled
                                value="<?php echo $dataDetails['name']; ?>">
                        </div><br>

                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control" name="user_address" disabled
                                value="<?php echo $dataDetails['user_address']; ?>">
                        </div><br>

                        <div class="form-group">
                            <label>Area Office</label>
                            <input type="text" class="form-control" name="user_area" disabled
                                value="<?php echo $dataDetails['user_area']; ?>">
                        </div><br>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Premises ID</label>
                                <input type="text" class="form-control" name="user_premises" disabled
                                    value="<?php echo $dataDetails['user_premises']; ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Gas Account Number</label>
                                <input type="text" class="form-control" name="user_account" disabled
                                    value="<?php echo $dataDetails['user_account']; ?>">
                            </div>
                        </div><br>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <button class="btn btn-success btn-lg btn-block myBtn" data-bs-toggle="modal"
                                    data-bs-target="#approveModal">Approve</button>

                                <!-- Modal -->
                                <div class="modal fade" id="approveModal" tabindex="-1"
                                    aria-labelledby="approveModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success" style="color: white;">
                                                <h5 class="modal-title" id="approveModalLabel">Approve</h5>
                                                <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                                                    aria-label="Close" style="color: white;">
                                                    <i class="fa fa-times" aria-hidden="true"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="Form-Approved.php?user_id=<?php echo $data['user_id']?>"
                                                    method="POST" class="px-3 needs-validation" id="admin_add">
                                                    <div class="form-group">
                                                        <label>Select the Category</label>
                                                        <select id="category" name="category" class="form-control">
                                                            <option value="Domestic">Domestic</option>
                                                            <option value="Industrial 1">Industrial 1</option>
                                                            <option value="Hotel 1">Hotel 1</option>
                                                            <option value="Religious & Charity">Religious & Charity
                                                            </option>
                                                            <option value="General Purpose 1">General Purpose 1</option>
                                                        </select>
                                                    </div><br>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Approve</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <button class="btn btn-danger btn-lg btn-block myBtn" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">Reject</button>
                                <!-- Modal -->
                                <div class="modal fade" id="rejectModal" tabindex="-1"
                                    aria-labelledby="rejectModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rejectModalLabel">Reject</h5>
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <i class="fa fa-times" aria-hidden="true"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to Reject this form? If so please provide Reasons
                                                for Rejection.
                                                <form class="px-3 needs-validation" method="POST"
                                                    action="Form-Rejected.php?user_id=<?php echo $data['user_id']?>">
                                                    <div class="form-group"><br>
                                                        <label>Reasons for Rejection</label>
                                                        <input type="text" class="form-control" name="feedback" required
                                                            placeholder="Please provide Reasons">
                                                    </div>


                                            </div>
                                            <div class="modal-footer">
                                                <button name="submit" type="submit" class="btn btn-danger">Yes</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php
            }

            else if($dataDetails['status'] == 'Approved'){
                //if($data_img['status'] == 'Pending'){
                    ?>
            <div class="col-md-6 p-2">
                <div class="card border shadow-lg mb-4 p-2">
                    <h2 class="text-center">Registered Form</h2>
                    <hr>
                    <div class="px-3 needs-validation">
                        <div class="form-group">
                            <label>Name of the Person</label>
                            <input type="text" class="form-control" name="name" disabled
                                value="<?php echo $dataDetails['name']; ?>">
                        </div><br>

                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control" name="user_address" disabled
                                value="<?php echo $dataDetails['user_address']; ?>">
                        </div><br>

                        <div class="form-group">
                            <label>Area Office</label>
                            <input type="text" class="form-control" name="user_area" disabled
                                value="<?php echo $dataDetails['user_area']; ?>">
                        </div><br>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Premises ID</label>
                                <input type="text" class="form-control" name="user_premises" disabled
                                    value="<?php echo $dataDetails['user_premises']; ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label>GAs Account Number</label>
                                <input type="text" class="form-control" name="user_account" disabled
                                    value="<?php echo $dataDetails['user_account']; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Category</label>
                                <input type="text" class="form-control" name="category" disabled
                                    value="<?php echo $dataDetails['category']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-14">
                <div class="card mb-3">
                    <h2 class="text-center">Previous Bills & Payments</h2>
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-hover" style="font-size: 14px;" id="payTable">
                                <thead style="font-weight: bold;font-size: 16px;">
                                    <tr style="text-align: center;">
                                        <td style="text-align: center;">Month</td>
                                        <td style="text-align: center;">Name</td>
                                        <td style="text-align: center;">Bill</td>
                                        <td style="text-align: center;">Amount</td>
                                        <td style="text-align: center;">Paid</td>
                                    </tr>
                                </thead>
                                <?php
                    $records_one_pay = mysqli_query($link,"SELECT * FROM current_pay WHERE user_id = '$uid'");

                    while($data_one_pay=mysqli_fetch_assoc($records_one_pay)){
                        $bill_one_month = $data_one_pay['month'];
                        $results_month_bill = mysqli_query($link,"SELECT * FROM current_bill WHERE month = '$bill_one_month'");
                        $data_bill_month=mysqli_fetch_assoc($results_month_bill);
                        $id = strval($data_one_pay['id']);
                    ?>
                                <tr style="text-align: center;">
                                    <td><?php echo $data_one_pay['month']; ?></td>
                                    <td><?php echo $data_one_pay['pay_name']; ?></td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#payment<?php echo $id; ?>">View&nbsp;<i
                                                class="fa fa-file-text" aria-hidden="true"></i></button>

                                    </td>
                                    <div class="modal fade" id="payment<?php echo $id; ?>" tabindex="-1"
                                        aria-labelledby="payment<?php echo $id; ?>Label" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h4 class="modal-title" id="payment<?php echo $id; ?>Label"
                                                        style="color: white;">
                                                        Payment</h4>
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <i class="fa fa-times" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="px-3 needs-validation">
                                                        <div class="form-row">
                                                            <!--<div class="form-group col-md-2"><img
                                                                    src="../../images/ceb_bill.png"></div>-->
                                                            <div class="form-group col-md-12 p-2">
                                                                <h5 style="text-align: center;">Petros Statement of Gas Account<h5>
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
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Gas Account Number</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $dataDetails['user_account'] ?>"
                                                                    disabled>
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Category</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $dataDetails['category'] ?>"
                                                                    disabled>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Meter Reading</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $data_bill_month['meter'] ?>"
                                                                    disabled>
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Units Consumed for Month
                                                                    <?php echo date('F')?></label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $data_bill_month['units'] ?>"
                                                                    disabled>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Charge For the Month(Rs.)</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $data_bill_month['charge'] ?>"
                                                                    disabled>
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Total Amount Due (Rs.)</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $data_bill_month['total'] ?>"
                                                                    disabled>
                                                            </div>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Updated</label>
                                                                <input type="text" class="form-control"
                                                                    value="<?php echo $data_bill_month['updated_at']?>"
                                                                    disabled>

                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Due Date</label>
                                                                <input type="date" class="form-control"
                                                                    value="<?php echo $data_bill_month['due'] ?>"
                                                                    disabled>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-dark"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <td>
                                        <div class="btn btn-success"><?php echo $data_one_pay['pay_amount']; ?></div>
                                    </td>
                                    <td><?php echo $data_one_pay['paid_at']; ?></td>
                                </tr>

                                <?php
                    }

                   ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                }

                //else if($data_img['status'] == 'Prepared'){

                //}

                //else{
                //}
            //}

            else if($dataDetails['status'] == 'Rejected'){
                ?>
        <div class="col-md-6">
            <div class="card border shadow-lg mb-4 p-2">
                <h2 class="text-center">Rejected Gas Bill Management Form</h2>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="text-align: center;">
                    <strong>Form Rejected&nbsp;<i class="fa fa-times" aria-hidden="true"></i></strong>
                </div>
                <div class="px-3 needs-validation">
                    <div class="form-group">
                        <label>Name of the Person</label>
                        <input type="text" class="form-control" name="name" disabled
                            value="<?php echo $dataDetails['name']; ?>">
                    </div><br>

                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" name="user_address" disabled
                            value="<?php echo $dataDetails['user_address']; ?>">
                    </div><br>

                    <div class="form-group">
                        <label>Area Office</label>
                        <input type="text" class="form-control" name="user_area" disabled
                            value="<?php echo $dataDetails['user_area']; ?>">
                    </div><br>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Premises ID</label>
                            <input type="text" class="form-control" name="user_premises" disabled
                                value="<?php echo $dataDetails['user_premises']; ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Gas Account Number</label>
                            <input type="text" class="form-control" name="user_account" disabled
                                value="<?php echo $dataDetails['user_account']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Reasons for Rejection</label>
                        <input type="text" class="form-control" name="feedback" disabled
                            value="<?php echo $dataDetails['feedback']; ?>">
                    </div>

                </div>
            </div>
        </div>
        <?php
            }
            ?>
        

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