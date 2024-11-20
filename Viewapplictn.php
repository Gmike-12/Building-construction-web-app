<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include('connectionForeman.php');

session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

$message = $_SESSION['message'] ?? '';
$emailSent = $_SESSION['emailSent'] ?? false;
$applications = $_SESSION['applications'] ?? null;

if (isset($_POST['submit'])) {
    $enteredToken = $_POST['token'];

    // Prepare the SQL query to fetch applications with the matching token
    $query = "SELECT * FROM _applications WHERE accessToken = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $enteredToken);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any applications with the given token
    if ($result->num_rows > 0) {
        $applications = $result->fetch_all(MYSQLI_ASSOC);
        $_SESSION['applications'] = $applications; // Store the applications in the session
    } else {
        $error = "No applications found with the provided token.";
        unset($_SESSION['applications']); // Clear previous applications
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Applications</title>
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
    background-image: url('pexels-tomfisk-1770801.jpg');
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
    margin-top: 20%;
    padding: 20px;
    user-select: text;
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
            background-color:  rgb(94, 130, 187);  
            transition: left 0.5s ease-in-out;
            z-index: 0;
        }
        button.employed {
            background: #fff;
            color: #000;
            cursor: default;
            border: 1px solid #000;
            transition: none;
        }

        button.employed::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 5px;
            background-color: transparent;  
            transition: none;
        }

        button span {
            position: relative;
            z-index: 1;
        }

        button:hover::before {
            left: 0;
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

.disp {
    list-style-type: none;
    padding: 0;
}

.displi {
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 30px;  
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
                    
                    <li><a href="upload.php">
                    <i class="fas fa-upload"></i>
                    <span class="nav-item">Post a job</span>
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

          <?php if (!empty($message)): ?>
            
        <?php endif; ?>
    
        <?php if (!empty($applications)): ?>
            <h2>Applications Found:</h2>
            <ul class ='disp'>
                <?php foreach ($applications as $application): ?>
                    <li class='displi'>
                        <strong>Name:</strong> <?php echo htmlspecialchars($application['fullName']); ?><br>
                        <strong>Phone Number:</strong> <?php echo htmlspecialchars($application['phoneNumber']); ?><br>
                        <strong>Email Address:</strong> <?php echo htmlspecialchars($application['emailAddress']); ?><br>
                        <strong>Physical Address:</strong> <?php echo htmlspecialchars($application['physicalAddress']); ?><br>
                        <strong>National ID/Passport:</strong> 
                        <a href="download.php?id=<?php echo $application['Id']; ?>&type=nationalId">Download/View ID/Passport</a><br>
                        <strong>Proficiency:</strong> <?php echo htmlspecialchars($application['jobType']); ?><br>
                        <strong>Job Experience:</strong> <?php echo htmlspecialchars($application['experience']); ?><br>
                        <strong>CV:</strong> 
                        <a href="download.php?id=<?php echo $application['Id']; ?>&type=cv">Download/View Cv</a><br>
                        <strong>Highest Education Level:</strong> <?php echo htmlspecialchars($application['educationLevel']); ?><br>
                        <strong>Academic Certificate:</strong> 
                        <a href="download.php?id=<?php echo $application['Id']; ?>&type=educationCert">Download/View Certificate</a><br>
                        <strong>Availability If Contacted:</strong> <?php echo htmlspecialchars($application['availability_']); ?><br>
                        <strong>Additional Information:</strong> <?php echo htmlspecialchars($application['additionalInfo']); ?><br>

                        <form method="post" action="Success.php">
    <input type="hidden" name="emailAddress" value="<?php echo htmlspecialchars($application['emailAddress']); ?>">
    <input type="hidden" name="fullName" value="<?php echo htmlspecialchars($application['fullName']); ?>">
    <input type="hidden" name="applicationId" value="<?php echo $application['Id']; ?>">
    
    <button type="submit" name="action" value="employ" class="<?php echo (isset($_SESSION['employed'][$application['Id']]) && $_SESSION['employed'][$application['Id']]) ? 'employed' : ''; ?>">
        <span><?php echo (isset($_SESSION['employed'][$application['Id']]) && $_SESSION['employed'][$application['Id']]) ? 'Employed' : 'Employ'; ?></span>
    </button>
</form>

                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (isset($error)): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
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
