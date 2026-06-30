<?php
require "config.php";

$suche = "";
if (isset($_GET['suche'])) {
    $suche = $_GET['suche'];
}

if ($suche != "") {
    $sql = "SELECT * FROM bestellungen WHERE lesername LIKE '%$suche%' OR buchnummer LIKE '%$suche%'";
} else {
    $sql = "SELECT * FROM bestellungen";
}

$result = mysqli_query($con, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['bestellnummer'] . "</td>";
    echo "<td>" . $row['lesername'] . "</td>";
    echo "<td>" . $row['leseradresse'] . "</td>";
    echo "<td>" . $row['buchnummer'] . "</td>";
    echo "<td>" . $row['erstellt_am'] . "</td>";
    echo "<td><a href='loeschen.php?id=" . $row['bestellnummer'] . "'>Löschen</a></td>";
    echo "</tr>";
}
?>