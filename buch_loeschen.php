<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: buecher.php");
    exit;
}

$id = $_POST['id'];

mysqli_begin_transaction($con);

$stmt_bestellungen = mysqli_prepare($con, "DELETE FROM bestellungen WHERE buch_id = ?");
mysqli_stmt_bind_param($stmt_bestellungen, "i", $id);
$ok_bestellungen = mysqli_stmt_execute($stmt_bestellungen);

$stmt_buch = mysqli_prepare($con, "DELETE FROM buecher WHERE buch_id = ?");
mysqli_stmt_bind_param($stmt_buch, "i", $id);
$ok_buch = mysqli_stmt_execute($stmt_buch);

if ($ok_bestellungen && $ok_buch) {
    mysqli_commit($con);
} else {
    mysqli_rollback($con);
}

header("Location: buecher.php");
exit;
?>
