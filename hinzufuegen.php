<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['lesername'];
    $adresse = $_POST['leseradresse'];
    $buchnummer = $_POST['buchnummer'];

    if (empty($name) || empty($adresse) || empty($buchnummer)) {
        echo json_encode(["status" => "fehler", "meldung" => "Alle Felder müssen ausgefüllt sein."]);
        exit;
    }

    if (!preg_match('/^\d{8}$/', $buchnummer)) {
        echo json_encode(["status" => "fehler", "meldung" => "Buchnummer muss genau 8 Ziffern sein."]);
        exit;
    }

    $stmt = mysqli_prepare($con, "INSERT INTO bestellungen (lesername, leseradresse, buchnummer) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $name, $adresse, $buchnummer);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "ok"]);
    } else {
        echo json_encode(["status" => "fehler", "meldung" => "Datenbankfehler: " . mysqli_error($con)]);
    }
    exit;
}
?>