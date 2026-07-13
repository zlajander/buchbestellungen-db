<?php
require "config.php";
require "funktionen.php";

$suchbegriff = isset($_GET['suche']) ? $_GET['suche'] : "";
$seite = isset($_GET['seite']) ? (int)$_GET['seite'] : 1;
$sortiere_nach = isset($_GET['sortiere_nach']) ? $_GET['sortiere_nach'] : "b.bestellnummer";
$richtung = isset($_GET['richtung']) ? $_GET['richtung'] : "ASC";

$daten = holeBestellungen($con, $suchbegriff, $seite, $sortiere_nach, $richtung);

$zeilen = "";
foreach ($daten['bestellungen'] as $bestellung) {
    $zeilen .= zeileHtml($bestellung);
}

echo json_encode([
    "zeilen" => $zeilen,
    "total" => $daten['total'],
    "seiten_gesamt" => (int)ceil($daten['total'] / PRO_SEITE),
    "aktuelle_seite" => $seite
]);
?>