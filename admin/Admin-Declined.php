<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $mail = new PHPMailer(true);

        // Debugging SMTP output
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';

        // Fetch form data
        $rec_email = $_POST['rec_email'];
        $sender_mail = $_POST['sender_mail'];

        if (empty($rec_email)) {
            die('Recipient email is empty. Please check the form data.');
        }

        // Email content
        $htmlContent = "
            <h2>Account Status: Declined</h2>
            <p>Unfortunately, your account request has been <b>declined</b>.</p>

                <p><b>Reason for Decline:</b></p>

                <p>The account request did not comply with our platform's terms and conditions.</p>

                <p>We encourage you to review the submitted information and reapply with the necessary corrections. If you need further clarification or assistance, please don’t hesitate to reach out.</p>

                <p>You can submit a new account request here:</p>

               <p>👉 <a href='https://cvsu-alumni-tracker.com/'>Login to CvSU-Imus Alumni Tracking System</a></p>

               <p>If you encounter any issues logging in, feel free to contact us for assistance.</p>

            <p>Best regards,</p>
            <p><b>System Admin</b></p>
            <p><b>CVSU Imus Alumni Tracker</b></p>
        ";

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $sender_mail; // Gmail account
        $mail->Password = 'soju atrb qbdd rhjb'; // App-specific password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom($sender_mail, 'CvSU-Imus Alumni Tracker');
        $mail->addAddress($rec_email);

        $mail->isHTML(true);
        $mail->Subject = 'Update on Your Account Request!';
        $mail->Body = $htmlContent;

        // Send email
        if ($mail->send()) {
            echo 'Email sent successfully!';
        } else {
            echo 'Failed to send email.';
        }
    } catch (Exception $e) {
        echo 'Error: ', $mail->ErrorInfo;
    }
}
?>
