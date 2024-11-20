<?php 
include("connectionForeman.php");

if (isset($_POST['submit']) && strlen($_POST["password"]) >= 8) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


    $query = mysqli_query($conn, "INSERT INTO _login (email_, password_) 
        VALUES ('$email', '$password')");

    if ($query) {
        
        echo "<script> alert('Success! You can now Login')
        
         window.location.href = 'http://localhost/Foremanapp/index.php';
        
        </script>";
         
         
    } else if(strlen($_POST["password"]) < 8) {
            !$_POST['submit'];
        echo "<script> alert('An Error Occurred or the password is less than 8 characters')</script>";
    }
    $conn->close();
}


?>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Account</title>
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

        .backgroundimg {
            animation: zoomBackground 8s infinite alternate;
            background-image: url('Tower-Crane-Power-Requirements.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            width: 100%;
            height: 100%;
        }

        input {
            width: 300px;
            height: 37px;
            border-radius: 5px;
            background-color: white;
            padding: 3px;
            outline: none;
            border: none;
            margin-left: 0;
            text-align: start;
        }

        .right {
            position: relative;
            z-index: 1;
            margin-top: 10%;
            text-align: center;
            padding: 20px;
            background-color: rgb(10, 10, 67);
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .pass {
            display: flex;
            align-items: center;
            width: 100%;
            height: 44px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: white;
            padding-right: 10px;
        }

        #togglePassword {
            position: relative;
    margin-top: -1%;
    width: 11%;
    height: 110%;
        }

        strong {
            font-size: 30px; /* Correct font size */
            color: white;
            text-shadow: 1px 1px gray;
        }

        button {
            margin-left: 0;
            margin-top: 8px;
            width: 310px;
            height: 35px;
            border: none;
            font-size: 15px;
            border-radius: 2px;
            background: rgb(26, 106, 235);
            color: white;
            transition: background 0.5s ease-in-out;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            border-radius: 2px;
            background-color: rgb(94, 130, 187);
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
h3 {
            color: white;
            margin-bottom: 0;
            text-shadow: 1px 1px gray;
            text-align: center;
        }

    </style>
</head>
<body>

<div class="background">
    <div class="backgroundimg"></div>
</div>

<div class="right">
    <strong>Create New Account</strong>
    <form method="POST" id="login"> 
        <h3 class="email">Email Address</h3>
        <input type="email" id="email" name="email" placeholder="Email address" required value="">

        <h3 class="password">Password</h3>
        <div class="pass">
            <input placeholder="Password" type="password" id="password" name="password" required value="">
            <img id="togglePassword" src="eye.png" onclick="view()" alt="Toggle Password">
        </div>

        <button id="submit" type="submit" name="submit"><span>Sign in</span></button>
    </form>
</div>

<script>
    let i = 0;
    let Password = document.getElementById("password");
    let Pass = document.getElementById("togglePassword");

    function view() {
        i++;
        if (i % 2 == 0) {
            Password.type = "text";
        } else {
            Password.type = "password";
        }
    }
</script>

</body>
</html>
