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
        $rec_email = $_POST['emp_email1'];
        $sender_mail = $_POST['sender_mail'];

        if (empty($rec_email)) {
            die('Recipient email is empty. Please check the form data.');
        }

        // Email content
        $htmlContent = "
            <h2>Your Job Opening Has A New Applicant</h2>
            <p>We are excited to inform you that a new applicant has applied for your job posting on the CVSU Imus Alumni Tracking System.

                To view more details about the application, including the applicant's resume and profile, kindly log in to your account on our platform CvSU-Imus Alumni Tracker.</p>

                <p>If you have any questions or need assistance, feel free to reach out to us.

                Thank you for choosing the CVSU Imus Alumni Tracking System to connect with our talented alumni!</p>

               <p>To log in, please visit our platform here:</p>

               <p>👉 <a href='https://cvsu-alumni-tracker.com/'>Login to CvSU-Imus Alumni Tracking System</a></p>

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
        $mail->Subject = 'New Applicant For Your Job Opening!';
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
