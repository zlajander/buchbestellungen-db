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
    echo "<td>" . htmlspecialchars($row['bestellnummer'], ENT_QUOTES) . "</td>";
    echo "<td>" . htmlspecialchars($row['lesername'], ENT_QUOTES) . "</td>";
    echo "<td>" . htmlspecialchars($row['leseradresse'], ENT_QUOTES) . "</td>";
    echo "<td>" . htmlspecialchars($row['buchnummer'], ENT_QUOTES) . "</td>";
    echo "<td>" . htmlspecialchars($row['erstellt_am'], ENT_QUOTES) . "</td>";
    echo "<td><a href='loeschen.php?id=" . htmlspecialchars($row['bestellnummer'], ENT_QUOTES) . "'>Löschen</a></td>";
    echo "</tr>";
}
?>