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

// if user signup button is pressed for ALUMNI account

if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    $user = mysqli_real_escape_string($con, $_POST['user']);

    $tracking_number = mysqli_real_escape_string($con, $_POST['tracking_number']); 
    $mname = mysqli_real_escape_string($con, $_POST['mname']); 
    $lname = mysqli_real_escape_string($con, $_POST['lname']); 
    $birthday = mysqli_real_escape_string($con, $_POST['birthday']); 
    $course = mysqli_real_escape_string($con, $_POST['course']); 
    $batch_year = mysqli_real_escape_string($con, $_POST['batch_year']); 
    $gender = mysqli_real_escape_string($con, $_POST['gender']); 
    $about = mysqli_real_escape_string($con, $_POST['about']); 

    // Check if passwords match
    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
    }

    // Check if email already exists in usertable
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email that you have entered already exists!";
    }

    // Check if Tracking Number exists in tbl_atn
    $tracking_check = "SELECT * FROM tbl_atn WHERE Tracking_Number = '$tracking_number'";
    $tracking_res = mysqli_query($con, $tracking_check);

    if (mysqli_num_rows($tracking_res) == 0) {
        $errors['tracking_number'] = "The inputted Alumni Tracking Number was not found. Please confirm it with the designated school staff.";
    } else {
        // Check if Tracking Number is already registered in tbl_alumni
        $alumni_check = "SELECT * FROM tbl_alumni WHERE Alumni_ID = '$tracking_number'";
        $alumni_res = mysqli_query($con, $alumni_check);

        if (mysqli_num_rows($alumni_res) > 0) {
            // Error: Alumni Tracking Number already registered
            $errors['tracking_number'] = "The Alumni Tracking Number is already registered. Please confirm yours with the designated school staff.";
        }
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
                            VALUES (CONCAT('$name', ' ', '$lname'), '$email', '$encpass', '$code', '$status', '$user')";
            $user_insert_check = mysqli_query($con, $insert_user);

            if(!$user_insert_check) {
                throw new Exception("Error inserting into usertable");
            }

            // Insert into `tbl_alumni` (additional alumni-specific data)
            $insert_alumni = "INSERT INTO tbl_alumni (Alumni_ID, First_Name, Middle_Name, Last_Name, Birthday, Gender, About, Course, Batch_Year, Email, Account)
                              VALUES ('$tracking_number', '$name', '$mname', '$lname', '$birthday', '$gender', '$about', '$course', '$batch_year', '$email', '$status')";
            $alumni_insert_check = mysqli_query($con, $insert_alumni);

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


// if user signup button is pressed for EMPLOYER account

if(isset($_POST['signup1'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    $user = mysqli_real_escape_string($con, $_POST['user']);

    $mname = mysqli_real_escape_string($con, $_POST['mname']); 
    $lname = mysqli_real_escape_string($con, $_POST['lname']); 
    $comlink = mysqli_real_escape_string($con, $_POST['comlink']); 
    $comname = mysqli_real_escape_string($con, $_POST['comname']); 
    $industry = mysqli_real_escape_string($con, $_POST['industry']); 
    $state = mysqli_real_escape_string($con, $_POST['state']); 
    $description = mysqli_real_escape_string($con, $_POST['description']); 

    // Check if passwords match
    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
    }

    // Check if email already exists in usertable
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email that you have entered already exists!";
    }


    // If no errors, proceed with email sending first
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT); // Encrypt password
        $code = rand(999999, 111111); // Random verification code
        $status = "notverified"; // Default status for email verification

        try {
            // Send email for verification using PHPMailer
            if(isset($_POST["signup1"])) {
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
                            VALUES (CONCAT('$name', ' ', '$lname'), '$email', '$encpass', '$code', '$status', '$user')";
            $user_insert_check = mysqli_query($con, $insert_user);

            if(!$user_insert_check) {
                throw new Exception("Error inserting into usertable");
            }

            // Insert into `tbl_employer` (additional employer-specific data)
            $insert_employer = "INSERT INTO tbl_employer (Employer_ID, First_Name, Middle_Name, Last_Name, Website_Link, Company_Name, Industry, State, Description, Email, Account)
                              VALUES ('$tracking_number', '$name', '$mname', '$lname', '$comlink', '$comname', '$industry', '$state', '$description', '$email', '$status')";
            $employer_insert_check = mysqli_query($con, $insert_employer);

            if(!$employer_insert_check) {
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



    // if user clicks the verification code submit button
if(isset($_POST['check'])){
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    
    // Check if the OTP code exists in the `usertable`
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email']; // get the email to update both tables
        $code = 0; // reset OTP code to 0
        $status = 'verified'; // change status to verified
        $employer_status = 'notverified'; // employer status before admin approval
        
        // Start a transaction
        mysqli_begin_transaction($con);
        
        try {
            // Update the `usertable` with code = 0 and status = 'verified'
            $update_usertable = "UPDATE usertable SET code = '$code', status = '$status' WHERE email = '$email' AND user IN ('Alumni', 'Admin')";

            $update_usertable_res = mysqli_query($con, $update_usertable);

            $update_usertable1 = "UPDATE usertable SET code = $code, status = '$employer_status' WHERE email = '$email' AND user = 'Employer'";

            $update_usertable_res1 = mysqli_query($con, $update_usertable1);
            
            if(!$update_usertable_res || !$update_usertable_res1) {
                throw new Exception("Failed to update usertable");
            }
            
            // Update the `tbl_alumni` to set Account status
            $update_alumni = "UPDATE tbl_alumni SET Account = '$status' WHERE Email = '$email'";
            $update_alumni_res = mysqli_query($con, $update_alumni);
            
            if(!$update_alumni_res) {
                throw new Exception("Failed to update tbl_alumni");
            }
            
            // If both updates succeed, commit the transaction
            mysqli_commit($con);
            
            // Set session variables and redirect to home
            $_SESSION['name'] = $fetch_data['name']; // Assuming the `usertable` has a `name` column
            $_SESSION['email'] = $email;
            header('location: login-user.php');
            exit();
            
        } catch (Exception $e) {
            // Rollback the transaction on error
            mysqli_rollback($con);
            $errors['otp-error'] = "Failed while updating the database: " . $e->getMessage();
        }
        
    } else {
        // If OTP is incorrect
        $errors['otp-error'] = "You've entered an incorrect code!";
    }
}


    //if user click login button
    if(isset($_POST['login'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $check_email = "SELECT * FROM usertable WHERE email = '$email'";
        $res = mysqli_query($con, $check_email);
        if(mysqli_num_rows($res) > 0){
            $fetch = mysqli_fetch_assoc($res);
            $fetch_pass = $fetch['password'];
            if(password_verify($password, $fetch_pass)){
                $_SESSION['email'] = $email;
                $status = $fetch['status'];
                $code = $fetch['code'];
                $user = $fetch['user'];
                if($status == 'verified' && $user == 'Alumni'){
                  $_SESSION['email'] = $email;
                  $_SESSION['password'] = $password;
                    header('location: Home.php');

                }
                else if($status == 'verified' && $user == 'Employer'){
                  $_SESSION['email'] = $email;
                  $_SESSION['password'] = $password;
                    header('location: home-Employer.php');

                }
                else if($status == 'notverified' AND $code == 0){
                   $info = "It's look like the admin haven't still verify your account - $email";
                    $_SESSION['info'] = $info;
                    header('location: user-otp1.php');
                }
                else{
                    $info = "It's look like you haven't still verify your email - $email";
                    $_SESSION['info'] = $info;
                    header('location: user-otp.php');
                }
            }else{
                $errors['email'] = "Incorrect email or password!";
            }
        }else{
            $errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
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