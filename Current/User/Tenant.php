<?php
require_once 'User-Header.php';
$uid = $_SESSION['user_id'];
$uname = $_SESSION['user_uname'];

$sql_month = "SELECT * FROM bill_month";
$records_month = mysqli_query($link, $sql_month);
$data_month = mysqli_fetch_assoc($records_month);
$due_month = $data_month['month'];

$sql_record = "SELECT * FROM image_upload WHERE user_id='" . $uid . "' AND month = '$due_month' AND status != 'Rejected'";
$recordsDetails = mysqli_query($link, $sql_record);



$records_img = mysqli_query($link, "SELECT * FROM image_upload WHERE user_id = '$uid'");
$data_img = mysqli_fetch_assoc($records_img);

$sql_details = "SELECT * FROM tenant WHERE tenant_id='" . $uid . "'";
$records_details = mysqli_query($link, $sql_details);
$dataDetails = mysqli_fetch_array($records_details);
?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />

<div class="row justify-content-center wrapper">
    <div class="col-lg-14 p-4 pt-12" style="background-color: #E5E4E2;">
        <div class="row gutters-sm">
            <div class="col-md-12" ><br>
                <div class="card border shadow-lg mb-3 p-2">
                    <h2 class="align-items-center text-center">Owner Billing Account </h2>
                    
                    <div class="col-md-4">
                         <a href="AddOwner.php" class="btn btn-primary" role="button">Add Owner Account&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></a>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-hover" style="font-size: 14px;" id="newTable">
                                <thead style="font-weight: bold;font-size: 16px;">
                                    <tr>
                                        
                                        <th style="text-align: center;">Bill Name</th>
                                        <th style="text-align: center;">Owner Account</th>
                                        <th style="text-align: center;">View Bill</th>
                                        <th style="text-align: center;">Delete Account</th>

                                        
                                    </tr>
                                </thead>
                                <?php
                                #$sql_bill = "SELECT * FROM current_bill WHERE user_id='" . $uid . "'";
                                #$records_bill = mysqli_query($link, $sql_bill);
                                
                                
                                $account = mysqli_query($link, $sql_details);
                                
                                while ($accountdetail = mysqli_fetch_assoc($account)) {
                                ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <?php
                                            echo $accountdetail['bill_name'];
                                             ?>
                                            
                                        </td>
                                        

                                        <td style="text-align: center;">
                                            <?php
                                            echo $accountdetail['owner_account'];
                                             ?>
                                        </td>




                                        

                                        
                                        <td style="text-align: center;">
                                            <form method="post" action="ViewPay.php">
                                                  <input type="hidden" name="accountNum" value="<?php echo $accountdetail['owner_account']; ?>">
                                                  <input type="hidden" name="user_id" value="<?php echo $accountdetail['owner_id']; ?>">
                                                 <button type="submit" class="btn btn-primary">View Bill</button>
                                            </form> 
                                        </td> 
                                        
                                        <td style="text-align: center;">
                                            <form method="post" action="deleteuser.php">
                                                  <input type="hidden" name="accountNum" value="<?php echo $accountdetail['owner_account']; ?>">
                                                  <input type="hidden" name="user_id" value="<?php echo $accountdetail['owner_id']; ?>">
                                                 <button type="submit" class="btn btn-danger">Delete</button>
                                            </form> 
                                        </td>
                                    </tr>

                                <?php
                                }

                                ?>
                            </table>
                        </div>
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
require_once 'User-Footer.php';
?>