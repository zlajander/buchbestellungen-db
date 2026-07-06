<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['lesername'];
    $adresse = $_POST['leseradresse'];
    $buch_id = $_POST['buch_id'];

    if (empty($name) || empty($adresse) || empty($buch_id)) {
        echo json_encode(["status" => "fehler", "meldung" => "Alle Felder müssen ausgefüllt sein."]);
        exit;
    }

    $stmt_pruef = mysqli_prepare($con, "SELECT buch_id FROM buecher WHERE buch_id = ?");
    mysqli_stmt_bind_param($stmt_pruef, "i", $buch_id);
    mysqli_stmt_execute($stmt_pruef);
    $result_pruef = mysqli_stmt_get_result($stmt_pruef);

    if (mysqli_num_rows($result_pruef) === 0) {
        echo json_encode(["status" => "fehler", "meldung" => "Das ausgewählte Buch existiert nicht."]);
        exit;
    }

    $stmt = mysqli_prepare($con, "INSERT INTO bestellungen (lesername, leseradresse, buch_id) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssi", $name, $adresse, $buch_id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "ok"]);
    } else {
        echo json_encode(["status" => "fehler", "meldung" => "Bestellung konnte nicht gespeichert werden."]);
    }
    exit;
}
?>
