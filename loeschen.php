<?php
require "config.php";

$id = $_GET['id'];

$sql = "DELETE FROM bestellungen WHERE bestellnummer = '$id'";
mysqli_query($con, $sql);

header("Location: index.php");
exit;
?>