<?php
require "config.php";
require "funktionen.php";

$seite = 1;
$daten = holeBestellungen($con, "", $seite);
$seiten_gesamt = (int)ceil($daten['total'] / PRO_SEITE);
$buecher = holeBuecher($con);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Buchbestellungen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="menu">
    <a href="index.php" class="aktiv">Bestellungen</a>
    <a href="buecher.php">Bücher</a>
</div>

<h1>Alle Buchbestellungen</h1>

<input type="text" id="suche" placeholder="Suche...">

<?php if (isset($_GET['msg'])): ?>
    <p style="color: green;">
        <?php
        if ($_GET['msg'] == "hinzugefuegt") echo "Bestellung wurde hinzugefügt!";
        if ($_GET['msg'] == "geloescht") echo "Bestellung wurde gelöscht!";
        ?>
    </p>
<?php endif; ?>

<button type="button" id="openModalBtn">Neue Bestellung hinzufügen</button>

<div id="orderModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-content">
        <button type="button" class="modal-close" id="closeModalBtn" aria-label="Schließen">&times;</button>
        <h2 id="modalTitle">Neue Buchbestellung</h2>
        <p id="modal-fehler" style="color: red; display: none;"></p>
        <form id="modal-form">
            <label for="lesername">Name des Lesers:</label>
            <input type="text" id="lesername" name="lesername" required>

            <label for="leseradresse">Adresse des Lesers:</label>
            <input type="text" id="leseradresse" name="leseradresse" required>

            <label for="buch_id">Buch:</label>
            <select id="buch_id" name="buch_id" required>
                <option value="">-- Buch auswählen --</option>
                <?php foreach ($buecher as $buch): ?>
                    <option value="<?php echo htmlspecialchars($buch['buch_id'], ENT_QUOTES); ?>">
                        <?php echo htmlspecialchars($buch['titel'] . " (" . $buch['isbn'] . ")", ENT_QUOTES); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Hinzufügen">
        </form>
    </div>
</div>

<table id="bestellungen">
    <tr>
        <th class="sortierbar" data-spalte="b.bestellnummer">Bestellnummer <span class="sortier-indikator"></span></th>
        <th class="sortierbar" data-spalte="b.lesername">Name <span class="sortier-indikator"></span></th>
        <th class="sortierbar" data-spalte="b.leseradresse">Adresse <span class="sortier-indikator"></span></th>
        <th class="sortierbar" data-spalte="bu.isbn">ISBN <span class="sortier-indikator"></span></th>
        <th class="sortierbar" data-spalte="bu.titel">Titel <span class="sortier-indikator"></span></th>
        <th class="sortierbar" data-spalte="b.erstellt_am">Erstellt am <span class="sortier-indikator"></span></th>
        <th>Aktion</th>
    </tr>
    <?php foreach ($daten['bestellungen'] as $bestellung) echo zeileHtml($bestellung); ?>
</table>

<div id="pagination"></div>

<div id="anzahl-info"><?php echo $daten['total']; ?> Datensätze</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="app.js"></script>
<script type="text/javascript">
    var debounceTimer;
    var aktuelle_seite = 1;
    var seiten_gesamt = <?php echo $seiten_gesamt; ?>;
    var aktueller_suchbegriff = "";
    var sortiere_nach = "b.bestellnummer";
    var richtung = "ASC";

    function ladeDaten(seite, suche) {
        $.ajax({
            url: "suche.php",
            method: "GET",
            data: { suche: suche, seite: seite, sortiere_nach: sortiere_nach, richtung: richtung },
            dataType: "json",
            success: function(response) {
                $("#bestellungen").html(
                    "<tr><th class='sortierbar' data-spalte='b.bestellnummer'>Bestellnummer <span class='sortier-indikator'></span></th>" +
                    "<th class='sortierbar' data-spalte='b.lesername'>Name <span class='sortier-indikator'></span></th>" +
                    "<th class='sortierbar' data-spalte='b.leseradresse'>Adresse <span class='sortier-indikator'></span></th>" +
                    "<th class='sortierbar' data-spalte='bu.isbn'>ISBN <span class='sortier-indikator'></span></th>" +
                    "<th class='sortierbar' data-spalte='bu.titel'>Titel <span class='sortier-indikator'></span></th>" +
                    "<th class='sortierbar' data-spalte='b.erstellt_am'>Erstellt am <span class='sortier-indikator'></span></th>" +
                    "<th>Aktion</th></tr>" + response.zeilen
                );
                aktualisiereSortierIndikatoren();
                aktuelle_seite = response.aktuelle_seite;
                seiten_gesamt = response.seiten_gesamt;
                erstellePagination(aktuelle_seite, seiten_gesamt);
                if (suche === "") {
                    $("#anzahl-info").text(response.total + " Datensätze");
                } else {
                    $("#anzahl-info").text(response.total + " Treffer für \"" + suche + "\"");
                }
                $("html, body").animate({ scrollTop: 0 }, 200);
            }
        });
    }

    function aktualisiereSortierIndikatoren() {
        $("#bestellungen th.sortierbar").each(function() {
            $(this).removeClass("sortiert-asc sortiert-desc");
            if ($(this).data("spalte") === sortiere_nach) {
                if (richtung === "ASC") {
                    $(this).addClass("sortiert-asc");
                    $(this).find(".sortier-indikator").text(" ↑");
                } else {
                    $(this).addClass("sortiert-desc");
                    $(this).find(".sortier-indikator").text(" ↓");
                }
            } else {
                $(this).find(".sortier-indikator").text("");
            }
        });
    }

    $(document).ready(function() {

        erstellePagination(aktuelle_seite, seiten_gesamt);
        aktualisiereSortierIndikatoren();
        initModal('orderModal', 'openModalBtn', 'closeModalBtn');

        $("#buch_id").select2({
            dropdownParent: $("#orderModal"),
            placeholder: "Buch suchen (Titel oder ISBN)",
            width: "100%"
        });

        $("#suche").keyup(function(){
            var input = $(this).val();
            aktueller_suchbegriff = input;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                ladeDaten(1, aktueller_suchbegriff);
            }, 300);
        });

        $(document).on("click", "#bestellungen th.sortierbar", function() {
            var spalte = $(this).data("spalte");
            if (sortiere_nach === spalte) {
                richtung = (richtung === "ASC") ? "DESC" : "ASC";
            } else {
                sortiere_nach = spalte;
                richtung = "ASC";
            }
            ladeDaten(1, aktueller_suchbegriff);
        });

        $(document).on("click", ".page-btn", function() {
            ladeDaten($(this).data("seite"), aktueller_suchbegriff);
        });

        $("#modal-form").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "hinzufuegen.php",
                method: "POST",
                data: {
                    lesername: $("#lesername").val(),
                    leseradresse: $("#leseradresse").val(),
                    buch_id: $("#buch_id").val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == "ok") {
                        $("#orderModal").removeClass("show");
                        location.reload();
                    } else {
                        $("#modal-fehler").text(response.meldung).show();
                    }
                },
                error: function() {
                    $("#modal-fehler").text("Unbekannter Fehler.").show();
                }
            });
        });

    });
</script>
</body>
</html>