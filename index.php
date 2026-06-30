<?php
require "config.php";
$suche = "";
if (isset($_GET['suche'])) {
    $suche = $_GET['suche'];
}

if ($suche != "") {
    $sql = "SELECT * FROM bestellungen WHERE lesername LIKE '%$suche%' OR buchnummer LIKE '%$suche%'";
} else {
    $sql = "SELECT * FROM bestellungen";
}
$result = mysqli_query($con, $sql);
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
    <input type="text" name="suche" placeholder="Suche..." value="<?php echo $suche; ?>">
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

<a href="hinzufuegen.php">Neue Bestellung hinzufügen</a>
<table>
    <tr>
        <th>Bestellnummer</th>
        <th>Name</th>
        <th>Adresse</th>
        <th>Buchnummer</th>
        <th>Erstellt am</th>
        <th>Aktion</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo $row['bestellnummer']; ?></td>
        <td><?php echo $row['lesername']; ?></td>
        <td><?php echo $row['leseradresse']; ?></td>
        <td><?php echo $row['buchnummer']; ?></td>
        <td><?php echo $row['erstellt_am']; ?></td>
        <td>
            <a href="loeschen.php?id=<?php echo $row['bestellnummer']; ?>">Löschen</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>