<?php

const PRO_SEITE = 50;

function holeBestellungen($con, $suchbegriff, $seite, $sortiere_nach = "b.bestellnummer", $richtung = "ASC"): array {
    $offset = ($seite - 1) * PRO_SEITE;
    $pro_seite = PRO_SEITE;

    $erlaubte_spalten = ["b.bestellnummer", "b.lesername", "b.leseradresse", "bu.isbn", "bu.titel", "b.erstellt_am"];
    if (!in_array($sortiere_nach, $erlaubte_spalten)) {
        $sortiere_nach = "b.bestellnummer";
    }

    $richtung = strtoupper($richtung);
    if ($richtung !== "ASC" && $richtung !== "DESC") {
        $richtung = "ASC";
    }

    $spalten = "b.bestellnummer, b.lesername, b.leseradresse, bu.isbn, bu.titel, b.erstellt_am";
    $von = "FROM bestellungen b JOIN buecher bu ON b.buch_id = bu.buch_id";
    $order = "ORDER BY $sortiere_nach $richtung";

    if ($suchbegriff !== "") {
        $suche_like = "%" . $suchbegriff . "%";
        $wo = "WHERE b.lesername LIKE ? OR b.leseradresse LIKE ? OR bu.isbn LIKE ? OR bu.titel LIKE ? OR bu.autor LIKE ? OR bu.verlag LIKE ?";

        $stmt_total = mysqli_prepare($con, "SELECT COUNT(*) as total $von $wo");
        mysqli_stmt_bind_param($stmt_total, "ssssss", $suche_like, $suche_like, $suche_like, $suche_like, $suche_like, $suche_like);
        mysqli_stmt_execute($stmt_total);
        $result_total = mysqli_stmt_get_result($stmt_total);
        $total = mysqli_fetch_assoc($result_total)['total'];

        $stmt = mysqli_prepare($con, "SELECT $spalten $von $wo $order LIMIT ? OFFSET ?");
        mysqli_stmt_bind_param($stmt, "ssssssii", $suche_like, $suche_like, $suche_like, $suche_like, $suche_like, $suche_like, $pro_seite, $offset);
    } else {
        $stmt_total = mysqli_prepare($con, "SELECT COUNT(*) as total $von");
        mysqli_stmt_execute($stmt_total);
        $result_total = mysqli_stmt_get_result($stmt_total);
        $total = mysqli_fetch_assoc($result_total)['total'];

        $stmt = mysqli_prepare($con, "SELECT $spalten $von $order LIMIT ? OFFSET ?");
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

function holeBuecher($con): array {
    $result = mysqli_query($con, "SELECT buch_id, isbn, titel FROM buecher ORDER BY titel");
    $buecher = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $buecher[] = $row;
    }
    return $buecher;
}

function holeBuecherSeite($con, $suchbegriff, $seite, $sortiere_nach = "titel", $richtung = "ASC"): array {
    $offset = ($seite - 1) * PRO_SEITE;
    $pro_seite = PRO_SEITE;

    $erlaubte_spalten = ["isbn", "titel", "autor", "verlag", "veroeffentlichungsdatum"];
    if (!in_array($sortiere_nach, $erlaubte_spalten)) {
        $sortiere_nach = "titel";
    }

    $richtung = strtoupper($richtung);
    if ($richtung !== "ASC" && $richtung !== "DESC") {
        $richtung = "ASC";
    }

    $spalten = "buch_id, isbn, titel, autor, verlag, veroeffentlichungsdatum, (SELECT COUNT(*) FROM bestellungen WHERE bestellungen.buch_id = buecher.buch_id) AS anzahl_bestellungen";
    $order = "ORDER BY $sortiere_nach $richtung";

    if ($suchbegriff !== "") {
        $suche_like = "%" . $suchbegriff . "%";
        $wo = "WHERE isbn LIKE ? OR titel LIKE ? OR autor LIKE ? OR verlag LIKE ?";

        $stmt_total = mysqli_prepare($con, "SELECT COUNT(*) as total FROM buecher $wo");
        mysqli_stmt_bind_param($stmt_total, "ssss", $suche_like, $suche_like, $suche_like, $suche_like);
        mysqli_stmt_execute($stmt_total);
        $result_total = mysqli_stmt_get_result($stmt_total);
        $total = mysqli_fetch_assoc($result_total)['total'];

        $stmt = mysqli_prepare($con, "SELECT $spalten FROM buecher $wo $order LIMIT ? OFFSET ?");
        mysqli_stmt_bind_param($stmt, "ssssii", $suche_like, $suche_like, $suche_like, $suche_like, $pro_seite, $offset);
    } else {
        $stmt_total = mysqli_prepare($con, "SELECT COUNT(*) as total FROM buecher");
        mysqli_stmt_execute($stmt_total);
        $result_total = mysqli_stmt_get_result($stmt_total);
        $total = mysqli_fetch_assoc($result_total)['total'];

        $stmt = mysqli_prepare($con, "SELECT $spalten FROM buecher $order LIMIT ? OFFSET ?");
        mysqli_stmt_bind_param($stmt, "ii", $pro_seite, $offset);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $buecher = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $buecher[] = $row;
    }

    return [
        "buecher" => $buecher,
        "total" => (int)$total
    ];
}

function buchZeileHtml($buch): string {
    $html  = "<tr>";
    $html .= "<td>" . htmlspecialchars($buch['isbn'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars($buch['titel'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars($buch['autor'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars($buch['verlag'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars(date('d.m.Y', strtotime($buch['veroeffentlichungsdatum'])), ENT_QUOTES) . "</td>";
    $html .= "<td><button type='button' class='loeschen-btn buch-loeschen-btn'";
    $html .= " data-id='" . htmlspecialchars($buch['buch_id'], ENT_QUOTES) . "'";
    $html .= " data-titel='" . htmlspecialchars($buch['titel'], ENT_QUOTES) . "'";
    $html .= " data-anzahl='" . htmlspecialchars($buch['anzahl_bestellungen'], ENT_QUOTES) . "'>Löschen</button></td>";
    $html .= "</tr>";
    return $html;
}

function bereinigeIsbn($isbn): string {
    return strtoupper(preg_replace('/[\s-]/', '', $isbn));
}

function istGueltigeIsbn($isbn): bool {
    $isbn = bereinigeIsbn($isbn);
    if (preg_match('/^\d{13}$/', $isbn)) {
        return istGueltigeIsbn13($isbn);
    }
    if (preg_match('/^\d{9}[\dX]$/', $isbn)) {
        return istGueltigeIsbn10($isbn);
    }
    return false;
}

function istGueltigeIsbn13($isbn): bool {
    $summe = 0;
    for ($i = 0; $i < 13; $i++) {
        $summe += (int)$isbn[$i] * ($i % 2 === 0 ? 1 : 3);
    }
    return $summe % 10 === 0;
}

function istGueltigeIsbn10($isbn): bool {
    $summe = 0;
    for ($i = 0; $i < 10; $i++) {
        $ziffer = ($isbn[$i] === 'X') ? 10 : (int)$isbn[$i];
        $summe += $ziffer * (10 - $i);
    }
    return $summe % 11 === 0;
}

function zeileHtml($bestellung): string {
    $html  = "<tr>";
    $html .= "<td>" . htmlspecialchars($bestellung['bestellnummer'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars($bestellung['lesername'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars($bestellung['leseradresse'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars($bestellung['isbn'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars($bestellung['titel'], ENT_QUOTES) . "</td>";
    $html .= "<td>" . htmlspecialchars(date('d.m.Y H:i', strtotime($bestellung['erstellt_am'])), ENT_QUOTES) . "</td>";
    $html .= "<td><form action='loeschen.php' method='post' class='loeschen-form'>";
    $html .= "<input type='hidden' name='id' value='" . htmlspecialchars($bestellung['bestellnummer'], ENT_QUOTES) . "'>";
    $html .= "<button type='submit' class='loeschen-btn'>Löschen</button>";
    $html .= "</form></td>";
    $html .= "</tr>";
    return $html;
}
