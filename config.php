<?php
$con = mysqli_connect("localhost", "root", "", "buchbestellungen");

if(!$con) {
    die("Error!:".mysqli_connect_error());
}
?>