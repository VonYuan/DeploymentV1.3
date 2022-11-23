<?php
require_once 'Admin-Header.php';
require_once '../../Config.php';

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



?>

<div class="row gutters-sm">
            <div class="col-md-6 p-5">
                <div class="card border shadow-lg mb-3">
                    <h2 class="text-center">Send Reminder</h2>
                    <div class="card-body">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"
                            style="text-align: center;">
                            Send a reminder to pay the bills before deadline&nbsp;<i
                                class="fa fa-calendar" aria-hidden="true"></i>
                            . A notification will be sent
                            to the user account as well as an email for user's email address.
                        </div>
                        <h4 class="text-center">Send a New Reminder</h4>
                        <form class="px-3 needs-validation" method="POST"
                            action="Send-Reminder.php?user_id=<?php echo $dataDetails['user_id']?>">
                            <div class="form-group"><br>
                                <input name="user_name" value="<?php echo $data['user_name'] ?>" hidden>
                                <input name="user_email" value="<?php echo $data['user_email'] ?>" hidden>
                                <label>Reminder:</label>
                                <textarea type="text" style="height: 200px;" class="form-control"
                                    name="message">You have not pay your bill. Please pay it as soon as possible. Action will be taken if the bill is not pay before the deadline! Discard this message if you have already pay the bill.</textarea>
                            </div>
                            <button class="btn btn-danger" style="float: right;">Send&nbsp;<i
                                    class="fa fa-share-square-o" aria-hidden="true"></i></button>
                        </form><br>
                    </div>
                </div>
            </div>
            <div class="col-md-6 p-5">
                <div class="card border shadow-lg mb-3">
                    <h2 class="text-center">Previously Sent Reminders</h2>
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" style="text-align: center;">Reminder</th>
                                <th scope="col" style="text-align: center;">Month</th>
                                <th scope="col" style="text-align: center;">Sent</th>
                            </tr>
                        </thead>
                        <?php
                        $sql_rem = "SELECT * FROM notifications WHERE user_id = '$uid'";
                        $result_rem = mysqli_query($link, $sql_rem);
                            while($row_rem = mysqli_fetch_array($result_rem)){
                                ?>
                        <tr>
                            <td style="text-align: center;"><button class="btn btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#rem<?php echo $row_rem['id']; ?>">See
                                    Message</button></td>
                            <!-- Modal -->
                            <div class="modal fade" id="rem<?php echo $row_rem['id']; ?>" tabindex="-1" aria-labelledby="rem<?php echo $row_rem['id']; ?>Label"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rem<?php echo $row_rem['id']; ?>Label">Message</h5>
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <i class="fa fa-times" aria-hidden="true"></i></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php echo$row_rem['message']; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <td style="text-align: center;"><?php echo $row_rem['month']; ?></td>
                            <td style="text-align: center;"><?php echo $row_rem['date_time']; ?></td>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>

<?php
require_once 'Admin-Footer.php';
?>