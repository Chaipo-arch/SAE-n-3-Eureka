<?php
session_start();
// test si on est bien passé par la page de login sinon on retourne sur index.php
if (!isset($_SESSION['connecte'])) {
	//Pas de session en cours, on est pas passé par le login password
	header('Location: ../index.php');
	exit();
}
if ($_SESSION['role'] != "Admin") {
	//On est déja connecté (ouverture dans une autre page par exemple, on renvoie vers la liste des comptes
	header('Location: forum.php');
	exit();
}
// Intégration des fonctions qui seront utilisées pour les acces à la BD
require('../fonctions/gestionBD.php');
require('../fonctions/generation.php');

// Connexion à la BD
if (!connecteBD($erreur)) {
	// Pas de connexion à la BD, renvoie vers l'index
	header('Location: ../index.php');
	exit();
} 
$pdo = getPDO();
if(isset($_POST['action'])&& $_POST['action'] == "generation") {
	$duree = $_POST['duree'];
	$type = $_POST['type'];
	generationPlanning($pdo,$duree,$type);

}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Eureka - Votre planning</title>

		<!-- Bootstrap CSS -->
		<link href="../bootstrap-4.6.2-dist/css/bootstrap.css" rel="stylesheet">

		<!-- Lien vers mon CSS -->
		<link href="../css/monStyle.css" rel="stylesheet">
		<link href="../css/HeaderCss.css" rel="stylesheet">
		<link href="../css/EntrepriseCss.css" rel="stylesheet">
        <link href="../css/HeaderCss.css" rel="stylesheet">

		<!-- Lien vers CSS fontawesome -->
		<link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet"> <!--load all styles -->
	</head>

	<body>
    <?php 
    include("../fonctions/viewHelper.php");
    headerHelper();

    ?>
	</br>
	<div class="container separation">
            <form action="planning.php" method="post">
				<input type="hidden" name="duree"  value="00:00:00">
				<input type="hidden" name="type"  value="1">
				<input type="hidden" name="action"  value="generation">
                <input type="submit"  value="Modifier Caractéristiques forum">
            </form>
        </div>
        <?php footerHelper(); ?>
  </body>
</html>