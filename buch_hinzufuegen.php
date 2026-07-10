<?php
require "config.php";
require "funktionen.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titel = $_POST['titel'] ?? '';
    $autor = $_POST['autor'] ?? '';
    $verlag = $_POST['verlag'] ?? '';
    $datum = $_POST['veroeffentlichungsdatum'] ?? '';
    $isbn = $_POST['isbn'] ?? '';

    if (empty($titel) || empty($autor) || empty($verlag) || empty($datum) || empty($isbn)) {
        echo json_encode(["status" => "fehler", "meldung" => "Alle Felder müssen ausgefüllt sein."]);
        exit;
    }

    $pruef_datum = DateTime::createFromFormat('Y-m-d', $datum);
    if (!$pruef_datum || $pruef_datum->format('Y-m-d') !== $datum) {
        echo json_encode(["status" => "fehler", "meldung" => "Ungültiges Datum."]);
        exit;
    }

    if (!istGueltigeIsbn($isbn)) {
        echo json_encode(["status" => "fehler", "meldung" => "Ungültige ISBN."]);
        exit;
    }

    $isbn = bereinigeIsbn($isbn);

    $stmt = mysqli_prepare($con, "INSERT INTO buecher (isbn, titel, autor, verlag, veroeffentlichungsdatum) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $isbn, $titel, $autor, $verlag, $datum);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "ok"]);
    } elseif (mysqli_errno($con) === 1062) {
        echo json_encode(["status" => "fehler", "meldung" => "Diese ISBN existiert bereits."]);
    } else {
        echo json_encode(["status" => "fehler", "meldung" => "Buch konnte nicht gespeichert werden."]);
    }
    exit;
}
?>
