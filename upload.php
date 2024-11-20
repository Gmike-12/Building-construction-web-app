<?php
include('connectionForeman.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';


if (empty($user_email) || !filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email address. Please try again.'); window.history.back();</script>";
    exit();
}
 
echo "<script>console.log('User Email: " . $user_email . "');</script>";

$token = bin2hex(random_bytes(16));

if (isset($_POST['submit'])) {
    $image = $_POST['image'];
    $infrastructure = $_POST['infrastructure'];
    $selected_types = $_POST['type'];
    $quantities = $_POST['quantity'];
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $arrival = $_POST['arrival'];
    $closing = $_POST['closing'];
   
    $worker_entries = [];

    foreach ($selected_types as $type) {
        $type_key = $type;

        if (!empty($type) && !empty($quantities[$type_key])) {
            $worker_entries[] = $type . '-' . $quantities[$type_key];
        }
    }

    $type_of_workers = implode(', ', $worker_entries);

    $query = mysqli_query($con, "INSERT INTO const_sites (accessToken, sitephoto, infrastructure, _location, typeOfWorkforce, arrivalTime, closingTime) 
        VALUES ('$token', '$image', '$infrastructure', '$location', '$type_of_workers', '$arrival', '$closing')");

    if ($query) {
        // Send email
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mikedev173@gmail.com';
            $mail->Password = 'mukz rlti pmvs dpiw';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            $mail->setFrom('mikedev173@gmail.com', 'Foreman Services');
            $mail->addAddress($user_email, 'User');
    
            $mail->isHTML(false);
            $mail->Subject = 'Access Token for Your Job Advertisement';
            $mail->Body    = "Dear $username,\n\nYour job advertisement has been successfully posted.\n\nHere is your access token: $token\n\nThank you for using our service.";
    
            $mail->send();
            echo "<script>alert('Data inserted successfully. An email possessing your access token has been sent!'); window.location.href = 'upload.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
        }
   
    exit();
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Your AD</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");

        @keyframes zoomBackground {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", sans-serif;
            user-select: none;
            margin: 0;
            padding-top: 30px;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('heavy_equipment_company.jpg');
            background-size: cover;
            background-position: center;
            animation: zoomBackground 8s infinite alternate;
            z-index: -1;
        }

        .container1 {
            display: block;
            position: relative;
            z-index: 1;
        }

        .navbar {
            background: rgb(9, 37, 82);
            display: flex;
            position: fixed;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin: 0;
            padding: 30px 0; 
            top: 0;
            z-index: 9999; 
        }

        .container {
            width: 60%;
            margin: 130px auto 0;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            opacity: 0;
            animation: fadeIn 0.9s ease-in forwards;
            animation-delay: 0.5s;
        }

        .toggle-btn {
            display: flex;
            flex-direction: column;
            cursor: pointer;
            position: absolute;
            left: 20px;
            z-index: 2;
        }

        .line {
            width: 25px;
            height: 3px;
            background-color: #fff;
            margin: 4px 0;
        }

        .menu {
            list-style-type: none;
            padding: 0;
            margin: 0;
            background: rgb(9, 37, 82);
            width: 250px;
            position: fixed;
            top: 115px;
            left: -250px;
            bottom: 0;
            overflow-y: auto;
            transition: left 0.5s ease;
        }

        .menu.open {
            left: 0;
        }

        .menu li {
            padding: 20px;
        }

        .menu li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        .logo {
            position: absolute;
            right: 10px;
        }

        .logo video {
            width: 200px;
            height: 130px;
            border-radius: 10px;
            padding: 15px;
        }

        .main-top {
            border-bottom: 1px solid darkgray;
            display: flex;
            width: 100%;
            background: #fff;
            padding: 10px;
            justify-content: space-around;
            text-align: center;
            font-size: 18px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgb(43, 43, 43);
        }

        .main {
            width: 100%;
            padding: 20px;
        }

        .headertxt {
            margin-left: 25%;
            margin-top: 3%;
        }

        option {
            font-size: 18px;
            padding: 10px 0;
        }

        h1 {
            color: white;
        }

        .title {
            color: black;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        .user-info {
            display: flex;
            align-items: center;
            padding: 10px;
        }

        .user-info img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
        }

        .user-info .username {
            color: #fff;
            margin-left: 20px;
            font-size: 18px;
        }

        button {
            padding: 10px 20px;
            background: rgb(9, 37, 82);
            width: 100px;
            height: 40px;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.5s ease-in-out;
            position: relative;
            overflow: hidden;
        }
        
        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 40px;
            border-radius: 5px;
            background-color:  rgb(94, 130, 187); /* Sky blue color */
            transition: left 0.5s ease-in-out;
            z-index: 0;
        }
 
        
        button:hover::before {
            left: 0;
        }

        button span {
            position: relative;
            z-index: 1;
        }

        input {
            outline: none;
            height: 40px;
            text-align: center;
            border: 1px solid black;
            border-radius: 5px;
        }

        input[type="text"] {
            width: 400px;
            margin-top: 7px;
        }

        input[type="file"] {
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="container1">
        <div class="navbar">
            <div class="toggle-btn">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
            <div class="Heading">
                <h1>CONSTRUCTION JOBS IN KENYA</h1>
            </div>
            <div class="logo">
                <video src="foreman logo.mp4" autoplay muted loop></video>
            </div>
            <nav class="menu">
                <ul>
                    <li>
                        <div class="user-info">
                            <img src="logopic.jpg" alt="User Avatar">
                            <span class="username"><?php echo htmlspecialchars($username); ?></span>
                        </div>
                    </li>
                    
                    <li><a href="LoginToView.php" class="applications">
                    <i class="applications"></i>
                    <span class="nav-item">View Applications</span>
                </a></li>

                <li><a href="Homepage.php" class="Home">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-item">Home</span>
                    </a></li>

                    <li><a href="Delete.php">
                    <i class="far fa-question-circle"></i>
                    <span class="nav-item">Delete Ad</span>
                </a></li>

                    <li><a href="#">
                        <i class="far fa-question-circle"></i>
                        <span class="nav-item">About Us</span>
                     </a></li>
                     
                    <li><a href="" class="help">
                    <i class="far fa-question-circle"></i>
                    <span class="nav-item">Help</span>
                    </a></li>
                   
                    <li><a href="index.php" class="logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-item">Logout</span>
                    </a></li>
                  
                </ul>
            </nav>
        </div>
    </div>

    <div class="container">
        <h1 class="title">FILL THIS TO POST YOUR JOB ADVERTISEMENT</h1>
        <form id="deviceForm" method="POST">
            <div class="form-group">
                <label for="siteImage">Photo of the site:</label>
                <input type="file" id="site image" name="image" required value="">
            </div>
            <div class="form-group">
                <label for="infrastructure">Infrastructure to be constructed:</label>
                <input type="text" id="infrastructure" name="infrastructure" placeholder="e.g. 3 storey apartment, Road, Market" required value="">
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" placeholder="County, Sub-county" required value="">
            </div>


            <div class="form-group">
    <label for="status">Type of workforce needed (Select all required):</label>

    <div>
        <label for="any">Any worker</label>
        <input type="checkbox" id="any" name="type[]" value="Any worker">
        <input type="number" name="quantity[Any worker]" min="1" placeholder="Number required">
    </div>

    <div>
        <label for="painter">Painter/Designer</label>
        <input type="checkbox" id="painter" name="type[]" value="Painter/Designer">
        <input type="number" name="quantity[Painter/Designer]" min="1" placeholder="Number required">
    </div>

    <div>
        <label for="plumber">Plumber</label>
        <input type="checkbox" id="plumber" name="type[]" value="Plumber">
        <input type="number" name="quantity[Plumber]" min="1" placeholder="Number required">
    </div>

    <div>
        <label for="genlab">General Labourer/Mason</label>
        <input type="checkbox" id="genlab" name="type[]" value="General labourer/Mason">
        <input type="number" name="quantity[General labourer/Mason]" min="1" placeholder="Number required">
    </div>

    <div>
        <label for="electrician">Electrician</label>
        <input type="checkbox" id="electrician" name="type[]" value="Electrician">
        <input type="number" name="quantity[Electrician]" min="1" placeholder="Number required">
    </div>

    <div>
        <label for="contractor">Contractor</label>
        <input type="checkbox" id="contractor" name="type[]" value="Contractor">
        <input type="number" name="quantity[Contractor]" min="1" placeholder="Number required">
    </div>

    <div>
        <label for="subcontractor">Sub-contractor</label>
        <input type="checkbox" id="subcontractor" name="type[]" value="Sub-contractor">
        <input type="number" name="quantity[Sub-contractor]" min="1" placeholder="Number required">
    </div>

    <div>
        <label for="engineer">Structural Engineer</label>
        <input type="checkbox" id="engineer" name="type[]" value="Structural engineer">
        <input type="number" name="quantity[Structural engineer]" min="1" placeholder="Number required">
    </div>
</div>


 
            <div class="form-group">
                <label for="arrival">Required arrival time:</label>
                <input type="time" id="arrival" name="arrival" required value="">
            </div>
            <div class="form-group">
                <label for="closing">Closing time:</label>
                <input type="time" id="closing" name="closing" required value="">
            </div>
          
            <button type="submit" name="submit"><span>Submit</span></button>
        </form>
    </div>

    <script>
        document.querySelector('.toggle-btn').addEventListener('click', function() {
            const menu = document.querySelector('.menu');
            menu.classList.toggle('open');
        });

        window.onscroll = function() {
            var navbar = document.querySelector(".navbar");
            if (window.scrollY > 20) {
                navbar.style.position = "fixed";
                navbar.style.top = "0";
            } else {
                navbar.style.position = "fixed";
            }
        };


    </script>
</body>
</html>
