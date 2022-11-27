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




$password_err = $confirm_password_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    if (empty(trim($_POST["new_password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
        $user_password="";
    } else {
        $user_password = trim($_POST["new_password"]);
        
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (($user_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    
    
    
    if (empty($password_err) && empty($confirm_password_err))
    {
        $new_password = password_hash($user_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET user_password = '$new_password' WHERE user_id = '$uid'";
        mysqli_query($link, $sql);
        echo "<script> location.href='../../User-Login.php'; </script>";
        exit;
    }   
}

?>
<!DOCTYPE>
<div class="d-flex justify-content-center"><h1 style="align-center">Changing Password</h1></div>


<div class="modal-body" style="text-align: left;">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="px-3 needs-validation" id="user_add">

    <div class="form-group">
        <label>New Password</label>
        <input type="password" name="new_password" class="form-control">
        <span style="color:#FF0000" class="help-block"><?php echo $password_err; ?></span>
    </div>
        
    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control">
        <span style="color:#FF0000" class="help-block"><?php echo $confirm_password_err; ?></span>
    </div>

   
   

    <button type="submit" class="btn btn-success">Save changes</button>
    </form>
  