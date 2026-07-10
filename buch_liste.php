<?php
require "config.php";
require "funktionen.php";

$suchbegriff = isset($_GET['suche']) ? $_GET['suche'] : "";
$seite = isset($_GET['seite']) ? (int)$_GET['seite'] : 1;

$daten = holeBuecherSeite($con, $suchbegriff, $seite);

$zeilen = "";
foreach ($daten['buecher'] as $buch) {
    $zeilen .= buchZeileHtml($buch);
}

echo json_encode([
    "zeilen" => $zeilen,
    "total" => $daten['total'],
    "seiten_gesamt" => (int)ceil($daten['total'] / PRO_SEITE),
    "aktuelle_seite" => $seite
]);
?>
