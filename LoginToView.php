
<?php
include('connectionForeman.php');

session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

// Handle the form submission
if (isset($_POST['submit'])) {
    $enteredToken = $_POST['token'];

    // Check if the token exists in the database
    $stmt = $con->prepare("SELECT * FROM _applications WHERE accessToken = ?");
    $stmt->bind_param("s", $enteredToken);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If token is valid, redirect to Viewapplictn.php with the token as a query parameter
        header("Location: Viewapplictn.php?token=" . urlencode($enteredToken));
        exit();
    } else {
        // If token is not found, show an error message
        $error = "Invalid access token. Please try again.";
    }

    $stmt->close();
}

$con->close();


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
        <?php if (isset($error)): ?>
            <div style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="Viewapplictn.php">
            <div class="form-group">
                <label for="token">Enter Your Access Token To View Applications For Your Posted Job:</label>
                <input type="text" id="token" name="token" required value="">
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
