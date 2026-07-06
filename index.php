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
        <th>Bestellnummer</th>
        <th>Name</th>
        <th>Adresse</th>
        <th>ISBN</th>
        <th>Titel</th>
        <th>Erstellt am</th>
        <th>Aktion</th>
    </tr>
    <?php foreach ($daten['bestellungen'] as $bestellung) echo zeileHtml($bestellung); ?>
</table>

<div id="pagination"></div>

<div id="anzahl-info"><?php echo $daten['total']; ?> Datensätze</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    var debounceTimer;
    var aktuelle_seite = 1;
    var seiten_gesamt = <?php echo $seiten_gesamt; ?>;
    var aktueller_suchbegriff = "";

    function ladeDaten(seite, suche) {
        $.ajax({
            url: "suche.php",
            method: "GET",
            data: { suche: suche, seite: seite },
            dataType: "json",
            success: function(response) {
                $("#bestellungen").html(
                    "<tr><th>Bestellnummer</th><th>Name</th><th>Adresse</th><th>ISBN</th><th>Titel</th><th>Erstellt am</th><th>Aktion</th></tr>" + response.zeilen
                );
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

    function erstellePagination(aktSeite, gesamt) {
        var html = "";

        if (aktSeite > 1) {
            html += "<button class='page-btn' data-seite='" + (aktSeite - 1) + "'>←</button>";
        } else {
            html += "<button disabled>←</button>";
        }

        var rand = 2; 
        var letzteGezeigt = 0;
        for (var i = 1; i <= gesamt; i++) {
            if (i === 1 || i === gesamt || (i >= aktSeite - rand && i <= aktSeite + rand)) {
                if (letzteGezeigt !== 0 && i - letzteGezeigt > 1) {
                    html += "<span class='page-dots'>…</span>";
                }
                if (i === aktSeite) {
                    html += "<button class='page-btn aktiv' data-seite='" + i + "'>" + i + "</button>";
                } else {
                    html += "<button class='page-btn' data-seite='" + i + "'>" + i + "</button>";
                }
                letzteGezeigt = i;
            }
        }

        if (aktSeite < gesamt) {
            html += "<button class='page-btn' data-seite='" + (aktSeite + 1) + "'>→</button>";
        } else {
            html += "<button disabled>→</button>";
        }

        $("#pagination").html(html);
    }

    $(document).ready(function() {

        erstellePagination(aktuelle_seite, seiten_gesamt);

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

    document.addEventListener('DOMContentLoaded', function() {
        const modalFenster = document.getElementById('orderModal');
        const oeffnenButton = document.getElementById('openModalBtn');
        const schliessenButton = document.getElementById('closeModalBtn');

        function schliesseModal() {
            modalFenster.classList.remove('show');
            document.getElementById('modal-fehler').style.display = 'none';
        }

        oeffnenButton.addEventListener('click', function() {
            modalFenster.classList.add('show');
        });

        schliessenButton.addEventListener('click', schliesseModal);

        modalFenster.addEventListener('click', function(event) {
            if (event.target === modalFenster) {
                schliesseModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                schliesseModal();
            }
        });
    });
</script>
</body>
</html>