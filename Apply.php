
<?php
include('connectionForeman.php');

session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if (isset($_GET['ad_id'])) {
    $ad_id = $_GET['ad_id'];

    // Query to get the token from the ads table
    $query = "SELECT accessToken FROM const_sites WHERE Id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $ad_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $token1 = $row['accessToken'];
    } else {
        echo "Ad not found.";
        exit();
    }
}

if (isset($_POST['submit'])) {
    
    $token1 = mysqli_real_escape_string($con, $_POST['token']);
    $Name = mysqli_real_escape_string($con, $_POST['name_']);
    $Phone = mysqli_real_escape_string($con, $_POST['phone']);
    $Email = mysqli_real_escape_string($con, $_POST['email']);
    $Address = mysqli_real_escape_string($con, $_POST['address']);
    $Jobtype = mysqli_real_escape_string($con, $_POST['job_type']);
    $Experience = mysqli_real_escape_string($con, $_POST['experience']);
    $Education = mysqli_real_escape_string($con, $_POST['education']);
    $Available = isset($_POST['available']) ? 'yes' : 'no';
    $Additional_info = mysqli_real_escape_string($con, $_POST['additional_info']);
   
    $uploadDir = __DIR__ . '/uploads/';
    $idPath = $cvPath = $educertPath = '';

    // Handle ID/Passport files
    if (isset($_FILES['ID_Passport']) && $_FILES['ID_Passport']['tmp_name']) {
        $IdFiles = preg_replace("/[^a-zA-Z0-9\.-]/", "_", basename($_FILES['ID_Passport']['name']));
        $idPath = $uploadDir . $IdFiles;
        if (!move_uploaded_file($_FILES['ID_Passport']['tmp_name'], $idPath)) {
            echo "<script>alert('Failed to upload ID/Passport file.')</script>";
            exit(); // Stop the script if the upload fails
        }
    }

    // Handle CV file
    if (isset($_FILES['CV']) && $_FILES['CV']['tmp_name']) {
        $cvFileName = preg_replace("/[^a-zA-Z0-9\.-]/", "_", basename($_FILES['CV']['name']));
        $cvPath = $uploadDir . $cvFileName;
        if (!move_uploaded_file($_FILES['CV']['tmp_name'], $cvPath)) {
            echo "<script>alert('Failed to upload CV file.')</script>";
            exit(); // Stop the script if the upload fails
        }
    }

    // Handle education certificate file
    if (isset($_FILES['educert']) && $_FILES['educert']['tmp_name']) {
        $educertFileName = preg_replace("/[^a-zA-Z0-9\.-]/", "_", basename($_FILES['educert']['name']));
        $educertPath = $uploadDir . $educertFileName;
        if (!move_uploaded_file($_FILES['educert']['tmp_name'], $educertPath)) {
            echo "<script>alert('Failed to upload education certificate.')</script>";
            exit(); // Stop the script if the upload fails
        }
    }

    // Insert data into the database
    $stmt = $con->prepare("INSERT INTO _applications (accessToken, fullName, phoneNumber, emailAddress, physicalAddress, nationalId, jobType, experience, cv, educationLevel, educationCert, availability_, additionalInfo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssss", $token1, $Name, $Phone, $Email, $Address, $idPath, $Jobtype, $Experience, $cvPath, $Education, $educertPath, $Available, $Additional_info);
    if ($stmt->execute()) {
        echo "<script>alert('Thank you! A confirmation email will be sent to you if successfully hired.')</script>";
        // Redirect to a confirmation page
        echo "<script>window.location.href = 'http://localhost/Foremanapp/Apply.php'</script>";
    } else {
        echo "<script>alert('An Error Occurred!')</script>";
    }
    $stmt->close();
    exit();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Your AD</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
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
        textarea{
            width: 400px;
            height: 60px;
        }
        option{
            margin-bottom: 10px;
           
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
                    
                    <li><a href="upload.php" class="applications">
                    <i class="applications"></i>
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
        <h1 class="title">JOB APPLICATION FORM</h1>
        <form id="deviceForm" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token1); ?>">

            <div class="form-group">
                <label for="name_">Full Name As Per ID: </label>
                <input type="Text" id="name_" name="name_" placeholder="Mike Kibet Gitau" required value="">
            </div>

            <div class="form-group">
                <label for="phone">Phone Number: </label>
                <input type="tel" id="phone" name="phone" required value="">
            </div>

            <div class="form-group">
                <label for="email">Email Address: </label>
                <input type="email" id="email" name="email" placeholder="person@gmail.com" required value="">
            </div>

            <div class="form-group">
                <label for="address">Physical Address: </label>
                <input type="address" id="address" name="address" placeholder="Kiambu, Juja" required value="">
            </div>

            <div class="form-group">
                <label for="ID_Passport">Upload Copy of ID/Passport(Front and Back Inform of PDF): </label>
                <input type="file" id="ID_Passport" name="ID_Passport">
            </div>

            <div class="form-group">
                <label for="job_type">What Position Best Describes You: </label>
                <select id="job_type" name="job_type" style="height: 26px;">

                    <option>Job Type</option>
                     <option>Painter/Designer</option>
                     <option>Plumber</option>
                     <option>General Labourer/Mason</option>
                     <option>Electrician</option>
                     <option>Contractor</option>
                     <option>Sub-contractor</option>
                     <option>Structural Engineer</option>
                </select>
            </div>

            <div class="form-group">
                <label for="experience">Previous Job Experience(Brief Explanation): </label>
                <textarea id="experience" name="experience" required value=""></textarea>
            </div>

            <div class="form-group" id="CV">
                <label for="CV">Upload CV: </label>
                <input type="file" id="CV" name="CV">
            </div>


            <div class="form-group">
                <label for="education">Highest Education Level: </label>
                <select id="education" name="education" style="width: 160px; height: 26px;">
                    <option>Education</option>
                    <option>None</option>
                    <option>Primary Level</option>
                    <option>Highschool Level</option>
                    <option>College Diploma</option>
                    <option>Bachelor's Degree</option>
                    <option>Masters</option>
                    <option>PHD</option>
                </select>
            </div>

            <div class="form-group" id="educert">
                <label for="educert">Upload Education Certificates(KCSE, KCPE Or Any Other): </label>
                <input type="file" id="educert" name="educert">
            </div>

            <div class="form-group">
                <label for="available">Will You Be Available If Called Upon: </label>
                <input type="checkbox" id="available" name="available">
            </div>

            <div class="form-group">
                <label for="additional_info">Additional Information: </label>
                <textarea id="additional_info" name="additional_info"></textarea>
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

    document.addEventListener('DOMContentLoaded', function() {
    const phoneInputField = document.querySelector("#phone");

    const phoneInput = window.intlTelInput(phoneInputField, {
        initialCountry: "ke",
        onlyCountries: ["ke"],
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });

    // Automatically append the +254 country code if the user inputs a number without it
    phoneInputField.addEventListener('blur', function() {
        const currentValue = phoneInputField.value.trim();
        if (!currentValue.startsWith("+254")) {
            phoneInputField.value = "+254" + currentValue;
        }
    });
});

function toggleCVUpload() {
        const education = document.getElementById('education').value;
        const eduCert = document.getElementById('educert');

 
        if (education === 'None') {
             
            eduCert.style.display = 'none';
        } else {
            eduCert.style.display = 'block';
            
        }
    }

    document.getElementById('education').addEventListener('change', toggleCVUpload);

    document.addEventListener('DOMContentLoaded', toggleCVUpload);
      
    </script>
</body>
</html>
