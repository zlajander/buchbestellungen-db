<?php
require "config.php";
require "funktionen.php";

$seite = 1;
$daten = holeBuecherSeite($con, "", $seite);
$seiten_gesamt = (int)ceil($daten['total'] / PRO_SEITE);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Bücher</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="menu">
    <a href="index.php">Bestellungen</a>
    <a href="buecher.php" class="aktiv">Bücher</a>
</div>

<h1>Alle Bücher</h1>

<input type="text" id="suche" placeholder="Suche...">

<button type="button" id="openBuchModalBtn">Neues Buch hinzufügen</button>

<div id="buchModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="buchModalTitle">
    <div class="modal-content">
        <button type="button" class="modal-close" id="closeBuchModalBtn" aria-label="Schließen">&times;</button>
        <h2 id="buchModalTitle">Neues Buch</h2>
        <p id="modal-fehler" style="color: red; display: none;"></p>
        <form id="buch-form">
            <label for="titel">Titel:</label>
            <input type="text" id="titel" name="titel" required>

            <label for="autor">Autor:</label>
            <input type="text" id="autor" name="autor" required>

            <label for="verlag">Verlag:</label>
            <input type="text" id="verlag" name="verlag" required>

            <label for="veroeffentlichungsdatum">Veröffentlichungsdatum:</label>
            <input type="date" id="veroeffentlichungsdatum" name="veroeffentlichungsdatum" required>

            <label for="isbn">ISBN (10 oder 13):</label>
            <input type="text" id="isbn" name="isbn" required>

            <input type="submit" value="Hinzufügen">
        </form>
    </div>
</div>

<table id="buecher">
    <tr>
        <th>ISBN</th>
        <th>Titel</th>
        <th>Autor</th>
        <th>Verlag</th>
        <th>Veröffentlichungsdatum</th>
        <th>Aktion</th>
    </tr>
    <?php foreach ($daten['buecher'] as $buch) echo buchZeileHtml($buch); ?>
</table>

<div id="pagination"></div>

<div id="anzahl-info"><?php echo $daten['total']; ?> Bücher</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="app.js"></script>
<script type="text/javascript">
    var debounceTimer;
    var aktuelle_seite = 1;
    var seiten_gesamt = <?php echo $seiten_gesamt; ?>;
    var aktueller_suchbegriff = "";

    function ladeBuecher(seite, suche) {
        $.ajax({
            url: "buch_liste.php",
            method: "GET",
            data: { suche: suche, seite: seite },
            dataType: "json",
            success: function(response) {
                $("#buecher").html(
                    "<tr><th>ISBN</th><th>Titel</th><th>Autor</th><th>Verlag</th><th>Veröffentlichungsdatum</th><th>Aktion</th></tr>" + response.zeilen
                );
                aktuelle_seite = response.aktuelle_seite;
                seiten_gesamt = response.seiten_gesamt;
                erstellePagination(aktuelle_seite, seiten_gesamt);
                if (suche === "") {
                    $("#anzahl-info").text(response.total + " Bücher");
                } else {
                    $("#anzahl-info").text(response.total + " Treffer für \"" + suche + "\"");
                }
                $("html, body").animate({ scrollTop: 0 }, 200);
            }
        });
    }

    $(document).ready(function() {

        erstellePagination(aktuelle_seite, seiten_gesamt);
        initModal('buchModal', 'openBuchModalBtn', 'closeBuchModalBtn');

        $("#suche").keyup(function(){
            var input = $(this).val();
            aktueller_suchbegriff = input;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                ladeBuecher(1, aktueller_suchbegriff);
            }, 300);
        });

        $(document).on("click", ".page-btn", function() {
            ladeBuecher($(this).data("seite"), aktueller_suchbegriff);
        });

        $(document).on("click", ".buch-loeschen-btn", function() {
            var id = $(this).data("id");
            var titel = $(this).data("titel");
            var anzahl = $(this).data("anzahl");

            var text = "„" + titel + "\" löschen?";
            if (anzahl > 0) {
                text += "\nEs werden auch " + anzahl + " Bestellung(en) mitgelöscht.";
            }

            if (confirm(text)) {
                var form = $("<form>", { method: "post", action: "buch_loeschen.php" });
                form.append($("<input>", { type: "hidden", name: "id", value: id }));
                form.appendTo("body").submit();
            }
        });

        $("#buch-form").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "buch_hinzufuegen.php",
                method: "POST",
                data: {
                    titel: $("#titel").val(),
                    autor: $("#autor").val(),
                    verlag: $("#verlag").val(),
                    veroeffentlichungsdatum: $("#veroeffentlichungsdatum").val(),
                    isbn: $("#isbn").val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == "ok") {
                        $("#buchModal").removeClass("show");
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
