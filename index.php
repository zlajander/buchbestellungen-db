<?php
require "config.php";
$suchbegriff = "";
if (isset($_GET['suche'])) {
    $suchbegriff = $_GET['suche'];
}

if ($suchbegriff != "") {
    $suchbegriff_like = "%" . $suchbegriff . "%";
    $stmt = mysqli_prepare($con, "SELECT * FROM bestellungen WHERE lesername LIKE ? OR buchnummer LIKE ?");
    mysqli_stmt_bind_param($stmt, "ss", $suchbegriff_like, $suchbegriff_like);
} else {
    $stmt = mysqli_prepare($con, "SELECT * FROM bestellungen");
}
mysqli_stmt_execute($stmt);
$resultBestellungen = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Buchbestellungen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Alle Buchbestellungen</h1>
<form method="GET">
    <input type="text" id="suche" name="suche" placeholder="Suche..." value="<?php echo $suchbegriff; ?>">
    <input type="submit" value="Suchen">
</form>

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
        <form method="post" action="hinzufuegen.php">
            <label for="lesername">Name des Lesers:</label>
            <input type="text" id="lesername" name="lesername" required>

            <label for="leseradresse">Adresse des Lesers:</label>
            <input type="text" id="leseradresse" name="leseradresse" required>

            <label for="buchnummer">Buch-Nummer (8-stellig):</label>
            <input type="text" id="buchnummer" name="buchnummer" maxlength="8" required>

            <input type="submit" value="Hinzufügen">
        </form>
    </div>
</div>

<table id="bestellungen">
    <tr>
        <th>Bestellnummer</th>
        <th>Name</th>
        <th>Adresse</th>
        <th>Buchnummer</th>
        <th>Erstellt am</th>
        <th>Aktion</th>
    </tr>
    <?php while ($bestellung = mysqli_fetch_assoc($resultBestellungen)): ?>
    <tr>
        <td><?php echo htmlspecialchars($bestellung['bestellnummer'], ENT_QUOTES); ?></td>
        <td><?php echo htmlspecialchars($bestellung['lesername'], ENT_QUOTES); ?></td>
        <td><?php echo htmlspecialchars($bestellung['leseradresse'], ENT_QUOTES); ?></td>
        <td><?php echo htmlspecialchars($bestellung['buchnummer'], ENT_QUOTES); ?></td>
        <td><?php echo htmlspecialchars($bestellung['erstellt_am'], ENT_QUOTES); ?></td>
        <td>
            <a href="loeschen.php?id=<?php echo htmlspecialchars($bestellung['bestellnummer'], ENT_QUOTES); ?>">Löschen</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#suche").keyup(function(){
            var input = $(this).val();

            $.ajax({
                url: "suche.php",
                method: "GET",
                data: {suche: input},
                success: function(data){
                    $("#bestellungen").html(
                        "<tr><th>Bestellnummer</th><th>Name</th><th>Adresse</th><th>Buchnummer</th><th>Erstellt am</th><th>Aktion</th></tr>" + data
                    );
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