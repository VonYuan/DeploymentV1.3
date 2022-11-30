<?php

require_once 'Admin-Headers.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    
require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';
    
// Load Composer's autoloader
require '../../vendor/autoload.php';
    
// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

function allUsers(){
    $db =new mysqli('petrosbilling.mysql.database.azure.com', 'petrosadmin', 'AdminLogin123', 'petrosbilling');
    $all = mysqli_query($db, "SELECT * FROM users");
    $all_users = mysqli_num_rows($all);
    return $all_users;
  }

  function UnsetPreviousSession()
    {
       unset($_SESSION['user_id']); 
    }

$user_name = $user_gender = $user_nic = $user_email = $user_contact = $user_password = $confirm_password = $send_password = "";
$username_err = $password_err = $email_err = $confirm_password_err = $nic_err = $contact_err = $stat_err = "";
$name_err = $name = $user_account = $user_address = $user_area = $address_err = $area_err = $acc_err = $user_premises = $premises_err = "";
$stat = $_SESSION['var'] = 1;


?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
<div class="row justify-content-center wrapper">
    <div class="col-lg-8 bg-white p-4">

        <div class="row gutters-sm">
                <div class="border shadow-lg card p-2">
                    <h4 class="text-center font-weight-bold">All Address</h4>
                    <hr class="my-3" />
                    <div class="table-responsive-sm">
                    <div class="category-filter">
                        <select id="categoryFilter" class="form-control">
                        <option selected value="">Select Area</option>
                         <option value="APT">Airport Road</option>
                          <option value="BBP">Bandar Baru permy Jaya</option>
                           <option value="BKM">Luak/Jalan Bakam</option>
                           <option value="BRT">Brighton Road</option>
                            <option value="KRP">Krokop</option>
                            <option value="LTG">Lutong</option>
                             <option value="MPR">PermaisuriPujut</option>
                             <option value="PGA">Piasau</option>
                             <option value="PSU">Pujut</option>
                              <option value="PUJ">Senadin</option>
                             <option value="SND">Town</option>
                             <option value="TWN">Others</option>
                        </select>
                        </div>
                        <table class="table table-striped table-hover" style="font-size: 14px;" id="filterTable">
                            <thead style="font-weight: bold;font-size: 16px;">
                                <tr>
                                    <td style="text-align: center;">Address</td>
                                    <td style="text-align: center;">Area</td>
                                    <td style="text-align: center;">Name</td>
                                    <td style="text-align: center;">Gas Meter Number</td>
                                    <td style="text-align: center;">Bill</td>
                                </tr>
                            </thead>
                            <?php
       $db = new mysqli('petrosbilling.mysql.database.azure.com', 'petrosadmin', 'AdminLogin123', 'petrosbilling');
       $records = mysqli_query($db,"SELECT user_id, user_address, name,user_account,user_area FROM current_details");

            while($data=mysqli_fetch_array($records)){
                // $_SESSION['learners_name'] = $data['learners_name'];
                ?>
                            <tr>
                                <td style="text-align: center;">
                                    &nbsp;<br><?php echo $data['user_address'];?>
                                </td>
                                <td style="text-align: center;">
                                    &nbsp;<br><?php echo $data['user_area'];?>
                                </td>
                                <td style="text-align: center;">
                                    &nbsp;<br><?php echo $data['name'];?>
                                </td>
                                <td style="text-align: center;">
                                    &nbsp;<br><?php echo $data['user_account'];?>
                                </td>
                                
                                <td style="text-align: center;">
                                   <!-- <a href="test.php?user_id=<?php #echo urlencode($data['user_account']);?>"
                                        class="btn btn-primary">View&nbsp;<i class="fa fa-info-circle"
                                            aria-hidden="true"></i></a>-->
                                    
                                      <form method="post" action="test.php">
                                            <input type="hidden" name="accountNum" value="<?php echo $data['user_account']; ?>">
                                          
                                            <input type="hidden" name="user_id" value="<?php echo $data['user_id']; ?>">
                                          
                                            <button type="submit" class="btn btn-primary">View&nbsp<i class="fa fa-info-circle"
                                            aria-hidden="true"></i></button>
                                      </form>
                                </td>

                            <?php
            }

        ?>
                        </table>
                    </div>
                </div>
            </div>
</div>

<style>
label {
    font-weight: normal;
    font-size: 18px;
}

.form-group {
    text-align: left;
}

.help-block {
    color: red;
}

.pagination,
    div.dataTables_wrapper div.dataTables_filter label,
    div.dataTables_wrapper div.dataTables_length label,
    div.dataTables_wrapper div.dataTables_filter,
    table.dataTable td.dataTables_empty,
    table.dataTable th.dataTables_empty,
    div.dataTables_wrapper div.dataTables_info {
        font-size: 14px;
    }

    .page-link,
    .page-link:hover {
        color: black;
        text-decoration: none;
    }
</style>

<!-- <style>
.page-link,
.page-link:hover,
.page-group-current-page:active {
    color: red;
    text-decoration: none;
}
</style> -->

<script src="../../vendor.bundle.base.js"></script>
<script type="text/javascript"
    src="https://cdn.datatables.net/v/bs4/dt-1.10.24/r-2.2.7/sc-2.0.3/sp-1.2.2/datatables.min.js"></script>
<script>
$(document).ready(function() {
    $('#myTable').DataTable();
});

$(document).ready(function() {
    $('#redTable').DataTable();
});
</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script type="text/javascript">
var ctx = document.getElementById("chartjs_bar").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($tot_user_methods); ?>,
        datasets: [{
            backgroundColor: [
                "#ffc750",
                "#2ec551",
                "#ff407b",
                "#3B444B",

            ],
            data: <?php echo json_encode($tot_users); ?>,
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
<script>
    $("document").ready(function () {
      $("#filterTable").dataTable({
        "searching": true
      });
      //Get a reference to the new datatable
      var table = $('#filterTable').DataTable();
      //Take the category filter drop down and append it to the datatables_filter div. 
      //You can use this same idea to move the filter anywhere withing the datatable that you want.
      $("#filterTable_filter.dataTables_filter").append($("#categoryFilter"));
      
      //Get the column index for the Category column to be used in the method below ($.fn.dataTable.ext.search.push)
      //This tells datatables what column to filter on when a user selects a value from the dropdown.
      //It's important that the text used here (Category) is the same for used in the header of the column to filter
      var categoryIndex = 1;
      $("#filterTable th").each(function (i) {
        if ($($(this)).html() == "Category") {
          categoryIndex = i; return false;
        }
      });
      //Use the built in datatables API to filter the existing rows by the Category column
      $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
          var selectedItem = $('#categoryFilter').val()
          var category = data[categoryIndex];
          if (selectedItem === "" || category.includes(selectedItem)) {
            return true;
          }
          return false;
        }
      );
      //Set the change event for the Category Filter dropdown to redraw the datatable each time
      //a user selects a new filter.
      $("#categoryFilter").change(function (e) {
        table.draw();
      });
      table.draw();
    });
</script>

<?php
require_once 'Admin-Footer.php';
?>