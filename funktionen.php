<?php

const PRO_SEITE = 50;

function holeBestellungen($con, $suchbegriff, $seite) {
    $offset = ($seite - 1) * PRO_SEITE;
    $pro_seite = PRO_SEITE;

    if ($suchbegriff !== "") {
        $suche_like = "%" . $suchbegriff . "%";

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

    $bestellungen = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bestellungen[] = $row;
    }

    return [
        "bestellungen" => $bestellungen,
        "total" => (int)$total
    ];
}

function zeileHtml($bestellung) {
    $html  = "<tr>";
    $html .= "<td>" . htmlspecialchars($bestellung['bestellnummer'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars($bestellung['lesername'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars($bestellung['leseradresse'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars($bestellung['buchnummer'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars($bestellung['erstellt_am'], ENT_QUOTES) . "</td>";
    $html .= "<td><form action='loeschen.php' method='post' class='loeschen-form'>";
    $html .= "<input type='hidden' name='id' value='" . htmlspecialchars($bestellung['bestellnummer'], ENT_QUOTES) . "'>";
    $html .= "<button type='submit' class='loeschen-btn'>Löschen</button>";
    $html .= "</form></td>";
    $html .= "</tr>";
    return $html;
}
