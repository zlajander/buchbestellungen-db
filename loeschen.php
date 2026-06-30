<?php
require "config.php";

$id = $_GET['id'];

$stmt = mysqli_prepare($con, "DELETE FROM bestellungen WHERE bestellnummer = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: index.php?msg=geloescht");
exit;
?>