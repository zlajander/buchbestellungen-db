<?php
require "config.php";

$suche = "";
if (isset($_GET['suche'])) {
    $suche = $_GET['suche'];
}

if ($suche != "") {
    $suche_like = "%" . $suche . "%";
    $stmt = mysqli_prepare($con, "SELECT * FROM bestellungen WHERE lesername LIKE ? OR buchnummer LIKE ?");
    mysqli_stmt_bind_param($stmt, "ss", $suche_like, $suche_like);
} else {
    $stmt = mysqli_prepare($con, "SELECT * FROM bestellungen");
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

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