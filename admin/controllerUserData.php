<?php 
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require "connection.php";

$email = "";
$name = "";
$errors = array();

//if user signup button
if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    $user = mysqli_real_escape_string($con, $_POST['user']);
    $position = mysqli_real_escape_string($con, $_POST['position']);
    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
    }
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email that you have entered is already exist!";
    }
    // If no errors, proceed with email sending first
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT); // Encrypt password
        $code = rand(999999, 111111); // Random verification code
        $status = "notverified"; // Default status for email verification

        try {
            // Send email for verification using PHPMailer
            if(isset($_POST["signup"])) {
                $mail = new PHPMailer(true);

                $email = $_POST['email'];
                $name = $_POST['name'];
                $com_mail = $_POST['sender']; // Sender email

                // Email body content
                $htmlContent = '<h2>'.$code.'</h2>';

                $headers = "From:". $name." <".$email.">";

                // PHPMailer settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $com_mail; // Sender email address
                $mail->Password = 'soju atrb qbdd rhjb'; // Sender email password
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom($_POST["sender"]);
                $mail->addAddress($_POST["email"]); // Recipient email address

                $mail->isHTML(true); // Email format set to HTML
                $mail->Subject = 'Verification Code';
                $mail->Body = $htmlContent;

                // Attempt to send the email
                $mail->send();
            }

            // Begin transaction only after successful email
            mysqli_begin_transaction($con);

            // Insert into `usertable`
            $insert_user = "INSERT INTO usertable (name, email, password, code, status, user)
                            VALUES ('$name', '$email', '$encpass', '$code', '$status', '$user')";
            $user_insert_check = mysqli_query($con, $insert_user);

            if(!$user_insert_check) {
                throw new Exception("Error inserting into usertable");
            }

            // Insert into `tbl_admin` (additional alumni-specific data)
            $insert_admin = "INSERT INTO tbl_admin (Name, Email, Status, Position)
                              VALUES ('$name', '$email', '$status', '$position')";
            $alumni_insert_check = mysqli_query($con, $insert_admin);

            if(!$alumni_insert_check) {
                throw new Exception("Error inserting into Database");
            }

            // If both inserts are successful, commit the transaction
            mysqli_commit($con);

            // Email sent and data inserted, proceed with user-otp
            $info = "We've sent a verification code to your email - $email";
            $_SESSION['info'] = $info;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            header('location: user-otp.php');
            exit();

        } catch (Exception $e) {
            // Rollback the transaction on error
            mysqli_rollback($con);

            if($e->getMessage() === "Failed to send OTP. Please check your internet connection and try again.") {
                $errors['mail-error'] = $e->getMessage();
            } else {
                $errors['db-error'] = "Transaction failed: " . $e->getMessage();
            }
        }
    }

}
    //if user click verification code submit button
    if(isset($_POST['check'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $fetch_code = $fetch_data['code'];
            $email = $fetch_data['email'];
            $code = 0;
            $status = 'verified';
            $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
            $update_otp1 = "UPDATE tbl_admin SET status = '$status' WHERE Email = '$email'";
            $update_res = mysqli_query($con, $update_otp);
            $update_res1 = mysqli_query($con, $update_otp1);
            if($update_res && $update_res1){
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                header('location: home.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while updating code!";
            }
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

    //if user click login button
    if(isset($_POST['login'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $check_email = "SELECT * FROM usertable WHERE email = '$email' AND user = 'Admin'";
        $res = mysqli_query($con, $check_email);
        if(mysqli_num_rows($res) > 0){
            $fetch = mysqli_fetch_assoc($res);
            $fetch_pass = $fetch['password'];
            if(password_verify($password, $fetch_pass)){
                $_SESSION['email'] = $email;
                $status = $fetch['status'];
                if($status == 'verified'){
                  $_SESSION['email'] = $email;
                  $_SESSION['password'] = $password;
                    header('location: home.php');
                }else{
                    $info = "It's look like you haven't still verify your email - $email";
                    $_SESSION['info'] = $info;
                    header('location: user-otp.php');
                }
            }else{
                $errors['email'] = "Incorrect email or password!";
            }
        }else{
            $errors['email'] = "It's look like you're not yet a member! Click on the Upper Left link to signup.";
        }
    }

    //if user click continue button in forgot password form
    if(isset($_POST['check-email'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $check_email = "SELECT * FROM usertable WHERE email='$email'";
        $run_sql = mysqli_query($con, $check_email);
        if(mysqli_num_rows($run_sql) > 0){
            $code = rand(999999, 111111);
            $insert_code = "UPDATE usertable SET code = $code WHERE email = '$email'";
            $run_query =  mysqli_query($con, $insert_code);
            if($run_query){
                $subject = "Password Reset Code";
                $message = "Your password reset code is $code";
                $sender = "From: gsample219@gmail.com";
                if(mail($email, $subject, $message, $sender)){
                    $info = "We've sent a passwrod reset otp to your email - $email";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;
                    header('location: reset-code.php');
                    exit();
                }else{
                    
                    if(isset($_POST["check-email"])) {
                    $mail = new PHPMailer(true);

                    $email = $_POST['emai1'];
                    $name = 'gsample219';
                    $com_mail = $_POST['sender'];

                    $htmlContent = '<h2>'.$code.'</h2>';

                    $headers = "From:". $name." <".$email.">";

                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = $com_mail;
                    $mail->Password = 'soju atrb qbdd rhjb';
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = 465;

                    $mail->setFrom($_POST["sender"]);

                    $mail->addAddress($_POST["email"]);

                    $mail->isHTML(true);

                    $mail->Header = $headers;
                    $mail->Subject = 'Appointment For House Tripping Reschedule By. '.$_POST["name"];
                    $mail->Body = $htmlContent;

                    $mail->send();

                    $info = "We've sent a passwrod reset otp to your email - $email";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;
                    header('location: reset-code.php');
                    exit();

                    }
                }
            }else{
                $errors['db-error'] = "Something went wrong!";
            }
        }else{
            $errors['email'] = "This email address does not exist!";
        }
    }

    //if user click check reset otp button
    if(isset($_POST['check-reset-otp'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $email = $fetch_data['email'];
            $_SESSION['email'] = $email;
            $info = "Please create a new password that you don't use on any other site.";
            $_SESSION['info'] = $info;
            header('location: new-password.php');
            exit();
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

    //if user click change password button
    if(isset($_POST['change-password'])){
        $_SESSION['info'] = "";
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
        if($password !== $cpassword){
            $errors['password'] = "Confirm password not matched!";
        }else{
            $code = 0;
            $email = $_SESSION['email']; //getting this email using session
            $encpass = password_hash($password, PASSWORD_BCRYPT);
            $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";
            $run_query = mysqli_query($con, $update_pass);
            if($run_query){
                $info = "Your password changed. Now you can login with your new password.";
                $_SESSION['info'] = $info;
                header('Location: password-changed.php');
            }else{
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
    
   //if login now button click
    if(isset($_POST['login-now'])){
        header('Location: login-user.php');
    }


?>