<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

require 'PhpSpreadsheet-master/vendor/autoload.php'; // Include Composer's autoloader
use PhpOffice\PhpSpreadsheet\IOFactory;
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $batchYear = $_POST['batch_year1'];
    if (empty($batchYear)) {
        echo "<p style='color: red;'>Invalid input. Please provide a valid batch year.</p>";
        exit;
    }

    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['excelFile']['tmp_name'];

        try {
            $spreadsheet = IOFactory::load($uploadedFile);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            $conn = new mysqli($hostname, $username, $password, $database);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $insertedCount = 0;
            $recipients = []; // Array to store email and ATN pairs

            // Fetch all existing tracking numbers for the selected batch year
            $existingATNs = [];
            $sqlFetch = "SELECT Tracking_Number FROM tbl_atn WHERE Batch_Year = ?";
            $stmt = $conn->prepare($sqlFetch);
            $stmt->bind_param('i', $batchYear);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $existingATNs[] = $row['Tracking_Number'];
            }
            $stmt->close();

            foreach ($data as $row) {
                $email = $row[0];
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $uniqueNumber = str_pad($insertedCount + 1, 5, '0', STR_PAD_LEFT);
                    $trackingNumber = "TN{$uniqueNumber}{$batchYear}";

                    $stmt = $conn->prepare("INSERT INTO tbl_atn (Batch_Year, Email, Tracking_Number) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $batchYear, $email, $trackingNumber);

                    if ($stmt->execute()) {
                        $recipients[] = [
                            'email' => $email,
                            'trackingNumber' => $trackingNumber
                        ];
                        $insertedCount++;
                    }
                    $stmt->close();
                }
            }

            $conn->close();

            // Bulk email sending
            $mail = new PHPMailer(true);

            // Debugging SMTP output
            $mail->Debugoutput = 'html';

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'gsample219@gmail.com';
                $mail->Password = 'soju atrb qbdd rhjb';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('gsample219@gmail.com', 'CvSU-Imus Alumni Tracker');
                $mail->Subject = "Your Alumni Tracking Number (ATN) and Account Creation Instructions";
                $mail->isHTML(true); // Enable HTML format

                foreach ($recipients as $recipient) {
                    $trackingNumber = $recipient['trackingNumber'];
                    $mail->addAddress($recipient['email']);

                    // HTML Content for each recipient
                    $htmlContent = "
                        <p>Dear Alumni,</p>
                        <p>We hope this message finds you well. As part of our Alumni Tracking System, we have generated a unique Alumni Tracking Number (ATN) for you to use while creating your account in the system.</p>
                        <h2>Your ATN: {$trackingNumber}</h2>
                        <p>Please note the following important details:</p>
                        <ul>
                            <li><b>1. Account Registration Requirement:</b> You must use this ATN and your personal active email address to register an account in the system.</li>
                            <li><b>2. Support for Registration Issues:</b> If you encounter any issues while creating your account, feel free to reach out to us by replying to this email. We're here to assist you.</li>
                            <li><b>3. Platform Link (Click Sign Up):</b> 👉 <a href='https://cvsu-alumni-tracker.com/'>CvSU-Imus Alumni Tracking System</a></li>
                        </ul>
                        <p>Thank you for being a valued member of our alumni community. We look forward to helping you stay connected and take advantage of the opportunities available through our platform.</p>
                        <p>Best regards,</p>
                        <p><b>System Admin</b></p>
                        <p><b>CVSU Imus Alumni Tracker</b></p>
                    ";

                    $mail->Body = $htmlContent;

                    // Send the email and clear recipients for the next batch
                    $mail->send();
                    $mail->clearAddresses();
                }

                echo "<a href='Admin-Setting.php' style='display: block; margin-bottom: 20px; margin-top: 20px;'>Reload Page</a>";
                echo "Successfully imported $insertedCount email(s) and sent mail(s) with their respective generated ATNs.";

            } catch (Exception $e) {
                echo "Error sending emails: {$mail->ErrorInfo}";
            }

        } catch (Exception $e) {
            echo "Error processing file: " . $e->getMessage();
        }
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "Invalid request.";
}
?>
