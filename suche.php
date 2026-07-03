<?php
require "config.php";

$suche = "";
if (isset($_GET['suche'])) {
    $suche = $_GET['suche'];
}

$seite = isset($_GET['seite']) ? (int)$_GET['seite'] : 1;
$pro_seite = 50;
$offset = ($seite - 1) * $pro_seite;

if ($suche != "") {
    $suche_like = "%" . $suche . "%";

    $stmt_total = mysqli_prepare($con, "SELECT COUNT(*) as total FROM bestellungen WHERE lesername LIKE ? OR buchnummer LIKE ?");
    mysqli_stmt_bind_param($stmt_total, "ss", $suche_like, $suche_like);
    mysqli_stmt_execute($stmt_total);
    $result_total = mysqli_stmt_get_result($stmt_total);
    $total = mysqli_fetch_assoc($result_total)['total'];

    $stmt = mysqli_prepare($con, "SELECT * FROM bestellungen WHERE lesername LIKE ? OR buchnummer LIKE ? LIMIT ? OFFSET ?");
    mysqli_stmt_bind_param($stmt, "ssii", $suche_like, $suche_like, $pro_seite, $offset);
} else {
    $stmt_total = mysqli_prepare($con, "SELECT COUNT(*) as total FROM bestellungen");
    mysqli_stmt_execute($stmt_total);
    $result_total = mysqli_stmt_get_result($stmt_total);
    $total = mysqli_fetch_assoc($result_total)['total'];

    $stmt = mysqli_prepare($con, "SELECT * FROM bestellungen LIMIT ? OFFSET ?");
    mysqli_stmt_bind_param($stmt, "ii", $pro_seite, $offset);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$zeilen = "";
while ($row = mysqli_fetch_assoc($result)) {
    $zeilen .= "<tr>";
    $zeilen .= "<td>" . htmlspecialchars($row['bestellnummer'], ENT_QUOTES) . "</td>";
    $zeilen .= "<td>" . htmlspecialchars($row['lesername'], ENT_QUOTES) . "</td>";
    $zeilen .= "<td>" . htmlspecialchars($row['leseradresse'], ENT_QUOTES) . "</td>";
    $zeilen .= "<td>" . htmlspecialchars($row['buchnummer'], ENT_QUOTES) . "</td>";
    $zeilen .= "<td>" . htmlspecialchars($row['erstellt_am'], ENT_QUOTES) . "</td>";
    $zeilen .= "<td><form action='loeschen.php' method='post' class='loeschen-form'>";
    $zeilen .= "<input type='hidden' name='id' value='" . htmlspecialchars($row['bestellnummer'], ENT_QUOTES) . "'>";
    $zeilen .= "<button type='submit' class='loeschen-btn'>Löschen</button>";
    $zeilen .= "</form></td>";
    $zeilen .= "</tr>";
}

echo json_encode([
    "zeilen" => $zeilen,
    "total" => (int)$total,
    "seiten_gesamt" => (int)ceil($total / $pro_seite),
    "aktuelle_seite" => $seite
]);
?>