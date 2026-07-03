<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = $_POST['id'];

$stmt = mysqli_prepare($con, "DELETE FROM bestellungen WHERE bestellnummer = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?msg=geloescht");
} else {
    header("Location: index.php?msg=fehler");
}
exit;
?>