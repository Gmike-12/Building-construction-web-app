<?php

$con = mysqli_connect("localhost", "root", "", "foreman");
if($con == false) {
    die("Connection Error". mysqli_connect_error());
}

$conn = mysqli_connect("localhost", "root", "", "constructionlgn");
if($conn == false) {
    die("Connection Error". mysqli_connect_error());
}

?>


