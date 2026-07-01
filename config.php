<?php
$con = mysqli_connect("localhost", "root", "", "buchbestellungen");

if(!$con) {
    die("Error!:".mysqli_connect_error());
}
mysqli_set_charset($con, "utf8mb4");
?>