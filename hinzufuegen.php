<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['lesername'];
    $adresse = $_POST['leseradresse'];
    $buchnummer = $_POST['buchnummer'];

    $stmt = mysqli_prepare($con, "INSERT INTO bestellungen (lesername, leseradresse, buchnummer) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $name, $adresse, $buchnummer);
    mysqli_stmt_execute($stmt);

    header("Location: index.php?msg=hinzugefuegt");
    exit;
}
?>