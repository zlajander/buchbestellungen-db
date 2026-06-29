<?php
require "config.php";

$sql = "SELECT * FROM bestellungen";
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
            <a> Löschen</a>
        </td>
    </tr>
    <?php endwhile; ?>

</table>

</body>
</html>