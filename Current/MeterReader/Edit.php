<?php

require '../../Config.php';

$name_err = $username_err = $password_err = $email_err = $confirm_password_err = $nic_err = $contact_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    
    $reader_id = $_SESSION['reader_id'];

    $sql = "SELECT * FROM meter_reader WHERE reader_id='" . $reader_id . "'";
    $records = mysqli_query($link, $sql);
    $data = mysqli_fetch_assoc($records);

    //$admin_username = $data['admin_username'];
    $reader_name = $data['reader_name'];
    $reader_nic = $data['reader_nic'];
    $reader_contact = $data['reader_contact'];
    $reader_email = $data['reader_email'];
    $gender = $data['gender'];

    
    if ($stmt = $link->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        //$stmt->bind_param("s", $param_name);

        // Set parameters
        $param_name = trim($_POST["reader_name"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            /* store result */
            $stmt->store_result();

            if ($stmt->num_rows() >= 1) {
                $name_err = "This meter reader already has an account!";
            } else {
                $reader_name = trim($_POST["reader_name"]);
            }
        } else {
            echo "Oops! Something went wrong when inserting name. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }

    if (empty(trim($_POST["reader_username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement
        $sql = "SELECT reader_id FROM meter_reader WHERE reader_name = ?";

        if ($stmt = $link->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["reader_name"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                /* store result */
                $stmt->store_result();

                if ($stmt->num_rows() >= 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $reader_name = trim($_POST["reader_name"]);
                }
            } else {
                echo "Oops! Something went wrong when inserting username. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    if (!preg_match("/^[0-9'V'v]*$/",strlen($_POST["reader_nic"]))) {
        $nic_err = "Only Numbers and V or v allowed for old version and Only Numbers are allowed for new version";
    }else if (strlen($_POST["reader_nic"])!=10 && strlen($_POST["reader_nic"])!=12) {
        $nic_err = "NIC number is Invalid";
    }else{
        $reader_nic = $_POST['reader_nic'];
    }

    if (empty(trim($_POST["reader_contact"]))) {
        $contact_err = "Please enter a contact number.";
    } elseif (strlen(trim($_POST["reader_contact"])) != 10){
        $contact_err = "Invalid c=Contact Number.";
    } else {
        $reader_contact = trim($_POST["reader_contact"]);
        $send_contact = $reader_contact;
    }

    $reader_email = trim($_POST["reader_email"]);
        $reader_email = stripslashes($reader_email);
        $reader_email = htmlspecialchars($reader_email);
        if (!filter_var($reader_email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format";
        } else {
            // Prepare a select statement
            $sql = "SELECT reader_id FROM meter_reader WHERE reader_email = ?";

            if ($stmt = $link->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("s", $param_email);

                // Set parameters
                $param_email = $reader_email;

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    /* store result */
                    $stmt->store_result();

                    if ($stmt->num_rows() >= 1) {
                        $email_err = "This Email is already taken.";
                    } else {
                        $reader_email = trim($_POST["reader_email"]);
                    }
                } else {
                    echo "Oops! Something went wrong when inserting email. Please try again later.";
                }

                // Close statement
                $stmt->close();
            }
        }

    if(isset($_POST['gender']))
    $gender = $_POST['gender'];

    $update = "UPDATE meter_reader SET reader_name = '$reader_name', reader_nic = '$reader_nic', 
    reader_contact = '$reader_contact', reader_email = '$reader_email', gender = '$gender' WHERE reader_id = '$reader_id'";

    if(mysqli_query($link,$update)){
        header("Location:View-Address.php");
    }

    else{
        mysqli_error($link);
    }
}

?>