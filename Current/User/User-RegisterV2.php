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

$sql_details = "SELECT * FROM current_details WHERE user_id='" . $uid . "'";
$records_details = mysqli_query($link, $sql_details);
$dataDetails = mysqli_fetch_array($records_details);
?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />

<div class="row justify-content-center wrapper">
    <div class="col-lg-14 p-4 pt-12" style="background-color: #E5E4E2;">
        <div class="row gutters-sm">
            <div class="col-md-12" ><br>
                <div class="card border shadow-lg mb-3 p-2">
                    <h2 class="align-items-center text-center">Account </h2>
                    
                    <div class="col-md-4">
                         <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#register">Add&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
                         <a href="RegisterBill.php" class="btn btn-primary" role="button">Add&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></a>
                    </div>
                    
                    <div class="modal fade" id="register" tabindex="-1" aria-labelledby="photo Label" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                        <h4 class="modal-title" id="photo Label" style="color: white;">
                                                            Register Gas Bill</h4>
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-times" aria-hidden="true"></i></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="px-3 needs-validation">
                                                            <div class="form-row">
                                                                <!--<div class="form-group col-md-2"><img src="../../images/ceb_bill.png"></div>-->
                                                                <div class="form-group col-md-12 p-2">
                                                                    <h5 style="text-align: center;">Registration for Gas Billing System</h5>
                                                                </div>
                                                            </div>

<?php

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate user Username
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter the Name.";
    } else {
            $name = trim($_POST["name"]);
    }

    //validate address
    if (empty(trim($_POST["user_address"]))) {
        $address_err = "Please enter the Address!";
    } else {
        $user_address = trim($_POST["user_address"]);           
    }

    if (empty(trim($_POST["user_area"]))) {
        $area_err = "Please enter the Area Office as mentioned in the Bill!";
    } else {
        $user_area = trim($_POST["user_area"]);           
    }

    
    if (empty(trim($_POST["user_premises"]))) {
        $premises_err = "Please enter the Premises ID.";
    } else {
        $user_premises = trim($_POST["user_premises"]);
    }

    if (empty(trim($_POST["user_account"]))) {
        $acc_err = "Please enter the account number.";
    } elseif (strlen(trim($_POST["user_account"])) != 10){
        $acc_err = "Invalid Account Number. SHould consist with only 10 numbers";
    } else {
        $user_account = trim($_POST["user_account"]);
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($address_err) && empty($area_err) && empty($premises_err) && empty($acc_err)) {


        // Prepare an insert statement
        $sql = "INSERT INTO test_details (user_id, name, user_address, user_area, user_premises, user_account) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $link->prepare($sql)) {


            // Bind variables to the prepared statement as parameters
            if ($stmt->bind_param("isssss",$param_userid, $param_name, $param_address, $param_area, $param_premises, $param_acc))

                // Set parameters
            $param_userid = $uid;
            $param_name = $name;
            $param_address = $user_address;
            $param_area = $user_area;
            $param_premises = $user_premises;
            $param_acc = $user_account; // Creates a password hash

            $message = "Uploaded the registration form.";
            $activity = "INSERT INTO activity_log (user_id, message) VALUES ('$uid', '$message')";

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                header("Location: User-Dashboard.php.php");
                mysqli_query($link,$activity);
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
                                                            

                                                            
                                                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="px-3 needs-validation"
                                                                id="user_add">
                                                                
                                                             <p style="font-size: 14px;">*Please fill this form to register for the Gas bill management system. All the information is related to manual bill</p>
                                                                
                                                                
                                                            <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" class="form-control" name="name" required
                                                                placeholder="Enter the Name">
                                                            </div>
                                                                
                                                            <div class="form-group">
                                                                    <label>Address</label>
                                                                    <input type="text" class="form-control" name="user_address" placeholder="Enter the Address" required>
                                                            </div>
                                                                
                                                                
                                                            <div class="form-group">
                                                                   <label>Area Office</label>
                                                                    <input type="text" class="form-control" name="user_area" placeholder="Enter the Area Office" required>
                                                            </div>
                                                                
                                                                
                                                            <div class="form-row">    
                                                                    <div class="form-group col-md-6">
                                                                       <label>Premises ID</label>
                                                                        <input type="text" class="form-control" name="user_premises" placeholder="Enter the Premises ID" required>
                                                                    </div>
                                                                
                                                                <div class="form-group col-md-6">
                                                                       <label>Gas Account Number</label>
                                                                        <input type="text" class="form-control" name="user_account" placeholder="Enter the Account Number" required>
                                                                </div>
                                                                
                                                                    
                                                            </div>
                                                                
                                                                
                                                            <div class="form-group">
                                                                <button class="btn btn-danger btn-lg btn-block myBtn" type="submit " name="submit">Submit</button>
                                                            </div><br>
                                                        </form>








  

                                                        </div>
                                                    </div>



                                                </div>
                                            </div>
                                        </div>
                    
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-hover" style="font-size: 14px;" id="newTable">
                                <thead style="font-weight: bold;font-size: 16px;">
                                    <tr>
                                        <th>Name</th>
                                        <th style="text-align: center;">Account Number</th>
                                        <th style="text-align: center;">Address</th>
                                        <th style="text-align: center;">Area</th>
                                        <th style="text-align: center;">Premises</th>
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;">Action</th>
                                        
                                    </tr>
                                </thead>
                                <?php
                                #$sql_bill = "SELECT * FROM current_bill WHERE user_id='" . $uid . "'";
                                #$records_bill = mysqli_query($link, $sql_bill);
                                
                                
                                $account = mysqli_query($link, $sql_details);
                                
                                while ($accountdetail = mysqli_fetch_assoc($account)) {
                                ?>
                                    <tr>
                                        <td>
                                            <?php
                                            echo $accountdetail['name'];
                                             ?>
                                            
                                        </td>
                                        

                                        <td style="text-align: center;">
                                            <?php
                                            echo $accountdetail['user_account'];
                                             ?>
                                        </td>

                                        <td style="text-align: center;">
                                            <?php
                                            echo $accountdetail['user_address'];
                                             ?>
                                        </td>

                                        <td style="text-align: center;">
                                            <?php
                                            echo $accountdetail['user_area'];
                                             ?>
                                        </td>
                                        
                                        <td style="text-align: center;">
                                            <?php
                                            echo $accountdetail['user_premises'];
                                             ?>
                                        </td>
                                        
                                        <td style="text-align: center;">
                                            <?php
                                            echo $accountdetail['status'];
                                             ?>
                                        </td>
                                        
                                        <td style="text-align: center;">
                                            <form method="post" action="ViewPay.php">
                                                  <input type="hidden" name="accountNum" value="<?php echo $accountdetail['user_account']; ?>">
                                                 <button type="submit" >View Bill</button>
                                            </form> 
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
require_once 'User-Footer.php';
?>