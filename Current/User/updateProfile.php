<?php
require_once '../../Config.php';
require_once 'User-Header.php';
$username_err=$user_nic=$email_err=$contact_err="";
$username_err = $password_err = $email_err = $confirm_password_err = $nic_err = $contact_err = $new_password_err  = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
        $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM users WHERE user_id='" . $user_id . "'";
    $records = mysqli_query($link, $sql);
    $data = mysqli_fetch_assoc($records);

    $user_name = $data['user_name'];
    $user_nic = $data['user_nic'];
    $user_contact = $data['user_contact'];
    $user_email = $data['user_email'];
    $gender = $data['gender'];

    
    $sql = "SELECT user_id FROM users WHERE user_name = ?";

    if ($stmt = $link->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_username);

        // Set parameters
        $param_username = trim($_POST["user_name"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            /* store result */
            $stmt->store_result();

         $user_name = trim($_POST["user_name"]);
        } else {
            echo "Oops! Something went wrong when inserting username. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }

    $user_email = trim($_POST["user_email"]);
        $user_email = stripslashes($user_email);
        $user_email = htmlspecialchars($user_email);
        
        
    
    
    
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format";
        } else {
            // Prepare a select statement
            $sql = "SELECT user_id FROM users WHERE user_email = ?";

            if ($stmt = $link->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_email);

                // Set parameters
                $param_email = $user_email;

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    /* store result */
                    $stmt->store_result();

                    if ($stmt->num_rows() >= 1) {
                        $emailsql = "SELECT * FROM users WHERE user_id='" . $user_id . "'";
                        $emailsqlResult=mysqli_query($link, $emailsql);
                        $getemail=mysqli_fetch_array($emailsqlResult);

                        $uicR=$getemail['user_email'];
                        $Eemail=$_POST["user_email"];
                        if($uicR!=$Eemail)
                        {
                            $email_err = "This Email Address is already taken.";
                        }
                        
                        
                        
                    } else {
                        $user_email = trim($_POST["user_email"]);
                    }
                } else {
                    echo "Oops! Something went wrong when inserting email. Please try again later.";
                }

                // Close statement
                $stmt->close();
            }
        }
        
        $uicValue=$_POST['user_nic'];
        
    
        $checkicquery="SELECT * FROM users WHERE user_nic='" . $uicValue . "'";
        $checkicReault=mysqli_query($link,$checkicquery);
        $countic=mysqli_num_rows($checkicReault);
        if($countic>0)
        {
            $uicsql = "SELECT * FROM users WHERE user_id='" . $user_id . "'";
            $uicResult=mysqli_query($link, $uicsql);
            $getUIC=mysqli_fetch_array($uicResult);
            $uicR=$getUIC['user_nic'];
            if($uicValue!=$uicR)
            {
               $nic_err = "This NIC had been used";
            }
        }
        

        if (!preg_match("/^[0-9]*$/",($_POST["user_nic"]))) {
            $nic_err = "Only Number are allowed";
        }else if (strlen($_POST["user_nic"])!=10 && strlen($_POST["user_nic"])!=12) {
            $nic_err = "NIC number is Invalid";
        }else{
            $user_nic = $_POST['user_nic'];
        }

        if (empty(trim($_POST["user_contact"]))) {
            $contact_err = "Please enter a Contact Number.";
        } elseif (strlen(trim($_POST["user_contact"])) != 10){
            $contact_err = "Invalid Contact Number.";
        } else {
            $user_contact = trim($_POST["user_contact"]);
            $send_contact = $user_contact;
        }

        if(isset($_POST['gender']))
        {
            $gender = $_POST['gender'];
        }
    

    if (empty($username_err) && empty($user_nic) && empty($email_err) && empty($contact_err))
    {
        $update = "UPDATE users SET user_name = '$user_name', user_nic = '$user_nic', 
        user_contact = '$user_contact', user_email = '$user_email', gender = '$gender' WHERE user_id = '$user_id'";

        $message = "Profile updated";
        $activity = "INSERT INTO activity_log (user_id, message) VALUES ('$user_id', '$message')";
        if(mysqli_query($link,$update)){
            mysqli_query($link,$activity);
            header("Location:Profile.php");
        }

        else{
            mysqli_error($link);
        }
        
    }
    
}



?>