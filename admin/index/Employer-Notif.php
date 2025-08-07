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
        $mail_job = $_POST['mail_job'];
        $mail_status = $_POST['mail_status'];

        if (empty($rec_email)) {
            die('Recipient email is empty. Please check the form data.');
        }

        // Email content
        $htmlContent = "
            <h2>Your Job Application Has Been Updated</h2>
            <p>We are excited to provide you with an update regarding your job application for the position of <b>".$mail_job."</b> submitted through the CvSU-Imus Alumni Tracking System.</p>

                <p>Your application status has been updated to: <b>".$mail_status."</b></p>

                <p><b>• If Status is Under Screening or Interview Scheduled:</b></p>

                <p>Kindly check your account for further details, including any instructions or schedules for the next steps in the application process.</p>

                <p><b>• If Status is Declined:</b></p>

                <p>We encourage you to explore other opportunities available on the platform. Keep your profile updated to improve your chances of matching with suitable job openings.</p>

               <p>To view the full details of your application status, please log in to your account:</p>

               <p>👉 <a href='https://cvsu-alumni-tracker.com/'>Login to CvSU-Imus Alumni Tracking System</a></p>

               <p>If you have any questions or require assistance, feel free to reach out to us. Thank you for using the CvSU-Imus Alumni Tracking System, and we wish you the best of luck in your career journey!</p>

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
        $mail->Subject = 'Update on Your Job Application!';
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
