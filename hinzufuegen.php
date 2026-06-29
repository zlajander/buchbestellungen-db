<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['lesername'];
    $adresse = $_POST['leseradresse'];
    $buchnummer = $_POST['buchnummer'];

    $sql = "INSERT INTO bestellungen (lesername, leseradresse, buchnummer) VALUES ('$name', '$adresse', '$buchnummer')";
    mysqli_query($con, $sql);

    header("Location: index.php?msg=hinzugefuegt");
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Neue Bestellung</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Neue Buchbestellung</h1>
<form method="post" action="hinzufuegen.php">
    <label>Name des Lesers:</label>
    <input type="text" name="lesername" required>

    <label>Adresse des Lesers:</label>
    <input type="text" name="leseradresse" required>

    <label>Buch-Nummer (8-stellig):</label>
    <input type="text" name="buchnummer" maxlength="8" required>

    <input type="submit" value="Hinzufügen">
</form>
<a href="index.php">Zurück zur Übersicht</a>
</body>
</html>