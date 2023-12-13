<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mezabi - All categories</title>
    <link rel="stylesheet" href="/mezabi/static/css/mezabi.css">
</head>
<body>


<h1>Mezabi</h1>

<h2>Catégories</h2>

<table>
    <tr>
        <th>Code</th>
        <th>Désignation</th>
        <th></th>
        <th></th>
    </tr>
    <?php while ($row = $searchStmt->fetch()) {
        $code = $row['code_categorie'];
        $designation = $row['designation'];
        ?>
        <tr>
            <td><?php echo $code ?></td>
            <td><?php echo $designation ?></td>
            <td><a href="/mezabi?controller=Articles&code_categorie=<?php echo $code ?>&categorie=<?php echo $designation ?>">Voir les articles</a></td>
            <td><a href="/mezabi/edit-categorie.php?code_categorie=<?php echo $code ?>&categorie=<?php echo $designation ?>">Modifier la catégorie</a></td>
        </tr>
    <?php } ?>
</table>



</body>
</html>