<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('connectionForeman.php');

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

// Define a function to send the email
function sendSuccessEmail($to, $name) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mikedev173@gmail.com';
        $mail->Password = 'mukz rlti pmvs dpiw';  
        $mail->Port = 587;

        $mail->setFrom('mikedev173@gmail.com', 'Foreman Services');
        $mail->addAddress($to, $name);

        $mail->isHTML(false);
        $mail->Subject = 'Application Status Update';
        $mail->Body = "Dear $name,\n\nCongratulations! Your application for has been successfully processed and analysed. YOU HAVE BEEN SELECTED!.\n\nBest regards,\nThe Team";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Error: " . $mail->ErrorInfo);
        return false;
    }
}

// Handle the form submission
if (isset($_POST['action']) && $_POST['action'] === 'employ') {
    if (isset($_POST['emailAddress']) && isset($_POST['fullName']) && isset($_POST['applicationId'])) {
        $emailAddress = $_POST['emailAddress'];
        $fullName = $_POST['fullName'];
        $applicationId = $_POST['applicationId'];

        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            $emailSent = sendSuccessEmail($emailAddress, $fullName);
            if ($emailSent) {
                $_SESSION['employed'][$applicationId] = true;
            }
        }
    }
    header('Location: Viewapplictn.php');
    exit;
}
?>

 