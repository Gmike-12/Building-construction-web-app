

<?php

include('connectionForeman.php');

if (isset($_GET['county']) && !empty($_GET['county'])) {
    $selected_county = $_GET['county'];
    $query = "SELECT * FROM const_sites WHERE _location LIKE '$selected_county%'";
} else {
    $query = "SELECT * FROM const_sites ORDER BY Id DESC"; // This runs when "All counties" or the default option is selected
}

$result = mysqli_query($con, $query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        echo '<div class="site">'; ?>
        <img src ="<?php echo $row['sitePhoto']; ?>" alt="Site Photo">
        <?php
        echo '<p><h3>Infrastructure:</h3> <i class="fas fa-building"></i> : ' . $row['infrastructure'] . '</p>';
        echo '<p><h3>Location:</h3> <i class="fas fa-map-marker"></i> : ' . $row['_location'] . '</p>';
        echo '<p><h3>Workforce Needed:</h3> <i class="fas fa-briefcase"></i> : ' . $row['typeOfWorkforce'] . '</p>';
        echo '<p><h3>Arrival Time:</h3> <i class="fas fa-hourglass-start"></i> : ' . $row['arrivalTime'] . '</p>';
        echo '<p><h3>Closing Time:</h3> <i class="fas fa-hourglass-end"></i> : ' . $row['closingTime'] . '</p>';
        echo '<a class="paybtn" href="Apply.php?ad_id=' . $row['Id'] . '"> <button class="contact-btn"><span>Apply Now</span></button></a>';
        echo '</div>';
    }
} else {
    echo "<div class = 'emptymessage'><h2 class='message'>Sorry Dear No Related Data!!</h2>
    <i class='fas fa-box-open' id='empty'></i></div>";
}

$con->close();
?>
