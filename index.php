
<?php
include("connectionForeman.php");
require_once 'vendor/autoload.php';

session_start();



if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists in the database
    $query = mysqli_query($conn, "SELECT * FROM _login WHERE email_='$email'");

    if (!$query) {
        die('Query failed: ' . mysqli_error($conn));
    }

    $user = mysqli_fetch_assoc($query);

    if ($user) {
        echo "<script>console.log('User found');</script>";

        $_SESSION['email'] = $user['email_'];

        // Ensure password stored in DB is hashed
        if (password_verify($password, $user['password_'])) {
            // Store username in session
            $_SESSION['username'] = explode('@', $user['email_'])[0]; // Save the part before '@' as username
            
            echo "<script>
                    alert('Login successful!');
                    window.location.href = 'http://localhost/Foremanapp/Homepage.php';
                  </script>";
            exit();
        } else {
            echo "<script> alert('Incorrect password. Please try again.')</script>";
        }
    } else {
        echo "<script> alert('Email not found. Please check your email or create a new account.')</script>";
    }
}

$conn->close();




/**$clientID = ''
$clientSecret = ''
$redirectUri =  'http://localhost/Foremanapp/Homepage.php';

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
315848644101-o1romh8klpca0mhjr3rreac35k7s8hj2.apps.googleusercontent.com

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
$googleAuthUrl = $client->createAuthUrl();
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;


    // now you can use this profile info to create account in your website and make user logged in.
} */




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Construction Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");

        @keyframes zoomBackground {
            0% {
                transform: scale(1); /* Start at normal size */
            }
            50% {
                transform: scale(1.05); /* Zoom in */
            }
            100% {
                transform: scale(1); /* Zoom back to normal size */
            }
        }

        html, body {
            font-family: "Poppins", sans-serif;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .background {
            position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: -1;
    overflow: hidden;
        }

        .backgroundimg{
            animation: zoomBackground 8s infinite alternate;
    background-image: url('Tower-Crane-Power-Requirements.jpg');
    background-repeat: no-repeat;
    background-size: cover;
    width: 100%;
    height: 100%;
        }
       
        .content {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-around;
    width: 100%;
    height: 100%;
}
        .icons {
            display: flex;
            margin-top: 50px;
        }

        .icons a {
            margin: 0 15px;
            font-size: 30px;
            color: #fff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .icons a:hover {
            color: #007bff; /* Change this to your desired hover color */
        }

        p {
            font-weight: bold;
            color: white;
            font-size: 50px; /* Correct font size */
            text-shadow: 1px 1px gray;
            margin-bottom: 0;
        }

        .left {
            margin: auto 10px auto;
        }

        .right {
            margin-top: 14%;
            height: 290px;
            text-align: center;
            padding: 5px;
        }

        h3 {
            color: white;
            margin-bottom: 0;
            text-shadow: 1px 1px gray;
            text-align: start;
        }

        input {
            width: 300px;
            height: 37px;
            border-radius: 5px;
            background-color: white;
            padding:3px;
            outline: none;
            border: none;
            margin-left: 0;
            text-align: start;
        }

        strong {
            font-size: 30px; /* Correct font size */
            color: white;
            text-shadow: 1px 1px gray;
        }

        .brief {
            font-size: 20px;
            text-shadow: 1px 1px gray;
            margin-bottom: 0;
        }

        .terms {
            display: flex;
            font-size: 15px;
            justify-content: space-around;
            margin-top: 14%;
        }

        a {
            color: white;
        }

        button a {
            text-decoration: none;
        }

        button {
            margin-left: 0;
            width: 85px;
            height: 35px;
            border: none;
            font-size: 15px;
            border-radius: 2px;
            background: rgb(26, 106, 235);
            color: white;
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
            height: 35px;
            border-radius: 2px;
            background-color:  rgb(94, 130, 187);  
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

        .btns {
            margin-top: 25px;
            justify-content: space-between;
        }
        #submit {
          left: -12%
        }
        #google {
            right: -12%;
            width: 140px;
        }
 .pass{
    margin-left: auto;
    margin-right: auto;
    display: flex;
    background-color: white;
    width: 300px;
    height: 44px;
    margin-bottom: 1%;
    border-radius: 5px;
}
 #togglePassword{
    position: relative;
    margin-top: -1%;
    width: 11%;
    height: 110%;
 }
 #newacc{
    width: 120px;
    height: 35px;
    margin-top: 10px;
    margin-left: -60%;
 }
    </style>
</head>

<body>
    <div class="background">
    <div class="backgroundimg"></div>
</div>
      <div class = "content">
        <div class="left">
            <p>Welcome <br>Back</p>
            <p class="brief">This is the number one building<br>construction platform where you<br>can advertise your job or look<br>for a job</p>
            <div class="icons">
                <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://whatsapp.com" target="_blank"><i class="fab fa-whatsapp"></i></a>
                <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>

        <div class="right">
            <strong>Sign in</strong>

            <form method="POST" id="login"> 
                <h3 class="email">Email Address</h3>
                <input type="email" id="email" name="email" placeholder="Email address" required value= "">

                <h3 class="password">Password</h3>
         <div class="pass">
                <input placeholder="Password" type="password" id="password" name="password" required value ="">
                <img id="togglePassword" src="eye.png" onclick="view()"><br>
</div>

                <div class="btns">
                    <button id="submit" type="submit" name="submit"><span>Login</span></button>
                    <button id="google" type="button" onclick="window.location.href='<?php echo htmlspecialchars($googleAuthUrl); ?>'"><span>Google Login</span></button>
                   
                </div>
                     
            </form>
            <a href="createnewacc.php"><button id="newacc"><span>Create account</span></button></a>
            <div class="terms">
                <h4><a href="#">Terms & conditions</a></h4>
                <h4><a href="#">About us</a></h4>
            </div>
        </div>
    </div>

    <script>


        let i = 0
        let Password = document.getElementById("password")
        let Pass = document.getElementById("togglePassword")

    function view(){
            i++
    
    if(i % 2 == 0){
        Password.type = "text"
    }
    else if(i == 1){
        Password.type = "password"
    }
    else{
        Password.type = "password"
    
    }
        }
    </script>
</body>
</html>
