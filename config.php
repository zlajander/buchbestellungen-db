<?php
$con = mysqli_connect("localhost", "root", "", "buchbestellungen_db");

if(!$con) {
    die("Error!:".mysqli_connect_error());
}
?>